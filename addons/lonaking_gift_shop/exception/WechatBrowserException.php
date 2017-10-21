<?php

/**
 * 微信浏览器异常 错误码 5040
 * User: leon
 * Date: 15/9/19
 * Time: 上午11:34
 */
class WechatBrowserException extends Exception
{
    const ERROR_MESSAGE = '请在微信浏览器中打开';
    const ERROR_CODE = 5040;
    private $error_message;
    private $error_code;

    /**
     * SelectNullException constructor.
     * @param $error_message
     * @param $error_code
     * @param $reason_for_user
     */
    public function __construct()
    {
        $this->error_message = ERROR_MESSAGE;
        $this->error_code = ERROR_CODE;
    }


    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param mixed $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @param mixed $error_code
     */
    public function setErrorCode($error_code)
    {
        $this->error_code = $error_code;
    }

}