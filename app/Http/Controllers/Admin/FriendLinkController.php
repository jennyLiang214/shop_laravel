<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\FriendLinkRepository;

class FriendLinkController extends Controller
{
    /**
     *  文件操作
     *
     * @var \Storage
     */
    protected $disk;

    /**
     * @var FriendLinkRepository
     */
    protected $friendLink;

    /**
     * AdminUserController constructor.
     * @param FriendLinkRepository $friendLink
     * @author wutao
     */
    public function __construct(FriendLinkRepository $friendLink)
    {
        //注入七牛服务
        $this->disk = \Storage::disk('qiniu');
        //注入友情链接操作
        $this->friendLink = $friendLink;
    }

    /**
     * 返回友情链接模板视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author wutao
     */
    public function index()
    {
        return view('admin.links.link');
    }

    /**
     * 文件处理函数
     *
     * @param Request $request
     * @return bool
     * @author: wutao
     */
    public function fileDo(Request $request)
    {
        //判断是否有图标上传,并且检查图片是否合法
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            //检测图片是否合法
            if (checkImage($file)) {
                //上传图片到七牛云后返回图片名
                $filename = $this->disk->put(IMAGE_PATH, $file);
                //成功返回信息
                return responseMsg($filename, 200);
            }
            //失败返回信息
            return responseMsg('图片格式不正确!', 400);
        }
    }

    /**
     * 添加友情链接
     *
     * @author  wutao
     */
    public function store(Request $request)
    {
        //判断是不是图片 并且不为空
        if ($request['type'] == 1 && empty($request['image'])) {
            //失败信息
            return responseMsg('没有图片被上传!', 400);
        }
        //判断地址是否为空
        if (empty($request['url'])) return responseMsg('url地址不能为空!', 400);
        //查询数据库中是否存在要添加的URL
        $data = $this->friendLink->find(['url' => $request['url']]);
        //判断数据库中的URL和添加的URL是否相等
        if ($request['url'] == $data['url']) {
            return responseMsg('URL地址已存在!', 400);
        }
        //数据操作
        $result = $this->friendLink->insert($request->all());
        //数据插入成功 返回成功信息
        if (!empty($result)) {
            //成功返回信息
            return responseMsg('添加成功', 200);
        }
        //失败返回信息
        return responseMsg('添加失败', 400);
    }

    /**
     * 修改友情链接
     *
     * @author  wutao
     */
    public function update(Request $request)
    {
        // 修改单条数据
        $param ['name'] = $request['name'];
        $param ['url'] = $request['url'];
        //判断是否修改图片
        if (!empty($request['image'])) {
            //
            $param ['image'] = $request['image'];
        }
        //数据操作
        $data = $this->friendLink->update(['id' => $request['id']], $param);
        if (!empty($data)) {
            //成功返回信息
            return responseMsg('修改成功', 200);
        }
        //失败返回信息
        return responseMsg('修改失败', 400);
    }

    /**
     * 删除友情链接
     *
     * @author  wutao
     */
    public function destroy($id)
    {
        // 参数验证
        if (empty($id)) return responseMsg('非法操作', 400);
        //数据操作
        $result = $this->friendLink->delete(['id' => $id]);
        // 数据判断
        if (empty($result)) return responseMsg('删除失败', 400);

        return responseMsg('删除成功');
    }

    /**
     * 获取友情链接列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author wutao
     */
    public function linkList(Request $request)
    {
        //判断搜索条件
        $where['name'] = trim($request['where']['value']);
        //判断是否搜索
        if (!empty($request['where']['value'])) {
            //获取搜索框中name值
            $where['name'] = $request['where']['value'];
        } else {
            //不搜索为空
            $where = [];
        }
        //数据操作
        $result = $this->friendLink->paging($where, $request['perPage']);
        // 数据是否获取成功
        if (!empty($result)) {
            //成功返回信息
            return responseMsg($result, 200);
        }
        //失败返回信息
        return responseMsg('失败!', 400);
    }
}
