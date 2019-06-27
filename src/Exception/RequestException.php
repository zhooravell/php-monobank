<?php

declare(strict_types=1);

namespace Monobank\Exception;

use Exception;

/**
 * Class RequestException.
 */
class RequestException extends Exception implements MonobankException
{
    /**
     * @return RequestException
     */
    public static function maxPersonalStatementPeriod(): self
    {
        return new self('The maximum period is 31 days (2678400 seconds).');
    }

    /**
     * @return RequestException
     */
    public static function invalidPersonalStatementPeriod(): self
    {
        return new self('"Date to" should be greater than "date from".');
    }
}
