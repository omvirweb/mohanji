<div id="backup_db_modal" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="backup_db_modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ReIndex</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="backup_db_password">Password</label>
                            <input type="text" id="backup_db_password" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="backup_db_cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="backup_db_submit">ReIndex</button>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2">
	<div id="time_print" class="time_print" style="display:none;"></div>
</div>
<footer class="main-footer">
    <div class="text-center">
        <strong class="text-black">Support : </strong>&nbsp;
        <span class="text-black">Vipul Shah</span>
        <a href="tel:9727691355">&nbsp;<i class="fa fa-phone"></i> 9727691355</a>&nbsp;&nbsp;
        <small class="text-bold">Or</small> &nbsp; <a href="https://wa.me/919727691355" target="_blank"><i class="fa fa-whatsapp text-bold" style="color: green;"></i> WhatsApp &nbsp;&nbsp;</a>
        &nbsp;&nbsp;&nbsp;
        <span class="text-black">Prashant Bhatt</span>
        <a href="tel:9913411672">&nbsp;<i class="fa fa-phone"></i> 9913411672</a>&nbsp;&nbsp;
        <small class="text-bold">Or</small> &nbsp; <a href="https://wa.me/919913411672" target="_blank"><i class="fa fa-whatsapp text-bold" style="color: green;"></i> WhatsApp &nbsp;&nbsp;</a>
    </div>
    <hr style="margin: 10px 0px; border-color: #d2d6de;">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2018 <?php echo PACKAGE_NAME; ?> <a href="http://jewelbook.in/" target="_blank">Powered by HRDK</a>.</strong>
</footer>


<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
<!-- ./wrapper -->

<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/plugins/fastclick/fastclick.js');?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('assets/plugins/sparkline/jquery.sparkline.min.js');?>"></script>
<!-- jvectormap -->
<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/dist/js/demo.js');?>"></script>
<!-- AdminLTE JS file -->
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/app.js');?>"></script>
<script src="<?php echo base_url('assets/dist/js/shortcut.js');?>"></script>
<!-------- /Parsleyjs --------->
<script src="<?= base_url('assets/plugins/validator/dist/validator.min.js');?>"></script>

<!-- notify -->
<script src="<?php echo base_url('assets/plugins/notify/jquery.growl.js');?>"></script>
<!-- DataTables -->
<script src="<?=base_url('assets/plugins/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js');?>"></script>
<!-- timepicker -->
<script src="<?=base_url('assets/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>
<!-- datepicker -->
<script src="<?=base_url('assets/plugins/datepicker/bootstrap-datepicker.js');?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?=base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
<!-- date-range-picker -->
<script src="<?=base_url('assets/plugins/moment/min/moment.min.js');?>"></script>
<script src="<?=base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
<!-- bootstrap-year-calendar -->
<script src="<?=base_url('assets/plugins/bootstrap-year-calendar/bootstrap-year-calendar.min.js');?>"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/buttons.colVis.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/jszip.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>assets/plugins/datatables/extensions/Buttons/js/vfs_fonts.js"></script>
<div id="ajax-loader" style="display: none;">
    <div style="width:100%; height:100%; left:0px; top:0px; position:fixed; opacity:0; filter:alpha(opacity=40); background:#000000;z-index:999999999;">
    </div>
    <div style="float:left;width:100%; left:0px; top:45%; text-align:center; position:fixed; padding:0px; z-index:999999999;">
        <img src="<?php echo base_url();?>assets/image/loading.gif" width="150" height="150">
        <p></p>
    </div>     
