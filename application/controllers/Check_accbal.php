<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Check_accbal extends CI_Controller {

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

    // Check Account Balance Functions
    function index() {
        if ($this->applib->have_access_role(OUTSTANDING_MODULE_ID, "view") && $this->applib->have_access_role(OUTSTANDING_MODULE_ID, 'view')) {
            $data['account_groups'] = $this->crud->get_columns_val_by_where('account_group', 'account_group_id,account_group_name', array());
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));

            $data['account_id'] = isset($_GET['account_id']) && !empty($_GET['account_id']) ? $_GET['account_id'] : '';
            $data['account_group_id'] = isset($_GET['account_group_id']) && !empty($_GET['account_group_id']) ? $_GET['account_group_id'] : '';

            set_page('reports/check_accbal',$data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function get_account_balance($account_id){
        $data = array();
        if(!empty($account_id)){
            $account_data = $this->crud->get_row_by_id('account',array('account_id' => $account_id));
            if(!empty($account_data)){
                $data['gold_fine'] = $account_data[0]->gold_fine;
                $data['silver_fine'] = $account_data[0]->silver_fine;
                $data['amount'] = $account_data[0]->amount;
            }
        }
        print json_encode($data);
        exit;
    }
    
}
