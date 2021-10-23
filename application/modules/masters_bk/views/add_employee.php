<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<link rel="stylesheet" href="<?= $theme_path ?>/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="<?= $theme_path ?>/js/jquery.MultiFile.js"></script>
<script type="text/javascript" src="<?= $theme_path ?>/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?= $theme_path ?>/js/employee.js"></script>

<style>
    ul.employee-ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;

    }
    .employee-ul li {
        float: left;
        border-right:1px solid #bbb;
    }
    .employee-ul li a {
        display: block;
        /*color: white;*/
        text-align: center;
        padding: 11px 16px;
        text-decoration: none;
    }
    .employee-ul .button_right_align{
        margin-top: 10px;
    }
    .profile{
        margin-top: -31px;
    }
    img {
        vertical-align: middle;
        margin-r: -131px;
        margin-left: -127px;
    }
    .profile_pic {
        margin-bottom: -59px;
        margin-top: 10px;
        width: 196px;
        margin-left: -9px;
    }

</style>

<?php
//$user_image  = $this->session_view->get_session('masters','add_employee');
//print_r($user_image);

$roles = json_decode($roles[0]["roles"]);

//print_r($roles);
?>

<div class="mainpanel">
    <div class="media mt--20">
        <h4>ADD EMPLOYEE</h4>
    </div>
    <div class="contentpanel">
        <div class="panel-body mt-top5">
            <div class="row">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <ul class="employee-ul">

                            <?php
                            if (isset($error)) {
                                ?>

                                <script type="text/javascript">

                                    tab_index = "<?php echo $error ?>";

                                </script>

                                <?php
                            }
                            ?>
                            <?php
                            $attributes = array('class' => 'stdform editprofileform', 'method' => 'post');
                            echo form_open_multipart('', $attributes);
                            ?>

                            <li><a class="nav-item nav-link active" id="tabs-1-tab" data-toggle="tab" href="#tabs-1" role="tab" aria-controls="nav-home" aria-selected="true">General Details</a></li>
                            <li><a class="nav-item nav-link" id="tabs-2-tab" data-toggle="tab" href="#tabs-2" role="tab" aria-controls="nav-profile" aria-selected="false">Contacts</a></li>
                            <li><a class="nav-item nav-link" id="tabs-3-tab" data-toggle="tab" href="#tabs-3" role="tab" aria-controls="nav-contact" aria-selected="false">Company Details</a></li>
                            <li><a class="nav-item nav-link" id="tabs-4-tab" data-toggle="tab" href="#tabs-4" role="tab" aria-controls="nav-home" aria-selected="true">Educations</a></li>
                            <li><a class="nav-item nav-link" id="tabs-5-tab" data-toggle="tab" href="#tabs-5" role="tab" aria-controls="nav-profile" aria-selected="false">Family Members</a></li>
                            <li><a class="nav-item nav-link" id="tabs-6-tab" data-toggle="tab" href="#tabs-6" role="tab" aria-controls="nav-contact" aria-selected="false">Languages</a></li>
                            <li><a class="nav-item nav-link" id="tabs-7-tab" data-toggle="tab" href="#tabs-7" role="tab" aria-controls="nav-home" aria-selected="true">Identification</a></li>
                            <li><a class="nav-item nav-link" id="tabs-8-tab" data-toggle="tab" href="#tabs-8" role="tab" aria-controls="nav-profile" aria-selected="false">Reference Details</a></li>
                            <li><a class="nav-item nav-link" id="tabs-9-tab" data-toggle="tab" href="#tabs-9" role="tab" aria-controls="nav-contact" aria-selected="false">Experience</a></li>

                        </ul>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane active" id="tabs-1" role="tabpanel" aria-labelledby="tabs-1-tab">
                        <h5 style="color:#23b7e5;">LOGIN INFORMATION</h5>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Emp Id</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[employee_id]',
                                            'class' => 'input-large required',
                                            'value' => $last_increment_id[0]["last_increment_id"],
                                            'readonly' => 'readonly'
                                        );
                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Access Id <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[access_id]',
                                            'class' => 'input-large required',
                                            'value' => set_value('users[access_id]')
                                        );



                                        echo form_input($data);
                                        ?>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Username <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[username]',
                                            'class' => 'input-large required',
                                            'value' => set_value('users[username]')
                                        );



                                        echo form_input($data);
                                        ?>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Password <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[password]',
                                            'class' => 'input-large required',
                                            'value' => set_value('users[password]'),
                                            'autocomplete' => 'off'
                                        );



                                        echo form_password($data);
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <h5 style="color:#23b7e5;">PERSONAL INFORMATION</h5>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Firstname <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[first_name]',
                                            'class' => 'input-large required alphabet',
                                            'value' => set_value('users[first_name]')
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Lastname <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[last_name]',
                                            'class' => 'input-large alphabet',
                                            'value' => set_value('users[last_name]')
                                        );



                                        echo form_input($data);
                                        ?>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">DOB <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $dob_val = "";

                                        if (isset($_POST['save'])) {



                                            $dob_val = $input["users"]["dob"];
                                        }

                                        $data = array(
                                            'id' => 'datepicker',
                                            'name' => 'users[dob]',
                                            'class' => 'input-large required datepicker date_of_birth input-date',
                                            'readonly' => 'readonly',
                                            'value' => $dob_val
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Religion <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => '',
                                            'name' => 'users[religion]',
                                            'class' => 'input-large required alphabet',
                                            'value' => set_value('users[religion]')
                                        );
                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Mobile Number <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[mobile]',
                                            'class' => 'input-large required numeric mobile',
                                            'value' => set_value('users[mobile]')
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Landline Number</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[landline_no]',
                                            'class' => 'input-large required numeric landline',
                                            'value' => $landline_no
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Email Id <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'users[email]',
                                            'class' => 'input-large required email',
                                            'value' => set_value('users[email]')
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Blood Group <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $options = array('' => 'select');

                                        if (isset($blood_group) && !empty($blood_group)) {

                                            foreach ($blood_group as $val) {

                                                $options[$val["value"]] = ucwords($val["value"]);
                                            }
                                        }

                                        //$data = array('name'=> 'shirts','class'=>'required');

                                        echo form_dropdown('users[blood_group]', $options, set_value('users[blood_group]'), 'class="required"');
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Gender <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="">
                                        <?php
                                        $male = FALSE;
                                        $female = FALSE;

                                        if (isset($_POST['save'])) {



                                            if (isset($input["users"]["gender"])) {

                                                if ($input["users"]["gender"] == 1) {

                                                    $male = TRUE;
                                                } else if ($input["users"]["gender"] == 2) {

                                                    $female = TRUE;
                                                }
                                            }
                                        }

                                        $data = array(
                                            'name' => 'users[gender]',
                                            'type' => 'radio',
                                            'value' => '1',
                                            'class' => 'required-radio',
                                            'checked' => $male
                                        );



                                        echo form_checkbox($data);
                                        ?> Male &nbsp; &nbsp;



                                        <?php
                                        $data = array(
                                            'name' => 'users[gender]',
                                            'type' => 'radio',
                                            'value' => '2',
                                            'class' => 'required-radio',
                                            'checked' => $female
                                        );

                                        echo form_checkbox($data);
                                        ?>Female &nbsp; &nbsp;
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Marital Status <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="">
                                        <?php
                                        $single = FALSE;
                                        $married = FALSE;

                                        $widow = FALSE;

                                        $widower = FALSE;

                                        if (isset($_POST['save'])) {

                                            if (isset($input["users"]["marital_status"])) {

                                                if ($input["users"]["marital_status"] == 1) {

                                                    $single = TRUE;
                                                } else if ($input["users"]["marital_status"] == 2) {

                                                    $married = TRUE;
                                                } else if ($input["users"]["marital_status"] == 3) {

                                                    $widow = TRUE;
                                                } else if ($input["users"]["marital_status"] == 4) {

                                                    $widower = TRUE;
                                                }
                                            }
                                        }

                                        $data = array(
                                            'name' => 'users[marital_status]',
                                            'type' => 'radio',
                                            'value' => '1',
                                            'class' => 'required-radio',
                                            'checked' => $single
                                        );



                                        echo form_checkbox($data);
                                        ?>Single &nbsp; &nbsp;

                                        <?php
                                        $data = array(
                                            'name' => 'users[marital_status]',
                                            'type' => 'radio',
                                            'value' => '2',
                                            'class' => 'required-radio',
                                            'checked' => $married
                                        );



                                        echo form_checkbox($data);
                                        ?>Married &nbsp; &nbsp;

                                        <?php
                                        $data = array(
                                            'name' => 'users[marital_status]',
                                            'type' => 'radio',
                                            'value' => '3',
                                            'class' => 'required-radio',
                                            'checked' => $widow
                                        );



                                        echo form_checkbox($data);
                                        ?>Widow &nbsp; &nbsp;

                                        <?php
                                        $data = array(
                                            'name' => 'users[marital_status]',
                                            'type' => 'radio',
                                            'value' => '4',
                                            'class' => 'required-radio',
                                            'checked' => $widower
                                        );



                                        echo form_checkbox($data);
                                        ?>Widower &nbsp; &nbsp;

                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">Employee Status <span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="">
                                        <?php
                                        $active = FALSE;
                                        $inactive = FALSE;

                                        if (isset($_POST['save'])) {

                                            if (isset($input["users"]["status"])) {

                                                if ($input["users"]["status"] == 1) {

                                                    $active = TRUE;
                                                } else if ($input["users"]["status"] == 0) {

                                                    $inactive = TRUE;
                                                }
                                            }
                                        }

                                        $data = array(
                                            'name' => 'users[status]',
                                            'type' => 'radio',
                                            'value' => '1',
                                            'class' => 'required-radio',
                                            'checked' => $active
                                        );
                                        echo form_checkbox($data);
                                        ?>Active &nbsp; &nbsp;

                                        <?php
                                        $data = array(
                                            'name' => 'users[status]',
                                            'type' => 'radio',
                                            'value' => '0',
                                            'class' => 'required-radio',
                                            'checked' => $inactive
                                        );

                                        echo form_checkbox($data);
                                        ?>Deactive &nbsp; &nbsp;

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 profile">

                            <h5 style="color:#23b7e5;">YOUR PROFILE PHOTO</h5>
                            <div class="profilethumb">

                                <a href="#">Change Thumbnail</a>



                                <?php
                                //echo ".yhg" . $user[0]['image'];
                                if ($user[0]['image'] != NULL) {
                                    $src = $this->config->item('base_url') . "attachments/user_profile/" . $user[0]['image'];
                                } else {

                                    $src = $theme_path . "/img/profilethumb.png";
                                }
                                ?>

                                <img src="<?= $src; ?>" alt="" class="img-polaroid" />

                                <?php
                                $data = array(
                                    'name' => 'users[image]',
                                    'type' => 'file',
                                    'class' => 'profile_pic'
                                );



                                echo form_input($data);
                                ?>

                                <input type="hidden" id="input_file" name="temp[file]" />

                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="tabs-2" role="tabpanel" aria-labelledby="tabs-2-tab">
                        <div class="col-md-6"><h5 style="color:#23b7e5; margin-bottom:5px;">PRESENT ADDRESS</h5></div>

                        <div class="col-md-6"><h5 style="color:#23b7e5; margin-bottom:5px;" >PERMANENT ADDRESS</h5></div>
                        <div class="col-md-6"></div>

                        <div class="col-md-6">
                            <?php
                            $checked_pr = FALSE;



                            if (isset($_POST['save'])) {

                                if (isset($input["address_checked"])) {



                                    $checked_pr = TRUE;
                                }
                            }

                            $data = array(
                                'id' => 'address_copy',
                                'name' => 'address_checked',
                                'value' => 1,
                                'class' => "address",
                                'checked' => $checked_pr
                            );

                            echo form_checkbox($data);
                            ?>&nbsp;same as present address

                            <?php
                            $line1 = $line2 = $line3 = $po = $dsct = $city = $state = $zip = "";

                            if (isset($_POST['save'])) {

                                $line1 = $input["address"][1]["line1"];

                                $line2 = $input["address"][1]["line2"];

                                $line3 = $input["address"][1]["line3"];

                                $po = $input["address"][1]["post_office"];

                                $dsct = $input["address"][1]["district"];

                                $city = $input["address"][0]["city"];

                                $state = $input["address"][1]["state"];

                                $zip = $input["address"][1]["zip"];
                            }
                            ?>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 1<br /></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $line1 = $line2 = $line3 = $po = $dsct = $city = $state = $zip = "";

                                        if (isset($_POST['save'])) {

                                            $line1 = $input["address"][0]["line1"];

                                            $line2 = $input["address"][0]["line2"];

                                            $line3 = $input["address"][0]["line3"];

                                            $po = $input["address"][0]["post_office"];

                                            $dsct = $input["address"][0]["district"];

                                            $city = $input["address"][0]["city"];

                                            $state = $input["address"][0]["state"];

                                            $zip = $input["address"][0]["zip"];
                                        }

                                        $data = array(
                                            'id' => 'line1',
                                            'name' => 'address[0][line1]',
                                            'class' => 'input-large required present line contact',
                                            'value' => $line1,
                                            'field_name' => 'line1'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 1<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'line21',
                                            'name' => 'address[1][line1]',
                                            'class' => 'input-large required permanent line contact',
                                            'value' => $line1,
                                            'field_name' => 'line1'
                                        );

                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 2<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'line2',
                                            'name' => 'address[0][line2]',
                                            'class' => 'input-large required present line contact',
                                            'value' => $line2,
                                            'field_name' => 'line2'
                                        );
                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 2<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'line22',
                                            'name' => 'address[1][line2]',
                                            'class' => 'input-large required permanent line contact',
                                            'value' => $line2,
                                            'field_name' => 'line2'
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 3<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'line3',
                                            'name' => 'address[0][line3]',
                                            'class' => 'input-large required present lines contact',
                                            'value' => $line3,
                                            'field_name' => 'line3'
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Address line 3<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'line23',
                                            'name' => 'address[1][line3]',
                                            'class' => 'input-large required permanent lines contact',
                                            'value' => $line3,
                                            'field_name' => 'line3'
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Post Office<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'po1',
                                            'name' => 'address[0][post_office]',
                                            'class' => 'input-large required present',
                                            'value' => $po,
                                            'field_name' => 'line4'
                                        );



                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Post Office<br/></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'po2',
                                            'name' => 'address[1][post_office]',
                                            'class' => 'input-large required permanent',
                                            'value' => $po,
                                            'field_name' => 'line4'
                                        );



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
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'dst1',
                                            'name' => 'address[0][district]',
                                            'class' => 'input-large required present',
                                            'value' => $dsct,
                                            'field_name' => 'line5'
                                        );



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
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'dst2',
                                            'name' => 'address[1][district]',
                                            'class' => 'input-large required permanent',
                                            'value' => $dsct,
                                            'field_name' => 'line5'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">City</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'city1',
                                            'name' => 'address[0][city]',
                                            'class' => 'input-large required present city contact',
                                            'value' => $city,
                                            'field_name' => 'line6'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">City</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'city2',
                                            'name' => 'address[1][city]',
                                            'class' => 'input-large required present city contact',
                                            'value' => $city,
                                            'field_name' => 'line6'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">State</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'state1',
                                            'name' => 'address[0][state]',
                                            'class' => 'input-large required present',
                                            'value' => $state,
                                            'field_name' => 'line7'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">State</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'state2',
                                            'name' => 'address[1][state]',
                                            'class' => 'input-large required permanent',
                                            'value' => $state,
                                            'field_name' => 'line7'
                                        );



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
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'zip1',
                                            'name' => 'address[0][zip]',
                                            'class' => 'input-large required numeric present',
                                            'value' => $zip,
                                            'field_name' => 'line8'
                                        );



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
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'id' => 'zip2',
                                            'name' => 'address[1][zip]',
                                            'class' => 'input-large required numeric permanent',
                                            'value' => $zip,
                                            'field_name' => 'line8'
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 style="color:#23b7e5; margin-bottom:5px;" >EMERGENCY CONTACT DETAILS</h5>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Name<span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'contact[name]',
                                            'class' => 'input-large required alphabet contact_name contact',
                                            'autocomplete' => 'off',
                                            'value' => $contact_name
                                        );
                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contact Number<span class="req">*</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <?php
                                        $data = array(
                                            'name' => 'contact[contact_no]',
                                            'class' => 'input-large required numeric mobile contact_number contact',
                                            'autocomplete' => 'off',
                                            'value' => $contact_no,
                                        );



                                        echo form_input($data);
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>






                    </div>
                    <div class="tab-pane" id="tabs-3" role="tabpanel" aria-labelledby="tabs-3-tab">
                        <table class="table table-bordered">
                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Designation <span class="req">*</span></th>

                                    <th>Salary Type <span class="req">*</span></th>

                                    <th>Department <span class="req">*</span></th>

                                    <th>Shift <span class="req">*</span></th>

                                    <th>OT applicable <span class="req">*</span></th>

                                    <th>Salary Group <span class="req">*</span></th>

                                    <?php if (in_array("reports:wage_reports", $roles)): ?>

                                        <th>Basic <span class="req">*</span></th>

                                        <th>DA <span class="req">*</span></th>

                                    <?php endif; ?>

                                    <th>Date of Joining <span class="req">*</span></th>

                                </tr>

                            </thead>
                            <tbody>

                                <tr>

                                    <td class="center">1</td>

                                    <td class="center">

                                        <?php
                                        $options = array('' => 'select');

                                        if (isset($designations) && !empty($designations)) {

                                            foreach ($designations as $des) {

                                                $options[$des["id"]] = ucwords($des["name"]);
                                            }
                                        }

                                        //$data = array('class'=>'uniformselect');

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_dep"]["designation"])) {

                                                $default = $input["user_dep"]["designation"];
                                            }
                                        }

                                        echo form_dropdown('user_dep[designation]', $options, $default, 'class="required"');
                                        ?>



                                    </td>

                                    <td class="center">

                                        <?php
                                        $options = array(
                                            '' => 'select',
                                            '1' => 'Daily',
                                            '2' => 'Weekly',
                                            '3' => 'Monthly',
                                                //  '4'   => 'Pieces',
                                        );

                                        //$data = array('class'=>'uniformselect');

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_salary"]["type"])) {

                                                $default = $input["user_salary"]["type"];
                                            }
                                        }

                                        echo form_dropdown('user_salary[type]', $options, $default, 'class="required"');
                                        ?>



                                    </td>

                                    <td class="center">

                                        <?php
                                        $options = array('' => 'select');

