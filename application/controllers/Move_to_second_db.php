<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Move_to_second_db extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->hlimited_db = $this->load->database("hrdklimited",true);
        $this->hfull_db = $this->load->database("hrdkfull",true);
        $this->testlimited = $this->load->database("testlimited",true);
    }
    
    function index(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            $data = array();
            set_page('move_to_second_db', $data);
            
//            // Demo script
//            $this->hlimited_db->select("*");
//            $this->hlimited_db->from("category");
//            $category_query = $this->hlimited_db->get();
//            foreach ($category_query->result() as $category_row) {
//                $select_result = $this->hfull_db->select('*')->from('category')->where('category_id', $category_row->category_id)->get();
//                if ($select_result->num_rows() > 0){
//                    $insert_update_result = $this->hfull_db->where('category_id', $category_row->category_id)->update('category', $category_row);
//                } else {
//                    $insert_update_result = $this->hfull_db->insert('category', $category_row);
//                }
////                echo '<pre>'; print_r($select_result);
////                echo $this->hfull_db->last_query(); exit;
//                echo $category_row->category_id;
////                $this->hlimited_db->where('category_id', $category_row->category_id)->delete('category');
//                echo '<br>';
//            }
            
//            $this->hfull_db->select("*");
//            $this->hfull_db->from("category");
//            $category_query = $this->hfull_db->get();
//            foreach ($category_query->result() as $category_row) {
//                $select_result = $this->hlimited_db->select('*')->from('category')->where('category_id', $category_row->category_id)->get();
//                if ($select_result->num_rows() > 0){
//                    $insert_update_result = $this->hlimited_db->where('category_id', $category_row->category_id)->update('category', $category_row);
//                } else {
//                    $insert_update_result = $this->hlimited_db->insert('category', $category_row);
//                }
////                echo '<pre>'; print_r($select_result);
////                echo $this->hfull_db->last_query(); exit;
//                echo $insert_id = $category_row->category_id;;
//                $this->hfull_db->where('category_id', $category_row->category_id)->delete('category');
//                echo '<br>';
//            }
            
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function copy_masters_data(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            
            // Account Group Modules
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("account_group");
            $account_group_query = $this->hlimited_db->get();
            foreach ($account_group_query->result() as $account_group_row) {
                $select_result = $this->hfull_db->select('*')->from('account_group')->where('account_group_id', $account_group_row->account_group_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('account_group_id', $account_group_row->account_group_id)->update('account_group', $account_group_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('account_group', $account_group_row);
                }
                echo $account_group_row->account_group_id;
                echo '<br>';
            }
            
            // Account Modules
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("account");
            $account_query = $this->hlimited_db->get();
            foreach ($account_query->result() as $account_row) {
                $select_result = $this->hfull_db->select('*')->from('account')->where('account_id', $account_row->account_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('account_id', $account_row->account_id)->update('account', $account_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('account', $account_row);
                }
                
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("party_item_details");
                $this->hlimited_db->where('account_id', $account_row->account_id);
                $party_item_details_query = $this->hlimited_db->get();
                foreach ($party_item_details_query->result() as $party_item_details_row) {
                    $oli_select_result = $this->hfull_db->select('*')->from('party_item_details')->where('party_item_id', $party_item_details_row->party_item_id)->get();
                    if ($oli_select_result->num_rows() > 0){
                        $insert_update_result = $this->hfull_db->where('party_item_id', $party_item_details_row->party_item_id)->update('party_item_details', $party_item_details_row);
                    } else {
                        $insert_update_result = $this->hfull_db->insert('party_item_details', $party_item_details_row);
                    }
                    echo $party_item_details_row->party_item_id;
                    echo '<br>';
                }
                
                echo $account_row->account_id;
                echo '<br>';
            }
            
            // carat Tunch Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("carat");
            $carat_query = $this->hlimited_db->get();
            foreach ($carat_query->result() as $carat_row) {
                $select_result = $this->hfull_db->select('*')->from('carat')->where('carat_id', $carat_row->carat_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('carat_id', $carat_row->carat_id)->update('carat', $carat_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('carat', $carat_row);
                }
                echo $carat_row->carat_id;
                echo '<br>';
            }
            
            // Category Group Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("category_group");
            $category_group_query = $this->hlimited_db->get();
            foreach ($category_group_query->result() as $category_group_row) {
                $select_result = $this->hfull_db->select('*')->from('category_group')->where('category_group_id', $category_group_row->category_group_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('category_group_id', $category_group_row->category_group_id)->update('category_group', $category_group_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('category_group', $category_group_row);
                }
                echo $category_group_row->category_group_id;
                echo '<br>';
            }
            
            // Category Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("category");
            $category_query = $this->hlimited_db->get();
            foreach ($category_query->result() as $category_row) {
                $select_result = $this->hfull_db->select('*')->from('category')->where('category_id', $category_row->category_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('category_id', $category_row->category_id)->update('category', $category_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('category', $category_row);
                }
                echo $category_row->category_id;
                echo '<br>';
            }
            
            // Item Master Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("item_master");
            $item_query = $this->hlimited_db->get();
            foreach ($item_query->result() as $item_row) {
                $select_result = $this->hfull_db->select('*')->from('item_master')->where('item_id', $item_row->item_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('item_id', $item_row->item_id)->update('item', $item_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('item_master', $item_row);
                }
                echo $item_row->item_id;
                echo '<br>';
            }
            
            // Order Status Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("order_status");
            $order_status_query = $this->hlimited_db->get();
            foreach ($order_status_query->result() as $order_status_row) {
                $select_result = $this->hfull_db->select('*')->from('order_status')->where('order_status_id', $order_status_row->order_status_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('order_status_id', $order_status_row->order_status_id)->update('order_status', $order_status_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('order_status', $order_status_row);
                }
                echo $order_status_row->order_status_id;
                echo '<br>';
            }
            
            // User Master Modules
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("user_master");
            $user_master_query = $this->hlimited_db->get();
            foreach ($user_master_query->result() as $user_master_row) {
                $select_result = $this->hfull_db->select('*')->from('user_master')->where('user_id', $user_master_row->user_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('user_id', $user_master_row->user_id)->update('user_master', $user_master_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('user_master', $user_master_row);
                }
                
                // User Family members
                $this->hfull_db->delete("user_family_member", array('user_id', $user_master_row->user_id));
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("user_family_member");
                $this->hlimited_db->where('user_id', $user_master_row->user_id);
                $user_family_member_query = $this->hlimited_db->get();
                foreach ($user_family_member_query->result() as $user_family_member_row) {
                    $insert_update_result = $this->hfull_db->insert('user_family_member', $user_family_member_row);
                }
                
                // User Departments
                $this->hfull_db->delete("user_department", array('user_id', $user_master_row->user_id));
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("user_department");
                $this->hlimited_db->where('user_id', $user_master_row->user_id);
                $user_department_query = $this->hlimited_db->get();
                foreach ($user_department_query->result() as $user_department_row) {
                    $insert_update_result = $this->hfull_db->insert('user_department', $user_department_row);
                }
                
                // User Order Departments
                $this->hfull_db->delete("user_order_department", array('user_id', $user_master_row->user_id));
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("user_order_department");
                $this->hlimited_db->where('user_id', $user_master_row->user_id);
                $user_order_department_query = $this->hlimited_db->get();
                foreach ($user_order_department_query->result() as $user_order_department_row) {
                    $insert_update_result = $this->hfull_db->insert('user_order_department', $user_order_department_row);
                }
                
                // User Account Groups
                $this->hfull_db->delete("user_account_group", array('user_id', $user_master_row->user_id));
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("user_account_group");
                $this->hlimited_db->where('user_id', $user_master_row->user_id);
                $user_account_group_query = $this->hlimited_db->get();
                foreach ($user_account_group_query->result() as $user_account_group_row) {
                    $insert_update_result = $this->hfull_db->insert('user_account_group', $user_account_group_row);
                }
                
                echo $user_master_row->user_id;
                echo '<br>';
            }
            
            // City Table
            // $this->hfull_db->empty_table('city');
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("city");
            $city_query = $this->hlimited_db->get();
            foreach ($city_query->result() as $city_row) {
                $select_result = $this->hfull_db->select('*')->from('city')->where('city_id', $city_row->city_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('city_id', $city_row->city_id)->update('city', $city_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('city', $city_row);
                }
                echo $city_row->city_id;
                echo '<br>';
            }
            
            // State Table
            // $this->hfull_db->empty_table('state');
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("state");
            $state_query = $this->hlimited_db->get();
            foreach ($state_query->result() as $state_row) {
                $select_result = $this->hfull_db->select('*')->from('state')->where('state_id', $state_row->state_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('state_id', $state_row->state_id)->update('state', $state_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('state', $state_row);
                }
                echo $state_row->state_id;
                echo '<br>';
            }
            
            // Sell Type Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("sell_type");
            $sell_type_query = $this->hlimited_db->get();
            foreach ($sell_type_query->result() as $sell_type_row) {
                $select_result = $this->hfull_db->select('*')->from('sell_type')->where('sell_type_id', $sell_type_row->sell_type_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('sell_type_id', $sell_type_row->sell_type_id)->update('sell_type', $sell_type_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('sell_type', $sell_type_row);
                }
                echo $sell_type_row->sell_type_id;
                echo '<br>';
            }
            
            // User Type Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("user_type");
            $user_type_query = $this->hlimited_db->get();
            foreach ($user_type_query->result() as $user_type_row) {
                $select_result = $this->hfull_db->select('*')->from('user_type')->where('user_type_id', $user_type_row->user_type_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('user_type_id', $user_type_row->user_type_id)->update('user_type', $user_type_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('user_type', $user_type_row);
                }
                echo $user_type_row->user_type_id;
                echo '<br>';
            }
            
            // Design Master Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("design_master");
            $design_master_query = $this->hlimited_db->get();
            foreach ($design_master_query->result() as $design_master_row) {
                $select_result = $this->hfull_db->select('*')->from('design_master')->where('design_id', $design_master_row->design_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('design_id', $design_master_row->design_id)->update('design_master', $design_master_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('design_master', $design_master_row);
                }
                echo $design_master_row->design_id;
                echo '<br>';
            }
            
            // Settings Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("settings");
            $settings_query = $this->hlimited_db->get();
            foreach ($settings_query->result() as $settings_row) {
                $select_result = $this->hfull_db->select('*')->from('settings')->where('settings_id', $settings_row->settings_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('settings_id', $settings_row->settings_id)->update('settings', $settings_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('settings', $settings_row);
                }
                echo $settings_row->settings_id;
                echo '<br>';
            }
            
            // Settings Mac Address Table
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("setting_mac_address");
            $setting_mac_address_query = $this->hlimited_db->get();
            foreach ($setting_mac_address_query->result() as $setting_mac_address_row) {
                $select_result = $this->hfull_db->select('*')->from('setting_mac_address')->where('id', $setting_mac_address_row->id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('id', $setting_mac_address_row->id)->update('setting_mac_address', $setting_mac_address_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('setting_mac_address', $setting_mac_address_row);
                }
                echo $setting_mac_address_row->id;
                echo '<br>';
            }
            
            exit;
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function move_all_data(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            
            $dir = './backup_email/';
            if (!(file_exists($dir))) {
                mkdir($dir, 0777);
            }
            $this->load->helper('file');
            $this->load->dbutil();
            $file_name = 'hrdk_' . date("Y-m-d-H-i-s") . '.sql.zip';
            $prefs = array(
                'format' => 'zip',
                'filename' => $file_name,
                'add_drop' => FALSE,
                'foreign_key_checks' => FALSE,
            );
            $backup = $this->dbutil->backup($prefs);
            $way = $dir . $file_name;
            $handle = fopen($way, 'w+');
            fwrite($handle, $backup);
            fclose($handle);
            $this->load->library('email');
            $to_email_address = 'omviravinash@gmail.com';//SEND_BACKUP_EMAIL_ON;
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'ssl://smtp.googlemail.com';
            $config['smtp_port']    = '465';
            $config['smtp_timeout'] = '120';
            $config['smtp_user']    = 'omweb2013@gmail.com';
            $config['smtp_pass']    = '9374973952';
            $config['charset']    = 'utf-8';
            $config['wordwrap'] = TRUE;
    //        $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->from('omweb2013@gmail.com','HRDK');
            $this->email->to($to_email_address);

            $this->email->subject('Database Backup of HRDK');
            $message = "<html><head><head></head><body><p>Hi,</p>Database Backup of HRDK</body></html>";
            $this->email->message($message);
            $this->email->attach($way);
            $this->email->send();
            unlink($way);
            
            // Order Modules
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("new_order");
            $this->hlimited_db->where('order_status_id', COMPLETED_STATUS);
            $new_order_query = $this->hlimited_db->get();
            $new_orders = $new_order_query->result();
            if(!empty($new_orders)){
            foreach ($new_orders as $new_order_row) {
                $select_result = $this->hfull_db->select('*')->from('new_order')->where('order_id', $new_order_row->order_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('order_id', $new_order_row->order_id)->update('new_order', $new_order_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('new_order', $new_order_row);
                }
                
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("order_lot_item");
                $this->hlimited_db->where('order_id', $new_order_row->order_id);
                $order_lot_item_query = $this->hlimited_db->get();
                $order_lot_items = $order_lot_item_query->result();
                if(!empty($order_lot_items)){
                foreach ($order_lot_items as $order_lot_item_row) {
                    $oli_select_result = $this->hfull_db->select('*')->from('order_lot_item')->where('order_lot_item_id', $order_lot_item_row->order_lot_item_id)->get();
                    if ($oli_select_result->num_rows() > 0){
                        $insert_update_result = $this->hfull_db->where('order_lot_item_id', $order_lot_item_row->order_lot_item_id)->update('order_lot_item', $order_lot_item_row);
                    } else {
                        $insert_update_result = $this->hfull_db->insert('order_lot_item', $order_lot_item_row);
                    }
                    echo $order_lot_item_row->order_lot_item_id;
                    echo '<br>';
                }
                }
                
//                echo '<pre>'; print_r($select_result);
//                echo $this->hfull_db->last_query(); exit;
                echo $new_order_row->order_id;
                $this->hlimited_db->where('order_id', $new_order_row->order_id)->delete('order_lot_item');
                $this->hlimited_db->where('order_id', $new_order_row->order_id)->delete('new_order');
                echo '<br>';
            }
            }
            
            // Cashbook Modules
            $this->hlimited_db->select("*");
            $this->hlimited_db->from("payment_receipt");
            $this->hlimited_db->where('sell_id', NULL);
            $this->hlimited_db->where('other_id', NULL);
            $payment_receipt_query = $this->hlimited_db->get();
            $account_arr = array();
            $payment_receipts = $payment_receipt_query->result();
            if(!empty($payment_receipts)){
            foreach ($payment_receipts as $payment_receipt_row) {
                if(in_array($payment_receipt_row->department_id, $account_arr)){ } else {
                    $account_arr[] = $payment_receipt_row->department_id;
                }
                if(in_array($payment_receipt_row->account_id, $account_arr)){ } else {
                    $account_arr[] = $payment_receipt_row->account_id;
                }
                $select_result = $this->hfull_db->select('*')->from('payment_receipt')->where('pay_rec_id', $payment_receipt_row->pay_rec_id)->get();
                if ($select_result->num_rows() > 0){
                    $insert_update_result = $this->hfull_db->where('pay_rec_id', $payment_receipt_row->pay_rec_id)->update('payment_receipt', $payment_receipt_row);
                } else {
                    $insert_update_result = $this->hfull_db->insert('payment_receipt', $payment_receipt_row);
                }
                echo $payment_receipt_row->pay_rec_id;
                $this->hlimited_db->where('pay_rec_id', $payment_receipt_row->pay_rec_id)->delete('payment_receipt');
                echo '<br>';
            }
            }
            
            if(!empty($account_arr)){
                $this->hlimited_db->select("*");
                $this->hlimited_db->from("account");
                $this->hlimited_db->where_in('account_id', $account_arr);
                $account_query = $this->hlimited_db->get();
                $accounts = $account_query->result();
                if(!empty($accounts)){
                foreach ($accounts as $account_row) {
                    $select_result = $this->hfull_db->select('*')->from('account')->where('account_id', $account_row->account_id)->get();
                    if ($select_result->num_rows() > 0){
                        $insert_update_result = $this->hfull_db->where('account_id', $account_row->account_id)->update('account', $account_row);
                    } else {
                        $insert_update_result = $this->hfull_db->insert('account', $account_row);
                    }

                    echo $account_row->account_id;
                    echo '<br>';
                }
                }
            }
            
            exit;
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function move_data_using_procedure(){
        $return = array();
        $post_data = $this->input->post();
        $query = $this->db->query("CALL `insert_triggers`()");
        if($query){
            $return['success'] = "Called";
        }
//        $query->free_result();
        print json_encode($return);
        exit;
    }
}