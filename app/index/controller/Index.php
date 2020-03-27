<?php

namespace addons\app_demo\app\index\controller;

use addons\base\controller\AppFrontend;
use think\facade\Config;
use think\facade\Request;
use xiaoyaor\think\Jump;
use think\facade\View;

/**
 * 后台首页
 * @internal
 */
class Index extends AppFrontend
{
    use Jump;
    protected $noNeedLogin = ['login','captcha'];
    protected $noNeedRight = ['index', 'logout'];

    //构造方法
    public function __construct()
    {
        parent::__construct();
        $this->appview->layout(false);
    }

    /**
     * 后台首页
     * @throws \Exception
     */
    public function index()
    {
        return $this->fetch();
    }


}
