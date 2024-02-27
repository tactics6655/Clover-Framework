<?php

declare(strict_types=1);

namespace Neko\Classes\Logging;

use Neko\Classes\File\Handler as FileHandler;

use Neko\Enumeration\LoggingLevel;

use DateTimeZone;
use DateTimeImmutable;

class Logger
{

    private $timezone;
    private $fileLocation;

    public function __construct(?DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone ?? new DateTimeZone(date_default_timezone_get());
        $this->fileLocation = __ROOT__."/application.log";
    }

    public function write(LoggingLevel $loggingLevel, string $content, ?string $namespace = null)
    {
        $timestamp = new DateTimeImmutable("now", $this->timezone);

        $formattedLog = sprintf("[%s] [%s] %s\n",  $timestamp->format('Y-m-d H:i:s.u'), $loggingLevel->value, $content);

        if (FileHandler::isExists($this->fileLocation)) {
            return FileHandler::appendContent($this->fileLocation, $formattedLog);
        }

        return FileHandler::write($this->fileLocation, $formattedLog);
    }

    public function information(string|array $content, ?string $namespace = null)
    {
        $this->write(LoggingLevel::INFORMATION, $content, $namespace);
    }
}