<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 15:36
 */

namespace mofeng\tp5\utils\sms;


use mofeng\tp5\constants\BaseConstants;

interface SMSCodeApi {

    /**
     * @param string $phoneNumber 手机号码
     * @param int 验证码过期时间
     * @param array $templateParam 模板参数
     * @return string 验证码版本号
     */
    function sendSMSCode($phoneNumber, $expire = BaseConstants::DEFAULT_PHONE_CAPTCHA_EXPIRE, $templateParam = []);

    /**
     * 查询余额
     * @param null $config 查询参数
     * @return mixed
     */
    function queryBalance($config = null);
}