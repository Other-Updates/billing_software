<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />

<script type="text/javascript" src="<?= $theme_path ?>/js/employee.js"></script>

<div class="contentinner mb-100">
    <div class="media mt--20">
        <h4 class="widgettitle">Employee Details</h4> <a href="<?= $this->config->item('base_url') . "masters/biometric/add_employee" ?>" class="btn btn-success right topgen  "style="margin-top: -34px;"><span class="glyphicon glyphicon-plus"></span>Add</a>
    </div>

    <div class="widgetcontent">

        <?php
//        $user_role = json_decode($roles[0]["roles"]);
//        $filter = $thiss->session_view->get_session('masters', 'employees');
        //print_r($filter);

        $head = array("S.No", "Employee Id", "Username", "Employee Name", "Department", "Designation", "Email");

        $db_name = array("id", "emp_id", "username", "first_name", "dept_name", "des_name", "email");

        if (isset($status)) {

            $db_name[] = "status";

            $head[] = "Status";
        }
        ?>

        <?php
        $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');



        echo form_open('', $attributes);
        ?>

        <div class="">

<!--            <table class="table responsive_table">

                <thead>

                    <tr>

                        <th>Department &nbsp; <?php
            $default = '';

            if (isset($filter["department"]) && !empty($filter["department"])) {

                $default = $filter["department"];
            }

            $options = array();

            if (isset($departments) && !empty($departments)) {

                foreach ($departments as $dept) {

                    $options[$dept["dept_id"]] = ucwords($dept["dept_name"]);
                }
            }



            echo form_multiselect('department[]', $options, $default, 'class="multiselect" id="department_select"');
            ?></th>

                        <th id ="designation">Designation &nbsp; <?php
            $default = '';

            if (isset($filter["designation"]) && !empty($filter["designation"])) {

                $default = $filter["designation"];
            }

            $options = array();

            if (isset($designations) && !empty($designations)) {

                foreach ($designations as $des) {

                    $options[$des["id"]] = ucwords($des["name"]);
                }
            }

            echo form_multiselect('designation[]', $options, $default, 'class="multiselect" id="designation_select"');
            ?></th>



                        <th>&nbsp;

            <?php
            $options = array('' => 'Select');

            $default = '';

            $values = array("access_id" => "Access Id", "blood_group" => "Blood group", "email" => "Email", "employee_id" => "Employee Id", "gender" => "Gender", "marital_status" => "Marital status", "mobile" => "Mobile no", "religion" => "Religion", "username" => "Username");

            if (isset($filter["field"]) && !empty($filter["field"])) {

                $default = $filter["field"];
            }

            foreach ($values as $key => $val) {

                $options[$key] = $val;
            }

            echo form_dropdown('field', $options, $default, 'class="uniformselect"');
            ?>

                        </th>

                        <th>&nbsp;

            <?php
            $default = '';

            if (isset($filter["value"]) && $filter["value"] != "") {

                $default = $filter["value"];
            }

            $data = array(
                'name' => 'value',
                'class' => 'input-medium',
                'value' => $default
            );

            echo form_input($data);
            ?>

                        </th>

                        <th>Status&nbsp;

            <?php
            $options = array();

            $default = 0;

            if (isset($filter["inactive"]) && !empty($filter["inactive"])) {

                $default = $filter["inactive"];
            }

            $values = array(0 => "Active", 1 => "In-active", 2 => "Both");

            if (isset($filter["field"]) && !empty($filter["field"])) {

                $default = $filter["field"];
            }

            foreach ($values as $key => $val) {

                $options[$key] = $val;
            }

            echo form_dropdown('inactive', $options, $default, 'class="uniformselect"');
            ?>

                        </th>

                        <th>&nbsp;

            <?php
            $data = array(
                'name' => 'go',
                'value' => '  Go  ',
                'class' => 'btn btn-info border4',
                'title' => 'Go'
            );



            echo form_submit($data);
            ?>

                        </th>

                        <th>&nbsp;

                            <a href="javascript:void(0)" style="float:right" title="Reset" ><input type="button" class="btn btn-danger border4 reset" value="Reset"></a>

                        </th>

                    </tr>

                </thead>

            </table>-->

        </div>
        <div class="panel-body mt-top5">
            <?php
//echo $count;

            $options = array();

            $this->load->model('options_model');

            $record = array(10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 120, 140, 160, 180, 200);

            $closest = $this->options_model->getClosest($no_of_users1[0]['count'], $record);

//echo $closest;

            for ($k = 10; $k <= $closest;) {

                $options[$k] = $k;

                if ($k < 100)
                    $k = $k + 10;
                else
                    $k = $k + 20;
            }

            if ($no_of_users1[0]['count'] >= 1000) {

                $count_start = $no_of_users1[0]['count'] / 100;

                if ($count_start >= 10) {



                    for ($c = 4; $c < $count_start;) {

                        $options[$c * 100] = $c * 100;

                        $c+=2;
                    }
                }
            }

            if (!in_array($count, $options)) {

                $max = $this->options_model->getClosest($count, $options);

                if ($max < $no_of_users1[0]['count'])
                    $count = "all";
                else
                    $count = $max;
            }

