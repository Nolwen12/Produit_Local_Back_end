<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private Configuration $config;

    public function __construct(private UrlGeneratorInterface $urlGenerator, private JwtService $jwtService, private UserRepository $userRepository, string $jwtSecret)
    { 
        $this->config = Configuration::forSymmetricSigner(new Sha256(),InMemory::plainText($jwtSecret));
    }

    public function authenticate(Request $request) : SelfValidatingPassport    
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, needle: 'Bearer ')) {
            throw new AuthenticationException('No JWT token found');
        }

        $token = substr($authHeader, 7);

        $payload = $this->jwtService->validate($token);

        if (!$payload) {
            throw new AuthenticationException('Invalid JWT token');
        }

        return new SelfValidatingPassport(
            new UserBadge($payload['id'], function ($userIdentifier) {
                return $this->userRepository->find($userIdentifier);
            })
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse(['error' => $exception->getMessage()], 401);
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function supports(Request $request): bool
    {
        // Ne pas appliquer le JWT au login
        if ($request->getPathInfo() === '/api/login') {
            return false;
        }

        return str_starts_with($request->getPathInfo(), '/api');
    }
}
