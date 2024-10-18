<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Twilio_whatsapp_demo extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->now_time = date('Y-m-d H:i:s');
    }
    
    function test(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(DEMO_MODULE_ID,"view")) {
            $data = array();
            set_page('twilio_whatsapp_demo', $data);
            
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function whatsapp(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(DEMO_MODULE_ID,"view")) {
            $data = array();
            $from_numbers = $this->crud->getFromSQL("SELECT `message_from` FROM `twilio_webhook_demo` WHERE `webhook_type` = 'message_comes' AND `message_from` != '' GROUP BY `message_from`");
            $from_numbers_arr = array();
            foreach ($from_numbers as $from_number){
                $message_from = str_replace('+91', '', $from_number->message_from);
                $account_name = $this->crud->getFromSQL("SELECT `account_name` FROM `account` WHERE `account_mobile` LIKE '%".$message_from."%'");
                $from_number->account_name = '';
                if(!empty($account_name)){
                    $from_number->account_name = $account_name[0]->account_name;
                }
                $from_numbers_arr[] = $from_number;
            }
            $data['from_numbers'] = $from_numbers_arr;
            set_page('twilio_whatsapp', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_conversations($conversation_text = ''){
        $data = array();
        $sql_query = "SELECT `message_from` FROM `twilio_webhook_demo` ";
        $sql_query .= " WHERE `webhook_type` = 'message_comes' AND `message_from` != '' ";
        $sql_query .= " GROUP BY `message_from`";
        $from_numbers = $this->crud->getFromSQL($sql_query);
        $from_numbers_arr = array();
        foreach ($from_numbers as $from_number){
            $message_from = str_replace('+91', '', $from_number->message_from);
            $account_name = $this->crud->getFromSQL("SELECT `account_name` FROM `account` WHERE `account_mobile` LIKE '%".$message_from."%'");
            $from_number->account_name = '';
            if(!empty($account_name)){
                $from_number->account_name = $account_name[0]->account_name;
            }
            $from_numbers_arr[] = $from_number;
        }
        echo json_encode($from_numbers_arr);
        exit;
    }
    
    function pre_webhook(){
        $webhook_content = json_encode($_REQUEST);
        $data_array = array();
        $data_array['webhook_type'] = 'pre';
        $data_array['webhook_content'] = $webhook_content;
        $data_array['created_at'] = $this->now_time;
        $data_array['created_by'] = '1';
        $data_array['updated_at'] = $this->now_time;
        $data_array['updated_by'] = '1';
        $this->crud->insert('twilio_webhook_demo', $data_array);
    }
    
    function post_webhook(){
        $webhook_content = json_encode($_REQUEST);
        $data_array = array();
        $data_array['webhook_type'] = 'post';
        $data_array['webhook_content'] = $webhook_content;
        $data_array['created_at'] = $this->now_time;
        $data_array['created_by'] = '1';
        $data_array['updated_at'] = $this->now_time;
        $data_array['updated_by'] = '1';
        $this->crud->insert('twilio_webhook_demo', $data_array);
    }
    
    function message_comes_in_webhook(){
        $message_from = null;
        if(isset($_REQUEST['From'])){
            $message_from = $_REQUEST['From'];
            $message_from = explode(':', $message_from);
            $message_from = $message_from[1];
        }
        $message_to = null;
        if(isset($_REQUEST['To'])){
            $message_to = $_REQUEST['To'];
            $message_to = explode(':', $message_to);
            $message_to = $message_to[1];
        }
        $message_body = null;
        if(isset($_REQUEST['Body'])){
            $message_body = $_REQUEST['Body'];
        }
        $webhook_content = json_encode($_REQUEST);
        $data_array = array();
        $data_array['webhook_type'] = 'message_comes';
        $data_array['webhook_content'] = $webhook_content;
        $data_array['message_from'] = $message_from;
        $data_array['message_to'] = $message_to;
        $data_array['message_body'] = $message_body;
        $data_array['created_at'] = $this->now_time;
        $data_array['created_by'] = '1';
        $data_array['updated_at'] = $this->now_time;
        $data_array['updated_by'] = '1';
        $this->crud->insert('twilio_webhook_demo', $data_array);
    }
    
}