<?php if ($this->session->flashdata('success') == true) { ?>
    <script>
        $( document ).ready(function() {
            show_notify('<?php echo $this->session->flashdata('message'); ?>',true);    
        });
    </script>
<?php } ?>
<?php $this->load->library('form_validation');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<form name="update_profile" id="update_profile" method="post">
				<input type="hidden" name="user_id" id="user_id" value="<?=$user_detail->user_id; ?>" >
			<div class="col-md-3">
				<div class="basic_info_div">
					<!-- Profile Image -->
					<div class="box box-primary">
						<div class="overlay" style="display: none" ><i class="fa fa-refresh fa-spin"></i></div>
						<div class="box-body box-profile">
							<?php if(!empty($user_detail->profile_image)){ ?>
								<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/uploads/profile_image/<?=$user_detail->profile_image; ?>" alt="User profile picture">
							<?php } else { ?>
								<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/image/profile-pic.jpg" alt="User profile picture">
							<?php } ?>
							<h3 class="profile-username text-center"><?=$user_detail->nickname; ?></h3>
							<p class="text-muted text-center"><?=$user_detail->company_name; ?></p>
							<center>
								<a href="#facebook"><i class="fa fa-facebook-square fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>&nbsp;
								<a href="#instagram"><i class="fa fa-instagram fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>&nbsp;
								<a href="#google_plus"><i class="fa fa-google-plus-square fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>&nbsp;
								<a href="#pinterest" ><i class="fa fa-pinterest-square fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>&nbsp;
								<a href="#twitter" ><i class="fa fa-twitter-square fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>&nbsp;
								<a href="#linkedin" ><i class="fa fa-linkedin-square fa-2x" aria-hidden="true" style="color:#3c8dbc;"></i></a>
							</center>
							<span class="clearfix"></span><br>
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b>Email</b> <a class="pull-right"><?=$user_detail->email_id; ?></a>
								</li>
								<li class="list-group-item">
									<b>Contact No.</b> <a class="pull-right"><?=$user_detail->company_contact_no; ?></a>
								</li>
								<li class="list-group-item">
									<b>City</b> <a class="pull-right"><?=!empty($user_detail->city)?$user_detail->city:''; ?>, <?=!empty($user_detail->state)?$user_detail->state:''; ?> - <?=!empty($user_detail->country)?$user_detail->country:''; ?> </a>
								</li>
							</ul>
							<a class="btn btn-primary btn-block edit_profile">Edit</a>
							<button type="submit" class="btn btn-success btn-block update_profile_btn" style="display: none;">Update</button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
					<div><a href="<?= base_url()?>auth/change_password" class="btn btn-primary" align="center">Change Password</a><br /><br /></div>
					<!--      Collapsible Accordion       -->
					<div class="box box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Support</h3>
						</div>
						<div class="box-body">
							<div class="box-group" id="accordion">
								<div class="panel box box-info">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
										<div class="box-header with-border">
											<h4 class="box-title text-info">Email</h4>
										</div>
									</a>
									<div id="collapseOne" class="panel-collapse collapse">
										<div class="box-body">support@shipmentsmart.com</div>
									</div>
								</div>
								
								<div class="panel box box-primary">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
										<div class="box-header with-border">
											<h4 class="box-title text-info">Contact No.</h4>
										</div>
									</a>
									<div id="collapseTwo" class="panel-collapse collapse">
										<div class="box-body">8000840084</div>
									</div>
								</div>
								<div class="panel box box-success">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
										<div class="box-header with-border">
											<h4 class="box-title text-info">Skype Chat</h4>
										</div>
									</a>
									<div id="collapseThree" class="panel-collapse collapse">
										<div class="box-body">shipmentsmart</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
            
            <div class="col-md-9">
					
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">About Me</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
						    <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>User type:</label>
                                    <?=$user_detail->user_type_name; ?>
                                </div>
                            </div>
							<?php if(!empty($user_detail->cin_no)){ ?>
								<div class="col-md-4">
									<div class="form-group has-feedback" >
										<label>CIN No.:</label>
										<?=$user_detail->cin_no; ?>
									</div>
								</div>
							<?php } ?>
							<?php if(!empty($user_detail->service_type_name)){ ?>
								<div class="col-md-4">
									<div class="form-group has-feedback" >
										<label>CIN No.:</label>
										<?=$user_detail->service_type_name; ?>
									</div>
								</div>
							<?php } ?>
							<?php if(!empty($user_detail->gstin_no)){ ?>
								<div class="col-md-4">
									<div class="form-group has-feedback" >
										<label>CIN No.:</label>
										<?=$user_detail->gstin_no; ?>
									</div>
								</div>
							<?php } ?>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Company Email:</label>
                                    <input type="email" name="email_id" id="email_id" class="form-control" value="<?=$user_detail->email_id; ?>" placeholder="Company Email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Company Contact No.:</label>
                                    <input type="text" name="company_contact_no" id="company_contact_no" class="form-control" value="<?=$user_detail->company_contact_no; ?>" placeholder="Company Contact no." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Contact Person:</label>
                                    <input type="text" name="contact_person" id="contact_person" class="form-control" value="<?=$user_detail->contact_person; ?>" placeholder="Contact Person" required>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Mobile Number:</label>
                                    <input type="text" name="cp_mobile_no" id="cp_mobile_no" class="form-control" value="<?=$user_detail->cp_mobile_no; ?>" placeholder="Mobile number" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Phone Number:</label>
                                    <input type="text" name="cp_phone_no" id="cp_phone_no" class="form-control" value="<?=$user_detail->cp_phone_no; ?>" placeholder="Phone Number" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Web Address:</label>
                                    <input type="text" name="weblink" id="weblink" class="form-control" value="<?=$user_detail->weblink; ?>" placeholder="Web Address" required>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                            
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                <label>Country:</label>
                                <select name="country_id" class="country_id form-control input-sm" id="country_id"></select>
                                </div>
                            </div>
                            
                             <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <select class="form-control select2" id="state_id" name="state_id">
                                        <option>-- State --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <select class="form-control select2" id="city_id" name="city_id">
                                        <option>-- City --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>                            
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Address:</label>
                                    <input type="text" name="address" id="address" class="form-control" value="<?=$user_detail->address; ?>" placeholder="Address" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Pin Code:</label>
                                    <input type="text" name="pincode" id="pincode" class="form-control" value="<?=$user_detail->pincode; ?>" placeholder="Pin Code" required>
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="enterprise_type_id">Enterprise Type:</label>
                                    <select name="enterprise_type_id" class="enterprise_type_id" id="enterprise_type_id"></select>
                                </div>
                            </div>
							<div class="clearfix"></div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Working Office Address:</label>
                                    <input type="text" name="office_address" id="office_address" class="form-control" value="<?=$user_detail->office_address; ?>" placeholder="Working Office Address">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Billing Currency:</label>
                                    <input type="text" name="currency" id="currency" class="form-control" value="<?=$user_detail->currency; ?>" placeholder="Billing Currency">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="enterprise_type_id">Profile Picture:</label>
                                    <input type="file" name="profile_image" id="profile_image" >
                                </div>
                            </div>
							<div class="clearfix"></div>
							<div class="box-header with-border header_col"> <h3 class="box-title">TDS Info</h3> </div>
                            <div class="clearfix"></div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label> TDS Group:</label>
                                    <select name="tds_group_id" class="tds_group_id" id="tds_group_id"></select>
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Vendor Status:</label>
                                    <input type="text" name="vendor_status" id="vendor_status" class="form-control" value="<?=$user_detail->vendor_status; ?>" placeholder="Vendor Status">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Percentage:</label>
                                    <input type="text" name="tds_per" id="tds_per" class="form-control" value="<?=$user_detail->tds_per; ?>" placeholder="Percentage">
                                </div>
                            </div>
                            <div class="clearfix"></div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>PAN No.:</label>
                                    <input type="text" name="pan_no" id="pan_no" class="form-control" value="<?=$user_detail->pan_no; ?>" placeholder="PAN No.">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>IEC Code (If applicable):</label>
                                    <input type="text" name="iec_code" id="iec_code" class="form-control" value="<?=$user_detail->iec_code; ?>" placeholder="IFC code">
                                </div>
                            </div>
							<div class="clearfix"></div>
							<div class="box-header with-border header_col"> <h3 class="box-title">Bank Details</h3> </div>
							<div class="clearfix"></div>    
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Bank Name:</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?=$user_detail->bank_name; ?>" placeholder="Bank name">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Bank Address:</label>
                                    <input type="text" name="bank_address" id="bank_address" class="form-control" value="<?=$user_detail->bank_address; ?>" placeholder="Bank Address">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Bank Account No.:</label>
                                    <input type="text" name="bank_account_no" id="bank_account_no" class="form-control" value="<?=$user_detail->bank_account_no; ?>" placeholder="Bank Account No.">
                                </div>
                            </div>
                            <div class="clearfix"></div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>MICR Code:</label>
                                    <input type="text" name="bank_micr_code" id="bank_micr_code" class="form-control" value="<?=$user_detail->bank_micr_code; ?>" placeholder="MICR Code">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Branch Name:</label>
                                    <input type="text" name="bank_branch_name" id="bank_branch_name" class="form-control" value="<?=$user_detail->bank_branch_name; ?>" placeholder="Branch Name">
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>IFSC Code:</label>
                                    <input type="text" name="bank_ifsc_code" id="bank_ifsc_code" class="form-control" value="<?=$user_detail->bank_ifsc_code; ?>" placeholder="IFSC Code">
                                </div>
                            </div>
                            <div class="clearfix"></div>
							<div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label>Swift Code:</label>
                                    <input type="text" name="bank_swift_code" id="bank_swift_code" class="form-control" value="<?=$user_detail->bank_swift_code; ?>" placeholder="Swift Code">
                                </div>
                            </div>
							<div class="clearfix"></div>
						</div>
					</div>
                
					<div class="box box-primary" style="margin-top: 20px;">
						<div class="box-header with-border"> <h3 class="box-title">Personal Preference</h3> </div>
						<div class="box-body">
							<div class="col-md-12">
								<div class="form-group">
									<label>
										<input type="checkbox" name="terms_agree_check" id="terms_agree_check" class="flat-red" <?=($user_detail->terms_agree_check == 1)? ' Checked ': ''; ?> > &nbsp;
										I agree to ShipmentSmart contacting me with updates on shipping news, port & customs updates, commodity news, market trend reports, marketing offers etc 
									</label>
									<span class="clearfix"></span>
									<label>
										<input type="checkbox" name="subscribe_check" id="subscribe_check" class="flat-red" <?=($user_detail->subscribe_check == 1)? ' Checked ': ''; ?> > &nbsp;
										Subscribe me to Shipment Tracking Notification Emails
									</label>
								</div>
							</div>
						</div>
					</div>
						
					<div class="box box-primary" style="margin-top: 20px;">
						<div class="box-header with-border">
							<h3 class="box-title">Social Media</h3>
						</div>
						<div class="box-body table-responsive">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-labelsm">Facebook link</label>
									<input type="text" name="fb_link" id="fb_link" class="form-control" value="<?=$user_detail->fb_link; ?>" >
								</div>
								<div class="form-group">
									<label class="control-label">Instagram link</label>
									<input type="text" name="insta_link" id="insta_link" class="form-control" value="<?=$user_detail->insta_link; ?>" >
								</div>
								<div class="form-group">
								   <label class="control-label">Google plus link</label>
									<input type="text" name="google_plus_link" id="google_plus_link" class="form-control" value="<?=$user_detail->google_plus_link; ?>" >
								</div> 
							</div>
							<div class="col-md-6">
								<div class="form-group">
								   <label class="control-label">Pinterest link</label>
									<input type="text" name="pintrest_link" id="pintrest_link" class="form-control" value="<?=$user_detail->pintrest_link; ?>" >
								</div> 
								<div class="form-group">
								   <label class="control-label">Twitter link</label>
									<input type="text" name="twitter_link" id="twitter_link" class="form-control" value="<?=$user_detail->twitter_link; ?>" >
								</div> 
								<div class="form-group">
								   <label class="control-label">LinkedIn link</label>
									<input type="text" name="linkedin_link" id="linkedin_link" class="form-control" value="<?=$user_detail->linkedin_link; ?>" >
								</div> 
							</div>
						</div>
					</div>
					
					
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Documents Manager</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
							   <div class="col-md-3">
								<label for="exampleInputFile">Address Proof &nbsp;&nbsp;
								<div class="box-tools pull-right" data-toggle="tooltip" title="Copy of Electricity Bill / Telephone Bill / Any documents for residential proof of owner / director of firm">
									<i class="fa fa-question-circle fa-lg"></i>
								</div><br>
								<label for="exampleInputFile">&nbsp;</label>
								</label>
								<input type="file" id="exampleInputFile"><br/>
								<button class="btn btn-success">Upload</button>
								</div>
							  
								<div class="col-md-3">
								<label for="exampleInputFile">PAN Card</label><br>
								<label for="exampleInputFile">&nbsp;</label>
								<input type="file" id="exampleInputFile"><br/>
								<button class="btn btn-success">Upload</button>
								</div>
								
							  
								<div class="col-md-3">
								<label for="exampleInputFile">Copy Of Cancel Cheque</label><br>
								<label for="exampleInputFile">&nbsp;</label>
								<input type="file" id="exampleInputFile"><br/>
								<button class="btn btn-success">Upload</button>
								</div>
							  
							  
								<div class="col-md-3">
								<label for="exampleInputFile">Company Registartion Certificate</label>
								<input type="file" id="exampleInputFile"><br/>
								<button class="btn btn-success">Upload</button>
								</div>
								
								<span class="clearfix"></span>
							 
							  <div class="col-md-12"><br/>
								<p><b>Note :</b><br/>
								  <ol>
									<li> Put a note below document management box ends.</li>
									<li> If Transport service - Mandatory Transport declaretion form.</li>
									<li> Pan No : If Vendor don't have company pan no and he provied the owner pan no - Need to submit pan declaration.</li>
									<li> If Vendor Supply of Material – TIN and CST only applicable.</li>
									<li> If Service – TDS and Service Tax Only applicable.</li>
									<li> If Service and Supply – TIN,CST,TDS and Service Tax applicable.</li>
								  </ol>
							  </div>
  
							</div>
						</div>
					</div>
					
			</div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>-->
