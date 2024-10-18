<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Crud $crud
 */

class Auth extends CI_Controller
{
    public $now_time = null;
    function __construct()
    {
        parent::__construct();
        $this->load->model("Appmodel", "app_model");
        $this->load->model('Crud', 'crud');
//        $this->load->library(array('session', 'form_validation', 'email')); 
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
        $this->department_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }

    function index()
    {
        if ($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            $data = array();
            $orders = $this->crud->get_all_records('new_order', 'order_id', 'ASC');
            $lot_items = $this->crud->get_all_records('order_lot_item', 'order_lot_item_id', 'ASC');
            $total_order = 0;
            $total_lot_items = 0;
            if(!empty($orders)){
                foreach ($orders as $order) {
                     $total_order++;     
                }
            }
            if(!empty($lot_items)){
                foreach ($lot_items as $lot_item){
                    $total_lot_items++;
                }
            }
            $data['total_order'] = $total_order;
            $data['total_lot_items'] = $total_lot_items;
            $delivery_date = date('Y-m-d', strtotime('+2 days'));
            $sql = "
                    SELECT count(oi.order_lot_item_id) as pending_orders
                    FROM order_lot_item oi
                    LEFT JOIN new_order o ON oi.order_id = o.order_id
                    WHERE oi.item_status_id = 1 AND o.delivery_date <= '".$delivery_date."'";

            $pending_orders = $this->crud->getFromSQL($sql);
            $data['pending_orders'] = isset($pending_orders[0]->pending_orders) && $pending_orders[0]->pending_orders ? $pending_orders[0]->pending_orders : 0;

            $gold_silver_fine_data = $this->get_gold_silver_fine();

            $data = array_merge($data,$gold_silver_fine_data);
            if($this->user_type == USER_TYPE_USER){
//                $where = '';
//                if($this->department_id != ''){
//                    $where = " AND no.process_id = ". $this->department_id;
//                }
                
                $custom_where = '';
                $account_groups = $this->applib->current_user_account_group_ids();
                if(!empty($account_groups)) {
                    $custom_where .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
                } else {
                    $custom_where .= ' AND a.account_group_id IN(-1)';
                }

                $account_ids = $this->applib->current_user_account_ids();
                if($account_ids == "allow_all_accounts") {

                } elseif(!empty($account_ids)) {
                    $custom_where .= ' AND a.account_id IN ('.implode(',',$account_ids).')';
                } else {
                    $custom_where .= ' AND a.account_id IN(-1)';
                }

                $department_ids = $this->applib->current_user_order_department_ids();
                if(!empty($department_ids)){
                    $department_ids = implode(',', $department_ids);
                    $custom_where .= ' AND no.process_id IN('.$department_ids.')';
                } else {
                    $custom_where .= ' AND no.process_id IN(-1)';
                }
                
                $sql = "
                    SELECT no.order_id, no.order_no, no.delivery_date, im.design_no, im.die_no, im.image as item_image, c.purity, oi.weight, oi.pcs, oi.size, oi.length, oi.hook_style as hook, no.remark, oi.image as order_image
                    FROM new_order no
                    LEFT JOIN account a ON a.account_id = no.party_id
                    LEFT JOIN order_lot_item oi ON oi.order_id = no.order_id
                    LEFT JOIN item_master im ON im.item_id = oi.item_id
                    LEFT JOIN carat c ON c.carat_id = oi.touch_id
                    WHERE oi.item_status_id = 1 ".$custom_where."
                    ORDER BY no.order_id ASC;
                ";
                $data['orders'] = $this->crud->getFromSQL($sql);
                set_page('master/slider',$data);
            } else {
                set_page('dashboard', $data);
            }
        } else {
            redirect('/auth/login/');
        }
    }

