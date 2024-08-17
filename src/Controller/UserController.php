<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Exception;
use Lib\Attribute\ActionPermissionAttribute;
use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Lib\SchemaValidator;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\UsernameAlreadyInUseException;
use Mega\Service\UserService;

class UserController
{
    public function __construct(protected SchemaValidator $schemaValidator, protected UserService $userService) {}

    public function create(Request $request): JsonResponse
    {
        $data = $request->getBody();
        $schema = [
            [
                'field_name' => 'username',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ],
            [
                'field_name' => 'password',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ],
            [
                'field_name' => 'roles',
                'validation' => SchemaValidator::FIELD_TYPE_LIST_OF_OBJECTS,
                'schema' => [
                    [
                        'field_name' => 'uid',
                        'validation' => SchemaValidator::FIELD_TYPE_STRING,
                    ]
                ]
            ]
        ];

        if (!$this->schemaValidator->validate($schema, $data)) {
            $errors = $this->schemaValidator->getErrors();
            $response = new JsonResponse(['errors' => $errors]);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }

        try {
            $user = $this->userService->createFromPostData($data);

            $response = new JsonResponse(['message' => 'User created!', 'user' => $user->toArray()]);
            $response->setStatusCode(Response::HTTP_STATUS_CREATED);

            return $response;
        } catch (UsernameAlreadyInUseException $e) {
            $response = new JsonResponse(['errors' => ['Username already in use']]);
            $response->setStatusCode(Response::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            return $response;
        }
    }

    public function listAll(Request $request): JsonResponse
    {
        $users = $this->userService->getAll();
        $responseData = [];
        foreach ($users as $user) {
            $responseData[] = $user->toArray();
        }

        return new JsonResponse($responseData);
    }

    public function listOne(Request $request): JsonResponse
    {
        $userUid = $request->getParam(':uid:');

        try {
            $user = $this->userService->getOneByUid($userUid);

            return new JsonResponse($user->toArray());
        } catch (EntityNotFoundException $e) {
            $response = new JsonResponse(['message' => 'Not Found']);
            $response->setStatusCode(Response::HTTP_STATUS_NOT_FOUND);

            return $response;
        }
    }
}
