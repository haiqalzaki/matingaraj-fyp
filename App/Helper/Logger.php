<?php

namespace App\Helper;

class Logger 
{
    private static $logDir;

    private static function init()
    {
        if (!self::$logDir) {
            self::$logDir = $_ENV['BASE_ROOT'] . "/log/";
        }
    }

    public static function log($message) 
    {   
        self::init();
        error_log($message . " >> " . date('H:i:s d-m-Y') . "\r\n", 3, self::$logDir . "log.txt");
    }

    private static function stackTraces($message) {
        $e = new \Exception;

        self::init();
        error_log("Date >> " . date('H:i:s d-m-Y') . "\r\nReason >> " . $message . "\r\nStack Traces: \r\n" . $e->getTraceAsString() . "\r\n\r\n", 3, self::$logDir . "tracelog.txt");
    }

    public static function logSuccess($message) 
    {
        self::init();
        error_log($message . " >> " . date('H:i:s d-m-Y') . "\r\n", 3, self::$logDir . "operations.txt");
    }

    public static function getClientIp() {
        $ipaddress = '';
        self::init();

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        error_log("Client IP is" . " >> " . $ipaddress . " | Request Method: " . $_SERVER['REQUEST_METHOD'] . " | "  . date('H:i:s d-m-Y') . "\r\n", 3, self::$logDir . "requestip.txt");
    }
}
