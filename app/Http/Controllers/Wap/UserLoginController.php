<?php
namespace App\Http\Controllers\Wap;
use URL;
use Cache;
use DB;
use App\User;
use App\lib\common;
use App\Models\Api\UcAuth;
use App\Models\Api\UserLogin;
use EasyWeChat\Foundation\Application;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Foundation\Auth\RegistersUsers;
class UserLoginController extends Controller
{
    
    public function wechatLogin(Request $request)
    {
        $token = $request->session()->get('token');
       
        //$token = $request->input('token','');

        $config = [
            'app_id' => env('WECHAT_APP_ID', ''),
            'secret' => env('WECHAT_APP_SECRET', ''),
            'oauth' => [
              'scopes'   => ['snsapi_userinfo'],
              'callback' => URL::action('Wap\UserLoginController@wechatCallBack'),
            ],
        ];

        $app = new Application($config);
        $oauth = $app->oauth;
        $userInfo = Cache::store('redis')->get($token);
        
        if(!$userInfo && empty($userInfo)) {
            return $oauth->redirect();
        }
        $redirectUrl = URL("wap/usercenter");
        return redirect($redirectUrl);

        return $this->detailApi(['data'=>$userInfo]);
    }
    /**
     * [wechatCallBack 微信登陆回调地址]
     * @return [type] [description]
     */
    public function wechatCallBack(Request $request)
    {
        $config = [
            'app_id' => env('WECHAT_APP_ID', ''),
            'secret' => env('WECHAT_APP_SECRET', ''),
            'oauth' => [
              'scopes'   => ['snsapi_userinfo'],
              'callback' => URL::action('Wap\UserLoginController@wechatCallBack'),
            ],
        ];
        $app = new Application($config);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray();
        //echo '<pre>';print_r($user);exit;
        if ($user) {
            $userOauthInfo = UserLogin::getUserInfoByOpenId(env('WECHAT_APP_ID', ''),$user['original']['openid'],'');
            $data['type']   = 2;//2 微信

            if ($userOauthInfo) {
                //已经注册过的
                $data['id'] = $userOauthInfo->id;
                $data['userId'] = $userOauthInfo->user_id;
                $data['openId'] = $user['original']['openid'];
                $data['nickname'] = $this->filterEmoji($user['nickname']);
                $data['headimgurl'] =$user['original']['headimgurl'];
                $data['appId']  = env('WECHAT_APP_ID', '');
                $data['unionid'] = $user['original']['unionid'];
                $data['originalId']  = env('WECHAT_APP_ORIGINALId', 'gh_60f385a99421');
                $userData = [
                    'name'  => $this->filterEmoji($user['name']),
                    'mobile'=> !empty($user['mobile'])?$user['mobile']:'',
                    'id'    => $userOauthInfo->user_id
                ];
                
                User::saveUserInfo($userData);
                $res = UcAuth::saveOauthInfo($data);
            } else {
                //自动注册
                $password = common::createPassword();
                DB::beginTransaction();
                $userModel = User::create([
                    'name'      => $this->filterEmoji($user['name']),
                    'mobile'    => !empty($user['mobile'])?$user['mobile']:'',
                    'password'  => bcrypt($password),
                ]);
                if (!$userModel->id) {
                    DB::rollBack();
                }
                $data['userId'] = $userModel->id;
                $data['openId'] = $user['original']['openid'];
                $data['nickname'] = $this->filterEmoji($user['nickname']);
                $data['headimgurl'] = $user['original']['headimgurl'];
                $data['appId']  = env('WECHAT_APP_ID', '');
                $data['originalId']  = env('WECHAT_APP_ORIGINALId', '');
                $data['unionid'] = $user['original']['unionid'];
                $res = UcAuth::saveOauthInfo($data);
                if (!$res) {
                    DB::rollBack();
                }
                DB::commit();
            }
            $token = md5($data['appId'].$data['openId'].$data['unionid']);
            $expires = $user['token']->expires_in;
            $data['token'] = $token;
            Cache::store('redis')->put($token, $data, 3600);
            session(['token' => $token]);
            $redirectUrl = URL("wap/usercenter");
            return redirect($redirectUrl);
            //return  $this->detailApi(['data'=>$data]);
        } else{
            $res = [
                'code'=>'50000',
                'msg'=>'操作失败',
                'data'=>['data'=>false],
            ];
            return  $this->failApi($res);
        }
    }

    private function filterEmoji($str)
    {
     $str = preg_replace_callback(
       '/./u',
       function (array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
       },
       $str);
     
      return $str;
     }

   
}