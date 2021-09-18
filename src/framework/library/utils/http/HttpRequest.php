<?php

namespace mofeng\tp5\utils\http;

/**
 * http请求类
 *
 * @author liuwenwei
 *
 */
class HttpRequest {
    /**
     * curl post请求
     *
     * @param string $url
     *            请求url
     * @param array $data
     *            请求数据
     * @return array|error 成功返回微信服务器响应数组，失败返回错误信息
     */
    public static function curl_post($url, $data, $header = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($header) && $header == 'json') {
            if (is_array($data)) {
                $data = json_encode($data);
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        } else if (!empty($header) && $header == 'form') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded')
            );
            $data = http_build_query($data);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    /**
     * curl get请求
     *
     * @param string $url
     *            请求url
     * @param array $data 请求参数
     * @return array|error 成功返回微信服务器响应数组，失败返回错误信息
     */
    public static function curl_get($url, $data = null) {
        $curl = curl_init();

        //array数组序列化为字符串，拼接于url后
        if (!empty($data) && is_array($data)) {
            $url = $url . '?' . http_build_query($data);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        $response = curl_exec($curl);
        $result = json_decode($response, true);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    /**
     * 下载远程文件
     * @param string $url 远程地址
     * @param string $savePath 保存路径
     * @param string $fileName 文件名称(不包含后缀)
     * @param string $ext 文件后缀
     * @return 成功返回路径，失败返回false
     */
    public static function put_file($url, $savePath, $fileName = null, $extension = null) {
        set_time_limit(0);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $file = curl_exec($curl);
        curl_close($curl);

        if (!is_dir($savePath)) {
            mkdir($savePath);
        }

        //文件名
        if (empty($extension)) {
            trace($url, 'URL');
            $ext = strrchr($url, '.'); //文件名后缀
            trace($ext, 'EXT');
            if ($ext) {
                $fileName = empty($fileName) ? md5(time() . rand()) . $ext : $fileName . $ext;
            } else {
                return false;
            }
        } else {
            $fileName = empty($fileName) ? md5(time() . rand()) . $extension : $fileName . $extension;
        }

        $write = @fopen($savePath . '/' . $fileName, "w");
        if ($write == false) {
            return false;
        }
        if (fwrite($write, $file) == false) {
            return false;
        }
        if (fclose($write) == false) {
            return false;
        }

        return $savePath . '/' . $fileName;
    }

}