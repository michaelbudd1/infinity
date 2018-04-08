<?php
namespace Application\Events;

class Validator 
{
    public static function validate(string $field, string $value, array $rowData)
    {
        $validationRules = self::getRulesForField($field);

        if(FALSE !== $validationRules) {

            foreach($validationRules as $function) {

				if(FALSE === call_user_func(array('self', $function), $value, $rowData)) {

                    throw new \Exception( "The field \"" . $field . "\" failed validation" );
				}
            }
        }
    }

    public static function getRulesForField($field)
    {
        $rules = [

        	'eventDatetime' => [ 'isValidDateTime', 'isNotEmpty' ],
        	'eventAction' => [ 'isNotEmpty' ],
        	'callRef' => [ 'isInteger' ],
        	'eventValue'	=> [ 'isDecimalOrEmpty' ],
        	'eventCurrencyCode' => [ 'isCurrencyCode', 'requiredIfValueIsNonZero' ]
        ];

        if(isset($rules[$field])) {

        	return $rules[$field];
        }

        return FALSE;
    }

    private static function isNotEmpty($value, array $rowData) 
    {
        if(trim($value) !== "") {

            return TRUE;
        }

        return FALSE;
    }

    private static function requiredIfValueIsNonZero($value, array $rowData)
    {
        if(trim($rowData['eventValue']) === "0") {

            return self::isNotEmpty($value, $rowData);

        } else {

            return TRUE;
        }
    }

    private static function isInteger($value, array $rowData)
    {
        return filter_var( $value, FILTER_VALIDATE_INT );
    }

    private static function isDecimalOrEmpty($value, array $rowData)
    {
        if(filter_var( $value, FILTER_VALIDATE_FLOAT ) !== FALSE ||
           filter_var( $value, FILTER_VALIDATE_INT ) !== FALSE ||
           trim($value) === "") {

            return TRUE;
        }

        return FALSE;
    }

    private static function isCurrencyCode($value, array $rowData)
    {
        return in_array(strtoupper($value), self::getCurrencyCodes());
    }

    private static function isValidDateTime($value, array $rowData) 
    {
        $isValid = TRUE;

        $patternToMatch = "^[0-9][0-9][0-9][0-9]\-[0-9][0-9]\-[0-9][0-9] " .
            "[0-9][0-9]\:[0-9][0-9]\:[0-9][0-9]$";

        if( 0 === preg_match( '/' . $patternToMatch . '/', $value ) ) {

            $isValid = FALSE;   
        }

        return $isValid;
    }

    private static function getCurrencyCodes() {

        return ["AFA","ALL","DZD","USD","EUR","AOA","XCD","NOK","XCD","ARA","AMD","AWG","AUD","EUR","AZM","BSD","BHD","BDT","BBD","BYR","EUR","BZD","XAF","BMD","BTN","BOB","BAM","BWP","NOK","BRL","GBP","BND","BGN","XAF","BIF","KHR","XAF","CAD","CVE","KYD","XAF","XAF","CLF","CNY","AUD","AUD","COP","KMF","CDZ","XAF","NZD","CRC","HRK","CUP","EUR","CZK","DKK","DJF","XCD","DOP","TPE","USD","EGP","USD","XAF","ERN","EEK","ETB","FKP","DKK","FJD","EUR","EUR","EUR","EUR","XPF","EUR","XAF","GMD","GEL","EUR","GHC","GIP","EUR","DKK","XCD","EUR","USD","GTQ","GNS","GWP","GYD","HTG","AUD","EUR","HNL","HKD","HUF","ISK","INR","IDR","IRR","IQD","EUR","ILS","EUR","XAF","JMD","JPY","JOD","KZT","KES","AUD","KPW","KRW","KWD","KGS","LAK","LVL","LBP","LSL","LRD","LYD","CHF","LTL","EUR","MOP","MKD","MGF","MWK","MYR","MVR","XAF","EUR","USD","EUR","MRO","MUR","EUR","MXN","USD","MDL","EUR","MNT","XCD","MAD","MZM","MMK","NAD","AUD","NPR","EUR","ANG","XPF","NZD","NIC","XOF","NGN","NZD","AUD","USD","NOK","OMR","PKR","USD","PAB","PGK","PYG","PEI","PHP","NZD","PLN","EUR","USD","QAR","EUR","ROL","RUB","RWF","XCD","XCD","XCD","WST","EUR","STD","SAR","XOF","EUR","SCR","SLL","SGD","EUR","EUR","SBD","SOS","ZAR","GBP","EUR","LKR","SHP","EUR","SDG","SRG","NOK","SZL","SEK","CHF","SYP","TWD","TJR","TZS","THB","XAF","NZD","TOP","TTD","TND","TRY","TMM","USD","AUD","UGS","UAH","SUR","AED","GBP","USD","USD","UYU","UZS","VUV","VEF","VND","USD","USD","XPF","XOF","MAD","ZMK","USD" ];
    }
}