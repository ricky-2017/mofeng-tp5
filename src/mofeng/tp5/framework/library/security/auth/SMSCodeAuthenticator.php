<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 1:59
 */

namespace mofeng\tp5\security\auth;

use mofeng\tp5\constants\BaseConstants;
use mofeng\tp5\constants\ReturnCode;
use think\facade\Cache;

class SMSCodeAuthenticator extends BasicAuthenticator {

    /***
     * 短信验证码验证，失败时会直接抛出异常，成功返回true
     * @param SMSCodeContent $authContent
     * @throws AuthenticationFailedException
     * @return bool
     */
    function auth($authContent) {
        $phoneNumber = $authContent->getPhoneNumber();
        $captcha = $authContent->getCaptcha();
        $captchaVersion = $authContent->getCaptchaVersion();

        // 非法
        if (empty($captcha) || empty($captchaVersion)) {
            throw new AuthenticationFailedException(ReturnCode::CAPTCHA_OR_VERSION_INVALID);
        }

        $cacheKey = BaseConstants::PHONE_LOGIN_CAPTCHA_CACHE_PREFIX . $phoneNumber;
        $expected = Cache::get($cacheKey);

        // 过期
        if (empty($expected)) {
            throw new AuthenticationFailedException(ReturnCode::CAPTCHA_EXPIRED);
        }

        // 不匹配
        if ($expected !== "$captchaVersion|$captcha") {
            throw new AuthenticationFailedException(ReturnCode::CAPTCHA_NOT_MATCH);
        }

        // 匹配成功刷掉验证码
        Cache::rm($cacheKey);

        return true;
    }


}