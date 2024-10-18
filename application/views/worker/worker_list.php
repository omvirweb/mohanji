<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Worker Entry List
            <?php
            $isView = $this->app_model->have_access_role(WORKER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(WORKER_MODULE_ID, "add"); ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('worker/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Worker</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <?php if($isView) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="worker_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Worker Name</th> 
                                                    <th>Department</th> 
                                                    <th>Salary</th>
                                                    <th>Worker Type</th>
                                                    <th>Documents</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Document Image</h4>
                </div>
                <div class="modal-body edit-content">
                    <img id="doc_img_src" src="" class="img-responsive" height='500px' width='300px'>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");

            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });
        
        table = $('#worker_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('worker/worker_entry_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=worker_entry_id&table_name=worker_entry',
                    success: function (data) {
                        table.draw();
                        show_notify('Worker Deleted Successfully!', true);
                    }
                });
            }
        });


    });
</script>
