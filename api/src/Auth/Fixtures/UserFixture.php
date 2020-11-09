<?php

declare(strict_types=1);

namespace App\Auth\Fixtures;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class UserFixture extends AbstractFixture
{
    private const PASSWORD_HASH = '$argon2i$v=19$m=16,t=4,p=1$YnVVQUhIV1Y5dEoxejE2Mg$LT8HlwhWXI4YwVXAUyXO0ki7x1HjmT46Sgw2WQZhFdQ';

    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            new Id('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable('-14 days'),
            new Email('active@app.test'),
            self::PASSWORD_HASH,
            new Token($value = Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);
        $manager->flush();
    }
}
