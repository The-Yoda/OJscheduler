<?php
class ResponseManager {
    public static function send(GenModel $response, GenModel $request = null) {
        http_response_code(self::getCode($response->getStatusCode()));
        self::setHeaders($response , $request);
        self::setCookies($response);
        self::setCookies($request);
        echo $response->getContent();
    }

    public static function sendException(Exception $ge) {

        if ($ge instanceof GenericException) {
            http_response_code(self::getCode($ge->getHttpCode()));
        } else {
            http_response_code(500);
        }
        echo $ge->getMessage();
    }


    private static function setCookies($request){
        if($request->hasCookie())
            foreach($request->getCookie()->asArray() as $cookie_name => $cookie_value){
                 if($cookie_name != "sessionid")
                 setcookie($cookie_name, $cookie_value, time() + 3600, "/");
            }
    }

    private static function setHeaders($response , $request =  null){
        $aHeaders = Utils::array_flatten($response->getHeader());
        $sContentType = $response->getContentType();
        $aHeaders["content-type"] = !empty($sContentType) ? $sContentType : "application/json";

        foreach ($aHeaders as $header => $value) {
            header(self::getHeaderAsString($header, $value));
        }
    }

    private static function getCode($iCode = 200) {
        return !empty($iCode) ? intval($iCode) : 200;
    }

    private static function getHeaderAsString($sHeader, $sValue) {
        $sHeader = Utils::sanitize($sHeader);
        return "{$sHeader}: {$sValue}";
    }
}
?>