<?php

namespace addons\app_demo\app\admin\controller;

use addons\app_demo\app\admin\controller\AppBackend;
use think\facade\Config;
use think\facade\Request;
use xiaoyaor\think\Jump;
use think\facade\View;

/**
 * 后台首页
 * @internal
 */
class Index extends AppBackend
{
    use Jump;
    protected $noNeedLogin = ['login','captcha'];
    protected $noNeedRight = ['index', 'logout'];

    //构造方法
    public function __construct()
    {
        parent::__construct();
        $this->view->layout(false);
    }

    /**
     * 后台首页
     * @throws \Exception
     */
    public function index()
    {
        //菜单标识
        Config::get('site.menu_flag')?$flag=['dashboard' => 'hot','addon' => ['new', 'red', 'badge'],'auth/rule' => __('Menu'),'general' => ['new', 'purple']]:$flag=[];
        //左侧菜单,有无权限
        if (!$this->auth){
            list($menulist, $navlist, $fixedmenu, $referermenu) = getSidebar($flag, Config::get('site.fixedpage'));
        }else{
            list($menulist, $navlist, $fixedmenu, $referermenu) = $this->auth->getSidebar($flag, Config::get('site.fixedpage'));
        }
        $action = Request::request('action');
        if (Request::isPost()) {
            if ($action == 'refreshmenu') {
                $this->success('', null, ['menulist' => $menulist, 'navlist' => $navlist]);
            }
        }
        $this->assign('menulist', $menulist);
        $this->assign('navlist', $navlist);
        $this->assign('fixedmenu', $fixedmenu);
        $this->assign('referermenu', $referermenu);
        $this->assign('title', __('Home'));
        return $this->fetch();
    }

    /*
     * 切换皮肤
     *
     */
    function changeSkin()
    {
        if (request()->isPost()) {
            $skin = request()->post("skin");
            if ($skin != Config::get('site.skin') && $skin!=''){
                change_site('skin',$skin);
                $this->success('切换皮肤成功');
            }
            else{
                $this->success('');
            }
        }
    }

}
