<?php

declare(strict_types=1);

namespace Monobank;

use GuzzleHttp\Exception\GuzzleException;
use Monobank\Exception\InvalidResponseException;

/**
 * Private data.
 *
 * @see https://api.monobank.ua/docs/
 * @see https://api.monobank.ua/docs/corporate.html
 */
interface MonobankPrivateDataClientInterface
{
    /**
     * @return array
     *
     * @throws GuzzleException
     * @throws InvalidResponseException
     */
    public function getClientInfo(): array;
}
