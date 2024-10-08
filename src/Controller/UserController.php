<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Lib\SchemaValidator;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\UsernameAlreadyInUseException;
use Mega\Service\UserService;

class UserController extends AbstractController
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
            return JsonResponse::createNotFound();
        }
    }

    public function patch(Request $request): JsonResponse
    {
        $userUid = $request->getParam(':uid:');
        $data = $request->getBody();
        $schema = [
            [
                'field_name' => 'password',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
                'optional' => true,
            ],
            [
                'field_name' => 'roles',
                'validation' => SchemaValidator::FIELD_TYPE_LIST_OF_OBJECTS,
                'optional' => true,
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
            $user = $this->userService->getOneByUid($userUid);
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }

        $this->userService->update($user, $data);

        return new JsonResponse($user->toArray());
    }

    public function disable(Request $request): JsonResponse
    {
        $userUid = $request->getParam(':uid:');

        try {
            $user = $this->userService->getOneByUid($userUid);
            $this->userService->disable($user);

            return new JsonResponse(['message' => 'User has successfully been disabled']);
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }
    }
}
