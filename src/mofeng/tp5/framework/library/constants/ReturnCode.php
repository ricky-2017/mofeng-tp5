<?php
namespace mofeng\tp5\constants;

/**
 * api统一返回状态代码
 * Author: JerryChaox
 * Date: 2018/2/6
 * Time: 10:41
 */
class ReturnCode
{
    const SUCCESS = [10000, 'SUCCESS'];

    /** 基础类返回码 **/
    const INVALID_PARAM = [40000, '请求参数错误'];
    const UNSUPPORTED_HTTP_METHOD = [40001, 'HTTP请求方法错误'];

    const NULL_ACCESS_TOKEN = [40300, '授权标识为空'];
    const ACCESS_TOKEN_EXPIRE = [40301, '授权标识已过期'];
    const ACCESS_TOKEN_REJECTED = [40302, '授权标识已拒绝'];
    const REQUESTED_RESOURCE_FORBIDDEN = [40303, '无权限访问该资源'];
    const AUTHENTICATION_FAILED = [40304, '鉴权失败'];
    const REQUESTED_RESOURCE_NOT_FOUND = [40400, '请求资源不存在'];

    const DATA_NOT_FOUND = [40500, '数据不存在'];
    const DUPLICATE_DATA_NOT_ALLOW = [40501, '数据不能重复'];
    const DATA_SIZE_TOO_BIG = [40502, '数据过大'];
    const DATA_CONSTRAINT_ERROR = [40503, "数据约束错误"];

    const UNEXPECTED_ERROR = [50000, '意外错误, 请联系开发者'];
    const DB_OPERATION_ERROR = [50001, '数据库操作异常'];
    const SYSTEM_UPDATING = [50002, '系统升级中'];
    const TOKEN_KEY_UNDEFINED = [50003, '缺少token签发密钥'];

    const CAPTCHA_CODE_TOO_FREQUENT = [60000, '验证码获取过于频繁'];
    const SMS_API_FAILED = [60001, '短信接口调取失败'];
    const CAPTCHA_OR_VERSION_INVALID = [60002, '验证码校验参数不能空'];
    const CAPTCHA_EXPIRED = [60003, '验证码已过期'];
    const CAPTCHA_NOT_MATCH = [60004, '验证码校验失败'];

    const UNDEFINED = [90000, '未定义消息'];

    const ADMIN_PASSWORD_ERROR = [30000, '管理员密码错误'];
    const ADMIN_FORBIDDEN = [30001, '管理员已被禁用'];
    const SYSTEM_ADMIN_DELETE_NOT_ALLOW = [30002, '无法删除系统管理员'];
    const SYSTEM_ADMIN_EDIT_NOT_ALLOW = [30003, '无法编辑系统管理员'];

    /** 微信类错误返回码 */
    const WX_KEYWORD_EXISTED = [40000];
    const WX_KEYWORD_NOT_EXIST = [40001];
    const REACHED_MAX_FIRST_LEVEL_WX_MENU = [40002];
    const REACHED_MAX_SECOND_LEVEL_WX_MENU = [40003];
    const WX_PUSH_MENU_ERROR = [40004];
    const WX_API_FAILED = [40005, '调取微信api错误'];
    const WX_AUTHENTICATION_REQUIRE = [40006, '需要进行微信授权'];
}