    function get_gold_silver_fine()
    {
        $department_ids = $this->applib->current_user_department_ids();
        $gold_fine_category_id = GOLD_FINE_CATEGORY_ID;
        $silver_fine_category_id = SILVER_FINE_CATEGORY_ID;
        $gold_fine_item_id = GOLD_FINE_ITEM_ID;
        $silver_fine_item_id = SILVER_FINE_ITEM_ID;
        $this->db->select('`s`.`department_id`, `s`.`category_id`, `s`.`item_id`, `s`.`item_stock_id`, `s`.`tunch`,`cat`.`category_name`, `im`.`item_name`, `im`.`stock_method`, `cat`.`category_group_id`,SUM(s.grwt) AS grwt,SUM(s.ntwt) AS ntwt,sum(s.less) AS less, SUM((s.ntwt * s.tunch)/100) AS fine');
        $this->db->from('item_stock s');
        $this->db->join('item_master im','im.item_id = s.item_id','left');
        $this->db->join('account pm','pm.account_id = s.department_id','left');
        $this->db->join('category cat','cat.category_id = s.category_id','left');
        $this->db->where('(im.stock_method = 1 AND (s.grwt = 0 OR s.grwt != 0) OR (im.stock_method = 2 AND s.grwt != 0) OR (im.stock_method = 3 AND s.grwt != 0))');
        $this->db->where_in('s.department_id',$department_ids);
        $this->db->where_in('s.category_id',array($gold_fine_category_id,$silver_fine_category_id));
        $this->db->where_in('s.item_id',array($gold_fine_item_id,$silver_fine_item_id));
        $this->db->where('s.grwt != 0');
        $this->db->group_by('s.category_id, s.item_id, if(`im`.`stock_method` = 1, `s`.`tunch`, "")');
        $query = $this->db->get();

        $silver_fine = 0;
        $gold_fine = 0;
        $gold_stock_ledger_url = '#';
        $silver_stock_ledger_url = '#';
        if($query->num_rows() > 0) {
            foreach ($query->result() as $stock) {
                $gold = 0;
                $silver = 0;

                if ($stock->category_group_id == 1) {
                    $gold = number_format((float) $stock->fine, 3, '.', '');
                    $gold_fine = (float) $gold_fine + (float) $gold;

                    $gold_stock_ledger_url = base_url('reports/stock_ledger/' . $stock->item_stock_id.'/0');
                } else if ($stock->category_group_id == 2) {
                    $silver = number_format((float) $stock->fine, 3, '.', '');
                    $silver_fine = (float) $silver_fine + (float) $silver;

                    $silver_stock_ledger_url = base_url('reports/stock_ledger/' . $stock->item_stock_id.'/0');
                }
            }
        }
        return array(
            'gold_fine' => $gold_fine,
            'silver_fine' => $silver_fine,
            'gold_stock_ledger_url' => $gold_stock_ledger_url,
            'silver_stock_ledger_url' => $silver_stock_ledger_url
        );
    }

