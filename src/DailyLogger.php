<?php

namespace Timer;

use DateTime;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class DailyLogger
{
    public \Monolog\Logger $logger;

    public function __construct(string $date)
    {

        $this->logger = new \Monolog\Logger('Timer');
        $logHandler = new StreamHandler(TIMER_ROOT.'/logs/'.$date.'.log', Level::Debug);

        $dateFormat = "H:i";
        $formatter = new LineFormatter("[%datetime%]  %message%\n", $dateFormat);

        $logHandler->setFormatter($formatter);
        $this->logger->pushHandler($logHandler);
    }

    public function log(string $message) : void
    {
        $this->logger->debug($message);
    }

}