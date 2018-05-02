<?php
namespace App\lib;
use App\lib\Banned;
/**
 * 文件上传类
 */
class common
{
    /**
     * [createPassword 创建随机密码]
     * @return [type] [description]
     */
    public static function createPassword()
    {
        $str = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        $password = substr(str_shuffle($str),26,10);
        return $password;
    }
    /**
     * [checkWorld 违禁词过滤]
     * @return [type] [description]
     */
    public static function checkWorld($title,$content,$remarks)
    {
        $banned = new Banned();
        if ($title) {
            $checkTitle = $banned->check_text($title);
            if ($checkTitle['banned']) {
                return false;
            }
        }
        if ($content) {
            $checkContent = $banned->check_text($content);
            if ($checkContent['banned']) {
                return false;
            }
        }
        if ($remarks) {
            $checkRemarks = $banned->check_text($remarks);
            if ($checkRemarks['banned']) {
                return false;
            }
        }
        return true;
    }
}