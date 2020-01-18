<?php
	if(!empty($_POST['submit'])){
	   if($_POST['id_user']=='' && $_POST['submit']=="Save changes"){
		   if($_SESSION[$sessionname]->roles =='su' ){
			   
			$password = $_POST['username']."123";
			$query = "insert into tbl_person (fullname,id_kelas, id_sekolah) values(
			  '".$_POST['fullname']."',
			  '".$_POST['id_kelas']."',
			  '".$_POST['id_sekolah']."'
			  )";
			db_query_insert($query);
			$id_person = last_insert_id();
			$query = "insert into tbl_user (username,password, level, status, id_person) values(
			  '".$_POST['username']."',
			  (MD5(SHA1('".$password."'))),
			  '".$_POST['level']."',
			  '".$_POST['status']."',
			  '".$id_person."'
			  )";
			  $msg = "Data User telah ditambah";
		   }elseif($_POST['id_user']<>'' && $_POST['submit']=="Save changes"){
				$query = "
				UPDATE tbl_user
				SET
					username = '".$_POST['username']."',
					roles = '".$_POST['roles']."',
					id_cluster = '".$_POST['id_cluster']."'
				WHERE id_user = '".$_POST['id_user']."'
				";
				$msg = "Data User telah diubah";
		   }elseif($_POST['id_user']<>'' && $_POST['submit']=="Delete"){
			   $query = "delete from tbl_user where id_user='".$_POST['id_user']."'
			   ";
			   $msg = "Data User telah dihapus";
		   }
		   $doquery = db_query_insert($query);
		   
		   if($doquery['result']==TRUE){
			   //echo alertflash('success','Sukses','Data Category telah ditambahkan');
			   $_SESSION[$sessionname]->warningtype = 'success';
			   $_SESSION[$sessionname]->warningheader = 'Sukses';
			   $_SESSION[$sessionname]->warningmessage = $msg;
			   echo redirect('admin/adminuser');
			   exit;
		   }else{
			   //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
			   $_SESSION[$sessionname]->warningtype = 'danger';
			   $_SESSION[$sessionname]->warningheader = 'Gagal';
			   $_SESSION[$sessionname]->warningmessage = 'Data User gagal ditambah. '.$output['error'];
			   echo redirect('admin/adminuser');
			   exit;
		   }
		}
	}
	
	if(isset($_SESSION[$sessionname]->warningtype)){
	   echo alertflash($_SESSION[$sessionname]->warningtype,$_SESSION[$sessionname]->warningheader,$_SESSION[$sessionname]->warningmessage);
	   unset($_SESSION[$sessionname]->warningtype);
	   unset($_SESSION[$sessionname]->warningheader);
	   unset($_SESSION[$sessionname]->warningmessage);
	}
