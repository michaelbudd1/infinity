<?php
namespace Models;

class Event 
{
    private $dateTime;
    private $action;
    private $callRef;
    private $value;
    private $currencyCode;
    private $persistentObj;

    public function __construct( $persistenceObj ) {

        $this->persistenceObj = $persistenceObj;
    }

    public function setDateTime(String $dateTime) {

    	$this->dateTime = $dateTime;
    }

    public function setAction(String $action) {

    	$this->action = $action;
    }

    public function setCallRef(String $callRef) {

    	$this->callRef = $callRef;
    }

    public function setValue(String $value) {

    	$this->value = $value;
    }

    public function setCurrencyCode(String $currency) {

    	$this->currencyCode = $currency;
    }

    public function getDateTime() {

    	return $this->dateTime;
    }

    public function getAction() {

    	return $this->action;
    }

    public function getCallRef() {

    	return $this->callRef;
    }

    public function getCurrencyCode() {

    	return $this->currencyCode;
    }

    public function getValue() {

    	return $this->value;
    }

    public function save() {

        $this->persistenceObj->store( $this );
    }
}
