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
            Operation List
            <?php
            $isView = $this->app_model->have_access_role(OPERATION_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(OPERATION_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?=base_url('manu_hand_made/operation') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Operation</a>
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
                                        <table id="lot_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Operation Name</th>
                                                    <th>Department(s)</th>
                                                    <th>Worker(s)</th>
                                                    <th>Fix Loss</th>
                                                    <th>Fix Loss Per</th>
                                                    <th>Allow Max Loss?</th>
                                                    <th>Max Loss Wt</th>
                                                    <th>Issue Finish Fix Loss</th>
                                                    <th>Issue Finish Fix Loss Per</th>
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
//        $('#department_id').select2();
//        initAjaxSelect2($("#department_id"), "<?= base_url('app/department_select2_source') ?>");
//        setSelect2Value($("#department_id"), "<?= base_url('app/set_process_master_select2_val_by_id/' . $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id']) ?>");

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
                "url": "<?php echo site_url('manu_hand_made/operation_datatable') ?>",
                "type": "POST",
                "data": function (d) {
//                    d.department_id = $('#department_id').val();
//                    d.from_date = $('#datepicker1').val();
//                    d.to_date = $('#datepicker2').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [
                {
                    "className": "dt-right",
                    "targets": [5,7],
                }
            ],
        });

        $(document).on("click", ".delete_operation", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Operation. This Operation has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Operation Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

//        $(document).on('click', '#search', function (){
//            $('#ajax-loader').show();
//            table.draw();
//        });
    });
</script>
