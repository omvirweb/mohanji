<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <form class="form-horizontal" action="<?= base_url('master/save_refinery') ?>" method="post" id="refinery_form" novalidate enctype="multipart/form-data">                                    
        <?php if (isset($refinery_data->r_entry_id) && !empty($refinery_data->r_entry_id)) { ?>
            <input type="hidden" name="r_entry_id" class="r_entry_id" value="<?= $refinery_data->r_entry_id ?>">
        <?php } ?>
        <section class="content-header">
            <h1>
                Refinery Entry
                <?php $isEdit = $this->app_model->have_access_role(REFINERY_MODULE_ID, "edit");
                $isAdd = $this->app_model->have_access_role(REFINERY_MODULE_ID, "add"); 
                $isView = $this->app_model->have_access_role(REFINERY_MODULE_ID, "view"); ?>
                <?php if (isset($refinery_data->item_id) && !empty($refinery_data->item_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($refinery_data->item_id) ? '' : $btn_disable;?>><?= isset($refinery_data->item_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                <?php if($isView){ ?>
                    <a href="<?= base_url('refinery/refinery_list') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Refinery Entry List</a>
                <?php } ?>
            </h1>
        </section>
        <div class="clearfix">
            <div class="row">
                <div style="margin: 15px;">
                    <!-- Horizontal Form -->
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <?php if($isAdd || $isEdit) { ?>
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label for="account_id">Account Name <span class="required-sign">&nbsp;*</span></label>
                                                <select name="account_id" id="account_id" class="form-control select2" ></select>
                                                <div class="clearfix"></div><br />
                                            </div>
                                            <div class="col-md-2">
                                                <label for="entry_date">Date <span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="entry_date" id="datepicker2" class="form-control input-datepicker entry_date" value="<?php echo isset($refinery_data->entry_date) ? date('d-m-Y', strtotime($refinery_data->entry_date)) : date('d-m-Y'); ?>" style="padding: 5px;"><br />
                                            </div>
                                            <?php if(isset($invoice_no)){ ?>
                                                <div class="col-md-2">
                                                    <label for="invoice_no">Invoice No.</label>
                                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control num_only" value="<?= (isset($invoice_no)) ? $invoice_no : ''; ?>" style="padding: 5px;"><br />
                                                </div>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                            <div class="col-md-6">
                                                <h4><b>Receiving : </b></h4>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_hsn_sac_code">HSN & SAC Code No</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_hsn_sac_code" id="r_hsn_sac_code" class="form-control"  value="<?= (isset($refinery_data->r_hsn_sac_code)) ? $refinery_data->r_hsn_sac_code : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_old_jewels_weight">Old & Scrap Gold Jewels Weight</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_old_jewels_weight" id="r_old_jewels_weight" class="form-control num_only"  value="<?= (isset($refinery_data->r_old_jewels_weight)) ? $refinery_data->r_old_jewels_weight : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_stones_dust_weights_loss">Stones and Dust Weights Loss</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_stones_dust_weights_loss" id="r_stones_dust_weights_loss" class="form-control num_only"  value="<?= (isset($refinery_data->r_stones_dust_weights_loss)) ? $refinery_data->r_stones_dust_weights_loss : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_before_melting_weight">Before Melting Weight</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_before_melting_weight" id="r_before_melting_weight" class="form-control num_only" readonly="" value="<?= (isset($refinery_data->r_before_melting_weight)) ? $refinery_data->r_before_melting_weight : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_melting_loss">Melting Loss</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_melting_loss" id="r_melting_loss" class="form-control num_only" readonly="" value="<?= (isset($refinery_data->r_melting_loss)) ? $refinery_data->r_melting_loss : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_after_melting_weight">After Melting Weight</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_after_melting_weight" id="r_after_melting_weight" class="form-control num_only"  value="<?= (isset($refinery_data->r_after_melting_weight)) ? $refinery_data->r_after_melting_weight : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_testing_purity_per">Testing Purity %</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_testing_purity_per" id="r_testing_purity_per" class="form-control num_only"  value="<?= (isset($refinery_data->r_testing_purity_per)) ? $refinery_data->r_testing_purity_per : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="r_net_fine_gold">Net Fine Gold Wt. 99.9%</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="r_net_fine_gold" id="r_net_fine_gold" class="form-control num_only"  value="<?= (isset($refinery_data->r_net_fine_gold)) ? $refinery_data->r_net_fine_gold : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-6">
                                                <h4><b>Delivery : </b></h4>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="d_hsn_sac_code">HSN & SAC Code No</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="d_hsn_sac_code" id="d_hsn_sac_code" class="form-control"  value="<?= (isset($refinery_data->d_hsn_sac_code)) ? $refinery_data->d_hsn_sac_code : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="d_given_fine_gold_purity">Given Fine Gold Purity 99.90</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="d_given_fine_gold_purity" id="d_given_fine_gold_purity" class="form-control num_only"  value="<?= (isset($refinery_data->d_given_fine_gold_purity)) ? $refinery_data->d_given_fine_gold_purity : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="d_melting_charges_weight">Melting Charges Per Gram</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_melting_charges_weight" id="d_melting_charges_weight" class="form-control num_only"  value="<?= (isset($refinery_data->d_melting_charges_weight)) ? $refinery_data->d_melting_charges_weight : ''; ?>"><br />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_melting_charges_per_gram" id="d_melting_charges_per_gram" class="form-control num_only"  value="<?= (isset($refinery_data->d_melting_charges_per_gram)) ? $refinery_data->d_melting_charges_per_gram : ''; ?>"><br />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_melting_charges_total" id="d_melting_charges_total" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->d_melting_charges_total)) ? $refinery_data->d_melting_charges_total : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="d_refining_charges_weight">Refining Charges Per Gram</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_refining_charges_weight" id="d_refining_charges_weight" class="form-control num_only"  value="<?= (isset($refinery_data->d_refining_charges_weight)) ? $refinery_data->d_refining_charges_weight : ''; ?>"><br />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_refining_charges_per_gram" id="d_refining_charges_per_gram" class="form-control num_only"  value="<?= (isset($refinery_data->d_refining_charges_per_gram)) ? $refinery_data->d_refining_charges_per_gram : ''; ?>"><br />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="d_refining_charges_total" id="d_refining_charges_total" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->d_refining_charges_total)) ? $refinery_data->d_melting_charges_total : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="">Total</label>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="sub_total" id="sub_total" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->sub_total)) ? $refinery_data->sub_total : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="d_refining_charges_per_gram">GST Type</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <select name="gst_type_id" id="gst_type_id" class="form-control select2" >
                                                        <option value="">--Select--</option>
                                                        <option value="1" <?= (isset($refinery_data->gst_type_id) && $refinery_data->gst_type_id == '1') ? 'selected' : ''; ?>>SGST + CGST</option>
                                                        <option value="2" <?= (isset($refinery_data->gst_type_id) && $refinery_data->gst_type_id == '2') ? 'selected' : ''; ?>>IGST</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <label for="gst_per" style="white-space: nowrap;">GST Per</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="gst_per" id="gst_per" class="form-control num_only"  value="<?= (isset($refinery_data->gst_per)) ? $refinery_data->gst_per : '18'; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="col-md-8 sgst_div">
                                                <div class="col-md-4">
                                                    <label for="">SGST</label>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="sgst" id="sgst" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->sgst)) ? $refinery_data->sgst : ''; ?>"><br />
                                                    <input type="hidden" name="sgst_per" id="sgst_per"   value="<?= (isset($refinery_data->sgst_per)) ? $refinery_data->sgst_per : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-8 cgst_div">
                                                <div class="col-md-4">
                                                    <label for="">CGST</label>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="cgst" id="cgst" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->cgst)) ? $refinery_data->cgst : ''; ?>"><br />
                                                    <input type="hidden" name="cgst_per" id="cgst_per" value="<?= (isset($refinery_data->cgst_per)) ? $refinery_data->cgst_per : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-8 igst_div">
                                                <div class="col-md-4">
                                                    <label for="">IGST</label>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="igst" id="igst" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->igst)) ? $refinery_data->igst : ''; ?>"><br />
                                                    <input type="hidden" name="igst_per" id="igst_per"  value="<?= (isset($refinery_data->igst_per)) ? $refinery_data->igst_per : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-md-4">
                                                    <label for="">Total Amount</label>
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="total_amount" id="total_amount" class="form-control num_only" readonly=""  value="<?= (isset($refinery_data->total_amount)) ? $refinery_data->total_amount : ''; ?>"><br />
                                                </div>
                                            </div>
                                            <div class="clearfix"></div><br/>
                                            <?php if (isset($refinery_data->item_id) && !empty($refinery_data->item_id)) { ?>
                                            <div class="created_updated_info" style="margin-left: 15px;">
                                                Created by : <?php echo (isset($refinery_data->created_by_name)) ? $refinery_data->created_by_name :'';?>
                                                @ <?php echo (isset($refinery_data->created_at)) ? date('d-m-Y h:i A', strtotime($refinery_data->created_at)) :'';?><br/>
                                                Updated by : <?php echo (isset($refinery_data->updated_by_name)) ? $refinery_data->updated_by_name :'';?>
                                                @ <?php echo (isset($refinery_data->updated_at)) ? date('d-m-Y h:i A', strtotime($refinery_data->updated_at)) :'';?>
                                            </div>
                                            <?php } ?>
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
    </form>
