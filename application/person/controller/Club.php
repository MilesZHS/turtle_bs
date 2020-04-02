<?php


namespace app\person\controller;


use think\Exception;

class Club extends AuthBase
{
    public function getList(){
        try{
            $clubList = model('Club')->column([
                'club_id',
                'club_name',
                'school',
                'department',
                'number',
                'type',
                'logo_path',
                'level'
            ]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        try{
            $banner = model('Photo')->where('purpose','like','banner_%')->column('path');
            if (empty($banner)){
                return show(0,'banner is none',[],400);
            }
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
//        var_dump($clubList[0]);
//        foreach ($clubList as $item){
//            static $i = 0;
//            $photoList[$i] = $item['logo_path'];
//            $i ++;
//        }
        $res = [
            'info'  =>  $clubList,
//            'photo' =>  $photoList,
            'banner'    => $banner
        ];
        return show(1,'查询成功',$res,200);
    }
    public function getClubNameByType(){
        $data = input('get.');
        try{
            $list = model('Club')->field('club_name,club_id')->where(['type'    =>  $data['type']])->select();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (!empty($list)){
            return show(1,'查询成功',$list,200);
        }
        return show(0,'查询失败',[],400);
    }

    /**
     * 通过用户信息查找社团
     * 查找用户参加的社团
     */
    public function getClubListByUser(){
        if (!request()->isGet()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('get.');
        $res = array();
        try{
            $array = model('Member')->where('u_id',$data['u_id'])->column('c_id');
            foreach ($array as $item){
                $info = model('Club')->where('club_id',$item)->select();
                $info = $info[0];
                $info['banner'] = model('Photo')->where('c_id',$item)->select();
                $info['banner'] = $info['banner'][0]['path'];
                array_push($res,$info);
            }
        }catch (Exception $e){
            return show(0,'error1',$e->getMessage(),400);
        }
//
//        try{
//            for ($i = 0 ; $i < sizeof($array) ; $i++){
//                $array[$i]['banner'] = model('Photo')->where(['c_id'    =>  $array[$i]['c_id']])->select();
//                $array[$i]['banner'] = $array[$i]['banner'][0]['path'];
//            }
//        }catch (Exception $e){
//            return show(0,'error2',$e->getMessage(),400);
//        }
        return show(1,'查询成功',$res,200);
    }

    /**
     *获取社团详情
     * club_id
     *
     */
    public function getClub(){
        //获取前端提交的数据(GET)
        $data = input('get.');
        try{
            $club = model('Club')->get(['club_id'   =>  $data['club_id']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (!empty($club)){
            return show(1,'信息返回成功',$club,200);
        }
        return show(0,'返回失败',[],404);
    }
}