<?php


namespace app\common\model;



use traits\model\SoftDelete;

class Equipment extends Base
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}