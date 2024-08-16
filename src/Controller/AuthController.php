<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Entity\UserIdentity;
use Mega\Exception\AuthenticationException;
use Mega\Service\AuthService;
use Mega\Service\UserAccessService;

class AuthController
{
    public function __construct(protected AuthService $authService, protected UserAccessService $userAccessService) {}

    public function authenticate(Request $request): JsonResponse
    {
        $postData = $request->getBody();
        $userIdentity = new UserIdentity($postData['username'], $postData['password']);

        try {
            $user = $this->authService->authenticate($userIdentity);
        } catch (AuthenticationException $e) {
            $response = new JsonResponse(['message' => 'Invalid Credentials']);
            $response->setStatusCode(Response::HTTP_STATUS_UNAUTHORIZED);

            return $response;
        }

        $tokens = $this->userAccessService->regenerateTokens($user);
        $responseData = [];
        foreach ($tokens as $token) {
            $responseData[] = $token->toArray();
        }

        return new JsonResponse($responseData);
    }
}
