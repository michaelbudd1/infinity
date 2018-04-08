<?php
ini_set("auto_detect_line_endings", "1");
require_once './config.php';
require_once './autoloader.php';
Application\System\ProcessManager::killIfAlreadyRunning();
$sysLogger = new Application\System\Syslog;
$runner = new Application\ProcessCSVs($sysLogger);
$directoryListingsObj = new Application\FileUtilities\DirectoryListings( 
            UPLOADS_FOLDER, "csv"); 
$runner->processFiles($directoryListingsObj);
register_shutdown_function(['Application\System\ProcessManager', 'removePIDFile']); 

