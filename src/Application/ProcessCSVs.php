<?php
namespace Application;

class ProcessCSVs extends Events\EventProcessor
{
    private $filesToProcess = [];

    public function __construct() 
    {
        parent::__construct(); 
        $this->storeEvents();
    }	

    public function storeEvents() 
    {
        $directoryListingsObj = new FileUtilities\DirectoryListings( 
            UPLOADS_FOLDER, "csv"); 

        foreach($directoryListingsObj->getFiles() as $file) {

            if(TRUE === $this->isValidCSVFile($file)) {

                $this->createAndStoreEvents($file);

            }
        }
    }

    private function isValidCSVFile(String $file) {

        $sysLogger = new \Application\System\Syslog;

        if(1 === preg_match( '/\\t/', file_get_contents(UPLOADS_FOLDER . '/' . $file))) {

            $errMsg = "Please check the file " . $file . ". It does not appear to be a " .
                      "correctly formatted CSV";

            $sysLogger->log( $errMsg, LOG_ERR );

            return FALSE;
        }

        return TRUE;
    }

    private function createAndStoreEvents(String $file) 
    {
        $fileObj = new \SplFileObject(UPLOADS_FOLDER . '/' . $file);

        $i = 0;

        while (!$fileObj->eof()) {

            $data = $fileObj->fgetcsv();

            if( $i === 0 ) {

                $headerKeyMap = $this->getColumnOrderingMap($data, $file);
            
            } else {

                if(array(null) !== $data) {

                    $this->processRow($data, $headerKeyMap, $file, $i);   
                }
            }

            $i++;
        }

        $this->moveFileToProcessedFolder($file);
    }

    private function processRow(array $data, array $headerKeyMap, string $file, int $i)
    {
        $sysLogger = new \Application\System\Syslog;
        $formattedData = $this->createArrayReadyForValidation($data, $headerKeyMap);
        $isValid = TRUE;

        try {

            $this->rowDataIsValid( $formattedData );
    
        } catch(\Exception $e) {

            $isValid = FALSE;
            $errorMsg = $this->formatErrorMessage( $i, $file, $e->getMessage() );
            $sysLogger->log($errorMsg, LOG_ERR);
        }

        if( TRUE === $isValid ) {

            $event = $this->createEvent( $formattedData );
            $event->save();

        }
    }

    private function formatErrorMessage(int $i, string $file, string $errorMsg)
    {
        return "Row " . $i . " in file " . $file . " failed " .
               "validation and was skipped. Validation error: " . 
               $errorMsg;
    }

    private function getColumnOrderingMap(array $data, string $file)
    {   
        $requiredKeys = $this->getRequiredKeys();

        $keyMap = [];

        foreach($requiredKeys as $searchNeedle) {

            $key = array_search($searchNeedle, $data);
            
            if(FALSE === $key) {

                throw new \Exception( "Missing column " . $searchNeedle . " in file " . $file );
            }

            $keyMap[$searchNeedle] = $key;
        }

        return $keyMap;
    }

    private function getRequiredKeys()
    {
        return [

            'eventDatetime',
            'eventAction',
            'callRef',
            'eventValue',
            'eventCurrencyCode'
        ];
    }

    private function createArrayReadyForValidation(array $data, array $headerKeyMap) 
    {

        return [
            'eventDatetime' => ($data[$headerKeyMap['eventDatetime']]) ?? '',
            'eventAction' => ($data[$headerKeyMap['eventAction']]) ?? '',
            'callRef' => ($data[$headerKeyMap['callRef']]) ?? '',
            'eventValue' => ($data[$headerKeyMap['eventValue']]) ?? '',
            'eventCurrencyCode' => ($data[$headerKeyMap['eventCurrencyCode']]) ?? ''
        ];
    }
}