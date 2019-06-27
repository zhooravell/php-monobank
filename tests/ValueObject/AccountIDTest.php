<?php

declare(strict_types=1);

namespace Monobank\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Monobank\ValueObject\AccountID;
use Monobank\Exception\AccountException;

/**
 * Class AccountIDTest.
 */
class AccountIDTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     *
     * @param string $id
     *
     * @throws AccountException
     */
    public function testSuccess(string $id)
    {
        $this->assertEquals($id, strval(new AccountID($id)));
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
     * @throws AccountException
     */
    public function testFail(string $token)
    {
        $this->expectException(AccountException::class);
        $this->expectExceptionMessage('Account ID should not be blank.');

        new AccountID($token);
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
