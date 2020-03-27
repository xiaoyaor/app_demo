<?php

namespace addons\app_demo\app\admin\controller;

use app\admin\model\AdminLog;
use app\common\controller\Backend;
use app\common\model\Config as ConfigModel;
use think\facade\Config;
use think\facade\Event;
use think\facade\Request;
use think\Validate;
use xiaoyaor\think\Jump;
use think\facade\View;
use think\facade\Session;

/**
 * 后台首页
 * @internal
 */
class Login extends Backend
{
    use Jump;
    protected $noNeedLogin = ['index','captcha'];
    protected $noNeedRight = ['logout'];

    //构造方法
    public function __construct()
    {
        parent::__construct();
        View::engine()->layout(false);
    }

    /**
     * 管理员登录
     * @throws \Exception
     */
    public function index()
    {
        $url = Request::get('url', 'index/index');
        if ($this->auth->isLogin()) {
            $data=[
                'url' =>$url,
                'id' =>$this->auth->id,
                'username' =>$this->auth->username,
                'avatar' =>$this->auth->avatar,
            ];
            $this->success(__("You've logged in, do not login again"), $url,$data);
        }
        if (Request::isPost()) {
            $username = Request::post('username');
            $password = Request::post('password');
            $keeplogin = Request::post('keeplogin');
            $token = Request::post('__token__');
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:3,30',
                '__token__' => 'require|token',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                '__token__' => $token,
            ];

            if (hook('use_captcha')){
                $captcha=Request::post('captcha') ;
                if(!captcha_check($captcha)){
                    Config::set(['default_return_type'=>'json'],'app');
                    $this->error(__('Captcha fault'));
                }
            }

            $validate = new Validate();
            $result = $validate->check($data,$rule);
            if (!$result) {
                $this->error($validate->getError(), $url, '');
            }
            $result = $this->auth->login($username, $password, $keeplogin ? 86400 : 0);
            if ($result === true) {
                event_trigger("AdminLoginAfter", request());
                $this->success(__('Login successful'), $url, ['url' => $url, 'id' => $this->auth->id, 'username' => $username, 'avatar' => $this->auth->avatar]);
            } else {
                event_trigger("AdminLoginErrorAfter", request());
                $msg = $this->auth->getError();
                $msg = $msg ? $msg : __('Username or password is incorrect');
                $this->error($msg, $url, '');
            }
        }

        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin()) {
            $this->redirect($url);
        }

        $background = Config::get('easyadmin.login_background');
        $background = stripos($background, 'http') === 0 ? $background : config('site.cdnurl') . $background;
        View::assign('background', $background);
        View::assign('title', __('Login'));
        View::assign('easyadmin', Config::get('easyadmin'));
        event_trigger("admin_login_init", request());
        return View::fetch();
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        event_trigger("AdminLogoutAfter", request());
        $this->auth->logout();
        $this->success(__('Logout successful'), 'login/index');
    }

    /*
     * 验证码
     *
     */
    public function captcha()
    {
        return captcha();
    }

}
