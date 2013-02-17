<?php
class IDGenerator
{
    static function getNewId()
    {
        $randString = time().rand(). IDGenerator::getRandomString(20) .time().rand();
        $id = md5($randString);
        return $id;
    }
    private static function getRandomString($length) {
        $str = "abcdefghijklmnopqrstuvwxyz1234567890-=+_!@#$%^&*<>?:;,.\/'\""; 
        $size = strlen($str);
        $ret = "";
        for( $i = 0; $i < $length; $i++ ) {
            $ret .= $str[rand(0, $size - 1 )];
        }
        return $ret;
    }
}
?>