<!-- iCheck -->
<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
<script>
	$(window).ready(function(){
		$("#update_profile :input").prop("disabled", true);
	});
	$(document).ready(function(){
		
		initAjaxSelect2($("#city_id"),"<?=base_url('app/city_select2_source')?>");
		<?php if(isset($user_detail->city_id) && !empty($user_detail->city_id)){ ?>
			setSelect2Value($("#city_id"),"<?=base_url('app/set_city_select2_val_by_id/'.$user_detail->city_id)?>");
		<?php } ?>
        initAjaxSelect2($("#state_id"),"<?=base_url('app/state_select2_source')?>");
        <?php if(isset($user_detail->state_id) && !empty($user_detail->state_id)){ ?>
			setSelect2Value($("#state_id"),"<?=base_url('app/set_state_select2_val_by_id/'.$user_detail->state_id)?>");
		<?php } ?>
        initAjaxSelect2($("#country_id"),"<?=base_url('app/country_select2_source')?>");
        <?php if(isset($user_detail->country_id) && !empty($user_detail->country_id)){ ?>
			setSelect2Value($("#country_id"),"<?=base_url('app/set_country_select2_val_by_id/'.$user_detail->country_id)?>");
		<?php } ?>
        initAjaxSelect2Tags($("#enterprise_type_id"),"<?=base_url('app/enterprise_type_select2_source')?>");
        <?php if(isset($user_detail->enterprise_type_id) && !empty($user_detail->enterprise_type_id)){ ?>
			setSelect2Value($("#enterprise_type_id"),"<?=base_url('app/set_enterprise_type_select2_val_by_id/'.$user_detail->enterprise_type_id)?>");
		<?php } ?>
        initAjaxSelect2Tags($("#tds_group_id"),"<?=base_url('app/tds_group_select2_source')?>");
        <?php if(isset($user_detail->tds_group_id) && !empty($user_detail->tds_group_id)){ ?>
			setSelect2Value($("#tds_group_id"),"<?=base_url('app/set_tds_group_select2_val_by_id/'.$user_detail->tds_group_id)?>");
		<?php } ?>
		
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
		
		var elementPosition = $('.basic_info_div').offset();
		$(window).scroll(function(){
			if($(window).scrollTop() > elementPosition.top){
				  $('.basic_info_div').css('position','fixed').css('top','0').css('min-width','294px');
			} else {
				$('.basic_info_div').css('position','static');
			}    
		});
		
		$(document).on('click', '.edit_profile', function(){
			$(this).hide();
			$("#update_profile :input").prop("disabled", false);
			$(".update_profile_btn").show();
		});
		
		$(document).on('submit', '#update_profile', function () {
			var postData = new FormData(this);
			var isNew = $('#enterprise_type_id').find('[data-select2-tag="true"]');
			if(isNew.length){
				postData.append('enterprise_type_id_new', $('#enterprise_type_id').val());
			}
			var isNew = $('#tds_group_id').find('[data-select2-tag="true"]');
			if(isNew.length){
				postData.append('tds_group_id_new', $('#tds_group_id').val());
			}
			$.ajax({
				url: "<?=base_url('auth/update_profile') ?>",
				type: "POST",
				processData: false,
				contentType: false,
				cache: false,
				fileElementId	:'user_image',
				data: postData,
				success: function (response) {
					var json = $.parseJSON(response);
					if(json['error'] == 'emailExist'){
						show_notify('Email Already Exist !',false);
						jQuery("#email_id").focus();
						return false;
					}
					if(json['error'] == 'error'){
						show_notify('Some error has occurred !',false);
						return false;
					}
					if (json['success'] == 'Added'){
						window.location.href = "<?php echo base_url('auth/profile') ?>";
					}
					if (json['success'] == 'Updated'){
						window.location.href = "<?php echo base_url('auth/profile') ?>";
					}
					return false;
				},
			});
			return false;
		});
		
	});
</script>
