<?php


namespace Tests\Neznajka\Codeception\Engine\Objects\Checker;


use DateTime;
use Exception;

class DateTimeNowChecker
{
    /** @var DateTime */
    private $created;

    /**
     * DateTimeNowChecker constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isNow(DateTime $dateTime): bool
    {
        if ($this->created->getTimestamp() > $dateTime->getTimestamp() || time() < $dateTime->getTimestamp()) {
            throw new \RuntimeException('date time is not now');
        }

        return true;
    }
}
