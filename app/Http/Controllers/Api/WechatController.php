<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\lib\wechat\WechatEvent;
use App\lib\wechat\WechatText;
use App\lib\wechat\EasyWechat;
use URL;
use Cache;

class WechatController extends Controller
{
    /**
     * [wechatback 微信回调]
     * @param  Request $Request [description]
     * @return [type]           [description]
     */
    public function wechatback(Request $Request)
    {
        $options = [
            'appId' => env('WECHAT_APP_ID', ''),
            'appSecret' => env('WECHAT_APP_SECRET', '774e28ba817d36bb8de6bd59d6366618'),
        ];
        $easyWechat = EasyWechat::getInstance($options);
        //echo '<pre>';print_r($easyWechat->app);exit;
        // 从项目实例中得到服务端应用实例。
        $server = $easyWechat->app->server;
 
        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    $wechatEvent = new WechatEvent();
                    $res = $wechatEvent->wechatEvent($message);
                    return $res;
                    break;
                case 'text':
                    $wechatText = new WechatText();
                    $res = $wechatText->wechatText($message);
                    return $res;
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                default:
                    return "您好！欢迎关注我!";
                    break;
            }
            //return "您好！欢迎关注我!";
        });
        $response = $server->serve();
        return $response;
    }
    /**
     * [follow 获取二维码]
     * @return [type] [description]
     */
    public function follow()
    {
        $qrcodeUrl = $imgurl = URL::asset('/').'img/'.'code.jpg';
        if (!$qrcodeUrl) {
            $options = [
            'appId' => env('WECHAT_APP_ID', ''),
            'appSecret' => env('WECHAT_APP_SECRET', ''),
            ];
            $easyWechat = EasyWechat::getInstance($options);
            $app = $easyWechat->app;
            //永久二维码
            $qrcode = $app->qrcode;
            $result = $qrcode->forever(56);
            $ticket = $result->ticket;
             //echo '<pre>';print_r($result);exit;
            $url = $qrcode->url($ticket);
            $content = file_get_contents($url); 
            file_put_contents(env('UPLOAD_PATH') . '/code.jpg', $content);
            $qrcodeUrl = $imgurl = URL::asset('/').'img/'.'code.jpg';
        }
        $data = [
            'qrcodeUrl'=>$qrcodeUrl,
        ];
        return $this->detailApi($data);
    }
   
}