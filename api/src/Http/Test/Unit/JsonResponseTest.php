<?php

declare(strict_types=1);

namespace App\Http\Test\Unit;

use App\Http\JsonResponse;
use JsonException;
use PHPUnit\Framework\TestCase;
use stdClass;

class JsonResponseTest extends TestCase
{
    /**
     * @dataProvider getCases
     *
     * @param mixed $source
     * @param mixed $expect
     *
     * @throws JsonException
     */
    public function testResponse($source, $expect): void
    {
        $response = new JsonResponse($source);

        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals($expect, (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return iterable
     * @psalm-return iterable<array-key, array<array-key, mixed>>
     */
    public function getCases(): iterable
    {
        $object         = new stdClass();
        $object->string = 'string';
        $object->int    = 22;
        $object->null   = null;

        $arr = [
            'string' => 'string',
            'int' => 22,
            'null' => null,
        ];

        return [
            'null' => [null, 'null'],
            'empty' => ['', '""'],
            'number' => [20, '20'],
            'string' => ['20', '"20"'],
            'object' => [$object, '{"string":"string","int":22,"null":null}'],
            'array' => [$arr, '{"string":"string","int":22,"null":null}'],
        ];
    }
}
