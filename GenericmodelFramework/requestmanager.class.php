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
		$aParsedUrl = self::parseUrl($oRequest->getUrl());
		$oRequest->setClass(self::getClassName($aParsedUrl));
		$oRequest->setSite(self::getSiteName($aParsedUrl));
        $oRequest->setAction(self::getMethodName($aParsedUrl));
        $oRequest->setMethod(strtolower($oRequest->getServer()->getRequestMethod()));
        return $oRequest;
	}

	private static function parseUrl($sUrl){
        $aParsedUrl = parse_url($sUrl);
		$arr = explode("/", $aParsedUrl['path']);
		return $arr;
	}

	private static function getSiteName($aParsedUrl){
		return $aParsedUrl[2];
	}

    private static function getClassName($aParsedUrl) {
        return $aParsedUrl[1];
    }

    private static function getMethodName($aParsedUrl) {
        return $aParsedUrl[3];
    }
}
?>
