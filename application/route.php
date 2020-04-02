<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post('identifycode','person/identify/save');
Route::post('person/login','person/login/index');
Route::post('person/register','person/register/save');
Route::post('person/getinfo','person/info/getinfo');
Route::post('person/update','person/info/update');
//获取社团详情
Route::get('person/getclub','person/club/getclub');
//获取社团列表
Route::get('person/getclublist','person/club/getlist');
//提交借出设备申请
Route::post('person/lendequireq','person/lend/lendequirequest');
//退出登录
Route::post('person/logout','person/login/logout');
//判断登录是否过期
Route::post('person/checklogin','person/login/checklogin');
//获取活动列表
Route::get('person/getactlist','person/activity/getlist');
//通过社团类型获取社团名称
Route::get('person/getclubnamebytype','person/club/getclubnamebytype');
//获取七牛云上传token
Route::post('person/getuploadtoken','person/info/gettoken');
//获取用户参加的社团
Route::get('person/getclublistbyuser','person/club/getclublistbyuser');
//获取个人参加的活动列表
Route::post('person/getuseractlist','person/activity/getuseractlist');
//获取图片上传token
Route::post('person/getuploadtoken','person/info/gettoken');
//更新用户信息
Route::post('person/updateinfo','person/info/update');
Route::get('person/getAct','person/activity/getAct');

Route::post('club/register','club/register/save');
Route::post('club/login','club/login/login');
Route::post('club/update','club/info/update');
Route::get('club/logout','club/login/logout');
Route::post('club/uploadtoken','club/uploadfile/gettoken');
Route::post('club/getinfo','club/info/getinfo');
Route::post('club/getmemberlist','club/info/getmemberlist');
Route::post('club/getbycondition','club/info/getbycondition');
Route::post('club/deletemember','club/info/deletemember');
Route::post('club/updatemember','club/info/updatemember');
//添加设备记录
Route::post('club/addequi','club/equipment/addequi');
//查询设备列表
Route::get('club/getequilist','club/equipment/getlist');
//按条件查询设备
Route::post('club/getequibycondition','club/equipment/getequibycondition');
//删除设备
Route::post('club/deleteequi','club/equipment/deleteequi');
//更新设备信息
Route::post('club/updateequi','club/equipment/updateequi');
//返回借出列表
Route::post('club/getrecord','club/Record/getlist');
//按条件查询借出记录
Route::post('club/getrecbyconf','club/Record/getlistbycondition');
//修改设备借出记录
Route::post('club/updaterec','club/record/updaterec');
//活动发布
Route::post('club/addactivity','club/activity/addactivity');
//发送短信验证码测试
//Route::post('sendidentifycode','person/test/sendsms');
