<?php
namespace App\lib\wechat;
//use App\Models\Api\UcAuth;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Core\Exceptions\HttpException;
use Doctrine\Common\Cache\RedisCache;
use Illuminate\Support\Facades\Log;

/**
 * 微信相关
 */
class EasyWechat
{
    /**
     * [$appId appid]
     * @var [type]
     */
    private $appId;
    /**
     * [$appSecret appSecret]
     * @var [type]
     */
    private $appSecret;
    /**
     * [$app ]
     * @var [obj]
     */
    public $app = null ;
    private static $instance = [];
    /**
     * [getInstance 实类话]
     * @param  [type] $options [description]
     * @return [type]          [description]
     */
    public static function getInstance($options) 
    {
        if (!isset(self::$instance[$options['appId']]) ) {
            self::$instance[$options['appId']] = new self($options);
        }
        return self::$instance[$options['appId']];
    }


    public function __construct($options)
    {
        $this->appId            = isset($options['appId'])     ? $options['appId']:'';
        $this->appSecret        = isset($options['appSecret']) ? $options['appSecret']:'';
        $this->getApp($options['appId'],$options['appSecret']);
    }

    /**
     * [getApp 获取app实力]
     * @return [type] [description]
     */
    private  function getApp($appId,$appSecret)
    {   
       
        $config = [
            'debug'  => true,
            'app_id' => $appId,
            'secret' => $appSecret,
            // // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            // 'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => env('WECHAT_LOG_PATH'),
            ],
        ];
        //缓存设置成redis
        $app = new Application($config);
        $cache = new RedisCache();
        $redis = new \Redis();
        $redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));
        if (env('REDIS_PASSWORD')) {
            $redis->auth(env('REDIS_PASSWORD'));
        }
        
        $cache->setRedis($redis);
        $app->access_token->setCache($cache);

        $this->app = $app;
    }
    /**
     * [getAccessToken 获取tocken]
     * @return [type] [description]
     */
    public function getAccessToken()
    {
        $accessToken = $this->app->access_token;
        $tocken = $accessToken->getToken();
        return $tocken;
    }
    /**
     * [sendMsgTmp description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function sendMsgTmp($data)
    {
        $notice = $this->app->notice;
        try {
            $res = $notice->send($data);
            if ($res && $res['errcode']==0) {
                return true;
            } else {
                Log::info('微信模板消息发送失败: '.json_encode($res));
                return false;
            }
        } catch (HttpException $e) {
            Log::info('微信模板消息发送失败: '.json_encode($res));
            DWDData_Logger::getInstance()->error('微信模板消息发送失败' . json_encode($e->getCode()).json_encode($e->getMessage()).json_encode($data) );
            return false;
        }
    }
    /**
     * [sendMsg 客服消息]
     * @param  [type] $msg    [description]
     * @param  [type] $openId [description]
     * @return [type]         [description]
     */
    public function sendMsg($msg,$openId)
    {

        try {
                $staff = $this->app->staff;
                $messageBuilder = $staff->message($msg)->to($openId);

                $result = $messageBuilder->send();
        } catch (HttpException $e) {
            echo '<pre>';print_r($e->getMessage());exit;
        }

    }
    /**
     * [getPrivateTemplates 获取模板列表]
     * @return [type] [description]
     */
    public function getPrivateTemplates()
    {
        $notice = $this->app->notice;
        $res = $notice->getPrivateTemplates();
        //echo '<pre>';print_r($res);exit;
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }
    /**
     * [getUserInfo 获取微信用户信息]
     * @param  [type] $openId [description]
     * @return [type]         [description]
     */
    public function getUserInfo($openId)
    {
        $userService = $this->app->user;
        
        try {
            $res = $userService->get($openId);
            return $res;
        } catch (HttpException $e) {
            DWDData_Logger::getInstance()->error('获取用户信息' . json_encode($e->getCode()).json_encode($e->getMessage()).json_encode($openId) . __FILE__ . 'line: ' . __LINE__);
            return [];
        }
        
    }
}