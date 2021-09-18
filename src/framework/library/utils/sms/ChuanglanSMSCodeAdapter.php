<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 15:47
 */

namespace mofeng\tp5\utils\sms;


use mofeng\tp5\constants\BaseConstants;
use mofeng\tp5\constants\ReturnCode;
use think\facade\Cache;
use think\facade\Log;
use utilphp\util;

class ChuanglanSMSCodeAdapter implements SMSCodeApi {

    /**
     * @var ChuanglanSmsApi
     */
    private $smsApi;

    /**
     * ChuanglanSMSAdapter constructor.
     */
    public function __construct() {
        $this->smsApi = new ChuanglanSmsApi(
            config('sms.CHUANG_LAN_API_ACCOUNT'),
            config('sms.CHUANG_LAN_API_PASSWORD')
        );
    }

    function sendSMSCode($phoneNumber, $expire = BaseConstants::DEFAULT_PHONE_CAPTCHA_EXPIRE, $templateParam = []) {
        // 缓存key
        $cacheKey = BaseConstants::PHONE_LOGIN_CAPTCHA_CACHE_PREFIX . $phoneNumber;

        // 判断是否可以获取验证码
        if(Cache::get($cacheKey)) {
            bizException(ReturnCode::CAPTCHA_CODE_TOO_FREQUENT);
        }

        // 6位验证码
        $captcha = mt_rand(10000, 99999);
        $result = empty($templateParam)
            ? $this->smsApi->sendSMS($phoneNumber, $captcha)
            : $this->smsApi->sendTemplateSMS($phoneNumber, $captcha, $templateParam);
        $result = json_decode($result, true);

        // 接口调用失败
        if($result['code'] !== "0" ) {
            Log::warning("短信接口调用失败: {time} | {code} | {errorMsg} | {msgId}", $result);
            bizException(ReturnCode::SMS_API_FAILED);
        };

        // 用随机字符串生成验证码的一个版本号
        $version = util::random_string(16);

        // 锁定号码
        Cache::set($cacheKey, "$version|$captcha", config("sms.CAPTCHA_EXPIRE"));

        return $version;
    }


    function queryBalance($config = null) {
        return $this->smsApi->queryBalance();
    }


}