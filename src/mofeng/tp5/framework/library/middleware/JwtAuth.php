<?php
/**
 * Created by PhpStorm.
 * User: JerryChaox
 * Date: 2018/10/4
 * Time: 16:40
 */

namespace mofeng\tp5\middleware;


use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use mofeng\tp5\constants\ReturnCode;
use mofeng\tp5\security\token\TokenSignSecretUndefinedException;

class JwtAuth {
    /**
     * 1. 初次登录：用户初次登录，输入用户名密码 （可能是其他形式登陆）
     * 2. 密码验证：服务器从数据库取出用户名和密码进行验证
     * 3. 生成JWT：服务器端验证通过，根据从数据库返回的信息，以及预设规则，生成JWT
     * 4. 返还JWT：服务器的HTTP RESPONSE中将JWT返还
     * 5. 带JWT的请求：以后客户端发起请求，HTTP REQUEST HEADER中的Authorization字段都要有值，为JWT
     * @param \think\Request $request
     * @param \Closure $next
     * @throws TokenSignSecretUndefinedException
     * @return mixed
     */
    public function handle($request, \Closure $next) {
        if(!config("token.ENABLE")) {
            return $next($request);
        }

        $key = config("token.JWT_SECRET");

        // 必需配置签发密钥
        if(!$key) {
            throw new TokenSignSecretUndefinedException("token签发密钥未配置");
        }

        // 从get的query获取token
        if(!($token = request()->get('token'))
        // 从http头部获取token
            && !($token = preg_replace("/^Bearer /", "", trim($request->header("Authorization")))))
            abortJsonReturnCode(ReturnCode::NULL_ACCESS_TOKEN);
        $token = (new Parser())->parse((string) $token);

        // 验证jwt的签名
        if(!$token->verify(new Sha256(), $key)) {
            abortJsonReturnCode(ReturnCode::ACCESS_TOKEN_REJECTED, [], "token已被篡改");
        }

        // 验证是否过期
        /*if ($token->isExpired()) {
            abortJsonReturnCode(ReturnCode::ACCESS_TOKEN_EXPIRE);
        }*/

        // 验证jwt的所属类数据
        $validationData = new ValidationData();
        // 验证签发者
        $validationData->setIssuer(config("token.JWT_ISSUER") ? config("token.JWT_ISSUER") : "iss");
        // 不验证接收者, 交由开发者通过payload的user_id去验证

        // 注入jwt信息
        $request->token = $token;
        $request->payload = $token->getClaims();
        return $next($request);
    }
}