<?php
class RequestManager{

    public static function getRequest() {
        $oRequest = ObjectFactory::getGenModelInstance("Request");
        $oRequest->setUrl(Utils::getFullUrl($_SERVER));
        $aData = array();
        $aData['Server']  = $_SERVER;
        $aData['Get']     = $_GET;
        $aData['Post']    = $_POST;
        $aData['Files']   = $_FILES;
        $aData['Cookie']  = $_COOKIE;
        $aData['Env']     = $_ENV;
        $aData['Request'] = $_REQUEST;

        foreach ($aData as $sKey => $sValue) {
            $oRequest->{'set'.$sKey}(ObjectFactory::getGenModelInstance($sKey, $sValue, true));
        }

        $oRequest->setClass(self::getClassName($oRequest->getUrl()));
        $oRequest->setAction(self::getMethodName($oRequest->getUrl()));
        $oRequest->setMethod(strtolower($oRequest->getServer()->getRequestMethod()));
        return $oRequest;
    }

    private static function getClassName($sUrl) {
        $aParsedUrl = parse_url($sUrl);
        $arr = explode("/", $aParsedUrl['path']);
        return $arr[3];
    }

    private static function getMethodName($sUrl) {
        $aParsedUrl = parse_url($sUrl);
        $arr = explode("/", $aParsedUrl['path']);
        return $arr[4];
    }
}
?>
