<?php
namespace Application;

class CSVDataNormaliser
{   
    public static function getColumnOrderingMap(array $data, string $file)
    {  
        $requiredKeys = self::getRequiredKeys();

        $keyMap = [];

        foreach($requiredKeys as $searchNeedle) {

            $key = array_search($searchNeedle, $data);
            
            if(FALSE === $key) {

                throw new \Exception("Missing column " . $searchNeedle . " in file " . $file);
            }

            $keyMap[$searchNeedle] = $key;
        }

        return $keyMap;
    }

    private static function getRequiredKeys()
    {
        return [

            'eventDatetime',
            'eventAction',
            'callRef',
            'eventValue',
            'eventCurrencyCode'
        ];
    }

    public static function createArrayReadyForValidation(array $data, array $headerKeyMap) 
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