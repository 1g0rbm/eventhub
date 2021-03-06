<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Psr\Log\LoggerInterface;
use Slim\Handlers\ErrorHandler;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * TODO Remove $logger property annotation
 * @property LoggerInterface $logger
 */
class LogErrorHandler extends ErrorHandler
{
    protected function writeToErrorLog(): void
    {
        $this->logger->error(
            $this->exception->getMessage(),
            [
                'exception' => $this->exception,
                'url' => $this->request->getUri(),
            ]
        );
    }
}
