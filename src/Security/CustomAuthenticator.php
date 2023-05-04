<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomAuthenticator extends JWTAuthenticator
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $requestToken = $this->getTokenExtractor()->extract($request);
        
        $userToken = $token->getUser()->getToken();
        
        if ($requestToken !== $userToken) {
            return new JsonResponse(['code' => 401, 'message' => 'Already connected'], 401);
        }
        return null;
    }

}