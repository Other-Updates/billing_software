<?php

class Attendance extends MX_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library('user_auth');

        date_default_timezone_set($this->config->item("default_time_zone"));

//        if (!$this->user_auth->active_application()) {
//
//            redirect($this->config->item("base_url") . "users/login/");
//        }
//
//        if (!$this->user_auth->get_user_permission($this->router->class . ":" . $this->router->method)) {
//
//            $this->session_messages->add_message('warning', 'You dont have permission to access this area');
//
//            redirect($this->config->item("base_url") . "users/");
//        }

        $this->load->helper('url');

        $this->load->library('session');
        $this->load->library('session_view');
        $this->load->helper('form');

        $this->load->library("pagination");

        $this->load->model('masters/users_model');

        $this->load->model('masters/department_model');

        $this->load->model('masters/designation_model');

        $this->load->model('masters/user_roles_model');

        $this->load->model('masters/shift_model');

        $this->load->model('masters/salary_group_model');

        $this->load->model('attendance/attendance_model');

        $this->load->model('masters/options_model');


//        $this->load->library('session');
//        $datam["messages"] = $this->session_messages->view_all_messages();
//
//        $this->template->write_view('session_msg', 'masters/session_messages', $datam);
    }

    function check_manual_attendance() {
        $data = $this->attendance_model->check_manual_attendance();
        echo $data;
    }

    function monthly_attendance() {

        $this->load->model('masters/users_model');
        $this->load->library('session_view');
        $this->load->model('masters/department_model');

        $this->load->model('masters/designation_model');

        $this->load->model('masters/user_roles_model');

        $this->load->model('masters/options_model');

        $data["default_number_of_records"] = $this->options_model->get_option_by_name('default_number_of_records');

//        $data["roles"] = $this->user_roles_model->get_user_role($this->user_auth->get_user_id());

        $result = array();

        $filter = null;



        if ($this->input->post("search")) {

            $filter = $this->input->post();

            if (isset($filter["search"]))
                unset($filter["search"]);

            $data["no_of_users1"] = $this->users_model->get_filter_user_count($filter, 1);

            $this->session_view->add_session('attendance', 'monthly_attendance', $filter);

            redirect($this->config->item('base_url') . "attendance/monthly_attendance/");
        }

        else {

            $filter = $this->session_view->get_session('attendance', 'monthly_attendance');

            if (isset($filter) && !empty($filter)) {

                $data["no_of_users1"] = $this->users_model->get_filter_user_count($filter, 1);
            } else {

                $data["no_of_users1"] = $this->users_model->get_users_count(1);
            }
        }

        if (isset($filter["show_count"]))
            $default = $filter["show_count"];

        else {

            if (isset($data["default_number_of_records"]) && !empty($data["default_number_of_records"]))
                $default = $data["default_number_of_records"][0]["value"];
            else
                $default = 10;
        }



        if (isset($filter["inactive"]))
            $data["status"] = TRUE;



        $result["total_rows"] = $data["no_of_users1"][0]['count'];

        $result["base_url"] = $this->config->item('base_url') . "attendance/monthly_attendance/";

        $result["per_page"] = $default;

        $data["count"] = $default;

        $result["num_links"] = 3;

        $result["uri_segment"] = 3;

        $result['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';

        $result['full_tag_close'] = '</ul>';

        $result['prev_link'] = '&lt;';

        $result['prev_tag_open'] = '<li>';

        $result['prev_tag_close'] = '</li>';

        $result['next_link'] = '&gt;';

        $result['next_tag_open'] = '<li>';

        $result['next_tag_close'] = '</li>';

        $result['cur_tag_open'] = '<li class="current"><a href="#">';

        $result['cur_tag_close'] = '</a></li>';

        $result['num_tag_open'] = '<li>';

        $result['num_tag_close'] = '</li>';

        $result['first_tag_open'] = '<li>';

        $result['first_tag_close'] = '</li>';

        $result['last_tag_open'] = '<li>';

        $result['last_tag_close'] = '</li>';

        $result['first_link'] = '&lt;&lt;';

        $result['last_link'] = '&gt;&gt;';

//        $this->pagination->initialize($result);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($default == "all")
            $data['users'] = $this->users_model->get_users_with_dept($filter, 1);
        else
            $data['users'] = $this->users_model->get_users_with_dept_by_limit($result["per_page"], $page, $filter, 1);


//        echo "<pre>";
//        print_r($data);
//        exit;

        $data["links"] = $this->pagination->create_links();

        $data["start"] = $page;

        $data["departments"] = $this->department_model->get_all_departments_by_status(1);

        $data["designations"] = $this->designation_model->get_all_designations();


        $this->template->write_view('content', 'attendance/monthly_attendance', $data);

        $this->template->render();
    }

    function get_all_user_details() {
        $this->load->model('masters/user_model');
        $data = $this->user_model->get_insert_users_from_biousers();
        echo "<pre>";
        print_r($data);
        exit;
    }

    function cron_for_daily_attenance() {
        $this->load->model('masters/user_model');
        $data = $this->user_model->get_all_device_log_attenance_details();
        echo "<pre>";
        print_r($data);
        exit;
    }

    function cron_for_daily_attenance_direction() {
        $this->load->model('masters/user_model');
        $data = $this->user_model->get_all_device_log_attenance_direction();
        echo "<pre>";
        print_r($data);
        exit;
    }

    function daily_attendance() {

        $data["default_number_of_records"] = $this->options_model->get_option_by_name('default_number_of_records');

        //    $data["roles"] = $this->user_roles_model->get_user_role($this->user_auth->get_user_id());

        $result = array();

        $filter = null;


        if ($this->input->post('search')) {

            $filter = $this->input->post();

            if (isset($filter["search"]))
                unset($filter["search"]);

            $start_date = $filter["start_date"];

            $start_date = date("Y-m-d", strtotime($start_date));

            $filter["start_date"] = $start_date;

            $this->users_model->create_view_for_normal_users($start_date);

            //  $data["no_of_users1"] = $this->users_model->get_users_count_with_shift_salary($filter, 1, null, $filter['start_date'], null);
            $data["no_of_users1"] = $this->attendance_model->get_all_attendance_users();
//            echo '<pre>';
//            print_r($this->input->post());
//            die;
//            echo '<pre>';
//            print_r($data["no_of_users1"]);
//            exit;
            $this->session_view->add_session('attendance', 'daily_attendance', $filter);

            redirect($this->config->item('base_url') . "attendance/daily_attendance/");
        } else {

            $filter = $this->session_view->get_session('attendance', 'daily_attendance');

            if (empty($filter)) {

                $start_date = date("Y-m-d", strtotime("-1 day"));
            } else {

                if (isset($filter["start_date"]))
                    $start_date = $filter["start_date"];
                else
                    $start_date = date("Y-m-d", strtotime("-1 day"));

                $start_date = date("Y-m-d", strtotime($start_date));

                $filter["start_date"] = $start_date;

                $this->users_model->create_view_for_normal_users($start_date);

                // $data["no_of_users1"] = $this->users_model->get_users_count_with_shift_salary($filter, 1, null, $start_date, null);
                $data["no_of_users1"] = $this->attendance_model->get_all_attendance_users();
            }
        }

//        echo "<pre>";
//        print_r($data["no_of_users1"]);
//        exit;

        if ($this->input->post("add_attendance")) {

            $filters = $this->input->post();

            if (isset($filter["add_attendance"]))
                unset($filter["add_attendance"]);

            foreach ($filters["attendance_data"] as $key => $val) {

                $user_id[] = $key;
            }

            $filter["user_id"] = $user_id;

            if (!isset($filter["start_date"]))
                $filter["start_date"] = date("Y-m-d", strtotime("-1 day"));

//            print_r($filter);
//            exit;

            $this->session_view->add_session('attendance', 'daily_attendance', $filter);
            redirect($this->config->item('base_url') . "attendance/add_attendance_for_day/");
        }



        if (isset($filter["show_count"]))
            $default = $filter["show_count"];

        else {

            if (isset($data["default_number_of_records"]) && !empty($data["default_number_of_records"]))
                $default = $data["default_number_of_records"][0]["value"];
            else
                $default = 10;
        }



        if (isset($filter["inactive"]))
            $data["status"] = TRUE;

        if (isset($data["no_of_users1"]))
            $result["total_rows"] = count($data["no_of_users1"]);

        $result["base_url"] = $this->config->item('base_url') . "attendance/daily_attendance/";

        $result["per_page"] = $default;

        $data["count"] = $default;

        $result["num_links"] = 3;

        $result["uri_segment"] = 3;

        $result['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';

        $result['full_tag_close'] = '</ul>';

        $result['prev_link'] = '&lt;';

        $result['prev_tag_open'] = '<li>';

        $result['prev_tag_close'] = '</li>';

        $result['next_link'] = '&gt;';

        $result['next_tag_open'] = '<li>';

        $result['next_tag_close'] = '</li>';

        $result['cur_tag_open'] = '<li class="current"><a href="#">';

        $result['cur_tag_close'] = '</a></li>';

        $result['num_tag_open'] = '<li>';

        $result['num_tag_close'] = '</li>';

        $result['first_tag_open'] = '<li>';

        $result['first_tag_close'] = '</li>';

        $result['last_tag_open'] = '<li>';

        $result['last_tag_close'] = '</li>';

        $result['first_link'] = '&lt;&lt;';

        $result['last_link'] = '&gt;&gt;';

        //  $this->pagination->initialize($result);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;



        $this->users_model->create_view_for_normal_users($start_date);


        if ($default == "all") {
            //$data['users'] = $this->users_model->get_users_count_with_shift_salary($filter, 1, null, $start_date, null);
            $data['users'] = $this->attendance_model->get_all_attendance_users();
        } else {

            //$data['users'] = $this->users_model->get_users_with_shift_salary($filter, 1, null, $start_date, null, $result["per_page"], $page, null);
            $data['users'] = $this->attendance_model->get_all_attendance_users();
        }
        $data["links"] = $this->pagination->create_links();

        $data["start"] = $page;

        // $user_id = $this->user_auth->get_user_id();

        $filter_date = date("Y-m-d", strtotime($start_date));

        $data["user_attendance"] = $this->attendance_model->get_user_attendance_by_date($filter_date);
//        echo '<pre>';
//        print_r($data["user_attendance"]);
//        exit;

        $data["departments"] = $this->department_model->get_all_departments_by_status(1);

        $data["designations"] = $this->designation_model->get_all_designations();

        $data["salary_groups"] = $this->salary_group_model->get_all_salary_groups();

        $data["shifts"] = $this->shift_model->get_all_shifts();
//
//        echo '<pre>';
//        print_r($data);
//        exit;
        $data['add_attendance_users'] = $this->attendance_model->get_all_attendance_users();


        $this->template->write_view('content', 'attendance/daily_attendance', $data);

        $this->template->render();
    }

    function attendance_dashboard() {

        $this->load->model('admin/admin_model');
        $date = '';
        if (date('m') > 3) {
            $from_date = date('Y') . '-04-01';
            $to_date = (date('Y') + 1) . '-03-31';
        } else {
            $from_date = (date('Y') - 1) . '-04-01';
            $to_date = date('Y') . '-03-31';
        }
        $data['report'] = $this->admin_model->get_dashboard_report();
        $data['cash_credit'] = $this->admin_model->get_agent_cash($this->user_auth->get_from_session('user_id'));
        $data['cash_debit'] = $this->admin_model->get_agent_debit($this->user_auth->get_from_session('user_id'));
        $data['amount'] = $data['cash_credit'][0]['credit'] - $data['cash_debit'][0]['debit'];
        $data['absentees_users_data'] = $this->attendance_model->get_absentees_users_list();
        $data['data_exists'] = $this->attendance_model->check_data_exists();
        $data['late_users_data'] = $this->attendance_model->get_late_comers_list();
        $data['early_going_users_data'] = $this->attendance_model->get_early_going_list();

        $this->template->write_view('content', 'attendance/dashboard', $data);
        $this->template->render();
    }

    function late_coming_attendance() {
        $data = [];

        $data['start_date'] = date('Y-m-01');
        $data['end_date'] = date('Y-m-d');

        if (!empty($this->input->post()) && !empty($this->input->post('start_date'))) {
            $postArr = $this->input->post();
            if ($postArr['start_date'] != "")
                $data['start_date'] = date('Y-m-d', strtotime($postArr['start_date']));

            if ($postArr['end_date'] != "")
                $data['end_date'] = date('Y-m-d', strtotime($postArr['end_date']));
        }

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        while (strtotime($start_date) <= strtotime($end_date)) {
            $start_dates[] = $start_date;
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data['users_data'] = $this->attendance_model->get_late_coming_users_list($start_dates);

//        echo "<pre>";
//        print_r($data);
//        exit;

        $this->template->write_view('content', 'attendance/late_coming_attendance', $data);

        $this->template->render();
    }

    function early_going_attendance() {
        $data = [];

        $data['start_date'] = date('Y-m-01');
        $data['end_date'] = date('Y-m-d');

        if (!empty($this->input->post()) && !empty($this->input->post('start_date'))) {
            $postArr = $this->input->post();
            if ($postArr['start_date'] != "")
                $data['start_date'] = date('Y-m-d', strtotime($postArr['start_date']));

            if ($postArr['end_date'] != "")
                $data['end_date'] = date('Y-m-d', strtotime($postArr['end_date']));
        }

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        while (strtotime($start_date) <= strtotime($end_date)) {
            $start_dates[] = $start_date;
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $data['users_data'] = $this->attendance_model->get_early_going_users_list($start_dates);

        $this->template->write_view('content', 'attendance/early_going_attendance', $data);

        $this->template->render();
    }

    function daily_reports() {
        $data = [];

        $data['start_date'] = date('Y-m-01');
        $data['end_date'] = date('Y-m-d');
        $attendance_status = "all";
        if (!empty($this->input->post()) && !empty($this->input->post('start_date'))) {
            $postArr = $this->input->post();

            if ($postArr['start_date'] != "")
                $data['start_date'] = date('Y-m-d', strtotime($postArr['start_date']));

            if ($postArr['end_date'] != "")
                $data['end_date'] = date('Y-m-d', strtotime($postArr['end_date']));

            if (isset($postArr['atten_status_p']) && $postArr['atten_status_p'] != "") {
                $attendance_status = "1";
            }
            if (isset($postArr['atten_status_a']) && $postArr['atten_status_a'] != "") {
                $attendance_status = "0";
            }
            if ($postArr['atten_status_p'] != "" && $postArr['atten_status_a'] != "") {
                $attendance_status = "all";
            }
        }

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        while (strtotime($start_date) <= strtotime($end_date)) {
            $start_dates[] = $start_date;
            $start_date = date("Y-m-d", strtotime("+1 days", strtotime($start_date)));
        }

        $results_data = $this->attendance_model->get_daily_reports_users_list($start_dates, $attendance_status);


        //  echo "<pre>";print_r($results_data);exit;
        $data['attendance_status'] = $attendance_status;


        $data['users_data'] = $results_data;


        $this->template->write_view('content', 'attendance/daily_reports', $data);

        $this->template->render();
    }

    function add_attend() {

        $this->load->library('email');
        $from_email = "your@example.com";
        $to_email = $this->input->post('email');

        //Load email library
        $this->load->library('email');

        $this->email->from($from_email, 'Your Name');
        $this->email->to($to_email);
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        //Send mail
        if ($this->email->send())
            $this->session->set_flashdata("email_sent", "Email sent successfully.");
        else
            $this->session->set_flashdata("email_sent", "Error in sending Email.");
        $this->load->view('email_form');

        if ($this->input->post()) {
            echo '<pre>';
            print_r($this->input->post());
            die;
        }
    }

    function add_attendance($user_id) {

//        $this->template->write('scripts', 'attendance', TRUE);

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/shift_model');

        $this->load->model('attendance_model');

        $this->load->model('masters/available_leaves_model');

        $this->load->model('masters/options_model');

        $this->load->model('leave_model');

        $this->load->model('masters/shift_model');

        $this->load->model('masters/user_shift_model');

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/user_salary_model');

        $this->load->model('masters/user_history_model');

        $this->load->model('masters/holidays_model');

        $this->load->model('masters/users_model');

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/user_roles_model');

        $data["enable_earned_leave"] = $this->options_model->get_option_by_name("enable_earned_leave");

//        $data["roles"] = $this->user_roles_model->get_user_role($this->user_auth->get_user_id());

        $options = array('week_starting_day', 'month_starting_date', 'saturday_holiday');

        $data["threshold"] = $this->options_model->get_options_by_type('attendance_threshold');

        $admin_id = $this->user_auth->get_user_id();

        $mail = array();

        $admin_mail = $this->users_model->get_user_mail_id_by_user_id($admin_id);

        $mail["admin"] = $admin_mail[0]["email"];

        $mail["admin_name"] = $admin_mail[0]["name"];

        $mail["user_id"] = $user_id;
//
        $settings = $this->options_model->get_option_by_name($options);

        $data["name"] = $this->users_model->get_user_name_by_user_id($user_id);

        $data["doj"] = $this->user_history_model->get_history_by_user_id_and_type($user_id, 'doj');

        $data["dept"] = $this->user_department_model->get_department_by_user_id($user_id);
//        echo '<pre>';
//        print_r($data["dept"]);
//        exit;
        $joined_date = explode("-", $data["doj"][0]["date"]);

        if (isset($settings) && !empty($settings)) {

            foreach ($settings as $key => $set) {

                $data[$set["key"]] = $set["value"];
            }
        }

        $ws_day = "";

        switch ($data["week_starting_day"]) {

            case 0 :

                $ws_day = "sunday";

                break;

            case 1 :

                $ws_day = "monday";

                break;

            case 2 :

                $ws_day = "tuesday";

                break;

            case 3 :

                $ws_day = "wednesday";

                break;

            case 4 :

                $ws_day = "thursday";

                break;

            case 5 :

                $ws_day = "friday";

                break;

            case 6 :

                $ws_day = "saturday";

                break;
        }
//        echo'<pre>';
//        print_r($this->input->post());
//        exit;
        if ($this->input->post('save')) {
//            echo'<pre> testes';
//            print_r($this->input->post());
//            exit;
            $input = $this->input->post();

            if (isset($input) && !empty($input)) {

                $attendance = $input["attendance"];

                for ($i = 0; $i < count($attendance["created"]); $i++) {


                    $new_val = date("Y-m-d", strtotime($attendance["created"][$i])) . " 00:00:00";

                    $leave = 0;

                    if (isset($attendance['leave'][$i])) {

                        if ($attendance['leave'][$i] != "none")
                            $leave = 1;
                    }

                    if ($leave == 0) {

                        $in_time = ($attendance["in"][$i] == "00:00" || $attendance["in"][$i] == "00:00:00") ? NULL : $attendance["in"][$i];

                        $out_time = ($attendance["out"][$i] == "00:00" || $attendance["out"][$i] == "00:00:00") ? NULL : $attendance["out"][$i];

                        if ($in_time != NULL) {//in_time_check
                            $att_arr = array("created" => $new_val, "in" => $attendance["in"][$i], "user_id" => $user_id, "ip" => gethostbyname(trim(`hostname`)));

                            if ($out_time != NULL)
                                $att_arr["out"] = $attendance["out"][$i];



                            $att_id = $this->attendance_model->insert_attendance($att_arr);


                            if (isset($input["break"]["in_time"][$i]) && !empty($input["break"]["in_time"][$i])) {
//                                echo '$att_id';
//                                exit;

                                for ($k = 0; $k < count($input["break"]["in_time"][$i]); $k++) {

                                    $br_in_time = ($input["break"]["in_time"][$i][$k] == "00:00" || $input["break"]["in_time"][$i][$k] == "00:00:00") ? NULL : $input["break"]["in_time"][$i][$k];

                                    $br_out_time = ($input["break"]["out_time"][$i][$k] == "00:00" || $input["break"]["out_time"][$i][$k] == "00:00:00") ? NULL : $input["break"]["out_time"][$i][$k];

                                    if ($br_in_time != NULL) {

                                        $break = array("in_time" => $input["break"]["in_time"][$i][$k], "out_time" => $br_out_time, "type" => "break", "attendance_id" => $att_id);

                                        $this->attendance_model->insert_break_values($break);
                                    }
                                }
                            }
                        }//in_time_check
                    } else {

                        $lop = 0;

                        if (isset($attendance["lop"][$i])) {

                            if ($attendance["lop"][$i] == "LOP")
                                $lop = 1;
                        }

                        $leave_val = array("user_id" => $user_id, "leave_from" => $new_val, "leave_to" => $new_val, "lop" => $lop, "type" => $attendance['leave'][$i]);

                        $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, $attendance['leave'][$i]);

                        $this->leave_model->insert_user_leaves($leave_val);

                        if ($lop == 0) {

                            if (isset($leave_type[0]['available_casual_leave']))
                                $new_value = $leave_type[0]['available_casual_leave'] - 1;

                            else if (isset($leave_type[0]['available_sick_leave']))
                                $new_value = $leave_type[0]['available_sick_leave'] - 1;

                            $this->available_leaves_model->update_user_leaves_by_type($user_id, $attendance["leave"][$i], $new_value);
                        }
                    }
                }
            }

//            $this->session_messages->add_message('success', 'Attendance added');
//            echo '<pre>';
//            print_r($this->input->post());
//            exit;
            redirect($this->config->item('base_url') . "attendance/monthly_attendance");
        }

        else if ($this->input->post('go')) {



            $filter = $this->input->post();
            if (isset($filter["go"]))
                unset($filter["go"]);

//            $this->session_view->add_session(null, null, $filter);

            $data["attendance"] = $this->attendance_model->get_attendance_by_month_year_and_user_id($filter["year"], $filter["month"], $user_id);

            $data["leave"] = $this->leave_model->get_user_leaves_by_month_year_and_user_id($filter["year"], $filter["month"], $user_id);

            $data["year"] = $filter["year"];

            $data["month"] = $filter["month"];

            $data["start_date"] = $filter["start_date"];

            $data["end_date"] = $filter["end_date"];

            $data["month_start_date"] = $filter["month_start_date"];

            $data["month_end_date"] = $filter["month_end_date"];

            // week end date

            $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

            $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

            $next_date2 = new DateTime($last_saturday . ' +6 day');

            //  $data["week_end_date"] = $next_date2->format('Y-m-d');
            $week_date = date("t", strtotime($data["month_end_date"]));
            $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

            // week start date

            $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

            $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

            // if (ltrim($last_saturday_date, '0') == 8)
            $last_saturday_date = 01;

            $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;

            if (strtotime($data["start_date"]) < strtotime($data["month_start_date"])) {

                $start_date_val = $data["start_date"];
            } else {

                $start_date_val = $data["month_start_date"];
            }

            if ($joined_date[0] == $data["year"]) {

                if ($joined_date[1] = $data["month"]) {

                    $start_date_val = $data["doj"][0]["date"];
                }
            }

            $data["user_salary"] = $this->user_salary_model->get_user_salary_by_user_id($user_id, $start_date_val);

            if ($data["user_salary"][0]["type"] == "monthly") {

                $data["attendance"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["start_date"], $data["end_date"], $data["dept"][0]["department"]);
            } else {

                $data["attendance"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"], $data["dept"][0]["department"]);
            }
        } else if ($this->input->post("apply")) {



            $input = $this->input->post();

            if (isset($input["leave"]) && !empty($input["leave"])) {

                $lop = 0;

                $approved = 0;

                $from = '';

                $to = '';

                $type = "";

                $type_arr = array_filter($input["leave"]["leave_type"]);

                foreach ($type_arr as $s => $val) {

                    $department_head = $this->user_department_model->get_user_dept_head_by_userid($user_id);

                    if (isset($department_head) && !empty($department_head)) {

                        $mail["department_head"] = $department_head[0]["email"];

                        $mail["head_name"] = $department_head[0]["head_name"];
                    }

                    $user_mail = $this->users_model->get_user_mail_id_by_user_id($user_id);

                    $mail["user"] = $user_mail[0]["email"];

                    $mail["user_name"] = $user_mail[0]["name"];

                    if (isset($input['leave']['leave_type'][$s]) && $input['leave']['leave_type'][$s] != "") {

                        if ($input['leave']['leave_type'][$s] == 1 || $input['leave']['leave_type'][$s] == 2) {

                            if ($input['leave']['leave_type'][$s] == 1)
                                $type = 'sick leave';

                            else if ($input['leave']['leave_type'][$s] == 2)
                                $type = 'casual leave';

                            $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, $type);
                        }



                        if (isset($input["leave"]["lop"][$s])) {

                            $lop = 1;
                        }

                        if (isset($input["leave"]["approved"][$s])) {

                            $approved = $input["leave"]["approved"][$s];
                        }

                        if ($input["leave"]["leave_type"][$s] == 1) {

                            $date_start = $date_end = $break_start = $break_end = '';

                            if (isset($input["leave"]["date"][$s])) {

                                $from_one = $input["leave"]["date"][$s];

                                $shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $from_one);
                            }

                            if (isset($shift_id) && !empty($shift_id)) {

                                $shift_time = $this->shift_model->get_regular_and_lunch_by_shift_id($shift_id[0]['shift_id']);

                                if (isset($shift_time) && !empty($shift_time)) {

                                    foreach ($shift_time as $s_time) {

                                        if ($s_time["type"] == 'regular') {

                                            $date_start = $s_time["from_time"];

                                            $date_end = $s_time["to_time"];
                                        }

                                        if ($s_time["type"] == "lunch") {

                                            $break_start = $s_time["from_time"];

                                            $break_end = $s_time["to_time"];
                                        }
                                    }
                                }
                            }



                            if ($input["leave"]["session"][$s] == 1) {

                                $from = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $date_start;

                                $to = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $break_start;
                            } elseif ($input["leave"]["session"][$s] == 2) {

                                $from = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $break_end;

                                $to = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $date_end;
                            }



                            if (isset($leave_type[0]['available_casual_leave'])) {

                                if ($leave_type[0]['available_casual_leave'] <= 0)
                                    $lop = 1;

                                $new_value = $leave_type[0]['available_casual_leave'] - 0.5;
                            }

                            else if (isset($leave_type[0]['available_sick_leave'])) {

                                if ($leave_type[0]['available_sick_leave'] <= 0)
                                    $lop = 1;

                                $new_value = $leave_type[0]['available_sick_leave'] - 0.5;
                            }

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to);

                            $full_day_leave1 = $this->leave_model->get_user_leaves_for_diff($user_id, $from);

                            $check = 1;

                            if (isset($full_day_leave1) && !empty($full_day_leave1)) {

                                foreach ($full_day_leave1 as $fd) {

                                    $diff_d = $this->dateTimeDiff(new DateTime($fd["leave_from"]), new DateTime($fd["leave_to"]));

                                    if ($diff_d->h == 0)
                                        $check = 0;
                                }
                            }

                            if (empty($leaves_applied) && $check != 0) {

                                if ($lop == 0)
                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                if ($approved == 1)
                                    $leave_val["approved_by"] = $admin_id;

                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                $mail["leave_from"] = $from;

                                $mail["leave_to"] = $to;

                                $mail["type"] = "Half day leave";

                                if ($input["leave"]["session"] == 1)
                                    $mail["session"] = "Session1";
                                else
                                    $mail["session"] = "Session2";

                                $mail["leave_type"] = $input['leave']['type'];

                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                $this->session_messages->add_message('success', 'Leave applied successfully');
                            }

                            else {

//                                $this->session_messages->add_message('warning', 'Already leave applied for this day');

                                $data["error"] = 1;
                            }
                        } elseif ($input["leave"]["leave_type"][$s] == 2 || $input["leave"]["leave_type"][$s] == 4 || $input["leave"]["leave_type"][$s] == 6) {

                            $month_from = explode("-", $input["leave"]["leave_from"][$s]);

                            $month_to = explode("-", $input["leave"]["leave_to"][$s]);

                            $from = date("Y-m-d", strtotime($input["leave"]["leave_from"][$s])) . " 00:00:00";

                            $to = date("Y-m-d", strtotime($input["leave"]["leave_to"][$s])) . " 00:00:00";

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to, 1);

                            if (empty($leaves_applied)) {

                                $date1 = new DateTime($from);

                                $date2 = new DateTime($to);

                                $interval = $this->dateTimeDiff($date1, $date2);

                                $month_differ = 0;

                                if ($month_from[1] != $month_to[1]) {

                                    $days_in_from = cal_days_in_month(CAL_GREGORIAN, $month_from[1], $month_from[2]);

                                    $days_in_to = cal_days_in_month(CAL_GREGORIAN, $month_to[1], $month_to[2]);

                                    $month_differ = 1;

                                    $days_in_first = $days_in_from - $month_from[0];

                                    $days_in_second = $month_to[1];

                                    $interval->d = $days_in_first + $days_in_second;

                                    $first_from = new DateTime(date("Y-m-d", strtotime($input["leave"]["leave_from"][$s])) . " 00:00:00");

                                    $first_to = new DateTime(date("Y-m-d", strtotime($month_from[2] . "-" . $month_from[1] . "-" . $days_in_from)) . " 00:00:00");

                                    $second_from = new DateTime(date("Y-m-d", strtotime($month_to[2] . "-" . $month_to[1] . "-1")) . " 00:00:00");

                                    $second_to = new DateTime(date("Y-m-d", strtotime($month_to[2] . "-" . $month_to[1] . "-" . $month_to[0])) . " 00:00:00");

                                    $interval_from = $this->dateTimeDiff($first_from, $first_to);

                                    $interval_to = $this->dateTimeDiff($second_from, $second_to);
                                }



                                if ($input["leave"]["leave_type"][$s] == 2) {

                                    if (isset($leave_type[0]['available_casual_leave'])) {

                                        if ($lop == 0) {

                                            if ($leave_type[0]['available_casual_leave'] > 0) {

                                                if ($leave_type[0]['available_casual_leave'] < ($interval->d + 1)) {

                                                    $new_value = 0;

                                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                    if ($month_differ == 0) {

                                                        $next = "";

                                                        $next = new DateTime($from . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from;

                                                        $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave']) . ' day');

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;



                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $to;

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    elseif ($month_differ == 1) {

                                                        $next = "";

                                                        if ($leave_type[0]['available_casual_leave'] < ($interval_from->d + 1)) {

                                                            $next = "";

                                                            $next = new DateTime($first_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            $from = new DateTime($next->format('Y-m-d H:i:s') . ' + 1 day');

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }

                                                        else {

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $firest_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }



                                                        if ($leave_type[0]['available_casual_leave'] > 0) {

                                                            if ($leave_type[0]['available_casual_leave'] < ($interval_to->d + 1)) {

                                                                $next = "";

                                                                $next = new DateTime($second_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');

                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;



                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                                $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave']) . ' day');



                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;

                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                                                //print_r($leave_val);
                                                            }

                                                            else {

                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;

                                                                //print_r($leave_val);

                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                                            }
                                                        }

                                                        else {



                                                            //print_r($second_to);



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            //print_r($leave_val);

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }
                                                    }
                                                }

                                                else {

                                                    $new_value = $leave_type[0]['available_casual_leave'] - ($interval->d + 1);

                                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                    if ($month_differ == 0) {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from;

                                                        $mail["leave_to"] = $to;

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    else if ($month_differ == 1) {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }
                                                }
                                            }

                                            else {

                                                if ($month_differ == 0) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                else if ($month_differ == 1) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }
                                            }
                                        }

                                        else {

                                            if ($month_differ == 0) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $from;

                                                $mail["leave_to"] = $to;

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }

                                            else if ($month_differ == 1) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                            }
                                        }
                                    }
                                }

                                if (isset($leave_type[0]['available_sick_leave'])) {

                                    if ($lop == 0) {

                                        if ($leave_type[0]['available_sick_leave'] > 0) {

                                            if ($leave_type[0]['available_sick_leave'] < ($interval->d + 1)) {

                                                $new_value = 0;

                                                $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                if ($month_differ == 0) {

                                                    $next = "";

                                                    $next = new DateTime($from . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                    $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave']) . ' day');

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                elseif ($month_differ == 1) {

                                                    $next = "";

                                                    if ($leave_type[0]['available_sick_leave'] < ($interval_from->d + 1)) {

                                                        $next = "";

                                                        $next = new DateTime($first_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        $from = new DateTime($next->format('Y-m-d H:i:s') . ' + 1 day');

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    else {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');

                                                        $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;
                                                    }

                                                    if ($leave_type[0]['available_sick_leave'] > 0) {

                                                        if ($leave_type[0]['available_sick_leave'] < ($interval_to->d + 1)) {

                                                            $next = "";

                                                            $next = new DateTime($second_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave']) . ' day');

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }

                                                        else {

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }
                                                    }

                                                    else {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }
                                                }
                                            }

                                            else {

                                                $new_value = $leave_type[0]['available_sick_leave'] - ($interval->d + 1);

                                                $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                if ($month_differ == 0) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                else if ($month_differ == 1) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }
                                            }
                                        }

                                        else {

                                            if ($month_differ == 0) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $from;

                                                $mail["leave_to"] = $to;

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }

                                            else if ($month_differ == 1) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }
                                        }
                                    }

                                    else {

                                        if ($month_differ == 0) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

//                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                        }
                                    }
                                }

                                elseif ($input["leave"]["leave_type"][$s] == 4) {

                                    $input['leave']['type'][$s] = 4;

                                    $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'compoff');

                                    if ($interval->d + 1 > $leave_type[0]['comp_off']) {

//                                        $this->session_messages->add_message('warning', 'Your compoff days are less than the requested number of days');

                                        $data["error"] = 1;
                                    } else {

                                        $new_value = $leave_type[0]['comp_off'] - ($interval->d + 1);

                                        $this->available_leaves_model->update_user_leaves_by_type($user_id, 'compoff', $new_value);

                                        if ($month_differ == 0) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Comp off leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Comp off leave applied successfully');
                                        }
                                    }
                                }

                                elseif ($input["leave"]["leave_type"][$s] == 6) {

                                    $input['leave']['type'][$s] = 6;

                                    $lop = 0;

                                    $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'available_earned_leave');

                                    if ($interval->d + 1 > $leave_type[0]['available_earned_leave']) {

//                                        $this->session_messages->add_message('warning', "Your earned leaves are less than the requested number of days");

                                        $data["error"] = 1;
                                    } else {

                                        $new_value = $leave_type[0]['available_earned_leave'] - ($interval->d + 1);

                                        if ($lop == 0)
                                            $this->available_leaves_model->update_user_leaves_by_type($user_id, 'available_earned_leave', $new_value);

                                        if ($month_differ == 0) {

                                            if ($input["leave"]["approved"][$s] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['type'][$s];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Earned off leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            if ($input["leave"]["approved"] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['leave_type'][$s];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);



                                            if ($input["leave"]["approved"][$s] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Earned leave applied successfully');
                                        }
                                    }
                                }
                            }

                            else {

//                                $this->session_messages->add_message('warning', "Already Leave applied for this day");

                                $data["error"] = 1;
                            }
                        } elseif ($input["leave"]["leave_type"][$s] == 3) {

                            $from = date("Y-m-d H:i:s", strtotime($input["leave"]["leave_from"][$s]));

                            $to = date("Y-m-d H:i:s", strtotime($input["leave"]["leave_to"][$s]));

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to);

                            $full_day_leave1 = $this->leave_model->get_user_leaves_for_diff($user_id, $from);

                            $check = 1;



                            if (isset($full_day_leave1) && !empty($full_day_leave1)) {

                                foreach ($full_day_leave1 as $fd) {

                                    $diff_d = $this->dateTimeDiff(new DateTime($fd["leave_from"]), new DateTime($fd["leave_to"]));

                                    if ($diff_d->h == 0)
                                        $check = 0;
                                }
                            }



                            if (empty($leaves_applied) && $check != 0) {

                                $date1 = new DateTime($from);

                                $date2 = new DateTime($to);

                                $input['leave']['type'][$s] = 3;

                                $interval = $this->dateTimeDiff($date1, $date2);

                                $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'permission');

                                if ($leave_type[0]['permission'] <= 0) {

                                    $lop = 1;
                                }

                                $new_value = $leave_type[0]['permission'] - ($interval->h);

                                if ($lop == 0)
                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, 'permission', $new_value);

                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'], "approved" => $approved, "applied_by" => $admin_id);

                                if ($approved == 1)
                                    $leave_val["approved_by"] = $admin_id;

                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                $mail["leave_from"] = $from;

                                $mail["leave_to"] = $to;

                                $mail["type"] = "Permission";

                                $mail["leave_type"] = $input['leave']['type'];

                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                $this->email_notif->send_mail_notif("apply_leave", $mail);
                            }

                            else {

//                                $this->session_messages->add_message('warning', "Already Leave applied for this day");

                                $data["error"] = 1;
                            }
                        }
                    }
                }

                redirect($this->config->item('base_url') . "attendance/add_attendance/" . $user_id);
            }
        } else {


//            $filter = $this->session_view->get_session(null, null);

            if (isset($filter) && !empty($filter)) {

                $data["year"] = $filter["year"];

                $data["month"] = $filter["month"];

                $data["start_date"] = $filter["start_date"];

                $data["end_date"] = $filter["end_date"];

                $data["month_start_date"] = $filter["month_start_date"];

                $data["month_end_date"] = $filter["month_end_date"];

                // week end date

                $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

                $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

                $next_date2 = new DateTime($last_saturday . ' +6 day');

                //  $data["week_end_date"] = $next_date2->format('Y-m-d');
                $week_date = date("t", strtotime($data["month_end_date"]));
                $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

                // week start date

                $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

                $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

                //  if (ltrim($last_saturday_date, '0') == 8)
                $last_saturday_date = 01;

                $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;
            } else {

                $data["year"] = date('Y');

                $data["month"] = date('m');

                $day = $data["month_starting_date"];

                if ($data["month_starting_date"] == 1) {

                    if ($data["month"] != 12) {


                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = $data["year"] . "-" . ($data["month"] + 1) . "-" . $day;
                    } else {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = ($data["year"]) . "-12-31";
                    }
                } else {

                    if ($data["month"] != 12) {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = $data["year"] . "-" . ($data["month"] + 1) . "-" . ($day - 1);
                    } else {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = ($data["year"] + 1) . "-1-" . ($day - 1);
                    }
                }



                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $data["month"], $data["year"]);

                $data["month_start_date"] = $data["year"] . "-" . $data["month"] . "-1";

                $data["month_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $days_in_month;

                // week end date

                $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

                $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

                $next_date2 = new DateTime($last_saturday . ' +6 day');

                //   $data["week_end_date"] = $next_date2->format('Y-m-d');
                $week_date = date("t", strtotime($data["month_end_date"]));
                $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

                // week start date

                $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

                $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

                // if (ltrim($last_saturday_date, '0') == 8)
                $last_saturday_date = 01;

                $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;
            }

            if (strtotime($data["start_date"]) < strtotime($data["month_start_date"])) {

                $start_date_val = $data["start_date"];
            } else {

                $start_date_val = $data["month_start_date"];
            }



            if ($joined_date[0] == $data["year"]) {

                if ($joined_date[1] = $data["month"]) {

                    $start_date_val = $data["doj"][0]["date"];
                }
            }



            $data["user_salary"] = $this->user_salary_model->get_user_salary_by_user_id($user_id, $start_date_val);



            if ($data["user_salary"][0]["type"] == "monthly") {

                $data["attendance"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["start_date"], $data["end_date"], $data["dept"][0]["department"]);
            } else {

                $data["attendance"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"], $data["dept"][0]["department"]);
            }
        }



        $data["department"] = $this->user_department_model->get_department_by_user_id($user_id);

        $day_value = $data["year"] . "-" . $data["month"] . "-1";



        if ($joined_date[0] == $data["year"]) {

            if ($joined_date[1] = $data["month"]) {

                $day_value = $data["doj"][0]["date"];
            }
        }



        $user_id = 1;
        $data["shift_first"] = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $day_value);




        $data["user_id"] = $user_id;


        $data["available"] = $this->available_leaves_model->get_user_leaves_by_user_id($user_id);

        $data["attendance_threshold"] = $this->options_model->get_option_by_name("attendance_threshold");

        $this->template->write_view('content', 'attendance/add_attendance', $data);

        $this->template->render();
    }

    function add_attendance_for_day() {

        // $this->template->write('scripts', 'attendance', TRUE);

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/shift_model');

        $this->load->model('attendance_model');

        $this->load->model('masters/available_leaves_model');

        $this->load->model('masters/options_model');

        $this->load->model('leave_model');

        $this->load->model('masters/shift_model');

        $this->load->model('masters/user_shift_model');

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/user_salary_model');

        $this->load->model('masters/user_history_model');

        $this->load->model('masters/holidays_model');

        $this->load->model('masters/users_model');

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/user_roles_model');

        $this->load->model('masters/users_model');


        $session = $this->session_view->get_session('attendance', 'daily_attendance');

//        print_r($session);
//        exit;
        //   $data["roles"] = $this->user_roles_model->get_user_role($this->user_auth->get_user_id());

        $data["enable_earned_leave"] = $this->options_model->get_option_by_name("enable_earned_leave");

        $options = array('week_starting_day', 'month_starting_date', 'saturday_holiday');

        $filter = array();


        $data["users"] = $filter["user_id"];

        $data["available"] = array();

        $available = $this->available_leaves_model->get_user_leaves_by_user_id($data["users"]);

        if (isset($available) && !empty($available)) {

            foreach ($available as $avl)
                $data["available"][$avl["user_id"]] = $avl;
        }

        //$this->pre_print->view($data);

        $data["day_value"] = $filter["start_date"];

        $settings = $this->options_model->get_option_by_name($options);

        $data["name"] = $this->users_model->get_user_name_by_user_id($data["users"]);



        if (isset($settings) && !empty($settings)) {

            foreach ($settings as $key => $set) {

                $data[$set["key"]] = $set["value"];
            }
        }



        if ($this->input->post('save')) {

            $input = $this->input->post();


            if (isset($input) && !empty($input)) {

                $attendance = $input["attendance"];

                //$this->pre_print->viewExit($input);

                $in_set = 0;

                for ($i = 0; $i < count($input["user_id"]); $i++) {

                    $new_val = date("Y-m-d", strtotime($input["attendance_date"][$i])) . " 00:00:00";



                    $user_id_value = $input["user_id"][$i];



                    $out_time = ($time2 > 1403029800) ? $input["attendance"]["out"][$user_id_value] : NULL;

                    $in_time = ($input["attendance"]["in"][$user_id_value] == "00:00" || $input["attendance"]["in"][$user_id_value] == "00:00:00") ? NULL : $input["attendance"]["in"][$user_id_value];

                    $out_time = ($input["attendance"]["out"][$user_id_value] == "00:00" || $input["attendance"]["out"][$user_id_value] == "00:00:00") ? NULL : $input["attendance"]["out"][$user_id_value];



                    if ($in_time != NULL) {

                        $att_arr = array("created" => $new_val, "in" => $input["attendance"]["in"][$user_id_value], "out" => $out_time, "user_id" => $input["user_id"][$i], "ip" => gethostbyname(trim(`hostname`)));



                        $att_id = $this->attendance_model->insert_attendance($att_arr);



                        if (isset($input["break"]["in_time"]) && !empty($input["break"]["in_time"])) {

                            $break_count = count($input["break"]["in_time"][$user_id_value]);

                            for ($s = 0; $s < $break_count; $s++) {

                                $br_in_time = ($input["break"]["in_time"][$user_id_value][$s] == "00:00" || $input["break"]["in_time"][$user_id_value][$s] == "00:00:00") ? NULL : $input["break"]["in_time"][$user_id_value][$s];

                                $br_out_time = ($input["break"]["out_time"][$user_id_value][$s] == "00:00" || $input["break"]["out_time"][$user_id_value][$s] == "00:00:00") ? NULL : $input["break"]["out_time"][$user_id_value][$s];

                                if ($br_in_time != NULL) {

                                    $break = array("in_time" => $input["break"]["in_time"][$user_id_value][$s], "out_time" => $br_out_time, "type" => "break", "attendance_id" => $att_id);



                                    $this->attendance_model->insert_break_values($break);
                                }
                            }
                        }
                    } else
                        $inset = 1;
                }
            }

//            if ($inset == 0)
//                $this->session_messages->add_message('success', 'Attendance added');
//            else
//                $this->session_messages->add_message('error', 'Attendance not updated');

            redirect($this->config->item('base_url') . "attendance/daily_attendance");
        }


//        echo '<pre>22';
//        print_r($filter);
//        exit;
        if ($this->input->post("apply")) {

            $input = $this->input->post();

            $data["input"] = $input;



            $admin_id = $this->user_auth->get_user_id();



            $mail = array();

            $admin_mail = $this->users_model->get_user_mail_id_by_user_id($admin_id);

            $mail["admin"] = $admin_mail[0]["email"];

            $mail["admin_name"] = $admin_mail[0]["name"];



            if (isset($input["leave"]) && !empty($input["leave"])) {//isset start
                $lop = 0;

                $approved = 0;

                $from = '';

                $to = '';

                $type = "";

                for ($s = 0; $s < count($data["users"]); $s++) {

                    $user_id = $input["leave_user_id"][$s];

                    $department_head = $this->user_department_model->get_user_dept_head_by_userid($user_id);

                    if (isset($department_head) && !empty($department_head)) {

                        $mail["department_head"] = $department_head[0]["email"];

                        $mail["head_name"] = $department_head[0]["head_name"];
                    }

                    $user_mail = $this->users_model->get_user_mail_id_by_user_id($user_id);

                    $mail["user"] = $user_mail[0]["email"];

                    $mail["user_name"] = $user_mail[0]["name"];

                    $mail["user_id"] = $user_id;

                    if (isset($input['leave']['leave_type'][$s]) && $input['leave']['leave_type'][$s] != "") {

                        if ($input['leave']['leave_type'][$s] == 1 || $input['leave']['leave_type'][$s] == 2) {

                            if ($input['leave']['type'][$s] == 1)
                                $type = 'sick leave';

                            else if ($input['leave']['type'][$s] == 2)
                                $type = 'casual leave';

                            $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, $type);
                        }



                        if (isset($input["leave"]["lop"][$s])) {

                            $lop = 1;
                        }

                        if (isset($input["leave"]["approved"][$s])) {

                            $approved = $input["leave"]["approved"][$s];
                        }

                        if ($input["leave"]["leave_type"][$s] == 1) {

                            $date_start = $date_end = $break_start = $break_end = '';

                            $from_one = $input["leave"]["date"][$s];





                            $shift_id = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $from_one);

                            if (isset($shift_id) && !empty($shift_id)) {

                                $shift_time = $this->shift_model->get_regular_and_lunch_by_shift_id($shift_id[0]['shift_id']);

                                if (isset($shift_time) && !empty($shift_time)) {



                                    foreach ($shift_time as $s_time) {

                                        if ($s_time["type"] == 'regular') {

                                            $date_start = $s_time["from_time"];

                                            $date_end = $s_time["to_time"];
                                        }

                                        if ($s_time["type"] == "lunch") {

                                            $break_start = $s_time["from_time"];

                                            $break_end = $s_time["to_time"];
                                        }
                                    }
                                }
                            }

                            if ($input["leave"]["session"][$s] == 1) {

                                $from = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $date_start;



                                $to = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $break_start;
                            } elseif ($input["leave"]["session"][$s] == 2) {

                                $from = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $break_end;



                                $to = date("Y-m-d", strtotime($input["leave"]["date"][$s])) . " " . $date_end;
                            }



                            if (isset($leave_type[0]['available_casual_leave'])) {

                                if ($leave_type[0]['available_casual_leave'] <= 0)
                                    $lop = 1;

                                $new_value = $leave_type[0]['available_casual_leave'] - 0.5;
                            }

                            else if (isset($leave_type[0]['available_sick_leave'])) {

                                if ($leave_type[0]['available_sick_leave'] <= 0)
                                    $lop = 1;

                                $new_value = $leave_type[0]['available_sick_leave'] - 0.5;
                            }

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to);

                            $full_day_leave1 = $this->leave_model->get_user_leaves_for_diff($user_id, $from);

                            $check = 1;

                            if (isset($full_day_leave1) && !empty($full_day_leave1)) {

                                foreach ($full_day_leave1 as $fd) {

                                    $diff_d = $this->dateTimeDiff(new DateTime($fd["leave_from"]), new DateTime($fd["leave_to"]));

                                    if ($diff_d->h == 0)
                                        $check = 0;
                                }
                            }

                            if (empty($leaves_applied) && $check != 0) {



                                if ($lop == 0)
                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);



                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                if ($approved == 1)
                                    $leave_val["approved_by"] = $admin_id;

                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                $mail["leave_from"] = $from;

                                $mail["leave_to"] = $to;

                                $mail["type"] = "Half day leave";

                                if ($input["leave"]["session"] == 1)
                                    $mail["session"] = "Session1";
                                else
                                    $mail["session"] = "Session2";

                                $mail["leave_type"] = $input['leave']['type'];

                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                $this->session_messages->add_message('success', 'Leave applied successfully');
                            }

                            else {

//                                $this->session_messages->add_message('warning', 'Already leave applied for this day');

                                $data["error"] = 1;
                            }
                        } elseif ($input["leave"]["leave_type"][$s] == 2 || $input["leave"]["leave_type"][$s] == 4 || $input["leave"]["leave_type"][$s] == 6) {

                            //echo $input["leave"]["leave_type"][$s];
                            //$this->pre_print->viewExit($input);

                            $month_from = explode("-", $input["leave"]["leave_from"][$s]);

                            $month_to = explode("-", $input["leave"]["leave_to"][$s]);



                            $from = date("Y-m-d", strtotime($input["leave"]["leave_from"][$s])) . " 00:00:00";

                            $to = date("Y-m-d", strtotime($input["leave"]["leave_to"][$s])) . " 00:00:00";

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to, 1);

                            if (empty($leaves_applied)) {



                                $date1 = new DateTime($from);

                                $date2 = new DateTime($to);

                                $interval = $this->dateTimeDiff($date1, $date2);

                                $month_differ = 0;

                                if ($month_from[1] != $month_to[1]) {

                                    $days_in_from = cal_days_in_month(CAL_GREGORIAN, $month_from[1], $month_from[2]);

                                    $days_in_to = cal_days_in_month(CAL_GREGORIAN, $month_to[1], $month_to[2]);

                                    $month_differ = 1;

                                    $days_in_first = $days_in_from - $month_from[0];

                                    $days_in_second = $month_to[1];

                                    $interval->d = $days_in_first + $days_in_second;

                                    $first_from = new DateTime(date("Y-m-d", strtotime($input["leave"]["leave_from"][$s])) . " 00:00:00");

                                    $first_to = new DateTime(date("Y-m-d", strtotime($month_from[2] . "-" . $month_from[1] . "-" . $days_in_from)) . " 00:00:00");

                                    $second_from = new DateTime(date("Y-m-d", strtotime($month_to[2] . "-" . $month_to[1] . "-1")) . " 00:00:00");

                                    $second_to = new DateTime(date("Y-m-d", strtotime($month_to[2] . "-" . $month_to[1] . "-" . $month_to[0])) . " 00:00:00");

                                    $interval_from = $this->dateTimeDiff($first_from, $first_to);

                                    $interval_to = $this->dateTimeDiff($second_from, $second_to);
                                }

                                if ($input["leave"]["leave_type"][$s] == 2) {



                                    if (isset($leave_type[0]['available_casual_leave'])) {



                                        if ($lop == 0) {



                                            if ($leave_type[0]['available_casual_leave'] > 0) {

                                                //echo $month_differ;

                                                if ($leave_type[0]['available_casual_leave'] < ($interval->d + 1)) {

                                                    $new_value = 0;

                                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                    if ($month_differ == 0) {

                                                        $next = "";



                                                        $next = new DateTime($from . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //echo "<pre>";



                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from;

                                                        $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        //print_r($leave_val);

                                                        $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave']) . ' day');



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //print_r($leave_val);



                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $to;

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    elseif ($month_differ == 1) {



                                                        $next = "";





                                                        if ($leave_type[0]['available_casual_leave'] < ($interval_from->d + 1)) {



                                                            $next = "";



                                                            $next = new DateTime($first_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;



                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            //print_r($leave_val);

                                                            $from = new DateTime($next->format('Y-m-d H:i:s') . ' + 1 day');



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

                                                            //print_r($leave_val);

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }

                                                        else {

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            //print_r($leave_val);

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $firest_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }

                                                        if ($leave_type[0]['available_casual_leave'] > 0) {

                                                            if ($leave_type[0]['available_casual_leave'] < ($interval_to->d + 1)) {

                                                                $next = "";



                                                                $next = new DateTime($second_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave'] - 1) . ' day');



                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;



                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);



                                                                $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_casual_leave']) . ' day');



                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;

                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                                                //print_r($leave_val);
                                                            }

                                                            else {

                                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                                if ($approved == 1)
                                                                    $leave_val["approved_by"] = $admin_id;

                                                                //print_r($leave_val);

                                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                                $mail["type"] = "Leave";

                                                                $mail["leave_type"] = $input['leave']['type'];

                                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                                            }
                                                        }

                                                        else {



                                                            //print_r($second_to);



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            //print_r($leave_val);

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }
                                                    }
                                                }

                                                else {



                                                    $new_value = $leave_type[0]['available_casual_leave'] - ($interval->d + 1);

                                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                    if ($month_differ == 0) {



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //print_r($leave_val);

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from;

                                                        $mail["leave_to"] = $to;

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    else if ($month_differ == 1) {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //print_r($leave_val);

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //	print_r($leave_val);

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }
                                                }
                                            }

                                            else {



                                                if ($month_differ == 0) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //	print_r($leave_val);

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                else if ($month_differ == 1) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //print_r($leave_val);



                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //print_r($leave_val);

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                                }
                                            }
                                        }

                                        else {





                                            if ($month_differ == 0) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $from;

                                                $mail["leave_to"] = $to;

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }

                                            else if ($month_differ == 1) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;

                                                //print_r($leave_val);

                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                                //$leave_type[0]['available_casual_leave'] -= $interval_from->d+1;
                                            }
                                        }
                                    }
                                }

                                if (isset($leave_type[0]['available_sick_leave'])) {

                                    if ($lop == 0) {

                                        //echo $leave_type[0]['available_sick_leave'];

                                        if ($leave_type[0]['available_sick_leave'] > 0) {

                                            //echo $month_differ;

                                            if ($leave_type[0]['available_sick_leave'] < ($interval->d + 1)) {

                                                $new_value = 0;

                                                $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                if ($month_differ == 0) {

                                                    $next = "";



                                                    $next = new DateTime($from . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');



                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //echo "<pre>";

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                    //print_r($leave_val);

                                                    $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave']) . ' day');



                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //print_r($leave_val);



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                elseif ($month_differ == 1) {



                                                    $next = "";





                                                    if ($leave_type[0]['available_sick_leave'] < ($interval_from->d + 1)) {



                                                        $next = "";



                                                        $next = new DateTime($first_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;



                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                        //print_r($leave_val);

                                                        $from = new DateTime($next->format('Y-m-d H:i:s') . ' + 1 day');



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;



                                                        //print_r($leave_val);

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }

                                                    else {

                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;

                                                        //print_r($leave_val);

                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');

                                                        $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;
                                                    }

                                                    if ($leave_type[0]['available_sick_leave'] > 0) {

                                                        if ($leave_type[0]['available_sick_leave'] < ($interval_to->d + 1)) {

                                                            $next = "";



                                                            $next = new DateTime($second_from->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave'] - 1) . ' day');



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $next->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;



                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $next->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                            //print_r($leave_val);

                                                            $from = new DateTime($next->format('Y-m-d H:i:s') . ' + ' . floor($leave_type[0]['available_sick_leave']) . ' day');



                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }

                                                        else {

                                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                            if ($approved == 1)
                                                                $leave_val["approved_by"] = $admin_id;

                                                            //print_r($leave_val);

                                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                            $mail["type"] = "Leave";

                                                            $mail["leave_type"] = $input['leave']['type'];

                                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                                        }
                                                    }

                                                    else {



                                                        $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                        if ($approved == 1)
                                                            $leave_val["approved_by"] = $admin_id;



                                                        $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                        $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                        $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                        $mail["type"] = "Leave";

                                                        $mail["leave_type"] = $input['leave']['type'];

                                                        $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                        $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                        $this->session_messages->add_message('success', 'Leave applied successfully');
                                                    }
                                                }
                                            }

                                            else {

                                                $new_value = $leave_type[0]['available_sick_leave'] - ($interval->d + 1);

                                                $this->available_leaves_model->update_user_leaves_by_type($user_id, $type, $new_value);

                                                if ($month_differ == 0) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $from;

                                                    $mail["leave_to"] = $to;

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }

                                                else if ($month_differ == 1) {

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;



                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                    $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                    if ($approved == 1)
                                                        $leave_val["approved_by"] = $admin_id;

                                                    //	print_r($leave_val);

                                                    $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                    $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                                    $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                                    $mail["type"] = "Leave";

                                                    $mail["leave_type"] = $input['leave']['type'];

                                                    $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                    $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                    $this->session_messages->add_message('success', 'Leave applied successfully');
                                                }
                                            }
                                        }

                                        else {

                                            if ($month_differ == 0) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $from;

                                                $mail["leave_to"] = $to;

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }

                                            else if ($month_differ == 1) {

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => 1, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                                if ($approved == 1)
                                                    $leave_val["approved_by"] = $admin_id;



                                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                                $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                                $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                                $mail["type"] = "Leave";

                                                $mail["leave_type"] = $input['leave']['type'];

                                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                                $this->email_notif->send_mail_notif("apply_leave", $mail);

                                                $leave_type[0]['available_sick_leave'] -= $interval_from->d + 1;

//                                                $this->session_messages->add_message('success', 'Leave applied successfully');
                                            }
                                        }
                                    }

                                    else {



                                        if ($month_differ == 0) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_type[0]['available_casual_leave'] -= $interval_from->d + 1;

//                                            $this->session_messages->add_message('success', 'Leave applied successfully');
                                        }
                                    }
                                }

                                elseif ($input["leave"]["leave_type"][$s] == 4) {

                                    //echo $month_differ;



                                    $input['leave']['type'][$s] = 4;

                                    $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'compoff');



                                    if ($interval->d + 1 > $leave_type[0]['comp_off']) {

//                                        $this->session_messages->add_message('warning', 'Your compoff days are less than the requested number of days');

                                        $data["error"] = 1;
                                    } else {



                                        $new_value = $leave_type[0]['comp_off'] - ($interval->d + 1);

                                        $this->available_leaves_model->update_user_leaves_by_type($user_id, 'compoff', $new_value);

                                        if ($month_differ == 0) {



                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);



                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Comp off leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

                                            $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $approved, "applied_by" => $admin_id);

                                            if ($approved == 1)
                                                $leave_val["approved_by"] = $admin_id;



                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Comp off";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);

//                                            $this->session_messages->add_message('success', 'Comp off leave applied successfully');
                                        }
                                    }
                                }

                                elseif ($input["leave"]["leave_type"][$s] == 6) {



                                    //$this->pre_print->viewExit($input);

                                    $input['leave']['type'][$s] = 6;

                                    $lop = 0;

                                    $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'available_earned_leave');

                                    if ($interval->d + 1 > $leave_type[0]['available_earned_leave']) {



//                                        $this->session_messages->add_message('warning', "Your earned leaves are less than the requested number of days");

                                        $data["error"] = 1;
                                    } else {

                                        $new_value = $leave_type[0]['available_earned_leave'] - ($interval->d + 1);

                                        if ($lop == 0)
                                            $this->available_leaves_model->update_user_leaves_by_type($user_id, 'available_earned_leave', $new_value);

                                        if ($month_differ == 0) {

                                            if ($input["leave"]["approved"][$s] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);



                                            $mail["leave_from"] = $from;

                                            $mail["leave_to"] = $to;

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['type'][$s];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);



//                                            $this->session_messages->add_message('success', 'Earned off leave applied successfully');
                                        }

                                        else if ($month_differ == 1) {

                                            if ($input["leave"]["approved"] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $first_from->format('Y-m-d H:i:s'), "leave_to" => $first_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);



                                            $mail["leave_from"] = $first_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $first_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['leave_type'][$s];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);



                                            if ($input["leave"]["approved"][$s] == 1)
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id, "approved_by" => $admin_id);
                                            else
                                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $second_from->format('Y-m-d H:i:s'), "leave_to" => $second_to->format('Y-m-d H:i:s'), "lop" => $lop, "type" => $input['leave']['leave_type'][$s], "approved" => $input["leave"]["approved"][$s], "applied_by" => $admin_id);

                                            $id_leave = $this->leave_model->insert_user_leaves($leave_val);



                                            $mail["leave_from"] = $second_from->format('Y-m-d H:i:s');

                                            $mail["leave_to"] = $second_to->format('Y-m-d H:i:s');

                                            $mail["type"] = "Earned Leave";

                                            $mail["leave_type"] = $input['leave']['type'];

                                            $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                            $this->email_notif->send_mail_notif("apply_leave", $mail);





