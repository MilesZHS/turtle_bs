<?php


namespace app\club\controller;


use Qiniu\Auth;
use think\Controller;
use think\Request;

class Uploadfile extends Controller
{
    public function getToken(){
        if (!request()->isPost()){
            return show(0,'请求错误',[],403);
        }
        $data = Request::instance()->header('token');
        if (empty($data)){
            return show(0,'缺少请求条件',[],403);
        }
        $accessKey = 'X6KlJ0aACNyzYNeiuZcVAYIIjVO8yS8T-f8_CWLX';
        $secretKey = 'oKtIafO9xkY0iMZ9ofR50Gwr4JyqJydzyRESwtvj';
        $auth = new Auth($accessKey,$secretKey);
        $bucket = 'turtle-qdu';
        $token['token'] = $auth->uploadToken($bucket);
        return show(1,'请求成功',$token['token'],200);
    }

}