<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<link rel="stylesheet" href="<?= $theme_path ?>/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="<?= $theme_path ?>/js/jquery.MultiFile.js"></script>
<script type="text/javascript" src="<?= $theme_path ?>/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?= $theme_path; ?>/js/department.js"></script>

<link href="<?= $theme_path ?>/css/print_page.css" rel="stylesheet">

<script type="text/javascript" src="<?= $theme_path ?>/js/attendance.js"></script>

<style type="text/css">

    .emp_info { text-align:center; }

    .emp_info span + span { font-weight: bold; padding-left:12%; }

</style>

<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>

<div class="contentinner">
    <div class="media mt--20">
        <h4 class="widgettitle">Attendance Add </h4>
    </div>
    <div class="widgetcontent">

        <?php
        $result = validation_errors();

        if (trim($result) != ""):
            ?>

            <div class="alert alert-error">

                <button data-dismiss="alert" class="close" type="button">&times;</button>

                <?php echo validation_errors(); ?>



            </div>

        <?php endif; ?>

        <?php
//		 $user_role = json_decode($roles[0]["roles"]);

        $this->load->model('masters/user_shift_model');

        $this->load->model('masters/shift_model');



        $days_array = array();

        $satur_holiday = 0;

        if (isset($saturday_holiday)) {

            if ($saturday_holiday == 1)
                $satur_holiday = 1;
        }

        if ($user_salary[0]["type"] == "monthly") {

            $starting_date = $start_date;

            $ending_date = $end_date;
        } else {

            $starting_date = $week_start_date;

            $ending_date = $week_end_date;
        }

        $s_date = date('d-m-Y', strtotime($starting_date));

        $std_dt = $ending_date . " 00:00:00";

        $exclude_date = new DateTime($std_dt . ' +1 day');

        $e_date = $exclude_date->format('d-m-Y');



        $start = new DateTime($s_date . ' 00:00:00');

        //Create a DateTime representation of the last day of the current month based off of "now"

        $end = new DateTime($e_date . ' 00:00:00');

        //Define our interval (1 Day)

        $interval = new DateInterval('P1D');

        //Setup a DatePeriod instance to iterate between the start and end date by the interval

        $period = new DatePeriod($start, $interval, $end);



        //Iterate over the DatePeriod instance

        $sunday = array();

        foreach ($period as $date) {

            //Make sure the day displayed is ONLY sunday.

            $days_array[] = $date->format('d-m-Y');
        }

        if (isset($leave) && !empty($leave)) {

            foreach ($leave as $lval) {

                $current_shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $lval["l_from"]);



                $current_shift = $this->shift_model->get_shift_regular_time_by_shift_id($current_shift_id[0]["shift_id"]);

                $start_hour = strtotime($current_shift[0]["from_time"]);

                $end_hour = strtotime($current_shift[0]["to_time"]);



                if ($lval["l_from"] == $lval["l_to"]) {

                    if ($start_hour >= $end_hour) {



                        $sdt = date("H:i", strtotime($lval["leave_from"]));

                        $edt = date("H:i", strtotime($lval["leave_to"]));



                        if (strtotime("00:00") < strtotime($sdt) && (strtotime($edt) < $end_hour)) {

                            $previous = date('Y-m-d', strtotime($lval["l_from"] . ' -1 day'));

                            $leave_arr[date('d-m-Y', strtotime($previous))] = $lval;
                        } else
                            $leave_arr[$lval["l_from"]] = $lval;
                    } else
                        $leave_arr[$lval["l_from"]] = $lval;
                }

                else if ($lval["type"] == "permission") {

                    if ($start_hour >= $end_hour) {



                        $sdt = date("H:i", strtotime($lval["leave_from"]));

                        $edt = date("H:i", strtotime($lval["leave_to"]));



                        if (strtotime("00:00") < strtotime($sdt) && (strtotime($edt) < $end_hour)) {

                            $previous = date('Y-m-d', strtotime($lval["l_from"] . ' -1 day'));

                            $leave_arr[date('d-m-Y', strtotime($previous))] = $lval;
                        } else
                            $leave_arr[$lval["l_from"]] = $lval;
                    } else
                        $leave_arr[$lval["l_from"]] = $lval;
                }

                else {



                    $start = $lval["l_from"];

                    $std_dt = date('Y-m-d', strtotime($lval["l_to"]));

                    $end_current = new DateTime($lval["l_to"] . ' 00:00:00');

                    $exclude_date = new DateTime($std_dt . ' +1 day');

                    $end = $exclude_date->format('d-m-Y');

                    $start = new DateTime($start . ' 00:00:00');

                    //Create a DateTime representation of the last day of the current month based off of "now"

                    $end = new DateTime($end . ' 00:00:00');

                    $interval_od = dateTimeDiff($start, $end_current);

                    if ($lval["type"] == "on-duty") {



                        if ($start_hour >= $end_hour) {

                            //Define our interval (1 Day)



                            $interval = new DateInterval('P1D');

                            //Setup a DatePeriod instance to iterate between the start and end date by the interval

                            $period = new DatePeriod($start, $interval, $end_current);



                            //Iterate over the DatePeriod instance

                            $lval["shift"] = "night";

                            $sunday = array();

                            foreach ($period as $date) {

                                //Make sure the day displayed is ONLY sunday.



                                $leave_arr[$date->format('d-m-Y')] = $lval;
                            }
                        } else {



                            $interval = new DateInterval('P1D');

                            //Setup a DatePeriod instance to iterate between the start and end date by the interval

                            $period = new DatePeriod($start, $interval, $end);

                            $lval["shift"] = "day";

                            //Iterate over the DatePeriod instance

                            $sunday = array();

                            foreach ($period as $date) {

                                //Make sure the day displayed is ONLY sunday.



                                $leave_arr[$date->format('d-m-Y')] = $lval;
                            }
                        }
                    } else {

                        //Define our interval (1 Day)

                        $interval = new DateInterval('P1D');

                        //Setup a DatePeriod instance to iterate between the start and end date by the interval

                        $period = new DatePeriod($start, $interval, $end);



                        //Iterate over the DatePeriod instance

                        $sunday = array();

                        foreach ($period as $date) {

                            //Make sure the day displayed is ONLY sunday.



                            $leave_arr[$date->format('d-m-Y')] = $lval;
                        }
                    }
                }
            }
        }

        //$this->pre_print->view($leave_arr);

        $holi_arr = array();

        if (isset($holidays) && !empty($holidays)) {

            foreach ($holidays as $hval) {

                if ($hval["holiday_from"] == $hval["holiday_to"])
                    $holi_arr[$hval["h_from"]] = $hval;

                else {

                    $start = $hval["h_from"];

                    $std_dt = date('Y-m-d', strtotime($hval["h_to"]));

                    $exclude_date = new DateTime($std_dt . ' +1 day');

                    $end = $exclude_date->format('d-m-Y');

                    $start = new DateTime($std_dt . ' 00:00:00');

                    //Create a DateTime representation of the last day of the current month based off of "now"

                    $end = new DateTime($end . ' 00:00:00');

                    //Define our interval (1 Day)

                    $interval = new DateInterval('P1D');

                    //Setup a DatePeriod instance to iterate between the start and end date by the interval

                    $period = new DatePeriod($start, $interval, $end);



                    //Iterate over the DatePeriod instance

                    $sunday = array();

                    foreach ($period as $date) {

                        //Make sure the day displayed is ONLY sunday.

                        $holi_arr[$date->format('d-m-Y')] = $hval;
                    }
                }
            }
        }



        $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');



        echo form_open('', $attributes);

        $joined_date = explode("-", $doj[0]["date"]);

        //echo $start_date;
        //echo $end_date;
        //print_r($days_array);



        $user_days = array();

        if (isset($days_array) && !empty($days_array)) {

            foreach ($days_array as $d_val) {

                if (strtotime($d_val) >= strtotime($doj[0]["date"]) && strtotime($d_val) < strtotime(date('d-m-Y')))
                    $user_days[] = $d_val;
            }
        }
        ?>
        <div class="panel-body mt-top5">
            <h4 class="emp_info">

                <span class=""><span class="emp_title">Employee Name</span> :

                    <?php echo $name[0]['first_name']; ?>&nbsp;<?php echo $name[0]['last_name']; ?></span>

                <span class=""><span class="emp_title">Department</span> : <?php echo $dept[0]['dep_name']; ?></span>

                <span class=""><span class="emp_title">Designation</span> : <?php echo $dept[0]['des_name']; ?></span>

            </h4>

            <table width="100%" border="0">



                <tbody><tr>



                        <td>

                            <p>

                                <?php echo form_label('Select Year'); ?>

                                <span class="field">



                                    <?php
                                    $options = array('' => 'Select Year');





                                    $i = $joined_date[0];

                                    if ($month == 1) {

                                        if ($joined_date[2] < $month_starting_date)
                                            $i = $joined_date[0] - 1;
                                    }

                                    for (; $i <= date('Y'); $i++) {

                                        $options[$i] = $i;
                                    }



                                    $default = $year;



                                    echo form_dropdown('year', $options, $default, 'id="year_select"');
                                    ?>

                                </span>

                            </p>

                        </td>

                        <td>

                            <p>

                                <?php echo form_label('Select Month'); ?>

                                <span class="field">

                                    <?php
                                    //echo date('m');

                                    $month_arr = array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec");

                                    $options = array('' => 'Select Month');
                                    $default = $month;



                                    if ($year == $joined_date[0] && $year == date('Y')) {

                                        if ($joined_date[2] < $month_starting_date)
                                            $i = $joined_date[1] - 2;
                                        else
                                            $i = $joined_date[1] - 1;

                                        for (; $i < date('m'); $i++) {

                                            if (isset($month_arr[$i]))
                                                $options[$i + 1] = $month_arr[$i];
                                        }
                                    }

                                    else if ($year == $joined_date[0]) {

                                        if ($joined_date[2] < $month_starting_date)
                                            $i = $joined_date[1] - 2;
                                        else
                                            $i = $joined_date[1] - 1;

                                        for (; $i < 12; $i++) {

                                            $options[$i + 1] = $month_arr[$i];
                                        }
                                    } else if ($year == date('Y')) {



                                        for ($i = 0; $i < date('m'); $i++) {

                                            $options[$i + 1] = $month_arr[$i];
                                        }
                                    } else {

                                        for ($i = 0; $i < 12; $i++) {

                                            $options[$i + 1] = $month_arr[$i];
                                        }
                                    }

                                    $default = $month;



                                    //print_r($default);
                                    //$options[$default] = $month_arr[$default-1];

                                    echo form_dropdown('month', $options, $default, 'id="month_select"');
                                    ?>



                                </span>

                            </p>

                        </td>

                        <td width="3%">&nbsp;</td>

                        <td><input type="hidden" name="go" value="Go" id="go" class="btn btn-warning border4"><?php
                            $data = array(
                                'name' => 'go',
                                'class' => 'btn btn-warning border4',
                                'value' => "Go",
                                'id' => 'go',
                            );

                            echo form_submit($data);
                            ?>
                            <!--<input type="submit" name="go" value="Go" id="go" class="btn btn-warning border4">-->
                        </td>

                    </tr>

                </tbody></table>


        </div>


        <input type="hidden" name="start_date" id="start_date" value="<?= $start_date ?>">

        <input type="hidden" name="end_date" id="end_date" value="<?= $end_date ?>">

        <input type="hidden" name="month_start_date" id="month_start_date" value="<?= $month_start_date ?>">

        <input type="hidden" name="month_end_date" id="month_end_date" value="<?= $month_end_date ?>">

        <input type="hidden"  id="month_starting_date" value="<?= $month_starting_date ?>">

        <input type="hidden" id="week_starting_day" value="<?= $week_starting_day ?>">

        <input type="hidden" id="doj" value="<?= $doj[0]["date"] ?>">

        <?php
        $month_val = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

        if (empty($attendance)):
            ?>
            <div class="panel-body mt-top5">
                <div class="scroll_bar">

                    <table class="table table-bordered">

                        <caption><b> <?php
                                if (isset($year)) {

                                    echo $month_val[$month - 1] . " " . $year;

                                    if ($year == date('Y')) {

                                        if ($month == date('m'))
                                            $days = date('d');
                                        else
                                            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                    } else
                                        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                }

                                else {

                                    echo date('F') . " " . date('Y');



                                    $days = date('d');



                                    $month = date('m');



                                    $year = date('Y');
                                }
                                ?></b>

                            <span style="float:right;">

                                <span style="background-color:#F2DCE6;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Holiday

                                <span style="background-color:#99CCFF;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Full Day Leave

                                <span style="background-color:#CCCCCC;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Half Day Leave

                                <span style="background-color:#FFCC66;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Permission

                                <span style="background-color:#CC99CC;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Compoff

                                <span style="background-color:#CCFFFF;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - LOP

                                <span style="background-color:#99CC99;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - On-duty

                                <span style="background-color:#D5D1A4;" class="btn-rounded">&nbsp;&nbsp;&nbsp;&nbsp;</span> - Earned Leave

                            </span>

                            <span class="formwrapper" style="float:right;">

                                <input type="checkbox" name="radiofield" id="inc_br1" class="inc_session" />Break 1 &nbsp; &nbsp;

                                <input type="checkbox" name="radiofield" id="inc_lunch" class="inc_session"/>Lunch &nbsp; &nbsp;

                                <input type="checkbox" name="radiofield" id="inc_br2" class="inc_session"/>Break 2 &nbsp; &nbsp;

                            </span>

                        </caption>

                        <thead>

                            <tr>



                                <?php
                                //print_r($users);
                                //echo  $department[0]["ot_applicable"];
                                $data = array(
                                    'type' => 'checkbox',
                                    'class' => 'generate-random-all',
                                    'checked' => FALSE
                                );
                                $head = array("S No", "Date", "Day", "In Time - Out Time", "Break / Lunch", "Over Time", "Total Hours", "Apply for Leave", form_checkbox($data));

                                //print_r($head);

                                foreach ($head as $ele) {

                                    if ($ele == "Over Time")
                                        echo "<th  class='ot_class'>" . $ele . "</th>";
                                    else
                                        echo "<th>" . $ele . "</th>";
                                }
                                ?>
                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            if (isset($shift_first) && !empty($shift_first)) {

                                $ot_enable = 0;

                                for ($k = 0; $k <= count($user_days) - 1; $k++) {
                                    ?>

                                    <?php
                                    $holiday = 0;

                                    $start_time = 0;

                                    $saturday = 0;

                                    $day_value = $user_days[$k];

                                    $split_day = explode("-", $day_value);

                                    $current_day = ltrim($split_day[0], '0');

                                    $leave_type = '';

                                    $on_duty_text = "";

                                    $regular_time_val = 0;

                                    $breaktimediff = 0;

                                    $current_shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $day_value);



                                    $current_shift = $this->shift_model->get_shift_details_by_shift_id($current_shift_id[0]["shift_id"]);



                                    if (isset($current_shift) && !empty($current_shift)) {

                                        $shift = array();

                                        foreach ($current_shift as $key => $value) {



                                            $shift[$value["type"]][] = $value;
                                        }
                                    }

                                    //$this->pre_print->view($shift);

                                    $overtimestart = 0;

                                    $overtimeend = 0;

                                    if (isset($shift["overtimestart"][0])) {



                                        $overtimestart = $shift["overtimestart"][0]["from_time"];

                                        $overtimeend = $shift["overtimestart"][0]["to_time"];

                                        $ot_enable = 1;
                                    }

                                    if (isset($shift["regular"][0])) {



                                        $reg_st = explode(':', $shift["regular"][0]["from_time"]);

                                        $reg_et = explode(':', $shift["regular"][0]["to_time"]);

                                        $shift_in_time = $day_value . " " . $shift["regular"][0]["from_time"];

                                        if ($reg_st[0] > 12 && $reg_et[0] < 12) {

                                            $next_day = new DateTime(date('d-m-Y H:i:s') . ' + 1 day');

                                            $date8 = new DateTime(date('d-m-Y') . " " . $shift["regular"][0]["from_time"]);

                                            $date9 = new DateTime($next_day->format('d-m-Y') . " " . $shift["regular"][0]["to_time"]);
                                        } else {

                                            $date8 = new DateTime(date('d-m-Y') . " " . $shift["regular"][0]["from_time"]);

                                            $date9 = new DateTime(date('d-m-Y') . " " . $shift["regular"][0]["to_time"]);
                                        }

                                        $start_time = $shift["regular"][0]["from_time"];

                                        $shift_end = $shift["regular"][0]["to_time"];

                                        $shift_start = date("d-m-Y H:i:s", strtotime($shift_in_time) - 21600);

                                        //print_r($date8);

                                        $regular_time = dateTimeDiff($date8, $date9);
                                    }

                                    if (isset($shift["break"])) {

                                        foreach ($shift["break"] as $sh) {

                                            $reg_st = explode(':', $sh["from_time"]);

                                            $reg_et = explode(':', $sh["to_time"]);

                                            if ($reg_st[0] > 12 && $reg_et[0] < 12) {

                                                $next_day = new DateTime(date('d-m-Y H:i:s') . ' + 1 day');

                                                $date8 = new DateTime(date('d-m-Y') . " " . $sh["from_time"]);

                                                $date9 = new DateTime($next_day->format('d-m-Y') . " " . $sh["to_time"]);
                                            } else {

                                                $date8 = new DateTime(date('d-m-Y') . " " . $sh["from_time"]);

                                                $date9 = new DateTime(date('d-m-Y') . " " . $sh["to_time"]);
                                            }

                                            $inter = dateTimeDiff($date8, $date9);

                                            if ($inter->h > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->h) * 60;
                                            }

                                            if ($inter->i > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->i);
                                            }

                                            if ($inter->s > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->s / 60);
                                            }
                                        }
                                    }



                                    if (isset($shift["lunch"])) {

                                        foreach ($shift["lunch"] as $sh) {

                                            $reg_st = explode(':', $sh["from_time"]);

                                            $reg_et = explode(':', $sh["to_time"]);

                                            if ($reg_st[0] > 12 && $reg_et[0] < 12) {

                                                $next_day = new DateTime(date('d-m-Y H:i:s') . ' + 1 day');

                                                $date10 = new DateTime(date('d-m-Y') . " " . $sh["from_time"]);

                                                $date11 = new DateTime($next_day->format('d-m-Y') . " " . $sh["to_time"]);
                                            } else {

                                                $date10 = new DateTime(date('d-m-Y') . " " . $sh["from_time"]);

                                                $date11 = new DateTime(date('d-m-Y') . " " . $sh["to_time"]);
                                            }

                                            $inter = dateTimeDiff($date10, $date11);

                                            //print_r($inter);

                                            if ($inter->h > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->h) * 60;
                                            }

                                            if ($inter->i > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->i);
                                            }

                                            if ($inter->s > 0) {

                                                $breaktimediff = $breaktimediff + ($inter->s / 60);
                                            }
                                        }
                                    }

                                    $regular_time_val = ($regular_time->h * 60) + $regular_time->i - $breaktimediff - $threshold[0]['value'];

                                    if ($regular_time->s > 0) {

                                        $regular_time_val = $regular_time_val + ($regular_time->s / 60);
                                    }

                                    $res = explode(':', $start_time);

                                    if (!isset($res[1]))
                                        $res[1] = 0;



                                    $shift_start_time = $res[0] * 60 + $res[1] + $threshold[0]['value'];

                                    if (isset($res[2]) && $res[2] > 0) {

                                        $shift_start_time = $shift_start_time + ($res[2] / 60);
                                    }

                                    //$this->pre_print->view($shift);

                                    $sun = date('l', strtotime($day_value));

                                    $style = "";



                                    if ($sun == "Sunday") {

                                        $style_class = $style = "background-color:#F2DCE6;";
                                    }

                                    if ($satur_holiday == 1) {

                                        if ($sun == "Saturday") {

                                            $style_class = $style = "background-color:#F2DCE6;";

                                            $saturday = 1;
                                        }
                                    }

                                    if (isset($holi_arr[$day_value]) && !empty($holi_arr[$day_value])) {

                                        $holiday = 1;

                                        $style_class = $style = "background-color:#F2DCE6;";
                                    }

                                    if (isset($leave_arr[$day_value]) && !empty($leave_arr[$day_value])) {

                                        if ($leave_arr[$day_value]['type'] == "sick leave" || $leave_arr[$day_value]['type'] == "casual leave" || $leave_arr[$day_value]['type'] == "earned leave") {



                                            $date1 = new DateTime(date('d-m-Y H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                            $date2 = new DateTime(date('d-m-Y H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                            $interval = dateTimeDiff($date1, $date2);

                                            if ($date1 == $date2) {

                                                $leave_type = 2;
                                            } else {

                                                if ($interval->h > 0) {

                                                    $leave_type = 1;
                                                } else if ($interval->h == 0) {

                                                    $leave_type = 2;
                                                }
                                            }
                                        } else if ($leave_arr[$day_value]['type'] == "permission") {

                                            $leave_type = 3;
                                        } else if ($leave_arr[$day_value]['type'] == "compoff") {

                                            $leave_type = 4;
                                        } else if ($leave_arr[$day_value]['type'] == "on-duty") {

                                            //echo "enter";

                                            $leave_type = 5;
                                        }
                                    }

                                    if ($leave_type == 4 || $leave_type == 2) {

                                        if ($leave_arr[$day_value]['lop'] == 1) {

                                            $style_class = "background-color:#CCFFFF";
                                        } else {

                                            if ($leave_arr[$day_value]['type'] == "earned leave")
                                                $style_class = "background-color:#D5D1A4;";
                                            else
                                                $style_class = "background-color:#99CCFF;";

                                            if ($leave_type == 4)
                                                $style_class = "background-color:#CC99CC;";
                                        }



                                        //	echo $style_class;

                                        echo "<tr><td class='center' style='" . $style_class . "' >" . ($k + 1) . "</td>

									<td class='center' style='" . $style_class . "' >" . $day_value . "</td>

									<td class='center'  style='" . $style_class . "'>" . $sun . "</td>

								<td colspan='6' style='" . $style_class . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";
                                    }

                                    else {

                                        //echo $leave_type;



                                        if ($leave_type == 1)
                                            $style = "background-color:#CCCCCC;";

                                        else if ($leave_type == 3)
                                            $style = "background-color:#FFCC66;";

                                        else if ($leave_type == 5) {

                                            $style = "background-color:#99CC99;";

                                            $od_dt = $day_value . " 00:00:00";

                                            $exclude_date = new DateTime($od_dt . ' +1 day');

                                            $next_day = $exclude_date->format('d-m-Y');

                                            if ($leave_arr[$day_value]["l_from"] == $day_value && $leave_arr[$day_value]["l_to"] == $day_value) {

                                                $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                $duty_start_time = $ds[0] * 60 + $ds[1];

                                                if (isset($ds[2]) && $ds[2] > 0) {

                                                    $duty_start_time = $duty_start_time + ($ds[2] / 60);
                                                }

                                                $ds_to = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                $duty_end_time = $ds_to[0] * 60 + $ds_to[1];

                                                if (isset($ds_to[2]) && $ds_to[2] > 0) {

                                                    $duty_end_time = $duty_end_time + ($ds_to[2] / 60);
                                                }

                                                $d_from = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                $d_to = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                $d_inter = dateTimeDiff($d_from, $d_to);

                                                $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                //echo $duty_hours;

                                                $duty_hours = $regular_time_val - $duty_hours;

                                                if ($duty_hours >= $regular_time_val) {



                                                    echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

													<td class='center' style='" . $style . "' >" . $day_value . "</td>

													<td class='center'  style='" . $style . "'>" . $sun . "</td>

												<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";





                                                    continue;
                                                } else {

                                                    if ($shift_start_time > $duty_start_time)
                                                        $on_duty = 1;

                                                    $on_duty_text = date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to']));
                                                }

                                                //goto show_attendance;
                                            }

                                            elseif ($leave_arr[$day_value]["l_from"] == $next_day && $leave_arr[$day_value]["l_to"] == $next_day) {

                                                $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                $duty_start_time = $ds[0] * 60 + $ds[1];

                                                if (isset($ds[2]) && $ds[2] > 0) {

                                                    $duty_start_time = $duty_start_time + ($ds[2] / 60);
                                                }

                                                $ds_to = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                $duty_end_time = $ds_to[0] * 60 + $ds_to[1];

                                                if (isset($ds_to[2]) && $ds_to[2] > 0) {

                                                    $duty_end_time = $duty_end_time + ($ds_to[2] / 60);
                                                }

                                                $d_from = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                $d_to = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                $d_inter = dateTimeDiff($d_from, $d_to);

                                                $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                //echo $duty_hours;

                                                $duty_hours = $regular_time_val - $duty_hours;

                                                if ($duty_hours >= $regular_time_val) {



                                                    echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

												<td class='center' style='" . $style . "' >" . $day_value . "</td>

												<td class='center'  style='" . $style . "'>" . $sun . "</td>

											<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";





                                                    continue;
                                                } else {

                                                    if ($shift_start_time > $duty_start_time)
                                                        $on_duty = 1;

                                                    $on_duty_text = date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to']));
                                                }

                                                //goto show_attendance;
                                            }

                                            elseif ($leave_arr[$day_value]["shift"] == "day") {

                                                if ($leave_arr[$day_value]["l_from"] == $day_value && $leave_arr[$day_value]["l_to"] != $day_value) {

                                                    //echo "enter";

                                                    $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                    $duty_start_time = $ds[0] * 60 + $ds[1];

                                                    if (isset($ds[2]) && $ds[2] > 0) {

                                                        $duty_start_time = $duty_start_time + ($ds[2] / 60);
                                                    }

                                                    if ($shift_start_time < $duty_start_time) {



                                                        $d_from = new DateTime($day_value . " " . $start_time);

                                                        $d_to = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                        $d_inter = dateTimeDiff($d_from, $d_to);

                                                        $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                        //	print_r($d_inter);

                                                        $on_duty_text = date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . $shift_end;

                                                        //goto show_attendance;
                                                    } else if ($shift_start_time >= $duty_start_time) {



                                                        echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

											<td class='center' style='" . $style . "' >" . $day_value . "</td>

											<td class='center'  style='" . $style . "'>" . $sun . "</td>

											<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";



                                                        continue;
                                                    }
                                                } else if ($leave_arr[$day_value]["l_from"] != $day_value && $leave_arr[$day_value]["l_to"] != $day_value) {



                                                    echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

										<td class='center' style='" . $style . "' >" . $day_value . "</td>

										<td class='center'  style='" . $style . "'>" . $sun . "</td>

										<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";

                                                    continue;
                                                } elseif ($leave_arr[$day_value]["l_from"] != $day_value && $leave_arr[$day_value]["l_to"] == $day_value) {

                                                    $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                    $duty_end_time = $ds[0] * 60 + $ds[1];

                                                    if (isset($ds[2]) && $ds[2] > 0) {

                                                        $duty_end_time = $duty_end_time + ($ds[2] / 60);
                                                    }

                                                    if ($shift_start_time < $duty_end_time) {





                                                        $d_from = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                        $d_to = new DateTime($day_value . " " . $shift_end);

                                                        $d_inter = dateTimeDiff($d_from, $d_to);

                                                        $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                        $on_duty = 1;

                                                        $on_duty_text = $start_time . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to']));

                                                        //print_r($d_inter);
                                                        //goto show_attendance;
                                                    } else if ($shift_start_time >= $duty_end_time) {

                                                        $duty_hours = 0;

                                                        //goto show_attendance;
                                                    }
                                                }
                                            } elseif ($leave_arr[$day_value]["shift"] == "night") {



                                                if ($leave_arr[$day_value]["l_from"] == $day_value && $leave_arr[$day_value]["l_to"] == $next_day) {

                                                    $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                    $duty_start_time = $ds[0] * 60 + $ds[1];

                                                    if (isset($ds[2]) && $ds[2] > 0) {

                                                        $duty_start_time = $duty_start_time + ($ds[2] / 60);
                                                    }

                                                    $ds_to = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                    $duty_end_time = $ds_to[0] * 60 + $ds_to[1];

                                                    if (isset($ds_to[2]) && $ds_to[2] > 0) {

                                                        $duty_end_time = $duty_end_time + ($ds_to[2] / 60);
                                                    }

                                                    $d_from = new DateTime($day_value . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                    $d_to = new DateTime($next_day . " " . date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                    $d_inter = dateTimeDiff($d_from, $d_to);

                                                    $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                    //echo $duty_hours;

                                                    $duty_hours = $regular_time_val - $duty_hours;

                                                    if ($duty_hours >= $regular_time_val) {



                                                        echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

												<td class='center' style='" . $style . "' >" . $day_value . "</td>

												<td class='center'  style='" . $style . "'>" . $sun . "</td>

											<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";



                                                        continue;
                                                    } else {

                                                        if ($shift_start_time > $duty_start_time)
                                                            $on_duty = 1;

                                                        $on_duty_text = date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to']));
                                                    }

                                                    //goto show_attendance;
                                                }

                                                else if ($leave_arr[$day_value]["l_from"] == $day_value && $leave_arr[$day_value]["l_to"] != $day_value) {

                                                    $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"])));

                                                    $duty_start_time = $ds[0] * 60 + $ds[1];

                                                    if (isset($ds[2]) && $ds[2] > 0) {

                                                        $duty_start_time = $duty_start_time + ($ds[2] / 60);
                                                    }

                                                    //print_r($ds);
                                                    //echo $shift_start_time."<br>";
                                                    //echo $duty_start_time."<br>";

                                                    if ($shift_start_time < $duty_start_time) {
                                                        $d_from = new DateTime($day_value . " " . $start_time);

                                                        $d_end = date('H:i:s', strtotime($leave_arr[$day_value]["leave_from"]));

                                                        if (strtotime($d_end) <= strtotime($start_time))
                                                            $d_to = new DateTime($day_value . " " . $d_end);
                                                        else
                                                            $d_to = new DateTime($next_day . " " . $d_end);

                                                        $d_inter = dateTimeDiff($d_from, $d_to);

                                                        $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                        //print_r($d_inter);

                                                        $on_duty_text = date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . $shift_end;
                                                    }

                                                    else if ($shift_start_time >= $duty_start_time) {

                                                        echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

												<td class='center' style='" . $style . "' >" . $day_value . "</td>

												<td class='center'  style='" . $style . "'>" . $sun . "</td>

											<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";



                                                        continue;
                                                    }
                                                } else if ($leave_arr[$day_value]["l_from"] != $day_value && $leave_arr[$day_value]["l_to"] != $day_value) {

                                                    if ($next_day == $leave_arr[$day_value]["l_to"]) {



                                                        $ds = explode(':', date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"])));

                                                        $duty_end_time = $ds[0] * 60 + $ds[1];

                                                        if (isset($ds[2]) && $ds[2] > 0) {

                                                            $duty_end_time = $duty_end_time + ($ds[2] / 60);
                                                        }

                                                        //echo $shift_start_time."<br>";
                                                        //echo $duty_end_time."<br>";

                                                        if ($shift_start_time >= $duty_end_time) {



                                                            $d_st = date('H:i:s', strtotime($leave_arr[$day_value]["leave_to"]));

                                                            $d_from = new DateTime($day_value . " " . $d_st);

                                                            if (strtotime($d_st) >= strtotime($shift_end))
                                                                $d_to = new DateTime($next_day . " " . $shift_end);
                                                            else
                                                                $d_to = new DateTime($day_value . " " . $shift_end);

                                                            $d_inter = dateTimeDiff($d_from, $d_to);

                                                            $duty_hours = $d_inter->h * 60 + $d_inter->i + $d_inter->i / 60;

                                                            $on_duty = 1;

                                                            $on_duty_text = $start_time . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to']));

                                                            //print_r($duty_hours);
                                                            //goto show_attendance;
                                                        }

                                                        else if ($shift_start_time < $duty_end_time) {

                                                            $duty_hours = 0;

                                                            //goto show_attendance;
                                                        }
                                                    } else {



                                                        echo "<tr><td class='center' style='" . $style . "' >" . ($k + 1) . "</td>

													<td class='center' style='" . $style . "' >" . $day_value . "</td>

													<td class='center'  style='" . $style . "'>" . $sun . "</td>

												<td colspan='6' style='" . $style . "' class='center colspan_td'>" . $leave_arr[$day_value]["reason"] . "</td>";





                                                        continue;
                                                    }
                                                }
                                            }
                                        }
                                        ?>

                                        <tr>
                                            <td class="center" style="<?= $style ?>"><?= $k + 1 ?></td>

                                            <td class="center" style="<?= $style ?>"><?php
                                                $data = array(
                                                    'name' => 'attendance[created][]',
                                                    'class' => 'required datepicker input-small',
                                                    'value' => $day_value,
                                                    ' readonly ' => 'readonly'
                                                );

                                                echo form_input($data);
                                                ?></td>

                                            <td class="center" style="<?= $style ?>"><?= $sun ?></td>

                                            <td class="center" style="<?= $style ?>">

                                                <?php
                                                $data = array(
                                                    'name' => 'attendance[in][]',
                                                    'class' => 'required timepicker input-small time_in',
                                                    'value' => set_value('attendance[in][]')
                                                );

                                                $data["shift_in_time"] = $shift["regular"][0]["from_time"];

                                                echo form_input($data);
                                                ?>



                                                <?php
                                                $data = array(
                                                    'name' => 'attendance[out][]',
                                                    'class' => 'required timepicker input-small valid time_out',
                                                    'value' => set_value('attendance[out][]')
                                                );



                                                $data["shift_out_time"] = $shift["regular"][0]["to_time"];



                                                if (isset($shift["overtimestart"][0])) {



                                                    $data["overtime_in"] = $shift["overtimestart"][0]["from_time"];

                                                    $data["overtime_out"] = $shift["overtimestart"][0]["to_time"];
                                                }

                                                echo form_input($data);
                                                ?>

                                            </td>
                                            <td class="center break_td" style="<?= $style ?>">
                                                <span class="break_to_clone">

                                                    <?php
                                                    $g = $k;

                                                    $data = array(
                                                        'name' => 'break[in_time][' . $g . '][]',
                                                        'class' => 'input-small break in_break',
                                                        'value' => set_value('break[in][' . $g . ']')
                                                    );

                                                    echo form_input($data);
                                                    ?>

                                                    <?php
                                                    $data = array(
                                                        'name' => 'break[out_time][' . $g . '][]',
                                                        'class' => 'input-small out_break',
                                                        'value' => set_value('break[out][[' . $g . ']')
                                                    );

                                                    echo form_input($data);
                                                    ?>

                                                </span>
                                                <?php if (isset($shift["lunch"][0])) { ?>

                                                    <input type="hidden" class="lunch_in" value="<?= $shift["lunch"][0]["from_time"] ?>" />

                                                    <input type="hidden" class="lunch_out" value="<?= $shift["lunch"][0]["to_time"] ?>" />

                                                <?php } ?>

                                                <?php if (isset($shift["break"][0])) { ?>

                                                    <input type="hidden" class="break_first_in" value="<?= $shift["break"][0]["from_time"] ?>" />

                                                    <input type="hidden" class="break_first_out" value="<?= $shift["break"][0]["to_time"] ?>" />

                                                <?php } ?>

                                                <?php if (isset($shift["break"][1])) { ?>

                                                    <input type="hidden" class="break_second_in" value="<?= $shift["break"][1]["from_time"] ?>" />
                                                    <input type="hidden" class="break_second_out" value="<?= $shift["break"][1]["to_time"] ?>" />

                                                <?php } ?>

                                                <a href="javascript:void(0);" class="btn btn-danger add_row" title="Add">+</a>

                                            </td>



                                            <td class="center overtime ot_class" style="<?= $style ?>"  >

                                                <span class="overtime_val"></span>

                                                <input type="hidden" class= "overtimestart" value="<?= $overtimestart ?>" />

                                                <input type="hidden" class="overtimeend" value="<?= $overtimeend ?>" />

                                            </td>



                                            <td class="center total_hours" style="<?= $style ?>">

                                                <span class="total_diff"></span>

                                                <input type="hidden" class="total" /></td>

                                            <td style="<?= $style ?>" class="center">

                                                <?php
                                                if ($leave_type == '' && $sun != "Sunday" && $saturday != 1) {

                                                    if (in_array("leave:apply_leave", $user_role)) {
                                                        ?>

                                                        <a href="javascript:void(0)" class="btn btn-rounded btn-info" data-toggle="modal" title="Apply leave" data-target="#apply_leave<?= $k ?>" >Apply Leave</a>

                                                        <?php
                                                    }
                                                } else {

                                                    if ($leave_type == 1)
                                                        echo $leave_arr[$day_value]['type'] . " ( " . date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to'])) . " )";

                                                    else if ($leave_type == 3) {

                                                        if ($leave_arr[$day_value]['type'] != "")
                                                            echo $leave_arr[$day_value]['type'] . " ( " . date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to'])) . " )";
                                                        else
                                                            echo $leave_arr[$day_value]['reason'] . " ( " . date('H:i', strtotime($leave_arr[$day_value]['leave_from'])) . " - " . date('H:i', strtotime($leave_arr[$day_value]['leave_to'])) . " )";
                                                    }

                                                    else if ($leave_type == 5) {



                                                        echo $leave_arr[$day_value]['type'] . " ( " . $on_duty_text . " )";
                                                    }
                                                }
                                                ?>

                                            </td>

                                            <td class="center"><?php
                                                $data = array(
                                                    'type' => 'checkbox',
                                                    'class' => 'generate-random',
                                                    'checked' => FALSE
                                                );





                                                $tr_class = "";

                                                if (($leave_type != "" && $leave_type != 1 && $leave_type != 3) || $sun == "Sunday" || $holiday == 1 || $saturday == 1) {



                                                    $data["class"] = "generate-random no_attendance";
                                                }



                                                echo form_checkbox($data);
                                                ?>

                                            </td>

                                        <?php } ?>

                                    </tr>

                                <?php } ?>

                                <?php
                            } else {
                                echo "<tr><td colspan='9'><div class='alert alert-error'>Shift Not assigned for this month</div></tr></tr>";
                            }

                            if (empty($user_days))
                                echo "<tr><td colspan='9'><div class='alert alert-error'>Company's startup month date not yet started</div></tr></tr>";
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>


            <div class="button_right_align action-btn-align" style="margin-top:7px;">

                <?php
                $data = array(
                    'name' => 'save',
                    'value' => 'Save',
                    'class' => 'btn btn-success border4 submit',
                    'title' => 'Save'
                );



                echo form_submit($data);
                ?>

                <a href="<?= $this->config->item('base_url') . "attendance/monthly_attendance" ?>" title="Cancel" ><input type="button" class="btn btn-danger border4" value="Cancel" /></a>



            </div>

        <?php endif; ?>



        <?php if (isset($attendance) && !empty($attendance)): ?>

            <div class="button_right_align action-btn-align">

                <a href="<?= $this->config->item('base_url') . "attendance/monthly_attendance" ?>" title="Cancel" ><input type="button" class="btn btn-danger border4" value="Cancel" /></a>    </div>

        <?php endif; ?>



    </div>

</div>

<!-- Hidden values for jquery add attendance -->

<input type="hidden" id="attendance_threshold" value='<?php echo (isset($attendance_threshold[0]["value"])) ? $attendance_threshold[0]["value"] : ""; ?>'/>

<!-- Hidden values for jquery add attendance -->



<?php
if (empty($attendance)):

    for ($k = 0; $k <= count($user_days) - 1; $k++) {



        $day_value = $user_days[$k];

        /* $split_day = explode("-",$day_value);

          $day_value = ltrim($split_day[0],'0'); */
        ?>

        <div class="modal fade" id="apply_leave<?= $k ?>" style="left:30%;width:1050px;">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">Apply Leave</h4>

                    </div>

                    <div class="modal-body">

                        <?php
                        $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');



                        echo form_open('', $attributes);
                        ?>

                        <table class="table table-bordered" width="100%">

                            <thead>

                            <th>Duration Type </th>
                            <th class="half_option" >

                                Session

                            </th>

                            <th class="date">Date</th>

                            <th class="leave_from">Leave From</th>

                            <th class="leave_to">Leave To</th>



                            <th>Reason</th>

                            <th class="lop_td">LOP</th>

                            <th>Status</th>

                            <th class="leave_type">Leave Type</th>



                            </thead>

                            <tbody>

                                <tr>

                                    <td class="center"><?php
                                        if (isset($available[0]['comp_off'])) {

                                            if ($available[0]["comp_off"] > 0) {

                                                $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave", 4 => "Comp off");

                                                if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {

                                                    if ($enable_earned_leave[0]["value"] == 1) {

                                                        if ($available[0]['available_earned_leave'] != NULL && $available[0]['available_earned_leave'] > 0)
                                                            $options[6] = "Earned leave";
                                                    }
                                                }
                                            }

                                            else {

                                                $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave");

                                                if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {

                                                    if ($enable_earned_leave[0]["value"] == 1) {

                                                        if ($available[0]['available_earned_leave'] != NULL && $available[0]['available_earned_leave'] > 0)
                                                            $options[6] = "Earned leave";
                                                    }
                                                }
                                            }
                                        }

                                        else {

                                            $options = array("" => "Select", 1 => "Half Day leave", 2 => "Full Day leave");

                                            if (isset($enable_earned_leave) && !empty($enable_earned_leave)) {

                                                if ($enable_earned_leave[0]["value"] == 1) {

                                                    if ($available[0]['available_earned_leave'] != NULL && $available[0]['available_earned_leave'] > 0)
                                                        $options[6] = "Earned leave";
                                                }
                                            }
                                        }

                                        echo form_dropdown('leave[leave_type][' . $k . ']', $options, '', 'class="required input-medium type_select"');
                                        ?></td>

                                    <td class="half_option center">

                                        <?php
                                        $options = array("" => "Select", 1 => "Session 1", 2 => "Session 2");



                                        echo form_dropdown('leave[session][' . $k . ']', $options, '', 'class="input-medium"');
                                        ?>

                                        </th>

                                    <td class="center date" ><?php
                                        $data = array(
                                            'name' => 'leave[date][' . $k . ']',
                                            'class' => 'required input-small datepicker',
                                            'value' => $day_value
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                    <td class="center leave_from" ><?php
                                        $data = array(
                                            'name' => 'leave[leave_from][' . $k . ']',
                                            'class' => 'required datetimepicker input-small leave_from1',
                                            'value' => $day_value
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                    <td class="center leave_to"><?php
                                        $data = array(
                                            'name' => 'leave[leave_to][' . $k . ']',
                                            'class' => 'required datetimepicker input-small leave_to1'
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>
                                    <td class="center"><?php
                                        $data = array(
                                            'name' => 'leave[reason][' . $k . ']',
                                            'class' => 'required'
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                    <td class="center lop_td">

                                        <?php
                                        $cheked_status = FALSE;



                                        $data = array(
                                            'name' => 'leave[lop][' . $k . ']',
                                            'value' => 1,
                                            'checked' => $cheked_status
                                        );

                                        echo form_checkbox($data);
                                        ?>

                                    </td>

                                    <td class="center">

                                        <?php
                                        $options = array("" => "Select", "0" => "New", 1 => "Approved", 2 => "Reject", 3 => "Hold");

                                        echo form_dropdown('leave[approved][' . $k . ']', $options, "", 'class="required input-medium" id="type_select"');
                                        ?>

                                    </td>

                                    <td class="center leave_type">



                                        <?php
                                        $checked_status1 = FALSE;

                                        $checked_status2 = FALSE;

                                        $data = array(
                                            'name' => 'leave[type][' . $k . ']',
                                            'value' => 1,
                                            'checked' => $checked_status1,
                                            'type' => 'radio'
                                        );

                                        echo form_checkbox($data) . " Sick Leave";

                                        $data = array(
                                            'name' => 'leave[type][' . $k . ']',
                                            'value' => 2,
                                            'checked' => $checked_status2,
                                            'type' => 'radio'
                                        );

                                        echo "<br>" . form_checkbox($data) . " Casual Leave";
                                        ?>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="modal-footer">

                        <input type="button" value="Close"  class='btn btn-rounded btn-danger close' style="opacity: 0.88;" title="Close"/>

                        <input type="submit" value="Apply" name="apply" class='btn btn-rounded btn-info' title="Apply"/>

                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <?php
    }

endif;

function dateTimeDiff($date1, $date2) {



    $alt_diff = new stdClass();

    $alt_diff->y = floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24 * 365));

    $alt_diff->m = floor((floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365)) / 30);

    $alt_diff->d = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60 * 24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));

    $alt_diff->h = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60 * 60)) - ($alt_diff->y * 365 * 24) - ($alt_diff->m * 30 * 24 ) - ($alt_diff->d * 24));

    $alt_diff->i = floor(floor(abs($date1->format('U') - $date2->format('U')) / (60)) - ($alt_diff->y * 365 * 24 * 60) - ($alt_diff->m * 30 * 24 * 60) - ($alt_diff->d * 24 * 60) - ($alt_diff->h * 60));

    $alt_diff->s = floor(floor(abs($date1->format('U') - $date2->format('U'))) - ($alt_diff->y * 365 * 24 * 60 * 60) - ($alt_diff->m * 30 * 24 * 60 * 60) - ($alt_diff->d * 24 * 60 * 60) - ($alt_diff->h * 60 * 60) - ($alt_diff->i * 60));

    $alt_diff->invert = (($date1->format('U') - $date2->format('U')) > 0) ? 0 : 1;
    return $alt_diff;
}
?>