    function get_pending_orders() {
        $delivery_date = date('Y-m-d', strtotime($_POST['delivery_date']));
        $return = array();
        $sql = "
                SELECT count(oi.order_lot_item_id) as pending_orders
                FROM order_lot_item oi
                LEFT JOIN new_order o ON oi.order_id = o.order_id
                WHERE oi.item_status_id = 1 AND o.delivery_date <= '".$delivery_date."'";

        $pending_orders = $this->crud->getFromSQL($sql);

        $return['pending_orders'] = isset($pending_orders[0]->pending_orders) && $pending_orders[0]->pending_orders ? $pending_orders[0]->pending_orders : 0;
        print json_encode($return);
        exit;
    }
    /**
     * Login user on the site
     *
     * @return void
     */
    function login()
    {
        if ($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {                                    // logged in
            if($this->user_type == USER_TYPE_USER){
                redirect('new_order/new_order_list');
            } else {
                redirect('');
            }
        } else {
            $my_mac_address = '';
            $cmd = "ifconfig -a | grep -Po 'HWaddr \K.*$'";
            $status = 0;
            $return = [];
            exec($cmd, $return, $status);
            $mac_add = (Array) $return;
            foreach ($mac_add as $address){
                $my_mac_address = $address;
            }
            $mac_addressess = array();
            $sql = "SELECT LOWER(mac_address) AS mac FROM setting_mac_address";
            $rows = $this->db->query($sql)->result();
            $i = 0;
            foreach ($rows as $row) {
                $mac_addressess[] = $row->mac;
                $i++;
            }
//            if(in_array($my_mac_address, $mac_addressess)){
                $this->form_validation->set_rules('user_name','User Name', 'trim|required');
                $this->form_validation->set_rules('user_password', 'password', 'trim|required');
                $this->form_validation->set_rules('remember', 'Remember me', 'integer');
                $data['errors'] = array();
                if ($this->form_validation->run()) {
                    $email = $_POST['user_name'];
                    $pass = $_POST['user_password'];
                    $response = $this->app_model->login($email,$pass);
                    if ($response) {

//                        //check user is already logged in or not
//                        if($response[0]['is_login'] == 1) {
//                            $this->session->set_flashdata('success', false);
//                            $this->session->set_flashdata('message', 'Please Logout From Another Place (1 user can login from 1 place only)');
//                            redirect('auth/login');
//                        }

                        //check user active or inactive
                        if($response[0]['status'] == 1){
                            $this->session->set_flashdata('success', false);
                            $this->session->set_flashdata('message', 'Your account not active');
                            redirect('auth/login');
                        }

                        if($response[0]['user_type'] == USER_TYPE_WORKER || $response[0]['user_type'] == USER_TYPE_SALESMAN){
                            $this->session->set_flashdata('success', false);
                            $this->session->set_flashdata('message', 'Your Are Not Allow to login.');
                            redirect('auth/login');
                        }
                        
                        if($response[0]['user_type'] != 1 && $response[0]['user_id'] != STAFF_LOVE_KUSH_USER_ID && $response[0]['user_id'] != STAFF_SUKHSHANTI_USER_ID){
                            $session_login_start_time = $login_start_time = $this->crud->get_column_value_by_id("settings","settings_value",array("settings_key"=>"login_time_start"));
                            $session_login_end_time = $login_end_time = $this->crud->get_column_value_by_id("settings","settings_value",array("settings_key"=>"login_time_end"));
                            $now_time = date('H:i:s');
                            $login_start_time = date('H:i:s',strtotime($login_start_time));
                            $login_end_time = date('H:i:s',strtotime($login_end_time));
                            if(strtotime($now_time) <= strtotime($login_start_time) || strtotime($now_time) >= strtotime($login_end_time)){
                                $data['errors']['invalid'] = 'Invalid Login Time';
                                $this->session->set_flashdata('success', false);
                                $this->session->set_flashdata('message', 'Invalid Login Time! Login allow between '.$session_login_start_time.' to '.$session_login_end_time);
                                redirect('auth/login');
                            }
                        }
                        
                        if(!empty(ALLOW_TO_LOGIN_AT_A_TIME) && ALLOW_TO_LOGIN_AT_A_TIME != 'all'){
                            $user_data = $this->crud->get_row_by_id('user_master',array('is_login' => '1'));
                            echo $logged_in_users_count = count($user_data);
                            if($logged_in_users_count >= ALLOW_TO_LOGIN_AT_A_TIME){
                                $this->session->set_flashdata('success', false);
                                $this->session->set_flashdata('message', 'Already '. ALLOW_TO_LOGIN_AT_A_TIME .' Users are logged in!!!');
                                redirect('auth/login');
                            }
                        }
                        
                        $mobile_no = $this->crud->get_column_value_by_id("settings","settings_value",array("settings_key"=>"send_otp_mobile_no"));
                        $user_data = $this->crud->get_data_row_by_id('user_master','user_id',$response[0]['user_id']);
                        if(!empty($user_data->otp_on_user) && !empty($user_data->user_mobile)){
                            $mobile_no = $user_data->user_mobile;
                        }
                        $direct_login_flag = 1;
                        if(!empty($mobile_no)){
                            $direct_login_flag = 0;
                            $otp_value = mt_rand(100000,999999);
                            $this->crud->update('user_master', array('otp_value' => $otp_value), array('user_id' => $response[0]['user_id']));
                            $sms = SEND_OTP_SMS;
                            $vars = array(
                                '{{user_name}}' => $response[0]['login_username'],
                                '{{user_mobile}}' => $response[0]['user_mobile'],
                                '{{otp_value}}' => $otp_value,
                            );
                            $sms = strtr($sms, $vars);
                            $result = $this->applib->send_sms($mobile_no, $sms, 'for_login_otp');
                        } else {
                            $this->db->query("SET GLOBAL sql_mode = '' ");
                            $user_id = $response[0]['user_id'];
                            $sql = "
                                SELECT
                                        ur.user_id,ur.website_module_id,ur.role_type_id, LOWER(r.title) as role, LOWER(m.title) as module
                                FROM user_roles ur
                                INNER JOIN website_modules m ON ur.website_module_id = m.website_module_id
                                INNER JOIN module_roles r ON ur.role_type_id = r.module_role_id WHERE ur.user_id = $user_id;
                            ";
                            $results = $this->crud->getFromSQL($sql);

                            $roles = array();
                            foreach ($results as $row) {
                                $roles[$row->website_module_id][] = $row->role;
                            }
                            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'user_roles', $roles);
                            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'is_logged_in', $response[0]);
//                            $this->crud->update('user_master', array('is_login' => 1), array('user_id' => $user_id));
                            
                            $theme_color_code_results = $this->crud->getFromSQL("SELECT `settings_value` FROM `settings` WHERE `settings_key` = 'theme_color_code'");
                            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'theme_color_code', $theme_color_code_results[0]->settings_value);
                            
                            $sell_purchase_difference_results = $this->crud->getFromSQL("SELECT `settings_value` FROM `settings` WHERE `settings_key` = 'sell_purchase_difference'");
                            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'sell_purchase_difference', $sell_purchase_difference_results[0]->settings_value);

                            $company_data = $this->crud->getFromSQL("SELECT * FROM `account` WHERE `user_id` = '" . $user_id . "'");
                            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'company_data', $company_data[0]);

                            redirect('');
                        }
                        redirect('auth/load_otp_form/'.$response[0]['user_id']."/".$direct_login_flag);
                    } else {
                        $data['errors']['invalid'] = 'Invalid User Name or Password!';
                    }
                } else {
                    if (validation_errors()) {
                        $error_messages = $this->form_validation->error_array();
                        $data['errors'] = $error_messages;
                    }
                }
