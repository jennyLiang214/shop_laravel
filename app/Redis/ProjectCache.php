<?php
/**
 * Project redis 缓存仓库
 * @author 张洵之
 */

namespace App\Redis;

use App\Tools\CustomPage;
use App\Store\ProjectStore;
use Log;

class ProjectCache extends MasterCache
{
    private static $lkey = LIST_PROJECT_INFO_;      //项目list表key
    private static $hkey = HASH_PROJECT_INFO_;     //项目hash表key

    private static $project_store;

    public function __construct(ProjectStore $projectStore)
    {
        self::$project_store = $projectStore;
    }


    /**
     * 创建listKey
     * @param int $type list索引
     * @return bool
     * author 张洵之
     */
    public function createList($type)
    {
        //type : 11 为全部在线项目；1-10为其他融资阶段的项目
        if($type == 11) {
            $temp = self::$project_store->getList(['status' => 1], 'guid');
        }else{
            $temp = self::$project_store->getList(['status' => 1, 'financing_stage' => $type], 'guid');
        }

        if ($temp) {
            $result = $this ->rPushLists(self::$lkey.$type, $temp);
            if(!$result) {
                Log::info('Redis添加失败（'.self::$lkey.$type.'=>'.$temp.'）');
            }
            return true;//有数据返回true
        }else{
            return false;//无数据返回false
        }
    }

    /**
     * 创建hash
     * @param object|array $data
     * @return bool
     * author 张洵之
     */
    public function createHash($data)
    {
        if(!$data) return false;

        $temp = CustomPage::objectToArray($data);
        foreach ($temp as $value) {
            $index = self::$hkey . $value['guid'];
            $result = $this->addHash($index, $value);

            if(!$result) {
                Log::info('Redis添加失败（'.$index.'=>'.$temp.'）');
            }
        }
    }

    /**
     * 拿取项目缓存
     * @param $indexArray
     * @return array
     * author 张洵之
     */
    public function getProjectHash($indexArray)
    {
        if(!$indexArray) return false;

        $data = array();
        foreach ($indexArray as $value) {
            $index = self::$hkey . $value;

            if ($this->exists($index)) {
                $data[] = CustomPage::arrayToObject($this->getHash($index));
            } else {
                $temp[0] = self::$project_store->getOneData(['guid' => $value]);

                if(!$temp[0]) return false;

                if($temp[0]->status == 1) {
                    $this->createHash($temp);
                }

                $data[] = $temp[0];
            }

        }
        return $data;
    }

    /**
     * 分页返回项目数据
     * author 张洵之
     * @param int $nowPage 当前页
     * @param int $pageNum 一页的数据量
     * @param array $where 条件
     * @return array|null
     * author 张洵之
     */
    public function getPageData($nowPage, $pageNum, $where)
    {
        //格式化索引
        if(empty($where['financing_stage'])) {
            $type = 11;
        }else {
            $type = (int)$where['financing_stage'];
        }
        //检查索引的listKey是否存在
        if(!$this->exists(self::$lkey.$type)) {
            //创建索引(这里判断的是数据库是否有数据)
            if(!$this->createList($type)) return false;

            $indexData = $this->getPageLists(self::$lkey.$type, $pageNum, $nowPage);

            if($indexData){
                $data = $this->getProjectHash($indexData);
            }else{
                $data = self::$project_store->getPage($nowPage, $pageNum, $where);
            }

        }else{
            $indexData = $this->getPageLists(self::$lkey.$type, $pageNum, $nowPage);
            $data = $this->getProjectHash($indexData);
        }

        return $data;
    }

    /**
     * 针对首页刷新随机项目数据的方法
     * @param $number
     * @return array|bool|mixed|null
     * author 张洵之
     */
    public function takeData($number)
    {
        $index = self::$lkey.'11';
        $lkeyLength = $this->getLength($index);

        if($lkeyLength == 0) {

            if(!$this->createList(11)) return false;

            $data = self::$project_store->takeData($number);
            $this->createHash($data);
            return $data;

        }elseif($lkeyLength<$number) {

            return $this->getPageData(1, $lkeyLength, []);

        }else {
            $totalPage = (int)ceil($lkeyLength/PAGENUM);
            $indexArray = $this->getPageLists($index, $number, rand(1,$totalPage-1));
            return  $this->getProjectHash($indexArray);
        }
    }

