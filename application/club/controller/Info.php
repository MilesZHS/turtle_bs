<?php


namespace app\club\controller;



use app\common\model\Member;
use app\common\model\User;
use think\Db;
use think\Exception;
use think\Request;

class Info extends AuthBase
{
    public function update(){
        if(!request()->isPost()){
            return show(0,'请求错误',[],403);
        }
        $data = input('post.');
        if (!empty($data['banner'])){
            $photo = [
                'c_id'  =>  $data['c_id'],
                'path'  =>  $data['banner'],
                'purpose'   =>  'banner_' . $data['c_id']
            ];
            try{
                $ph = model('Photo')->get(['purpose'  =>  'banner_' . $data['c_id']]);
                if (empty($ph)){
                    model('Photo')->isUpdate(false)->insert($photo);
                }else{
                    model('Photo')->where(['purpose'    =>  'banner_' . $data['c_id']])->update($photo);
                }
            }catch (Exception $e)
            {
                return show(0,'error',$e->getMessage(),400);
            }
        }

        unset($data['banner'],$data['c_id']);
        try{
            model('Club')->where(['phone'   =>  $data['phone']])->update($data);
            $res = model('Club')->get(['phone'  =>  $data['phone']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'更新成功',$res,200);
    }

    public function getInfo(){
        if(!request()->isPost()){
            return show(0,'请求错误',[],403);
        }
        $token = Request::instance()->header('token');
//        $token = \think\Request::instance()->header('token');
        try{
            $club = model('Club')->get(['token' =>  $token]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        unset($club['password']);
        unset($club['token']);
        return show(1,'信息返回成功',$club,200);
    }

    /**
     * 获取成员列表
     */
    public function getMemberList(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $token = Request::instance()->header('token');
        try{
            $list = Db::query('select * from club,member,user where club.club_id = member.c_id 
                and user.id = member.u_id 
                and club.token = ? and member.delete_time is null',[$token]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'success',$list,200);
    }

    /**
     * 按条件查询
     */
    public function getByCondition(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        $token = Request::instance()->header('token');
        $num = $data['flag'];
        try{
            switch ($num){
                case '1'://按姓名查找
                    $res = Db::query(
                        'select * from club,member,user where club.club_id = member.c_id 
                                    and user.id = member.u_id 
                                    and club.token = ? 
                                    and user.name = ? 
                                    and member.delete_time is null',
                        [$token,$data['condition']]);
                    break;
                case '2'://按手机号查询
                    $res = Db::query(
                        'select * from club,member,user where club.club_id = member.c_id and user.id = member.u_id and club.token = ? and user.phone = ? and member.delete_time is null',
                        [$token,$data['condition']]);
                    break;
                case '3'://按学校查找
                    $res = Db::query(
                        'select * from club,member,user where club.club_id = member.c_id and user.id = member.u_id and club.token = ? and user.school = ? and member.delete_time is null',
                        [$token,$data['condition']]);
                    break;
                default:
                    $res = Db::query(
                        'select * from club,member,user where club.club_id = member.c_id and user.id = member.u_id and club.token = ? and member.delete_time is null',
                        [$token]);
                    break;
            }
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if ($res){
            return show(1,'查询成功',$res,200);
        }
        return show(0,'未找到相关记录',[],400);
    }
    public function deleteMember(){
        if (!\request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            $user = Member::get(['u_id'   =>  $data['id']]);
            $user->delete();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'删除成功',[],200);
    }

    public function updateMember(){
        if(!\request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            model('User')->where(['id'  =>  $data['id']])->update($data);
            $user = model('User')->get(['id'    =>  $data['id']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'信息修改成功',$user,200);
    }
}