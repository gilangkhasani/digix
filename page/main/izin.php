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
              <h3 class="box-title"><i class="fa fa-edit"></i> Izin</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th >No</th>
                      <th >Jenis Izin</th>
                      <th >Tanggal Izin</th>
                      <th >Deskripsi</th>
                      <th >Foto Izin</th>
                      <th >Nama Lengkap</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
					$kondisi = "WHERE c.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
					if($_SESSION[$sessionname]->roles == 'su'){
						$kondisi = "";
					} else if($_SESSION[$sessionname]->roles != 'admin'){
						$kondisi = "WHERE id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."' AND a.id_user = '".$_SESSION[$sessionname]->id_user."'";
					} 
                    $query = "select * from tbl_izin a join tbl_user b on (a.id_user = b.id_user) join tbl_person c on (b.id_person = c.id_person) $kondisi";
                    $data = db_query2list($query);
					$no = 0;
                    foreach($data as $val){
						$no++;
						echo "<tr>";
							echo "<td>".$no."</td>";
							echo "<td>".$val->jenis_izin."</td>";
							echo "<td>".$val->tanggal."</td>";
							echo "<td>".$val->deskripsi."</td>";
							echo "<td><a href='#' class='pop'><img src='https://itbsjabartsel.com/lokasi/upload/".$val->poto."' width='50' height='50' /></a></td>";
							echo "<td>".$val->fullname."</td>";
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
    $(function() {
		$('.pop').on('click', function() {
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
	});
	
	$('#example1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
</script>