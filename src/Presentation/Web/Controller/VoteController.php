<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use App\Application\Command\CreateVoteCommand;
use App\Application\CommandBusInterface;
use App\Application\Exception\RetrieveRatingException;
use App\Application\Exception\SaveVoteException;
use App\Application\Query\RatingQuery;
use App\Application\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoteController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function showAction(Request $request): JsonResponse
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
                "status" => "validation_error",
                "errors" => $this->getFormErrorsAsArray($form)
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $form->getData();
        $query = new RatingQuery($data['url']);

        try {
            $rating = $this->queryBus->handle($query);
        } catch (RetrieveRatingException $exception) {
            return $this->json(["status" => "internal_error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($rating->toArray());
    }

    public function createAction(Request $request): JsonResponse
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
            ->add('fingerprint', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->getForm();

        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json([
                "errors" => "validation_error",
                "message" => $this->getFormErrorsAsArray($form)
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $form->getData();
        $command = new CreateVoteCommand($data['url'], $data['value'], $data['fingerprint']);

        try {
            $this->commandBus->handle($command);
        } catch (SaveVoteException $exception) {
            return $this->json(["status" => "internal_error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(["status" => "success"], Response::HTTP_CREATED);
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
