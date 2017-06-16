<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendReminderEmail;
use App\Repositories\ActivityRepository;
use App\Repositories\CargoRepository;
use App\Repositories\CategoryAttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\GoodsAttributeRepository;
use App\Repositories\GoodsRepository;
use App\Repositories\IndexGoodsRepository;
use App\Repositories\RecommendRepository;
use App\Repositories\RelCategoryAttributeRepository;
use App\Repositories\RelCategoryLabelAttributeRepository;
use App\Repositories\RelGoodsAttrRepository;
use App\Repositories\RelLabelCargoRepository;
use App\Repositories\RelRecommendGoodRepository;
use App\Tools\Analysis;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * 商品操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $goods;
    
    /**
     * 分类操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $category;
    
    /**
     * 分类标签值操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $categoryAttribute;
    
    /**
     * 商品标签值操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $goodsAttribute;

    /**
     * 文件操作
     *
     * @var
     * @author zhulinjie
     */
    protected $disk;

    /**
     * 货品操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $cargo;

    /**
     * 标签值货品关联操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $relLabelCargo;

    /**
     * 分词类
     *
     * @var
     * @author zhulinjie
     */
    protected $analysis;

    /**
     * 商品索引操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $indexGoods;

    /**
     * 推荐位操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $recommend;

    /**
     * 推荐位与货品关联操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $relRG;

    /**
     * 商品标签值关联操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $relGA;

    /**
     * @var
     * @author zhulinjie
     */
    protected $relCLA;

    /**
     * @var
     * @author zhulinjie
     */
    protected $relCA;

    /**
     * CargoController constructor.
     * @param GoodsRepository $goodsRepository
     * @param CategoryRepository $categoryRepository
     * @param CategoryAttributeRepository $categoryAttributeRepository
     * @param GoodsAttributeRepository $goodsAttributeRepository
     */
    public function __construct(
        GoodsRepository $goodsRepository,
        CategoryRepository $categoryRepository,
        CategoryAttributeRepository $categoryAttributeRepository,
        GoodsAttributeRepository $goodsAttributeRepository,
        CargoRepository $cargoRepository,
        RelLabelCargoRepository $relLabelCargoRepository,
        Analysis $analysis,
        IndexGoodsRepository $indexGoodsRepository,
        RecommendRepository $recommendRepository,
        RelRecommendGoodRepository $relRecommendGoodRepository,
        RelGoodsAttrRepository $relGoodsAttrRepository,
        RelCategoryLabelAttributeRepository $relCategoryLabelAttributeRepository,
        RelCategoryAttributeRepository $relCategoryAttributeRepository
    )
    {
        // 注入商品操作类
        $this->goods = $goodsRepository;
        // 注入分类操作类
        $this->category = $categoryRepository;
        // 注入分类标签值操作类
        $this->categoryAttribute = $categoryAttributeRepository;
        // 注入商品标签值操作类
        $this->goodsAttribute = $goodsAttributeRepository;
        // 注入货品操作类
        $this->cargo = $cargoRepository;
        // 注入七牛服务
        $this->disk = \Storage::disk('qiniu');
        // 注入标签值货品关联操作类
        $this->relLabelCargo = $relLabelCargoRepository;
        // 注入分词类
        $this->analysis = $analysis;
        // 注入商品索引操作类
        $this->indexGoods = $indexGoodsRepository;
        // 注入推荐位操作类
        $this->recommend = $recommendRepository;
        // 注入推荐位货品关联操作类
        $this->relRG = $relRecommendGoodRepository;
        // 注入商品标签值关联操作类
        $this->relGA = $relGoodsAttrRepository;
        // 注入商品标签键值关联表操作类
        $this->relCLA = $relCategoryLabelAttributeRepository;
        // 注入商品标签值关联表操作类
        $this->relCA = $relCategoryAttributeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 货品添加
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function addCargo($id)
    {
        return view('admin.cargo.addCargo', compact('id'));
    }

    /**
     * 获取货品相关信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function detail(Request $request)
    {
        $data = $request->all();

        // 获取商品信息
        $goods = $this->goods->find(['id' => $data['goods_id']]);
        if (!$goods) {
            return responseMsg('该商品不存在', 404);
        }

        // 获取商品标签
        $goodsLabels = $goods->labels;

        // 获取商品标签值
        foreach ($goodsLabels as $label) {
            $label = $label->attrs;
        }

        // 获取货品信息
        if (isset($data['cargo_id'])) {
            $cargo = $this->cargo->find(['id' => $data['cargo_id']]);
            if (!$cargo) {
                return responseMsg('该货品不存在', 404);
            }
            $res['cargo'] = $cargo;

            // 货品拥有的分类标签键值对
            $labelCargo = $this->relLabelCargo->find(['cargo_id' => $cargo->id]);
            if ($labelCargo) {
                $res['labelCargo'] = $labelCargo;
            } else {
                $res['labelCargo'] = '';
            }
        }

        // 获取三级分类
        $lv3s = $this->category->find(['id' => $goods->category_id]);
        if (!$lv3s) {
            return responseMsg('该分类不存在', 404);
        }

        // 获取二级分类
        $lv2s = $this->category->find(['id' => $lv3s['pid']]);
        if (!$lv2s) {
            return responseMsg('该分类不存在', 404);
        }

        // 获取一级分类
        $lv1s = $this->category->find(['id' => $lv2s['pid']]);
        if (!$lv1s) {
            return responseMsg('该分类不存在', 404);
        }

        // 获取分类标签
        $categoryLabels = $lv3s->labels;

        // 获取分类标签值
        $categoryAttrs = $lv3s->attrs;
        foreach ($categoryLabels as $label) {
            $list = [];
            foreach($this->relCLA->select(['cid'=>$goods->category_id, 'lid' => $label->id]) as $attr){
                foreach ($categoryAttrs as $categoryAttr){
                    if($attr->aid == $categoryAttr->id){
                        $list[] = $categoryAttr;
                    }
                }
            }
            $label->categoryAttrs = $list;
        }
        
        $res['goods'] = $goods;
        $res['goodsLabels'] = $goodsLabels;
        $res['lv1s'] = $lv1s;
        $res['lv2s'] = $lv2s;
        $res['lv3s'] = $lv3s;
        
        return responseMsg($res);
    }
    
    /**
     * 添加分类标签值
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function addCategoryAttr(Request $request)
    {
        $data = $request->all();

        try{
            \DB::beginTransaction();
            $falg = false;
            // 添加之前判断标签值是否存在
            $res = $this->categoryAttribute->find(['attribute_name' => $data['attribute_name']]);
            if(!$res){
                $falg = true;
                // 向分类标签值表插入记录
                $res = $this->categoryAttribute->insert(['attribute_name' => $data['attribute_name']]);
            }
//            dd($data);
            // 判断是否已经存在
            if(!$this->relCLA->find(['cid'=>$data['category_id'], 'lid' => $data['category_label_id'], 'aid' => $res->id])){
                $falg = true;
                // 向分类标签键值关联表中插入记录
                $this->relCLA->insert(['cid'=>$data['category_id'], 'lid' => $data['category_label_id'], 'aid' => $res->id]);
            }
            // 判断是否已经存在
            if(!$this->relCA->find(['category_id' => $data['category_id'], 'category_attribute_id' => $res->id])){
                $falg = true;
                // 向分类标签值关联表中插入记录
                $this->relCA->insert(['category_id' => $data['category_id'], 'category_attribute_id' => $res->id]);
            }
            \DB::commit();
            if($falg){
                return responseMsg($res);
            }
            return responseMsg('');
        }catch (\Exception $e){
            \DB::rollback();
            return responseMsg($e->getMessage(), 400);
        }
    }

    /**
     * 添加商品标签值
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function addGoodsAttr(Request $request)
    {
        $data = $request->all();
        // 添加操作
        $res = $this->goodsAttribute->insert($data);
        // 判断操作是否成功
        if (!$res) {
            return responseMsg('商品标签值添加失败', 400);
        }
        return responseMsg($res);
    }

    /**
     * 货品图片上传
     *
     * @param Request $request
     * @return bool
     * @author zhulinjie
     */
    public function cargoImgUpload(Request $request)
    {
        // 判断是否有图片上传
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // 检测图片是否合法
            if (checkImage($file)) {
                // 上传图片到七牛云 返回图片路径名
                $filename = $this->disk->put(IMAGE_PATH, $file);
                return responseMsg($filename);
            } else {
                return responseMsg('图片格式不合法', 400);
            }
        }
        return responseMsg('暂无图片上传', 404);
    }

    /**
     * 新增货品操作
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // 货品标签/标签值ID键值对
        $cargoLabels = [];
        // 货品标签值ID集合
        $goodsAttrIds = [];
        // 货品标签/标签值ID键值对
        $categoryLabels = [];
        // 分类标签值ID集合
        $categoryAttrIds = [];
        foreach ($data as $key => $val) {
            if (strpos($key, 'goodsLabel') !== false) {
                $cargoLabels[str_replace('goodsLabel', '', $key)] = $val;
                $goodsAttrIds[] = $val;
            }
            if (strpos($key, 'categoryLabel') !== false) {
                $categoryLabels[str_replace('categoryLabel', '', $key)] = $val;
                $categoryAttrIds[] = $val;
            }
        }

        // 获取商品信息
        $goods = $this->goods->find(['id' => $data['goods_id']]);
        if (!$goods) {
            return responseMsg('该商品不存在', 404);
        }

        // 获取商品规格数量
        $standardNum = count($goods->labels->toArray());

        // 未选择商品规格或者说只选择了部分商品规格
        if ($standardNum != count($goodsAttrIds)) {
            return responseMsg('请选择商品规格', 400);
        }

        // 组合货品表中需要的数据
        $param['category_id'] = $data['category_id'];
        $param['goods_id'] = $data['goods_id'];
        $param['cargo_ids'] = json_encode($cargoLabels);
        $param['cargo_cover'] = $data['cargo_original'][0];
        $param['inventory'] = $data['inventory'];
        $param['cargo_price'] = $data['cargo_price'];
        $param['cargo_name'] = $data['cargo_name'];
        $param['cargo_discount'] = $param['cargo_price'];
        $param['cargo_original'] = json_encode($data['cargo_original']);
        $param['cargo_info'] = $data['cargo_info'];
        
        // 获取三级分类
        $lv3s = $this->category->find(['id' => $data['category_id']]);
        if (!$lv3s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 三级分类名称
        $body[] = $this->analysis->toUnicode($lv3s->name);
        $body = array_merge($body, $this->analysis->QuickCut($lv3s->name));

        // 获取二级分类
        $lv2s = $this->category->find(['id' => $lv3s['pid']]);
        if (!$lv2s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 二级分类名称
        array_push($body, $this->analysis->toUnicode($lv2s->name));
        $body = array_merge($body, $this->analysis->QuickCut($lv2s->name));

        // 获取一级分类
        $lv1s = $this->category->find(['id' => $lv2s['pid']]);
        if (!$lv1s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 一级分类名称
        array_push($body, $this->analysis->toUnicode($lv1s->name));
        $body = array_merge($body, $this->analysis->QuickCut($lv1s->name));

        // 分词处理 商品标题
        array_push($body, $this->analysis->toUnicode($goods->goods_title));
        $body = array_merge($body, $this->analysis->QuickCut($goods->goods_title));

        // 获取分类标签值
        if ($categoryAttrIds) {
            $categoryAttrs = $this->categoryAttribute->selectByWhereIn('id', $categoryAttrIds);
            foreach ($categoryAttrs as $categoryAttr) {
                // 分词处理 分类标签值
                array_push($body, $this->analysis->toUnicode($categoryAttr->attribute_name));
                $body = array_merge($body, $this->analysis->QuickCut($categoryAttr->attribute_name));
            }
        }

        try {
            \DB::beginTransaction();

            // 组合查询条件
            foreach ($cargoLabels as $k => $v) {
                $where['cargo_ids->' . $k] = $v;
            }

            // 新增之前判断相同属性的货品是否存在，如果存在则不添加
            if ($this->cargo->find($where)) {
                throw new \Exception('相同规格的货品已经存在', 400);
            }

            // 向货品表中新增记录
            $cargo = $this->cargo->insert($param);

            // 分词处理 货品标题
            array_push($body, $this->analysis->toUnicode($cargo->cargo_name));
            $body = array_merge($body, $this->analysis->QuickCut($cargo->cargo_name));

            // 分类标签值与货品关联表中新增记录
            if (!empty($categoryLabels)) {
                $arr = [];
                $arr['category_attr_ids'] = json_encode($categoryLabels);
                $arr['goods_id'] = $data['goods_id'];
                $arr['cargo_id'] = $cargo->id;
                $this->relLabelCargo->insert($arr);
            }

            // 商品标签值关联表新增记录
            if (!empty($goodsAttrIds)) {
                $arr = [];
                foreach ($goodsAttrIds as $id) {
                    $arr['goods_attr_id'] = $id;
                    $arr['goods_id'] = $data['goods_id'];
                    // 新增之前判断商品是否已经存在这个属性值，如果存在则不添加
                    if (!$this->relGA->find($arr)) {
                        $this->relGA->insert($arr);
                    }
                }
            }

            // 向商品索引表中新增记录
            $indexs['goods_id'] = $data['goods_id'];
            $indexs['cargo_id'] = $cargo->id;
            $indexs['body'] = implode(' ', $body);
            $this->indexGoods->insert($indexs);

            // 将货品信息存储到redis
            \Redis::hmset(HASH_CARGO_INFO_ . $cargo->id, $cargo->toArray());

            \DB::commit();
            return responseMsg('货品添加成功');
        } catch (\Exception $e) {
            \DB::rollback();
            if ($e->getCode() == 400) {
                return responseMsg($e->getMessage(), $e->getCode());
            }
            return responseMsg('货品添加失败', 400);
        }
    }

    /**
     * 货品列表界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function cargoList($id)
    {
        $cargoes = $this->cargo->select();

//        foreach ($cargoes as $cargo){
//            $this->dispatch(new SendReminderEmail($cargo));
//        }

        return view('admin.cargo.list', compact('id'));
    }

    /**
     * 获取货品列表数据
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function getCargoList(Request $request)
    {
        $data = $request->all();

        // 获取货品列表数据
        $cargos = $this->cargo->paging(['goods_id' => $data['goods_id']], $data['perPage']);

        // 获取货品推荐位
        foreach ($cargos as $cargo) {
            $recommends = $this->relRG->select(['cargo_id' => $cargo->id]);
            $tmp = [];
            if (!empty($recommends)) {
                foreach ($recommends as $recommend) {
                    $tmp[] = $this->recommend->find(['id' => $recommend->recommend_id]);
                }
            }
            $cargo->recommends = $tmp;
        }

        // 判断是否存在货品
        if (empty($cargos)) {
            return responseMsg($cargos, 404);
        }

        return responseMsg($cargos);
    }

    /**
     * 获取货品推荐位信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function getRecommend(Request $request)
    {
        // 接收前台数据
        $reqs = $request->all();

        // 获取一个货品对应的推荐位的所有ID
        $recommendIds = $this->relRG->lists(['cargo_id' => $reqs['cargo_id']], ['recommend_id']);

        // 获取所有的推荐位
        $recommends = $this->recommend->select();

        if (empty($recommends)) {
            return responseMsg('暂无数据', 404);
        }

        return responseMsg(['recommendIds' => $recommendIds, 'recommends' => $recommends]);
    }

    /**
     * 选择推荐位
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function selectRecommend(Request $request)
    {
        // 获取前台数据
        $data = $request->all();

        // 取消所有推荐位的情况或者是第一次设置推荐位且没有选择任何一个推荐位的情况
        if (!isset($data['recommend_id'])) {
            $data['recommend_id'] = [];
        }

        // 获取货品对应的推荐位的所有ID
        $recommendCargoIds = $this->relRG->lists(['cargo_id' => $data['cargo_id']], ['recommend_id'])->toArray();

        // 求出现在选择的推荐位与已有的推荐位的交集
        $intersect = array_intersect($data['recommend_id'], $recommendCargoIds);
        // 求出现在选择的推荐位与已有的推荐位的差集
        $diff = array_diff($data['recommend_id'], $recommendCargoIds);

        // 新增操作
        try {
            \DB::beginTransaction();
            // 删除非交集的部分
            $this->relRG->whereNotInRecommendIds($data['cargo_id'], $intersect);
            // 新增差集的部分
            foreach ($diff as $rid) {
                $this->relRG->insert(['recommend_id' => $rid, 'cargo_id' => $data['cargo_id']]);
            }
            $cargo = $this->cargo->find(['id' => $data['cargo_id']]);
            // 获取货品推荐位
            $recommends = $this->relRG->select(['cargo_id' => $cargo->id]);
            $arr = [];
            if (!empty($recommends)) {
                foreach ($recommends as $recommend) {
                    $arr[] = $this->recommend->find(['id' => $recommend->recommend_id]);
                }
            }
            $cargo->recommends = $arr;
            \DB::commit();
            return responseMsg($cargo);
        } catch (\Exception $e) {
            \DB::rollback();
            return responseMsg('选择推荐位失败', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 编辑界面
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cargo = $this->cargo->find(['id' => $id]);
        return view('admin.cargo.edit', compact('cargo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        // 货品标签/标签值ID键值对
        $cargoLabels = [];
        // 货品标签值ID集合
        $goodsAttrIds = [];
        // 货品标签/标签值ID键值对
        $categoryLabels = [];
        // 分类标签值ID集合
        $categoryAttrIds = [];
        foreach ($data as $key => $val) {
            if (strpos($key, 'goodsLabel') !== false) {
                $cargoLabels[str_replace('goodsLabel', '', $key)] = $val;
                $goodsAttrIds[] = $val;
            }
            if (strpos($key, 'categoryLabel') !== false) {
                $categoryLabels[str_replace('categoryLabel', '', $key)] = $val;
                $categoryAttrIds[] = $val;
            }
        }
        // 获取商品信息
        $goods = $this->goods->find(['id' => $data['goods_id']]);
        if (!$goods) {
            return responseMsg('该商品不存在', 404);
        }

        // 获取商品规格数量
        $standardNum = count($goods->labels->toArray());

        // 未选择商品规格或者说只选择了部分商品规格
        if ($standardNum != count($goodsAttrIds)) {
            return responseMsg('请选择商品规格', 400);
        }

        // 组合货品表中需要的数据
        $param['category_id'] = $data['category_id'];
        $param['goods_id'] = $data['goods_id'];
        $param['cargo_ids'] = json_encode($cargoLabels);
        $param['cargo_cover'] = $data['cargo_original'][0];
        $param['inventory'] = $data['inventory'];
        $param['cargo_price'] = $data['cargo_price'];
        $param['cargo_name'] = $data['cargo_name'];
        $param['cargo_original'] = json_encode($data['cargo_original']);
        $param['cargo_info'] = $data['cargo_info'];

        // 获取三级分类
        $lv3s = $this->category->find(['id' => $data['category_id']]);
        if (!$lv3s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 三级分类名称
        $body[] = $this->analysis->toUnicode($lv3s->name);
        $body = array_merge($body, $this->analysis->QuickCut($lv3s->name));

        // 获取二级分类
        $lv2s = $this->category->find(['id' => $lv3s['pid']]);
        if (!$lv2s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 二级分类名称
        array_push($body, $this->analysis->toUnicode($lv2s->name));
        $body = array_merge($body, $this->analysis->QuickCut($lv2s->name));

        // 获取一级分类
        $lv1s = $this->category->find(['id' => $lv2s['pid']]);
        if (!$lv1s) {
            return responseMsg('该分类不存在', 404);
        }
        // 分词处理 一级分类名称
        array_push($body, $this->analysis->toUnicode($lv1s->name));
        $body = array_merge($body, $this->analysis->QuickCut($lv1s->name));

        // 分词处理 商品标题
        array_push($body, $this->analysis->toUnicode($goods->goods_title));
        $body = array_merge($body, $this->analysis->QuickCut($goods->goods_title));

        // 分词处理 货品名称
        array_push($body, $this->analysis->toUnicode($param['cargo_name']));
        $body = array_merge($body, $this->analysis->QuickCut($param['cargo_name']));

        // 获取分类标签值
        if ($categoryAttrIds) {
            $categoryAttrs = $this->categoryAttribute->selectByWhereIn('id', $categoryAttrIds);
            foreach ($categoryAttrs as $categoryAttr) {
                // 分词处理 分类标签值
                array_push($body, $this->analysis->toUnicode($categoryAttr->attribute_name));
                $body = array_merge($body, $this->analysis->QuickCut($categoryAttr->attribute_name));
            }
        }

        try {
            \DB::beginTransaction();

            // 修改商品标签值关联表
            $cargo = $this->cargo->find(['id' => $id]);
            $cargo_ids = json_decode($cargo->cargo_ids, 1);
            $diff = array_diff($cargo_ids, $goodsAttrIds);
            $intersect = array_intersect($goodsAttrIds, $cargo_ids);

            if ($intersect != $goodsAttrIds) {
                // 组合查询条件
                foreach ($cargoLabels as $k => $v) {
                    $where['cargo_ids->' . $k] = $v;
                }

                // 修改之前判断相同属性的货品是否存在，如果存在则不允许修改
                if ($this->cargo->find($where)) {
                    throw new \Exception('相同规格的货品已经存在', 400);
                }
            }

            // 修改分类标签值与货品关联表
            if (!empty($categoryLabels)) {
                $arr = [];
                $arr['category_attr_ids'] = json_encode($categoryLabels);
                // 先判断记录是否存在 如果不存在 则为新增操作
                $relLabelCargo = $this->relLabelCargo->find(['id' => $id]);
                if (!$relLabelCargo) {
                    $arr['goods_id'] = $data['goods_id'];
                    $arr['cargo_id'] = $id;
                    $this->relLabelCargo->insert($arr);
                } else {
                    $this->relLabelCargo->update(['id' => $id], $arr);
                }
            }

            // 查找需要删除的商品标签值
            $ids = [];
            foreach ($diff as $k => $v) {
                if (!$this->cargo->find(['cargo_ids->' . $k => $v])) {
                    $ids[] = $v;
                }
            }

            // 删除商品标签值
            if (!empty($ids)) {
                $this->relGA->deleteWhereIn($ids);
            }

            // 新增商品标签值
            if (!empty($goodsAttrIds)) {
                $arr = [];
                foreach ($goodsAttrIds as $v) {
                    if (!in_array($v, $intersect)) {
                        $arr['goods_attr_id'] = $v;
                        $arr['goods_id'] = $data['goods_id'];
                        // 新增之前判断商品是否已经存在这个属性值，如果存在则不添加
                        if (!$this->relGA->find($arr)) {
                            $this->relGA->insert($arr);
                        }
                    }
                }
            }

            // 修改货品
            $this->cargo->update(['id' => $id], $param);

            // 修改商品索引表
            $indexs['body'] = implode(' ', $body);
            $this->indexGoods->update(['cargo_id' => $id], $indexs);

            // 更新缓存
            \Redis::hmset(HASH_CARGO_INFO_ . $id, $cargo->toArray());

            \DB::commit();
            return responseMsg('货品修改成功');
        } catch (\Exception $e) {
            \DB::rollback();
            if ($e->getCode() == 400) {
                return responseMsg($e->getMessage(), $e->getCode());
            }
            return responseMsg($e->getMessage(), 400);
        }
    }

    /**
     * 更改货品状态（上架或下架）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function updateCargoStatus(Request $request)
    {
        $req = $request->all();

        switch ($req['status']) {
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

        $res = $this->cargo->update(['id' => $req['cargo_id']], ['cargo_status' => $status]);

        if (!$res) {
            return responseMsg($msg . '失败', 400);
        }

        $cargo = $this->cargo->find(['id' => $req['cargo_id']]);

        // 获取货品推荐位
        $recommends = $this->relRG->select(['cargo_id' => $cargo->id]);
        $arr = [];
        if (!empty($recommends)) {
            foreach ($recommends as $recommend) {
                $arr[] = $this->recommend->find(['id' => $recommend->recommend_id]);
            }
        }
        $cargo->recommends = $arr;

        return responseMsg($cargo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
