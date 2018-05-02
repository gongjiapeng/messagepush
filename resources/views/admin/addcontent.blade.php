@extends('layouts.index')
@section('css')
<title>信息列表管理</title>
<meta name="keywords" content="信息修改" />
<meta name="description" content="信息修改" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@stop

  @section('content')
  
  <div class="page-content">
    <div class="page-header">
      <h1>
        发布信息
      </h1>
    </div><!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" id="ajaxform"  action="{{URL::action('Admin\MsgContentController@saveContent')}}" method="post">
          
          {{ csrf_field() }}
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 信息标题 </label>
            <div class="col-sm-9">
              <input type="text" name="title" id="form-field-1" placeholder="信息标题" class="col-xs-10 col-sm-5"  value=""/>
            </div>
          </div>
          <div class="space-4"></div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 所属大学 </label>
            <div class="col-sm-4">
              <select class="form-control input-lg" name="sid">
              @foreach($schoolList as $li)
                <option value="{{$li['sid']}}">{{$li['schoolname']}}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="space-4"></div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 信息分组 </label>
            <div class="col-sm-4">
              <select class="form-control input-lg" name="msgtypeid">
              @foreach($msgtypelist as $li)
                <option value="{{$li['tid']}}">{{$li['msgtype']}}</option>
              @endforeach
              </select>
            </div>
          </div>

          <div class="space-4"></div>
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 信息内容 </label>
            <div class="col-sm-4">
              <textarea class="form-control" name="content" rows="3"></textarea>
            </div>
          </div>
         
          <div class="space-4"></div>
          <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
              <button class="btn btn-info save-btn" type="button">
                <i class="icon-ok bigger-110"></i>
                保存
              </button>
              &nbsp; &nbsp; &nbsp;
              <button class="btn" type="reset">
                <i class="icon-undo bigger-110"></i>
                取消
              </button>
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
