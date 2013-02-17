<?php 
include_once('Log.php');
 
class Logfactory {
	 
	private static $aPriorities = array(    'emergency' => PEAR_LOG_EMERG, 
											'alert' => PEAR_LOG_ALERT,
											'critical' => PEAR_LOG_CRIT, 
											'error' => PEAR_LOG_ERR, 
											'warning' => PEAR_LOG_WARNING, 
											'notice' => PEAR_LOG_NOTICE, 
											'info' => PEAR_LOG_INFO, 
											'debug' => PEAR_LOG_DEBUG  
										);

    public static function getLogger($sType, $sFilename) {
		return Log::singleton($sType, $sFilename);
    }

    public static function __callStatic($sFunction, $aParam) {
		$conf = self::conf("Main");
	    $sFieldName  = strtolower($sFunction);
	    $logger = self::getLogger($conf->getLogType(), $conf->getLogName());
		$traceArray = debug_backtrace();
		$currArr = $traceArray[sizeof($traceArray) - 1];
		$params = "";
		for ($i = 0; $i < sizeof($currArr["args"]); $i++) { 
			$currArr["args"][$i] = (is_object($currArr["args"][$i])) ? $currArr["args"][$i]->asJson() : $currArr["args"][$i];
			$params .= $currArr["args"][$i];
			if ($i != sizeof($currArr["args"]) - 1) {
				$params .= ", ";
			}
		}
		$logger->log($currArr["file"] 
						. "---" .  $currArr["class"] 
						. "::" .  $currArr["function"] 
						. "(" .  $params . ")---" 
						. $aParam[0]
						, self::$aPriorities[$sFunction]
					);	
    }
}
?>