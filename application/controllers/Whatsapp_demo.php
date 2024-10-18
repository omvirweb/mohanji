<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_demo extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
    }
    
    function test(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(DEMO_MODULE_ID,"view")) {
            $data = array();
            set_page('whatsapp_demo', $data);
            
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
}