//$no_of_users1[0]['count'] = 1150;

            /* $options = array(10=>"10",25=>"25",50=>"50");

              $count_start = $no_of_users1[0]['count']/100;

              if($count_start<=10)

              {

              for($c=1;$c<$count_start;$c++)

              {

              $options[$c*100] = $c*100;

              }

              }

              else if($count_start>10)

              {



              for($c=1;$c<$count_start;)

              {

              $options[$c*100] = $c*100;

              $c+=2;

              }



              } */

            $options["all"] = "All";

//echo $no_of_users1[0]['count'];

            echo "Show " . form_dropdown('record_show', $options, $count, "id='count_change'") . " entries ";

            /* $x = array(0, 5, 10, 11, 12, 20);

              $search = 8;

              echo getClosest($search,$x);

             */
            ?>



<!--            <span style="float:right;margin-right:8px;" style="display:none;" id="legend">

                <span style="background-color:#c73f38;" class="btn-rounded" >&nbsp;&nbsp;&nbsp;&nbsp;</span> - Incomplete

            </span>-->

            <div class="scroll_bar">

                <table class="table table-bordered sortable table-striped table-bordered responsive dataTable no-footer dtr-inline
                       tquantity-cntr tamount-right">

                    <thead>

                        <tr>



                            <?php
                            $i = 0;

//print_r($users);

                            $id_sort = 0;

                            foreach ($head as $ele) {

                                $elem_class = $elem_id = "";

                                if (isset($filter["sort"])) {

                                    if ($db_name[$i] == $filter["sort"]) {

                                        $elem_class = "class='sort' ";

                                        $elem_id = "id='" . $filter["order"] . "' ";

                                        if ($filter["sort"] == "id" && $filter["order"] == "desc")
                                            $id_sort = 1;
                                    }
                                }



                                echo "<th " . $elem_class . $elem_id . " data='" . base64_encode($db_name[$i++]) . "'>" . $ele . "</th>";
                            }

                            /* if(isset($status))

                              {

                              echo "<th>Status</th>";

                              } */

//echo $id_sort;

                            if ($id_sort == 1)
                                $s = $no_of_users1[0]['count'] - $start;
                            else
                                $s = $start + 1;
                            ?>

                            <th class="action">Action</th>

                        </tr>



                    </thead>

                    <tbody>

                        <?php
                        if (isset($users) && !empty($users)) {

                            $exist = 0;

                            foreach ($users as $user) {

                                $class = "";

                                $this->load->model('masters/user_shift_model');

                                $this->load->model('masters/user_salary_model');

                                $current_shift = $this->user_shift_model->get_user_current_shift_by_user_id($user["id"], null);

                                $salary_group = $this->user_salary_model->get_user_salary_by_user_id($user["id"]);

                                if ($user["dept_name"] == "" || $user["des_name"] == "" || empty($current_shift) || empty($salary_group)) {

                                    $class = "in-complete";

                                    $exist = 1;
                                }
                                ?>

                                <tr class="<?= $class ?>">

                                    <td class="center"><?php echo $id_sort == 1 ? $s-- : $s++; ?></td>

                                    <td class="center"><?= $user['employee_id'] ?></td>

                                    <td class="center"><?= $user['username'] ?></td>

                                    <td class="center"><?= ucwords($user['first_name']) . " " . ucwords($user['last_name']) ?></td>

                                    <td class="center"><?= ucwords($user["dept_name"]) ?></td>

                                    <td class="center"><?= ucwords($user["des_name"]) ?></td>

                                    <td class="center"><?= $user['email'] ?></td>

                                    <?php
                                    $us = "active";

                                    if ($user["status"] == 0)
                                        $us = "in-active";

                                    if (isset($status)) {

                                        echo "<td class='center'>" . $us . "</td>";
                                    }
                                    ?>

                                    <td class="center action">

                                                <!--<a href="<?= $this->config->item('base_url') . "masters/biometric/view_employee/" . $user["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-info btn-xs btn-rounded" title="" data-original-title="View"> <span class="fa fa-eye"></span></a>-->


                                        <?php
                                        $v = 1;
                                        ?>

                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_employee/" . $user["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs " title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>


                                        <?php
                                        $v = 1;
                                        ?>

                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/delete_employee/" . $user["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Delete"><span class="fa fa-log-out "> <span class="fa fa-ban"></span></span></a>

                                    </td>
                                </tr>



                                <?php
                            }
                        } else {

                            if (isset($status)) {

                                echo "<tr><td colspan='9'>No Records Found</td></tr>";
                            } else {

                                echo "<tr><td colspan='8'>No Records Found</td></tr>";
                            }
                        }
                        ?>

                    </tbody>

                </table>

            </div>

            <?php
            if (isset($users) && !empty($users)) {

                $end = $start + count($users);



                $start = $start + 1;
                ?>

                Showing <?= $start ?> to <?= $end ?> of <?= $no_of_users1[0]['count'] ?> records

            <?php } ?>

        </div>
    </div>
</div>

<div class="button_right_align">

    <?php
    if (isset($links1) && $links1 != NULL)
        echo $links1;
    ?><br />

    <?php if (in_array("masters:add_employee", $user_role)) { ?>



    <?php } ?>

</div>


</div>

<script type="text/javascript">

    $(document).ready(function () {

<?php if ($exist == 1) { ?>

            $("#legend").css("display", "block");

    <?php
}

if ($v == 0) {
    ?>

            $(".action").css("display", "none");

<?php } ?>

    });

</script>