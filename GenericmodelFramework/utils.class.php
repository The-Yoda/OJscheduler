<?php
class Utils {

    public static function sanitize($sValue, $bHyphens = TRUE) {
        $sValue = preg_replace('/[^a-zA-Z0-9\$_-]/', '', $sValue);

        $sValue = ucwords(
                strtolower(
                        str_replace(array('-', '_'), ' ', $sValue)
                )
        );
        $sReplaceChar = $bHyphens ? "-" : "";
        return str_replace(' ', $sReplaceChar, $sValue);
    }

    public static function sanitizeArray($array, $bHyphens = TRUE) {

        if (empty($array)) return array();

        $aSanitized = array();
        array_walk($array,
                function($sValue, $sKey) use (&$aSanitized, $bHyphens)
                {
                    $aSanitized[Utils::sanitize($sKey, $bHyphens)] = $sValue;
                });
                return $aSanitized;
    }

    public static function array_flatten($array = array()) {

        if (empty($array)) return array();

        $aFlattened = array();
        array_walk_recursive($array,
                function($sValue, $sKey) use (&$aFlattened)
                {
                    $aFlattened[$sKey] = $sValue;
                });
                return $aFlattened;
    }

    public static function getFullUrl(array $aServer) {
        $sProtocol = empty($aServer['HTTPS']) ? "http" : "https";
        return $sProtocol . "://" . $aServer['HTTP_HOST']
        //                    . $aServer["SERVER_PORT"]
        . $aServer['REQUEST_URI'];

    }

    public static function runCurl($url, $method = 'GET', $postvals = null){
        $ch = curl_init($url);

        //GET request: send headers and return data transfer
        if ($method == 'GET'){
            $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSLVERSION => 3
            );
            curl_setopt_array($ch, $options);
            //POST / PUT request: send post object and return data transfer
        } else {
            $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => 1,
                    CURLOPT_VERBOSE => 1,
                    CURLOPT_POSTFIELDS => $postvals,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSLVERSION => 3
            );
            curl_setopt_array($ch, $options);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
