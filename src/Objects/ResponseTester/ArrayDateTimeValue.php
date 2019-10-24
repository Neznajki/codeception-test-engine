<?php
declare(strict_types=1);

namespace Tests\Neznajka\Codeception\Engine\Objects\ResponseTester;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Class ArrayDateTimeValue
 * @package Tests\Neznajka\Codeception\Engine\Objects\ResponseTester
 */
class ArrayDateTimeValue extends AbstractArrayElement
{
    const PHP_DATE_TIME_FORMAT     = 'Y-m-d H:i:s.u';
    const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    /** @var DateTime|null */
    protected $expectingTime;
    /** @var int */
    protected $precisionSeconds;

    /**
     * ArrayDateTimeValue constructor.
     * @param DateTime|null $expectingTime null means doesn't matter
     * @param int $precisionSeconds (date time will be generated after unknown time will pass, so we will check if this is in interval from DateTime to DateTime +x seconds)
     */
    public function __construct(
        DateTime $expectingTime = null,
        int $precisionSeconds = 1
    ) {
        $this->expectingTime    = $expectingTime;
        $this->precisionSeconds = $precisionSeconds;
    }

    /**
     * @param $item
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkElement($item): bool
    {
        $dateTime = $this->convertToDateTime($item);

        if ($dateTime instanceof DateTime) {
            $this->checkDateTimeObject($dateTime);
        } else {
            throw new InvalidArgumentException("invalid date received");
        }

        return true;
    }

    /**
     * @param DateTime $receivedItem
     * @throws InvalidArgumentException
     */
    protected function checkDateTimeObject(DateTime $receivedItem)
    {
        if ($this->expectingTime->getTimestamp() > $receivedItem->getTimestamp()) {
            throw new InvalidArgumentException('received date time is in past according to expecting');
        }

        if ($this->expectingTime instanceof ArrayDateTimeNow) {
            if ($receivedItem->getTimestamp() > time()) {
                throw new InvalidArgumentException('date time should be somewhere between test execution');
            }

            return;
        }

        $this->expectingTime->modify(sprintf('+%d seconds', $this->precisionSeconds));

        $diff = $receivedItem->getTimestamp() - $this->expectingTime->getTimestamp();
        if ($diff > 0) {
            throw new InvalidArgumentException(sprintf('received time is ahead of expected on %d seconds', $diff));
        }
    }

    /**
     * @param array $item // to allow override for getting it from string or int
     * @return DateTime
     * @throws InvalidArgumentException
     */
    protected function convertToDateTime($item): DateTime
    {
        if ($item instanceof DateTime) {
            return $item;
        }

        if (is_array($item)) {
            return $this->convertToDateTimeFromArray($item);
        }

        if (is_string($item)) {
            return $this->convertToDateTimeFromString($item);
        }

        throw new InvalidArgumentException("unknown item type");
    }

    /**
     * @param array $item
     * @return bool|DateTime
     * @throws InvalidArgumentException
     */
    protected function convertToDateTimeFromArray(array $item)
    {
        $requiredKeys = array_keys((array)$this->expectingTime);
        $receivedKeys = array_keys($item);

        arsort($requiredKeys);
        arsort($receivedKeys);

        if ($receivedKeys !== $requiredKeys) {
            throw new InvalidArgumentException("invalid array date provided keys do not match");
        }

        $result = DateTime::createFromFormat(
            self::PHP_DATE_TIME_FORMAT,
            $item['date'],
            new DateTimeZone($item['timezone'])
        );

        if (! $result) {
            throw new InvalidArgumentException(
                sprintf('invaild data in date time array %s (%s)', self::PHP_DATE_TIME_FORMAT, $item['date'])
            );
        }

        return $result;
    }

    /**
     * @param string $item
     * @return bool|DateTime
     * @throws InvalidArgumentException
     */
    protected function convertToDateTimeFromString(string $item)
    {
        $result = DateTime::createFromFormat(self::MYSQL_DATE_TIME_FORMAT, $item);

        if (! $result) {
            throw new InvalidArgumentException(
                sprintf('DateTime should be mysql data format %s (%s)', self::MYSQL_DATE_TIME_FORMAT, $item)
            );
        }

        return $result;
    }
}
