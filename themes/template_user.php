<?php
	//print_r($_SESSION[$sessionname]);
	empty($_POST['old_password']) ? $old_password = '' : $old_password = $_POST['old_password'];
	empty($_POST['new_password']) ? $new_password = '' : $new_password = $_POST['new_password'];
	empty($_POST['confirm_new_password']) ? $confirm_new_password = '' : $confirm_new_password = $_POST['confirm_new_password'];
	empty($_POST['username_session']) ? $username = '' : $username = $_POST['username_session'];
	$message = "";
	$sessionname = 'digix';
	
	if(isset($_POST['submit_change_password'])){
		
		if(empty($confirm_new_password) || empty($old_password) || empty($new_password)){
			$message = "Wajib diisi semuanya!";
		}else{
			if((md5(sha1($old_password))) == $_SESSION[$sessionname]->password){
				if($new_password == $confirm_new_password){
					$q = "
						UPDATE 
							tbl_user
						SET
							password = '".md5(sha1($new_password))."'
						WHERE
							password = '".md5(sha1($old_password))."'
						AND
							username = '".$username."'
					";
					$sql = mysqli_query($database->connection, $q);
					
					echo status("You Already Change Your Password, Thanks :D ");
					echo redirect('home');
					return;
				} else {
					echo status("Your new Password and Your Verification New Password not match");
					echo redirect('home');
					return;
				}
			} else {
				echo status("Your Old Password is not match");
				echo redirect('home');
				return;
			}
	    } 
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>.:: DIGI-X DASHBOARD ::.</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/bower_components/bootstrap/dist/css/bootstrap.css');?>">
	<!-- JQUERY UI !-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Font Awesome -->
    <!--<link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/bower_components/font-awesome/css/font-awesome.min.css');?>">-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/bower_components/Ionicons/css/ionicons.min.css');?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');?>">
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/bower_components/datatables.net-bs/css/dataTables.fontAwesome.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/dist/css/AdminLTE.css');?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/dist/css/skins/_all-skins.css');?>">
	
    <link rel="stylesheet" href="<?php echo url('themes/adminlte-2.4.3/dist/css/loading.css');?>">

    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<!-- jQuery 3 -->
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button);
	</script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js');?>"></script>
	<!-- DataTables -->
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>
	<!-- Slimscroll -->
	<script src="<?php echo url('themes/adminlte-2.4.3/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
    <!-- untuk export datatables -->
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo url('themes/adminlte-2.4.3/dist/js/adminlte.min.js');?>"></script>
	<style>
		.cap_1{
			background:#b15b48;
			color: #fff;
			text-align:center;
		}
		.cap_2{
			background:#f47c55;
			color: #fff;
			text-align:center;
		}
		.cap_3{
			background:#f89939;
			color: #fff;
			text-align:center;
		}
		.cap_4{
			background:#fecc67;
			color: #fff;
			text-align:center;
		}
		.cap_5{
			background:#feb21b;
			color: #fff;
			text-align:center;
		}

	</style>
