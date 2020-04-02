<?php


namespace app\person\controller;


use app\common\lib\Alidayu;
use app\common\lib\Ucpaas;
use think\Cache;
use think\Controller;

class Identify extends Controller
{
    /**
     * 设置短信验证码
     */
    public function save(){
        if (!request()->isPost()){
            return show(0,'请求方式错误',[],403);
        }

        //检验数据  判断手机号是否合法
        $validate = validate('Identify');
        if (!$validate->check(input('post.'))){
            return show(0,$validate->getError(),[],403);
        }

        //获取验证码--云之讯
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
	    $res = json_decode($res);
	    if($res->code == '000000'){
	    	$flag = Cache::set('login_'.$mobile,$code,config("yunzhixun.identify_time")*60);
	    	if(!$flag){
			    return show(0,'验证码发送失败-01');//缓存写入失败
		    }
		    return show(1,'验证码发送成功');
	    }else{
		    return show(0,'验证码发送失败-00');
	    }

	    //获取验证码--阿里
//        //获取手机号
//        $id = input('param.id');
//        //发送随机验证码
//        if (Alidayu::getInstance()->sendSms($id)){
//            return show(1,'ok');
//        }else{
//            return show(0,'验证码发送失败');
//        }
    }

}