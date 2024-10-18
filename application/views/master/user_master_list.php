<?php $this->load->view('success_false_notify'); ?>
<style type="text/css">
    table#user_master_table > tbody > tr > td:nth-of-type(6){
        color: blue;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User List
            <?php
            $isView = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "view");
            $isAdd = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "add"); 
            $role_logout = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "allow logout option");
            $role_view_password = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "view password");
            ?>
            <?php if($isAdd){ ?>
                <a href="<?= base_url('master/user_master') ?>" class="btn btn-primary btn-sm pull-right" style="margin: 5px;">Add User</a>
            <?php } ?>
        </h1>
    </section>
    <div class="clearfix">
        <div class="row">
            <div style="margin: 15px;">
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php if($isView) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body table-responsive">
                                        <?php if($role_logout) { ?>
                                            <div class="col-md-2">
                                                <label>Type</label>
                                                <select name="user_type" id="user_type" class="form-control select2" >
                                                    <option value="">All</option>
                                                    <option value="1">Online Users</option>
                                                    <option value="0">Offline Users</option>                                                
                                                </select>
                                            </div>
                                        <?php } else {?>
                                        <input type="hidden" name="user_type" id="user_type" value="">
                                        <?php }?>
                                        <div class="col-md-4">
                                            <label>Default Department</label>
                                            <select name="default_department_id" id="default_department_id" class="form-control select2"></select>
                                        </div>
                                        <div class="clearfix"></div><br />
                                        <table id="user_master_table" class="table row-border table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="80px;">Action</th>
                                                    <th>User Type</th> 
                                                    <th>User No.</th>
                                                    <th>User Name</th>
                                                    <th>Mobile No.</th>
                                                    <th>Default Department</th>
                                                    <th>Department</th>
                                                    <th>Order Department</th>
                                                    <th>Password</th>
                                                    <th>Salary</th>
                                                    <th>Balance</th>
                                                    <th>Files</th>
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
            </div>
        </div>
    </div>
</div>
<div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    Document Image &nbsp; &nbsp;
                    <a href="javascript:void(0);" class="btn_print_image" title="Print"><span class="glyphicon glyphicon-print"></span></a>
                </h4>
            </div>
            <div class="doc_img_src_div modal-body edit-content">
                <img id="doc_img_src" src="" class="img-responsive" height='500px' width='300px'>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var table;
    $(document).ready(function () {
        $('.select2').select2();
        initAjaxSelect2($('#default_department_id'), "<?= base_url('app/department_select2_source') ?>/");
        
        $(document).on('click', '.image_model', function () {
            let src = $(this).data("img_src");
            setTimeout(function () {
                $("#doc_img_src").attr('src', src);
            }, 0);
            $('#edit-modal').modal('show');
        });
        $('#ajax-loader').show();
        table = $('#user_master_table').DataTable({
            "serverSide": true,
            "scrollY": "480px",
            "scrollX": true,
            "search": true,
            "paging": false,
            "ordering": [1, "desc"],
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('master/user_master_datatable') ?>",
                "type": "POST",
                "data": function (d) {
                    d.user_type = $('#user_type').val();
                    d.default_department_id = $('#default_department_id').val();
                },
                "complete": function () {
                    $('#ajax-loader').hide();
                },
            },
            "columnDefs": [{
                "className": "dt-right",
                "targets": [9,10], // Salary column
            }]
        });
    
        <?php if($role_view_password){ ?>
        <?php } else { ?>
            table.columns([8]).visible( false, false ); // Password Column
        <?php } ?>

        $(document).on("click", ".delete_button", function () {
            if(confirm('Are you sure delete this records?')){
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: '',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('You cannot delete this Account. This Account has been used.', false);
                        } else if (json['success'] == 'Deleted') {
                            table.draw();
                            show_notify('User Deleted Successfully!', true);
                        }
                    }
                });
            }
        });

        $(document).on("click", ".btn_print_image", function () {
            var mywindow = window.open('', 'print_div');
            mywindow.document.write('<html><head><title>Print Window</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write("<img src='"+ $("#doc_img_src").attr('src') +"' style='height:100%;width:100%;'>");
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();
            mywindow.print();
            mywindow.close();
            return true;
        });

        $(document).on("click", ".status_btn", function () {
            var status = $(this).data('status');
            if(status == '1'){
                var msg = 'Are you sure you want to Inactive this User?';
            }
            if(status == '0') {
                var msg = 'Are you sure you want to Active this User?';
            }
            if (confirm(msg)) {
                $.ajax({
                    url: $(this).data('href'),
                    type: "POST",
                    data: {status : status},
                    success: function (response) {
                        var json = $.parseJSON(response);
                        if (json['error'] == 'Error') {
                            show_notify('Something Went Wrong!!.', false);
                        } else if (json['success'] == 'Updated') {
                            table.draw();
                            show_notify('User Status updated successfully!', true);
                        }
                    }
                });
            }
        });

        $(document).on("click", ".is_login", function () {
            var msg = 'Are you sure you want to Logout this User?';
            if (confirm(msg)) {
                var loggedin_user_id = '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>';
                var user_id = $(this).data('user_id');
                change_user_status_logout(loggedin_user_id, user_id);
            }
        });
        
        $(document).on('change', '#user_type', function(){
           table.draw(); 
        });
        
        $(document).on('change', '#default_department_id', function(){
           table.draw(); 
        });
    });
    
    function user_successfully_logout(){
        table.draw();
        show_notify('User Logged Out Successfully!', true);
    }
    
    function readURL(input) {
        if (input.files && input.files[0]) {
//            console.log(input.files);
            $("#ajax-loader").show();
            var form = new FormData();
            var myFormData = document.getElementById('file_upload').files[0];
            form.append('file_upload', myFormData);
            form.append('action', 'get_temp_path');
            $.ajax({
                type: 'POST',
                processData: false,
                contentType: false,
                data: form,
                url: "<?= base_url('master/get_temp_path_image') ?>",
                success: function (html) {
                    $('#image').val(html);
                    $("#ajax-loader").hide();
                }
            });
        }
    }
</script>
