<?php


namespace app\admin\controller;


use think\Controller;
use think\Exception;

class Admin extends Controller
{
    public function add(){
        //判断是否为post提交方式
        if(request()->isPost()){
            $data = input('post.');
            $validate = validate('AdminUser');
            if (!$validate->check($data)){
                $this->error($validate->getError());
            }
            $data['password'] = md5($data['password']);
            $data['status'] = 1;

            try{
                $id = model('AdminUser')->add($data);
            }catch (Exception $e){
                $this->error($e->getMessage());
            }

            if ($id){
                $this->success('id='.$id.'新建成功');
            }else{
                $this->error('error');
            }
        }
        return $this->fetch();
    }
}