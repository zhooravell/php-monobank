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
 * Class GetClientInfoTest.
 */
class GetClientInfoTest extends TestCase
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

        $result = $client->getClientInfo();

        $this->assertTrue(is_array($result));
        $this->assertTrue(is_array($result['accounts']));
        $this->assertTrue(1 == count($result['accounts']));
        $this->assertEquals('Mono cat', $result['name']);
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

        $client->getClientInfo();
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

        $client->getClientInfo();
    }

    /**
     * @throws GuzzleException
     * @throws InvalidResponseException
     * @throws InvalidTokenException
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
                'https://api.monobank.ua/personal/client-info',
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

        $client->getClientInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->successBody = <<<'JSON'
{
  "name": "Mono cat",
  "accounts": [
    {
      "id": "kKGVoZuHWzqVoZuH",
      "balance": 10000000,
      "creditLimit": 10000000,
      "currencyCode": 980,
      "cashbackType": "UAH"
    }
  ]
}
JSON;
        $this->errorBody = <<<'JSON'
{
  "errorDescription": "string"
}
JSON;
    }
}
