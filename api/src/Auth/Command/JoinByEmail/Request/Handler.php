<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\JoinConfirmationSender;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $userRepository;

    private PasswordHasher $hasher;

    private Tokenizer $tokenizer;

    private Flusher $flusher;

    private JoinConfirmationSender $confirmationSender;

    public function __construct(
        UserRepository $userRepository,
        PasswordHasher $hasher,
        Tokenizer $tokenizer,
        Flusher $flusher,
        JoinConfirmationSender $confirmationSender
    ) {
        $this->userRepository     = $userRepository;
        $this->hasher             = $hasher;
        $this->tokenizer          = $tokenizer;
        $this->flusher            = $flusher;
        $this->confirmationSender = $confirmationSender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('user_already_exist');
        }

        $now   = new DateTimeImmutable();
        $token = $this->tokenizer->generate($now);

        $user = User::requestJoinByEmail(
            Id::generate(),
            $now,
            $email,
            $this->hasher->hash($command->password),
            $token
        );

        $this->userRepository->add($user);

        $this->flusher->flush();

        $this->confirmationSender->send($email, $token);
    }
}
