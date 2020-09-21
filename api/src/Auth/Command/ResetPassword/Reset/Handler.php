<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $userRepository;

    private PasswordHasher $passwordHasher;

    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, PasswordHasher $passwordHasher, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher        = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByPasswordResetToken($command->token);
        if ($user === null) {
            throw new DomainException('token_not_found');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            $this->passwordHasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}
