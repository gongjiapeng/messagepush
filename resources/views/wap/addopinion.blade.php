@extends('layouts.wap')
@section('css')
      <link rel="stylesheet" href="{{ URL::asset('/') }}wap/css/upload.css">
@stop
@section('content')
<div class="navbar">
  <div class="navbar-inner sliding">
    <div class="left">
      <a href="javascript:history.back(-1)" class="link">
        <i class="icon icon-back"></i>
        <span class="ios-only">返回</span>
      </a>
    </div>
    <div class="title">吐槽</div>
    <div class="right">
      <a data-picker=".picker-1" class="link open-picker" >
        <i class="icon material-icons md-only">stars</i>
      </a>
    </div>
  </div>
</div>
<div class="page-content">
    <form class="list" id="mainForm" ajaxUrl="{{URL::action('Wap\MsgOpinionController@addOpinion')}}">
        <div class="list inline-labels no-hairlines-md">
          <ul>
            
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">吐槽点</div>
                <div class="item-input-wrap">
                  <textarea placeholder="内容不得超过25个字符" name="content"></textarea>
                </div>
              </div>
            </li>
            <li class="item-content ">
              <button class="col button  button-round save-sub-data" style="font-size: 30px;color: #cccccc;">
              提交
              </button>
            </li>
          </ul>
          
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
        //$$('.save-sub-data').attr("disabled","true");
        var postdata = app.form.convertToData("#mainForm");
        //console.log(postdata);
        app.request.post(postUrl,postdata , function (data) {
        var jsonData = JSON.parse(data);
            if (jsonData.data['redirect'] && jsonData.code!=0) {
                app.dialog.confirm('请先登录！','客官您好', function () {
                  location.href=jsonData.data['redirect'];
                });
            } else if(jsonData.data['redirect'] && jsonData.code==0) {
                app.dialog.confirm(jsonData.msg,'您好：', function () {
                  location.href=jsonData.data['redirect'];
                });
            } else {
                app.dialog.alert(jsonData.data['res'],jsonData.msg);
            }
        });
    });
</script>
@stop