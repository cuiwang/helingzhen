<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/24
 * Time: 下午2:55
 */
class WxHongBaoException extends Exception
{
    private $error_message;
    private $error_code;

    /**
     * WxHongBaoException constructor.
     * @param $error_message
     * @param $error_code
     */
    public function __construct($error_message, $error_code)
    {
        $this->error_message = $error_message;
        $this->error_code = $error_code;
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

}