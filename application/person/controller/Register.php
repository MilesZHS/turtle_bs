<?php


namespace app\person\controller;


use app\common\lib\Alidayu;
use think\Controller;
use think\Exception;

class Register extends Controller
{
    /**
     * @return array
     * 个人用户注册
     */
    public function save(){
        if (!request()->isPost()){
            return show(0,'您的请求不合法',[],403);
        }
        $data = input('post.');
        try{
            $user = model('User')->get(['phone' =>  $data['phone']]);//判断用户是否存在
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if($user){
            return show(0,'用户已存在，请直接登录',[],403);
        }
        //判断验证码是否有效
        if ($data['code'] != Alidayu::getInstance()->checkSmsIdentify($data['phone'])){
            return show(0,'验证码错误',[],400);
        }
        unset($data['code']);
        //生成一个唯一用户id
        $data['id'] = uniqid(true);
        $data['password'] = md5($data['password']);
        //自动生成用户名
        if ($data['username'] == ''){
            $data['username'] = 'turtle'.$data['phone'];
        }
        try{
            //向数据库里添加记录
            $res = model('User')->add($data);
        }catch (Exception $e){
            return show(0,'注册失败',$e->getMessage(),400);
        }
        if (!$res){
            return show(0,'注册失败',[],400);
        }
        return show(1,'注册成功',[],200);
    }

}