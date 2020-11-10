<?php

declare(strict_types=1);

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailerCheckCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('mailer:check');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Sending...</comment>');

        $transport = (new \Swift_SmtpTransport('mailer', 1025))
            ->setUsername('app')
            ->setPassword('secret')
            ->setEncryption('tcp');

        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Join confirmation'))
            ->setFrom('mail@app.test')
            ->setTo('user@app.test')
            ->setBody('Confirm');

        if ($mailer->send($message) === 0) {
            throw new \RuntimeException('Unable to send email.');
        }

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
