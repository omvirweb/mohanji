<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

class Kaleyra_whatsapp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->now_time = date('Y-m-d H:i:s');
    }
    
    function whatsapp()
    {
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        // if($this->applib->have_access_role(DEMO_MODULE_ID,"view")) {
            $data = array();
//            $from_numbers = $this->crud->getFromSQL("SELECT `id`, `message_from`, message_wanumber, `message_name` FROM `kaleyra_webhook_demo` WHERE `message_from` != '' AND `message_from` != '912912623524' GROUP BY `message_wanumber`,`message_from` ORDER BY `id` DESC");
            $from_numbers = $this->crud->getFromSQL("SELECT `id`, `message_from`, message_wanumber, `message_name` FROM `kaleyra_webhook_demo` WHERE `message_from` != '' AND (`message_from` != '912912623524') GROUP BY `message_from` ORDER BY `id` DESC");

            $from_numbers_arr = array();
            foreach ($from_numbers as $from_number) {
                $from_number->message_wanumber = substr($from_number->message_wanumber, -10);
                $from_number->message_from = substr($from_number->message_from, -10);
                $from_numbers_arr[$from_number->message_from] = $from_number;
            }
            $data['from_numbers'] = $from_numbers_arr;
            set_page('kaleyra_whatsapp', $data);
        // } else {
        //     $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
        //     redirect("/");
        // }
        // 

    }

    function load_conversation($number)
    {
        if (!isset($number) || !$number) {
            echo 'Error: No record found' ;
            return;
        }
        $number = substr($number, -10);
        $messages = $this->crud->getFromSQL("SELECT `message_name`, `message_body`, SUBSTR(`message_from`, -10) as `message_from`, `created_at`, SUBSTR(`message_wanumber`, -10) `message_wanumber` FROM `kaleyra_webhook_demo` WHERE 1 AND (
            `message_from` = '".$number."' OR `message_from` = '91".$number."' OR `message_wanumber` = '".$number."' OR `message_wanumber` = '+91".$number."'
        ) ORDER BY `created_at` ASC LIMIT 1000");
        echo json_encode($messages);
        exit;
    }
    
    function send_whatsapp_message($whatsapp_number, $whatsapp_message)
    {
        $data_array = array();
        $data_array['message_body'] = urldecode($whatsapp_message);
        $data_array['message_from'] = '912912623524';
        $data_array['message_name'] = 'HRDK';
        $data_array['message_type'] = 'text';
        $data_array['message_created_at'] = strtotime($this->now_time);
        $whatsapp_number = str_replace('+', '', $whatsapp_number);
        $data_array['message_wanumber'] = urldecode($whatsapp_number);
        $data_array['created_at'] = $this->now_time;
        $insert = $this->crud->insert('kaleyra_webhook_demo', $data_array);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.kaleyra.io/v1/HXAP1661953983IN/messages');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        
        $headers = array();
        $headers[] = 'Api-Key: A6f13f4b93751bc8cacc7f270b155382e';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                "to" => urldecode($whatsapp_number),
                "type" => "text",
                "body" => urldecode($whatsapp_message),
                "channel" => 'whatsapp',
                'from' => '+912912623524',
                "callback_url" => 'http://165.22.252.210/demo1/kaleyra_whatsapp/update_status?database_id='.$insert,
            )
        );

        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);
        $update['message_id'] = json_decode($result)->id;
        $update['webhook_content'] = $result;
        $where['id'] = $insert;
        $result = $this->crud->update('kaleyra_webhook_demo', $update, $where);

        echo $insert;
    }
    
    function read_whatsapp_message()
    {
//        $webhook_content = json_encode($_REQUEST);
//        $message_id = $webhook_content->id;
//        $data_array['message_body'] = urldecode($webhook_content->body);
//        $data_array['message_id'] = $message_id;
//        $data_array['message_from'] = $webhook_content->data[0]->recipient;
//        $data_array['message_name'] = 'HRDK';
//        $data_array['message_type'] = 'text';
//        $data_array['webhook_content'] = $webhook_content;
//        $data_array['message_created_at'] = strtotime($this->now_time);
//        $whatsapp_number = str_replace('+', '', $whatsapp_number);
//        $data_array['message_wanumber'] = urldecode($whatsapp_number);
//        $data_array['created_at'] = $this->now_time;
//
//        $this->crud->insert('kaleyra_webhook_demo', $data_array);

        $message_body = $_REQUEST['body'];
        $message_from = null;
        if(isset($_REQUEST['from'])){
                $message_from = $_REQUEST['from'];
        }
        $message_name = null;
        if(isset($_REQUEST['name'])){
                $message_name = $_REQUEST['name'];
        }
        $message_type = null;
        if(isset($_REQUEST['type'])){
                $message_type = $_REQUEST['type'];
        }
        $message_created_at = null;
        if(isset($_REQUEST['created_at'])){
                $message_created_at = $_REQUEST['created_at'];
        }
        $message_wanumber = null;
        if(isset($_REQUEST['wanumber'])){
                $message_wanumber = $_REQUEST['wanumber'];
        }
        $webhook_content = json_encode($_REQUEST);
        $data_array = array();
        $message_id = $webhook_content->id;
        $data_array['message_id'] = $message_id;
        $data_array['message_body'] = $message_body;
        $data_array['message_from'] = $message_from;
        $data_array['message_name'] = $message_name;
        $data_array['message_type'] = $message_type;
        $data_array['message_created_at'] = $message_created_at;
        $data_array['message_wanumber'] = $message_wanumber;
        $data_array['webhook_content'] = $webhook_content;
        $data_array['created_at'] = $this->now_time;
        $this->crud->insert('kaleyra_webhook_demo', $data_array);

        $message_from = substr($message_from, 2);
        $client = new Client(new Version2X(SERVER_REQUEST_SCHEME . '://' . HTTP_HOST . ':' . PORT_NUMBER));
        $client->initialize();
        $client->emit('new_message_from_customer', ['message_from' => $message_from, 'message_wanumber' => $message_wanumber]);
        $client->close();
        exit;
    }

    function update_status()
    {
        $webhook_content = $_REQUEST;
        $status = $webhook_content['status'];

        $update['message_status'] = $status;
        $where['id'] = $webhook_content['database_id'];
        
        $result = $this->crud->update('kaleyra_webhook_demo', $update, $where);
        exit;
    }
    
}