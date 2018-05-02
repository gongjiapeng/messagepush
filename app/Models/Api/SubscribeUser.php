<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;

class SubscribeUser extends Model
{
   
    protected $table = 'msg_subscribe_user';//模板类型表
    protected $primaryKey = 'id';
    //public $timestamps = true;
    static $fields = [
        'msg_subscribe_user.user_id',
        'msg_subscribe_user.original_id',
        'msg_subscribe_user.app_id',
        'msg_subscribe_user.tid',
        'msg_subscribe_user.is_deleted',
        'msg_subscribe_user.subscribe_type',
        'msg_subscribe_user.type',
    ];
    static $userfields = [
        'msg_subscribe_user.user_id',
        'msgtype.msgtype',
        'uc_oauth.open_id',
        'uc_oauth.nickname',
    ];
    static $usermsgtypefields = [
        'msg_subscribe_user.user_id',
        'msgtype.msgtype',
        'msg_subscribe_user.tid',
        'msg_subscribe_user.sid',
        'msg_subscribe_user.subscribe_type',
    ];

    /**
     * [saveSubscribeUser 保存用户订阅信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveSubscribeUser($data)
    {
        $result = DB::table('msg_subscribe_user')
                    ->where('user_id', $data['userId'])
                    ->where('tid', $data['tid'])
                    ->where('sid', $data['sid'])
                    ->where('app_id', $data['appId'])
                    ->select(self::$fields)
                    ->first();
        if ($result) {
            if ($result->subscribe_type==1) {
                return ['res'=>true,'msg'=>'您已订阅'];
            } else {
                $updateData = ['subscribe_type'=>1];
                $result = DB::table('msg_subscribe_user')
                    ->where('user_id', $data['userId'])
                    ->where('tid', $data['tid'])
                    ->where('sid', $data['sid'])
                    ->where('app_id', $data['appId'])
                    ->update($updateData);
                return ['res'=>$result,'msg'=>'订阅成功'];
            }
        } else {
            $saveData = [
                    'app_id'    =>$data['appId'],
                    'original_id'=>$data['originalId'],
                    'user_id'   =>$data['userId'],
                    'tid'       =>$data['tid'],
                    'sid'       =>$data['sid'],
                    'type'      =>$data['type'],
                ];
            $result = DB::table('msg_subscribe_user')
                        ->insert($saveData);
            return ['res'=>$result,'msg'=>'订阅成功'];
        }
    }
    /**
     * [updateSubscribeUser 取消用户订阅信息]
     * @param  [type] $data [description]
     * @return [type]          [description]
     */
    public static function updateSubscribeUser($data)
    {
        $updateData['subscribe_type'] = 2;
        
        $subscribe = DB::table('msg_subscribe_user')
                    ->where('tid', $data['tid'])
                    ->where('app_id', $data['appId'])
                    ->where('user_id', $data['userId'])
                    ->where('original_id', $data['originalId']);
                    //->update($updateData);
        if ($data['sid']) {
            $subscribe->where('sid', $data['sid']);
        }
        $result = $subscribe->update($updateData);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * [getSubscibeUserInfo 获取单条信息]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getSubscibeUserInfo($data)
    {
        $result = DB::table('msg_subscribe_user')
            ->where('tid', $data['tid'])
            ->where('original_id', $data['originalId'])
            ->where('app_id', $data['appId'])
            ->where('user_id', $data['userId'])
            ->select(self::$fields)
            ->first();
        if($result) {
            return $result;
        } else {
            return [];
        }
        return 111;exit;
    }
    /**
     * [getSubscribeUserListByTids 根据标签id 获取订阅的用户信息 ]
     * @return [type] [description]
     */
    public static function getSubscribeUserListByTids($tids)
    {
        $result = DB::table('msg_subscribe_user')
                ->leftJoin('msgtype', 'msg_subscribe_user.tid', '=', 'msgtype.tid')
                ->leftJoin('uc_oauth', 'msg_subscribe_user.user_id', '=', 'uc_oauth.user_id')
                ->whereIn('msg_subscribe_user.tid',$tids)
                ->where('msg_subscribe_user.subscribe_type','1')
                ->where('msg_subscribe_user.is_deleted','1')
                ->where('uc_oauth.is_subscribe','1')
                ->get(self::$userfields)->toArray();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * [getUserMsgType 获取用户订阅的标签列表]
     * @param  [type] $msgType [description]
     * @return [type]          [description]
     */
    public static function getUserMsgType($params)
    {
        $userMsgType = DB::table('msg_subscribe_user')
                    ->leftJoin('msgtype', 'msg_subscribe_user.tid', '=', 'msgtype.tid')
                    ->forPage($params['page'], $params['pageNum'])
                    ->where('msg_subscribe_user.subscribe_type', 1)
                    ->where('msg_subscribe_user.user_id',$params['userId'])
                    ->get(self::$usermsgtypefields)
                    ->toArray();
        if ($userMsgType) {
            return $userMsgType;
        } else {
            return [];
        }
    }

}
