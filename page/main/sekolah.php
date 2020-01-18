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
              <h3 class="box-title"><i class="fa fa-edit"></i> Sekolah</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
                       if($_POST['id_sekolah']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_sekolah (nama_sekolah, alamat_sekolah, telepon_sekolah, provinsi_sekolah) values(
                            '".$_POST['nama_sekolah']."',
                            '".$_POST['alamat_sekolah']."',
                            '".$_POST['telepon_sekolah']."',
                            '".$_POST['provinsi_sekolah']."'
                           )";
						   
                           $msg = "Data Menu telah ditambahkan";
                       }elseif($_POST['id_sekolah']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_sekolah set 
                            nama_sekolah='".$_POST['nama_sekolah']."',
                            alamat_sekolah='".$_POST['alamat_sekolah']."',
                            telepon_sekolah='".$_POST['telepon_sekolah']."',
                            provinsi_sekolah='".$_POST['provinsi_sekolah']."'
                           where id_sekolah='".$_POST['id_sekolah']."'
                           ";
						   
                           $msg = "Data Sekolah telah diubah";
                       }elseif($_POST['id_sekolah']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_sekolah where id_sekolah='".$_POST['id_sekolah']."'
                           ";
						   
                           $msg = "Data Sekolah telah dihapus";
                       }
                       $doquery = db_query_insert($query);
					   
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
				<?php
					$kondisi = "WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
				?>
					<div class="row">
					  <div class="col-md-4">
						<button type="button" class="btn btn-block btn-primary" onclick="addcat()">Add New Sekolah</button><br>
					  </div>
					</div>
				<?php } ?>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >Nama Sekolah</th>
                      <th >Alamat Sekolah</th>
                      <th >Nomor Telepon</th>
                      <th >Provinsi</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "select * from tbl_sekolah $kondisi";
                    $data = db_query2list($query);
                    foreach($data as $val){
						echo "<tr>";
							echo "<td>".$val->nama_sekolah."</td>";
							echo "<td>".$val->alamat_sekolah."</td>";
							echo "<td>".$val->telepon_sekolah."</td>";
							echo "<td>".$val->provinsi_sekolah."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_sekolah."','".$val->nama_sekolah."','".$val->alamat_sekolah."','".$val->telepon_sekolah."','".$val->provinsi_sekolah."')\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_sekolah."','".$val->nama_sekolah."','".$val->alamat_sekolah."','".$val->telepon_sekolah."','".$val->provinsi_sekolah."')\">Delete</a></td>";
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
                    <input type="hidden" name="id_sekolah" value="" id="id_sekolah">
                    <div class="form-group">
                      <label for="nama_sekolah">Nama Sekolah</label>
                      <input type="text" class="form-control" name="nama_sekolah" id="nama_sekolah" placeholder="Nama Sekolah" required="required">
                    </div>
                    <div class="form-group">
                      <label for="alamat_sekolah">Alamat Sekolah</label>
                      <input type="text" class="form-control" name="alamat_sekolah" id="alamat_sekolah" placeholder="Alamat Sekolah" required="required">
                    </div>
                    <div class="form-group">
                      <label for="telepon_sekolah">Telepon Sekolah</label>
                      <input type="number" class="form-control" name="telepon_sekolah" id="telepon_sekolah" placeholder="Telepon Sekolah" required="required">
                    </div>
                    <div class="form-group">
                      <label for="provinsi_sekolah">Provinsi Sekolah</label>
                      <input type="text" class="form-control" name="provinsi_sekolah" id="provinsi_sekolah" placeholder="Provinsi Sekolah" required="required">
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
		$('#id_sekolah').val('');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Add Sekolah');
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
    
    function editData(id_sekolah,nama_sekolah, alamat_sekolah, telepon_sekolah, provinsi_sekolah){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Sekolah');
        $('#id_sekolah').val(id_sekolah);
        $('#nama_sekolah').val(nama_sekolah);
        $('#alamat_sekolah').val(alamat_sekolah);
        $('#telepon_sekolah').val(telepon_sekolah);
        $('#provinsi_sekolah').val(provinsi_sekolah);
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(id_sekolah,nama_sekolah, alamat_sekolah, telepon_sekolah, provinsi_sekolah){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Sekolah');
        $('#id_sekolah').val(id_sekolah);
        $('#nama_sekolah').val(nama_sekolah);
		$('#alamat_sekolah').val(alamat_sekolah);
        $('#telepon_sekolah').val(telepon_sekolah);
        $('#provinsi_sekolah').val(provinsi_sekolah);
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