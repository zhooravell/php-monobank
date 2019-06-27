<?php

declare(strict_types=1);

namespace Monobank\Request;

use DateTimeInterface;
use Monobank\ValueObject\AccountID;
use Monobank\Exception\RequestException;

/**
 * @see https://api.monobank.ua/docs/
 */
class PersonalStatementRequest
{
    private const MAX_PERIOD_IN_SECONDS = 2678400;

    /**
     * @var DateTimeInterface
     */
    private $from;

    /**
     * @var DateTimeInterface
     */
    private $to;

    /**
     * @var AccountID
     */
    private $accountID;

    /**
     * The maximum time for which it is possible to extract is 31 days (2678400 seconds).
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     *
     * @throws RequestException
     */
    public function __construct(DateTimeInterface $from, DateTimeInterface $to)
    {
        if ($from > $to) {
            throw RequestException::invalidPersonalStatementPeriod();
        }

        $diffInSeconds = $to->getTimestamp() - $from->getTimestamp();

        if ($diffInSeconds > self::MAX_PERIOD_IN_SECONDS) {
            throw RequestException::maxPersonalStatementPeriod();
        }

        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return DateTimeInterface
     */
    public function getFrom(): DateTimeInterface
    {
        return $this->from;
    }

    /**
     * @return DateTimeInterface
     */
    public function getTo(): DateTimeInterface
    {
        return $this->to;
    }

    /**
     * @return AccountID|null
     */
    public function getAccountID(): ?AccountID
    {
        return $this->accountID;
    }

    /**
     * @param AccountID $accountID
     *
     * @return PersonalStatementRequest
     */
    public function setAccountID(AccountID $accountID): self
    {
        $this->accountID = $accountID;

        return $this;
    }
}
