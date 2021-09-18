<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 17:45
 */

namespace mofeng\tp5\security\auth;


use mofeng\tp5\constants\ReturnCode;
use mofeng\tp5\utils\http\HttpRequest;

class WechatOfficialAuthenticator extends WechatAuthenticator {


    function getAccessContent($authContent) {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';

        $param = [
            'appid' => $authContent->getClientCredential()['appid'],
            'secret' => $authContent->getClientCredential()['secret'],
            'code' => $authContent->getAuthenticationCode(),
            'grant_type' => 'authorization_code'
        ];
        $result = HttpRequest::curl_get($url, $param);

        if (!isset($result['openid'])) {
            throw new RequestAccessContentFailedException(ReturnCode::WX_API_FAILED, "获取openid失败，错误码：" . $result['errmsg'] . ", 错误信息: " . $result['errcode']);
        }

        return new OAuthUserAccessContent(
            $result['access_token']
            , $result['openid']
            , !empty($result['unionid']) ? $result['unionid'] : null
            , $this->getAccessOpenUserInfoUrl($authContent->getOAuthType())
        );
    }

    function getOAuthUser(OAuthUserAccessContent $accessContent) {

        $param = [
            "access_token" => $accessContent->getAccessToken(),
            "openid" => $accessContent->getOpenid(),
            "lang" => "zh_CN"
        ];

        if (empty($accessContent->getBaseAccessUrl())) {
            return ['openid' => $accessContent->getOpenid()];
        }

        $wxUserInfo = HttpRequest::curl_get($accessContent->getBaseAccessUrl(), $param);

        if (!isset($wxUserInfo['openid'])) {
            throw new RequestOpenUserInfoFailedException("拉取用户微信信息失败: 错误码: " . $wxUserInfo['errmsg'] . ", 错误信息: " . $wxUserInfo['errcode']);
        }

        return $wxUserInfo;
    }


}