<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Item Master
            <?php
            $isView = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/item_master') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Item Master</a>
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
                                        <table id="item_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Category Name</th> 
                                                    <th>Item Name</th> 
                                                    <th>Short Item Name</th>
                                                    <th>Die No</th>
                                                    <th>Design No</th>
                                                    <th>Min Order Qty.</th>
                                                    <th>Default Wastage</th>
                                                    <th>ST Default Wastage</th>
                                                    <th>Less</th>
                                                    <th>Display Item In</th>
                                                    <th>Stock Method</th>
                                                    <th>Metal Issue Receive</th>
                                                    <th>Image</th>
                                                    <th>Sequence No</th>
                                                    <th>Rate On</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
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
        $('#ajax-loader').show();
        table = $('#item_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
//            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            scroller: {
                loadingIndicator: true
            },
            "ajax": {
                "url": "<?php echo site_url('master/item_master_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },

        });

        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            $("#doc_img_src").attr('src', src);
            $('#edit-modal').modal('show');
        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=item_id&table_name=item_master',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Item. This Item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Item Deleted Successfully!', true);
                        }
                    }
                });
            }
        });


    });
</script>
