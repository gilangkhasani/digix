<style>
p .title  {
    color: red;
    font-size:20px;
}
</style>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>


<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
                <i class="fa fa-home"></i> Absen Per Class <?php echo date('Y-m-d')?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="container"><p style="text-align:center;">No data available in graph</p></div>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        
    </div>
        <!-- /.col -->
	<div class="row">
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
                <i class="fa fa-home"></i> Report Absen <?php echo date('Y-m-d')?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                 <div class="col-md-12" style="overflow-x:auto">
                  <table id="example1" class="table table-bordered">
                    <thead>
                    <tr>
                      <th rowspan="2">Nama Sekolah</th>
                      <th rowspan="2">Kelas</th>
                      <th colspan="3">Absen</th>
                    </tr>
					<tr>
					  <th>Tepat Waktu</th>
                      <th>Telat</th>
                      <th>Total</th>
					</tr>
                    </thead>
                    
                  </table>
                  </div>
                </div>
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        
    </div>
        <!-- /.col -->
</section>
<script>
	$( document ).ready(function() {
		var table_id = $("#example1").DataTable({
			data:[],
			columns: [
				{ "data": "nama_sekolah" },
				{ "data": "nama_kelas" },
				{ "data": "total_tepat_waktu" },
				{ "data": "total_telat" },
				{ "data": "total_absen" },
			],
			rowCallback: function (row, data) {},
			filter: true,
			info: true,
			ordering: false,
			processing: true,
			retrieve: true,
			lengthChange: true,
			dom: 'Bfrtip',
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
		});
		
		$.ajax({
			type:"GET",
			url: "<?php echo url('page/main/ajax/ajax-get-summary.php?id_sekolah='.$_SESSION[$sessionname]->id_sekolah)?>&auth=<?php echo $_SESSION[$sessionname]->auth?>",
			dataType:'json',
			error: function (request,status, error) {
				console.log(request);
			},
		}).done(function(data){
			console.log(data);
			if(data.count > 0) {
				table_id.clear().draw();
				table_id.rows.add(data.result).draw();
				
				Highcharts.chart('container', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Report Absence Per Class'
					},
					xAxis: {
						categories: data.categories
					},
					yAxis: {
						min: 0,
						title: {
							text: 'Value'
						}
					},
					tooltip: {
						pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
						shared: true
					},
					plotOptions: {
						column: {
							stacking: 'percent'
						}
					},
					series: data.series
				});
			}
		}).fail(function(data){
			console.log(data);
			//alert("terjadi kesalahan, silahkan refresh ulang");
		});
	});
</script>