<?php


namespace app\club\controller;


use app\common\lib\IAuth;
use think\Controller;
use think\Exception;

class Login extends Controller
{
    public function login(){
        //判断方法是否为POST请求
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        //获取提交的数据
        $data = input('post.');
        $phone = $data['phone'];
        $data['password'] = md5($data['password']);
        try{
            $club = model('Club')->get(['phone'     =>  $data['phone']]);//判断手机号是否存在
            if (!$club){
                return show(0,'该用户不存在，请先注册',[],404);
            }
            if ($data['password'] != $club['password']){
                return show(0,'密码错误',[],400);
            }else{
                //设置token以及过期时间
                $data = [
                    'token'     =>  IAuth::setAppLoginToken($data['phone']),
                    'time_out'  =>  strtotime("+1 days")
                ];
                model('Club')->where(['phone'   =>  $phone])->update($data);//token更新到数据库

                //准备更新后的社团信息
                $token = $data['token'];
                $res = [
                    'id'        =>  $club['club_id'],
                    'name'      =>  $club['club_name'],
                    'leader'    =>  $club['club_leader'],
                    'school'    =>  $club['school'],
                    'department'=>  $club['department'],
                    'phone'     =>  $club['phone'],
                    'email'     =>  $club['email'],
                    'qualification'=>$club['qualification'],
                    'create_time'=> $club['create_time'],
                    'number'    =>  $club['number'],
                    'type'      =>  $club['type'],
                    'token'     =>  $token,
                    'logo_path' =>  $club['logo_path']
                ];
            }
            //登录成功，将社团信息返回给客户端
            return show(1,'登录成功',$res,200);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
    }

    public function logout(){
        session(null,config('club.session_user_scope'));
        return show(1,'退出成功',[],200);
    }
}