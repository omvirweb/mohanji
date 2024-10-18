<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sell_purchase extends CI_Controller {

    public $logged_in_id = null;
    public $sell_purchase_difference = 0;
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
        $this->sell_purchase_difference = $this->session->userdata(PACKAGE_FOLDER_NAME.'sell_purchase_difference');
        $this->company_state = $this->session->userdata(PACKAGE_FOLDER_NAME.'company_data')->account_state;
        $this->now_time = date('Y-m-d H:i:s');
        $this->zero_value = 0;
    }

    function add($param1 = '', $param2 = '', $param3 = '') {
        $data = array();
        $data['company_state'] = $this->company_state;
        if($param1 == "sell") {
            $page_shortcut = "[CTRL + F1]";
            $data['sell_purchase_type'] = SELL_TYPE_SELL_ID;
        } else if($param1 == "purchase") {
            $page_shortcut = "[CTRL + F2]";
            $data['sell_purchase_type'] = SELL_TYPE_PURCHASE_ID;
        } else {
            redirect('/');
        }
        $data['sell_purchase'] = $param1;
        $data['page_variable'] = $param1;
        $data['page_label'] = ucfirst($param1);
        $data['page_shortcut'] = $page_shortcut = '';
        $list_page_url = base_url("sell_purchase/splist/");
        $data['list_page_url'] = $list_page_url;
        
        $sell_id = $param2;
        $order_id = $param3;
        

        $sell_items = new \stdClass();
        $oreder_lot_item = new \stdClass();
        $category = $this->crud->get_all_records('category', 'category_id', ''); 
        $data['category'] = $category;
        $items = $this->crud->get_all_records('item_master', 'item_id', '');        
        $data['items'] = $items;
        $type = $this->crud->get_all_records('sell_type', 'sell_type_id', '');        
        $data['type'] = $type;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');        
        $data['touch'] = $touch;
        
        $setting_data = $this->crud->get_all_records('settings', 'fields_section', 'asc');
        foreach($setting_data as $setting_row){
            if($setting_row->settings_key == 'without_purchase_sell_allow' || $setting_row->settings_key == 'use_category' || $setting_row->fields_section == '1' || $setting_row->fields_section == '2'){
                $data[$setting_row->settings_key] = $setting_row->settings_value;
            }
        }

        $process_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id'];
        $data['process_name'] = $this->crud->get_column_value_by_id('account','account_name',array('account_id' => $process_id));

        if(!empty($sell_id)){
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) {
                $sell_data = $this->crud->get_data_row_by_id('sell', 'sell_id', $sell_id);
                $sell_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$sell_data->created_by));
                if($sell_data->created_by != $sell_data->updated_by){
                    $sell_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $sell_data->updated_by));
                }else{
                    $sell_data->updated_by_name = $sell_data->created_by_name;
                }
                $data['sell_data'] = $sell_data;
                $data['sell_lineitems'] = $this->get_sell_purchase_lineitems($sell_id, 'edit');
                $data['pay_rec_data'] = $this->get_payment_receipt_lineitems($sell_id, 'edit');
                $data['metal_data'] = $this->get_metal_payment_receipt_lineitems($sell_id, 'edit');
                $data['gold_data'] = $this->get_gold_bhav_lineitems($sell_id, 'edit');
                $data['silver_data'] = $this->get_silver_bhav_lineitems($sell_id, 'edit');
