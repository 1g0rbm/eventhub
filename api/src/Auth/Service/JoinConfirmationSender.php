<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Frontend\FrontendUrlGenerator;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;

use function http_build_query;

class JoinConfirmationSender
{
    private Swift_Mailer $mailer;

    private FrontendUrlGenerator $urlGenerator;

    public function __construct(Swift_Mailer $mailer, FrontendUrlGenerator $urlGenerator)
    {
        $this->mailer       = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    public function send(Email $email, Token $token): void
    {
        $message = (new Swift_Message('Join Confirmation'))
            ->setTo($email->getValue())
            ->setBody(
                $this->urlGenerator->generate(
                    'join/confirm',
                    [
                        'token' => $token->getValue(),
                    ]
                )
            );

        if ($this->mailer->send($message) === 0) {
            throw new RuntimeException('unable_send_email');
        }
    }
}
