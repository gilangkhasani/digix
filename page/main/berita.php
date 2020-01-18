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
              <h3 class="box-title"><i class="fa fa-edit"></i> Berita</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                    if(!empty($_POST['submit'])){
						$eror		= false;
						$folder		= 'file/';
						$file_name = "";
						$gambar_berita = $_FILES['gambar_berita']['name'];
						if($gambar_berita!=''){
							//Mulai memorises data
							$file_size	= $_FILES['gambar_berita']['size'];
							//cari extensi file dengan menggunakan fungsi explode
							$explode	= explode('.',$_FILES['gambar_berita']['name']);
							$extensi	= $explode[count($explode)-1];
							
							$tanggal = date('Ymd');
							$waktu = date('His');
							
							//ubah nama file
							$gambar_berita	= "berita_".$tanggal."_".$waktu.".".$extensi;
							$file_loc = $folder.$gambar_berita;

							//check apakah type file sudah sesuai
							$pesan='';
							
							//check ukuran file apakah sudah sesuai

							if($eror == true){
								echo '<div class="alert alert-error">'.$pesan.'</div>';
							}
							else{
								//mulai memproses upload file
								move_uploaded_file($_FILES['gambar_berita']['tmp_name'],$folder.$gambar_berita);
							}
						}else{
							status("Rejecting !!!");
							$message = "<div id='al' class='alert alert-danger'>Data gagal Ditambahkan !!</div>";
						}
						
                       if($_POST['id_berita']=='' && $_POST['submit']=="Save changes"){
                           $query = "insert into tbl_berita (judul_berita, deskripsi_berita, gambar_berita, tanggal_berita, id_user, id_sekolah) values(
                            '".$_POST['judul_berita']."',
                            '".$_POST['deskripsi_berita']."',
                            '".$gambar_berita."',
                            '".$_POST['tanggal_berita']."',
                            '".$_POST['id_user']."',
                            '".$_POST['id_sekolah']."'
                           )";
						   
                           $msg = "Data Menu telah ditambahkan";
                       }elseif($_POST['id_berita']<>'' && $_POST['submit']=="Save changes"){
                           $query = "update tbl_berita set 
                            judul_berita='".$_POST['judul_berita']."',
                            deskripsi_berita='".$_POST['deskripsi_berita']."',
                            gambar_berita='".$gambar_berita."',
                            tanggal_berita='".$_POST['tanggal_berita']."',
                            id_user='".$_POST['id_user']."'
                           where id_berita='".$_POST['id_berita']."'
                           ";
						   
                           $msg = "Data Berita telah diubah";
                       }elseif($_POST['id_berita']<>'' && $_POST['submit']=="Delete"){
                           $query = "delete from tbl_berita where id_berita='".$_POST['id_berita']."'
                           ";
						   
                           $msg = "Data Berita telah dihapus";
                       }
                       $doquery = db_query_insert($query);
					   
                       if($doquery['result']==TRUE){
                           //echo alertflash('success','Sukses','Data Category telah ditambahkan');
                           $_SESSION[$sessionname]->warningtype = 'success';
                           $_SESSION[$sessionname]->warningheader = 'Sukses';
                           $_SESSION[$sessionname]->warningmessage = $msg;
                           echo redirect('Berita');
                           exit;
                       }else{
                           //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
                           $_SESSION[$sessionname]->warningtype = 'danger';
                           $_SESSION[$sessionname]->warningheader = 'Gagal';
                           $_SESSION[$sessionname]->warningmessage = 'Data Berita gagal ditambah. '.$output['error'];
                           echo redirect('Berita');
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
					<button type="button" class="btn btn-block btn-primary" onclick="addData()">Add New Berita</button><br>
				  </div>
				</div>
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >No</th>
                      <th >Judul Berita</th>
                      <th >Deskripsi Berita</th>
                      <th >Gambar Berita</th>
                      <th >Tanggal Berita</th>
                      <th >Creator Berita</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "WHERE a.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					} else if($_SESSION[$sessionname]->roles != 'admin'){
						$kondisi = "WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."' AND a.id_user = '".$_SESSION[$sessionname]->id_user."'";
					} 
                    $query = "select * from tbl_berita a join tbl_user b on (a.id_user = b.id_user) join tbl_person c on (b.id_person = c.id_person) $kondisi";
                    $data = db_query2list($query);
					$no = 0;
                    foreach($data as $val){
						$no++;
						echo "<tr>";
							echo "<td>".$no."</td>";
							echo "<td>".$val->judul_berita."</td>";
							echo "<td>".$val->deskripsi_berita."</td>";
							echo "<td><a href='#' class='pop'><img src='https://itbsjabartsel.com/digix/file/".$val->gambar_berita."' width='50' height='50' /></a></td>";
							echo "<td>".$val->tanggal_berita."</td>";
							echo "<td>".$val->fullname."</td>";
							echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->id_berita."', '".$val->judul_berita."', '".$val->deskripsi_berita."', '".$val->gambar_berita."', '".$val->tanggal_berita."', '".$val->id_user."')\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->id_berita."', '".$val->judul_berita."', '".$val->deskripsi_berita."', '".$val->gambar_berita."', '".$val->tanggal_berita."', '".$val->id_user."')\">Delete</a></td>";
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
              <form role="form" action="" method="post" id="menuform" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalTitle">Add New Category</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="id_berita" value="" id="id_berita">
                    <input type="hidden" name="id_user" value="<?php echo $_SESSION[$sessionname]->id_user?>" id="id_user">
                    <input type="hidden" name="tanggal_berita" value="<?php echo date('Y-m-d H:i:s')?>" id="tanggal_berita">
                    <input type="hidden" name="id_sekolah" value="<?php echo $_SESSION[$sessionname]->id_sekolah?>" id="id_sekolah">
                    <div class="form-group">
                      <label for="judul_berita">Judul Berita</label>
                      <input type="text" class="form-control" name="judul_berita" id="judul_berita" placeholder="Judul Berita" required="required">
                    </div>
                    <div class="form-group">
                      <label for="judul_berita">Deskripsi Berita</label>
                      <textarea id="deskripsi_berita" name="deskripsi_berita" class="form-control" required="required"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="judul_berita">Gambar Berita</label>
                      <input type="file" class="form-control" name="gambar_berita" id="gambar_berita" />
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
		<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">              
			  <div class="modal-body">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<img src="" class="imagepreview" style="width: 100%;" >
			  </div>
			</div>
		  </div>
		</div>
        <!-- /.modal -->
      <!-- /.row -->
    </section>
<script>
    //$('.select2').select2();
    function addData(){
        if($("#typeMenu option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeMenu").append(o);
        }
        $('#modal-formmenu').removeClass('modal-success').addClass('modal-primary');
		$('#id_berita').val('');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Add Berita');
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
    
    function editData(id_berita,judul_berita, deskripsi_berita, gambar_berita, tanggal_berita, id_user){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Berita');
        $('#id_berita').val(id_berita);
        $('#judul_berita').val(judul_berita);
        $('#deskripsi_berita').val(deskripsi_berita);
        //$('#gambar_berita').val(gambar_berita);
        $('#tanggal_berita').val(tanggal_berita);
        $('#id_user').val(id_user);
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(id_berita,judul_berita, deskripsi_berita, gambar_berita, tanggal_berita, id_user){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Berita');
        $('#id_berita').val(id_berita);
        $('#judul_berita').val(judul_berita);
        $('#deskripsi_berita').val(deskripsi_berita);
        //$('#gambar_berita').val(gambar_berita);
        $('#tanggal_berita').val(tanggal_berita);
        $('#id_user').val(id_user);
        $('#submitMenu').val('Delete');
        $('#modal-formmenu').modal();
    }
	
	$('#example1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
	
	$(function() {
		$('.pop').on('click', function() {
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
	});
</script>