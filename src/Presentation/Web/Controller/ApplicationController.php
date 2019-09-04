<?php declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use App\Application\Command\VoteCommand;
use App\Application\Exception\RetrieveVotesException;
use App\Application\Query\RatingQuery;
use App\Application\ServiceBus\CommandBus;
use App\Application\ServiceBus\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;

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

    public function ratingAction(string $identity): JsonResponse
    {
        $query = new RatingQuery($identity);

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
            ->add('hash', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 32])
                ],
            ])
            ->add('value', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0),
                    new LessThan(5),
                ],
            ])
            ->getForm();

        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json(["status" => "error"], 400);
        }

        $data = $form->getData();
        $command = new VoteCommand(Uuid::uuid4()->toString(), $data['hash'], $data['value']);
        $this->commandBus->handle($command);

        return $this->json(["status" => "created"], 201);
    }
}