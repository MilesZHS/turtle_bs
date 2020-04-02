<?php


namespace app\person\controller;


use Qiniu\Auth;
use think\Exception;
use think\Request;

class Info extends AuthBase
{
    /**
     * @return array
     * 获取用户信息
     */
    public function getInfo(){
        //判断请求是否为post
        if(!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        //获取报头里的token
        $token = Request::instance()->header('token');
        try{
            //根据token查找用户
            $user = model('User')->get(['token'     =>  $token]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        //删除用户数组中不能给前端返回的数据
        unset($user['password']);
        unset($user['token']);
        //将用户数据返回给前端，show（状态码，提示信息，返回数据，http状态码）
        return show(1,'信息获取成功',$user,200);
        /**
         * 状态码： 0失败 1成功
         * http状态码：400 请求失败 403 请求被拒绝   404 not found
         * 返回数据为数组
         */

    }

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


    /**
     * 更新用户信息
     */
    public function update(){
        if (!\request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            model('User')->where(['id'  =>  $data['id']])->update($data);
            $res = model('User')->get(['id'     =>  $data['id']]);
            unset($res['password']);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'更新成功！',$res,200);
    }

}
