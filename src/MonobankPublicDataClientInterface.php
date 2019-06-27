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
     * Get a basic list of monobank currency rates.
     * Information is cached and updated no more than once every 5 minutes.
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws InvalidResponseException
     */
    public function getExchangeRates(): array;
}
