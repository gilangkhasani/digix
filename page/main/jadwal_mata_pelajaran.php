	<?php
		$tahun = date('Y');
		(!empty($_GET['semester'])) ? $semester = $_GET['semester'] : $semester = 1;
		(!empty($_GET['tahun'])) ? $tahun = $_GET['tahun'] : $tahun = date('Y');
	?>
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
			  <br>
			  <br>
				<form method="GET" action="">
					<div class="form-group">
						<select name="semester" id="semester_form" class="form-control" style="display:inline; width:5%;">
							<option value="1" <?php echo $semester == 1 ? 'selected' : ''?>>1</option>
							<option value="2" <?php echo $semester == 2 ? 'selected' : ''?>>2</option>
						</select>
						<input type="text" name="tanggal" id="tahun" class="form-control" placeholder="Tahun" style="display:inline; width:5%;" value="<?php echo $tahun?>" />
						<button class="btn btn-primary btn-small" type="submit" style="display:inline;">Search</button>
					</div>
				</form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit']) || !empty($_POST['delete']) || !empty($_POST['update']) ){
                       if($_POST['id_jadwal_pelajaran']=='' && $_POST['submit']=="Save changes"){
						   $q = "
								SELECT *
								FROM tbl_jadwal_pelajaran a 
								WHERE a.jam_start = '".$_POST['jam_start']."'
								AND a.jam_selesai- '".$_POST['jam_selesai']."'
								AND a.id_kelas = ".$_POST['id_kelas']."
								AND a.id_hari = ".$_POST['id_hari']."
								AND a.id_sekolah = '".$_POST['id_sekolah']."'
								AND a.semester = '".$_POST['semester']."'
								AND a.tahun_ajaran = '".$_POST['tahun_ajaran']."'
						   ";
						   $count = db_num_rows($q);
						   if($count > 0){
							   $msg = "Data Jadwal Mata Pelajaran gagal ditambahkan karena Data tesebut sudah terinput di database"; 
						   } else {
							  $query = "insert into tbl_jadwal_pelajaran 
								(
									id_mata_pelajaran,
									jam,
									jam_start,
									jam_selesai,
									id_hari,
									id_kelas,
									id_sekolah,
									id_guru,
									semester,
									tahun_ajaran,
									created_date,
									created_by
								) 
							   values(
									'".$_POST['id_mata_pelajaran']."',
									'".$_POST['jam_start']."',
									'".$_POST['jam_start']."',
									'".$_POST['jam_selesai']."',
									'".$_POST['id_hari']."',
									'".$_POST['id_kelas']."',
									'".$_POST['id_sekolah']."',
									'".$_POST['id_guru']."',
									'".$_POST['semester']."',
									'".$_POST['tahun_ajaran']."',
									NOW(),
									'".$_SESSION[$sessionname]->username."'
							   )";
							   
							   $msg = "Data Jadwal Mata Pelajaran telah ditambahkan"; 
						   }
                           
                       }elseif( $_POST['update']=="Save changes"){
                           $query = "update tbl_jadwal_pelajaran set 
                            id_mata_pelajaran='".$_POST['id_mata_pelajaran']."',
                            jam='".$_POST['jam_start']."',
                            jam_start='".$_POST['jam_start']."',
                            jam_selesai='".$_POST['jam_selesai']."',
                            id_hari='".$_POST['id_hari']."',
                            id_kelas='".$_POST['id_kelas']."',
                            id_sekolah='".$_POST['id_sekolah']."',
                            id_guru='".$_POST['id_guru']."',
                            semester='".$_POST['semester']."',
                            tahun_ajaran='".$_POST['tahun_ajaran']."'
                           where 
						   jam_start='".$_POST['jam_start']."' and
                            jam_selesai='".$_POST['jam_selesai']."' and 
                            id_hari='".$_POST['id_hari']."' and 
                            id_kelas='".$_POST['id_kelas']."' and 
                            id_sekolah='".$_POST['id_sekolah']."' and 
                            semester='".$_POST['semester']."' and 
                            tahun_ajaran='".$_POST['tahun_ajaran']."'
                           ";
						   
                           $msg = "Data Mata Pelajaran telah diubah";
                       }elseif( $_POST['delete']=="delete"){
                           $query = "delete from tbl_jadwal_pelajaran where 
						   jam_start='".$_POST['jam_start']."' and
                            jam_selesai='".$_POST['jam_selesai']."' and 
                            id_hari='".$_POST['id_hari']."' and 
                            id_kelas='".$_POST['id_kelas']."' and 
                            id_sekolah='".$_POST['id_sekolah']."' and 
                            semester='".$_POST['semester']."' and 
                            tahun_ajaran='".$_POST['tahun_ajaran']."' 
                           ";
						   
                           $msg = "Data Mata Pelajaran telah dihapus";
                       }
					   
                       $doquery = db_query_insert($query);
					   
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('jadwal-mata-pelajaran');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Jadwal Mata Pelajaran gagal ditambah. '.$output['error'];
                           echo redirect('jadwal-mata-pelajaran');
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
					<button type="button" class="btn btn-block btn-primary" onclick="addcat()">Add New Jadwal Pelajaran</button><br>
				  </div>
				</div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
						<tr>
						  <th rowspan="2" style="text-align:center; vertical-align:middle;" >Kelas</th>
						  <th rowspan="2" style="text-align:center; vertical-align:middle;" >Pukul</th>
						  <th colspan="6" style="text-align:center;">Hari</th>
						</tr>
						<tr>
							<th>Senin</th>
							<th>Selasa</th>
							<th>Rabu</th>
							<th>Kamis</th>
							<th>Jumat</th>
							<th>Sabtu</th>
						</tr>
                    </thead>
                    <tbody>
                    <?php
						
						$kondisi = "WHERE a.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
						if($_SESSION[$sessionname]->roles == 'su'){
							$kondisi = "";
						}
						$query_kelas = "
							SELECT *
							from tbl_kelas a 
							JOIN tbl_sekolah b ON (a.id_sekolah = b.id_sekolah)
							$kondisi
						";
						$data = db_query2list($query_kelas);
						foreach($data as $result_kelas){
							$query = "
								SELECT a.jam_start, a.jam_selesai,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 1
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_senin,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 2
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_selasa,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 3
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_rabu,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 4
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_kamis,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 5
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_jumat,
								(
									SELECT b1.nama_mata_pelajaran
									FROM tbl_jadwal_pelajaran a1 
									JOIN tbl_mata_pelajaran b1 ON (a1.id_mata_pelajaran = b1.id_mata_pelajaran)
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 6
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS mapel_sabtu,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 1
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_senin,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 2
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_selasa,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 3
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_rabu,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 4
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_kamis,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 5
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_jumat,
								(
									SELECT a1.id_guru
									FROM tbl_jadwal_pelajaran a1 
									WHERE a1.tahun_ajaran = a.tahun_ajaran
									AND a1.semester = a.semester
									AND a1.id_hari = 6
									AND a1.id_kelas = a.id_kelas
									AND a1.jam_start = a.jam_start
									AND a1.jam_selesai = a.jam_selesai
								) AS guru_sabtu
								FROM tbl_jadwal_pelajaran a
								WHERE a.id_kelas = '".$result_kelas->id_kelas."'
								AND a.semester = '".$semester."'
								AND a.tahun_ajaran = ".$tahun."
								GROUP BY a.jam_start, a.jam_selesai
							";
							$count = db_num_rows($query);
							$data = db_query2list($query);
					?>
							<tr>
								<td rowspan="<?php echo $count + 1?>"><?php echo $result_kelas->nama_kelas?></td>
							</tr>
							<?php foreach($data as $result){ ?>
								<tr>
									<td><?php echo $result->jam_start?> - <?php echo $result->jam_selesai?></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>','1', '<?php echo $result->mapel_senin?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_senin?>')"><?php echo $result->mapel_senin?></a></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>', '2', '<?php echo $result->mapel_selasa?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_selasa?>')"><?php echo $result->mapel_selasa?></a></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>', '3', '<?php echo $result->mapel_rabu?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_rabu?>')"><?php echo $result->mapel_rabu?></a></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>', '4', '<?php echo $result->mapel_kamis?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_kamis?>')"><?php echo $result->mapel_kamis?></a></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>', '5', '<?php echo $result->mapel_jumat?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_jumat?>')"><?php echo $result->mapel_jumat?></a></td>
									<td><a href="#" onclick="editData('<?php echo $result->jam_start?>', '<?php echo $result->jam_selesai?>', '<?php echo $result_kelas->id_sekolah?>', '<?php echo $result_kelas->id_kelas?>', '6', '<?php echo $result->mapel_sabtu?>', '<?php echo $semester?>', '<?php echo $tahun?>', '<?php echo $result->guru_sabtu?>')"><?php echo $result->mapel_sabtu?></a></td>
								</tr>
							<?php } ?>
					<?php
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
        <div class="modal modal-primary fade" id="modal-form" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form role="form" action="" method="post" id="dataform">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Category</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="id_jadwal_pelajaran" value="" id="id_jadwal_pelajaran">
					<?php if ($_SESSION[$sessionname]->roles == 'su') {?>
                    <div class="form-group">
                      <label for="jam_selesai">Sekolah</label>
                      <select class="form-control" style="width: 100%;" name="id_sekolah" id="id_sekolah" required onchange="chooseKelasBySekolah();" >
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
					<?php } ?>  
					<div class="form-group">
                      <label for="jam_selesai">Kelas</label>
                      <select class="form-control" style="width: 100%;" name="id_kelas" id="id_kelas" required >
						<option value="" >---Kelas---</option>
						<?php 
							$query = "
								SELECT * 
								FROM tbl_kelas a
								$kondisi
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
                    <div class="form-group">
                      <label for="id_mata_pelajaran">Nama Mata Pelajaran</label>
                      <select class="form-control" style="width: 100%;" name="id_mata_pelajaran" id="id_mata_pelajaran" required >
						<option value="" >---Mata Pelajaran---</option>
						<?php 
							$query = "
								SELECT * 
								FROM tbl_mata_pelajaran a
								$kondisi
							";
							$data = db_query2list($query);
							foreach($data as $result){
						?>
								<option value="<?php echo $result->id_mata_pelajaran?>" ><?php echo $result->nama_mata_pelajaran?></option>
						<?php
							}
						?>
					</select>
                    </div>
					<div class="form-group">
                      <label for="jam_start">Jam Mulai</label>
                      <input type="text" class="form-control timepicker" name="jam_start" id="jam_start" placeholder="Jam Mulai" required="required">
                    </div>
					<div class="form-group">
                      <label for="jam_selesai">Jam Selesai</label>
                      <input type="text" class="form-control timepicker" name="jam_selesai" id="jam_selesai" placeholder="Jam Selesai" required="required">
                    </div>
					<div class="form-group">
                      <label for="jam_selesai">Hari</label>
                      <select class="form-control" style="width: 100%;" name="id_hari" id="id_hari" required >
						<option value="" >---Hari---</option>
						<?php 
							$query = "
								SELECT * 
								FROM tbl_hari
							";
							$data = db_query2list($query);
							foreach($data as $result){
						?>
								<option value="<?php echo $result->id_hari?>" ><?php echo $result->hari?></option>
						<?php
							}
						?>
					  </select>
					</div>
					<div class="form-group">
                      <label for="jam_selesai">Guru</label>
                      <select class="form-control" style="width: 100%;" name="id_guru" id="id_guru" required >
						<option value="" >---Guru---</option>
						<?php 
							$query = "
								SELECT * 
								FROM tbl_guru a
								$kondisi
							";
							$data = db_query2list($query);
							foreach($data as $result){
						?>
								<option value="<?php echo $result->id_guru?>" ><?php echo $result->nama_guru?></option>
						<?php
							}
						?>
					  </select>
                    </div>
					<div class="form-group">
                      <label for="jam_selesai">Semester</label>
					  <select name="semester" id="semester" class="form-control" >
						<option value="1" >1</option>
						<option value="2" >2</option>
					  </select>
                    </div>
					<div class="form-group">
                      <label for="jam_selesai">Tahun Ajaran</label>
					  <input type="text" class="form-control" name="tahun_ajaran" id="tahun_ajaran" placeholder="Tahun Ajaran" required="required">
                    </div>
                   </div>
				  </div>
                  <!-- /.box-body -->
              </div>
			  
              <div class="modal-footer">
                <button type="submit" style="display:none" name="delete" value="delete" id="btn-delete" class="btn btn-danger pull-left" >Delete</button>
                <input type="submit" style="display:none" class="btn btn-primary submitData" name="update" id="updateData" value="Save changes">
                <input type="submit" class="btn btn-primary submitData" name="submit" id="submitData" value="Save changes">
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
		var id_sekolah = $("#id_sekolah").val();
		console.log(id_sekolah);
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
			var selected = (value.id_kelas === x) ? 'selected' :'';
			ftext += "<option value='" + value.id_kelas + "' " + selected + ">" + value.nama_kelas + "</option>";
		  });
		  $("#id_kelas").html(ftext);
		}).fail(function(data){
		  console.log(data);
		  //alert("terjadi kesalahan, silahkan refresh ulang");
		});
	}
	
    function addcat(){
        if($("#typeJadwal Mata Pelajaran option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeJadwal Mata Pelajaran").append(o);
        }
        $('#modal-form').removeClass('modal-success').addClass('modal-primary');
		$('#id_jadwal_pelajaran').val('');
        $('#dataform')[0].reset();
        $('#modalTitle').text('Add Mata Pelajaran');
        $('.submitData').val('Save changes');
        $('#submitData').show();
        $('#btn-delete').hide();
        $('#updateData').hide();
        $('#modal-form').modal();
    }
    
    function editData(jam_start,jam_selesai, id_sekolah, id_kelas, id_hari, nama_mata_pelajaran, semester, tahun_ajaran, id_guru){
        $('#modal-form').removeClass('modal-primary').addClass('modal-success');
        $('#dataform')[0].reset();
        $('#modalTitle').text('Edit Mata Pelajaran');
        $('#jam_start').val(jam_start);
        $('#jam_selesai').val(jam_selesai);
        $('#id_sekolah').val(id_sekolah);
        $('#id_hari').val(id_hari);
        $('#semester').val(semester);
        $('#tahun_ajaran').val(tahun_ajaran);
        $('#id_guru').val(id_guru);
        $('#id_mata_pelajaran').val($('#id_mata_pelajaran option').filter(function () { return $(this).html() == nama_mata_pelajaran; }).val());
		chooseKelasBySekolah(id_kelas);
        $('.submitData').val('Save changes');
		$('#btn-delete').show();
		$('#updateData').show();
		$('#submitData').hide();
        $('#modal-form').modal();
    }
	
    function deleteData(id_jadwal_pelajaran,id_mata_pelajaran){
        $('#modal-form').removeClass('modal-primary').addClass('modal-danger');
        $('#dataform')[0].reset();
        $('#modalTitle').text('Delete Mata Pelajaran');
        $('#id_jadwal_pelajaran').val(id_jadwal_pelajaran);
        $('#id_mata_pelajaran').val(id_mata_pelajaran);
        $('#submitData').val('Delete');
        $('#modal-form').modal();
    }
	
</script>