<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/30
 * Time: 17:37
 */

namespace mofeng\tp5\security\auth;


use mofeng\tp5\utils\wechat\WxApi;

abstract class WechatAuthenticator extends OAuthenticator {
    const SNSAPI_USERINFO = "snsapi_userinfo";
    const SNSAPI_LOGIN = "snsapi_login";
    const SNSAPI_BASE = "snsapi_base";
    const ACCESS_OPEN_USER_INFO_URL = [
        self::SNSAPI_USERINFO => "https://api.weixin.qq.com/sns/userinfo",
        self::SNSAPI_LOGIN => "https://api.weixin.qq.com/sns/userinfo",
        self::SNSAPI_BASE => ""
    ];

    function getAccessTokenByClientCredential($clientCredential) {
        return WxApi::get_access_token();
    }

    function checkOAuthType($oauthType) {
        return in_array($oauthType, [self::SNSAPI_LOGIN, self::SNSAPI_USERINFO, self::SNSAPI_BASE]);
    }

    function getAccessOpenUserInfoUrl($oauthType) {
        return self::ACCESS_OPEN_USER_INFO_URL[$oauthType];
    }
}