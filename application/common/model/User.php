<?php


namespace app\common\model;



use traits\model\SoftDelete;

class User extends Base
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

}