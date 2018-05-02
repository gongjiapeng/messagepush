<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//用户微信授权登陆
Route::any('/v01/wechatlogin', 'Api\UserLoginController@login');
Route::any('/v01/userlogin', 'Api\UserLoginController@wehatLogin');
Route::any('/v01/login', 'Api\UserLoginController@wechatCallBack');
//图片上传
Route::any('/v01/upload', 'Api\UploadController@upload');
Route::any('/v01/doupload', 'Api\UploadController@doupload');
//信息发布
Route::any('/v01/savecontent', 'Api\MsgContentController@saveContent');//->middleware('checkapi');
//信息列表
Route::any('/v01/contentlist', 'Api\MsgContentController@getContentList');
//获取信息
Route::any('/v01/contentdetail', 'Api\MsgContentController@getContentDetail');
//删除信息
Route::any('/v01/delcontent', 'Api\MsgContentController@delContentInfo');
//修改信息状态
Route::any('/v01/updatecontentstatus', 'Api\MsgContentController@updateContentStatus');
//添加意见
Route::any('/v01/addopinion', 'Api\MsgOpinionController@addOpinion');


Route::any('/v01/wechatback', 'Api\WechatController@wechatback');
//二维码扫码关注
Route::any('/v01/wechatfollow', 'Api\WechatController@follow');
//订阅
Route::any('/v01/wechatsubscribe', 'Api\SubscribeController@subscribe');
//模板发送
Route::any('/v01/wechatsendtmp', 'Api\WechatSendController@weichatSend');



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
