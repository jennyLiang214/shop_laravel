<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CategoryLabelRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\RelCategoryLabelRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CategoryLabelController
 * @package App\Http\Controllers\Admin
 */
class CategoryLabelController extends Controller
{

    /**
     * @var CategoryLabelRepository
     * @author Luoyan
     */
    protected $categoryLabel;

    /**
     * @var RelCategoryLabelRepository
     * @author Luoyan
     */
    protected $relCL;

    /**
     * @var
     * @author Luoyan
     */
    protected $category;

    /**
     * 分类标签操作注入
     *
     * CategoryLabelController constructor.
     * @param CategoryLabelRepository $categoryLabelRepository
     * @param RelCategoryLabelRepository $relCategoryLabelRepository
     * @param CategoryRepository $categoryRepository
     * @author Luoyan
     */
    public function __construct
    (
        CategoryLabelRepository $categoryLabelRepository,
        RelCategoryLabelRepository $relCategoryLabelRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryLabel = $categoryLabelRepository;
        $this->relCL = $relCategoryLabelRepository;
        $this->category = $categoryRepository;
    }

    /**
     * 获取分类标签列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function index(Request $request)
    {
        // 获取所有标签
        $labels = $this->categoryLabel->select();
        // 判断获取结果, 因为 ORM 获取一个空数据，返回的集合会被判断为 true 所有得转换成数组判断
        if (!$labels->toArray()) {
            // 暂无标签
            return responseMsg([], 200);
        }
        // 查询当前分类下面已有的标签
        $existLabels = $this->relCL->lists(['category_id' => $request->get('id')], ['category_label_id']);
        // 判断查询结果
        if (!$existLabels = $existLabels->toArray()) {
            // 不打标记直接返回所有标签
            return responseMsg($labels);
        }
        // 标记当前分类已有的标签
        foreach ($labels as $v) {
            // 判断当前标签 id 是否已经存在 这个分类之下
            if (in_array($v->id, $existLabels)) {
                // 给当前这个标签标记一个选中状态
                $v->checked = true;
            }
        }

        // 返回标签数据
        return responseMsg($labels);
    }

    /**
     * 新增标签
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function store(Request $request)
    {
        // 创建一个分类标签，并且判断创建结果
        if ($label = $this->categoryLabel->insert($request->all())) {
            // 创建成功返回数据
            return responseMsg($label);
        }

        // 创建失败
        return responseMsg('创建失败!', 400);
    }

    /**
     * 绑定或取消分类下标签
     *
     * @param Request $request
     * @param $id
     * @author: Luoyan
     */
    public function update(Request $request, $id)
    {
        // 查询当前分类信息
        $category = $this->category->find(['id' => $id]);
        // 分类绑定或取消绑定标签
        $category->labels()->sync($request->all());
    }
}
