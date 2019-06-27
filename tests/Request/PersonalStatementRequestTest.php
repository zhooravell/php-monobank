<?php

declare(strict_types=1);

namespace Monobank\Tests\Request;

use DateTime;
use Exception;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Monobank\ValueObject\AccountID;
use Monobank\Exception\RequestException;
use Monobank\Request\PersonalStatementRequest;

/**
 * Class PersonalStatementRequestTest.
 */
class PersonalStatementRequestTest extends TestCase
{
    /**
     * @dataProvider successDataProvider
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @param AccountID         $accountID
     *
     * @throws RequestException
     */
    public function testSuccess(DateTimeInterface $from, DateTimeInterface $to, ?AccountID $accountID)
    {
        $request = new PersonalStatementRequest($from, $to);

        if (null != $accountID) {
            $obj = $request->setAccountID($accountID);

            $this->assertInstanceOf(PersonalStatementRequest::class, $obj);
        }

        $this->assertEquals($from, $request->getFrom());
        $this->assertEquals($to, $request->getTo());
        $this->assertEquals($accountID, $request->getAccountID());
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function successDataProvider(): array
    {
        return [
            [new DateTime(), new DateTime(), null],
            [new DateTime('2019-06-01 19:12:05'), new DateTime('2019-06-10 19:12:45'), null],
            [new DateTime('2019-06-01'), new DateTime('2019-06-25'), new AccountID('kKGVoZuHWzqVoZuH')],
            [new DateTime('2019-06-01'), new DateTime('2019-07-02'), new AccountID('test')],
            [new DateTime('2019-06-01'), new DateTime('2019-07-01'), null],
        ];
    }

    /**
     * @dataProvider failDataProvider
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     *
     * @throws RequestException
     */
    public function testFail(DateTimeInterface $from, DateTimeInterface $to)
    {
        $this->expectException(RequestException::class);

        new PersonalStatementRequest($from, $to);
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function failDataProvider(): array
    {
        return [
            [new DateTime('2019-06-01'), new DateTime('2019-07-05')],
            [new DateTime('2019-05-01'), new DateTime('2019-07-05')],
            [new DateTime('2019-07-05'), new DateTime('2019-05-01')],
        ];
    }
}
