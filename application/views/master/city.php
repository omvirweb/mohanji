<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            City
            <?php $isEdit = $this->app_model->have_access_role(CITY_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(CITY_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(CITY_MODULE_ID, "add"); ?>
            <?php if (isset($city_data->city_id) && !empty($city_data->city_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/city') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add City</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-6">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php if($isView){ ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="city_table" clEXECass="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>State Name</th> 
                                                    <th>City Name</th> 
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

                <!-- Horizontal Form -->
                <div class="col-md-6">
                    <?php if($isAdd || $isEdit) { ?>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <form class="form-horizontal" action="<?= base_url('master/save_city') ?>" method="post" id="city_form" novalidate enctype="multipart/form-data">                                    
                                            <?php if (isset($city_data->city_id) && !empty($city_data->city_id)) { ?>
                                                <input type="hidden" name="city_id" class="city_id" value="<?= $city_data->city_id ?>">
                                            <?php } ?>
                                                
                                            <label for="state_id">State<span class="required-sign">&nbsp;*</span></label>
                                            <select name="state_id" id="state_id" class="form-control select2"></select><br/><br />
                                            
                                            <label for="city_name">City Name<span class="required-sign">&nbsp;*</span></label>
                                            <input type="text" name="city_name" id="city_name" class="form-control"  value="<?= (isset($city_data->city_name)) ? $city_data->city_name : ''; ?>"><br />

                                            <div class="clrearfix"></div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?php echo isset($city_data->city_id) ? '' : $btn_disable; ?>><?= isset($city_data->city_id) ? 'Update' : 'Save' ?> [ Ctrl +S ]</button>
                                            <?php if (isset($city_data->city_id) && !empty($city_data->city_id)) { ?>
                                            <div class="created_updated_info">
                                                    Created by : <?php echo (isset($city_data->created_by_name)) ? $city_data->created_by_name : ''; ?>
                                                    @ <?php echo (isset($city_data->created_at)) ? date('d-m-Y h:i A', strtotime($city_data->created_at)) : ''; ?> <br/>
                                                    Updated by : <?php echo (isset($city_data->updated_by_name)) ? $city_data->updated_by_name : ''; ?>
                                                    @ <?php echo (isset($city_data->updated_at)) ?date('d-m-Y h:i A', strtotime($city_data->updated_at)) : '' ?>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
         initAjaxSelect2($("#state_id"), "<?= base_url('app/state_select2_source') ?>");
    <?php if (isset($city_data->state_id)) { ?>
        setSelect2Value($("#state_id"), "<?= base_url('app/set_state_select2_val_by_id/' . $city_data->state_id) ?>");
    <?php } ?>
        table = $('#city_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/city_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },
            "columnDefs": [{                        
                "className": "dt-right",
                "targets": [],
            }]
        });
        
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#city_form").submit();
                return false;
            }
        });
    
        $(document).on('submit', '#city_form', function () {
            if ($.trim($("#state_id").val()) == '') {
                show_notify('Please Select State.', false);
                $("#state_id").select2('open');
                return false;
            }
            if ($.trim($("#city_name").val()) == '') {
                show_notify('Please Enter City.', false);
                $("#city_name").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_city') ?>",
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
                        window.location.href = "<?php echo base_url('master/city') ?>";
//                        $('input').val('');
//                        $("#state_id").val(null).trigger("change");
//                        table.draw();
//                        show_notify('State Added Successfully!', true);
                    } else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/city') ?>";
                    }
                    $('.module_save_btn').removeAttr('disabled', 'disabled');
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
                    data: 'id_name=city_id&table_name=city',
                    success: function (data) {
                        table.draw();
                        show_notify('City Deleted Successfully!', true);
                    }
                });
            }
        });


    });
</script>