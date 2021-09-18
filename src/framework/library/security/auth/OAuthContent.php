<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 1:44
 */

namespace mofeng\tp5\security\auth;


class OAuthContent extends AuthContent {

    /**
     * @var string 授权类型
     */
    private $oauthType;

    /**
     * @var string 授权码
     */
    private $authenticationCode;

    /**
     * @var array 客户端凭证
     */
    private $clientCredential;

    /**
     * @return string
     */
    public function getOAuthType() {
        return $this->oauthType;
    }

    /**
     * @param string $oauthType
     */
    public function setOAuthType($oauthType) {
        $this->oauthType = $oauthType;
    }

    /**
     * @return string
     */
    public function getAuthenticationCode() {
        return $this->authenticationCode;
    }

    /**
     * @param string $authenticationCode
     */
    public function setAuthenticationCode($authenticationCode) {
        $this->authenticationCode = $authenticationCode;
    }

    /**
     * @return array
     */
    public function getClientCredential() {
        return $this->clientCredential;
    }

    /**
     * @param array $clientCredential
     */
    public function setClientCredential($clientCredential) {
        $this->clientCredential = $clientCredential;
    }


}