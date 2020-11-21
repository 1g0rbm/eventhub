<?php

declare(strict_types=1);

namespace Test\Functional;

use Throwable;

class Json
{
    /**
     * @param string $data
     *
     * @return array
     * @throws Throwable
     */
    public static function decode(string $data): array
    {
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }
}