</head>
<body class="fixed hold-transition skin-red sidebar-collapse sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D-X</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>DIGI-X</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo url('themes/img/User_icon_2.svg.png');?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION[$sessionname]->fullname;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo url('themes/img/User_icon_2.svg.png');?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo $_SESSION[$sessionname]->username;?>
                  <small><?php echo $_SESSION[$sessionname]->roles;?></small>
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#myModalChangePassword">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo url('logout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo url('themes/img/User_icon_2.svg.png');?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION[$sessionname]->username;?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php include('page/admin/menu.php');?>
      
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<section class="content-header">
      <h1>
        <?php 
        echo  $loader['title'];?>
      </h1>
      <ol class="breadcrumb">
      	<?php
      	$path_link = '<li><a href="#"><i class="fa fa-home"></i> Home</a></li>';
      	$pat = '';
        $count_path = count($path);
        for($i=0;$i<$count_path;$i++){
        	if($i <$count_path){
        		$path_link = $path_link.'<li>'.$path[$i].'</li>';
        		if($i == 0){
        			$pat = $path[$i];
        		}else{
        			$pat = $pat.'/'.$path[$i];
        	  }
        	}elseif($i==$count_path){
        		$path_link = $path_link.'<li class="active">'.$path[$i].'</li>';
        		$pat = $pat.'/'.$path[$i];
        	}
        }
      	echo $path_link;
      	//echo $pat;
        ?>
      </ol>
    </section>
    <?php if(!empty($loader['konten']))require_once $loader['konten']; ?>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <!--<b>Version</b> 2.4.0 -->
    </div>
      <strong>Copyright &copy; 2018<a> Customer Loyalty and Retention Development Area 2 </a></strong> powered by <strong><a>RPA - IT Support Bussiness JABAR</a></strong>. All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<div id="myModalChangePassword" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<form method="post">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Change Password</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" name="username_session" value="<?php echo $_SESSION[$sessionname]->username?>" id="username_session">
				<div class="form-group">
					<label for="old_password">Old Password*</label>
					<input type="password" class="form-control" name="old_password" id="old_password" required placeholder="Old Password *">
				</div>
				<div class="form-group">
					<label for="new_password">New Password*</label>
					<input type="password" class="form-control" name="new_password" id="new_password" required placeholder="New Password *">
				</div>
				<div class="form-group">
					<label for="confirm_new_password">Confirm New Password*</label>
					<input type="password" class="form-control" name="confirm_new_password" id="confirm_new_password" required placeholder="Confirm New Password *">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" name="submit_change_password">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</form>
    </div>

  </div>
</div>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo url('themes/adminlte-2.4.3/dist/js/pages/dashboard.js');?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo url('themes/adminlte-2.4.3/dist/js/demo.js');?>"></script>
    <script>
    // Click handler can be added latter, after jQuery is loaded...
    $('.sidebar-toggle').click(function(event) {
      event.preventDefault();
      if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
        sessionStorage.setItem('sidebar-toggle-collapsed', '');
        console.log('klik1');
      } else {
        sessionStorage.setItem('sidebar-toggle-collapsed', '1');
      console.log('klik2');
      }
    });
    
    $( document ).ready(function() {
		$(".datepicker").datepicker({ dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, });
		$("#filter_date").datepicker({ dateFormat: 'yy-mm-dd' });
		$("#end_date").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#start_date").datepicker({ dateFormat: 'yy-mm-dd' }).bind("change",function(){
            var minValue = $(this).val();
            minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
            minValue.setDate(minValue.getDate());
            $("#end_date").datepicker( "option", "minDate", minValue );
        });
		
		$('.timepicker').timepicker({
			showInputs: false,
			showMeridian: false
		});

        var body = $('body');
        var dropselvalue = parseInt(sessionStorage.getItem("sidebar-toggle-collapsed"));
        if (dropselvalue == 1) {
            body.removeClass('sidebar-collapse');
            //body.removeClass('sidebar-mini');
        }else{
            body.addClass('sidebar-collapse');
            //body.addClass('sidebar-mini');
        }
        function dinamicmenu() {
        var url = window.location.pathname;
            console.log(url);
            var che = url.replace("/boopati/","");
            console.log(che);
            // Will only work if string in href matches with location
            
            $('li a[href$="' + che + '"]').parent().addClass("active");
            $('.treeview-menu li a[href$="' + che + '"]').parent().addClass("active");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().addClass("active");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().addClass("menu-open");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().css("display","block");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().addClass("active");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().addClass("menu-open");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().css("display","block");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().parent().addClass("active");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().parent().addClass("menu-open");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().parent().parent().css("display","block");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().parent().parent().parent().addClass("active");
            $('.treeview .treeview-menu li a[href$="' + che + '"]').parent().parent().parent().parent().parent().parent().parent().parent().parent().addClass("menu-open");
            //console.log(checl);
            // Will also work for relative and absolute hrefs
            
        };
        
    dinamicmenu();
    });
     
  </script>
  <!-- Modal -->
</body>
</html>
