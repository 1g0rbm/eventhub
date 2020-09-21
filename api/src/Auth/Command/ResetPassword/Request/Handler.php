<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordResetConfirmationSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;

class Handler
{
    private UserRepository $userRepository;

    private Tokenizer $tokenizer;

    private Flusher $flusher;

    private PasswordResetConfirmationSender $confirmationSender;

    public function __construct(
        UserRepository $userRepository,
        Tokenizer $tokenizer,
        Flusher $flusher,
        PasswordResetConfirmationSender $confirmationSender
    ) {

        $this->userRepository     = $userRepository;
        $this->tokenizer          = $tokenizer;
        $this->flusher            = $flusher;
        $this->confirmationSender = $confirmationSender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);
        $user  = $this->userRepository->getByEmail($email);

        $date  = new DateTimeImmutable();
        $token = $this->tokenizer->generate($date);

        $user->requestPasswordReset($token, $date);

        $this->flusher->flush();

        $this->confirmationSender->send($email, $token);
    }
}
