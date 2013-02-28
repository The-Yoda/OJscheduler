<?php
trait Config {
	private static $aConfCache = array();

	public static function conf ($sConfName = __CLASS__) {
		if (isset(self::$aConfCache[$sConfName])) {
			return self::$aConfCache[$sConfName];
		}
		self::$aConfCache[$sConfName] = ObjectFactory::getGenModelInstance("Conf", self::readConf($sConfName));
		return self::$aConfCache[$sConfName];
	}

	private static function readConf($sConfName) {
		$sFile = APP_CONF. $sConfName . ".json";
		if (file_exists($sFile)) {
			return json_decode(file_get_contents($sFile), true);
		}
		throw GenericExceptionFactory::getException('ERR_CONF_1000');
	}

	public static function writeConf($oConf, $sConfName) {
		$sFile = APP_CONF. $sConfName . ".json";
		file_put_contents($sFile, $oConf->asJson());
		return $oConf->asJson();
	}
}
?>
