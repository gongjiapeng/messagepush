<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>信息管理平台</title>
		
		<!-- basic styles -->
		<link href="{{ URL::asset('/') }}/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="{{ URL::asset('/') }}/css/font-awesome.min.css" />

		<link rel="stylesheet" href="{{ URL::asset('/') }}/css/ace.min.css" />
		<script>
	        window.Laravel = <?php echo json_encode([
	            'csrfToken' => csrf_token(),
	        ]); ?>
	    </script>
		
		@yield('css')
	</head>

	<body>
		@if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
        @else
		<div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
	
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							<i class="icon-leaf"></i>
							信息管理系统后台
						</small>
					</a><!-- /.brand -->
				</div><!-- /.navbar-header -->

				<div class="navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="{{ URL::asset('/') }}/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>欢迎光临,</small>
									{{ Auth::user()->name }}
								</span>

								<i class="icon-caret-down"></i>
							</a>

							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="icon-cog"></i>
										设置
									</a>
								</li>

								<li>
									<a href="#">
										<i class="icon-user"></i>
										个人资料
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
										<i class="icon-off"></i>

										退出
									</a>
									<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
								</li>
							</ul>
						</li>
					</ul><!-- /.ace-nav -->
				</div><!-- /.navbar-header -->
			</div><!-- /.container -->
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<div class="sidebar-shortcuts" id="sidebar-shortcuts">
						<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
							<button class="btn btn-success">
								<i class="icon-signal"></i>
							</button>

							<button class="btn btn-info">
								<i class="icon-pencil"></i>
							</button>

							<button class="btn btn-warning">
								<i class="icon-group"></i>
							</button>

							<button class="btn btn-danger">
								<i class="icon-cogs"></i>
							</button>
						</div>

						<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>

							<span class="btn btn-info"></span>

							<span class="btn btn-warning"></span>

							<span class="btn btn-danger"></span>
						</div>
					</div><!-- #sidebar-shortcuts -->

					<ul class="nav nav-list">
						
						<li class="active">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> 信息管理 </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							<ul class="submenu">
								<li @if ($activeType=='tmp') class="active" @endif>
									<a href="{{URL::action('Admin\MsgContentController@getContentList')}}">
										<i class="icon-double-angle-right"></i>
										信息列表
									</a>
								</li>
							</ul>
						</li>
						<li class="active">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> 分类管理 </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							<ul class="submenu">
								<li @if ($activeType=='1') class="active" @endif>
									<a href="{{URL::action('Admin\MsgTypeController@getMsgTypeList',['parenttype'=>1])}}">
										<i class="icon-double-angle-right"></i>
										书籍
									</a>
								</li>
								<li @if ($activeType=='2') class="active" @endif>
									<a href="{{URL::action('Admin\MsgTypeController@getMsgTypeList',['parenttype'=>2])}}">
										<i class="icon-double-angle-right"></i>
										电子设备
									</a>
								</li>
								<li @if ($activeType=='3') class="active" @endif>
									<a href="{{URL::action('Admin\MsgTypeController@getMsgTypeList',['parenttype'=>3])}}">
										<i class="icon-double-angle-right"></i>
										办公用品
									</a>
								</li>
								<li @if ($activeType=='4') class="active" @endif>
									<a href="{{URL::action('Admin\MsgTypeController@getMsgTypeList',['parenttype'=>4])}}">
										<i class="icon-double-angle-right"></i>
										生活用品
									</a>
								</li>
							</ul>
						</li>
						<li class="active">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> 学校管理 </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							<ul class="submenu">
								<li @if ($activeType=='school') class="active" @endif>
									<a href="{{URL::action('Admin\SchoolController@getSchoolList')}}">
										<i class="icon-double-angle-right"></i>
										学校
									</a>
								</li>
								
							</ul>
						</li>
						<li class="active">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> 意见管理 </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							<ul class="submenu">
								<li @if ($activeType=='opinion') class="active" @endif>
									<a href="{{URL::action('Admin\MsgOpinionController@getOpinionList')}}">
										<i class="icon-double-angle-right"></i>
										学生意见
									</a>
								</li>
								
							</ul>
						</li>
						<li class="active">
							<a href="#" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text"> 微信菜单 </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							<ul class="submenu">
								<li @if ($activeType=='wechatmenu') class="active" @endif>
									<a href="{{URL::action('Admin\WechatMenuController@getMenuList')}}">
										<i class="icon-double-angle-right"></i>
										菜单列表
									</a>
								</li>
								
							</ul>
						</li>
					</ul><!-- /.nav-list -->

					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>

					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>

				<div class="main-content">
					@yield('content')
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		@endif
		<script src="{{ URL::asset('/') }}/js/ace-extra.min.js"></script>
		<script src="{{ URL::asset('/') }}/js/jquery-2.0.3.min.js"></script>
		<script src="{{ URL::asset('/') }}/js/bootstrap.min.js"></script>
		<script src="{{ URL::asset('/') }}/js/ace-elements.min.js"></script>
		<script src="{{ URL::asset('/') }}/js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		@yield('javascript')
		
	
</body>
</html>

