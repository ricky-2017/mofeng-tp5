<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/11
 * Time: 14:47
 */

namespace mofeng\tp5\exception\handler;

use Exception;
use mofeng\tp5\constants\ReturnCode;
use mofeng\tp5\exception\BizException;
use think\Db;
use think\exception\DbException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\RouteNotFoundException;
use think\exception\ValidateException;
use think\facade\Log;

class ExceptionHandler extends Handle {
    public function render(Exception $e) {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return jsonReturnCode(ReturnCode::INVALID_PARAM, $e->getError(), $e->getMessage());
        }

        // 参数验证错误
        if ($e instanceof \InvalidArgumentException) {
            return jsonReturnCode(ReturnCode::INVALID_PARAM, [], $e->getMessage());
        }

        // 拦截路由未定义异常
        if($e instanceof RouteNotFoundException) {
            return jsonReturnCode(ReturnCode::REQUESTED_RESOURCE_NOT_FOUND);
        }

        // 业务异常记录日志
        if ($e instanceof BizException) {
            Log::notice("BizException: {code} => {msg}", ["code"=>$e->getCode(), "msg"=>$e->getMessage()]);
            return jsonReturnCode($e->getReturnCode(), $e->getData(), $e->getMessage());
        }

        // 数据库异常自动回滚
        if($e instanceof DbException) {
            Db::rollback();
            Log::notice("DbException: {code} => {msg}, exception:{e}", ["code"=>$e->getCode(), "msg"=>$e->getMessage(), "e" => $e]);
            return jsonReturnCode(ReturnCode::DB_OPERATION_ERROR, [], $e->getMessage());
        }

        // 交由系统处理
        if ($e instanceof HttpResponseException || $e instanceof HttpException) {
            return parent::render($e);
        }

        // 记录意外错误
        Log::error("Unexpected error:" . $e);

        return jsonReturnCode(ReturnCode::UNEXPECTED_ERROR, [], $e->getMessage());
    }
}