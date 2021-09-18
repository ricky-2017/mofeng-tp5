<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 9:44
 */

namespace mofeng\tp5\log\driver;


use Aliyun_Log_Client;
use Aliyun_Log_Models_LogItem;
use Aliyun_Log_Models_PutLogsRequest;
use think\App;
use think\log\driver\File;
require_once __DIR__.'/../../extend/aliyun-log-php-sdk/Log_Autoload.php';

class Aliyun extends File {
    private $client;


    public function __construct(App $app, array $config = []) {
        $this->client = new Aliyun_Log_Client(
            $config['aliyun']['endpoint'],
            $config['aliyun']['accessKeyId'],
            $config['aliyun']['accessKey']
        );
        return parent::__construct($app, $config);
    }


    protected function write($message, $destination, $apart = false, $append = false) {
        $rawMessage = $message;
        if (PHP_SAPI != 'cli') {
            foreach ($message as $type => $msg) {
                $info[$type] = is_array($msg) ? implode("\r\n", $msg) : $msg;
            }

            $this->getDebugLog($info, $append, $apart);

            $message = $this->parseLog($info, false);

            $logItem = new Aliyun_Log_Models_LogItem();
            $logItem->setTime(time());
            $logItem->setContents($message);

            $this->client->putLogs(new Aliyun_Log_Models_PutLogsRequest(
                    $this->config['aliyun']['project'],
                    $this->config['aliyun']['logstore'],
                    PHP_SAPI,
                    null,
                    [$logItem])
            );
        }


        if($this->config['aliyun']['file']) {
            parent::write($rawMessage, $destination, $apart, $append);
        }

    }

    /**
     * 无需做大小检查
     * @param string $destination
     */
    protected function checkLogSize($destination) {
        return;
    }

    /**
     * 直接返回数组格式的日志信息
     * @param array $info
     * @param bool $fromParent 是否返回File驱动格式
     * @return array|string
     */
    protected function parseLog($info, $fromParent=true) {

        if($fromParent) {
            return parent::parseLog($info);
        }

        $requestInfo = [
            'ip'     => $this->app['request']->ip(),
            'method' => $this->app['request']->method(),
            'host'   => $this->app['request']->host(),
            'uri'    => $this->app['request']->url(),
        ];

        return $requestInfo + $info;

    }
}