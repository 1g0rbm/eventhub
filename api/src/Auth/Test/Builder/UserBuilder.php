<?php

declare(strict_types=1);

namespace App\Auth\Test\Builder;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Network;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class UserBuilder
{
    private Id $id;
    private Email $email;
    private DateTimeImmutable $timestamp;
    private string $passwordHash;
    private Token $joinConfirmToken;
    private bool $active = false;
    private ?Network $networkIdentity = null;

    public function __construct()
    {
        $this->id               = Id::generate();
        $this->email            = new Email('email@localhost.test');
        $this->passwordHash     = 'hash';
        $this->timestamp        = new DateTimeImmutable();
        $this->joinConfirmToken = new Token(Uuid::uuid4()->toString(), $this->timestamp->modify('+1 day'));
    }

    public function withJoinConfirmationToken(Token $token): self
    {
        $clone                   = clone $this;
        $clone->joinConfirmToken = $token;

        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;

        $clone->email = $email;

        return $clone;
    }

    public function active(): self
    {
        $clone         = clone $this;
        $clone->active = true;

        return $clone;
    }

    public function withNetwork(Network $networkIdentity = null): self
    {
        $clone = clone $this;

        $clone->networkIdentity = $networkIdentity ?: new Network('vk', 'vk-01');

        return $clone;
    }

    public function build(): User
    {
        if ($this->networkIdentity) {
            return User::requestJoinByNetwork(
                $this->id,
                $this->timestamp,
                $this->email,
                $this->networkIdentity
            );
        }

        $user = User::requestJoinByEmail(
            $this->id,
            $this->timestamp,
            $this->email,
            $this->passwordHash,
            $this->joinConfirmToken
        );

        if ($this->active) {
            $user->confirmJoin(
                $this->joinConfirmToken->getValue(),
                $this->joinConfirmToken->getExpires()->modify('-1 day')
            );
        }

        return $user;
    }
}