//            } else {
//                $data['errors']['invalid'] = 'Permission Denied!';
//            }
            $this->load->view('login_form', $data);
        }
    }
    
    function load_otp_form($id = '', $direct_login_flag = '') {
        $data['errors'] = array();
        if (isset($id)) {
            $data['user_id'] = $id;
        }
        if (isset($_POST['otp_value']) || ( isset($direct_login_flag) && $direct_login_flag == 1 )) {

            if ($direct_login_flag == 1) {
                $this->db->select('*');
                $this->db->from('user_master');
                $this->db->where('user_id', $data['user_id']);
                $this->db->limit('1');
                $query1 = $this->db->get();
                $check_otp = $query1->result_array();
            } else {
                $this->db->select('*');
                $this->db->from('user_master');
                $this->db->where('user_id', $_POST['user_id']);
                $this->db->where('otp_value', $_POST['otp_value']);
                $this->db->limit('1');
                $query1 = $this->db->get();
                $check_otp = $query1->result_array();
                $data['user_id'] = $_POST['user_id'];
            }

            if (!$check_otp) {
                $data['errors']['invalid'] = 'Invalid OTP!';
            } else {
                $this->db->query("SET GLOBAL sql_mode = '' ");
                $this->db->select('*');
                $this->db->from('user_master');
                $this->db->where('user_id', $_POST['user_id']);
                $query = $this->db->get();
                $response = $query->result_array();
                $user_id = $response[0]['user_id'];
                $sql = "
                    SELECT
                            ur.user_id,ur.website_module_id,ur.role_type_id, LOWER(r.title) as role, LOWER(m.title) as module
                    FROM user_roles ur
                    INNER JOIN website_modules m ON ur.website_module_id = m.website_module_id
                    INNER JOIN module_roles r ON ur.role_type_id = r.module_role_id WHERE ur.user_id = $user_id;
                ";
                $results = $this->crud->getFromSQL($sql);

                $roles = array();
                foreach ($results as $row) {
                    $roles[$row->website_module_id][] = $row->role;
                }
                $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'user_roles', $roles);
                $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'is_logged_in', $response[0]);
//                $this->crud->update('user_master', array('is_login' => 1), array('user_id' => $user_id));
                
                $theme_color_code_results = $this->crud->getFromSQL("SELECT `settings_value` FROM `settings` WHERE `settings_key` = 'theme_color_code'");
                $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'theme_color_code', $theme_color_code_results[0]->settings_value);

                $sell_purchase_difference_results = $this->crud->getFromSQL("SELECT `settings_value` FROM `settings` WHERE `settings_key` = 'sell_purchase_difference'");
                $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'sell_purchase_difference', $sell_purchase_difference_results[0]->settings_value);

                $company_data = $this->crud->getFromSQL("SELECT * FROM `account` WHERE `user_id` = '" . $user_id . "'");
                $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'company_data', $company_data[0]);

                redirect('');
            }
        }
        $this->load->view('otp_form', $data);
    }

    function logout()
    {
        $this->crud->update('user_master', array('is_login' => 0), array('user_id' => $this->logged_in_id));
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'user_roles');
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'is_logged_in');
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'theme_color_code');
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'sell_purchase_difference');
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'company_data');
        $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'download_backup_db');
