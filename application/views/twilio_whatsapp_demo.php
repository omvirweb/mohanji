<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-whatsapp"></i> Twilio WhatsApp Demo</span></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <button name="conversation_list" id="conversation_list" class="btn btn-info" > Conversation List</button><hr>
                <div id="conversation_list_div"></div>
            </div>
            <div class="col-md-10">
                <div class="col-md-8">
                    <textarea name="msg_text" id="msg_text" class="form-control" required="">Hello!</textarea>
                </div>
                <div class="col-md-2">
                    <button name="send_message" id="send_message" class="btn btn-info" > Send Message</button>
                </div>
                <div class="clearfix"></div><hr>
                <div id="messages_in_conversation_div"></div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var from_list = {};
    $(document).ready(function () {
        $(document).on('click', '#send_message', function () {
            var msg_text = $('#msg_text').val();
            if(msg_text == ''){
                alert('Please Enter Some Text!');
                return false;
            }
            var conversation_radio = $("input[name='conversation_radio']:checked").val();
            var twilio_number = '+14155238886';
            if(conversation_radio != undefined){
                send_custom_twilio_whatsapp_message(twilio_number, conversation_radio, msg_text);
            } else {
                alert('First Please Select Any Number!');
                return false;
            }
            $('#msg_text').val('');
        });
        $(document).on('click', '#conversation_list', function () {
            get_twilio_messages_in_conversation();
        });
        $(document).on('click', '.conversation_btn', function () {
            var from_number = $(this).attr('id');
            get_twilio_messages_in_conversation(from_number);
//            $("#conversation_radio_" + from_number).prop("checked", true);
            $("input[name='conversation_radio'][value='" + from_number + "']").prop("checked", true);
        });
        $('#conversation_list').click();
    });
    
    function set_twilio_conversation(data){
        $.each(data.response, function (index, value) {
            var from_number = value.from;
            from_number = from_number.split(':');
            from_number = from_number[1];
            var to_number = value.to;
            to_number = to_number.split(':');
            to_number = to_number[1];
            
            if(from_number == '+14155238886'){
                if (typeof from_list[to_number] == "undefined" || !(from_list[to_number] instanceof Array)) {
                    from_list[to_number] = new Array();
                }
                from_list[to_number].push(value);
            } else {
                if (typeof from_list[from_number] == "undefined" || !(from_list[from_number] instanceof Array)) {
                    from_list[from_number] = new Array();
                }
                from_list[from_number].push(value);
            }
        });
        console.log(from_list);
        
        var conversation_list_data = '<ul style="padding-left: 0; list-style: none;">';
        $.each(from_list, function (index, value) {
            conversation_list_data += '<li>';
            conversation_list_data += '<input type="radio" name="conversation_radio" id="conversation_radio_' + index + '" data-conversation_id="' + index + '"  value="' + index + '">';
            conversation_list_data += '<label for="conversation_radio_' + index + '"><input type="button" name="conversation_' + index + '" id="' + index + '" value="' + index + '" class="btn conversation_btn" style="margin-right: 15px"></label>';
            conversation_list_data += '</li>';
        });
        conversation_list_data += '</ul>';
        $('#conversation_list_div').html(conversation_list_data);
    }
    
    function set_twilio_messages_in_conversation(data){
        from_list[data.from_number] = new Array();
        
        var messages_in_conversation_data = '<ul>';
        $.each(data.response, function (index, value) {
            var from_number = value.from;
            from_number = from_number.split(':');
            from_number = from_number[1];
            var to_number = value.to;
            to_number = to_number.split(':');
            to_number = to_number[1];
            
            if(data.from_number == from_number || data.from_number == to_number){
                if(from_number == '+14155238886'){
                    if (typeof from_list[to_number] == "undefined" || !(from_list[to_number] instanceof Array)) {
                        from_list[to_number] = new Array();
                    }
                    from_list[to_number].push(value);
                } else {
                    if (typeof from_list[from_number] == "undefined" || !(from_list[from_number] instanceof Array)) {
                        from_list[from_number] = new Array();
                    }
                    from_list[from_number].push(value);
                }
                
                if(value.direction == 'inbound'){
                    messages_in_conversation_data += '<li class="text-left" style="padding-top:2px;">';
                } else {
                    messages_in_conversation_data += '<li class="text-right">';
                }
                if(value.numMedia == '0'){
                    messages_in_conversation_data += value.body;
                } else {
                    get_twilio_messages_media(value.accountSid, value.sid);
                    messages_in_conversation_data += '<img src="<?php echo base_url('assets/image/loading 2.gif'); ?>" id="' + value.accountSid + '_' + value.sid + '" alt="image" width="50px" >';
                    messages_in_conversation_data += ' <a href="#">Convert to Order <i class="fa fa-share"></i></a>';
                }
                messages_in_conversation_data += '</li>';
                
            }
        });
        messages_in_conversation_data += '</ul>';
        $('#messages_in_conversation_div').html('');
        $('#messages_in_conversation_div').html(messages_in_conversation_data);
    }
</script>
