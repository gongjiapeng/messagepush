
@extends('layouts.wap')

@section('content')
<div class="navbar">
    <div class="navbar-inner">
      <div class="left sliding">
        <a href="javascript:history.back(-1)" class="link back">
          <i class="icon icon-back"></i>
          <span class="ios-only">返回</span>
        </a>
      </div>
      <div class="title sliding">信息列表</div>
      <div class="right">
        <a class="link icon-only searchbar-enable" data-searchbar=".searchbar-demo">
          <i class="icon f7-icons ios-only">search_strong</i>
          <i class="icon material-icons md-only">search</i>
        </a>
      </div>
      <form id="search-contentlist" data-search-container=".search-list" data-search-in=".item-title" data-action="{{URL::action('Wap\MsgContentController@getContentList')}}" class="searchbar searchbar-expandable searchbar-demo searchbar-init">
        <div class="searchbar-inner">
          <div class="searchbar-input-wrap">
            <input type="search" name="keywords" placeholder="Search"/>
            <i class="searchbar-icon"></i>
            <span class="input-clear-button"></span>
          </div>
          <span class="searchbar-disable-button">取消</span>
        </div>
      </form>
    </div>
</div>
<div   class="page-content infinite-scroll" >
    <input type="hidden" id="page" value="{{$params['page']}}">
    <input type="hidden" id="keywords" value="{{$params['keywords']}}">
    <input type="hidden" id="sid" value="{{$params['sid']}}">
    <input type="hidden" id="tid" value="{{$params['tid']}}">
    <input type="hidden" id="ajaxurl" value="{{$ajaxurl}}">
    <div class="list media-list">
        <ul>
            @if($list['list'])
                @foreach($list['list'] as $li)
                <li>
                    <a href="{{URL::action('Wap\MsgContentController@getContentDetail',['id'=>$li->id])}}" class="external item-content">
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
</br>
    <div class="infinite-scroll-preloader">
        <div class="preloader"></div>
    </div>
</div>
@stop
@section('javascript')
<script>
var myApp = new Framework7();
var $$ = Dom7;
// 加载flag
var loading = false;
// 上次加载的序号
var lastIndex = $$('.media-list li').length;

// 最多可加载的条目
var maxItems = 60;
// 每次加载添加多少条目
var itemsPerLoad = 5;

// 注册'infinite'事件处理函数
if (lastIndex<itemsPerLoad) {
    $$('.infinite-scroll-preloader').remove();
}
myApp.infiniteScroll.create('.infinite-scroll')
$$('.infinite-scroll').on('infinite', function () {
var ajaxurl = $$('#ajaxurl').val();
var page= $$('#page').val();
var sid = $$('#sid').val();
var tid = $$('#tid').val();
var keywords = $$('#keywords').val();

//console.log(page)
  // 如果正在加载，则退出
  if (loading)  return;
  // 设置flag
  loading = true;
  myApp.request.post(ajaxurl, {page:page,sid:sid,tid:tid,keywords:keywords}, function (data) {
        var jsonData = JSON.parse(data);
        loading = false;
        console.log(page)
        if (!jsonData.data['res']['list']['list']){
            loading = false;
            // 加载完毕，则注销无限加载事件，以防不必要的加载
            myApp.infiniteScroll.destroy('.infinite-scroll');
            // 删除加载提示符
            $$('.infinite-scroll-preloader').remove();
            return;
        }
        page = Number(page)+Number(1);
        $$('#page').val(page);
        //console.log(jsonData.data['res']['list']['list'].length);
        var html = '';
        if (jsonData.data['res']['list']['list'].length>0) {

            for (var i = 0; i < jsonData.data['res']['list']['list'].length; i++) {
                if (!jsonData.data['res']['list']['list'][i]['imgurl']) {
                    imgurl=jsonData.data['res']['defaultimg'];
                } else {
                    imgurl=jsonData.data['res']['list']['list'][i]['imgurl'];
                }
                console.log(imgurl);
                html += '<li><a href="#" class="item-link item-content"><div class="item-media"><img src="'+imgurl+'" width="80" height="80" ></div><div class="item-inner"><div class="item-title-row"><div class="item-title">' + jsonData.data['res']['list']['list'][i]['title'] + '</div><div class="item-after">更多</div></div><div class="item-subtitle">'+jsonData.data['res']['list']['list'][i]['remarks']+'</div><div class="item-text">'+jsonData.data['res']['list']['list'][i]['schoolname']+'</div></div></a></li>';
            }
        } else {
            loading = false;
            myApp.infiniteScroll.destroy('.infinite-scroll');
            //myApp.detachInfiniteScroll($$('.infinite-scroll'));
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
//搜索
$$('#search-contentlist').on('submit', function(){
    var formData=myApp.form.convertToData('#search-contentlist');
    var keywords=encodeURIComponent(formData.keywords);
    window.location.href=$$(this).data("action")+'?keywords='+keywords;
  
});   
</script>
@stop