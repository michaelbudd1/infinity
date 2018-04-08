<?php
namespace Application\System;

class ProcessManager 
{
    public static function killIfAlreadyRunning() 
    {
        if(TRUE === self::processIsCurrentlyRunning()) {

            $sysLoggerObj = new \Application\System\Syslog;
            $sysLoggerObj->log("Process already running, please remove PID lock if required", LOG_ERR);
            die();
        }

        file_put_contents(ROOT_FOLDER . '/' . PID_FILE, posix_getpid());
    }

    public static function processIsCurrentlyRunning() 
    {
    
        if (!file_exists(ROOT_FOLDER . '/' . PID_FILE) || !is_file(ROOT_FOLDER . '/' . PID_FILE)) return FALSE;
        return TRUE;
    }

    public static function removePIDFile() 
    {
        unlink(PID_FILE);
    }
}