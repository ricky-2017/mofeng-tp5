<?php

use mofeng\tp5\constants\ReturnCode;
use mofeng\tp5\exception\BizException;
use think\Response;
use think\exception\HttpResponseException;
use think\facade\Env;

/**
 * 上一个请求的url
 * @return string
 */
function prevUrl(){
	return $_SERVER['HTTP_REFERER'];
}

/**
 * 返回json数据
 * @desc 构造函数中用，其他地方请用json()
 * @param array $data
 */
function responseJson($data = []){
    $response = Response::create($data, 'json');
    throw new HttpResponseException($response);
}

/**
 * uploads相对目录
 * @return string
 */
function getUploadsRelativePath(){
    if(!empty(config('service.SUB_DIR'))){
        return '/'. config('service.SUB_DIR').'/public/uploads';
    }else{
        return '/public/uploads';
    }
}

/**
 * uploads绝对目录
 * @return string
 */
function getUploadsAbsolutePath(){
    return Env::get('root_path').'/public/uploads';
}

/**
 * 获取网站基本域名地址
 * @return string
 */
function getBaseDomain(){
    if(!empty(config('service.SUB_DIR'))){
        return request()->domain(). '/'. config('service.SUB_DIR');
    }else {
        return request()->domain();
    }
}

/**
 * 获取extend目录路径
 * @return string
 */
function getExtendPath(){
    return Env::get('root_path').'/extend';
}

/**
 * 文件地址处理为相对于根目录
 * @param string $filePath 文件路径
 * @return string
 */
function toAbsolutePath($filePath){
    $string = preg_replace('/' . config("service.SUB_DIR") . '/', '', $filePath);
    $string = preg_replace('/\/\//', '', $string);
    return Env::get('root_path') . $string;
}

function isLogDebugEnable() {
    $logLevel = config("log.level");

    return empty($logLevel)
        || (is_string($logLevel) && strcasecmp("debug", $logLevel))
        || (is_array($logLevel) && in_array("debug", array_map("strtolower", $logLevel)));
}

function UUID(){
    $uuid = '';
    if (function_exists('uuid_create') === true){
        $uuid = uuid_create(1);
    }else{
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $uuid =  vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    return $uuid;
}

/**
 * 向外抛出业务异常
 * @see BizException
 * @param array $code
 * @param string $message
 * @param array $data 额外调试信息
 * @param Throwable $previous 异常堆栈
 * @throws BizException 业务异常
 *
 */
function bizException(array $code, $message = "", $data = [], Throwable $previous = null) {
    throw new BizException($code, $message, $data, $previous);
}

/**
 * 接口返回代码通用函数
 * @param array $code
 * @param array $data
 * @param string $msg
 * @return \think\response\Json
 */
function jsonReturnCode(array $code = ReturnCode::UNDEFINED, $data = [], $msg = '') {
    $return_data = [
        'code' => $code[0],
        'msg' => empty($msg) ? $code[1] : $msg,
        'data' => $data,
    ];

    return json($return_data);
}

/**
 * 接口返回成功
 * @param array $data
 * @param string $msg
 * @return \think\response\Json
 */
function jsonSuccess($data=[], $msg='') {
    return jsonReturnCode(ReturnCode::SUCCESS, $data, $msg);
}

/**
 * 向外抛出异常并直接以json格式进行响应
 * @param array $code 业务返回码
 * @param array $data 业务响应数据
 * @param string $msg 业务响应消息
 */
function abortJsonReturnCode(array $code = ReturnCode::UNDEFINED, $data = [], $msg = '') {
    abort(jsonReturnCode($code, $data, $msg));
}