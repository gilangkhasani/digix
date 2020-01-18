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
              <h3 class="box-title"><i class="fa fa-edit"></i> Siswa</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
					if(!empty($_POST['submit_import'])){
						$eror		= false;
						$folder		= 'file/';
						$file_name = "";
						$file_import = $_FILES['file_import']['name'];
						$id_kelas_import = $_POST['id_kelas_import'];
						$id_sekolah_import = $_POST['id_sekolah_import'];
						if($file_import!=''){
							//Mulai memorises data
							$file_size	= $_FILES['file_import']['size'];
							//cari extensi file dengan menggunakan fungsi explode
							$explode	= explode('.',$_FILES['file_import']['name']);
							$extensi	= $explode[count($explode)-1];
							
							$tanggal = date('Ymd');
							$waktu = date('His');
							
							//ubah nama file
							$file_import	= "Import_file_id_sekolah_".$id_sekolah_import."_id_kelas_".$id_kelas_import."_".$tanggal."_".$waktu.".".$extensi;
							$file_loc = $folder.$file_import;

							//check apakah type file sudah sesuai
							$pesan='';
							
							//check ukuran file apakah sudah sesuai

							if($eror == true){
								echo '<div class="alert alert-error">'.$pesan.'</div>';
							}
							else{
								//mulai memproses upload file
								if(move_uploaded_file($_FILES['file_import']['tmp_name'],$folder.$file_import)){
									$msg = 'Import File Berhasil ditambahkan';
									$_SESSION[$sessionname]->warningtype = 'success';
									$_SESSION[$sessionname]->warningheader = 'Sukses';
									$_SESSION[$sessionname]->warningmessage = $msg;
									if (($getfile = fopen($folder.$file_import, "r")) !== FALSE && ! feof($getfile)) {
										
										$n = 0;
										while (($data = fgetcsv($getfile, 1000, detectDelimiter($folder.$file_import))) !== FALSE) {
											if($n > 5 && $data[1] != ''){
												$nama_siswa = $data[1];
												$jenis_kelamin = $data[2];
												$nomor_induk = $data[3];
												$tempat_lahir = $data[4];
												$tanggal_lahir = $data[5];
												
												$password = $nomor_induk."123";
												$query = 'insert into tbl_person (fullname,id_kelas, id_sekolah, nisn, jenis_kelamin, tempat_lahir, tanggal_lahir) values(
												  "'.$nama_siswa.'",
												  "'.$id_kelas_import.'",
												  "'.$id_sekolah_import.'",
												  "'.$nomor_induk.'",
												  "'.$jenis_kelamin.'",
												  UPPER("'.$tempat_lahir.'"),
												  "'.$tanggal_lahir.'"
												  )';
												db_query_insert($query);
												$id_person = last_insert_id();
												$query = "insert into tbl_user (username,password, level, status, id_person) values(
												  '".$nomor_induk."',
												  (MD5(SHA1('".$password."'))),
												  'murid',
												  'mobile',
												  '".$id_person."'
												  )";
												db_query_insert($query);
											}
											$n++;
										} 
									}
									
									echo redirect('siswa');
									exit;
								}
							}
						}else{
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Import File gagal ditambah';
                           echo redirect('siswa');
                           exit;
						}
					}
					
                    if(!empty($_POST['submit'])){
                       if($_POST['id_person']=='' && $_POST['submit']=="Save changes"){
						   $password = $_POST['nomor_induk']."123";
							$query = 'insert into tbl_person (fullname,id_kelas, id_sekolah, nisn, jenis_kelamin, tempat_lahir, tanggal_lahir) values(
							  "'.$_POST['fullname'].'",
							  "'.$_POST['id_kelas'].'",
							  "'.$_POST['id_sekolah'].'",
							  "'.$_POST['nomor_induk'].'",
							  "'.$_POST['jenis_kelamin'].'",
							  UPPER("'.$_POST['tempat_lahir'].'"),
							  "'.$_POST['tanggal_lahir'].'"
							  )';
							db_query_insert($query);
							$id_person = last_insert_id();
							$query = "insert into tbl_user (username,password, level, status, id_person) values(
							  '".$_POST['nomor_induk']."',
							  (MD5(SHA1('".$password."'))),
							  'murid',
							  'mobile',
							  '".$id_person."'
							  )";
                           $msg = "Data Siswa telah ditambahkan";
                       }elseif($_POST['id_person']<>'' && $_POST['submit']=="Save changes"){
							$password = $_POST['nomor_induk']."123";
							$query = "
							UPDATE tbl_user
							SET
								username = '".$_POST['nomor_induk']."',
								password = (MD5(SHA1('".$password."')))
							WHERE id_person = '".$_POST['id_person']."'
							";
							db_query_insert($query);
                           $query = "update tbl_person set 
                            fullname='".$_POST['fullname']."',
                            id_kelas='".$_POST['id_kelas']."',
                            id_sekolah='".$_POST['id_sekolah']."',
                            nisn='".$_POST['nomor_induk']."',
                            jenis_kelamin='".$_POST['jenis_kelamin']."',
                            tempat_lahir=UPPER('".$_POST['tempat_lahir']."'),
                            tanggal_lahir='".$_POST['tanggal_lahir']."'
                           where id_person='".$_POST['id_person']."'
                           ";
						   
                           $msg = "Data Siswa telah diubah";
                       }elseif($_POST['id_person']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_user where id_person='".$_POST['id_person']."'
                           ";
						   db_query_insert($query);
						   $query = "delete from tbl_person where id_person='".$_POST['id_person']."'
                           ";
						   
                           $msg = "Data Siswa telah dihapus";
                       }
                       $doquery = db_query_insert($query);
					   
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('siswa');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Category gagal ditambah. '.$output['error'];
                           echo redirect('siswa');
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
                    <button type="button" class="btn btn-block btn-primary" onclick="addData()">Add New Siswa</button>
                    <button type="button" class="btn btn-block btn-warning" onclick="importData()">Import Data Siswa</button><br>
                  </div>
                </div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >No</th>
                      <th >Nomor Induk</th>
                      <th >Nama Lengkap</th>
                      <th >Jenis Kelamin</th>
                      <th >Tempat Lahir</th>
                      <th >Tanggal Lahir</th>
                      <th >Nama Kelas</th>
                      <th >Nama Sekolah</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "WHERE c.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."' AND d.level NOT IN ('admin', 'su')";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					}
                    $query = "select a.*, b.*, c.*, d.username as nomor_induk from tbl_person a join tbl_kelas b on (a.id_kelas = b.id_kelas) join tbl_sekolah c on(a.id_sekolah = c.id_sekolah) join tbl_user d on (a.id_person = d.id_person) $kondisi";
                    $data = db_query2list($query);
					$no = 0;
                    foreach($data as $val){
						$no++;
						echo "<tr>";
							echo "<td>".$no."</td>";
							echo "<td>".$val->nomor_induk."</td>";
							echo "<td>".$val->fullname."</td>";
							echo "<td>".$val->jenis_kelamin."</td>";
							echo "<td>".$val->tempat_lahir."</td>";
							echo "<td>".$val->tanggal_lahir."</td>";
							echo "<td>".$val->nama_kelas."</td>";
							echo "<td>".$val->nama_sekolah."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_person."','".$val->fullname."', '".$val->jam_masuk."','".$val->jam_selesai."','".$val->id_sekolah."', '".$val->id_kelas."', '".$val->nomor_induk."' , '".$val->jenis_kelamin."', '".$val->tempat_lahir."', '".$val->tanggal_lahir."')\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_person."','".$val->fullname."', '".$val->jam_masuk."','".$val->jam_selesai."','".$val->id_sekolah."', '".$val->id_kelas."','".$val->nomor_induk."', '".$val->jenis_kelamin."', '".$val->tempat_lahir."', '".$val->tanggal_lahir."')\">Delete</a></td>";
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
        <div class="modal modal-primary fade" id="modal-formSiswa" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="" method="post" id="Siswaform">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Category</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="id_person" value="" id="id_person">
					<?php if ($_SESSION[$sessionname]->roles == 'su') {?>
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
							<select class="form-control" style="width: 100%;" name="id_kelas" id="id_kelas" required >
								<option value="" >---Kelas---</option>
							</select>
						</div>
					<?php } else { ?>
						<div class="form-group">
							<label for="namaUser">Kelas</label>
							<input type="hidden" name="id_sekolah" value="<?php echo $_SESSION[$sessionname]->id_sekolah?>" id="id_sekolah">
							<select class="form-control" style="width: 100%;" name="id_kelas" id="id_kelas" required >
								<option value="" >---Kelas---</option>
								<?php 
									$query = "
										SELECT * 
										FROM tbl_kelas
										WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'
									";
									$data = db_query2list($query);
									foreach($data as $result){
								?>
										<option value="<?php echo $result->id_kelas?>" ><?php echo $result->nama_kelas?></option>
								<?php
									}
								?>
							</select>
						</div>
					<?php } ?>
                    <div class="form-group">
                      <label for="nomor_induk">Nomor Induk</label>
                      <input type="text" class="form-control" name="nomor_induk" id="nomor_induk" placeholder="Nomor Induk" required="required">
                    </div>
                    <div class="form-group">
                      <label for="fullname">Nama Lengkap</label>
                      <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Nama Kelas" required="required">
                    </div>
                    <div class="form-group">
                      <label for="jenis_kelamin">Jenis Kelamin</label>
                      <select class="form-control" style="width: 100%;" name="jenis_kelamin" id="jenis_kelamin" required >
						<option value="" >---Jenis Kelamin---</option>
						<option value="L" >Laki-laki</option>
						<option value="P" >Perempuan</option>
					</select>
                    </div>
                    <div class="form-group">
                      <label for="tempat_lahir">Tempat Lahir</label>
                      <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" required="required">
                    </div>
                    <div class="form-group">
                      <label for="tempat_lahir">Tanggal Lahir (YYYY-MM-DD)</label>
                      <input type="text" class="form-control datepicker" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" required="required">
                    </div>
                   </div>
				  </div>
                  <!-- /.box-body -->
              </div>
			  
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="submitSiswa" value="Save changes">
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		<div class="modal modal-default fade" id="modal-import-form-siswa" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="" method="post" id="FormImportSiswa" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Import Siswa</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
					<div class="form-group">
						<a href="https://itbsjabartsel.com/digix/file/Rekap Siswa Kelas X SMA XXX.csv">Contoh File</a>
					</div>
					<?php if ($_SESSION[$sessionname]->roles == 'su') {?>
						<div class="form-group">
							<label for="namaUser">Sekolah</label>
							<select class="form-control" style="width: 100%;" name="id_sekolah_import" id="id_sekolah_import" required onchange="chooseKelasBySekolah();">
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
							<select class="form-control" style="width: 100%;" name="id_kelas_import" id="id_kelas_import" required >
								<option value="" >---Kelas---</option>
							</select>
						</div>
					<?php } else { ?>
						<div class="form-group">
							<label for="namaUser">Kelas</label>
							<input type="hidden" name="id_sekolah_import" value="<?php echo $_SESSION[$sessionname]->id_sekolah?>" id="id_sekolah">
							<select class="form-control" style="width: 100%;" name="id_kelas_import" id="id_kelas_import" required >
								<option value="" >---Kelas---</option>
								<?php 
									$query = "
										SELECT * 
										FROM tbl_kelas
										WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'
									";
									$data = db_query2list($query);
									foreach($data as $result){
								?>
										<option value="<?php echo $result->id_kelas?>" ><?php echo $result->nama_kelas?></option>
								<?php
									}
								?>
							</select>
						</div>
					<?php } ?>
					<div class="form-group">
                      <label for="judul_berita">File</label>
                      <input type="file" class="form-control" name="file_import" id="file_import" />
					  <p style="color:red">* Format file harus CSV</p>
                    </div>
                   </div>
				  </div>
                  <!-- /.box-body -->
              </div>
			  
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit_import" id="submitImport" value="Save changes">
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
	
	function chooseKelasBySekolah(x){
		var id_sekolah = $("#id_sekolah option:selected").val();
		$.ajax({
		  type:"GET",
		  url: "<?php echo url('page/main/ajax/ajax-get-kelas-by-sekolah.php?id_sekolah=')?>"+id_sekolah,
		  dataType:'json',
		  error: function (request,status, error) {
			console.log(request);
		  },
		  //data:fdata
		}).done(function(data){
		  console.log(data);
		  var ftext = "<option value=''>---Kelas---</option>";
		  $.each( data.result, function( key, value ) {
			//var hasName = (value.id_tdc === id_cluster) ? 'selected' :'';
			ftext += "<option value='" + value.id_kelas + "'>" + value.nama_kelas + "</option>";
		  });
		  $("#id_kelas").html(ftext);
		}).fail(function(data){
		  console.log(data);
		  //alert("terjadi kesalahan, silahkan refresh ulang");
		});
	}
	
    function importData(){
		$('#modal-import-form-siswa').modal();
	}
	
    function addData(){
        if($("#typeSiswa option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeSiswa").append(o);
        }
        $('#modal-formSiswa').removeClass('modal-success').addClass('modal-primary');
		$('#id_person').val('');
        $('#Siswaform')[0].reset();
        $('#modalTitle').text('Add Sekolah');
        $('#submitSiswa').val('Save changes');
        $('#modal-formSiswa').modal();
    }
    
    function editData(id_person,fullname, jam_masuk, jam_selesai, id_sekolah, id_kelas, nomor_induk, jenis_kelamin, tempat_lahir, tanggal_lahir){
        $('#modal-formSiswa').removeClass('modal-primary').addClass('modal-success');
        $('#Siswaform')[0].reset();
        $('#modalTitle').text('Edit Sekolah');
        $('#id_person').val(id_person);
        $('#fullname').val(fullname);
        $('#jam_masuk').val(jam_masuk);
        $('#jam_selesai').val(jam_selesai);
        $('#nomor_induk').val(nomor_induk);
        $('#jenis_kelamin').val(jenis_kelamin);
        $('#tempat_lahir').val(tempat_lahir);
        $('#tanggal_lahir').val(tanggal_lahir);
        $('#id_kelas').val(id_kelas);
        $('#submitSiswa').val('Save changes');
        $('#modal-formSiswa').modal();
    }
	
    function deleteData(id_person,fullname, jam_masuk, jam_selesai, id_sekolah, id_kelas, nomor_induk, jenis_kelamin, tempat_lahir, tanggal_lahir){
        $('#modal-formSiswa').removeClass('modal-primary').addClass('modal-danger');
        $('#Siswaform')[0].reset();
        $('#modalTitle').text('Delete Sekolah');
        $('#id_person').val(id_person);
        $('#fullname').val(fullname);
		$('#jam_masuk').val(jam_masuk);
        $('#jam_selesai').val(jam_selesai);
        $('#id_sekolah').val(id_sekolah);
        $('#id_kelas').val(id_kelas);
		$('#nomor_induk').val(nomor_induk);
		$('#jenis_kelamin').val(jenis_kelamin);
        $('#tempat_lahir').val(tempat_lahir);
        $('#tanggal_lahir').val(tanggal_lahir);
        $('#submitSiswa').val('Delete');
        $('#modal-formSiswa').modal();
    }
	
	$('#example1').DataTable({
		 "order": [[ 6, "asc" ]],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
</script>