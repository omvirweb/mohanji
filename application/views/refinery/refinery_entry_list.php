<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Refinery Entry List
            <?php
            $isView = $this->app_model->have_access_role(REFINERY_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(REFINERY_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('refinery/refinery_entry') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Refinery Entry</a>
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
                                        <table id="refinery_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Invoice No</th> 
                                                    <th>Account Name</th> 
                                                    <th>Date</th> 
                                                    <th>Sub Total</th>
                                                    <th>Total Amount</th>
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
<script>
    $(document).ready(function () {
        $('#ajax-loader').show();
        table = $('#refinery_table').DataTable({
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
                "url": "<?php echo site_url('refinery/refinery_entry_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                { "className": "dt-right", "targets": [4,5] },
            ]

        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=r_entry_id&table_name=refinery_entry',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Entry. This Item has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Entry Deleted Successfully!', true);
                        }
                    }
                });
            }
        });


    });
</script>
