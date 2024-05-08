<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Controller\JsonController;
use App\DTO\Order\OrderInputDTO;
use App\Form\Order\OrderFormType;
use App\Handler\Order\Command\CreateOrderCommandHandler;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class CreateOrderController extends JsonController
{
    private CreateOrderCommandHandler $createOrderCommandHandler;

    public function __construct(
        SerializerInterface $serializer,
        LoggerInterface $logger,
        CreateOrderCommandHandler $createOrderCommandHandler
    ) {
        parent::__construct($serializer, $logger);

        $this->createOrderCommandHandler = $createOrderCommandHandler;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $this->transformJson($request);
            $form = $this->createForm(OrderFormType::class);
            $form->submit($data);

            if ($form->isValid()) {
                $view = $this->createOrderCommandHandler->handle(OrderInputDTO::fromArray($data));

                return $this->getSuccessResponse($view);
            }

            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $exception) {
            return $this->getErrorResponse($exception);
        }
    }
}
