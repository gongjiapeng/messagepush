
@extends('layouts.wap')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <div class="left">
      <a href="javascript:history.back(-1)" class="link">
        <i class="icon icon-back"></i>
        <span class="ios-only">返回</span>
      </a>
    </div>
    <div class="title">我发布的信息</div>
    <div class="right"></div>
    <div class="subnavbar">
      <div class="subnavbar-inner">
        <div class="segmented segmented-raised"  data-status="{{$status or 0}}">
          <a class="button tab-link external @if(!$status) tab-link-active @endif " href="{{URL::action('Wap\MsgUserCenterController@getSubscribeContentList')}}">全部</a>
          <a  class="button tab-link external @if($status==1) tab-link-active @endif" href="{{URL::action('Wap\MsgUserCenterController@getSubscribeContentList',['status'=>1])}}">上架中</a>
          <a  class="button tab-link external @if($status==2) tab-link-active @endif" href="{{URL::action('Wap\MsgUserCenterController@getSubscribeContentList',['status'=>2])}}">已下架</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="page-content infinite-scroll hide-navbar-on-scroll" >
    <input type="hidden" id="page" value="{{$params['page']}}">
    <input type="hidden" id="ajaxurl" value="{{$ajaxurl}}">
    <div class="tabs">
        <div class="tab tab-active" id="tab1">
            <div class="block">
                <div class="list media-list">
                    <ul>
                        @if($list['list'])
                            @foreach($list['list'] as $li)
                            <li>
                                <a href="{{URL::action('Wap\MsgContentController@getContentDetail',['id'=>$li->id])}}" class=" external item-content">
                                    <div class="item-media">
                                        <img src="{{$li->imgurl or $defaultimg}}" width="80" height="80" >
                                    </div>
                                    <div class="item-inner">
                                        <div class="item-title-row">
                                            <div class="item-title">{{$li->title}}</div>
                                            <div class="item-after">{{$li->msgtype}}</div>
                                        </div>
                                        <div class="item-subtitle">{{$li->remarks}}</div>
                                        <div class="item-text">{{$li->schoolname}}</div>
                                        
                                        <div class="item-text">
                                            <div class="row">
                                                @if (!$status)
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>1])}}" class="button col button-round upstatus">上架</button>
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>2])}}" class="button col button-round upstatus">下架</button>
                                                @elseif ($status == 1)
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>2])}}" class="button col button-round upstatus">下架</button>
                                                @elseif ($status == 2)
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>1])}}" class="button col button-round upstatus">上架</button>
                                                @endif
                                            </div>
                                            
                                        </div>
                                    </div>
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
        </div>
        <div class="tab " id="tab2">
            <div class="block">
                <div class="list media-list">
                    <ul>
                        @if($list['list'])
                            @foreach($list['list'] as $li)
                            <li>
                                <a href="{{URL::action('Wap\MsgContentController@getContentDetail',['id'=>$li->id])}}" class=" external item-content">
                                    <div class="item-media">
                                        <img src="{{$li->imgurl or $defaultimg}}" width="80" height="80" >
                                    </div>
                                    <div class="item-inner">
                                        <div class="item-title-row">
                                            <div class="item-title">{{$li->title}}</div>
                                            <div class="item-after">{{$li->msgtype}}</div>
                                        </div>
                                        <div class="item-subtitle">{{$li->remarks}}</div>
                                        <div class="item-text">{{$li->schoolname}}</div>
                                        
                                        <div class="item-text">
                                            <div class="row">
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>2])}}" class="button col button-round upstatus">下架</button>
                                            </div>
                                            
                                        </div>
                                    </div>
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
        </div>
        <div class="tab " id="tab3">
            <div class="block">
                <div class="list media-list">
                    <ul>
                        @if($list['list'])
                            @foreach($list['list'] as $li)
                            <li>
                                <a href="{{URL::action('Wap\MsgContentController@getContentDetail',['id'=>$li->id])}}" class=" external item-content">
                                    <div class="item-media">
                                        <img src="{{$li->imgurl or $defaultimg}}" width="80" height="80" >
                                    </div>
                                    <div class="item-inner">
                                        <div class="item-title-row">
                                            <div class="item-title">{{$li->title}}</div>
                                            <div class="item-after">{{$li->msgtype}}</div>
                                        </div>
                                        <div class="item-subtitle">{{$li->remarks}}</div>
                                        <div class="item-text">{{$li->schoolname}}</div>
                                        
                                        <div class="item-text">
                                            <div class="row">
                                                <button ajaxUrl="{{URL::action('Wap\MsgContentController@updateContentStatus',['id'=>$li->id,'status'=>1])}}" class="button col button-round upstatus">上架</button> 
                                            </div>
                                            
                                        </div>
                                    </div>
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
        </div>
    </div>
    <div class="infinite-scroll-preloader">
        <div class="preloader"></div>
    </div>
