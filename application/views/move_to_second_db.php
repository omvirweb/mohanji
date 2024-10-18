<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>TransferIndex</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">  
            <div class="col-md-12 display_alert"></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo base_url('move_to_second_db/copy_masters_data'); ?>" class="btn btn-primary" target="_blank"> Copy Masters Data</a>
                &nbsp;&nbsp;&nbsp;
                <a href="<?php echo base_url('move_to_second_db/move_all_data'); ?>" class="btn btn-primary" target="_blank"> Move All Data</a>
                
                <br><br><br>
                <h4>Only for Developer Testing</h4>
                <button name="move_data_using_procedure" id="move_data_using_procedure" class="btn btn-info" > Move All Data</button>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        $(document).on('click', '#move_data_using_procedure', function () {
            $('#move_data_using_procedure').attr('disabled', 'disabled');
            var postData = ''; //new FormData(this);
            $.ajax({
                url: "<?php echo base_url('move_to_second_db/move_data_using_procedure'); ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                data: postData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json['success'] == 'Called') {
                        show_notify('Moved Successfully!', true);
                    } else {
                        show_notify('some error occurred!', false);
                    }
                    $('#move_data_using_procedure').removeAttr('disabled', 'disabled');
                    return false;
                },
            });
            return false;
        });
    });
</script>
