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
                Log Report
            <?php
            $isView = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "add"); ?>
                <!--<a href="<?=base_url('journal/add') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Journal Entry</a>-->
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
                                        <div class="col-md-2">
                                            <label>Select Log</label>
                                            <select name="log_type" id="log_type" class="form-control select2">
                                                <option value="0"> --Select-- </option>
                                                <option value="1">Cashbook - Receipt</option>
                                                <option value="2">Cashbook - Payment</option>
                                            </select>
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

<script>
    $(document).ready(function () {
        $('#log_type').select2();
        $(document).on('change', '#log_type', function(){
            change_log($(this).val());
        });
        change_log($('#log_type').val());
        
    });
    
    function change_log(log_val){
        if(log_val == '1'){
            window.location.href = "<?php echo base_url('reports/cash_receipt_log') ?>";
        } else if(log_val == '2'){
            window.location.href = "<?php echo base_url('reports/cashbook_payment') ?>";
        }
    }
    
</script>
