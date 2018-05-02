@extends('layouts.index')
@section('css')
<title>添加学校</title>
<meta name="keywords" content="添加学校" />
<meta name="description" content="添加学校" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@stop

  @section('content')
  
  <div class="page-content">
    <div class="page-header">
      <h1>
        学校添加
      </h1>
    </div><!-- /.page-header -->
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" id="ajaxform"  action="{{URL::action('Admin\SchoolController@saveSchool')}}" method="post">
          
          {{ csrf_field() }}
          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 学校名称 </label>
            <div class="col-sm-9">
              <input type="text" name="schoolname" id="form-field-1" placeholder="学校名称" class="col-xs-10 col-sm-5"  value=""/>
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
