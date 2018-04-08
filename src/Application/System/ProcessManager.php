<?php
namespace Application\System;

class ProcessManager 
{
    public function killIfAlreadyRunning() 
    {
        if(TRUE === $this->processIsCurrentlyRunning()) {

            $sysLoggerObj = new \Application\System\Syslog;
            $sysLoggerObj->log("Process terminated as one is already running", LOG_ERR);
            die();
        }

        file_put_contents(ROOT_FOLDER . '/' . PID_FILE, posix_getpid());
    }

    public function processIsCurrentlyRunning() 
    {
    
        if (!file_exists(ROOT_FOLDER . '/' . PID_FILE) || !is_file(ROOT_FOLDER . '/' . PID_FILE)) return FALSE;
        return TRUE;
    }

    public function removePIDFile() 
    {

        unlink(PID_FILE);

    }
}