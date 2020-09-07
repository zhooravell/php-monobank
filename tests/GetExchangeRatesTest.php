<?php

declare(strict_types=1);

namespace Monobank\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Monobank\MonobankClient;
use GuzzleHttp\Psr7\Response;
use Monobank\ValueObject\Token;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use Monobank\Exception\InvalidTokenException;
use Monobank\Exception\InvalidResponseException;

/**
 * Class GetExchangeRatesTest.
 */
class GetExchangeRatesTest extends TestCase
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
     */
    public function testSuccess()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], $this->successBody),
        ]));

        $httpClient = new Client(['handler' => $handler]);
        $token = new Token('test-token');
        $client = new MonobankClient($httpClient, $token);

        $result = $client->getExchangeRates();

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame(840, $result[0]['currencyCodeA']);
        $this->assertSame(980, $result[0]['currencyCodeB']);
        $this->assertSame(1552392228, $result[0]['date']);
        $this->assertSame(27, $result[0]['rateSell']);
        $this->assertSame(27.2, $result[0]['rateBuy']);
        $this->assertSame(27.1, $result[0]['rateCross']);
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
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

        $client->getExchangeRates();
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
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

        $client->getExchangeRates();
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
     */
    public function testCheckParams()
    {
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
                'https://api.monobank.ua/bank/currency',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => ' MonobankPHPClient/0.0.1',
                    ],
                ]
            )
            ->willReturn($response)
        ;

        $token = new Token('test-token');
        $client = new MonobankClient($httpClient, $token);

        $client->getExchangeRates();
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
    "currencyCodeA": 840,
    "currencyCodeB": 980,
    "date": 1552392228,
    "rateSell": 27,
    "rateBuy": 27.2,
    "rateCross": 27.1
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
