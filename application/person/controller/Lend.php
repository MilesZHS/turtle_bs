<?php


namespace app\person\controller;


use think\Exception;

class Lend extends AuthBase
{
    /**
     * 设备借出请求
     * 请求参数：
     * 1.e_id   设备id
     * 2.num    借出数量
     * 3.lend_time  借出时间
     * 4.time_of_return 归还时间
     * 5.use    用途
     * 6.c_id   社团id
     * 7.u_id   用户id
     * @return array
     */
    public function lendEquiRequest(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        $data['status'] = '2';
        $data['create_time'] = time();
        $data['id'] = uniqid(true);
        try{
            $user = model('User')->get(['id'    =>  $data['u_id']]);
            $data['lender'] = $user['name'];
            $data['lender_phone'] = $user['phone'];
            $res = model('BorrowReturn')->insert($data);
            if (empty($res)){
                return show(0,'error',[],400);
            }
            $result = model('BorrowReturn')->get(['u_id'    =>  $data['u_id']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'请求成功，请耐心等待管理员审核',$result,200);
    }

}