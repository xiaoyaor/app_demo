<?php

namespace app\api\controller;

use app\common\controller\Api;
use easy\Http;
use think\facade\Cache;
use think\facade\Config;
use app\api\model\addins\Index as indexModel;
use app\api\model\addins\Category as categoryModel;
use think\facade\View;

/**
 * 示例接口
 */
class Addon extends Api
{

    protected $index;
    protected $category;

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['index','download','test', 'test1'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['test2'];

    public function _initialize()
    {
        parent::_initialize();
        $this->index=new indexModel();
        $this->category=new categoryModel();
    }

    /**
     * 无需登录的接口
     *
     */
    public function index()
    {
        $offset = (int)request()->get("offset");
        $limit = (int)request()->get("limit");
        $filter = request()->get("filter");
        $search = request()->get("search");
        $search = htmlspecialchars(strip_tags($search));



            $total = indexModel::alias('index')
                ->with(['addinscategory'])
                ->order('releasetime', 'desc')
                ->count();

            $list = indexModel::alias('index')
                ->with(['addinscategory'])
                ->order('releasetime', 'desc')
                ->limit($offset, $limit)
                ->select();


        //$total = count($list);
//        if ($limit) {
//            $list = array_slice($list, $offset, $limit);
//        }
        $result = array("total" => $total, "rows" => $list);

        $callback = request()->get('callback') ? "jsonp" : "json";
        $call=$callback($result);
        return $call;
    }

    public function download()
    {
        $name = request()->param("name");
        $value=['code'=>'1','msg'=>'成功','time'=>time(),'data'=>['url'=>'http://www.002d.com/addons/'.$name.'.zip']];
        return json_encode($value);
    }

    /**
     * 测试方法
     *
     * @ApiTitle    (测试名称)
     * @ApiSummary  (测试描述信息)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/demo/test/id/{id}/name/{name})
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiParams   (name="name", type="string", required=true, description="用户名")
     * @ApiParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
    'code':'1',
    'msg':'返回成功'
    })
     */
    public function test()
    {
        $this->success('返回成功', $this->request->param());
    }

    /**
     * 无需登录的接口
     *
     */
    public function test1()
    {
        $this->success('返回成功', ['action' => 'test1']);
    }

    /**
     * 需要登录的接口
     *
     */
    public function test2()
    {
        $this->success('返回成功', ['action' => 'test2']);
    }

    /**
     * 需要登录且需要验证有相应组的权限
     *
     */
    public function test3()
    {
        $this->success('返回成功', ['action' => 'test3']);
    }

}
