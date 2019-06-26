<?php

declare(strict_types=1);

namespace Monobank;

use GuzzleHttp\RequestOptions;
use Monobank\ValueObject\Token;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Monobank\Exception\InvalidResponseException;

/**
 * Monobank HTTP client.
 */
class MonobankClient implements MonobankPublicDataClientInterface, MonobankPrivateDataClientInterface
{
    private const HOST = 'https://api.monobank.ua';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Token
     */
    private $token;

    /**
     * @var string
     */
    private $host;

    /**
     * @param ClientInterface $client
     * @param Token           $token
     * @param string          $host
     */
    public function __construct(ClientInterface $client, Token $token, string $host = self::HOST)
    {
        $this->client = $client;
        $this->token = $token;
        $this->host = rtrim($host, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function getExchangeRates(): array
    {
        $response = $this->request('GET', '/bank/currency');
        $decodedContents = json_decode($response->getBody()->getContents(), true);

        if (!is_array($decodedContents)) {
            throw InvalidResponseException::wrongFormat();
        }

        return $decodedContents;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientInfo(): array
    {
        // TODO: Implement getClientInfo() method.
    }

    /**
     * @param string $method
     * @param string $endpoint
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    private function request(string $method, string $endpoint): ResponseInterface
    {
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => sprintf('%s MonobankPHPClient/%s', $this->client->getConfig('headers')['User-Agent'], MONOBANK_CLIENT_VERSION),
        ];

        return $this->client->request($method, $this->host.$endpoint, [
            RequestOptions::HEADERS => $headers,
        ]);
    }
}
