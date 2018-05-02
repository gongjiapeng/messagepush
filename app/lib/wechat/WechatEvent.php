<?php
namespace App\lib\wechat;
use App\Models\Api\UcAuth;
use App\User;
use App\Models\Api\UserLogin;
use App\lib\common;

/**
 * 微信相关
 */
class WechatEvent
{
    /**
     * [wechatEvent 微信事件通知]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public function wechatEvent($message)
    {
        switch ($message->Event) {
            case 'subscribe':
                $res = $this->isSubscribe($message);
                return '您好！欢迎关注我!';
                break;
            case 'unsubscribe':
                $res = $this->isSubscribe($message);
                return '取消关注成功';
                break;
            default:
                return '您好';
                break;
        }
    }
    /**
     * [isSubscribe 关注取关]
     * @param  [type]  $message [description]
     * @return boolean          [description]
     */
    private function isSubscribe($message)
    {
        $isSubscribe = 0;
        if ($message->Event=='subscribe') {
            $isSubscribe = 1;
            $data['subscribed_at']  = $message->CreateTime;
        }
        $data['isSubscribe']    = $isSubscribe;
        $data['open_id']        = $message->FromUserName;
        $data['original_id']    = $message->ToUserName;
        $userInfo = UserLogin::getUserInfoByOpenId(env('WECHAT_APP_ID', 'wx38697bce749870fb'),$data['open_id'],$data['original_id']);
        
        if ($userInfo) {
            $res = UcAuth::updateOauthInfo($data);
            return $res;
        } else {
            //自动注册
            if ($message->Event!='subscribe') {
                return true;
            }
            $password = common::createPassword();
            $userModel = User::create([
                'name'      => '',
                'mobile'    => !empty($user['mobile'])?$user['mobile']:'',
                'password'  => bcrypt($password),
            ]);
            //保存第三方
            $ucAuthdata['userId'] = $userModel->id;
            $ucAuthdata['openId'] = $data['open_id'];
            $ucAuthdata['nickname'] = '';
            $ucAuthdata['headimgurl'] = '';
            $ucAuthdata['appId']  = env('WECHAT_APP_ID', 'wx38697bce749870fb');
            $ucAuthdata['unionid'] = '';
            $ucAuthdata['type'] = '2';
            $ucAuthdata['originalId']  = $data['original_id'];
            $ucAuthdata['isSubscribe']  = $data['isSubscribe'];
            $ucAuthdata['subscribedAt']  = $data['subscribed_at'];
            $res = UcAuth::saveOauthInfo($ucAuthdata);
            return $res;
        }
    }
}