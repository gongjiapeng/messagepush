<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\lib\wechat\EasyWechat;
use URL;
use Cache;

class WechatMenuController extends Controller
{
    static $activeType = 'wechatmenu';
    /**
     * [getMenuList 微信菜单]
     * @param  Request $Request [description]
     * @return [type]           [description]
     */
    public function getMenuList(Request $Request)
    {
        $options = [
            'appId' => env('WECHAT_APP_ID', ''),
            'appSecret' => env('WECHAT_APP_SECRET', ''),
        ];
        $easyWechat = EasyWechat::getInstance($options);
        
        // 从项目实例中得到服务端应用实例。
        $app = $easyWechat->app;
        $menu = $app->menu;
        $menusList = $menu->all()->menu;
        $data = [
            'list'          => $menusList['button'],
            'activeType'    => self::$activeType,
        ];
        //echo '<pre>';print_r($data);exit;
        return view('admin.wechatmenulist',$data);
        
    }
    /**
     * [addMenuList 添加菜单]
     * @param Request $Request [description]
     */
    public function addWechatMenu(Request $Request)
    {
       $menu = $this->getWechatAppMenu();
       $buttons = [
            [
                "type" => "view",
                "name" => "信息发布",
                "url"  => "http://messagepush.dev.haoshiqi.net/wap/addcontent"
            ],
            [
                "type" => "view",
                "name" => "标签订阅",
                "url"  => "http://messagepush.dev.haoshiqi.net/wap/msgtypelist",
            ],
            [
                "type" => "view",
                "name" => "个人中心",
                "url"  => "http://messagepush.dev.haoshiqi.net/wap/usercenter"
            ],
        ];
        $res = $menu->add($buttons);
        echo '<pre>';print_r($res);exit;
    }
    /**
     * [getWechatAppMenu 获取第三方类]
     * @return [type] [description]
     */
    private function getWechatAppMenu()
    {
         $options = [
            'appId' => env('WECHAT_APP_ID', ''),
            'appSecret' => env('WECHAT_APP_SECRET', ''),
        ];
        $easyWechat = EasyWechat::getInstance($options);
        
        // 从项目实例中得到服务端应用实例。
        $app = $easyWechat->app;
        $menu = $app->menu;
        return $menu;
    }
   
}