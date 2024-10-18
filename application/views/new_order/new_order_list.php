<?php $this->load->view('success_false_notify'); ?>
<?php $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type']; ?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
        .modal-body {
            max-height: calc(100% - 120px);
            overflow-y: scroll;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order List
            <?php
            $isView = $this->app_model->have_access_role(ORDER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(ORDER_MODULE_ID, "add"); 
            $isShowParty = $this->app_model->have_access_role(ORDER_MODULE_ID, "show party name"); ?>
            <?php if($isAdd){ ?>
                <a href="<?=base_url('new_order/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Order</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php if($isView) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <div class="col-md-2">
                                            <label>Department</label>
                                            <select name="department_id" id="department_id" class="form-control select2" ></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Category</label>
                                            <select name="line_items_data[category_id]" class="form-control category_id" id="category_id">
                                                <option value="0"> All </option>
                                                <?php foreach ($category as $value) { ?>
                                                    <option value="<?= $value->category_id; ?>"><?= $value->category_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Item</label>
                                            <select id="item_id" name="item_id" class="form-control">
                                                <option value=""> All </option>
                                                <?php foreach ($items as $value) { ?>
                                                    <option value="<?= $value->item_id; ?>"><?= $value->item_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="remark">Status</label>    
                                            <select name="order_status_id" id="order_status_id" class="form-control order_status_id"></select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="order_type">Type</label>
                                            <select name="order_type" id="order_type" class="form-control select2">
                                                <option value="0"> All </option>
                                                <option value="1" <?= $type == 1 ? 'selected' : ''; ?>>Order</option>
                                                <option value="2" <?= $type == 2 ? 'selected' : ''; ?>>Inquiry</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div><br/>
                                        <div class="col-md-2">
                                            <label for="from_date">From Date</label>
                                            <input type="text" name="from_date" id="from_date" class="form-control " value="<?=date("01-m-Y")?>">
                                            <label style="font-size: 10px">Everything From Start <input type="checkbox" name="everything_from_start" id="everything_from_start" checked="true" ></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="to_date">To Date</label>
                                            <input type="text" name="to_date" id="to_date" class="form-control " value="<?=date("d-m-Y")?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Supplier</label>
                                            <select name="order_supplier_id" id="order_supplier_id" class="form-control select2" ></select>
                                        </div>
                                        <div class="clearfix"></div><br />
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Party Name</th>                                                    
                                                    <th>Department Name</th>
                                                    <th>Order No</th>
                                                    <th>Order Date</th>
                                                    <th>Delivery Date</th>
                                                    <th>Real Delivery Date</th>
                                                    <th>Supplier</th>
                                                    <th>Supplier Delivery Date</th>
                                                    <th>Total Weight</th>
                                                    <th>Total PCS</th>
                                                    <th>Status</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                            <tr>
                                                <th style="text-align:Center">Total:</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_item_id" value="-1" >
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:80% !important; max-height: 650px; overflow-y: scroll;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Lot Item List</h4>
            </div>
            <div class="modal-body">
                <table id="item_table" class="table row-border table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Order Item No</th>
                            <th>Category Name</th>
                            <th>Item Name</th>
                            <th>Design No</th>
                            <th>Die No</th>
                            <th>Tunch</th>
                            <th>Weight</th>
                            <th>Pcs</th>
                            <th>Size</th>
                            <th>Length</th>
                            <th>Hook Style</th>
                            <th>Remark</th>
                            <th>Status</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body edit-content">
                <img id="doc_img_src" alt="No Image Found" class="img-responsive" height='300px' width='600px'>
            </div>
        </div>
    </div>
</div>
<script>
    var table;
    $(document).ready(function () {
        department_id_data = '<?php echo $department_ids; ?>';
        department_ids_data = JSON.parse(department_id_data);
        default_dep = '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']; ?>';
        if(default_dep != ''){
            $.each(department_ids_data, function (index, value) {
                if(value == default_dep){
                    setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");
                }
            });
        }
        $('#category_id').select2();
        $('#item_id').select2();
        $('#order_type').select2();
        initAjaxSelect2($("#department_id"), "<?= base_url('app/order_department_select2_source') ?>");
        initAjaxSelect2($("#order_status_id"), "<?= base_url('app/order_status_select2_source') ?>");
        setSelect2Value($("#order_status_id"), "<?= base_url('app/set_order_status_select2_val_by_id/' . PENDING_STATUS) ?>");

        initAjaxSelect2($("#order_supplier_id"), "<?= base_url('app/supplier_filter_select2_source') ?>");

//        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        $('#ajax-loader').show();
        table = $('#lot_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('new_order/new_order_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.department_id = $('#department_id').val();
                    d.item_id = $('#item_id').val();
                    d.category_id = $('#category_id').val();
                    d.order_status_id = $('#order_status_id').val();
                    d.order_type = $('#order_type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.supplier_id = $('#order_supplier_id').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [9,10],
            }],
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
             
            pageTotal = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 9 ).footer() ).html(
                pageTotal.toFixed(3)
            );
    
            pageTotal1 = api
                .column(10,{ page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                // Update footer
                $( api.column(10).footer() ).html(
                    pageTotal1
                );
            }
        });
        
        item_table = $('#item_table').DataTable({
            "serverSide": true,
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('new_order/order_lot_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.order_id = $('#clicked_item_id').val()
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                   "className": "text-nowrap",
                   "targets": [0],
               },{
                   "className": "dt-right",
                   "targets": [5, 6, 7, 8, 9],
               }]
        });
        
        <?php if(!$isShowParty){ ?>
            table.columns( [1] ).visible( false, false );
        <?php } ?>
        
        jQuery('#item_table').wrap('<div class="dataTables_scroll" />');
        
        $(document).on('change', '#everything_from_start', function(){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#category_id', function(){
            var category_id = $('#category_id').val();
            if(category_id != ' ' && category_id != null){
                $.ajax({
                    url:"<?php echo base_url('new_order/get_item_name'); ?>/" + category_id,
                    type:'GET',
                    data:'',
                    success: function(response){
                        var json = $.parseJSON(response);
//                        console.log(json);
                        var row_inc = 1;
                        var option = '<option value="">-- All --</option>';
                        if(json.items != false){
                            $.each(json.items ,function(index, value){
                                option += '<option value="' + value.item_id + '">' + value.item_name + '</option>';
                            });
                        }
                        $('#item_id').html(option);
                        row_inc++;
                    }
                });
            }
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#department_id', function(){
            var department_id = $('#department_id').val();
            if(department_id == '' || department_id === null){
                $('#select2-department_id-container .select2-selection__placeholder').html(' All ');
            }
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#order_supplier_id', function(){
            var supplier_id = $('#order_supplier_id').val();
            if(supplier_id == '' || supplier_id === null){
                $('#select2-order_supplier_id-container .select2-selection__placeholder').html(' All ');
            }
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('change', '#order_status_id', function(){
            var order_status_id = $('#order_status_id').val();
            if(order_status_id == '' || order_status_id === null){
                $('#select2-order_status_id-container .select2-selection__placeholder').html(' All ');
            }
            $('#ajax-loader').show();
            table.draw();
        });
        
        $("#from_date").datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            autoclose: true
        }).on('changeDate', function(e){
            $('#ajax-loader').show();
            table.draw();
        });

        $("#to_date").datepicker({
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
            autoclose: true
        }).on('changeDate', function(e){
            $('#ajax-loader').show();
            table.draw();
        });

        $(document).on('change', '#item_id, #order_type', function(){
            $('#ajax-loader').show();
            table.draw();
        });
        
        $(document).on('click', '.item_row', function () {
            $('#clicked_item_id').val($(this).attr('data-order_id'));
            $('#ajax-loader').show();
            item_table.draw();
            $('#myModal').modal('show');
        });
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            $("#doc_img_src").attr('src', src);
            $('#edit-modal').modal('show');
        });
    
        $(document).on("click", ".delete_order", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Orders. This Orders has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            $('#ajax-loader').show();
                            table.draw();
                            show_notify('Orders Deleted Successfully!', true);
                        }
                    }
                });
            }
        });
        
        $(document).on("click", ".change_order_type", function () {
            $.ajax({
                url: $(this).data('href'),
                type: "POST",
                data: '',
                success: function (data) {
                    $('#ajax-loader').show();
                    table.draw();
                    show_notify('Inquiry To Order Convert Successfully!', true);
                }
            });
        });
    });
</script>