//                                            $this->session_messages->add_message('success', 'Earned leave applied successfully');
                                        }
                                    }
                                }
                            }



                            else {

//                                $this->session_messages->add_message('warning', "Already Leave applied for this day");

                                $data["error"] = 1;
                            }
                        } elseif ($input["leave"]["leave_type"][$s] == 3) {

                            $from = date("Y-m-d H:i:s", strtotime($input["leave"]["leave_from"][$s]));

                            $to = date("Y-m-d H:i:s", strtotime($input["leave"]["leave_to"][$s]));

                            $leaves_applied = $this->leave_model->get_user_leaves_by_date($user_id, $from, $to);



                            $full_day_leave1 = $this->leave_model->get_user_leaves_for_diff($user_id, $from);

                            $check = 1;



                            if (isset($full_day_leave1) && !empty($full_day_leave1)) {

                                foreach ($full_day_leave1 as $fd) {

                                    $diff_d = $this->dateTimeDiff(new DateTime($fd["leave_from"]), new DateTime($fd["leave_to"]));

                                    if ($diff_d->h == 0)
                                        $check = 0;
                                }
                            }

                            if (empty($leaves_applied) && $check != 0) {

                                $date1 = new DateTime($from);

                                $date2 = new DateTime($to);

                                $input['leave']['type'][$s] = 3;

                                $interval = $this->dateTimeDiff($date1, $date2);

                                $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, 'permission');

                                if ($leave_type[0]['permission'] <= 0) {

                                    $lop = 1;
                                }

                                $new_value = $leave_type[0]['permission'] - ($interval->h);

                                if ($lop == 0)
                                    $this->available_leaves_model->update_user_leaves_by_type($user_id, 'permission', $new_value);

                                $leave_val = array("user_id" => $user_id, "reason" => $input['leave']['reason'][$s], "leave_from" => $from, "leave_to" => $to, "lop" => $lop, "type" => $input['leave']['leave_type'], "approved" => $approved, "applied_by" => $admin_id);

                                if ($approved == 1)
                                    $leave_val["approved_by"] = $admin_id;

                                $id_leave = $this->leave_model->insert_user_leaves($leave_val);

                                $mail["leave_from"] = $from;

                                $mail["leave_to"] = $to;

                                $mail["type"] = "Permission";

                                $mail["leave_type"] = $input['leave']['type'];

                                $mail["link"] = $this->config->item('base_url') . "users/leaves/view_user_leave/" . $id_leave;

                                $this->email_notif->send_mail_notif("apply_leave", $mail);
                            }

                            else {

//                                $this->session_messages->add_message('warning', "Already Leave applied for this day");

                                $data["error"] = 1;
                            }
                        }

                        //echo $new_value;
                    }
                }

                /* print_r($leave_val);

                  exit; */

                redirect($this->config->item('base_url') . "attendance/daily_attendance/");
            }
        }//post end



        $data["user_id"] = $this->user_auth->get_user_id();



        $data["attendance_threshold"] = $this->options_model->get_option_by_name("attendance_threshold");



        $this->template->write_view('content', 'attendance/add_attendance_for_day', $data);

        $this->template->render();
    }

    public function view_attendance($user_id) {


//        $this->template->write('scripts', 'attendance', TRUE);

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/users_model');

        $this->load->model('masters/options_model');

        $this->load->model('masters/shift_model');

        $this->load->model('attendance_model');

        $this->load->model('masters/available_leaves_model');

        $this->load->model('masters/user_salary_model');

        $this->load->model('leave_model');

        $this->load->model('pieces_model');

        $this->load->model('masters/user_history_model');

        $this->load->model('masters/holidays_model');

        $this->load->model('masters/user_shift_model');

        $this->load->model('masters/users_model');

        $this->load->model('masters/user_roles_model');



//        $data["roles"] = $this->user_roles_model->get_user_role($this->user_auth->get_user_id());

        $options = array('week_starting_day', 'month_starting_date', 'saturday_holiday');
        $data["threshold"] = $this->options_model->get_options_by_type('attendance_threshold');

        $admin_id = $this->user_auth->get_user_id();

        $mail = array();

        $admin_mail = $this->users_model->get_user_mail_id_by_user_id($admin_id);

        $mail["admin"] = $admin_mail[0]["email"];

        $mail["admin_name"] = $admin_mail[0]["name"];

        $mail["user_id"] = $user_id;

        $data["name"] = $this->users_model->get_user_name_by_user_id($user_id);


        $settings = $this->options_model->get_option_by_name($options);

        $data["doj"] = $this->user_history_model->get_history_by_user_id_and_type($user_id, 'doj');

        $data["dol"] = $this->user_history_model->get_history_by_user_id_and_type($user_id, 'dol');

        $data["dept"] = $this->user_department_model->get_department_by_user_id($user_id);


        $joined_date = explode("-", $data["doj"][0]["date"]);

        if (isset($settings) && !empty($settings)) {

            foreach ($settings as $key => $set) {

                $data[$set["key"]] = $set["value"];
            }
        }

        $ws_day = "";

        switch ($data["week_starting_day"]) {

            case 0 :

                $ws_day = "sunday";

                break;

            case 1 :

                $ws_day = "monday";

                break;

            case 2 :

                $ws_day = "tuesday";

                break;

            case 3 :

                $ws_day = "wednesday";

                break;

            case 4 :

                $ws_day = "thursday";

                break;

            case 5 :

                $ws_day = "friday";

                break;

            case 6 :

                $ws_day = "saturday";

                break;
        }

        if ($this->input->post('update')) {

            $year = $this->input->post("year");

            $month = $this->input->post("month");

            //$this->pre_print->viewExit($this->input->post());

            $in_time = $this->input->post("in_time");

            $day = $this->input->post("day_value");

            $out_time = $this->input->post("out_time");

            $break = $this->input->post("break");

            $break_in = $break["in_time"];

            $break_out = $break["out_time"];

            //$this->pre_print->viewExit($this->input->post());

            if (isset($day) && !empty($day)) {

                for ($k = 0; $k < count($day); $k++) {

                    $day_value = date('Y-m-d', strtotime($day[$k]));

                    $current_att = $this->attendance_model->get_user_attendance_by_userid_and_date($user_id, $day_value);

                    $attendance_id = 0;

                    $null_data = 0;

                    if (isset($current_att) && !empty($current_att)) {

                        $attendance_id = $current_att[0]["id"];

                        if ($in_time[$day[$k]] != "00:00:00" && $in_time[$day[$k]] != "00:00" && $in_time[$day[$k]] != "")
                            $current_data["in"] = $in_time[$day[$k]];
                        else
                            $current_data["in"] = NULL;

                        if (isset($current_data["in"]) && ($out_time[$day[$k]] != "00:00:00" && $out_time[$day[$k]] != "00:00" && $out_time[$day[$k]] != ""))
                            $current_data["out"] = $out_time[$day[$k]];
                        else
                            $current_data["out"] = NULL;



                        if (isset($current_data) && !empty($current_data)) {

                            //echo "em1";

                            if ($current_data["in"] == NULL && $current_data["out"] == NULL) {

                                $null_data = 1;

                                //echo "enter1";

                                $this->attendance_model->delete_updation_for_att_id($attendance_id);

                                $this->attendance_model->delete_break_details_by_att_id($attendance_id);

                                $this->attendance_model->delete_attendance_by_id($attendance_id);



                                $attendance_id = 0;
                            } else {

                                //echo "enter2";

                                $this->attendance_model->update_attendance($attendance_id, $current_data);

                                //$this->pre_print->view($current_data);

                                $update_data = array("attendance_id" => $attendance_id, "updated" => date('Y-m-d H:i:s'));

                                $this->attendance_model->insert_updation_for_att_id($update_data);

                                $this->attendance_model->delete_break_details_by_att_id($attendance_id);
                            }
                        } else {

//                            $this->session_messages->add_message('error', 'Invalid inputs');
                        }
                    } else {





                        if ($in_time[$day[$k]] != "00:00:00" && $in_time[$day[$k]] != "00:00" && $in_time[$day[$k]] != "")
                            $current_data["in"] = $in_time[$day[$k]];

                        if (isset($current_data["in"]) && ($out_time[$day[$k]] != "00:00:00" && $out_time[$day[$k]] != "00:00" && $out_time[$day[$k]] != ""))
                            $current_data["out"] = $out_time[$day[$k]];
                        else
                            $current_data["out"] = NULL;

                        if (isset($current_data) && !empty($current_data)) {

                            //echo "em";

                            $current_data["user_id"] = $user_id;

                            $current_data["created"] = $day_value . " " . date('H:i:s');

                            $current_data["ip"] = $_SERVER['REMOTE_ADDR'];

                            //$this->pre_print->view($current_data);

                            $attendance_id = $this->attendance_model->insert_attendance($current_data);
                        }
                    }

                    if ($attendance_id != 0) {

                        if (isset($break_in[$day[$k]]) && !empty($break_in[$day[$k]])) {

                            for ($i = 0; $i < count($break_in[$day[$k]]); $i++) {

                                if ($break_in[$day[$k]][$i] != "00:00:00" && $break_in[$day[$k]][$i] != "00:00" && $break_in[$day[$k]][$i] != "")
                                    $break_data["in_time"] = $break_in[$day[$k]][$i];

                                if (isset($break_data ["in_time"]) && ($break_out[$day[$k]][$i] != "00:00:00" && $break_out[$day[$k]][$i] != "00:00" && $break_out[$day[$k]][$i] != ""))
                                    $break_data["out_time"] = $break_out[$day[$k]][$i];
                                else
                                    $break_data["out_time"] = NULL;

                                if (isset($break_data) && !empty($break_data)) {

                                    $break_data["attendance_id"] = $attendance_id;

                                    $break_data["type"] = "break";

                                    //$this->pre_print->view($break_data);

                                    $this->attendance_model->insert_break_values($break_data);
                                }
                            }
                        }
                    }
                }
            }

            //	exit;
//            $this->session_messages->add_message('success', "Attendance updated successfully");



            redirect($this->config->item('base_url') . "attendance/view_attendance/" . $user_id);
        } else if ($this->input->post("save", TRUE)) {

            $att_id = $this->input->post("att_id");

            $pieces = $this->input->post("pieces");

            if (isset($att_id) && !empty($att_id)) {

                foreach ($att_id as $val) {

                    $created = date('Y-m-d', strtotime($val));

                    $this->pieces_model->delete_pieces_by_created($created);

                    $att_pieces = array("user_id" => $user_id, "created" => $created, "pieces" => $pieces);

                    $this->pieces_model->insert_pieces($att_pieces);
                }
            }

            $data["attendance_month"] = $this->attendance_model->get_attendance_by_month_year_and_user_id(date('Y'), date('m'), $user_id);




            $data["leave"] = $this->leave_model->get_user_leaves_by_month_year_and_user_id(date('Y'), date('m'), $user_id);
        } else if ($this->input->post('go')) {

            $filter = $this->input->post();



            if (isset($filter["go"]))
                unset($filter["go"]);

            if (isset($filter["in_time"]))
                unset($filter["in_time"]);

            if (isset($filter["out_time"]))
                unset($filter["out_time"]);

            if (isset($filter["break"]))
                unset($filter["break"]);

//            $this->session_view->add_session(null, null, $filter);



            $data["year"] = $filter["year"];

            $data["month"] = $filter["month"];

            $data["start_date"] = $filter["start_date"];

            $data["end_date"] = $filter["end_date"];



            $data["month_start_date"] = $filter["month_start_date"];

            $data["month_end_date"] = $filter["month_end_date"];

            // week end date

            $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

            $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

            $next_date2 = new DateTime($last_saturday . ' +6 day');

            //   $data["week_end_date"] = $next_date2->format('Y-m-d');
            $week_date = date("t", strtotime($data["month_end_date"]));
            $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

            // week start date

            $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

            $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

            //if (ltrim($last_saturday_date, '0') == 8)
            $last_saturday_date = 01;

            $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;

            if (strtotime($data["start_date"]) < strtotime($data["month_start_date"])) {

                $start_date_val = $data["start_date"];
            } else {

                $start_date_val = $data["month_start_date"];
            }

            if ($joined_date[0] == $data["year"]) {

                if ($joined_date[1] = $data["month"]) {

                    $start_date_val = $data["doj"][0]["date"];
                }
            }

            $data["user_salary"] = $this->user_salary_model->get_user_salary_by_user_id($user_id, $start_date_val);

            if ($data["user_salary"][0]["type"] == "monthly") {

                $data["attendance_month"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["start_date"], $data["end_date"]);



                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["start_date"], $data["end_date"], $data["dept"][0]["department"]);
            } else {

                $data["attendance_month"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);



                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"], $data["dept"][0]["department"]);
            }
        } else {

//            $filter = $this->session_view->get_session(null, null);



            if (isset($filter) && !empty($filter)) {

                //$this->pre_print->view($filter);

                $data["year"] = $filter["year"];

                $data["month"] = $filter["month"];

                $data["start_date"] = $filter["start_date"];

                $data["end_date"] = $filter["end_date"];

                $data["month_start_date"] = $filter["month_start_date"];



                $data["month_end_date"] = $filter["month_end_date"];

                // week end date

                $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

                $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

                $next_date2 = new DateTime($last_saturday . ' +6 day');

                //  $data["week_end_date"] = $next_date2->format('Y-m-d');
                $week_date = date("t", strtotime($data["month_end_date"]));
                $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

                // week start date

                $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

                $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

                // if (ltrim($last_saturday_date, '0') == 8)
                $last_saturday_date = 01;

                $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;
            } else {

                //$this->pre_print->viewExit($filter);

                $data["year"] = date('Y');

                $data["month"] = date('m');



                $day = $data["month_starting_date"];

                if ($data["month_starting_date"] == 1) {

                    if ($data["month"] != 12) {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = $data["year"] . "-" . ($data["month"] + 1) . "-" . $day;
                    } else {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = ($data["year"]) . "-12-31";

                        //$data["end_date"]= ($data["year"]+1)."-1-".$day-1;
                    }
                } else {

                    if ($data["month"] != 12) {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = $data["year"] . "-" . ($data["month"] + 1) . "-" . ($day - 1);
                    } else {

                        $data["start_date"] = $data["year"] . "-" . $data["month"] . "-" . $day;

                        $data["end_date"] = ($data["year"] + 1) . "-1-" . ($day - 1);
                    }
                }
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, $data["month"], $data["year"]);

                $data["month_start_date"] = $data["year"] . "-" . $data["month"] . "-1";

                $data["month_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $days_in_month;

                // week end date

                $nextMonthStart = mktime(0, 0, 0, $data["month"] + 1, 1, $data["year"]);

                $last_saturday = date("Y-m-d H:i:s", strtotime("previous " . $ws_day, $nextMonthStart));

                $next_date2 = new DateTime($last_saturday . ' +6 day');

                // $data["week_end_date"] = $next_date2->format('Y-m-d');
                $week_date = date("t", strtotime($data["month_end_date"]));
                $data["week_end_date"] = $data["year"] . "-" . $data["month"] . "-" . $week_date;

                // week start date

                $nextMonthStart = mktime(0, 0, 0, $data["month"], 1, $data["year"]);

                $last_saturday_date = date("d", strtotime("first " . $ws_day, $nextMonthStart));

                // if (ltrim($last_saturday_date, '0') == 8)
                $last_saturday_date = 01;

                $data["week_start_date"] = $data["year"] . "-" . $data["month"] . "-" . $last_saturday_date;
            }

            if (strtotime($data["start_date"]) < strtotime($data["month_start_date"])) {

                $start_date_val = $data["start_date"];
            } else {

                $start_date_val = $data["month_start_date"];
            }



            if ($joined_date[0] == $data["year"]) {

                if ($joined_date[1] = $data["month"]) {

                    $start_date_val = $data["doj"][0]["date"];
                }
            }

            $data["user_salary"] = $this->user_salary_model->get_user_salary_by_user_id($user_id, $start_date_val);

            if ($data["user_salary"][0]["type"] == "monthly") {

                $data["attendance_month"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["start_date"], $data["end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["start_date"], $data["end_date"], $data["dept"][0]["department"]);
            } else {

                $data["attendance_month"] = $this->attendance_model->get_attendance_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["leave"] = $this->leave_model->get_approved_user_leaves_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"]);

                $data["holidays"] = $this->holidays_model->get_holidays_by_between_dates($user_id, $data["week_start_date"], $data["week_end_date"], $data["dept"][0]["department"]);
            }
        }

        //print_r($data["leave"]);

        $day_value = $data["year"] . "-" . $data["month"] . "-1";



        if ($joined_date[0] == $data["year"]) {

            if ($joined_date[1] = $data["month"]) {

                $day_value = $data["doj"][0]["date"];
            }
        }

        //echo $day_value;
        //print_r($filter);

        $data["shift_first"] = $this->user_shift_model->get_user_current_shift_by_user_id($user_id, $day_value);

        $data["threshold"] = $this->options_model->get_options_by_type('attendance_threshold');

        $current_date = date("Y-m-d");

        $data["today_attendance"] = $this->attendance_model->get_user_attendance_by_userid_and_date($user_id, $current_date);

        //$data["department"] = $this->user_department_model->get_department_by_user_id($user_id);
        //$data["shift"] = $this->shift_model->get_shift_by_id($data["department"][0]["shift_id"]);

        $data["user_id"] = $user_id;

        $data["user_status"] = $this->users_model->get_user_status_by_user_id($user_id);

        $data["salary_type"] = $this->user_salary_model->get_user_salary_type_by_user_id($user_id);

        //$this->pre_print->viewExit($data);
//        echo "<pre>";
//        print_r($data['attendance_month']);
//        exit;
//        echo '<pre>';
//        print_r($data);
        // exit;

        $this->template->write_view('content', 'attendance/view_attendance', $data);

        $this->template->render();
    }

    public function edit_attendance($user_id, $attendance_id) {

        $this->template->write('scripts', 'attendance', TRUE);

        //echo gettype($attendance_id);

        $this->load->model('masters/user_department_model');

        $this->load->model('masters/shift_model');

        $this->load->model('attendance_model');

        $this->load->model('masters/available_leaves_model');

        $this->load->model('leave_model');

        $data["department"] = $this->user_department_model->get_department_by_user_id($user_id);

        $data["shift"] = $this->shift_model->get_shift_by_id($data["department"][0]["shift_id"]);

        $id = explode('-', $attendance_id);

        if ($this->input->post('save')) {

            $input = $this->input->post();

            //$this->pre_print->viewExit($input);

            if (isset($input) && !empty($input)) {

                $attendance = $input["attendance"];

                for ($i = 0; $i < count($attendance["created"]); $i++) {

                    $leave = 0;

                    $new_val = date("Y-m-d", strtotime($attendance["created"][$i])) . " 00:00:00";

                    /* if(isset($attendance['leave'][$i]))

                      {

                      if($attendance['leave'][$i]!="none")

                      $leave = 1;

                      } */

                    if ($leave == 0) {



                        //echo  $attendance["created"][$i];



                        $att_arr = array("created" => $new_val, "in" => $attendance["in"][$i], "out" => $attendance["out"][$i], "user_id" => $user_id, "ip" => gethostbyname(trim(`hostname`)));

                        //print_r($att_arr);

                        if (count($id) == 1) {

                            $this->attendance_model->update_attendance($attendance_id, $att_arr);

                            $this->attendance_model->delete_break_details_by_att_id($attendance_id);

                            if (isset($input["break"]["in_time"][$i]) && !empty($input["break"]["in_time"][$i])) {

                                //echo count($input["break"]["in_time"]);

                                for ($k = 0; $k < count($input["break"]["in_time"][0]); $k++) {

                                    if (isset($input["break"]["in_time"][0]) && isset($input["break"]["out_time"][0])) {

                                        $break_val = array("in_time" => $input["break"]["in_time"][0][$k], "out_time" => $input["break"]["out_time"][0][$k], "type" => "break", "attendance_id" => $attendance_id);

                                        //print_r($break_val);

                                        $this->attendance_model->insert_break_values($break_val);
                                    }
                                }
                            }
                        } else {

                            $attendance_id = $this->attendance_model->insert_attendance($att_arr);

                            if (isset($input["break"]["in_time"]) && !empty($input["break"]["in_time"])) {

                                //echo count($input["break"]["in_time"]);

                                for ($k = 0; $k < count($input["break"]["in_time"]); $k++) {

                                    if (isset($input["break"]["in_time"][$k][0]) && isset($input["break"]["out_time"][$k][0])) {

                                        $break_val = array("in_time" => $input["break"]["in_time"][$k][0], "out_time" => $input["break"]["out_time"][$k][0], "type" => "break", "attendance_id" => $attendance_id);

                                        //print_r($break_val);

                                        $this->attendance_model->insert_break_values($break_val);
                                    }
                                }
                            }
                        }
                    } else {

                        $lop = 0;



                        if (isset($attendance["lop"][$i])) {

                            //echo $attendance["lop"][$i];

                            if ($attendance["lop"][$i] == "LOP")
                                $lop = 1;
                        }

                        if (count($id) == 1) {

                            $this->attendance_model->delete_break_details_by_att_id($attendance_id);

                            $this->attendance_model->delete_attendance_by_id($attendance_id);
                        }

                        $leave_val = array("user_id" => $user_id, "leave_from" => $new_val, "leave_to" => $new_val, "lop" => $lop, "type" => $attendance['leave'][$i]);

                        $leave_type = $this->available_leaves_model->get_user_leaves_by_type($user_id, $attendance['leave'][$i]);

                        $this->leave_model->insert_user_leaves($leave_val);



                        if (isset($leave_type[0]['available_casual_leave']))
                            $new_value = $leave_type[0]['available_casual_leave'] - 1;

                        else if (isset($leave_type[0]['available_sick_leave']))
                            $new_value = $leave_type[0]['available_sick_leave'] - 1;

                        //echo $new_value;

                        $this->available_leaves_model->update_user_leaves_by_type($user_id, $attendance["leave"][$i], $new_value);
                    }
                }
            }



            redirect($this->config->item('base_url') . "attendance/view_attendance/" . $user_id);
        }





        if (count($id) == 1) {

            $data["attendance1"] = $this->attendance_model->get_attendance_by_id($attendance_id);

            $data["attendance_id"] = $attendance_id;

            $data["break"] = $this->attendance_model->get_break_details_by_attendance_id($attendance_id);
        } else
            $data["attendance_id"] = $attendance_id;



        $data["user_id"] = $user_id;

        //$this->pre_print->viewExit($data);

        $this->template->write_view('content', 'attendance/edit_attendance', $data);

        $this->template->render();
    }

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

    public function data_migration() {

        $this->load->library('user_agent');
        if ($this->agent->is_referral()) {
            $refer = $this->agent->referrer();
        }
//echo "<pre>";print_r($refer);exit;
        $data = [];
        $data['migration_logs'] = $this->attendance_model->get_all_datamigration();
        //echo "<pre>";print_r($data);exit;
        $this->template->write_view('content', 'attendance/data_migration', $data);
        $this->template->render();
    }

    public function epushserver_run() {

        $input = $this->input->post();
        $get_last_run_date = $this->attendance_model->last_migration_log();

        $get_push_server_logs = $this->attendance_model->get_push_logs($get_last_run_date);
        if ($get_push_server_logs == 0) {
            echo 0;
        } else {
            echo 1;
        }
    }

    public function run_migration() {
        $input = $this->input->post();
        $last_log_datetime = $input['last_logdate'];
        // $last_log_datetime="2018-11-07 20:52:23";
        if ($last_log_datetime != "") {


            $explode_last_log_date = explode(' ', $last_log_datetime);
            $last_log_date = $explode_last_log_date[0];
            $last_log_time = $explode_last_log_date[1];

            $current_datetime = date('Y-m-d H:i:s');

            $explode_current_datetime = explode(' ', $current_datetime);
            $current_date = $explode_current_datetime[0];
            $current_time = $explode_current_datetime[1];

            $migration_data = [
                "log_datetime" => $current_datetime,
                "log_date" => $current_date,
                "start_time" => $current_time,
                "status" => "in_progress"
            ];
            $this->db->insert('data_migration', $migration_data);
            $insert_id = $this->db->insert_id();

            while (strtotime($last_log_date) <= strtotime($current_date)) {
                $logdata_dates[] = $last_log_date;
                $last_log_date = date("Y-m-d", strtotime("+1 days", strtotime($last_log_date)));
            }

            $device_log = "";
            $device_log_data_total = "";
            foreach ($logdata_dates as $key => $log_dates) {
                $explode_log_dates = explode('-', $log_dates);
                $month = $explode_log_dates[1];
                $year = $explode_log_dates[0];
                $table = 'devicelogs_' . $month . '_' . $year;
                $table1[] = 'devicelogs_' . $month . '_' . $year;
                $this->db->select('UserId,LogDate,Direction');
                if ($key == 0) {
                    $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $last_log_datetime . "'");
                    $this->db->where('DATE(' . $table . '.LogDate)', $log_dates);
                } else {
                    $this->db->where('DATE(' . $table . '.LogDate)', $log_dates);
                }


                $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') < '" . date('Y-m-d H:i:s') . "'");

                $device_log_data = $this->db->get($table)->result_array();
                $device_log_data_total[] = $device_log_data;
                $device_log[$log_dates][$key] = $device_log_data;

                $status = "Pending";

                $check_data = 0;

                if (count($device_log_data > 0)) {

                    if ($key == 0) {
                        //$this->insert_cron_data($table,$key,$last_log_datetime);
                    } else {
                        // $this->insert_cron_data($table,$key,$log_dates);
                    }

                    foreach ($device_log_data as $d_data) {
                        $download_data = $d_data['LogDate'];
                    }
                    $status = "Completed";
                    $insert_migrate_result = [
                        "migrate_id" => $insert_id,
                        "date" => $log_dates,
                        "result" => "Data found",
                        "status" => "Completed",
                        "last_run_date" => $download_data,
                    ];
                } else {
                    $insert_migrate_result = [
                        "migrate_id" => $insert_id,
                        "date" => $log_dates,
                        "result" => "No data found",
                        "status" => "Fail",
                        "last_run_date" => "",
                    ];
                }
            }
            if ($device_log_data_total != "") {

            }

            $explode_current_datetime = explode(' ', $current_datetime);
            $current_date = $explode_current_datetime[0];
            $current_time = $explode_current_datetime[1];

            $update_data = [
                "end_time" => $current_time,
                "status" => "not completed",
                "last_run_log_datetime" => $download_data];

            $this->db->where('id', $insert_id);
            $this->db->update('data_migration', $update_data);
        }
        echo 1;
    }

    public function insert_cron_data($table, $key_data, $log_dates_time) {
        //$users = array('2', '3', '15', '23', '28', '34', '36', '37', '38', '39', '40', '41', '42', '43');
        $this->db->select('users.id,users.username');
        //$this->db->where('id',36);
        $this->db->where_in('id', $users);
        $user_details = $this->db->get('users');
        if ($user_details->num_rows() > 0) {
            $user_details = $user_details->result_array();

            // $month = date('m');
            // $year = date('Y');
            //$month = "10";
            // $year = "2018";
            // $table = 'devicelogs_' . $month . '_' . $year;
            //echo $table; exit;

            foreach ($user_details as $key => $user_data) {

                if ($this->db->table_exists($table)) {
                    if ($key_data == 0)
                        $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $log_dates_time . "'");
                    else
                        $this->db->where('DATE(' . $table . '.LogDate)', $log_dates_time);

                    $this->db->where("DATE_FORMAT(" . $table . ".DownloadDate,'%Y-%m-%d %H:%i:%s') < '" . $current_datetime . "'");
                    $this->db->where('UserId', $user_data['id']);
                    $device_log = $this->db->get($table);
                }
                else {
                    $device_log = "";
                }
                if ($device_log->num_rows() > 0) {

                    $device_log = $device_log->result_array();
                    $total_device_log = count($device_log);
                    foreach ($device_log as $keys => $log_data) {
                        $user_atten_date = date('Y-m-d', strtotime($log_data['LogDate']));
                        $atten_user_id = $log_data['UserId'];
                        $device_log_id = $log_data['DeviceLogId'];
                        // Check if already this user have a attendance on same day..
                        $this->load->database('default', TRUE);
                        $this->db = $this->load->database('default', true);
                        $this->db->select('*');
                        $this->db->where('user_id', $atten_user_id);
                        $this->db->where('DATE(created)', $user_atten_date);
                        $alreadyAttendance = $this->db->get('attendance');

                        if ($alreadyAttendance->num_rows() == 0) {
                            //echo "IF : " . $device_log_id . '--' . $atten_user_id . '<br />';
                            if ($log_data['Direction'] == 'in') {
                                $insert_atten_data = [
                                    "user_id" => $log_data['UserId'],
                                    "in" => date("H:i", strtotime($log_data['LogDate'])),
                                    "created" => $log_data['LogDate'],
                                ];

                                $this->load->database('default', TRUE);
                                $this->db = $this->load->database('default', true);
                                $this->db->insert('attendance', $insert_atten_data);
                                $insert_attenance_id = $this->db->insert_id();
                                // echo "Attendance Inserted For User : " . $user_data['username'] . "For Date : " . date("d-m-Y", strtotime($log_data['LogDate'])) . '<br />';

                                $this->db->where('DATE(LogDate)', $log_data['LogDate']);
                                $this->db->where('UserId', $atten_user_id);
                                $this->db->where('DeviceLogId', $device_log_id);
                                $update_devicelog_data = array('hrapp_syncstatus' => 1);
                                $this->db->update($table, $update_devicelog_data);
                                // $this->db->table($table)->update($update_devicelog_data);
                                // echo "Device Log Id : " . $device_log_id . "Updated as 1<br />";
                                $this->load->database('default', TRUE);
                                $this->db = $this->load->database('default', true);
                            }
                        } else {
                            //echo "ELSE : Device Log id : " . $device_log_id . '-- User Id : ' . $atten_user_id . '<br />';
                            $this->db->select('*');
                            $this->db->where('DATE(created)', $user_atten_date);
                            $this->db->where('user_id', $atten_user_id);
                            $query = $this->db->get('attendance');
                            $attendance_data = $query->result_array();
                            if (!empty($attendance_data[0]['out'])) {
                                $this->load->database('default', TRUE);
                                $this->db = $this->load->database('default', true);
                                $out_time = $attendance_data[0]['out'];
                                if ($log_data['Direction'] == 'in') {

                                    $in_time = date('H:i', strtotime($log_data['LogDate']));

                                    $this->db->where('break_table.attendance_id', $attendance_data[0]['id']);
                                    $this->db->where('break_table.in_time', $out_time);
                                    $this->db->where('break_table.out_time', $in_time);
                                    $this->db->where('break_table.type', 'break');
                                    $attendance_check = $this->db->get('break_table')->result_array();

                                    if (count($attendance_check) == 0) {

                                        $insert_break_data = array(
                                            'attendance_id' => $attendance_data[0]['id'],
                                            'in_time' => $out_time,
                                            'out_time' => $in_time,
                                            'type' => 'break'
                                        );
                                        $this->db->insert('break_table', $insert_break_data);

                                        $insert_break_id = $this->db->insert_id();
                                    } else {
                                        $insert_break_id = $attendance_check[0]['id'];
                                    }



                                    // Update Attendance
                                    $this->db->where('attendance.id', $attendance_data[0]['id']);
                                    $update_atten_data = array(
                                        'out' => NULL
                                    );
                                    $update_atten = $this->db->update('attendance', $update_atten_data);
                                } else {
                                    $this->load->database('default', TRUE);
                                    $this->db = $this->load->database('default', true);
                                    // Update Attendance
                                    $this->db->where('attendance.id', $attendance_data[0]['id']);
                                    $update_atten_data = array(
                                        'out' => NULL
                                    );
                                    $update_atten = $this->db->update('attendance', $update_atten_data);
                                }
                            } else {
                                if ($log_data['Direction'] == 'out') {
                                    $this->load->database('default', TRUE);
                                    $this->db = $this->load->database('default', true);
                                    $out_time = date('H:i', strtotime($log_data['LogDate']));
                                    // Update Attendance
                                    $this->db->where('attendance.id', $attendance_data[0]['id']);
                                    $update_atten_data = array(
                                        'out' => $out_time
                                    );
                                    $update_atten = $this->db->update('attendance', $update_atten_data);
                                }
                            }
                        }
                    }
                }
            }
            exit;
            return true;
        } else {

            return false;
        }
    }

}

?>