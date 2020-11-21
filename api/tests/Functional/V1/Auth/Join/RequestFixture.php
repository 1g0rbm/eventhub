<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RequestFixture extends AbstractFixture
{
    public const EXISTING_EMAIL = 'existion@app.test';

    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable('-30 days'),
            new Email(self::EXISTING_EMAIL),
            'password-hash',
            new Token(Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}
