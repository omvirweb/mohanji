<?= $this->session->flashdata('msg') ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>ShipmentSmart | Register</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?= base_url();?>assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="http://localhost/ship/assets/plugins/iCheck/all.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="<?= base_url('assets/plugins/iCheck/square/blue.css');?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/AdminLTE.min.css');?>">
		<!-- ==== Progress Loader ===== -->
		<link rel="stylesheet" href="<?= base_url('assets/dist/css/loader.css');?>">
		<!----------------Notify---------------->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/notify/jquery.growl.css');?>">
		<!----------------Notify---------------->
		<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
	</head>
	<body class="hold-transition login-page" style="background-color: #183650;">
		<div id="loader"></div>
		<div class="col-md-10 col-md-push-1" >
			<div class="col-md-6 col-md-push-3">
				<center> <img src="<?= base_url();?>assets/image/logo.png" class="img-responsive"></center>
			</div>
			<!-- /.login-logo -->

			<div class="clearfix"></div>

			<div class="col-xs-12"  style="background-color: #B8D241; color: white; border-radius:7px 7px 0px 0px;" >
				<p class="register-box-msg" style="margin-top: 14px;font-size: large; "><b>Register a new membership</b></p>
			</div>

			<form id="form_register" enctype="multipart/form-data" data-parsley-trigger="keyup" data-parsley-validate>
				<div class="register-box-body" style="box-shadow:-4px 5px 4px 0 rgba(242,242,242,.4); border-radius: 7px;">
					<div class="clearfix"></div>
					<div class="row"style="margin-top:10px;">
						<div class="col-md-4">
							<div class="form-group has-feedback">
								<select class="form-control"  id='type_select' name="user_type" >
									<option>-- User type --</option>
									<option value="2">Shipper</option>
									<option value="3">Service Provider</option>
								</select>
							</div>
						</div>

						<!--    Hide show elements    -->
						<div class="col-md-4">
							<div class="form-group has-feedback" style='display:none;' id='cin'>
								<!--<label>CIN:</label>-->
								<input type="text" class="form-control" placeholder="Enter CIN" id="CIN_no" name="cin_no" pattern="[A-Za-z0-9]{21}" oninvalid="setCustomValidity('Plz enter only 21 character CIN no.')" onchange="try{setCustomValidity('')}catch(e){}">
							</div>

							<div class="form-group has-feedback" id="servc_prov" style='display:none;' >
								<!--<label>Service Provide:</label>-->
								<select class="form-control select2" name="service_provide" id="service_type"></select>
							</div>


						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback" id="gstn" style='display:none;'>
								<!--                            <label>GSTN:</label>-->
								<input type="text" class="form-control" placeholder="Enter GSTN" id="gstin_no" name="gstin_no" pattern="[A-Za-z0-9]{15}" oninvalid="setCustomValidity('Plz enter only 15 character GST no.')" onchange="try{setCustomValidity('')}catch(e){}">
							</div>
						</div>
						<!--    /Hide show elements    -->

						<div class="col-md-4">

						</div>

						<span class="clearfix"></span>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Company Name:</label>-->
								<input type="text" class="form-control" placeholder="Company name" id="company_name" name="company_name" required>
								<span class="glyphicon glyphicon-align-justify form-control-feedback"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Company Email:</label>-->
								<input type="email" class="form-control" placeholder="Company Email" id="company_email" name="company_email" required>
								<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Company Contact No.:</label>-->
								<input type="tel" class="form-control" placeholder="Company Contact no." id="company_contact_no" name="company_contact_no" pattern="^\d{10}$" required oninvalid="setCustomValidity('Plz enter only 10 digit no.')" oninput="try{setCustomValidity('')}catch(e){}">
								<span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>
							</div>
						</div>

						<span class="clearfix"></span>
						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Contact Person:</label>-->
								<input type="text" class="form-control" placeholder="Contact Person" id="contact_person" name="contact_person" required>
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Mobile Number:</label>-->
								<input type="tel" class="form-control" placeholder="Mobile number" id="mobile_number" name="mobile_number" pattern="^\d{10}$" required oninvalid="setCustomValidity('Plz enter only 10 digit no.')" oninput="try{setCustomValidity('')}catch(e){}">
								<span class="glyphicon glyphicon-phone form-control-feedback"></span>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<!--<label>Phone Number:</label>-->
								<input type="tel" class="form-control" placeholder="Phone Number" id="phone_number" name="phone_number" pattern="^\d{10}$" required oninvalid="setCustomValidity('Plz enter only 10 digit no.')" oninput="try{setCustomValidity('')}catch(e){}">
								<span class="glyphicon glyphicon-earphone form-control-feedback"></span>
							</div>
						</div>

						<span class="clearfix"></span>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<select class="form-control select2" id="country_id" name="country_id" required>
									<option value="">-- Country --</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<select class="form-control select2" id="state_id" name="state_id" required>
									<option value="">-- State --</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group has-feedback">
								<select class="form-control select2" id="city_id" name="city_id">
									<option value="">-- City --</option>
								</select>
							</div>
						</div>

						<section class="col-md-4 connectedSortable">
							<div class="form-group has-feedback">
								<!--<label>Address:</label>-->
								<input type="text" class="form-control" placeholder="Address" id="address" name="address" required>
								<span class="glyphicon glyphicon-home form-control-feedback"></span>
							</div>

							<div class="form-group has-feedback">
								<!--<label>User Name :</label>-->
								<input type="text" class="form-control" placeholder="Nick Name" id="email_id" name="email_id" value="" required>
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
						</section>

						<section class="col-md-4 connectedSortable">
							<div class="form-group has-feedback">
								<!--<label>Pin Code:</label>-->
								<input type="text" class="form-control" placeholder="Pin Code" id="pincode" name="pincode" required>
								<span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
							</div>

							<div class="form-group has-feedback">
								<!--<label>Password:</label>-->
								<input type="password" class="form-control" placeholder="Password" id="password" name="password" value="" required>
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
						</section>

						<div class="col-md-4">
							<div class="form-group has-feedback pull-right">
								<!-- Local Server Key-->
								<!--6LfWnjUUAAAAAIkxODTZo45CKZX1FmKtDH7IM6wO-->
								<div class="g-recaptcha" data-sitekey="6LfWnjUUAAAAAIkxODTZo45CKZX1FmKtDH7IM6wO"></div>
							</div>
						</div>

					</div>


					<div class="row">
						<br>
						<div class="col-md-4 pull-left">
							<label>&nbsp;</label>
							<input type="checkbox" id="agree"> &nbsp;&nbsp; I agree to the <a href="<?=base_url('auth/terms_conditions');?>" target="_blank">Terms & Conditions</a>
						</div>

						<div class="col-md-4 pull-right">
							<input type="submit" class="btn btn-primary btn-block btn-flat" id="register" value="Register"/>
						</div>

						<div class="clearfix"></div>
						<div class="col-md-4 pull-right" style="margin-top: 10px;"> 
							<a href="login" style="float: right;">I already have a membership</a>
						</div>
					</div>
				</div>
			</form>
			<!-- /.register-box-body -->
		</div>
		<!-- /.register-box -->

		<!-- jQuery 2.2.3 -->
		<script src="<?=base_url('assets/plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="<?=base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
		<!-- iCheck -->
		<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
		<!-- select 2 -->
		<link rel="stylesheet" href="<?=base_url('assets/plugins/s2/select2.css');?>">
		<script src="<?=base_url('assets/plugins/s2/select2.full.js');?>"></script>

		<!-- notify -->
		<script src="<?php echo base_url('assets/plugins/notify/jquery.growl.js');?>"></script>
		<!-- recaptcha -->
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.select2').select2();
				document.getElementById("loader").style.display = "none";
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
				initAjaxSelect2($("#country_id"),"<?=base_url('app/country_select2_source')?>");
				initAjaxSelect2($("#service_type"),"<?=base_url('app/service_type_select2_source')?>");
				$('#country_id').on('change', function() {
					$("#state_id").empty().trigger('change');
					var country_id = this.value;
					initAjaxSelect2($('#state_id'),"<?=base_url('app/state_select2_source')?>/"+country_id);
				});
				$('#state_id').on('change', function() {
					$("#city_id").empty().trigger('change');
					var state_id = this.value;
					initAjaxSelect2($('#city_id'),"<?=base_url('app/city_select2_source')?>/"+state_id);
				});

				$('#type_select').select2({
					minimumResultsForSearch: -1
				});
				
				var detail_flag = 0;
				$('#CIN_no').on('input', function() {
					var cin_no = this.value;
					if ( this.value != '' && this.value.length >= 21){
						jQuery.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>" + "/Ajax_post_controller/getDataByCIN",
							dataType: 'json',
							data: {cin_no: cin_no},
							success: function(res) {
								//console.log(res);
								if(res){
									detail_flag = 1;
									//console.log(res);
									$('#gstin_no').val(res['gstin']);
									$('#company_name').val(res['company_name']);
									if(res['company_name'] != null){
										$('#company_name').prop('readonly', true);
									}
									$('#company_email').val(res['email_id']);
									$('#company_contact_no').val(res['company_contact_no']);
									$('#contact_person').val(res['contact_person']);
									$('#mobile_number').val(res['cp_mobile_no']);
									$('#phone_number').val(res['cp_phone_no']);
									$('#address').val(res['address']);
									$('#pincode').val(res['address_pincode']);
									if(res['address_country']){
										setSelect2Value($("#country_id"),"<?=base_url('app/set_country_select2_val_by_id/')?>"+res['address_country']);
									}
									if(res['address_state']){
										setSelect2Value($("#state_id"),"<?=base_url('app/set_state_select2_val_by_id/')?>"+res['address_state']);
									}
									if(res['address_city']){
										setSelect2Value($("#city_id"),"<?=base_url('app/set_city_select2_val_by_id/')?>"+res['address_city']);
									}
								}else if(res == false){
									if(detail_flag == 0){
										//$('#gstin_no').val('');
										$('#company_name').prop('readonly', false);
										$('#company_name').val('');
										$('#company_email').val('');
										$('#company_contact_no').val('');
										$('#contact_person').val('');
										$('#mobile_number').val('');
										$('#phone_number').val('');
										$('#address').val('');
										$('#pincode').val('');

										$("#country_id").select2('val', 'All');
										$("#state_id").select2('val', 'All');
										$("#city_id").select2('val', 'All');   
									}
								}
							}
						});
					}
				});

				$('#gstin_no').on('input', function() {
					var gstin_no = this.value;
					if ( this.value != '' && this.value.length >= 15){
						jQuery.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>" + "/Ajax_post_controller/getDataByGSTIN",
							dataType: 'json',
							data: {gstin: gstin_no},
							success: function(res) {
								if(res){
									detail_flag = 1;
									//console.log(res);
									$('#CIN_no').val(res['cin_no']);
									$('#company_name').val(res['company_name']);
									if(res['company_name'] != null){
										$('#company_name').prop('readonly', true);   
									}
									$('#company_email').val(res['email_id']);
									$('#company_contact_no').val(res['company_contact_no']);
									$('#contact_person').val(res['contact_person']);
									$('#mobile_number').val(res['cp_mobile_no']);
									$('#phone_number').val(res['cp_phone_no']);
									$('#address').val(res['address']);
									$('#pincode').val(res['address_pincode']);
									if(res['address_country']){
										setSelect2Value($("#country_id"),"<?=base_url('app/set_country_select2_val_by_id/')?>"+res['address_country']);
									}
									if(res['address_state']){
										setSelect2Value($("#state_id"),"<?=base_url('app/set_state_select2_val_by_id/')?>"+res['address_state']);
									}
									if(res['address_city']){
										setSelect2Value($("#city_id"),"<?=base_url('app/set_city_select2_val_by_id/')?>"+res['address_city']);
									}
								}else if(res == false){
									if(detail_flag == 0){
										//$('#CIN_no').val('');
										$('#company_name').prop('readonly', false);
										$('#company_name').val('');
										$('#company_email').val('');
										$('#company_contact_no').val('');
										$('#contact_person').val('');
										$('#mobile_number').val('');
										$('#phone_number').val('');
										$('#address').val('');
										$('#pincode').val('');

										$("#country_id").select2('val', 'All');
										$("#state_id").select2('val', 'All');
										$("#city_id").select2('val', 'All');	
									}
								}
							}
						});
					}
				});

				//Usertype dropdown input show hide
				$('#type_select').on('change', function() {
					if ( this.value == '2'){
						$("#cin").show();
						$("#gstn").show();
						$("#servc_prov").hide();
						$("#CIN_no").prop('required',true);
					}else if(this.value == "3"){
						$("#cin").hide();
						$("#gstn").show();
						$("#servc_prov").show();
						$("#CIN_no").prop('required',false);
						$("#gstin_no").prop('required',true);
						$("#service_type").prop('required',true);
					}                          
				});
			});

			$(document).on('submit', '#form_register', function () {
				if ($("#agree").is(":checked")) {
					var postData = new FormData(this);
					$("#form_register").css({ 'filter': "blur(1px)" });
					document.getElementById("loader").style.display = "block";
					$.ajax({
						url: "<?=base_url('auth/save_user') ?>",
						type: "POST",
						processData: false,
						contentType: false,
						cache: false,
						//fileElementId	:'account_image',
						data: postData,
						success: function (response) {
							document.getElementById("loader").style.display = "none";
							var json = $.parseJSON(response);
							if (json['success'] == 'Added'){
								window.location.href = "<?php echo base_url('auth/register') ?>";
							}
							if(json['error'] == 'errorAdded'){
								$("#form_register").css({'filter': "blur(0px)"});
								show_notify('Some error has occurred !',false);
								return false;
							}
							if(json['error'] == 're-enter-reCAPTCHA'){
								$("#form_register").css({'filter': "blur(0px)"});
								show_notify('Please re-enter your reCAPTCHA',false);
								return false;
							}
							if (json['success'] == 'Updated'){
								window.location.href = "<?php echo base_url('') ?>";
							}
							if(json['error'] == 'EmailExist'){
								$("#form_register").css({'filter': "blur(0px)"});
								show_notify('E-mail address already exist!',false);
								$( "#company_email" ).focus();
								return false;
							}
							return false;
						},
					});
					return false;

				}else{
					show_notify('Please Accept Terms and conditions !',false);
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

			/**
            * @param $selector
            * @constructor
            */
			function initAjaxSelect2($selector,$source_url){
				$selector.select2({
					placeholder: " --Select-- ",
					allowClear: true,
					width:"100%",
					ajax: {
						url: $source_url,
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								q: params.term, // search term
								page: params.page
							};
						},
						processResults: function (data,params) {
							params.page = params.page || 1;
							return {
								results: data.results,
								pagination: {
									more: (params.page * 5) < data.total_count
								}
							};
						},
						cache: true
					}
				});
			}

			function setSelect2Value($selector,$source_url = ''){
				if($source_url != '') {
					$.ajax({
						url: $source_url,
						type: "GET",
						data: null,
						contentType: false,
						cache: false,
						async: false,
						processData: false,
						dataType: 'json',
						success: function (data) {
							if (data.success == true) {
								$selector.empty().append($('<option/>').val(data.id).text(data.text)).val(data.id).trigger("change");
							}
						}
					});
				} else {
					$selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
				}
			}
		</script>
	</body>
</html>