?>
	<section class="content">
	  <div class="row">
		<div class="col-xs-12">
		  <div class="box">
			<div class="box-header">
			  <h3 class="box-title"><i class="fa fa-edit"></i> User Administration</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				
				<div class="row">
				  <div class="col-md-4">
					<button type="button" class="btn btn-block btn-primary" onclick="adduser('admin')">Add User</button><br>
				  </div>
				</div>
				<div class="row">
				 <div class="col-md-12" style="overflow-x:auto">
				  <table id="example1" class="table table-bordered">
					<thead>
					<tr>
					  <th width="3%">ID User</th>
					  <th width="10%">Username</th>
					  <th width="10%">Level</th>
					  <th>Full Name</th>
					  <th>User Status</th>
					  <th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
					
					$counter = 0;
					$data = db_query2list("SELECT * FROM tbl_user a LEFT JOIN tbl_person b ON (a.id_person = b.id_person) ");
					foreach($data as $val){
						$counter++;
						echo "<tr>";
						echo "  <td>".$counter."</td>";
						echo "  <td>".$val->username."</td>";
						echo "  <td>".$val->level."</td>";
						echo "  <td>".$val->fullname."</td>";
						echo "  <td>".$val->status."</td>";
						echo "  <td>
							<a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_user."','".$val->username."', '".$val->roles."', '".$val->id_cluster."')\">Edit</a>
							<a class='btn btn-danger btn-sm' onclick=\"deluser('".$val->id_user."','".$val->username."')\">delete</a>
						</td>";
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
      <div class="modal modal-primary fade" id="modal-formuser" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="adminuser" method="post" id="userform">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Category</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_user" value="" id="id_user">
				<div class="form-group">
				  <label for="namaUser">Fullname</label>
				  <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname" required>
				</div>
				<div class="form-group">
					<label for="namaUser">Sekolah</label>
					<select class="form-control" style="width: 100%;" name="id_sekolah" id="id_sekolah" required onchange="chooseKelasBySekolah();">
						<option value="" >---Sekolah---</option>
						<?php 
							$query = "
								SELECT * 
								FROM tbl_sekolah
							";
							$data = db_query2list($query);
							foreach($data as $result){
						?>
								<option value="<?php echo $result->id_sekolah?>" ><?php echo $result->nama_sekolah?></option>
						<?php
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="namaUser">Kelas</label>
					<select class="form-control" style="width: 100%;" name="id_kelas" id="id_kelas"  >
						<option value="" >---Kelas---</option>
					</select>
				</div>
				<div class="form-group">
				  <label for="username">Username (Nomor Induk)</label>
				  <input type="text" class="form-control" name="username" id="username" placeholder="username" required>
				</div>
				<div class="form-group">
				  <label for="level">Level</label>
				  <select class="form-control" style="width: 100%;" name="level" id="level" required>
					<option value="" >--Level--</option>
					<option value="admin" >Admin</option>
					<option value="murid" >Murid</option>
					<option value="kepala sekolah" >Kepala Sekolah</option>
				  </select>
				</div>
				<div class="form-group" id="bagian-cluster">
				  <label for="clustertbl_user" id="clustertbl_user">Status</label>
				  <select class="form-control" style="width: 100%;" name="status" id="status" required>
					<option value="" >---Status---</option>
					<option value="mobile" >Mobile</option>
					<option value="web" >Web</option>
				  </select>
				</div>
                <!-- /.box-body -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="submitUser" value="Save changes">
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      <!-- /.row -->
    </section>
<script>
    
	function chooseKelasBySekolah(x){
		var id_sekolah = $("#id_sekolah option:selected").val();
		$.ajax({
		  type:"GET",
		  url: "<?php echo url('page/admin/ajax/ajax-get-kelas-by-sekolah.php?id_sekolah=')?>"+id_sekolah,
		  dataType:'json',
		  error: function (request,status, error) {
			console.log(request);
		  },
		  //data:fdata
		}).done(function(data){
		  console.log(data);
		  var ftext = "<option value=''>---Kelas---</option>";
		  $.each( data.result, function( key, value ) {
			ftext += "<option value='" + value.id_kelas + "'>" + value.nama_kelas + "</option>";
		  });
		  $("#id_kelas").html(ftext);
		}).fail(function(data){
		  console.log(data);
		  //alert("terjadi kesalahan, silahkan refresh ulang");
		});
	}
	
	function editData(id_user, username, roles, id_cluster){
		
        $('#modal-formuser').removeClass('modal-success').addClass('modal-success');
        $('#modal-formuser').removeClass('modal-danger').addClass('modal-success');
        $('#userform')[0].reset();
        $('#id_user').val(id_user);
        $('#username').val(username);
        $('#roles').val(roles);
        $('#id_cluster').val(id_cluster);
        $('#modalTitle').text('Edit User');
        $('#submitMenu').val('Save changes');
        $('#modal-formuser').modal();
    }
    
    function adduser(jenisuser){
        
        $('#modal-formuser').removeClass('modal-success').addClass('modal-primary');
        $('#modal-formuser').removeClass('modal-danger').addClass('modal-primary');
        $('#userform')[0].reset();
        $('#id_user').val('');
        $('#modalTitle').text('Add User');
        $('#submitMenu').val('Save changes');
        $('#modal-formuser').modal();
    }
	
    function deluser(id_username,username){
        $('#modal-formuser').removeClass('modal-primary').addClass('modal-danger');
        $('#modal-formuser').removeClass('modal-success').addClass('modal-danger');
        $('#userform')[0].reset();
        $('#modalTitle').text('Delete User');
        $('#id_user').val(id_username);
        $('#namaUser').val(username);
        $('#submitUser').val('Delete');
        $('#modal-formuser').modal();
    }
    
</script>
<script>
	$('.table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
</script>