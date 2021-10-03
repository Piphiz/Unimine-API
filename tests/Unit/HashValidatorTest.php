<?php

namespace Tests\Unit;

use App\Services\HashValidator;
use PHPUnit\Framework\TestCase;

class HashValidatorTest extends TestCase
{
    /**
     * Valida se um hash tem o formato esperado
     *
     * @dataProvider hashProvider
     * @test
     */
    public function should_validate_a_hash($expectedResult, $hash)
    {
        $result = HashValidator::validate($hash);

        $this->assertEquals($expectedResult, $result);
    }

    public function hashProvider(): array
    {
        return [
            [true, "abc123"],
            [true, "abcdef"],
            [true, "000000"],
            [false, "******"],
            [false, "abc1234"],
            [false, "ve@j34"],
            [false, "_123ba"],
            [false, ""],
            [false, "abc"],
        ];
    }
}
