<?php

declare(strict_types=1);

namespace Monobank;

use GuzzleHttp\Exception\GuzzleException;
use Monobank\Exception\InvalidResponseException;

/**
 * Public data.
 *
 * @see https://api.monobank.ua/docs/
 */
interface MonobankPublicDataClientInterface
{
    /**
     * @return array
     *
     * @throws GuzzleException
     * @throws InvalidResponseException
     */
    public function getExchangeRates(): array;
}
