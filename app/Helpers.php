<?php

/**
 * 32 位 16 进制 Uuid
 *
 * @return string
 * @author: Luoyan
 */
function hexUuid()
{
    $uuid = \Ramsey\Uuid\Uuid::uuid1();

    return $uuid->getHex();
}

if (!function_exists('lang')) {
    /**
     * 获取本地化消息
     *
     * @param string $text
     * @param  array $parameters
     * @return string
     */
    function lang($text, $parameters = [])
    {
        return trans('message.' . $text, $parameters);
    }
}

/**
 * 消息返回
 *
 * @param $message
 * @param int $status
 * @return \Illuminate\Http\JsonResponse
 * @author: Luoyan
 */
function responseMsg($message, $status = 200)
{
    return response()->json([
        'ServerTime' => time(),
        'ServerNo'   => $status,
        'ResultData' => $message
    ]);
}


/**
 * 检测文件是否是图片
 *
 * @param Request $file
 * @return bool|string
 * @author: Luoyan
 */
function checkImage($file)
{
    // 验证文件是否合法
    if ($file->isValid()) {
        // 判断文件后缀是否是图片
        return in_array(strtolower($file->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png']);
    }

    return false;
}

if (!function_exists('dda')) {
    /**
     * dda 等于： dd to array
     *
     * @param $model
     * @author: Luoyan
     */
    function dda($model)
    {
        dd($model->toArray());
    }
}