<?php
namespace Application\FileUtilities;

class DirectoryListings implements FileLister
{
    private $folder;
    private $fileType;
    private $files = [];

    public function __construct(String $folder, String $fileType) 
    {

        $this->folder 	= $folder;
        $this->fileType = $fileType;
        $this->fetchFilesInsideDirectory($folder, $fileType);
    }

    public function getFiles() : array
    {
        return $this->files;
    }

    private function fetchFilesInsideDirectory(String $directory, String $fileType)
    {
        $returnArray = [];

        if(TRUE === $this->folderIsReadable($directory)) {

            $files = scandir($directory);

            $returnArray = $this->returnFilesOfType($files, $fileType);
        }

        $this->files = $returnArray;
    }


    private function folderIsReadable(String $directory) 
    {
        $isReadable = TRUE;

        try {

            if(FALSE === scandir($directory)) {

                $isReadable = FALSE;
            }

        } catch( \Exception $e ) {

            $isReadable = FALSE;
        }

        return $isReadable;
    }

    private function returnFilesOfType(array $files, String $passedFileType) {

        $returnArray = [];

        foreach( $files as $file ) {

            $fileType = pathinfo( $file, PATHINFO_EXTENSION );

            if(strtolower($fileType) === strtolower($passedFileType)) {

                $returnArray[] = $file;
            }
        }

        return $returnArray;
    }
}