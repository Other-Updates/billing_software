<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<!--<script type="text/javascript" src="<?= $theme_path ?>/js/employee.js"></script>-->
<script type="text/javascript" src="<?= $theme_path; ?>/js/department.js"></script>

<div class="contentinner">
    <div class="media mt--20">
        <h4 class="widgettitle">View Shift Details</h4>
    </div>
    <div class="widgetcontent">

        <?php
//        $user_role = json_decode($roles[0]["roles"]);

        $result = validation_errors();

        if (trim($result) != ""):
            ?>

            <div class="alert alert-error">

                <button data-dismiss="alert" class="close" type="button">&times;</button>

                <?php echo implode("</p>", array_unique(explode("</p>", validation_errors()))); ?>



            </div>

        <?php endif; ?>

        <?php
        $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');

        echo form_open('', $attributes);
        ?>

        <div class="salary_grp"><span>Shift Name&nbsp;:&nbsp;</span><?php echo $shift[0]['name'] ?></div>
				<div class="panel-body mt-top5">
        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>S.No</th>



                    <?php
//print_r($users);

                    $head = array("Session name", "From", "To");



                    foreach ($head as $ele) {

                        echo "<th>" . $ele . "</th>";
                    }
                    ?>



                </tr>

            </thead>

            <tbody>

                <?php
                $s_length = count($shift);



                for ($len = 0; $len < $s_length; $len++) {
                    ?>

                    <tr>

                        <td class="center"><?= $len + 1 ?></td>

                        <td class="center"><?php echo ucwords($shift[$len]['type']); ?></td>

                        <td class="center"><?php echo $shift[$len]['from_time']; ?></td>

                        <td class="center"><?php echo $shift[$len]['to_time']; ?></td>

                    </tr>

                <?php }
                ?>

            </tbody>

        </table>

    

    <div class="button_right_align action-btn-align">

        <a href="<?= $this->config->item('base_url') . "masters/biometric/shifts/" ?>" title="Back"><input type="button" class="btn btn-defaultback border4" value="Back" /></a>

        <?php if (in_array("masters:edit_shift", $user_role)) { ?>

            <a href="<?= $this->config->item('base_url') . "masters/biometric/edit_shift/" . $shift[0]['id'] ?>" title="Edit"><input type="button" class="btn btn-info border4" value="Edit" /></a>

        <?php } ?>

    </div>
			</div>
    </div>
</div>