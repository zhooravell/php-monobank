<?php

declare(strict_types=1);

namespace Monobank\Exception;

use Exception;

/**
 * Class InvalidResponseException.
 */
class InvalidResponseException extends Exception implements MonobankException
{
    /**
     * @return InvalidResponseException
     */
    public static function wrongFormat(): self
    {
        return new self('Wrong response body format.');
    }
}
