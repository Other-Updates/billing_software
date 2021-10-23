<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<style>
    .btn-xs {padding: 0px 3px 1px 4px !important; }
    .bg-red {background-color: #dd4b39 !important;}
    .bg-green {background-color:#09a20e !important;}
    .bg-yellow{ background-color:orange !important; }
    .ui-datepicker td.ui-datepicker-today a {background:#999999;}

    .img-polaroid {
        max-width: 150px;
        max-height: 180px;
    }

    .img-polaroid {
        padding: 4px;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.2);
        -webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        -moz-box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .suf {
        margin-top:10px;
    }

</style>
<div class="mainpanel">

    <div class="media mt--20">
        <h4>Settings</h4>
    </div>

    <div class="contentpanel">
        <div class="panel-body mt-top5">
            <div class="">

                <?php
                $result = validation_errors();

                if (trim($result) != ""):
                    ?>

                    <div class="alert alert-error">

                        <button data-dismiss="alert" class="close" type="button">&times;</button>

                        <?php echo implode("</p>", array_unique(explode("</p>", validation_errors()))); ?>
                    </div>

                <?php endif;
                ?>

                <?php
                // $user_role = json_decode($roles[0]["roles"]);
                //$this->pre_print->viewExit($user_role);

                $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');
                echo form_open_multipart('masters/biometric/settings', $attributes);
                ?>

                <div class="row">

                    <!-- <div class="col-md-6">
                         <div class="form-group">
                             <label class="col-sm-4 control-label">Overtime Hourly Wages</label>
                             <div class="col-sm-8">
                                 <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['overtime_wages']))
                        $default = ucwords($settings['overtime_wages']["value"]);

                    $data = array(
                        'name' => 'setting[overtime_wages]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[overtime_wages]')) : $default,
                        'class' => 'required form-align',
                        'id' => 'overtime_wages'
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_input($data);
                    ?>
                                 </div>
                             </div>
                         </div>
                     </div>-->


                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Attendance Threshold</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['attendance_threshold']))
                        $default = ucwords($settings['attendance_threshold']["value"]);
                    $data = array(
                        'name' => 'setting[attendance_threshold]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[attendance_threshold]')) : $default,
                        'class' => 'required input-medium numeric',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        //$data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    echo "  (in Minutes)";
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!--<div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Permission per Month</label>
                            <div class="col-sm-8">
                                <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['permission_per_month']))
                        $default = $settings['permission_per_month']["value"];

                    $data = array(
                        'name' => 'setting[permission_per_month]',
                        'value' => isset($_POST['save']) ? set_value('setting[permission_per_month]') : $default,
                        'class' => 'required input-medium float',
                        'id' => 'time_permission'
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data) . " (in Hours)";
                    ?>

                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Employee ID(prefix,suffix)</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <?php
                                    $default = '';

                                    if (isset($settings['emp_id_prefix']))
                                        $default = $settings['emp_id_prefix']["value"];

                                    $data = array(
                                        'name' => 'setting[emp_id_prefix]',
                                        'value' => $default,
                                        'class' => 'required input-small character empid-width',
                                    );

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        $data["disabled"] = "disabled";
                                    }

                                    echo form_input($data);
                                    ?>

                                    <?php
                                    $default = '';

                                    if (isset($settings['emp_id_suffix']))
                                        $default = $settings['emp_id_suffix']["value"];

                                    $data = array(
                                        'name' => 'setting[emp_id_suffix]',
                                        'value' => $default,
                                        'class' => 'required input-small numeric empid-width suf',
                                    );

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        $data["disabled"] = "disabled";
                                    }
                                    //      print_r($data);exit;
                                    echo form_input($data);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Minimum Overtime Hours</label>
                            <div class="col-sm-8">
                                <div class="input-group inlin">
                                    <?php
                                    $default = '';

                                    if (isset($settings['min_ot_hours']))
                                        $default = $settings['min_ot_hours']["value"];

                                    $data = array(
                                        'name' => 'setting[min_ot_hours]',
                                        'value' => isset($_POST['save']) ? set_value('setting[min_ot_hours]') : $default,
                                        'class' => 'required input-medium time',
                                    );

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        // $data["disabled"] = "disabled";
                                    }

                                    echo form_input($data) . " (in hh : mm)";
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Minimum Overtime Hours</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['min_ot_hours']))
                        $default = $settings['min_ot_hours']["value"];

                    $data = array(
                        'name' => 'setting[min_ot_hours]',
                        'value' => isset($_POST['save']) ? set_value('setting[min_ot_hours]') : $default,
                        'class' => 'required input-medium time',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data) . " (in hh : mm)";
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                    -->
                    <!--  <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Overtime Threshold</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['ot_threshold']))
                        $default = $settings['ot_threshold']["value"];

                    $data = array(
                        'name' => 'setting[ot_threshold]',
                        'value' => isset($_POST['save']) ? set_value('setting[ot_threshold]') : $default,
                        'class' => 'required input-medium float',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_input($data) . " (in Minutes)";
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>-->

                    <!--  <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Overtime Division</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';
                    if (isset($settings['ot_division']))
                        $default = $settings['ot_division']["value"];

                    $data = array(
                        'name' => 'setting[ot_division]',
                        'value' => isset($_POST['save']) ? set_value('setting[ot_division]') : $default,
                        'class' => 'required input-medium float',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_input($data) . " (in Minutes)";
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>-->

                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Month Starting Date</label>
                            <div class="col-sm-5">
                                <div class="input-group inlin" style="width:172px">
                    <?php
                    $default = '';

                    if (isset($settings['month_starting_date']))
                        $default = $settings['month_starting_date']["value"];

                    $options = array();

                    for ($k = 1; $k <= 31; $k++) {

                        $options[$k] = $k;
                    }

                    $prop = "";

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $prop = "disabled='disabled'";
                    }
                    echo form_dropdown('setting[month_starting_date]', $options, $default, $prop);
                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Week Starting Day</label>
                            <div class="col-sm-5">
                                <div class="input-group inlin" style="width:172px">
                    <?php
                    $default = '';

                    if (isset($settings['week_starting_day']))
                        $default = $settings['week_starting_day']["value"];

                    $options = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
                    $prop = "";

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $prop = "disabled='disabled'";
                    }
                    echo form_dropdown('setting[week_starting_day]', $options, $default, $prop);
                    ?>

                                </div>
                            </div>
                        </div>
                    </div>-->



                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Default Number of records</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin" style="width:172px">
                    <?php
                    $default = '';

                    if (isset($settings['default_number_of_records']))
                        $default = $settings['default_number_of_records']["value"];

                    $options = array();

                    for ($k = 10; $k <= 200;) {

                        $options[$k] = $k;

                        if ($k < 100)
                            $k = $k + 10;
                        else
                            $k = $k + 20;
                    }

                    $prop = "";

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $prop = "disabled='disabled'";
                    }

                    echo form_dropdown('setting[default_number_of_records]', $options, $default, $prop);
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!--    <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Saturday Holiday</label>
                                <div class="col-sm-5">
                                    <div class="input-group inlin">
                    <?php
                    $s_yes = FALSE;

                    $s_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['saturday_holiday'])) {

                            if (set_value('setting[saturday_holiday]') == 1)
                                $s_yes = TRUE;

                            else if (set_value('setting[saturday_holiday]') == 0)
                                $s_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['saturday_holiday'])) {
                            if ($settings["saturday_holiday"]["value"] == 1)
                                $s_yes = TRUE;

                            else if ($settings["saturday_holiday"]["value"] == 0)
                                $s_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[saturday_holiday]', 'type' => 'radio', "value" => 1, 'checked' => $s_yes, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[saturday_holiday]', 'type' => 'radio', "value" => 0, "checked" => $s_no, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                    </div>
                                </div>
                            </div>
                        </div>
                    -->

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Allow Manual Attendance</label>
                            <div class="col-sm-5" style="margin-top:5px;">
                                <div class="input-group inlin">

                                    <?php
                                    $ma_yes = FALSE;

                                    $ma_no = FALSE;

                                    if (isset($_POST['save'])) {

                                        if (isset($post['setting']['manual_attendance_entry'])) {

                                            if (set_value('setting[manual_attendance_entry]') == 1)
                                                $ma_yes = TRUE;

                                            else if (set_value('setting[manual_attendance_entry]') == 0)
                                                $ma_no = TRUE;
                                        }
                                    }

                                    else {

                                        if (isset($settings['manual_attendance_entry'])) {
                                            if ($settings["manual_attendance_entry"]["value"] == 1)
                                                $ma_yes = TRUE;

                                            else if ($settings["manual_attendance_entry"]["value"] == 0)
                                                $ma_no = TRUE;
                                        }
                                    }

                                    $data = array('name' => 'setting[manual_attendance_entry]', 'type' => 'radio', "value" => 1, 'checked' => $ma_yes, 'class' => 'required-radio');

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        // $data["disabled"] = "disabled";
                                    }
                                    echo form_checkbox($data);
                                    ?> Yes &nbsp; &nbsp;

                                    <?php
                                    $data = array('name' => 'setting[manual_attendance_entry]', 'type' => 'radio', "value" => 0, "checked" => $ma_no, 'class' => 'required-radio');

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        // $data["disabled"] = "disabled";
                                    }

                                    echo form_checkbox($data);
                                    ?> No &nbsp; &nbsp;

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Late Coming Threshold</label>
                            <div class="col-sm-8">
                                <div class="input-group inlin">
                                    <?php
                                    $default = '';

                                    if (isset($settings['late_coming_threshold_value']))
                                        $default = $settings['late_coming_threshold_value']["value"];

                                    $data = array(
                                        'name' => 'setting[late_coming_threshold_value]',
                                        'value' => isset($_POST['save']) ? set_value('setting[late_coming_threshold_value]') : $default,
                                        'class' => 'required input-medium time',
                                    );

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        // $data["disabled"] = "disabled";
                                    }

                                    echo form_input($data) . " (in hh : mm)";
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Early Going Threshold</label>
                            <div class="col-sm-8">
                                <div class="input-group inlin">
                                    <?php
                                    $default = '';

                                    if (isset($settings['early_going_threshold_value']))
                                        $default = $settings['early_going_threshold_value']["value"];

                                    $data = array(
                                        'name' => 'setting[early_going_threshold_value]',
                                        'value' => isset($_POST['save']) ? set_value('setting[early_going_threshold_value]') : $default,
                                        'class' => 'required input-medium time',
                                    );

                                    if (!in_array("masters:edit_settings", $user_role)) {
                                        // $data["disabled"] = "disabled";
                                    }

                                    echo form_input($data) . " (in hh : mm)";
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Saturday Holidays</label>
                            <div class="col-sm-5" style="margin-top:5px;">
                                <div class="input-group inlin">

                                    <?php
                                    for ($i = 1; $i < 6; $i++) {
                                        if ($i == 1) {
                                            $week_name = 'st';
                                        } elseif ($i == 2) {
                                            $week_name = 'nd';
                                        } elseif ($i == 3) {
                                            $week_name = 'rd';
                                        } else {
                                            $week_name = 'th';
                                        }
                                        $check = "";
                                        if ($settings['Week_end_holidays'] != "") {
                                            $explode_holidays = explode(',', $settings['Week_end_holidays']['value']);

                                            if (in_array($i, $explode_holidays)) {

                                                $check = "checked";
                                            }
                                        }
                                        //echo $check;
                                        echo'<input type="checkbox" value="' . $i . '" name="setting[Week_end_holidays][]" ' . $check . '> ' . $i . '' . $week_name . ' Week &nbsp; &nbsp;<br>';
                                    }
                                    ?>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                         <div class="form-group">
                             <label class="col-sm-4 control-label">Enable Force Break</label>
                             <div class="col-sm-5">
                                 <div class="input-group inlin">
                    <?php
                    $br_yes = FALSE;

                    $br_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['enable_force_break'])) {

                            if (set_value('setting[enable_force_break]') == 1)
                                $br_yes = TRUE;

                            else if (set_value('setting[enable_force_break]') == 0)
                                $br_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['enable_force_break'])) {

                            if ($settings["enable_force_break"]["value"] == 1)
                                $br_yes = TRUE;

                            else if ($settings["enable_force_break"]["value"] == 0)
                                $br_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[enable_force_break]', 'type' => 'radio', "value" => 1, 'checked' => $br_yes, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;



                    <?php
                    $data = array('name' => 'setting[enable_force_break]', 'type' => 'radio', "value" => 0, "checked" => $br_no, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                 </div>
                             </div>
                         </div>
                     </div>-->


                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Leave Mail Notifications</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $lm_yes = FALSE;

                    $lm_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['leave_mail_notifications'])) {

                            if (set_value('setting[leave_mail_notifications]') == 1)
                                $lm_yes = TRUE;

                            else if (set_value('setting[leave_mail_notifications]') == 0)
                                $lm_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['leave_mail_notifications'])) {
                            if ($settings["leave_mail_notifications"]["value"] == 1)
                                $lm_yes = TRUE;
                            else if ($settings["leave_mail_notifications"]["value"] == 0)
                                $lm_no = TRUE;
                        }
                    }



                    $data = array('name' => 'setting[leave_mail_notifications]', 'type' => 'radio', "value" => 1, 'checked' => $lm_yes, 'class' => 'required-radio current_leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[leave_mail_notifications]', 'type' => 'radio', "value" => 0, "checked" => $lm_no, 'class' => 'required-radio current_leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Leave Extra Mail Notifications</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $ln_yes = FALSE;

                    $ln_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['leave_extra_mail_notifications'])) {

                            if (set_value('setting[leave_extra_mail_notifications]') == 1)
                                $ln_yes = TRUE;

                            else if (set_value('setting[leave_extra_mail_notifications]') == 0)
                                $ln_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['leave_extra_mail_notifications'])) {



                            if ($settings["leave_extra_mail_notifications"]["value"] == 1)
                                $ln_yes = TRUE;

                            else if ($settings["leave_extra_mail_notifications"]["value"] == 0)
                                $ln_no = TRUE;
                        }
                    }



                    $data = array('name' => 'setting[leave_extra_mail_notifications]', 'type' => 'radio', "value" => 1, 'checked' => $ln_yes, 'class' => 'required-radio leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[leave_extra_mail_notifications]', 'type' => 'radio', "value" => 0, "checked" => $ln_no, 'class' => 'required-radio leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Leave Extra Notifications Email id(s)</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['leave_notify_mail']))
                        $default = ucwords($settings['leave_notify_mail']["value"]);

                    $data = array(
                        'name' => 'setting[leave_notify_mail]',
                        'value' => isset($_POST['save']) ? set_value('setting[leave_notify_mail]') : $default,
                        'class' => 'required input-medium mail',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data) . "<br><span style='padding-left:44px;'>(use comma for multiple emails)</span>";
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->


                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Leave Mail Notifications for other appliers</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $trigger_yes = FALSE;

                    $trigger_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['leave_mail_notification_for_other_appliers'])) {

                            if (set_value('setting[leave_mail_notification_for_other_appliers]') == 1)
                                $trigger_yes = TRUE;

                            else if (set_value('setting[leave_mail_notification_for_other_appliers]') == 0)
                                $trigger_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['leave_mail_notification_for_other_appliers'])) {



                            if ($settings["leave_mail_notification_for_other_appliers"]["value"] == 1)
                                $trigger_yes = TRUE;

                            else if ($settings["leave_mail_notification_for_other_appliers"]["value"] == 0)
                                $trigger_no = TRUE;
                        }
                    }



                    $data = array('name' => 'setting[leave_mail_notification_for_other_appliers]', 'type' => 'radio', "value" => 1, 'checked' => $trigger_yes, 'class' => 'required-radio ');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;



                    <?php
                    $data = array('name' => 'setting[leave_mail_notification_for_other_appliers]', 'type' => 'radio', "value" => 0, "checked" => $trigger_no, 'class' => 'required-radio ');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                    <!--  <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Company Name</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['company_name']))
                        $default = ucwords($settings['company_name']["value"]);

                    $data = array(
                        'name' => 'setting[company_name]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[company_name]')) : $default,
                        'class' => 'required input-medium alphabet',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>-->


                    <!--  <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Address</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin" style="width:172px">
                    <?php
                    $default = '';

                    if (isset($settings['address']))
                        $default = ucwords($settings['address']["value"]);

                    $data = array(
                        'name' => 'setting[address]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[address]')) : $default,
                        'class' => 'required input-medium',
                        'maxlength' => 50,
                        'rows' => 3,
                        'cols' => 5
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_textarea($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>


                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Place</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['place']))
                        $default = ucwords($settings['place']["value"]);

                    $data = array(
                        'name' => 'setting[place]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[place]')) : $default,
                        'class' => 'required input-medium alphabet place settings',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>


                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">District</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['district']))
                        $default = ucwords($settings['district']["value"]);

                    $data = array(
                        'name' => 'setting[district]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[district]')) : $default,
                        'class' => 'required input-medium alphabet',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_input($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>



                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Pincode</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['pincode']))
                        $default = $settings['pincode']["value"];

                    $data = array(
                        'name' => 'setting[pincode]',
                        'value' => isset($_POST['save']) ? set_value('setting[pincode]') : $default,
                        'class' => 'required input-medium numeric',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Phone Number</label>
                              <div class="col-sm-8">
                                  <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['company_phone']))
                        $default = $settings['company_phone']["value"];

                    $data = array(
                        'name' => 'setting[company_phone]',
                        'value' => isset($_POST['save']) ? set_value('setting[company_phone]') : $default,
                        'class' => 'required input-medium numeric phone',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                  </div>
                              </div>
                          </div>
                      </div>-->


                    <!-- <div class="col-md-6">
                         <div class="form-group">
                             <label class="col-sm-4 control-label">Company Website</label>
                             <div class="col-sm-8">
                                 <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['company_website']))
                        $default = $settings['company_website']["value"];

                    $data = array(
                        'name' => 'setting[company_website]',
                        'value' => isset($_POST['save']) ? set_value('setting[company_website]') : $default,
                        'class' => 'required input-medium website settings',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        //$data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                 </div>
                             </div>
                         </div>
                     </div>

                    <?php
                    /*
                      if ($temp_data = $this->session_view->get_session()) {

                      $temp_data = $temp_data["temp_data"];

                      $image_data = $this->temp_data_model->get_temp_data_by_id($temp_data);

                      $image_data = (array) json_decode($image_data[0]["value"]);

                      $src = $image_data["prof_image"];

                      //print_r($this->session_view->get_session('masters','add_employee'));
                      } else if (isset($settings["logo"])) {

                      $src = $this->config->item('base_url') . "/attachments/company_logo/" . $settings["logo"]["value"];
                      } else {

                      $src = $theme_path . "/img/profilethumb.png";
                      }
                     */

                    if (isset($settings["logo"])) {

                        $src = $this->config->item('base_url') . "attachments/company_logo/" . $settings["logo"]["value"];
                    } else {

                        $src = $theme_path . "/img/profilethumb.png";
                    }
                    ?>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label class="col-sm-4 control-label">Company Logo (275*70)</label>
                             <div class="col-sm-8">
                                 <div class="input-group inlin">
                                     <span style="height:100px; width:100px;" class="field"> <img  src="<?= $src; ?>" alt="" class="img-polaroid"/></span>
                    <?php
                    $data = array(
                        'name' => 'setting[logo]',
                        'type' => 'file',
                        'id' => 'profile',
                        'class' => 'profile_pic'
                    );
                    echo form_input($data);
                    ?>

                                     <input type="hidden" id="input_file" name="temp[file]" />

                                 </div>
                             </div>
                         </div>
                     </div>-->
                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Wage Slip Mail Notifications</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $wn_yes = FALSE;

                    $wn_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['wage_slip_mail_notifications'])) {

                            if (set_value('setting[wage_slip_mail_notifications]') == 1)
                                $wn_yes = TRUE;

                            else if (set_value('setting[wage_slip_mail_notifications]') == 0)
                                $wn_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['wage_slip_mail_notifications'])) {
                            if ($settings["wage_slip_mail_notifications"]["value"] == 1)
                                $wn_yes = TRUE;

                            else if ($settings["wage_slip_mail_notifications"]["value"] == 0)
                                $wn_no = TRUE;
                        }
                    }



                    $data = array('name' => 'setting[wage_slip_mail_notifications]', 'type' => 'radio', "value" => 1, 'checked' => $wn_yes, 'class' => 'required-radio current_wage');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[wage_slip_mail_notifications]', 'type' => 'radio', "value" => 0, "checked" => $wn_no, 'class' => 'required-radio current_wage');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Wage Slip Extra Mail Notifications</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $w_yes = FALSE;

                    $w_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['wage_slip_extra_mail_notifications'])) {

                            if (set_value('setting[wage_slip_extra_mail_notifications]') == 1)
                                $w_yes = TRUE;

                            else if (set_value('setting[wage_slip_extra_mail_notifications]') == 0)
                                $w_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['wage_slip_extra_mail_notifications'])) {
                            if ($settings["wage_slip_extra_mail_notifications"]["value"] == 1)
                                $w_yes = TRUE;

                            else if ($settings["wage_slip_extra_mail_notifications"]["value"] == 0)
                                $w_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[wage_slip_extra_mail_notifications]', 'type' => 'radio', "value" => 1, 'checked' => $w_yes, 'class' => 'required-radio wage');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;



                    <?php
                    $data = array('name' => 'setting[wage_slip_extra_mail_notifications]', 'type' => 'radio', "value" => 0, "checked" => $w_no, 'class' => 'required-radio wage');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Wage Slip Notifications Mail id(s)</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['leave_notify_mail']))
                        $default = ucwords($settings['wage_slip_mail']["value"]);

                    $data = array(
                        'name' => 'setting[wage_slip_mail]',
                        'value' => isset($_POST['save']) ? set_value('setting[wage_slip_mail]') : $default,
                        'class' => 'required input-medium mail',
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data) . "<br><span style='padding-left:25px;'>(use comma for multiple emails)</span>";
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->

                    <!--                    <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Enable Earned Leave</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $el_yes = FALSE;

                    $el_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['enable_earned_leave'])) {

                            if (set_value('setting[enable_earned_leave]') == 1)
                                $el_yes = TRUE;

                            else if (set_value('setting[enable_earned_leave]') == 0)
                                $el_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['enable_earned_leave'])) {

                            if ($settings["enable_earned_leave"]["value"] == 1)
                                $el_yes = TRUE;

                            else if ($settings["enable_earned_leave"]["value"] == 0)
                                $el_no = TRUE;
                        }
                    }



                    $data = array('name' => 'setting[enable_earned_leave]', 'type' => 'radio', "value" => 1, 'checked' => $el_yes, 'class' => 'required-radio earned-leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[enable_earned_leave]', 'type' => 'radio', "value" => 0, "checked" => $el_no, 'class' => 'required-radio earned-leave');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Working Days for Earned Leave</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group inlin">
                    <?php
                    $default = '';

                    if (isset($settings['working_days_for_earned_leave']))
                        $default = ucwords($settings['working_days_for_earned_leave']["value"]);
                    $data = array(
                        'name' => 'setting[working_days_for_earned_leave]',
                        'value' => isset($_POST['save']) ? ucwords(set_value('setting[working_days_for_earned_leave]')) : $default,
                        'class' => 'required input-medium numeric',
                        'id' => 'working_days_for_earned_leave'
                    );

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_input($data);
                    ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Earned Leave Carry Forward</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $elcf_yes = FALSE;

                    $elcf_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['earned_leave_carry_forward'])) {

                            if (set_value('setting[earned_leave_carry_forward]') == 1)
                                $elcf_yes = TRUE;

                            else if (set_value('setting[earned_leave_carry_forward]') == 0)
                                $elcf_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['earned_leave_carry_forward'])) {



                            if ($settings["earned_leave_carry_forward"]["value"] == 1)
                                $elcf_yes = TRUE;

                            else if ($settings["earned_leave_carry_forward"]["value"] == 0)
                                $elcf_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[earned_leave_carry_forward]', 'type' => 'radio', "value" => 1, 'checked' => $elcf_yes, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[earned_leave_carry_forward]', 'type' => 'radio', "value" => 0, "checked" => $elcf_no, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">CL Carry Forward</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $a_yes = FALSE;

                    $a_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['cl_carry_forward'])) {

                            if (set_value('setting[cl_carry_forward]') == 1)
                                $a_yes = TRUE;

                            else if (set_value('setting[cl_carry_forward]') == 0)
                                $a_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['cl_carry_forward'])) {



                            if ($settings["cl_carry_forward"]["value"] == 1)
                                $a_yes = TRUE;

                            else if ($settings["cl_carry_forward"]["value"] == 0)
                                $a_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[cl_carry_forward]', 'type' => 'radio', "value" => 1, 'checked' => $a_yes, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;

                    <?php
                    $data = array('name' => 'setting[cl_carry_forward]', 'type' => 'radio', "value" => 0, "checked" => $a_no, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;


                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">SL Carry Forward</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group inlin">
                    <?php
                    $sl_yes = FALSE;

                    $sl_no = FALSE;

                    if (isset($_POST['save'])) {

                        if (isset($post['setting']['sl_carry_forward'])) {

                            if (set_value('setting[sl_carry_forward]') == 1)
                                $sl_yes = TRUE;

                            else if (set_value('setting[sl_carry_forward]') == 0)
                                $sl_no = TRUE;
                        }
                    }

                    else {

                        if (isset($settings['sl_carry_forward'])) {



                            if ($settings["sl_carry_forward"]["value"] == 1)
                                $sl_yes = TRUE;

                            else if ($settings["sl_carry_forward"]["value"] == 0)
                                $sl_no = TRUE;
                        }
                    }

                    $data = array('name' => 'setting[sl_carry_forward]', 'type' => 'radio', "value" => 1, 'checked' => $sl_yes, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        // $data["disabled"] = "disabled";
                    }

                    echo form_checkbox($data);
                    ?> Yes &nbsp; &nbsp;
                    <?php
                    $data = array('name' => 'setting[sl_carry_forward]', 'type' => 'radio', "value" => 0, "checked" => $sl_no, 'class' => 'required-radio');

                    if (!in_array("masters:edit_settings", $user_role)) {
                        $data["disabled"] = "disabled";
                    }
                    echo form_checkbox($data);
                    ?> No &nbsp; &nbsp;

                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                    <br/>
                    <div class="frameset_table action-btn-align" >
<!--                        <input type="submit" value="Save" class="submit btn btn-success" id="submit"/>-->
                        <?php
                        $data = array(
                            'name' => 'save',
                            'value' => 'Save',
                            'class' => 'submit btn btn-success',
                            'id' => 'save_btn',
                            'titlt' => 'Save'
                        );

                        echo form_submit($data);
                        ?>
                        <input type="reset" value="Clear" class=" btn btn-danger1" id="cancel" />
<!--                        <a href="<?php //echo $this->config->item('base_url') . 'masters/masters'                                                                                               ?>" class="btn btn-defaultback"><span class="glyphicon"></span> Back </a>-->
                    </div>

                </div>

            </div>

        </div>
    </div>

</div><!-- contentpanel -->

</div><!-- mainpanel -->

<script type="text/javascript">

    var ln_yes = "<?= $ln_yes ?>";

    var w_yes = "<?= $w_yes ?>";

    var lm_yes = "<?= $lm_yes ?>";

    var wn_yes = "<?= $wn_yes ?>";

    var el_yes = "<?= $el_yes ?>";

    $(document).ready(function () {

        //alert(lm_yes);

        if (!ln_yes)
            $("#leave_notify").hide();

        if (!w_yes)
            $("#wage_notify").hide();

        if (!lm_yes)
            $(".leave_extra").hide();

        if (!wn_yes)
            $(".wage_extra").hide();

        if (!el_yes)
            $(".earned-leave-show").hide();



        $(".leave").click(function () {



            if ($(this).attr("value") == 1)

            {

                $("#leave_notify").show();

            } else

            {

                $("#leave_notify").hide();

            }

        });

        $(".wage").click(function () {



            if ($(this).attr("value") == 1)

            {

                $("#wage_notify").show();

            } else

            {

                $("#wage_notify").hide();

            }

        });

        $(".current_leave").click(function () {



            if ($(this).attr("value") == 1)

            {

                $(".leave_extra").show();

            } else

            {

                $(".leave_extra").hide();

            }

        });

        $(".current_wage").click(function () {

            //alert($(this).attr("value"));

            if ($(this).attr("value") == 1)

            {

                $(".wage_extra").show();

            } else

            {

                $(".wage_extra").hide();

            }

        });

        $(".earned-leave").click(function () {



            if ($(this).attr("value") == 1)

            {

                $(".earned-leave-show").show();

            } else

            {

                $(".earned-leave-show").hide();

            }

        });

    });

</script>