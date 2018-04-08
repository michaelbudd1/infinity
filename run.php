<?php
ini_set("auto_detect_line_endings", "1");
require_once './config.php';
require_once './autoloader.php';
$processManager = new Application\System\ProcessManager;
$processManager->killIfAlreadyRunning();
$runner = new Application\ProcessCSVs;
$processManager->removePIDFile(); 

