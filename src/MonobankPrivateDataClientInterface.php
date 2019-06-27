<?php

declare(strict_types=1);

namespace Monobank;

use GuzzleHttp\Exception\GuzzleException;
use Monobank\Request\PersonalStatementRequest;
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
     * Obtaining information about the client and the list of his accounts.
     * Limit on the use of the function no more than 1 time in 60 seconds.
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws InvalidResponseException
     */
    public function getClientInfo(): array;

    /**
     * Receive an extract for the time {from} to {to} time in seconds Unix time format.
     * The maximum time for which it is possible to extract is 31 days (2678400 seconds).
     * Limit on the use of the function no more than 1 time in 60 seconds.
     *
     * @param PersonalStatementRequest $request
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws InvalidResponseException
     */
    public function getPersonalStatement(PersonalStatementRequest $request): array;
}
