<?php

declare(strict_types=1);

namespace App\Http\Validator;

use LogicException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends LogicException
{
    private ConstraintViolationListInterface $violation;

    public function __construct(
        ConstraintViolationListInterface $violation,
        string $message = 'Invalid input.',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->violation = $violation;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violation;
    }
}
