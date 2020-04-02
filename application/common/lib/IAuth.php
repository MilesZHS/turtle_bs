<?php


namespace app\common\lib;


class IAuth
{
    public static function setPassword($data){
        return md5($data.config('app.password_pre_halt'));
    }

    public static function setAppLoginToken($phone = ''){
        $str = md5(uniqid(md5(microtime(true),true)));
        $str = sha1($str.$phone);
        return $str;
    }
}