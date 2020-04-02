<?php


namespace app\club\controller;

use think\Exception;
use think\Request;

class Equipment extends AuthBase
{
    /**
     * 添加设备
     * @return array
     */
    public function addEqui(){
        if(!request()->isPost()){
            return show(0,'您的请求不合法',[],403);
        }
        $data = input('post.');
        $id = $data['c_id'];
        try{
            //判断社团是否存在
            $club = model('Club')->where(['club_id' =>  $id])->column('club_name');
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        $data['c_name'] = $club;
        $data['id'] = uniqid(true);  //设备唯一标识符e_id
        $path = $data['path'];
        unset($data['path']);
        $purpose = '111--'.$data['id'];
        $data['show_purpose'] = $purpose;

        /**
         * 上传图片地址
         */
        for ($i = 0 ; $i < sizeof($path) ; $i++){
            $arr = [
                'e_id'    =>  $data['id'],
                'c_id'    =>    $data['c_id'],
                'path'    =>    $path[$i],
                'purpose' =>    $purpose
            ];
            try{
                //将照片路径存入数据库
                $res = model('Photo')->isUpdate(false)->save($arr);
            }catch (Exception $e){
                return show(0,'图片链接上传失败',$e->getMessage(),400);
            }
            if (!$res){
                return show(0,'图片链接上传失败',[],400);
            }
            //可行解决方案
//                $res = Db::execute('INSERT INTO photo (path,e_id,c_id,purpose) values (?,?,?,?)',[$path[$i],$data['id'],$data['c_id'],$purpose]);
        }
        try{
            //向数据库中插入记录
            $equi = model('Equipment')->add($data);

        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (!$equi){
            return show(0,'新建失败',[],400);
        }
        return show(1,'登记成功',$equi,200);
    }

    /**
     * 获取设备列表
     * @return array
     */
    public function getList(){
        if(!request()->isGet()){
            return show(0,'请求不合法',[],403);
        }
        $c_id = input('get.c_id');
        try{
            $list = model('Equipment')->where(['c_id'   =>  $c_id])->select();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if(!$list){
            return show(0,'记录不存在',[],400);
        }
        return show(1,'查询成功',$list,200);
    }

    /**
     * 按条件搜索设备
     */
    public function getEquiByCondition(){
        if(!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
//        $token = Request::instance()->header('token');
        $data = input('post.');
        try{
            switch ($data['flag']){
                case '1':   //按设备编号查找
                    $list = model('Equipment')->where([
                        'c_id'  =>  $data['c_id'],
                        'id'    =>  $data['condition']
                    ])->select();
                    break;
                case '2':   //按设备名称查找
                    $list = model('Equipment')->where([
                        'c_id'  =>  $data['c_id'],
                        'name'  => $data['condition']
                    ])->select();
                    break;
                case '3':   //按管理员查找
                    $list = model('Equipment')->where([
                        'c_id'  =>  $data['c_id'],
                        'administrator'    =>  $data['condition']
                    ])->select();
                    break;
                case '4':   //按设备状态查找
                    $list = model('Equipment')->where([
                        'c_id'  =>  $data['c_id'],
                        'status'    =>  $data['condition']
                    ])->select();
                    break;
                case '5':   //查找全部
                    $list = model('Equipment')->where([
                        'c_id'  =>  $data['c_id']
                    ])->select();
                    break;
            }
        }catch (Exception $e){
            return show(0,'查询失败',$e->getMessage(),400);
        }

        if(empty($list)){
            return show(0,'记录不存在',[],400);
        }
        return show(1,'记录查询成功',$list,200);
    }

    /**
     * 删除设备
     */
    public function deleteEqui(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            $equi = model('Equipment')->get(['id'  =>  $data['id']]);
            //软删除 并不真正删除数据库中的数据，向'delete_time'属性插入时间戳，数据被隐藏不返回
            $equi->delete();
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        return show(1,'删除成功',[],200);
    }

    /**
     * @return array
     * 编辑设备
     */
    public function updateEqui(){
        if (!\request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        try{
            model('Equipment')->where(['id' =>  $data['id']])->update($data);
            $res = model('Equipment')->get(['id'    =>  $data['id']]);
        }catch (Exception $e){
            return show(0,'error',$e->getMessage(),400);
        }
        if (empty($res)){
            return show(0,'更新失败',[],400);
        }

        unset($res['update_time']);
        unset($res['c_id']);
        unset($res['c_name']);
        unset($res['show_purpose']);
        return show(1,'更新成功',$res,200);
    }
}