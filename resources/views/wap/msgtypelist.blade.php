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
    <div class="title">标签中心</div>
  </div>
</div>
<div class="page-content">
    <div class="block-title">欢迎订阅</div>
    <div class="list simple-list">
        <ul>
            @if($tmplist)
                @foreach($tmplist as $li)
                <li>
                    <a class="external" href="{{URL::action('Wap\MsgIndexController@selectSchool',['tid'=>$li['tid']])}}">
                        {{$li['msgtype']}}
                    </a>
                </li>
                @endforeach
            @else
                <div class="block block-strong">
                    <p>暂无信息</p>
                </div>
            @endif
        </ul>
    </div>
</div>
@stop
