<?php


namespace app\club\controller;


use think\Db;
use think\Exception;

class Record extends AuthBase
{
    /**
     * 获取借还记录
     * 请求参数：
     * 1.c_id
     * 返回参数：
     * name           设备名称
     * lender         借出人
     * lender_phone   借出人手机号
     * lender_time    借出时间
     * num            数量
     * use            用途
     * time_of_return 归还时间
     * administrator  管理员
     * status         状态
     * @return array
     */
    public function getList(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            $res = model('BorrowReturn')->where(['c_id'   =>  $data['c_id']])->select();
//            $res = Db::name('borrow_return')
//                ->alias('br')
//                ->join('equipment eq','eq.c_id = br.c_id')
//                ->field('eq.name,eq.administrator,br.lender,br.lender_phone,'.
//                'br.lender_time,br.num,br.use,br.time_of_return,br.status')
//                ->where(['br.c_id' =>  $data['c_id']])
//                ->select();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (empty($res)){
            return show(0,'查询失败',[],400);
        }
        for($i = 0 ; $i < sizeof($res) ; $i++){
            $res[$i]['lender_time'] = (string)date( "Y-m-d H:i:s", $res[$i]['lender_time']/1000);
            $res[$i]['time_of_return'] = (string)date( "Y-m-d H:i:s", $res[$i]['time_of_return']/1000);
        }
        return show(1,'查询成功',$res,200);
    }

    public function getListByCondition(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            switch ($data['flag']){
                case '0':   //设备名称
                    $res = model('BorrowReturn')->where([
                        'name' =>  $data['condition'],
                        'c_id'  =>  $data['c_id']
                    ])->select();
                    break;
                case '1':   //借出人
                    $res = model('BorrowReturn')->where([
                        'lender' =>  $data['condition'],
                        'c_id'  =>  $data['c_id']
                    ])->select();
                    break;
                case '2':   //状态
                    $res = model('BorrowReturn')->where([
                        'status' =>  $data['condition'],
                        'c_id'  =>  $data['c_id']
                    ])->select();
                    break;
                case '3':   //全部
                    $res = model('BorrowReturn')->where(['c_id'  =>  $data['c_id']])->select();
                    break;
            }
            if (empty($res)){
                return show(0,'记录不存在',[],400);
            }
            for($i = 0 ; $i < sizeof($res) ; $i++){
                $res[$i]['lender_time'] = date( "Y-m-d H:i:s", $res[$i]['lender_time']/1000);
                $res[$i]['time_of_return'] = date( "Y-m-d H:i:s", $res[$i]['time_of_return']/1000);
            }
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'查询成功',$res,200);
    }

    /**
     * 更新借出记录
     */
    public function updateRec(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            $res = model('BorrowReturn')->where(['id'  =>  $data['id']])->update($data);
//            if (empty($res)){
//                return show(0,'更新失败',[],400);
//            }
            $result = model('BorrowReturn')->where(['id'    =>  $data['id']])->select();
        }catch (Exception $e){
            return show(0,'更新失败',$e->getMessage(),403);
        }
        return show(1,'更新成功',$result,200);
    }



}