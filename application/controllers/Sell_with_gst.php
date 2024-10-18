<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sell_with_gst extends CI_Controller {

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

    function add($sell_id = '') {
        
        $data = array();
        $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
        $data['gold_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_min'));
        $data['gold_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_max'));
        $data['silver_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_max'));
        $data['silver_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_min'));
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        
        if(isset($sell_id) && !empty($sell_id)){
            //----------------- Sell Data -------------------
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit")) {
                $sell_data = $this->crud->get_row_by_id('sell_with_gst', array('sell_id' => $sell_id));
                $sell_data = $sell_data[0];
                $sell_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$sell_data->created_by));
                if($sell_data->created_by != $sell_data->updated_by){
                    $sell_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $sell_data->updated_by));
                }else{
                    $sell_data->updated_by_name = $sell_data->created_by_name;
                }
                $sell_data->department_id = $sell_data ->process_id;
                $data['sell_data'] = $sell_data;

                //----------------- Sell Itemms -------------------
                $sell_data_items = $this->crud->get_row_by_id('sell_items_with_gst', array('sell_id' => $sell_id));
                if(!empty($sell_data_items)){
                    foreach($sell_data_items as $sell_data_item_row){
                        $sell_items_with_gst = new \stdClass();
                        $sell_items_with_gst->sell_item_delete = 'allow';
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                            $total_sell_sell_grwt = $this->crud->get_total_sell_sell_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id);
                            $total_sell_grwt = $total_transfer_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_sell_sell_grwt;
                            $used_lineitem_ids = array();
                            $sell_items_with_gst->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                                $receive_items = $this->crud->get_receive_items_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id, '0');
                                $sell_purchase_items = $this->crud->get_sell_purchase_items_grwt($sell_data->process_id, $sell_data_item_row->category_id, $sell_data_item_row->item_id);
                                $purchase_delete_array = array_merge($stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $sell_purchase_items);

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
                                            $used_lineitem_ids[] = $purchase_item->sell_item_id;
                                            if($sell_data_item_row->sell_item_id == $purchase_item->sell_item_id){
                                                $sell_items_with_gst->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $purchase_item->grwt;
                                            }
                                        }
                                        $first_check_purchase_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($purchase_item->type == 'O P'){
                                            $used_lineitem_ids[] = $purchase_item->sell_item_id;
                                            if($sell_data_item_row->sell_item_id == $purchase_item->sell_item_id){
                                                $sell_items_with_gst->total_grwt_sell = $purchase_item->grwt;
                                            }
                                        }

                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($sell_data_item_row->sell_item_id, $used_lineitem_ids)){
                                $sell_items_with_gst->sell_item_delete = 'not_allow';
                            }
                        }
                        
                        $sell_items_with_gst->type = $sell_data_item_row->type;
                        $type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $sell_data_item_row->type));
                        $sell_items_with_gst->type_name = $type_name[0];
                        $sell_items_with_gst->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $sell_data_item_row->category_id));
                        $sell_items_with_gst->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_data_item_row->category_id));
                        $sell_items_with_gst->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $sell_data_item_row->item_id));
                        $sell_items_with_gst->grwt = $sell_data_item_row->grwt;
                        $sell_items_with_gst->spi_rate = $sell_data_item_row->spi_rate;
                        $sell_items_with_gst->rate_per_1_gram = $sell_data_item_row->rate_per_1_gram;
                        $sell_items_with_gst->gst_rate = $sell_data_item_row->gst_rate;
                        $sell_items_with_gst->tax = $sell_data_item_row->tax;
                        $sell_items_with_gst->amount = $sell_data_item_row->amount;
                        $sell_items_with_gst->category_id = $sell_data_item_row->category_id;
                        $sell_items_with_gst->item_id = $sell_data_item_row->item_id;
                        $sell_items_with_gst->hsn_code = $sell_data_item_row->hsn_code;
                        $sell_items_with_gst->sell_item_id = $sell_data_item_row->sell_item_id;
                        $sell_lineitems[] = json_encode($sell_items_with_gst);
                    }
                    $data['sell_items_with_gst_data'] = implode(',', $sell_lineitems);
                }