</div>
<link rel="stylesheet" href="<?=base_url()?>assets/plugins/sweetalert/dist/sweetalert.css">
<script src="<?=base_url()?>assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url('node_modules/socket.io-client/dist/socket.io.js'); ?>"></script>
<script type="text/javascript">
    var port_number = '<?php echo PORT_NUMBER; ?>';
    var socket = io.connect('http://' + window.location.hostname + ':'+port_number);
    socket.on('connect', function(data) {
        var login_user_id = '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>';
        $.ajax({
            type: "POST",
            data: {},
            url: "<?= base_url('auth/update_user_socket_id'); ?>/" + login_user_id + '/' + socket.id,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (data.success = true) {
                }
            }
        });
//        socket.emit('logout_at_other_place', {login_user_id: login_user_id, socket_id: socket.id});
        return false;
    });
    
    function change_user_status_logout(loggedin_user_id, user_id){
        socket.emit('change_user_status_logout', {loggedin_user_id: loggedin_user_id, user_id: user_id});
    }
    socket.on('change_user_status_logout', function (data) {
        if(data.loggedin_user_id == '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>'){
            user_successfully_logout();
        }
        if(data.user_id == '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>'){
            window.location.href = "<?php echo base_url('auth/logout') ?>";
        }
    });
    socket.on('logout_at_other_place', function (data) {
        if(data.login_user_id == '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>'){
            if(data.socket_id != socket.id){
                window.location.href = "<?php echo base_url('auth/logout') ?>";
            }
            if(data.socket_id == socket.id){
                var login_user_id = '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>';
                $.ajax({
                    type: "POST",
                    data: {},
                    url: "<?= base_url('auth/update_user_socket_id'); ?>/" + login_user_id + '/' + socket.id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                    }
                });
            }
        }
    });
    
    $(document).ready(function(){
        //select2 not working modal
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};

        document.getElementById("time_print").innerHTML = formatAMPM();
        
        $(document).on('click', '.open_popup', function (e) {
            var myUrl = $(this).attr('data-href');
            e.preventDefault();
            window.open(myUrl,'open_popup','width=700,height=600,_blank');
        });

        if ($('#datepicker1').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker1').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: "linked",
                autoclose: true
            });
        }
        if ($('#datepicker2').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker2').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: "linked",
                autoclose: true
            });
        }
        if ($('#datepicker3').hasClass("disable_datepicker")) {
        } else {
            $('#datepicker3').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: "linked",
                autoclose: true
            });
        }
        if ($('.datepicker').hasClass("disable_datepicker")) {
        } else {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                todayBtn: "linked",
                autoclose: true
            });
        }
        
        $(":input").attr('autocomplete','off');
        
        $(document).on('input', ".num_only", function () {
            var textbox_value = this.value = this.value.replace(/[^\d\.\-]/g, '');
            var textbox_str = textbox_value.split(/\./);
            if(textbox_str.length > 2){
                $(this).val('');
            }
        });
        //iCheck for checkbox and radio inputs