</div>
@stop
@section('javascript')
<script>
//var contenturl="http://jiapeng.messagepush.com/wap/updatecontentstatus?id=";
var contenturl="http://messagepush.dev.haoshiqi.net/wap/updatecontentstatus?id=";
//var contenturl="http://messagepush.haoshiqi.net/wap/updatecontentstatus?id=";
var app = new Framework7();
 
var $$ = Dom7;
var status = $$('.segmented-raised').attr('data-status');
// 加载flag
var loading = false;
 
// 上次加载的序号
var lastIndex = $$('#tab .media-list li').length;
 
// 最多可加载的条目
var maxItems = 60;
 
// 每次加载添加多少条目
var itemsPerLoad = 5;
if (lastIndex<itemsPerLoad) {
    $$('.infinite-scroll-preloader').remove();
}
 
// 注册'infinite'事件处理函数
app.infiniteScroll.create('.infinite-scroll')
$$('.infinite-scroll').on('infinite', function () {
var ajaxurl = $$('#ajaxurl').val();
var page= $$('#page').val();
var sid = $$('#sid').val();
var tid = $$('#tid').val();
//console.log(page)
  // 如果正在加载，则退出
  if (loading)  return;
  // 设置flag
  loading = true;
  app.request.post(ajaxurl, {page:page,sid:sid,tid:tid}, function (data) {
        var jsonData = JSON.parse(data);
        loading = false;
        console.log(page)
        if (!jsonData.data['res']['list']['list']){
            loading = false;
            // 加载完毕，则注销无限加载事件，以防不必要的加载
            app.infiniteScroll.destroy('.infinite-scroll');
            // 删除加载提示符
            $$('.infinite-scroll-preloader').remove();
            return;
        }
        page = Number(page)+Number(1);
        $$('#page').val(page);
        //console.log(jsonData.data['res']['list']['list'].length);
        var html = '';
        var buttonhtml = '';
        if (jsonData.data['res']['list']['list'].length>0) {

            for (var i = 0; i < jsonData.data['res']['list']['list'].length; i++) {
                if (status==0) {
                    buttonhtml='<button ajaxUrl='+contenturl+jsonData.data['res']['list']['list'][i]['id']+'&status=1 class="button col button-round upstatus">上架</button><button ajaxUrl='+contenturl+jsonData.data['res']['list']['list'][i]['id']+'&status=2 class="button col button-round upstatus">下架</button>';
                } else if (status==1) {
                    buttonhtml='<button ajaxUrl='+contenturl+jsonData.data['res']['list']['list'][i]['id']+'&status=2 class="button col button-round upstatus">下架</button>';
                } else {
                    buttonhtml='<button ajaxUrl='+contenturl+jsonData.data['res']['list']['list'][i]['id']+'&status=1 class="button col button-round upstatus">上架</button>';
                }
                if (!jsonData.data['res']['list']['list'][i]['imgurl']) {
                    imgurl=jsonData.data['res']['defaultimg'];
                } else {
                    imgurl=jsonData.data['res']['list']['list'][i]['imgurl'];
                }
                console.log(imgurl);
                html += '<li><a href="#" class="item-link item-content"><div class="item-media"><img src="'+imgurl+'" width="80" height="80" ></div><div class="item-inner"><div class="item-title-row"><div class="item-title">' + jsonData.data['res']['list']['list'][i]['title'] + '</div><div class="item-after">'+jsonData.data['res']['list']['list'][i]['msgtype']+'</div></div><div class="item-subtitle">'+jsonData.data['res']['list']['list'][i]['remarks']+'</div><div class="item-text">'+jsonData.data['res']['list']['list'][i]['schoolname']+'</div>'+'<div class="row">'+buttonhtml+'</div></div></a></li>';
            }
        } else {
            loading = false;
            app.infiniteScroll.destroy('.infinite-scroll');
            // 删除加载提示符
            $$('.infinite-scroll-preloader').remove();
            return;
        }
        // 添加新条目
        $$('.media-list ul').append(html);
        // 更新最后加载的序号
        lastIndex = $$('.media-list li').length;
        
    });
});
//上下架
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