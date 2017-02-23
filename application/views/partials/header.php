	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo base_url('dashboard');?>"><img src="<?php echo $general_settings->logopathname;?>" width="230" height="50"></a>
		</div>
		<!-- /.navbar-header -->

		<ul class="nav navbar-top-links navbar-right">			
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="<?php echo base_url('profile');?>"><i class="fa fa-user fa-fw"></i> Profile</a></li>
					<li><a href="<?php echo base_url('settings');?>"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo base_url('user/logout');?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
				</ul>
				<!-- /.dropdown-user -->
			</li>
			<!-- /.dropdown -->
		</ul>
		<!-- /.navbar-top-links -->

		<?php echo $sidebar; ?>
	</nav>