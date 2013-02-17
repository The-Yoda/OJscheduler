<?php
define('EXCEPTION_PATH', APP_CONF . 'error-codes.json');
define('DEFAULT_CODE', 500);
define('DEFAULT_ERROR_CODE', 500);

class GenericExceptionFactory {
    private static $aExceptions = array();

    public static function getException($sErrCode = DEFAULT_ERROR_CODE, Exception $e = null) {

        $aErrInfo = self::$aExceptions[strtolower($sErrCode)];

        $ge = new GenericException($aErrInfo['message'], null, $e);
        $ge->setErrorCode($sErrCode);
        $ge->setHttpCode(self::getHttpCode($aErrInfo));
        $ge->setDevMessage($aErrInfo['devmessage']);
        return $ge;
    }

    private function getHttpCode($aErrInfo) {
        if (!empty($aErrInfo['httpstatus']) &&
                is_numeric($aErrInfo['httpstatus'])) {
            return intval($aErrInfo['httpstatus']);
        }
        return DEFAULT_CODE;
    }

    public static function init() {
        self::$aExceptions = json_decode(file_get_contents(EXCEPTION_PATH), true);
    }
} GenericExceptionFactory::init();
?>
