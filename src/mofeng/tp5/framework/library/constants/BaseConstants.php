<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 15:23
 */

namespace mofeng\tp5\constants;

/**
 * 公共常数类
 * Class BaseConstants
 * @package mofeng\tp5\constants
 */
class BaseConstants {

    /**
     * 手机登陆验证码缓存前缀
     */
    const PHONE_LOGIN_CAPTCHA_CACHE_PREFIX = "captcha:";

    /**
     * 验证码默认过期时间
     */
    const DEFAULT_PHONE_CAPTCHA_EXPIRE = 60;
}