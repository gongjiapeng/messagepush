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
    <div class="title">发布信息</div>
    <div class="right">
      <a data-picker=".picker-1" class="link open-picker" >
        <i class="icon material-icons md-only">stars</i>
      </a>
    </div>
  </div>
</div>
<div class="page-content">
    <form class="list" id="mainForm" ajaxUrl="{{URL::action('Wap\MsgContentController@saveContent')}}">
        <div class="list inline-labels no-hairlines-md">
          <ul>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">标题</div>
                <div class="item-input-wrap">
                  <input type="text" placeholder="title" name="title">
                  <span class="input-clear-button"></span>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">所属标签</div>
                <div class="item-input-wrap input-dropdown-wrap">
                  <select placeholder="Please choose..." name="tid">
                    @foreach($tmptypeinfo as $li)
                        <option value="{{$li['tid']}}" >{{$li['msgtype']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">所属大学</div>
                <div class="item-input-wrap input-dropdown-wrap">
                  <select name="sid" placeholder="Please choose...">
                    @foreach($schoollist as $li)
                        <option value="{{$li['sid']}}" >{{$li['schoolname']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">联系方式</div>
                <div class="item-input-wrap">
                  <input type="text" placeholder="phone or email" name="phone">
                  <span class="input-clear-button"></span>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">备注</div>
                <div class="item-input-wrap">
                  <input type="text" name="remarks" placeholder="remark">
                  <span class="input-clear-button"></span>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">图片</div>
                  <div class="item-input-wrap" id="zwb_upload" uploadurl="{{URL::action('Wap\UploadController@doupload')}}">
                      <label for="upload">上传图片</label>
                      <input type="file" id="upload"  class="add">
                  </div>
                  <span id="callbackPath2"></span>
                  <input type="hidden" name="filePath" id="imgpath">
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-media">
                <i class="icon demo-list-icon"></i>
              </div>
              <div class="item-inner">
                <div class="item-title item-label">内容</div>
                <div class="item-input-wrap">
                  <textarea placeholder="content" name="content"></textarea>
                </div>
              </div>
            </li>
            <li class="item-content ">
              <button class="col button  button-round save-sub-data" style="font-size: 30px;color: #cccccc;">
              发布
              </button>
            </li>
          </ul>
          
        </div>
      </form>
</div>
@stop
@section('javascript')
  <script src="{{ URL::asset('/') }}wap/js/jquery-1.10.2.js"></script>
  <script src="{{ URL::asset('/') }}wap/js/upload.js"></script>
  <script>
    (function($){
        var uploadurl = $("#zwb_upload").attr('uploadurl');
        //alert(uploadurl);
        $("#zwb_upload").bindUpload({
          url:uploadurl,//上传服务器地址
          callbackPath:"#callbackPath2",//绑定上传成功后 
          num:1,//上传数量的限制 默认为空 无限制
          type:"jpg|png|gif",//上传文件类型 默认为空 无限制
          size:5,//上传文件大小的限制,默认为5单位默认为mb
          imgpath:"#imgpath",
      });
    })(jQuery);
</script>
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