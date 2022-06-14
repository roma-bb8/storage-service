<?php declare(strict_types=1);

namespace Storage\Service\Helpers;


use DateTime;
use DateTimeZone;
use Exception;

class DateTimeHelper
{
    public const DATETIME_FORMAT = 'Y-m-d h:i:s';
    public const DATE_FORMAT = 'Y-m-d';
    public const TIMEZONE_UTC = 'UTC';

    public function getUTCDateTimeByCurrent(): ?DateTime
    {
        try {
            return new DateTime('now', new DateTimeZone(self::TIMEZONE_UTC));
        } catch (Exception $e) {
            return null;
        }
    }

    public function getUTCDateTimeByTimestamp(int $timestamp): ?DateTime
    {
        try {
            return (new DateTime('now', new DateTimeZone(self::TIMEZONE_UTC)))->setTimestamp($timestamp);
        } catch (Exception $e) {
            return null;
        }
    }
}
