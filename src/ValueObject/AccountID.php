<?php

declare(strict_types=1);

namespace Monobank\ValueObject;

use Monobank\Exception\AccountException;
use Monobank\ValueObject\Traits\ValueToStringTrait;

/**
 * Class AccountID.
 */
class AccountID
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws AccountException
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw AccountException::emptyID();
        }

        $this->value = $value;
    }
}
