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
              <h3 class="box-title"><i class="fa fa-edit"></i> Mata Pelajaran</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
                       if($_POST['id_mata_pelajaran']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_mata_pelajaran (id_sekolah, nama_mata_pelajaran) values(
                            '".$_POST['id_sekolah']."',
                            '".$_POST['nama_mata_pelajaran']."'
                           )";
						   
                           $msg = "Data Menu telah ditambahkan";
                       }elseif($_POST['id_mata_pelajaran']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_mata_pelajaran set 
                            nama_mata_pelajaran='".$_POST['nama_mata_pelajaran']."',
                            id_sekolah='".$_POST['id_sekolah']."'
                           where id_mata_pelajaran='".$_POST['id_mata_pelajaran']."'
                           ";
						   
                           $msg = "Data Mata Pelajaran telah diubah";
                       }elseif($_POST['id_mata_pelajaran']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_mata_pelajaran where id_mata_pelajaran='".$_POST['id_mata_pelajaran']."'
                           ";
						   
                           $msg = "Data Mata Pelajaran telah dihapus";
                       }
                       $doquery = db_query_insert($query);
					   
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('mata-pelajaran');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Mata Pelajaran gagal ditambah. '.$output['error'];
                           echo redirect('mata-pelajaran');
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
					<button type="button" class="btn btn-block btn-primary" onclick="addcat()">Add New Mata Pelajaran</button><br>
				  </div>
				</div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >No</th>
                      <th >Nama Mata Pelajaran</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "WHERE a.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					}
                    $query = "select * from tbl_mata_pelajaran a $kondisi";
                    $data = db_query2list($query);
					$no = 0;
                    foreach($data as $val){
						$no++;
						echo "<tr>";
							echo "<td>".$no."</td>";
							echo "<td>".$val->nama_mata_pelajaran."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_mata_pelajaran."','".$val->nama_mata_pelajaran."', '".$val->id_sekolah."')\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_mata_pelajaran."','".$val->nama_mata_pelajaran."', '".$val->id_sekolah."')\">Delete</a></td>";
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
                    <input type="hidden" name="id_mata_pelajaran" value="" id="id_mata_pelajaran">
					<?php if ($_SESSION[$sessionname]->roles == 'su') {?>
                    <div class="form-group">
                      <label for="jam_selesai">Sekolah</label>
                      <select class="form-control" style="width: 100%;" name="id_sekolah" id="id_sekolah" required >
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
					<?php } else { ?>
					  <input type="hidden" name="id_sekolah" value="<?php echo $_SESSION[$sessionname]->id_sekolah?>" id="id_sekolah">
					<?php } ?>
                    <div class="form-group">
                      <label for="nama_mata_pelajaran">Nama Mata Pelajaran</label>
                      <input type="text" class="form-control" name="nama_mata_pelajaran" id="nama_mata_pelajaran" placeholder="Nama Mata Pelajaran" required="required">
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
        $('#id_mata_pelajaran').val('');
        $('#modalTitle').text('Add Mata Pelajaran');
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
    
    function editData(id_mata_pelajaran,nama_mata_pelajaran, id_sekolah){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Mata Pelajaran');
        $('#id_mata_pelajaran').val(id_mata_pelajaran);
        $('#nama_mata_pelajaran').val(nama_mata_pelajaran);
        $('#id_sekolah').val(id_sekolah);
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(id_mata_pelajaran,nama_mata_pelajaran, id_sekolah){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Mata Pelajaran');
        $('#id_mata_pelajaran').val(id_mata_pelajaran);
        $('#nama_mata_pelajaran').val(nama_mata_pelajaran);
        $('#id_sekolah').val(id_sekolah);
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