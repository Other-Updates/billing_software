<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?><script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script><script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script><script type="text/javascript" src="<?php echo $theme_path ?>/js/attendance_reports.js"></script><style>.btn-xs {	border-radius:0px !important;	text-align:center;}table tr th {	text-align:center !important;}table tr td {	text-align:center !important;}</style><div class="mainpanel">	<div class="mt--20" >        <h4 class="widgettitle mtop">Data Migration</h4>    </div></div>	<div class="mainpanel">        <div class="panel-body mt-top5">        <table class="table responsive_table table-striped table-bordered responsive dataTable no-footer dtr-inline" id="basicTable_call_back">            <thead>                <tr>                    <th>S.No</th>                                        <th>Last Log Date</th>                    <th>Last Log Time</th>                    <th>Logs downloaded</th>                    <th>status</th>                    <th>Last migration log datetime</th>                </tr>            </thead>            <tbody>                <?php if(!empty($migration_logs)) {                     foreach($migration_logs as $key=>$migrate_data) {  ?>                <tr>                                        <td><?php echo $key+1; ?></td>                    <td><?php echo date('d-m-Y', strtotime($migrate_data['log_date']));?></td>                <td><?php echo date('g:i A', strtotime($migrate_data['start_time']))."-".date('g:i A', strtotime($migrate_data['end_time']));?></td>                    <td style="text-align:center;"><a href="javascript:void(0)" stock_id="1" data-toggle="tooltip" id="load" date="<?php echo                    $migrate_data['last_run_log_datetime']; ?>" class="tooltips btn btn-default btn-xs edit" title="" data-original-title="Edit"><span class="fa fa-download "></span></a></td>                <td style="text-align:center;"><?php echo $migrate_data['status'];?></td>                <td style="text-align:center;"><?php echo $migrate_data['last_run_log_datetime'];?></td>                </tr>            <?php } }else { ?>                <tr><td><center>No result found...</center></td></tr>            <?php } ?>            </tbody>        </table>    </div></div><script>$('.btn').on('click', function() {    var $this = $(this);  $this.button('loading');  var date=$(this).attr('date');//  alert(date);  $.ajax({            url: BASE_URL + "attendance/run_migration",            type: "POST",            data: {last_logdate: date},            success: function (result)            {                //alert(result);                //return false;                $this.button('reset');                location.reload();            }        });});</script>