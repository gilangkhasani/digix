<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBCUcNV5zciXWiyV8HX2vLYHebNE4CL1s"></script>

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
		  <h3 class="box-title"><i class="fa fa-edit"></i> Koordinat</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<?php
				if(!empty($_POST['submit'])){
				   if($_POST['id_polygon']=='' && $_POST['submit']=="Save changes"){
					   $query = "insert into tbl_polygon (remark_polygon, id_sekolah) values('".$_POST['remark_polygon']."', '".$_POST['id_sekolah']."' )";
					   
					   $msg = "Data Koordinat telah ditambahkan";
					   $doquery = db_query_insert($query);
					   
					   $id_polygon = last_insert_id();
					   for($i = 0; $i < count($_POST['urut']); $i++){
							$query_detail = "insert into tbl_polygon_koordinat (id_polygon, point_x1, point_y1, urut) values(
							'".$id_polygon."', '".$_POST['point_x1'][$i]."', '".$_POST['point_y1'][$i]."', '".$_POST['urut'][$i]."' 
							)";
							
							$doquery = db_query_insert($query_detail);
					   }
					   $last_count = count($_POST['urut']) + 1;
					   $query_detail = "insert into tbl_polygon_koordinat (id_polygon, point_x1, point_y1, urut) values(
						'".$id_polygon."', '".$_POST['point_x1'][0]."', '".$_POST['point_y1'][0]."', '".$last_count."' 
						)";
						
						$doquery = db_query_insert($query_detail);
				   }elseif($_POST['id_polygon']<>'' && $_POST['submit']=="Save changes"){
					   $query = "update tbl_polygon set 
						remark_polygon='".$_POST['remark_polygon']."',
						id_sekolah='".$_POST['id_sekolah']."'
					   where id_polygon='".$_POST['id_polygon']."'
					   ";
					   
					   $msg = "Data Koordinat telah diubah";
					   $doquery = db_query_insert($query);
					   $query = "delete from tbl_polygon_koordinat where id_polygon='".$_POST['id_polygon']."'
					   ";
					   $doquery = db_query_insert($query);
					   
					   for($i = 0; $i < count($_POST['urut']); $i++){
							$query_detail = "insert into tbl_polygon_koordinat (id_polygon, point_x1, point_y1, urut) values(
							'".$_POST['id_polygon']."', '".$_POST['point_x1'][$i]."', '".$_POST['point_y1'][$i]."', '".$_POST['urut'][$i]."' 
							)";
							
							$doquery = db_query_insert($query_detail);
					   }
					   $last_count = count($_POST['urut']) + 1;
					   $query_detail = "insert into tbl_polygon_koordinat (id_polygon, point_x1, point_y1, urut) values(
						'".$_POST['id_polygon']."', '".$_POST['point_x1'][0]."', '".$_POST['point_y1'][0]."', '".$last_count."' 
						)";
						
						$doquery = db_query_insert($query_detail);
					   
				   }elseif($_POST['id_polygon']<>'' && $_POST['submit']=="Delete"){
					   $query = "delete from tbl_polygon_koordinat where id_polygon='".$_POST['id_polygon']."'
					   ";
					   $doquery = db_query_insert($query);
					   
					   $query = "delete from tbl_polygon where id_polygon='".$_POST['id_polygon']."'
					   ";
					   
					   $msg = "Data Koordinat telah dihapus";
					   $doquery = db_query_insert($query);
				   }
				   if($doquery['result']==TRUE){
					   //echo alertflash('success','Sukses','Data Category telah ditambahkan');
					   $_SESSION[$sessionname]->warningtype = 'success';
					   $_SESSION[$sessionname]->warningheader = 'Sukses';
					   $_SESSION[$sessionname]->warningmessage = $msg;
					   echo redirect('koordinat');
					   exit;
				   }else{
					   //echo alertflash('danger','Gagal','Data Category gagal ditambah. '.$output['error']);
					   $_SESSION[$sessionname]->warningtype = 'danger';
					   $_SESSION[$sessionname]->warningheader = 'Gagal';
					   $_SESSION[$sessionname]->warningmessage = 'Data Koordinat gagal ditambah. '.$output['error'];
					   echo redirect('koordinat');
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
				<button type="button" class="btn btn-block btn-primary" onclick="addData()">Add New Koordinat</button><br>
			  </div>
			</div>
			<div class="row">
			 <div class="col-md-12" style="overflow-x:auto">
			  <table id="example1" class="table table-bordered">
				<thead>
				<tr>
				  <th >No</th>
				  <th >Group Koordinat</th>
				  <th >Nama Sekolah</th>
				  <th>Action</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$kondisi = "WHERE a.id_sekolah = '".$_SESSION[$sessionname]->id_sekolah."'";
				if($_SESSION[$sessionname]->roles == 'su'){
					$kondisi = "";
				}
				$query = "select * from tbl_polygon a join tbl_sekolah b on (a.id_sekolah = b.id_sekolah) $kondisi";
				$data = db_query2list($query);
				$no = 0;
				foreach($data as $val){
					$no++;
					echo "<tr>";
						echo "<td>".$no."</td>";
						echo "<td>".$val->remark_polygon."</td>";
						echo "<td>".$val->nama_sekolah."</td>";
						echo "<td><a class='btn btn-success btn-sm' onclick=\"editData('".$val->remark_polygon."','".$val->id_sekolah."', '".$val->id_polygon."' )\">Edit</a><a class='btn btn-danger btn-sm' onclick=\"deleteData('".$val->remark_polygon."','".$val->id_sekolah."', '".$val->id_polygon."')\">Delete</a></td>";
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
				<input type="hidden" name="id_polygon" value="" id="id_polygon">
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
				  <label for="remark_polygon">Group Koordinat</label>
				  <input type="text" class="form-control" name="remark_polygon" id="remark_polygon" placeholder="Group Koordinat" required="required">
				</div>
				<div class="form-group table-responsive" style="background:#ffffff; color:black;">
					<label for="jam_selesai">Koordinat</label>
					<table class="table table-responsive">
						<thead>
							<tr>
								<th>Longitude</th>
								<th>Latitude</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="table-body-koordinat">
							
						</tbody>
					</table>
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
	<div class="modal modal-primary fade" id="modal-get-longlat" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <form role="form" action="" method="post" id="menuform">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="modalTitle">Maps</h4>
		  </div>
		  <div class="modal-body">
			<input type="hidden" id="id_value" />
			<input type="hidden" id="longitude_maps" value="" />
			<input type="hidden" id="latitude_maps" value="" />
			<div id="map" style="width:100%; height:300px;text-align:center;margin-bottom:5px;"></div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			<input type="button" class="btn btn-primary" name="submitGetLongLat" id="submitGetLongLat" value="Get Coordinate">
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
		initMap();
		$('#example1').DataTable({
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'print'
			]
		});
	});

	var map, marker;

    function addData(){
        if($("#typeMenu option[value='header']").length ==0){
            var o = new Option("header", "header");
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html("Header");
            $("#typeMenu").append(o);
        }
        $('#modal-formmenu').removeClass('modal-success').addClass('modal-primary');
		$('#id_polygon').val('');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Add Sekolah');
        $('#submitMenu').val('Save changes');
		
		$("#table-body-koordinat").html(
			'<tr id="koordinat1"> ' + 
				'<input type="hidden" class="form-control" name="urut[]" id="urut1" value="1" required="required">' + 
				'<td><input type="text" class="form-control longitude" name="point_x1[]" id="point_x1" placeholder="Longitude" onclick="openModalLongLat(1)" required="required"></td>' +
				'<td><input type="text" class="form-control latitude" name="point_y1[]" id="point_y1" placeholder="Latitude" onclick="openModalLongLat(1)" required="required"></td>' +
				'<td>' +
					'<a class="btn btn-primary btn-sm" onclick="addKoordinatData(1)"><i class="fa fa-plus"></i></a>' +
				'</td>' +
			'</tr>'
		);
        $('#modal-formmenu').modal();
    }
	
	function addKoordinatData(value){
		var new_value = value + 1;
		$("#table-body-koordinat").append(
			'<tr id="koordinat' + new_value + '"> ' + 
				'<input type="hidden" class="form-control" name="urut[]" id="urut' + new_value + '" value="' + new_value + '" required="required">' + 
				'<td><input type="text" class="form-control longitude" name="point_x1[]" id="point_x' + new_value + '" onclick="openModalLongLat(' + new_value + ')" placeholder="Longitude" required="required"></td>' +
				'<td><input type="text" class="form-control latitude" name="point_y1[]" id="point_y' + new_value + '" onclick="openModalLongLat(' + new_value + ')" placeholder="Latitude" required="required"></td>' +
				'<td>' +
					'<a class="btn btn-primary btn-sm" onclick="addKoordinatData(' + new_value + ')"><i class="fa fa-plus"></i></a>' +
					'<a class="btn btn-danger btn-sm" onclick="deleteKoordinatData(' + new_value + ')"><i class="fa fa-times"></i></a>' +
				'</td>' +
			'</tr>'
		);
	}
	
	function deleteKoordinatData(value){
		$("#koordinat" + value).remove();
	}
    
    function editData(remark_polygon, id_sekolah, id_polygon){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-success');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Edit Koordinat');
        $('#remark_polygon').val(remark_polygon);
        $('#id_polygon').val(id_polygon);
        $('#id_sekolah').val(id_sekolah);
		
		$.ajax({
		  type:"GET",
		  url: "<?php echo url('page/main/ajax/ajax-get-koordinat-group.php?id_polygon=')?>"+id_polygon,
		  dataType:'json',
		  error: function (request,status, error) {
			console.log(request);
		  },
		  //data:fdata
		}).done(function(data){
		  console.log(data);
		  var ftext = "";
		  
		  $.each( data.result, function( key, value ) {
			  var new_key = key + 1;
			  
			  ftext += '<tr id="koordinat' + new_key + '"> ' ; 
				ftext += '<input type="hidden" class="form-control" name="urut[]" id="urut' + new_key + '" value="' + new_key + '" required="required">' ;
				ftext += '<td><input type="text" class="form-control" name="point_x1[]" id="point_x' + new_key + '" value="' + value.point_x1 + '" placeholder="Longitude" required="required" onclick="openModalLongLat(' + new_key + ')" ></td>' ;
				ftext += '<td><input type="text" class="form-control" name="point_y1[]" id="point_y' + new_key + '" value="' + value.point_y1 + '" placeholder="Latitude" required="required" onclick="openModalLongLat(' + new_key + ')" ></td>' ;
				ftext += '<td>' ;
					ftext += '<a class="btn btn-primary btn-sm" onclick="addKoordinatData(' + new_key + ')"><i class="fa fa-plus"></i></a>' ;
					if(new_key > 1){
						ftext += '<a class="btn btn-danger btn-sm" onclick="deleteKoordinatData(' + new_key + ')"><i class="fa fa-times"></i></a>' ;
					}
				ftext += '</td>' ;
			ftext += '</tr>';
		  });
		  $("#table-body-koordinat").html(ftext);
		}).fail(function(data){
		  console.log(data);
		  //alert("terjadi kesalahan, silahkan refresh ulang");
		});
		
        $('#submitMenu').val('Save changes');
        $('#modal-formmenu').modal();
    }
	
    function deleteData(remark_polygon, id_sekolah, id_polygon){
        $('#modal-formmenu').removeClass('modal-primary').addClass('modal-danger');
        $('#menuform')[0].reset();
        $('#modalTitle').text('Delete Koordinat');
        $('#remark_polygon').val(remark_polygon);
        $('#id_polygon').val(id_polygon);
        $('#id_sekolah').val(id_sekolah);
        $('#submitMenu').val('Delete');
        $('#modal-formmenu').modal();
    }
	
	
	$("#submitGetLongLat").click(function(){
		var id = $("#id_value").val();
		var longitude_maps = $("#longitude_maps").val();
		var latitude_maps = $("#latitude_maps").val();
		
		$('#point_x' + id).val(longitude_maps);
		$('#point_y' + id).val(latitude_maps);
		$("#modal-get-longlat").modal("toggle");
		
	});
	
	function openModalLongLat(value){
		$("#id_value").val(value);
		$("#modal-get-longlat").modal("show");
	}
	
	function initMap(){
		var latlng;
		// Try HTML5 geolocation.
        if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			var pos = {
			  lat: position.coords.latitude,
			  lng: position.coords.longitude
			};
			placeMarker(pos);
			map.setCenter(pos);
			latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
        } else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
        }
	
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 16,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		google.maps.event.addListener(map, 'click', function( event ){
			document.getElementById("longitude_maps").value = event.latLng.lng();
			document.getElementById("latitude_maps").value = event.latLng.lat();
			placeMarker(event.latLng);
		});
		
	}
	
	function placeMarker(location) {
		if ( marker ) {
			marker.setPosition(location);
		} else {
			marker = new google.maps.Marker({
				position: location,
				map: map
			});
		}
	}
	
</script>