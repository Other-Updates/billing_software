<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?><script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script><script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script><script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script><link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" /><div class="mainpanel">    <div class="media mt--20">        <h4>Department details            <?php            if (isset($links) && $links != NULL)                echo $links;            ?>            <p class="right">  <a href="<?= $this->config->item('base_url') . "masters/biometric/add_department/" ?>" class="btn btn-success topgen" style="background-color:sal#ff5a92; color:#ffffff"><span class="glyphicon glyphicon-plus"></span> Add</a>&nbsp;            </p>        </h4>    </div>    <div class="contentpanel">        <div class="panel-body mt-top5">            <div class="">                <table  id="basicTable_call_back" class="table table-striped table-bordered responsive dataTable no-footer dtr-inline">                    <thead>                        <tr>                            <td class="action-btn-align">S.No</td>                            <td class="action-btn-align">Department Name</td>                            <td class="action-btn-align">Head</td>                            <td class="action-btn-align">Status</td>                            <?php                            $filter = array();                            $i = 0;                            $id_sort = 0;                            foreach ($head as $ele) {                                $elem_class = $elem_id = "";                                if ($db_name[$i] == $filter["sort"]) {                                    $elem_class = "class='sort' ";                                    $elem_id = "id='" . $filter["order"] . "' ";                                    if ($filter["sort"] == "dept_id" && $filter["order"] == "desc")                                        $id_sort = 1;                                }                                echo "<th " . $elem_class . $elem_id . " data='" . base64_encode($db_name[$i++]) . "'>" . $ele . "</th>";                            }                            if ($id_sort == 1)                                $s = $no_of_depts[0]['count'] - $start;                            else                                $s = $start + 1;                            ?>                            <td class="hide_class action-btn-align">Action</td>                        </tr>                    </thead>                    <tbody id='result_div' >                        <?php                        if (isset($departments) && !empty($departments)) {                            foreach ($departments as $dept) {                                ?>                                <tr>                                    <td class="first_td action-btn-align"><?= $id_sort == 1 ? $s-- : $s++; ?></td>                                    <td class="action-btn-align"><?= ucwords($dept['dept_name']) ?></td>                                    <td class="action-btn-align"><?= ucwords($dept['department_head']) ?></td>                                    <td class="action-btn-align"><?php echo ($dept["status"] == 0) ? "Disable" : "Enable" ?></td>                                    <td class="hide_class action-btn-align">                                        <?php                                        $v = 0;                                        // if (in_array("masters:view_department")) {                                        ?>                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/view_department/" . $dept["dept_id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="View"><span class="fa fa-eye"></span> </span></a>                                                                                <!--                                        <a href="<?= $this->config->item('base_url') . "masters/masters/view_department/" . $dept["dept_id"] ?>" title="View" class="btn btn-success btn-rounded">                                                                                                                   <i class="fa fa-eye"></i></a>-->                                        <?php                                        $v = 1;                                        // }                                        // if (in_array("masters:edit_department")) {                                        ?>                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_department/" . $dept["dept_id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>                                                                                        <!--                                        <a href="<?= $this->config->item('base_url') . "masters/masters/edit_department/" . $dept["dept_id"] ?>" title="Edit" class="btn btn-info btn-rounded">                                                                                                                       <i class="icon-pencil"></i></a>-->                                        <?php                                        $v = 1;                                        // }                                        // if (in_array("masters:delete_department")) {                                        ?>                                        <a href="<?= $this->config->item('base_url') . "masters/biometric/delete_department/" . $dept["dept_id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Delete"><span class="fa fa-log-out "> <span class="fa fa-ban"></span></span></a>                                                                <!--                                        <a href="<?= $this->config->item('base_url') . "masters/masters/delete_department/" . $dept["dept_id"] ?>" title="Delete"                                                                                                           class="deleterow btn btn-error btn-danger btn-rounded"><i class=" icon-remove"></i></a>-->                                        <?php                                        $v = 1;                                        // }                                        ?>                                    </td>                                </tr>                                <?php                            }                        } else {                            echo "<tr><td colspan='5'>No Records Found</td></tr>";                        }                        ?>                    </tbody>                </table>                <?php                if (isset($departments) && !empty($departments)) {                    $end = $start + count($departments);                    $start = $start + 1;                    ?>                    Showing <?= $start ?> to <?= $end ?> of <?= $no_of_depts[0]['count'] ?> records                <?php } ?>            </div>        </div>    </div></div>