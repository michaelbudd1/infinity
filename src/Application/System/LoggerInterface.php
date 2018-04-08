<?php
namespace Application\System;

interface LoggerInterface 
{
    public function log(string $message, $type);

}