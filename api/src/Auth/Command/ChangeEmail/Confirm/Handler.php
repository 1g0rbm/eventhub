<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\UserRepository;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $userRepository;

    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher        = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->findByNewEmailToken($command->token);
        if ($user === null) {
            throw new DomainException('token_not_found');
        }

        $date = new DateTimeImmutable();
        $user->confirmEmailChanging(new Token($command->token, $date), $date);

        $this->flusher->flush();
    }
}
