var socket  = require( 'socket.io' );
var express = require('express');
var app     = express();
var server  = require('http').createServer(app);
var io      = socket.listen( server );
var port    = process.env.PORT || 3035;
var messagebird = require('messagebird')('KbzjR1UqmhKsvFbczw5waVdan');

const twilio_accountSid = 'ACb80b66779372aaa258eb9daaaae6d41b';
const twilio_authToken = 'c1051a0671061bc4d8671583f9e8ef38';
const twilio_client = require('twilio')(twilio_accountSid, twilio_authToken);
const twilio_MessagingResponse = require('twilio').twiml.MessagingResponse;

var mysql = require('mysql');
var connection = {
  host: "localhost",
  user: "root",
  password: "root",
  database: "guru"
};

var con_mysql;

function handleDisconnect() {
  con_mysql = mysql.createConnection(connection); 
  con_mysql.connect(function(err) {
    if(err) {
      console.log('error when connecting to db:', err);
      setTimeout(handleDisconnect, 2000);
    }
  });
     
  con_mysql.on('error', function(err) {
    console.log('db error', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') {
      handleDisconnect();
    } else {
        var indianTimeZoneVal = new Date().toLocaleString('en-US', {timeZone: 'Asia/Kolkata'});
        var currentdate = new Date(indianTimeZoneVal);
        var datetime = currentdate.getFullYear() + "-"
                + (currentdate.getMonth() + 1) + "-"
                + currentdate.getDate() + " "
                + currentdate.getHours() + ":"
                + currentdate.getMinutes() + ":"
                + currentdate.getSeconds();
        console.log(datetime);
      throw err;
    }
  });
}

handleDisconnect();

server.listen(port, function () {
	console.log('Server listening at port %d', port);
        get_login_data();
});

//delete_reminder_data();

when_a_twilio_sendbox_message_comes();

