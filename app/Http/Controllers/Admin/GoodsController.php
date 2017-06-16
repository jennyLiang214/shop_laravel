<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CargoRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\GoodsLabelRepository;
use App\Repositories\GoodsRepository;
use App\Repositories\RelGoodsLabelRepository;
use Illuminate\Http\Request;
use App\Tools\Common;

class GoodsController extends Controller
{
    /**
     * 商品操作类
     *
     * @var GoodsRepository
     * @author zhulinjie
     */
    protected $goods;

    /**
     * 分类操作类
     *
     * @var CategoryRepository
     * @author zhulinjie
     */
    protected $category;

    /**
     * 商品标签操作类
     *
     * @var GoodsLabelRepository
     * @author zhulinjie
     */
    protected $goodsLabel;

    /**
     * 文件操作
     *
     * @var
     * @author zhulinjie
     */
    protected $disk;

    /**
     * 商品标签关联操作类
     *
     * @var RelGoodsLabelRepository
     * @author zhulinjie
     */
    protected $relGL;

    /**
     * 货品操作表
     *
     * @var
     * @author zhulinjie
     */
    protected $cargo;

    /**
     * GoodsController constructor.
     * @param GoodsRepository $goods
     * @param GoodsLabelRepository $goodsLabel
     * @param CategoryRepository $category
     * @author zhulinjie
     */
    public function __construct
    (
        GoodsRepository $goods,
        GoodsLabelRepository $goodsLabel,
        CategoryRepository $category,
        RelGoodsLabelRepository $relGoodsLabelRepository,
        CargoRepository $cargoRepository
    )
    {
        // 注入商品操作类
        $this->goods = $goods;
        // 注入商品标签操作类
        $this->goodsLabel = $goodsLabel;
        // 注入分类操作类
        $this->category = $category;
        // 注入七牛服务
        $this->disk = \Storage::disk('qiniu');
        // 商品标签关联操作类
        $this->relGL = $relGoodsLabelRepository;
        // 货品操作类
        $this->cargo = $cargoRepository;
    }

