<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Other extends CI_Controller {

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
        $this->zero_value = 0;
//        echo anchor('news/local/123', 'My News', 'title="News title"');

    }

    function add($other_id = '') {
        
        $data = array();
        $other_items = new \stdClass();
        $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
        $data['gold_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_min'));
        $data['gold_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_max'));
        $data['silver_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_max'));
        $data['silver_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_min'));
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        
        if(isset($other_id) && !empty($other_id)){
            //----------------- Sell Data -------------------
            if($this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"edit")) {
                $other_data = $this->crud->get_row_by_id('other', array('other_id' => $other_id));
                $other_data = $other_data[0];
                $other_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$other_data->created_by));
                if($other_data->created_by != $other_data->updated_by){
                    $other_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $other_data->updated_by));
                }else{
                    $other_data->updated_by_name = $other_data ->created_by_name;
                }
                $data['other_data'] = $other_data;

                //----------------- Sell Itemms -------------------
                $other_data_items = $this->crud->get_row_by_id('other_items', array('other_id' => $other_id));
                if(!empty($other_data_items)){
                    foreach($other_data_items as $lot_item){
                        
                        $other_items->sell_item_delete = 'allow';
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id);
                            $total_sell_grwt = $total_transfer_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $other_items->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                                $receive_items = $this->crud->get_receive_items_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id, '0');
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($other_data->department_id, $lot_item->category_id, $lot_item->item_id);
                                $purchase_delete_array = array_merge($stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);

                                uasort($purchase_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
                                $purchase_grwt = 0;
                                $first_check_purchase_grwt = 0;

                                foreach ($purchase_delete_array as $purchase_item){
                                    $purchase_grwt = $purchase_grwt + $purchase_item->grwt;
                                    if($purchase_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0){
                                        if($purchase_item->type == 'O P'){
                                            $used_lineitem_ids[] = $purchase_item->other_item_id;
                                            if($lot_item->other_item_id == $purchase_item->other_item_id){
                                                $other_items->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $purchase_item->grwt;
                                            }
                                        }
                                        $first_check_purchase_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($purchase_item->type == 'O P'){
                                            $used_lineitem_ids[] = $purchase_item->other_item_id;
                                            if($lot_item->other_item_id == $purchase_item->other_item_id){
                                                $other_items->total_grwt_sell = $purchase_item->grwt;
                                            }
                                        }

                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($lot_item->other_item_id, $used_lineitem_ids)){
                                $other_items->sell_item_delete = 'not_allow';
                            }
                        }
                        
                        $other_items->type = $lot_item->type;
                        $type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $lot_item->type));
                        $other_items->type_name = $type_name[0];
                        $other_items->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                        $other_items->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lot_item->category_id));
                        $other_items->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                        $other_items->grwt = $lot_item->grwt;
                        $other_items->rate = $lot_item->rate;
                        $other_items->rate_on = $lot_item->rate_on;
                        $other_items->amount = $lot_item->amount;
                        $other_items->category_id = $lot_item->category_id;
                        $other_items->item_id = $lot_item->item_id;
                        $other_items->other_item_id = $lot_item->other_item_id;
                        $other_lineitems[] = json_encode($other_items);
                    }
                    $data['other_item'] = implode(',', $other_lineitems);
                }

                //----------------- Payment Receipt -------------------
                $payment_receipt = $this->crud->get_row_by_id('payment_receipt', array('other_id' => $other_id));
                if(!empty($payment_receipt)){
                    $pay_lineitems = new \stdClass();
                    foreach ($payment_receipt as $value){
                        $pay_lineitems->payment_receipt = $value->payment_receipt;
                        $pay_lineitems->cash_cheque = $value->cash_cheque;
                        if(isset($value->bank_id) && !empty($value->bank_id)){
                            $pay_lineitems->bank_name = $this->crud->get_column_value_by_id('account', 'bank_name', array('account_id' => $value->bank_id));
                        } else {
                            $pay_lineitems->bank_name = '';
                        }
                        $pay_lineitems->pay_rec_id = $value->pay_rec_id;
                        $pay_lineitems->bank_id = $value->bank_id;
                        $pay_lineitems->amount = $value->amount;
                        $pay_lineitems->narration = $value->narration;
                        $pay_rec_lineitems[] = json_encode($pay_lineitems);
                    }
                    $data['pay_rec_data'] = implode(',', $pay_rec_lineitems);
                }
