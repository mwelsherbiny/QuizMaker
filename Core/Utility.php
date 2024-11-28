<?php

namespace Core;

class Utility
{
    static function log($msg) {
        $logMessage = "[" . date("Y-m-d H:i:s") . "] $msg \n";
        file_put_contents("logfile.log", $logMessage, FILE_APPEND);
    }
}