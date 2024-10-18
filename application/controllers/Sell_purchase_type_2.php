<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sell_purchase_type_2 extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;
    public $sell_purchase_difference = 0;

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
        $this->now_time = date('Y-m-d H:i:s');
        $this->zero_value = 0;
//        echo anchor('news/local/123', 'My News', 'title="News title"');

    }

    function add($param1 = '',$param2 = '',$param3 = '') {
        if(in_array($param1,array("sell","purchase")) && $this->sell_purchase_difference) {
            $sell_purchase = $param1;
            $sell_id = $param2;
            $order_id = $param3;
            if($sell_purchase == "sell") {
                $page_label = "Sell";
                $page_shortcut = "[CTRL + F1]";
                $list_page_url = base_url("sell_purchase_type_2/splist/sell");
            } else {
                $page_label = "Purchase";
                $page_shortcut = "[CTRL + F2]";
                $list_page_url = base_url("sell_purchase_type_2/splist/purchase");
            }
        } else {
            $sell_purchase = "sell_purchase";
            $sell_id = $param1;
            $order_id = $param2;
            $page_label = "Sell/Purchase";
            $page_shortcut = "[CTRL + F1]";
            $list_page_url = base_url("sell_purchase_type_2/splist");
        }
        $data = array();
        $data['page_label'] = $page_label;
        $data['page_shortcut'] = $page_shortcut;
        $data['list_page_url'] = $list_page_url;
        $data['sell_purchase'] = $sell_purchase;

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
//        echo '<pre>'; print_r($data); exit;
        
        //----------------- Order Data -------------------
        if (isset($sell_id) && !empty($order_id)) {
        } else if(!empty($sell_id)){
            //----------------- Sell Data -------------------
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) {
                $sell_data = $this->crud->get_row_by_id('sell', array('sell_id' => $sell_id));
                $sell_data = $sell_data[0];
                $sell_data->total_gold_fine = (!empty($sell_data->total_gold_fine)) ? $sell_data->total_gold_fine : 0;
                $sell_data->total_silver_fine = (!empty($sell_data->total_silver_fine)) ? $sell_data->total_silver_fine : 0;
                $sell_data->total_amount = (!empty($sell_data->total_amount)) ? $sell_data->total_amount : 0;
                if(PACKAGE_FOR == 'manek') {
                    $sell_data->discount_amount = (!empty($sell_data->discount_amount)) ? $sell_data->discount_amount : 0;
                    $sell_data->total_amount = $sell_data->total_amount - $sell_data->discount_amount;
                }
                $sell_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$sell_data->created_by));
                if($sell_data->created_by != $sell_data->updated_by){
                    $sell_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $sell_data->updated_by));
                }else{
                    $sell_data->updated_by_name = $sell_data ->created_by_name;
                }
                $data['sell_data'] = $sell_data;
                $data['sell_lineitems'] = $this->get_sell_purchase_lineitems($sell_id, 'edit');
                $data['pay_rec_data'] = $this->get_payment_receipt_lineitems($sell_id, 'edit');
                $data['metal_data'] = $this->get_metal_payment_receipt_lineitems($sell_id, 'edit');
                $data['gold_data'] = $this->get_gold_bhav_lineitems($sell_id, 'edit');
                $data['silver_data'] = $this->get_silver_bhav_lineitems($sell_id, 'edit');
                $data['transfer_data'] = $this->get_transfer_lineitems($sell_id, 'edit');
//                echo '<pre>'; print_r($data); exit;
                set_page('sell/sell_purchase_type_2', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            //----------------- Data -------------------
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add")) {
                set_page('sell/sell_purchase_type_2', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_sell($have_rfid = '') {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
        }
        $metal_data = array();
        if(isset($post_data['metal_data']) && !empty($post_data['metal_data'])){
            $metal_data = json_decode($post_data['metal_data']);
        }
        $pay_rec_data = array();
        if(isset($post_data['pay_rec_data']) && !empty($post_data['pay_rec_data'])){
            $pay_rec_data = json_decode($post_data['pay_rec_data']);
        }
        $gold_data = array();
        if(isset($post_data['gold_data']) && !empty($post_data['gold_data'])){
            $gold_data = json_decode($post_data['gold_data']);
        }
        $silver_data = array();
        if(isset($post_data['silver_data']) && !empty($post_data['silver_data'])){
            $silver_data = json_decode($post_data['silver_data']);
        }
        $transfer_data = array();
        if(isset($post_data['transfer_data']) && !empty($post_data['transfer_data'])){
            $transfer_data = json_decode($post_data['transfer_data']);
        }
        $ad_lineitem_data = array();
        if(isset($post_data['ad_lineitem_data']) && !empty($post_data['ad_lineitem_data'])){
            $ad_lineitem_data = json_decode($post_data['ad_lineitem_data']);
        }
        $adjust_cr_lineitem_data = array();
        if(isset($post_data['adjust_cr_lineitem_data']) && !empty($post_data['adjust_cr_lineitem_data'])){
            $adjust_cr_lineitem_data = json_decode($post_data['adjust_cr_lineitem_data']);
        }

        if (empty($line_items_data) && empty($pay_rec_data) && empty($metal_data) && empty($gold_data) && empty($silver_data) && empty($transfer_data) && empty($ad_lineitem_data) && empty($adjust_cr_lineitem_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}
//        echo '<pre>'; print_r($post_data); exit;
//        echo '<pre>'; print_r($line_items_data); exit;
        $post_data['bank_id'] = isset($post_data['bank_id']) && !empty($post_data['bank_id']) ? $post_data['bank_id'] : NULL;
        $post_data['account_id'] = isset($post_data['account_id']) && !empty($post_data['account_id']) ? $post_data['account_id'] : NULL;
        $post_data['process_id'] = isset($post_data['process_id']) && !empty($post_data['process_id']) ? $post_data['process_id'] : NULL;
        $post_data['sell_remark'] = isset($post_data['sell_remark']) && !empty($post_data['sell_remark']) ? $post_data['sell_remark'] : NULL;
        $post_data['metal_category_id'] = isset($post_data['metal_category_id']) && !empty($post_data['metal_category_id']) ? $post_data['metal_category_id'] : NULL;
        $post_data['metal_item_id'] = isset($post_data['metal_item_id']) && !empty($post_data['metal_item_id']) ? $post_data['metal_item_id'] : NULL;
        $post_data['transfer_account_id'] = isset($post_data['transfer_account_id']) && !empty($post_data['transfer_account_id']) ? $post_data['transfer_account_id'] : NULL;
        $post_data['delivery_type'] = isset($post_data['delivery_type']) && !empty($post_data['delivery_type']) ? $post_data['delivery_type'] : NULL;
        $order_id = (isset($post_data['order_id']) && !empty($post_data['order_id'])) ? $post_data['order_id'] : NULL;
        if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {
            $sell_id = $post_data['sell_id'];
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
                        $old_charges_amt = $old_sell_purchase_item->labour_amount + $old_sell_purchase_item->stone_rs + $old_sell_purchase_item->charges_amt;
                        $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $old_sell_purchase_item->category_id));
                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $old_sell_purchase_item->fine;
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $old_sell_purchase_item->fine;
                        }

                        // Revert : Update Item Stock and Charges Amount
                        $stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $old_sell_purchase_item->item_id));
                        if ($old_sell_purchase_item->type == SELL_TYPE_SELL_ID) {
                            // Sell to Increase
                            if($stock_method == STOCK_METHOD_ITEM_WISE){
                                $this->applib->update_itemwise_item_stock_increase($old_sell_data->process_id, $old_sell_purchase_item->category_id, $old_sell_purchase_item->item_id, $old_sell_purchase_item->grwt, $old_sell_purchase_item->less, $old_sell_purchase_item->net_wt, $old_sell_purchase_item->touch_id, $old_sell_purchase_item->fine, $old_sell_purchase_item->purchase_sell_item_id, $old_sell_purchase_item->stock_type);
                            } else {
                                $this->applib->update_item_stock_increase($old_sell_data->process_id, $old_sell_purchase_item->category_id, $old_sell_purchase_item->item_id, $old_sell_purchase_item->grwt, $old_sell_purchase_item->less, $old_sell_purchase_item->net_wt, $old_sell_purchase_item->touch_id, $old_sell_purchase_item->fine);
                            }

                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, $gold_fine, $silver_fine, $old_charges_amt);
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->process_id, $gold_fine, $silver_fine, '');
                            // Update MF Loss balance to Increase
                            $this->applib->update_account_balance_increase(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $old_charges_amt);
                        } else {
                            // Purchase to Decrease
                            if($stock_method == STOCK_METHOD_ITEM_WISE){
                                $this->applib->update_itemwise_item_stock_decrease($old_sell_data->process_id, $old_sell_purchase_item->category_id, $old_sell_purchase_item->item_id, $old_sell_purchase_item->grwt, $old_sell_purchase_item->less, $old_sell_purchase_item->net_wt, $old_sell_purchase_item->touch_id, $old_sell_purchase_item->fine, $old_sell_purchase_item->purchase_sell_item_id, $old_sell_purchase_item->stock_type);
                            } else {
                                $this->applib->update_item_stock_decrease($old_sell_data->process_id, $old_sell_purchase_item->category_id, $old_sell_purchase_item->item_id, $old_sell_purchase_item->grwt, $old_sell_purchase_item->less, $old_sell_purchase_item->net_wt, $old_sell_purchase_item->touch_id, $old_sell_purchase_item->fine);
                            }

                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, $gold_fine, $silver_fine, $old_charges_amt);
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->process_id, $gold_fine, $silver_fine, '');
                            // Update MF Loss balance to Decrease
                            $this->applib->update_account_balance_decrease(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $old_charges_amt);
                        }
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
                        $category_group_id = $this->crud->get_id_by_val('category', 'category_group_id', 'category_id', $old_metal_payment_receipt_row->metal_category_id);

                        // Revert : Update Selected Account balance
                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $old_metal_payment_receipt_row->metal_fine;
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $old_metal_payment_receipt_row->metal_fine;
                        }
                        if($old_metal_payment_receipt_row->metal_payment_receipt == '1'){
                            // Sell to Increase
                            $this->applib->update_item_stock_increase($old_sell_data->process_id, $old_metal_payment_receipt_row->metal_category_id, $old_metal_payment_receipt_row->metal_item_id, $old_metal_payment_receipt_row->metal_grwt, 0, $old_metal_payment_receipt_row->metal_ntwt, $old_metal_payment_receipt_row->metal_tunch, $old_metal_payment_receipt_row->metal_fine);

                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, $gold_fine, $silver_fine, '');
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->process_id, $gold_fine, $silver_fine, '');
                        } else {
                            // Purchase to Decrease
                            $this->applib->update_item_stock_decrease($old_sell_data->process_id, $old_metal_payment_receipt_row->metal_category_id, $old_metal_payment_receipt_row->metal_item_id, $old_metal_payment_receipt_row->metal_grwt, 0, $old_metal_payment_receipt_row->metal_ntwt, $old_metal_payment_receipt_row->metal_tunch, $old_metal_payment_receipt_row->metal_fine);

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
                $this->crud->delete('gold_bhav', array('sell_id' => $sell_id, 'through_lineitem' => '1'));
                $this->crud->delete('silver_bhav', array('sell_id' => $sell_id, 'through_lineitem' => '1'));

                $old_transfer_id_arr = array();
                $old_transfer_data = $this->get_transfer_lineitems($sell_id, 'update');
                if (!empty($old_transfer_data)) {
                    foreach ($old_transfer_data as $old_transfer_row) {
                        if($old_transfer_row->naam_jama == '1'){
                            // Revert : Update Selected Account Balance to Increase
                            $this->applib->update_account_balance_increase($old_sell_data->account_id, $old_transfer_row->transfer_gold, $old_transfer_row->transfer_silver, $old_transfer_row->transfer_amount);
                            // Revert : Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($old_transfer_row->transfer_account_id, $old_transfer_row->transfer_gold, $old_transfer_row->transfer_silver, $old_transfer_row->transfer_amount);
                        } else {
                            // Revert : Update Selected Account Wt to Decrease
                            $this->applib->update_account_balance_decrease($old_sell_data->account_id, $old_transfer_row->transfer_gold, $old_transfer_row->transfer_silver, $old_transfer_row->transfer_amount);
                            // Revert : Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($old_transfer_row->transfer_account_id, $old_transfer_row->transfer_gold, $old_transfer_row->transfer_silver, $old_transfer_row->transfer_amount);
                        }
                        $old_transfer_id_arr[] = $old_transfer_row->silver_id;
                    }
                }

                $update_arr['account_id'] = $post_data['account_id'];
                $update_arr['process_id'] = $post_data['process_id'];
                $update_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
                $update_arr['sell_remark'] = $post_data['sell_remark'];
                $update_arr['total_gold_fine'] = $post_data['sell_gold_fine'];
                $update_arr['total_silver_fine'] = $post_data['sell_silver_fine'];
                $update_arr['total_amount'] = $post_data['sell_amount'];
                $update_arr['total_c_amount'] = $post_data['bill_cr_c_amount'];
                $update_arr['total_r_amount'] = $post_data['bill_cr_r_amount'];
                if(isset($post_data['discount_amount'])){
                    $post_data['discount_amount'] = (!empty($post_data['discount_amount'])) ? $post_data['discount_amount'] : 0;
                    $update_arr['discount_amount'] = $post_data['discount_amount'];
                    if(PACKAGE_FOR == 'manek') {
                        $post_data['sell_amount'] = $post_data['sell_amount'] - $post_data['discount_amount'];
                    }
                }
                $update_arr['delivery_type'] = $post_data['delivery_type'];
                $update_arr['order_id'] = $order_id;
                $update_arr['updated_at'] = $this->now_time;
                $update_arr['updated_by'] = $this->logged_in_id;
                $result = $this->crud->update('sell', $update_arr, array('sell_id' => $sell_id));
                if ($result) {
                    $return['success'] = "Updated";
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'Sell/purchase Updated Successfully');
                    if(isset($post_data['saveform_clickbtn']) && $post_data['saveform_clickbtn'] == 'saveformwithprint'){
                        $this->session->set_flashdata('saveformwithprint', true);
                        $this->session->set_flashdata('saveformwithprinturl', 'sell/sell_print/'.$sell_id);
                    }

                    // Discount Amount Effects
                    if(isset($post_data['discount_amount'])){
                        // Discount Amount Decrease from Selected Account
                        $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $post_data['discount_amount']);
                        // Discount Amount Increase to Department
                        $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $post_data['discount_amount']);
                    }

