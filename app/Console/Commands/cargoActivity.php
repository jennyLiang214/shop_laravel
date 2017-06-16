<?php

namespace App\Console\Commands;

use App\Repositories\ActivityRepository;
use App\Repositories\CargoRepository;
use App\Repositories\RelGoodsActivityRepository;
use Illuminate\Console\Command;

class cargoActivity extends Command
{
    /**
     * @var string
     * @author zhulinjie
     */
    protected $signature = 'cargoActivity';

    /**
     * @var
     * @author zhulinjie
     */
    protected $cargo;

    /**
     * @var
     * @author zhulinjie
     */
    protected $activity;

    /**
     * @var
     * @author zhulinjie
     */
    protected $relGoodsActivity;

    /**
     * cargoActivity constructor.
     * @param CargoRepository $cargoRepository
     * @param ActivityRepository $activityRepository
     * @param RelGoodsActivityRepository $relGoodsActivityRepository
     */
    public function __construct(
        CargoRepository $cargoRepository,
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository
    )
    {
        parent::__construct();
        $this->cargo = $cargoRepository;
        $this->activity = $activityRepository;
        $this->relGoodsActivity = $relGoodsActivityRepository;
    }

    /**
     * @throws \Exception
     * @author zhulinjie
     */
    public function handle()
    {
        // 处理已经结束的活动
        $this->over();
        // 处理正在进行的活动
        $this->ongoing();
    }

    /**
     * 处理正在进行的活动
     *
     * @throws \Exception
     * @author zhulinjie
     */
    public function ongoing()
    {
        // 获取正在进行的活动
        $ctime = time();
        $activity = $this->activity->ongoingActivities($ctime);

        if($activity){
            try {
                \DB::beginTransaction();
                // 将正在做活动的货品的价格改为活动价
                foreach ($activity->cargos as $cargo) {
                    $relGoodsActivity = $this->relGoodsActivity->find(['activity_id' => $activity->id, 'cargo_id' => $cargo->id]);
                    $cargo->cargo_discount = $relGoodsActivity->promotion_price;
                    if (!$cargo->save()) {
                        throw new \Exception('将正在做活动的货品的价格改为活动价失败，商品ID[' . $cargo->id . ']', 400);
                    }
                    $arr = $cargo->toArray();
                    unset($arr['pivot']);
                    // 更新缓存
                    \Redis::hmset(HASH_CARGO_INFO_ . $cargo->id, $arr);
                }
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::info('Error|----[行号：' . $e->getLine() . '错误信息：' . $e->getMessage() . ']');
            }
        }
    }

    /**
     * 处理已经结束的活动
     * @author zhulinjie
     */
    public function over()
    {
        // 获取已经结束但未添加结束标记的活动
        $ctime = time();
        $overActivity = $this->activity->overActivity($ctime);

        if(!empty($overActivity)){
            try {
                \DB::beginTransaction();
                foreach ($overActivity as $activity) {
                    // 对已经结束的活动添加结束标记
                    $activity->is_over = 1;
                    if (!$activity->save()) {
                        throw new \Exception('对已经结束的活动添加结束标记失败，活动ID[' . $activity->id . ']', 400);
                    }

                    // 对活动结束的货品恢复原价
                    foreach ($activity->cargos as $cargo) {
                        $cargo->cargo_discount = $cargo->cargo_price;
                        if (!$cargo->save()) {
                            throw new \Exception('对活动结束的货品恢复原价失败，商品ID[' . $cargo->id . ']', 400);
                        }
                        $arr = $cargo->toArray();
                        unset($arr['pivot']);
                        // 更新缓存
                        \Redis::hmset(HASH_CARGO_INFO_ . $cargo->id, $arr);
                    }
                }
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::info('Error|----[行号：' . $e->getLine() . '错误信息：' . $e->getMessage() . ']');
            }
        }
    }
}