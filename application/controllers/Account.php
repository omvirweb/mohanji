<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Account extends CI_Controller {

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

    function account() {
        $name_data = $this->crud->execuetSQL("SET SESSION group_concat_max_len = 1000000;");
        $name_data = $this->crud->getFromSQL('SELECT GROUP_CONCAT(\'\"\', account_name, \'\"\') as names FROM account');
        $name_data = $name_data[0]->names;
//        echo '<pre>'.$this->db->last_query(); exit;
//        echo '<pre>'; print_r($name_data); exit;
        if (!empty($_POST['account_id']) && isset($_POST['account_id'])) {
            if ($this->applib->have_access_role(ACCOUNT_MODULE_ID, "edit")) {
                $oreder_lot_item = new \stdClass();
                $lineitems = array();
                $result = $this->crud->get_data_row_by_id('account', 'account_id', $_POST['account_id']);
                $result->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $result->created_by));
                if ($result->created_by != $result->updated_by) {
                    $result->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $result->updated_by));
                } else {
                    $result->updated_by_name = $result->created_by_name;
                }
                $data = array(
                    'account_id' => $result->account_id,
                    'account_name' => $result->account_name,
                    'account_email_ids' => $result->account_email_ids,
                    'account_address' => $result->account_address,
                    'account_state' => $result->account_state,
                    'account_city' => $result->account_city,
                    'account_postal_code' => $result->account_postal_code,
                    'account_phone' => $result->account_phone,
                    'account_mobile' => $result->account_mobile,
                    'account_contect_person_name' => $result->account_contect_person_name,
                    'account_group_id' => $result->account_group_id,
                    'account_remarks' => $result->account_remarks,
                    'account_gst_no' => $result->account_gst_no,
                    'account_aadhaar' => $result->account_aadhaar,
                    'account_pan' => $result->account_pan,
                    'interest' => $result->interest,
                    'opening_balance' => $result->opening_balance,
                    'credit_debit' => $result->credit_debit,
                    'opening_balance_in_gold' => $result->opening_balance_in_gold,
                    'gold_ob_credit_debit' => $result->gold_ob_credit_debit,
                    'opening_balance_in_silver' => $result->opening_balance_in_silver,
                    'silver_ob_credit_debit' => $result->silver_ob_credit_debit,
                    'opening_balance_in_rupees' => $result->opening_balance_in_rupees,
                    'price_per_pcs' => $result->price_per_pcs,
                    'rupees_ob_credit_debit' => $result->rupees_ob_credit_debit,
                    'bank_name' => $result->bank_name,
                    'bank_account_no' => $result->bank_account_no,
                    'ifsc_code' => $result->ifsc_code,
                    'bank_interest' => $result->bank_interest,
                    'credit_limit' => $result->credit_limit,
                    'is_supplier' => $result->is_supplier,
                    'created_by_name' => $result->created_by_name,
                    'created_at' => $result->created_at,
                    'updated_by_name' => $result->updated_by_name,
                    'updated_at' => $result->updated_at
                );
                $data['names'] = $name_data;
                $party_item_details = $this->crud->get_row_by_id('party_item_details', array('account_id' => $result->account_id));
                foreach ($party_item_details as $lot_item) {
                    $oreder_lot_item->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                    $oreder_lot_item->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                    $oreder_lot_item->account_id = $lot_item->account_id;
                    $oreder_lot_item->category_id = $lot_item->category_id;
                    $oreder_lot_item->item_id = $lot_item->item_id;
                    $oreder_lot_item->wstg = $lot_item->wstg;
                    $oreder_lot_item->party_item_id = $lot_item->party_item_id;
                    $lineitems[] = json_encode($oreder_lot_item);
                }
                $data['party_item_detail'] = implode(',', $lineitems);
                $data['account_mobile_no_is_required'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'account_mobile_no_is_required'));
                set_page('account/account', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(ACCOUNT_MODULE_ID, "add")) {
                $data = array();
                $data['names'] = $name_data;
                $data['account_mobile_no_is_required'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'account_mobile_no_is_required'));
//                echo '<pre>'; print_r($data); exit;
                set_page('account/account', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }
    function get_name() {
        //print_r($_POST['keyword']); exit;
        $data = '';
        if(!empty($_POST['keyword'])){}
        $this->db->select('*');
        $this->db->from('account');
        $this->db->like('account_name', $_POST['keyword']);
        $res = $this->db->get();
        $rows = $res->num_rows();
        
        if ($rows > 0) {
            $result = $res->result();
            
//            $result = $result[0];
            //echo "<pre>";
            //print_r($result); exit;
            $data = '<ul id="name-list">';
            foreach($result as $name){
                //print_r($name);
                
                $data .= '<li class="li_tag" onClick="selectCountry(\''.$name->account_name.'\')">'.$name->account_name.'</li>';
                
            }
            $data .= '</ul>';
        }
        echo $data;
    }

    function save_account() {
        $return = array();
        $post_data = $this->input->post();
        $is_approve = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "approve");
