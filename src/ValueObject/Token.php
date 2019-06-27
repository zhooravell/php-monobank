<?php

declare(strict_types=1);

namespace Monobank\ValueObject;

use Monobank\Exception\InvalidTokenException;
use Monobank\ValueObject\Traits\ValueToStringTrait;

/**
 * Token for personal access to the API.
 *
 * @see https://api.monobank.ua/docs/
 */
class Token
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws InvalidTokenException
     */
    public function __construct(string $value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw InvalidTokenException::emptyToken();
        }

        $this->value = $value;
    }
}
