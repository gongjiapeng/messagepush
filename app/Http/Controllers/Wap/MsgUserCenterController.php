<?php

namespace App\Http\Controllers\Wap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MsgContent;
use App\Models\Admin\MsgType;
use App\Models\Admin\MsgSchool;
use App\Models\Api\SubscribeUser;

use Cache;
use URL;
class MsgUserCenterController extends Controller
{
    private $pageNum = 15;//每条条数
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $redirectUrl = URL('wap/userlogin');
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        if(empty($userInfo['userId'])) {
            // $res = [
            //     'code'=>'50004',
            //     'msg'=>'订阅失败！请先登录',
            //     'data'=>['redirect'=>$redirectUrl],
            // ];
            // return  $this->failApi($res);
            return redirect($redirectUrl);
        }
        $data = [
            'userinfo'  => $userInfo,
            'defaultimg'=> URL::asset('/').'/images/head.jpg',
        ];
        //echo '<pre>';print_r($data);exit;
        return view('wap.user',$data);
    }
    /**
     * [getSubscribeContentList 获取用户发布信息列表]
     * @return [type] [description]
     */
    public function getSubscribeContentList(Request $request)
    {
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数
        $params['userId'] = $userInfo['userId'];
        $status = $request->input('status');
        if (isset($status)) {
            $params['status'] = $status;
        }
        $list = MsgContent::getMsgContentLists($params);
        $data = [
            'list'      => $list,
            'status'    => $status,
            'defaultimg'=> URL::asset('/').'/images/face.jpg',
            'params'    => $params,
            'ajaxurl' => URL::action('Wap\MsgUserCenterController@getAjaxSubscribeContentList'),
        ];
        return view('wap.usercontentlist',$data);
    }
    /**
     * [getAjaxSubscribeContentList 异步获取数据]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getAjaxSubscribeContentList(Request $request)
    {
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数
        $params['userId'] = $userInfo['userId'];
        $status = $request->input('status');
        if (isset($status)) {
            $params['status'] = $status;
        }
        $list = MsgContent::getMsgContentLists($params);
        $data = [
            'list'      => $list,
            'status'    => $status,
            'defaultimg'=> URL::asset('/').'/images/face.jpg',
            'params'    => $params,
        ];
        $res = [
                'msg'=>'操作成功',
                'data'=>['res'=>$data],
            ];
        
        return $this->detailApi($res);
    }
    /**
     * [getSubscrbeType 用户订阅标签]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSubscrbeType(Request $request)
    {
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数
        $params['userId'] = $userInfo['userId'];

        $list = SubscribeUser::getUserMsgType($params);
        $data = [
            'list'      => $list,
            'params'    => $params,
        ];
        //echo '<pre>';print_r($userInfo);exit;
        return view('wap.usermsgtypelist',$data);
    }
    /**
     * [unSubscrbeType 取消订阅]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function cancelSubscrbeType(Request $request)
    {
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        $params['userId'] = $userInfo['userId'];
        $params['appId'] = $userInfo['appId'];
        $params['originalId'] = $userInfo['originalId'];
        $params['tid'] = $request->input('tid');
        $params['sid'] = $request->input('sid');
        
        $res = SubscribeUser::updateSubscribeUser($params);
        if (!$res) {
            $res = [
                'code'=>'50010',
                'data'=>['msg'=>'取消失败！'],
            ];
            return  $this->failApi($res);
        }
        $res = [
                'msg'=>'取消成功',
                'data'=>['redirect'=>URL('wap/usermsgtypelist')],
            ];
        return $this->detailApi($res);
    }
   
   
}
