<?php


namespace app\person\controller;


use app\common\lib\exception\ApiException;
use app\common\model\User;
use Composer\DependencyResolver\Request;
use think\Controller;

/**
 * Class AuthBase
 * 客户端auth 登录权限基础类库
 * 1.每个接口（需要登录 个人中心、点赞、评论）
 * 2.判定access_user_token合法性
 * 3.用户信息-》user
 * @package app\person\controller
 */
class AuthBase extends Controller
{
    public $user = [];
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        parent::_initialize();
        if (empty($this->isLogin())){
            throw new ApiException('您没有登录',401);
        }
    }

    /**
     * 判断是否登录
     */
    public function isLogin(){
        $token = \think\Request::instance()->header('token');
        if (empty($token)){
            return false;
        }
        $user = User::get(['token'  =>  $token]);
        if (time() > $user['time_out']){
            return false;
        }
        $this->user = $user;
        return true;
    }

}


