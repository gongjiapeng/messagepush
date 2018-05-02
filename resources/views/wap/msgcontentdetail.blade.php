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
    <div class="title">信息详情</div>
  </div>
</div>
<div class="page-content">
    <div class="list media-list">
        @if($msgInfo)
            <ul>
              <li class="external item-content">
                  <div class="item-media">
                    <img src="{{$msgInfo->imgurl or $defaultimg}}" width="120" height="120" />
                </div>
                  <div class="item-inner">
                    <div class="item-title-row">
                      <div class="item-title"><p>{{$msgInfo->title}}</p></div>
                      
                    </div>
                    <div class="item-subtitle"><span>学校：{{$msgInfo->schoolname}}</span></div>
                    <div class="item-text">标签：{{$msgInfo->msgtype}} </div>
                    <div class="item-title">备注：{{$msgInfo->remarks}}</div>
                  </div>
              </li>
            </ul>
            <div class="block block-strong inset">
              <p>{{$msgInfo->content}}</p>
            </div>
        @else
            <div class="block block-strong">
                <p>暂无信息</p>
            </div>
        @endif
    </div>
</div>
@stop