<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?><script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script><script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script><script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script><link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" /><link rel="stylesheet" href="<?= $theme_path; ?>/css/bootstrap-multiselect.css" type="text/css"/><script type="text/javascript" src="<?= $theme_path; ?>/js/bootstrap-multiselect.js"></script><script type="text/javascript" src="<?= $theme_path; ?>/js/attendance.js"></script><div class="mainpanel">    <div class="media mt--20">        <h4>Add Attendance For Day </h4>    </div>    <div class="contentpanel">        <div class="panel-body mt-top5">            <?php            $result = validation_errors();            if (trim($result) != ""):                ?>                <div class="alert alert-error">                    <button data-dismiss="alert" class="close" type="button">&times;</button>                    <?php echo validation_errors(); ?>                </div>            <?php endif; ?>            <?php            // $user_role = json_decode($roles[0]["roles"]);            $filter = array();            $filter = $this->session_view->get_session('attendance', 'daily_attendance');            //print_r($filter);            $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');            echo form_open('', $attributes);            ?>            <div class="scroll_bar">                <table class="table table-bordered">                    <caption><b>                            <?php                            echo "<span class='date_left' style='float:left;'>Date : " . date("d-m-Y", strtotime($filter["start_date"])) . "&nbsp;&nbsp;&nbsp;";                            echo "Day : " . date('l', strtotime($filter["start_date"])) . "</span>";                            ?>                            <span class="formwrapper" style="float:right;">                                <input type="checkbox" name="radiofield" id="inc_br1" class="inc_session" />Break 1 &nbsp; &nbsp;                                <input type="checkbox" name="radiofield" id="inc_lunch" class="inc_session"/>Lunch &nbsp; &nbsp;                                <input type="checkbox" name="radiofield" id="inc_br2" class="inc_session"/>Break 2 &nbsp; &nbsp;                                                                <!--<input type="checkbox" name="radiofield" id="inc_ot"/> Include OT &nbsp; &nbsp;-->                            </span>                    </caption>                    <thead>                        <tr>                            <?php                            $data = array(                                'type' => 'checkbox',                                'class' => 'generate-random-all',                                'checked' => FALSE                            );                            $head = array("S.No", "Employee Name", "In Time - Out Time", "Break / Lunch", "Over Time", "Total Hours", "Apply for Leave");                            foreach ($head as $ele) {                                if ($ele == "Over Time")                                    echo "<th  class='ot_class'>" . $ele . "</th>";                                else                                    echo "<th>" . $ele . "</th>";                            }                            ?>                            <th class="random_check"><?php echo form_checkbox($data); ?></th>                        </tr>                    </thead>                    <tbody>                        <?php                        $ot_enable = 0;                        $enter_count = 0;                        for ($k = 0; $k < count($filter["user_id"]); $k++) {                            ?>                            <?php                            $holiday = 0;                            $saturday = 0;                            $day_value = $filter["start_date"];                            $split_day = explode("-", $day_value);                            $current_day = ltrim($split_day[0], '0');                            $leave_type = '';                            $this->load->model('masters/user_department_model');                            $this->load->model('masters/holidays_model');                            $dept_id = $this->user_department_model->get_department_by_user_id($filter["user_id"][$k]);                            //print_r($dept_id);                            $holiday_arr = $this->holidays_model->get_holidays_by_department_id($filter['start_date'], $dept_id[0]["department"]);                            if (isset($holiday_arr) && !empty($holiday_arr))                                $holiday = 1;                            $current_shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($filter["user_id"][$k], $day_value);                            $current_shift = $this->shift_model->get_shift_details_by_shift_id($current_shift_id[0]["shift_id"]);                            //print_r($current_shift);                            if (isset($current_shift) && !empty($current_shift)) {                                $shift = array();                                foreach ($current_shift as $key => $value) {                                    $shift[$value["type"]][] = $value;                                }                            }                            $overtimestart = 0;                            $overtimeend = 0;                            if (isset($shift["overtimestart"][0])) {                                $overtimestart = $shift["overtimestart"][0]["from_time"];                                $overtimeend = $shift["overtimestart"][0]["to_time"];                                $ot_enable = 1;                            }                            $sun = date('l', strtotime($day_value));                            if ($saturday_holiday == 1) {                                if ($sun == "Saturday") {                                    $saturday = 1;                                }                            }                            ?>                            <tr>                                <td class="center"><?= $k + 1 ?></td>                                <td class="center"><?php                        $this->load->model('masters/users_model');                        $employee_details = $this->users_model->get_user_name_by_user_id($filter['user_id'][$k]);                        echo $employee_details[0]['first_name'] . " " . $employee_details[0]['last_name'];                        echo form_hidden('user_id[]', $filter["user_id"][$k]);                        echo form_hidden('attendance_date[]', $filter["start_date"]);                            ?></td>                                <?php                                $leave_date = date("Y-m-d", strtotime($filter["start_date"]));                                $this->load->model('attendance/leave_model');                                $leave = $this->leave_model->get_approved_user_leaves_on_date($filter["user_id"][$k], $leave_date);                                //print_r($leave);                                if (isset($leave) && !empty($leave)) {                                    //print_r($leave);                                    $leave_type = "";                                    $date1 = new DateTime(date('d-m-Y H:i:s', strtotime($leave[0]["leave_from"])));                                    $date2 = new DateTime(date('d-m-Y H:i:s', strtotime($leave[0]["leave_to"])));                                    $interval = dateTimeDiff($date1, $date2);                                    if ($leave[0]['type'] == "sick leave" || $leave[0]['type'] == "casual leave" || $leave[0]['type'] == "earned leave") {                                        if ($date1 == $date2) {                                            $leave_type = 2;                                        } else {                                            if ($interval->d == 0) {                                                $leave_type = 1;                                                $half_day = ($interval->h * 60) + $interval->i;                                            } else {                                                $leave_type = 2;                                            }                                        }                                    } else if ($leave[0]['type'] == "permission") {                                        $leave_type = 3;                                        $permission_hrs = ($interval->h * 60) + $interval->i;                                    } else if ($leave[0]['type'] == "compoff") {                                        $leave_type = 4;                                    } else if ($leave[0]['type'] == "on-duty") {                                        $leave_type = 5;                                    }                                }                                if ($leave_type != 1 && $leave_type != 3 && $leave_type != "" && $leave_type != 5) {                                    //echo $leave_type;                                    ?>                                    <td colspan="5" class="center">                                        <?php echo $leave[0]['reason']; ?></td>                                    <?php                                } else {                                    ?>                                    <td class="center">                                        <?php                                        $enter_count++;                                        $p = $filter["user_id"][$k];                                        $data = array(                                            'name' => 'attendance[in][' . $p . ']',                                            'class' => 'required timepicker input-small time_in',                                            'value' => set_value('attendance[in][' . $p . ']')                                        );                                        $data["shift_in_time"] = $shift["regular"][0]["from_time"];                                        echo form_input($data);                                        ?>                                        <?php                                        $v = $filter["user_id"][$k];                                        $data = array(                                            'name' => 'attendance[out][' . $v . ']',                                            'class' => 'required timepicker input-small valid time_out',                                            'value' => set_value('attendance[out][' . $v . ']')                                        );                                        $data["shift_out_time"] = $shift["regular"][0]["to_time"];                                        if (isset($shift["overtimestart"][0])) {                                            $data["overtime_in"] = $shift["overtimestart"][0]["from_time"];                                            $data["overtime_out"] = $shift["overtimestart"][0]["to_time"];                                        }                                        echo form_input($data);                                        ?>                                    </td>                                    <td class="center break_td">                                        <span class="break_to_clone">                                            <?php                                            $g = $filter["user_id"][$k];                                            $data = array(                                                'name' => 'break[in_time][' . $g . '][]',                                                'class' => 'input-small break in_break',                                                'value' => set_value('break[in][' . $g . ']')                                            );                                            echo form_input($data);                                            ?>                                            <?php                                            $data = array(                                                'name' => 'break[out_time][' . $g . '][]',                                                'class' => 'input-small out_break',                                                'value' => set_value('break[out][[' . $g . ']')                                            );                                            echo form_input($data);                                            ?>                                        </span>                                        <?php if (isset($shift["lunch"][0])) { ?>                                            <input type="hidden" class="lunch_in" value="<?= $shift["lunch"][0]["from_time"] ?>" />                                            <input type="hidden" class="lunch_out" value="<?= $shift["lunch"][0]["to_time"] ?>" />                                        <?php } ?>                                        <?php if (isset($shift["break"][0])) { ?>                                            <input type="hidden" class="break_first_in" value="<?= $shift["break"][0]["from_time"] ?>" />                                            <input type="hidden" class="break_first_out" value="<?= $shift["break"][0]["to_time"] ?>" />                                        <?php } ?>                                        <?php if (isset($shift["break"][1])) { ?>                                            <input type="hidden" class="break_second_in" value="<?= $shift["break"][1]["from_time"] ?>" />                                            <input type="hidden" class="break_second_out" value="<?= $shift["break"][1]["to_time"] ?>" />                                        <?php } ?>                                        <a href="javascript:void(0);" class="btn btn-danger add_row" title="Add"><i class="fa fa-plus fa fa-black"></i></a>                                    </td>                                    <td class="center overtime ot_class">                                        <span class="overtime_val"></span>                                        <input type="hidden" class= "overtimestart" value="<?= $overtimestart ?>" />                                        <input type="hidden" class="overtimeend" value="<?= $overtimeend ?>" />                                    </td>                                    <td class="center total_hours">                                        <span class="total_diff"></span>                                        <input type="hidden" class="total" /></td>                                    <?php ?>                                    <td class="center">                                        <?php                                        if ($leave_type == '' && $sun != "Sunday" && $saturday != 1 && $holiday == 0) {                                            if (in_array("leave:apply_leave", $user_role)) {                                                ?>                                                <a href="javascript:void(0)" class="btn btn-rounded btn-info" data-toggle="modal" title="Apply leave" data-target="#apply_leave<?= $k ?>" >Apply Leave</a>                                                <?php                                            }                                        } else {                                            if ($leave_type == 1)                                                echo $leave[0]['type'] . " ( " . date('H:i', strtotime($leave[0]['leave_from'])) . " - " . date('H:i', strtotime($leave[0]['leave_to'])) . " )";                                            else if ($leave_type == 3) {                                                if ($leave[0]['type'] != "")                                                    echo $leave[0]['type'] . " ( " . date('H:i', strtotime($leave[0]['leave_from'])) . " - " . date('H:i', strtotime($leave[0]['leave_to'])) . " )";                                                else                                                    echo $leave[0]['reason'] . " ( " . date('H:i', strtotime($leave[0]['leave_from'])) . " - " . date('H:i', strtotime($leave[0]['leave_to'])) . " )";                                            }                                            else if ($leave_type == 5) {                                                if ($leave[0]['type'] != "")                                                    echo $leave[0]['type'] . " ( " . date('d-m-Y H:i', strtotime($leave[0]['leave_from'])) . " - " . date('d-m-Y H:i', strtotime($leave[0]['leave_to'])) . " )";                                                else                                                    echo $leave[0]['reason'] . " ( " . date('d-m-Y H:i', strtotime($leave[0]['leave_from'])) . " - " . date('d-m-Y H:i', strtotime($leave[0]['leave_to'])) . " )";                                            }                                        }                                        ?>                                    </td>                                    <td class="center random_check"><?php                                $data = array(                                    'type' => 'checkbox',                                    'class' => 'generate-random',                                    'checked' => FALSE                                );                                $tr_class = "";                                if (($leave_type != "" && $leave_type != 1 && $leave_type != 3)) {                                    $data["class"] = "generate-random no_attendance";                                }                                echo form_checkbox($data);                                        ?>                                    </td>                                <?php } ?>                            <?php } ?>                        </tr>                    </tbody>                </table>            </div>            <div class="action-btn-align">                <?php                $data = array(                    'id' => 'save_button',                    'name' => 'save',                    'value' => 'Save',                    'class' => 'btn btn-success border4 submit',                    'title' => 'Save'                );                echo form_submit($data);                ?>                <a href="<?= $this->config->item('base_url') . "attendance/daily_attendance" ?>" title="Cancel" ><input type="button" class="btn btn-danger border4" value="Cancel" /></a>            </div>        </div>    </div></div><!-- Hidden values for jquery add attendance --><input type="hidden" id="attendance_threshold" value='<?php echo (isset($attendance_threshold[0]["value"])) ? $attendance_threshold[0]["value"] : ""; ?>'/><!-- Hidden values for jquery add attendance --><?phpfor ($k = 0; $k < count($filter["user_id"]); $k++) {    $day_value = $filter["start_date"];    ?>    <div class="modal fade" id="apply_leave<?= $k ?>" style="left:30%;width:1050px;">        <div class="modal-dialog">            <div class="modal-content">                <div class="modal-header">                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                    <h4 class="modal-title">Apply Leave</h4>                </div>                <div class="modal-body">                    <?php                    $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');                    echo form_open('', $attributes);                    ?>                    <table class="table table-bordered" width="100%">                        <thead>                        <th>Duration Type </th>                        <th class="half_option" >                            Session                        </th>                        <th class="date">Date</th>                        <th class="leave_from">Leave From</th>                        <th class="leave_to">Leave To</th>                        <th>Reason</th>                        <th  class="lop_td">LOP</th>                        <th>Status</th>                        <th class="leave_type">Leave Type</th>                        </thead>                        <tbody>                            <tr>                                <td class="center"><?php                if (isset($available[$filter["user_id"][$k]]['comp_off'])) {                    if ($available[$filter["user_id"][$k]]["comp_off"] > 0) {                        $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave", 4 => "Comp off");                        if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {                            if ($enable_earned_leave[0]["value"] == 1) {                                if ($available[$filter["user_id"][$k]]['available_earned_leave'] != NULL && $available[$filter["user_id"][$k]]['available_earned_leave'] > 0)                                    $options[6] = "Earned leave";                            }                        }                    }                    else {                        $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave");                        if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {                            if ($enable_earned_leave[0]["value"] == 1) {                                if ($available[$filter["user_id"][$k]]['available_earned_leave'] != NULL && $available[$filter["user_id"][$k]]['available_earned_leave'] > 0)                                    $options[6] = "Earned leave";                            }                        }                    }                }                else {                    $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave");                    if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {                        if ($enable_earned_leave[0]["value"] == 1) {                            if ($available[$filter["user_id"][$k]]['available_earned_leave'] != NULL && $available[$filter["user_id"][$k]]['available_earned_leave'] > 0)                                $options[6] = "Earned leave";                        }                    }                }                echo form_dropdown('leave[leave_type][' . $k . ']', $options, '', 'class="required input-medium type_select"');                    ?></td>                                <td class="half_option center">                                    <?php                                    $options = array("" => "Select", 1 => "Session 1", 2 => "Session 2");                                    echo form_dropdown('leave[session][' . $k . ']', $options, '', 'class="input-medium"');                                    ?>                                    </th>                                <td class="center date" ><?php                                $data = array(                                    'name' => 'leave[date][' . $k . ']',                                    'class' => 'required input-small datepicker',                                    'value' => date("d-m-Y", strtotime($day_value))                                );                                echo form_input($data);                                    ?>                                </td>                                <td class="center leave_from" ><?php                                $data = array(                                    'name' => 'leave[leave_from][' . $k . ']',                                    'class' => 'required datetimepicker input-small leave_from1',                                    'value' => date("d-m-Y", strtotime($day_value))                                );                                echo form_input($data);                                    ?>                                </td>                                <td class="center leave_to"><?php                                $data = array(                                    'name' => 'leave[leave_to][' . $k . ']',                                    'class' => 'required datetimepicker input-small leave_to1'                                );                                echo form_input($data);                                    ?>                                </td>                                <td class="center"><?php                                $data = array(                                    'name' => 'leave[reason][' . $k . ']',                                    'class' => 'required'                                );                                echo form_input($data);                                    ?>                                </td>                                <td class="center lop_td">                                    <?php                                    $cheked_status = FALSE;                                    $data = array(                                        'name' => 'leave[lop][' . $k . ']',                                        'value' => 1,                                        'checked' => $cheked_status                                    );                                    echo form_checkbox($data);                                    ?>                                </td>                                <td class="center">                                    <?php                                    $options = array("" => "Select", "0" => "New", 1 => "Approved", 2 => "Reject", 3 => "Hold");                                    echo form_dropdown('leave[approved][' . $k . ']', $options, "", 'class="required input-medium" id="type_select"');                                    /* $approved_status = FALSE;                                      $data = array(                                      'name' =>'leave[approved]['.$k.']',                                      'value' =>1,                                      'checked'=>$approved_status                                      );                                      echo form_checkbox($data); */                                    ?>                                </td>                                <td class="center leave_type">                                    <?php                                    $checked_status1 = FALSE;                                    $checked_status2 = FALSE;                                    $data = array(                                        'name' => 'leave[type][' . $k . ']',                                        'value' => 1,                                        'checked' => $checked_status1,                                        'type' => 'radio'                                    );                                    echo form_checkbox($data) . " Sick Leave";                                    $data = array(                                        'name' => 'leave[type][' . $k . ']',                                        'value' => 2,                                        'checked' => $checked_status2,                                        'type' => 'radio'                                    );                                    echo "<br>" . form_checkbox($data) . " Casual Leave";                                    ?>                                </td>                                <?php echo form_hidden("leave_user_id[" . $k . "]", $filter["user_id"][$k]); ?>                                <?php                                //  $admin_id = $this->user_auth->get_user_id();                                //  echo form_hidden("admin_id", $admin_id);                                ?>                            </tr>                        </tbody>                    </table>                </div>                <div class="modal-footer">                    <span><input type="button" value="Close"  class='btn btn-rounded btn-danger close' style="opacity: 0.88;" title="Close"/></span>                    <span><input type="submit" value="Apply" name="apply" class='btn btn-rounded btn-info' title="Apply"/></span>                </div>            </div><!-- /.modal-content -->        </div><!-- /.modal-dialog -->    </div><!-- /.modal -->    <?php}function dateTimeDiff($date1, $date2) {    $alt_diff = new stdClass();    $alt_diff->y = floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24 * 365));    $alt_diff->m = floor((floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365)) / 30);    $alt_diff->d = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));    $alt_diff->h = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60)) - ($alt_diff->y * 365 * 24) - ($alt_diff->m * 30 * 24 ) - ($alt_diff->d * 24));    $alt_diff->i = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60)) - ($alt_diff->y * 365 * 24 * 60) - ($alt_diff->m * 30 * 24 * 60) - ($alt_diff->d * 24 * 60) - ($alt_diff->h * 60));    $alt_diff->s = floor(floor(abs($date1->format('U') - $date2->format('U'))) - ($alt_diff->y * 365 * 24 * 60 * 60) - ($alt_diff->m * 30 * 24 * 60 * 60) - ($alt_diff->d * 24 * 60 * 60) - ($alt_diff->h * 60 * 60) - ($alt_diff->i * 60));    $alt_diff->invert = (($date1->format('U') - $date2->format('U')) > 0) ? 0 : 1;    return $alt_diff;}?><script type="text/javascript">    $(document).ready(function () {        var enter_count = "<?= $enter_count ?>";        //alert(enter_count);        if (enter_count == 0)        {            $(".random_check").hide();            $("#save_button").hide();            $(".formwrapper").hide();        } else        {            $(".random_check").show();            $("#save_button").show();            $(".formwrapper").show();        }<?phpif (isset($ot_enable)) {    if ($ot_enable == 0) {        ?>                $(".ot_class").hide();                $(".colspan_td").attr("colspan", 5);        <?php    }}?>    });    function removejscssfile(filename, filetype) {        var targetelement = (filetype == "js") ? "script" : (filetype == "css") ? "link" : "none" //determine element type to create nodelist from        var targetattr = (filetype == "js") ? "src" : (filetype == "css") ? "href" : "none" //determine corresponding attribute to test for        var allsuspects = document.getElementsByTagName(targetelement)        for (var i = allsuspects.length; i >= 0; i--) { //search backwards within nodelist for matching elements to remove            if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf(filename) != -1)                allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()        }    }    removejscssfile("<?= $theme_path; ?>/css/jquery-ui-timepicker-addon.min.css", "css")    removejscssfile("<?= $theme_path; ?>/js/jquery-ui-timepicker-addon.min.js", "js")</script><link rel="stylesheet" href="<?= $theme_path ?>/css/jquery-ui-theme.css" type="text/css" /><link rel="stylesheet" href="<?= $theme_path ?>/css/jquery.ui.timepicker.css" type="text/css" /><script type="text/ecmascript" src="<?= $theme_path ?>/js/jquery.ui.timepicker.js"></script>