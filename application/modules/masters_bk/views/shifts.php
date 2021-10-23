

<div class="contentinner">


    <div class="media mt--20">
        <h4 class="widgettitle">Shift Details </h4><a href="<?= $this->config->item('base_url') . "masters/biometric/add_shift/" ?>" class="btn btn-success right topgen  "style="margin-top: -34px;"><span class="glyphicon glyphicon-plus"></span>Add </a>
    </div>

    
</div>

<div class="panel-body mt-top5">
    <div class="widgetcontent scroll_bar">

        <table class="table table-bordered sortable table-striped table-bordered responsive dataTable no-footer dtr-inline
               tquantity-cntr tamount-right" >

            <thead>

                <?php
                //print_r($shifts);
//				$user_role = json_decode($roles[0]["roles"]);

                $head = array("S.No", "Name", "From", "To");

                $db_name = array("id", "name", "from_time", "to_time");

                $id_sort = 0;
                ?>

                <tr>



                    <?php
//					$filter = $this->session_view->get_session(null,null);

                    $i = 0;

                    foreach ($head as $ele) {

                        $elem_class = $elem_id = "";

                        if ($db_name[$i] == $filter["sort"]) {

                            $elem_class = "class='sort' ";

                            $elem_id = "id='" . $filter["order"] . "' ";

                            if ($filter["sort"] == "id" && $filter["order"] == "desc")
                                $id_sort = 1;
                        }

                        echo "<th " . $elem_class . $elem_id . " data='" . base64_encode($db_name[$i++]) . "'>" . $ele . "</th>";
                    }
                    ?>

                    <th class="action">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php
                if ($id_sort == 1)
                    $s = $no_of_shifts[0]['count'] - $start;
                else
                    $s = $start + 1;

                $new_shift = array();

                if (isset($shifts) && !empty($shifts)) {



                    foreach ($shifts as $shift) {

                        $new_shift[$shift["id"]] = $shift;
                    }
                }

                if (isset($new_shift) && !empty($new_shift)) {



                    foreach ($new_shift as $shift) {
                        ?>

                        <tr>

                            <td class="center"><?php echo $id_sort == 1 ? $s-- : $s++; ?></td>

                            <td class="center"><?= ucwords($shift['name']) ?></td>

                            <td class="center"><?= $shift["from_time"] ?></td>

                            <td class="center"><?= $shift["to_time"] ?></td>

                            <td class="center action">
                                <a href="<?= $this->config->item('base_url') . "masters/biometric/view_shift/" . $shift["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs btn-rounded" title="" data-original-title="View"> <span class="fa fa-eye"></span></a>

                                <?php
                                $v = 1;
                                ?>
                                <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_shift/" . $shift["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs " title="" data-original-title="Edit"><span class="fa fa-edit "></span></a>

                                <?php
                                $v = 1;
                                ?>
                                <a href="<?= $this->config->item('base_url') . "masters/biometric/delete_shift/" . $shift["id"] ?>" data-toggle="tooltip" class="tooltips btn btn-default btn-xs" title="" data-original-title="Delete"><span class="fa fa-log-out "> <span class="fa fa-ban"></span></span></a>

                                <?php
                                $v = 1;
                                ?>

                            </td>

                        </tr>

                        <?php
                    }
                } else {



                    echo "<tr><td colspan='5'>No Records Found</td></tr>";
                }
                ?>

            </tbody>

        </table>

        <?php
//echo $no_of_shifts[0]['count'];

        if (isset($shifts) && !empty($shifts)) {



            $end = $start + count($new_shift);

            $start = $start + 1;
            ?>

            Showing <?= $start ?> to <?= $end ?> of <?= $no_of_shifts[0]['count'] ?> records



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

