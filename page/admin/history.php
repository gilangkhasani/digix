<section class="content">
    <div class="row">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i>
                        History Login User
                        <small class="pull-right">Date: <?php echo date("d/m/Y");?></small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped" id="history_table">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="10%">User Ldap</th>
                                <th width="20%">Activity</th>
                                <th width="10%">Status</th>
                                <th>Error Code</th>
                                <th>Datetimelog</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                   // $query = "select * from boopati_HVC_nonregistration where TEMP_NIK='".$nik."' and TEMP_TANGGAL='".date("Y-m-d")."' and REMARK_CLAIM is null";
                                    $query = "select * from boopati_history where datetimelog >= now() - interval  7 day order by datetimelog desc";
                                    $data = db_query2list($query);
                                    $iq = 1;
                                    foreach($data as $val){
                                        echo "<tr>";
                                        echo "  <td>".$iq."</td>";
                                        echo "  <td>".$val->username."</td>";
                                        echo "  <td>".$val->activity."</td>";
                                        echo "  <td>".$val->status."</td>";
                                        echo "  <td>".$val->errorcode."</td>";
                                        echo "  <td>".$val->datetimelog."</td>";
                                        echo "</tr>";
                                        $iq++;
                                    }   
                                ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
        </section>
    </div>
</section>
<script>
 $('#history_table').DataTable({
        lengthMenu: [
            [ 25, 50, -1],
            [ 25, 50, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print'
        ]
    });
</script>
    