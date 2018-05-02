
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
    <div class="title">用户中心</div>
  </div>
</div>
<div class="page-content">
    <div class="block-title">已订阅的标签</div>
        <div class="list simple-list">
          <ul>
            @if($list)
              @foreach($list as $li)
                  <li>
                      <p>{{$li->msgtype}}</p>
                      <div class="item-text">
                          <div class="row">
                              <button ajaxUrl="{{URL::action('Wap\MsgUserCenterController@cancelSubscrbeType',['tid'=>$li->tid,'sid'=>$li->sid])}}" class="button col button-round upstatus">取消订阅</button>
                          </div>
                          
                      </div>
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

@section('javascript')
<script>
var app = new Framework7();
 
var $$ = Dom7;

$$(".upstatus").on("click",function(e){
    e.preventDefault();
    var postUrl=$$(this).attr('ajaxUrl');
    app.request.post(postUrl, function (data) {
        var jsonData = JSON.parse(data);
        if (jsonData.data['redirect'] && jsonData.code!=0) {
            app.dialog.confirm(jsonData.msg,'客官您好', function () {
              location.href=jsonData.data['redirect'];
            });
        } else if(jsonData.data['redirect'] && jsonData.code==0) {
            app.dialog.confirm(jsonData.msg,'客官您好', function () {
              location.href=jsonData.data['redirect'];
            });
        } else {
            app.dialog.alert(jsonData.msg);
        }
    });
});
</script>
@stop