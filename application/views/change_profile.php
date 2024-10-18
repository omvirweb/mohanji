<?php if ($this->session->flashdata('success') == true) { ?>
    <script>
        $(document).ready(function () {
            show_notify('<?php echo $this->session->flashdata('message'); ?>', true);
        });
    </script>
<?php } ?>
<?php $this->load->library('form_validation'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Profile
            <a href="<?php echo base_url() ?>auth/change_password" class="btn btn-primary pull-right">Change Password</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">  
            <div class="col-md-12 display_alert"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <form action="<?php echo base_url(); ?>auth/change_profile/" method="post" id="update_profile">
                            <input type="hidden" name="account_id" value="<?php echo isset($account_data->account_id) ? $account_data->account_id : ''; ?>">

                            <div class="form-group">
                                <label for="account_name" class="control-labelsm">Account Name</label>
                                <input type="text" name="account_name" class="form-control" value="<?php echo isset($account_data->account_name) ? $account_data->account_name : ''; ?>" parsley-trigger="change" autofocus>
                            </div>

                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
<script>

</script>

