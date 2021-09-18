<?php

namespace mofeng\tp5\utils\wechat;

use think\facade\Cache;

/**
 * 微信常用api
 *
 * @author liuwenwei
 *
 */
class WxApi {
    /**
     * 获取access_token
     * // TODO: 将下列配置分离到另外一个配置文件当中，并作为入参
     */
    public static function get_access_token() {
        //来自管理控制中心
        if (!empty(config('app.IS_HAVE_WECHAT_CONTROL_CENTER')) && config('app.IS_HAVE_WECHAT_CONTROL_CENTER') == true) {
            $url = config('app.WECHAT_CONTROL_CENTER_GET_ACCESS_TOKEN_URL') . '?appid=' . config("app.APP_ID") . '&appsecret=' . config("app.APP_SECRET");
            $result = HttpRequest::curl_get($url);
            if ($result['code'] == 10000) {
                return $result['data']['access_token'];
            } else {
                exception($result['msg'], $result['code']);
            }
        } else {//来自本地管理
            // 优先从缓存中读取access_token
            if (Cache::has('access_token')) {
                return Cache::get('access_token');
            }

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config("app.APP_ID") . "&secret=" . config("app.APP_SECRET");
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $output = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                exception($error);
            } else {
                $result = json_decode($output, true);
                Cache::set('access_token', $result ['access_token'], 7000); // 缓存access_token
                return $result['access_token'];
            }
        }
    }

    /**
     * 获取jsApiTicket
     */
    public static function getJsApiTicket() {
        //来自管理控制中心
        if (!empty(config('app.IS_HAVE_WECHAT_CONTROL_CENTER')) && config('app.IS_HAVE_WECHAT_CONTROL_CENTER') == true) {
            $url = config('app.WECHAT_CONTROL_CENTER_GET_TICKET_URL') . '?appid=' . config("app.APP_ID") . '&appsecret=' . config("app.APP_SECRET");
            $result = HttpRequest::curl_get($url);
            if ($result['code'] == 10000) {
                return $result['data']['ticket'];
            } else {
                exception($result['msg'], $result['code']);
            }
        } else {//来自本地管理
            if (Cache::has('jsapi_ticket')) {
                return Cache::get('jsapi_ticket');
            } else {
                $accessToken = WxApi::get_access_token();
                // 如果是企业号用以下 URL 获取 ticket
                // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
                $result = HttpRequest::curl_get($url);
                if ($result['ticket']) {
                    Cache::set('jsapi_ticket', $result['ticket'], 7000);//记录缓存
                    return $result['ticket'];
                }
            }
        }
    }

    /**
     * 回复Text格式xml
     *
     * @param string $from
     *            要发送的用户名
     * @param string $to
     *            来自用户
     * @param string $contentStr
     *            回复的文本内容
     */
    public static function responseText($from, $to, $contentStr) {
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";
        $time = time();
        $resultStr = sprintf($textTpl, $from, $to, $time, $contentStr);
        return $resultStr;
    }

    /**
     * 回复图片格式xml
     *
     * @param string $from
     *            要发送的用户名
     * @param string $to
     *            来自用户
     * @param string $mediaId
     *            回复的图片ID
     */
    public static function responsePic($from, $to, $mediaId) {
        $picTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[image]]></MsgType>
					<Image>
					<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>";
        $time = time();
        $resultStr = sprintf($picTpl, $from, $to, $time, $mediaId);
        return $resultStr;
    }

    /**
     * 请确保您的libcurl版本是否支持双向认证，版本高于7.20.1
     * @desc 企业付款中用到
     * @param string $url
     * @param array $data
     * @param number $second
     * @param array $aHeader
     * @return mixed|boolean
     */
    public static function curl_post_ssl($url, $data, $second = 30, $aHeader = array()) {
        $ch = curl_init();
        // 超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_SSLCERT, config('app.APP_CERT_PATH'));
        curl_setopt($ch, CURLOPT_SSLKEY, config('app.APP_KEY_PATH'));

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, self::ToXml($data));
        $result = curl_exec($ch);
        if ($result) {
            curl_close($ch);

            //xml转换为数组
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);//转化为对象
            $result = json_decode(json_encode($postObj), true);//转化为数组

            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

    /**
     * 生成签名
     * @param array $param 数组参数
     * @return 签名
     */
    public static function MakeSign($param = array()) {
        // 签名步骤一：按字典序排序参数
        ksort($param);

        //格式化参数格式化成url参数
        $buff = "";
        foreach ($param as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $string = trim($buff, "&");

        // 签名步骤二：在string后加入KEY
        $string = $string . "&key=" . config('app.key');
        // 签名步骤三：MD5加密
        $string = md5($string);
        // 签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 数组转换成xml字符
     * @param array $arr 数组
     * @return xml
     */
    public static function ToXml($arr) {
        if (!is_array($arr) || count($arr) <= 0) {
            return '不是数组';
        }

        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}