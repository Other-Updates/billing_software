<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**

 * Attendance_model

 *

 * This model represents attendance. It operates the following tables:

 * - education,

 *

 * @package	Payroll

 * @author	Vathsala

 */
class Attendance_model extends CI_Model {

    private $table_name = 'attendance';
    private $associate = 'attendance_updated';
    private $break_table = 'break_table';

    function __construct() {

        parent::__construct();
    }

    /**

     * Get all user attendance

     *

     * @return	array

     */
    function check_manual_attendance() {
        $this->db->where('key', 'manual_attendance_entry');
        $get_options_list = $this->db->get('options')->result_array();
        // print_r($get_options_list);exit;
        if (count($get_options_list) > 0) {
            if ($get_options_list[0]['value'] == 1) {
                return 1;
            }if ($get_options_list[0]['value'] == 0) {
                return 0;
            }
        } else {
            return 1;
        }
    }

    function get_all_users_attendances() {





        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {



            return $query->result_array();
        }

        return false;
    }

    /**

     * Get user attendance by id (user id)

     *

     * @param	int

     * @return	array

     */
    function get_user_attendance_by_user_id($user_id) {



        $this->db->where('user_id', $user_id);



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function get_all_datamigration() {
        $this->db->order_by('id', 'desc');
        $logs = $this->db->get('data_migration')->result_array();
        return $logs;
    }

    /*

     * Get user attendance by attendance id (attendance id)

     * @param	int

     * @return	array

     */

    function last_migration_log() {
        $this->db->order_by('id', 'desc');
        $this->db->where('last_run_log_datetime !=', ' ');
        $get_log_history = $this->db->get('data_migration')->result_array();

        if (count($get_log_history) > 0) {
            return $get_log_history[0]['last_run_log_datetime'];
        } else {
            $this->db->order_by('id', 'desc');
            $atten_log = $this->db->get('attendance')->result_array();
            if (count($atten_log) > 0) {
                return $atten_log[0]['created'];
            } else {
                return date('Y-m-d h:i:s');
            }
        }
    }

    function check_any_data_available($logdata_dates, $last_log_datetime) {
        foreach ($logdata_dates as $key => $log_dates) {
            $explode_log_dates = explode('-', $log_dates);
            $month = $explode_log_dates[1];
            $year = $explode_log_dates[0];
            $table = 'devicelogs_' . $month . '_' . $year;
            //$table = 'devicelogs_12_2018';
            //$data= $this->load->database('epushserverdb', TRUE);


            /* $this->epushserverdb_db = $this->load->database('epushserverdb', true);
              $data= $this->epushserverdb_db->get('biotype')->result_array();

              //check table exists
              if ($this->epushserverdb_db->table_exists($table)) {
              $this->epushserverdb_db->select('UserId,LogDate,Direction,DeviceId,DeviceLogId,hrapp_syncstatus');
              $this->epushserverdb_db->where('DeviceId', 3);
              $this->epushserverdb_db->where('hrapp_syncstatus', NULL);
              $this->epushserverdb_db->where('DATE(' . $table . '.LogDate)', $log_dates);
              //$this->db->where('UserId', $user_data['id']);
              $this->epushserverdb_db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $last_log_datetime . "'");
              $this->epushserverdb_db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') < '" . date('Y-m-d H:i:s') . "'");
              $get_hostorys = $this->epushserverdb_db->get($table)->result_array();

              if (count($get_hostorys) > 0) {
              foreach ($get_hostorys as $key1 => $history_list) {
              $get_hostorys[$key1]['table_name'] = $table;
              }
              }
              $push_data[$key] = $get_hostorys;
              }
             */
            //check table exists
            if ($this->db->table_exists($table)) {
                $this->db->select('UserId,LogDate,Direction,DeviceId,DeviceLogId,hrapp_syncstatus');
                $this->db->where('DeviceId', 3);
                $this->db->where('hrapp_syncstatus', NULL);
                $this->db->where('DATE(' . $table . '.LogDate)', $log_dates);
                //$this->db->where('UserId', $user_data['id']);
                $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $last_log_datetime . "'");
                $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') < '" . date('Y-m-d H:i:s') . "'");
                $get_hostorys = $this->db->get($table)->result_array();

                if (count($get_hostorys) > 0) {
                    foreach ($get_hostorys as $key1 => $history_list) {
                        $get_hostorys[$key1]['table_name'] = $table;
                    }
                }
                $push_data[$key] = $get_hostorys;
            }
        }
        //get all push data in single array
        $singleArray = [];
        foreach ($push_data as $childArray) {
            foreach ($childArray as $value) {
                $singleArray[] = $value;
            }
        }

        return $singleArray;
    }

    function get_push_server_datas($logdata_dates, $last_log_datetime, $user_id) {
        foreach ($logdata_dates as $key => $log_dates) {
            $explode_log_dates = explode('-', $log_dates);
            $month = $explode_log_dates[1];
            $year = $explode_log_dates[0];
            $table = 'devicelogs_' . $month . '_' . $year;

            /* $this->load->database('epushserverdb', TRUE);
              $this->epushserverdb_db = $this->load->database('epushserverdb', true);

              //check table exists
              if ($this->epushserverdb_db->table_exists($table)) {
              $this->epushserverdb_db->select('UserId,LogDate,Direction,DeviceId,DeviceLogId,hrapp_syncstatus');
              $this->epushserverdb_db->where('DeviceId', 3);
              $this->epushserverdb_db->where('hrapp_syncstatus', NULL);
              $this->epushserverdb_db->where('DATE(' . $table . '.LogDate)', $log_dates);
              $this->epushserverdb_db->where('UserId', $user_id);
              $this->epushserverdb_db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $last_log_datetime . "'");
              $this->epushserverdb_db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') < '" . date('Y-m-d H:i:s') . "'");
              $get_hostorys = $this->epushserverdb_db->get($table)->result_array();

              if (count($get_hostorys) > 0) {
              foreach ($get_hostorys as $key1 => $history_list) {
              $get_hostorys[$key1]['table_name'] = $table;
              }
              }
              $push_data[$key] = $get_hostorys;
              } */

            //check table exists
            if ($user_id == 1) {
                $user_id = "0001";
            }
            if ($user_id == 2) {
                $user_id = "0002";
            }
            if ($user_id == 15) {
                $user_id = "0015";
            }
            if ($this->db->table_exists($table)) {
                $this->db->select('UserId,LogDate,Direction,DeviceId,DeviceLogId,hrapp_syncstatus');
                $this->db->where('DeviceId', 3);
                $this->db->where('hrapp_syncstatus', NULL);
                $this->db->where('DATE(' . $table . '.LogDate)', $log_dates);
                $this->db->where('UserId', $user_id);
                $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') > '" . $last_log_datetime . "'");
                $this->db->where("DATE_FORMAT(" . $table . ".LogDate,'%Y-%m-%d %H:%i:%s') < '" . date('Y-m-d H:i:s') . "'");
                $get_hostorys = $this->db->get($table)->result_array();

                if (count($get_hostorys) > 0) {
                    foreach ($get_hostorys as $key1 => $history_list) {
                        $get_hostorys[$key1]['table_name'] = $table;
                    }
                }
                $push_data[$key] = $get_hostorys;
            }
        }

        //get all push data in single array
        $singleArray = [];
        foreach ($push_data as $childArray) {
            foreach ($childArray as $value) {
                $singleArray[] = $value;
            }
        }

        return $singleArray;
    }

    function user_last_logs($user_id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('user_id', $user_id);
        $this->db->where('out', NULL);
        $get_user_logs_out = $this->db->get('attendance')->result_array();

        if (count($get_user_logs_out) > 0) {

            $datetime = $get_user_logs_out[0]['created'];
            $datetime_ex = explode(' ', $datetime);
            $date = $datetime_ex[0];
            $datetime = $datetime_ex[0] . " " . $get_user_logs_out[0]['in'];
            $this->db->order_by('id', 'desc');
            $this->db->where('attendance_id', $get_user_logs_out[0]['id']);
            $check_break = $this->db->get('break_table')->result_array();

            if (count($check_break) > 0) {
                if ($check_break[0]['out_time'] != "")
                    $date_time = $date . " " . $check_break[0]['out_time'];
                else
                    $date_time = $date . " " . $check_break[0]['in_time'];
            }else {
                $date_time = $datetime;
            }
        } else {
            $this->db->order_by('id', 'desc');
            $this->db->where('user_id', $user_id);
            $get_user_logs_in = $this->db->get('attendance')->result_array();
            if (count($get_user_logs_in) > 0) {
                $datetime = $get_user_logs_in[0]['created'];
                $datetime_ex = explode(' ', $datetime);
                $date = $datetime_ex[0];
                $datetime = $datetime_ex[0] . " " . $get_user_logs_in[0]['out'];
                $this->db->order_by('id', 'desc');
                $this->db->where('attendance_id', $get_user_logs_in[0]['id']);
                $check_break = $this->db->get('break_table')->result_array();

                if (count($check_break) > 0) {
                    if ($check_break[0]['out_time'] != "")
                        $date_time = $date . " " . $check_break[0]['out_time'];
                    else
                        $date_time = $date . " " . $check_break[0]['in_time'];
                }else {
                    $date_time = $datetime;
                }
            } else {
                $this->db->select('created');
                $this->db->order_by('id', 'asc');
                $get_attendance_start = $this->db->get('attendance')->result_array();
                if (!empty($get_attendance_start)) {
                    $date = $get_attendance_start[0]['created'];
                    $get_date_explode = explode(' ', $date);
                    $date_time = $get_date_explode[0] . " " . "00:00:00";
                } else {
//                    $date_time = date('Y-m-d h:i:s');
                    $date_time = '2018-10-31 17:15:14';
                }
            }
        }

        return $date_time;
    }

