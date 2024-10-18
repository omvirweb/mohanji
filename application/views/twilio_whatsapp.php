<!-- Content Wrapper. Contains page content -->
<style>
    [v-cloak] {display: none}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-whatsapp"></i> Twilio WhatsApp</span></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3" style="border:1px solid #305777">
                <h3> Conversation List</h3>
                <div class="row" id="conversation_list_div" v-cloak>
                    <div class="col-md-9">
                        <input v-model="conversation_text" type="search" class="form-control" placeholder="Searching...">
                    </div>
                    <div class="clearfix"></div><br>
                    <ul style="padding-left: 10px; list-style: none; max-height: 300px; overflow-y: scroll;">
                        <li v-for="conversation in conversation_list" class="conversation">
                            <input type="radio" name="conversation_of" id="'conversation_of_' + conversation.message_from" :data-conversation_id="conversation.message_from"  :value="conversation.message_from">
                            <label for="'conversation_of_' + conversation.message_from"><input type="button" :name="'conversation_' + conversation.message_from" :id="conversation.message_from" :value="conversation.message_from + ' - ' + conversation.account_name" class="btn conversation_btn" style="margin-right: 15px"></label>
                        </li>
                        <li v-if="noResults">
                            Sorry, but no results were found.
                        </li>
                        <li v-if="searching">
                            <i>Searching...</i>
                        </li>
                    
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <h3> Read Messages</h3><hr>
                <div id="messages_in_conversation_div"></div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- The Modal -->
<div class="modal" tabindex="-1" id="whatsapp_image_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <img src="" id="whatsapp_media_fullview" alt="whatsapp_media" width="100%">
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- VueJs -->
<script src="<?php echo base_url('assets/dist/js/vue.js');?>"></script>
<script>
    const app = new Vue({
        el:'#conversation_list_div',
        data: {
            conversation_text:'',
            conversations: [],
            noResults:false,
            searching:true
        },
        created() {
            this.searching = true;
            var apiURL = "<?php echo base_url('twilio_whatsapp_demo/get_conversations'); ?>";
            fetch(apiURL)
                .then(res => res.json())
                .then(res => {
                    this.searching = false;
                    this.conversations = res;
                    this.noResults = this.conversations.length === 0;
                })
                .catch(error => console.log(error));
        },
        computed: {
            conversation_list() {
                var filtered_conversations = this.conversations.filter(conversation => {
                    return conversation.message_from.toLowerCase().includes(this.conversation_text.toLowerCase()) || 
                            conversation.account_name.toLowerCase().includes(this.conversation_text.toLowerCase());
                });
                this.noResults = filtered_conversations.length === 0;
                return filtered_conversations;
            }
        }
    });
    $(document).ready(function () {
        $(document).on('click', '.conversation_btn', function () {
            var from_number = $(this).attr('id');
            read_twilio_messages(from_number, '<?php echo $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id']; ?>');
            $("input[name='conversation_of'][value='" + from_number + "']").prop("checked", true);
        });
        
        $(document).on('click', '.whatsapp_media', function () {
            var whatsapp_media_src = $(this).attr('src');
            $('#whatsapp_media_fullview').attr('src', whatsapp_media_src);
        });
        
        $('#whatsapp_image_modal').on('shown.bs.modal', function () {
        });
        $('#whatsapp_image_modal').on('hidden.bs.modal', function () {
        });
    });
    
    function set_twilio_messages_in_conversation(data){
        $('#messages_in_conversation_div').html('');
        var messages_in_conversation_data = '<ul>';
        $.each(data.result, function (index, value) {
            messages_in_conversation_data += '<li class="text-left" style="padding-top:2px;">';
            if(value.message_body != ''){
                messages_in_conversation_data += value.message_body;
            } else {
                var webhook_content_json = $.parseJSON(value.webhook_content);
                messages_in_conversation_data += '<img src="'+ webhook_content_json.MediaUrl0 +'" id="' + webhook_content_json.AccountSid + '_' + webhook_content_json.SmsSid + '" alt="image" class="whatsapp_media" height="150px" data-toggle="modal" data-target="#whatsapp_image_modal" >';
                messages_in_conversation_data += ' <a href="#">Convert to Order <i class="fa fa-share"></i></a>';
            }
            messages_in_conversation_data += '</li>';
        });
        messages_in_conversation_data += '</ul>';
        $('#messages_in_conversation_div').html(messages_in_conversation_data);
    }
</script>
