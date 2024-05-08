<?php

declare(strict_types=1);

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class JsonController extends AbstractController
{
    private const string JSON_TYPE = 'json';

    private LoggerInterface $logger;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public function getSuccessResponse($value, string $format = 'json', $groups = ['Default']): JsonResponse
    {
        return $this->json($this->serialize($value, $format, $groups));
    }

    public function getErrorResponse(Throwable $exception): JsonResponse
    {
        $text = sprintf('%s%s%s%s%s%s', 'ApiError: ', $exception->getMessage(),
            ' File: ', $exception->getFile(), ' Line: ', $exception->getLine());

        if ($exception->getCode() === Response::HTTP_NOT_FOUND) {
            $this->logger->info($text);
        } else {
            $this->logger->error($text);
        }

        return $this->json('Something went wrong, please try again later.', 500);
    }

    protected function serialize($data, string $format, array $groups): string
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->setGroups($groups);

        return $this->serializer->serialize($data, $format, $context);
    }

    protected function transformJson(Request $request): array
    {
        try {
            if ($request->getContentTypeFormat() !== self::JSON_TYPE) {
                throw new RuntimeException("This format isn't JSON");
            }

            return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
}
