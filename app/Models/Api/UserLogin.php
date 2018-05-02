<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;
class UserLogin extends Model
{
   
    protected $table = 'users';//模板类型表
    protected $primaryKey = 'id';
    static $fields = [
        'uc_oauth.id',
        'uc_oauth.user_id',
        'uc_oauth.open_id',
        'uc_oauth.unionid',
        'uc_oauth.nickname',
        'uc_oauth.headimgurl',
        'uc_oauth.app_id',
        'uc_oauth.original_id',
    ];
    //public $timestamps = true;

    /**
     * [saveUserInfo 保存用户信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveUserInfo($data)
    {
        $user = new UserLogin;
        $user->name   = $data['name'];
        $user->mobile = $data['mobile'];
        $user->password   = $data['password'];
        $result = $user->save();
        return $result;
    }
    /**
     * [getUserInfoByOpenId 根据openid获取用户信息]
     * @param  [type] $openId [description]
     * @return [type]         [description]
     */
    public static function getUserInfoByOpenId($appId,$openId,$originalId)
    { 
        $result = DB::table('uc_oauth')
                    ->where('app_id', $appId)
                    ->where('open_id', $openId)
                    ->where('original_id', $originalId)
                    ->select(self::$fields)
                    ->first();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }
}
