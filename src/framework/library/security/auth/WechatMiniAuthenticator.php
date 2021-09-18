<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 17:46
 */

namespace mofeng\tp5\security\auth;


use mofeng\tp5\constants\ReturnCode;
use mofeng\tp5\http\utils\HttpRequest;
use mofeng\tp5\utils\wechat\WxBizDataCrypt;

class WechatMiniAuthenticator extends WechatAuthenticator {


    /**
     * @param WechatMiniOAuthContent $authContent
     * @return OAuthUserAccessContent
     * @throws RequestAccessContentFailedException
     */
    function getAccessContent($authContent) {

        $url = 'https://api.weixin.qq.com/sns/jscode2session';

        $param = [
            'appid' => $authContent->getClientCredential()['appid'],
            'secret' => $authContent->getClientCredential()['secret'],
            'js_code' => $authContent->getAuthenticationCode(),
            'grant_type' => 'authorization_code'
        ];

        $result = HttpRequest::curl_get($url, $param);

        if (!isset($result['openid'])) {
            throw new RequestAccessContentFailedException(ReturnCode::WX_API_FAILED, "获取openid失败，错误码：" . $result['errmsg'] . ", 错误信息: " . $result['errcode']);
        }


        $pc = new WxBizDataCrypt(Config('app.APP_ID'), $result['session_key']);
        $data = null;
        $errCode = $pc->decryptData($authContent->getEncryptedData(), $authContent->getIv(), $data);

        if ($errCode != 0) {
            throw new RequestAccessContentFailedException(ReturnCode::WX_API_FAILED, '解密数据失败', $errCode);
        }

        $data = json_decode($data, true);
        unset($data['watermark']);
        return new OAuthUserAccessContent(null, $data['openId'], $data['unionId'], null);

    }

    function getOAuthUser(OAuthUserAccessContent $accessContent) {
        return [
            'openid' => $accessContent->getOpenid(),
            'unionid' => $accessContent->getUnionid()
        ];
    }


}