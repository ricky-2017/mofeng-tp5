<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 1:55
 */

namespace mofeng\tp5\security\auth;


class PasswordContent extends AuthContent {

    private $userName;
    private $password;

    /**
     * @return mixed
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName) {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }


}