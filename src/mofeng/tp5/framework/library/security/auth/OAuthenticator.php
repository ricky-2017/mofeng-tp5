<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 17:38
 */

namespace mofeng\tp5\security\auth;

/**
 * OAuth认证抽象类
 * Class OAuthenticator
 * @package mofeng\tp5\security\auth
 */
abstract class OAuthenticator implements Authenticator {

    /**
     * @param OAuthContent $authContent
     * @throws InValidAuthContentException 认证内容非法
     * @throws OAuthTypeNotSupportedException OAuthType认证类型不支持
     * @throws RequestAccessContentFailedException 获取访问令牌信息失败
     * @return array 认证服务器返回结果
     */
    function auth($authContent) {
        if (empty($authContent->getOAuthType())) {
            throw new InValidAuthContentException("oauthType不能为空");
        }

        if ($this->checkOAuthType($authContent->getOAuthType()) !== true) {
            throw new OAuthTypeNotSupportedException("oauthType不支持");
        }

        $accessContent = $this->getAccessContent($authContent);

        return $this->getOAuthUser($accessContent);
    }

    /**
     * 通过用户授权获得的授权码向认证服务器换取访问令牌
     * @param OAuthContent $authContent
     * @return OAuthUserAccessContent 访问令牌
     * @throws RequestAccessContentFailedException
     */
    abstract function getAccessContent($authContent);

    /**
     * 通过令牌信息拉取用户信息
     * @param OAuthUserAccessContent $accessContent
     * @return mixed
     */
    abstract function getOAuthUser(OAuthUserAccessContent $accessContent);

    /**
     * 直接通过客户端向认证服务器获取访问令牌
     * @param array|object $clientCredential 客户端认证资料
     * @return string 访问令牌
     */
    abstract function getAccessTokenByClientCredential($clientCredential);

    /**
     * 是否支持授权类型
     * @param $oauthType
     * @return bool
     */
    abstract function checkOAuthType($oauthType);

    /**
     * 拉取用户信息的url
     * @param $oauthType
     * @return string
     */
    abstract function getAccessOpenUserInfoUrl($oauthType);

}