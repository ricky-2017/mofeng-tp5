<?php

namespace mofeng\tp5\utils\mailer;

/**
 * 邮件工具类
 * @author liuwenwei
 *
 */
class Email {
    private $showUserName;//邮箱用户名
    private $account;//帐号
    private $authPwd;//授权码
    private $values;//邮件数据
    private $error;//错误信息

    public function __construct($showUserName, $account, $authPwd) {
        $this->showUserName = $showUserName;
        $this->account = $account;
        $this->authPwd = $authPwd;
    }

    /**
     * 设置接收邮件邮箱
     * @param array|string $value 支持数组或字符串
     */
    public function setEmails($value) {
        $this->values['emails'] = $value;
    }

    /**
     * 设置主题
     * @param string $value 主题名称
     */
    public function setTheme($value) {
        $this->values['theme'] = $value;
    }

    /**
     * 设置html内容
     * @param string $value html邮件内容
     */
    public function setContent($value) {
        $this->values['content'] = $value;
    }

    /**
     * 设置正文是否为html
     * @param boolean $value
     */
    public function setIsHtml($value) {
        $this->values['isHtml'] = $value;
    }

    /**
     * 设置附件
     * @param string $value 附件地址
     */
    public function setAttach($value) {
        $this->values['attach'] = $value;
    }

    /**
     * 设置错误信息
     * @param string $value
     */
    public function setError($value) {
        $this->error = $value;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * 发送邮件方法
     * @return boolean
     */
    public function sendEmail() {
        // 实例化PHPMailer核心类
        $mail = new \PHPMailer\PHPMailer();
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 1;
        // 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        // smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        // 链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.qq.com';
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = 465;
        // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
        // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = $this->showUserName;
        // smtp登录的账号 QQ邮箱即可
        $mail->Username = $this->account;
        // smtp登录的密码 使用生成的授权码
        $mail->Password = $this->authPwd;
        // 设置发件人邮箱地址 同登录账号
        $mail->From = $this->account;
        if ($this->values['isHtml'] == true) {
            // 邮件正文是否为html编码 注意此处是一个方法
            $mail->isHTML(true);
        }
        if (is_array($this->values['emails'])) {
            foreach ($this->values['emails'] as $v) {
                // 设置收件人邮箱地址
                $mail->addAddress($v);
            }
        }
        if (is_string($this->values['emails'])) {
            $mail->addAddress($this->values['emails']);
        }
        if ($this->values['theme']) {
            // 添加该邮件的主题
            $mail->Subject = $this->values['theme'];
        }
        // 添加邮件正文
        $mail->Body = $this->values['content'] ? $this->values['content'] : '';
        if ($this->values['attach']) {
            // 为该邮件添加附件
            $mail->addAttachment($this->values['attach']);
        }
        // 发送邮件 返回状态
        $status = $mail->send();
        if (!$status) {
            $this->setError($mail->ErrorInfo);
            return false;
        }
        return true;
    }
}