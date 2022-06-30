<?php

namespace App\Framework\Symfony\Extension;

use Generator;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final class RequestDTOResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return str_contains($argument->getType(), "DTO");
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                'json'
            );

            foreach ($this->validator->validate($dto) as $error) {
                throw new RuntimeException(sprintf('Invalid value for property "%s": %s',
                    mb_strtolower(
                        preg_replace(
                            "/[A-Z]/",
                            '_' . "$0",
                            $error->getPropertyPath()
                        )
                    ),
                    $error->getMessage()
                ));
            }
        } catch (Throwable $exception) {
            if ($exception instanceof NotNormalizableValueException) {
                throw new BadRequestHttpException(
                    preg_replace('/for class \".*?\"/',
                        '',
                        $exception->getMessage()
                    )
                );
            }

            if ($exception instanceof MissingConstructorArgumentsException) {
                preg_match_all('/\".*?\"/', $exception->getMessage(), $matches);
                throw new BadRequestHttpException(sprintf('Property %s is missing.',
                    mb_strtolower(
                        preg_replace(
                            "/[A-Z]/",
                            '_' . "$0",
                            $matches[0][1]
                        )
                    ),
                ));
            }

            throw new BadRequestHttpException($exception->getMessage());
        }

        yield $dto;
    }
}