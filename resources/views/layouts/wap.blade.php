<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="theme-color" content="#2196f3">
  <title>Face To Face</title>

  <link rel="stylesheet" href="{{ URL::asset('/') }}wap/css/framework7.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}wap/css/app.css">
  <link rel="apple-touch-icon" href="{{ URL::asset('/') }}wap/img/f7-icon-square.png">
  <link rel="icon" href="{{ URL::asset('/') }}wap/img/f7-icon.png">
  @yield('css')
 
</head>
<body >
  <div id="app" >
    <div class="statusbar"></div>

    <div class="view view-main view-init ios-edges" data-url="/">
      <div class="page">
      @yield('content')
        <div class="toolbar tabbar tabbar-labels toolbar-bottom-md">
          <div class="toolbar-inner">
            <a href="{{URL::action('Wap\MsgIndexController@index')}}" class="tab-link external">
              <i class="icon f7-icons ios-only">home</i>
              <i class="icon icon-fill f7-icons ios-only">home_fill</i>
              <i class="icon material-icons md-only">home</i>
              <span class="tabbar-label">首页</span>
            </a>
            <a class= "external tab-link" href="{{URL::action('Wap\MsgContentController@getContentList')}}" >
              <i class="icon f7-icons ios-only">eye</i>
              <i class="icon icon-fill f7-icons">eye_fill</i>
              
              <span class="tabbar-label">列表</span>
            </a>
            <a class="external tab-link" href="{{URL::action('Wap\MsgIndexController@addMsgOpinion')}}"  >
              <i class="icon f7-icons ">compose</i>
              <i class="icon icon-fill f7-icons ios-only">compose_fill</i>
              
              <span class="tabbar-label">吐槽</span>
            </a>
            <a class="external tab-link" href="{{URL::action('Wap\MsgUserCenterController@index')}}"  >
              <i class="icon f7-icons ios-only">person</i>
              <i class="icon icon-fill f7-icons ios-only">person_fill</i>
              <i class="icon material-icons md-only">person</i>
              <span class="tabbar-label">个人中心</span>
            </a>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ URL::asset('/') }}wap/js/framework7.js"></script>
  <script src="{{ URL::asset('/') }}wap/js/routes.js"></script>
  <script src="{{ URL::asset('/') }}wap/js/app.js"></script>
  @yield('javascript')
</body>
</html>
