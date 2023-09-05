<?php

declare(strict_types=1);

namespace Monobank\Tests\ValueObject;

use Monobank\ValueObject\Token;
use PHPUnit\Framework\TestCase;
use Monobank\Exception\InvalidTokenException;

/**
 * Class TokenTest.
 */
class TokenTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     *
     * @param string $token
     *
     * @throws InvalidTokenException
     */
    public function testSuccess(string $token)
    {
        $this->assertSame($token, (string) (new Token($token)));
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            '1 character' => ['q'],
            '2 characters' => ['a1'],
            '32 characters' => [str_repeat('a', 32)],
            '100 characters' => [str_repeat('2', 100)],
        ];
    }

    /**
     * @dataProvider failDataProvider
     *
     * @param string $token
     *
     * @throws InvalidTokenException
     */
    public function testFail(string $token)
    {
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage('Monobank API token should not be blank.');

        new Token($token);
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            'empty' => [''],
            'space' => [' '],
            'tab' => ["\t"],
            'brake line' => ["\n"],
            'carriage return' => ["\r"],
        ];
    }
}
