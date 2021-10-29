<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?php echo $theme_path; ?>/js/jquery-1.8.2.js" type="text/javascript"></script>
<script src="<?php echo $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<link rel="stylesheet" href="<?php echo $theme_path; ?>/css/bootstrap-select.css" />
<script src="<?php echo $theme_path; ?>/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo $theme_path; ?>/js/jquery.scannerdetection.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path; ?>/js//sweetalert.css">
<script src="<?php echo $theme_path; ?>/js/sweetalert.min.js" type="text/javascript"></script>
<style type="text/css">
    .text_right {
        text-align: right;
    }

    .box,
    .box-body,
    .content {
        padding: 0;
        margin: 0;
        border-radius: 0;
    }

    #top_heading_fix h3 {
        top: -57px;
        left: 6px;
    }

    #TB_overlay {
        z-index: 20000 !important;
    }

    #TB_window {
        z-index: 25000 !important;
    }

    .dialog_black {
        z-index: 30000 !important;
    }

    #boxscroll22 {
        max-height: 291px;
        overflow: auto;
        cursor: inherit !important;
    }

    .error_msg,
    em {
        color: rgb(255, 0, 0);
        font-size: 12px;
        font-weight: normal;
    }

    .ui-datepicker td.ui-datepicker-today a {
        background: #999999;
    }

    .auto-asset-search ul {
        position: absolute !important;
        z-index: 4;
        height: 150px;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .auto-asset-search ul#service-list li.no_data {
        overflow-y: none;
    }

    .auto-asset-search ul#country-list li:hover {
        background: #c3c3c3;
        cursor: pointer;
    }

    .auto-asset-search ul#product-list li:hover {
        background: #c3c3c3;
        cursor: pointer;
    }

    .auto-asset-search ul#service-list li:hover {
        background: #c3c3c3;
        cursor: pointer;
    }

    .auto-asset-search ul#country-list li {
        background: #dadada;
        margin: 0;
        padding: 5px;
        border-bottom: 1px solid #f3f3f3;
    }

    .auto-asset-search ul#product-list li {
        background: #dadada;
        margin: 0;
        padding: 5px;
        border-bottom: 1px solid #f3f3f3;
    }

    ul li {
        list-style-type: none;
    }

    .auto-asset-search ul#service-list li {
        background: #dadada;
        margin: 0;
        padding: 5px;
        border-bottom: 1px solid #f3f3f3;
    }

    .tabwid3 {
        width: 92.5% !important;
    }

    .tabwid4 {
        width: 89.5% !important;
    }

    .auto-asset-search ul {
        width: 100%;
        padding: 0px;
    }

    .auto-asset-search ul#country-list li {
        width: 100%;
    }
