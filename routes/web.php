<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Auth::routes();
Route::get('/register', function () {
    return Redirect::to(URL::action('Admin\MsgContentController@getContentList')) ;
});
Route::get('/password/reset', function () {
    return Redirect::to(URL::action('Admin\MsgContentController@getContentList')) ;
});


Route::get('/home', 'Admin\MsgContentController@getContentList');
Route::get('/', function () {
    return Redirect::to(URL::action('Admin\MsgContentController@getContentList')) ;
});
Route::group(['prefix' => 'admin', 'namespace' => 'Admin','middleware' => 'auth'], function()
{
	  Route::get('/', 'MsgContentController@getContentList');
    //获取信息列表
  	Route::get('/contentlist', 'MsgContentController@getContentList');
    //填加信息
  	Route::get('/addcontent', 'MsgContentController@addContent');
    //保存信息
  	Route::post('/savecontent', 'MsgContentController@saveContent');
    //修改信息
  	Route::get('/updatecontent', 'MsgContentController@updateContent');
    //删除信息
  	Route::post('/deletecontent', 'MsgContentController@deleteContent');

    //获取意见列表
    Route::get('/opinionlist', 'MsgOpinionController@getOpinionList');
  	Route::any('/getopinion', 'MsgOpinionController@getOpinionInfo');
    Route::any('/delopinion', 'MsgOpinionController@delOpinionById');
  	//获取信息类型列表
  	Route::get('/msgtypelist', 'MsgTypeController@getMsgTypeList');
  	//添加信息类型
  	Route::get('/addmsgtype', 'MsgTypeController@addMsgType');
  	//保存信息类型列表
  	Route::post('/savemsgtype', 'MsgTypeController@saveMsgType');
  	//修改信息类型
  	Route::get('/updatemsgtype', 'MsgTypeController@updateMsgType');
  	//删除信息类型
  	Route::any('/delmsgtype', 'MsgTypeController@delMsgType');

    //获取信息类型列表
    Route::any('/schoollist', 'SchoolController@getSchoolList');
    //添加信息类型
    Route::any('/addschool', 'SchoolController@addSchool');
    //保存信息类型列表
    Route::any('/saveschool', 'SchoolController@saveSchool');
    //修改信息类型
    Route::any('/updateschool', 'SchoolController@updateSchool');
    //删除信息类型
    Route::any('/delschool', 'SchoolController@delSchool');

    //获取微信菜单列表
    Route::any('/weichatmenulist', 'WechatMenuController@getMenuList');
    //获取微信菜单列表
    Route::any('/weichataddmenu', 'WechatMenuController@addWechatMenu');
});
Route::group(['prefix' => 'wap', 'namespace' => 'Wap'], function()
{
    //首页
    Route::any('/index', 'MsgIndexController@index');
    Route::any('/schoollist', 'MsgIndexController@selectSchool');
    Route::any('/msgtypelist', 'MsgIndexController@msgTypeList');
    Route::any('/addcontent', 'MsgIndexController@addMsgContent')->middleware('checkapi');
    //个人中心
    Route::any('/usercenter', 'MsgUserCenterController@index')->middleware('checkapi');
    Route::any('/usercontentlist', 'MsgUserCenterController@getSubscribeContentList')->middleware('checkapi');
    Route::any('/userajaxcontentlist', 'MsgUserCenterController@getAjaxSubscribeContentList')->middleware('checkapi');
    Route::any('/usermsgtypelist', 'MsgUserCenterController@getSubscrbeType')->middleware('checkapi');
    Route::any('/cancelmsgtype', 'MsgUserCenterController@cancelSubscrbeType')->middleware('checkapi');
    //用户微信授权登陆
    Route::any('/wechatlogin', 'UserLoginController@login');
    Route::any('/userlogin', 'UserLoginController@wechatLogin');
    //微信回调
    Route::any('/login', 'UserLoginController@wechatCallBack');
    //图片上传
    Route::any('/upload', 'UploadController@upload')->middleware('checkapi');
    Route::any('/doupload', 'UploadController@doupload')->middleware('checkapi');
    //信息发布
    Route::any('/savecontent', 'MsgContentController@saveContent')->middleware('checkapi');
    //信息列表
    Route::any('/contentlist', 'MsgContentController@getContentList');
    Route::any('/ajaxcontentlist', 'MsgContentController@getAjaxContentList');
    //获取信息
    Route::any('/contentdetail', 'MsgContentController@getContentDetail');
    //删除信息
    Route::any('/delcontent', 'MsgContentController@delContentInfo')->middleware('checkapi');
    //修改信息状态
    Route::any('/updatecontentstatus', 'MsgContentController@updateContentStatus')->middleware('checkapi');
    //添加意见
    Route::any('/doaddopinion', 'MsgOpinionController@addOpinion')->middleware('checkapi');
    Route::any('/addopinion', 'MsgIndexController@addMsgOpinion')->middleware('checkapi');


    Route::any('/wechatback', 'WechatController@wechatback');
    //二维码扫码关注
    Route::any('/wechatfollow', 'WechatController@follow');
    //订阅
    Route::any('/wechatsubscribe', 'SubscribeController@subscribe')->middleware('checkapi');
    //模板发送
    Route::any('/wechatsendtmp', 'WechatSendController@weichatSend')->middleware('checkapi');
});


