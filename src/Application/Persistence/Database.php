<?php
namespace Application\Persistence;

class Database implements PersistenceInterface
{
    public function store(\Models\Event $event) 
    {
        $conn = $this->getConnection();

        $sql = "INSERT INTO event_uploads 
               ( date_time, action, call_ref, value, currency_code ) 
               VALUES ( :dateTime, :action, :callRef, :value, :currencyCode )";

        $args = [

            ':dateTime' => $event->getDateTime(),
            ':action' => $event->getAction(),
            ':callRef' => $event->getCallRef(),
            ':value' => $event->getValue(),
            ':currencyCode' => $event->getCurrencyCode()
        ];

        $q = $conn->prepare($sql);
        $q->execute($args);
        $this->closeConnection($conn);
    }

    public function createTableIfNotExists(string $tableName) 
    {
        if(FALSE === $this->tableDoesExist($tableName)) {

            $this->createTable($tableName);
        }   
    }

    private function createTable(string $tableName) 
    {
        $schemaObj = new Schema;
        $sql = $schemaObj->get($tableName);

        if(FALSE !== $sql) {

            $conn = $this->getConnection();

            $conn->query($sql);

            $this->closeConnection( $conn );
        }
    }

    private function tableDoesExist(string $tableName) 
    {
           
        $doesExist = FALSE; 

        $conn = $this->getConnection();
        
        if(FALSE !== $conn) {
        
            $sql = "SELECT * 
                   FROM information_schema.tables
                   WHERE table_schema = " . DB_NAME . 
                   "AND table_name = :tableName
                   LIMIT 1";

            $q = $conn->prepare($sql);
            $q->execute([ ':tableName' => $tableName ]);
            $doesExist = $q->fetch( \PDO::FETCH_OBJ );
        }

        $this->closeConnection( $conn );

        return $doesExist;

    }

    private function getConnection() 
    {
        $db = FALSE;

        try {

            $db = new \PDO('mysql:host=localhost;dbname=' . DB_NAME, DB_USER, DB_PASS);
        
        } catch( \Exception $e ) {

            $syslogObj = new \Application\System\Syslog;
            $syslogObj->log("Could not connect to database", LOG_CRIT);
        }

        return $db;
    }

    private function closeConnection( $conn ) {

        $conn = null;
    }
}