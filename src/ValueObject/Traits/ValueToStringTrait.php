<?php

declare(strict_types=1);

namespace Monobank\ValueObject\Traits;

/**
 * Class ValueToStringTrait.
 */
trait ValueToStringTrait
{
    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
