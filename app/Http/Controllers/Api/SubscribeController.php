<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\SubscribeUser;
use App\Models\Api\UcAuth;
use URL;
use Validator;
use Cache;
class SubscribeController extends Controller
{
    /**
     * [subscribe 订阅]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public  function subscribe(Request $request)
    {
        $redirectUrl = URL('api/v01/userlogin');
        $token = $request->input('token');
        $userInfo = Cache::store('redis')->get($token);
        if(empty($userInfo)) {
            return redirect($redirectUrl);
        }
        $data['type'] = 2;
        $data['appId'] = env('WECHAT_APP_ID', '');
        $data['originalId'] = env('WECHAT_APP_ORIGINALId','');
        $data['userId'] = $userInfo['userId'];
        $data['tid'] = $request->input('tid','');
        $data['token'] = $token;
        //数据校验
        $validator = $this->checkAddData($data);
        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50004',
                'msg'=>'订阅失败',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
        //检查是否关注
        $ucInfo = UcAuth::getOauthInfo($data);
        if ($ucInfo&&$ucInfo->is_subscribe==1) {
            $res = SubscribeUser::saveSubscribeUser($data);
            return $this->detailApi($res);
        } else {
            $url = URL('api/v01/wechatfollow');
            $res = [
                'code'=>'50005',
                'msg'=>'请先关注公众号！',
                'msg'=>['res'=>$url],
            ];
            return  $this->failApi($res);
        }
        
    }

    /**
     * [checkData 数据检查]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function checkAddData(array $data)
    {
        $validator = Validator::make($data, [
            'tid'   => 'required|numeric',
            'token' => 'required',
        ]);
        return $validator;
    }

}