//        session_destroy();
        redirect('auth/login');
    }

    function change_password() {
        $data = array();
        if (!empty($_POST)) {
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
            $this->form_validation->set_rules('old_pass', 'old password', 'trim|required|callback_check_old_password');
            $this->form_validation->set_rules('new_pass', 'new password', 'trim|required');
            $this->form_validation->set_rules('confirm_pass', 'confirm Password', 'trim|required|matches[new_pass]');
            if ($this->form_validation->run()) {
                $this->db->where('user_id', $_POST['user_id']);
                $this->db->update('user_master', array('user_password' => $_POST['new_pass']));
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'You have successfully changed password!');
                redirect('auth/change_password');
            } else {
                if (validation_errors()) {
                    $error_messages = $this->form_validation->error_array();
                    $data['errors'] = $error_messages;
                }
            }
            set_page('change_password', $data);
        } else {
            set_page('change_password');
        }
    }
   
    function check_old_password($old_pass){
        $user_id = $_POST['user_id'];
        $query = $this->db->get_where('user_master',array('user_id'=>$user_id,'user_password'=>$old_pass));
        if($query->num_rows() > 0){
            return true;
        }else{
            $this->form_validation->set_message('check_old_password', 'wrong old password.');
            return false;
        }
    }

    function register() {
        $this->load->view('register_form', $data = array());
    }

    function save_user(){
        $return = array();
        $post_data = $this->input->post();

        $recaptcha = trim($this->input->post('g-recaptcha-response'));
        $userIp= $this->input->ip_address();
        // Local 
        $secret='6LfWnjUUAAAAAGatwDgHHy7td7_c3dxZRfX2ZpnZ';
        //$secret='6LeCxzUUAAAAAChk7TPTM2qQozrzv9UH3xA4XUIA';
        $data_capcha = array(
            'secret' => "$secret",
            'response' => "$recaptcha",
            'remoteip' =>"$userIp"
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data_capcha));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $status= json_decode($response, true);
        if(empty($status['success'])){
            $return['error'] = "re-enter-reCAPTCHA";
            $return['msg']="Please re-enter your reCAPTCHA.";
            print json_encode($return);
            exit;
        }else{
            $e_id = $this->crud->get_id_by_val('user','user_id','email_id',trim($post_data['company_email']));
            if(!empty($e_id)) {
                $return['error'] = "EmailExist";
                print json_encode($return);
                exit;
            }
            //echo '<pre>';print_r($post_data);exit;
            $user_id = isset($post_data['user_id']) ? $post_data['user_id'] : 0;
            $data["user_type_id"] = isset($post_data['user_type']) ? $post_data['user_type'] : null;
            $data['cin_no'] = isset($post_data['cin_no']) ? $post_data['cin_no'] : null;
            $data['gstin_no'] = isset($post_data['gstin_no']) ? $post_data['gstin_no'] : null;

            $data['service_type_id'] = isset($post_data['service_provide']) ? $post_data['service_provide'] : null;
            $data['company_name'] = isset($post_data['company_name']) ? $post_data['company_name'] : null;
            
            
            $data['email_id'] = isset($post_data['company_email']) ? $post_data['company_email'] : null;
            
            
            $data['company_contact_no'] = isset($post_data['company_contact_no']) ? $post_data['company_contact_no'] : null;

            $data['contact_person'] = isset($post_data['contact_person']) ? $post_data['contact_person'] : null;
            $data['cp_mobile_no'] = isset($post_data['mobile_number']) ? $post_data['mobile_number'] : null;
            $data['cp_phone_no'] = isset($post_data['phone_number']) ? $post_data['phone_number'] : null;

            $data['country_id'] = isset($post_data['country_id']) ? $post_data['country_id'] : null;
            $data['state_id'] = isset($post_data['state_id']) ? $post_data['state_id'] : null;
            $data['city_id'] = isset($post_data['city_id']) ? $post_data['city_id'] : null;


            $data['address'] = isset($post_data['address']) ? $post_data['address'] : null;
            $data['pincode'] = isset($post_data['pincode']) ? $post_data['pincode'] : null;

            $data['nickname'] = isset($post_data['email_id']) ? $post_data['email_id'] : null;
            $data['password'] = isset($post_data['password']) ? md5($post_data['password']) : null;
            $verification_code = $this->generateRandomString();
            $data['verification_code'] = isset($verification_code) ? $verification_code : null;

            $data['created_at'] = $this->now_time;
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = '0';
            $data['created_by'] = '0';

            $result = $this->crud->insert('user',$data);
            if($result){
                if($this->sendemail($data['email_id'], $verification_code)){
                    // successfully sent mail to user email
                    $return['success'] = "Added";
                    $this->session->set_flashdata('success',true);
                    //$this->session->set_flashdata('message','Account Added Successfully');
                    $last_query_id = $this->db->insert_id();
                    
                    $this->session->set_flashdata('msg','<div class="alert alert-success alert-dismissable fade in text-center">Please confirm the mail sent to your email id to complete the registration.</div>');
                }else{
                    $this->session->set_flashdata('msg','<div class="alert alert-danger alert-dismissable fade in text-center">Please try again ...</div>');
                }
            }else{
                $return['error'] = "errorAdded";
            }
        }
        //echo '<pre>';print_r($data);exit;
        print json_encode($return);
        exit;
    }


    function get_user_details(){
        $cin_no = $_POST['value'];
        if(!empty($_POST)){
            $user_data = $this->crud->get_row_by_id('company_master',array('cin_no' => $cin_no));
            if(!empty($user_data)){

            }
            echo $user_data;
        }
        exit;
    }

    function terms_conditions(){
        //set_page('terms_conditions');
        $this->load->view('terms_conditions');
    }
    
    function forgot_pwd(){
        $return = array();
        $post_data = $this->input->post();
        $e_id = $this->crud->get_id_by_val('user_master','user_id','login_username',trim($post_data['user_name']));
        if(empty($e_id)) {
            $return['error'] = "EmailNotExist";
            print json_encode($return);
            exit;
        }
        $new_pwd = $this->generateRandomString(8);
        //echo '<pre>';print_r($new_pwd);exit;
        $data['password'] = isset($new_pwd) ? md5($new_pwd) : null;
        $data['isforgot'] = 1;
        $data['updated_at'] = $this->now_time;
        //$data['updated_by'] = '0';
        //$data['updated_by'] = $this->logged_in_id;
        $where_array['email_id'] = $post_data['user_email'];
        $result = $this->crud->update('user', $data, $where_array);
        if($result){
            if($this->sendemail_pwd($post_data['user_email'], $new_pwd)){
                // successfully sent mail to user email
                $return['success'] = "Added";
                $this->session->set_flashdata('success',true);
                //$this->session->set_flashdata('message','Account Added Successfully');
                $last_query_id = $this->db->insert_id();
                $this->session->set_flashdata('msg','<div class="alert alert-success alert-dismissable fade in text-center">A new password has been sent to your e-mail address.</div>');
            }else{
                $this->session->set_flashdata('msg','<div class="alert alert-danger alert-dismissable fade in text-center">Please try again ...</div>');
            }
        }else{
            $return['error'] = "errorAdded";
        }
        //echo '<pre>';print_r($data);exit;
        print json_encode($return);
        exit;
    }
    
    function sendemail_pwd($email,$pwd){
        // configure the email setting
        $this->load->library('email');
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.zoho.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'app@shipmentsmart.com';
        $config['smtp_pass']    = 'SMart@8484';
        $config['charset']    = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not
        $this->email->initialize($config);

        $this->email->from('app@shipmentsmart.com', 'Shipment Smart');
        $this->email->to($email); 
        $this->email->bcc('app@shipmentsmart.com'); 
        $this->email->subject('New Password For Sign in.');
        $message = "<html><head><head></head><body><p>Hi,</p><p>Your New password is : </p><b>".$pwd."</b><br/><p>Sincerely,</p><p>ShipmentSmart Team</p></body></html>";
        $this->email->message($message);
        /*if ($this->email->send()){
            echo "hi its works";
        }else{
            show_error($this->email->print_debugger());
        }*/
        return $this->email->send();
    }
    
    function update_user_socket_id($login_user_id, $socket_id){
        $data = array(
            'is_login' => 1,
            'socket_id' => $socket_id
        );
        $this->crud->update('user_master', $data, array('user_id' => $login_user_id));
    }

    function change_profile() {
        $data = array();
        $data['account_data'] = $this->crud->get_data_row_by_id('account', 'user_id', $this->logged_in_id);
        if (!empty($_POST)) {
            $this->crud->update('account', array('account_name' => $_POST['account_name']), array('account_id' => $_POST['account_id']));

            $company_data = $this->crud->getFromSQL("SELECT * FROM `account` WHERE `account_id` = '" . $_POST['account_id'] . "'");
            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'company_data', $company_data[0]);

            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Profile successfully Updated!');
            redirect('auth/change_profile');
        }
        set_page('change_profile', $data);
    }
}
