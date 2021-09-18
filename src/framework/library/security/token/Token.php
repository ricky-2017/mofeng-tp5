<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:51
 */

namespace mofeng\tp5\security\token;


interface Token {

    /**
     * @param array $config token配置
     * @return string
     */
    function build($config=[]);
}