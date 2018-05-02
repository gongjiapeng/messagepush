<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;

class UcAuth extends Model
{
   
    protected $table = 'uc_oauth';//模板类型表
    protected $primaryKey = 'id';
    //public $timestamps = true;
    static $fields = [
        'uc_oauth.user_id',
        'uc_oauth.original_id',
        'uc_oauth.app_id',
        'uc_oauth.open_id',
        'uc_oauth.nickname',
        'uc_oauth.headimgurl',
        'uc_oauth.is_subscribe',
        'uc_oauth.subscribed_at',
    ];

    /**
     * [saveOauthInfo 保存用户第三方信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveOauthInfo($data)
    {
        if (isset($data['id'])) {
            //$ucAuth->id     = $data['id'];
            $ucAuth = ucAuth::find($data['id']);
            $ucAuth->user_id    = $data['userId'];
            $ucAuth->open_id    = $data['openId'];
            $ucAuth->unionid    = $data['unionid'];
            $ucAuth->original_id= $data['originalId'];
            $ucAuth->type       = $data['type'];
            $ucAuth->nickname   = $data['nickname'];
            $ucAuth->headimgurl = $data['headimgurl'];
            $ucAuth->app_id     = $data['appId'];
        } else {
            $ucAuth = new UcAuth;
            $ucAuth->user_id    = $data['userId'];
            $ucAuth->open_id    = $data['openId'];
            $ucAuth->unionid    = $data['unionid'];
            $ucAuth->original_id= $data['originalId'];
            $ucAuth->type       = $data['type'];
            $ucAuth->nickname   = $data['nickname'];
            $ucAuth->headimgurl = $data['headimgurl'];
            $ucAuth->app_id     = $data['appId'];
        }
        if (isset($data['isSubscribe'])) {
            $ucAuth->is_subscribe = $data['isSubscribe'];
        }
        if (isset($data['subscribedAt'])) {
            $ucAuth->subscribed_at = $data['subscribedAt'];
        }
        
        //echo '<pre>';print_r($ucAuth);exit;
        $result = $ucAuth->save();
        return $result;
    }
    /**
     * [updateOauthInfo 修改用户信息]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public static function updateOauthInfo($message)
    {
        $updateData['is_subscribe'] = $message['isSubscribe'];
        if (isset($message['subscribed_at'])) {
            $updateData['subscribed_at'] = $message['subscribed_at'];
        }
        $result = DB::table('uc_oauth')
                    ->where('open_id', $message['open_id'])
                    ->where('original_id', $message['original_id'])
                    ->update($updateData);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * [getOauthInfo 获取微信信息]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getOauthInfo($data)
    {
        $result = DB::table('uc_oauth')
            ->where('user_id', $data['userId'])
            ->where('original_id', $data['originalId'])
            ->where('app_id', $data['appId'])
            ->select(self::$fields)
            ->first();
        if($result) {
            return $result;
        } else {
            return [];
        }
    }
    /**
     * [getOauthInfoByOpenId 根据第三方信息获取userId]
     * @return [type] [description]
     */
    public static function getOauthInfoByOpenId($data)
    {
        $result = DB::table('uc_oauth')
            ->where('open_id', $data['openId'])
            ->where('original_id', $data['originalId'])
            ->where('app_id', $data['appId'])
            ->where('type', $data['type'])
            ->select(self::$fields)
            ->first();
        if($result) {
            return $result;
        } else {
            return [];
        }
    }
}