//                echo '<pre>'; print_r($data); exit;
                set_page('sell_with_gst/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            //----------------- Data -------------------
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add")) {
                set_page('sell_with_gst/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function sell_with_gst_list() { 
        if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) {
            $data = array();
            set_page('sell_with_gst/sell_with_gst_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function save_sell() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
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
        $post_data['sell_remark'] = isset($post_data['sell_remark']) && !empty($post_data['sell_remark']) ? $post_data['sell_remark'] : NULL;
        $post_data['ship_to_name'] = isset($post_data['ship_to_name']) && !empty($post_data['ship_to_name']) ? $post_data['ship_to_name'] : NULL;
        $post_data['ship_to_address'] = isset($post_data['ship_to_address']) && !empty($post_data['ship_to_address']) ? $post_data['ship_to_address'] : NULL;
        if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {
            $sell_id = $post_data['sell_id'];
            $old_sell_data = $this->crud->get_data_row_by_id('sell_with_gst', 'sell_id', $sell_id);
            if(!empty($old_sell_data)){

                // Revert : Total Amount Effects
                if(!empty($old_sell_data->total_amount)){
                    // Total Amount Decrease to Selected Account
                    $this->applib->update_account_balance_decrease($old_sell_data->account_id, '', '', $old_sell_data->total_amount);
                    // Total Amount Increase from Department
                    $this->applib->update_account_balance_increase($old_sell_data->process_id, '', '', $old_sell_data->total_amount);
                }
                $old_sell_item_id_arr = array();
                $old_sell_purchase_items = $this->crud->get_row_by_id('sell_items_with_gst', array('sell_id' => $sell_id));
                if(!empty($old_sell_purchase_items)){
                    foreach($old_sell_purchase_items as $old_sell_purchase_item){
                        $old_sell_item_id_arr[] = $old_sell_purchase_item->sell_item_id;
                    }
                }
            }

            $update_arr['account_id'] = $post_data['account_id'];
            $update_arr['process_id'] = $post_data['department_id'];
            $update_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
            $update_arr['sell_remark'] = $post_data['sell_remark'];
            $update_arr['ship_to_name'] = $post_data['ship_to_name'];
            $update_arr['ship_to_address'] = $post_data['ship_to_address'];
//            $update_arr['total_grwt'] = $post_data['sell_grwt'];
            $update_arr['total_amount'] = $post_data['sell_amount'];
            $update_arr['tcs_per'] = isset($post_data['tcs_per']) && !empty($post_data['tcs_per']) ? $post_data['tcs_per'] : 0;
            $update_arr['tcs_amount'] = isset($post_data['tcs_amount']) && !empty($post_data['tcs_amount']) ? $post_data['tcs_amount'] : 0;
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('sell_with_gst', $update_arr, array('sell_id' => $post_data['sell_id']));
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Sell Entry Updated Successfully');

                // Total Amount Effects
                if(!empty($update_arr['total_amount'])){
                    // Total Amount Increase to Selected Account
                    $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $update_arr['total_amount']);
                    // Total Amount Decrease from Department
                    $this->applib->update_account_balance_decrease($post_data['department_id'], '', '', $update_arr['total_amount']);
                }

                if(!empty($line_items_data)){
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        
                        $insert_item['sell_id'] = $post_data['sell_id'];;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['hsn_code'] = isset($lineitem->hsn_code) && !empty($lineitem->hsn_code) ? $lineitem->hsn_code : NULL;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['spi_rate'] = $lineitem->spi_rate;
                        $insert_item['rate_per_1_gram'] = $lineitem->rate_per_1_gram;
                        $insert_item['gst_rate'] = isset($lineitem->gst_rate) && !empty($lineitem->gst_rate) ? $lineitem->gst_rate : 0;
                        $insert_item['tax'] = isset($lineitem->tax) && !empty($lineitem->tax) ? $lineitem->tax : 0;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->sell_item_id) && !empty($lineitem->sell_item_id)){
                            $this->db->where('sell_item_id', $lineitem->sell_item_id);
                            $this->db->update('sell_items_with_gst', $insert_item);
                            $old_sell_item_id_arr = array_diff($old_sell_item_id_arr, array($lineitem->sell_item_id));
                        } else {
                            $lot = $this->crud->get_max_number('sell_items_with_gst', 'sell_item_no');
                            $sell_item_no = 1;
                            if ($lot->sell_item_no > 0) {
                                $sell_item_no = $lot->sell_item_no + 1;
                            }
                            $insert_item['sell_item_no'] = $sell_item_no;
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('sell_items_with_gst', $insert_item);
                            $sell_item_id = $this->db->insert_id();
                        }
                    }
                }
                // Delete Deleted lineitems
                if (!empty($old_sell_item_id_arr)) {
                    $this->crud->delete_where_in('sell_items_with_gst', 'sell_item_id', $old_sell_item_id_arr);
                }
            }
        } else {
            $sell = $this->crud->get_max_number('sell_with_gst', 'sell_no');
            $sell_no = 1;
            if ($sell->sell_no > 0) {
                $sell_no = $sell->sell_no + 1;
            }
            $insert_arr = array();
            $insert_arr['sell_no'] = $sell_no;            
            $insert_arr['account_id'] = $post_data['account_id'];            
            $insert_arr['process_id'] = $post_data['department_id'];            
            $insert_arr['sell_date'] = date('Y-m-d', strtotime($post_data['sell_date']));
            $insert_arr['sell_remark'] = $post_data['sell_remark'];
            $insert_arr['ship_to_name'] = $post_data['ship_to_name'];
            $insert_arr['ship_to_address'] = $post_data['ship_to_address'];
//            $insert_arr['total_grwt'] = $post_data['sell_grwt'];
            $insert_arr['total_amount'] = $post_data['sell_amount'];
            $insert_arr['tcs_per'] = isset($post_data['tcs_per']) && !empty($post_data['tcs_per']) ? $post_data['tcs_per'] : 0;
            $insert_arr['tcs_amount'] = isset($post_data['tcs_amount']) && !empty($post_data['tcs_amount']) ? $post_data['tcs_amount'] : 0;
            $insert_arr['entry_through'] = ENTRY_THROUGH_SELL_PURCHASE_ENTRY_WITH_GST;
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('sell_with_gst', $insert_arr);
            $sell_id = $this->db->insert_id();
//            echo $this->db->last_query();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Sell Entry Added Successfully');

                // Total Amount Effects
                if(!empty($insert_arr['total_amount'])){
                    // Total Amount Increase to Selected Account
                    $this->applib->update_account_balance_increase($post_data['account_id'], '', '', $insert_arr['total_amount']);
                    // Total Amount Decrease from Department
                    $this->applib->update_account_balance_decrease($post_data['department_id'], '', '', $insert_arr['total_amount']);
                }

//                echo '<pre>'; print_r($line_items_data); exit;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $lot = $this->crud->get_max_number('sell_items_with_gst', 'sell_item_no');
                        $sell_item_no = 1;
                        if ($lot->sell_item_no > 0) {
                            $sell_item_no = $lot->sell_item_no + 1;
                        }
                        $insert_item = array();
                        $insert_item['sell_id'] = $sell_id;
                        $insert_item['sell_item_no'] = $sell_item_no;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['hsn_code'] = isset($lineitem->hsn_code) && !empty($lineitem->hsn_code) ? $lineitem->hsn_code : NULL;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['spi_rate'] = $lineitem->spi_rate;
                        $insert_item['rate_per_1_gram'] = $lineitem->rate_per_1_gram;
                        $insert_item['gst_rate'] = isset($lineitem->gst_rate) && !empty($lineitem->gst_rate) ? $lineitem->gst_rate : 0;
                        $insert_item['tax'] = isset($lineitem->tax) && !empty($lineitem->tax) ? $lineitem->tax : 0;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('sell_items_with_gst', $insert_item);
                        $sell_item_id = $this->db->insert_id();
                    }
                }
                
            }
        }
        print json_encode($return);
        exit;
    }
        
    function sell_datatable() {
        $post_data = $this->input->post();
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell_with_gst s';
        $config['select'] = 's.*,p.account_name,a.account_name AS department_name';
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.process_id', 'join_type' => 'left');
        $config['column_search'] = array('s.sell_no','p.account_name','a.account_name','DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 's.sell_remark');
        $config['column_order'] = array(null, 'p.account_name', 'a.account_name', 's.sell_no', 's.sell_date', 's.sell_remark');

        $config['wheres'][] = array('column_name' => 's.entry_through', 'column_value' => ENTRY_THROUGH_SELL_PURCHASE_ENTRY_WITH_GST);
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

        if(!empty($post_data['from_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if(!empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('s.sell_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
        foreach ($list as $sell) {
            $row = array();
            $action = '';
            if($sell->account_id != ADJUST_EXPENSE_ACCOUNT_ID){
                if($role_edit){
                    $action .= '<a href="' . base_url("sell_with_gst/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($role_delete){
                    $action .= '<a href="javascript:void(0);" class="delete_sell" data-href="' . base_url('sell_with_gst/delete_sell_with_gst/' . $sell->sell_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            }
            $action .= '<a href="' . base_url("sell_with_gst/sell_with_gst_print/" . $sell->sell_id) . '" target="_blank" title="Sell/Purchase Print" alt="Sell/Purchase Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell->account_name . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell->department_name . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell->sell_no . '</a>';
            $sell_date = (!empty(strtotime($sell->sell_date))) ? date('d-m-Y', strtotime($sell->sell_date)) : '';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell_date  . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->sell_id . '" >' . $sell->sell_remark . '</a>';
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
    
    function sell_item_datatable() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $config['table'] = 'sell_items_with_gst si';
        $config['select'] = 'si.*,st.type_name,im.item_name,c.category_name';
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = si.sell_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_type st', 'join_by' => 'st.sell_type_id = si.type', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = si.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= si.category_id', 'join_type' => 'left');
        $config['column_order'] = array('si.sell_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'si.grwt','si.spi_rate', 'si.rate_per_1_gram', 'si.amount');
        $config['column_search'] = array('si.sell_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'si.grwt','si.spi_rate', 'si.rate_per_1_gram', 'si.amount');
        $config['order'] = array('si.sell_item_no' => 'desc');
        if (isset($post_data['sell_id']) && !empty($post_data['sell_id'])) {
            $config['wheres'][] = array('column_name' => 'si.sell_id', 'column_value' => $post_data['sell_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query(); exit;
        $data = array();
        $role_delete = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit");
        foreach ($list as $sell_detail) {
            $row = array();
            $action = '';
            $row[] = $sell_detail->sell_item_no;
            $row[] = $sell_detail->type_name;
            $row[] = $sell_detail->category_name;
            $row[] = $sell_detail->item_name;
            $row[] = number_format($sell_detail->grwt, 3, '.', '');
            $row[] = $sell_detail->spi_rate;
            $row[] = $sell_detail->rate_per_1_gram;
            $row[] = $sell_detail->amount;
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
    
    function delete_sell_with_gst($sell_id = '') {
        $where_array = array('sell_id' => $sell_id);
        $sell_data = $this->crud->get_data_row_by_id('sell_with_gst', 'sell_id', $sell_id);
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $return = array();
        if(!empty($sell_data)){
            $found = false;
            $sell_items_with_gst = $this->crud->get_row_by_id('sell_items_with_gst', $where_array);
            if(!empty($sell_items_with_gst)){
                foreach($sell_items_with_gst as $sell_item){
                    if($without_purchase_sell_allow == '1'){
                        $used_lineitem_ids = $this->check_default_item_sell_or_not($sell_data->process_id, $sell_item->category_id, $sell_item->item_id, '0');
                        if(!empty($used_lineitem_ids) && in_array($sell_item->sell_item_id, $used_lineitem_ids)){
                            $found = true;
                        }
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {
                // Revert : Total Amount Effects
                if(!empty($sell_data->total_amount)){
                    // Total Amount Decrease to Selected Account
                    $this->applib->update_account_balance_decrease($sell_data->account_id, '', '', $sell_data->total_amount);
                    // Total Amount Increase from Department
                    $this->applib->update_account_balance_increase($sell_data->process_id, '', '', $sell_data->total_amount);
                }
                $this->crud->delete('sell_items_with_gst', $where_array);
                $this->crud->delete('sell_with_gst', $where_array);
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
        $total_sell_sell_grwt = $this->crud->get_total_sell_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_transfer_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_sell_sell_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $sell_purchase_items = $this->crud->get_sell_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $sell_purchase_items);
            
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
                        $used_lineitem_ids[] = $purchase_item->sell_item_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'O P'){
                        $used_lineitem_ids[] = $purchase_item->sell_item_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }

    function sell_with_gst_print($sell_id = '') {
        $data = array();
        if(!empty($sell_id)){
            $sell_data = $this->crud->get_data_row_by_id('sell_with_gst', 'sell_id', $sell_id);
            $sell_data->old_amount = 0;
            $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_data->account_id);
            if(!empty($account_data)){
                $sell_data->old_amount = number_format($account_data->amount, 0, '.', '');
            }
            $sell_data->total_amount = (!empty($sell_data->total_amount)) ? $sell_data->total_amount : 0;
            $sell_data->old_amount = $sell_data->old_amount - $sell_data->total_amount;
            $data['sell_data'] = $sell_data;
            $data['account_data'] = $account_data;
            $data['account_state_name'] = $this->crud->get_column_value_by_id('state', 'state_name', array('state_id' => $data['account_data']->account_state));
            $data['account_city_name'] = $this->crud->get_column_value_by_id('city', 'city_name', array('city_id' => $data['account_data']->account_city));

            //----------------- Sell Items -------------------
            $sell_lineitems = array();
            $sell_data_items = $this->crud->get_row_by_id('sell_items_with_gst', array('sell_id' => $sell_id));
            if(!empty($sell_data_items)){
                foreach($sell_data_items as $sell_data_item){
                    $sell_items_with_gst = new \stdClass();
                    $sell_items_with_gst->sell_item_delete = 'allow';
                    $sell_items_with_gst->tunch_textbox = (isset($sell_data_item->tunch_textbox) && $sell_data_item->tunch_textbox == '1') ? '1' : '0';
                    $sell_items_with_gst->type = $sell_data_item->type;
                    $sell_items_with_gst->type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $sell_data_item->type));
                    $sell_items_with_gst->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $sell_data_item->category_id));
                    $sell_items_with_gst->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_data_item->category_id));
                    $sell_items_with_gst->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $sell_data_item->item_id));
                    if($sell_items_with_gst->type != SELL_TYPE_SELL_ID){
                        $sell_items_with_gst->grwt = ZERO_VALUE - $sell_data_item->grwt;
                        $sell_items_with_gst->spi_rate = ZERO_VALUE - $sell_data_item->spi_rate;
                        $sell_items_with_gst->tax = ZERO_VALUE - $sell_data_item->tax;
                        $sell_items_with_gst->amount = ZERO_VALUE - $sell_data_item->amount;
                    } else {
                        $sell_items_with_gst->grwt = $sell_data_item->grwt;
                        $sell_items_with_gst->spi_rate = $sell_data_item->spi_rate;
                        $sell_items_with_gst->tax = $sell_data_item->tax;
                        $sell_items_with_gst->amount = $sell_data_item->amount;
                    }
                    $sell_items_with_gst->grwt = number_format($sell_items_with_gst->grwt, 3, '.', '');
                    $sell_items_with_gst->category_id = $sell_data_item->category_id;
                    $sell_items_with_gst->item_id = $sell_data_item->item_id;
                    $sell_items_with_gst->hsn_code = $sell_data_item->hsn_code;
                    $sell_items_with_gst->gst_rate = $sell_data_item->gst_rate;
                    $sell_items_with_gst->sell_item_id = $sell_data_item->sell_item_id;
                    $sell_lineitems[] = $sell_items_with_gst;
                }
                $data['sell_items_with_gst'] = $sell_lineitems;
            }
//            print_r($data['sell_items_with_gst']); exit;

        }
        $data['company_details'] = $this->crud->get_data_row_by_id('company_details', 'company_id', '1');
        $data['state_name'] = $this->crud->get_column_value_by_id('state', 'state_name', array('state_id' => $data['company_details']->company_state_id));
        $data['city_name'] = $this->crud->get_column_value_by_id('city', 'city_name', array('city_id' => $data['company_details']->company_city_id));
        $data['ask_discount_in_sell_purchase'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'ask_discount_in_sell_purchase'));
//        print_r($data); exit;
        $html = $this->load->view('sell_with_gst/sell_with_gst_print', $data, true);
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
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
   
    function get_category_data($category_id){
        if(!empty($category_id)){
            $category_data = $this->crud->get_data_row_by_id('category', 'category_id', $category_id);
        }
        print json_encode($category_data);
        exit;
    }
}
