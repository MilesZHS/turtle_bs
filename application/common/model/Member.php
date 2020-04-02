<?php


namespace app\common\model;


use traits\model\SoftDelete;

class Member extends Base
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getClubList($u_id){
        $array =  $this->where('u_id',$u_id)->column('c_id');
//        for ($i = 0 ; $i < sizeof($array) ; $i++){
//            $array[$i]['banner'] = model('Photo')
//                ->where(['c_id' =>  $array[$i]['c_id']],['purpose','like','banner%'])
//                ->find();
//        }
        return $array;
    }

}