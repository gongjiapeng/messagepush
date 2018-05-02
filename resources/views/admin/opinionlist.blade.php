@extends('layouts.index')
@section('css')
<title>信息列表管理</title>
<meta name="keywords" content="信息列表管理" />
<meta name="description" content="信息列表管理" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@stop

    @section('content')
    <div class="breadcrumbs" id="breadcrumbs">
        <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
        </script>

        <ul class="breadcrumb">
            <li>
                <i class="icon-home home-icon"></i>
                <a href="#">首页</a>
            </li>
            <li class="active">意见列表</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                意见列表
                
            </h1>
        </div><!-- /.page-header -->
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            
                            <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center">
                                            <label>
                                                <input type="checkbox" class="ace" />
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th>id</th>
                                        <th>用户昵称</th>
                                       
                                        <th class="hidden-480">内容</th>
                                        <th>
                                            <i class="icon-time bigger-110 hidden-480"></i>
                                            创建时间
                                        </th>
                                        <th class="hidden-480">最后修改时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $li)
                                    <tr>
                                        <td class="center">
                                            <label>
                                                <input type="checkbox" name="id" class="ace" value="{{$li->id}}" />
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="#">{{$li->id}}</a>
                                        </td>
                                        <td>{{$li->user_name}}</td>
                                        
                                        <td class="hidden-480">{{$li->content}}</td>
                                        <td>{{$li->created_at}}</td>
                                        <td class="hidden-480">
                                            <span class="label label-sm label-inverse arrowed-in">{{$li->updated_at}}</span>
                                        </td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                <a class="green" id="modalBtn" href="{{URL::action('Admin\MsgOpinionController@getOpinionInfo',['id'=>$li->id])}}">
                                                    <i class="icon-pencil bigger-130"></i>
                                                </a>
                                                <span class="red remove-tmp" data-href="{{URL::action('Admin\MsgOpinionController@delOpinionById')}}" data-value="{{$li->id}}" data-tocken={{ csrf_token() }}>
                                                    <i class="icon-trash bigger-130"></i>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" id="sample-table-2_info">每页显示{{$pageNum}}条
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_bootstrap">
                                    @if($list->hasPages())
                                        {!! $list->render() !!}  
                                    @endif
                                    </div>
                                </div>
                            </div>
                            </br></br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 弹出层 -->
   <!--  <div class="modal fade" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modal 标题</h4>
          </div>
          <div class="modal-body">
            <p>内容&hellip;</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary">确定</button>
          </div>
        </div>
      </div>
    </div>
     -->

    @stop

@section('javascript')
    <script src="{{ URL::asset('/') }}/js/jquery.dataTables.js"></script>
    <script src="{{ URL::asset('/') }}/js/jquery.dataTables.min.js"></script>
    <!-- <script type="text/javascript">
      $(function(){
        // dom加载完毕
        var $m_btn = $('#modalBtn');
        var $modal = $('#myModal');
        $m_btn.on('click', function(){
          $modal.modal({backdrop: 'static'});
        });
      });
    </script> -->
@stop
