<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\NewEmailConfirmTokenSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UserRepository $userRepository;

    private Tokenizer $tokenizer;

    private NewEmailConfirmTokenSender $sender;

    private Flusher $flusher;

    public function __construct(
        UserRepository $userRepository,
        Tokenizer $tokenizer,
        NewEmailConfirmTokenSender $sender,
        Flusher $flusher
    ) {
        $this->userRepository = $userRepository;
        $this->tokenizer      = $tokenizer;
        $this->sender         = $sender;
        $this->flusher        = $flusher;
    }

    public function handle(Command $command): void
    {
        $user  = $this->userRepository->getById(new Id($command->id));
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('email_in_use');
        }

        $date  = new DateTimeImmutable();
        $token = $this->tokenizer->generate($date);

        $this->flusher->flush();
        $this->sender->send($email, $token);
    }
}
