@extends('layouts.index')
@section('css')
<title>微信菜单管理</title>
<meta name="keywords" content="微信菜单管理" />
<meta name="description" content="微信菜单管理" />
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
            <li class="active">菜单列表</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                菜单列表
                
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
                                        <th>菜单名称</th>
                                        <th>菜单类型</th>
                                        <th>
                                            <i class="icon-time bigger-110 hidden-480"></i>
                                            菜单链接
                                        </th>
                                        
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $li)
                                    <tr>
                                        <td class="center">
                                            <label>
                                                <input type="checkbox" name="id" class="ace" value="{{$loop->iteration}}" />
                                                <span class="lbl"></span>
                                            </label>
                                        </td>
                                        
                                        <td>{{$li['name']}}</td>
                                        <td>{{$li['type']}}</td>
                                        <td>{{$li['url']}}</td>

                                        

                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                
                                                <a class="green" id="">
                                                    <i class="icon-pencil bigger-130"></i>
                                                </a>

                                                <span class="red remove-tmp" data-href="" data-tocken={{ csrf_token() }}>
                                                    <i class="icon-trash bigger-130"></i>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop
@section('javascript')
    <script src="{{ URL::asset('/') }}/js/jquery.dataTables.js"></script>
    <script src="{{ URL::asset('/') }}/js/jquery.dataTables.min.js"></script>

@stop
