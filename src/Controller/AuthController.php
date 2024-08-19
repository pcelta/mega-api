<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Entity\UserIdentity;
use Mega\Exception\AuthenticationException;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\InvalidRefreshTokenException;
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
        } catch (AuthenticationException | EntityNotFoundException $e) {
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

    public function refreshToken(Request $request): JsonResponse
    {
        $refreshToken = $request->getAuthorizationToken();
        try {
            $userAccess = $this->userAccessService->renewAccessTokenByRefreshToken($refreshToken);

            return new JsonResponse($userAccess->toArray());
        } catch (InvalidRefreshTokenException $e) {
            $response = new JsonResponse(['message' => 'Invalid Refresh Token']);
            $response->setStatusCode(Response::HTTP_STATUS_UNAUTHORIZED);

            return $response;
        }
    }
}
