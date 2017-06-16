<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ClassificationController
 * @package App\Http\Controllers\Admin
 */
class ClassificationController extends Controller
{
    /**
     * 文件操作
     *
     * @var \Storage
     */
    protected $disk;

    /**
     * @var CategoryRepository
     */
    protected $category;

    /**
     * 服务注入
     *
     * ClassificationController constructor.
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        // 注入七牛服务
        $this->disk = \Storage::disk('qiniu');
        // 注入分类操作类
        $this->category = $categoryRepository;
    }

    /**
     * 分类列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function index()
    {
        // 返回分类列表视图
        return view('admin.classification.index');
    }

    /**
     * 添加分类视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function create()
    {
        // 返回创建分类视图
        return view('admin.classification.insert');
    }


    /**
     * 分类信息录入
     *
     * @param Request $request
     * @return $this
     * @author: Luoyan
     */
    public function store(Request $request)
    {
        // 文件处理函数
        $this->fileDo($request);

        // 录入分类信息，并且判断录入结果
        if ($this->category->insert($request->all())) {
            // 录入成功跳转分类列表
            return redirect()->route('classification.index');
        }

        // 录入失败返回上一页，并且附带提交表单值
        return back()->withInput();
    }

    /**
     * 分类列表
     *
     * @param Request $request
     * @return mixed
     * @author: Luoyan
     */
    public function categoryList(Request $request)
    {
        // 获取分页或搜索后的数据
        return $this->category->paging($request->get('where'), $request->get('perPage'), 'created_at', 'desc');
    }

    /**
     * 查询分类数据方法
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function show($id)
    {
        // 查询分类数据，并且判断查询是否成功
        if ($category = $this->category->findByIdWithParent($id)) {
            $category->doma = env('QINIU_DOMAIN');
        }

        return $category;
    }

    /**
     * 修改分类信息
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function update(Request $request, $id)
    {
        // 文件处理函数
        $this->fileDo($request);
        // 除去请求中得 _token 字段
        $data = $request->except(['_token', 'image']);
        // 修改分类数据, 判断返回结果
        if ($this->category->update(['id' => $id], $data)) {
            // 查询更新后的值
            $data = $this->category->findByIdWithParent($id);

            // 成功返回修改数据
            return responseMsg($data, 200);
        }

        // 修改失败
        return responseMsg('修改失败!', 400);
    }

    /**
     * 文件处理函数
     *
     * @param Request $request
     * @return bool
     * @author: Luoyan
     */
    public function fileDo(Request $request)
    {
        // 判断是否有图标上传，并且检查图片是否合法
        if ($request->hasFile('image') && checkImage($file = $request->file('image'))) {
            // 上传七牛文件云存储后返回图片名字
            $imageName = $this->disk->put(IMAGE_PATH, $file);
            // 将图片名字塞入请求之中
            $request->merge(['img' => $imageName]);

            return true;
        }

        return false;
    }

    /**
     * 添加子元素操作
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function categoryCreate(Request $request)
    {
        // 文件处理函数
        $this->fileDo($request);
        // 录入分类信息，并且判断录入结果
        if ($this->category->insert($request->all())) {
            // 录入成功跳转分类列表
            return responseMsg('添加成功!');
        }

        // 修改失败
        return responseMsg('添加失败!', 400);
    }

    /**
     * 分类软删除与恢复
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function destroy(Request $request, $id)
    {
        // 恢复一条数据，并判断结果
        if ($request->get('boolean') && $this->category->softRstore($id)) {
            return responseMsg('启用成功!');
        }
        
        // 软删除一条数据并判断结果
        if ($this->category->delete(['id' => $id])) {
            // 成功提示消息
            return responseMsg('禁用成功!');
        }

        // 失败提示
        return responseMsg('操作失败!', 400);
    }
}
