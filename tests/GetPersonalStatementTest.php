<?php

declare(strict_types=1);

namespace Monobank\Tests;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Monobank\MonobankClient;
use GuzzleHttp\Psr7\Response;
use Monobank\ValueObject\Token;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Monobank\ValueObject\AccountID;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Monobank\Exception\AccountException;
use Monobank\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use Monobank\Exception\InvalidTokenException;
use Monobank\Request\PersonalStatementRequest;
use Monobank\Exception\InvalidResponseException;

/**
 * Class GetPersonalStatementTest.
 */
class GetPersonalStatementTest extends TestCase
{
    /**
     * @var string
     */
    private $successBody;

    /**
     * @var string
     */
    private $errorBody;

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     * @throws RequestException
     */
    public function testSuccess()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], $this->successBody),
        ]));

        $httpClient = new Client(['handler' => $handler]);
        $token = new Token('test-token');
        $client = new MonobankClient($httpClient, $token);
        $result = $client->getPersonalStatement(new PersonalStatementRequest(new DateTime(), new DateTime()));

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('ZuHWzqkKGVo=', $result[0]['id']);
        $this->assertEquals(1554466347, $result[0]['time']);
        $this->assertEquals('Покупка щастя', $result[0]['description']);
        $this->assertEquals(7997, $result[0]['mcc']);
        $this->assertFalse($result[0]['hold']);
        $this->assertEquals(-95000, $result[0]['amount']);
        $this->assertEquals(-95000, $result[0]['operationAmount']);
        $this->assertEquals(980, $result[0]['currencyCode']);
        $this->assertEquals(0, $result[0]['commissionRate']);
        $this->assertEquals(19000, $result[0]['cashbackAmount']);
        $this->assertEquals(10050000, $result[0]['balance']);
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     * @throws RequestException
     */
    public function testFail()
    {
        $this->expectException(GuzzleException::class);

        $handler = HandlerStack::create(new MockHandler([
            new Response(500, [], $this->errorBody),
        ]));

        $httpClient = new Client(['handler' => $handler]);
        $token = new Token('test-token');
        $client = new MonobankClient($httpClient, $token);

        $client->getPersonalStatement(new PersonalStatementRequest(new DateTime(), new DateTime()));
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     * @throws RequestException
     */
    public function testInvalidBody()
    {
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('Wrong response body format.');

        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], 'test'),
        ]));

        $httpClient = new Client(['handler' => $handler]);
        $token = new Token('test-token');
        $client = new MonobankClient($httpClient, $token);

        $client->getPersonalStatement(new PersonalStatementRequest(new DateTime(), new DateTime()));
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     * @throws RequestException
     */
    public function testCheckParams()
    {
        $xToken = 'test-token';

        /** @var ClientInterface|MockObject $client */
        $httpClient = $this->createMock(ClientInterface::class);

        $response = $this->createMock(ResponseInterface::class);
        $responseBody = $this->createMock(StreamInterface::class);
        $responseBody->expects($this->once())
            ->method('getContents')
            ->willReturn($this->successBody)
        ;

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody)
        ;

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET',
                'https://api.monobank.ua/personal/statement/0/1560124800/1560556800',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => ' MonobankPHPClient/0.0.1',
                        'X-Token' => $xToken,
                    ],
                ]
            )
            ->willReturn($response)
        ;

        $token = new Token($xToken);
        $client = new MonobankClient($httpClient, $token);

        $client->getPersonalStatement(new PersonalStatementRequest(new DateTime('2019-06-10'), new DateTime('2019-06-15')));
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     * @throws RequestException
     * @throws AccountException
     */
    public function testCheckParamsWithAccountID()
    {
        $xToken = 'test-token';

        /** @var ClientInterface|MockObject $client */
        $httpClient = $this->createMock(ClientInterface::class);

        $response = $this->createMock(ResponseInterface::class);
        $responseBody = $this->createMock(StreamInterface::class);
        $responseBody->expects($this->once())
            ->method('getContents')
            ->willReturn($this->successBody)
        ;

        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($responseBody)
        ;

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET',
                'https://api.monobank.ua/personal/statement/test/1560124800/1560556800',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => ' MonobankPHPClient/0.0.1',
                        'X-Token' => $xToken,
                    ],
                ]
            )
            ->willReturn($response)
        ;

        $token = new Token($xToken);
        $client = new MonobankClient($httpClient, $token);

        $request = new PersonalStatementRequest(new DateTime('2019-06-10'), new DateTime('2019-06-15'));
        $request->setAccountID(new AccountID('test'));

        $client->getPersonalStatement($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->successBody = <<<'JSON'
[
  {
    "id": "ZuHWzqkKGVo=",
    "time": 1554466347,
    "description": "Покупка щастя",
    "mcc": 7997,
    "hold": false,
    "amount": -95000,
    "operationAmount": -95000,
    "currencyCode": 980,
    "commissionRate": 0,
    "cashbackAmount": 19000,
    "balance": 10050000
  }
]
JSON;
        $this->errorBody = <<<'JSON'
{
  "errorDescription": "string"
}
JSON;
    }
}
