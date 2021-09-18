<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 16:50
 */

namespace mofeng\tp5\security\token;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTBuilder implements Token {
    function build($config=[]) {
        return self::buildJWT($config);
    }

    private static function buildJWT($config) {
        $config['duration'] = (!empty($config['duration']) && is_numeric($config['duration'])) ?: 1*24*3600;
        $builder =  (new Builder())
            ->setIssuer($config['payload']['iss']) // 签发者
            ->setAudience($config['payload']['aud']) // 接受者
            ->setId(UUID(), true) // jwt的id
            ->setIssuedAt(time()) // 签发时间
            ->setExpiration(time() + $config['duration']) // 过期时间
            ->setNotBefore(time()); // 生效时间

        if(!empty($config['payload']) && is_array($config['payload'])) {
            foreach ($config['payload'] as $key => $value) {
                $builder = $builder->set($key, $value);
            }
        }

        $builder = $builder->sign(new Sha256(), $config['key']);

        return $builder->getToken();

    }
}