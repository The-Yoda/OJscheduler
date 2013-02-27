<?php
trait Rest {
    use Prototype;

    public function __construct() {
        $this->setupDefaults();
    }
    private function setupDefaults() {

        $cDef = function (GenModel $request) {
            throw GenericExceptionFactory::getException('ERR_API_1000');
        };
        $this->delete = $this->put = $cDef;

        $cGetPostDefault = function (GenModel $request) {
            $method = $request->getAction();
            if (!method_exists($this, $method)){
                throw GenericExceptionFactory::getException('ERR_API_1002');
            }
            try {
				$oVars = $request->getRequest();
				$oVars->setSite($request->getSite());
                return $this->buildResponse($this->$method($oVars));
            } catch (Exception $e) {
                throw GenericExceptionFactory::getException('ERR_API_1002');
            }
        };

        $this->post = $this->get = $cGetPostDefault ;
    }


    private function buildResponse($sContent) {
        $oResponse = ObjectFactory::getGenModelInstance("Response");
        $oResponse->setContent($sContent);
        return $oResponse;
    }
}
?>
