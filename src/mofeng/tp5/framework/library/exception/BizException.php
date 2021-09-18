<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/11
 * Time: 11:29
 */

namespace mofeng\tp5\exception;
use mofeng\tp5\constants\ReturnCode;
use think\Exception;
use Throwable;

/**
 * 统一业务异常
 */
class BizException extends Exception {

    protected $returnCode;

    public function __construct(array $returnCode = ReturnCode::UNDEFINED,
                                $message = '',
                                $data=[],
                                Throwable $previous = null
    ) {
        $this->message = $message ? $message : ($returnCode[1] ? $returnCode[1] : '');
        $this->returnCode = $returnCode;
        $this->code = $returnCode[0];
        $this->data = $data;
        parent::__construct($this->message, $returnCode[0], $previous);
    }

    public function getReturnCode(): array {
        return $this->returnCode;
    }
}