//                echo '<pre>'; print_r($data); exit;
                set_page('sell_purchase/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
    //        echo '<pre>'; print_r($data); exit;
            set_page('sell_purchase/add', $data);
        }
    }
    
    function save_sell_purchase(){
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
        $metal_data = array();
        if(isset($post_data['metal_data']) && !empty($post_data['metal_data'])){
            $metal_data = json_decode($post_data['metal_data']);
        }
        $gold_data = array();
        if(isset($post_data['gold_data']) && !empty($post_data['gold_data'])){
            $gold_data = json_decode($post_data['gold_data']);
        }
        $silver_data = array();
        if(isset($post_data['silver_data']) && !empty($post_data['silver_data'])){
            $silver_data = json_decode($post_data['silver_data']);
        }
        
        if (empty($line_items_data) && empty($pay_rec_data) && empty($metal_data) && empty($gold_data) && empty($silver_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}
        
//        echo '<pre>'; print_r($post_data); exit;
        $post_data['sell_no'] = isset($post_data['sell_no']) && !empty($post_data['sell_no']) ? $post_data['sell_no'] : NULL;
        $post_data['bill_financial_year'] = isset($post_data['bill_financial_year']) && !empty($post_data['bill_financial_year']) ? $post_data['bill_financial_year'] : NULL;
        $post_data['account_id'] = isset($post_data['account_id']) && !empty($post_data['account_id']) ? $post_data['account_id'] : NULL;
        $post_data['process_id'] = isset($post_data['process_id']) && !empty($post_data['process_id']) ? $post_data['process_id'] : NULL;
        $post_data['sell_remark'] = isset($post_data['sell_remark']) && !empty($post_data['sell_remark']) ? $post_data['sell_remark'] : NULL;
        $order_id = (isset($post_data['order_id']) && !empty($post_data['order_id'])) ? $post_data['order_id'] : NULL;
        
        if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {
            $sell_id = $post_data['sell_id'];
            $check_bill_sell_entry = $this->crud->get_column_value_by_id('sell', 'sell_id', array('sell_no' => $post_data['sell_no'], 'bill_financial_year' => $post_data['bill_financial_year'], 'sell_id !=' => $sell_id));
            if(!empty($check_bill_sell_entry)){
                $return['error'] = "Exist";
                $return['error_exist'] = "Bill No. Already Exist";
                print json_encode($return);
                exit;
            }

            $old_sell_data = $this->crud->get_data_row_by_id('sell', 'sell_id', $sell_id);
            if(!empty($old_sell_data)){
                // Revert : Discount Amount Effects
                if(isset($old_sell_data->discount_amount)){
                    // Discount Amount Increase to Selected Account
                    $this->applib->update_account_balance_increase($old_sell_data->account_id, '', '', $old_sell_data->discount_amount);
                    // Discount Amount Decrease from Department
                    $this->applib->update_account_balance_decrease($old_sell_data->process_id, '', '', $old_sell_data->discount_amount);
                }

                $old_sell_item_id_arr = array();
                $old_sell_purchase_items = $this->get_sell_purchase_lineitems($sell_id, 'update');
                if (!empty($old_sell_purchase_items)) {
                    foreach ($old_sell_purchase_items as $old_sell_purchase_item) {
                        // Revert : Update Selected Account balance to Decrease
                        $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', '', $old_sell_purchase_item->amount);
                        // Revert : Update Department balance to Increase
                        $this->applib->update_account_balance_increase($old_sell_data->process_id, '', '', $old_sell_purchase_item->amount);

                        $old_sell_item_id_arr[] = $old_sell_purchase_item->sell_item_id;
                    }
                }

                $old_pay_rec_id_arr = array();
                $old_pay_rec_data = $this->get_payment_receipt_lineitems($sell_id, 'update');
                if (!empty($old_pay_rec_data)) {
                    foreach ($old_pay_rec_data as $old_pay_rec_row) {
                        // Revert : Update Selected Account balance
                        if($old_pay_rec_row->payment_receipt == '1'){
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', '', $old_pay_rec_row->amount);

                            if($old_pay_rec_row->cash_cheque == '1'){ // Update Department Amount
                                $this->applib->update_account_balance_increase($old_sell_data->process_id, '', '', $old_pay_rec_row->amount);
                            } else if($old_pay_rec_row->cash_cheque == '2'){ // Update Bank Amount
                                $this->applib->update_account_balance_increase($old_pay_rec_row->bank_id, '', '', $old_pay_rec_row->amount);
                            }
                        } else {
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, '', '', $old_pay_rec_row->amount);

                            if($old_pay_rec_row->cash_cheque == '1'){ // Update Department Amount
                                $this->applib->update_account_balance_decrease($old_sell_data->process_id, '', '', $old_pay_rec_row->amount);
                            } else if($old_pay_rec_row->cash_cheque == '2'){ // Update Bank Amount
                                $this->applib->update_account_balance_decrease($old_pay_rec_row->bank_id, '', '', $old_pay_rec_row->amount);
                            }
                        }
                        $old_pay_rec_id_arr[] = $old_pay_rec_row->pay_rec_id;
                    }
                }

                $old_metal_pr_id_arr = array();
                $old_metal_payment_receipt_data = $this->get_metal_payment_receipt_lineitems($sell_id, 'update');
                if (!empty($old_metal_payment_receipt_data)) {
                    foreach ($old_metal_payment_receipt_data as $old_metal_payment_receipt_row) {
                        $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $old_metal_payment_receipt_row->metal_item_id);
                        $category_group_id = $this->crud->get_id_by_val('category', 'category_group_id', 'category_id', $category_id);

                        // Revert : Update Selected Account balance
                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $old_metal_payment_receipt_row->metal_fine;
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $old_metal_payment_receipt_row->metal_fine;
                        }
                        if($old_metal_payment_receipt_row->metal_payment_receipt == '1'){
                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, $gold_fine, $silver_fine, '');
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->process_id, $gold_fine, $silver_fine, '');
                        } else {
                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, $gold_fine, $silver_fine, '');
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->process_id, $gold_fine, $silver_fine, '');
                        }
                    }
                    $old_metal_pr_id_arr[] = $old_metal_payment_receipt_row->metal_pr_id;
                }

                $old_gold_id_arr = array();
                $old_gold_bhav_data = $this->get_gold_bhav_lineitems($sell_id, 'update');
                if (!empty($old_gold_bhav_data)) {
                    foreach ($old_gold_bhav_data as $old_gold_bhav_row) {
                        if($old_gold_bhav_row->gold_sale_purchase == '1'){
                            // Revert : Update Selected Account Wt to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, $old_gold_bhav_row->gold_weight, '', '');
                            // Revert : Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', '', $old_gold_bhav_row->gold_value);
                        } else {
                            // Revert : Update Selected Account Wt to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, $old_gold_bhav_row->gold_weight, '', '');
                            // Revert : Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, '', '', $old_gold_bhav_row->gold_value);
                        }
                        $old_gold_id_arr[] = $old_gold_bhav_row->gold_id;
                    }
                }

                $old_silver_id_arr = array();
                $old_silver_bhav_data = $this->get_silver_bhav_lineitems($sell_id, 'update');
                if (!empty($old_silver_bhav_data)) {
                    foreach ($old_silver_bhav_data as $old_silver_bhav_row) {
                        if($old_silver_bhav_row->silver_sale_purchase == '1'){
                            // Revert : Update Selected Account Wt to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, '', $old_silver_bhav_row->silver_weight, '');
                            // Revert : Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', '', $old_silver_bhav_row->silver_value);
                        } else {
                            // Revert : Update Selected Account Wt to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', $old_silver_bhav_row->silver_weight, '');
                            // Revert : Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, '', '', $old_silver_bhav_row->silver_value);
                        }
                        $old_silver_id_arr[] = $old_silver_bhav_row->silver_id;
                    }
                }

                $update_arr = array();
                $update_arr['sell_no'] = $post_data['sell_no'];
    //            $update_arr['bill_financial_year'] = $post_data['bill_financial_year'];
                $update_arr['account_id'] = $post_data['account_id'];
    //            $update_arr['process_id'] = $post_data['process_id'];
                $update_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
                $update_arr['sell_remark'] = $post_data['sell_remark'];
                $update_arr['order_id'] = $order_id;
                $update_arr['total_gold_fine'] = $post_data['total_gold_fine'];
                $update_arr['total_silver_fine'] = $post_data['total_silver_fine'];
                $update_arr['total_amount'] = $post_data['total_amount'];
                if(isset($post_data['discount_amount'])){
                    $update_arr['discount_amount'] = $post_data['discount_amount'];
                }
                $update_arr['updated_at'] = $this->now_time;
                $update_arr['updated_by'] = $this->logged_in_id;
                $result = $this->crud->update('sell', $update_arr, array('sell_id' => $sell_id));
                if ($result) {
                    $return['success'] = "Updated";
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'Sell/purchase Updated Successfully');

                    // Discount Amount Effects
                    if(isset($post_data['discount_amount'])){
                        // Discount Amount Decrease from Selected Account
                        $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $post_data['discount_amount']);
                        // Discount Amount Increase to Department
                        $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $post_data['discount_amount']);
                    }

                    if (!empty($line_items_data)) {
                        foreach ($line_items_data as $key => $lineitem) {
                            $insert_item = array();
                            $insert_item['sell_id'] = $sell_id;
                            $insert_item['type'] = SELL_TYPE_SELL_ID;
                            $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                            if($line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                                if(isset($lineitem->stock_type)){
                                    $insert_item['stock_type'] = $lineitem->stock_type;
                                }
                            }
                            $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $lineitem->item_id);
                            $insert_item['category_id'] = $category_id;
                            $insert_item['item_id'] = $lineitem->item_id;
                            $insert_item['grwt'] = $lineitem->grwt;
                            $insert_item['less'] = $lineitem->less;
                            $insert_item['net_wt'] = $lineitem->net_wt;
                            $insert_item['touch_id'] = '100';

                            $insert_item['rate_per_1_gram'] = $lineitem->rate_per_1_gram;
                            $insert_item['gross_amount'] = $lineitem->gross_amount;
                            $insert_item['labout_other_charges'] = $lineitem->labout_other_charges;
                            $insert_item['amount'] = $lineitem->amount;
                            $insert_item['li_narration'] = $lineitem->li_narration;

                            $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                            $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                            if(isset($lineitem->order_lot_item_id) && !empty($lineitem->order_lot_item_id)){
                                $insert_item['order_lot_item_id'] = $lineitem->order_lot_item_id;
                                // On Sell item add order lot item status to Completed
                                $this->crud->update('order_lot_item', array('item_status_id' => '3'), array('order_lot_item_id' => $lineitem->order_lot_item_id));
                            }
                            if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                                $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                            }

                            $insert_item['updated_at'] = $this->now_time;
                            $insert_item['updated_by'] = $this->logged_in_id;
                            if(isset($lineitem->sell_item_id) && !empty($lineitem->sell_item_id)){
                                $this->crud->update('sell_items', $insert_item, array('sell_item_id' => $lineitem->sell_item_id));
                                $old_sell_item_id_arr = array_diff($old_sell_item_id_arr, array($lineitem->sell_item_id));
                                $sell_item_id = $lineitem->sell_item_id;
                            } else {
                                $lot = $this->crud->get_max_number('sell_items', 'sell_item_no');
                                $sell_item_no = 1;
                                if ($lot->sell_item_no > 0) {
                                    $sell_item_no = $lot->sell_item_no + 1;
                                }
                                $insert_item['sell_item_no'] = $sell_item_no;
                                $insert_item['created_at'] = $this->now_time;
                                $insert_item['created_by'] = $this->logged_in_id;
                                $this->crud->insert('sell_items', $insert_item);
                                $sell_item_id = $this->db->insert_id();
                            }

                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $lineitem->amount);
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['process_id'], '', '', $lineitem->amount);

                            // Delete lineitems charges details
                            $this->crud->delete('sell_item_charges_details', array('sell_id' => $sell_id, 'sell_item_id' => $sell_item_id));
                            // Insert lineitems charges details
                            if(isset($lineitem->sell_item_charges_details) && !empty($lineitem->sell_item_charges_details)){
                                $sell_item_charges_details = json_decode($lineitem->sell_item_charges_details);
                                foreach ($sell_item_charges_details as $sell_item_charges_detail){
                                    $insert_sell_item_charges_detail = array();
                                    $insert_sell_item_charges_detail['sell_id'] = $sell_id;
                                    $insert_sell_item_charges_detail['sell_item_id'] = $sell_item_id;
                                    $insert_sell_item_charges_detail['sell_item_charges_details_ad_id'] = $sell_item_charges_detail->sell_item_charges_details_ad_id;
                                    $insert_sell_item_charges_detail['sell_item_charges_details_net_wt'] = $sell_item_charges_detail->sell_item_charges_details_net_wt;
                                    $insert_sell_item_charges_detail['sell_item_charges_details_per_gram'] = $sell_item_charges_detail->sell_item_charges_details_per_gram;
                                    $insert_sell_item_charges_detail['sell_item_charges_details_ad_amount'] = $sell_item_charges_detail->sell_item_charges_details_ad_amount;
                                    $insert_sell_item_charges_detail['created_at'] = $this->now_time;
                                    $insert_sell_item_charges_detail['created_by'] = $this->logged_in_id;
                                    $insert_sell_item_charges_detail['updated_at'] = $this->now_time;
                                    $insert_sell_item_charges_detail['updated_by'] = $this->logged_in_id;
                                    $result = $this->crud->insert('sell_item_charges_details', $insert_sell_item_charges_detail);
                                }
                            }
                        }
                    }
                    // Delete Deleted lineitems
                    if (!empty($old_sell_item_id_arr)) {
//                        $old_sell_item_ids = implode(',', $old_sell_item_id_arr);
                        $this->crud->delete_where_in('sell_items', 'sell_item_id', $old_sell_item_id_arr);
                        // Delete lineitems sell item charges details
                        $this->crud->delete_where_in('sell_item_charges_details', 'sell_item_id', $old_sell_item_id_arr);
                    }

                    // Insert Payment Receipt Data
                    if(!empty($pay_rec_data)){
                        foreach ($pay_rec_data as $pay_rec){
                            $insert_pay_rec = array();
                            $insert_pay_rec['sell_id'] = $sell_id;
                            $insert_pay_rec['payment_receipt'] = $pay_rec->payment_receipt;
                            $insert_pay_rec['cash_cheque'] = $pay_rec->cash_cheque;
                            $insert_pay_rec['bank_id'] = $pay_rec->bank_id;
                            $insert_pay_rec['transaction_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
                            $insert_pay_rec['department_id'] = $post_data['process_id'];
                            $insert_pay_rec['account_id'] = $post_data['account_id'];
                            $insert_pay_rec['on_behalf_of'] = $post_data['process_id'];
                            $insert_pay_rec['amount'] = $pay_rec->amount;
                            $insert_pay_rec['narration'] = isset($pay_rec->narration) ? $pay_rec->narration : '';
                            $insert_pay_rec['updated_at'] = $this->now_time;
                            $insert_pay_rec['updated_by'] = $this->logged_in_id;
                            if(isset($pay_rec->pay_rec_id) && !empty($pay_rec->pay_rec_id)){
                                $this->crud->update('payment_receipt', $insert_pay_rec, array('pay_rec_id' => $pay_rec->pay_rec_id));
                                $old_pay_rec_id_arr = array_diff($old_pay_rec_id_arr, array($pay_rec->pay_rec_id));
                            } else {
                                $insert_pay_rec['created_at'] = $this->now_time;
                                $insert_pay_rec['created_by'] = $this->logged_in_id;
                                $this->crud->insert('payment_receipt', $insert_pay_rec);
                            }

                            // Update Selected Account balance
                            if($pay_rec->payment_receipt == '1'){
                                $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $pay_rec->amount);

                                if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                                    $this->applib->update_account_balance_decrease($post_data['process_id'], '', '', $pay_rec->amount);
                                } else if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                                    $this->applib->update_account_balance_decrease($pay_rec->bank_id, '', '', $pay_rec->amount);
                                }
                            } else {
                                $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $pay_rec->amount);

                                if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                                    $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $pay_rec->amount);
                                } else if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                                    $this->applib->update_account_balance_increase($pay_rec->bank_id, '', '', $pay_rec->amount);
                                }
                            }
                        }
                    }
                    // Delete Deleted Payment Receipt Data
                    if (!empty($old_pay_rec_id_arr)) {
//                        $old_pay_rec_ids = implode(',', $old_pay_rec_id_arr);
                        $this->crud->delete_where_in('payment_receipt', 'pay_rec_id', $old_pay_rec_id_arr);
                    }

                    // Insert Metal Payment Receipt Data
                    if (!empty($metal_data)) {
                        foreach ($metal_data as $metal) {
                            $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $metal->metal_item_id);
                            $insert_metal_pr = array();
                            $insert_metal_pr['sell_id'] = $sell_id;
                            $insert_metal_pr['metal_payment_receipt'] = $metal->metal_payment_receipt;
                            $insert_metal_pr['metal_category_id'] = $category_id;
                            $insert_metal_pr['metal_item_id'] = $metal->metal_item_id;
                            $insert_metal_pr['metal_grwt'] = $metal->metal_grwt;
                            $insert_metal_pr['metal_ntwt'] = $metal->metal_grwt;
                            $insert_metal_pr['metal_tunch'] = $metal->metal_tunch;
                            $insert_metal_pr['metal_fine'] = $metal->metal_fine;
                            $insert_metal_pr['metal_narration'] = $metal->metal_narration;
                            $insert_metal_pr['total_gold_fine'] = $post_data['metal_gold_total'];
                            $insert_metal_pr['total_silver_fine'] = $post_data['metal_silver_total'];
                            $insert_metal_pr['updated_at'] = $this->now_time;
                            $insert_metal_pr['updated_by'] = $this->logged_in_id;
                            if(isset($metal->metal_pr_id) && !empty($metal->metal_pr_id)){
                                $this->crud->update('metal_payment_receipt', $insert_metal_pr, array('metal_pr_id' => $metal->metal_pr_id));
                                $old_metal_pr_id_arr = array_diff($old_metal_pr_id_arr, array($metal->metal_pr_id));
                            } else {
                                $insert_metal_pr['created_at'] = $this->now_time;
                                $insert_metal_pr['created_by'] = $this->logged_in_id;
                                $this->crud->insert('metal_payment_receipt', $insert_metal_pr);
                            }

                            if($metal->group_name == CATEGORY_GROUP_GOLD_ID){
                                $gold_fine = $metal->metal_fine;
                                $silver_fine = '';
                            } else {
                                $gold_fine = '';
                                $silver_fine = $metal->metal_fine;
                            }
                            if($metal->metal_payment_receipt == '1'){
                                // Update Selected Account balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, '');
                                // Update Department balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                            } else {
                                // Update Selected Account balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, '');
                                // Update Department balance to Increase
                                $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                            }
                        }
                    }
                    // Delete Deleted Metal Payment Receipt Data
                    if (!empty($old_metal_pr_id_arr)) {
//                        $old_metal_pr_ids = implode(',', $old_metal_pr_id_arr);
                        $this->crud->delete_where_in('metal_payment_receipt', 'metal_pr_id', $old_metal_pr_id_arr);
                    }

                    // Insert Gold bhav Data
                    if(!empty($gold_data)){
                        foreach ($gold_data as $gold){
                            $insert_gold = array();
                            $insert_gold['sell_id'] = $sell_id;
                            $insert_gold['gold_sale_purchase'] = $gold->gold_sale_purchase;
                            $insert_gold['gold_weight'] = $gold->gold_weight;
                            $insert_gold['gold_rate'] = $gold->gold_rate;
                            $insert_gold['gold_value'] = $gold->gold_value;
                            $insert_gold['gold_narration'] = $gold->gold_narration;
                            $insert_gold['updated_at'] = $this->now_time;
                            $insert_gold['updated_by'] = $this->logged_in_id;
                            if(isset($gold->gold_id) && !empty($gold->gold_id)){
                                $this->crud->update('gold_bhav', $insert_gold, array('gold_id' => $gold->gold_id));
                                $old_gold_id_arr = array_diff($old_gold_id_arr, array($gold->gold_id));
                            } else {
                                $insert_gold['created_at'] = $this->now_time;
                                $insert_gold['created_by'] = $this->logged_in_id;
                                $this->crud->insert('gold_bhav', $insert_gold);
                            }

                            if($gold->gold_sale_purchase == '1'){
                                // Update Selected Account Wt to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], $gold->gold_weight, '', '');
                                // Update Selected Account balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $gold->gold_value);
                            } else {
                                // Update Selected Account Wt to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], $gold->gold_weight, '', '');
                                // Update Selected Account balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $gold->gold_value);
                            }
                        }
                    }
                    // Delete Deleted Gold bhav Data
                    if (!empty($old_gold_id_arr)) {
//                        $old_gold_ids = implode(',', $old_gold_id_arr);
                        $this->crud->delete_where_in('gold_bhav', 'gold_id', $old_gold_id_arr);
                    }

                    // Insert Silver bhav Data
                    if(!empty($silver_data)){
                        foreach ($silver_data as $silver){
                            $insert_silver = array();
                            $insert_silver['sell_id'] = $sell_id;
                            $insert_silver['silver_sale_purchase'] = $silver->silver_sale_purchase;
                            $insert_silver['silver_weight'] = $silver->silver_weight;
                            $insert_silver['silver_rate'] = $silver->silver_rate;
                            $insert_silver['silver_value'] = $silver->silver_value;
                            $insert_silver['silver_narration'] = $silver->silver_narration;
                            $insert_silver['updated_at'] = $this->now_time;
                            $insert_silver['updated_by'] = $this->logged_in_id;
                            if(isset($silver->silver_id) && !empty($silver->silver_id)){
                                $this->crud->update('silver_bhav', $insert_silver, array('silver_id' => $silver->silver_id));
                                $old_silver_id_arr = array_diff($old_silver_id_arr, array($silver->silver_id));
                            } else {
                                $insert_silver['created_at'] = $this->now_time;
                                $insert_silver['created_by'] = $this->logged_in_id;
                                $this->crud->insert('silver_bhav', $insert_silver);
                            }

                            if($silver->silver_sale_purchase == '1'){
                                // Update Selected Account Wt to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], '', $silver->silver_weight, '');
                                // Update Selected Account balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $silver->silver_value);
                            } else {
                                // Update Selected Account Wt to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], '', $silver->silver_weight, '');
                                // Update Selected Account balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $silver->silver_value);
                            }
                        }
                    }
                    // Delete Deleted Silver bhav Data
                    if (!empty($old_silver_id_arr)) {
//                        $old_silver_ids = implode(',', $old_silver_id_arr);
                        $this->crud->delete_where_in('silver_bhav', 'silver_id', $old_silver_id_arr);
                    }
                }
            }
        } else {
            $check_bill_sell_entry = $this->crud->get_column_value_by_id('sell','sell_id',array('sell_no' => $post_data['sell_no'], 'bill_financial_year' => $post_data['bill_financial_year']));
            if(!empty($check_bill_sell_entry)){
                $return['error'] = "Exist";
                $return['error_exist'] = "Bill No. Already Exist";
                print json_encode($return);
                exit;
            }

            $insert_arr = array();
            $insert_arr['sell_no'] = $post_data['sell_no'];
            $insert_arr['bill_financial_year'] = $post_data['bill_financial_year'];
            $insert_arr['account_id'] = $post_data['account_id'];
            $insert_arr['process_id'] = $post_data['process_id'];
            $insert_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
            $insert_arr['sell_remark'] = $post_data['sell_remark'];
            $insert_arr['order_id'] = $order_id;
            
            $insert_arr['total_gold_fine'] = $post_data['total_gold_fine'];
            $insert_arr['total_silver_fine'] = $post_data['total_silver_fine'];
            $insert_arr['total_amount'] = $post_data['total_amount'];
            if(isset($post_data['discount_amount'])){
                $insert_arr['discount_amount'] = $post_data['discount_amount'];
            }
            
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('sell', $insert_arr);
            $sell_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Sell/purchase Added Successfully');

                // Discount Amount Effects
                if(isset($post_data['discount_amount'])){
                    // Discount Amount Decrease from Selected Account
                    $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $post_data['discount_amount']);
                    // Discount Amount Increase to Department
                    $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $post_data['discount_amount']);
                }

                // Insert sell_purchase_items
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $lot = $this->crud->get_max_number('sell_items', 'sell_item_no');
                        $sell_item_no = 1;
                        if ($lot->sell_item_no > 0) {
                            $sell_item_no = $lot->sell_item_no + 1;
                        }
                        $insert_item = array();
                        $insert_item['sell_id'] = $sell_id;
                        $insert_item['sell_item_no'] = $sell_item_no;
                        $insert_item['type'] = SELL_TYPE_SELL_ID;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $lineitem->item_id);
                        $insert_item['category_id'] = $category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['touch_id'] = '100';
                        
                        $insert_item['rate_per_1_gram'] = $lineitem->rate_per_1_gram;
                        $insert_item['gross_amount'] = $lineitem->gross_amount;
                        $insert_item['labout_other_charges'] = $lineitem->labout_other_charges;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['li_narration'] = $lineitem->li_narration;
                        
                        $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                        $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        if(isset($lineitem->order_lot_item_id) && !empty($lineitem->order_lot_item_id)){
                            $insert_item['order_lot_item_id'] = $lineitem->order_lot_item_id;
                            // On Sell item add order lot item status to Completed
                            $this->crud->update('order_lot_item', array('item_status_id' => '3'), array('order_lot_item_id' => $lineitem->order_lot_item_id));
                        }
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('sell_items', $insert_item);
                        $sell_item_id = $this->db->insert_id();

                        // Update Selected Account balance to Increase
                        $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $lineitem->amount);
                        // Update Department balance to Decrease
                        $this->applib->update_account_balance_decrease($post_data['process_id'], '', '', $lineitem->amount);

                        if(isset($lineitem->sell_item_charges_details) && !empty($lineitem->sell_item_charges_details)){
                            $sell_item_charges_details = json_decode($lineitem->sell_item_charges_details);
                            foreach ($sell_item_charges_details as $sell_item_charges_detail){
                                $insert_sell_item_charges_detail = array();
                                $insert_sell_item_charges_detail['sell_id'] = $sell_id;
                                $insert_sell_item_charges_detail['sell_item_id'] = $sell_item_id;
                                $insert_sell_item_charges_detail['sell_item_charges_details_ad_id'] = $sell_item_charges_detail->sell_item_charges_details_ad_id;
                                $insert_sell_item_charges_detail['sell_item_charges_details_net_wt'] = $sell_item_charges_detail->sell_item_charges_details_net_wt;
                                $insert_sell_item_charges_detail['sell_item_charges_details_per_gram'] = $sell_item_charges_detail->sell_item_charges_details_per_gram;
                                $insert_sell_item_charges_detail['sell_item_charges_details_ad_amount'] = $sell_item_charges_detail->sell_item_charges_details_ad_amount;
                                $insert_sell_item_charges_detail['created_at'] = $this->now_time;
                                $insert_sell_item_charges_detail['created_by'] = $this->logged_in_id;
                                $insert_sell_item_charges_detail['updated_at'] = $this->now_time;
                                $insert_sell_item_charges_detail['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('sell_item_charges_details', $insert_sell_item_charges_detail);
                            }
                        }
                    }
                }

                // Insert Payment Receipt Data
                if(!empty($pay_rec_data)){
                    foreach ($pay_rec_data as $pay_rec){
                        $insert_pay_rec = array();
                        $insert_pay_rec['sell_id'] = $sell_id;
                        $insert_pay_rec['payment_receipt'] = $pay_rec->payment_receipt;
                        $insert_pay_rec['cash_cheque'] = $pay_rec->cash_cheque;
                        $insert_pay_rec['bank_id'] = $pay_rec->bank_id;
                        $insert_pay_rec['transaction_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
                        $insert_pay_rec['department_id'] = $post_data['process_id'];
                        $insert_pay_rec['account_id'] = $post_data['account_id'];
                        $insert_pay_rec['on_behalf_of'] = $post_data['process_id'];
                        $insert_pay_rec['amount'] = $pay_rec->amount;
                        $insert_pay_rec['narration'] = isset($pay_rec->narration) ? $pay_rec->narration : '';
                        $insert_pay_rec['created_at'] = $this->now_time;
                        $insert_pay_rec['created_by'] = $this->logged_in_id;
                        $insert_pay_rec['updated_at'] = $this->now_time;
                        $insert_pay_rec['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('payment_receipt', $insert_pay_rec);

                        // Update Selected Account balance
                        if($pay_rec->payment_receipt == '1'){
                            $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $pay_rec->amount);

                            if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                                $this->applib->update_account_balance_decrease($post_data['process_id'], '', '', $pay_rec->amount);
                            } else if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                                $this->applib->update_account_balance_decrease($pay_rec->bank_id, '', '', $pay_rec->amount);
                            }
                        } else {
                            $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $pay_rec->amount);

                            if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                                $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $pay_rec->amount);
                            } else if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                                $this->applib->update_account_balance_increase($pay_rec->bank_id, '', '', $pay_rec->amount);
                            }
                        }
                    }
                }

                // Insert Metal Payment Receipt Data
                if (!empty($metal_data)) {
                    foreach ($metal_data as $metal) {
                        $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $metal->metal_item_id);
                        $insert_metal_pr = array();
                        $insert_metal_pr['sell_id'] = $sell_id;
                        $insert_metal_pr['metal_payment_receipt'] = $metal->metal_payment_receipt;
                        $insert_metal_pr['metal_category_id'] = $category_id;
                        $insert_metal_pr['metal_item_id'] = $metal->metal_item_id;
                        $insert_metal_pr['metal_grwt'] = $metal->metal_grwt;
                        $insert_metal_pr['metal_ntwt'] = $metal->metal_grwt;
                        $insert_metal_pr['metal_tunch'] = $metal->metal_tunch;
                        $insert_metal_pr['metal_fine'] = $metal->metal_fine;
                        $insert_metal_pr['metal_narration'] = $metal->metal_narration;
                        $insert_metal_pr['total_gold_fine'] = $post_data['metal_gold_total'];
                        $insert_metal_pr['total_silver_fine'] = $post_data['metal_silver_total'];
                        $insert_metal_pr['created_at'] = $this->now_time;
                        $insert_metal_pr['created_by'] = $this->logged_in_id;
                        $insert_metal_pr['updated_at'] = $this->now_time;
                        $insert_metal_pr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('metal_payment_receipt', $insert_metal_pr);

                        if($metal->group_name == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $metal->metal_fine;
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $metal->metal_fine;
                        }
                        if($metal->metal_payment_receipt == '1'){
                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, '');
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                        } else {
                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, '');
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                        }
                    }
                }

                // Insert Gold bhav Data
                if(!empty($gold_data)){
                    foreach ($gold_data as $gold){
                        $insert_gold = array();
                        $insert_gold['sell_id'] = $sell_id;
                        $insert_gold['gold_sale_purchase'] = $gold->gold_sale_purchase;
                        $insert_gold['gold_weight'] = $gold->gold_weight;
                        $insert_gold['gold_rate'] = $gold->gold_rate;
                        $insert_gold['gold_value'] = $gold->gold_value;
                        $insert_gold['gold_narration'] = $gold->gold_narration;
                        $insert_gold['created_at'] = $this->now_time;
                        $insert_gold['created_by'] = $this->logged_in_id;
                        $insert_gold['updated_at'] = $this->now_time;
                        $insert_gold['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('gold_bhav', $insert_gold);

                        if($gold->gold_sale_purchase == '1'){
                            // Update Selected Account Wt to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], $gold->gold_weight, '', '');
                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $gold->gold_value);
                        } else {
                            // Update Selected Account Wt to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], $gold->gold_weight, '', '');
                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $gold->gold_value);
                        }
                    }
                }

                // Insert Silver bhav Data
                if(!empty($silver_data)){
                    foreach ($silver_data as $silver){
                        $insert_silver = array();
                        $insert_silver['sell_id'] = $sell_id;
                        $insert_silver['silver_sale_purchase'] = $silver->silver_sale_purchase;
                        $insert_silver['silver_weight'] = $silver->silver_weight;
                        $insert_silver['silver_rate'] = $silver->silver_rate;
                        $insert_silver['silver_value'] = $silver->silver_value;
                        $insert_silver['silver_narration'] = $silver->silver_narration;
                        $insert_silver['created_at'] = $this->now_time;
                        $insert_silver['created_by'] = $this->logged_in_id;
                        $insert_silver['updated_at'] = $this->now_time;
                        $insert_silver['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('silver_bhav', $insert_silver);

                        if($silver->silver_sale_purchase == '1'){
                            // Update Selected Account Wt to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], '', $silver->silver_weight, '');
                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $silver->silver_value);
                        } else {
                            // Update Selected Account Wt to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], '', $silver->silver_weight, '');
                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $silver->silver_value);
                        }
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function splist($param1 = '') {
        if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) {
            if($param1 == "sell") {
                $page_label = "Sell";
                $entry_page_url = base_url("sell_purchase/add/sell");
            } else {
                $page_label = "Purchase";
                $entry_page_url = base_url("sell_purchase/add/purchase");
            }
            $data = array();
            $data['page_label'] = $page_label;
            $data['entry_page_url'] = $entry_page_url;
            $data['sell_purchase'] = $param1;
            set_page('sell_purchase/list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function sell_purchase_datatable() {
        $post_data = $this->input->post();
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell s';
        $config['select'] = 's.*,p.account_name,p.account_mobile,a.account_name AS process_name';
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.process_id', 'join_type' => 'left');
        $config['column_search'] = array('s.sell_no','p.account_name','a.account_name','DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 'sell_remark');
        $config['column_order'] = array(null, 'p.account_name', 'a.account_name', 's.sell_no', 's.sell_date', 'sell_remark');

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
            $config['custom_where'] .= ' AND s.process_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND s.process_id IN(-1)';
        }

//        if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
//            $config['custom_where'] .= ' AND s.sell_id IN(SELECT sell_id FROM sell_items WHERE type IN('.SELL_TYPE_SELL_ID.'))';
//        }
//
//        if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
//            $config['custom_where'] .= ' AND s.sell_id IN(SELECT sell_id FROM sell_items WHERE type IN('.SELL_TYPE_PURCHASE_ID.','.SELL_TYPE_EXCHANGE_ID.') AND sell_id=s.sell_id)';
//        }

        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date'])){
                $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
            }
        }
        if(!empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 's.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }

        $config['group_by'] = 's.sell_id';
        $config['order'] = array('s.sell_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo '<pre>'; print_r($list); exit;
        $role_delete = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
        foreach ($list as $sell) {
            $row = array();
            $action = '';
            if($sell->account_id != ADJUST_EXPENSE_ACCOUNT_ID){

                if($sell->audit_status != AUDIT_STATUS_AUDITED){
//                    if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
                        $action .= '<a href="' . base_url("sell_purchase/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
//                        $action .= '<a href="' . base_url("sell_purchase/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//
//                    } else {
//                        $action .= '<a href="' . base_url("sell_purchase/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//                    }

                    if($role_delete){
                        $action .= '<a href="javascript:void(0);" class="delete_sell" data-sell_id="'.$sell->sell_id.'" data-href="' . base_url('sell_purchase/delete_sell/' . $sell->sell_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else {
//                    if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
//                        $action .= '<a href="' . base_url("sell_purchase/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//
//                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
//                        $action .= '<a href="' . base_url("sell_purchase/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//
//                    } else {
//                        $action .= '<a href="' . base_url("sell_purchase/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
//                    }

                }
            }
            $action .= '<a href="' . base_url("sell_purchase/sell_purchase_print/" . $sell->sell_id) . '" target="_blank" title="Sell/Purchase Print" alt="Sell/Purchase Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            $audit_status = '';
            if($sell->audit_status == AUDIT_STATUS_AUDITED){
                $audit_status = 'A';
            } else if($sell->audit_status == AUDIT_STATUS_SUSPECTED){
                $audit_status = 'S';
            } else {
                $audit_status = 'P';
            }
            $action .= '<a href="javascript:void(0);" class="audit_status_button" data-audit_status_sell_id="' . $sell->sell_id . '" data-audit_status="' . $sell->audit_status . '" style="margin: 8px;">'. $audit_status .' </a>';
            $pay_rec = $this->crud->get_id_by_val('payment_receipt', 'payment_receipt', 'sell_id', $sell->sell_id);
            if(!empty($pay_rec)){
                $action .= '<a href="javascript:void(0);" class="pay_rec_id" data-pay_rec_id="' . $sell->sell_id . '" ><span class="btn btn-sm btn-instagram">P</span></a>&nbsp;';
            }
            $metal_pr = $this->crud->get_id_by_val('metal_payment_receipt', 'metal_payment_receipt', 'sell_id', $sell->sell_id);
            if(!empty($metal_pr)){
                $action .= '<a href="javascript:void(0);" class="metal_pr_id" data-metal_pr_id="' . $sell->sell_id . '" ><span class="btn btn-sm btn-info">M</span></a>&nbsp;';
            }
            $gold = $this->crud->get_id_by_val('gold_bhav', 'gold_sale_purchase', 'sell_id', $sell->sell_id);
            if(!empty($gold)){
                $action .= '<a href="javascript:void(0);" class="gold_id" data-gold_id="' . $sell->sell_id . '" ><span class="btn btn-sm btn-success">G</span></a>&nbsp;';
            }
            $silver = $this->crud->get_id_by_val('silver_bhav', 'silver_sale_purchase', 'sell_id', $sell->sell_id);
            if(!empty($silver)){
                $action .= '<a href="javascript:void(0);" class="silver_id" data-silver_id="' . $sell->sell_id . '" ><span class="btn btn-sm btn-danger">S</span></a>&nbsp;';
            }
            $transfer = $this->crud->get_id_by_val('transfer', 'naam_jama', 'sell_id', $sell->sell_id);
            if(!empty($transfer)){
                $action .= '<a href="javascript:void(0);" class="transfer_id" data-transfer_id="' . $sell->sell_id . '" ><span class="btn btn-sm btn-warning">T</span></a>&nbsp;';
            }
            $row[] = $action;
            $row[] = $sell->account_name . ' - ' . $sell->account_mobile;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >SJ/' . $sell->bill_financial_year . '/' . $sell->process_name . '/' . $sell->sell_no . '</a>';
            $row[] = (!empty(strtotime($sell->sell_date))) ? date('d-m-Y', strtotime($sell->sell_date)) : '';
            $row[] = $sell->sell_remark;
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

    function delete_sell($sell_id = '', $have_rfid = '') {
        $return = array();
        $sell_data = $this->crud->get_data_row_by_id('sell', 'sell_id', $sell_id);
        if(!empty($sell_data)){
            // Revert : Discount Amount Effects
            if(isset($sell_data->discount_amount)){
                // Discount Amount Increase to Selected Account
                $this->applib->update_account_balance_increase($sell_data->account_id, '', '', $sell_data->discount_amount);
                // Discount Amount Decrease from Department
                $this->applib->update_account_balance_decrease($sell_data->process_id, '', '', $sell_data->discount_amount);
            }

            $old_sell_purchase_items = $this->get_sell_purchase_lineitems($sell_id, 'update');
            if (!empty($old_sell_purchase_items)) {
                foreach ($old_sell_purchase_items as $old_sell_purchase_item) {
                    // Revert : Update Selected Account balance to Decrease
                    $this->applib->update_account_balance_decrease($sell_data->account_id, '', '', $old_sell_purchase_item->amount);
                    // Revert : Update Department balance to Increase
                    $this->applib->update_account_balance_increase($sell_data->process_id, '', '', $old_sell_purchase_item->amount);
                }
            }

            $old_pay_rec_data = $this->get_payment_receipt_lineitems($sell_id, 'update');
            if (!empty($old_pay_rec_data)) {
                foreach ($old_pay_rec_data as $old_pay_rec_row) {
                    // Revert : Update Selected Account balance
                    if($old_pay_rec_row->payment_receipt == '1'){
                        $this->applib->update_account_balance_decrease($sell_data->account_id, '', '', $old_pay_rec_row->amount);

                        if($old_pay_rec_row->cash_cheque == '1'){ // Update Department Amount
                            $this->applib->update_account_balance_increase($sell_data->process_id, '', '', $old_pay_rec_row->amount);
                        } else if($old_pay_rec_row->cash_cheque == '2'){ // Update Bank Amount
                            $this->applib->update_account_balance_increase($old_pay_rec_row->bank_id, '', '', $old_pay_rec_row->amount);
                        }
                    } else {
                        $this->applib->update_account_balance_increase($sell_data->account_id, '', '', $old_pay_rec_row->amount);

                        if($old_pay_rec_row->cash_cheque == '1'){ // Update Department Amount
                            $this->applib->update_account_balance_decrease($sell_data->process_id, '', '', $old_pay_rec_row->amount);
                        } else if($old_pay_rec_row->cash_cheque == '2'){ // Update Bank Amount
                            $this->applib->update_account_balance_decrease($old_pay_rec_row->bank_id, '', '', $old_pay_rec_row->amount);
                        }
                    }
                }
            }
            $this->crud->delete('payment_receipt', array('sell_id' => $sell_id));

            $old_metal_payment_receipt_data = $this->get_metal_payment_receipt_lineitems($sell_id, 'update');
            if (!empty($old_metal_payment_receipt_data)) {
                foreach ($old_metal_payment_receipt_data as $old_metal_payment_receipt_row) {
                    $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $old_metal_payment_receipt_row->metal_item_id);
                    $category_group_id = $this->crud->get_id_by_val('category', 'category_group_id', 'category_id', $category_id);

                    // Revert : Update Selected Account balance
                    if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $gold_fine = $old_metal_payment_receipt_row->metal_fine;
                        $silver_fine = '';
                    } else {
                        $gold_fine = '';
                        $silver_fine = $old_metal_payment_receipt_row->metal_fine;
                    }
                    if($old_metal_payment_receipt_row->metal_payment_receipt == '1'){
                        // Update Selected Account balance to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->account_id, $gold_fine, $silver_fine, '');
                        // Update Department balance to Increase
                        $this->applib->update_account_balance_increase($sell_data->process_id, $gold_fine, $silver_fine, '');
                    } else {
                        // Update Selected Account balance to Increase
                        $this->applib->update_account_balance_increase($sell_data->account_id, $gold_fine, $silver_fine, '');
                        // Update Department balance to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->process_id, $gold_fine, $silver_fine, '');
                    }
                }
            }
            $this->crud->delete('metal_payment_receipt', array('sell_id' => $sell_id));

            $old_gold_bhav_data = $this->get_gold_bhav_lineitems($sell_id, 'update');
            if (!empty($old_gold_bhav_data)) {
                foreach ($old_gold_bhav_data as $old_gold_bhav_row) {

                    if($old_gold_bhav_row->gold_sale_purchase == '1'){
                        // Revert : Update Selected Account Wt to Increase
                        $this->applib->update_account_balance_increase($sell_data->account_id, $old_gold_bhav_row->gold_weight, '', '');
                        // Revert : Update Selected Account balance to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->account_id, '', '', $old_gold_bhav_row->gold_value);
                    } else {
                        // Revert : Update Selected Account Wt to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->account_id, $old_gold_bhav_row->gold_weight, '', '');
                        // Revert : Update Selected Account balance to Increase
                        $this->applib->update_account_balance_increase($sell_data->account_id, '', '', $old_gold_bhav_row->gold_value);
                    }
                }
            }
            $this->crud->delete('gold_bhav', array('sell_id' => $sell_id));

            $old_silver_bhav_data = $this->get_silver_bhav_lineitems($sell_id, 'update');
            if (!empty($old_silver_bhav_data)) {
                foreach ($old_silver_bhav_data as $old_silver_bhav_row) {

                    if($old_silver_bhav_row->silver_sale_purchase == '1'){
                        // Revert : Update Selected Account Wt to Increase
                        $this->applib->update_account_balance_increase($sell_data->account_id, '', $old_silver_bhav_row->silver_weight, '');
                        // Revert : Update Selected Account balance to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->account_id, '', '', $old_silver_bhav_row->silver_value);
                    } else {
                        // Revert : Update Selected Account Wt to Decrease
                        $this->applib->update_account_balance_decrease($sell_data->account_id, '', $old_silver_bhav_row->silver_weight, '');
                        // Revert : Update Selected Account balance to Increase
                        $this->applib->update_account_balance_increase($sell_data->account_id, '', '', $old_silver_bhav_row->silver_value);
                    }
                }
            }
            $this->crud->delete('silver_bhav', array('sell_id' => $sell_id));

            // Delete sell items
            $this->crud->delete('sell_items', array('sell_id' => $sell_id));
            // Delete sell item charges details
            $this->crud->delete('sell_item_charges_details', array('sell_id' => $sell_id));
            $this->crud->delete('sell', array('sell_id' => $sell_id));
            $return['success'] = 'Deleted';
        }
        echo json_encode($return);
        exit;
    }

    function sell_purchase_print($sell_id = '', $isimage = '') {
        $data = array();
        $setting_data = $this->crud->get_all_records('settings', 'fields_section', 'asc');
        foreach($setting_data as $setting_row){
            if($setting_row->settings_key == 'without_purchase_sell_allow' || $setting_row->settings_key == 'use_category' || $setting_row->fields_section == '1' || $setting_row->fields_section == '2'){
                $data[$setting_row->settings_key] = $setting_row->settings_value;
            }
        }

        if(!empty($sell_id)){
            $sell_data = $this->crud->get_row_by_id('sell', array('sell_id' => $sell_id));
            $sell_data = $sell_data[0];
            $sell_data->account_name = '';
            $sell_data->old_gold_fine = 0;
            $sell_data->old_silver_fine = 0;
            $sell_data->old_amount = 0;
            $account_data = $this->crud->get_row_by_id('account',array('account_id' => $sell_data->account_id));
            if(!empty($account_data)){
                $sell_data->account_name = $account_data[0]->account_name;
                $sell_data->old_gold_fine = $account_data[0]->gold_fine;
                $sell_data->old_silver_fine = $account_data[0]->silver_fine;
                $sell_data->old_amount = number_format($account_data[0]->amount, 0, '.', '');
            }
            $sell_data->process_name = $this->crud->get_column_value_by_id('account','account_name',array('account_id' => $sell_data->process_id));
            $sell_data->total_gold_fine = (!empty($sell_data->total_gold_fine)) ? $sell_data->total_gold_fine : 0;
            $sell_data->total_silver_fine = (!empty($sell_data->total_silver_fine)) ? $sell_data->total_silver_fine : 0;
            $sell_data->total_amount = (!empty($sell_data->total_amount)) ? $sell_data->total_amount : 0;
            $sell_data->old_gold_fine = $sell_data->old_gold_fine - $sell_data->total_gold_fine;
            $sell_data->old_silver_fine = $sell_data->old_silver_fine - $sell_data->total_silver_fine;
            $sell_data->old_amount = $sell_data->old_amount - $sell_data->total_amount;
            $data['sell_data'] = $sell_data;
//            print_r($data['sell_data']); exit;

            $data['sell_lineitems'] = $this->get_sell_purchase_lineitems($sell_id, 'update');
            $data['pay_rec_data'] = $this->get_payment_receipt_lineitems($sell_id, 'update');
            $data['metal_data'] = $this->get_metal_payment_receipt_lineitems($sell_id, 'update');
            $data['gold_data'] = $this->get_gold_bhav_lineitems($sell_id, 'update');
            $data['silver_data'] = $this->get_silver_bhav_lineitems($sell_id, 'update');
        }
//        print_r($data); exit;
        $data['company_name'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_name'));
        $data['company_contact'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_contact'));
        $data['company_address'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'company_address'));
        $data['ask_discount_in_sell_purchase'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'ask_discount_in_sell_purchase'));

        $html = $this->load->view('sell_purchase/print_shanti', $data, true);
        $mpdf = new \Mpdf\Mpdf();
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
        $mpdf->defHTMLHeaderByName('myHeader2','<div style="text-align: center; font-weight: bold;">Sell / Purchase Print</div>');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function get_sell_purchase_lineitems($sell_id = '', $for = ''){
        $data = array();
        $sell_lineitems = array();
        $sell_items = $this->crud->get_row_by_id('sell_items', array('sell_id' => $sell_id));
        if(!empty($sell_items)){
            foreach($sell_items as $sell_item){
                $sell_item->sell_item_delete = 'allow';
                $sell_item->type = $sell_item->type;
                $type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $sell_item->type));
                $sell_item->type_name = $type_name[0];
                $sell_item->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $sell_item->category_id));
                $sell_item->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_item->category_id));
                $sell_item->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $sell_item->item_id));
                $sell_item->grwt = number_format($sell_item->grwt, 3, '.', '');
                $sell_item->less = number_format($sell_item->less, 3, '.', '');
                $sell_item->net_wt = number_format($sell_item->net_wt, 3, '.', '');
                $sell_item->touch_id = $sell_item->touch_id;
                $sell_item->wstg = number_format($sell_item->wstg, 3, '.', '');
                $sell_item->fine = number_format($sell_item->fine, 3, '.', '');
                $sell_item->category_id = $sell_item->category_id;
                $sell_item->item_id = $sell_item->item_id;
                $sell_item->sell_item_id = $sell_item->sell_item_id;
                $sell_item->item_stock_rfid_id = (isset($sell_item->item_stock_rfid_id) && !empty($sell_item->item_stock_rfid_id)) ? $sell_item->item_stock_rfid_id : NULL;
                $sell_item->rfid_number = (isset($sell_item->rfid_number) && !empty($sell_item->rfid_number)) ? $sell_item->rfid_number : NULL;
                $sell_item->charges_amt = (isset($sell_item->charges_amt) && !empty($sell_item->charges_amt)) ? $sell_item->charges_amt : 0;
                $sell_item->spi_pcs = (isset($sell_item->spi_pcs) && !empty($sell_item->spi_pcs)) ? $sell_item->spi_pcs : 0;
                $sell_item->spi_rate = (isset($sell_item->spi_rate) && !empty($sell_item->spi_rate)) ? $sell_item->spi_rate : 0;
                $sell_item->amount = (isset($sell_item->amount) && !empty($sell_item->amount)) ? $sell_item->amount : 0;
                $sell_item->image = $sell_item->image;
                $sell_item->order_lot_item_id = $sell_item->order_lot_item_id;
                $sell_item->purchase_sell_item_id = $sell_item->purchase_sell_item_id;
                $sell_item->stock_type = $sell_item->stock_type;

                //----------------- Sell item charges Details -------------------
                $sell_item->sell_item_charges_details = '';
                $sell_item_charges_details = $this->crud->get_row_by_id('sell_item_charges_details', array('sell_id' => $sell_item->sell_id, 'sell_item_id' => $sell_item->sell_item_id));
                if(!empty($sell_item_charges_details)){
                    $sell_item_charges_details_data = array();
                    foreach ($sell_item_charges_details as $sell_item_charges_detail){
                        $sell_item_charges_details_lineitems = new \stdClass();
                        $sell_item_charges_details_lineitems->sell_item_charges_details_id = $sell_item_charges_detail->sell_item_charges_details_id;
                        $sell_item_charges_details_lineitems->sell_item_charges_details_delete = 'allow';
                        $sell_item_charges_details_lineitems->sell_item_charges_details_ad_id = $sell_item_charges_detail->sell_item_charges_details_ad_id;
                        $sell_item_charges_details_lineitems->sell_item_charges_details_ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $sell_item_charges_detail->sell_item_charges_details_ad_id));
                        $sell_item_charges_details_lineitems->sell_item_charges_details_net_wt = $sell_item_charges_detail->sell_item_charges_details_net_wt;
                        $sell_item_charges_details_lineitems->sell_item_charges_details_per_gram = $sell_item_charges_detail->sell_item_charges_details_per_gram;
                        $sell_item_charges_details_lineitems->sell_item_charges_details_ad_amount = $sell_item_charges_detail->sell_item_charges_details_ad_amount;
                        $sell_item_charges_details_data[] = json_encode($sell_item_charges_details_lineitems);
                    }
                    $sell_item->sell_item_charges_details = '['.implode(',', $sell_item_charges_details_data).']';
                }

                $sell_lineitems[] = $sell_item;
            }
        }
        $data['sell_items'] = $sell_lineitems;
        if ($for == 'edit') {
            return json_encode($sell_lineitems);
        } else if ($for == 'update') {
            return $sell_lineitems;
        } else {
            echo json_encode($data);
        }
    }

    function get_payment_receipt_lineitems($sell_id = '', $for = ''){
        $data = array();
        $pay_rec_lineitems = array();
        $payment_receipt = $this->crud->get_row_by_id('payment_receipt', array('sell_id' => $sell_id));
        if(!empty($payment_receipt)){
            foreach ($payment_receipt as $value){
                $pay_lineitems = new \stdClass();
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
                $pay_rec_lineitems[] = $pay_lineitems;
            }
        }
        $data['pay_rec_data'] = $pay_rec_lineitems;
        if ($for == 'edit') {
            return json_encode($pay_rec_lineitems);
        } else if ($for == 'update') {
            return $pay_rec_lineitems;
        } else {
            echo json_encode($data);
        }
    }

    function get_metal_payment_receipt_lineitems($sell_id = '', $for = ''){
        $data = array();
        $metal_pay_rec_lineitems = array();
        $metal_payment_receipt = $this->crud->get_row_by_id('metal_payment_receipt', array('sell_id' => $sell_id));
        if(!empty($metal_payment_receipt)){
            foreach ($metal_payment_receipt as $metal){
                $metal_lineitems = new \stdClass();
                $metal_lineitems->metal_item_delete = 'allow';
                $metal_lineitems->metal_item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $metal->metal_item_id));
                $metal_lineitems->metal_payment_receipt = $metal->metal_payment_receipt;
                $metal_lineitems->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $metal->metal_category_id));
                $metal_lineitems->metal_item_id = $metal->metal_item_id;
                $metal_lineitems->metal_grwt = $metal->metal_grwt;
                $metal_lineitems->metal_tunch = $metal->metal_tunch;
                $metal_lineitems->metal_fine = $metal->metal_fine;
                $metal_lineitems->metal_narration = $metal->metal_narration;
                $metal_lineitems->metal_pr_id = $metal->metal_pr_id;
                $metal_pay_rec_lineitems[] = $metal_lineitems;
            }
        }
        $data['metal_data'] = $metal_pay_rec_lineitems;
        if ($for == 'edit') {
            return json_encode($metal_pay_rec_lineitems);
        } else if ($for == 'update') {
            return $metal_pay_rec_lineitems;
        } else {
            echo json_encode($data);
        }
    }

    function get_gold_bhav_lineitems($sell_id = '', $for = ''){
        $data = array();
        $gold_lineitems = array();
        $gold_bhav = $this->crud->get_row_by_id('gold_bhav', array('sell_id' => $sell_id));
        if(!empty($gold_bhav)){
            foreach ($gold_bhav as $gold){
                $gold_bhav_lineitems = new \stdClass();
                $gold_bhav_lineitems->gold_sale_purchase = $gold->gold_sale_purchase;
                $gold_bhav_lineitems->gold_weight = $gold->gold_weight;
                $gold_bhav_lineitems->gold_rate = $gold->gold_rate;
                $gold_bhav_lineitems->gold_value = $gold->gold_value;
                $gold_bhav_lineitems->gold_narration = $gold->gold_narration;
                $gold_bhav_lineitems->gold_id = $gold->gold_id;
                $gold_lineitems[] = $gold_bhav_lineitems;
            }
        }
        $data['metal_data'] = $gold_lineitems;
        if ($for == 'edit') {
            return json_encode($gold_lineitems);
        } else if ($for == 'update') {
            return $gold_lineitems;
        } else {
            echo json_encode($data);
        }
    }

    function get_silver_bhav_lineitems($sell_id = '', $for = ''){
        $data = array();
        $silver_lineitems = array();
        $silver_bhav = $this->crud->get_row_by_id('silver_bhav', array('sell_id' => $sell_id));
        if(!empty($silver_bhav)){
            foreach ($silver_bhav as $silver){
                $silver_bhav_lineitems = new \stdClass();
                $silver_bhav_lineitems->silver_sale_purchase = $silver->silver_sale_purchase;
                $silver_bhav_lineitems->silver_weight = $silver->silver_weight;
                $silver_bhav_lineitems->silver_rate = $silver->silver_rate;
                $silver_bhav_lineitems->silver_value = $silver->silver_value;
                $silver_bhav_lineitems->silver_narration = $silver->silver_narration;
                $silver_bhav_lineitems->silver_id = $silver->silver_id;
                $silver_lineitems[] = $silver_bhav_lineitems;
            }
        }
        $data['metal_data'] = $silver_lineitems;
        if ($for == 'edit') {
            return json_encode($silver_lineitems);
        } else if ($for == 'update') {
            return $silver_lineitems;
        } else {
            echo json_encode($data);
        }
    }

}
