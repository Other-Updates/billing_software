<?php $theme_path = $this->config->item('theme_locations') . $this->config->item('active_template'); ?>
<script src="<?= $theme_path; ?>/js/jquery-1.8.2.js"></script>
<script src="<?= $theme_path; ?>/js/jquery-ui-my-1.10.3.min.js"></script>
<script type='text/javascript' src='<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?= $theme_path; ?>/js/auto_com/jquery.autocomplete.css" />
<script type='text/javascript' src='<?= $theme_path; ?>/js/jquery.table2excel.min.js'></script>
<style>
    .bg-red {background-color: #dd4b39 !important;}
    .bg-green { background-color:#09a20e !important;}
    .bg-yellow{background-color:orange !important;}
    .ui-datepicker td.ui-datepicker-today a { background:#999999;}
    ul.tabs{
        margin: 0px;
        padding: 0px;
        list-style: none;
    }
    ul.tabs li{
        background: none;
        color: #222;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
    }


    ul.tabs li.current{
        background: #777;
        color: #fcfcfc;
        border-radius:2em;
    }


    .tab-content{
        display: none;
        background: #ededed;
        padding: 15px;
    }

    .tab-content.current{
        display: inherit;
    }
    .bg-red {
        background-color: #dd4b39 !important;
    }
    .bg-green {
        background-color:#09a20e !important;
    }
    .bg-yellow
    {
        background-color:orange !important;
    }
    .ui-datepicker td.ui-datepicker-today a {
        background:#999999;
    }
    .btn-group > .btn, .btn-group-vertical > .btn { border-width: 0px!important;}
    table tr td:nth-child(9) { text-align:right; }
    table tr td:nth-child(12) { text-align:center; }
    #myDIVSHOW {
        display:none;
    }@media print {		table tr td:last-child {		border:0px solid !important; dispaly:none !important; border-left:0px solid !important;}		table tr th:last-child {		border:0px solid !important; dispaly:none !important; border-left:0px solid !important;}	}
</style>
<?php
$this->load->model('admin/admin_model');
$data['company_details'] = $this->admin_model->get_company_details();
?>
<div class="print_header">
    <table width="100%">
        <tr>
            <td width="15%" style="vertical-align:middle;">
                <div class="print_header_logo" ><img src="<?= $theme_path; ?>/images/logo.png" /></div>
            </td>
            <td width="85%">
                <div class="print_header_tit" >
                    <h3><?= $this->config->item("company_name") ?></h3>
                    <p>
                        <?= $data['company_details'][0]['address1'] ?>,
                        <?= $data['company_details'][0]['address2'] ?>,
                    </p>
                    <p></p>
                    <p><?= $data['company_details'][0]['city'] ?>-
                        <?= $data['company_details'][0]['pin'] ?>,
                        <?= $data['company_details'][0]['state'] ?></p>
                    <p></p>
                    <p>Ph:
                        <?= $data['company_details'][0]['phone_no'] ?>, Email:
                        <?= $data['company_details'][0]['email'] ?>
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="mainpanel">

    <div class="media mt--20">
        <div class="row">
            <div class="col-md-10">
                <h4>Customer Based List</h4>
            </div>
            <div class="col-md-2">
                <input type="button" id="show" value="Advance Search" class="btn btn-info clor" style="float:right; margin-top:9px;"></input>
                <!--<button id="hide" class="btn btn-danger clor">hide</button>-->	</div>
        </div>
    </div>
    <ul class="tabs">
        <li class="tab-link current" data-tab="tabs-1">Customer Invoice Report</li>
        <li class="tab-link" data-tab="tabs-2">Customer Type Chart view</li>
    </ul>
    <div id="tabs-1" class="tab-content current">
        <div class="panel-body mt--40" id="myDIVSHOW">
            <div class="row search_table_hide search-area">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Customer</label>
                        <select id='customer'  class="form-control" >
                            <option>Select</option>
                            <?php
                            if (isset($all_receipt) && !empty($all_receipt)) {
                                foreach ($all_receipt as $val) {
                                    ?>
                                    <option value='<?= $val['customer'] ?>'><?= $val['store_name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Invoice Id</label>
                        <select id='inv_id' class="form-control">
                            <option>Select</option>
                            <?php
                            if (isset($all_receipt) && !empty($all_receipt)) {
                                foreach ($all_receipt as $val) {
                                    ?>
                                    <option value='<?= $val['inv_id'] ?>'><?= $val['inv_id'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Product</label>
                        <select id='product'  class="form-control">
                            <option>Select</option>
                            <?php
                            if (isset($all_product) && !empty($all_product)) {
                                foreach ($all_product as $val) {
                                    ?>
                                    <option value='<?= $val['product_id'] ?>'><?= $val['product_name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Sales Man</label>
                        <select id='sales_man'  class="form-control" >
                            <option>Select</option>
                <?php
                /* if (isset($sales_man_list) && !empty($sales_man_list)) {
                  foreach ($sales_man_list as $val) {
                  ?>
                  <option value='<?= $val['id'] ?>'><?= $val['sales_man_name'] ?></option>
                  <?php
                  }
                  } */
                ?>
                        </select>
                    </div>
                </div>-->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">From Date</label>
                        <input type="text" id='from_date'  class="form-control datepicker" name="inv_date" placeholder="dd-mm-yyyy" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">To Date</label>
                        <input type="text"  id='to_date' class="form-control datepicker" name="inv_date" placeholder="dd-mm-yyyy" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Overdue / Advance</label>
                        <select name="overdue" id="overdue" class="form-control">
                            <option value="">Select</option>
                            <option value="1">Credit Days</option>
                            <option value="2">Credit Limit</option>
                            <option value="3">Advance</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mcenter">
                        <label class="control-label col-md-12 mnone">&nbsp;</label>
                        <a id='search' class="btn btn-success  mtop4"><span class="glyphicon glyphicon-search "></span> Search</a>
                        <a class="btn btn-danger1 mtop4" id="clear"><span class="fa fa-close"></span> Clear</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="contentpanel">
            <div class="panel-body mt-top5">
                <div class="">
                    <table id="basicTable_call_back" class="table table-striped table-bordered responsive dataTable no-footer dtr-inline result_div
                           cusinvamt-right cusadvamt-right cuspaid0amt-right cusdisamt-right cusbalance-right cusduedate-cntr cusstatus-cntr">
                        <thead>
                            <tr>
                                <th class="action-btn-align">S.No</th>
                                <th class="action-btn-align">Customer Name</th>
                                <th class='action-btn-align'>Inv #</th>
                                <th class="action-btn-align">Inv Amt</th>
                                <th class="action-btn-align">Advance Amt</th>
                                <th class="action-btn-align">Paid Amt</th>
                                <th class="action-btn-align">Return Amt</th>
                                <th class="action-btn-align">Discount Amt</th>
                                <th class="action-btn-align">Balance</th>
                                <th class="action-btn-align">Inv Date</th>
                                <th class="action-btn-align">Paid Date</th>
                                <th class=" action-btn-align">Payment Status</th>
                                <th class="hide_class action-btn-align">Action</th>
                            </tr>
                        </thead>

                        <tbody id="result_data">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text_right total-bg"><?= number_format($inv, 2, '.', ',') ?></td>
                                <td class="text_right total-bg"><?= number_format($advance, 2, '.', ',') ?></td>
                                <td class="text_right total-bg"><?= number_format($paid, 2, '.', ',') ?></td>
                                <td class="text_right total-bg"></td>
                                <td class="text_right total-bg"></td>
                                <td class="text_right total-bg"><?= number_format($bal, 2, '.', ',') ?></td>
                                <td class=""></td>
                                <td class=""></td>
                                <td class=""></td>
                                <td class="hide_class"></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
                <div class="action-btn-align mb-10">
                    <button class="btn btn-defaultprint6 print_btn"><span class="glyphicon glyphicon-print"></span> Print</button>
                    <!--<button class="btn btn-success excel_btn1" ><span class="glyphicon glyphicon-print"></span> Excel</button>-->
                    <div class="btn-group">
                        <button type="button" class=" btn btn-success dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-print"></span> Excel
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" class="excel_btn1">Current Entries</a></li>
                            <li><a href="#" id="excel-prt">Entire Entries</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div id="tabs-2" class="tab-content">
        <div class="contentpanel">
            <div class="panel-body">
                <div id='result_div' class="mscroll">
                    <b>Chart View Of Local and Non-Local Customers</b>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="pie_chart_div" class="pcenter"></div>
                        </div>
                        <div class="col-md-6 pwid50">
                            <div id="bar_chart_div" class="pcenter"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="action-btn-align mb-10">
                    <button class="btn btn-defaultprint6 print_btn"><span class="glyphicon glyphicon-print"></span> Print</button>
                    <button class="btn btn-success excel_btn"><span class="glyphicon glyphicon-print"></span> Excel</button>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    $('.print_btn').click(function () {
        window.print();
    });
    $('#clear').live('click', function ()
    {
        window.location.reload();
    });

</script>
</div><!-- contentpanel -->

</div><!-- mainpanel -->
<div id="export_excel"></div>
<script type="text/javascript">


    $(document).ready(function () {
        $('#customer').select2();
        $('#inv_id').select2();
        $('#product').select2();
        jQuery('.datepicker').datepicker();
    });
    $(document).ready(function () {
        $("#po_no").autocomplete(BASE_URL + "gen/get_po_list", {
            width: 260,
            autoFocus: true,
            matchContains: true,
            selectFirst: false
        });
    });
    $('#search').live('click', function () {
        for_loading();
        $.ajax({
            url: BASE_URL + "report/customer_invoice_search_result",
            type: 'POST',
            data: {
                inv_id: $('#inv_id').val(),
                customer: $('#customer').val(),
                product: $('#product').val(),
                sales_man: $('#sales_man').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                overdue: $('#overdue').val(),
                length: 50
            },
            success: function (result) {
                for_response();
                $('#result_data').html('');
                var table = $('#basicTable_call_back').DataTable();
                table.destroy();
                $('#result_data').html(result);
                datatable();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var table;
        //datatables
        table = jQuery('#basicTable_call_back').DataTable({
            "lengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]],
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "retrieve": true,
            "order": [], //Initial no order.
            //dom: 'Bfrtip',
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('report/customer_based_ajaxList/'); ?>",
                "type": "POST",
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0, 11], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                var cols = [3, 4, 5, 6, 7, 8];
                for (x in cols) {
                    total = api.column(cols[x]).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total over this page
                    pageTotal = api.column(cols[x], {page: 'current'}).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Update footer
                    if (Math.floor(pageTotal) == pageTotal && $.isNumeric(pageTotal)) {
                        pageTotal = pageTotal;

                    } else {
                        pageTotal = pageTotal.toFixed(2);/* float */

                    }
                    $(api.column(cols[x]).footer()).html(pageTotal);
                }


            },
            responsive: true,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -2}
            ]
        });
        new $.fn.dataTable.FixedHeader(table);
        $("#yesin").live("click", function ()
        {

            var hidin = $(this).parent().parent().find('.id').val();
            // alert(hidin);
            $.ajax({
                url: BASE_URL + "Project_cost_model/quotation_delete",
                type: 'POST',
                data: {value1: hidin},
                success: function (result) {

                    window.location.reload(BASE_URL + "Project_cost_model/quotation_list");
                }
            });

        });

        $('.modal').css("display", "none");
        $('.fade').css("display", "none");

    });
</script>
<script>
    $('.excel_btn1').live('click', function () {
        $('#basicTable_call_back').find('.hide_class').remove();
        fnExcelReport2();
//        window.reload();
    });
</script>
<script>
    function datatable() {
        $('#basicTable_call_back').DataTable({
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                var cols = [3, 4, 5, 6, 7, 8];
                for (x in cols) {
                    total = api.column(cols[x]).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Total over this page
                    pageTotal = api.column(cols[x], {page: 'current'}).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Update footer
                    if (Math.floor(pageTotal) == pageTotal && $.isNumeric(pageTotal)) {
                        pageTotal = pageTotal;

                    } else {
                        pageTotal = pageTotal.toFixed(2);/* float */

                    }
                    $(api.column(cols[x]).footer()).html(pageTotal);
                }


            },
            responsive: true,
            columnDefs: [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 2, targets: -2}
            ]
        });
        new $.fn.dataTable.FixedHeader(table);
    }
    function fnExcelReport2()
    {
        /* var tab_text = "<table border='5px'><tr width='100px' bgcolor='#87AFC6'>";
         var textRange;
         var j = 0;
         tab = document.getElementById('basicTable_call_back'); // id of table
         for (j = 0; j < tab.rows.length; j++)
         {
         tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
         //tab_text=tab_text+"</tr>";
         }
         tab_text = tab_text + "</table>";
         tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
         tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
         tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
         var ua = window.navigator.userAgent;
         var msie = ua.indexOf("MSIE ");
         if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
         {
         txtArea1.document.open("txt/html", "replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
         } else                 //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
         return (sa); */
        var tab_text = "<table id='custom_export' border='5px'><tr width='100px' bgcolor='#87AFC6'>";
        var textRange;
        var j = 0;
        tab = document.getElementById('basicTable_call_back'); // id of table
        for (j = 0; j < tab.rows.length; j++)
        {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }
        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        $('#export_excel').show();
        $('#export_excel').html('').html(tab_text);
        $('#export_excel').hide();
        $("#custom_export").table2excel({
            exclude: ".noExl",
            name: "Customer Based Report",
            filename: "Customer Based Report",
            fileext: ".xls",
            exclude_img: false,
            exclude_links: false,
            exclude_inputs: false
        });

    }
</script>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages': ['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var jsonData = $.ajax({
            url: "<?php echo base_url() . 'masters/customers/customer_piechart' ?>",
            dataType: "json",
            async: false
        }).responseText;
        //alert(jsonData);
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
        chart.draw(data, {width: 600, height: 500});
    }

</script>
<script type="text/javascript">
    // Load the Visualization API and the line package.
    google.charts.load('current', {'packages': ['bar']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart1);

    function drawChart1() {
        var jsonData = $.ajax({
            url: "<?php echo base_url() . 'masters/customers/customer_barchart' ?>",
            dataType: "json",
            async: false
        }).responseText;
        //alert(jsonData);
        var data = new google.visualization.DataTable(jsonData);

        var chart = new google.charts.Bar(document.getElementById('bar_chart_div'));
        var options = {
            chart: {
                title: 'Customer Report',
                subtitle: 'Show Local and Non-local Customers of the Company'
            },
            width: 600,
            height: 500,
            axes: {
                x: {
                    0: {side: 'bottom'}
                }
            }

        };
        chart.draw(data, options);

    }
</script>
<script>
    $(document).ready(function () {

        $('ul.tabs li').click(function () {
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#" + tab_id).addClass('current');
        })

    });
    $('#excel-prt').on('click', function ()
    {
        var arr = [];
        arr.push({'inv_id': $('#inv_id').val()});
        arr.push({'customer': $('#customer').val()});
        arr.push({'product': $('#product').val()});
        arr.push({'from': $('#from_date').val()});
        arr.push({'to': $('#to_date').val()});
        arr.push({'overdue': $('#overdue').val()});
        var arrStr = JSON.stringify(arr);
        window.location.replace('<?php echo $this->config->item('base_url') . 'report/customer_excel_report?search=' ?>' + arrStr);
    });
</script>
<script>
    $(document).ready(function () {
        $("#show").click(function () {
            $("#myDIVSHOW").toggle();
        });
    });
    $('#show').click(function () {
        var self = this;
        change(self);
    });
    function change(el) {
        if (el.value === "Advance Search")
            el.value = "Hide";
        else
            el.value = "Advance Search";
    }
</script>
<script src="<?= $theme_path; ?>/js/fixedheader/jquery.dataTables.min.js"></script>



