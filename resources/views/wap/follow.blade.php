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
    <div class="title">欢迎关注</div>
  </div>
</div>
<div class="page-content">
    <div class="block">     
        <div align="center">
           <img src="{{$qrcodeUrl}}" alt="" width="300px" height="300px" align="center">
        </div>
        <p align="center" style="color: red;">长按关注</p>
    </div>
</div>
@stop