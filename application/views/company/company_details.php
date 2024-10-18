<?php $this->load->view('success_false_notify'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="body-content">
    <style>
    .ui-autocomplete {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      display: none;
      float: left;
      min-width: 160px;
      padding: 7px 0;
      padding-left: 7px;
      margin: 2px 0 0;
      list-style: none;
      font-size: 14px;
      max-height: 200px;
      overflow-x: hidden;
      text-align: left;
      background-color: #ffffff;
      border: 1px solid #cccccc;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      background-clip: padding-box;
    }

    .ui-autocomplete > li > div {
      display: block !important;
      padding: 3px 20px !important;
      clear: both;
      font-weight: normal;
      line-height: 1.42857143 !important;
      color: #333333 !important;
      white-space: nowrap;
    }

    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
      text-decoration: none;
      color: #262626;
      background-color: #f5f5f5 !important;
      cursor: pointer;
    }

    .ui-helper-hidden-accessible {
      border: 0;
      clip: rect(0 0 0 0);
      height: 1px;
      margin: -1px;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
    } 
</style>
    <form id="company_form_company" class="" action="" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate autocomplete="off">
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Company Details
                <?php $isEdit = $this->app_model->have_access_role(COMPANY_DETAILS_MODULE_ID, "edit"); ?>
                <button type="submit" class="btn btn-primary form_btn pull-right module_save_btn btn-sm" style="margin: 5px;"> Update [ Ctrl +S ]</button>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- START ALERTS AND CALLOUTS -->
            <div class="row">
                <div class="col-md-12">
                    <?php if($isEdit) { ?>
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name" class="control-label">Company Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="company_name" class="form-control" id="company_name" value="<?= isset($company_details->company_name) ? $company_details->company_name : '' ?>" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_gst_no" class="control-label">GSTIN</label>
                                            <input type="text" name="company_gst_no" class="form-control" id="company_gst_no" value="<?= isset($company_details->company_gst_no) ? $company_details->company_gst_no : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_phone" class="control-label"> Phone</label>
                                            <input type="text" name="company_phone" class="form-control num_only" id="company_phone" value="<?= isset($company_details->company_phone) ? $company_details->company_phone : '' ?>" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_mobile" class="control-label"> Mobile</label>
                                            <input type="text" name="company_mobile" class="form-control" id="company_mobile" minlength="10" value="<?= isset($company_details->company_mobile) ? $company_details->company_mobile : '' ?>" placeholder="" >
                                            <small class="">Add multiple mobile by comma separated.</small>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="box-header with-border">
                                            <h4 class="box-title form_title">Address</h4>
                                        </div>	
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_address" class="control-label">Street</label>
                                            <textarea name="company_address" class="form-control" id="company_address" placeholder=""><?= isset($company_details->company_address) ? $company_details->company_address : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_postal_code" class="control-label">Postal Code</label>
                                            <input type="text" name="company_postal_code" class="form-control" id="company_postal_code" value="<?= isset($company_details->company_postal_code) ? $company_details->company_postal_code : '' ?>" placeholder="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_state_id" class="control-label">State</label>
                                            <select name="company_state_id" id="company_state_id" class="form-control select2" ></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_city_id" class="control-label">City</label>
                                            <select name="company_city_id" id="company_city_id" class="form-control select2" ></select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_cin" class="control-label">CIN</label>
                                            <input type="text" name="company_cin" class="form-control" id="company_cin" value="<?= isset($company_details->company_cin) ? $company_details->company_cin : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reg_no" class="control-label">Regn. No.</label>
                                            <input type="text" name="company_reg_no" class="form-control" id="company_reg_no" value="<?= isset($company_details->company_reg_no) ? $company_details->company_reg_no : '' ?>" placeholder="" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary form_btn btn-sm">Update [ Ctrl +S ]</button>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- END ALERTS AND CALLOUTS -->
        </section>
    </form>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    var module_submit_flag = 0;
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    
    $(document).ready(function () {
        $('.mobile_required').hide();
        $(".select2").select2({
            width: "100%",
            placeholder: " --Select-- ",
            allowClear: true,
        });
        
        initAjaxSelect2($("#company_state_id"), "<?= base_url('app/state_select2_source') ?>");
        $('#company_state_id').on('change', function () {
            $("#company_city_id").empty().trigger('change');
            var state_home = this.value;
            initAjaxSelect2($('#company_city_id'), "<?= base_url('app/city_select2_source') ?>/" + state_home);
        });
        

<?php if (isset($company_details->company_state_id) && !empty($company_details->company_state_id)) { ?>
            setSelect2Value($("#company_state_id"), "<?= base_url('app/set_state_select2_val_by_id/' . $company_details->company_state_id) ?>");
            initAjaxSelect2($('#company_city_id'), "<?= base_url('app/city_select2_source/' . $company_details->company_state_id) ?>");
<?php } ?>

<?php if (isset($company_details->company_city_id) && !empty($company_details->company_city_id)) { ?>
            setSelect2Value($("#company_city_id"), "<?= base_url('app/set_city_select2_val_by_id/' . $company_details->company_city_id) ?>");
<?php } ?>

    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                e.preventDefault();
                if(module_submit_flag == 0 ){
                    $("#company_form_company").submit();
                    return false;
                }
            }
        });
        
        $(document).on('submit', '#company_form_company', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#company_name").val()) == '') {
                show_notify('Please Enter Name.', false);
                $("#company_name").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('company/save_company') ?>",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
//                fileElementId: 'company_image',
                data: postData,
                async: false,
                success: function (response) {
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    var json = $.parseJSON(response);
                    if (json['error'] == 'errorAdded') {
                        show_notify('Some error has occurred !', false);
                        return false;
                    }
                    if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('company/company_details') ?>";
                    }
                    return false;
                },
            });
            module_submit_flag = 1;
            return false;
        });

    });
    
</script>
