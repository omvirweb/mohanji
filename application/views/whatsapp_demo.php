<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-whatsapp"></i> WhatsApp Demo</span></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <button name="conversation_list" id="conversation_list" class="btn btn-info" > Conversation List</button>
                <div id="conversation_list_div"></div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <textarea name="msg_text" id="msg_text" class="form-control">Hello!</textarea>
                <button name="send_message" id="send_message" class="btn btn-info" > Send Message</button>
            </div>
            <div class="clearfix"></div><hr>
            <div class="col-md-12">
                <?php /*<button name="messages_in_conversation" id="messages_in_conversation" class="btn btn-info" > Message List</button>*/ ?>
                <div id="messages_in_conversation_div"></div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        $(document).on('click', '#send_message', function () {
            var msg_text = $('#msg_text').val();
            var conversation_radio = $("input[name='conversation_radio']:checked").val();
            send_custom_whatsapp_message(conversation_radio, 'c9eefdf3320e433c826d5cd911dd8ad5' ,'text', msg_text);
            $('#msg_text').val('');
        });
        $(document).on('click', '#conversation_list', function () {
            get_conversation_list();
        });
        $(document).on('click', '.conversation_btn', function () {
            var conversation_id = $(this).attr('id');
            $("#conversation_radio_" + conversation_id).prop("checked", true);
            get_messages_in_conversation(conversation_id);
        });
        $(document).on('click', '#messages_in_conversation', function () {
            get_messages_in_conversation('e6b593e389c0467aa989c4dcb1068c8a');
        });
        $('#conversation_list').click();
//        $('#messages_in_conversation').click();
    });
    function send_custom_whatsapp_message_response(data){
//        var conversation_id = $("input[name='conversation_radio']:checked").attr('data-conversation_id');
//        get_messages_in_conversation(conversation_id);
    }
    function set_conversation_list(data){
        var conversation_list_data = '';
        $.each(data.response.items, function (index, value) {
            conversation_list_data += '<input type="radio" name="conversation_radio" id="conversation_radio_' + value.id + '" data-conversation_id="' + value.id + '"  value="+' + value.contact.displayName + '">';
            conversation_list_data += '<label for="conversation_radio_' + value.id + '"><input type="button" name="conversation_' + value.id + '" id="' + value.id + '" value="' + value.contact.displayName + '" class="btn conversation_btn" style="margin-right: 15px"></label>';
        });
        $('#conversation_list_div').html(conversation_list_data);
    }
    function set_messages_in_conversation(data){
        var messages_in_conversation_data = '<ul>';
        $.each(data.response.items, function (index, value) {
            if(value.direction == 'received'){
                messages_in_conversation_data += '<li class="text-left">';
            } else {
                messages_in_conversation_data += '<li class="text-right">';
            }
            if(value.type == 'text'){
                messages_in_conversation_data += value.content.text;
            } else if(value.type == 'image'){
                messages_in_conversation_data += '<img src="' + value.content.image.url + '" alt="image" width="50px" >'
            } else if(value.type == 'audio'){
                messages_in_conversation_data += '<audio controls>';
                messages_in_conversation_data += '<source src="horse.ogg" type="audio/ogg">';
                messages_in_conversation_data += '<source src="' + value.content.audio.url + '" type="audio/mpeg">';
                messages_in_conversation_data += 'Your browser does not support the audio element.';
                messages_in_conversation_data += '</audio>';
            } else if(value.type == 'video'){
                messages_in_conversation_data += '<video width="300" height="220" controls>';
                messages_in_conversation_data += '<source src="' + value.content.video.url + '" type="video/mp4">';
                messages_in_conversation_data += '<source src="movie.ogg" type="video/ogg">';
                messages_in_conversation_data += 'Your browser does not support the video tag.';
                messages_in_conversation_data += '</audio>';
            }
            messages_in_conversation_data += '</li>';
        });
        messages_in_conversation_data += '</ul>';
        $('#messages_in_conversation_div').html(messages_in_conversation_data);
    }
</script>
