<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<div class="mainpanel">
    <div class="media mt--20">
        <h4>Designation Details
            <?php
            if (isset($links) && $links != NULL)
                echo $links;
            ?>

            <?php //if (in_array("masters:add_designation", $user_role)) {   ?>

            <p class="right"> <a href="<?= $this->config->item('base_url') . "masters/biometric/add_designation/" ?>"  class="btn btn-success topgen" style="background-color:sal#ff5a92; color:#ffffff"><span class="glyphicon glyphicon-plus"></span> Add</a>&nbsp;
            </p>
            <?php //}   ?></h4>

    </div>

    <div class="widgetcontent">
        <div class="contentpanel">

            <div class="panel-body mt-top5">

                <table  id="basicTable_call_back" class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">

                    <thead>

                        <?php
                        //print_r($shifts);
                        //	$user_role = json_decode($roles[0]["roles"]);
//                    $head = array("S.No", "Designation");
//
//                    $db_name = array("id", "name");
                        ?>

                        <tr>
                            <th class="action-btn-align">S.No</th>
                            <th class="action-btn-align">Designation</th>
                            <th class="hide_class action-btn-align">Action</th>
                            <?php
                            $i = 0;

                            $id_sort = 0;

//                            $filter = array();
//                            foreach ($head as $ele) {
//
//                                $elem_class = $elem_id = "";
//
//                                if ($db_name[$i] == $filter["sort"]) {
//
//                                    $elem_class = "class='sort' ";
//
//                                    $elem_id = "id='" . $filter["order"] . "' ";
//
//                                    if ($filter["sort"] == "id" && $filter["order"] == "desc")
//                                        $id_sort = 1;
//                                }
//
//                                echo "<th " . $elem_class . $elem_id . " data='" . base64_encode($db_name[$i++]) . "'>" . $ele . "</th>";
//                            }
//
                            if ($id_sort == 1)
                                $s = $no_of_des[0]['count'] - $start;
                            else
                                $s = $start + 1;
                            ?>



                        </tr>

                    </thead>

                    <tbody id='result_div' >

                        <?php
                        if (isset($designations) && !empty($designations)) {



                            foreach ($designations as $des) {
                                ?>

                                <tr>

                                    <td class="first_td action-btn-align"><?php echo $id_sort == 1 ? $s-- : $s++; ?></td>

                                    <td class="action-btn-align"><?= ucwords($des['name']) ?></td>

                                    <td class="hide_class action-btn-align">

                                        <?php
                                        $v = 0;

                                        //if (in_array("masters:edit_designation", $user_role)) {
                                        ?>
                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_designation/" . $des["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>



                                        <?php
                                        $v = 1;
                                        // }
                                        //   if (in_array("masters:delete_designation", $user_role)) {
                                        ?>
                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/delete_designation/" . $des["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Delete"><span class="fa fa-log-out "> <span class="fa fa-ban"></span></span></a>

                                        <?php
                                        $v = 1;
                                        //  }
                                        ?>

                                    </td>

                                </tr>

                                <?php
                            }
                        } else {

                            echo "<tr><td colspan='3'>No Records Found </tr>";
                        }
                        ?>

                    </tbody>

                </table>
                <?php
                if (isset($designations) && !empty($designations)) {



                    $end = $start + count($designations);

                    $start = $start + 1;
                    ?>

                    Showing <?= $start ?> to <?= $end ?> of <?= $no_of_des[0]['count'] ?> records

                <?php } ?>
            </div>
        </div>


        <div class="button_right_align">



        </div>

    </div>

</div>

<script type="text/javascript">

    $(document).ready(function () {

<?php if ($v == 0) { ?>

            //	$(".action").css("display","none");

<?php } ?>

    });

</script>