//                    echo '<pre>'; print_r($line_items_data); exit;
                    if (!empty($line_items_data)) {
                        foreach ($line_items_data as $key => $lineitem) {
                            $insert_item = array();
                            $insert_item['sell_id'] = $sell_id;
                            $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                            $insert_item['type'] = $lineitem->type;
                            $stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                            if($stock_method == STOCK_METHOD_ITEM_WISE){
                                $insert_item['stock_type'] = NULL;
                                if($lineitem->type == SELL_TYPE_SELL_ID){
                                    if(isset($lineitem->stock_type)){
                                        $insert_item['stock_type'] = $lineitem->stock_type;
                                    }
                                } else if($lineitem->type == SELL_TYPE_PURCHASE_ID){
                                    $insert_item['stock_type'] = STOCK_TYPE_PURCHASE_ID;
                                } elseif ($lineitem->type == SELL_TYPE_EXCHANGE_ID){
                                    $insert_item['stock_type'] = STOCK_TYPE_EXCHANGE_ID;
                                }
                            }
                            $insert_item['category_id'] = $lineitem->category_id;
                            $insert_item['item_id'] = $lineitem->item_id;
                            $insert_item['grwt'] = $lineitem->grwt;
                            $insert_item['stone_wt'] = (isset($lineitem->stone_wt) && !empty($lineitem->stone_wt)) ? $lineitem->stone_wt : NULL;
                            $insert_item['sijat'] = (isset($lineitem->sijat) && !empty($lineitem->sijat)) ? $lineitem->sijat : NULL;
                            $insert_item['less'] = $lineitem->less;
                            $insert_item['net_wt'] = $lineitem->net_wt;
                            $insert_item['touch_id'] = $lineitem->touch_id;
                            $insert_item['wastage_labour'] = (isset($lineitem->wastage_labour) && !empty($lineitem->wastage_labour)) ? $lineitem->wastage_labour : NULL;
                            $insert_item['wastage_labour_value'] = (isset($lineitem->wastage_labour_value) && !empty($lineitem->wastage_labour_value)) ? $lineitem->wastage_labour_value : NULL;
                            $insert_item['labour_amount'] = (isset($lineitem->labour_amount) && !empty($lineitem->labour_amount)) ? $lineitem->labour_amount : 0;
                            $insert_item['fine'] = (isset($lineitem->fine_gold_silver) && !empty($lineitem->fine_gold_silver)) ? $lineitem->fine_gold_silver : 0;
                            $insert_item['gold_silver_rate'] = (isset($lineitem->gold_silver_rate) && !empty($lineitem->gold_silver_rate)) ? $lineitem->gold_silver_rate : 0;
                            $insert_item['gold_silver_amount'] = (isset($lineitem->gold_silver_amount) && !empty($lineitem->gold_silver_amount)) ? $lineitem->gold_silver_amount : 0;
                            $insert_item['stone_qty'] = (isset($lineitem->stone_qty) && !empty($lineitem->stone_qty)) ? $lineitem->stone_qty : 0;
                            $insert_item['stone_rs'] = (isset($lineitem->stone_rs) && !empty($lineitem->stone_rs)) ? $lineitem->stone_rs : 0;
                            $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                            $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                            $insert_item['charges_amt'] = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                            $insert_item['charges_amt'] = $insert_item['labour_amount'] + $insert_item['stone_rs'] + $insert_item['charges_amt'];
                            $insert_item['spi_pcs'] = (isset($lineitem->spi_pcs) && !empty($lineitem->spi_pcs)) ? $lineitem->spi_pcs : 0;
                            $insert_item['spi_rate'] = (isset($lineitem->spi_rate) && !empty($lineitem->spi_rate)) ? $lineitem->spi_rate : 0;
                            $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                            $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                            $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
                            $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                            $insert_item['image'] = $lineitem->image;
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
                            if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){ } else {
                                $insert_item['purchase_sell_item_id'] = $sell_item_id;
                            }

                            // Delete lineitems charges details
                            $this->crud->delete('sell_item_charges_details', array('sell_id' => $sell_id, 'sell_item_id' => $sell_item_id));
                            if(isset($lineitem->sell_item_charges_details) && !empty($lineitem->sell_item_charges_details)){
                                $sell_item_charges_details = json_decode($lineitem->sell_item_charges_details);
                                foreach ($sell_item_charges_details as $less_ad_detail){
                                    $insert_less_ad_detail = array();
                                    $insert_less_ad_detail['sell_id'] = $sell_id;
                                    $insert_less_ad_detail['sell_item_id'] = $sell_item_id;
                                    $insert_less_ad_detail['sell_item_charges_details_ad_id'] = $less_ad_detail->sell_item_charges_details_ad_id;
                                    $insert_less_ad_detail['sell_item_charges_details_net_wt'] = $less_ad_detail->sell_item_charges_details_net_wt;
                                    $insert_less_ad_detail['sell_item_charges_details_per_gram'] = $less_ad_detail->sell_item_charges_details_per_gram;
                                    $insert_less_ad_detail['sell_item_charges_details_ad_amount'] = $less_ad_detail->sell_item_charges_details_ad_amount;
                                    if(isset($less_ad_detail->sell_item_charges_details_remark)){
                                        $insert_less_ad_detail['sell_item_charges_details_remark'] = $less_ad_detail->sell_item_charges_details_remark;
                                    }
                                    $insert_less_ad_detail['created_at'] = $this->now_time;
                                    $insert_less_ad_detail['created_by'] = $this->logged_in_id;
                                    $insert_less_ad_detail['updated_at'] = $this->now_time;
                                    $insert_less_ad_detail['updated_by'] = $this->logged_in_id;
                                    $result = $this->crud->insert('sell_item_charges_details', $insert_less_ad_detail);
                                }
                            }

                            $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $gold_fine = $lineitem->fine;
                                $silver_fine = '';
                            } else {
                                $gold_fine = '';
                                $silver_fine = $lineitem->fine;
                            }

                            // Update Item Stock and Charges Amount
                            if ($lineitem->type == SELL_TYPE_SELL_ID) {
                                // Sell to Decrease
                                if($stock_method == STOCK_METHOD_ITEM_WISE){
                                    $this->applib->update_itemwise_item_stock_decrease($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine, $insert_item['purchase_sell_item_id'], $insert_item['stock_type']);
                                } else {
                                    $this->applib->update_item_stock_decrease($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine);
                                }

                                // Update Selected Account balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, $insert_item['charges_amt']);
                                // Update Department balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                                // Update MF Loss balance to Decrease
                                $this->applib->update_account_balance_decrease(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $insert_item['charges_amt']);
                            } else {
                                // Purchase to Increase
                                if($stock_method == STOCK_METHOD_ITEM_WISE){
                                    $this->applib->update_itemwise_item_stock_increase($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine, $insert_item['purchase_sell_item_id'], $insert_item['stock_type']);
                                } else {
                                    $this->applib->update_item_stock_increase($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine);
                                }

                                // Update Selected Account balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, $insert_item['charges_amt']);
                                // Update Department balance to Increase
                                $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                                // Update MF Loss balance to Increase
                                $this->applib->update_account_balance_increase(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $insert_item['charges_amt']);
                            }

                            // Gold / Silver Bhav Entry
                            if(!empty($insert_item['gold_silver_rate'])){
                                if ($lineitem->type == SELL_TYPE_SELL_ID) {
                                    $sale_purchase_type = '1';
                                } else {
                                    $sale_purchase_type = '2';
                                }
                                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                    $insert_gold = array();
                                    $insert_gold['sell_id'] = $sell_id;
                                    $insert_gold['gold_sale_purchase'] = $sale_purchase_type;
                                    $insert_gold['gold_weight'] = $insert_item['fine'];
                                    $insert_gold['gold_rate'] = $insert_item['gold_silver_rate'];
                                    $insert_gold['gold_value'] = $insert_item['gold_silver_amount'];
                                    $insert_gold['gold_cr_effect'] = '1';
                                    $insert_gold['c_amt'] = 0;
                                    $insert_gold['r_amt'] = 0;
                                    $insert_gold['gold_narration'] = 'Sell/Purchase Lineitem';
                                    $insert_gold['through_lineitem'] = '1';
                                    $insert_gold['created_at'] = $this->now_time;
                                    $insert_gold['created_by'] = $this->logged_in_id;
                                    $insert_gold['updated_at'] = $this->now_time;
                                    $insert_gold['updated_by'] = $this->logged_in_id;
                                    $result = $this->crud->insert('gold_bhav', $insert_gold);

                                    if($sale_purchase_type == '1'){
                                        // Update Selected Account Wt to Decrease
                                        $this->applib->update_account_balance_decrease($post_data['account_id'], $insert_item['fine'], '', '');
                                        // Update Selected Account balance to Increase
                                        $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                    } else {
                                        // Update Selected Account Wt to Increase
                                        $this->applib->update_account_balance_increase($post_data['account_id'], $insert_item['fine'], '', '');
                                        // Update Selected Account balance to Decrease
                                        $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                    }
                                } else {
                                    $insert_silver = array();
                                    $insert_silver['sell_id'] = $sell_id;
                                    $insert_silver['silver_sale_purchase'] = $sale_purchase_type;
                                    $insert_silver['silver_weight'] = $insert_item['fine'];
                                    $insert_silver['silver_rate'] = $insert_item['gold_silver_rate'];
                                    $insert_silver['silver_value'] = $insert_item['gold_silver_amount'];
                                    $insert_silver['silver_cr_effect'] = '1';
                                    $insert_silver['c_amt'] = 0;
                                    $insert_silver['r_amt'] = 0;
                                    $insert_silver['silver_narration'] = 'Sell/Purchase Lineitem';
                                    $insert_silver['through_lineitem'] = '1';
                                    $insert_silver['created_at'] = $this->now_time;
                                    $insert_silver['created_by'] = $this->logged_in_id;
                                    $insert_silver['updated_at'] = $this->now_time;
                                    $insert_silver['updated_by'] = $this->logged_in_id;
                                    $result = $this->crud->insert('silver_bhav', $insert_silver);

                                    if($sale_purchase_type == '1'){
                                        // Update Selected Account Wt to Decrease
                                        $this->applib->update_account_balance_decrease($post_data['account_id'], '', $insert_item['fine'], '');
                                        // Update Selected Account balance to Increase
                                        $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                    } else {
                                        // Update Selected Account Wt to Increase
                                        $this->applib->update_account_balance_increase($post_data['account_id'], '', $insert_item['fine'], '');
                                        // Update Selected Account balance to Decrease
                                        $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                    }
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
                            $insert_pay_rec['sell_id'] = $post_data['sell_id'];
                            $insert_pay_rec['payment_receipt'] = $pay_rec->payment_receipt;
                            $insert_pay_rec['cash_cheque'] = $pay_rec->cash_cheque;
                            $insert_pay_rec['bank_id'] = $pay_rec->bank_id;
                            $insert_pay_rec['transaction_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
                            $insert_pay_rec['department_id'] = $post_data['process_id'];
                            $insert_pay_rec['account_id'] = $post_data['account_id'];
                            $insert_pay_rec['amount'] = $pay_rec->amount;
                            $insert_pay_rec['c_amt'] = (isset($pay_rec->c_amt) && !empty($pay_rec->c_amt)) ? abs($pay_rec->c_amt) : 0;
                            $insert_pay_rec['r_amt'] = (isset($pay_rec->r_amt) && !empty($pay_rec->r_amt)) ? abs($pay_rec->r_amt) : 0;
                            $insert_pay_rec['on_behalf_of'] = $post_data['process_id'];
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
                    if(!empty($metal_data)){
                        foreach ($metal_data as $metal) {
                            $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $metal->metal_item_id);
                             $insert_metal_pr = array();
                            $insert_metal_pr['sell_id'] = $post_data['sell_id'];
                            $insert_metal_pr['metal_payment_receipt'] = $metal->metal_payment_receipt;
                            $insert_metal_pr['metal_category_id'] = $category_id;
                            $insert_metal_pr['metal_item_id'] = $metal->metal_item_id;
                            $insert_metal_pr['metal_grwt'] = $metal->metal_grwt;
                            $insert_metal_pr['metal_ntwt'] = $metal->metal_grwt;
                            $insert_metal_pr['metal_tunch'] = $metal->metal_tunch;
                            $insert_metal_pr['metal_fine'] = $metal->metal_fine;
                            $insert_metal_pr['metal_narration'] = $metal->metal_narration;
                            $insert_metal_pr['total_gold_fine'] = $post_data['metal_gold_total_fine'];
                            $insert_metal_pr['total_silver_fine'] = $post_data['metal_silver_total_fine'];
                            $insert_metal_pr['total_other_fine'] = $post_data['metal_other_total_fine'];
                            $insert_metal_pr['updated_at'] = $this->now_time;
                            $insert_metal_pr['updated_by'] = $this->logged_in_id;
                            if(isset($metal->metal_pr_id) && !empty($metal->metal_pr_id)){
                                $this->crud->update('metal_payment_receipt', $insert_metal_pr, array('metal_pr_id' => $metal->metal_pr_id));
                                $old_metal_pr_id_arr = array_diff($old_metal_pr_id_arr, array($metal->metal_pr_id));
                            } else {
                                $insert_metal_pr['created_at'] = $this->now_time;
                                $insert_metal_pr['created_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('metal_payment_receipt', $insert_metal_pr);
                            }

                            $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $insert_metal_pr['metal_category_id']));
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $gold_fine = $insert_metal_pr['metal_fine'];
                                $silver_fine = '';
                            } else {
                                $gold_fine = '';
                                $silver_fine = $insert_metal_pr['metal_fine'];
                            }

                            // Update Item Stock and Account balance
                            if($insert_metal_pr['metal_payment_receipt'] == '1'){
                                // Sell to Decrease
                                $this->applib->update_item_stock_decrease($post_data['process_id'], $insert_metal_pr['metal_category_id'], $insert_metal_pr['metal_item_id'], $insert_metal_pr['metal_grwt'], 0, $insert_metal_pr['metal_ntwt'], $insert_metal_pr['metal_tunch'], $insert_metal_pr['metal_fine']);

                                // Update Selected Account balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, '');
                                // Update Department balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                            } else {
                                // Purchase to Increase
                                $this->applib->update_item_stock_increase($post_data['process_id'], $insert_metal_pr['metal_category_id'], $insert_metal_pr['metal_item_id'], $insert_metal_pr['metal_grwt'], 0, $insert_metal_pr['metal_ntwt'], $insert_metal_pr['metal_tunch'], $insert_metal_pr['metal_fine']);

                                // Update Selected Account balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, '');
                                // Update Department balance to Increase
                                $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                            }
                        }
                    }
                    // Delete Deleted Payment Receipt Data
                    if (!empty($old_metal_pr_id_arr)) {
//                        $old_metal_pr_ids = implode(',', $old_metal_pr_id_arr);
                        $this->crud->delete_where_in('metal_payment_receipt', 'metal_pr_id', $old_metal_pr_id_arr);
                    }

                    // Insert Gold bhav Data
                    if(!empty($gold_data)){
                        foreach ($gold_data as $gold){
                            $insert_gold = array();
                            $insert_gold['sell_id'] = $post_data['sell_id'];
                            $insert_gold['gold_sale_purchase'] = $gold->gold_sale_purchase;
                            $insert_gold['gold_weight'] = $gold->gold_weight;
                            $insert_gold['gold_rate'] = $gold->gold_rate;
                            $insert_gold['gold_value'] = $gold->gold_value;
                            $insert_gold['gold_cr_effect'] = isset($gold->gold_cr_effect) ? $gold->gold_cr_effect : '1';
                            $insert_gold['c_amt'] = (isset($gold->c_amt) && !empty($gold->c_amt)) ? abs($gold->c_amt) : 0;
                            $insert_gold['r_amt'] = (isset($gold->r_amt) && !empty($gold->r_amt)) ? abs($gold->r_amt) : 0;
                            $insert_gold['gold_narration'] = $gold->gold_narration;
                            $insert_gold['updated_at'] = $this->now_time;
                            $insert_gold['updated_by'] = $this->logged_in_id;
                            if(isset($gold->gold_id) && !empty($gold->gold_id)){
                                $this->crud->update('gold_bhav', $insert_gold, array('gold_id' => $gold->gold_id));
                                $old_gold_id_arr = array_diff($old_gold_id_arr, array($gold->gold_id));
                            } else {
                                $insert_gold['created_at'] = $this->now_time;
                                $insert_gold['created_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('gold_bhav', $insert_gold);
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
                            $insert_silver['sell_id'] = $post_data['sell_id'];
                            $insert_silver['silver_sale_purchase'] = $silver->silver_sale_purchase;
                            $insert_silver['silver_weight'] = $silver->silver_weight;
                            $insert_silver['silver_rate'] = $silver->silver_rate;
                            $insert_silver['silver_value'] = $silver->silver_value;
                            $insert_silver['silver_cr_effect'] = isset($silver->silver_cr_effect) ? $silver->silver_cr_effect : '1';
                            $insert_silver['c_amt'] = (isset($silver->c_amt) && !empty($silver->c_amt)) ? abs($silver->c_amt) : 0;
                            $insert_silver['r_amt'] = (isset($silver->r_amt) && !empty($silver->r_amt)) ? abs($silver->r_amt) : 0;
                            $insert_silver['silver_narration'] = $silver->silver_narration;
                            $insert_silver['created_at'] = $this->now_time;
                            $insert_silver['created_by'] = $this->logged_in_id;
                            $insert_silver['updated_at'] = $this->now_time;
                            $insert_silver['updated_by'] = $this->logged_in_id;
                            if(isset($silver->silver_id) && !empty($silver->silver_id)){
                                $this->crud->update('silver_bhav', $insert_silver, array('silver_id' => $silver->silver_id));
                                $old_silver_id_arr = array_diff($old_silver_id_arr, array($silver->silver_id));
                            } else {
                                $insert_silver['created_at'] = $this->now_time;
                                $insert_silver['created_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('silver_bhav', $insert_silver);
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

                    // Insert Transfer Data
                    if(!empty($transfer_data)){
                        foreach ($transfer_data as $transfer){
                            $insert_transfer = array();
                            $insert_transfer['sell_id'] = $post_data['sell_id'];
                            $insert_transfer['naam_jama'] = $transfer->naam_jama;
                            $insert_transfer['transfer_account_id'] = $transfer->transfer_account_id;
                            $insert_transfer['transfer_gold'] = $transfer->transfer_gold;
                            $insert_transfer['transfer_silver'] = $transfer->transfer_silver;
                            $insert_transfer['transfer_amount'] = $transfer->transfer_amount;
                            $insert_transfer['c_amt'] = (isset($transfer->c_amt) && !empty($transfer->c_amt)) ? abs($transfer->c_amt) : 0;
                            $insert_transfer['r_amt'] = (isset($transfer->r_amt) && !empty($transfer->r_amt)) ? abs($transfer->r_amt) : 0;
                            $insert_transfer['transfer_narration'] = $transfer->transfer_narration;
                            $insert_transfer['updated_at'] = $this->now_time;
                            $insert_transfer['updated_by'] = $this->logged_in_id;
    //                            echo '<pre>'; print_r($transfer->transfer_entry_id); exit;
                            if(isset($transfer->transfer_entry_id) && !empty($transfer->transfer_entry_id)){
                                $this->crud->update('transfer', $insert_transfer, array('transfer_id' => $transfer->transfer_entry_id));
                                $old_transfer_id_arr = array_diff($old_transfer_id_arr, array($transfer->transfer_entry_id));
                            } else {
                                $insert_transfer['created_at'] = $this->now_time;
                                $insert_transfer['created_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('transfer', $insert_transfer);
                            }

                            if($transfer->naam_jama == '1'){
                                // Update Selected Account Balance to Decrease
                                $this->applib->update_account_balance_decrease($post_data['account_id'], $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                                // Update Transfer Account Balance to Increase
                                $this->applib->update_account_balance_increase($transfer->transfer_account_id, $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                            } else {
                                // Update Selected Account Balance to Increase
                                $this->applib->update_account_balance_increase($post_data['account_id'], $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                                // Update Transfer Account Balance to Decrease
                                $this->applib->update_account_balance_decrease($transfer->transfer_account_id, $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                            }
                        }
                    }
                    // Delete Deleted Transfer Data
                    if (!empty($old_transfer_id_arr)) {
//                        $old_transfer_ids = implode(',', $old_transfer_id_arr);
                        $this->crud->delete_where_in('transfer', 'transfer_id', $old_transfer_id_arr);
                    }

                }
            }
        } else {
            $sell = $this->crud->get_max_number('sell', 'sell_no');
            $sell_no = 1;
            if ($sell->sell_no > 0) {
                $sell_no = $sell->sell_no + 1;
            }
            $insert_arr = array();
            $insert_arr['sell_no'] = $sell_no;
            $insert_arr['account_id'] = $post_data['account_id'];
            $insert_arr['process_id'] = $post_data['process_id'];
            $insert_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
            $insert_arr['sell_remark'] = $post_data['sell_remark'];
            $insert_arr['total_gold_fine'] = $post_data['sell_gold_fine'];
            $insert_arr['total_silver_fine'] = $post_data['sell_silver_fine'];
            $insert_arr['total_amount'] = $post_data['sell_amount'];
            $insert_arr['total_c_amount'] = $post_data['bill_cr_c_amount'];
            $insert_arr['total_r_amount'] = $post_data['bill_cr_r_amount'];
            if(isset($post_data['discount_amount'])){
                $post_data['discount_amount'] = (!empty($post_data['discount_amount'])) ? $post_data['discount_amount'] : 0;
                $insert_arr['discount_amount'] = $post_data['discount_amount'];
                if(PACKAGE_FOR == 'manek') {
                    $post_data['sell_amount'] = $post_data['sell_amount'] - $post_data['discount_amount'];
                }
            }
            $insert_arr['delivery_type'] = $post_data['delivery_type'];
            $insert_arr['order_id'] = $order_id;
            $insert_arr['entry_through'] = ENTRY_THROUGH_SELL_PURCHASE_TYPE_2;
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
                if(isset($post_data['saveform_clickbtn']) && $post_data['saveform_clickbtn'] == 'saveformwithprint'){
                    $this->session->set_flashdata('saveformwithprint', true);
                    $this->session->set_flashdata('saveformwithprinturl', 'sell/sell_print/'.$sell_id);
                }

                // Discount Amount Effects
                if(isset($post_data['discount_amount'])){
                    // Discount Amount Decrease from Selected Account
                    $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $post_data['discount_amount']);
                    // Discount Amount Increase to Department
                    $this->applib->update_account_balance_increase($post_data['process_id'], '', '', $post_data['discount_amount']);
                }

//                echo '<pre>'; print_r($line_items_data); exit;
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
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['type'] = $lineitem->type;
                        $stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($stock_method == STOCK_METHOD_ITEM_WISE){
                            $insert_item['stock_type'] = NULL;
                            if($lineitem->type == SELL_TYPE_SELL_ID){
                                if(isset($lineitem->stock_type)){
                                    $insert_item['stock_type'] = $lineitem->stock_type;
                                }
                            } else if($lineitem->type == SELL_TYPE_PURCHASE_ID){
                                $insert_item['stock_type'] = STOCK_TYPE_PURCHASE_ID;
                            } elseif ($lineitem->type == SELL_TYPE_EXCHANGE_ID){
                                $insert_item['stock_type'] = STOCK_TYPE_EXCHANGE_ID;
                            }
                        }
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['stone_wt'] = (isset($lineitem->stone_wt) && !empty($lineitem->stone_wt)) ? $lineitem->stone_wt : NULL;
                        $insert_item['sijat'] = (isset($lineitem->sijat) && !empty($lineitem->sijat)) ? $lineitem->sijat : NULL;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['touch_id'] = $lineitem->touch_id;
                        $insert_item['wastage_labour'] = (isset($lineitem->wastage_labour) && !empty($lineitem->wastage_labour)) ? $lineitem->wastage_labour : NULL;
                        $insert_item['wastage_labour_value'] = (isset($lineitem->wastage_labour_value) && !empty($lineitem->wastage_labour_value)) ? $lineitem->wastage_labour_value : NULL;
                        $insert_item['labour_amount'] = (isset($lineitem->labour_amount) && !empty($lineitem->labour_amount)) ? $lineitem->labour_amount : 0;
                        $insert_item['fine'] = (isset($lineitem->fine_gold_silver) && !empty($lineitem->fine_gold_silver)) ? $lineitem->fine_gold_silver : 0;
                        $insert_item['gold_silver_rate'] = (isset($lineitem->gold_silver_rate) && !empty($lineitem->gold_silver_rate)) ? $lineitem->gold_silver_rate : 0;
                        $insert_item['gold_silver_amount'] = (isset($lineitem->gold_silver_amount) && !empty($lineitem->gold_silver_amount)) ? $lineitem->gold_silver_amount : 0;
                        $insert_item['stone_qty'] = (isset($lineitem->stone_qty) && !empty($lineitem->stone_qty)) ? $lineitem->stone_qty : 0;
                        $insert_item['stone_rs'] = (isset($lineitem->stone_rs) && !empty($lineitem->stone_rs)) ? $lineitem->stone_rs : 0;
                        $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                        $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        $insert_item['charges_amt'] = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $insert_item['charges_amt'] = $insert_item['labour_amount'] + $insert_item['stone_rs'] + $insert_item['charges_amt'];
                        $insert_item['spi_pcs'] = (isset($lineitem->spi_pcs) && !empty($lineitem->spi_pcs)) ? $lineitem->spi_pcs : 0;
                        $insert_item['spi_rate'] = (isset($lineitem->spi_rate) && !empty($lineitem->spi_rate)) ? $lineitem->spi_rate : 0;
                        $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                        $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
                        $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                        $insert_item['image'] = $lineitem->image;
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
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){ } else {
                            $insert_item['purchase_sell_item_id'] = $sell_item_id;
                        }

                        if(isset($lineitem->sell_item_charges_details) && !empty($lineitem->sell_item_charges_details)){
                            $sell_item_charges_details = json_decode($lineitem->sell_item_charges_details);
                            foreach ($sell_item_charges_details as $less_ad_detail){
                                $insert_less_ad_detail = array();
                                $insert_less_ad_detail['sell_id'] = $sell_id;
                                $insert_less_ad_detail['sell_item_id'] = $sell_item_id;
                                $insert_less_ad_detail['sell_item_charges_details_ad_id'] = $less_ad_detail->sell_item_charges_details_ad_id;
                                $insert_less_ad_detail['sell_item_charges_details_net_wt'] = $less_ad_detail->sell_item_charges_details_net_wt;
                                $insert_less_ad_detail['sell_item_charges_details_per_gram'] = $less_ad_detail->sell_item_charges_details_per_gram;
                                $insert_less_ad_detail['sell_item_charges_details_ad_amount'] = $less_ad_detail->sell_item_charges_details_ad_amount;
                                if(isset($less_ad_detail->sell_item_charges_details_remark)){
                                    $insert_less_ad_detail['sell_item_charges_details_remark'] = $less_ad_detail->sell_item_charges_details_remark;
                                }
                                $insert_less_ad_detail['created_at'] = $this->now_time;
                                $insert_less_ad_detail['created_by'] = $this->logged_in_id;
                                $insert_less_ad_detail['updated_at'] = $this->now_time;
                                $insert_less_ad_detail['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('sell_item_charges_details', $insert_less_ad_detail);
                            }
                        }

                        $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $lineitem->fine;
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $lineitem->fine;
                        }

                        // Update Item Stock and Charges Amount
                        if ($lineitem->type == SELL_TYPE_SELL_ID) {
                            // Sell to Decrease
                            if($stock_method == STOCK_METHOD_ITEM_WISE){
                                $this->applib->update_itemwise_item_stock_decrease($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine, $insert_item['purchase_sell_item_id'], $insert_item['stock_type']);
                            } else {
                                $this->applib->update_item_stock_decrease($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine);
                            }

                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, $insert_item['charges_amt']);
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                            // Update MF Loss balance to Decrease
                            $this->applib->update_account_balance_decrease(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $insert_item['charges_amt']);
                        } else {
                            // Purchase to Increase
                            if($stock_method == STOCK_METHOD_ITEM_WISE){
                                $this->applib->update_itemwise_item_stock_increase($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine, $insert_item['purchase_sell_item_id'], $insert_item['stock_type']);
                            } else {
                                $this->applib->update_item_stock_increase($post_data['process_id'], $lineitem->category_id, $lineitem->item_id, $lineitem->grwt, $lineitem->less, $lineitem->net_wt, $lineitem->touch_id, $lineitem->fine);
                            }

                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, $insert_item['charges_amt']);
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                            // Update MF Loss balance to Increase
                            $this->applib->update_account_balance_increase(MF_LOSS_EXPENSE_ACCOUNT_ID, '', '', $insert_item['charges_amt']);
                        }

                        // Gold / Silver Bhav Entry
                        if(!empty($insert_item['gold_silver_rate'])){
                            if ($lineitem->type == SELL_TYPE_SELL_ID) {
                                $sale_purchase_type = '1';
                            } else {
                                $sale_purchase_type = '2';
                            }
                            $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $insert_gold = array();
                                $insert_gold['sell_id'] = $sell_id;
                                $insert_gold['gold_sale_purchase'] = $sale_purchase_type;
                                $insert_gold['gold_weight'] = $insert_item['fine'];
                                $insert_gold['gold_rate'] = $insert_item['gold_silver_rate'];
                                $insert_gold['gold_value'] = $insert_item['gold_silver_amount'];
                                $insert_gold['gold_cr_effect'] = '1';
                                $insert_gold['c_amt'] = 0;
                                $insert_gold['r_amt'] = 0;
                                $insert_gold['gold_narration'] = 'Sell/Purchase Lineitem';
                                $insert_gold['through_lineitem'] = '1';
                                $insert_gold['created_at'] = $this->now_time;
                                $insert_gold['created_by'] = $this->logged_in_id;
                                $insert_gold['updated_at'] = $this->now_time;
                                $insert_gold['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('gold_bhav', $insert_gold);

                                if($sale_purchase_type == '1'){
                                    // Update Selected Account Wt to Decrease
                                    $this->applib->update_account_balance_decrease($post_data['account_id'], $insert_item['fine'], '', '');
                                    // Update Selected Account balance to Increase
                                    $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                } else {
                                    // Update Selected Account Wt to Increase
                                    $this->applib->update_account_balance_increase($post_data['account_id'], $insert_item['fine'], '', '');
                                    // Update Selected Account balance to Decrease
                                    $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                }
                            } else {
                                $insert_silver = array();
                                $insert_silver['sell_id'] = $sell_id;
                                $insert_silver['silver_sale_purchase'] = $sale_purchase_type;
                                $insert_silver['silver_weight'] = $insert_item['fine'];
                                $insert_silver['silver_rate'] = $insert_item['gold_silver_rate'];
                                $insert_silver['silver_value'] = $insert_item['gold_silver_amount'];
                                $insert_silver['silver_cr_effect'] = '1';
                                $insert_silver['c_amt'] = 0;
                                $insert_silver['r_amt'] = 0;
                                $insert_silver['silver_narration'] = 'Sell/Purchase Lineitem';
                                $insert_silver['through_lineitem'] = '1';
                                $insert_silver['created_at'] = $this->now_time;
                                $insert_silver['created_by'] = $this->logged_in_id;
                                $insert_silver['updated_at'] = $this->now_time;
                                $insert_silver['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('silver_bhav', $insert_silver);

                                if($sale_purchase_type == '1'){
                                    // Update Selected Account Wt to Decrease
                                    $this->applib->update_account_balance_decrease($post_data['account_id'], '', $insert_item['fine'], '');
                                    // Update Selected Account balance to Increase
                                    $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                } else {
                                    // Update Selected Account Wt to Increase
                                    $this->applib->update_account_balance_increase($post_data['account_id'], '', $insert_item['fine'], '');
                                    // Update Selected Account balance to Decrease
                                    $this->applib->update_account_balance_decrease($post_data['account_id'], '', '', $insert_item['gold_silver_amount']);
                                }
                            }
                        }

                    }
                }

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
                        $insert_pay_rec['c_amt'] = (isset($pay_rec->c_amt) && !empty($pay_rec->c_amt)) ? abs($pay_rec->c_amt) : 0;
                        $insert_pay_rec['r_amt'] = (isset($pay_rec->r_amt) && !empty($pay_rec->r_amt)) ? abs($pay_rec->r_amt) : 0;
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
                        $insert_metal_pr['total_gold_fine'] = $post_data['metal_gold_total_fine'];
                        $insert_metal_pr['total_silver_fine'] = $post_data['metal_silver_total_fine'];
                        $insert_metal_pr['total_other_fine'] = $post_data['metal_other_total_fine'];
                        $insert_metal_pr['created_at'] = $this->now_time;
                        $insert_metal_pr['created_by'] = $this->logged_in_id;
                        $insert_metal_pr['updated_at'] = $this->now_time;
                        $insert_metal_pr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('metal_payment_receipt', $insert_metal_pr);

                        $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $insert_metal_pr['metal_category_id']));
                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                            $gold_fine = $insert_metal_pr['metal_fine'];
                            $silver_fine = '';
                        } else {
                            $gold_fine = '';
                            $silver_fine = $insert_metal_pr['metal_fine'];
                        }

                        // Update Item Stock and Account balance
                        if($insert_metal_pr['metal_payment_receipt'] == '1'){
                            // Sell to Decrease
                            $this->applib->update_item_stock_decrease($post_data['process_id'], $insert_metal_pr['metal_category_id'], $insert_metal_pr['metal_item_id'], $insert_metal_pr['metal_grwt'], 0, $insert_metal_pr['metal_ntwt'], $insert_metal_pr['metal_tunch'], $insert_metal_pr['metal_fine']);

                            // Update Selected Account balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], $gold_fine, $silver_fine, '');
                            // Update Department balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['process_id'], $gold_fine, $silver_fine, '');
                        } else {
                            // Purchase to Increase
                            $this->applib->update_item_stock_increase($post_data['process_id'], $insert_metal_pr['metal_category_id'], $insert_metal_pr['metal_item_id'], $insert_metal_pr['metal_grwt'], 0, $insert_metal_pr['metal_ntwt'], $insert_metal_pr['metal_tunch'], $insert_metal_pr['metal_fine']);

                            // Update Selected Account balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], $gold_fine, $silver_fine, '');
                            // Update Department balance to Increase
                            $this->applib->update_account_balance_increase($post_data['process_id'], $gold_fine, $silver_fine, '');
                        }
                    }
                }

                if(!empty($gold_data)){
                    foreach ($gold_data as $gold){
                        $insert_gold = array();
                        $insert_gold['sell_id'] = $sell_id;
                        $insert_gold['gold_sale_purchase'] = $gold->gold_sale_purchase;
                        $insert_gold['gold_weight'] = $gold->gold_weight;
                        $insert_gold['gold_rate'] = $gold->gold_rate;
                        $insert_gold['gold_value'] = $gold->gold_value;
                        $insert_gold['gold_cr_effect'] = isset($gold->gold_cr_effect) ? $gold->gold_cr_effect : '1';
                        $insert_gold['c_amt'] = (isset($gold->c_amt) && !empty($gold->c_amt)) ? abs($gold->c_amt) : 0;
                        $insert_gold['r_amt'] = (isset($gold->r_amt) && !empty($gold->r_amt)) ? abs($gold->r_amt) : 0;
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

                if(!empty($silver_data)){
                    foreach ($silver_data as $silver){
                        $insert_silver = array();
                        $insert_silver['sell_id'] = $sell_id;
                        $insert_silver['silver_sale_purchase'] = $silver->silver_sale_purchase;
                        $insert_silver['silver_weight'] = $silver->silver_weight;
                        $insert_silver['silver_rate'] = $silver->silver_rate;
                        $insert_silver['silver_value'] = $silver->silver_value;
                        $insert_silver['silver_cr_effect'] = isset($silver->silver_cr_effect) ? $silver->silver_cr_effect : '1';
                        $insert_silver['c_amt'] = (isset($silver->c_amt) && !empty($silver->c_amt)) ? abs($silver->c_amt) : 0;
                        $insert_silver['r_amt'] = (isset($silver->r_amt) && !empty($silver->r_amt)) ? abs($silver->r_amt) : 0;
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

                if(!empty($transfer_data)){
                    foreach ($transfer_data as $transfer){
                        $insert_transfer = array();
                        $insert_transfer['sell_id'] = $sell_id;
                        $insert_transfer['naam_jama'] = $transfer->naam_jama;
                        $insert_transfer['transfer_account_id'] = $transfer->transfer_account_id;
                        $insert_transfer['transfer_gold'] = $transfer->transfer_gold;
                        $insert_transfer['transfer_silver'] = $transfer->transfer_silver;
                        $insert_transfer['transfer_amount'] = $transfer->transfer_amount;
                        $insert_transfer['c_amt'] = (isset($transfer->c_amt) && !empty($transfer->c_amt)) ? abs($transfer->c_amt) : 0;
                        $insert_transfer['r_amt'] = (isset($transfer->r_amt) && !empty($transfer->r_amt)) ? abs($transfer->r_amt) : 0;
                        $insert_transfer['transfer_narration'] = $transfer->transfer_narration;
                        $insert_transfer['created_at'] = $this->now_time;
                        $insert_transfer['created_by'] = $this->logged_in_id;
                        $insert_transfer['updated_at'] = $this->now_time;
                        $insert_transfer['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('transfer', $insert_transfer);

                        if($transfer->naam_jama == '1'){
                            // Update Selected Account Balance to Decrease
                            $this->applib->update_account_balance_decrease($post_data['account_id'], $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                            // Update Transfer Account Balance to Increase
                            $this->applib->update_account_balance_increase($transfer->transfer_account_id, $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                        } else {
                            // Update Selected Account Balance to Increase
                            $this->applib->update_account_balance_increase($post_data['account_id'], $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
                            // Update Transfer Account Balance to Decrease
                            $this->applib->update_account_balance_decrease($transfer->transfer_account_id, $transfer->transfer_gold, $transfer->transfer_silver, $transfer->transfer_amount);
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
            if(in_array($param1,array("sell","purchase")) && $this->sell_purchase_difference) {
                $sell_purchase = $param1;
                $view = '';
                if($sell_purchase == "sell") {
                    $page_label = "Sell";
                    $entry_page_url = base_url("sell_purchase_type_2/add/sell");
                } else {
                    $page_label = "Purchase";
                    $entry_page_url = base_url("sell_purchase_type_2/add/purchase");
                }
            } else {
                $sell_purchase = "sell_purchase";
                $view = $param1;
                $page_label = "Sell/Purchase";
                $entry_page_url = base_url("sell_purchase_type_2/add");
            }

            $data = array();

            $data['page_label'] = $page_label;
            $data['entry_page_url'] = $entry_page_url;
            $data['sell_purchase'] = $sell_purchase;

            if(!empty($view)){
                $data['view_not'] = $view;
            }
            set_page('sell/sell_purchase_type_2_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function sp_datatable() {
        $post_data = $this->input->post();
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell s';
        $config['select'] = 's.*,p.account_name,p.account_mobile,a.account_name AS process_name,IF(s.delivery_type = 1, "Delivered" ,"Not Delivered") AS delivery_type';
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.process_id', 'join_type' => 'left');
        $config['column_search'] = array('s.sell_no','p.account_name','a.account_name','DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 'sell_remark', 'IF(s.delivery_type = 1, "Delivered" ,"Not Delivered")');
        $config['column_order'] = array(null, 'p.account_name', 'a.account_name', 's.sell_no', 's.sell_date', 'sell_remark', 's.delivery_type');

        $config['wheres'][] = array('column_name' => 's.entry_through', 'column_value' => ENTRY_THROUGH_SELL_PURCHASE_TYPE_2);
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

        if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
            $config['custom_where'] .= ' AND s.sell_id IN(SELECT sell_id FROM sell_items WHERE type IN('.SELL_TYPE_SELL_ID.'))';
        }

        if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
            $config['custom_where'] .= ' AND s.sell_id IN(SELECT sell_id FROM sell_items WHERE type IN('.SELL_TYPE_PURCHASE_ID.','.SELL_TYPE_EXCHANGE_ID.') AND sell_id=s.sell_id)';
        }

        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date'])){
                $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
            }
        }
        if(!empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if(isset($post_data['delivery_type']) && !empty($post_data['delivery_type'])){
            $config['wheres'][] = array('column_name' => 's.delivery_type', 'column_value' => $post_data['delivery_type']);
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
                    if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';                        
                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';

                    } else {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }

                    if($role_delete){
                        $action .= '<a href="javascript:void(0);" class="delete_sell" data-sell_id="'.$sell->sell_id.'" data-href="' . base_url('sell/delete_sell/' . $sell->sell_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else {
                    if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';

                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';

                    } else {
                        $action .= '<a href="' . base_url("sell_purchase_type_2/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }

                }
            }
            $action .= '<a href="' . base_url("sell/sell_print/" . $sell->sell_id) . '" target="_blank" title="Sell/Purchase Print" alt="Sell/Purchase Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            if(PACKAGE_FOR != 'manek') {
                $action .= '<a href="' . base_url("sell/sell_print/" . $sell->sell_id . '/isimage/') . '" target="_blank" title="Sell/Purchase Print as a Image" alt="Sell/Purchase Print as a Image"><span class="glyphicon glyphicon-picture" style="color : #419bf4">&nbsp</a>';
            }
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
            $row[] = $sell->process_name;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell->sell_no . '</a>';
            $row[] = (!empty(strtotime($sell->sell_date))) ? date('d-m-Y', strtotime($sell->sell_date)) : '';
            $row[] = $sell->sell_remark;
            $row[] = $sell->delivery_type;
            if(isset($post_data['check']) && $post_data['check'] == '1'){
                $row[] = '<a href="javascript:void(0);" class="update_delivery_type" data-href="' . base_url('sell/update_delivery_type/' . $sell->sell_id) . '"><input type="checkbox"  class="icheckbox_flat-blue check_delivery" value="'.$sell->sell_id.'"></a>';
            }
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

    function sp_item_datatable() {
        $post_data = $this->input->post();
        if(isset($post_data['from_date']) && !empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(isset($post_data['to_date']) && !empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell_items si';
        $config['select'] = 'si.*,p.account_name,a.account_name AS process_name,st.type_name,im.item_name,c.category_name,s.sell_no,s.sell_date';
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = si.sell_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.process_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_type st', 'join_by' => 'st.sell_type_id = si.type', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = si.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= si.category_id', 'join_type' => 'left');
        $config['column_search'] = array('p.account_name','a.account_name','s.sell_no','DATE_FORMAT(s.sell_date,"%d-%m-%Y")','si.sell_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'si.grwt', 'si.less', 'si.net_wt','si.wstg','si.fine');
        $config['column_order'] = array(null, 'p.account_name','a.account_name','s.sell_no','s.sell_date','si.sell_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'si.grwt', 'si.less','si.net_wt','si.wstg','si.fine');
        $config['order'] = array('si.sell_item_no' => 'desc');
        if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {
            $config['wheres'][] = array('column_name' => 'si.sell_id', 'column_value' => $post_data['sell_id']);
        }
        if(isset($post_data['from_date']) && !empty($post_data['from_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if(isset($post_data['to_date']) && !empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if(isset($post_data['delivery_type']) && !empty($post_data['delivery_type'])){
            $config['wheres'][] = array('column_name' => 's.delivery_type', 'column_value' => $post_data['delivery_type']);
        }
        if(isset($post_data['item_id']) && !empty($post_data['item_id'])){
            $config['wheres'][] = array('column_name' => 'si.item_id', 'column_value' => $post_data['item_id']);
        }
        if(isset($post_data['sell_type']) && !empty($post_data['sell_type'])){
            $config['wheres'][] = array('column_name' => 'si.type', 'column_value' => $post_data['sell_type']);
        }
        if(isset($post_data['account_id']) && !empty($post_data['account_id'])){
            $config['wheres'][] = array('column_name' => 's.account_id', 'column_value' => $post_data['account_id']);
        }
        if(isset($post_data['wastage']) && !empty($post_data['wastage'])){
            $config['wheres'][] = array('column_name' => 'si.wastage_change_approve', 'column_value' => $post_data['wastage']);
        }
        if(isset($post_data['touch_id']) && !empty($post_data['touch_id'])){
            $config['wheres'][] = array('column_name' => 'si.touch_id', 'column_value' => $post_data['touch_id']);
        }

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

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        $role_delete = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
        foreach ($list as $sell_detail) {
            $row = array();
            $action = '';
            if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {} else{
                if($role_edit){
                    $action .= '<a href="' . base_url("sell/add/" . $sell_detail->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($sell_detail->wastage_change_approve != '0_0') {
                    $checked = $sell_detail->wastage_change_approve == '1_1' ? 'checked' : '';
                    $action .= '<input type="checkbox" class="wastage_change_approve" id="' . $sell_detail->sell_item_id . '" ' . $checked . '>';
                }
                $row[] = $action;
                $row[] = $sell_detail->account_name;
                $row[] = $sell_detail->process_name;
                $row[] = $sell_detail->sell_no;
                $row[] = (!empty(strtotime($sell_detail->sell_date))) ? date('d-m-Y', strtotime($sell_detail->sell_date)) : '';
            }
            $row[] = $sell_detail->sell_item_no;
            $row[] = $sell_detail->type_name;
            $row[] = $sell_detail->category_name;
            $row[] = $sell_detail->item_name;
            $row[] = number_format($sell_detail->grwt, 3, '.', '');
            $row[] = number_format($sell_detail->stone_wt, 3, '.', '');
            $row[] = number_format($sell_detail->sijat, 3, '.', '');
            $row[] = number_format($sell_detail->less, 3, '.', '');
            $row[] = number_format($sell_detail->net_wt, 3, '.', '');
            $row[] = $sell_detail->touch_id;
            $wastage_labour = '';
            if($sell_detail->wastage_labour == 1) { $wastage_labour = 'Wastage'; } else if($sell_detail->wastage_labour == 2) { $wastage_labour = 'Labour'; }
            $row[] = $wastage_labour;
            $row[] = number_format($sell_detail->wastage_labour_value, 2, '.', '');
            $row[] = number_format($sell_detail->labour_amount, 2, '.', '');
            $row[] = number_format($sell_detail->fine, 3, '.', '');
            $row[] = number_format($sell_detail->gold_silver_rate, 2, '.', '');
            $row[] = number_format($sell_detail->gold_silver_amount, 2, '.', '');
            $row[] = $sell_detail->stone_qty;
            $row[] = number_format($sell_detail->stone_rs, 2, '.', '');
            $sell_detail->charges_amt = (isset($sell_detail->charges_amt) && !empty($sell_detail->charges_amt)) ? $sell_detail->charges_amt : 0;
            $sell_detail->charges_amt = $sell_detail->charges_amt - $sell_detail->labour_amount - $sell_detail->stone_rs;
            $row[] = number_format($sell_detail->charges_amt, 2, '.', '');
            $row[] = number_format($sell_detail->amount, 2, '.', '');
            $row[] = $sell_detail->li_narration;
            if(!empty($sell_detail->order_lot_item_id) && strpos($sell_detail->image, 'uploads/order_item_photo') !== false){
                $img_src = base_url('/'). $sell_detail->image;
            } else {
                $img_src = base_url('/uploads/sell_item_photo/'). $sell_detail->image;
            }
            $row[] = '<a href="javascript:void(0);" class="image_model" data-img_src="' .$img_src .'" ><img src="' . $img_src . '" alt="" height="42" width="42"></a> ';
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

    function delete_sell($id = '', $have_rfid = '') {
        $where_array = array('sell_id' => $id);
        $sell = $this->crud->get_row_by_id('sell', $where_array);
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $return = array();
        if(!empty($sell)){
            $found = false;
            $sell_items = $this->crud->get_row_by_id('sell_items', $where_array);
            if(!empty($sell_items)){
                foreach($sell_items as $sell_item){

                    // Update rfid_number rfid_used status and rfid_created_grwt
                    if(isset($sell_item->item_stock_rfid_id) && !empty($sell_item->item_stock_rfid_id)){
                        $check_item_stock_rfid = $this->crud->get_row_by_id('item_stock_rfid', array('real_rfid' => $sell_item->rfid_number, 'rfid_used' => '0'));
                        if(empty($check_item_stock_rfid)){
                            if($have_rfid == '1'){
                                $this->crud->update('item_stock_rfid', array('rfid_used' => '0', 'to_relation_id' => NULL, 'to_module' => NULL), array('item_stock_rfid_id' => $sell_item->item_stock_rfid_id));
                                $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $sell_item->item_stock_rfid_id);
                                $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $item_stock_rfid->item_stock_id));
                                $new_rfid_created_grwt = $old_rfid_created_grwt + $item_stock_rfid->rfid_grwt;
                                $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $item_stock_rfid->item_stock_id));
                            } else {
                                $this->crud->update('item_stock_rfid', array('to_relation_id' => NULL, 'to_module' => RFID_RELATION_MODULE_SELL_DELETE), array('item_stock_rfid_id' => $sell_item->item_stock_rfid_id));
                            }
                        } else {
                            $this->crud->update('item_stock_rfid', array('to_relation_id' => NULL, 'to_module' => RFID_RELATION_MODULE_SELL_DELETE), array('item_stock_rfid_id' => $sell_item->item_stock_rfid_id));
                        }
                    }

                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $sell_item->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                        $item_sells = $this->crud->getFromSQL('SELECT * FROM sell_items WHERE purchase_sell_item_id = '.$sell_item->sell_item_id.' AND stock_type IN (1,2)');
    //                    $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $sell_item->sell_item_id));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->getFromSQL('SELECT * FROM stock_transfer_detail WHERE purchase_sell_item_id = '.$sell_item->sell_item_id.' AND stock_type IN (1,2)');
    //                    $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $sell_item->sell_item_id));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->getFromSQL('SELECT * FROM issue_receive_details WHERE purchase_sell_item_id = '.$sell_item->sell_item_id.' AND stock_type IN (1,2)');
    //                    $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $sell_item->sell_item_id));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->getFromSQL('SELECT * FROM manu_hand_made_details WHERE purchase_sell_item_id = '.$sell_item->sell_item_id.' AND stock_type IN (1,2)');
                        if(!empty($item_sells)){
                            $found = true;
                        }
                    } else if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_sell_or_not($sell[0]->process_id, $sell_item->category_id, $sell_item->item_id, $sell_item->touch_id);
                            if(!empty($used_lineitem_ids) && in_array($sell_item->sell_item_id, $used_lineitem_ids)){
                                $found = true;
                            }
                        }
                    }

                    // Update Item Stock
                    if ($lineitem->type == SELL_TYPE_SELL_ID) {
                        // Sell to Increase
                        if($stock_method == STOCK_METHOD_ITEM_WISE){
                            $this->applib->update_itemwise_item_stock_increase($sell[0]->process_id, $sell_item->category_id, $sell_item->item_id, $sell_item->grwt, $sell_item->less, $sell_item->net_wt, $sell_item->touch_id, $sell_item->fine, $sell_item->stock_type, $sell_item->stock_type);
                        } else {
                            $this->applib->update_item_stock_increase($sell[0]->process_id, $sell_item->category_id, $sell_item->item_id, $sell_item->grwt, $sell_item->less, $sell_item->net_wt, $sell_item->touch_id, $sell_item->fine);
                        }
                    } else {
                        // Purchase to Decrease
                        if($stock_method == STOCK_METHOD_ITEM_WISE){
                            $this->applib->update_itemwise_item_stock_decrease($sell[0]->process_id, $sell_item->category_id, $sell_item->item_id, $sell_item->grwt, $sell_item->less, $sell_item->net_wt, $sell_item->touch_id, $sell_item->fine, $sell_item->stock_type, $sell_item->stock_type);
                        } else {
                            $this->applib->update_item_stock_decrease($sell[0]->process_id, $sell_item->category_id, $sell_item->item_id, $sell_item->grwt, $sell_item->less, $sell_item->net_wt, $sell_item->touch_id, $sell_item->fine);
                        }
                    }
                }
            }
            $found_metal = false;
            if($without_purchase_sell_allow == '1'){
                $metal_items = $this->crud->get_row_by_id('metal_payment_receipt', $where_array);
                if(!empty($metal_items)){
                    foreach($metal_items as $metal_item){
                        $used_lineitem_ids = $this->check_default_item_metal_or_not($sell[0]->process_id, $metal_item->metal_category_id, $metal_item->metal_item_id, $metal_item->metal_tunch);
                        if(!empty($used_lineitem_ids) && in_array($metal_item->metal_pr_id, $used_lineitem_ids)){
                            $found_metal = true;
                        }
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else if($found_metal == true){
                $return['error'] = 'Error';
            } else {
                // Delete Sub tables data
                $this->crud->delete('silver_bhav', $where_array);
                $this->crud->delete('gold_bhav', $where_array);
                $this->crud->delete('sell_items', $where_array);
                // Delete sell item charges details
                $this->crud->delete('sell_item_charges_details', $where_array);
                // Delete Main table data
                $this->crud->delete('sell', $where_array);
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
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
                $sell_item->fine_gold_silver = $sell_item->fine;
                $sell_item->category_id = $sell_item->category_id;
                $sell_item->item_id = $sell_item->item_id;
                $sell_item->sell_item_id = $sell_item->sell_item_id;
                $sell_item->item_stock_rfid_id = (isset($sell_item->item_stock_rfid_id) && !empty($sell_item->item_stock_rfid_id)) ? $sell_item->item_stock_rfid_id : NULL;
                $sell_item->rfid_number = (isset($sell_item->rfid_number) && !empty($sell_item->rfid_number)) ? $sell_item->rfid_number : NULL;
                $sell_item->labour_amount = (isset($sell_item->labour_amount) && !empty($sell_item->labour_amount)) ? $sell_item->labour_amount : 0;
                $sell_item->stone_rs = (isset($sell_item->stone_rs) && !empty($sell_item->stone_rs)) ? $sell_item->stone_rs : 0;
                $sell_item->charges_amt = (isset($sell_item->charges_amt) && !empty($sell_item->charges_amt)) ? $sell_item->charges_amt : 0;
                $sell_item->charges_amt = $sell_item->charges_amt - $sell_item->labour_amount - $sell_item->stone_rs;
                $sell_item->spi_pcs = (isset($sell_item->spi_pcs) && !empty($sell_item->spi_pcs)) ? $sell_item->spi_pcs : 0;
                $sell_item->spi_rate = (isset($sell_item->spi_rate) && !empty($sell_item->spi_rate)) ? $sell_item->spi_rate : 0;
                $sell_item->amount = (isset($sell_item->amount) && !empty($sell_item->amount)) ? $sell_item->amount : 0;
                $sell_item->total_amount = $sell_item->amount;
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
                $metal_lineitems->metal_category_id = $metal->metal_category_id;
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
                $gold_bhav_lineitems->through_lineitem = $gold->through_lineitem;
                $gold_bhav_lineitems->gold_id = $gold->gold_id;
                $gold_lineitems[] = $gold_bhav_lineitems;
            }
        }
        $data['gold_bhav_data'] = $gold_lineitems;
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
                $silver_bhav_lineitems->through_lineitem = $silver->through_lineitem;
                $silver_bhav_lineitems->silver_id = $silver->silver_id;
                $silver_lineitems[] = $silver_bhav_lineitems;
            }
        }
        $data['silver_bhav_data'] = $silver_lineitems;
        if ($for == 'edit') {
            return json_encode($silver_lineitems);
        } else if ($for == 'update') {
            return $silver_lineitems;
        } else {
            echo json_encode($data);
        }
    }

    function get_transfer_lineitems($sell_id = '', $for = ''){
        $data = array();
        $transfer_lineitems = array();
        $transfer_results = $this->crud->get_row_by_id('transfer', array('sell_id' => $sell_id));
        if(!empty($transfer_results)){
            foreach ($transfer_results as $transfer){
                $transfer_lineitems = new \stdClass();
                $transfer_lineitems->naam_jama = $transfer->naam_jama;
                $transfer_lineitems->party_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $transfer->transfer_account_id));
                $transfer_lineitems->transfer_account_id = $transfer->transfer_account_id;
                $transfer_lineitems->transfer_gold = $transfer->transfer_gold;
                $transfer_lineitems->transfer_silver = $transfer->transfer_silver;
                $transfer_lineitems->transfer_amount = $transfer->transfer_amount;
                $transfer_lineitems->transfer_narration = $transfer->transfer_narration;
                $transfer_lineitems->transfer_id = $transfer->transfer_id;
                $transfer_lineitems->transfer_entry_id = $transfer->transfer_id;
                $transfer_lineitems[] = $transfer_lineitems;
            }
        }
        $data['transfer_data'] = $transfer_lineitems;
        if ($for == 'edit') {
            return json_encode($transfer_lineitems);
        } else if ($for == 'update') {
            return $transfer_lineitems;
        } else {
            echo json_encode($data);
        }
    }

}
