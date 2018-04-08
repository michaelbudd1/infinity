<?php
namespace Application\Persistence;

class Schema {

    public function get(string $tableName) 
    {
        $schema = FALSE;

        switch($tableName) {

            case "event_uploads" :

                $schema = $this->getEventUploadsSchema($tableName); 

                break;

            default :

              break;
        }

        return $schema;
    }

    private function getEventUploadsSchema(string $tableName)
    {
        return "CREATE TABLE `event_uploads` (
               `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
               `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
               `action` varchar(20) NOT NULL DEFAULT '',
               `call_ref` int(11) DEFAULT NULL,
               `value` decimal(11,0) DEFAULT NULL,
               `currency_code` varchar(3) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    }
}