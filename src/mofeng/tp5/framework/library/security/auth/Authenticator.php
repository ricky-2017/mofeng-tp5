<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/26
 * Time: 16:52
 */

namespace mofeng\tp5\security\auth;

/**
 * 认证功能类接口
 * Interface Authenticator
 * @package mofeng\tp5\security\auth
 */
interface Authenticator {

    /**
     * 对认证内容进行审核，并返回认证用户实体
     * @param array|object $authContent 认证内容
     * @return array|boolean 认证结果
     * @throws AuthenticationFailedException
     */
    function auth($authContent);
}