<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 13:45
 */

namespace mofeng\tp5\middleware;


use think\facade\Request;

class BeforeResponseWrite {

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next) {
        $response = $next($request);
        $data = $response->getData();
        if(!isset($data['code']) || !isset($data['msg']) || !isset($data['data'])) {
            return jsonSuccess($data);
        }

        return $next($request);
    }
}