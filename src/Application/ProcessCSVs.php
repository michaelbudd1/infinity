<?php
namespace Application;

class ProcessCSVs extends Events\EventProcessor
{
    private $filesToProcess = [];
    private $logger;

    public function __construct(\Application\System\LoggerInterface $logger) 
    {
        parent::__construct(); 
        $this->logger = $logger;
    }	

    public function processFiles(FileUtilities\FileLister $fileListObj) 
    {
        foreach($fileListObj->getFiles() as $file) {

            if(TRUE === \Application\Events\Validator::isValidCSVFile($file)) {

                $this->createAndStoreEvents($file);
            }

            $this->moveFileToProcessedFolder($file);
        }
    }

    private function createAndStoreEvents(String $file) 
    {
        $fileObj = new \SplFileObject(UPLOADS_FOLDER . '/' . $file);

        $rowNumber = 0;

        while (!$fileObj->eof()) {

            $data = $fileObj->fgetcsv();

            if( $rowNumber === 0 ) {

                $headerKeyMap = CSVDataNormaliser::getColumnOrderingMap($data, $file);
            
            } else {

                if(array(null) !== $data) {

                    $this->processRow($data, $headerKeyMap, $file, $rowNumber);   
                }
            }

            $rowNumber++;
        }
    }

    private function processRow(array $data, array $headerKeyMap, string $file, int $rowNumber)
    {
        $formattedData = CSVDataNormaliser::createArrayReadyForValidation($data, $headerKeyMap);
        $isValid = TRUE;

        try {

            $this->rowDataIsValid( $formattedData );
    
        } catch(\Exception $e) {

            $isValid = FALSE;
            $errorMsg = $this->formatErrorMessage( $rowNumber, $file, $e->getMessage() );
            $this->logger->log($errorMsg, LOG_ERR);
        }

        if( TRUE === $isValid ) {

            $event = $this->createEvent( $formattedData );
            $event->save();

        }
    }

    private function formatErrorMessage(int $rowNumber, string $file, string $errorMsg)
    {
        return "Row " . $rowNumber . " in file " . $file . " failed " .
               "validation and was skipped. Validation error: " . 
               $errorMsg;
    }
}