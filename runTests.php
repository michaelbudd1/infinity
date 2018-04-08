<?php
require_once './config.php';
require_once './autoloader.php';

function doesPassValidation($function) 
{

	$passedValidation = TRUE;

	try {

		$function();

	} catch(\Exception $e) {

		$passedValidation = FALSE;
	}

	return $passedValidation;
}

function runTest(string$function, bool $expectedBool)
{
	if(doesPassValidation($function) === $expectedBool) {

		echo "Test \"" . $function . "\" passed\r\n";
	
	} else {

		echo "Test \"" . $function . "\" failed\r\n";
	}
}

function testEventCurrencyCodeRequiredWhenEventValueIsNonZero() 
{

	$testArray = [

		"eventDateTime"	=>	"2018-01-02 10:27:36",
		"eventAction" => "sale",
		"callRef" => 4536,
		"eventValue" => 123,
		"eventCurrencyCode" => ""
	];

	\Application\Events\Validator::validate( "eventCurrencyCode", "", $testArray );
}



function testEventCurrencyCodeNotRequiredWhenEventValueIsZero() {

	$testArray = [

		"eventDateTime"	=>	"2018-01-02 10:27:36",
		"eventAction" => "sale",
		"callRef" => 4536,
		"eventValue" => 0,
		"eventCurrencyCode" => ""
	];

	\Application\Events\Validator::validate( "eventCurrencyCode", "", $testArray );

}

function testIsDecimalOrEmptyPassingString() {

	$testArray = [

		"eventDateTime"	=>	"2018-01-02 10:27:36",
		"eventAction" => "sale",
		"callRef" => 4536,
		"eventValue" => "asd",
		"eventCurrencyCode" => ""
	];

	\Application\Events\Validator::validate( "eventValue", "asd", $testArray );

}

function testIsDecimalOrEmptyPassingDecimal() {

	$testArray = [

		"eventDateTime"	=>	"2018-01-02 10:27:36",
		"eventAction" => "sale",
		"callRef" => 4536,
		"eventValue" => 123.23,
		"eventCurrencyCode" => ""
	];

	\Application\Events\Validator::validate( "eventValue", 123.23, $testArray );

}

runTest('testEventCurrencyCodeRequiredWhenEventValueIsNonZero', FALSE);
runTest('testEventCurrencyCodeNotRequiredWhenEventValueIsZero', TRUE);
runTest('testIsDecimalOrEmptyPassingString', FALSE);
runTest('testIsDecimalOrEmptyPassingDecimal', TRUE);
