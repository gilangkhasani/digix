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
		
		.modal-content {
		  
		  overflow:auto;
		}
	</style>

	<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-edit"></i> Kelas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
                       if($_POST['id_kelas']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_kelas (nama_kelas, id_sekolah, jam_masuk, jam_selesai, id_polygon) values(
                            '".$_POST['nama_kelas']."', '".$_POST['id_sekolah']."', '".$_POST['jam_masuk']."', '".$_POST['jam_selesai']."' , '".$_POST['id_polygon']."' 
                           )";
						   
                           $msg = "Data Kelas telah ditambahkan";
						   $doquery = db_query_insert($query);
                       }elseif($_POST['id_kelas']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_kelas set 
                            id_polygon='".$_POST['id_polygon']."',
                            nama_kelas='".$_POST['nama_kelas']."',
                            id_sekolah='".$_POST['id_sekolah']."',
                            jam_masuk='".$_POST['jam_masuk']."',
                            jam_selesai='".$_POST['jam_selesai']."'
                           where id_kelas='".$_POST['id_kelas']."'
                           ";
						   
                           $msg = "Data Kelas telah diubah";
						   $doquery = db_query_insert($query);
						   
                       }elseif($_POST['id_kelas']<>'' && $_POST['submit']=="Delete"){
						   $query = "delete from tbl_kelas_kordinat where id_kelas='".$_POST['id_kelas']."'
                           ";
						   $doquery = db_query_insert($query);
						   
                           $query = "delete from tbl_kelas where id_kelas='".$_POST['id_kelas']."'
                           ";
						   
                           $msg = "Data Kelas telah dihapus";
						   $doquery = db_query_insert($query);
                       }
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('kelas');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Sekolah gagal ditambah. '.$output['error'];
                           echo redirect('kelas');
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
                    <button type="button" class="btn btn-block btn-primary" onclick="addData()">Add New Kelas</button><br>
                  </div>
                </div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >No</th>
                      <th >Nama Kelas</th>
                      <th >Jam Masuk</th>
                      <th >Jam Selesai</th>
                      <th >Nama Sekolah</th>
                      <th >Group Koordinat</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "WHERE a.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					}
                    $query = "select * from tbl_kelas a join tbl_sekolah b on (a.id_sekolah = b.id_sekolah) join tbl_polygon c on (a.id_polygon = c.id_polygon) $kondisi";
                    $data = db_query2list($query);
					$no = 0;
                    foreach($data as $val){
						$no++;
						echo "<tr>";
							echo "<td>".$no."</td>";
							echo "<td>".$val->nama_kelas."</td>";
							echo "<td>".$val->jam_masuk."</td>";
							echo "<td>".$val->jam_selesai."</td>";
							echo "<td>".$val->nama_sekolah."</td>";
							echo "<td>".$val->remark_polygon."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_kelas."','".$val->nama_kelas."', '".$val->jam_masuk."','".$val->jam_selesai."','".$val->id_sekolah."', '".$val->id_polygon."' )\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_kelas."','".$val->nama_kelas."', '".$val->jam_masuk."','".$val->jam_selesai."','".$val->id_sekolah."', '".$val->id_polygon."')\">Delete</a></td>";
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
                    <input type="hidden" name="id_kelas" value="" id="id_kelas">
					<?php if ($_SESSION[$sessionname]->roles == 'su') {?>
                    <div class="form-group">
                      <label for="jam_selesai">Sekolah</label>
                      <select class="form-control" style="width: 100%;" name="id_sekolah" id="id_sekolah" required onchange="getKoordinat()" >
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
                      <label for="jam_selesai">Koordinat</label>
                      <select class="form-control" style="width: 100%;" name="id_polygon" id="id_polygon" required >
						
					   </select>
                    </div>
					<?php } else { ?>
					<?php 
						$query = "
							SELECT * 
							FROM tbl_sekolah
							WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'
						";
						$row = db_query($query);
					?>
					<div class="form-group">
                      <label for="jam_selesai">Sekolah</label>
					  <input type="hidden" name="id_sekolah" value="<?php echo $row->id_sekolah?>" id="id_sekolah">
					  <input type="text" class="form-control" name="nama_sekolah" id="nama_sekolah" placeholder="Nama Sekolah" value="<?php echo $row->nama_sekolah?>" required="required" disabled >
                    </div>
					<div class="form-group">
                      <label for="jam_selesai">Koordinat</label>
                      <select class="form-control" style="width: 100%;" name="id_polygon" id="id_polygon" required >
							<option value="">--Koordinat--</option>
							<?php 
							$query = "
								SELECT * 
								FROM tbl_polygon
								WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'
							";
							$data = db_query2list($query);
							foreach($data as $result){
						?>
								<option value="<?php echo $result->id_polygon?>" ><?php echo $result->remark_polygon?></option>
						<?php
							}
						?>
					   </select>
                    </div>
					<?php } ?>
                    <div class="form-group">
                      <label for="nama_kelas">Nama Kelas</label>
                      <input type="text" class="form-control" name="nama_kelas" id="nama_kelas" placeholder="Nama Kelas" required="required">
                    </div>
                    <div class="form-group">
                      <label for="jam_masuk">Jam Masuk</label>
                      <input type="text" class="form-control timepicker" name="jam_masuk" id="jam_masuk" placeholder="Jam Masuk" required="required">
                    </div>
                    <div class="form-group">
                      <label for="jam_selesai">Jam Selesai</label>
                      <input type="text" class="form-control timepicker" name="jam_selesai" id="jam_selesai" placeholder="Jam Selesai" required="required">
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
<script type="text/javascript">

	
	$(document).ready(function() {
		$('#example1').DataTable({
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'print'
			]
		});
	});
	
	function getKoordinat(x){
		var id_sekolah = $("#id_sekolah option:selected").val();
		$.ajax({
		  type:"GET",
		  url: "<?php echo url('page/admin/ajax/ajax-get-koordinat-list-by-sekolah.php?id_sekolah=')?>"+id_sekolah,
		  dataType:'json',
		  error: function (request,status, error) {
			console.log(request);
		  },
		  //data:fdata
		}).done(function(data){
		  console.log(data);
		  var ftext = "<option value=''>---Koordinat---</option>";
		  $.each( data.result, function( key, value ) {
			//var hasName = (value.id_tdc === id_cluster) ? 'selected' :'';
			ftext += "<option value='" + value.id_polygon + "'>" + value.remark_polygon + "</option>";
		  });
		  $("#id_kelas").html(ftext);
		}).fail(function(data){
		  console.log(data);
		  //alert("terjadi kesalahan, silahkan refresh ulang");
		});
	}

    function addData(){
        if($("#typeMenu option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeMenu").append(o);
        }
        $('#modal-formmenu').removeClass('modal-success').addClass('modal-primary');
		$('#id_kelas').val('');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Add Sekolah');
        $('#submitMenu').val('Save changes');
		
        $('#modal-formmenu').modal();
    }
	
    function editData(id_kelas,nama_kelas, jam_masuk, jam_selesai, id_sekolah, id_polygon){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Sekolah');
        $('#id_kelas').val(id_kelas);
        $('#nama_kelas').val(nama_kelas);
        $('#jam_masuk').val(jam_masuk);
        $('#jam_selesai').val(jam_selesai);
        $('#id_sekolah').val(id_sekolah);
        $('#id_polygon').val(id_polygon);
		
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(id_kelas,nama_kelas, jam_masuk, jam_selesai, id_sekolah, id_polygon){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Sekolah');
        $('#id_kelas').val(id_kelas);
        $('#nama_kelas').val(nama_kelas);
		$('#jam_masuk').val(jam_masuk);
        $('#jam_selesai').val(jam_selesai);
        $('#id_sekolah').val(id_sekolah);
        $('#id_polygon').val(id_polygon);
        $('#submitMenu').val('Delete');
        $('#modal-formmenu').modal();
    }
	
</script>