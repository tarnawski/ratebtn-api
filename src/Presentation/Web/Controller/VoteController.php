<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use App\Application\Command\CreateVoteCommand;
use App\Application\CommandBusInterface;
use App\Application\Exception\RetrieveRatingException;
use App\Application\Exception\SaveVoteException;
use App\Application\Query\RatingQuery;
use App\Application\QueryBusInterface;
use App\Presentation\Web\Validator\CreateVoteInputValidator;
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
    private CreateVoteInputValidator $validator;
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct(CreateVoteInputValidator $validator, CommandBusInterface $commandBus, QueryBusInterface $queryBus)
    {
        $this->validator = $validator;
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
        $data = json_decode($request->getContent(), true);
        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            return $this->json(
                ["errors" => "validation_error","message" => $errors],
                Response::HTTP_BAD_REQUEST
            );
        }

        $command = new CreateVoteCommand($data['url'], $data['value'], $data['fingerprint']);

        try {
            $this->commandBus->handle($command);
        } catch (SaveVoteException $exception) {
            return $this->json(["status" => "internal_error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(["status" => "success"], Response::HTTP_CREATED);
    }
}
