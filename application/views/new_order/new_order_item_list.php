<?php $this->load->view('success_false_notify'); ?>
<?php $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type']; ?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Order Item List
            <?php
            $isView = $this->app_model->have_access_role(ORDER_MODULE_ID, "view");
            $isShowParty = $this->app_model->have_access_role(ORDER_MODULE_ID, "show party name"); ?>

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
                                            <label>Date</label>
                                            <input type="text" name="date" id="datepicker1" class="from_date form-control" value="<?php echo isset($delivery_date) && !empty($delivery_date) ? date("d-m-Y", strtotime($delivery_date)) : date('d-m-Y');?>">
                                            <label style="font-size: 10px">Do Not Consider Date <input type="checkbox" name="everything_from_start" id="everything_from_start" checked="true" ></label>
                                        </div>
                                        <div class="col-md-3">
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
                                            <br /><button name="search" id="search" class="btn btn-primary btn-sm"><span class="fa fa-search-plus"></span> Search</button>
                                        </div>
                                        <div class="clearfix"></div>
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Party Name</th>
                                                    <th>Department Name</th>
                                                    <th>Image</th>
                                                    <th>Status</th>
                                                    <th>Order Item No</th>
                                                    <th class="text-nowrap" width="60px;">Order Date</th>
                                                    <th class="text-nowrap" width="60px;">Delivery Date</th>
                                                    <th>Category Name</th>
                                                    <th>Item Name</th>
                                                    <th>Design No</th>
                                                    <th>Die No</th>
                                                    <th>Tunch</th>
                                                    <th>Weight</th>
                                                    <th>Pcs</th>
                                                    <th>Total Weight</th>
                                                    <th>Size</th>
                                                    <th>Length</th>
                                                    <th>Hook Style</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
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
                                                    <th></th>
                                                    <th>Total</th>
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
    $(document).ready(function () {
        $('#category_id').select2();
        $('#item_id').select2();
        
        initAjaxSelect2($("#order_status_id"), "<?= base_url('app/order_status_select2_source') ?>");
        setSelect2Value($("#order_status_id"), "<?= base_url('app/set_order_status_select2_val_by_id/' . PENDING_STATUS) ?>");
//        initAjaxSelect2($("#item_id"), "<?= base_url('app/item_name_select2_source') ?>");
        $('#ajax-loader').show();
        table = $('#lot_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": true,
            "ordering":[1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('new_order/new_order_item_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.item_id = $('#item_id').val();
                    d.category_id = $('#category_id').val();
                    d.order_status_id = $('#order_status_id').val();
                    d.date = $('#datepicker1').val();
                    d.everything_from_start = $('input[name="everything_from_start"]').prop('checked');
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [3,10,11,12,13,14,15],
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

                // Total over this page
                total_pcs = api
                    .column( 14, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                total_weight = api
                    .column( 15, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 14 ).footer() ).html(
                    total_pcs
                );
                $( api.column( 15 ).footer() ).html(
                    total_weight.toFixed(3)
                );
            },
        });
        
        <?php if(!$isShowParty){ ?>
            table.columns( [1] ).visible( false, false );
        <?php } ?>
        
        $(document).on('change', '#category_id', function(){
            var category_id = $('#category_id').val();
            if(category_id != ' ' && category_id != null){
                $.ajax({
                    url:"<?php echo base_url('new_order/get_item_name'); ?>/" + category_id,
                    type:'GET',
                    data:'',
                    success: function(response){
                        var json = $.parseJSON(response);
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
//            table.draw();
        });
        
        $(document).on('change', '#order_status_id', function(){
            var order_status_id = $('#order_status_id').val();
            if(order_status_id == '' || order_status_id === null){
                $('#select2-order_status_id-container .select2-selection__placeholder').html(' All ');
            }
//            table.draw();
        });
        
        $(document).on('change', '#item_id', function(){
//            table.draw();
        });
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            $("#doc_img_src").attr('src', src);
            $('#edit-modal').modal('show');
        });

        $(document).on('click', '.change_item_status', function () {
            if (confirm('Are you sure, You Want to Change Status ?')) {
                let status = $(this).attr('status');
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: {status : status},
                    success: function (data) {
                        table.draw();
                        show_notify('Order Item status updated successfully!', true);
                    }
                });
            }
        });

        $(document).on('click', '#search', function (){
            $('#ajax-loader').show();
            table.draw();
        });
    });
</script>
