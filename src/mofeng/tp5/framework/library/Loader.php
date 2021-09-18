<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/10
 * Time: 10:29
 */

namespace mofeng\tp5;

use think\Container;

class Loader {

    public static function init($appPath, $module = '') {
        // 定位模块目录
        $module = $module ? $module . DIRECTORY_SEPARATOR : '';
        $path = $appPath . $module;

        // 加载行为扩展文件
        if (is_file($path . 'tags.php')) {
            $tags = include $path . 'tags.php';
            if (is_array($tags)) {
                Container::get('app')->hook->import($tags);
            }
        }

        // 加载公共文件
        if (is_file($path . 'common.php')) {
            include_once $path . 'common.php';
        }

        // 加载中间件
        if (is_file($path . 'middleware.php')) {
            $middleware = include $path . 'middleware.php';
            if (is_array($middleware)) {
                Container::get('app')->middleware->import($middleware);
            }
        }

        // 注册服务的容器对象实例
        if (is_file($path . 'provider.php')) {
            $provider = include $path . 'provider.php';
            if (is_array($provider)) {
                Container::get('app')->bindTo($provider);
            }
        }

        // 自动读取配置文件
        if (is_dir($path . 'config')) {
            $dir = $path . 'config' . DIRECTORY_SEPARATOR;
        }

        $files = isset($dir) ? scandir($dir) : [];

        foreach ($files as $file) {
            if ('.' . pathinfo($file, PATHINFO_EXTENSION) === Container::get('app')->getConfigExt()) {
                Container::get('app')->config->load($dir . $file, pathinfo($file, PATHINFO_FILENAME));
            }
        }
    }
}