    /**
     * 商品列表界面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function index()
    {
        return view('admin.goods.index');
    }

    /**
     * 获取商品列表数据
     *
     * @param Request $request
     * @return mixed
     * @author zhulinjie
     */
    public function goodsList(Request $request)
    {
        $data = $request->all();

        // 获取商品列表
        $res = $this->goods->paging($data['where'], $data['perPage']);

        // 判断商品是否存在
        if(empty($res)){
            return responseMsg('暂无数据', 404);
        }
        
        return responseMsg($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.goods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // 组合商品表中需要的数据
        $param['category_id'] = $data['level3'];
        $param['goods_title'] = $data['goods_title'];
        $param['goods_original'] = json_encode($data['goods_original']);
        $param['goods_info'] = $data['goods_info'];

        try{
            \DB::beginTransaction();
            // 向商品表中新增记录
            $goods = $this->goods->insert($param);

            // 向商品标签关联表中新增记录
            foreach($data['goods_label'] as $val){
                $arr['goods_id'] = $goods->id;
                $arr['goods_label_id'] = $val;
                $this->relGL->insert($arr);
            }
            \DB::commit();
            return responseMsg('商品添加成功');
        }catch (\Exception $e){
            \DB::rollback();
            return responseMsg('商品添加失败', 400);
        }
    }

    /**
     * 获取分类信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function getCategory(Request $request)
    {
        $data = $request->all();
        $res = $this->category->select($data);
        return responseMsg($res);
    }

    /**
     * 获取分类下的商品标签
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function getGoodsLabel(Request $request)
    {
        $data = $request->all();
        // 获取分类下的商品标签
        $goodsLabels = $this->goodsLabel->select(['category_id'=>$data['category_id']]);
        // 判断是否添加成功
        if($goodsLabels){
            return responseMsg($goodsLabels);
        }else{
            return responseMsg('添加商品标签失败', 400);
        }
    }

    /**
     * 添加商品标签
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function addGoodsLabel(Request $request)
    {
        $data = $request->all();
        // 先判断标签是否存在
        if(!$this->goodsLabel->find($data)){
            $res = $this->goodsLabel->insert($data);
            if(!$res){
                return responseMsg('商品标签添加失败', 400);
            }
            return responseMsg($res);
        }else{
            return responseMsg('商品标签已经存在', 400);
        }
    }

    /**
     * 商品图片上传
     *
     * @param Request $request
     * @return bool
     * @author zhulinjie
     */
    public function goodsImgUpload(Request $request)
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

    public function getGoodsDetail(Request $request)
    {
        $req = $request->all();

        // 获取商品信息
        $goods = $this->goods->find(['id' => $req['goods_id']]);
        if(!$goods){
            return responseMsg('商品不存在', 404);
        }
        
        // 获取家谱树
        $categorys = $this->category->select()->toArray();
        $goods->goodsCategory = array_reverse(Common::tree($categorys, $goods->category_id));
        
        // 获取一级分类
        $goods->lv1s = $this->category->select(['level' => 1])->toArray();

        // 获取二级分类
        $goods->lv2s = $this->category->select(['pid' => $goods->goodsCategory[0]['id']])->toArray();

        // 获取三级分类
        $goods->lv3s = $this->category->select(['pid' => $goods->goodsCategory[1]['id']])->toArray();

        // 获取商品标签
        $goods->goodsLabelIds = $this->relGL->lists(['goods_id' => $goods->id], ['goods_label_id'])->toArray();

        // 获取分类下面的商品标签
        $goods->goodsLabels = $this->goodsLabel->select(['category_id' => $goods->category_id])->toArray();

        // 获取商品下面是否有货品，如果有货品则不能修改商品规格
        $goods->cargo = $this->cargo->find(['goods_id' => $req['goods_id']]);

        return responseMsg($goods);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 获取商品信息
        $goods = $this->goods->find(['id' => $id]);

        if(!$goods){
            return responseMsg('商品不存在', 404);
        }
        
        return view('admin.goods.edit', compact('goods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $req = $request->all();
        // 获取原商品标签
        $goodsLables = $this->relGL->lists(['goods_id' => $id], ['goods_label_id'])->toArray();
        
        // 获取现在拥有的商品标签与原商品标签的交集
        if(!isset($req['goods_label'])){
            $req['goods_label'] = [];
        }
        $intersect = array_intersect($req['goods_label'], $goodsLables);
        $diff = array_diff($req['goods_label'], $intersect);

        // 组合商品表中需要的数据
        $param['category_id'] = $req['level3'];
        $param['goods_title'] = $req['goods_title'];
        $param['goods_original'] = json_encode($req['goods_original']);
        $param['goods_info'] = $req['goods_info'];

        try{
            \DB::beginTransaction();
            // 向商品表中新增记录
            $this->goods->update(['id' => $id], $param);

            // 修改货品对应的分类
            $this->cargo->update(['goods_id' => $id], ['category_id' => $req['level3']]);

            // 先删除原商品标签
            if($intersect){
                $this->relGL->deleteWhereNotIn(['goods_id' => $id], $intersect);
            }

            // 向商品标签关联表中新增记录
            if($diff){
                foreach($diff as $val){
                    $arr['goods_id'] = $id;
                    $arr['goods_label_id'] = $val;
                    $this->relGL->insert($arr);
                }
            }

            \DB::commit();
            return responseMsg('商品修改成功');
        }catch (\Exception $e){
            \DB::rollback();
            dd($e->getMessage());
            return responseMsg('商品修改失败', 400);
        }
    }

    /**
     * 更改商品状态（上架或下架）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function updateGoodsStatus(Request $request)
    {
        $req = $request->all();

        switch ($req['status']){
            case 1:
            case 3:
                $status = 2;
                $msg = '上架';
                break;
            case 2:
                $status = 3;
                $msg = '下架';
                break;
        }

        $res = $this->goods->update(['id' => $req['goods_id']], ['goods_status' => $status]);

        if(!$res){
            return responseMsg($msg . '失败', 400);
        }

        $goods = $this->goods->find(['id' => $req['goods_id']]);
        
        return responseMsg($goods);
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
}
