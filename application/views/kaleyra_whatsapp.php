<!-- Content Wrapper. Contains page content -->
<style>
    [v-cloak] {display: none}
    #conversation_list_div {
        height:500px;
        overflow-y:scroll;
        overflow-x:hidden;
    }
    #conversation_list_div li {
        list-style-type: none;
    }
    .height500 {
        height:500px;
        vertical-align: text-bottom;
        overflow-y:scroll;
        overflow-x:wrap;
        background-color:#efefef;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-whatsapp"></i> Kaleyra Whatsapp</span></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <form id="send_message_form" @submit="checkForm" method="post">

            <div class="col-md-3" >
                <!--<h3> Conversation List</h3>-->
                
                <div class="row"  >
                    <div class="col-md-10">
                        <label for="whatsapp_number">Enter Customer Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" v-model="whatsapp_number" class="form-control" placeholder="+91999999999">
                        <small>Write No. with +91, Like : +91999999999</small>
                    </div>
                    
                    <div class="col-md-10" id="conversation_list_div" >
                        OR
                        <?php foreach ($from_numbers as $from_number) { ?>
                            <li>
                                <input type="radio" name="conversation_radio" id="conversation_radio_<?php echo $from_number->id; ?>" data-conversation_id="<?php echo $from_number->message_from; ?>"  value="<?php echo $from_number->message_from; ?>" class="hide">
                                <label for="conversation_radio_<?php echo $from_number->id; ?>">
                                    <input type="button" name="conversation_<?php echo $from_number->message_from; ?>" id="<?php echo $from_number->id; ?>" value="<?php echo $from_number->message_from . ' : ' . $from_number->message_name; ?>" class="btn conversation_btn" style="margin-right: 15px">
                                </label>
                            </li>
                        <?php } ?>
                    </div>
                </div>
                    
            </div>
            <div class="col-md-9">
                    <div v-if="errors.length">
                        <b>Please correct the following error(s):</b>
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <?php /*<div class="row" >
                        <div class="col-md-9 height500" id="conversation_content"></div>
                        <div class="col-md-9">
                            <label for="whatsapp_message">Enter Message</label>
                            <textarea type="text" name="whatsapp_message" id="whatsapp_message" v-model="whatsapp_message" class="form-control"></textarea>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" id="send_message" class="btn btn-info" value="Send Message">  
                        </div>
                    </div>*/ ?>
                    <div class="clearfix"></div>
                    <div class="box box-primary direct-chat direct-chat-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Chat : <span class="current_conversation"></span></h3>
                            <div class="box-tools pull-right">
                                <!--<span data-toggle="tooltip" title="3 New Messages" class="badge bg-red">3</span>-->
                                <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
                                <!-- In box-tools add this button if you intend to use the contacts pane -->
                                <!--<button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>-->
                                <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages" id="conversation_content" style="min-height: 400px;"></div><!--/.direct-chat-messages-->

                            <?php /*<!-- Contacts are loaded here -->
                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="../dist/img/user1-128x128.jpg" alt="Contact Avatar">
                                            <div class="contacts-list-info">
                                                <span class="contacts-list-name">
                                                    Count Dracula
                                                    <small class="contacts-list-date pull-right">2/28/2015</small>
                                                </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div><!-- /.contacts-list-info -->
                                        </a>
                                    </li><!-- End Contact Item -->
                                </ul><!-- /.contatcts-list -->
                            </div><!-- /.direct-chat-pane -->*/ ?>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div class="input-group">
                                <input type="text" name="whatsapp_message" id="whatsapp_message" v-model="whatsapp_message" placeholder="Type Message ..." class="form-control">
                                <span class="input-group-btn">
                                    <button type="submit" id="send_message" class="btn btn-primary btn-flat">Send</button>
                                </span>
                            </div>
                        </div><!-- /.box-footer-->
                    </div><!--/.direct-chat -->

            </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- VueJs -->
<script src="<?php echo base_url('assets/dist/js/vue.js');?>"></script>
<script>
    $(document).ready(function () {
        $('#whatsapp_number').focus();
//        $(document).on('click', '#send_message', function () {
//            send_kaleyra_message('+919724887520');
//        });
        $(document).on("change", "input[name='conversation_radio']", function() {
            app.load_conversation($(this).val());
        });
        $(document).on("click", ".conversation_btn", function() {
            var conversation_btn_id = $(this).attr('id');
            $('.conversation_btn').removeClass('bg-blue');
            $(this).closest('li').find('#conversation_radio_' + conversation_btn_id).attr('checked', true);
            $(this).addClass('bg-blue');
            $('#conversation_radio_' + conversation_btn_id).change();
            var conversation_btn_val = $(this).val();
            $('.current_conversation').html(conversation_btn_val);
        });
    });
    
    const apiUrl = '<?= base_url('kaleyra_whatsapp/'); ?>';
    const app = new Vue({
        el:'#send_message_form',
        data:{
            errors:[],
            whatsapp_number:'',
            whatsapp_message:'',
            load_conversation_val:''
        },
        methods:{
            checkForm:function(e) {
                e.preventDefault();
                this.errors = [];
                if(this.whatsapp_number === '') {
                    this.errors.push("Whatsapp Number is required.");
                } else if(this.whatsapp_message === '') {
                    this.errors.push("Whatsapp Message is required.");
                } else {
                  fetch(apiUrl + 'send_whatsapp_message/' + encodeURIComponent(this.whatsapp_number) + '/' + encodeURIComponent(this.whatsapp_message))
                  .then(async res => {
                      console.log(res);
                    if(res.status === 200) {
                        show_notify("Message Send",true);
                        this.whatsapp_number = '';
                        this.whatsapp_message = '';
                        app.load_conversation(this.load_conversation_val);
                    } else if(res.status === 404) {
                      let errorResponse = await res.json();
                      this.errors.push(errorResponse.error);
                    }
                  });
                }
            },
            load_conversation: function(val) {
                console.log(val);
                this.load_conversation_val = val;
                app.whatsapp_number="+91"+val;
                $.ajax({
                    url: apiUrl + 'load_conversation/' + encodeURIComponent(val),
                    type: "GET",
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    success: function (response) {
                        var res_data = $.parseJSON(response);
                        let chat_content = '';
                        $(res_data).each(function(k,v) {
                            let align = '';
                            if (v.message_from != val) {
                                align = 'right"';
                            }
                            chat_content += '<div class="direct-chat-msg ' + align + '">';
                            chat_content += '<div class="direct-chat-info clearfix">';
                            chat_content += '<span class="direct-chat-name pull-left">' + v.message_name + ' (' + v.message_from + ')</span>';
                            chat_content += '<span class="direct-chat-timestamp pull-right">' + v.created_at + '</span>';
                            chat_content += '</div><!-- /.direct-chat-info -->';
                            chat_content += '<img class="direct-chat-img" src="<?php echo site_url(); ?>/assets/dist/img/default-user.png" alt="message user image"><!-- /.direct-chat-img -->';
                            chat_content += '<div class="direct-chat-text">' + v.message_body + '</div><!-- /.direct-chat-text -->';
                            chat_content += '</div><!-- /.direct-chat-msg -->';
                        });
                        chat_content += '';
                        $("#conversation_content").html(chat_content);
                        $('.direct-chat-messages').scroll();
                        $(".direct-chat-messages").animate({scrollTop: 2000000000}, 1000);
                    }
                });

            }
        }
    });
</script>
