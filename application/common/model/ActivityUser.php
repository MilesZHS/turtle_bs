<?php


namespace app\common\model;


use think\Exception;

class ActivityUser extends Base
{
    public function getActList($u_id){
        try{
            $array = $this->where(['u_id'    =>  $u_id])->select();
        }catch (Exception $e){
            return $e->getMessage();
        }
        return $array;
    }

}