    function get_push_logs($last_log_datetime) {
        //Last log date
        $explode_last_log_date = explode(' ', $last_log_datetime);
        $last_log_date = $explode_last_log_date[0];
        $last_log_time = $explode_last_log_date[1];

        //current time
        $current_datetime = date('Y-m-d H:i:s');
        $explode_current_datetime = explode(' ', $current_datetime);
        $current_date = $explode_current_datetime[0];
        $current_time = $explode_current_datetime[1];

        //migration start data
        $migration_data = [
            "log_datetime" => $current_datetime,
            "log_date" => $current_date,
            "start_time" => $current_time,
            "status" => "in_progress"
        ];
        while (strtotime($last_log_date) <= strtotime($current_date)) {
            $logdata_dates[] = $last_log_date;
            $last_log_date = date("Y-m-d", strtotime("+1 days", strtotime($last_log_date)));
        }

        //check_data_exists_in_push_server
        $check_data_available = $this->check_any_data_available($logdata_dates, $last_log_datetime);

        if (count($check_data_available) == 0) {
            return 0;
        }

        $get_hostorys = "";
        $push_data = "";

        $this->load->database('default', TRUE);

        $this->db->select('users.id,users.username');
        $this->db->where('status', 1);
        $user_details = $this->db->get('users');
        if ($user_details->num_rows() > 0) {
            $user_details = $user_details->result_array();

            foreach ($user_details as $key => $user_data) {


                $get_logs_date = $this->user_last_logs($user_data['id']);

                if (empty($get_logs_date))
                    $get_logs_date = $last_log_datetime;

                //get_push_server_datas
                $singleArray = $this->get_push_server_datas($logdata_dates, $get_logs_date, $user_data['id']);

                $this->load->database('default', TRUE);

                if (count($singleArray) > 0) {
                    $this->db->order_by('id', 'desc');
                    $check_migration_data = $this->db->get('data_migration')->result_array();
                    $datas = $check_migration_data[0]['last_run_log_datetime'];
                    if ($datas != "") {
                        $this->db->insert('data_migration', $migration_data);
                        $insert_migrate_id = $this->db->insert_id();
                    }
                    foreach ($singleArray as $key_data => $results) {

                        if ($results['UserId'] == '0001') {
                            $results['UserId'] = "1";
                        }
                        if ($results['UserId'] == '0002') {
                            $results['UserId'] = "2";
                        }
                        if ($results['UserId'] == '0015') {
                            $results['UserId'] = "15";
                        }
                        $user_atten_date = date('Y-m-d', strtotime($results['LogDate']));
                        $this->db->select('*');
                        $this->db->where('user_id', $results['UserId']);
                        $this->db->where('DATE(created)', $user_atten_date);
                        $alreadyAttendance = $this->db->get('attendance')->result_array();

                        if (count($alreadyAttendance) == 0) {
                            if ($results['Direction'] == 'in') {
                                $insert_atten_data = [
                                    "user_id" => $results['UserId'],
                                    "in" => date("H:i", strtotime($results['LogDate'])),
                                    "created" => $results['LogDate'],
                                ];
                                //Insert attandance
                                $this->db->insert('attendance', $insert_atten_data);
                                $insert_attenance_id = $this->db->insert_id();

                                $update_data = array('hrapp_syncstatus' => 1);

                                /* $this->load->database('epushserverdb', TRUE);
                                  $this->epushserverdb_db = $this->load->database('epushserverdb', true);

                                  $this->epushserverdb_db->where('LogDate', $results['LogDate']);
                                  $this->epushserverdb_db->where('UserId', $results['UserId']);
                                  $this->epushserverdb_db->where('DeviceLogId', $results['DeviceLogId']);
                                  $this->epushserverdb_db->update($results['table_name'], $update_data); */
                                $this->db->where('LogDate', $results['LogDate']);
                                $this->db->where('UserId', $results['UserId']);
                                $this->db->where('DeviceLogId', $results['DeviceLogId']);
                                $this->db->update($results['table_name'], $update_data);
                            }
                        } else {

                            $this->load->database('default', TRUE);

                            $this->db->select('*');
                            $this->db->where('DATE(created)', $user_atten_date);
                            $this->db->where('user_id', $results['UserId']);
                            $query = $this->db->get('attendance');
                            $attendance_data = $query->result_array();
                            if (!empty($attendance_data[0]['out'])) {
                                $out_time = $attendance_data[0]['out'];
                                if ($results['Direction'] == 'in') {
                                    $in_time = date('H:i', strtotime($results['LogDate']));
                                    $this->db->where('break_table.attendance_id', $attendance_data[0]['id']);
                                    $this->db->where('break_table.in_time', $out_time);
                                    $this->db->where('break_table.out_time', $in_time);
                                    $this->db->where('break_table.type', 'break');
                                    $attendance_check = $this->db->get('break_table')->result_array();
                                    if ($out_time != $attendance_data[0]['in']) {
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
                                    }

                                    // Update Attendance
                                    $this->db->where('attendance.id', $attendance_data[0]['id']);
                                    $update_atten_data = array(
                                        'out' => NULL
                                    );
                                    $update_atten = $this->db->update('attendance', $update_atten_data);
                                } else {
                                    $this->db->where('attendance.id', $attendance_data[0]['id']);
                                    $update_atten_data = array(
                                        'out' => NULL
                                    );
                                    $update_atten = $this->db->update('attendance', $update_atten_data);
                                }
                            } else {
                                $this->load->database('default', TRUE);

                                if ($results['Direction'] == 'out') {
                                    $out_time = date('H:i', strtotime($results['LogDate']));
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
            $this->load->database('default', TRUE);

            if ($insert_migrate_id) {
                $explode_current_datetime = explode(' ', date('Y-m-d H:i:s'));
                $current_date = $explode_current_datetime[0];
                $current_time = $explode_current_datetime[1];

                $last_log_dates = $this->check_last_logs();
                if (empty($last_log_dates)) {
                    return date('Y-m-d h:i:s');
                }
                $this->load->database('default', TRUE);
                $update_data = [
                    "end_time" => $current_time,
                    "status" => "completed",
                    "last_run_log_datetime" => $last_log_dates];

                $this->db->where('id', $insert_migrate_id);
                $this->db->update('data_migration', $update_data);
                return $update_data;
            } else {
                return 0;
            }
        }
    }

    function check_last_logs() {
        // $this->load->database('epushserverdb', TRUE);
        //  $this->epushserverdb_db = $this->load->database('epushserverdb', true);
        $month = date('m');
        $year = date('Y');
        $table = 'devicelogs_' . $month . '_' . $year;
        if ($this->db->table_exists($table)) {
            $this->db->order_by('DeviceLogId', 'desc');
            $device_log = $this->db->get($table)->result_array();
            if (count($device_log) > 0) {
                return $device_log[0]['LogDate'];
            }
        } else {
            $this->load->database('default', TRUE);
            $this->db->order_by('id', 'desc');
            $last_atten = $this->db->get('attendance')->result_array();
            return $last_atten[0]['created'];
        }
    }

    function get_attendance_by_id($attendance_id) {



        $this->db->select($this->table_name . '.*');



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d-%m-%Y") as attendance_date', FALSE);



        $this->db->where('id', $attendance_id);



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /**

     * Insert new user attendance

     *

     * @param	array

     * @param	bool

     * @return	id

     */
    function insert_attendance($data) {





        if ($this->db->insert($this->table_name, $data)) {



            $att_id = $this->db->insert_id();



            return $att_id;
        }

        return false;
    }

    /**

     * Update user attendance by user id

     *

     * @param	array

     * @param	int

     * @return	bool

     */
    function update_user_attendance($user_id, $data) {

        $this->db->where('user_id', $user_id);



        if ($this->db->update($this->table_name, $data)) {



            return true;
        }

        return false;
    }

    /**

     * insert updation for attendance in  attendance updated table

     *

     * @param	array

     * @param	int

     * @return	bool

     */
    function insert_updation_for_att_id($data) {



        if ($this->db->insert($this->associate, $data)) {



            $att_id = $this->db->insert_id();



            return $att_id;
        }

        return false;
    }

    /**

     * Update  attendance by  id

     *

     * @param	array

     * @param	int

     * @return	bool

     */
    function update_attendance($att_id, $data) {

        $this->db->where('id', $att_id);

        if ($this->db->update($this->table_name, $data)) {



            return true;
        }

        return false;
    }

    /**

     * Delete user attendance by user id

     *

     * @param	int

     * @return	bool

     */
    function delete_user_attendance($user_id) {

        $this->db->where('user_id', $user_id);



        $this->db->delete($this->table_name);



        if ($this->db->affected_rows() > 0) {



            return true;
        }

        return false;
    }

    /**

     * Delete user attendance by  id

     *

     * @param	int

     * @return	bool

     */
    function delete_attendance_by_id($att_id) {

        $this->db->where('id', $att_id);



        $this->db->delete($this->table_name);



        if ($this->db->affected_rows() > 0) {



            return true;
        }

        return false;
    }

    function delete_updation_for_att_id($attendance_id) {



        $this->db->where('attendance_id', $attendance_id);



        $this->db->delete($this->associate);



        if ($this->db->affected_rows() > 0) {



            return true;
        }

        return false;
    }

    function get_attendance_by_month_year_and_user_id($year, $month, $user_id) {



        $this->db->select('*');



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d-%m-%Y") as attendance_date', FALSE);



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d") as key_date', FALSE);



        $this->db->where('YEAR(created)', $year);



        if (gettype($month) == "array")
            $this->db->where_in('MONTH(created)', $month);
        else
            $this->db->where('MONTH(created)', $month);



        $this->db->where('user_id', $user_id);



        $query = $this->db->get($this->table_name);



        //echo $this->db->last_query();

        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function get_attendance_by_between_dates($user_id, $date1, $date2) {



        $this->db->select('*');



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d-%m-%Y") as attendance_date', FALSE);



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d") as key_date', FALSE);



        $this->db->where('DATE(created) >="' . $date1 . '"');



        $this->db->where('DATE(created) <="' . $date2 . '"');



        $this->db->where('user_id', $user_id);



        $query = $this->db->get($this->table_name);



        //  echo $this->db->last_query();

        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function insert_break_values($data) {

        if ($this->db->insert($this->break_table, $data)) {



            $br_id = $this->db->insert_id();



            return $br_id;
        }

        return false;
    }

    function update_break_values($break_id, $data) {

        $this->db->where('id', $break_id);



        if ($this->db->update($this->break_table, $data)) {



            return true;
        }

        return false;
    }

    function get_break_details_by_attendance_id($att_id) {


        $this->db->where('attendance_id', $att_id);



        $query = $this->db->get($this->break_table);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function delete_break_details_by_att_id($att_id) {

        $this->db->where('attendance_id', $att_id);



        $this->db->delete($this->break_table);



        if ($this->db->affected_rows() > 0) {



            return true;
        }

        return false;
    }

    function get_user_attendance_id_by_month_year($year, $month, $user_id) {



        $this->db->select('id,in,out');



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d") as date', FALSE);



        $this->db->where('YEAR(created)', $year);



        $this->db->where('MONTH(created)', $month);



        $this->db->where('user_id', $user_id);



        $this->db->order_by('created');



        $query = $this->db->get($this->table_name);



        //echo $this->db->last_query();

        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /* Get number of users present for admin */

    function get_number_of_users_present() {

        $this->db->select('count(*) as count');

        $cur_date = date('Y-m-d');

        //$cur_date = '2014-02-01';

        $this->db->where('DATE(created)', $cur_date);



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /* Get number of users present for admin by filter */

    function get_number_of_users_present_by_filter($filter) {

        $this->db->select('count(*) as count');



        $cur_date = date('Y-m-d');



        if (isset($filter) && !empty($filter)) {

            if (isset($filter["department"]) && !empty($filter["department"])) {

                $this->db->where_in('department.id', $filter['department']);
            }

            if (isset($filter["designation"]) && !empty($filter["designation"])) {

                $this->db->where_in('designation.id', $filter['designation']);
            }

            if (isset($filter["users"]) && !empty($filter["users"])) {



                $this->db->where_in($this->table_name . '.id', $filter["users"]);
            }



            if (isset($filter['field']) && $filter['field'] != NULL && isset($filter["value"]) && $filter["value"] != NULL) {

                if ($filter['field'] == "gender")
                    $where = 'users.' . $filter['field'] . " = '" . $filter["value"] . "'";
                else
                    $where = 'users.' . $filter['field'] . ' LIKE "%' . $filter["value"] . '%"';

                $this->db->where($where);
            }
        }



        $this->db->where('DATE(' . $this->table_name . '.created)', $cur_date);



        $this->db->where('user_history.date <=', $cur_date);



        $this->db->where('user_history.type =', "doj");



        $this->db->join('users', 'users.id=' . $this->table_name . '.user_id', 'left');



        $this->db->join('user_department', 'user_department.user_id=' . $this->table_name . '.user_id', 'left');



        $this->db->join('user_history', 'user_history.user_id=' . $this->table_name . '.user_id', 'left');



        $this->db->join('department', 'department.id=user_department.department', 'left');



        $this->db->join('designation', 'designation.id=user_department.designation', 'left');



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /* Get number of users present for user */

    function get_num_of_users_present($user_id_list) {

        $this->db->select('count(*) as count');

        $cur_date = date('Y-m-d');

        $this->db->where_in('user_id', $user_id_list);

        $this->db->where('DATE(created)', $cur_date);



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /**

     * Get user attendance by id (user id)

     *

     * @param	int

     * @return	array

     */
    function get_user_attendance_by_userid_and_date($user_id, $date) {

        $this->db->where('DATE(created)', $date);



        $this->db->where('user_id', $user_id);



        $query = $this->db->get($this->table_name);


        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function get_user_attendance_by_date($date) {

        $this->db->where('DATE(created)', $date);



        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function get_break_details_by_attendance_and_type($att_id, $type) {

        $this->db->select('*');



        //	$this->db->select('DATE(created) as attendance_date');



        $this->db->where('attendance_id', $att_id);



        $this->db->where('type', $type);



        $query = $this->db->get($this->break_table);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    //Get all attendance without having out time

    function get_all_attendances_without_outtime($user_id = NULL) {

        $this->db->select($this->table_name . '.*');



        $this->db->select('DATE(attendance.created) as attendance_date');



        $this->db->select('DATE_FORMAT(attendance.created,"%d-%m-%Y") as cu_date', FALSE);



        $this->db->select("CONCAT_WS(' ', users.first_name, users.last_name ) AS name");



        $this->db->join('users', 'users.id=' . $this->table_name . '.user_id', 'left');



        $this->db->where($this->table_name . '.out', NULL);



        $this->db->where($this->table_name . '.in IS NOT NULL');



        $this->db->where('DATE(' . $this->table_name . '.created)!=', date('Y-m-d'));



        if ($user_id != NULL)
            $this->db->where($this->table_name . '.user_id', $user_id);







        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    /*

     * Get user attendance by attendance id (attendance id)

     * @param	int

     * @return	array

     */

    function get_attendance_by_id_without_outtime($attendance_id) {



        $this->db->select($this->table_name . '.*');



        $this->db->select("CONCAT_WS(' ', users.first_name, users.last_name ) AS name");



        $this->db->select('DATE_FORMAT(' . $this->table_name . '.created,"%d-%m-%Y") as attendance_date', FALSE);



        $this->db->where($this->table_name . '.id', $attendance_id);



        $this->db->join('users', 'users.id=' . $this->table_name . '.user_id', 'left');





        $query = $this->db->get($this->table_name);



        if ($query->num_rows() >= 1) {

            return $query->result_array();
        }

        return false;
    }

    function get_emp_all_logs_by_day($user_id, $created_date) {
        $all_logs = array();
        $this->db->select('id as attendance_id');
        $this->db->where('DATE(' . $this->table_name . '.created)', date('Y-m-d', strtotime($created_date)));
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table_name)->row_array();

        if (!empty($query['attendance_id'])) {
            $this->db->select('*');
            $this->db->where('break_table.attendance_id', $query['attendance_id']);
            $all_logs = $this->db->get('break_table')->result_array();
        }

        return $all_logs;
    }

    function get_late_coming_users_list($dates) {
        $late_coming_users = [];
        $office_time_details = $this->get_office_time();

        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        foreach ($dates as $date_data) {

            $this->db->select('users.id,users.employee_id,users.username');
            $this->db->where('users.status', 1);
            $users = $this->db->get('users')->result_array();
            $explode_date = explode('-', $date_data);
            $month = $explode_date[1];
            $year = $explode_date[0];
            $table = 'devicelogs_' . $month . '_' . $year;
            $late_users = "";
            if (count($users) > 0) {
                foreach ($users as $key => $users_list) {

                    $this->db->where('attendance.user_id', $users_list['id']);
                    $this->db->where('DATE(attendance.created)=', $date_data);
                    $attendance_details = $this->db->get('attendance')->result_array();

                    $user_list_details = "";
                    $late_coming_users = "";

                    if ($attendance_details != "") {
                        $attendance_id = $attendance_details[0]['id'];

                        //get_user_morning_time
                        $user_mor_time = $this->user_mor_time($users_list['id'], $date_data);
                        $mor_status = "";
                        $users[$key]['morning_status'] = $this->check_attendance_status($user_mor_time['morning_work_duration']);
                        $mor_status = $users[$key]['morning_status'];
                        //get_user_evening_time
                        $user_eve_time = $this->user_eve_time($users_list['id'], $date_data);
                        $aftnun_status = "";
                        $users[$key]['evening_status'] = $this->check_attendance_status($user_eve_time['evening_work_duration']);
                        $aftnun_status = $users[$key]['evening_status'];

                        $morning_late_time = $this->round_hours($user_mor_time['morning_lateby_time']);
                        $evening_late_time = $this->round_hours($user_eve_time['evening_lateby_time']);

                        //mor&eve duration
                        $users[$key]['overall_work_duration'] = $this->explode_time($this->sum_the_time($user_mor_time['morning_work_duration'], $user_eve_time['evening_work_duration']));

                        //punch records
                        $this->db->select('UserId,LogDate,Direction');
                        $this->db->where('DATE(' . $table . '.LogDate)', $date_data);
                        $this->db->where('UserId', $users_list['id']);
                        $device_log = $this->db->get($table)->result_array();

                        $full_logs = "";
                        $log_details = "";
                        $log_details_data = "";
                        if ($device_log != "") {
                            foreach ($device_log as $d_key => $logs) {
                                $keyss = $d_key + 1;
                                $direction = $logs['Direction'];
                                $log_time_explode = explode(' ', $logs['LogDate']);
                                $time = $this->explode_time($log_time_explode[1]);
                                $log_details_data = $time . ":" . $direction;

                                if ($keyss % 5 == "0" && $keyss != 0) {
                                    $log_details_data = $time . ":" . $direction . '<br>';
                                }
                                $log_details[] = $log_details_data;
                            }
                            $full_logs = implode(',', $log_details);
                        }
                        $users[$key]['full_logs_details'] = $device_log;
                        $users[$key]['full_logs'] = $full_logs;

                        $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);
                        $users[$key]['status'] = $check_attend;
                        if ($morning_late_time > 0 || $evening_late_time > 0) {
                            $late_coming_users = [
                                "id" => $users_list['id'],
                                "employee_id" => $users_list['employee_id'],
                                "username" => $users_list['username'],
                                "mor_in" => $this->explode_time($user_mor_time['morning_in_time']),
                                "mor_out" => $user_mor_time['morning_out_time'],
                                "aftnun_in" => $this->explode_time($user_eve_time['evening_in_time']),
                                "aftnun_out" => $this->explode_time($user_eve_time['evening_out_time']),
                                "mor_late_by" => $this->explode_time($user_mor_time['morning_lateby_time']),
                                "aftnun_late_by" => $this->explode_time($user_eve_time['evening_lateby_time']),
                                "morning_status" => $mor_status,
                                "evening_status" => $aftnun_status,
                                "overall_work_duration" => $users[$key]['overall_work_duration'],
                                "morning_work_duration" => $this->explode_time($user_mor_time['morning_work_duration']),
                                "afternoon_work_duration" => $this->explode_time($user_eve_time['evening_work_duration']),
                                "full_logs_details" => $device_log,
                                "full_logs" => $full_logs,
                                "status" => $check_attend
                            ];
                            $late_users[] = $late_coming_users;
                        }
                    }
                }
            }

            $late_coming_users_list[$date_data]['late_by'] = $late_users;
        }

        foreach ($late_coming_users_list as $key => $value) {
            if (empty($value['late_by'])) {
                unset($key);
            } else {
                $result[$key][] = $value;
            }
        }

        return $result;
    }

    function get_early_going_users_list($dates) {

        $early_going_users = [];
        $office_time_details = $this->get_office_time();
        //office_general_time_detils_start
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];

        foreach ($dates as $date_data) {

            $users = [];

            $this->db->select('users.id,users.employee_id,users.username');
            $this->db->where('users.status', 1);
            $users = $this->db->get('users')->result_array();
            $explode_date = explode('-', $date_data);
            $month = $explode_date[1];
            $year = $explode_date[0];
            $table = 'devicelogs_' . $month . '_' . $year;
            $early_going = "";
            if (count($users) > 0) {
                foreach ($users as $key => $users_list) {

                    $this->db->where('attendance.user_id', $users_list['id']);
                    $this->db->where('DATE(attendance.created)=', $date_data);
                    $attendance_details = $this->db->get('attendance')->result_array();

                    $user_list_details = "";
                    $ealry_going_users = "";

                    if ($attendance_details != "") {
                        $attendance_id = $attendance_details[0]['id'];
                        //get_user_morning_time
                        $user_mor_time = $this->user_mor_time($users_list['id'], $date_data);

                        $mor_status = "";
                        $users[$key]['morning_status'] = $this->check_attendance_status($user_mor_time['morning_work_duration']);
                        $mor_status = $users[$key]['morning_status'];
                        //get_user_evening_time
                        $user_eve_time = $this->user_eve_time($users_list['id'], $date_data);

                        $aftnun_status = "";
                        $users[$key]['evening_status'] = $this->check_attendance_status($user_eve_time['evening_work_duration']);
                        $aftnun_status = $users[$key]['evening_status'];

                        $morning_early_time = $this->round_hours($user_mor_time['morning_early_going']);
                        $evening_early_time = $this->round_hours($user_eve_time['evening_early_going']);

                        //mor&eve duration
                        $users[$key]['overall_work_duration'] = $this->explode_time($this->sum_the_time($user_mor_time['morning_work_duration'], $user_eve_time['evening_work_duration']));

                        //punch records
                        $this->db->select('UserId,LogDate,Direction');
                        $this->db->where('DATE(' . $table . '.LogDate)', $date_data);
                        $this->db->where('UserId', $users_list['id']);
                        $device_log = $this->db->get($table)->result_array();

                        $full_logs = "";
                        $log_details = "";
                        $log_details_data = "";
                        if ($device_log != "") {
                            foreach ($device_log as $d_key => $logs) {
                                $keyss = $d_key + 1;
                                $direction = $logs['Direction'];
                                $log_time_explode = explode(' ', $logs['LogDate']);
                                $time = $this->explode_time($log_time_explode[1]);
                                $log_details_data = $time . ":" . $direction;

                                if ($keyss % 5 == "0" && $keyss != 0) {
                                    $log_details_data = $time . ":" . $direction . '<br>';
                                }
                                $log_details[] = $log_details_data;
                            }
                            $full_logs = implode(',', $log_details);
                        }
                        $users[$key]['full_logs_details'] = $device_log;
                        $users[$key]['full_logs'] = $full_logs;

                        $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);
                        $users[$key]['status'] = $check_attend;

                        if ($morning_early_time > 0 || $evening_early_time > 0) {
                            $ealry_going_users = [
                                "id" => $users_list['id'],
                                "employee_id" => $users_list['employee_id'],
                                "username" => $users_list['username'],
                                "mor_in" => $this->explode_time($user_mor_time['morning_in_time']),
                                "mor_out" => $user_mor_time['morning_out_time'],
                                "aftnun_in" => $this->explode_time($user_eve_time['evening_in_time']),
                                "aftnun_out" => $this->explode_time($user_eve_time['evening_out_time']),
                                "mor_early_going" => $this->explode_time($user_mor_time['morning_early_going']),
                                "aftnun_early_going" => $this->explode_time($user_eve_time['evening_early_going']),
                                "morning_status" => $mor_status,
                                "evening_status" => $aftnun_status,
                                "overall_work_duration" => $users[$key]['overall_work_duration'],
                                "morning_work_duration" => $this->explode_time($user_mor_time['morning_work_duration']),
                                "afternoon_work_duration" => $this->explode_time($user_eve_time['evening_work_duration']),
                                "full_logs_details" => $device_log,
                                "full_logs" => $full_logs,
                                "status" => $check_attend
                            ];
                            $early_going[] = $ealry_going_users;
                        }
                    }
                }
            }
            $ealry_going_users_list[$date_data]['early_going'] = $early_going;
        }

        foreach ($ealry_going_users_list as $key => $value) {
            if (empty($value['early_going'])) {
                unset($key);
            } else {
                $result[$key][] = $value;
            }
        }

        return $result;
    }

    function get_office_time() {
        $data = [];
        $this->db->where('type', 'forenoon');
        $this->db->where('shift_id', '1');
        $forenoon_office_time = $this->db->get('shift_split')->result_array();

        $this->db->where('type', 'afternoon');
        $this->db->where('shift_id', '1');
        $afternoon_office_time = $this->db->get('shift_split')->result_array();

        $this->db->where('type', 'lunch');
        $this->db->where('shift_id', '1');
        $lunch_time = $this->db->get('shift_split')->result_array();

        $data['in_time'] = "09:15:00";
        $data['out_time'] = "21:00:00";
        $data['lunch_time'] = "01:45:00";
        $data['over_time_hours'] = "00:00:00";
        $data['lunch_time_from'] = "00:00:00";
        $data['lunch_time_to'] = "00:00:00";

        $total_fn_ofc_hrs = "00:00:00";
        if (count($total_fn_ofc_hrs) > 0) {
            $total_fn_ofc_hrs = $this->time_difference($forenoon_office_time[0]['from_time'], $forenoon_office_time[0]['to_time']);
        }

        $total_an_ofc_hrs = "00:00:00";
        if (count($total_an_ofc_hrs) > 0) {
            $total_an_ofc_hrs = $this->time_difference($afternoon_office_time[0]['from_time'], $afternoon_office_time[0]['to_time']);
        }
        $total_ofc_hours = "00:00:00";
        $total_ofc_hours = $this->sum_the_time($total_fn_ofc_hrs, $total_an_ofc_hrs);

        //   $this->db->where('type', 'break');
        $this->db->where('type', 'lunch');
        $this->db->where('shift_id', '1');
        $office_break_details = $this->db->get('shift_split')->result_array();

        $over_all_break = "";
        $over_break_time = "";
        // $ofc_time = "08:00:00";
        if ($office_break_details != "") {

            foreach ($office_break_details as $key1 => $break_data) {

                $over_break_time[] = $this->time_difference($break_data['from_time'], $break_data['to_time']);
            }

            $over_all_break = $this->sum_multi_time($over_break_time);

            $ofc_time = $this->time_difference($over_all_break, $total_ofc_hours);
        } else {
            $ofc_time = $total_ofc_hours;
        }
        $data['ofc_time'] = $ofc_time;
        if (!empty($forenoon_office_time)) {
            $data['fn_in_time'] = $forenoon_office_time[0]['from_time'];
            $data['fn_out_time'] = $forenoon_office_time[0]['to_time'];
        }
        if (!empty($afternoon_office_time)) {
            $data['an_in_time'] = $afternoon_office_time[0]['from_time'];
            $data['an_out_time'] = $afternoon_office_time[0]['to_time'];
        }
        if (!empty($lunch_time)) {
            $data['lunch_time'] = $lunch_time[0]['from_time'];
            $data['lunch_time_from'] = $lunch_time[0]['from_time'];
            $data['lunch_time_to'] = $lunch_time[0]['to_time'];
        }
        $this->db->where('key', 'min_ot_hours');
        $over_time = $this->db->get('options')->result_array();

        $data['over_time'] = "00:00:00";
        if (count($over_time) > 0) {
            $over_time_hours = $over_time[0]['value'];
            $data['over_time_hours'] = $over_time_hours . ":00";
            $data['over_time'] = $over_time_hours;
            $data['over_time'] = $this->sum_the_time($data['an_out_time'], $data['over_time']);
        }

        $data['fn_late_coming_threshold'] = $data['fn_in_time'];
        $data['fn_early_going_threshold'] = $data['fn_out_time'];
        $data['an_late_coming_threshold'] = $data['an_in_time'];
        $data['an_early_going_threshold'] = $data['an_out_time'];

        $this->db->where('key', 'late_coming_threshold_value');
        $late_coming_time = $this->db->get('options')->result_array();

        if (!empty($late_coming_time))
            $data['fn_late_coming_threshold'] = $this->sum_the_time($data['fn_in_time'], $late_coming_time[0]['value'] . ":00");
        $data['an_late_coming_threshold'] = $this->sum_the_time($data['an_in_time'], $late_coming_time[0]['value'] . ":00");

        $this->db->where('key', 'early_going_threshold_value');
        $early_going_time = $this->db->get('options')->result_array();

        if ($early_going_time)
            $data['fn_early_going_threshold'] = $this->time_difference($data['fn_out_time'], $early_going_time[0]['value'] . ":00");
        $data['an_early_going_threshold'] = $this->time_difference($data['an_out_time'], $early_going_time[0]['value'] . ":00");

        $this->db->where('key', 'quater_day_calculation');
        $quater_day_calculation = $this->db->get('options')->result_array();
        $data['quater_day_calculation'] = $quater_day_calculation[0]['value'];

        $this->db->where('key', 'half_day_calculation');
        $half_day_calculation = $this->db->get('options')->result_array();
        $data['half_day_calculation'] = $half_day_calculation[0]['value'];

        return $data;
    }

    function get_users_based_on_status($date_data, $status) {
        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $users = $this->db->get('users')->result_array();
        $present_users = [];
        $absent_users = [];
        $get_atten_details = '';

        $office_time_details = $this->get_office_time();

        $lunch_time_from = $office_time_details['lunch_time_from'];
        $lunch_time_to = $office_time_details['lunch_time_to'];

        foreach ($users as $key => $data) {
            $this->db->where('DATE(attendance.created)=', $date_data);
            $this->db->where('user_id', $data['id']);
            $get_atten_details = $this->db->get('attendance')->result_array();

            if (!empty($get_atten_details)) {

                $result = '';

                if (($get_atten_details[0]['in'] >= $lunch_time_from) || ($get_atten_details[0]['out'] >= $lunch_time_from && $get_atten_details[0]['out'] <= $lunch_time_to)) {
                    $result = [
                        "id" => $data['id'],
                        "employee_id" => $data['employee_id'],
                        "username" => $data['username']];
                    $absent_users[] = $result;
                } else {
                    $result = [
                        "id" => $data['id'],
                        "employee_id" => $data['employee_id'],
                        "username" => $data['username']];
                    $present_users[] = $result;
                }
            } else {
                $this->db->where('DATE(attendance.created)=', $date_data);
                $check_any_one_user_present = $this->db->get('attendance')->result_array();

                $result = '';
                if (count($check_any_one_user_present) > 4) {
                    $result = [
                        "id" => $data['id'],
                        "employee_id" => $data['employee_id'],
                        "username" => $data['username']];
                    $absent_users[] = $result;
                }
            }
        }

        if ($status == 1) {
            return $present_users;
        } else {
            return $absent_users;
        }
    }

    function get_daily_reports_users_list($dates, $status) {

        $daily_report_users = [];

        $office_time_details = $this->get_office_time();
        //office_general_time_detils_start
        $over_time = $office_time_details['over_time'];
        $over_hours = $office_time_details['over_time_hours'];
        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];
        $half_day_status = $office_time_details['half_day_calculation'];
        $quater_day_status = $office_time_details['quater_day_calculation'];

        $get_office_time = $this->get_office_time();

        $work_mor_start = $get_office_time['fn_in_time'];
        $work_mor_end = $get_office_time['fn_out_time'];
        $work_aftnun_start = $get_office_time['an_in_time'];
        $work_aftnun_end = $get_office_time['an_out_time'];

        foreach ($dates as $date_data) {
            $users = [];
            $this->db->select('users.id,users.employee_id,users.username');
            $this->db->where('users.status', 1);
            $users = $this->db->get('users')->result_array();

            $explode_date = explode('-', $date_data);
            $month = $explode_date[1];
            $year = $explode_date[0];
            $table = 'devicelogs_' . $month . '_' . $year;
//            if ($status != "all") {
//                $users = $this->get_users_based_on_status($date_data, $status);
//            }
            if (!empty($users)) {
                foreach ($users as $key => $user_list) {
                    $users[$key]['ot'] = "00:00";
                    $users[$key]['an_late_by'] = "00:00";
                    $users[$key]['an_early_going'] = "00:00";
                    $users[$key]['fn_late_by'] = "00:00";
                    $users[$key]['fn_early_going'] = "00:00";
                    $users[$key]['shift_fn_in'] = $this->explode_time($work_mor_start);
                    $users[$key]['shift_fn_out'] = $this->explode_time($work_mor_end);
                    $users[$key]['shift_an_in'] = $this->explode_time($work_aftnun_start);
                    $users[$key]['shift_an_out'] = $this->explode_time($work_aftnun_end);
                    $users[$key]['full_logs'] = "";
                    $users[$key]['morning_status'] = "";
                    $users[$key]['evening_status'] = "";

                    //get attendance
                    $this->db->where('attendance.user_id', $user_list['id']);
                    $this->db->where('DATE(attendance.created)=', $date_data);
                    $attendance_details = $this->db->get('attendance')->result_array();
                    if (!empty($attendance_details)) {

                        //get_user_morning_time
                        $user_mor_time = $this->user_mor_time($user_list['id'], $date_data);

                        //mor_details
                        $users[$key]['mor_in'] = $this->explode_time($user_mor_time['morning_in_time']);
                        $users[$key]['mor_out'] = $this->explode_time($user_mor_time['morning_out_time']);
                        $users[$key]['mor_late_by'] = $this->explode_time($user_mor_time['morning_lateby_time']);
                        $users[$key]['mor_early_going'] = $this->explode_time($user_mor_time['morning_early_going']);
                        $users[$key]['morning_work_duration'] = $this->explode_time($user_mor_time['morning_work_duration']);

                        $mor_status = "";
                        $users[$key]['morning_status'] = $this->check_attendance_status($users[$key]['morning_work_duration']);
                        $mor_status = $users[$key]['morning_status'];

                        //get_user_evening_time
                        $user_eve_time = $this->user_eve_time($user_list['id'], $date_data);

                        //eve_details
                        $users[$key]['aftnun_in'] = $this->explode_time($user_eve_time['evening_in_time']);
                        $users[$key]['aftnun_out'] = $this->explode_time($user_eve_time['evening_out_time']);
                        $users[$key]['aftnun_late_by'] = $this->explode_time($user_eve_time['evening_lateby_time']);
                        $users[$key]['aftnun_early_going'] = $this->explode_time($user_eve_time['evening_early_going']);
                        $users[$key]['afternoon_work_duration'] = $this->explode_time($user_eve_time['evening_work_duration']);

                        $aftnun_status = "";
                        $users[$key]['evening_status'] = $this->check_attendance_status($users[$key]['afternoon_work_duration']);
                        $aftnun_status = $users[$key]['evening_status'];

                        //mor&eve duration
                        $users[$key]['overall_work_duration'] = $this->explode_time($this->sum_the_time($user_mor_time['morning_work_duration'], $user_eve_time['evening_work_duration']));

                        //punch records
                        $this->db->select('UserId,LogDate,Direction');
                        $this->db->where('DATE(' . $table . '.LogDate)', $date_data);
                        $this->db->where('UserId', $user_list['id']);
                        $device_log = $this->db->get($table)->result_array();

                        $full_logs = "";
                        $log_details = "";
                        $log_details_data = "";
                        if ($device_log != "") {
                            foreach ($device_log as $d_key => $logs) {
                                $keyss = $d_key + 1;
                                $direction = $logs['Direction'];
                                $log_time_explode = explode(' ', $logs['LogDate']);
                                $time = $this->explode_time($log_time_explode[1]);
                                $log_details_data = $time . ":" . $direction;

                                if ($keyss % 5 == "0" && $keyss != 0) {
                                    $log_details_data = $time . ":" . $direction . '<br>';
                                }
                                $log_details[] = $log_details_data;
                            }
                            $full_logs = implode(',', $log_details);
                        }
                        $users[$key]['full_logs_details'] = $device_log;
                        $users[$key]['full_logs'] = $full_logs;

                        $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);
                        $users[$key]['status'] = $check_attend;
                    } else {

                        $this->db->where('DATE(attendance.created)=', $date_data);
                        $check_attendance = $this->db->get('attendance')->result_array();
                        if (count($check_attendance) > 5) {
                            $users[$key]['status'] = "A";
                            $users[$key]['mor_in'] = '-';
                            $users[$key]['mor_out'] = '-';
                            $users[$key]['aftnun_in'] = '-';
                            $users[$key]['aftnun_out'] = '-';
                        } else {

                            $this->db->where("holiday_from <=", $date_data);
                            $this->db->where("holiday_to >=", $date_data);
                            $holidays = $this->db->get('public_holidays')->result_array();
                            if (count($holidays) > 0) {
                                $users[$key]['status'] = "PH";
                                $users[$key]['mor_in'] = '-';
                                $users[$key]['mor_out'] = '-';
                                $users[$key]['aftnun_in'] = '-';
                                $users[$key]['aftnun_out'] = '-';
                            } else {
                                $week_off_holiday = $this->week_off_holiday($date_data);
                                $users[$key]['status'] = $week_off_holiday;
                                if ($users[$key]['status'] == "H") {
                                    $users[$key]['mor_in'] = '-';
                                    $users[$key]['mor_out'] = '-';
                                    $users[$key]['aftnun_in'] = '-';
                                    $users[$key]['aftnun_out'] = '-';
                                }if ($users[$key]['status'] == "A") {
                                    $users[$key]['mor_in'] = '-';
                                    $users[$key]['mor_out'] = '-';
                                    $users[$key]['aftnun_in'] = '-';
                                    $users[$key]['aftnun_out'] = '-';
                                } else {
                                    $users[$key]['mor_in'] = '-';
                                    $users[$key]['mor_out'] = '-';
                                    $users[$key]['aftnun_in'] = '-';
                                    $users[$key]['aftnun_out'] = '-';
                                }
                            }
                        }
                    }
                }
            }

            $user_data = "";
            if ($status != "all") {
                foreach ($users as $key => $result) {
                    if ($status == "1") {
                        if ($result['status'] == "P") {
                            $userdata = $result;
                        }
                    } else {
                        $absent_status = ["A", "1/2A", "1/4A", "3/4A"];

                        if (in_array($result['status'], $absent_status)) {
                            $userdata = $result;
                        }
                    }

                    $user_data[] = $userdata;
                }
                $users = $user_data;
            }

            $daily_report_users[$date_data]['users'] = $users;

            if (count($users) > 0)
                $daily_report_users[$date_data]['is_users'] = 1;
            else
                $daily_report_users[$date_data]['is_users'] = 0;

            $daily_report_users[$date_data]['holidays'] = "";
            // if($status=="all")
            {



                $holiday_exists = 0;
                $this->db->select('attendance.id');
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();

                if (count($attendance_details) == 0) {
                    $this->db->where("holiday_from <=", $date_data);
                    $this->db->where("holiday_to >=", $date_data);
                    $holidays = $this->db->get('public_holidays')->result_array();
                    if ($holidays) {
                        $daily_report_users[$date_data]['holidays'] = "Public Holiday";
                    } else {
                        $holidays = "";
                        $timestamp = strtotime($date_data);
                        $weekday = date("l", $timestamp);

                        $number_week = $this->find_week($date_data);
                        $this->db->where('key', 'Week_end_holidays');
                        $check_week_holidays = $this->db->get('options')->result_array();
                        $week_holidays = "";

                        if ($check_week_holidays != "") {
                            $week_holidays = explode(',', $check_week_holidays[0]['value']);

                            if (($weekday == "Sunday")) {
                                $holidays = "Week Off";
                            } else if (in_array($number_week, $week_holidays) && $weekday == "Saturday") {
                                $holidays = "Holiday";
                            }
                        }
                        $daily_report_users[$date_data]['holidays'] = $holidays;
                    }
                }
            }
        }

//        echo "<pre>";
//        print_r($daily_report_users);
//        exit;
        return $daily_report_users;
    }

    function user_mor_time($user_id, $date) {
        $office_time_details = $this->get_office_time();

        $this->db->where('attendance.user_id', $user_id);
        $this->db->where('DATE(attendance.created)=', $date);
        $attendance_details = $this->db->get('attendance')->result_array();
        $in_time = $attendance_details[0]['in'];

        $attendance_id = $attendance_details[0]['id'];
        $user_mng_in = "";
        //mor_in
        if ($in_time < $office_time_details['fn_in_time']) {
            $user_mng_in = $office_time_details['fn_in_time'];
        } else {
            $user_mng_in = $in_time;
        }
        //mor_out
        $this->db->where('break_table.attendance_id', $attendance_id);
        $this->db->where('break_table.in_time <', $office_time_details['fn_out_time']);
        $this->db->where('break_table.out_time >', $office_time_details['fn_out_time']);
        $this->db->order_by('id', 'desc');
        $mor_out = $this->db->get('break_table')->result_array();
        $user_mng_out = "";
        if (empty($mor_out)) {
            $user_mng_out = $this->explode_time($office_time_details['fn_out_time']);
        } else {
            $user_mng_out = $this->explode_time($mor_out[0]['in_time']);
        }
        $user_mng_late_by = "";
        //mng_late_by
        if ($user_mng_in > $office_time_details['fn_late_coming_threshold'])
            $user_mng_late_by = $this->time_difference($office_time_details['fn_late_coming_threshold'], $user_mng_in);
        else
            $user_mng_late_by = "00:00:00";

        $user_mng_early_going = "";
        //mng_early_going
        if ($user_mng_out < $office_time_details['fn_early_going_threshold'])
            $user_mng_early_going = $this->time_difference($office_time_details['fn_early_going_threshold'], $user_mng_out);
        else
            $user_mng_early_going = "00:00:00";

        $user_time['morning_in_time'] = $user_mng_in;
        $user_time['morning_out_time'] = $user_mng_out;
        $user_time['morning_lateby_time'] = $user_mng_late_by;
        $user_time['morning_early_going'] = $user_mng_early_going;
        $user_time['morning_break_time'] = $this->get_break_time($attendance_id, $user_mng_in, $user_mng_out);
        $user_time['morning_overall_duration'] = $this->time_difference($user_mng_in, $user_mng_out);
        $user_time['morning_work_duration'] = $this->time_difference($this->time_difference($user_mng_in, $user_mng_out), $this->get_break_time($attendance_id, $user_mng_in, $user_mng_out));

        return $user_time;
    }

    function user_eve_time($user_id, $date) {
        $office_time_details = $this->get_office_time();

        $this->db->where('attendance.user_id', $user_id);
        $this->db->where('DATE(attendance.created)=', $date);
        $attendance_details = $this->db->get('attendance')->result_array();

        //eve_in
        $this->db->where('break_table.attendance_id', $attendance_id);
        $this->db->where('break_table.in_time >	', $office_time_details['an_in_time']);
        $this->db->where('break_table.out_time <', $office_time_details['an_in_time']);
        $this->db->order_by('id', 'desc');
        $eve_in = $this->db->get('break_table')->result_array();

        $attendance_id = $attendance_details[0]['id'];
        $user_eve_in = "";
        if ($attendance_details[0]['out'] > $office_time_details['an_in_time']) {
            if (empty($eve_in)) {
                $user_eve_in = $this->explode_time($office_time_details['an_in_time']);
            } else {
                $user_eve_in = $this->explode_time($eve_in[0]['out_time']);
            }
        } else {
            $user_eve_in = "00:00:00";
        }

        $user_eve_late_by = "00:00:00";
        $user_eve_early_going = "00:00:00";
        $eve_out = $attendance_details[0]['out'];
        //eve_out
        if ($eve_out > $office_time_details['an_in_time']) {

            $user_eve_out = "";
            if ($eve_out > $office_time_details['an_out_time'])
                $user_eve_out = $office_time_details['an_out_time'];
            else
                $user_eve_out = $eve_out;

            //eve_late_by
            if ($user_eve_in > $office_time_details['an_late_coming_threshold'])
                $user_eve_late_by = $this->time_difference($office_time_details['an_late_coming_threshold'], $user_eve_in);
            else
                $user_eve_late_by = "00:00:00";


            //eve_early_going
            if ($user_eve_out < $office_time_details['an_early_going_threshold'])
                $user_eve_early_going = $this->time_difference($office_time_details['an_early_going_threshold'], $user_eve_out);
            else
                $user_eve_early_going = "00:00:00";
        }else {
            $user_eve_out = "00:00:00";
        }

        $user_time['evening_in_time'] = $user_eve_in;
        $user_time['evening_out_time'] = $user_eve_out;
        $user_time['evening_lateby_time'] = $user_eve_late_by;
        $user_time['evening_early_going'] = $user_eve_early_going;
        $user_time['evening_break_time'] = $this->get_break_time($attendance_id, $user_eve_in, $user_eve_out);
        $user_time['evening_overall_duration'] = $this->time_difference($user_eve_in, $user_eve_out);
        $user_time['evening_work_duration'] = $this->time_difference($this->time_difference($user_eve_in, $user_eve_out), $this->get_break_time($attendance_id, $user_eve_in, $user_eve_out));

        return $user_time;
    }

    function find_week($date) {
        $datee = strtotime(str_replace("/", "-", $date));
        $firstOfMonth = strtotime(date("Y-m-01", $datee));
        return intval(date("W", $datee)) - intval(date("W", $firstOfMonth)) + 1;
    }

    function get_all_monthly_reports($start_date, $end_date) {
        $monthly_reports = [];
        $office_time_details = $this->get_office_time();

        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];

        $lunch_time = $office_time_details['lunch_time'];
        $ofc_time_hrs = $office_time_details['ofc_time'];

        $office_work_hours = "08:00:00";
        $office_half_day_work_hours = "04:00:00";

        // $after_lunch_time="13:00:00";
        $explode_time = explode(':', $office_work_hours);
        $time_hr = $explode_time[0];
        $time_min = $explode_time[1];
        $half_time = round($time_hr . "." . $time_min) / 2;
        $office_half_day_work_hours = "0" . $half_time . ":00:00";

        $office_work_hours = $ofc_time_hrs;
        $after_lunch_time = $lunch_time;

        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $monthly_reports = $this->db->get('users')->result_array();
        if (count($monthly_reports) > 0) {
            foreach ($monthly_reports as $key => $user_data) {
                $this->db->where('user_id', $user_data['id']);
                $user_detail = $this->db->get('user_department')->result_array();
                if (!empty($user_detail)) {
                    $this->db->where('id', $user_detail[0]['department']);
                    $department_details = $this->db->get('department')->result_array();
                    $monthly_reports[$key]['department'] = $department_details[0]['name'];

                    $this->db->where('id', $user_detail[0]['designation']);
                    $designation_details = $this->db->get('designation')->result_array();
                    $monthly_reports[$key]['designation'] = $designation_details[0]['name'];
                } else {
                    $monthly_reports[$key]['department'] = "";
                    $monthly_reports[$key]['designation'] = "";
                }

                $s_date = date('d-m-Y', strtotime($start_date));
                $std_dt = $end_date . " 00:00:00";
                $exclude_date = new DateTime($std_dt . ' +1 day');
                $e_date = $exclude_date->format('d-m-Y');

                $start = new DateTime($s_date . ' 00:00:00');
                $end = new DateTime($e_date . ' 00:00:00');
                $interval = new DateInterval('P1D');
                $period = new DatePeriod($start, $interval, $end);
                $days_array = "";
                foreach ($period as $date) {
                    $days_array[] = $date->format('d-m-Y');
                }
                $current_day = "";
                for ($d = 0; $d <= count($days_array) - 1; $d++) {
                    $current_day[] = explode("-", $days_array[$d]);
                    $day_array[] = $current_day[0];
                }
                $periods = "";
                $user_mor_late = "";
                $user_aft_late = "";
                $user_mor_early = "";
                $user_aft_early = "";
                foreach ($current_day as $key1 => $dates) {

                    $get_office_time = $this->get_office_time();

                    $work_mor_start = $get_office_time['fn_in_time'];
                    $work_mor_end = $get_office_time['fn_out_time'];
                    $work_aftnun_start = $get_office_time['an_in_time'];
                    $work_aftnun_end = $get_office_time['an_out_time'];

                    $periods[$key1]['date'] = $dates[0];
                    $periods[$key1]['month'] = $dates[1];
                    $periods[$key1]['year'] = $dates[2];
                    $check_date = $dates[2] . "-" . $dates[1] . "-" . $dates[0];

                    $current_date = date('Y-m-d H:i');
                    $explode_current_date = explode(' ', $current_date);
                    $currenct_time = $explode_current_date[1];
                    $explode_currenct_time = explode(':', $currenct_time);
                    $currenct_hr = $explode_currenct_time[0];
                    $currenct_mins = $explode_currenct_time[1];
                    $check_date_name = $dates[2] . $dates[1] . $dates[0] . $currenct_hr . $currenct_mins;

                    $datetime = DateTime::createFromFormat('YmdHi', $check_date_name);
                    $day_name = $datetime->format('D');
                    $periods[$key1]['day_name'] = $day_name;

                    $this->db->where('user_id', $user_data['id']);
                    $this->db->where('DATE(attendance.created)=', $check_date);
                    $attendance_details = $this->db->get('attendance')->result_array();
                    $attendance_id = 0;
                    $user_mor_late_by = "00:00:00";
                    $user_aft_late_by = "00:00:00";
                    $user_mor_early_going = "00:00:00";
                    $user_aft_early_going = "00:00:00";
                    if (count($attendance_details) > 0) {
                        $attendance_id = $attendance_details[0]['id'];

                        //morning details
                        $user_mor_time = $this->user_mor_time($user_data['id'], $check_date);
                        $user_mor_late_by = $user_mor_time['morning_lateby_time'];
                        $periods[$key1]['mor_late_by'] = $this->explode_time($user_mor_late_by);
                        $user_mor_early_going = $user_mor_time['morning_early_going'];
                        $periods[$key1]['mor_early_going'] = $this->explode_time($user_mor_early_going);
                        $mor_status = "";
                        $periods[$key1]['morning_status'] = $this->check_attendance_status($user_mor_time['morning_work_duration']);
                        $mor_status = $periods[$key1]['morning_status'];

                        //Evening details
                        $user_eve_time = $this->user_eve_time($user_data['id'], $check_date);
                        $user_aft_late_by = $user_eve_time['evening_lateby_time'];
                        $periods[$key1]['aftnun_late_by'] = $this->explode_time($user_aft_late_by);
                        $user_aft_early_going = $user_eve_time['evening_early_going'];
                        $periods[$key1]['aftnun_early_going'] = $this->explode_time($user_aft_early_going);
                        $aftnun_status = "";
                        $periods[$key1]['evening_status'] = $this->check_attendance_status($user_eve_time['evening_work_duration']);
                        $aftnun_status = $periods[$key1]['evening_status'];
                        //mor&eve duration
                        $periods[$key1]['overall_work_duration'] = $this->explode_time($this->sum_the_time($user_mor_time['morning_work_duration'], $user_eve_time['evening_work_duration']));

                        $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);

                        $periods[$key1]['month_attenance'] = $check_attend;
                    } else {
                        $this->db->where('DATE(attendance.created)=', $check_date);
                        $check_attendance = $this->db->get('attendance')->result_array();
                        if (count($check_attendance) > 5) {
                            $periods[$key1]['month_attenance'] = "A";
                        } else {
                            $this->db->where("holiday_from <=", $check_date);
                            $this->db->where("holiday_to >=", $check_date);
                            $holidays = $this->db->get('public_holidays')->result_array();
                            if (count($holidays) > 0) {
                                $periods[$key1]['month_attenance'] = "PH";
                            } else {

                                $week_off_holiday = $this->week_off_holiday($check_date);
                                $periods[$key1]['month_attenance'] = $week_off_holiday;
                            }
                        }
                    }
                    $user_mor_late[] = $user_mor_late_by;
                    $user_mor_early[] = $user_mor_early_going;
                    $user_aft_late[] = $user_aft_late_by;
                    $user_aft_early[] = $user_aft_early_going;
                }

                $user_morning_late = $this->explode_time($this->sum_multi_time($user_mor_late));
                $user_morning_early = $this->explode_time($this->sum_multi_time($user_mor_early));
                $user_aftnun_late = $this->explode_time($this->sum_multi_time($user_aft_late));
                $user_aftnun_early = $this->explode_time($this->sum_multi_time($user_aft_early));

                $monthly_reports[$key]['late_by'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_late, $user_aftnun_late)));
                $monthly_reports[$key]['early_going'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_early, $user_aftnun_early)));
                $monthly_reports[$key]['monthly_works'] = $periods;
            }
            // exit;
            return $monthly_reports;
        }
    }

    function get_over_time_reports($start_date, $end_date) {
        $over_reports = [];

        $office_time_details = $this->get_office_time();

        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];

        $over_time = $office_time_details['over_time'];
        $over_hours = $office_time_details['over_time_hours'];
        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $over_reports = $this->db->get('users')->result_array();

        if (!empty($over_reports)) {
            foreach ($over_reports as $key => $user_list) {
                $this->db->where('user_id', $user_list['id']);
                $user_detail = $this->db->get('user_department')->result_array();
                if (!empty($user_detail)) {
                    $this->db->where('id', $user_detail[0]['department']);
                    $department_details = $this->db->get('department')->result_array();
                    $over_reports[$key]['department'] = $department_details[0]['name'];

                    $this->db->where('id', $user_detail[0]['designation']);
                    $designation_details = $this->db->get('designation')->result_array();
                    $over_reports[$key]['designation'] = $designation_details[0]['name'];
                } else {
                    $over_reports[$key]['department'] = "";
                    $over_reports[$key]['designation'] = "";
                }


                $s_date = date('d-m-Y', strtotime($start_date));
                $std_dt = $end_date . " 00:00:00";
                $exclude_date = new DateTime($std_dt . ' +1 day');
                $e_date = $exclude_date->format('d-m-Y');

                $start = new DateTime($s_date . ' 00:00:00');
                $end = new DateTime($e_date . ' 00:00:00');
                $interval = new DateInterval('P1D');
                $period = new DatePeriod($start, $interval, $end);
                $days_array = "";
                foreach ($period as $date) {
                    $days_array[] = $date->format('d-m-Y');
                }
                $current_day = "";
                for ($d = 0; $d <= count($days_array) - 1; $d++) {
                    $current_day[] = explode("-", $days_array[$d]);
                    $day_array[] = $current_day[0];
                }
                $over_time_add = "";
                $periods = "";
                $user_mor_late = "";
                $user_aft_late = "";
                $user_mor_early = "";
                $user_aft_early = "";
                foreach ($current_day as $key1 => $dates) {
                    $late_by[$key1] = "00:00:00";
                    $early_going[$key1] = "00:00:00";
                    $periods[$key1]['date'] = $dates[0];
                    $periods[$key1]['month'] = $dates[1];
                    $periods[$key1]['year'] = $dates[2];
                    $check_date = $dates[2] . "-" . $dates[1] . "-" . $dates[0];

                    $this->db->where('user_id', $user_list['id']);
                    $this->db->where('DATE(attendance.created)=', $check_date);
//                    $this->db->where('out >', $over_time);
                    $attendance_details = $this->db->get('attendance')->result_array();

                    $check_over_time = $this->check_over_time($attendance_details[0]['in'], $attendance_details[0]['out'], $attendance_details[0]['id']);

                    $user_mor_late_by = "00:00:00";
                    $user_aft_late_by = "00:00:00";
                    $user_mor_early_going = "00:00:00";
                    $user_aft_early_going = "00:00:00";

                    $this->db->select('in,out');
                    $this->db->where('user_id', $user_list['id']);
                    $this->db->where('DATE(attendance.created)=', $check_date);
                    $user_attendance_details = $this->db->get('attendance')->result_array();
                    if ($user_attendance_details != "") {

                        //morning details
                        $user_mor_time = $this->user_mor_time($user_list['id'], $check_date);
                        $user_mor_late_by = $user_mor_time['morning_lateby_time'];
                        $late_by[$key1]['mor_late_by'] = $this->explode_time($user_mor_late_by);
                        $user_mor_early_going = $user_mor_time['morning_early_going'];
                        $early_going[$key1]['mor_early_going'] = $this->explode_time($user_mor_early_going);

                        //Evening details
                        $user_eve_time = $this->user_eve_time($user_list['id'], $check_date);
                        $user_aft_late_by = $user_eve_time['evening_lateby_time'];
                        $late_by[$key1]['aftnun_late_by'] = $this->explode_time($user_aft_late_by);
                        $user_aft_early_going = $user_eve_time['evening_early_going'];
                        $early_going[$key1]['aftnun_early_going'] = $this->explode_time($user_aft_early_going);
                    }

                    if ($check_over_time > $over_hours) {

                        $periods[$key1]['over_time'] = $this->round_hours($this->explode_time($check_over_time));
                    } else {
                        $periods[$key1]['over_time'] = "-";
                    }

                    $user_mor_late[] = $user_mor_late_by;
                    $user_mor_early[] = $user_mor_early_going;
                    $user_aft_late[] = $user_aft_late_by;
                    $user_aft_early[] = $user_aft_early_going;
                }

                $user_morning_late = $this->explode_time($this->sum_multi_time($user_mor_late));
                $user_morning_early = $this->explode_time($this->sum_multi_time($user_mor_early));
                $user_aftnun_late = $this->explode_time($this->sum_multi_time($user_aft_late));
                $user_aftnun_early = $this->explode_time($this->sum_multi_time($user_aft_early));

                $over_reports[$key]['late_by'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_late, $user_aftnun_late)));
                $over_reports[$key]['early_going'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_early, $user_aftnun_early)));
                $over_reports[$key]['over_time_add'] = $this->round_hours($this->explode_time($this->sum_multi_time($over_time_add)));
                $over_reports[$key]['over_time_works'] = $periods;
            }
        }
        return $over_reports;
    }

    /* function get_office_time()
      {
      $this->db->where('type','regular');
      $this->db->where('shift_id','1');
      $this->db->get('shift_split')->result_array();
      } */

    function round_hours($time) {
        $explode_time = explode(':', $time);
        $hours = round($explode_time[0]);
        if ($explode_time[1] == "00") {
            $mins = "";
        } else {
            $mins = $explode_time[1];
        }

        if (!empty($mins))
            $time = $hours . "." . $mins;
        else
            $time = $hours;

        if ($hours == 0 && $mins == "")
            $time = 0;
        //if($time="0.")
        //$time=0;

        return $time;
    }

    function sum_multi_time($times) {

        $seconds = 0;

        if ($times) {
            foreach ($times as $time) {

                list($hour, $minute, $second) = explode(':', $time);
                $seconds += $hour * 3600;
                $seconds += $minute * 60;
                $seconds += $second;
            }
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;

        //  return "{$hours}:{$minutes}:{$seconds}";
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
    }

    function time_difference($in_time, $out_time) {


        $time1 = new DateTime($in_time);
        $time2 = new DateTime($out_time);
        $inter = $time2->diff($time1);

        $hours = $inter->h;
        if ($inter->h < 10) {
            $hours = "0" . $inter->h;
        }
        $mins = $inter->i;
        if ($inter->i < 10) {
            $mins = "0" . $inter->i;
        }
        $sec = $inter->s;
        if ($inter->s < 10) {
            $sec = "0" . $inter->s;
        }

        return $hours . ":" . $mins . ":" . $sec;
    }

    function sum_the_time($time1, $time2) {
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time) {
            list($hour, $minute, $second) = explode(':', $time);
            $seconds += $hour * 3600;
            $seconds += $minute * 60;
            $seconds += $second;
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        // return "{$hours}:{$minutes}:{$seconds}";
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
    }

    function explode_time($time) {
        $explode_time = explode(':', $time);
        $time = $explode_time[0] . ":" . $explode_time[1];
        return $time;
    }

    function chk_date_exits($check_date) {
        $data = array();

        $date = date("Y-m-d", strtotime($check_date));
        $timestamp = strtotime($check_date);
        $weekday = date("l", $timestamp);

        $this->db->where("holiday_from <=", $date);
        $this->db->where("holiday_to >=", $date);
        $holidays = $this->db->get('public_holidays');

        if ($holidays->num_rows() > 0) {
            $data['month_attendance'] = "PH";
            return 'PH';
        } else {

            $this->db->where('DATE(attendance.created)=', $date);
            $check_attendance = $this->db->get('attendance')->result_array();
            if (count($check_attendance) > 5) {
                return "A";
            } else {
                $number_week = $this->find_week($date);

                $this->db->where('key', 'Week_end_holidays');
                $check_week_holidays = $this->db->get('options')->result_array();
                $week_holidays = "";

                if ($check_week_holidays != "") {
                    $week_holidays = explode(',', $check_week_holidays[0]['value']);

                    if (($weekday == "Sunday")) {
                        $data['month_attendance'] = "H";
                        return 'WO';
                    } else if (in_array($number_week, $week_holidays) && $weekday == "Saturday") {
                        $data['month_attendance'] = "H";
                        return 'H';
                    } else {
                        $this->db->where('DATE(attendance.created)=', $date);
                        $attendance_details = $this->db->get('attendance')->result_array();

                        if (count($attendance_details) > 4) {
                            return 'A';
                        } else {
                            if (count($attendance_details) == 1 || count($attendance_details) == 2) {

                                return 'H';
                            } else {
                                return '-';
                            }
                        }
                    }
                } else {

                    $data['month_attendance'] = "-";
                    return '-';
                }
            }
        }
    }

    function public_holiday($check_date) {
        $this->db->where("holiday_from <=", $check_date);
        $this->db->where("holiday_to >=", $check_date);
        $holidays = $this->db->get('public_holidays');

        if ($holidays->num_rows() > 0) {
            $data['month_attendance'] = "PH";
            return 'PH';
        } else {
            $data['month_attendance'] = "-";
            return '-';
        }
    }

    function week_off_holiday($check_date) {
        $timestamp = strtotime($check_date);
        $weekday = date("l", $timestamp);

        $number_week = $this->find_week($check_date);
        $this->db->where('key', 'Week_end_holidays');
        $check_week_holidays = $this->db->get('options')->result_array();
        $week_holidays = "";

        if ($check_week_holidays != "") {
            $week_holidays = explode(',', $check_week_holidays[0]['value']);

            if (($weekday == "Sunday")) {
                return 'WO';
            } else if (in_array($number_week, $week_holidays) && $weekday == "Saturday") {
                return 'H';
            } else {

                $this->db->where('DATE(attendance.created)=', $check_date);
                $attendance_details = $this->db->get('attendance')->result_array();

                if (count($attendance_details) > 4) {
                    return 'A';
                } else {

                    if (count($attendance_details) == 1 || count($attendance_details) == 2) {

                        return 'H';
                    } else {
                        return '-';
                    }
                }
            }
        } else {
            return '-';
        }
    }

    function week_end_holiday($check_date) {
        $timestamp = strtotime($check_date);
        $weekday = date("l", $timestamp);

        $number_week = $this->find_week($check_date);
        $this->db->where('key', 'Week_end_holidays');
        $check_week_holidays = $this->db->get('options')->result_array();
        $week_holidays = "";

        if ($check_week_holidays != "") {
            $week_holidays = explode(',', $check_week_holidays[0]['value']);
            if (($weekday == "Sunday")) {
                $data['month_attendance'] = "WO";
                return 'Week Off';
            } else if (in_array($number_week, $week_holidays) && $weekday == "Saturday") {
                $data['reason'] = "H";
                return 'Holiday';
            }
        } else {

            $data['reason'] = "-";
            return '-';
        }
    }

    function check_attendance_status($user_work_duration) {

        $office_time_details = $this->get_office_time();
        //office_general_time_details_start
        $half_day_status = $office_time_details['half_day_calculation'];
        $quater_day_status = $office_time_details['quater_day_calculation'];

        //morning status
        if ($user_work_duration >= $quater_day_status) {
            return '1/2P';
        } else if ($user_work_duration < $quater_day_status && $user_work_duration >= $half_day_status) {
            return '1/4A';
        } else if ($user_work_duration < $half_day_status) {
            return '1/2A';
        } else {
            return ' ';
        }
    }

    function check_overall_attendance_status($mor_status, $aftnun_status) {
        if (($mor_status == "1/2P" && $aftnun_status == "1/2P") || ($mor_status == "1/2P" && $aftnun_status == "1/2P")) {
            return 'P';
        } else if (($mor_status == "1/2P" && $aftnun_status == "1/2A") || ($mor_status == "1/2A" && $aftnun_status == "1/2P")) {
            return '1/2A';
        } elseif (($mor_status == "1/2P" && $aftnun_status == "1/4A") || ($mor_status == "1/4A" && $aftnun_status == "1/2P")) {
            return '1/4A';
        } elseif (($mor_status == "1/2P" && $aftnun_status == "1/2A") || ($mor_status == "1/2A" && $aftnun_status == "1/2P")) {
            return '1/2A';
        } elseif (($mor_status == "1/2A" && $aftnun_status == "1/2A") || ($mor_status == "1/2A" && $aftnun_status == "1/2A")) {
            return 'A';
        } elseif (($mor_status == "1/2A" && $aftnun_status == "1/4A") || ($mor_status == "1/4A" && $aftnun_status == "1/2A")) {
            return '3/4A';
        } elseif (($mor_status == "1/4A" && $aftnun_status == "1/4A") || ($mor_status == "1/4A" && $aftnun_status == "1/4A")) {
            return '1/2A';
        } else {
            return '';
        }
    }

    function check_attendance($in_time, $over_all_atten_over_time, $out_time) {

        $office_time_details = $this->get_office_time();

        $lunch_time = $office_time_details['lunch_time'];
        $lunch_time_from = $office_time_details['lunch_time_from'];
        $lunch_time_to = $office_time_details['lunch_time_to'];
        $ofc_time_hrs = $office_time_details['ofc_time'];
//echo "<pre>";print_r($office_time_details);exit;
        // $office_work_hours = "08:00:00";
        $office_work_hours = $ofc_time_hrs;
        //  $office_half_day_work_hours = "04:00:00";
        // $after_lunch_time="13:00:00";
        $explode_time = explode(':', $office_work_hours);
        $time_hr = $explode_time[0];
        $time_min = $explode_time[1];
        $half_time = round($time_hr . "." . $time_min) / 2;
        $office_half_day_work_hours = "0" . $half_time . ":00:00";
        $office_work_hours = $ofc_time_hrs;
        $after_lunch_time = $lunch_time;
        if ($in_time >= $after_lunch_time) {
            $data['month_attenance'] = "1/2A";
            return '1/2A';
        } else {
            if ($out_time == "") {
                return '-';
            }
            if ($out_time >= $lunch_time_from && $out_time <= $lunch_time_to) {
                $data['month_attenance'] = "1/2A";
                return '1/2A';
            }
            if ($over_all_atten_over_time < $office_half_day_work_hours) {
                $data['month_attenance'] = "LOP";
                return 'LOP';
            }
            if ($over_all_atten_over_time >= $office_half_day_work_hours && $over_all_atten_over_time < $office_work_hours) {
                $data['month_attenance'] = "1/2LOP";
                return '1/2LOP';
            }
            if ($over_all_atten_over_time >= $office_work_hours) {
                $data['month_attenance'] = "P";
                return 'P';
            }
        }
    }

    function check_data_exists() {
        $this->db->where('DATE(attendance.created)=', date('Y-m-d'));
        return $this->db->get('attendance')->result_array();
    }

    function get_absentees_users_list() {

        $users = [];
        $date_data = date('Y-m-d');
        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $users = $this->db->get('users')->result_array();

        if (!empty($users)) {
            foreach ($users as $key => $user_list) {
                //atten in & out
                $this->db->where('attendance.user_id', $user_list['id']);
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();

                if (empty($attendance_details)) {

                    $this->db->where("holiday_from <=", $date_data);
                    $this->db->where("holiday_to >=", $date_data);
                    $holidays = $this->db->get('public_holidays')->result_array();

                    if (count($holidays) > 0) {
                        $users[$key]['status'] = "PH";
                    } else {
                        $timestamp = strtotime($date_data);
                        $weekday = date("l", $timestamp);

                        $number_week = $this->find_week($date_data);
                        $this->db->where('key', 'Week_end_holidays');
                        $check_week_holidays = $this->db->get('options')->result_array();
                        $week_holidays = "";

                        if ($check_week_holidays != "") {
                            $week_holidays = explode(',', $check_week_holidays[0]['value']);

                            if (!(in_array($number_week, $week_holidays) && $weekday == "Saturday" || $weekday == "Sunday")) {

                                $absenties_list = array(
                                    'id' => $user_list['id'],
                                    'employee_id' => $user_list['employee_id'],
                                    'username' => $user_list['username']);
                                $result_absentees[] = $absenties_list;
                            } else {
                                $this->db->where('DATE(attendance.created)=', $date_data);
                                $check_any_one_user_present = $this->db->get('attendance')->result_array();

                                $result = '';
                                if (count($check_any_one_user_present) > 4) {
                                    $result = [
                                        "id" => $user_list['id'],
                                        "employee_id" => $user_list['employee_id'],
                                        "username" => $user_list['username']];
                                    $result_absentees[] = $result;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result_absentees;
    }

    function get_late_comers_list() {

        $users = [];
        $office_time_details = $this->get_office_time();
        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        $date_data = date('Y-m-d');

        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $users = $this->db->get('users')->result_array();

        if (!empty($users)) {
            foreach ($users as $key => $users_list) {

                $this->db->where('attendance.user_id', $users_list['id']);
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();

                $late_coming_users = "";

                if ($attendance_details != "") {
                    $attendance_id = $attendance_details[0]['id'];

                    //get_user_morning_time
                    $user_mor_time = $this->user_mor_time($users_list['id'], $date_data);
                    //get_user_evening_time
                    $user_eve_time = $this->user_eve_time($users_list['id'], $date_data);

                    $morning_late_time = $this->round_hours($user_mor_time['morning_lateby_time']);
                    $evening_late_time = $this->round_hours($user_eve_time['evening_lateby_time']);

                    if ($morning_late_time > 0 || $evening_late_time > 0) {
                        $late_coming_users = array(
                            'username' => $users_list['username'],
                            'employee_id' => $users_list['employee_id'],
                            'fn_late_by' => $this->explode_time($user_mor_time['morning_lateby_time']),
                            'an_late_by' => $this->explode_time($user_eve_time['evening_lateby_time']),
                            'mor_in' => $this->explode_time($user_mor_time['morning_in_time']),
                            'aftnun_in' => $this->explode_time($user_eve_time['evening_in_time']));
                        $result_late_list[] = $late_coming_users;
                    }
                }
            }
        }
        return $result_late_list;
    }

    function get_early_going_list() {

        $users = [];
        $office_time_details = $this->get_office_time();
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];
        $date_data = date('Y-m-d');
        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $users = $this->db->get('users')->result_array();

        if (!empty($users)) {
            foreach ($users as $key => $users_list) {

                $this->db->where('attendance.user_id', $users_list['id']);
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();

                $ealry_going_users = "";

                if ($attendance_details != "") {
                    $attendance_id = $attendance_details[0]['id'];
                    //get_user_morning_time
                    $user_mor_time = $this->user_mor_time($users_list['id'], $date_data);

                    //get_user_evening_time
                    $user_eve_time = $this->user_eve_time($users_list['id'], $date_data);

                    $morning_early_time = $this->round_hours($user_mor_time['morning_early_going']);
                    $evening_early_time = $this->round_hours($user_eve_time['evening_early_going']);
                    if ($morning_early_time > 0 || $evening_early_time > 0) {
                        $ealry_going_users = array(
                            'username' => $users_list['username'],
                            'employee_id' => $users_list['employee_id'],
                            'fn_early_going' => $this->explode_time($user_mor_time['morning_early_going']),
                            'an_early_going' => $this->explode_time($user_eve_time['evening_early_going']),
                            'mor_out' => $user_mor_time['morning_out_time'],
                            'aftnun_out' => $this->explode_time($user_eve_time['evening_out_time']));
                        $result_early_list[] = $ealry_going_users;
                    }
                }
            }
        }
        return $result_early_list;
    }

    function get_today_attendance_notification() {

        $users = [];
        $office_time_details = $this->get_office_time();
        $office_fn_late_time = $office_time_details['fn_late_coming_threshold'];
        $office_fn_early_time = $office_time_details['fn_early_going_threshold'];
        $office_an_late_time = $office_time_details['an_late_coming_threshold'];
        $office_an_early_time = $office_time_details['an_early_going_threshold'];
        $date_data = date('Y-m-d');
        $this->db->select('users.id,users.employee_id,users.username');
        $this->db->where('users.status', 1);
        $users = $this->db->get('users')->result_array();
        $notification_users = "";
        if (!empty($users)) {
            foreach ($users as $key => $user_list) {


                //atten in & out
                $this->db->where('attendance.user_id', $user_list['id']);
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();
                $attendance_id = 0;
                $user_data = "";
                if (!empty($attendance_details)) {
                    $attendance_id = $attendance_details[0]['id'];

                    //get_user_morning_time
                    $user_mor_time = $this->user_mor_time($user_list['id'], $date_data);

                    //mor_details
                    $users[$key]['mor_in'] = $this->explode_time($user_mor_time['morning_in_time']);
                    $users[$key]['mor_out'] = $this->explode_time($user_mor_time['morning_out_time']);
                    $users[$key]['mor_late_by'] = $this->explode_time($user_mor_time['morning_lateby_time']);
                    $users[$key]['mor_early_going'] = $this->explode_time($user_mor_time['morning_early_going']);
                    $users[$key]['morning_work_duration'] = $this->explode_time($user_mor_time['morning_work_duration']);

                    $mor_status = "";
                    $users[$key]['morning_status'] = $this->check_attendance_status($users[$key]['morning_work_duration']);
                    $mor_status = $users[$key]['morning_status'];

                    //get_user_evening_time
                    $user_eve_time = $this->user_eve_time($user_list['id'], $date_data);

                    //eve_details
                    $users[$key]['aftnun_in'] = $this->explode_time($user_eve_time['evening_in_time']);
                    $users[$key]['aftnun_out'] = $this->explode_time($user_eve_time['evening_out_time']);
                    $users[$key]['aftnun_late_by'] = $this->explode_time($user_eve_time['evening_lateby_time']);
                    $users[$key]['aftnun_early_going'] = $this->explode_time($user_eve_time['evening_early_going']);
                    $users[$key]['afternoon_work_duration'] = $this->explode_time($user_eve_time['evening_work_duration']);

                    $aftnun_status = "";
                    $users[$key]['evening_status'] = $this->check_attendance_status($users[$key]['afternoon_work_duration']);
                    $aftnun_status = $users[$key]['evening_status'];

                    $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);
                    $users[$key]['status'] = $check_attend;
                    $user_data = [
                        "user_name" => $user_list['username'],
                        "mor_in" => $this->explode_time($user_mor_time['morning_in_time']),
                        "mor_out" => $user_mor_time['morning_out_time'],
                        "aftnun_in" => $this->explode_time($user_eve_time['evening_in_time']),
                        "aftnun_out" => $this->explode_time($user_eve_time['evening_out_time']),
                        "mor_late_by" => $this->explode_time($user_mor_time['morning_lateby_time']),
                        "aftnun_late_by" => $this->explode_time($user_eve_time['evening_lateby_time']),
                        "mor_early_going" => $this->explode_time($user_mor_time['morning_early_going']),
                        "aftnun_early_going" => $this->explode_time($user_eve_time['evening_early_going']),
                        "morning_status" => $mor_status,
                        "evening_status" => $aftnun_status,
                        "status" => $check_attend
                    ];
                    $notification_users[] = $user_data;
                }
            }
        }

        return $notification_users;
    }

    function get_all_attendance_users() {
        $add_users = [];
        $this->db->select('users.id,users.employee_id,users.username,users.first_name,users.last_name,users.email');
        $this->db->where('users.status', 1);
        $add_users = $this->db->get('users')->result_array();
        if (count($add_users) > 0) {
            foreach ($add_users as $key => $user_data) {
                $this->db->where('user_id', $user_data['id']);
                $user_detail = $this->db->get('user_department')->result_array();
                if (!empty($user_detail)) {
                    $this->db->where('id', $user_detail[0]['department']);
                    $department_details = $this->db->get('department')->result_array();
                    $add_users[$key]['department'] = $department_details[0]['name'];

                    $this->db->where('id', $user_detail[0]['designation']);
                    $designation_details = $this->db->get('designation')->result_array();
                    $add_users[$key]['designation'] = $designation_details[0]['name'];
                } else {
                    $add_users[$key]['department'] = "";
                    $add_users[$key]['designation'] = "";
                }
            }
            return $add_users;
        }
    }

    function get_break_time($id, $in_time, $out_time) {

        $this->db->where('break_table.attendance_id', $id);
        $this->db->where('break_table.in_time > ', $in_time);
        $this->db->where('break_table.out_time <', $out_time);
        $break_time = $this->db->get('break_table')->result_array();
        $over_all_time = "00:00:00";

        if (count($break_time) > 0) {
            $total_time = "";
            foreach ($break_time as $key => $log_data) {
                $total_time[] = $this->time_difference($log_data['out_time'], $log_data['in_time']);
            }
            $over_all_time = $this->sum_multi_time($total_time);
        }

        return $over_all_time;
    }

    function get_monthly_users_list_view($start_dates, $user_id) {
        $monthly_reports_view = [];

        $office_time_details = $this->get_office_time();
        $user_mor_late = "";
        $user_aft_late = "";
        $user_mor_early = "";
        $user_aft_early = "";
        foreach ($start_dates as $date_data) {
            $users = [];
            $this->db->select('users.id,users.employee_id,users.username');
            $this->db->where('users.status', 1);
            $this->db->where('users.id', $user_id);
            $users = $this->db->get('users')->result_array();

            $explode_date = explode('-', $date_data);
            $month = $explode_date[1];
            $year = $explode_date[0];
            $table = 'devicelogs_' . $month . '_' . $year;
            $date_for_name = date('d-m-Y', strtotime($date_data));
            $nameOfDay = date('l', strtotime($date_for_name));
            if (count($users) > 0) {
                $this->db->where('attendance.user_id', $user_id);
                $this->db->where('DATE(attendance.created)=', $date_data);
                $attendance_details = $this->db->get('attendance')->result_array();
                $user_mor_late_by = "00:00:00";
                $user_aft_late_by = "00:00:00";
                $user_mor_early_going = "00:00:00";
                $user_aft_early_going = "00:00:00";
                if (!empty($attendance_details)) {
                    $user_mor_time = $this->user_mor_time($user_id, $date_data);
                    $user_mor_late_by = $user_mor_time['morning_lateby_time'];
                    $users['mor_late_by'] = $this->explode_time($user_mor_late_by);
                    $user_mor_early_going = $user_mor_time['morning_early_going'];
                    $users['mor_early_going'] = $this->explode_time($user_mor_early_going);
                    $mor_status = "";
                    $users['morning_status'] = $this->check_attendance_status($user_mor_time['morning_work_duration']);
                    $mor_status = $users['morning_status'];
                    $user_eve_time = $this->user_eve_time($user_id, $date_data);
                    $user_aft_late_by = $user_eve_time['evening_lateby_time'];
                    $users['aftnun_late_by'] = $this->explode_time($user_aft_late_by);
                    $user_aft_early_going = $user_eve_time['evening_early_going'];
                    $users['aftnun_early_going'] = $this->explode_time($user_aft_early_going);
                    $aftnun_status = "";
                    $users['evening_status'] = $this->check_attendance_status($user_eve_time['evening_work_duration']);
                    $aftnun_status = $users['evening_status'];
                    //punch records
                    $this->db->select('UserId,LogDate,Direction');
                    $this->db->where('DATE(' . $table . '.LogDate)', $date_data);
                    $this->db->where('UserId', $user_id);
                    $device_log = $this->db->get($table)->result_array();

                    $full_logs = "";
                    $log_details = "";
                    $log_details_data = "";
                    if ($device_log != "") {
                        foreach ($device_log as $d_key => $logs) {
                            $keyss = $d_key + 1;
                            $direction = $logs['Direction'];
                            $log_time_explode = explode(' ', $logs['LogDate']);
                            $time = $this->explode_time($log_time_explode[1]);
                            $log_details_data = $time . ":" . $direction;

                            if ($keyss % 5 == "0" && $keyss != 0) {
                                $log_details_data = $time . ":" . $direction . '<br>';
                            }
                            $log_details[] = $log_details_data;
                        }
                    }

                    if (empty($attendance_details[0]['out']))
                        $attendance_details[0]['out'] = "00:00:00";
                    $check_attend = $this->check_overall_attendance_status($mor_status, $aftnun_status);
                    $users['status'] = $check_attend;

                    $user_mor_late[] = $user_mor_late_by;
                    $user_mor_early[] = $user_mor_early_going;
                    $user_aft_late[] = $user_aft_late_by;
                    $user_aft_early[] = $user_aft_early_going;

                    $user_morning_late = $this->explode_time($this->sum_multi_time($user_mor_late));
                    $user_morning_early = $this->explode_time($this->sum_multi_time($user_mor_early));
                    $user_aftnun_late = $this->explode_time($this->sum_multi_time($user_aft_late));
                    $user_aftnun_early = $this->explode_time($this->sum_multi_time($user_aft_early));

                    $users_data = [
                        "id" => $users[0]['id'],
                        "user_name" => $users[0]['username'],
                        "day" => date('d-m-Y', strtotime($date_data)),
                        "day_name" => $nameOfDay,
                        "user_mor_in" => $this->explode_time($user_mor_time['morning_in_time']),
                        "user_mor_out" => $this->explode_time($user_mor_time['morning_out_time']),
                        "user_mor_work_duration" => $this->explode_time($user_mor_time['morning_work_duration']),
                        "user_eve_in" => $this->explode_time($user_eve_time['evening_in_time']),
                        "user_eve_out" => $this->explode_time($user_eve_time['evening_out_time']),
                        "user_eve_work_duration" => $this->explode_time($user_eve_time['evening_work_duration']),
                        "status" => $check_attend,
                        "work_duration" => $this->explode_time($this->sum_the_time($user_mor_time['morning_work_duration'], $user_eve_time['evening_work_duration'])),
                        "break_duration" => $this->explode_time($this->get_break_time($attendance_details[0]['id'], $attendance_details[0]['in'], $attendance_details[0]['out'])),
                        "full_logs" => $full_logs];
                } else {

                    $users_data = [
                    ];
                }
            }
            $monthly_reports_view['late_by'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_late, $user_aftnun_late)));
            $monthly_reports_view['early_going'] = $this->round_hours($this->explode_time($this->sum_the_time($user_morning_early, $user_aftnun_early)));
            $monthly_reports_view[$date_data]['users_data'] = $users_data;
        }

        return $monthly_reports_view;
    }

    function check_over_time($in_time, $out_time, $atten_id) {
        $time24 = "24:00:00";
        $office_time_details = $this->get_office_time();
        $office_out_time = $office_time_details['out_time'];
        if ($in_time > $out_time) {
            $time_diff = $this->time_difference($time24, $office_out_time);
            $sum_time = $this->sum_the_time($time_diff, $out_time);
        } else {
            if ($office_out_time < $out_time) {
                $sum_time = $this->time_difference($out_time, $office_out_time);
            } else {
                $sum_time = "00:00:00";
            }
        }
        $round_off_sum = $this->round_hours($sum_time);
        if ($round_off_sum > 0) {
            $this->db->where('break_table.attendance_id', $atten_id);
            $this->db->where('break_table.in_time > ', $office_out_time);
            $break_time = $this->db->get('break_table')->result_array();

            $over_all_time = "00:00:00";

            if (count($break_time) > 0) {
                $total_time = "";
                foreach ($break_time as $key => $log_data) {
                    $total_time[] = $this->time_difference($log_data['out_time'], $log_data['in_time']);
                }
                $over_all_time = $this->sum_multi_time($total_time);
            }

            $overall_over_time_work_duration = $this->time_difference($over_all_time, $sum_time);
        }
        return $overall_over_time_work_duration;
    }

}
