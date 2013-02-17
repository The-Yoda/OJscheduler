<?php
class ObjectFactory {
    use Config;
    
	public static function __callStatic($sFunction, $sParam) {
	    $sObj  = substr($sFunction, 3, strlen($sFunction) - 11);
		if ($sParam == NULL) {
			$sFunc = 'get' . $sObj;
			$sProvider = self::conf("Main")->$sFunc();
		} else {
			$sProvider = $sParam[0];
		}
     return new $sProvider();
	}

	public static function getGenModelInstance($sEntity, $aDefaults = array(), $bSanitizeKeys= false) {
        if (!empty($aDefaults)) {
            $aDefaults = Utils::sanitizeArray($aDefaults, false);
            $aDefaults = array_change_key_case($aDefaults);

            foreach ($aDefaults as $key => $value) {
                if (is_array($value)) {
                    $aDefaults[$key] = ObjectFactory::getGenModelInstance($key, $value, $bSanitizeKeys);
                }
            }
            return new GenModel($aDefaults);
        }
        return new GenModel();
    }
}
?>
