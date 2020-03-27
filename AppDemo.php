<?php

namespace addons\app_demo;

use app\common\library\Menu;
use think\Addons;
use think\facade\Log;
use think\facade\Config;
use think\facade\View;

/**
 * 登录背景图插件
 */
class AppDemo extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu=[];
        $config_file= ADDON_PATH ."app_demo" . DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR . "menu.php";
        if (is_file($config_file)) {
            $menu = include $config_file;
        }
        if($menu){
            Menu::create($menu);
        }
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('app_demo');
        return true;
    }

    /**
     * 插件启用方法
     */
    public function enable()
    {
        Menu::enable('app_demo');
    }

    /**
     * 插件禁用方法
     */
    public function disable()
    {
        Menu::disable('app_demo');
    }

    /**
     * 控制台显示插件信息
     * 可复制dashboard函数内容到此处自己改写
     * @throws \think\Exception
     */
//    public function fetchToDashboard($params)
//    {
//        return $this->dashboard($params);
//    }


}
