@extends('layouts.wap')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <div class="left">
      <a href="#" class="link icon-only panel-open" data-panel="left">
        <i class="icon f7-icons ios-only">menu</i>
        <i class="icon material-icons md-only">menu</i>
      </a>
    </div>
    <div class="title sliding">Face To Face</div>
    <div class="right">
      <a class="link icon-only searchbar-enable" data-searchbar=".searchbar-components">
        <i class="icon f7-icons ios-only">search_strong</i>
        <i class="icon material-icons md-only">search</i>
      </a>
    </div>
    <form  id="search-contentlist" data-search-container=".components-list" data-action="{{URL::action('Wap\MsgContentController@getContentList')}}" data-search-in="a" class="searchbar searchbar-expandable searchbar-components searchbar-init">
      <div class="searchbar-inner">
        <div class="searchbar-input-wrap">
          <input type="search" name="keywords" placeholder="Search components"/>
          <i class="searchbar-icon"></i>
          <span class="input-clear-button"></span>
        </div>
        <span class="searchbar-disable-button">取消</span>
      </div>
    </form>
  </div>
</div>

<div class="page-content">
  <div class="list">
      <div class="item-title">
        <canvas id="canvas" height="200" dataurl="{{URL::action('Wap\MsgIndexController@addMsgContent')}}"></canvas>
      </div>
      <div class="item-title">
        <canvas id="canvas2" height="200" dataurl="{{URL::action('Wap\MsgIndexController@msgTypeList')}}"></canvas>
      </div>
     
  </div>
</div>
@stop 

@section('javascript')

<script>
var myApp = new Framework7();
var $$ = Dom7;
   //搜索
  $$('#search-contentlist').on('submit', function(){
      var formData=myApp.form.convertToData('#search-contentlist');
      var keywords=encodeURIComponent(formData.keywords);
      window.location.href=$$(this).data("action")+'?keywords='+keywords;
    
  });   
</script>

  <script type="text/javascript">

    var canvas=document.getElementById("canvas");
    var pushurl=canvas.attributes["dataurl"].nodeValue;
    //alert(redirecturl);
    var cxt=canvas.getContext("2d");
    cxt.beginPath();
    cxt.arc(180,100,100,0,360,false);
    cxt.fillStyle="#FFCCCC";//填充颜色,默认是黑色
    cxt.fill();//画实心圆
    cxt.fillStyle = "#ffffff";//颜色
    cxt.font = "bold 50px consolas";//字体
    cxt.textBaseline = "middle";//竖直对齐
    cxt.textAlign = "center";//水平对齐　
    cxt.fillText("发布", 180, 100, 160);//绘制文字
    cxt.closePath();
    canvas.addEventListener('click',function(){
      window.location.href=pushurl;
    },false);

    var canvas2=document.getElementById("canvas2");
    var subscribeurl=canvas2.attributes["dataurl"].nodeValue;
    var cxt1=canvas2.getContext("2d");
    cxt1.beginPath();
    cxt1.arc(180,100,100,0,360,false);
    cxt1.fillStyle="#FFCCCC";//填充颜色,默认是黑色
    cxt1.fill();//画实心圆
    cxt1.fillStyle = "#ffffff";//颜色
    cxt1.font = "bold 50px consolas";//字体
    cxt1.textBaseline = "middle";//竖直对齐
    cxt1.textAlign = "center";//水平对齐　
    cxt1.fillText("订阅", 180, 100, 160);//绘制文字
    cxt1.closePath();
    canvas2.addEventListener('click',function(){
      window.location.href=subscribeurl;
    },false);
  </script>
 
@stop