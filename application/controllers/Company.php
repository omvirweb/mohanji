<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Company extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('Appmodel', 'app_model');
        $this->load->model('Crud', 'crud');
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }

    function company_details() {
//        echo '<pre>'.$this->db->last_query(); exit;
//        echo '<pre>'; print_r($name_data); exit;
        $data = array();
            if ($this->applib->have_access_role(COMPANY_DETAILS_MODULE_ID, "edit")) {
                $result = $this->crud->get_data_row_by_id('company_details', 'company_id', '1');
                $data['company_details'] = $result;
                set_page('company/company_details', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
    }

    function save_company() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $post_data['updated_at'] = $this->now_time;
        $post_data['updated_by'] = $this->logged_in_id;
        $where_array['company_id'] = '1';
        $result = $this->crud->update('company_details', $post_data, $where_array);
        if ($result) {
            $return['success'] = "Updated";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Company Details Updated Successfully');
        }
        print json_encode($return);
        exit;
    }
    
}
