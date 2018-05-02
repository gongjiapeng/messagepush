<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendWechatTmp;
use URL;
class WechatSendController extends Controller
{
    /**
     * [weichatSend 微信模板发送]
     * @param  Request $Request [description]
     * @return [type]           [description]
     */
    public function weichatSend(Request $Request)
    {
        $data = ['name'=>'nihao'];
        //echo '<pre>';print_r($data);exit;
        return $this->detailApi($this->dispatch(new SendWechatTmp($data)));
    }
   
}