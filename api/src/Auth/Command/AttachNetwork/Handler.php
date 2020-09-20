<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\NetworkIdentity;
use App\Auth\Entity\User\UserRepository;
use App\Flusher;
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
        if ($this->userRepository->hasByNetworkIdentity($identity)) {
            throw new DomainException('user_with_network_exist');
        }

        $user = $this->userRepository->getById(new Id($command->id));

        $user->attachNetwork($identity);

        $this->flusher->flush();
    }
}
