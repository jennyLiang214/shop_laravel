<?php

namespace App\Http\Controllers\Home;

use App\Repositories\UserInfoRepository;
use App\Tools\Common;
use App\Tools\LogOperation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserInfoController extends Controller
{

    /**
     * @var LogOperation
     */
    protected $log;
    /**
     * @var UserInfoRepository
     */
    protected $userInfo;
    protected $disk;

    public function __construct
    (
        LogOperation $logOperation,
        UserInfoRepository $userInfoRepository
    )
    {

        $this->log = $logOperation;
        $this->userInfo = $userInfoRepository;
        $this->disk = \Storage::disk('qiniu');

    }

    /**
     * 获取用户基本信息
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function information()
    {

        // 从session中获取用户进本数据
        $userInfo = \Session::get('userInfo');
        // 判断数据是否获取成功
        if(empty($userInfo)) {
            // 失败 赋值默认值
            $userInfo = [];
        }
        // 返回用户信息视图
        return view('home.userInfo.information',['userInfo'=>$userInfo]);
    }

    /**
     * 更新用户基本信息
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateMessage(Request $request)
    {
        // 需要更新的参数
        $param['nickname'] = $request['nickname'];
        $param['sex'] = $request['sex'];
        // 组装生日时间
        if($request['year'] !=0 && $request['month'] != 0 &&  $request['day'] != 0) {
            $param['birthday'] = $request['year'].'-'.$request['month'].'-'.$request['day'];
        }
        // 获取用户ID
        $userId = \Session::get('user')->user_id;
        // 更新数据
        $userInfoResult = $this->userInfo->update(['user_id' => $userId] ,$param);
        // 判断数据是否更新成功
        if(empty($userInfoResult)){
            // 更新失败，返回
            return back();
        }
        // 更新session 数据
        foreach ($param as $key => $item) {
            \Session::get('userInfo')->$key = $item;
        }
        // 成功记录用户操作日志
        $logMessage = Common::logMessageForInside($userId, config('log.userLog')[2]);
        // 填写操作日志
        $this->log->writeUserLog($logMessage,false);

        // 返回用户信息展示页
        return redirect('/home/userInfo/information');

    }

    public function uploadAvatar(Request $request)
    {
        // 判断是否有图标上传，并且检查图片是否合法
        if ($request->hasFile('photo') && checkImage($file = $request->file('photo'))) {
            // 上传七牛文件云存储后返回图片名字
            $imageName = $this->disk->put(IMAGE_PATH, $file);
            $result = $this->userInfo->update(['user_id'=>\Session::get('userInfo')->user_id],['avatar'=>$imageName]);
            if(!empty($result)) {
                \Session::get('userInfo')->avatar = env('QINIU_DOMAIN').$imageName;
                return responseMsg($imageName,200);
            }
            responseMsg('上传失败',400);
        }
        return responseMsg('上传失败',400);
    }

}
