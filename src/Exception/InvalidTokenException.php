<?php

declare(strict_types=1);

namespace Monobank\Exception;

use Exception;

/**
 * Class InvalidTokenException.
 */
class InvalidTokenException extends Exception implements MonobankException
{
    /**
     * @return InvalidTokenException
     */
    public static function emptyToken(): self
    {
        return new self('Monobank API token should not be blank.');
    }
}
