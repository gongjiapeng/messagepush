@extends('layouts.wap')
@section('content')
<div class="navbar">
  <div class="navbar-inner sliding">
    <div class="left">
      <a href="javascript:history.back(-1)" class="link">
        <i class="icon icon-back"></i>
        <span class="ios-only">返回</span>
      </a>
    </div>
    <div class="title">个人中心</div>
  </div>
</div>
<div class="page-content">
    <div class="list media-list">
        <ul>
            <li>
                <div class="item-content">
                    <div class="item-media"><img src="{{$userinfo['headimgurl'] or $defaultimg}}" width="50"></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{$userinfo['nickname'] or '相信自己'}}</div>
                        </div>
                        <div class="item-subtitle">ID {{$userinfo['userId']}}</div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="list links-list">
        <ul>
            <li><a class= "external tab-link" href="{{URL::action('Wap\MsgUserCenterController@getSubscrbeType')}}">已订阅的标签</a></li>
            <li><a class= "external tab-link" href="{{URL::action('Wap\MsgUserCenterController@getSubscribeContentList')}}">已发布的信息</a></li>
           
        </ul>
    </div>
</div>
@stop