<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'password',
    ];
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * [updateUserInfo 修改用户信息]
     * @return [type] [description]
     */
    public static function saveUserInfo($data)
    {
        $user = User::find($data['id']);
        $user->name    = $data['name'];
        $user->mobile  = $data['mobile'];
        $res = $user->save();
        return $res;
    }

}
