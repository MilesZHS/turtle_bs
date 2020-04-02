<?php


namespace app\common\model;


use think\Model;

class AdminUser extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * 增加管理员用户
     */
    public function add($data){
        if (!is_array($data)){
            exception('传递数据不合法');
        }else{
            return $this->allowField(false)->save($data);
//            return $this->id;
        }

    }

}