<?php

namespace app\api\model\addins;

use think\Model;


class Index extends Model
{

    

    

    // 表名
    protected $name = 'addins';
    
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


    public function addinscategory()
    {
        return $this->belongsTo('app\admin\model\addins\Category', 'category_id', 'id');
    }
}
