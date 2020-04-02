<?php


namespace app\person\controller;


use app\common\model\ActivityUser;
use think\Db;
use think\Exception;

class Activity extends AuthBase
{
    /**
     * 获取活动详情
     */
    public function getAct()
    {
        //获取前端提交的数据（GET）
        $data = input('get.');
        try {
            $act = model('Activity')->get(['id' => $data['act_id']]);
        } catch (Exception $e) {
            return show(0, 'error', $e->getMessage(), 400);
        }
        if (!empty($act)) {
            return show(1, '信息返回成功', $act, 200);
        }
        return show(0, '返回失败', [], 404);
    }

    /**
     * @return array
     * 获取所有活动列表
     */
    public function getList(){
        try{
            $list = model('Activity')->select();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        try{
            foreach ($list as $item){
                //把时间戳格式化
                $item['time'] = date('Y-m-d h:m',$item['time']);
                //获取该活动的组织者（社团）的相关信息
                $item['club'] = model('Club')->where(['club_id'    =>  $item['c_id']])->column('club_name');
                $item['club'] = $item['club'][0];
                //获取照片路径
                $item['photo_path'] = model('Photo')->where(['a_id'  =>  $item['id']])->column('path');
//                $item['photo_path'] = $item['photo_path'][0];
            }
        }catch (Exception $e){
            return show(0,'图片查找失败',$e->getMessage(),400);
        }
        if (!empty($list)){
            return show(1,'信息返回成功',$list,200);
        }
        return show(0,'信息返回失败',[],400);
    }

    /**
     * 获取个人参与的活动的列表
     */
    public function getUserActList(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $u_id = input('post.u_id');
        $array = model('ActivityUser')->getActList($u_id);
        if (is_array($array) && !empty($array)){
            for ($i = 0 ; $i < sizeof($array) ; $i++ ){
                try{
                    $res[$i] = Db::query('SELECT club.club_name,photo.path,activity.name,activity.time,activity.`status`,activity.area,activity.`desc` 
                        FROM club,photo,activity 
                        WHERE club.club_id = \''.$array[$i]['c_id'].'\' 
                        AND photo.purpose = \''.$array[$i]['a_id'].'\' 
                        AND activity.id = \''.$array[$i]['a_id'].'\'');
//                    $res[$i]['time'] = date('Y-m-d h:m:s',$res[$i]['time']);
                }catch (Exception $e){
                    return show(0,'error-sql',$e->getMessage(),400);
                }
            }
//            var_dump($res);
            return show(1,'success',$res,200);
        }
        return show(0,'error',$array,400);
    }
}