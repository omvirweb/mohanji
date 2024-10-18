  <?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <form class="form-horizontal" action="<?= base_url('feedback/save_feedback') ?>"  method="post" id="save_feedback" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($feedback_data->feedback_id) && !empty($feedback_data->feedback_id)) { ?>
            <input type="hidden" name="feedback_id" class="feedback_id" value="<?= $feedback_data->feedback_id ?>">
        <?php } ?>
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Add Feedback
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" ><?= isset($feedback_data->feedback_id) ? 'Update' : 'Save' ?></button>
                <a href="<?= base_url('feedback/feedback_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Feedback List</a>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                         <label for="assign_id">Created By<span class="required-sign">&nbsp;*</span></label>
                                         <select name="assign_id" id="assign_id" class="form-control select2"></select><br/><br />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="feedback_date">Date</label>
                                        <input type="text" name="feedback_date" id="datepicker2" class="form-control input-datepicker" value="<?= (isset($feedback_data->feedback_date)) ? date('d-m-Y', strtotime($feedback_data->feedback_date)) : date('d-m-Y'); ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="note">Note</label>
                                        <input type="text" name="note" id="note" class="form-control" value="<?= (isset($feedback_data->note)) ? $feedback_data->note : ''; ?>"><br />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    
    $(document).ready(function () {
         
        initAjaxSelect2($("#assign_id"), "<?= base_url('app/user_master_select2_source') ?>");
<?php if (isset($feedback_data->assign_id)) { ?>
        setSelect2Value($("#assign_id"), "<?= base_url('app/set_user_master_select2_val_by_id/' . $feedback_data->assign_id) ?>");
<?php } ?>
        $(document).on('submit', '#save_feedback', function () {

            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('feedback/save_feedback') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Added') {
                        window.location.href = "<?php echo base_url('feedback/feedback_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('feedback/feedback_list') ?>";
                    }
                    return false;
                },
            });
            return false;
        });
    });

</script>
