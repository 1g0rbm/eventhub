<?php

declare(strict_types=1);

use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\JoinConfirmationSender;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

return [
    UserRepository::class => static function (ContainerInterface $container) {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(User::class);

        return new UserRepository($em, $repo);
    },

    JoinConfirmationSender::class => static function (ContainerInterface $container): JoinConfirmationSender {
        /** @var Swift_Mailer $mailer */
        $mailer = $container->get(Swift_Mailer::class);

        /**
         * @psalm-suppress MixedArrayAccess
         * @psalm-var      array{from:array} $mailerConfig
         */
        $mailerConfig = $container->get('config')['mailer'];

        return new JoinConfirmationSender($mailer, $mailerConfig['from']);
    },
];