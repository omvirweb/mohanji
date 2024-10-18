<?php $this->load->view('success_false_notify'); 
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
?>
<div class="content-wrapper">
    <style>
        .dataTables_scroll {
            overflow:auto;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Feedback List
            <a href="<?= base_url('feedback/feedback') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Feedback</a>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="feedback_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Created By</th>
                                                    <th>Assign To</th>
                                                    <th>Date</th>
                                                    <th>Note</th>
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
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clicked_item_id" value="<?= isset($feedback_data->feedback_id) ? $feedback_data->feedback_id : '-1'; ?>" >
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php $this->load->view('success_false_notify'); ?>
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1>
                            Reply
                        </h1>
                    </section>
                    <div class="clearfix">
                        <div class="row">
                            <div style="margin: 15px;">
                                <div class="col-md-6">
                                    <!-- Horizontal Form -->
                                    <div class="box box-primary">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-body table-responsive">
                                                        <table id="city_table" Cass="table row-border table-bordered table-striped" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Action</th>
                                                                    <th>Assign To</th> 
                                                                    <th>Date</th>
                                                                    <th>Reply</th> 
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
                                <!-- Horizontal Form -->
                                <div class="col-md-6">
                                    <div class="box box-primary">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="clearfix"></div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <form class="form-horizontal" action="<?= base_url('feedback/save_reply') ?>" method="post" id="reply_form" novalidate enctype="multipart/form-data">                                    
                                                            <?php if (isset($reply_data->reply_id) && !empty($reply_data->reply_id)) { ?>
                                                                <input type="hidden" name="reply_id" class="reply_id" value="<?= $reply_data->reply_id ?>">
                                                            <?php } ?>
                                                                <input type="hidden" name="feedback_id" class="feedback_id" id="feedback_id" value="<?= isset($feedback_data->feedback_id) ? $feedback_data->feedback_id : ''; ?>">
                                                            <label for="assign_id">Assign To<span class="required-sign">&nbsp;*</span></label>
                                                            <select name="assign_to_id" id="assign_id" class="form-control select2"></select><br/><br />


                                                            <label for="feedback_date">Date</label>
                                                            <input type="text" name="reply_date" id="datepicker2" class="form-control input-datepicker" value="<?= (isset($reply_data->reply_date)) ? date('d-m-Y', strtotime($reply_data->reply_date)) : date('d-m-Y'); ?>"><br />

                                                            <label>Reply <span style="color: red">*</span></label>
                                                            <textarea name="reply" id="reply" class="form-control"><?= (isset($reply_data->reply)) ? $reply_data->reply : ''; ?></textarea><br/>

                                                            <div class="clrearfix"></div>
                                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn"><?= isset($reply_data->reply_id) ? 'Update' : 'Save' ?></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        initAjaxSelect2($("#assign_id"), "<?= base_url('app/user_master_select2_source') ?>");
<?php if (isset($reply_data->assign_to_id)) { ?>
        setSelect2Value($("#assign_id"), "<?= base_url('app/set_user_master_select2_val_by_id/' . $reply_data->assign_to_id) ?>");
<?php } ?>
    
        table = $('#feedback_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('feedback/feedback_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
            "columnDefs": [{
                    "className": "dt-right",
                    "targets": [],
                }]
        });
        
        <?php if(isset($reply_data) && !empty($reply_data)){ ?>
            $('#myModal').modal('show');
        <?php } ?>
        feedback_table = $('#city_table').DataTable({
            "serverSide": true, 
            "paging": false,
            "ajax": {
                "url": "<?php echo site_url('feedback/reply_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.feedback_id = $('#clicked_item_id').val()
                },
            },
            "columnDefs": [{
                    "className": "dt-right",
                    "targets": [],
                }]
        });
        
        jQuery('#feedback_table').wrap('<div class="dataTables_scroll" />');

        $(document).on('click', '.feedback_row', function () {
            $('#clicked_item_id').val($(this).attr('data-feedback_id'));
            $('#feedback_id').val($(this).attr('data-feedback_id'));
            feedback_table.draw();
            $('#myModal').modal('show');
        });

        $(document).on('submit', '#reply_form', function () {
            if ($.trim($("#assign_id").val()) == '') {
                show_notify('Please Select Assign_To.', false);
                $("#assign_id").select2('open');
                return false;
            }
            $('.module_submit_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('feedback/save_reply') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);

                    if (json['error'] == 'Exist') {
                        show_notify(json['error_exist'], false);
                    } else if (json['success'] == 'Added') {
//                        window.location.href = "<?php echo base_url('feedback/reply') ?>";
                        $('input').val('');
                        $("#assign_id").val(null).trigger("change");
                        $('#myModal').modal('hide');
                        table.draw();
                        show_notify('Reply Added Successfully!', true);
                    } else if (json['success'] == 'Updated') {
//                        window.location.href = "<?php echo base_url('feedback/reply') ?>";
                        $('input').val('');
                        $("#assign_id").val(null).trigger("change");
                        $('#myModal').modal('hide');
                        table.draw();
                        show_notify('Reply Updated Successfully!', true);
                    }
                    $('.module_submit_btn').removeAttr('disabled', 'disabled');
                    return false;
                },

            });
            return false;
        });

        $(document).on("click", ".delete_button", function () {
            var value = confirm('Are you sure delete this records?');
            var tr = $(this).closest("tr");
            if (value) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: 'id_name=reply_id&table_name=reply',
                    success: function (data) {
                        table.draw();
                        show_notify('Reply Deleted Successfully!', true);
                    }
                });
            }
        });

        $(document).on("click", ".delete_feedback", function () {
            if (confirm('Are you sure delete this records?')) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (data) {
                        table.draw();
                        show_notify('Feedback Deleted Successfully!', true);
                    }
                });
            }
        });
    });
</script>
