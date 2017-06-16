<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\BasicConfigRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BasicConfigController extends Controller
{
    /**
     * @var BasicConfigRepository
     */
    protected $basicconfig;

    /**
     * 服务注入
     *
     * ClassificationController constructor.
     */
    public function __construct(BasicConfigRepository $basicconfigRepository)
    {

        // 注入网站配置操作类
        $this->basicconfig = $basicconfigRepository;
        // 注入七牛服务
        $this->disk = \Storage::disk('qiniu');
    }
    /**
     * 后台配置页面.
     *
     * @return \Illuminate\Http\Response
     * @author jiaohuafeng
     */
    public function index()
    {
        //从数据库获取网站配置记录
        $data = $this->basicconfig->getByBasicConfig();
        return view('admin.basicconfig.index',['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //添加网上配置文件界面
        return view('admin.basicconfig.create');
    }

    /**
     * 保存网站基础配置.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author jiaohuafeng
     */
    public function store(Request $request)
    {


        $data = $request->all();
        // 向网站基本配置表中新增记录
        if($this->basicconfig->insert($data)){
            return responseMsg('网站配置添加成功');
        }else{
            return responseMsg('网站配置添加失败', 400);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //根据id查询出对应的网站配置信息
        $config = $this->basicconfig->getBasicConfigById($id);
        //修改网上配置文件界面
        return view('admin.basicconfig.edit',['data' => $config]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

        // 除去请求中得 _token 字段
        $data = $request->except(['_token']);
        // 修改分类数据, 判断返回结果
        if ($this->basicconfig->update(['id' => $id], $data)) {
            // 成功返回修改数据
            return responseMsg('修改成功!', 200);
        }

        // 修改失败
        return responseMsg('修改失败!', 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 商品图片上传
     *
     * @param Request $request
     * @return bool
     * @author jiaohuafeng
     */
    public function logoImgUpload(Request $request)
    {
        // 判断是否有图片上传
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // 检测图片是否合法
            if(checkImage($file)){
                // 上传图片到七牛云 返回图片路径名
                $filename = $this->disk->put(IMAGE_PATH, $file);
                return responseMsg($filename);
            }else{
                return responseMsg('图片格式不合法', 400);
            }
        }
        return responseMsg('暂无图片上传', 404);
    }

}
