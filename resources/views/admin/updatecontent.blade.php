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
				信息查看
				
			</h1>
		</div><!-- /.page-header -->
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ajaxform"  action="{{URL::action('Admin\MsgContentController@saveContent')}}" method="post">
					<input type="hidden" name="id" value="{{$msgInfo->id}}">
					{{ csrf_field() }}
					<div class="space-4"></div>
			        <div class="form-group">
			            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 所属学校： </label>
			            <div class="col-sm-9">
			            	<input type="text" name="title" readonly="true" id="form-field-1" placeholder="所属学校" class="col-xs-10 col-sm-5"  value="{{$msgInfo->schoolname}}"/>
			            	
<!-- 			              <select class="form-control input-lg" name="sid">
			              @foreach($schoolList as $li)
			                <option value="{{$li['sid']}}" @if($msgInfo->sid == $li['sid']) selected @endif >{{$li['schoolname']}}</option>
			              @endforeach
			              </select> -->
			            </div>
			        </div>
					<div class="space-4"></div>
			        <div class="form-group">
			            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 信息标签： </label>
			            <div class="col-sm-4">
			            	<input type="text" name="title" readonly="true" id="form-field-1" placeholder="信息标签" class="col-xs-10 col-sm-5"  value="{{$msgInfo->msgtype}}"/>
			            	
<!-- 			              <select class="form-control input-lg" name="msgtypeid">
			              @foreach($msgtypelist as $li)
			                <option value="{{$li['tid']}}" @if($msgInfo->tid==$li['tid']) selected @endif >{{$li['msgtype']}}</option>
			              @endforeach
			              </select> -->
			            </div>
			        </div>
			        <div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 标题： </label>
						<div class="col-sm-9">
							
							<input type="text" name="title" readonly="true" id="form-field-1" placeholder="标题" class="col-xs-10 col-sm-5"  value="{{$msgInfo->title}}"/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 发布人： </label>
						<div class="col-sm-9">
							<input type="text" name="title" readonly="true" id="form-field-1" placeholder="发布人" class="col-xs-10 col-sm-5"  value="{{$msgInfo->user_name}}"/>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 实物照片： </label>
						<div class="col-sm-9">
							<img width="40" height="40" src="{{$msgInfo->imgurl}}">
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 信息内容： </label>
						<div class="col-sm-4">
							<textarea class="form-control" name="content" rows="3">{{$msgInfo->content}}</textarea>
						</div>
					</div>

					<!-- <div class="space-4"></div>
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
					</div> -->
				</form>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div>
	
	@stop

@section('javascript')
	<script src="{{ URL::asset('/') }}/js/jquery.dataTables.js"></script>
	<script src="{{ URL::asset('/') }}/js/jquery.dataTables.min.js"></script>
@stop
