<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\UserRepository;
use App\Flusher;
use DomainException;

class Handler
{
    private UserRepository $userRepository;

    private Flusher $flusher;

    public function __construct(
        UserRepository $userRepository,
        Flusher $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->flusher        = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByConfirmToken($command->token);
        if ($user === null) {
            throw new DomainException('incorrect_token');
        }

        $user->confirmJoin($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}