//        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//            checkboxClass: 'icheckbox_minimal-blue',
//            radioClass   : 'iradio_minimal-blue'
//        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        });
        
        $(document).on('click', '.btn_backup_db', function (e) {
            $("#backup_db_modal").modal('show');
            $("#backup_db_password").attr('type','password');
        });
        
        $(document).on('click', '#backup_db_cancel', function (e) {
            $("#backup_db_password").val('');
        });
        
        $('#backup_db_modal').on('hidden.bs.modal', function () {
            $("#backup_db_password").attr('type','text');
        });

        $(document).on('click', '#backup_db_submit', function (e) {
            var backup_db_password = $("#backup_db_password").val();
            if(backup_db_password == '' || backup_db_password == null) {
                show_notify("Please Enter Password");
                $("#backup_db_password").focus();
                return false;
            }
            $("#ajax-loader").show();
            $.ajax({
                type: "POST",
                data: "user_password="+backup_db_password,
                url: "<?= base_url('backup/check_password'); ?>",
                dataType: 'json',
                success: function (data) {
                    if(data.status == "success") {
                        $("#backup_db_modal").modal('hide');
                        show_notify("Downloading ReIndex",true);
                        $("#ajax-loader").hide();
                        $("#backup_db_password").val('');

                        window.location.href = "<?= base_url() ?>backup/";
                    } else {
                        $("#ajax-loader").hide();
                        show_notify("Invalid Password!");
                        $("#backup_db_password").focus();
                    }
                }
            });            
        });

        $(document).on('click', '.add_new_link', function (e) {
			var myUrl = $(this).attr('data-url');
			e.preventDefault();
			window.open(myUrl, 'Add', 'width=700,height=600,_blank');
		});
        startTime();

        <?php
            $sell_purchase_difference = 0;
            if(isset($this->session->userdata()[PACKAGE_FOLDER_NAME . 'sell_purchase_difference'])){
                $sell_purchase_difference = $this->session->userdata()[PACKAGE_FOLDER_NAME . 'sell_purchase_difference'];
            }
        ?>
         <?php if($sell_purchase_difference) {?>
        shortcut.add("ctrl+f1",function(event) {
            event.preventDefault();
            window.location.href = "<?= base_url(); ?>sell/add/sell";
        });
        shortcut.add("ctrl+f2",function(event) {
            event.preventDefault();
            window.location.href = "<?= base_url(); ?>sell/add/purchase";
        });
        <?php } else { ?>
        shortcut.add("ctrl+f1",function(event) {
            event.preventDefault();
            window.location.href = "<?= base_url(); ?>sell/add";
        });
        <?php } ?>
    });
    
    function formatAMPM() {
		var date = new Date();
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var ampm = hours >= 12 ? 'pm' : 'am';
		hours = hours % 12;
		hours = hours ? hours : 12; // the hour '0' should be '12'
		minutes = minutes < 10 ? '0'+minutes : minutes;
		var strTime = hours + ':' + minutes + ' ' + ampm;
		return strTime;
	}

	function startTime() {
		var today = new Date();
		var hr = today.getHours();
		var min = today.getMinutes();
		var sec = today.getSeconds();
		ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
		hr = (hr == 0) ? 12 : hr;
		hr = (hr > 12) ? hr - 12 : hr;
		//Add a zero in front of numbers<10
		hr = checkTime(hr);
		min = checkTime(min);
		sec = checkTime(sec);
		document.getElementById("clock").innerHTML = "&nbsp" +hr + ":" + min + ":" + sec + " " + ap;

		//var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
		var curWeekDay = days[today.getDay()];
		var curDay = today.getDate();
		var curMonth = months[today.getMonth()];
		var curYear = today.getFullYear();
		var date = curWeekDay+", "+curDay+" "+curMonth+" "+curYear+"&nbsp";
		document.getElementById("date").innerHTML = date;

		var time = setTimeout(function(){ startTime() }, 500);
	}

	function checkTime(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
	}

	function updateClock(){
		var currentTime = new Date ( );
		var currentHours = currentTime.getHours ( );
		var currentMinutes = currentTime.getMinutes ( );
		var currentSeconds = currentTime.getSeconds ( );

		// Pad the minutes and seconds with leading zeros, if required
		currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
		currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

		// Choose either "AM" or "PM" as appropriate
		var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

		// Convert the hours component to 12-hour format if needed
		currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

		// Convert an hours component of "0" to "12"
		currentHours = ( currentHours == 0 ) ? 12 : currentHours;

		// Compose the string for display
		var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

		//$("#clock").html(currentTimeString);
	}
    
    /*------------- Check For Unique --------------------*/
    function check_is_unique(table_name,column_name,column_value,id_column_name = '',id_column_value = ''){	
        var DataStr = "table_name="+table_name+"&column_name="+column_name+"&column_value="+column_value+"&id_column_name="+id_column_name+"&id_column_value="+id_column_value;
        var response = '1';
        $.ajax({
            url: "<?=base_url()?>master/check_is_unique/",
            type: "POST",
            data: DataStr,
            async:false
        }).done(function(data) {
            response = data;
        });
        return response;
    }
    /*------------- Check For Unique --------------------*/
    function show_notify(notify_msg,notify_type)
    {
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
    function initAjaxSelect2($selector,$source_url)
    {
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

    function initAjaxSelect3($selector,$source_url,$container_type,$new_edit)
    {
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
                        page: params.page,
                        container_type: $container_type,
                        new_edit: $new_edit
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

    function setSelect2Value($selector,$source_url = '')
    {
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
    function setSelect2MultiValue($selector,$source_url = '')
    {
        if($source_url != '') {
            $.ajax({
                url: $source_url,
                type: "GET",
                data: null,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
						var selectValues = data[0];
						$.each(selectValues, function(key, value) {   
							$selector.select2("trigger", "select", {
								data: value
							});
						});
                    }
                }
            });
        } else {
            $selector.empty().append($('<option/>').val('').text('--select--')).val('').trigger("change");
        }
    }
    //Tags
    function initAjaxSelect2Tags($selector,$source_url)
    {
        $selector.select2({
            placeholder: " --Select-- ",
            allowClear: true,
            width:"100%",
            tags: true,
            multiple: true,
            maximumSelectionLength: 1,
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

    function matchStartSelect2 (term, text) {
        if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
          return true;
        }
        return false;
    }

    function round(value, exp) {
        if (typeof exp === 'undefined' || +exp === 0)
          return Math.round(value);

        value = +value;
        exp = +exp;

        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
          return NaN;

        // Shift
        value = value.toString().split('e');
        value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

        // Shift back
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
    }
    
    socket.on('logout_notify', function (data) {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
        var notification = new Notification('HRDK : Your Logout Time Is '+data.time, {});
        swal({
            title: "<span style='color:  #c60707;'>Your Logout Time Is "+data.time+"</span>",
             html: true,
            type: "warning",
            confirmButtonClass: "btn-warning",
            confirmButtonText: "Ok",
            closeOnConfirm: true,
        });

    });
    
    socket.on('logout_user', function (data) {
        swal({
            title: "<span style='color:  #c60707;'>Your Logout Time Is "+data.time+"</span>",
             html: true,
            type: "warning",
            confirmButtonClass: "btn-warning",
            confirmButtonText: "Ok",
            closeOnConfirm: true,
        });
        setTimeout(function () {
            $.ajax({
            type: "POST",
            data: {},
            url: "<?= base_url('auth/logout'); ?>/",
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
        });
        window.location.href = "<?php echo base_url('auth/login') ?>";
        }, 500);
    });
    
    function send_custom_whatsapp_message(msg_to, msg_channel_id, msg_type, msg_text){
        socket.emit('send_custom_whatsapp_message', {msg_to: msg_to, msg_channel_id: msg_channel_id, msg_type: msg_type, msg_text: msg_text});
    }
    socket.on('send_custom_whatsapp_message', function (data) {
        console.log(data);
        send_custom_whatsapp_message_response(data);
    });
    
    function get_conversation_list(){
        socket.emit('get_conversation_list', {});
    }
    socket.on('get_conversation_list', function (data) {
        console.log(data);
        set_conversation_list(data);
    });
    
    function get_messages_in_conversation(conversation_id){
        socket.emit('get_messages_in_conversation', {conversation_id: conversation_id});
    }
    socket.on('get_messages_in_conversation', function (data) {
        console.log(data);
        set_messages_in_conversation(data);
    });
    
    function send_custom_twilio_whatsapp_message(from_number, to_number, msg_text){
        socket.emit('send_custom_twilio_whatsapp_message', {from_number: from_number, to_number: to_number, msg_text: msg_text});
    }
    socket.on('send_custom_twilio_whatsapp_message', function (data) {
//        console.log(data);
        get_twilio_messages_in_conversation(data.conversation_radio);
    });
//    function get_twilio_whatsapp_conversation_list(){
//        socket.emit('get_twilio_whatsapp_conversation_list', {});
//    }
//    socket.on('get_twilio_whatsapp_conversation_list', function (data) {
//        console.log(data);
//        set_twilio_conversation_list(data);
//    });
    function get_twilio_messages_in_conversation(from_number = ''){
        socket.emit('get_twilio_messages_in_conversation', {from_number: from_number});
    }
    socket.on('get_twilio_messages_in_conversation', function (data) {
        console.log(data);
        if(data.from_number != ''){
            set_twilio_messages_in_conversation(data);
        } else {
            set_twilio_conversation(data);
        }
    });
    function get_twilio_messages_media(accountSid, sid){
        socket.emit('get_twilio_messages_media', {accountSid: accountSid, sid:sid});
    }
    socket.on('get_twilio_messages_media', function (data) {
//        console.log(data);
        $.each(data.response, function (index, value) {
            var media_uri = value.uri;
            media_uri = 'https://api.twilio.com' + media_uri.replace(".json", "");
            $('#'+value.accountSid+'_'+value.parentSid+'').attr('src', media_uri);
        });
    });
    
    function read_twilio_messages(from_number, user_id){
        if(from_number != ''){
            socket.emit('read_twilio_messages', {from_number: from_number, user_id: user_id});
        }
    }
    socket.on('read_twilio_messages', function (data) {
        if(data.user_id == '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>'){
            console.log(data);
            if(data.from_number != ''){
                set_twilio_messages_in_conversation(data);
            }
        }
    });
    socket.on('new_twilio_sendbox_message_comes', function (data) {
        var conversation_of = $("input[name='conversation_of']:checked").attr('data-conversation_id');
        if(conversation_of != undefined){
            read_twilio_messages(conversation_of, '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>');
        }
    });

    socket.on('new_message_from_customer', function (data) {
//        var conversation_radio = $('input[name="conversation_radio"]:checked').val();
//        if(data.message_from == conversation_radio){
            console.log('data :::' + data.message_from);
            app.load_conversation(data.message_from);
            $('.conversation_btn').removeClass('bg-blue');
            $('input[name="conversation_' + data.message_from + '"]').addClass('bg-blue');
//        }
    });
</script>
