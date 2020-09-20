<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;

interface UserRepository
{
    /**
     * @param Id $id
     *
     * @return User
     * @throws DomainException
     */
    public function getById(Id $id): User;

    public function hasByEmail(Email $email): bool;

    public function hasByNetworkIdentity(NetworkIdentity $networkIdentity): bool;

    public function add(User $user): void;

    public function findByConfirmToken(string $token): ?User;
}
