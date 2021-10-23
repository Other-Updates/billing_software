
<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script type="text/javascript" src="<?= $theme_path; ?>/js/department.js"></script>
<style>
    .btn-xs {padding: 0px 3px 1px 4px !important; }
    .bg-red {background-color: #dd4b39 !important;}
    .bg-green {background-color:#09a20e !important;}
    .bg-yellow{ background-color:orange !important; }
    .ui-datepicker td.ui-datepicker-today a {background:#999999;}
</style>


<script type="text/javascript" src="<?= $theme_path ?>/js/employee.js"></script>
<script type="text/javascript">
    $(document).on('click', '.mtop4', function () {

        var dept_id = $('#dept_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/masters/search_test',
            method: "POST",
            data: {dept_id: dept_id, from_date: from_date, to_date: to_date},
            success: function (response) {
//
//                console.log(response);
//                alert(response);
                $('#display_info').html(response);
            }
        });
    });
    $(document).on('click', '.mtop4', function () {
        location.reload();
    });
</script>
<div class="contentinner">


    <div class="media mt--20">
        <h4 class="widgettitle">Public Holidays </h4><a href="<?= $this->config->item('base_url') . "masters/biometric/add_public_holidays/" ?>" class="btn btn-success right topgen  "style="margin-top: -34px;"><span class="glyphicon glyphicon-plus"></span> New Holiday</a>
    </div>
    <!--    <div class="panel-body mt--40">
            <div class="row search_table_hide search-area">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">Department</label>
                        <select id='dept_id' class="form-control" name="dept_id">
                            <option>Select</option>
    <?php
    if (isset($departments) && !empty($departments)) {
        foreach ($departments as $val) {
            ?>
                                                                                                                            <option value='<?= $val['dept_name'] ?>'><?= $val['dept_name'] ?></option>
            <?php
        }
    }
    ?>
                        </select>
                    </div>
                </div>


                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <input type="" id='from_date'  class="form-control datepicker" name="from_date" placeholder="dd-mm-yyyy" >
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="control-label">To Date</label>
                        <input type=""  id='to_date' class="form-control datepicker" name="to_date" placeholder="dd-mm-yyyy">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group mcenter">
                        <label class="control-label col-md-12 mnone">&nbsp;</label>
                        <a id='search' class="btn btn-success  mtop4"><span class="glyphicon glyphicon-search"></span> Search</a>&nbsp;
                        <a class="btn btn-danger1  mtop4" id="clear"><span class="fa fa-close"></span> Clear</a>
                    </div>
                </div>
            </div>
        </div>-->

    <div class="panel-body mt-top5">
        <table class="table table-bordered sortable  table-striped table-bordered responsive dataTable no-footer dtr-inline
               tquantity-cntr tamount-right" >

            <thead>

                <?php
                $head = array("S.No", "Reason", "From Date", "To Date", "Department");

                $db_name = array("id", "reason", "holiday_from", "holiday_to", "department");
                ?>

                <tr>

                    <?php
                    $i = 0;

                    $id_sort = 0;

                    //$filter = $this->session_view->get_session(null,null);

                    foreach ($head as $ele) {

                        $elem_class = $elem_id = "";

                        if (isset($filter["sort"]) && $db_name[$i] == $filter["sort"]) {

                            $elem_class = "class='sort' ";

                            $elem_id = "id='" . $filter["order"] . "' ";

                            if ($filter["sort"] == "id" && $filter["order"] == "desc")
                                $id_sort = 1;
                        }

                        echo "<th " . $elem_class . $elem_id . " data='" . base64_encode($db_name[$i++]) . "'>" . $ele . "</th>";
                    }

                    if ($id_sort == 1)
                        $s = $count - $start;
                    else
                        $s = $start + 1;
                    ?>

                    <th class="action center">Action</th>

                </tr>

            </thead>

            <tbody id="display_info">

                <?php
                if (isset($holidays) && !empty($holidays)) {



                    foreach ($holidays as $val2) {
                        ?>

                        <tr>

                            <td class="center"><?php echo $id_sort == 1 ? $s-- : $s++; ?></td>

                            <td class="center"><?= ucwords($val2['reason']) ?></td>

                            <td class="center"><?= $val2["from_date"] ?></td>

                            <td class="center"><?= $val2["to_date"] ?></td>

                            <td class="center"><?php
                                //print_r($val2["department_names"]);

                                $departments = explode(',', $val2["department_names"]);

                                if (count($departments) > 1)
                                    echo count($departments) . " Departments";
                                else
                                    echo ucwords($val2["department_names"])
                                    ?></td>


                                                                                                                                                                                                                                                                                                                                                                                                                                        <!--                        <td class='hide_class '>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href="<?php echo $this->config->item('base_url') . 'masters/edit_public_holiday/' ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="View" ><span class="fa fa-eye"></span></a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                </td>-->

                            <?php
                            $v = 0;
                            ?>

                            <td>
                                <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_public_holiday/" . $val2["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs " title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>
                                <!--<a href="<?= $this->config->item('base_url') . "masters/edit_public_holiday/" . $val2["id"] ?>" class="" title="Edit">-->


                                <?php
                                $v = 1;


                                // }
                                // if (in_array("masters:delete_public_holiday", $user_role)) {
                                ?>
                                <a href="<?= $this->config->item('base_url') . "masters/biometric/delete_public_holiday/" . $val2["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Delete"><span class="fa fa-log-out "> <span class="fa fa-ban"></span></span></a>
                               <!--<a href="<?= $this->config->item('base_url') . "masters/delete_public_holiday/" . $val2["id"] ?>" title="Delete"-->

                                                                                                                                                                                                                                                                                                                                                                                       <!--class="deleterow btn btn-error btn-danger btn-rounded"><i class=" icon-remove"></i></a>-->

                                <?php
                                $v = 1;
                                // }
                                ?>

                            </td>

                        </tr>

                        <?php
                    }
                } else {



                    echo "<tr><td colspan='6'>No Records Found</td></tr>";
                }
                ?>

            </tbody>

        </table>


        <?php
        if (isset($holidays) && !empty($holidays)) {



            $end = $start + count($holidays);

            $start = $start + 1;
            ?>

            Showing <?= $start ?> to <?= $end ?> of <?= $count ?> records



        <?php } ?>

        <div class="button_right_align">

            <?php
            if (isset($links) && $links != NULL)
                echo $links;
            ?><br />

            <?php if (in_array("masters:add_public_holidays", $user_role)) { ?>

                <a href="<?= $this->config->item('base_url') . "masters/biometric/add_public_holidays/" ?>" title="Add"><input type="button" class="btn btn-primary btn-rounded" value="Add"></a>

            <?php } ?>

        </div>
    </div>

</div>



<script type="text/javascript">

    $(document).ready(function () {

<?php if ($v == 0) { ?>

            $(".action").css("display", "none");
<?php } ?>

    });

</script>

