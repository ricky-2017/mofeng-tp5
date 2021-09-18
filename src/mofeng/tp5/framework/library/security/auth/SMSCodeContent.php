<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 1:56
 */

namespace mofeng\tp5\security\auth;


class SMSCodeContent extends AuthContent {

    /**
     * @var string 用户电话号码
     */
    private $phoneNumber;

    /**
     * @var string 验证码
     */
    private $captcha;

    /**
     * @var string 验证码版本
     */
    private $captchaVersion;

    /**
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getCaptcha() {
        return $this->captcha;
    }

    /**
     * @param string $captcha
     */
    public function setCaptcha($captcha) {
        $this->captcha = $captcha;
    }

    /**
     * @return string
     */
    public function getCaptchaVersion() {
        return $this->captchaVersion;
    }

    /**
     * @param string $captchaVersion
     */
    public function setCaptchaVersion($captchaVersion) {
        $this->captchaVersion = $captchaVersion;
    }

}