//                echo '<pre>'; print_r($data); exit;
                set_page('other/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            //----------------- Data -------------------
            if($this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"add")) {
                set_page('other/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function other_list() { 
        if($this->applib->have_access_role(OTHER_ENTRY_MODULE_ID,"view")) {
            $data = array();
            set_page('other/other_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function save_other() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
        }
        $pay_rec_data = array();
        if(isset($post_data['pay_rec_data']) && !empty($post_data['pay_rec_data'])){
            $pay_rec_data = json_decode($post_data['pay_rec_data']);
        }

        if (empty($line_items_data) && empty($pay_rec_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

//        echo '<pre>'; print_r($post_data); exit;
        $post_data['bank_id'] = isset($post_data['bank_id']) && !empty($post_data['bank_id']) ? $post_data['bank_id'] : NULL;
        $post_data['account_id'] = isset($post_data['account_id']) && !empty($post_data['account_id']) ? $post_data['account_id'] : NULL;
        $post_data['department_id'] = isset($post_data['department_id']) && !empty($post_data['department_id']) ? $post_data['department_id'] : NULL;
        if (isset($post_data['other_id']) && !empty($post_data['other_id'])) {
            
            // Increase fine and amount in Department && Decrese fine and amount in Account
            $this->update_account_and_department_balance_on_update($post_data['other_id']);
            // Update Item Stock in Other Item
            $this->update_stock_on_other_item_update($post_data['other_id']);
            
            $update_arr['account_id'] = $post_data['account_id'];
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['other_date'] = date('Y-m-d', strtotime($post_data['other_date']));
            $update_arr['other_remark'] = $post_data['other_remark'];
            $update_arr['total_grwt'] = $post_data['other_grwt'];
            $update_arr['total_amount'] = $post_data['other_amount'];
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('other', $update_arr, array('other_id' => $post_data['other_id']));
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Other Entry Updated Successfully');
                
                // Increase amount in Account
                $update_accounts  = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['account_id']));
                if(!empty($update_accounts)){
                    $acc_amount = number_format((float) $update_accounts[0]->amount, '3', '.', '') + number_format((float) $post_data['other_amount'], '3', '.', '');
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => $post_data['account_id']));
                }
                
                if(isset($post_data['deleted_other_item_id'])){
                    $this->db->where_in('other_item_id', $post_data['deleted_other_item_id']);
                    $this->db->delete('other_items');
                }
                $department_amount = 0;
                if(!empty($line_items_data)){
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        
                        $insert_item['other_id'] = $post_data['other_id'];;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['rate'] = $lineitem->rate;
                        $insert_item['rate_on'] = $lineitem->rate_on;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->other_item_id) && !empty($lineitem->other_item_id)){
                            $this->db->where('other_item_id', $lineitem->other_item_id);
                            $this->db->update('other_items', $insert_item);
                        } else {
                            $lot = $this->crud->get_max_number('other_items', 'other_item_no');
                            $other_item_no = 1;
                            if ($lot->other_item_no > 0) {
                                $other_item_no = $lot->other_item_no + 1;
                            }
                            $insert_item['other_item_no'] = $other_item_no;
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('other_items', $insert_item);
                            $lot_item_id = $this->db->insert_id();
                        }
                    }
                    $this->update_stock_on_other_item_insert($line_items_data,$post_data['department_id']);
                }
                
                if(isset($post_data['deleted_pay_rec_id'])){
                    $this->db->where_in('pay_rec_id', $post_data['deleted_pay_rec_id']);
                    $this->db->delete('payment_receipt');
                }
                if(!empty($pay_rec_data)){
                    foreach ($pay_rec_data as $pay_rec){
                        $insert_pay_rec = array();
                        $insert_pay_rec['other_id'] = $post_data['other_id'];
                        $insert_pay_rec['payment_receipt'] = $pay_rec->payment_receipt;
                        $insert_pay_rec['cash_cheque'] = $pay_rec->cash_cheque;
                        $insert_pay_rec['bank_id'] = $pay_rec->bank_id;
                        $insert_pay_rec['transaction_date'] = date('Y-m-d', strtotime($post_data['other_date']));
                        $insert_pay_rec['department_id'] = $post_data['department_id'];
                        $insert_pay_rec['account_id'] = $post_data['account_id'];
                        $insert_pay_rec['amount'] = $pay_rec->amount;
                        $insert_pay_rec['on_behalf_of'] = $post_data['department_id'];
                        $insert_pay_rec['narration'] = isset($pay_rec->narration) ? $pay_rec->narration : '';
                        $insert_pay_rec['updated_at'] = $this->now_time;
                        $insert_pay_rec['updated_by'] = $this->logged_in_id;
                        if(isset($pay_rec->pay_rec_id) && !empty($pay_rec->pay_rec_id)){
                            $this->db->where('pay_rec_id', $pay_rec->pay_rec_id);
                            $this->db->update('payment_receipt', $insert_pay_rec);
                        } else {
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('payment_receipt', $insert_pay_rec);
                        }
                        
                        if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                            $depart_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $post_data['department_id']));
                            if($pay_rec->payment_receipt == '1'){
                                $depart_amount = $depart_amount - $pay_rec->amount;
                            } else {
                                $depart_amount = $depart_amount + $pay_rec->amount;
                            }
                            $depart_amount = number_format((float) $depart_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $post_data['department_id']));
                        }
                        if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                            $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $pay_rec->bank_id));
                            if($pay_rec->payment_receipt == '1'){
                                $bank_amount = $bank_amount - $pay_rec->amount;
                            } else {
                                $bank_amount = $bank_amount + $pay_rec->amount;
                            }
                            $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $pay_rec->bank_id));
                        }
                    }
                }
            }
        } else {
            $other = $this->crud->get_max_number('other', 'other_no');
            $other_no = 1;
            if ($other->other_no > 0) {
                $other_no = $other->other_no + 1;
            }
            $insert_arr = array();
            $insert_arr['other_no'] = $other_no;            
            $insert_arr['account_id'] = $post_data['account_id'];            
            $insert_arr['department_id'] = $post_data['department_id'];            
            $insert_arr['other_date'] = date('Y-m-d', strtotime($post_data['other_date']));
            $insert_arr['other_remark'] = $post_data['other_remark'];           
            $insert_arr['total_grwt'] = $post_data['other_grwt'];
            $insert_arr['total_amount'] = $post_data['other_amount'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('other', $insert_arr);
            $other_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Other Entry Added Successfully');

                // Increase amount in Account
                $accounts = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['account_id']));
                if(!empty($accounts)){
                    $acc_amount = number_format((float) $accounts[0]->amount, '3', '.', '') + number_format((float) $post_data['other_amount'], '3', '.', '');
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => $post_data['account_id']));
                }
                
