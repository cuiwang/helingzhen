<?php
/*
 __________________________________________________
|  Encode by BajieTeam on we7                      |
|__________________________________________________|
*/

class FlashHongBaoException extends Exception
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
        goto TuI5q;
        mvW7z:
        $this->error_code = $error_code;
        goto Pskd4;
        TuI5q:
        $this->error_message = $error_message;
        goto mvW7z;
        Pskd4:
        $this->message = $error_message;
        goto J52CM;
        J52CM:
        $this->code = $error_code;
        goto Z0bDl;
        Z0bDl:
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
