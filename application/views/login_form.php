<?= $this->session->flashdata('msg') ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login | <?php echo PACKAGE_NAME; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" href="<?= base_url(); ?>assets/dist/img/logo_favicon.png" sizes="32x32" />
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css');?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">

        <!----------------Notify---------------->
        <link rel="stylesheet" href="<?=base_url('assets/plugins/notify/jquery.growl.css');?>">
        <!----------------Notify---------------->

        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
    </head>
    <body class="" style="background-color: #ecf0f5;">
        <div class="login-box" style="border-radius:50px; margin: 2% auto;">
            <div class="login-logo">
                <img src="<?php echo base_url(); ?>assets/dist/img/logo.jpg" width="250px" style="transform: scale(1.1); box-shadow: 0px 2px 10px #000000; border-radius: 20px;">
            </div>
            <!-- /.login-logo -->
            <div>
                <div class="col-xs-12" style="background-color: #ecf0f5; color: white; border-radius:7px 7px 0px 0px;" >
                    <p class="login-box-msg text-black" style="padding: 10px; font-size: large; ">Sign in </p>
                </div>
                <div class="login-box-body" style="box-shadow: 0px 2px 10px #000000; border-radius: 7px;">
                    <label id="username-error" class="text-danger login-box-msg" style="padding-left:80px;" for="invalid"><?php echo isset($errors['invalid'])?$errors['invalid']:''; ?></label>
                    <form action="<?php echo base_url('auth/login');?>" method="post">
                        <div class="form-group has-feedback" style="margin-bottom: 0px;">
                            <input type="text" autofocus class="form-control" placeholder="User Name"  name="user_name" id="email" value="<?=set_value('user_name')?>" style="border-radius:10px">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            <label id="username-error" class="text-danger" for="email"><?php echo isset($errors['user_name'])?$errors['user_name']:''; ?></label>
                        </div>
                        <div class="form-group has-feedback" style="margin-bottom: 0px;">
                            <input type="password" class="form-control" placeholder="Password" name="user_password" value="" style="border-radius:10px">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <label id="password-error" class="text-danger" for="pass"><?php echo isset($errors['user_password'])?$errors['user_password']:''; ?></label>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <button type="submit" class="btn btn-primary" style="border-radius:10px; width: 100px;">Sign In</button>
                            </div>
                           
                            <!-- /.col -->
                        </div>
                    </form>
                    <div class="social-auth-links text-center">
                        <!--<a href="#">I forgot my password</a>-->
                    </div>
                    <!-- /.social-auth-links -->
                    <div class="support_div text-center" style="margin-top: 30px; padding: 0px 10px 10px 10px; border:1px solid #d2d6de; border-radius: 10px;">
                        <h4 class="text-black"><strong>Support</strong></h4>
                        <div class="row">
                            <div class="pull-left" style="width: 49%">
                                <div class="col-md-12">
                                    <div class="text-black text-bold" style="border-bottom: 1px solid; padding-bottom: 5px;">Vipul Shah</div>
                                    <small class="text-bold">Call</small> <a href="tel:9727691355"><i class="fa fa-phone"></i> 9727691355</a><br>
                                    <small class="text-bold">Or</small> &nbsp; <a href="https://wa.me/919727691355" target="_blank"><i class="fa fa-whatsapp text-bold" style="color: green;"></i> WhatsApp &nbsp;&nbsp;</a>
                                </div>
                            </div>
                            <div class="pull-left" style="width: 49%">
                                <div class="col-md-12">
                                    <div class="text-black text-bold" style="border-bottom: 1px solid; padding-bottom: 5px;">Prashant Bhatt</div>
                                    <small class="text-bold">Call</small> <a href="tel:9913411672"><i class="fa fa-phone"></i> 9913411672</a><br>
                                    <small class="text-bold">Or</small> &nbsp; <a href="https://wa.me/919913411672" target="_blank"><i class="fa fa-whatsapp text-bold" style="color: green;"></i> WhatsApp &nbsp;&nbsp;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.login-box-body -->
            </div>
            <!-- /.login-box -->
            <!-- forgot password modal -->
            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="forgot" name="forgot">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Forgot password</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Please enter your registerd email address">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
        <!-- jQuery 2.2.3 -->
        <script src="<?=base_url('assets/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
        <!-- iCheck -->
        <script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
        <!-- notify -->
        <script src="<?php echo base_url('assets/plugins/notify/jquery.growl.js');?>"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            $(document).on('submit', '#forgot', function () {
                $("#forgot").css({ 'filter': "blur(1px)" });
                if($.trim($('#user_email').val()) != ''){
                    var postData = new FormData(this);
                    $.ajax({
                        url: "<?=base_url('auth/forgot_pwd') ?>",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        cache: false,
                        //fileElementId	:'account_image',
                        data: postData,
                        success: function (response) {
                            $("#forgot").css({ 'filter': "blur(0px)" });
                            var json = $.parseJSON(response);
                            if (json['success'] == 'Added'){
                                $('#modal-default').modal('toggle');
                                //show_notify('A new password has been sent to your e-mail address',true);
                                window.location.href = "<?php echo base_url('auth/login') ?>";
                                return false;
                            }
                            if(json['error'] == 'errorAdded'){
                                show_notify('Some error has occurred !',false);
                                return false;
                            }
                            if(json['error'] == 'EmailNotExist'){
                                show_notify('E-mail address not found!',false);
                                return false;
                            }
                            return false;
                        },
                    });
                    return false;
                }else{
                    show_notify('Please Enter email address for recover !',false);
                    return false;
                }

            });

            function show_notify(notify_msg,notify_type){
                if(notify_type == true){
                    $.growl.notice({ title:"Success!",message:notify_msg});
                }else{
                    $.growl.error({ title:"False!",message:notify_msg});
                }
            }
        </script>
    </body>
</html>

<?php $this->load->view('success_false_notify'); ?>
