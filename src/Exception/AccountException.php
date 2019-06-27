<?php

declare(strict_types=1);

namespace Monobank\Exception;

use Exception;

/**
 * Class AccountException.
 */
class AccountException extends Exception implements MonobankException
{
    /**
     * @return AccountException
     */
    public static function emptyID(): self
    {
        return new self('Account ID should not be blank.');
    }
}
