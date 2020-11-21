<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;

class UserRepository
{
    private EntityRepository $repo;

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->repo = $repo;
        $this->em   = $em;
    }

    public function findByConfirmToken(string $token): ?User
    {
        /** @var User|null $user */
        $user = $this->repo->findOneBy(['joinConfirmToken.value' => $token]);

        return $user;
    }

    public function findByNewEmailToken(string $token): ?User
    {
        /** @var User|null $user */
        $user = $this->repo->findOneBy(['newEmailToken.value' => $token]);

        return $user;
    }

    public function findByPasswordResetToken(string $token): ?User
    {
        /** @var User|null $user */
        $user = $this->repo->findOneBy(['passwordResetToken.value' => $token]);

        return $user;
    }

    /**
     * @param Id $id
     *
     * @return User
     * @throws DomainException
     */
    public function getById(Id $id): User
    {
        $user = $this->repo->find($id->getValue());
        if ($user === null) {
            throw new DomainException('user_not_found');
        }

        /** @var User $user */
        return $user;
    }

    /**
     * @param Email $email
     *
     * @return User
     * @throws DomainException
     */
    public function getByEmail(Email $email): User
    {
        $user = $this->findByEmail($email);
        if ($user === null) {
            throw new DomainException('user_not_found');
        }

        return $user;
    }

    public function findByEmail(Email $email): ?User
    {
        /** @var User $user */
        $user = $this->repo->findBy(['email' => $email->getValue()]);

        return $user;
    }

    /**
     * @param Email $email
     *
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo
                ->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.email = :email')
                ->setParameters(
                    [
                        'email' => $email->getValue(),
                    ]
                )
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    /**
     * @param Network $networkIdentity
     *
     * @return bool
     */
    public function hasByNetworkIdentity(Network $networkIdentity): bool
    {
        return $this->repo
                ->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->innerJoin('u.networks', 'n')
                ->andWhere('n.name = :name')
                ->andWhere('n.identity = :identity')
                ->setParameters(
                    [
                        'name' => $networkIdentity->getName(),
                        'identity' => $networkIdentity->getIdentity(),
                    ]
                )
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