//print_r($departments);

                                        if (isset($departments) && !empty($departments)) {

                                            foreach ($departments as $dept) {

                                                $options[$dept["dept_id"]] = ucwords($dept["dept_name"]);
                                            }
                                        }

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_dep"]["department"])) {

                                                $default = $input["user_dep"]["department"];
                                            }
                                        }

//$data = array('class'=>'uniformselect');

                                        echo form_dropdown('user_dep[department]', $options, $default, 'class="required"');
                                        ?>



                                    </td>

                                    <td class="center">

                                        <?php
                                        $options = array('' => 'select');

                                        if (isset($shift) && !empty($shift)) {

                                            foreach ($shift as $shf) {

                                                $options[$shf["id"]] = ucwords($shf["name"]);
                                            }
                                        }

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_shift"]["shift_id"])) {

                                                $default = $input["user_shift"]["shift_id"];
                                            }
                                        }

                                        //$data = array('class'=>'uniformselect');

                                        echo form_dropdown('user_shift[shift_id]', $options, $default, 'class="required"');
                                        ?>



                                    </td>

                            <!--<td class="center">

                                    <?php
                                    $ot_yes = FALSE;

                                    $ot_no = FALSE;

                                    if (isset($_POST['save'])) {



                                        if (isset($input["user_shift"]["ot_applicable"])) {

                                            if ($input["user_shift"]["ot_applicable"] == 1) {

                                                $ot_yes = TRUE;
                                            } else if ($input["user_shift"]["ot_applicable"] == 0) {

                                                $ot_no = TRUE;
                                            }
                                        }
                                    }

                                    $data = array(
                                        'name' => 'user_shift[ot_applicable]',
                                        'type' => 'radio',
                                        'value' => '1',
                                        'class' => 'required-radio',
                                        'checked' => $ot_yes
                                    );



                                    echo form_checkbox($data);
                                    ?> Yes &nbsp; &nbsp;



                                    <?php
                                    $data = array(
                                        'name' => 'user_shift[ot_applicable]',
                                        'type' => 'radio',
                                        'value' => '0',
                                        'class' => 'required-radio',
                                        'checked' => $ot_no
                                    );

                                    echo form_checkbox($data);
                                    ?>No &nbsp; &nbsp;

                            </td>-->



                                    <td class="center">

                                        <?php
                                        $options = array('' => "Select", 1 => "Yes", 0 => "No");

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_shift"]["ot_applicable"])) {

                                                $default = $input["user_shift"]["ot_applicable"];
                                            }
                                        }

                                        echo form_dropdown('user_shift[ot_applicable]', $options, $default, 'class="required"');
                                        ?>

                                    </td>





                                    <td class="center">

                                        <?php
                                        $options = array('' => 'select');

                                        if (isset($salary_group) && !empty($salary_group)) {

                                            foreach ($salary_group as $grp) {

                                                $options[$grp["id"]] = ucwords($grp["name"]);
                                            }
                                        }

                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_salary"]["salary_group"])) {

                                                $default = $input["user_salary"]["salary_group"];
                                            }
                                        }

                                        //$data = array('class'=>'uniformselect');

                                        echo form_dropdown('user_salary[salary_group]', $options, $default, 'class="required"');
                                        ?>



                                    </td>

                                    <?php
                                    $n = 0;

                                    if (in_array("reports:wage_reports", $roles)) {

                                        $n = 1;
                                    }

                                    if (in_array("reports:wage_reports", $roles)):
                                        ?>

                                        <td class="center">

                                            <?php
                                            $default = "";

                                            if (isset($_POST['save'])) {

                                                if (isset($input["user_salary"]["basic"])) {

                                                    $default = $input["user_salary"]["basic"];
                                                }
                                            }

                                            $data = array(
                                                'name' => 'user_salary[basic]',
                                                'class' => 'input-small wage-report float',
                                                'value' => $default
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $default = "";

                                            if (isset($_POST['save'])) {

                                                if (isset($input["user_salary"]["da"])) {

                                                    $default = $input["user_salary"]["da"];
                                                }
                                            }

                                            $data = array(
                                                'name' => 'user_salary[da]',
                                                'class' => 'input-small wage-report float',
                                                'value' => $default
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                    <?php endif; ?>

                                    <td class="center">

                                        <?php
                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["user_history"]["date"])) {

                                                $default = $input["user_history"]["date"];
                                            }
                                        }



                                        $data = array(
                                            'name' => 'user_history[date]',
                                            'class' => 'input-small required date-dob input-date',
                                            'readonly' => 'readonly',
                                            'value' => $default
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                </tr>

                            </tbody>
                        </table>
                        <h5 style="color:#23b7e5;"class="widgettitle">USER LEAVES</h5>
                        <table class="table table-bordered">
                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Sick Leave per Month <span class="req">*</span></th>

                                    <th>Casual Leave per Month <span class="req">*</span></th>



                                </tr>

                            </thead>
                            <tbody>

                                <tr>

                                    <td class="center">1</td>

                                    <td class="center">

                                        <?php
                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["leave"]["sick_leave"])) {

                                                $default = $input["leave"]["sick_leave"];
                                            }
                                        }

                                        $data = array(
                                            'name' => 'leave[sick_leave]',
                                            'class' => 'input-small required float l_val',
                                            'value' => $default
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                    <td class="center">

                                        <?php
                                        $default = "";

                                        if (isset($_POST['save'])) {

                                            if (isset($input["leave"]["casual_leave"])) {

                                                $default = $input["leave"]["casual_leave"];
                                            }
                                        }

                                        $data = array(
                                            'name' => 'leave[casual_leave]',
                                            'class' => 'input-small required float l_val',
                                            'value' => $default
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>



                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-4" role="tabpanel" aria-labelledby="tabs-4-tab">
                        <table class="table table-bordered education">

                            <colgroup>

                                <col class="con0" />

                                <col class="con1" />

                                <col class="con0" />

                                <col class="con1" />

                                <col class="con0" />

                            </colgroup>

                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Specialization</th>

                                    <th>Institute</th>

                                    <th>Type</th>

                                    <th>Completed Year</th>

                                    <th>Percentage</th>

                                    <th><a href="javascript:void(0);" class="btn btn-danger add_row">+</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $input = $this->input->post();

                                //print_r($input);

                                if (!isset($edu_length) || $edu_length == 0):

                                    $edu_length = 1;

                                endif;

                                //echo $edu_length;



                                for ($len = 0; $len < $edu_length; $len++) {
                                    ?>

                                    <tr>

                                        <td class="center sno"><?php echo $len + 1; ?></td>

                                        <td class="center">



                                            <?php
                                            $data = array(
                                                'name' => 'edu[specialization][]',
                                                'class' => 'input-small alphabet',
                                                'value' => isset($_POST['save']) ? $input['edu']['specialization'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>

                                        </td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'edu[institute][]',
                                                'class' => 'input-small required alphabet',
                                                'value' => isset($_POST['save']) ? $input['edu']['institute'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>

                                        </td>



                                        <td class="center">

                                            <?php
                                            $options = array('' => 'select');

                                            if (isset($edu_type) && !empty($edu_type)) {

                                                foreach ($edu_type as $val) {

                                                    $options[$val["value"]] = ucwords($val["value"]);
                                                }
                                            }

                                            $default = isset($_POST['save']) ? $input['edu']['type'][$len] : "";

                                            echo form_dropdown('edu[type][]', $options, $default, 'class="required"');
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $options = array('' => 'select');

                                            for ($i = 1945; $i <= date("Y"); $i++) {



                                                $options[$i] = $i;
                                            }

                                            $default = isset($_POST['save']) ? $input['edu']['completed_year'][$len] : "";



                                            echo form_dropdown('edu[completed_year][]', $options, $default, 'class="required"');
                                            ?></td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'edu[percentage][]',
                                                'class' => 'input-small required percentage float',
                                                'value' => isset($_POST['save']) ? $input['edu']['percentage'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        </td>

                                        <?php
                                        if ($len == 0)
                                            $style = "visibility:hidden;";
                                        else
                                            $style = "visibility:visible;";
                                        ?>

                                        <td class="center"><a href="javascript:void(0);" class="btn btn-danger remove_row" style="<?php echo $style; ?>"><i class="fa fa-minus icon-black"></i></a></td>

                                    </tr>

                                    <?php
                                }
                                ?>

                            </tbody>

                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-5" role="tabpanel" aria-labelledby="tabs-5-tab">
                        <table class="table table-bordered">

                            <colgroup>

                                <col class="con0" />

                                <col class="con1" />

                                <col class="con0" />

                                <col class="con1" />

                                <col class="con0" />

                            </colgroup>

                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Name</th>

                                    <th>Relation</th>

                                    <th>Age</th>

                                    <th>Designation</th>

                                    <th>Monthly Income</th>

                                    <th class="hide">Proportion by which <br />gratuity will be shared</th>

                                    <th  class="hide">Nominee</th>

                                    <th>Salary Settlement</th>

                                    <th><a href="javascript:void(0);" class="btn btn-danger add_row">+</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                if (!isset($f_length) || $f_length == 0):

                                    $f_length = 1;

                                endif;

                                for ($len = 0; $len < $f_length; $len++) {
                                    ?>

                                    <tr>

                                        <td class="center sno"><?php echo $len + 1 ?></td>

                                        <td class="center"> <?php
                                            $family_name = $relation = $dob = $des = $mi = $per = "";

                                            if (isset($_POST['save'])) {

                                                if (isset($input['family']['name'][$len]))
                                                    $family_name = $input['family']['name'][$len];

                                                if (isset($input['family']['relation'][$len]))
                                                    $relation = $input['family']['relation'][$len];

                                                if (isset($input['family']['age'][$len]))
                                                    $dob = $input['family']['age'][$len];

                                                if (isset($input['family']['designation'][$len]))
                                                    $des = $input['family']['designation'][$len];

                                                if (isset($input['family']['monthly_income'][$len]))
                                                    $mi = $input['family']['monthly_income'][$len];

                                                if (isset($input['family']['percentage'][$len]))
                                                    $per = $input['family']['percentage'][$len];
                                            }

                                            $data = array(
                                                'name' => 'family[name][]',
                                                'class' => 'input-small required family_name alphabet',
                                                'value' => $family_name
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"><?php
                                            $options = array("" => "select");

                                            if (isset($relations) && !empty($relations)) {

                                                foreach ($relations as $rel) {

                                                    $options[$rel["value"]] = ucwords($rel["value"]);
                                                }
                                            }

                                            echo form_dropdown('family[relation][]', $options, $relation, 'class="required"');
                                            ?></td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'family[age][]',
                                                'class' => 'input-small required age numeric',
                                                'value' => $dob
                                            );

                                            echo form_input($data);
                                            ?>

                                        </td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'family[designation][]',
                                                'class' => 'input-small alphabet',
                                                'value' => $des
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"> <?php
                                            $data = array(
                                                'name' => 'family[monthly_income][]',
                                                'class' => 'input-small float',
                                                'value' => $mi
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center hide"> <?php
                                            $data = array(
                                                'name' => 'family[percentage][' . $len . ']',
                                                'class' => 'input-small numeric',
                                                'value' => $per
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center hide">

                                            <?php
                                            $checked = FALSE;

                                            if (isset($input["family"]["nominee"][$len])) {

                                                $checked = TRUE;
                                            }

                                            $data = array(
                                                'name' => 'family[nominee][' . $len . ']',
                                                'value' => 1,
                                                'class' => "nominee",
                                                'checked' => $checked
                                            );

                                            echo form_checkbox($data);
                                            ?>

                                        </td>

                                        <td class="center">

                                            <?php
                                            $selected = FALSE;

                                            if (isset($_POST['save'])) {

                                                if (isset($input["wages"]["family_member_id"]) && $input["wages"]["family_member_id"] == $len)
                                                    $selected = TRUE;
                                            }

                                            $data = array(
                                                'name' => 'wages[family_member_id]',
                                                'value' => $len,
                                                'class' => "radio-address",
                                                'checked' => $selected,
                                                'type' => 'radio'
                                            );

                                            echo form_checkbox($data);
                                            ?>

                                        </td>

                                        <?php
                                        if ($len == 0)
                                            $style = "visibility:hidden;";
                                        else
                                            $style = "visibility:visible;";
                                        ?>

                                        <td class="center"><a href="javascript:void(0);" class="btn btn-danger remove_row" style="<?php echo $style; ?>"><i class="fa fa-minus icon-black"></i></a></td>



                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-6" role="tabpanel" aria-labelledby="tabs-6-tab">
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Languages</th>

                                    <th>Speak</th>

                                    <th>Read</th>

                                    <th>Write</th>

                                    <th><a href="javascript:void(0);" class="btn btn-danger add_row">+</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                if (!isset($l_length) || $l_length == 0):

                                    $l_length = 1;

                                endif;

                                $k = 0;

                                for ($len = 0; $len < $l_length; $len++) {

                                    $res = "";

                                    if (isset($_POST['save']))
                                        $res = $input["lang"]["language"][$len];
                                    ?>

                                    <tr>



                                        <td class="center sno"><?= $len + 1 ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'lang[language][]',
                                                'class' => 'input-small required lang',
                                                'value' => $res
                                            );

                                            echo form_input($data);

                                            // echo set_value('lang[rws][]');
                                            //echo "<pre>";

                                            if (isset($_POST['lang']))
                                                $rws = $_POST['lang']['rws'];

                                            $read = $write = $speak = FALSE;

                                            //echo "speak:".$speak." read:" .$read." write:".$write." ";
                                            ?>

                                        </td>

                                        <td class="center"><span class=""><?php
                                                if (isset($rws)) {

                                                    if ($rws[$k] == 1)
                                                        $speak = TRUE;
                                                }

                                                $data = array(
                                                    'class' => 'input-small required-check',
                                                    'value' => 0,
                                                    'checked' => $speak
                                                );

                                                echo form_checkbox($data);

                                                if (isset($_POST['lang']))
                                                    echo form_hidden('lang[rws][]', $rws[$k++]);
                                                else
                                                    echo form_hidden('lang[rws][]', '');
                                                ?>



                                            </span></td>

                                        <td class="center"><span class=""><?php
                                                if (isset($rws)) {

                                                    if ($rws[$k] == 1)
                                                        $read = TRUE;
                                                }

                                                $data = array(
                                                    'class' => 'input-small required-check',
                                                    'value' => 0,
                                                    'checked' => $read
                                                );

                                                echo form_checkbox($data);

                                                if (isset($_POST['lang']))
                                                    echo form_hidden('lang[rws][]', $rws[$k++]);
                                                else
                                                    echo form_hidden('lang[rws][]', '');
                                                ?></span></td>

                                        <td class="center"><span class=""><?php
                                                if (isset($rws)) {

                                                    if ($rws[$k] == 1)
                                                        $write = TRUE;
                                                }

                                                $data = array(
                                                    'class' => 'input-small required-check',
                                                    'value' => 0,
                                                    'checked' => $write
                                                );

                                                echo form_checkbox($data);

                                                if (isset($_POST['lang']))
                                                    echo form_hidden('lang[rws][]', $rws[$k++]);
                                                else
                                                    echo form_hidden('lang[rws][]', '');
                                                ?></span> </td>

                                        <?php
                                        if ($len == 0)
                                            $style = "visibility:hidden;";
                                        else
                                            $style = "visibility:visible;";
                                        ?>

                                        <td class="center"><a href="javascript:void(0);" class="btn btn-danger remove_row" style="<?php echo $style; ?>"><i class="fa fa-minus icon-black"></i></a></td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
                    </div>
                    <div class="tab-pane" id="tabs-7" role="tabpanel" aria-labelledby="tabs-7-tab">
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th style="text-align:center;">

                                        Identification 1

                                    </th>

                                    <th style="text-align:center;">

                                        Identification 2

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td style="text-align:center;">

                                        <?php
                                        $data = array(
                                            'name' => 'identification_marks[identification_mark][]',
                                            'class' => 'input-large required alphabet',
                                            'value' => isset($_POST['save']) ? $input['identification_marks']['identification_mark'][0] : ""
                                        );

                                        echo form_input($data);
                                        ?>



                                    </td>

                                    <td style="text-align:center;">

                                        <?php
                                        $data = array(
                                            'name' => 'identification_marks[identification_mark][]',
                                            'class' => 'input-large required alphabet',
                                            'value' => isset($_POST['save']) ? $input['identification_marks']['identification_mark'][1] : ""
                                        );

                                        echo form_input($data);
                                        ?>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>
                    <div class="tab-pane" id="tabs-8" role="tabpanel" aria-labelledby="tabs-8-tab">
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Name</th>

                                    <th>Relation</th>

                                    <th>Company Name</th>

                                    <th>Address</th>

                                    <th>Phone No</th>



                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                if (!isset($ref_length) || $ref_length == 0):

                                    $ref_length = 2;

                                endif;

                                for ($len = 0; $len < 2; $len++) {
                                    ?>

                                    <tr>

                                        <td class="center sno"><?= $len + 1; ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'ref[name][]',
                                                'class' => 'input-small required alphabet',
                                                'value' => isset($_POST['save']) ? $input['ref']['name'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'ref[relation][]',
                                                'class' => 'input-small required alphabet',
                                                'value' => isset($_POST['save']) ? $input['ref']['relation'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'ref[company_name][]',
                                                'class' => 'input-small required character',
                                                'value' => isset($_POST['save']) ? $input['ref']['company_name'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'ref[city][]',
                                                'class' => 'input-large required alphabet',
                                                'value' => isset($_POST['save']) ? $input['ref']['city'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'ref[contact_no][]',
                                                'class' => 'input-small required numeric',
                                                'value' => isset($_POST['save']) ? $input['ref']['contact_no'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <?php
                                        if ($len == 0)
                                            $style = "visibility:hidden;";
                                        else
                                            $style = "visibility:visible;";
                                        ?>



                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>
                    <div class="tab-pane" id="tabs-9" role="tabpanel" aria-labelledby="tabs-9-tab">
                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>S.No</th>

                                    <th>Company</th>

                                    <th>Designation</th>

                                    <th>Start Date</th>

                                    <th>End Date</th>

                                    <th>Salary</th>

                                    <th>Reason for Leaving</th>

                                    <th><a href="javascript:void(0);" class="btn btn-danger add_row">+</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                if (!isset($exp_length) || $exp_length == 0):

                                    $exp_length = 1;

                                endif;

                                for ($len = 0; $len < $exp_length; $len++) {
                                    ?>

                                    <tr>

                                        <td class="center sno"><?= $len + 1; ?></td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'exp[company][]',
                                                'class' => 'input-small required character',
                                                'value' => isset($_POST['save']) ? $input['exp']['company'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'exp[designation][]',
                                                'class' => 'input-small required alphabet',
                                                'value' => isset($_POST['save']) ? $input['exp']['designation'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'exp[start_date][]',
                                                'class' => 'input-small datepicker required start_date input-date',
                                                'readonly' => 'readonly',
                                                'value' => isset($_POST['save']) ? $input['exp']['start_date'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'exp[end_date][]',
                                                'class' => 'input-small datepicker required end_date input-date',
                                                'readonly' => 'readonly',
                                                'value' => isset($_POST['save']) ? $input['exp']['end_date'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>



                                        </td>

                                        <td class="center">

                                            <?php
                                            $data = array(
                                                'name' => 'exp[salary][]',
                                                'class' => 'input-small required float',
                                                'value' => isset($_POST['save']) ? $input['exp']['salary'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?>

                                        </td>

                                        <td class="center"><?php
                                            $data = array(
                                                'name' => 'exp[reason_for_leaving][]',
                                                'class' => 'input-small required character',
                                                'value' => isset($_POST['save']) ? $input['exp']['reason_for_leaving'][$len] : ""
                                            );

                                            echo form_input($data);
                                            ?></td>

                                        <?php
                                        if ($len == 0)
                                            $style = "visibility:hidden;";
                                        else
                                            $style = "visibility:visible;";
                                        ?>

                                        <td class="center"><a href="javascript:void(0);" class="btn btn-danger remove_row" style="<?= $style ?>"><i class="fa fa-minus icon-black"></i></a></td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['show'])) {

        $user_show = $_GET['show'];
    } else
        $user_show = 10;
    ?>
    <div class="button_right_align action-btn-align">

        <?php
        $data = array(
            'name' => 'save',
            'value' => 'Save',
            'class' => 'btn btn-success border4 submit',
            'title' => 'Save'
        );



        echo form_submit($data);
        ?>

        <a href="<?= $this->config->item('base_url') . "masters/biometric/employees" ?>" title="Back"><input type="button" class="btn btn-danger border4" value="Cancel" /></a>

    </div>
</div>



<script type="text/javascript">

    $(document).ready(function () {



<?php if (isset($input['family']['name'])) { ?>

            $(".hide").show();

    <?php
}

if ($n = 1) {
    ?>

            $(".wage-report").addClass("required");

<?php } ?>



    });

</script>