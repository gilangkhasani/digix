    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-edit"></i> Loader Page Administration</h3>
            </div>
            <!-- /.box-header -->
              <?php
                
              ?>
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
                       if($_POST['idLoader']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_menu_loader (nama_loader,session_loader,permission,filepage,title,created_by,created_date) values(
                            '".$_POST['namaLoader']."',
                            '".$_POST['sessionLoader']."',
                            '".$_POST['permission']."',
                            '".$_POST['pathLoader']."',
                            '".$_POST['titleLoader']."',
                            '".$_SESSION[$sessionname]->username."',
                            now()
                           )";
                           $msg = "Data User telah ditambahkan";
                       }elseif($_POST['idLoader']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_menu_loader set 
                            nama_loader='".$_POST['namaLoader']."',
                            session_loader='".$_POST['sessionLoader']."',
                            permission='".$_POST['permission']."',
                            filepage='".$_POST['pathLoader']."',
                            title='".$_POST['titleLoader']."'
                           where id_loader='".$_POST['idLoader']."'
                           ";
                           $msg = "Data Loader telah diubah";
                       }elseif($_POST['idLoader']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_menu_loader where id_loader='".$_POST['idLoader']."'
                           ";
                           $msg = "Data Loader telah dihapus";
                       }
                       $doquery = db_query_insert($query);
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('admin/adminloader');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Loader gagal ditambah. '.mysql_error();
                           echo redirect('admin/adminloader');
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
                    <button type="button" class="btn btn-block btn-primary" onclick="addloader()">Add Loader Page</button><br>
                  </div>
                </div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th width="3%">ID Loader</th>
                      <th>Nama Loader (url path)</th>
                      <th>Session check</th>
                      <th>Permission</th>
                      <th>path to File</th>
                      <th>Page Title</th>
                      <th>Create by</th>
                      <th>Create date</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "select * from tbl_menu_loader";
                    $data = db_query2list($query);
                    foreach($data as $val){
                        echo "<tr>";
                        echo "  <td>".$val->id_loader."</td>";
                        echo "  <td>".$val->nama_loader."</td>";
                        echo "  <td>".$val->session_loader."</td>";
                      //  echo "  <td>".$_SESSION[$sessionname]->{$val->session_loader}."</td>";
                        echo "  <td>".$val->permission."</td>";
                        echo "  <td>".$val->filepage."</td>";
                        echo "  <td>".$val->title."</td>";
                        echo "  <td>".$val->created_by."</td>";
                        echo "  <td>".$val->created_date."</td>";
                        echo "  <td><a class='btn btn-success btn-sm' onclick=\"editloader('".$val->id_loader."','".$val->nama_loader."','".$val->session_loader."','".$val->permission."','".$val->filepage."','".$val->title."')\">edit</a> | <a class='btn btn-danger btn-sm' onclick=\"delloader('".$val->id_loader."','".$val->nama_loader."','".$val->session_loader."','".$val->permission."','".$val->filepage."','".$val->title."')\">delete</a></td>";
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
        <div class="modal modal-primary fade" id="modal-formloader" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="adminloader" method="post" id="loaderform">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Loader</h4>
              </div>
              <div class="modal-body">
                    <input type="hidden" name="idLoader" value="" id="idLoader">
                    <div class="form-group">
                      <label for="namaLoader">Nama Loader</label>
                      <input type="text" class="form-control" name="namaLoader" id="namaLoader" placeholder="Nama Loader">
                    </div>
                    <div class="form-group">
                      <label for="sessionLoader">Session Name for Check</label>
                      <input type="text" class="form-control" name="sessionLoader" id="sessionLoader" placeholder="Session name for check">
                    </div>
                    <div class="form-group">
                      <label for="permission">Permission (user roles)</label>
                      <input type="text" class="form-control" name="permission" id="permission" placeholder="permission">
                    </div>
                    <div class="form-group">
                      <label for="pathLoader">File page path</label>
                      <input type="text" class="form-control" name="pathLoader" id="pathLoader" placeholder="File page path">
                    </div>
                    <div class="form-group">
                      <label for="titleLoader">Title page</label>
                      <input type="text" class="form-control" name="titleLoader" id="titleLoader" placeholder="Title Page">
                    </div>
                  <!-- /.box-body -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="submitLoader" value="Save changes">
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
	$('#example1').DataTable({
		"lengthMenu": [[25, 50, -1], [25, 50, "All"]]
	});
    function addloader(){
        $('#modal-formloader').removeClass('modal-success').addClass('modal-primary');
        $('#loaderform')[0].reset();
        $('#modalTitle').text('Add Loader');
        $('#submitMenu').val('Save changes');
        $('#modal-formloader').modal();
    }
    function editloader(idloader,namaloader,sessionloader,permission,pathloader,titleloader){
        $('#modal-formloader').removeClass('modal-primary').addClass('modal-success');
        $('#loaderform')[0].reset();
        $('#modalTitle').text('Edit Loader');
        $('#idLoader').val(idloader);
        $('#namaLoader').val(namaloader);
        $('#sessionLoader').val(sessionloader);
        $('#permission').val(permission);
        $('#pathLoader').val(pathloader);
        $('#titleLoader').val(titleloader);
        $('#submitLoader').val('Save changes');
        $('#modal-formloader').modal();
    }
    function delloader(idloader,namaloader,sessionloader,permission,pathloader,titleloader){
        $('#modal-formloader').removeClass('modal-primary').addClass('modal-danger');
        $('#loaderform')[0].reset();
        $('#modalTitle').text('Delete User');
        $('#idLoader').val(idloader);
        $('#namaLoader').val(namaloader);
        $('#sessionLoader').val(sessionloader);
        $('#permission').val(permission);
        $('#pathLoader').val(pathloader);
        $('#titleLoader').val(titleloader);
        $('#submitLoader').val('Delete');
        $('#modal-formloader').modal();
    }
</script>