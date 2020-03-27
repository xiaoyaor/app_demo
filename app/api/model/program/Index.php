<?php

namespace app\api\model\program;

use think\Model;


class Index extends Model
{

    

    

    // 表名
    protected $name = 'program';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'releasetime_text'
    ];
    

    



    public function getReleasetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['releasetime']) ? $data['releasetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setReleasetimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function programcategory()
    {
        return $this->belongsTo('app\admin\model\program\Category', 'category_id', 'id');
    }
}
