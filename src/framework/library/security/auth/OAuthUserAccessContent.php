<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/26
 * Time: 17:34
 */

namespace mofeng\tp5\security\auth;


class OAuthUserAccessContent {
    /**
     * @var string 服务访问令牌
     */
    private $accessToken;

    /**
     * @var string 服务方对三方应用的唯一标识
     */
    private $openid;

    /**
     * @var string 联合应用id
     */
    private $unionid;

    /**
     * @var string 服务方用户信息拉取地址
     */
    private $baseAccessUrl;

    /**
     * OAuthUserAccessContent constructor.
     * @param string $accessToken
     * @param string $openid
     * @param string $baseAccessUrl
     */
    public function __construct($accessToken, $openid, $unionid, $baseAccessUrl) {
        $this->accessToken = $accessToken;
        $this->openid = $openid;
        $this->unionid = $unionid;
        $this->baseAccessUrl = $baseAccessUrl;
    }


    /**
     * @return mixed
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @return mixed
     */
    public function getOpenid() {
        return $this->openid;
    }

    /**
     * @param mixed $openid
     */
    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    /**
     * @return string
     */
    public function getBaseAccessUrl() {
        return $this->baseAccessUrl;
    }

    /**
     * @param string $baseAccessUrl
     */
    public function setBaseAccessUrl($baseAccessUrl) {
        $this->baseAccessUrl = $baseAccessUrl;
    }

    /**
     * @return string
     */
    public function getUnionid(): string {
        return $this->unionid;
    }

    /**
     * @param string $unionid
     */
    public function setUnionid(string $unionid): void {
        $this->unionid = $unionid;
    }

}