<?php
namespace App\lib\wechat;
use App\Models\Api\SubscribeUser;
use App\Models\Api\UcAuth;
use App\Models\Admin\MsgType;
/**
 * 微信相关
 */
class WechatText
{
    /**
     * [wechatEvent 微信文字通知]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public function wechatText($message)
    {
        $content = explode('@',$message->Content);
        $msgType = isset($content[1])?$content[1]:'';
        if (!$msgType) {
            return '取消订阅的格式错误！，请重新回复如："取消@科技"';
        }
        $data['openId']        = $message->FromUserName;
        $data['originalId']    = $message->ToUserName;
        $data['appId']    = env('WECHAT_APP_ID', 'wx38697bce749870fb');
        $data['type']     = 2;
        //获取类型id
        $msgTypeInfo = MsgType::getMsgTypeIdByName($msgType);
        //获取用户信息
        $userInfo = UcAuth::getOauthInfoByOpenId($data);
        if ($msgTypeInfo&&$msgTypeInfo->tid) {
            $updateData['tid'] = $msgTypeInfo->tid;
            $updateData['appId']        = $data['appId'];
            $updateData['originalId']    = $data['originalId'];
            $updateData['userId']    = $userInfo->user_id;
            $subscribeUserInfo = SubscribeUser::getSubscibeUserInfo($updateData);
            if ($subscribeUserInfo&&$subscribeUserInfo->subscribe_type==2) {
                return $msgType.'订阅已经被取消了！';
            }
            $res = SubscribeUser::updateSubscribeUser($updateData);
            if ($res) {
                return $msgType.'订阅取消成功！';
            } else {
                return $msgType.'订阅取消失败！';
            }
        } else {
            return '取消订阅的类目不存在！';
        }  
    }
}