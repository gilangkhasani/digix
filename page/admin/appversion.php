    <style>
		.containers { 
			background-color: white;
			color : black;
			border:2px solid #ccc; 
			width:100%; 
			height: 150px; 
			overflow-y: scroll; 
			padding-left: 15px; 
			padding-right: 15px; 
		}
	</style>

	<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-edit"></i> App Version</h3>
            </div>
            <!-- /.box-header -->
              <?php
                
              ?>
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
                       if($_POST['id_app_version']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_apps_version (app_name, package_name, app_version, app_build_number) values(
                            '".$_POST['app_name']."','".$_POST['package_name']."','".$_POST['app_version']."','".$_POST['app_build_number']."'
                           )";
						   
                           $msg = "Data Menu telah ditambahkan";
                       }elseif($_POST['id_app_version']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_apps_version set 
                            app_name='".$_POST['app_name']."',
                            package_name='".$_POST['package_name']."',
                            app_version='".$_POST['app_version']."',
                            app_build_number='".$_POST['app_build_number']."'
                           where id_app_version='".$_POST['id_app_version']."'
                           ";
						   
                           $msg = "Data Sekolah telah diubah";
                       }elseif($_POST['id_app_version']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_apps_version where id_app_version='".$_POST['id_app_version']."'
                           ";
						   
                           $msg = "Data Sekolah telah dihapus";
                       }
                       $doquery = db_query_insert($query);
					   echo $query;
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('sekolah');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Sekolah gagal ditambah. '.$output['error'];
                           echo redirect('sekolah');
                           exit;
                       }
                    }
                    
                       if(isset($_SESSION[$sessionname]->warningtype)){
                           echo alertflash($_SESSION[$sessionname]->warningtype,$_SESSION[$sessionname]->warningheader,$_SESSION[$sessionname]->warningmessage);
                           unset($_SESSION[$sessionname]->warningtype);
                           unset($_SESSION[$sessionname]->warningheader);
                           unset($_SESSION[$sessionname]->warningmessage);
                       }
                ?>
                <div class="row">
                  <div class="col-md-4">
                    <button type="button" class="btn btn-block btn-primary" onclick="addcat()">Add New App Version</button><br>
                  </div>
                </div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >App Name</th>
                      <th >Package Name</th>
                      <th >App Version</th>
                      <th >App Build Number</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "select * from tbl_apps_version";
                    $data = db_query2list($query);
                    foreach($data as $val){
						echo "<tr>";
							echo "<td>".$val->app_name."</td>";
							echo "<td>".$val->package_name."</td>";
							echo "<td>".$val->app_version."</td>";
							echo "<td>".$val->app_build_number."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_app_version."','".$val->app_name."', '".$val->package_name."','".$val->app_version."', '".$val->app_build_number."')\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_app_version."','".$val->app_name."', '".$val->package_name."','".$val->app_version."', '".$val->app_build_number."')\">Delete</a></td>";
						echo "</tr>";
                    } 
                    ?>
                    </tbody>
                  </table>
                  </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
        <div class="modal modal-primary fade" id="modal-formmenu" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="" method="post" id="menuform">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Category</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="id_app_version" value="" id="id_app_version">
                    <div class="form-group">
                      <label for="app_name">App Name</label>
                      <input type="text" class="form-control" name="app_name" id="app_name" placeholder="App Name" required="required">
                    </div>
                    <div class="form-group">
                      <label for="app_name">Package Name	</label>
                      <input type="text" class="form-control" name="package_name" id="package_name" placeholder="Package Name" required="required">
                    </div>
                    <div class="form-group">
                      <label for="app_name">App Version	</label>
                      <input type="text" class="form-control" name="app_version" id="app_version" placeholder="App Version" required="required">
                    </div>
                    <div class="form-group">
                      <label for="app_name">App Build Number	</label>
                      <input type="text" class="form-control" name="app_build_number" id="app_build_number" placeholder="App Build Number" required="required">
                    </div>
                   </div>
				  </div>
                  <!-- /.box-body -->
              </div>
			  
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="submitMenu" value="Save changes">
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      <!-- /.row -->
    </section>
<script>
    //$('.select2').select2();
    function addcat(){
        if($("#typeMenu option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeMenu").append(o);
        }
        $('#modal-formmenu').removeClass('modal-success').addClass('modal-primary');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Add Sekolah');
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
    
    function editData(id_app_version,app_name, package_name, app_version, app_build_number){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Sekolah');
        $('#id_app_version').val(id_app_version);
        $('#app_name').val(app_name);
        $('#package_name').val(package_name);
        $('#app_version').val(app_version);
        $('#app_build_number').val(app_build_number);
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(id_app_version,app_name, package_name, app_version, app_build_number){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Sekolah');
        $('#id_app_version').val(id_app_version);
        $('#app_name').val(app_name);
		$('#package_name').val(package_name);
        $('#app_version').val(app_version);
        $('#app_build_number').val(app_build_number);
        $('#submitMenu').val('Delete');
        $('#modal-formmenu').modal();
    }
	
	$('#example1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
</script>