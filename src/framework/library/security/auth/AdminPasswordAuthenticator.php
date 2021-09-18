<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 9:28
 */

namespace mofeng\tp5\security\auth;

use app\system\model\Admin;

class AdminPasswordAuthenticator extends BasicAuthenticator {

    /**
     * @var Admin
     */
    private $adminModel;

    /**
     * AdminPasswordAuthenticator constructor.
     * @param Admin $admin
     */
    public function __construct(Admin $admin) {
        $this->adminModel = $admin;
    }


    function auth($authContent) {
        $userName = $authContent->getUserName();
        $password = $authContent->getPassword();
        $admin = $this->adminModel->where([
            "admin_username" => $userName
        ])->find();
        if (!$admin) {
            throw new AuthenticationFailedException("认证失败，管理员：$userName 不存在");
        }

        if ($admin->admin_password !== md5($password)) {
            throw new AuthenticationFailedException("认证失败，密码错误");
        }

        if ($admin->admin_status == -1) {
            throw new AuthenticationFailedException("认证失败，管理员已锁定");
        }

        return $admin;
    }

}