<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/27
 * Time: 1:43
 */

namespace mofeng\tp5\security\auth;


abstract class AuthContent {
    protected $authType;

    /**
     * @return mixed
     */
    public function getAuthType() {
        return $this->authType;
    }

    /**
     * @param mixed $authType
     */
    public function setAuthType($authType) {
        $this->authType = $authType;
    }

    public function __toString() {
        return json_encode($this);
    }


}