<?php
namespace Application\System;

class Syslog implements LoggerInterface
{

    public function log(String $msg, $type) {

        openlog("CSVProcessor", LOG_PID | LOG_PERROR, LOG_LOCAL0);
        syslog( $type, $msg . "\r\n" );
    	closelog();
    }
}