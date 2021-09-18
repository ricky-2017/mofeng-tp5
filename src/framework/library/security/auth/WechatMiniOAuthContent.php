<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/9
 * Time: 17:08
 */

namespace mofeng\tp5\security\auth;


class WechatMiniOAuthContent extends OAuthContent {
    /**
     * @var string 加密数据
     */
    private $encryptedData;

    /**
     * @var string 初始量iv
     */
    private $iv;

    /**
     * @return string
     */
    public function getEncryptedData() {
        return $this->encryptedData;
    }

    /**
     * @return string
     */
    public function getIv() {
        return $this->iv;
    }

    /**
     * @param string $encryptedData
     */
    public function setEncryptedData($encryptedData) {
        $this->encryptedData = $encryptedData;
    }

    /**
     * @param string $iv
     */
    public function setIv($iv) {
        $this->iv = $iv;
    }
}