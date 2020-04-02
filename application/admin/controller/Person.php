<?php


namespace app\admin\controller;


use think\Controller;

class Person extends Base
{
    public function delete(){
        return $this->fetch();
    }

}