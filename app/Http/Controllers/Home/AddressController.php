<?php

namespace App\Http\Controllers\Home;

use App\Repositories\AddressRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * @var AddressRepository
     */
    protected $address;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->address = $addressRepository;
    }

    /**
     * 获取用户收货地址
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function index()
    {
        // 获取该用户所有收货地址
        $result = $this->address->select(['user_id'=>\Session::get('user')->user_id]);
        // 判断返回结果
        if(empty($result)) {
            $result = [];
        }
        // 返回视图
       return view('home.address.index',['data'=>$result]);
    }

    /**
     * 返回添加收货地址视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhangyuchao
     */
    public function create()
    {
        return view('home.address.create');
    }

    /**
     * 添加单条收货地址
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author zhangyuchao
     */
    public function store(Request $request)
    {
        // 设定最多可填写9个收货地址
        $count = $this->address->count(['user_id'=>\Session::get('user')->user_id]);
        $param = $request->all();
        if($count>9) {
            // 返回错误信息
            return back()->withErrors('收货地址到达上线,最多可填写9个!');
        }
        // 第一条数据设为默认地址
        if($count<1) {
            $param['status'] = 2;
        }
        // 添加单条数据s
        $result = $this->address->insert($param);
        // 判断返回值
        if(!empty($result)) {
            // 添加成功
            return redirect('/home/address');
        }

        // 添加失败
        return back()->withErrors('添加收货地址失败!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 获取单挑数据
        $result = $this->address->find(['id' => $id]);
        if(empty($result)) {
            $result = [];
        }
        return view('home.address.edit',['data' => $result]);
    }

    /**
     * 编辑收货地址
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function update(Request $request, $id)
    {
        // 过滤token method
        $data = $request->except(['_token','_method']);
        // 设置默认
        if(count($data) == 1) {
            $this->address->update(['user_id'=>\Session::get('user')->user_id],['status'=>1]);
        }
        // 修改操作
        $result = $this->address->update(['id'=>$id],$data);
        // 判断返回结果
        if(!empty($result)) {
            // 成功
            return responseMsg('修改成功');
        }
        // 失败
        return responseMsg('修改失败',400);
    }

    /**
     * 删除收货地址
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author zhangyuchao
     */
    public function destroy($id)
    {
        // 删除操作
        $result = $this->address->delete(['id' => $id]);
        // 判断返回结果
        if(!empty($result)) {
            // 成功
            return responseMsg('删除成功');
        }
        // 失败
        return responseMsg('删除失败',400);
    }
}
