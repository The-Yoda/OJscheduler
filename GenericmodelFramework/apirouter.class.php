<?php
class APIRouter {

    private $oRequest;
    
    function __construct($oRequest){
        $this->oRequest = $oRequest;
    }

    function run() {
        $class = $this->oRequest->getClass();
        $method = $this->oRequest->getMethod();
        $c = new $class();
        return $c->$method($this->oRequest);
    }
}
?>
