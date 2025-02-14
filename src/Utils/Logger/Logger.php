<?php

namespace App\Utils\Logger;


class Logger
{
    private $logFile;

    private function getLogFilePath()
    {
        $date = new \DateTime();
        $formattedDate = $date->format('Y-m-d');
        return 'tpv-back/logs/' .  'TPVLogs-' .$formattedDate.'.txt';
    }   

    public function log($message)
    {
        $this->logFile = $this->getLogFilePath();
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $date = new \DateTime();
        $formattedDate = $date->format('Y-m-d H:i:s');
        $logMessage = "[$formattedDate] $message" . PHP_EOL;

        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}