@extends('layouts.index')
@section('css')
<title>意见管理</title>
<meta name="keywords" content="意见管理" />
<meta name="description" content="意见管理" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@stop
  @section('content')
  <div class="page-content">
    <div class="page-header">
      <h1>
        意见查看
      </h1>
    </div><!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
          
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 昵称 </label>
              <div class="col-sm-9">
                <input type="text" name="title" id="form-field-1" placeholder="模板名称" class="col-xs-10 col-sm-5"  value="{{$opinionInfo->user_name}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 信息内容 </label>
              <div class="col-sm-4">
                <textarea class="form-control" name="content" rows="3">{{$opinionInfo->content}}</textarea>
              </div>
            </div>
          </form>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div>
  @stop

@section('javascript')
  <script src="{{ URL::asset('/') }}/js/jquery.dataTables.js"></script>
  <script src="{{ URL::asset('/') }}/js/jquery.dataTables.min.js"></script>
@stop
