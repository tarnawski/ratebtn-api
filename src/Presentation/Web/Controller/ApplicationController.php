<?php declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use App\Application\Command\CreateVoteCommand;
use App\Application\Exception\RetrieveVotesException;
use App\Application\Query\RatingQuery;
use App\Application\ServiceBus\CommandBus;
use App\Application\ServiceBus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Yaml\Yaml;

class ApplicationController extends AbstractController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var QueryBus */
    private $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function statusAction(): JsonResponse
    {
        return $this->json(["status" => "operational", "version" => "1.0.0"], 200);
    }

    public function specificationAction(): JsonResponse
    {
        $rootDir = $this->getParameter('kernel.root_dir');
        $specificationPath = sprintf('%s/../doc/specification.yml', $rootDir);

        return $this->json(Yaml::parseFile($specificationPath));
    }

    public function ratingAction(Request $request): JsonResponse
    {
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('url', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255])
                ],
            ])
            ->getForm();

        $form->submit($request->query->all());

        if (!$form->isValid()) {
            return $this->json([
                "status" => "fail",
                "errors" => $this->getFormErrorsAsArray($form)
            ], 400);
        }

        $data = $form->getData();
        $query = new RatingQuery($data['url']);

        try {
            $rating = $this->queryBus->handle($query);
        } catch (RetrieveVotesException $exception) {
            return $this->json(["status" => "error"], 500);
        }

        return $this->json($rating->toArray());
    }

    public function voteAction(Request $request): JsonResponse
    {
        $form = $this->createFormBuilder()
            ->add('url', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 255])
                ],
            ])
            ->add('value', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0),
                    new LessThan(6),
                ],
            ])
            ->getForm();

        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json([
                "errors" => "fail",
                "message" => $this->getFormErrorsAsArray($form)
            ], 400);
        }

        $data = $form->getData();
        $command = new CreateVoteCommand($data['url'], $data['value']);
        $this->commandBus->handle($command);

        return $this->json(["status" => "success"], 201);
    }

    private function getFormErrorsAsArray(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            $errors[$key] = $error->getMessage();
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $key = $child->getName();
                $errors[$key] = $this->getFormErrorsAsArray($child);
            }
        }

        return $errors;
    }
}