</style>
<?php
$model_numbers_json = array();
if (!empty($products)) {
    foreach ($products as $list) {
        $model_numbers_json[] = '{ id: "' . $list['id'] . '", value: "' . $list['product_name'] . '"}';
    }
}
$model_numbers_extra = array();
if (!empty($products)) {
    foreach ($products as $list) {
        if (!empty($list['model_no'])) {
            $model_numbers_extra[] = '{ id: "' . $list['id'] . '", value: "' . $list['model_no'] . '"}';
        }
    }
}
$customers_json = array();
if (!empty($customers)) {
    foreach ($customers as $list) {
        $customers_json[] = '{ id: "' . $list['id'] . '", value: "' . $list['store_name'] . '"}';
    }
}
?>
<div class="mainpanel">
    <div id='empty_data'></div>
    <div class="auto-widget">
        <!--    <p>Your Skills: <input type="text" id="skill_input"/></p>-->
    </div>
    <div class="contentpanel mb-45">
        <div class="media">
            <h4>Add Sales Invoice &nbsp;
                <?php if (count($firms) == 1) { ?>
                    <div class="cuto-firm">
                        Firm : <small> <?php echo $firms[0]['firm_name']; ?> </small> &nbsp; &nbsp; &nbsp; Invoice No : <small id="grn_no_2"></small>
                    </div>
                <?php } ?>
            </h4>
        </div>
        <table class="static" style="display: none;">
            <tr>
                <td class="action-btn-align s_no"></td>
                <td>
                    <input type="text" name="model_no[]" id="model_no" style="width:100%;font-weight: 600; " class='form-align auto_customer tabwid model_no ' readonly="" />
                    <span class="error_msg"></span>
                    <input type="hidden" name="product_id[]" id="product_id" class=' tabwid form-align product_id' />
                    <input type="hidden" value="" id="product_cost" />
                    <input type="hidden" name="type[]" id="type" class=' tabwid form-align type' />
                    <div id="suggesstion-box1" class="auto-asset-search suggesstion-box1"></div>
                </td>
                <td style="display:none;">
                    <select id='cat_id' class='cat_id static_style  form-control form-align' style="display:none;" name='categoty[]'>
                        <option value="">Select</option>
                    </select>
                </td>
                <td class="action-btn-align">
                    <input type="hidden" style="width:100%" class='form-align tabwid model_no_extra' readonly="readonly" />
                    <input type="text" tabindex="-1" name='unit[]' style="width:70px;" class="unit" />
                </td>
                <td class="qty_text">
                    <select name='brand[]' tabindex="-1" class="form-control form-align brand_id" style="display: none;">
                        <option>Select</option>
                        <?php
                        if (isset($brand) && !empty($brand)) {
                            foreach ($brand as $val) {
                        ?>
                                <option value='<?php echo $val['id'] ?>'><?php echo $val['brands'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="col-xs-8">
                        <input type="text" tabindex="-1" name='quantity[]' style="width:70px;" class="qty" value="" id="qty" />
                    </div>
                    <!-- <div class="col-xs-4"> <span class="label label-success stock_qty" > 0 </span></div>
                    <span class="error_msg"></span> -->
                </td>
                <td>
                    <input type="text" tabindex="-1" name='per_cost[]' style="width:70px;" class="selling_price percost " id="price" />
                    <span class="error_msg"></span>
                </td>
                <td class="action-btn-align">
                    <input type="text" tabindex="-1" style="width:70px;" class="gross" />
                </td>
                <!--                <td>
                    <input type="text"  name='discount[]' style="width:70px;" class="discount" />
                </td>-->
                <td class="action-btn-align cgst_td">
                    <input type="text" tabindex="-1" name='tax[]' style="width:70px;" class="pertax" />
                </td>
                <td class="action-btn-align sgst_td">
                    <input type="text" tabindex="-1" name='gst[]' style="width:70px;" class="gst" />
                </td>
                <td class="action-btn-align igst_td">
                    <input type="text" tabindex="-1" name='igst[]' style="width:70px;" class="igst wid50" />
                </td>
                <td>
                    <input type="text" tabindex="-1" style="width:100px;" name='sub_total[]' readonly="readonly" id="sub_toatl" class="subtotal text_right" />
                </td>
                <td class="action-btn-align"><a id='delete_group' class="del btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
        </table>
        <form method="post" class="panel-body" id="quotation">
            <div class="row" id="add_sales">
                <div class="col-md-4">
                    <?php if (count($firms) > 1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Firm Name</label>
                            <div class="col-sm-8">
                                <select onchange="Firm(this.value, 0)" name="quotation[firm_id]" class="form-control form-align required" id="firm" tabindex="1">
                                    <option value="">Select</option>
                                    <?php
                                    if (isset($firms) && !empty($firms)) {
                                        foreach ($firms as $firm) {
                                    ?>
                                            <option value="<?php echo $firm['firm_id']; ?>"> <?php echo $firm['firm_name']; ?> </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="error_msg"></span>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <select onchange="Firm(this.value)" name="quotation[firm_id]" class="form-control form-align required" id="firm" readonly="" style="display:none;">
                            <?php
                            if (isset($firms) && !empty($firms)) {
                                foreach ($firms as $firm) {
                            ?>
                                    <option value="<?php echo $firm['firm_id']; ?>"> <?php echo $firm['firm_name']; ?> </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    <?php } ?>
                    <div class="form-group dnone">
                        <label class="col-sm-4 control-label">Customer Mobile </label>
                        <div class="col-sm-8">
                            <input type="hidden" tabindex="-1" name="customer[mobil_number]" id="customer_no" class="form-control form-align" />
                            <span class="error_msg"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Customer Name <span style="color:#F00; font-style:oblique;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="2" name="customer[store_name]" id="customer_name" value="<?php echo $val['id']; ?> " class='form-control form-align auto_customer  ' />
                            <span class="error_msg"></span>
                            <input type="hidden" name="customer[id]" id="customer_id" class='id_customer  form-align' />
                            <!--<input type="hidden"  name="quotation[product_id]" id="cust_id" class='id_customer' />-->
                            <div id="suggesstion-box" class="auto-asset-search "></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Delivery Status<span style="color:#F00; font-style:oblique;">*</span></label>
                        <div class="col-sm-8">
                            <?php $delivery_status = array('delivered', 'partially_delivered', 'pending'); ?>
                            <select name="quotation[delivery_status]" class="form-control required form-align" id="delivery_status" tabindex="3">
                                <!-- <option value="">Select</option> -->
                                <?php
                                if (isset($delivery_status) && !empty($delivery_status)) {
                                    foreach ($delivery_status as $status) {
                                ?>
                                        <option value="<?php echo $status; ?>"> <?php echo ucwords(str_replace("_", " ", $status)); ?> </option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Sales Man</label>
                        <div class="col-sm-8">
                            <select name='quotation[sales_man]' tabindex="4" class="form-control class_req" id="sales_man">
                                <option>Select</option>
                                <?php
                                if (isset($sales_man) && !empty($sales_man)) {
                                    foreach ($sales_man as $val) {
                                ?>
                                        <option value='<?php echo $val['id'] ?>'><?php echo $val['sales_man_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="error_msg"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php if (count($firms) > 1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Invoice Number</label>
                            <div class="col-sm-8">
                                <input type="text" tabindex="-1" name="quotation[q_no]" class="code form-control colournamedup  form-align" readonly="readonly" value="" id="grn_no">
                            </div>
                        </div>
                    <?php } else {
                    ?>
                        <input type="hidden" tabindex="-1" name="quotation[q_no]" class="code form-control colournamedup  form-align" readonly="readonly" value="" id="grn_no">
                    <?php } ?>
                    <div class="customer_details_invoice"><label id="customer_details_label"></label></div>
                    <div class="form-group dnone">
                        <label class="col-sm-4 control-label">Customer Email ID</label>
                        <div class="col-sm-8">
                            <div id='customer_td'>
                                <input type="hidden" tabindex="-1" name="customer[email_id]" id="email_id" class="form-control form-align " />
                                <span class="error_msg"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group dnone">
                        <label class="col-sm-4 control-label">Customer Address</label>
                        <div class="col-sm-8">
                            <textarea name="customer[address1]" tabindex="-1" id="address1" class="form-control form-align" style="display: none; "></textarea>
                            <span class="error_msg"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Reference Name<span style="color:#F00; font-style:oblique;">*</span></label>
                        <div class="col-sm-8">
                            <select id='ref_class' class='nick form-align  form-control static_style class_req required' name='quotation[ref_name]' tabindex="5">
                                <option value="">Select</option>
                                <?php
                                if (isset($ref_grps) && !empty($ref_grps)) {
                                    foreach ($ref_grps as $val) {
                                        if ($val['reference_type'] == 1) {
                                            $user_name = $this->user_model->get_user_name_by_id($val['user_id']);
                                        } else if ($val['reference_type'] == 2) {
                                            $user_name = $this->customer_model->get_customer_name_by_id($val['customer_id']);
                                        } else {
                                            $user_name = array();
                                        }
                                ?>
                                        <option value="<?php echo $val['id'] ?>"><?php
                                                                                    if (count($user_name) > 0) {
                                                                                        echo $user_name[0]['name'];
                                                                                    } else {
                                                                                        echo $val['others'];
                                                                                    }
                                                                                    ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <input type="hidden" name="quotation[job_id]" class="code form-control colournamedup  form-align" value="" id="sales_id">
                            <input type="hidden" name="quotation[inv_id]" class="code form-control colournamedup  form-align" value="" id="invoice_id">
                            <span class="error_msg"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">GSTIN NO </label>
                        <div class="col-sm-8">
                            <input type="text" name="company[tin_no]" id="tin" tabindex="-1" readonly="readonly" class="form-control form-align " />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label first_td1">Bill Type <span style="color:#F00; font-style:oblique;">*</span></label>
                        <div class="col-sm-8">
                            <!--                            <input type="radio" class="receiver" id="bill1" value="cash" name="cashbill"/>Cash sale
                            <input type="radio" class="receiver" id="bill2" value="credit" name="quotation[bill_type]"/>Credit sale-->
                            <input type="radio" tabindex="6" class="receiver" id="bill1" value="cash" name="quotation[bill_type]" />Cash Sale
                            <input type="radio" tabindex="6" class="receiver" id="bill2" value="credit" name="quotation[bill_type]" />Credit Sale<br>
                            <span id="type1" class="error_msg"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Date <span style="color:#F00; font-style:oblique;">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" tabindex="7" class="form-control form-align datepicker required" name="quotation[created_date]" placeholder="dd-mm-yyyy" value="<?php echo date('d-m-Y'); ?>">
                            <span class="error_msg"></span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="customer[credit_limit]" id="credit_limit" value="">
            <input type="hidden" name="customer[credit_days]" id="credit_days" value="">
            <input type="hidden" name="customer[temp_credit_limit]" id="temp_credit_limit" value="">
            <input type="hidden" name="cus_type" id="cus_type" value="">
            <div class="mscroll">
                <table class="table table-striped table-bordered responsive dataTable no-footer dtr-inline text-center" id="add_quotation">
                    <thead>
                        <tr>
                            <td width="3%" class="first_td1">S.No</td>
                            <!--<td width="15%" class="first_td1">Category</td>-->
                            <td width="25%" class="first_td1">Product Name</td>
                            <!--<td width="25%" class="first_td1">Model No</td>-->
                            <!--<td width="10%" class="first_td1">Brand</td>-->
                            <td width="5%" class="first_td1">Unit</td>
                            <td width="10%" class="first_td1 action-btn-align">QTY <span style="color:#F00; font-style:oblique;">*</span></td>
                            <td width="8%" class="first_td1 action-btn-align">Unit Price <span style="color:#F00; font-style:oblique;">*</span></td>
                            <td width="6%" class="first_td1 action-btn-align">Total</td>
                            <!--<td  width="7%" class="first_td1 action-btn-align">Discount %</td>-->
                            <td width="5%" class="first_td1 action-btn-align cgst_td">CGST %</td>
                            <td width="5%" class="first_td1 action-btn-align sgst_td">SGST %</td>
                            <td width="5%" class="first_td1 action-btn-align igst_td">IGST %</td>
                            <td width="8%" class="first_td1">Net Value</td>
                            <td width="5%" class="action-btn-align">
                                <a id='add_group' class="btn btn-success form-control padd2"><span class="glyphicon glyphicon-plus"></span></a>
                            </td>
                        </tr>
                    </thead>
                    <tbody id='app_table'>
                        <tr>
                            <td class="action-btn-align s_no">
                                <?php echo 1; ?>
                            </td>
                            <td>
                                <input type="text" name="model_no[]" id="model_no" tabindex="8" style="width:100%; font-weight: 600;" class='form-align auto_customer tabwid model_no required' readonly="" />
                                <!--<input type="text" name="model_no[]" id="model_no" tabindex="1" style="width:100%; font-weight: 600;" class='form-align auto_customer tabwid model_no required'  readonly="" />-->
                                <span class="error_msg"></span>
                                <input type="hidden" name="product_id[]" id="product_id" class='product_id tabwid form-align' />
                                <input type="hidden" value="" id="product_cost" />
                                <input type="hidden" name="type[]" id="type" class=' tabwid form-align type' />
                                <div id="suggesstion-box1" class="auto-asset-search suggesstion-box1"></div>
                            </td>
                            <td style="display: none;">
                                <select id='cat_id' class='cat_id static_style  form-control form-align' style="display: none;" name='categoty[]'>
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td class="action-btn-align">
                                <input type="hidden" style="width:100%" class='form-align tabwid model_no_extra' readonly="" />
                                <input type="text" tabindex="-1" name='unit[]' style="width:70px;" class="unit" />
                            </td>
                            <td class="action-btn-align">
                                <select name='brand[]' tabindex="-1" class="form-control form-align brand_id" style="display: none;">
                                    <option value="">Select</option>
                                    <?php
                                    if (isset($brand) && !empty($brand)) {
                                        foreach ($brand as $val) {
                                    ?>
                                            <option value='<?php echo $val['id'] ?>'><?php echo $val['brands'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="col-xs-8">
                                    <input type="text" name='quantity[]' style="width:70px;" class="qty " />
                                </div>
                                <!-- <div class="col-xs-4">
                                    <span class="label label-success stock_qty"> 0 </span>
                                </div> -->
                                <span class="error_msg"></span>
                            </td>
                            <td class="action-btn-align">
                                <input type="text" name='per_cost[]' style="width:70px;" class="selling_price percost required" />
                                <span class="error_msg"></span>
                            </td>
                            <td class="action-btn-align">
                                <input type="text" tabindex="-1" style="width:70px;" class="gross" />
                            </td>
                            <!--                            <td class="action-btn-align">
                                <input type="text"    name='discount[]' style="width:70px;" class="discount" />
                            </td>-->
                            <td class="action-btn-align cgst_td">
                                <input type="text" name='tax[]' tabindex="-1" style="width:70px;" class="pertax" />
                            </td>
                            <td class="action-btn-align sgst_td">
                                <input type="text" name='gst[]' tabindex="-1" style="width:70px;" class="gst" />
                            </td>
                            <td class="action-btn-align igst_td">
                                <input type="text" name='igst[]' tabindex="-1" style="width:70px;" class="igst wid50" />
                            </td>
                            <td>
                                <input type="text" style="width:100px;" tabindex="-1" name='sub_total[]' readonly="readonly" class="subtotal text_right" />
                            </td>
                            <td class="action-btn-align"><a id='delete_group' tabindex="-1" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                    </tbody>
                    <tbody class="addtional">
                        <tr>
                            <td colspan="3" style="width:100%; text-align:right;"><b>Total</b></td>
                            <td class="action-btn-align"><input type="text" tabindex="-1" name="quotation[total_qty]" readonly="readonly" class="total_qty" style="width:70%;" id="total" /></td>
                            <td colspan="4" style="text-align:right;"><b>Sub Total</b></td>
                            <td class="action-btn-align"><input type="text" name="quotation[subtotal_qty]" tabindex="-1" readonly="readonly" class="final_sub_total text_right" style="width:100px;" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align:right;"><b>Advance Amount</b></td>
                            <td class="action-btn-align"><input type="text" name="advance" tabindex="-1" id="advance" readonly="readonly" class="advance text_right" style="width:100px;" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="width:70px; text-align:right;"></td>
                            <td colspan="4" style="text-align:right;font-weight:bold;"><input type="text" tabindex="-1" name="quotation[tax_label]" class='tax_label text_right' style="width:100%;" /></td>
                            <td>
                                <input type="text" name="quotation[tax]" class='totaltax text_right' tabindex="-1" style="width:100px;" />
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="8" style="text-align:right;">Round Off ( - )</td>
                            <!--                            <td>
                                <div class="switch switch-blue" >
                                    <input type="radio" class="switch-input" name="view" value="week" id="week" checked>
                                    <label for="week" class="switch-label switch-label-off r-plus">+</label>
                                    <input type="radio" class="switch-input" name="view" value="month" id="month">
                                    <label for="month" class="switch-label switch-label-on r-minus">-</label>
                                    <span class="switch-selection"></span>
                                </div>
                            </td>-->
                            <td><input type="text" name="quotation[round_off]" tabindex="-1" value="" class="round_off text_right" style="width:100px;" readonly="" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tbody class="additional" id="add_new_values">
                        <tr>
                            <td colspan="2" style="text-align:right;">Transport Charge </td>
                            <td><input type="text" name="quotation[transport]" value="" class="transport text_right" tabindex="-1" style="width:70px;" /></td>
                            <td style="text-align:right;">Labour Charge</td>
                            <td><input type="text" name="quotation[labour]" value="" class="labour text_right" tabindex="-1" style="width:70px;" /></td>
                            <td style="text-align:right;" class="sgst_td"> SGST </td>
                            <td style="text-align:right;" class="igst_td"> IGST </td>
                            <td><input type="text" tabindex="-1" value="" readonly class="add_sgst text_right" style="width:70px;" /></td>
                            <td style="text-align:right;"> CGST </td>
                            <td><input type="text" tabindex="-1" value="" readonly class="add_cgst text_right" style="width:100px;" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="8" style="text-align:right;font-weight:bold;">Net Total</td>
                            <td><input type="text" tabindex="-1" name="quotation[net_total]" id="net_total" readonly="readonly" class="final_amt text_right" style="width:100px;" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <label class="">Remarks</label>
                                <input name="quotation[remarks]" tabindex="-1" type="text" class="form-control" style="width:90%; display: inline" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="inner-sub-tit mstyle">TERMS AND CONDITIONS</div>
            <div>
                <input type="hidden" class="form-control datepicker class_req borderra0 terms" name="quotation[delivery_schedule]" placeholder="dd-mm-yyyy">
                <input type="hidden" id='to_date' class="form-control datepicker borderra0 terms" name="quotation[notification_date]" placeholder="dd-mm-yyyy">
                <input type="text" tabindex="-1" class="form-control class_req borderra0 terms" name="quotation[mode_of_payment]" />
                <input type="hidden" class="form-control class_req borderra0 terms" name="quotation[validity]" />
            </div>
            <input type="hidden" name="quotation[customer]" id="c_id" class='id_customer' />
            <input type="hidden" name="gst_type" id="gst_type" class="gst_type" />
            <input type="hidden" class='hide_prod' />
            <div class="action-btn-align mb-bot20">
                <button class="btn btn-primary" name="print" value="no" id="save" tabindex="-1"><span class="glyphicon glyphicon-plus"></span>Create</button>
                <button class="btn btn-primary" name="print" value="yes" id="save"><span class="glyphicon glyphicon-plus"></span> Save and <span class="glyphicon glyphicon-print"></span> print</button>
            </div>
            <br />
            <div>
                <input type="hidden" name="" value="0" id="button_clik" />
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    // $('document').ready(function() {
    //     $('#firm').focus();
    //     var cus_name = $('#customer_name').val();
    //     if (cus_name == '') {
    //         $("#app_table input").attr("disabled", false);
    //     }
    // });
</script>
<script type="text/javascript">
    var formHasChanged = false;
    var submitted = false;
    $('#save').live('click', function() {
        var net_total = $('#net_total').val();
        var credit_limit = $('#credit_limit').val();
        var temp_credit_limit = $('#temp_credit_limit').val();
        var approved_by = $('#approved_by').val();
        var m = 0;
        $('.required').each(function() {
            var tr = $('#app_table tr').length;
            if (tr > 1) {
                test = $(this).closest('tr td').find('input.model_no').val();
                if (test == '') {
                    $(this).closest('tr').remove();
                }
            }
        });
        $('.required').each(function() {
            this_val = $.trim($(this).val());
            this_id = $(this).attr("id");
            this_class = $(this).attr("class");
            if (this_val == "") {
                $(this).closest('div .form-group').find('.error_msg').text('This field is required').css('display', 'inline-block');
                $(this).closest('tr td').find('.error_msg').text('This field is required').css('display', 'inline-block');
                m = 1;
            } else {
                $(this).closest('div .form-group').find('.error_msg').text('');
                $(this).closest('tr td').find('.error_msg').text('');
                submitted = true;
            }
        });
        if ($('.receiver:checked').length <= 0) {
            $("#type1").html("This field is required");
            m = 1;
        } else {
            $("#type1").html("");
        }
        // $('.qty').each(function() {
        //     var qty = $(this).closest('tr').find('.stock_qty').text();
        //     this_val = $.trim($(this).val());
        //     if (Number(this_val) > Number(qty)) {
        //         $(this).closest('td').find('.error_msg').text('Invalid quantity').css('display', 'inline-block');
        //         m = 1;
        //     } else {
        //         $(this).closest('td').find('.error_msg').text("");
        //     }
        // });
        if (m > 0) {
            $('html, body').animate({
                scrollTop: ($('.error_msg:visible').offset().top - 60)
            }, 500);
            return false;
        }

        if (m == 0) {
            var button_clik = $('#button_clik').val();
            if (button_clik == 0) {
                var button_clik = 1;
                $('#button_clik').val(button_clik);
                return true;
            } else {
                return false;
            }
        }
        //        else if ((Number(net_total) > Number(credit_limit)) && (Number(temp_credit_limit) == ''))
        //        {
        //            $.ajax({
        //                type: "POST",
        //                url: "<?php echo $this->config->item('base_url'); ?>" + "quotation/send_notification/",
        //                data: 'exceed_total=' + $("#net_total").val() + '&credit_limit=' + $("#credit_limit").val() + ' &cust_id=' + $('#customer_id').val(),
        //                success: function (response) {
        //                    if (response == 'sent')
        //                    {
        //                        sweetAlert("Error...", "Credit Limit Exceeded Please Contact your Admin!", "error");
        //
        //                    }
        //                }
        //            });
        //
        //            $('html, body').animate({
        //                scrollTop: ($('.error_msg:visible').offset().top - 60)
        //            }, 500);
        //            return false;
        //        }
    });
    $(document).ready(function() {
        if ($('#gst_type').val() == '') {
            $('#add_quotation').find('tr td.igst_td').hide();
            $('#add_new_values').find('tr td.igst_td').hide();
        }
        $('#firm').trigger('change');
        $('body').on('keydown', 'input#customer_name', function(e) {
            var firm_id = $('#firm').val();
            var c_data = [<?php echo implode(',', $customers_json); ?>];
            $("#customer_name").blur(function() {
                var keyEvent = $.Event("keydown");
                keyEvent.keyCode = $.ui.keyCode.ENTER;
                $(this).trigger(keyEvent);
                // Stop event propagation if needed
                return false;
            }).autocomplete({
                source: function(request, response) {
                    // filter array to only entries you want to display limited to 10
                    var outputArray = new Array();
                    for (var i = 0; i < c_data.length; i++) {
                        if (c_data[i].value.toLowerCase().match(request.term.toLowerCase())) {
                            outputArray.push(c_data[i]);
                        }
                    }
                    response(outputArray.slice(0, 10));
                },
                minLength: 0,
                autoFocus: true,
                select: function(event, ui) {
                    // $("#app_table input,select").attr("disabled", false);
                    cust_id = ui.item.id;
                    $.ajax({
                        type: 'POST',
                        data: {
                            cust_id: cust_id,
                            firm_id: firm_id
                        },
                        url: "<?php echo $this->config->item('base_url'); ?>" + "quotation/get_customer/",
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (result != null && result.length > 0) {
                                $("#gst_type").val(result[0].state_id);
                                $("#customer_id").val(result[0].id);
                                $("#c_id").val(result[0].id);
                                $("#cus_type").val(result[0].customer_type);
                                $("#customer_name").val(result[0].store_name);
                                $("#customer_no").val(result[0].mobil_number);
                                $("#email_id").val(result[0].email_id);
                                $("#address1").val(result[0].address1);
                                $("#tin").val(result[0].tin);
                                $("#credit_limit").val(result[0].credit_limit);
                                $("#credit_days").val(result[0].credit_days);
                                $("#temp_credit_limit").val(result[0].temp_credit_limit);
                                $("#approved_by").val(result[0].approved_by);
                                $("#advance").val(result[0].advance);
                                $("#customer_details_label").html('<span class="label label-success" style="float:right">' + result[0].balance + ' </span>' + result[0].store_name + '<br>' + result[0].address1 + '<br> Email : ' + result[0].email_id + '<br> Mobile : ' + result[0].mobil_number);
                                //                                if (result[0].customer_type == 1 || result[0].customer_type == 3)
                                //                                    $("#bill1").attr('checked', 'checked');
                                //                                else if (result[0].customer_type == 2 || result[0].customer_type == 4)
                                //                                    $("#bill2").attr('checked', 'checked');
                                //                                else
                                //                                    $(".receiver").prop("checked", false);
                                if (result[0].customer_type == 1 || result[0].customer_type == 3)
                                    $("#bill1").attr('checked', false);
                                else if (result[0].customer_type == 2 || result[0].customer_type == 4)
                                    $("#bill2").attr('checked', false);
                                else
                                    $(".receiver").prop("checked", false);
                                if ($('#gst_type').val() != '') {
                                    if ($('#gst_type').val() == 31) {
                                        $('#add_quotation').find('tr td.sgst_td').show();
                                        $('#add_quotation').find('tr td.igst_td').hide();
                                    } else {
                                        $('#add_quotation').find('tr td.igst_td').show();
                                        $('#add_quotation').find('tr td.sgst_td').hide();
                                    }
                                } else {
                                    $('#add_quotation').find('tr td.igst_td').hide();
                                }
                            }
                        }
                    });
                    var prod_array = new Array();
                    $(".product_id").each(function() {
                        prod_array.push($(this).val());
                    });
                    //if (!empty(prod_array)) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            cust_id: cust_id,
                            prod_array: prod_array
                        },
                        url: "<?php echo $this->config->item('base_url'); ?>" + "sales/get_product_cost/",
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (data != null && data.length > 0) {
                                $('input#product_cost').each(function(i) {
                                    if (i != 0) {
                                        var product_id = '';
                                        product_id = $(this).closest('tr').find('input#product_id').val();
                                        var qty = '';
                                        qty = $(this).closest('tr').find('input.qty').val();
                                        if (qty == '') {
                                            $(this).closest('tr').find('input.selling_price').val(result[product_id].selling_price);
                                            $(this).closest('tr').find('input.subtotal').val('');
                                            $(this).closest('tr').find('input.gross').val('');
                                        } else {
                                            $(this).closest('tr').find('input.selling_price').val(result[product_id].selling_price);
                                            $(this).closest('tr').find('.qty').trigger('keyup');
                                            //                                            var price = result[product_id].selling_price * qty;
                                            //                                            $(this).closest('tr').find('input.selling_price').val(price);
                                        }
                                    }
                                });
                            }
                        }
                    });
                    //}
                }
            });
        });
    });
    $('#add_group').click(function() {
        var tableBody = $(".static").find('tr').clone();
        $(tableBody).closest('tr').find('.model_no,.percost,.qty').addClass('required');
        var cus_name = $('#customer_name').val();
        if (cus_name == '') {
            $(tableBody).closest('tr').find('select,input').attr("disabled", false);
        }
        $('#app_table').append(tableBody);
        $('#add_quotation tbody tr td:nth-child(2)').addClass('relative');
        if ($('#gst_type').val() != '') {
            if ($('#gst_type').val() == 31) {
                $('#add_quotation').find('tr td.sgst_td').show();
                $('#add_quotation').find('tr td.igst_td').hide();
            } else {
                $('#add_quotation').find('tr td.igst_td').show();
                $('#add_quotation').find('tr td.sgst_td').hide();
            }
        } else {
            $('#add_quotation').find('tr td.igst_td').hide();
        }
        var i = 1;
        $('#app_table tr').each(function() {
            $(this).closest("tr").find('.s_no').html(i);
            $("#app_table").find('tr:last td input.model_no').focus();
            i++;
        });
        $('table#add_quotation').find("tbody#app_table").find('tr:last td:nth-child(2) input').focus();
    });
    $('#add_group_service').click(function() {
        var tableBody = $(".static_ser").find('tr').clone();
        $(tableBody).closest('tr').find('.model_no,.percost,.qty').addClass('required');
        $('#app_table').append(tableBody);
        $('#add_quotation tbody tr td:nth-child(2)').addClass('relative');
    });
    $('#delete_group').live('click', function() {
        $(this).closest("tr").remove();
        calculate_function();
        var i = 1;
        $('#app_table tr').each(function() {
            $(this).closest("tr").find('.s_no').html(i);
            i++;
        });
    });
    $(".remove_comments").live('click', function() {
        $(this).closest("tr").remove();
        var full_total = 0;
        $('.total_qty').each(function() {
            full_total = full_total + Number($(this).val());
        });
        $('.full_total').val(full_total);
    });
    $('.qty,.percost,.pertax,.totaltax,.gst,.igst,.discount,.transport,.labour').live('keyup', function() {
        calculate_function();
    });
    $(".r-plus").on('click', function() {
        var round_off = $('.round_off').val();
        $('.temp_round_off_plus').val(round_off);
        $('.temp_round_off_minus').val(0);
        calculate_function();
    });
    $(".r-minus").on('click', function() {
        var round_off = $('.round_off').val();
        $('.temp_round_off_minus').val(round_off);
        $('.temp_round_off_plus').val(0);
        calculate_function();
    });

    function calculate_function() {
        var final_qty = 0;
        var final_sub_total = 0;
        // var temp_round_off_plus = Number($('.temp_round_off_plus').val());
        var transport = Number($('.transport').val());
        var labour = Number($('.labour').val());
        var advance = Number($('.advance').val());
        var cgst = 0;
        var sgst = 0;
        $('.qty').each(function() {
            var qty = $(this);
            var percost = $(this).closest('tr').find('.percost');
            var pertax = $(this).closest('tr').find('.pertax');
            // var discount = $(this).closest('tr').find('.discount');
            var subtotal = $(this).closest('tr').find('.subtotal');
            var transport = $(this).closest('tr').find('.transport');
            var labour = $(this).closest('tr').find('.labour');
            var round_off = $(this).closest('tr').find('.round_off');
            if ($('#gst_type').val() != '') {
                if ($('#gst_type').val() == 31) {
                    var gst = $(this).closest('tr').find('.gst');
                } else {
                    gst = $(this).closest('tr').find('.igst');
                }
            }
            if (Number(qty.val()) != 0) {
                tot = Number(qty.val()) * Number(percost.val());
                $(this).closest('tr').find('.gross').val(tot);
                taxless = Number(qty.val()) * Number(percost.val());
                total_tax = pertax + gst;
                pertax1 = Number(pertax.val() / 100) * Number(percost.val());
                gst1 = Number(gst.val() / 100) * Number(percost.val());
                cgst += Number(pertax.val() / 100) * taxless;
                sgst += Number(gst.val() / 100) * taxless;
                //  discount1 = Number(discount.val() / 100) * Number(percost.val());
                var discount1 = 0;
                //temp_sub_total = temp_sub_total + ((Number(qty.val()) * Number(percost.val())) + (pertax1 * Number(qty.val())) + (gst1 * Number(qty.val()))) - (discount1 * Number(qty.val()));
                //temp_sub_total = temp_sub_total - (discount1 * Number(qty.val()));
                sub_total1 = (Number(qty.val()) * Number(percost.val())) + (pertax1 * Number(qty.val())) + (gst1 * Number(qty.val()));
                sub_total = sub_total1 - (discount1 * Number(qty.val()));
                taxless = taxless - (discount1 * Number(qty.val()));
                subtotal.val(taxless.toFixed(2));
                final_qty = final_qty + Number(qty.val());
                final_sub_total = final_sub_total + taxless;
            }
        });
        $('.add_cgst').val(cgst.toFixed(2));
        $('.add_sgst').val(sgst.toFixed(2));
        $('.total_qty').val(final_qty);
        $('.final_sub_total').val(final_sub_total.toFixed(2));
        //$('.temp_sub_total').val(temp_sub_total);
        //other item total
        total_item = 0;
        $('.totaltax').each(function() {
            var totaltax = $(this);
            if (Number(totaltax.val()) != 0) {
                total_item = total_item + Number(totaltax.val());
            }
        });
        var final_amt = final_sub_total + total_item + transport + labour + cgst + sgst;
        final_amt = final_amt - advance;
        value = final_amt.toFixed(2);
        var round = value.split('.');
        $('.round_off').val('0.' + round[1]);
        var temp_round_off_minus = Number($('.round_off').val());
        var finals = final_amt - Number(temp_round_off_minus);
        finals = Math.abs(finals);
        $('.final_amt').val(finals.toFixed(2));
    }
    $(".datepicker").datepicker({
        setDate: new Date(),
        onClose: function() {
            $("#app_table").find('tr:first td  input.model_no').focus();
        }
    });
    $(document).ready(function() {
        $("#po_no").autocomplete(BASE_URL + "gen/get_po_list", {
            width: 260,
            autoFocus: true,
            matchContains: true,
            selectFirst: false
        });
    });
    $('#search').live('click', function() {
        for_loading();
        $.ajax({
            url: BASE_URL + "po/search_result",
            type: 'GET',
            data: {
                po: $('#po_no').val(),
                style: $('#style').val(),
                supplier: $('#supplier').val(),
                supplier_name: $('#supplier').find('option:selected').text(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val()
            },
            success: function(result) {
                for_response();
                $('#result_div').html(result);
            }
        });
    });
</script>
<script>
    function checkAvailable(term) {
        var product_data = [<?php echo implode(',', $model_numbers_json); ?>];
        var length = term.length,
            chck = false,
            term = term.toLowerCase();
        for (var i = 0, z = product_data.length; i < z; i++)
            if (product_data[i].substring(0, length).toLowerCase() === term)
                return true;
        return false;
    }
</script>
<script>
    // $(document).ready(function () {
    $('body').on('keydown', '#add_quotation input.model_no', function(e) {
        var _this = $(this);
        // var product_data = [<?php echo implode(',', $model_numbers_json); ?>];
        $('#add_quotation tbody tr input.model_no').autocomplete({
            source: function(request, response) {
                var val = _this.closest('tr input.model_no').val();
                cat_id = $('#firm').val();
                cust_id = $('#customer_id').val();
                var product_data = [];
                if ($.trim(val).length != 0) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            firm_id: cat_id,
                            pro: val
                        },
                        async: false,
                        url: '<?php echo base_url(); ?>quotation/get_product_by_frim_id',
                        success: function(data) {
                            product_data = JSON.parse(data);
                        }
                    });
                }
                // filter array to only entries you want to display limited to 10
                var outputArray = new Array();
                leng = product_data.length;
                for (var i = 0; i < leng; i++) {
                    //if (product_data[i].value.toLowerCase().match(request.term.toLowerCase())) {
                    outputArray.push(product_data[i]);
                    // }
                }
                response(outputArray.slice(0, 10));
            },
            //  position: {collision: "flip"},
            minLength: 0,
            autoFocus: true,
            select: function(event, ui) {
                this_val = $(this);
                product = ui.item.value;
                $(this).val(product);
                model_number_id = ui.item.id;
                cat_it = ui.item.category_id;
                $.ajax({
                    type: 'POST',
                    data: {
                        model_number_id: model_number_id,
                        c_id: cust_id,
                        firm_id: $('#firm').val(),
                        cat_it: cat_it
                    },
                    url: "<?php echo $this->config->item('base_url'); ?>" + "quotation/get_product/",
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result != null && result.length > 0) {
                            if (result[0].quantity != null) {
                                this_val.closest('tr').find('.stock_qty').html(result[0].quantity);
                            } else {
                                this_val.closest('tr').find('.stock_qty').html('0');
                            }
                            this_val.closest('tr').find('.unit').val(result[0].unit);
                            this_val.closest('tr').find('.brand_id').val(result[0].brand_id);
                            this_val.closest('tr').find('.cat_id').val(result[0].category_id);
                            // this_val.closest('tr').find('.discount').val(result[0].discount);
                            if (result[0].selling_price != '') {
                                this_val.closest('tr').find('.selling_price').val(result[0].selling_price);
                            } else {
                                this_val.closest('tr').find('.selling_price').val('0');
                            }
                            this_val.closest('tr').find('.type').val(result[0].type);
                            this_val.closest('tr').find('.product_id').val(result[0].id);
                            this_val.closest('tr').find('.model_no').val(result[0].product_name);
                            this_val.closest('tr').find('.model_no_extra').val(result[0].model_no);
                            this_val.closest('tr').find('.product_description').val(result[0].product_description);
                            if ($('#gst_type').val() != '') {
                                if ($('#gst_type').val() == 31) {
                                    this_val.closest('tr').find('.pertax').val(result[0].cgst);
                                    this_val.closest('tr').find('.gst').val(result[0].sgst);
                                } else {
                                    this_val.closest('tr').find('.pertax').val(result[0].cgst);
                                    this_val.closest('tr').find('.igst').val(result[0].igst);
                                }
                            }
                            calculate_function();
                            var name = $('#app_table tr:last').find('.model_no').val();
                            if (name != '')
                                $('#add_group').trigger('click');
                            //                            this_val.closest('tr').find('.qty').val(1);
                            //                            this_val.closest('tr').find('.qty').trigger('keyup');
                            this_val.closest('tr').find('.qty').focus();
                            this_val.closest('tr').find('.qty').attr('tabindex', '');
                            this_val.closest('tr').find('.percost').attr('tabindex', '');
                        }
                    }
                });
            }
        });
    });
    // });

    $(document).ready(function() {
        $('body').on('keydown', 'input.model_no_extra', function(e) {
            //  var product_data = [<?php echo implode(',', $model_numbers_extra); ?>];
            var product_data = [];
            cat_id = $('#firm').val();
            cust_id = $('#customer_id').val();
            $.ajax({
                type: 'POST',
                data: {
                    firm_id: cat_id
                },
                url: '<?php echo base_url(); ?>quotation/get_model_no_by_frim_id',
                async: false,
                success: function(data) {
                    product_data = JSON.parse(data);
                }
            });
            $(".model_no_extra").autocomplete({
                source: function(request, response) {
                    // filter array to only entries you want to display limited to 10
                    var outputArray = new Array();
                    for (var i = 0; i < product_data.length; i++) {
                        if (product_data[i].value.toLowerCase().match(request.term.toLowerCase())) {
                            outputArray.push(product_data[i]);
                        }
                    }
                    response(outputArray.slice(0, 10));
                },
                minLength: 0,
                autoFill: false,
                select: function(event, ui) {
                    this_val = $(this);
                    product = ui.item.value;
                    $(this).val(product);
                    model_number_id = ui.item.id;
                    $.ajax({
                        type: 'POST',
                        data: {
                            model_number_id: model_number_id,
                            c_id: cust_id
                        },
                        url: "<?php echo $this->config->item('base_url'); ?>" + "quotation/get_product/" + cat_id,
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (result != null && result.length > 0) {
                                if (result[0].quantity != null) {
                                    this_val.closest('tr').find('.stock_qty').html(result[0].quantity);
                                } else {
                                    this_val.closest('tr').find('.stock_qty').html('0');
                                }
                                this_val.closest('tr').find('.unit').val(result[0].unit);
                                this_val.closest('tr').find('.brand_id').val(result[0].brand_id);
                                this_val.closest('tr').find('.cat_id').val(result[0].category_id);
                                //this_val.closest('tr').find('.discount').val(result[0].discount);
                                if (result[0].selling_price != '') {
                                    this_val.closest('tr').find('.selling_price').val(result[0].selling_price);
                                } else {
                                    this_val.closest('tr').find('.selling_price').val('0');
                                }
                                this_val.closest('tr').find('.type').val(result[0].type);
                                this_val.closest('tr').find('.product_id').val(result[0].id);
                                this_val.closest('tr').find('.model_no').val(result[0].product_name);
                                this_val.closest('tr').find('.model_no_extra').val(result[0].model_no);
                                this_val.closest('tr').find('.product_description').val(result[0].product_description);
                                if ($('#gst_type').val() != '') {
                                    if ($('#gst_type').val() == 31) {
                                        this_val.closest('tr').find('.pertax').val(result[0].cgst);
                                        this_val.closest('tr').find('.gst').val(result[0].sgst);
                                    } else {
                                        this_val.closest('tr').find('.pertax').val(result[0].cgst);
                                        this_val.closest('tr').find('.igst').val(result[0].igst);
                                    }
                                }
                                calculate_function();
                                var name = $('#app_table tr:last').find('.model_no').val();
                                if (name != '')
                                    $('#add_group').trigger('click');
                            }
                        }
                    });
                }
            });
        });
    });
    $("#model_no_ser").live('keyup', function() {
        var this_ = $(this);
        // cat_id = this_.closest('tr').find('.cat_id').val();
        cat_id = $('#firm').val();
        $.ajax({
            type: "GET",
            url: "<?php echo $this->config->item('base_url'); ?>" + "quotation/get_service/" + cat_id,
            data: 's=' + $(this).val(),
            success: function(datas) {
                if (datas) {
                    this_.closest('tr').find(".suggesstion-box1").show();
                    this_.closest('tr').find(".suggesstion-box1").html(datas);
                } else {
                    this_.closest('tr').find(".suggesstion-box1").hide();
                    $(this).val('NO DATA FOUND');
                }
            }
        });
    });
    $(document).ready(function() {
        $('body').click(function() {
            $(this).closest('tr').find(".suggesstion-box1").hide();
        });
    });
    $('.pro_class').live('click', function() {
        $(this).closest('tr').find('.cat_id').val($(this).attr('pro_cat'));
        $(this).closest('tr').find('.pertax').val($(this).attr('pro_cgst'));
        $(this).closest('tr').find('.gst').val($(this).attr('pro_sgst'));
        // $(this).closest('tr').find('.discount').val($(this).attr('pro_discount'));
        $(this).closest('tr').find('.selling_price').val($(this).attr('pro_sell'));
        $(this).closest('tr').find('.type').val($(this).attr('pro_type'));
        $(this).closest('tr').find('.product_id').val($(this).attr('pro_id'));
        $(this).closest('tr').find('.model_no').val($(this).attr('pro_name'));
        $(this).closest('tr').find('.product_description').val($(this).attr('pro_name') + "  " + $(this).attr('pro_description'));
        $(this).closest('tr').find('.product_image').attr('src', "<?php echo $this->config->item("base_url") . 'attachement/product/' ?>" + $(this).attr('pro_image'));
        $(this).closest('tr').find(".suggesstion-box1").hide();
        calculate_function();
    });
    $('.ser_class').live('click', function() {
        $(this).closest('tr').find('.cat_id').val($(this).attr('ser_cat'));
        $(this).closest('tr').find('.pertax').val($(this).attr('ser_cgst'));
        $(this).closest('tr').find('.gst').val($(this).attr('ser_sgst'));
        // $(this).closest('tr').find('.discount').val($(this).attr('pro_discount'));
        $(this).closest('tr').find('.selling_price').val($(this).attr('ser_sell'));
        $(this).closest('tr').find('.type_ser').val($(this).attr('ser_type'));
        $(this).closest('tr').find('.product_id').val($(this).attr('ser_id'));
        $(this).closest('tr').find('.model_no_ser').val($(this).attr('ser_name'));
        $(this).closest('tr').find('.product_description').val($(this).attr('ser_name') + "  " + $(this).attr('ser_description'));
        $(this).closest('tr').find('.product_image').attr('src', "<?php echo $this->config->item("base_url") . 'attachement/product/' ?>" + $(this).attr('ser_image'));
        $(this).closest('tr').find(".suggesstion-box1").hide();
        calculate_function();
    });

    function Firm(val, cat) {
        if (val != '') {
            $.ajax({
                type: 'POST',
                data: {
                    firm_id: val
                },
                url: '<?php echo base_url(); ?>masters/products/get_category_by_frim_id',
                success: function(data) {
                    var result = JSON.parse(data);
                    if (result != null && result.length > 0) {
                        option_text = '<option value="">Select Category</option>';
                        $.each(result, function(key, value) {
                            option_text += '<option value="' + value.cat_id + '">' + value.categoryName + '</option>';
                        });
                        $('.cat_id').html(option_text);
                        $('.cat_id,.model_no,.model_no_extra').val('');
                        $('.model_no').removeAttr('readonly', 'readonly');
                        if (cat != '') {
                            $('.cat_id').val(cat);
                        }
                    } else {
                        $('.cat_id,.model_no,.model_no_extra').val('');
                        $('.model_no').attr('readonly', 'readonly');
                    }
                }
            });
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                data: {
                    firm_id: val
                },
                url: '<?php echo base_url(); ?>quotation/get_prefix_by_frim_id/',
                success: function(data1) {
                    $('#grn_no').val(data1[0]['prefix']);
                    $('#sales_id').val(data1[0]['prefix']);
                    $('#invoice_id').val(data1[0]['prefix']);
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            type: data1[0]['prefix'],
                            code: 'TT'
                        },
                        url: '<?php echo base_url(); ?>quotation/get_increment_id/',
                        success: function(data2) {
                            $('#grn_no').val(data2);
                            $('#sales_id').val(data2);
                            $('#invoice_id').val(data2);
                            //console.log(data2);
                            var increment_id = $('#grn_no').val().split("/");
                            var increment_id1 = $('#sales_id').val().split("/");
                            var increment_id2 = $('#invoice_id').val().split("/");
                            final_id = data1[0]['prefix'] + '-' + increment_id[1] + '' + increment_id[2];
                            sales_id = 'SL-' + data1[0]['prefix'] + '-' + increment_id1[1] + '-' + increment_id1[2];
                            inv_id = 'INV-' + data1[0]['prefix'] + '-' + increment_id2[1] + '-' + increment_id2[2];
                            $('#sales_id').val(sales_id);
                            $('#grn_no').val(final_id);
                            $('#grn_no_2').text(final_id);
                            $('#invoice_id').val(inv_id);
                        }
                    });
                }
            });
            $.ajax({
                type: 'POST',
                async: false,
                //   dataType: 'JSON',
                data: {
                    firm_id: val
                },
                url: '<?php echo base_url(); ?>quotation/get_reference_group_by_frim_id/',
                success: function(data1) {
                    // $('#ref_class').html('');
                    var result = JSON.parse(data1);
                    if (result != null && result.length > 0) {
                        option_text = '<option value="">Select</option>';
                        $.each(result, function(key, value) {
                            option_text += '<option value="' + value.user_id + '">' + value.user_name + '</option>';
                        });
                        $('#ref_class').html(option_text);
                    } else {
                        $('#ref_class').html('');
                    }
                }
            });
            $.ajax({
                type: 'POST',
                async: false,
                // dataType: 'JSON',
                data: {
                    firm_id: val
                },
                url: '<?php echo base_url(); ?>quotation/get_sales_man_by_frim_id/',
                success: function(data1) {
                    // $('#sales_man').html('');
                    var result = JSON.parse(data1);
                    if (result != null && result.length > 0) {
                        option_text = '<option value="">Select</option>';
                        $.each(result, function(key, value) {
                            option_text += '<option value="' + value.id + '">' + value.sales_man_name + '</option>';
                        });
                        $('#sales_man').html(option_text);
                    } else {
                        $('#sales_man').html('');
                    }
                }
            });
        } else {
            $('.cat_id,.model_no,.model_no_extra').val('');
            $('.model_no').attr('readonly', 'readonly');
        }
    }
    // $('.qty').live('keyup', function() {
    //     var pro_qty = $(this).val();
    //     var stock_qty = $(this).closest('tr').find('.stock_qty').text();
    //     if (Number(pro_qty) > Number(stock_qty)) {
    //         $(this).closest('td').find('.error_msg').text('Invalid quantity').css('display', 'inline-block');
    //     } else {
    //         $(this).closest('td').find('.error_msg').text("");
    //     }
    // });
    $(window).bind('scannerDetectionReceive', function(event, data) {
        target_ele = event.target.activeElement;
    });
    /* $(document).scannerDetection({
     timeBeforeScanTest: 200, // wait for the next character for upto 200ms
     startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
     endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
     avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
     onComplete: function (barcode, qty) {
     $(target_ele).val('');
     cust_id = $('#customer_id').val();
     val = $('#app_table').find('.product_id').val();
     if (val == '') {
     $('#app_table').find('tr:first').remove();
     }
     barcode = barcode;
     if (barcode != '' && cust_id != '') {
     //alert(barcode);
     $.ajax({
     type: 'POST',
     async: false,
     data: {barcode: barcode, cust_id: cust_id},
     url: '<?php echo base_url(); ?>sales/get_all_products/',
     success: function (data) {
     result = JSON.parse(data);
     if (result != null && result.length > 0) {
     $.each(result, function (key, value) {
     var prod_array = new Array();
     $(".product_id").each(function () {
     prod_array.push($(this).val());
     });
     //var disabled = ''
     if (jQuery.inArray(value.id, prod_array) > -1 && prod_array.length > 0)
     {
     qty_val = $('#app_table .tr_' + value.id).find('.qty').val();
     var add = Number(qty_val) + Number(1);
     $('#app_table .tr_' + value.id).find('.qty').val(add);
     calculate_function();
     } else {
     $('#firm').val(result[0]['firm_id']);
     var tableBody = $(".static").find('tr').clone();
     $(tableBody).closest('tr').find('select,.model_no,.model_no_ser,.percost,.qty').addClass('required');
     $('#app_table').append(tableBody);
     $(tableBody).closest('tr').find('.model_no').val(result[0]['product_name']);
     if (result[0]['product_image'] == '')
     $(tableBody).closest('tr').find('.product_image').attr('src', "<?php echo $this->config->item("base_url") . 'attachement/product/' ?>" + result[0]['product_image']);
     else
     $(tableBody).closest('tr').find('.product_image').attr('src', "<?php echo $this->config->item("base_url") . 'attachement/product/no-img.gif' ?>");
     $(tableBody).closest('tr').find('.product_description').val(result[0]['product_description']);
     $(tableBody).closest('tr').find('.qty').val('1');
     $(tableBody).closest('tr').addClass('tr_' + result[0]['id']);
     $(tableBody).closest('tr').find('.product_id').val(result[0]['id']);
     $(tableBody).closest('tr').find('.selling_price').val(result[0]['selling_price']);
     $(tableBody).closest('tr').find('.type').val(result[0]['type']);
     $(tableBody).closest('tr').find('.brand_id').val(result[0]['brand_id']);
     $(tableBody).closest('tr').find('.unit').val(result[0]['unit']);
     $(tableBody).closest('tr').find('.cat_id').val(result[0]['category_id']);
     $(tableBody).closest('tr').find('.model_no').val(result[0]['product_name']);
     $(tableBody).closest('tr').find('.model_no_extra').val(result[0]['model_no']);
     if ($('#gst_type').val() != '')
     {
     if ($('#gst_type').val() == 31)
     {
     $(tableBody).closest('tr').find('.pertax').val(result[0]['cgst']);
     $(tableBody).closest('tr').find('.gst').val(result[0]['sgst']);
     } else {
     $(tableBody).closest('tr').find('.pertax').val(result[0]['cgst']);
     $(tableBody).closest('tr').find('.igst').val(result[0]['igst']);
     }
     }
     calculate_function();
     // Firm(result[0]['firm_id'], result[0]['category_id']);
     }
     });
     } else {
     sweetAlert("Error...", "This Product is not available!", "error");
     return false;
     }
     }
     });
     } else {
     sweetAlert("Error...", "This Product is not available!", "error");
     return false;
     }
     }
     });*/
    $('input').on('keypress', function() {
        formHasChanged = true;
    });
    $('select').on('click', function() {
        formHasChanged = true;
    });
    $(window).bind('beforeunload', function() {
        if (formHasChanged && !submitted) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).keydown(function(e) {
        var keycode = e.keyCode;
        if (keycode == 113) {
            $('#add_group').trigger('click');
        }
    });
</script>