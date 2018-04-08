<?php
namespace Application\Events;

class EventProcessor 
{

    public function __construct() {

        $this->runStartupChecks();
    }

    protected function createEvent( $formattedEventData ) {

        $persistenceObj = new \Application\Persistence\Database;

        $eventModelObj = new \Models\Event( $persistenceObj );
        $eventModelObj->setDateTime( $formattedEventData['eventDatetime'] );
        $eventModelObj->setAction( $formattedEventData['eventAction'] );
        $eventModelObj->setCallRef( $formattedEventData['callRef'] );
        $eventModelObj->setValue( $formattedEventData['eventValue'] );
        $eventModelObj->setCurrencyCode( $formattedEventData['eventCurrencyCode'] );
        return $eventModelObj;

    }

    protected function runStartupChecks() {

        $this->createEventsTableIfDoesntExist();
        
    }

    private function createEventsTableIfDoesntExist() 
    {

        $migrationManager = new \Application\Persistence\Database;
        $migrationManager->createTableIfNotExists( 'event_uploads' );
    }

    protected function moveFileToProcessedFolder(string $file) {

        rename( UPLOADS_FOLDER . '/' . $file, PROCESSED_UPLOADS_FOLDER . '/' . $file );
    }

    protected function rowDataIsValid(array $rowData) 
    {
        foreach( $rowData as $field => $value ) {

            Validator::validate( $field, $value, $rowData );
        }
    }
}