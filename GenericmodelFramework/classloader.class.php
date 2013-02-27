<?
class ClassLoader {
    static $aPaths = array();
    public static function setupClassLoaders() {    
        self::$aPaths = self::getScanPaths();        
        spl_autoload_register(function ($className) {
            if (isset(ClassLoader::$aPaths[strtolower($className)])) {
                return include ClassLoader::$aPaths[strtolower($className)];
            } else {
                return false;
            }
        });
    }

    static function getScanPaths() {
        $aScanDirs = array('api', 'lib', 'GenericmodelFramework');

        $aLoadPaths = array();
        foreach ($aScanDirs as $dir) {
            $it = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator(APP_ROOT . $dir));
            while($it->valid()) {
                if (substr($it->getPathname(), -4,4) == ".php") { 
                    $sClass = substr($it->getPathname(), strrpos($it->getPathname(), "/") + 1);
                    $sClass = substr($sClass,0, strpos($sClass, "."));
                    $aLoadPaths[$sClass] = $it->getPathname();
                }
                $it->next();
            }
        }
        return $aLoadPaths;
    }
}
?>
