<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?><script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script><script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script><script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script><link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" /><!--<script type="text/javascript" src="<?= $theme_path ?>/js/employee.js"></script>--><script type="text/javascript" src="<?= $theme_path; ?>/js/department.js"></script><style>    .wid {width:100%;}    .req { color:#FF0000; }</style><div class="contentinner">    <div class="media mt--20">        <h4 class="widgettitle">Shift Add </h4>    </div>    <div class="widgetcontent">        <?php        $result = validation_errors();        if (trim($result) != ""):            ?>            <div class="alert alert-error">                <button data-dismiss="alert" class="close" type="button">&times;</button>                <?php echo validation_errors(); ?>            </div>        <?php endif; ?>        <?php        $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');        echo form_open('', $attributes);        ?>        <p><?php echo form_label('Shift Name <span class="req">*</span>'); ?>            <span class="field">                <?php                $data = array(                    'name' => 'shift[name]',                    'value' => isset($_POST['save']) ? set_value('shift[name]') : '',                    'class' => 'required alphabet ',                );                echo form_input($data);                ?>            </span>        </p>        <div class="panel-body mt-top5">            <div class="scroll_bar">                <table class="table table-bordered shift_table">                    <thead>                        <tr>                            <th>S.No</th>                            <?php//print_r($users);                            $head = array("Type", "From <span class='req'>*</span>", "To <span class='req'>*</span>");                            foreach ($head as $ele) {                                echo "<th>" . $ele . "</th>";                            }                            ?>                            <th><a href="javascript:void(0)" class="btn btn-danger add_row">+</th>                        </tr>                    </thead>                    <tbody>                        <?php                        if (!isset($s_length)):                            $s_length = 1;                        endif;                        for ($len = 0; $len < $s_length; $len++) {                            $class = "";                            if ($len == 0)                                $class = "to_clone";                            ?>                            <tr class="<?= $class ?>">                                <td class="center sno"><?= $len + 1 ?></td>                                <td class="center"><?php                                    //print_r($shift);                                    $options = array(                                        '' => 'Select',                                        'forenoon' => 'Forenoon',                                        'break' => 'Break',                                        'lunch' => 'Lunch',                                        'afternoon' => 'Afternoon',                                        'regular' => 'Regular',                                        'overtimestart' => 'Overtimestart'                                    );                                    if ($len == 0) {                                        $options["regular"] = "Regular";                                        $default = 'regular';                                        $prop = 'class="required select_shift_no_chng"';                                    } else {                                        if (isset($shift)) {                                            $default = $shift["type"][$len];                                            //echo $default;                                        }                                        $prop = 'class="required select_shift"';                                    }                                    echo form_dropdown('shift[type][]', $options, $default, $prop);                                    ?> </td>                                <td class="center">                                    <?php                                    $data = array(                                        'name' => 'shift[from_time][]',                                        'value' => set_value('shift[from_time][]'),                                        'class' => 'required from_time timepicker',                                        'readonly' => 'readonly'                                    );                                    echo form_input($data);                                    ?>                                </td>                                <td class="center">                                    <?php                                    $data = array(                                        'name' => 'shift[to_time][]',                                        'value' => set_value('shift[to_time][]'),                                        'class' => 'required to_time timepicker',                                        'readonly' => 'readonly'                                    );                                    echo form_input($data);                                    ?>                                </td>                                <?php                                if ($len == 0)                                    $style = "visibility:hidden;";                                else                                    $style = "visibility:visible;";                                //echo $len;                                ?>                                <td class="center"><a href="javascript:void(0);" class="btn btn-danger remove_row" style="<?php echo $style; ?>">-</td>                            </tr>                        <?php }                        ?>                    </tbody>                </table>            </div>            <div class="action-btn-align">                <?php                //echo form_input(array('name' => 'sub', 'type'=>'hidden', 'id' =>'sub', 'value' =>1));                $data = array(                    'name' => 'save',                    'value' => 'Save',                    'class' => 'btn btn-success border4 submit',                    'title' => 'Save'                );                echo form_submit($data);                ?>                <a href="<?= $this->config->item('base_url') . "masters/biometric/shifts/" ?>" title="Cancel"><input type="button" class="btn btn-danger border4" value="Cancel" /></a>                <?php                $val = (isset($_POST['save'])) ? $_POST['save'] : 0;                ?>            </div>        </div>    </div></div><script type="text/javascript">    $(document).ready(function () {<?php if (isset($attendance) && !empty($attendance)) { ?>            alert("Current Month attendance already added for this User. Go to attendance edit for modify the attendance details")<?php } ?>        var s = "<?= $val ?>";        if (s != 0)        {            $(".timepicker").each(function () {                // $(this).addClass("hasTimepicker");                // $(this).attr("id","tp1404105784900");                $(this).timepicker({                    minutes: {                        starts: 0, // First displayed minute                        ends: 59, // Last displayed minute                        interval: 1, // Interval of displayed minutes                        manual: []    // Optional extra entries for minutes                    }                });            });        }    });</script><script type="text/javascript">    function removejscssfile(filename, filetype) {        var targetelement = (filetype == "js") ? "script" : (filetype == "css") ? "link" : "none" //determine element type to create nodelist from        var targetattr = (filetype == "js") ? "src" : (filetype == "css") ? "href" : "none" //determine corresponding attribute to test for        var allsuspects = document.getElementsByTagName(targetelement)        for (var i = allsuspects.length; i >= 0; i--) { //search backwards within nodelist for matching elements to remove            if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf(filename) != -1)                allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()        }    }    removejscssfile("<?= $theme_path; ?>/css/jquery-ui-timepicker-addon.min.css", "css")    removejscssfile("<?= $theme_path; ?>/js/jquery-ui-timepicker-addon.min.js", "js")    $(".remove_row").live('click', function () {        $(this).closest("tr").remove();        tr = $("tbody").children("tr");        $(tr).each(function (i) {            $(this).find("td.sno").text(i + 1);        });    });</script><link rel="stylesheet" href="<?= $theme_path ?>/css/jquery-ui-theme.css" type="text/css" /><link rel="stylesheet" href="<?= $theme_path ?>/css/jquery.ui.timepicker.css" type="text/css" /><script type="text/ecmascript" src="<?= $theme_path ?>/js/jquery.ui.timepicker.js"></script>