</div>
<script>
    $('#body-content').on('change keyup keydown click', 'input, textarea, select', function (e) {
        $(this).addClass('changed-input');
    });
    $(window).on('beforeunload', function () {
        if ($('.changed-input').length) {
            return 'Are you sure you want to leave?';
        }
    });
    $(document).ready(function () {
        
        initAjaxSelect2($("#account_id"), "<?= base_url('app/party_name_with_number_select2_source/1') ?>");
        
    <?php if (isset($refinery_data->account_id)) { ?>
            setSelect2Value($("#account_id"), "<?= base_url('app/set_account_name_with_number_val_by_id/' . $refinery_data->account_id) ?>");
    <?php } ?>
        
            
        $('#gst_type_id').select2();
        
        $(document).on('input', '#r_old_jewels_weight, #r_stones_dust_weights_loss', function () {
            var r_old_jewels_weight = $('#r_old_jewels_weight').val() || 0;
            var r_stones_dust_weights_loss = $('#r_stones_dust_weights_loss').val() || 0;
            if(parseFloat(r_stones_dust_weights_loss) < 0){
                r_before_melting_weight = parseFloat(r_old_jewels_weight) + parseFloat(r_stones_dust_weights_loss);
            } else {
                r_before_melting_weight = parseFloat(r_old_jewels_weight) - parseFloat(r_stones_dust_weights_loss);
            }
            $('#r_before_melting_weight').val(parseFloat(r_before_melting_weight));
            get_r_melting_loss();
        });
        
        $(document).on('input', '#r_after_melting_weight', function () {
            get_r_melting_loss();
        });
        
        
        
        $(document).on('change', '#gst_type_id', function () {
            get_gst();
        });
        $(document).on('input', '#gst_per', function () {
            get_gst();
        });
        $(document).on('input', '#d_melting_charges_weight, #d_melting_charges_per_gram', function () {
            var d_melting_charges_weight = $('#d_melting_charges_weight').val() || 0;
            var d_melting_charges_per_gram = $('#d_melting_charges_per_gram').val() || 0;
            var d_melting_charges_total = parseFloat(d_melting_charges_weight) * parseFloat(d_melting_charges_per_gram);
            $('#d_melting_charges_total').val(parseFloat(d_melting_charges_total));
            var d_refining_charges_total = $('#d_refining_charges_total').val() || 0;
            var sub_total = parseFloat(d_melting_charges_total) + parseFloat(d_refining_charges_total);
            $('#sub_total').val(parseFloat(sub_total));
            get_gst();
        });
        
        $(document).on('input', '#d_refining_charges_weight, #d_refining_charges_per_gram', function () {
            var d_refining_charges_weight = $('#d_refining_charges_weight').val() || 0;
            var d_refining_charges_per_gram = $('#d_refining_charges_per_gram').val() || 0;
            var d_refining_charges_total = parseFloat(d_refining_charges_weight) * parseFloat(d_refining_charges_per_gram);
            $('#d_refining_charges_total').val(parseFloat(d_refining_charges_total));
            var d_melting_charges_total = $('#d_melting_charges_total').val() || 0;
            var sub_total = parseFloat(d_refining_charges_total) + parseFloat(d_melting_charges_total);
            $('#sub_total').val(parseFloat(sub_total));
            get_gst();
        });
        
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#refinery_form").submit();
                return false;
            }
        });
        
        $(document).on('submit', '#refinery_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#account_id").val()) == '') {
                show_notify('Please Select Account Name.', false);
                $("#account_id").select2('open');
                return false;
            }
            if ($.trim($(".entry_date").val()) == '') {
                show_notify('Please Enter Date.', false);
                $("#entry_date").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('refinery/save_refinery') ?>",
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
                        window.location.href = "<?php echo base_url('refinery/refinery_list') ?>";
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('refinery/refinery_list') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
                    return false;
                },

            });
            return false;
        });
        
        $('.sgst_div').hide();
        $('.cgst_div').hide();
        $('.igst_div').hide();
        
        <?php if (isset($refinery_data->r_entry_id)) { ?>
            get_gst();
        <?php } ?>

    });
    
    function get_gst(){
        var gst_type_id = $('#gst_type_id').val();
        if(gst_type_id != ''){
            var gst = $('#gst_per').val() || 0;
            if(gst != 0){
                if(gst_type_id == '1'){
                    var sgst = gst / 2;
                    var cgst = gst / 2;
                    var sub_total = $('#sub_total').val() || 0;
                    var total_sgst = parseFloat(sub_total) * sgst / parseFloat(100);
                    var total_cgst = parseFloat(sub_total) * cgst / parseFloat(100);
                    var total_amount = parseFloat(total_sgst) + parseFloat(total_cgst) + parseFloat(sub_total);
                    total_amount = round(total_amount, 2).toFixed(2);
                    $('.sgst_div').show();
                    $('.cgst_div').show();
                    $('.igst_div').hide();
                    $('#total_amount').val(total_amount);
                    $('#sgst').val(parseFloat(total_sgst));
                    $('#sgst_per').val(sgst);
                    $('#cgst').val(parseFloat(total_cgst));
                    $('#cgst_per').val(cgst);
                    $('#igst').val('0');
                    $('#igst_per').val('0');
                } else if(gst_type_id == '2'){
                    var igst = gst;
                    var sub_total = $('#sub_total').val() || 0;
                    var total_igst = parseFloat(sub_total) * igst / parseFloat(100);
                    var total_amount = parseFloat(total_igst) + parseFloat(sub_total);
                    total_amount = round(total_amount, 2).toFixed(2);
                    $('#total_amount').val(total_amount);
                    $('.sgst_div').hide();
                    $('.cgst_div').hide();
                    $('.igst_div').show();
                    $('#sgst').val('0');
                    $('#sgst_per').val('0');
                    $('#cgst').val('0');
                    $('#cgst_per').val('0');
                    $('#igst').val(parseFloat(total_igst));
                    $('#igst_per').val(igst);
                }
            } else {
                var sub_total = $('#sub_total').val() || 0;
                var total_amount = parseFloat(sub_total);
                $('#total_amount').val(parseFloat(total_amount));
                $('#sgst').val('0');
                $('#sgst_per').val('0');
                $('#cgst').val('0');
                $('#cgst_per').val('0');
                $('#igst').val('0');
                $('#igst_per').val('0');
                $('.sgst_div').hide();
                $('.cgst_div').hide();
                $('.igst_div').hide();
            }
        } else {
            var sub_total = $('#sub_total').val() || 0;
            var total_amount = parseFloat(sub_total);
            $('#total_amount').val(parseFloat(total_amount));
            $('#sgst').val('0');
            $('#sgst_per').val('0');
            $('#cgst').val('0');
            $('#cgst_per').val('0');
            $('#igst').val('0');
            $('#igst_per').val('0');
            $('.sgst_div').hide();
            $('.cgst_div').hide();
            $('.igst_div').hide();
        }
        
    }
    
    function get_r_melting_loss(){
        var r_after_melting_weight = $('#r_after_melting_weight').val() || 0;
        var r_before_melting_weight = $('#r_before_melting_weight').val() || 0;
        r_melting_loss = parseFloat(r_after_melting_weight) - parseFloat(r_before_melting_weight);
        $('#r_melting_loss').val(parseFloat(r_melting_loss));
    }
    
</script>