//        echo '<pre>'; print_r($post_data); exit;
        if (isset($post_data['account_group_id']) && 
            ($post_data['account_group_id'] == CUSTOMER_GROUP 
            || $post_data['account_group_id'] == SUNDRY_CREDITORS_ACCOUNT_GROUP 
            || $post_data['account_group_id'] == SUNDRY_DEBTORS_ACCOUNT_GROUP)
        ) {
            $line_items_data = json_decode($post_data['line_items_data']);
        }
        $account_id = isset($post_data['account_id']) ? $post_data['account_id'] : 0;
        $post_data['account_state'] = isset($post_data['account_state']) ? $post_data['account_state'] : null;
        $post_data['account_city'] = isset($post_data['account_city']) ? $post_data['account_city'] : null;
        $post_data['account_group_id'] = isset($post_data['account_group_id']) ? $post_data['account_group_id'] : null;
        $post_data['account_email_ids'] = isset($post_data['account_email_ids']) ? $post_data['account_email_ids'] : null;
        $post_data['account_phone'] = isset($post_data['account_phone']) ? $post_data['account_phone'] : null;
        $post_data['account_mobile'] = isset($post_data['account_mobile']) ? $post_data['account_mobile'] : null;
        $post_data['account_remarks'] = isset($post_data['account_remarks']) ? $post_data['account_remarks'] : null;
        $post_data['account_address'] = isset($post_data['account_address']) ? $post_data['account_address'] : null;
        $post_data['account_postal_code'] = isset($post_data['account_postal_code']) ? $post_data['account_postal_code'] : null;
        $post_data['account_contect_person_name'] = isset($post_data['account_contect_person_name']) ? $post_data['account_contect_person_name'] : null;
        $post_data['account_gst_no'] = isset($post_data['account_gst_no']) ? $post_data['account_gst_no'] : null;
        $post_data['account_aadhaar'] = isset($post_data['account_aadhaar']) ? $post_data['account_aadhaar'] : null;
        $post_data['account_pan'] = isset($post_data['account_pan']) ? $post_data['account_pan'] : null;
        $post_data['interest'] = isset($post_data['interest']) ? $post_data['interest'] : null;
        $post_data['opening_balance'] = isset($post_data['opening_balance']) ? $post_data['opening_balance'] : null;
        $post_data['credit_debit'] = isset($post_data['credit_debit']) ? $post_data['credit_debit'] : null;
        $post_data['bank_name'] = isset($post_data['bank_name']) ? $post_data['bank_name'] : null;
        $post_data['bank_account_no'] = isset($post_data['bank_account_no']) ? $post_data['bank_account_no'] : null;
        $post_data['ifsc_code'] = isset($post_data['ifsc_code']) ? $post_data['ifsc_code'] : null;
        $post_data['bank_interest'] = isset($post_data['bank_interest']) ? $post_data['bank_interest'] : null;
        $post_data['opening_balance_in_rupees'] = isset($post_data['opening_balance_in_rupees']) && !empty($post_data['opening_balance_in_rupees']) ? $post_data['opening_balance_in_rupees'] : 0;
        $post_data['opening_balance_in_gold'] = isset($post_data['opening_balance_in_gold']) && !empty($post_data['opening_balance_in_gold']) ? $post_data['opening_balance_in_gold'] : 0;
        $post_data['opening_balance_in_silver'] = isset($post_data['opening_balance_in_silver']) && !empty($post_data['opening_balance_in_silver']) ? $post_data['opening_balance_in_silver'] : 0;
        $post_data['price_per_pcs'] = isset($post_data['price_per_pcs']) && !empty($post_data['price_per_pcs']) ? $post_data['price_per_pcs'] : null;
        
        unset($post_data['line_items_index']);
        unset($post_data['line_items_data']);
        if ($is_approve) {
            $post_data['status'] = '1';
        } else {
            $post_data['status'] = '2';
            $post_data['account_group_id'] = NOT_APPROVED_ACCOUNT_GROUP_ID;
        }
        
        if (isset($post_data['account_name']) && !empty($post_data['account_name'])) {
            if (isset($post_data['account_id']) && !empty($post_data['account_id'])) {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_name' => $post_data['account_name'], 'account_id !=' => $post_data['account_id']));
            } else {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_name' => $post_data['account_name']));
            }
            if (isset($acc_duplication) && !empty($acc_duplication)) {
                $return['error'] = "accountExist";
                print json_encode($return);
                exit;
            }
        }
        
        if (isset($post_data['account_email_ids']) && !empty($post_data['account_email_ids'])) {
            if (isset($post_data['account_id']) && !empty($post_data['account_id'])) {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_email_ids' => $post_data['account_email_ids'], 'account_id !=' => $post_data['account_id']));
            } else {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_email_ids' => $post_data['account_email_ids']));
            }
            if (isset($acc_duplication) && !empty($acc_duplication)) {
                $acc_duplication = $acc_duplication[0];
                $return['error'] = "emailExist";
                $return['msg'] = $acc_duplication->account_name;
                print json_encode($return);
                exit;
            }
        }

        $account_mobile_nos = isset($post_data['account_mobile']) ? trim($post_data['account_mobile']) : '';
        // get emails
        $temp = nl2br($account_mobile_nos);
        $mobiles = explode(",", $temp);

        $mobile_status = 1;
        $mobile_msg = "";

        $account_mobile_nos = array();
        // multiple email validation
        if (is_array($mobiles) && count($mobiles) > 0) {
            foreach ($mobiles as $mobile) {
                if (trim($mobile) != "") {
                    $mobile = trim($mobile);
                    $this->db->select('*');
                    $this->db->from('account');
                    $this->db->where('account_id !=', $account_id);
//                    $this->db->where('created_by', $this->logged_in_id);
                    $this->db->like('account_mobile', $mobile);
                    $res = $this->db->get();
                    $rows = $res->num_rows();
                    if ($rows > 0) {
                        $result = $res->result();
                        $mobile_msg .= "$mobile Mobile already exist.&nbsp; <br/> Original Account : " . $result[0]->account_name;
                        $mobile_status = 0;
                    } else {
                        $account_mobile_nos[] = $mobile;
                    }
                }
            }
        }
        if ($mobile_status == 0) {
            $return['error'] = "mobileExist";
            $return['msg'] = $mobile_msg;
            echo json_encode($return);
            exit;
        }

        $account_email_ids = isset($post_data['account_email_ids']) ? trim($post_data['account_email_ids']) : '';
        // get emails
        $temp = nl2br($account_email_ids);
        $emails = explode(",", $temp);

        $email_status = 1;
        $email_msg = "";

        $account_email_ids = array();
        // multiple email validation
        if (is_array($emails) && count($emails) > 0) {
            foreach ($emails as $email) {
                if (trim($email) != "") {
                    $email = trim($email);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        $email_msg .= "$email is not valid email.&nbsp;";
                        $email_status = 0;
                    }
                    $this->db->select('*');
                    $this->db->from('account');
                    $this->db->where('account_id !=', $account_id);
                    $this->db->where('created_by', $this->logged_in_id);
                    $this->db->like('account_email_ids', $email);
                    $res = $this->db->get();
                    $rows = $res->num_rows();
                    if ($rows > 0) {
                        $result = $res->result();
                        $email_msg .= "$email email already exist.&nbsp; <br/> Original Account : " . $result[0]->account_name;
                        $email_status = 0;
                    } else {
                        $account_email_ids[] = $email;
                    }
                }
            }
        }
        if ($email_status == 0) {
            $return['error'] = "email_error";
            $return['msg'] = $email_msg;
            echo json_encode($return);
            exit;
        }
        $account_email_ids = implode(',', $account_email_ids);
        if (isset($post_data['account_id']) && !empty($post_data['account_id']) && $post_data['account_id'] != 0) {
            $post_data['account_email_ids'] = $account_email_ids;
            if (empty($post_data['account_email_ids'])) {
                $result = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['account_id']);
                $post_data['account_email_ids'] = $result->account_email_ids;
            }
            $old_ob_gold = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'opening_balance_in_gold');
            $gold_ob_cr_db = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'gold_ob_credit_debit');
            $old_gold_fine = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'gold_fine');
            
            $old_ob_silver = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'opening_balance_in_silver');
            $silver_ob_cr_db = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'silver_ob_credit_debit');
            $old_silver_fine = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'silver_fine');

            $old_ob_rs = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'opening_balance_in_rupees');
            $rc_ob_cr_db = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'rupees_ob_credit_debit');
            $old_amount = $this->crud->get_val_by_id('account', $post_data['account_id'], 'account_id', 'amount');

            if ($gold_ob_cr_db == '1') { 
                $old_gold_fine = $old_gold_fine + $old_ob_gold;
            } else {
                $old_gold_fine = $old_gold_fine - $old_ob_gold;
            }
            if ($silver_ob_cr_db == '1') { 
                $old_silver_fine = $old_silver_fine + $old_ob_silver;
            } else {
                $old_silver_fine = $old_silver_fine - $old_ob_silver;
            }
            if ($rc_ob_cr_db == '1') { 
                $old_amount = $old_amount + $old_ob_rs;
            } else {
                $old_amount = $old_amount - $old_ob_rs;
            }

            if ($post_data['gold_ob_credit_debit'] == '1') {
                $post_data['gold_fine'] = $old_gold_fine - $post_data['opening_balance_in_gold'];
            } else {
                $post_data['gold_fine'] = $old_gold_fine + $post_data['opening_balance_in_gold'];
            }
            if ($post_data['silver_ob_credit_debit'] == '1') {
                $post_data['silver_fine'] = $old_silver_fine - $post_data['opening_balance_in_silver'];
            } else {
                $post_data['silver_fine'] = $old_silver_fine + $post_data['opening_balance_in_silver'];
            }
            if ($post_data['rupees_ob_credit_debit'] == '1') {
                $post_data['amount'] = $old_amount - $post_data['opening_balance_in_rupees'];
            } else {
                $post_data['amount'] = $old_amount + $post_data['opening_balance_in_rupees'];
            }

            if(isset($post_data['is_supplier'])){
                unset($post_data['is_supplier']);
                $post_data['is_supplier'] = '1';
            } else {
                $post_data['is_supplier'] = '0';
            }
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $where_array['account_id'] = $post_data['account_id'];
            $result = $this->crud->update('account', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Account Updated Successfully');
                $last_query_id = $post_data['account_id'];

                $where = array("account_id" => $post_data['account_id']);
                $this->crud->delete("party_item_details", $where);
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        $update_item = array();
                        $update_item['account_id'] = $post_data['account_id'];
                        $update_item['category_id'] = $lineitem->category_id;
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['wstg'] = $lineitem->wstg;
                        $update_item['created_at'] = $this->now_time;
                        $update_item['created_by'] = $this->logged_in_id;
                        $update_item['updated_at'] = $this->now_time;
                        $update_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('party_item_details', $update_item);
                    }
                }
            } else {
                $return['error'] = "errorUpdated";
            }
        } else {
            $post_data['account_email_ids'] = $account_email_ids;
            $zero_value = 0;
            if ($post_data['gold_ob_credit_debit'] == '1') {
                $post_data['gold_fine'] = $zero_value - $post_data['opening_balance_in_gold'];
            } else {
                $post_data['gold_fine'] = $post_data['opening_balance_in_gold'];
            }
            if ($post_data['silver_ob_credit_debit'] == '1') {
                $post_data['silver_fine'] = $zero_value - $post_data['opening_balance_in_silver'];
            } else {
                $post_data['silver_fine'] = $post_data['opening_balance_in_silver'];
            }
            if ($post_data['rupees_ob_credit_debit'] == '1') {
                $post_data['amount'] = $zero_value - $post_data['opening_balance_in_rupees'];
            } else {
                $post_data['amount'] = $post_data['opening_balance_in_rupees'];
            }

            if(isset($post_data['is_supplier'])){
                unset($post_data['is_supplier']);
                $post_data['is_supplier'] = '1';
            } else {
                $post_data['is_supplier'] = '0';
            }
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['created_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $result = $this->crud->insert('account', $post_data);
//            echo $this->db->last_query();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Account Added Successfully');
                $last_query_id = $this->db->insert_id();

                $item_inc = 1;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        $insert_item = array();
                        $insert_item['account_id'] = $last_query_id;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('party_item_details', $insert_item);
                        $item_inc++;
                    }
                }
            } else {
                $return['error'] = "errorAdded";
            }
        }
        //echo '<pre>';print_r($data);exit;
        print json_encode($return);
        exit;
    }
    
    function get_account_wastages($acc_id){
        $return = array();
        $item_data = $this->crud->getFromSQL("SELECT i.item_id,i.item_name,c.category_id,c.category_name,pi.wstg FROM party_item_details as pi LEFT JOIN item_master as i ON i.item_id = pi.item_id  LEFT JOIN category as c ON c.category_id = pi.category_id WHERE pi.account_id = " . $acc_id . " ");
        $return['item_data'] = $item_data;
        print json_encode($return);
        exit;
    }

    function account_list() {
        if ($this->applib->have_access_role(ACCOUNT_MODULE_ID, "view")) {
            set_page('account/account_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function account_datatable() {
        $is_approve = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "approve");
        $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
        $config['table'] = 'account a';
        $config['select'] = 'a.is_active,IF(a.is_supplier = 1,"Yes", "No") AS supplier,a.gold_fine, a.silver_fine, a.amount, a.opening_balance_in_gold, a.gold_ob_credit_debit, a.opening_balance_in_silver, a.silver_ob_credit_debit, a.opening_balance_in_rupees, a.rupees_ob_credit_debit,a.account_id, a.account_name, a.account_email_ids, a.account_phone, a.account_mobile, ag.account_group_name as ag_name,a.interest,a.opening_balance,a.credit_debit,a.account_group_id,a.bank_name,a.bank_account_no,a.ifsc_code,a.bank_interest,a.credit_limit,IF(a.status = 1,"Approve","Not Approve") AS status, IF(a.gold_ob_credit_debit = 1,"Credit", "Debit") AS gold_ob_cd, IF(a.silver_ob_credit_debit = 1,"Credit", "Debit") AS silver_ob_cd, IF(a.rupees_ob_credit_debit = 1,"Credit", "Debit") AS rupees_ob_cd';
        $config['column_order'] = array(null, null, 'a.account_name','a.account_email_ids', 'a.account_phone', 'a.account_mobile', 'ag.account_group_name','a.is_supplier', 'a.interest', 'a.credit_limit', 'a.opening_balance_in_gold', 'a.gold_ob_credit_debit', 'a.opening_balance_in_silver', 'a.silver_ob_credit_debit', 'a.opening_balance_in_rupees', 'a.rupees_ob_credit_debit');
        $config['column_search'] = array('a.account_name', 'a.account_email_ids', 'a.account_phone', 'a.account_mobile', 'ag.account_group_name', 'a.interest', 'a.credit_limit', 'a.opening_balance', 'a.credit_debit', 'a.opening_balance_in_gold', 'IF(a.gold_ob_credit_debit = 1,"Credit", "Debit")', 'a.opening_balance_in_silver', 'IF(a.silver_ob_credit_debit = 1,"Credit", "Debit")', 'a.opening_balance_in_rupees', 'IF(a.rupees_ob_credit_debit = 1,"Credit", "Debit")', 'a.bank_name', 'a.bank_account_no', 'a.ifsc_code', 'a.bank_interest', 'IF(a.status = 1,"Approve","Not Approve")','IF(a.is_supplier = 1,"Yes", "No")');
//        if ($this->user_type != USER_TYPE_ADMIN) {
//            $config['wheres'][] = array('column_name' => 'a.created_by', 'column_value' => $this->logged_in_id);
//        }


        $config['custom_where'] = '1=1';
        $account_groups = $this->applib->current_user_account_group_ids();
        if(!empty($account_groups)) {
            $config['custom_where'] .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
        } else {
            $config['custom_where'] .= ' AND a.account_group_id IN(-1)';
        }

        $account_ids = $this->applib->current_user_account_ids();
        if($account_ids == "allow_all_accounts"){
            
        } elseif(!empty($account_ids)){
            $account_ids = implode(',', $account_ids);
            $config['custom_where'] .= ' AND a.account_id IN('.$account_ids.')';
        } else {
            $config['custom_where'] .= ' AND a.account_id IN(-1)';
        }
        
        if (isset($_POST['status']) && !empty($_POST['status'])) {
            $config['wheres'][] = array('column_name' => 'a.status', 'column_value' => $_POST['status']);
        }

        $config['joins'][] = array('join_table' => 'account_group ag', 'join_by' => 'ag.account_group_id = a.account_group_id', 'join_type' => 'left');
        $config['order'] = array('a.account_name' => 'ASC');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo "<pre>"; print_r($list); exit;
        $role_delete = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ACCOUNT_MODULE_ID, "edit");
        foreach ($list as $accounts) {
            $row = array();
            $action = '';
            if($accounts->account_group_id != ADMIN_GROUP && $accounts->account_group_id != USER_GROUP && $accounts->account_group_id != WORKER_GROUP && $accounts->account_group_id != SALESMAN_GROUP){
                if($role_edit){
                    $action .= '<div style="width:80px;"><form id="edit_' . $accounts->account_id . '" method="post" action="' . base_url() . 'account/account" style="width: 30px; display: initial;" >
                                <input type="hidden" name="account_id" id="account_id" value="' . $accounts->account_id . '">
                                <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $accounts->account_id . '\').submit();" title="Edit Account"><i class="fa fa-edit"></i></a>
                            </form> ';
                }
            }
            
            if($accounts->account_id == CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID || $accounts->account_id == ADJUST_EXPENSE_ACCOUNT_ID || $accounts->account_id == SALARY_EXPENSE_ACCOUNT_ID || $accounts->account_id == MF_LOSS_EXPENSE_ACCOUNT_ID || $accounts->account_id == XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID){
            } else {
            
                if($accounts->account_group_id != DEPARTMENT_GROUP && $accounts->account_group_id != ADMIN_GROUP && $accounts->account_group_id != USER_GROUP && $accounts->account_group_id != WORKER_GROUP && $accounts->account_group_id != SALESMAN_GROUP){
                    if ($role_delete) {
                        $action .= '| <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('account/delete/' . $accounts->account_id) . '"><i class="fa fa-trash"></i></a> ';
                    }
    //            $action .= '| <a href="javascript:void(0);" class="print_modal" title="ledger Print" data-account_id="' . $accounts->account_id . '" ><span class="glyphicon glyphicon-print" style="color : #419bf4"></span></a></div>';
    //                $action .= '| <a href="javascript:void(0);" class="ledger" title="ledger" data-account_id="' . $accounts->account_id . '" ><span class="fa fa-file" style="color : #419bf4"></span></a></div>';
                }
            }
            if($role_edit){
                if($accounts->is_active == '1'){
                    $action .= '| <a href="javascript:void(0);" title="Click To Deactivate" data-status="0" class="ac_dc_button" data-href="' . base_url('account/active_deactive/' . $accounts->account_id) . '"><span class="glyphicon glyphicon-ok" style="color : green">&nbsp;</span></a>';
                } else if($accounts->is_active == '0'){
                    $action .= '| <a href="javascript:void(0);" title="Click To Active" data-status="1" class="ac_dc_button" data-href="' . base_url('account/active_deactive/' . $accounts->account_id) . '"><span class="glyphicon glyphicon-remove" style="color : red">&nbsp;</span></a>';
                }
            }
            if($accounts->account_id == CASE_CUSTOMER_ACCOUNT_ID){
                $row[] = '';
            } else {
                $row[] = $action;
            }
            if ($is_approve && $accounts->status == "Not Approve") {
                $is_confirm_only = 'data-confirm_only="0"';
                if (in_array($accounts->account_group_id,array(ADMIN_GROUP,USER_GROUP,WORKER_GROUP,SALESMAN_GROUP))) {
                    $is_confirm_only = 'data-confirm_only="1"';
                }
                $icon = '&nbsp;&nbsp;<a href="javascript:void(0);" class="change_approve_status btn-primary btn-xs" data-href="' . base_url('account/change_approve_status/' . $accounts->account_id) . '" data-account_id="'.$accounts->account_id.'" '.$is_confirm_only.'><i class="fa fa-check"></i></a>';
            } else {
                $icon = '';
            }
            $row[] = $accounts->status . $icon;
            if ($accounts->account_group_id == CUSTOMER_GROUP || $accounts->account_group_id == SUNDRY_CREDITORS_ACCOUNT_GROUP || $accounts->account_group_id == SUNDRY_DEBTORS_ACCOUNT_GROUP) {
                $row[] = '<a href="javascript:void(0);" class="item_row" data-account_id="' . $accounts->account_id . '" >' . $accounts->account_name . '</a>';
            } else {
                $row[] = $accounts->account_name;
            }
            $p_inc = 1;
            $email_id_arr = explode(',', $accounts->account_email_ids);
            $email_ids = '';
            foreach ($email_id_arr as $email_id) {
                if (!empty(trim($email_id))) {
                    $email_ids .= $email_id . ', ';
                    if ($p_inc % 2 == 0) {
                        $email_ids .= '<br />';
                    }
                    $p_inc++;
                }
            }
            $row[] = $email_ids;
            $row[] = $accounts->account_phone;
            $row[] = $accounts->account_mobile;
            $row[] = $accounts->ag_name;
            $row[] = $accounts->supplier;
            $row[] = $accounts->interest;
            $row[] = $accounts->credit_limit;
            $row[] = $accounts->opening_balance_in_gold;
            $row[] = $accounts->gold_ob_cd;
            $row[] = $accounts->opening_balance_in_silver;
            $row[] = $accounts->silver_ob_cd;
            $row[] = $accounts->opening_balance_in_rupees;
            $row[] = $accounts->rupees_ob_cd;
            $row[] = $accounts->bank_name;
            $row[] = $accounts->bank_account_no;
            $row[] = $accounts->ifsc_code;
            $row[] = $accounts->bank_interest;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    function active_deactive($account_id){
        $this->crud->update('account', array('is_active' => $_POST['status']), array('account_id' => $account_id));
    }
    
    function account_item_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'party_item_details si';
        $config['select'] = 'si.*,im.item_name,c.category_name';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = si.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= si.category_id', 'join_type' => 'left');
        $config['column_order'] = array('c.category_name', 'im.item_name', 'si.wstg');
        $config['column_search'] = array('c.category_name', 'im.item_name', 'si.wstg');
        $config['order'] = array('si.party_item_id' => 'desc');
        if (isset($post_data['account_id']) && !empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 'si.account_id', 'column_value' => $post_data['account_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->category_name;
            $row[] = $sell_detail->item_name;
            $row[] = number_format($sell_detail->wstg, 3, '.', '');
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function is_account_balace_zero($account_id) {
        if (!empty($account_id)) {
            $account_data = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
            if (!empty($account_data)) {
                $sell_data = $this->crud->get_row_by_id('sell', array('account_id' => $account_id));
                $order_data = $this->crud->get_row_by_id('new_order', array('party_id' => $account_id));
                $journal_details_data = $this->crud->get_row_by_id('journal_details', array('account_id' => $account_id));
                $payment_receipt_data = $this->crud->get_row_by_id('payment_receipt', array('account_id' => $account_id));
                $transfer_data = $this->crud->get_row_by_id('transfer', array('transfer_account_id' => $account_id));
//                if ($account_data[0]->gold_fine == 0 && $account_data[0]->silver_fine == 0 && $account_data[0]->amount == 0) {
                if (empty($sell_data) && empty($order_data) && empty($journal_details_data) && empty($payment_receipt_data) && empty($transfer_data)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function is_opening_balace_zero($account_id) {
        if (!empty($account_id)) {
            $account_data = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
            if (!empty($account_data)) {
                $account_data = $account_data[0];
                $gold_opening = $account_data->opening_balance_in_gold;
                $silver_opening = $account_data->opening_balance_in_silver;
                $amount_opening = $account_data->opening_balance_in_rupees;
                if($gold_opening == 0 && $silver_opening == 0 && $amount_opening == 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function delete($id) {

        if (!$this->is_account_balace_zero($id)) {
            echo json_encode(array("success" => false, "message" => "You cannot delete this Account. This Account has been used."));
            exit();
        }

        if (!$this->is_opening_balace_zero($id)) {
            echo json_encode(array("success" => false, "message" => "You cannot delete this Account. Opening Balance Of Gold, Solver and Amount should be 0."));
            exit();
        }
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $return = $this->crud->delete($table, array($id_name => $id));
        if (isset($return['error']) && $return['error'] == 'Error') {
            print json_encode($return);
            exit;
        } else {
            $this->crud->delete('party_item_details', array('account_id' => $id));
            print json_encode($return);
            exit;
        }

        //$this->session->set_flashdata('success',true);
        //$this->session->set_flashdata('message','Deleted Successfully');
    }
    
    function delete_account_group($id) {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $return = $this->crud->delete($table, array($id_name => $id));
        print json_encode($return);
        exit;
    }

    function ledger_datatable() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell s';
        $config['select'] = 's.*,pm.account_name AS process_name,IF(s.delivery_type = 1, "Delivered" ,"Not Delivered") AS delivery_type';
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = s.process_id', 'join_type' => 'left');
        //$config['joins'][] = array('join_table' => 'sell_items si', 'join_by' => 'si.sell_id = s.sell_id', 'join_type' => 'left');
        //$config['joins'][] = array('join_table' => 'metal_payment_receipt mpr', 'join_by' => 'mpr.sell_id = s.sell_id', 'join_type' => 'left');
        $config['column_search'] = array('s.sell_no', 'pm.account_name', 'DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 'sell_remark', 'IF(s.delivery_type = 1, "Delivered" ,"Not Delivered")');
        $config['column_order'] = array(null, 'pm.account_name', 's.sell_no', 's.sell_date', 'sell_remark', 's.delivery_type');
        if (!empty($post_data['from_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 's.account_id', 'column_value' => $post_data['account_id']);
        }
        $config['group_by'] = 's.sell_id';
        $config['order'] = array('s.sell_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $where = '';
        if (!empty($post_data['from_date'])) {
            $where .= " AND s.sell_date >= '" . $from_date . "'";
        }
        if (!empty($post_data['to_date'])) {
            $where .= " AND s.sell_date <= '" . $to_date . "'";
        }

        foreach ($list as $sell) {
            $total_grwt = 0;
            $metal_data = $this->crud->getFromSQL("SELECT m.metal_payment_receipt, m.metal_grwt FROM `metal_payment_receipt` as m LEFT JOIN sell as s ON s.sell_id = m.sell_id WHERE s.sell_no = '" . $sell->sell_no . "'" . $where);
            if (!empty($metal_data)) {
                foreach ($metal_data as $data1) {
                    if ($data1->metal_payment_receipt == 1) {
                        $total_grwt = $total_grwt + $data1->metal_grwt;
                    } else {
                        $total_grwt = $total_grwt - $data1->metal_grwt;
                    }
                }
            }
            $sell_data = $this->crud->getFromSQL("SELECT si.type, si.grwt FROM `sell_items` as si LEFT JOIN sell as s ON s.sell_id = si.sell_id WHERE s.sell_no = '" . $sell->sell_no . "'" . $where);
            if (!empty($sell_data)) {
                foreach ($sell_data as $data1) {
                    if ($data1->type == 1) {
                        $total_grwt = $total_grwt + $data1->grwt;
                    } else {
                        $total_grwt = $total_grwt - $data1->grwt;
                    }
                }
            }
            $row = array();
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->process_name . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->sell_no . '</a>';
            $date = (!empty(strtotime($sell->sell_date))) ? date('d-m-Y', strtotime($sell->sell_date)) : '';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $date . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->delivery_type . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $total_grwt . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->total_gold_fine . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->total_silver_fine . '</a>';
            $row[] = '<a href="' . base_url("sell/add/" . $sell->sell_id) . '" target="_blank">' . $sell->total_amount . '</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list), //$this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function change_approve_status($id = '') {
//        echo '<pre>';        print_r($id); exit;
        $this->crud->update('account', array('status' => '1'), array('account_id' => $id));
    }

    function approve_account($account_id = '',$account_group_id = '') {
        
        if($account_id != '' && $account_group_id != '') {
            $this->crud->update('account', array('status' => '1','account_group_id' => $account_group_id), array('account_id' => $account_id));
        } elseif ($account_id != '') {
            $this->crud->update('account', array('status' => '1'), array('account_id' => $account_id));
        }
        echo json_encode(array('status' => "success"));
    }

    function ledger_print($account_id = '', $from_date = '', $to_date = '') {
        $data = array();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $data['account_name'] = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $account_id));
        $sell_array = $this->crud->get_row_for_customer_ledger($account_id, $from_date, $to_date);
        $data['opening'] = $this->crud->get_account_opening_balance($account_id, $from_date);
//        echo '<pre>'; print_r($sell_array); exit;
        if (!empty($sell_array)) {
            $data['sell_array'] = $sell_array;
        }
        //echo '<pre>'; print_r($sell_array); exit;

        $html = $this->load->view('account/ledger_print', $data, true);
        require_once 'application/vendor/autoload.php';
        require_once 'application/third_party/random_compat/lib/random.php';
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function account_group() {

        if (!empty($_POST['id']) && isset($_POST['id'])) {
            if ($this->applib->have_access_role(ACCOUNT_GROUP_MODULE_ID, "edit")) {
                $result = $this->crud->get_data_row_by_id('account_group', 'account_group_id', $_POST['id']);
                $data = array(
                    'id' => $result->account_group_id,
                    'parent_group_id' => $result->parent_group_id,
                    'account_group_name' => $result->account_group_name,
                    'sequence' => $result->sequence,
                    'is_display_in_balance_sheet' => $result->is_display_in_balance_sheet,
                    'use_in_profit_loss' => $result->use_in_profit_loss,
                    'move_data_opening_zero' => $result->move_data_opening_zero,
                );
                set_page('account/account_group', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(ACCOUNT_GROUP_MODULE_ID, "view") || $this->applib->have_access_role(ACCOUNT_GROUP_MODULE_ID, "add")) {
                $data = array();
                set_page('account/account_group', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_account_group() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['parent_group_id']) && !empty($post_data['parent_group_id'])) {
            $parent_group_id = $post_data['parent_group_id'];
        } else {
            $parent_group_id = 0;
        }
        if(isset($post_data['move_data_opening_zero'])){
            unset($post_data['move_data_opening_zero']);
            $data['move_data_opening_zero'] = '1';
        } else {
            $data['move_data_opening_zero'] = '0';
        }
        if (isset($post_data['id']) && !empty($post_data['id'])) {
            $id_result = $this->crud->get_same_by_val('account_group', 'account_group_id', 'account_group_name', trim($post_data['account_group_name']), 'parent_group_id', $parent_group_id, $post_data['id']);
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['parent_group_id'] = $parent_group_id;
            $data['account_group_name'] = $post_data['account_group_name'];
            $data['sequence'] = $post_data['sequence'];
            if(isset($post_data['is_display_in_balance_sheet'])) {
                $data['is_display_in_balance_sheet'] = "1";
            } else {
                $data['is_display_in_balance_sheet'] = "0";
            }
            if(isset($post_data['use_in_profit_loss'])){
                unset($post_data['use_in_profit_loss']);
                $data['use_in_profit_loss'] = '1';
            } else {
                $data['use_in_profit_loss'] = '0';
            }
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = $this->logged_in_id;
            $where_array['account_group_id'] = $post_data['id'];
            $result = $this->crud->update('account_group', $data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
            }
        } else {
            $id_result = $this->crud->get_same_by_val('account_group', 'account_group_id', 'account_group_name', trim($post_data['account_group_name']), 'parent_group_id', $parent_group_id);
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['parent_group_id'] = $parent_group_id;
            $data['account_group_name'] = $post_data['account_group_name'];
            $data['sequence'] = $post_data['sequence'];
            if(isset($post_data['is_display_in_balance_sheet'])) {
                $data['is_display_in_balance_sheet'] = "1";
            } else {
                $data['is_display_in_balance_sheet'] = "0";
            }
            if(isset($post_data['use_in_profit_loss'])){
                unset($post_data['use_in_profit_loss']);
                $data['use_in_profit_loss'] = '1';
            } else {
                $data['use_in_profit_loss'] = '0';
            }
            $data['created_at'] = $this->now_time;
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = $this->logged_in_id;
            $data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('account_group', $data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function account_group_datatable() {
        $config['table'] = 'account_group a';
        $config['select'] = 'a.*, pa.account_group_name as pa_account_group_name, IF(a.is_display_in_balance_sheet = 1, "Yes" ,"No") AS in_balance_sheet, IF(a.use_in_profit_loss = 1, "Yes" ,"No") AS use_profit_loss, IF(a.move_data_opening_zero = 1, "Yes" ,"No") AS move_data_opening';
        $config['column_order'] = array(null, 'pa.account_group_name', 'a.account_group_name', 'a.sequence', 'a.is_display_in_balance_sheet','a.use_in_profit_loss','a.move_data_opening_zero');
        $config['column_search'] = array('pa.account_group_name', 'a.account_group_name', 'IF(a.is_display_in_balance_sheet = 1, "Yes" ,"No")','IF(a.use_in_profit_loss = 1, "Yes" ,"No")','IF(a.move_data_opening_zero = 1, "Yes" ,"No")');
        $config['joins'][] = array('join_table' => 'account_group pa', 'join_by' => 'pa.account_group_id = a.parent_group_id', 'join_type' => 'left');
        
        $config['custom_where'] = '1=1';
        $account_groups = $this->applib->current_user_account_group_ids();
        if(!empty($account_groups)) {
            $config['custom_where'] .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
        } else {
            $config['custom_where'] .= ' AND a.account_group_id IN(-1)';
        }

        $config['order'] = array('a account_group_name' => 'asc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $isEdit = $this->app_model->have_access_role(ACCOUNT_GROUP_MODULE_ID, "edit");
        $isDelete = $this->app_model->have_access_role(ACCOUNT_GROUP_MODULE_ID, "delete");

        foreach ($list as $account_group) {
            $row = array();
            $action = '';
            if($isEdit) {
                $action .= '<form id="edit_' . $account_group->account_group_id . '" method="post" action="' . base_url() . 'account/account_group" class="pull-left">
                    <input type="hidden" name="id" id="id" value="' . $account_group->account_group_id . '">
                    <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $account_group->account_group_id . '\').submit();" title="Edit Account Group"><i class="fa fa-edit"></i></a>
                </form>';    
            }
            
            if($isDelete) {
                if ($account_group->is_deletable == 1) {
                    $action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('account/delete_account_group/' . $account_group->account_group_id) . '"><i class="fa fa-trash"></i></a>';
                }    
            }
            
            $row[] = $action;
            $row[] = $account_group->pa_account_group_name;
            $row[] = $account_group->account_group_name;
            $row[] = $account_group->sequence;
            $row[] = $account_group->in_balance_sheet;
            $row[] = $account_group->use_profit_loss;
            $row[] = $account_group->move_data_opening;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function account_import() {
        if ($this->applib->have_access_role(ACCOUNT_MODULE_ID, "view")) {
            set_page('account/account_import');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function account_import_csv(){
		$count = 0;
		$fp = fopen($_FILES['import_file']['tmp_name'],'r') or die("can't open file");
        while($csv_line = fgetcsv($fp,1024)){
			$count++;
            if($count == 1){
				continue;
            }//keep this if condition if you want to remove the first row
            $insert_csv = array();
            if(!empty($csv_line[0])){
                $account_name = trim($csv_line[0]);
                $insert_csv['account_name'] = (!empty($account_name)) ? $account_name : NULL;
                $insert_csv['account_group_id'] = '49';
                $account_phone = trim($csv_line[2]);
                $insert_csv['account_phone'] = (!empty($account_phone)) ? $account_phone : NULL;
                $account_mobile = trim($csv_line[3]);
                $insert_csv['account_mobile'] = (!empty($account_mobile)) ? $account_mobile : NULL;
                $insert_csv['status'] = '2';
                
                if(!empty($account_phone)){
                    $account_data = $this->crud->get_row_by_id('account', array('account_phone' => $account_phone));
                    if (!empty($account_data)) {
                        continue;
                    }
                }
                if(!empty($account_mobile)){
                    $account_data = $this->crud->get_row_by_id('account', array('account_mobile' => $account_mobile));
                    if (!empty($account_data)) {
                        continue;
                    }
                }
                if(!empty($account_name)){
                    $account_data = $this->crud->get_row_by_id('account', array('account_name' => $account_name));
                    if (!empty($account_data)) {
                        continue;
                    }
                }
                
                $insert_csv['created_at'] = $this->now_time;
                $insert_csv['updated_at'] = $this->now_time;
                $insert_csv['updated_by'] = $this->logged_in_id;
                $insert_csv['created_by'] = $this->logged_in_id;
                $this->crud->insert('account', $insert_csv);
            }
        }
        fclose($fp) or die("can't close file");
        $this->session->set_flashdata('success',true);
		$this->session->set_flashdata('message','Account Import Successfully');
		set_page('account/account_import');
	}
}
