<?php

declare(strict_types=1);

namespace Monobank;

use GuzzleHttp\RequestOptions;
use Monobank\ValueObject\Token;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Monobank\Request\PersonalStatementRequest;
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
        return $this->decode($this->request('GET', '/bank/currency'));
    }

    /**
     * {@inheritdoc}
     */
    public function getClientInfo(): array
    {
        return $this->decode($this->request('GET', '/personal/client-info', $this->token));
    }

    /**
     * {@inheritdoc}
     */
    public function getPersonalStatement(PersonalStatementRequest $request): array
    {
        $endpoint = sprintf(
            '/personal/statement/%s/%d/%d',
            null === $request->getAccountID() ? '0' : strval($request->getAccountID()), // 0 - default account
            $request->getFrom()->getTimestamp(),
            $request->getTo()->getTimestamp()
        );

        return $this->decode($this->request('GET', $endpoint, $this->token));
    }

    /**
     * @param string     $method
     * @param string     $endpoint
     * @param Token|null $token
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    private function request(string $method, string $endpoint, Token $token = null): ResponseInterface
    {
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => sprintf('%s MonobankPHPClient/%s', $this->client->getConfig('headers')['User-Agent'] ?? '', MONOBANK_CLIENT_VERSION),
        ];

        if (null !== $token) {
            $headers['X-Token'] = strval($token);
        }

        return $this->client->request($method, $this->host.$endpoint, [
            RequestOptions::HEADERS => $headers,
        ]);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     *
     * @throws InvalidResponseException
     */
    private function decode(ResponseInterface $response): array
    {
        $decodedContents = json_decode($response->getBody()->getContents(), true);

        if (!is_array($decodedContents)) {
            throw InvalidResponseException::wrongFormat();
        }

        return $decodedContents;
    }
}