io.on('connection', function (socket) {
    socket.on('disconnect', function() {
        var sql = "SELECT * FROM `user_master` WHERE `socket_id` = '"+ socket.id + "'";
        con_mysql.query(sql, function (err, result) {
            if (err)
                throw err;
            if(result != ''){
//                console.log(result[0].session_id);
                
                var sql = "UPDATE `user_master` SET `is_login` = '0' WHERE `socket_id` = '"+ socket.id + "'";
                con_mysql.query(sql, function (err, result) {
                    if (err)
                        throw err;
//                    console.log(result.affectedRows + " record(s) updated");
                });
            }
        });
    });
    
    socket.on('close', function() {
        var sql = "SELECT * FROM `user_master` WHERE `socket_id` = '"+ socket.id + "'";
        con_mysql.query(sql, function (err, result) {
            if (err)
                throw err;
            if(result != ''){
                var sql = "UPDATE `user_master` SET `is_login` = '0' WHERE `socket_id` = '"+ socket.id + "'";
                con_mysql.query(sql, function (err, result) {
                    if (err)
                        throw err;
                });
            }
        });
    });
    
    socket.on( 'change_user_status_logout', function( data ) {
		io.sockets.emit( 'change_user_status_logout', {
            loggedin_user_id: data.loggedin_user_id,
            user_id: data.user_id,
        });
	});
    
    socket.on( 'logout_at_other_place', function( data ) {
		io.sockets.emit( 'logout_at_other_place', {
            login_user_id: data.login_user_id,
            socket_id: data.socket_id,
        });
	});
    
    socket.on( 'send_custom_whatsapp_message', function( data ) {
        // start a conversation
        messagebird.conversations.start({
          'to': data.msg_to,
          'channelId': data.msg_channel_id,
          'type': data.msg_type,
          'content': { 'text': data.msg_text }
        }, function (err, response) {
            io.sockets.emit( 'send_custom_whatsapp_message', {
                response: response,
                err: err,
            });
        });
	});
    socket.on( 'get_conversation_list', function( data ) {
        // list conversations
        messagebird.conversations.list(20, 0, function (err, response) {
          io.sockets.emit( 'get_conversation_list', {
                response: response,
                err: err,
            });
        });
	});
    socket.on( 'get_messages_in_conversation', function( data ) {
        // get messages in conversation
        messagebird.conversations.listMessages(data.conversation_id, 20, 0, function (err, response) {
            io.sockets.emit( 'get_messages_in_conversation', {
                response: response,
                err: err,
            });
        });
	});
    
    socket.on( 'send_custom_twilio_whatsapp_message', function( data ) {
        twilio_client.messages 
            .create({ 
               body: data.msg_text, 
               from: 'whatsapp:' + data.from_number,       
               to: 'whatsapp:' + data.to_number 
             }) 
            .then(
                message => 
                io.sockets.emit( 'send_custom_twilio_whatsapp_message', {
                    conversation_radio: data.to_number,
                    response: message,
                })
            ) 
            .done();
	});
    
    socket.on( 'get_twilio_whatsapp_conversation_list', function( data ) {
        twilio_client.conversations.conversations
        .list({limit: 20})
        .then(
            conversations => 
            io.sockets.emit( 'get_twilio_whatsapp_conversation_list', {
                response: conversations,
            })
        );
	});
    
    socket.on( 'get_twilio_messages_in_conversation', function( data ) {
        if(data.from_number == ''){
            twilio_client.messages 
                .list() 
                .then(
                    message => 
                    io.sockets.emit( 'get_twilio_messages_in_conversation', {
                        from_number: data.from_number,
                        response: message,
                    })
                ) 
                .done();
        } else {
            twilio_client.messages 
                .list({limit: 20}) 
                .then(
                    message => 
                    io.sockets.emit( 'get_twilio_messages_in_conversation', {
                        from_number: data.from_number,
                        response: message,
                    })
                ) 
                .done();
        }
	});
    
    socket.on( 'get_twilio_messages_media', function( data ) {
        twilio_client.messages(data.sid)
            .media
            .list()
            .then(
                media => 
                io.sockets.emit( 'get_twilio_messages_media', {
                    response: media,
                })
            ) 
            .done();
	});
    
    socket.on( 'read_twilio_messages', function( data ) {
        var message_sql = "SELECT * FROM `twilio_webhook_demo` WHERE `webhook_type` = 'message_comes' AND `message_from` = '"+ data.from_number +"' ORDER BY `id` DESC  LIMIT 0,20";
        con_mysql.query(message_sql, function (err, result) {
            io.sockets.emit( 'read_twilio_messages', {
                from_number: data.from_number,
                user_id: data.user_id,
                result: result,
                err: err,
            });
            var update_message_status_sql = "UPDATE `twilio_webhook_demo` SET `message_status` = '1' WHERE `webhook_type` = 'message_comes' AND `message_from` = '"+ data.from_number +"' ";
            con_mysql.query(update_message_status_sql, function (update_message_status_err, update_message_status_result) {});
        });
    });

    /*
    * Message From to Customer
    * */
    socket.on( 'new_message_from_customer', function( data ) {
        io.sockets.emit( 'new_message_from_customer', {
            message_from: data.message_from,
            message_wanumber: data.message_wanumber
        });
    });
});

    function get_login_data(){
        var indianTimeZoneVal = new Date().toLocaleString('en-US', {timeZone: 'Asia/Calcutta'});
            var currentdate = new Date(indianTimeZoneVal);
            var d2 = new Date ( currentdate );
            d2.setMinutes ( currentdate.getMinutes());
            var currentHours = d2.getHours();
            currentHours = ("0" + currentHours).slice(-2);
            var currentMinutes = d2.getMinutes();
            currentMinutes = ("0" + currentMinutes).slice(-2);
            var curr_time = currentHours + ":" + currentMinutes;
//            console.log('current_time '+curr_time);
            var sql = "SELECT * FROM settings WHERE settings_label = 'Login To' ";
            con_mysql.query(sql, function (err, result) {
            if (err)
                throw err;
            if (result != '') {
                var setting_time = timeConvertor(result[0].settings_value);
//                console.log('setting '+setting_time);
                var diff_time = diff(curr_time,setting_time);
//                console.log('diff '+diff_time);
                if(diff_time <= '00:10'){
//                    console.log('alert');
//                    var sql_user = "SELECT socket_id FROM user_master WHERE user_id != '1' AND socket_id IS NOT NULL  ";
                    var sql_user = "SELECT socket_id FROM user_master WHERE user_id NOT IN (1, 10, 44) AND socket_id IS NOT NULL "; // Admin, Love Kush, Sukhshanti
                    con_mysql.query(sql_user, function (err, user_result) {
                        if (err)
                            throw err;
                        if (user_result != '') {
                            user_result.forEach(function (user_data) {
                                io.to(user_data.socket_id).emit('logout_notify', {
                                    time:result[0].settings_value
                                });
                            });
                        }
                    });
                }
                if(curr_time >= setting_time){
//                    console.log('log_out');
//                    var sql_user = "SELECT socket_id FROM user_master WHERE user_id != '1' AND socket_id IS NOT NULL  ";
                    var sql_user = "SELECT socket_id FROM user_master WHERE user_id NOT IN (1, 10, 44) AND socket_id IS NOT NULL "; // Admin, Love Kush, Sukhshanti
                    con_mysql.query(sql_user, function (err, user_result) {
                        if (err)
                            throw err;
                        if (user_result != '') {
                            user_result.forEach(function (user_data) {
                                io.to(user_data.socket_id).emit('logout_user', {
                                    time: result[0].settings_value
                                });
                            });
                        }
                    });
                }
                result.forEach(function (remi_data) {
                    io.to(remi_data.socket_id).emit('reminder_notify', {
                    });
                });
            }
        });
        setTimeout(function () {
            get_login_data();
        }, 300000);
    }
    
    function timeConvertor(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var AMPM = time.match(/\s(.*)$/)[1];
        if(AMPM == "PM" && hours<12) hours = hours+12;
        if(AMPM == "AM" && hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if(hours<10) sHours = "0" + sHours;
        if(minutes<10) sMinutes = "0" + sMinutes;
        return sHours + ":" + sMinutes;
    }

    
function diff(start, end) {
    start = start.split(":");
    end = end.split(":");
    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
    var diff = endDate.getTime() - startDate.getTime();
    var hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;
    var minutes = Math.floor(diff / 1000 / 60);

    // If using time pickers with 24 hours format, add the below line get exact hours
    if (hours < 0)
       hours = hours + 24;

    return (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
}

//function delete_reminder_data() {
//    setTimeout(function () {
//        var acc_sql = " DELETE FROM reminder WHERE account_id IN (SELECT account_id FROM `account` WHERE gold_fine = 0 AND silver_fine = 0 AND amount = 0) ";
//        con_mysql.query(acc_sql, function (err, result) {
//            if (err)
//                throw err;
//            if(result != ''){
//            }
//        });
//        delete_reminder_data();
//        // 7 minutue 420000
//    }, 420000);
//}

function when_a_twilio_sendbox_message_comes() {
    setTimeout(function () {
        var message_sql = "SELECT * FROM `twilio_webhook_demo` WHERE `webhook_type` = 'message_comes' AND `message_status` = '0' ORDER BY `id` DESC  LIMIT 0,5";
        con_mysql.query(message_sql, function (err, result) {
            if (err)
                throw err;
            if(result != ''){
                io.sockets.emit( 'new_twilio_sendbox_message_comes', {});
            }
        });
        when_a_twilio_sendbox_message_comes();
        // 1/2 Second
    }, 500);
}
