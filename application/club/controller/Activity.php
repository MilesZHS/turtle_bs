<?php


namespace app\club\controller;


use think\Exception;

class Activity extends AuthBase
{
    public function addActivity(){
        if (!request()->isPost()){
            return show(0,'请求不合法',[],403);
        }
        $data = input('post.');
        $data['id'] = 'act-'.uniqid(true);
        $data['time'] = strtotime($data['time']);
        $data['photo_id'] = $data['id'];
        try{
            if (!empty($data['path'])){
                for ($i = 0 ; $i < sizeof($data['path']) ; $i++){
                    $arr = [
                        'path'  =>  $data['path'][$i],
                        'c_id'  =>  $data['c_id'],
                        'purpose'=> $data['photo_id'],
                        'a_id'  =>  $data['photo_id']
                    ];
                    try{
                        $res = model('Photo')->isUpdate(false)->save($arr);
                    }catch (Exception $e){
                        return show(0,'图片链接上传失败',$e->getMessage(),400);
                    }
                    if (!$res){
                        return show(0,'图片链接上传失败',[],400);
                    }
                }
            }
            $id = model('Activity')->add($data);
            $act = model('Activity')->get(['id'     =>  $data['id']]);
        }catch (Exception $e){
            return show(0,'发布失败',$e->getMessage(),400);
        }
        unset($act['id']);
        $act['time'] = date( "Y-m-d H:i:s", $act['time']);
        if (empty($id)){
            return show(0,'发布失败',[],400);
        }
        return show(1,'发布成功',$act,200);
    }

}