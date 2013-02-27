<?php
//*****************************\
error_reporting(-1);
ini_set('display_errors', 1);
/******************************/
define('DIR_PATH_SEPARATOR', "/");
define('APP_ROOT', getAppRoot());
define('VENDOR_PATH', APP_ROOT.'thirdpartylib' . DIR_PATH_SEPARATOR);
define('APP_LIB', APP_ROOT . 'lib' . DIR_PATH_SEPARATOR);
define('APP_FW', APP_ROOT . 'GenericmodelFramework' . DIR_PATH_SEPARATOR);
define('APP_CONF', APP_ROOT . 'conf' . DIR_PATH_SEPARATOR);

require_once(APP_FW . "classloader.class.php");
ClassLoader::setupClassLoaders();
try {
	$oRequest = RequestManager::getRequest();
	$apirouter = new APIRouter($oRequest);
	ResponseManager::send($apirouter->run() , $oRequest);
} catch (GenericException $ge) {
	ResponseManager::sendException($ge);
}



function getAppRoot() {
	$aPath = explode(DIR_PATH_SEPARATOR, __DIR__);
	return join(DIR_PATH_SEPARATOR, $aPath) . DIR_PATH_SEPARATOR;
}
?>
