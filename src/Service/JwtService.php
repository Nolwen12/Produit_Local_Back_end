<?php

namespace App\Service;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Symfony\Component\Console\SignalRegistry\SignalMap;

class JwtService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($_ENV['JWT_SECRET']));

        $this->config->setValidationConstraints(new SignedWith($this->config->signer(),$this->config->signingKey()),
            new ValidAt(SystemClock::fromUTC())
        );
    }

    public function generate(array $data): string
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('user', $data)
            ->getToken(
                $this->config->signer(),
                $this->config->signingKey()
            );

        return $token->toString();
    }

    public function validate(string $token): ?array
    {
        try {
            $tokenObj = $this->config->parser()->parse($token);

            if (!$tokenObj instanceof Plain) {
                return null;
            }

            if (! $this->config->validator()->validate(
                $tokenObj,
                ...$this->config->validationConstraints()
            )) {
                return null;
            }

            return $tokenObj->claims()->get('user');

        } catch (\Throwable $e) {
            return null;
        }
    }
}