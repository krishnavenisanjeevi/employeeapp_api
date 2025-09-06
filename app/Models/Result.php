<?php
namespace App\Models;

class Result
{
    public $status;
    public $data;

    function __construct($status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public static function success($data = '')
    {
        return new Result(true, $data);
    }

    public static function failed($data = '')
    {
        return new Result(false, $data);
    }

    public static function error(\Exception $ex)
    {
        $code = $ex->getCode();
        $message = $ex->getMessage();
        return new Result(false, "Error Code: $code, Message: $message");
    }
}