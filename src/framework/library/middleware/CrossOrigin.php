<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 11:21
 */

namespace mofeng\tp5\middleware;


use think\facade\Request;

class CrossOrigin {

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next) {
        header("Access-Control-Allow-Origin: *");
        if($request->isOptions()) {
            header("Access-Control-Allow-Headers: Content-Type,Authorization");
            header("Access-Control-Allow-Methods: *");
            abort(204);
        }

        return $next($request);
    }
}