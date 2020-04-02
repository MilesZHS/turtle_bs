<?php


namespace app\person\controller;


use app\common\lib\Alidayu;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Ucpaas;
use Qiniu\Auth;
use think\Controller;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Test extends Controller
{
    public function hello(){
        halt($this->user);
    }
    public function sendSms(){
//        Alidayu::getInstance()->sendSms('18560677018');
	    //初始化 $options必填
	    $code = rand(000000,999999);
	    $param = $code.",3000"; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
	    $mobile = input('param.id');
	    //填写在开发者控制台首页上的Account Sid
	    $options['accountsid']=config("yunzhixun.accountsid");
		//填写在开发者控制台首页上的Auth Token
	    $options['token']=config("yunzhixun.token");
	    $uid = "";
	    $ucpass = new Ucpaas($options);
	    $res =  $ucpass->SendSms(config("yunzhixun.appid"),config("yunzhixun.templateid"),$param,$mobile,$uid);
	    return show(1,'短信发送成功',$res,200);
    }

//    public function token(){
//        $data = IAuth::setAppLoginToken('18560677018');
//        echo $data;
//    }

//    public function save(){
//        $data = input('post.');
//        if ($data['mts']!=1){
//            throw new ApiException('您提交的数据不合法',403);
//        }
//        return show(1,'success',[],200);
//    }
//
//    public function test(){
//        echo date("Y-m-d H:i:s",'1579228289');
//    }

}