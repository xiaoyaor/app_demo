<?php

namespace app\api\model\addins;

use think\Model;


class Category extends Model
{

    

    

    // 表名
    protected $name = 'addins_category';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public static function onAfterInsert($row)
    {
        $pk = $row->getPk();
        $row::where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
    }

    







}