    /**
     * 将后台推送项目加入缓存
     * @param object $data
     * author 张洵之
     */
    public function insertCache($data)
    {
        $this ->lPushLists(self::$lkey.$data->financing_stage, $data->guid);
        $this ->lPushLists(self::$lkey.'11', $data->guid);

        if($this->exists(self::$hkey.$data->guid)) {
            $result3 = $this->delKey(self::$hkey.$data->guid);
            if(!$result3) {
                Log::info('Redis哈希移出失败,key=>'.self::$hkey.$data->guid);
            }
        }

        $this->createHash([$data]);
    }

    /**
     * 将缓存移出缓存（后台）
     * @param object $data
     * author 张洵之
     */
    public function deletCache($data)
    {
        $result1 = $this->delList(self::$lkey.$data->financing_stage, $data->guid);
        $result2 = $this ->delList(self::$lkey.'11', $data->guid);
        $result3 = $this->delKey(self::$hkey.$data->guid);

        if(!$result1) {
            Log::info('Redis索引移出失败,'.self::$lkey.$data->financing_stage.'=>'.$data->guid.'');
        }

        if(!$result2) {
            Log::info('Redis索引移出失败,'.self::$lkey.'11'.'=>'.$data->guid.'');
        }

        if(!$result3) {
            Log::info('Redis哈希移出失败,key=>'.self::$hkey.$data->guid);
        }

    }

    /**
     * 将指定条件查询到的所有guid加入redis list中
     * @param $where [] 查询条件
     * @param $key string list KEY
     * @author 郭庆
     */
    public function mysqlToList($where, $key)
    {
        if ($this->exists($key)) return $this->getBetweenList($key, 0, -1);
        //从数据库获取所有的guid
        $guids = self::$project_store->getList($where, 'guid');

        if (!$guids) return [];
        //将获取到的所有guid存入redis
        $redisList = $this->rPushLists($key, $guids);
        if (!$redisList) {
            Log::error("将数据库数据写入list失败,list为：".$key);
            return $guids;
        }else{
            return $guids;
        }
    }

    /**
     * 检测list
     * @param
     * @return bool
     * @author 郭庆
     */
    public function checkList($key, $where)
    {
        $sqlLength = self::$project_store->getCount($where);
        if (!$this->exists($key)) return true;
        $listLength = count(array_unique($this->getBetweenList($key, 0, -1)));

        if ($sqlLength != $listLength) {
            if (!$this->delKey($key)) return false;
            return $this->mysqlToList($where, $key);
        }else{
            return true;
        }
    }

    /**
     * 任务调度查list异常
     * @param
     * @return array
     * @author 郭庆
     */
    public function check()
    {
        if (!$this->checkList(self::$lkey.'1', ['status' => 1, 'financing_stage' => 1])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'1');
        if (!$this->checkList(self::$lkey.'2', ['status' => 1, 'financing_stage' => 2])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'2');
        if (!$this->checkList(self::$lkey.'3', ['status' => 1, 'financing_stage' => 3])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'3');
        if (!$this->checkList(self::$lkey.'4', ['status' => 1, 'financing_stage' => 4])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'4');
        if (!$this->checkList(self::$lkey.'5', ['status' => 1, 'financing_stage' => 5])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'5');
        if (!$this->checkList(self::$lkey.'6', ['status' => 1, 'financing_stage' => 6])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'6');
        if (!$this->checkList(self::$lkey.'7', ['status' => 1, 'financing_stage' => 7])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'7');
        if (!$this->checkList(self::$lkey.'8', ['status' => 1, 'financing_stage' => 8])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'8');
        if (!$this->checkList(self::$lkey.'9', ['status' => 1, 'financing_stage' => 9])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'9');
        if (!$this->checkList(self::$lkey.'10', ['status' => 1, 'financing_stage' => 10])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'10');
        if (!$this->checkList(self::$lkey.'11', ['status' => 1])) Log::warning('任务调度，检测到list异常，未成功解决'.self::$lkey.'11');
    }
}