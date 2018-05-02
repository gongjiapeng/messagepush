<?php
namespace App\lib\wechat;
use App\lib\wechat\EasyWechat;

/**
 * 微信相关
 */
class WechatSendTmp
{
    /**
     * [sendWechat 模板发送]
     * @return [type] [description]
     */
    public static function sendWechatTmp($data)
    {
        $options = [
            'appId' => env('WECHAT_APP_ID', 'wx38697bce749870fb'),
            'appSecret' => env('WECHAT_APP_SECRET', '774e28ba817d36bb8de6bd59d6366618'),
        ];
        $easyWechat = EasyWechat::getInstance($options);
        //echo '<pre';print_r($data);exit;
        $res = $easyWechat->sendMsgTmp($data);
        return $res;

    }
    

}