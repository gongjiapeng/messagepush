<?php

namespace App\Jobs;
use App\Models\Api\SubscribeUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\lib\wechat\WechatSendTmp;


class SendWechatTmp implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data   = $this->data;
        $tids   = $data['tids'];
        $title  = $data['title'];
        $imgurl = $data['imgurl'];
        $url    = $data['url'];
        $phone  = $data['phone'];
        $remarks = $data['remarks'];
        $remark = '请及时联系购买哦！！,联系方式：'.$phone;
        $userInfoList = SubscribeUser::getSubscribeUserListByTids($tids);

        foreach ($userInfoList as $key => $userInfo) {
            $first = '客官您订阅的'.$userInfo->msgtype.'有人发布了';
            //要发送的模板数据
            $sendData = [
                'touser'        => $userInfo->open_id,
                'template_id'   => env('WECHAT_TMPID'),
                'url'  => $url,
                'data' => [
                    "first"     => [$first, '#173177'],
                    "keyword1"  => [$title, '#173177'],
                    "keyword2"  => ['1件', '#173177'],
                    "keyword3"  => [$remarks, '#173177'],
                    "remark"    => [$remark, "#ff0000"],
                ],
                'appId'        => env('WECHAT_APP_ID'),
                'appSecret'    => env('WECHAT_APP_SECRET'),
            ];
            //echo '<pre>';print_r($sendData);exit;
            //error_log(var_export($sendData,1)."\n",3,'/data/logs/a.log');
            $res = WechatSendTmp::sendWechatTmp($sendData);
        }
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}
