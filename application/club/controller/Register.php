<?php


namespace app\club\controller;


use think\Controller;
use think\Exception;

class Register extends Controller
{
    /**
     * 社团注册
     * @return array
     */
    public function save(){
        if(!request()->isPost()){//判断请求是否合法
            return show(0,'您的请求不合法',[],403);
        }
        $data = input('post.');
        $phone = $data['phone'];
        try{
            $club = model('Club')->get(['phone' =>  $phone]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (!empty($club)){
            return show(0,'用户已存在，请直接登录',[],403);
        }
        //生成一个社团id
        $data['club_id'] = uniqid(true);
        $data['password'] = md5($data['password']);
        try{
            //向数据库中添加记录
            $club = model('Club')->save($data);
        }catch (Exception $e){
            return show(0,'新用户创建失败',$e->getMessage(),400);
        }
        if (!$club){
            return show(0,'新用户创建失败',[],400);
        }
        return show(1,'注册成功',$club,200);
    }

}