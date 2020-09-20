<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\NetworkIdentity;
use App\Auth\Entity\User\User;
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
        $identity = new NetworkIdentity($command->network, $command->identity);
        $email    = new Email($command->email);

        if ($this->userRepository->hasByNetworkIdentity($identity)) {
            throw new DomainException('user_with_network_exist');
        }

        if ($this->userRepository->hasByEmail($email)) {
            throw new DomainException('user_with_email_exist');
        }

        $user = User::requestJoinByNetwork(
            Id::generate(),
            new DateTimeImmutable(),
            $email,
            $identity
        );

        $this->userRepository->add($user);

        $this->flusher->flush();
    }
}
