<?php


namespace app\person\controller;


use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use think\Cache;
use think\Controller;
use think\Exception;
use think\Request;

class Login extends Controller
{
    /**
     * 判断用户登录是否过期
     */
    public function checkLogin(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $token = Request::instance()->header('token');
        try{
            $time = model('User')->where(['token'   =>  $token])->column('time_out');
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),403);
        }
        $now = time();
        if ( $now >= $time[0]){
            return show(0,'登录过期',[],400);
        }
        try{
            $res = model('User')->get(['token'  =>  $token]);
            if (!$res){
                return show(0,'用户不存在',[],404);
            }
            return show(1,'登录有效',$res,200);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),404);
        }
        return show(1,'登录有效',[],200);
    }

    /**
     * @return array
     * @throws \think\exception\DbException
     * 个人客户端登录
     */
    public function index(){
        if (!request()->isPost()){
           return show(0,'请求不合法',[],400);
        }
        $param = input('post.');
        if(empty($param['phone'])){
            return show(0,'手机号不能为空',[],400);
        }
        if (empty($param['code'])){
            return show(0,'验证码不合法',[],400);
        }

//        //严格校验 到Alidayu检验验证码是否正确
//        $code = Alidayu::getInstance()->checkSmsIdentify($param['phone']);
//        if ($code != $param['code']){
//            return show(0,'验证码错误','',400);
//        }
	    //判断验证码是否正确
	    $code = Cache::get('login_'.$param['phone']);
        if ($code != $param['code']){
	        return show(0,'验证码错误','',400);
        }else {
        	Cache::rm('login_'.$param['phone']);
        }

        //设置token
        $data = [
            'token'     =>  IAuth::setAppLoginToken($param['phone']),
            'time_out'  =>  strtotime("+".config('app.login_time_out_day')." days"),
        ];
        $token = $data['token'];

        //获取用户信息，判读用户是否存在
        try{
            $user = model('User')->get(['phone'    =>  $param['phone']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),404);
        }
        if (!$user){
            //第一次登录 注册数据  手机号+验证码快速登录注册
            $data = [
                'token'     =>  IAuth::setAppLoginToken($param['phone']),
                'time_out'  =>  strtotime("+".config('app.login_time_out_day')." days"),
                'id'        =>  uniqid(true),
                'username'  =>  'turtle'.$param['phone'],
                'status'    =>  1,
                'phone'     =>  $param['phone']
            ];
            try{
                //向数据库里添加记录
                $user = model('User')->add($data);
                if (!$user){
                    return show(0,'新用户添加失败',[],404);
                }
            }catch (Exception $e){
                return show(0,'新用户添加失败',$e->getMessage(),404);
            }
            try{
                //返回用户信息
                $res = model('User')->get(['phone'  =>  $param['phone']]);
                if (!$res){
                    return show(0,'用户不存在',[],404);
                }
                return show(1,'登录成功',$res,200);
            }catch (Exception $e){
                return show(0,'error',$e->getMessage(),404);
            }
        }else{
            //当用户已存在，更新token
            $id = model('User')->save($data,['phone'    =>  $param['phone']]);
            if ($id){
                //获取最新用户记录，准备返回给个人客户端
                $res = model('User')->get(['token'  =>  $token]);
                return show(1,'登录成功',$res,200);
            }
            return show(0,'登录失败',[],404);
        }
    }

    public function logout(){
        $phone = input('post.phone');
        try{
            model('User')->where(['phone'   =>  $phone])->update(['token'   =>  '']);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'退出成功',[],200);
    }

}