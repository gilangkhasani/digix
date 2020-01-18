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
	<?php
		(!empty($_GET['tanggal'])) ? $tanggal = $_GET['tanggal'] : $tanggal = date('Y-m-d');
	?>
	<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-edit"></i> Report Absen <?php echo $tanggal?></h3>
			   <form method="GET" action="">
					<div class="form-group">
						<input type="text" name="tanggal" id="start_date" class="form-control datepicker" placeholder="Tanggal" required style="display:inline; width:20%;" value="<?php echo $tanggal?>" />
						<button class="btn btn-primary btn-small" type="submit" style="display:inline;">Search</button>
					</div>
				</form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
						<tr>
							<th rowspan="2" style="width:5%;vertical-align:middle; text-align:center;">Username</th>
							<th rowspan="2" style="vertical-align:middle; text-align:center;">Nama Lengkap</th>
							<th rowspan="2" style="width:3%;vertical-align:middle; text-align:center;">Kelas</th>
							<th colspan="2" style="text-align:center;">Checkin</th>
							<th colspan="2" style="text-align:center;" >Checkout</th>
						</tr>
						<tr>
							<th >Tanggal</th>
							<th >Kordinat</th>
							<th >Tanggal</th>
							<th >Kordinat</th>
						</tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "AND d .id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					}
                    $query = "
						SELECT * 
						FROM tbl_absen a 
						JOIN tbl_user b ON (a.id_user = b.id_user)
						JOIN tbl_person c ON (b.id_person = c.id_person)
						JOIN tbl_kelas d ON (a.id_kelas = d.id_kelas)
						WHERE a.mydate = '".$tanggal."'
						$kondisi
					";
                    $data = db_query2list($query);
                    foreach($data as $val){
						echo "<tr>";
							echo "<td>".$val->username."</td>";
							echo "<td>".$val->fullname."</td>";
							echo "<td>".$val->nama_kelas."</td>";
							echo "<td>".$val->checkin_time."</td>";
							echo "<td>(".$val->longitude_in.",".$val->latitude_in.")</td>";
							echo "<td>".$val->checkout_time."</td>";
							echo "<td>(".$val->longitude_out.",".$val->latitude_out.")</td>";
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