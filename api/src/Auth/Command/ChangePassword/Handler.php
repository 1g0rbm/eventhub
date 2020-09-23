<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Flusher;

class Handler
{
    private UserRepository $userRepository;

    private PasswordHasher $passwordHasher;

    private Flusher $flusher;

    public function __construct(
        UserRepository $userRepository,
        PasswordHasher $passwordHasher,
        Flusher $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher        = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getById(new Id($command->id));

        $user->changePassword(
            $command->current,
            $command->new,
            $this->passwordHasher
        );

        $this->flusher->flush();
    }
}
