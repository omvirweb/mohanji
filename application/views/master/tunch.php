<?php $this->load->view('success_false_notify'); ?>
<div class="content-wrapper" id="body-content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tunch
            <?php $isEdit = $this->app_model->have_access_role(TUNCH_MODULE_ID, "edit");
            $isView = $this->app_model->have_access_role(TUNCH_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(TUNCH_MODULE_ID, "add"); ?>
            <?php if (isset($carat_data->carat_id) && !empty($carat_data->carat_id)) { } else { if($isAdd) { $btn_disable = null; } else { $btn_disable = 'disabled';} } ?> 
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/tunch') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add Tunch </a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                
                <?php if($isAdd || $isEdit) { ?>
                <!-- Horizontal Form -->
                    <div class="col-md-6 col-md-push-6">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="clearfix"></div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <form class="form-horizontal" id="tunch_form" action="<?= base_url('master/save_tunch') ?>" method="post" novalidate enctype="multipart/form-data">                                    
                                                <?php if (isset($carat_data->carat_id) && !empty($carat_data->carat_id)) { ?>
                                                    <input type="hidden" name="carat_id" class="carat_id" value="<?= $carat_data->carat_id ?>">
                                                <?php } ?>
                                                <label for="tunch">Tunch<span class="required-sign">&nbsp;*</span></label>
                                                <input type="text" name="purity" id="tunch" class="form-control num_only" autofocus value="<?= (isset($carat_data->purity)) ? $carat_data->purity : ''; ?>"><br />
                                                <div class="clrearfix"></div>
                                                <label for="show_in_xrf">Show in XRF</label> &nbsp;
                                                <input type="checkbox" name="show_in_xrf" id="show_in_xrf" <?= isset($carat_data->show_in_xrf) && !empty($carat_data->show_in_xrf) ? 'checked' : '' ?> >
                                                <div class="clrearfix"></div>
                                                <button type="submit" class="btn btn-primary btn-sm pull-right module_save_btn" <?= isset($carat_data->carat_id) ? '' : $btn_disable;?>><?= isset($carat_data->carat_id) ? 'Update' : 'Save' ?> [ Ctrl + S ]</button>
                                                <?php if (isset($carat_data->carat_id) && !empty($carat_data->carat_id)) { ?>
                                                <div class="created_updated_info">
                                                    Created by : <?php echo (isset($carat_data->created_by_name)) ? $carat_data->created_by_name : '' ; ?> 
                                                    @ <?php echo (isset($carat_data->created_at)) ? date('d-m-Y h:i A',strtotime($carat_data->created_at)) : '' ; ?><br/> 
                                                    Updated by : <?php echo (isset($carat_data->updated_by_name)) ? $carat_data->updated_by_name : '' ; ?> 
                                                    @ <?php echo (isset($carat_data->updated_at)) ? date('d-m-Y h:i A',strtotime($carat_data->updated_at)) : '' ; ?> 
                                                </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-md-6 col-md-pull-6">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <?php if($isView){ ?>
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <table id="carat_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Action</th>
                                                    <th>Tunch</th>                                                    
                                                    <th>Show in XRF</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
</div>
<script>
    $(document).ready(function () {
        table = $('#carat_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering":[1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/carat_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                },
            },

        });
    
        $(document).bind("keydown", function(e){
            if(e.ctrlKey && e.which == 83){
                $("#tunch_form").submit();
                return false;
            }
        });

        $(document).on('submit', '#tunch_form', function () {
            $(window).unbind('beforeunload');
            if ($.trim($("#tunch").val()) == '') {
                show_notify('Please Enter Tunch ', false);
                $("#tunch").focus();
                return false;
            }
            $('.module_save_btn').attr('disabled', 'disabled');
            var postData = new FormData(this);
            $.ajax({
                url: "<?= base_url('master/save_tunch') ?>",
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
                        $('input').val('');
                        $('#show_in_xrf').attr('checked', false);
                        $('#carat_id').find('option').remove().end().val('whatever');
                        table.draw();
                        show_notify('Tunch Added Successfully!', true);
                    }else if (json['success'] == 'Updated') {
                        window.location.href = "<?php echo base_url('master/tunch') ?>";
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
                    data: 'id_name=carat_id&table_name=carat',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Tunch. This Tunch has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('Tunch Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

    });
</script>