<script type="text/javascript">

    $(document).ready(function () {
<?php if (isset($attendance) && !empty($attendance)) { ?>
            alert("Current Month attendance already added for this User. Go to attendance edit for modify the attendance details")
<?php } ?>
    });

</script>

<script type="text/javascript">

    $(document).ready(function () {

<?php
if (isset($ot_enable)) {

    if ($ot_enable == 0) {
        ?>

                $(".ot_class").hide();

                $(".colspan_td").attr("colspan", 5);

        <?php
    }
}
?>



    });

    function removejscssfile(filename, filetype) {

        var targetelement = (filetype == "js") ? "script" : (filetype == "css") ? "link" : "none" //determine element type to create nodelist from

        var targetattr = (filetype == "js") ? "src" : (filetype == "css") ? "href" : "none" //determine corresponding attribute to test for

        var allsuspects = document.getElementsByTagName(targetelement)

        for (var i = allsuspects.length; i >= 0; i--) { //search backwards within nodelist for matching elements to remove

            if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf(filename) != -1)
                allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()

        }

    }



    removejscssfile("<?= $theme_path; ?>/css/jquery-ui-timepicker-addon.min.css", "css")



    removejscssfile("<?= $theme_path; ?>/js/jquery-ui-timepicker-addon.min.js", "js")



</script>

<link rel="stylesheet" href="<?= $theme_path ?>/css/jquery-ui-theme.css" type="text/css" />

<link rel="stylesheet" href="<?= $theme_path ?>/css/jquery.ui.timepicker.css" type="text/css" />

<script type="text/ecmascript" src="<?= $theme_path ?>/js/jquery.ui.timepicker.js"></script>



