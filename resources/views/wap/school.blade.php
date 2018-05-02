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
    <div class="title">订阅</div>
  </div>
</div>
<div class="page-content">
    <div class="block-title">欢迎订阅</div>
    <form class="list " id="mainForm" ajaxUrl="{{URL::action('Wap\SubscribeController@subscribe')}}">
    {{ csrf_field() }}
    <ul>
      <li class="item-content item-input">
        <div class="item-inner">
          <div class="item-title item-label">标签名称</div>
          <div class="item-input-wrap">

            {{$tmptypeinfo['msgtype']}}
            <input type="hidden" name="tid" value="{{$tmptypeinfo['tid']}}" >
          </div>
        </div>
      </li>
      
      <li class="item-content item-input">
        <div class="item-inner">
          <div class="item-title item-label">学校名称</div>
          <div class="item-input-wrap input-dropdown-wrap">
            <select name="sid" placeholder="Please choose...">
                @foreach($schoollist as $li)
                    <option value="{{$li['sid']}}" >{{$li['schoolname']}}</option>
                @endforeach
            </select>
            
          </div>
        </div>
      </li>

    </ul>
    <div class="block">
      <div class="row">
        <button class="col button button-fill button-round save-sub-data">订阅</button>
      </div>
    </div>
  </form>
</div>
@stop
@section('javascript')


<script type="text/javascript">
    var app = new Framework7({
            cache: false,
        });
    var $$ = Dom7;
    var postUrl=$$('#mainForm').attr('ajaxUrl');
    
    $$('.save-sub-data').on('click', function(e) {
        e.preventDefault();
        $$('.save-sub-data').attr("disabled","true");
        var postdata = app.form.convertToData("#mainForm");
        app.request.post(postUrl,postdata , function (data) {
        var jsonData = JSON.parse(data);
            if (jsonData.data['redirect'] && jsonData.code!=0) {
                app.dialog.confirm(jsonData.msg,'客官您好', function () {
                  location.href=jsonData.data['redirect'];
                });
            } else if(jsonData.data['redirect'] && jsonData.code==0) {
                app.dialog.confirm(jsonData.msg,'您好', function () {
                  location.href=jsonData.data['redirect'];
                });
            } else {
                app.dialog.alert(jsonData.msg);
            }
        });
    });
</script>
@stop