//                echo '<pre>'; print_r($line_items_data); exit;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $lot = $this->crud->get_max_number('other_items', 'other_item_no');
                        $other_item_no = 1;
                        if ($lot->other_item_no > 0) {
                            $other_item_no = $lot->other_item_no + 1;
                        }
                        $insert_item = array();
                        $insert_item['other_id'] = $other_id;
                        $insert_item['other_item_no'] = $other_item_no;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['rate'] = $lineitem->rate;
                        $insert_item['rate_on'] = $lineitem->rate_on;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('other_items', $insert_item);
                        $lot_item_id = $this->db->insert_id();
                    }
                    $this->update_stock_on_other_item_insert($line_items_data,$post_data['department_id']);
                }
                
                if(!empty($pay_rec_data)){
                    foreach ($pay_rec_data as $pay_rec){
                        $insert_pay_rec = array();
                        $insert_pay_rec['other_id'] = $other_id;
                        $insert_pay_rec['payment_receipt'] = $pay_rec->payment_receipt;
                        $insert_pay_rec['cash_cheque'] = $pay_rec->cash_cheque;
                        $insert_pay_rec['bank_id'] = $pay_rec->bank_id;
                        $insert_pay_rec['transaction_date'] = date('Y-m-d', strtotime($post_data['other_date']));
                        $insert_pay_rec['department_id'] = $post_data['department_id'];
                        $insert_pay_rec['account_id'] = $post_data['account_id'];
                        $insert_pay_rec['on_behalf_of'] = $post_data['department_id'];
                        $insert_pay_rec['amount'] = $pay_rec->amount;
                        $insert_pay_rec['narration'] = isset($pay_rec->narration) ? $pay_rec->narration : '';
                        $insert_pay_rec['created_at'] = $this->now_time;
                        $insert_pay_rec['created_by'] = $this->logged_in_id;
                        $insert_pay_rec['updated_at'] = $this->now_time;
                        $insert_pay_rec['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('payment_receipt', $insert_pay_rec);
                        
                        if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                            $depart_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $post_data['department_id']));
                            if($pay_rec->payment_receipt == '1'){
                                $depart_amount = $depart_amount - $pay_rec->amount;
                            } else {
                                $depart_amount = $depart_amount + $pay_rec->amount;
                            }
                            $depart_amount = number_format((float) $depart_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $post_data['department_id']));
                        }
                        if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                            $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $pay_rec->bank_id));
                            if($pay_rec->payment_receipt == '1'){
                                $bank_amount = $bank_amount - $pay_rec->amount;
                            } else {
                                $bank_amount = $bank_amount + $pay_rec->amount;
                            }
                            $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $pay_rec->bank_id));
                        }
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function update_stock_on_other_item_insert($lineitem_data='',$department_id=''){
        if (!empty($lineitem_data)) {
//            echo '<pre>'; print_r($lineitem_data); exit;
            $department_amount = 0;
            foreach ($lineitem_data as $lineitem) {
                $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => '0');
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                if(!empty($exist_item_id)){
                    if($lineitem->type == SELL_TYPE_SELL_ID){
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                    } else {
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                    }
                    $update_item_stock = array();
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                } else { 
                    if($lineitem->type == SELL_TYPE_SELL_ID){
                        $lineitem->grwt = $this->zero_value - number_format((float) $lineitem->grwt, '3', '.', '');
                    }
                    $insert_item_stock = array();
                    $insert_item_stock['department_id'] = $department_id;
                    $insert_item_stock['category_id'] = $lineitem->category_id;
                    $insert_item_stock['item_id'] = $lineitem->item_id;
                    $insert_item_stock['tunch'] = '0';
                    $insert_item_stock['grwt'] = $lineitem->grwt;
                    $insert_item_stock['less'] = '0';
                    $insert_item_stock['ntwt'] = $lineitem->grwt;
                    $insert_item_stock['fine'] = '0';
                    $insert_item_stock['created_at'] = $this->now_time;
                    $insert_item_stock['created_by'] = $this->logged_in_id;
                    $insert_item_stock['updated_at'] = $this->now_time;
                    $insert_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('item_stock', $insert_item_stock);
                }

                // Lineitem to Department Amount
                if($lineitem->type == SELL_TYPE_SELL_ID){
                    $department_amount = $department_amount - $lineitem->amount;
                } else {
                    $department_amount = $department_amount + $lineitem->amount;
                }
            }
            if($department_amount != 0){
                $department_data = $this->crud->get_row_by_id('account', array('account_id' => $department_id));
                if (!empty($department_data)) {
                    $department_new_amount = number_format((float) $department_data[0]->amount, '3', '.', '') + number_format((float) $department_amount, '3', '.', '');
                    $department_new_amount = number_format((float) $department_new_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $department_new_amount), array('account_id' => $department_id));
                }
            }
        }
    }
    
    function update_stock_on_other_item_update($other_id =''){
        $other_items = $this->crud->get_all_with_where('other_items', '', '', array('other_id' => $other_id));
        if(!empty($other_items)){
            $department_id = $this->crud->get_column_value_by_id('other', 'department_id', array('other_id' => $other_id));
            $department_amount = 0;
            foreach ($other_items as $lineitem){
                
                $where_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => '0');
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_array);
                if(!empty($exist_item_id)){
                    if($lineitem->type == SELL_TYPE_SELL_ID){
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                    } else {
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                    }
                    $update_item_stock = array();
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                }

                // Lineitem to Department Amount
                if($lineitem->type == SELL_TYPE_SELL_ID){
                    $department_amount = $department_amount + $lineitem->amount;
                } else {
                    $department_amount = $department_amount - $lineitem->amount;
                }
            }
            if($department_amount != 0){
                $department_data = $this->crud->get_row_by_id('account', array('account_id' => $department_id));
                if (!empty($department_data)) {
                    $department_new_amount = number_format((float) $department_data[0]->amount, '3', '.', '') + number_format((float) $department_amount, '3', '.', '');
                    $department_new_amount = number_format((float) $department_new_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $department_new_amount), array('account_id' => $department_id));
                }
            }
        }
    }
    
    function update_account_and_department_balance_on_update($other_id = '') {
        $other_data = $this->crud->get_row_by_id('other', array('other_id' => $other_id));
        if (!empty($other_data)) {
            $accounts = $this->crud->get_row_by_id('account', array('account_id' => $other_data[0]->account_id));
            if (!empty($accounts)) {
                $acc_amount = number_format((float) $accounts[0]->amount, '3', '.', '') - number_format((float) $other_data[0]->total_amount, '3', '.', '');
                $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => $other_data[0]->account_id));
            }

            $total_amount = 0;
            $payment_receipt_data = $this->crud->get_row_by_id('payment_receipt', array('other_id'=> $other_id));
            if(!empty($payment_receipt_data)){
                foreach ($payment_receipt_data as $payment_rec_data){
                    if($payment_rec_data->cash_cheque == '2'){ // if payment_receipt type Cheque
                        $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $payment_rec_data->bank_id));
                        if($payment_rec_data->payment_receipt == '1'){
                            $bank_amount = $bank_amount + $payment_rec_data->amount;
                        } else {
                            $bank_amount = $bank_amount - $payment_rec_data->amount;
                        }
                        $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $payment_rec_data->bank_id));
                    } else {
                        if($payment_rec_data->payment_receipt == '1'){
                            $total_amount = $total_amount + $payment_rec_data->amount;
                        } else {
                            $total_amount = $total_amount - $payment_rec_data->amount;
                        }
                    }
                }
            }
            $departments = $this->crud->get_row_by_id('account', array('account_id' => $other_data[0]->department_id));
            if (!empty($departments)) {
                $depart_amount = number_format((float) $departments[0]->amount, '3', '.', '') + number_format((float) $total_amount, '3', '.', '');
                $depart_amount = number_format((float) $depart_amount, '2', '.', '');
                $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $other_data[0]->department_id));
            }
        }
    }
        
    function other_datatable() {
        $post_data = $this->input->post();
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'other o';
        $config['select'] = 'o.*,p.account_name,a.account_name AS department_name';
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = o.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.department_id', 'join_type' => 'left');
        $config['column_search'] = array('o.other_no','p.account_name','a.account_name','DATE_FORMAT(o.other_date,"%d-%m-%Y")', 'o.other_remark');
        $config['column_order'] = array(null, 'p.account_name', 'a.account_name', 'o.sell_no', 'o.other_date', 'o.other_remark');
        
        $config['custom_where'] = '1=1';
        $account_groups = $this->applib->current_user_account_group_ids();
        if(!empty($account_groups)) {
            $config['custom_where'] .= ' AND p.account_group_id IN ('.implode(',',$account_groups).')';
        } else {
            $config['custom_where'] .= ' AND p.account_group_id IN(-1)';
        }

        $account_ids = $this->applib->current_user_account_ids();
        if($account_ids == "allow_all_accounts"){
            
        } elseif(!empty($account_ids)){
            $config['custom_where'] .= ' AND p.account_id IN('.implode(',',$account_ids).')';
        } else {
            $config['custom_where'] .= ' AND p.account_id IN(-1)';
        }
        
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= ' AND o.department_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND o.department_id IN(-1)';
        }

        if(!empty($post_data['from_date'])){
            $config['wheres'][] = array('column_name' => 'o.other_date >=', 'column_value' => $from_date);
        }
        if(!empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 'o.other_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('o.other_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "edit");
        foreach ($list as $other) {
            $row = array();
            $action = '';
            if($other->account_id != ADJUST_EXPENSE_ACCOUNT_ID){
                if($role_edit){
                    $action .= '<a href="' . base_url("other/add/" . $other->other_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($role_delete){
                    $action .= '<a href="javascript:void(0);" class="delete_other" data-href="' . base_url('other/delete_other/' . $other->other_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            }
            $action .= '<a href="' . base_url("other/other_print/" . $other->other_id) . '" target="_blank" title="Other Sell/Purchase Print" alt="Other Sell/Purchase Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            $pay_rec = $this->crud->get_id_by_val('payment_receipt', 'payment_receipt', 'other_id', $other->other_id);
            if(!empty($pay_rec)){
                $action .= '<a href="javascript:void(0);" class="pay_rec_id" data-pay_rec_id="' . $other->other_id . '" ><span class="btn btn-sm btn-instagram">P</span></a>&nbsp;';
            }
            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-other_id="' . $other->other_id . '" >' . $other->account_name . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-other_id="' . $other->other_id . '" >' . $other->department_name . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-other_id="' . $other->other_id . '" >' . $other->other_no . '</a>';
            $other_date = (!empty(strtotime($other->other_date))) ? date('d-m-Y', strtotime($other->other_date)) : '';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-other_id="' . $other->other_id . '" >' . $other_date  . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-other_id="' . $other->other_id . '" >' . $other->other_remark . '</a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function other_item_datatable() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $config['table'] = 'other_items oi';
        $config['select'] = 'oi.*,st.type_name,im.item_name,c.category_name';
        $config['joins'][] = array('join_table' => 'other o', 'join_by' => 'o.other_id = oi.other_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_type st', 'join_by' => 'st.sell_type_id = oi.type', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = oi.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= oi.category_id', 'join_type' => 'left');
        $config['column_order'] = array('oi.other_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'oi.grwt','oi.rate', 'oi.rate_on', 'oi.amount');
        $config['column_search'] = array('oi.other_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'oi.grwt','oi.rate', 'oi.rate_on', 'oi.amount');
        $config['order'] = array('oi.other_item_no' => 'desc');
        if (isset($post_data['other_id']) && !empty($post_data['other_id'])) {
            $config['wheres'][] = array('column_name' => 'oi.other_id', 'column_value' => $post_data['other_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query(); exit;
        $data = array();
        $role_delete = $this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "edit");
        foreach ($list as $other_detail) {
            $row = array();
            $action = '';
            $row[] = $other_detail->other_item_no;
            $row[] = $other_detail->type_name;
            $row[] = $other_detail->category_name;
            $row[] = $other_detail->item_name;
            $row[] = number_format($other_detail->grwt, 3, '.', '');
            $row[] = $other_detail->rate;
            $row[] = $other_detail->rate_on;
            $row[] = $other_detail->amount;
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
    
    function pay_rec_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'payment_receipt pr';
        $config['select'] = 'pr.*, IF(pr.payment_receipt = 1, "Payment" ,"Receipt") AS payment_receipt, IF(pr.cash_cheque = 1, "Cash" ,"Cheque") AS cash_cheque, a.bank_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pr.bank_id', 'join_type' => 'left');
        $config['column_order'] = array('pr.payment_receipt', 'pr.cash_cheque', 'a.bank_name', 'pr.amount', 'pr.narration');
        $config['column_search'] = array('IF(pr.payment_receipt = 1, "Payment" ,"Receipt")', 'IF(pr.cash_cheque = 1, "Cash" ,"Cheque")', 'a.bank_name', 'pr.amount', 'pr.narration');
        $config['order'] = array('pr.pay_rec_id' => 'desc');
        if (isset($post_data['pay_rec_id']) && !empty($post_data['pay_rec_id'])) {
            $config['wheres'][] = array('column_name' => 'pr.other_id', 'column_value' => $post_data['pay_rec_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->payment_receipt;
            $row[] = $sell_detail->cash_cheque;
            $row[] = $sell_detail->bank_name;
            $row[] = $sell_detail->amount;
            $row[] = $sell_detail->narration;
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
    
    function delete_other($id = '') {
        $where_array = array('other_id' => $id);
        $other = $this->crud->get_row_by_id('other', $where_array);
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $return = array();
        if(!empty($other)){
            $found = false;
            $other_items = $this->crud->get_row_by_id('other_items', $where_array);
            if(!empty($other_items)){
                foreach($other_items as $other_item){
                    if($without_purchase_sell_allow == '1'){
                        $used_lineitem_ids = $this->check_default_item_sell_or_not($other[0]->department_id, $other_item->category_id, $other_item->item_id, '0');
                        if(!empty($used_lineitem_ids) && in_array($other_item->other_item_id, $used_lineitem_ids)){
                            $found = true;
                        }
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {
                // Increase fine and amount in Department && Decrese fine and amount in Account
                $this->update_account_and_department_balance_on_update($id);
                // Update Item Stock in Other Item
                $this->update_stock_on_other_item_update($id);

                $this->crud->delete('payment_receipt', $where_array);
                $this->crud->delete('other_items', $where_array);
                $this->crud->delete('other', $where_array);
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function check_default_item_sell_or_not($department_id, $category_id, $item_id, $touch_id){
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_transfer_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $other_purchase_items = $this->crud->get_other_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
            
            uasort($purchase_delete_array, function($a, $b) {
                $value1 = strtotime($a->created_at);
                $value2 = strtotime($b->created_at);
                return $value1 - $value2;
            });
            
            $purchase_grwt = 0;
            $first_check_purchase_grwt = 0;
        
            foreach ($purchase_delete_array as $purchase_item){
                $purchase_grwt = $purchase_grwt + $purchase_item->grwt;
                if($purchase_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0){
                    if($purchase_item->type == 'O P'){
                        $used_lineitem_ids[] = $purchase_item->other_item_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'O P'){
                        $used_lineitem_ids[] = $purchase_item->other_item_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }

    function other_print($other_id = '') {
        $data = array();
        if(!empty($other_id)){
            $other_data = $this->crud->get_row_by_id('other', array('other_id' => $other_id));
            $other_data = $other_data[0];
            $other_data->account_name = '';
            $other_data->old_amount = 0;
            $account_data = $this->crud->get_row_by_id('account',array('account_id' => $other_data->account_id));
            if(!empty($account_data)){
                $other_data->account_name = $account_data[0]->account_name;
                $other_data->old_amount = number_format($account_data[0]->amount, 0, '.', '');
            }
            $other_data->total_amount = (!empty($other_data->total_amount)) ? $other_data->total_amount : 0;
            $other_data->old_amount = $other_data->old_amount - $other_data->total_amount;
            $data['other_data'] = $other_data;

            //----------------- Other Items -------------------
            $other_lineitems = array();
            $other_data_items = $this->crud->get_row_by_id('other_items', array('other_id' => $other_id));
            if(!empty($other_data_items)){
                foreach($other_data_items as $other_data_item){
                    $other_items = new \stdClass();
                    $other_items->sell_item_delete = 'allow';
                    $other_items->tunch_textbox = (isset($other_data_item->tunch_textbox) && $other_data_item->tunch_textbox == '1') ? '1' : '0';
                    $other_items->type = $other_data_item->type;
                    $other_items->type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $other_data_item->type));
                    $other_items->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $other_data_item->category_id));
                    $other_items->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $other_data_item->category_id));
                    $other_items->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $other_data_item->item_id));
                    if($other_items->type != SELL_TYPE_SELL_ID){
                        $other_items->grwt = ZERO_VALUE - $other_data_item->grwt;
                        $other_items->rate = ZERO_VALUE - $other_data_item->rate;
                        $other_items->amount = ZERO_VALUE - $other_data_item->amount;
                    } else {
                        $other_items->grwt = $other_data_item->grwt;
                        $other_items->rate = $other_data_item->rate;
                        $other_items->amount = $other_data_item->amount;
                    }
                    $other_items->grwt = number_format($other_items->grwt, 3, '.', '');
                    $other_items->category_id = $other_data_item->category_id;
                    $other_items->item_id = $other_data_item->item_id;
                    $other_items->other_item_id = $other_data_item->other_item_id;
                    $other_lineitems[] = $other_items;
                }
                $data['other_items'] = $other_lineitems;
            }
//            print_r($data['other_items']); exit;

            //----------------- Payment Receipt -------------------
            $payment_receipt = $this->crud->get_row_by_id('payment_receipt', array('other_id' => $other_id));
            if(!empty($payment_receipt)){
                foreach ($payment_receipt as $value){
                    $pay_lineitems = new \stdClass();
                    $pay_lineitems->payment_receipt = $value->payment_receipt;
                    $pay_lineitems->payment_receipt_name = ($value->payment_receipt == '1') ? 'Payment' : 'Receipt';
                    $pay_lineitems->cash_cheque = ($value->cash_cheque == '1') ? 'Cash' : 'Cheque';
                    if(isset($value->bank_id) && !empty($value->bank_id)){
                        $pay_lineitems->bank_name = ' - ' . $this->crud->get_column_value_by_id('account', 'bank_name', array('account_id' => $value->bank_id));
                    } else {
                        $pay_lineitems->bank_name = '';
                    }
                    $pay_lineitems->pay_rec_id = $value->pay_rec_id;
                    $pay_lineitems->bank_id = $value->bank_id;
                    if($pay_lineitems->payment_receipt == '2'){
                        $pay_lineitems->amount = ZERO_VALUE - $value->amount;
                    } else {
                        $pay_lineitems->amount = $value->amount;
                    }
                    $pay_lineitems->amount = number_format($pay_lineitems->amount, 2, '.', '');
                    $pay_lineitems->narration = $value->narration;
                    $pay_rec_lineitems[] = $pay_lineitems;
                }
                $data['pay_rec_data'] = $pay_rec_lineitems;
            }

        }
//        print_r($data); exit;
        $data['company_name'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_name'));
        $data['company_contact'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_contact'));
        $data['company_address'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_address'));
        $data['ask_discount_in_sell_purchase'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'ask_discount_in_sell_purchase'));
        $html = $this->load->view('other/other_print', $data, true);
        if(PACKAGE_FOR != 'mohanji') {
            $mpdf = new \Mpdf\Mpdf();
        } else {
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A5']);
        }
        $mpdf->AddPage(
            'P', //orientation
            '', //type
            '', //resetpagenum
            '', //pagenumstyle
            '', //suppress
            5, //margin-left
            5, //margin-right
            5, //margin-top
            5, //margin-bottom
            0, //margin-header
            0 //margin-footer
        );
        $mpdf->defHTMLHeaderByName('myHeader2','<div style="text-align: center; font-weight: bold;">Invoice</div>');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

}
