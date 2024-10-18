<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manufacture extends CI_Controller {

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
        $this->user_type = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'];
        $this->department_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['default_department_id'];
        $this->now_time = date('Y-m-d H:i:s');
        $this->zero_value = 0;
    }

    function issue_receive($ir_id = '') {
        $data = array();
        $issue_receive_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');   
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $data['worker_gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'worker_gold_rate'));
        $data['manufacture_lott_complete_in'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'manufacture_lott_complete_in'));
        if (!empty($ir_id)) {
            if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"edit")) {
                $issue_receive_data = $this->crud->get_row_by_id('issue_receive', array('ir_id' => $ir_id));
                $issue_receive_details = $this->crud->get_row_by_id('issue_receive_details', array('ir_id' => $ir_id));
                $issue_receive_data = $issue_receive_data[0];

                $issue_receive_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$issue_receive_data->created_by));
                if($issue_receive_data->created_by != $issue_receive_data->updated_by){
                    $issue_receive_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $issue_receive_data->updated_by));
                }else{
                    $issue_receive_data->updated_by_name = $issue_receive_data->created_by_name;
                }
                
                $data['ir_data'] = $issue_receive_data;
                $lineitems = array();
                foreach($issue_receive_details as $detail){
                    
                    $issue_receive_detail->ir_item_delete = 'allow';
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){

                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $issue_receive_detail->ir_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_transfer)){
                            $issue_receive_detail->ir_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_issue_receive)){
                            $issue_receive_detail->ir_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_manu_hand_made)){
                            $manu_hand_made_detail->ir_item_delete = 'not_allow';
                        }
//                        echo '<pre>'. $this->db->last_query(); exit;
//                        echo '<pre>'; print_r($issue_receive_detail->ir_item_delete); exit;
                        $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$detail->ird_id.'" AND s.process_id = "'.$issue_receive_data->department_id.'"');
                        $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="'.$detail->ird_id.'" AND s.from_department = "'.$issue_receive_data->department_id.'"');
                        $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="'.$detail->ird_id.'" AND ir.department_id = "'.$issue_receive_data->department_id.'"');
                        $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$detail->ird_id.'" AND mhm.department_id = "'.$issue_receive_data->department_id.'"');
                        $issue_receive_detail->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt;
                    } else {
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $issue_receive_detail->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($issue_receive_data->department_id, $detail->category_id, $detail->item_id);
                                $receive_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
    //                            echo '<pre>'; print_r($receive_delete_array); exit;
                                uasort($receive_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
    //                            print_r($receive_delete_array); exit;
                                $purchase_grwt = 0;
                                $first_check_receive_grwt = 0;

                                foreach ($receive_delete_array as $receive_item){
                                    $purchase_grwt = $purchase_grwt + $receive_item->grwt;
                                    if($purchase_grwt >= $total_sell_grwt && $first_check_receive_grwt == 0){
                                        if($receive_item->type == 'R'){
                                            $used_lineitem_ids[] = $receive_item->ird_id;
                                            if($detail->ird_id == $receive_item->ird_id){
                                                $issue_receive_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $receive_item->grwt;
                                            }
                                        }
                                        $first_check_receive_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($receive_item->type == 'R'){
                                            $used_lineitem_ids[] = $receive_item->ird_id;
                                            if($detail->ird_id == $receive_item->ird_id){
                                                $issue_receive_detail->total_grwt_sell = $receive_item->grwt;
                                            }
                                        }

                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($detail->ird_id, $used_lineitem_ids)){
                                $issue_receive_detail->ir_item_delete = 'not_allow';
                            }
                        }
                    }
//                    
                    $issue_receive_detail->type_name = $detail->type_id == 1 ? 'Issue' : 'Receive';
                    $issue_receive_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $issue_receive_detail->purity = $detail->tunch;
                    $issue_receive_detail->weight = $detail->weight;
                    $issue_receive_detail->grwt = $detail->weight;
                    $issue_receive_detail->less = $detail->less;
                    $issue_receive_detail->net_wt = $detail->net_wt;
                    $issue_receive_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $issue_receive_detail->fine = $detail->fine;
                    $issue_receive_detail->wastage = !empty($detail->wastage) ? $detail->wastage : 0;
                    $issue_receive_detail->calculated_wastage = !empty($detail->calculated_wastage) ? $detail->calculated_wastage : 0;
                    $issue_receive_detail->ird_date = $detail->ird_date ? date('d-m-Y', strtotime($detail->ird_date)) : '';
                    $issue_receive_detail->ird_remark = $detail->ird_remark;
                    $issue_receive_detail->ird_id = $detail->ird_id;
                    $issue_receive_detail->type_id = $detail->type_id;
                    $issue_receive_detail->item_id = $detail->item_id;
                    $issue_receive_detail->touch_id = $detail->tunch;
                    $issue_receive_detail->wstg = '0';
                    $issue_receive_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $issue_receive_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $issue_receive_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($issue_receive_detail);
                }
                $data['issue_receive_detail'] = implode(',', $lineitems);
                set_page('manufacture/issue_receive', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"add")) {
                $lineitems = array();
                set_page('manufacture/issue_receive', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_issue_receive() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($post_data); exit;
//        echo '<pre>'; print_r($line_items_data); exit;

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

        $post_data['worker_id'] = isset($post_data['worker_id']) && !empty($post_data['worker_id']) ? $post_data['worker_id'] : null;
        $post_data['department_id'] = isset($post_data['department_id']) && !empty($post_data['department_id']) ? $post_data['department_id'] : null;
        $post_data['ir_date'] = isset($post_data['ir_date']) && !empty($post_data['ir_date']) ? date('Y-m-d', strtotime($post_data['ir_date'])) : null;
        $post_data['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
        $post_data['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
        $post_data['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
        $post_data['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');

        $manufacture_lott_complete_in = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'manufacture_lott_complete_in'));
        $issue_receive_karigar_wastage = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'issue_receive_karigar_wastage'));

        if (isset($post_data['ir_id']) && !empty($post_data['ir_id'])) {
            
            // Increase fine in Account And Department
            $this->update_account_and_department_balance_on_update($post_data['ir_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_manufacture_update($post_data['ir_id']);
            $post_data['department_id'] = $this->crud->get_column_value_by_id('issue_receive','department_id',array('ir_id' => $post_data['ir_id']));
            $update_arr = array();
            $update_arr['worker_id'] = $post_data['worker_id'];
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['ir_date'] = $post_data['ir_date'];           
//            $update_arr['reference_no'] = $post_data['reference_no'];
            if(isset($post_data['lott_complete'])){
                $update_arr['lott_complete'] = $post_data['lott_complete'];
            }
            if($issue_receive_karigar_wastage == '1'){
                $update_arr['ir_diffrence'] = $post_data['ir_diffrence'];
            } else {
                $update_arr['ir_diffrence'] = '0';
            }
            if(isset($post_data['worker_gold_rate'])){
                $update_arr['worker_gold_rate'] = $post_data['worker_gold_rate'];
            }
            $update_arr['ir_remark']= $post_data['ir_remark'];
            $update_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $update_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $update_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $update_arr['total_receive_fine']= $post_data['total_receive_fine'];
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['ir_id'] = $post_data['ir_id'];

            $result = $this->crud->update('issue_receive', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Issue/Receive Updated Successfully');
                
                
                if(isset($post_data['deleted_lineitem_id'])){
                    $this->db->where_in('ird_id', $post_data['deleted_lineitem_id']);
                    $this->db->delete('issue_receive_details');
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $update_item = array();
                        $update_item['ir_id'] = $post_data['ir_id'];
                        $update_item['type_id'] = $lineitem->type_id;
                        $update_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['tunch'] = $lineitem->purity;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['less'] = $lineitem->less;
                        $update_item['net_wt'] = $lineitem->net_wt;
                        $update_item['actual_tunch'] = $lineitem->actual_tunch;
                        $update_item['fine'] = $lineitem->fine;
                        $update_item['wastage'] = (isset($lineitem->wastage) && !empty($lineitem->wastage)) ? $lineitem->wastage : 0;
                        $update_item['calculated_wastage'] = (isset($lineitem->calculated_wastage) && !empty($lineitem->calculated_wastage)) ? $lineitem->calculated_wastage : 0;
                        $update_item['ird_date'] = !empty($lineitem->ird_date) ? date('Y-m-d', strtotime($lineitem->ird_date)) : null;
                        $update_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $update_item['ird_remark'] = $lineitem->ird_remark;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $update_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID && $line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $update_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $update_item['updated_at'] = $this->now_time;
                        $update_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->ird_id) && !empty($lineitem->ird_id)){
                            $this->db->where('ird_id', $lineitem->ird_id);
                            $this->db->update('issue_receive_details', $update_item);
                        } else {
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('issue_receive_details', $update_item);
                            $last_inserted_id = $this->db->insert_id();
                            $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                        }
                        $line_items_data[$key]->category_id = $update_item['category_id'];
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }
                    
                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if(!empty($account)){
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }
                    
                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data,$post_data['department_id']);
                }
            }
        } else {

            $insert_arr = array();
            $reference = $this->crud->get_max_number('issue_receive', 'reference_no');
            $reference_no = 1;
            if ($reference->reference_no > 0) {
                $reference_no = $reference->reference_no + 1;
            }
            $insert_arr['worker_id'] = $post_data['worker_id'];
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['ir_date'] = $post_data['ir_date'];
            $insert_arr['reference_no'] = $reference_no;
            if(isset($post_data['lott_complete'])){
                $insert_arr['lott_complete'] = $post_data['lott_complete'];
            }
            if($issue_receive_karigar_wastage == '1'){
                $insert_arr['ir_diffrence'] = $post_data['ir_diffrence'];
            } else {
                $insert_arr['ir_diffrence'] = '0';
            }
            if(isset($post_data['worker_gold_rate'])){
                $insert_arr['worker_gold_rate'] = $post_data['worker_gold_rate'];
            }
            $insert_arr['ir_remark'] = $post_data['ir_remark'];
            $insert_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $insert_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $insert_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $insert_arr['total_receive_fine']= $post_data['total_receive_fine'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('issue_receive', $insert_arr);
            $ir_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $return['ir_id'] = $ir_id;
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Issue/Receive Added Successfully');
                
                /*if($manufacture_lott_complete_in == '3'){
                    if(!empty($post_data['ir_diffrence']) && isset($post_data['worker_gold_rate']) && !empty($post_data['worker_gold_rate'])){
                        $ir_diffrence_amount = $post_data['ir_diffrence'] * $post_data['worker_gold_rate'] / 10;
                        $ir_diffrence_amount = number_format((float) $ir_diffrence_amount, '2', '.', '');
                        if($post_data['lott_complete'] == 1 && $ir_diffrence_amount != 0){
                            $this->add_journal_for_ir_diffrence_amount($ir_id, $post_data['department_id'], $post_data['worker_id'], $ir_diffrence_amount);
                        }
                    }
                }*/

                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['ir_id'] = $ir_id;
                        $insert_item['type_id'] = $lineitem->type_id;
                        $insert_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['tunch'] = $lineitem->purity;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['actual_tunch'] = $lineitem->actual_tunch;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['wastage'] = (isset($lineitem->wastage) && !empty($lineitem->wastage)) ? $lineitem->wastage : 0;
                        $insert_item['calculated_wastage'] = (isset($lineitem->calculated_wastage) && !empty($lineitem->calculated_wastage)) ? $lineitem->calculated_wastage : 0;
                        $insert_item['ird_date'] = !empty($lineitem->ird_date) ? date('Y-m-d', strtotime($lineitem->ird_date)) : null;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['ird_remark'] = $lineitem->ird_remark;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->touch_id = $lineitem->purity;
                        $line_items_data[$key]->category_id = $insert_item['category_id'];
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID && $line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $this->crud->insert('issue_receive_details', $insert_item);
                        $last_inserted_item_id = $this->db->insert_id();
                        $line_items_data[$key]->purchase_item_id = $last_inserted_item_id;
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }
                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if(!empty($account)){
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }
                    
                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data, $post_data['department_id']);
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function revert_journal_for_ir_diffrence_amount($ir_id, $worker_id) {
        $journal_id = $this->crud->get_column_value_by_id('issue_receive', 'journal_id', array('ir_id' => $ir_id));
        if (!empty($journal_id)) {
            $journal_id = $this->crud->get_column_value_by_id('journal_details', 'journal_id', array('journal_id' => $journal_id));
            if ($journal_id) {

                $worker_journal_details = $this->crud->get_row_by_id('journal_details', array('journal_id' => $journal_id, 'account_id' => $worker_id));
                if (!empty($worker_journal_details)) {
                    $worker_journal_details = $worker_journal_details[0];
                    if ($worker_journal_details->type == 1) {

                        // Update Worker Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
                        $account_amount = $account_amount - $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

                        // Update MF Loss Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        $account_amount = $account_amount + $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    } else {

                        // Update Worker Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
                        $account_amount = $account_amount + $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

                        // Update MF Loss Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        $account_amount = $account_amount - $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    }
                    $this->crud->delete('journal_details', array('journal_id' => $journal_id));
                }
                $this->crud->delete('journal', array('journal_id' => $journal_id));
            }
        }
    }

    function add_journal_for_ir_diffrence_amount($ir_id, $department_id, $worker_id, $ir_diffrence_amount) {
        // Add Journal
        $journal_array = array(
            'department_id' => $department_id,
            'journal_date' => date('Y-m-d'),
            'relation_id' => $ir_id,
            'is_module' => IR_TO_JOURNAL_ID,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $result = $this->crud->insert('journal', $journal_array);
        $journal_id = $this->db->insert_id();
        $this->crud->update('issue_receive', array('journal_id' => $journal_id), array('ir_id' => $ir_id));
        $reference_no = $this->crud->get_column_value_by_id('issue_receive', 'reference_no', array('ir_id' => $ir_id));
        if ($ir_diffrence_amount > 0) {  // If Diff Positive : Debit in Worker and Credit in MF Loss
            // Add Worker Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 2,
                'account_id' => $worker_id,
                'amount' => $ir_diffrence_amount,
                'narration' => 'Issue/Receive : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update Worker Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
            $account_amount = $account_amount - $ir_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

            // Add MF Loss Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 1,
                'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
                'amount' => $ir_diffrence_amount,
                'narration' => 'Issue/Receive : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update MF Loss Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
            $account_amount = $account_amount + $ir_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
        } else {   // If Diff Negative : Credit in Worker and Debit in MF Loss
            $ir_diffrence_amount = abs($ir_diffrence_amount);
            // Add Worker Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 1,
                'account_id' => $worker_id,
                'amount' => $ir_diffrence_amount,
                'narration' => 'Issue/Receive : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update Worker Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
            $account_amount = $account_amount + $ir_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

            // Add MF Loss Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 2,
                'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
                'amount' => $ir_diffrence_amount,
                'narration' => 'Issue/Receive : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update MF Loss Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
            $account_amount = $account_amount - $ir_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
        }
    }

    function update_stock_on_manufacture_insert($lineitem_data ,$department_id){
//        echo '<pre>'; print_r($lineitem_data);
//        echo '<pre>'; print_r($department_id); exit;
        if (!empty($lineitem_data)) {
            foreach ($lineitem_data as $lineitem) {
                
                $lineitem->fine = $lineitem->net_wt * ($lineitem->touch_id) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                if((isset($lineitem->stock_method) && $lineitem->stock_method == '2') && $lineitem->type_id != MANUFACTURE_TYPE_ISSUE_ID){
                    
                    if(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $department_id;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->touch_id;
                        $insert_item_stock['grwt'] = $lineitem->weight;
                        $insert_item_stock['less'] = $lineitem->less;
                        $insert_item_stock['ntwt'] = $lineitem->net_wt;
                        $insert_item_stock['fine'] = $lineitem->fine;
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_item_id;
                        if($lineitem->type_id == MANUFACTURE_TYPE_RECEIVE_ID){
                            $insert_item_stock['stock_type'] = '4';
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
                        
                        $this->crud->insert('item_stock', $insert_item_stock);
                    } else {
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->ird_id) && !empty($lineitem->ird_id)){
                            $purchase_item_id = $lineitem->ird_id;
                        } 
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                        if(!empty($exist_item_id)){
                            if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->weight, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else {
                                $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->weight, '3', '.', '');
                            }
                            $update_item_stock = array();
                            $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                            $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                            $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                            $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                            $update_item_stock['updated_at'] = $this->now_time;
                            $update_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                        } else {
                            $insert_item_stock = array();
                            $insert_item_stock['department_id'] = $department_id;
                            $insert_item_stock['category_id'] = $lineitem->category_id;
                            $insert_item_stock['item_id'] = $lineitem->item_id;
                            $insert_item_stock['tunch'] = $lineitem->touch_id;
                            $insert_item_stock['grwt'] = $lineitem->weight;
                            $insert_item_stock['less'] = $lineitem->less;
                            $insert_item_stock['ntwt'] = $lineitem->net_wt;
                            $insert_item_stock['fine'] = $lineitem->fine;
                            $insert_item_stock['purchase_sell_item_id'] = $purchase_item_id;
                            if($lineitem->type_id == MANUFACTURE_TYPE_RECEIVE_ID){
                                $insert_item_stock['stock_type'] = '4';
                            }
                            $insert_item_stock['created_at'] = $this->now_time;
                            $insert_item_stock['created_by'] = $this->logged_in_id;
                            $insert_item_stock['updated_at'] = $this->now_time;
                            $insert_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('item_stock', $insert_item_stock);
                        }
                    }
                } else {
                    if(isset($lineitem->stock_method) && $lineitem->stock_method == '2'){
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->ird_id) && !empty($lineitem->ird_id)){
                            $purchase_item_id = $lineitem->ird_id;
                        } elseif(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                            $purchase_item_id = $lineitem->purchase_item_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                    echo '<pre>'.$this->db->last_query(); exit;
                    if(!empty($exist_item_id)){
                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->weight, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                        } else {
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->weight, '3', '.', '');
                        }
                        $update_item_stock = array();
                        $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                        $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                        $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                        $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                        $update_item_stock['updated_at'] = $this->now_time;
                        $update_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    } else { 
                        if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                            $lineitem->grwt = $this->zero_value - number_format((float) $lineitem->grwt, '3', '.', '');
                            $lineitem->less = $this->zero_value - number_format((float) $lineitem->less, '3', '.', '');
                            $lineitem->net_wt = $this->zero_value - number_format((float) $lineitem->net_wt, '3', '.', '');
                            $lineitem->fine = $this->zero_value - number_format((float) $lineitem->fine, '3', '.', '');
                        }
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $department_id;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->touch_id;
                        $insert_item_stock['grwt'] = $lineitem->grwt;
                        $insert_item_stock['less'] = $lineitem->less;
                        $insert_item_stock['ntwt'] = $lineitem->net_wt;
                        $insert_item_stock['fine'] = $lineitem->fine;
                        if($lineitem->type_id == MANUFACTURE_TYPE_RECEIVE_ID){
                            $insert_item_stock['stock_type'] = '4';
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
//                        echo '<pre>'; print_r($insert_item_stock); exit;
                        $this->crud->insert('item_stock', $insert_item_stock);
                    }
                }
            }
        }
    }
    
    function update_stock_on_manufacture_update($ir_id =''){
        $issue_receive_details = $this->crud->get_all_with_where('issue_receive_details', '', '', array('ir_id' => $ir_id));
        if(!empty($issue_receive_details)){
            foreach ($issue_receive_details as $lineitem){
                
                $lineitem->fine = $lineitem->net_wt * $lineitem->tunch / 100;
                $lineitem->grwt = $lineitem->weight;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                $department_id = $this->crud->get_column_value_by_id('issue_receive', 'department_id', array('ir_id' => $lineitem->ir_id));
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if($stock_method == '2'){
                    if(!empty($lineitem->purchase_sell_item_id)){
                        $ird_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $ird_id = $lineitem->ird_id;
                    }
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $ird_id);
                } else {
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }
                
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                echo $this->db->last_query(); exit;
                if(!empty($exist_item_id)){
                    if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                        $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                        $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                        $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    } else {
                        $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                        $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                        $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                    }
                    $update_item_stock = array();
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
//                    echo '<pre>'.$this->db->last_query(); exit;
                }
            }
        }
    }
    
    function update_account_and_department_balance_on_update($ir_id=''){
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $issue_receive_details = $this->crud->get_all_with_where('issue_receive_details', '', '', array('ir_id' => $ir_id));
//        echo '<pre>'; print_r($issue_receive_details); exit;
        $department_id = $this->crud->get_column_value_by_id('issue_receive', 'department_id', array('ir_id' => $ir_id));
        $account_id = $this->crud->get_column_value_by_id('issue_receive', 'worker_id', array('ir_id' => $ir_id));
        if(!empty($issue_receive_details)){
            foreach ($issue_receive_details as $lineitem){
                
                $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                if($lineitem->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                    if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    }
                } else {
                    if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    }
                }
            }
            
//            echo '<pre>'; print_r($total_gold_fine);
//            echo '<pre>'; print_r($total_silver_fine); exit;
            //Update Account Balance
            $account = $this->crud->get_data_row_by_id('account', 'account_id', $account_id);
            if(!empty($account)){
                $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $account_id));
            }
//            echo '<pre>'. $this->db->last_query(); exit;

            //Update Department Balance
            $department = $this->crud->get_data_row_by_id('account', 'account_id', $department_id);
            if(!empty($department)){
                $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $department_id));
            }
        }
    }

    function issue_receive_list() {
        if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"view")) {
            $data = array();
            set_page('manufacture/issue_receive_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function issue_receive_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'issue_receive ir';
        $config['select'] = 'ir.*,a.account_name AS worker,aa.account_name AS department,IF(ir.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(ir.hisab_done = 0,"No","Yes") AS is_hisab_done';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = ir.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = ir.department_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(ir.ir_date,"%d-%m-%Y")', 'ir.reference_no', 'IF(ir.lott_complete = 0,"No","Yes")', 'IF(ir.hisab_done = 0,"No","Yes")', 'ir.ir_remark');
        $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'ir.ir_date', 'ir.reference_no', null, null, null, null, null, null, null, null,'ir.lott_complete', 'ir.hisab_done', 'ir.ir_remark');

        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 'ir.department_id IN('.$department_ids.')';
        }
        if(!empty($post_data['department_id'])){
            $config['wheres'][] = array('column_name' => 'ir.department_id', 'column_value' => $post_data['department_id']);
        }
        if(!empty($post_data['worker_id'])){
            $config['wheres'][] = array('column_name' => 'ir.worker_id', 'column_value' => $post_data['worker_id']);
        }
        if(isset($post_data['lott_complete'])){
            if($post_data['lott_complete'] == '2'){
                $config['wheres'][] = array('column_name' => 'ir.hisab_done', 'column_value' => '1');
            } else if($post_data['lott_complete'] == '1'){
                $config['wheres'][] = array('column_name' => 'ir.lott_complete', 'column_value' => '1');
                $config['wheres'][] = array('column_name' => 'ir.hisab_done', 'column_value' => '0');
            } else if($post_data['lott_complete'] == 'all'){
                $config['wheres'][] = array('column_name' => 'ir.hisab_done', 'column_value' => '0');
            } else {
                $config['wheres'][] = array('column_name' => 'ir.lott_complete', 'column_value' => $post_data['lott_complete']);
            }
        }

        $config['order'] = array('ir.ir_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'. $this->db->last_query(); exit;
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit");

        foreach ($list as $ir) {
            $row = array();
            $action = '';
            if($role_edit){
                if($ir->hisab_done != '1'){
                    $action .= '<a href="' . base_url("manufacture/issue_receive/" . $ir->ir_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if($role_delete){
                if($ir->hisab_done != '1'){
                    $action .= '<a href="javascript:void(0);" class="delete_ir" data-href="' . base_url('manufacture/delete_manufacture_ir/' . $ir->ir_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                }
            }
            if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab")) {
                if(isset($post_data['checked_or_not']) && $post_data['checked_or_not'] == '1' && $ir->hisab_done == '0'){
                    $action .= '&nbsp;&nbsp;<input type="checkbox" name="check_ir[]" id="checkbox_id_'.$ir->ir_id.'" class="icheckbox_flat-blue check_ir" value="'.$ir->ir_id.'" data-total_issue_net_wt="'. $ir->total_issue_net_wt .'" data-total_issue_fine="'. $ir->total_issue_fine .'" data-total_receive_net_wt="'. $ir->total_receive_net_wt .'" data-total_receive_fine="'. $ir->total_receive_fine .'">';
                }
            }
            $row[] = $action;
            $row[] = $ir->worker;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-ir_id="' . $ir->ir_id . '" >' . $ir->department. '</a>';
            $row[] = (!empty(strtotime($ir->ir_date))) ? date('d-m-Y', strtotime($ir->ir_date)) : '';
            $row[] = $ir->reference_no;
            if(!empty($ir->total_issue_fine) && !empty($ir->total_issue_net_wt)){
                $issue_avg_tunch = $ir->total_issue_fine * 100 / $ir->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            $row[] = number_format($issue_avg_tunch, 2, '.', '');
            $row[] = number_format($ir->total_issue_net_wt, 3, '.', '');
            $row[] = number_format($ir->total_issue_fine, 3, '.', '');
            if(!empty($ir->total_receive_fine) && !empty($ir->total_receive_net_wt)){
                $receive_avg_tunch = $ir->total_receive_fine * 100 / $ir->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $row[] = number_format($receive_avg_tunch, 2, '.', '');
            $row[] = number_format($ir->total_receive_net_wt, 3, '.', '');
            $row[] = number_format($ir->total_receive_fine, 3, '.', '');
            $balance_net_wt = $ir->total_issue_net_wt - $ir->total_receive_net_wt;
            $row[] = number_format($balance_net_wt, 3, '.', '');
            $balance_fine = $ir->total_issue_fine - $ir->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $lott_complete = $ir->is_lott_complete;
            if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) {
                if($ir->lott_complete == 0) {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-ir_id="'.$ir->ir_id.'" data-lott_complete="1" data-href="'. base_url('manufacture/set_lott_complete_yes_no/'.$ir->ir_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;">';
                } else {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-ir_id="'.$ir->ir_id.'" data-lott_complete=0 data-href="'. base_url('manufacture/set_lott_complete_yes_no/'.$ir->ir_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;" checked="checked">';
                }
            }
            $row[] = $lott_complete;
            $row[] = $ir->is_hisab_done;
            $row[] = $ir->ir_remark;
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
    
    function set_lott_complete_yes_no($ir_id= '') {
        $update_data = array();
        $update_data['lott_complete'] = $_POST['lott_complete'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('issue_receive', $update_data, array('ir_id' => $ir_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function issue_receive_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'issue_receive_details ird';
        $config['select'] = 'ird.*,im.item_name, IF(ird.type_id = 1,"Issue","Receive") AS type';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = ird.item_id', 'join_type' => 'left');
        $config['column_search'] = array('IF(ird.type_id = 0,"Issue","Receive")', 'im.item_name', 'ird.weight', 'ird.tunch', 'ird.fine', 'DATE_FORMAT(ird.ird_date,"%d-%m-%Y")', 'ird.ird_remark');
        $config['column_order'] = array('ird.type_id', 'im.item_name', 'ird.weight', NULL, 'ird.weight', 'ird.tunch', 'ird.fine', 'ird.ird_date', 'ird.created_at', 'ird.updated_at', 'ird.ird_remark');
        $config['order'] = array('ird.ird_id' => 'desc');
        if (isset($post_data['ir_id']) && !empty($post_data['ir_id'])) {
            $config['wheres'][] = array('column_name' => 'ird.ir_id', 'column_value' => $post_data['ir_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $detail) {
            $row = array();
            $row[] = $detail->type;
            $row[] = $detail->item_name;
            $row[] = number_format($detail->weight, 3, '.', '');
            $row[] = number_format($detail->less, 3, '.', '');
            $row[] = number_format($detail->net_wt, 3, '.', '');
            $row[] = number_format($detail->tunch, 2, '.', '');
            $row[] = number_format($detail->actual_tunch, 2, '.', '');
            $row[] = number_format($detail->fine, 3, '.', '');
            $row[] = $detail->ird_date ? date('d-m-Y', strtotime($detail->ird_date)) : '';
            $row[] = $detail->created_at ? date('d-m-Y H:i:s', strtotime($detail->created_at)) : '';
            $row[] = $detail->updated_at ? date('d-m-Y H:i:s', strtotime($detail->updated_at)) : '';
            $row[] = $detail->ird_remark;
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
    
    function delete_manufacture_ir($id = '') {
        $where_array = array('ir_id' => $id);
        $issue_receive = $this->crud->get_row_by_id('issue_receive', $where_array);
        $return = array();
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if(!empty($issue_receive)){
            $found = false;
            $issue_receive_details = $this->crud->get_row_by_id('issue_receive_details', $where_array);
            if(!empty($issue_receive_details)){
                foreach($issue_receive_details as $ir_detail){
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $ir_detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $ir_detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $ir_detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $ir_detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $ir_detail->ird_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                    } else if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_receive_or_not($issue_receive[0]->department_id, $ir_detail->category_id, $ir_detail->item_id, $ir_detail->tunch);
                            if(!empty($used_lineitem_ids) && in_array($ir_detail->ird_id, $used_lineitem_ids)){
                                $found = true;
                            }
                        } 
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {
                // Increase fine and amount in Department
                $this->update_account_and_department_balance_on_update($id);
//                // Increase Item Stock
                $this->update_stock_on_manufacture_update($id);
//                $this->crud->delete('worker_hisab_detail', array('relation_id' => $id, 'is_module' => HISAB_DONE_IS_MODULE_MIR));
//                $this->crud->delete('worker_hisab', $where_array);
                $this->revert_journal_for_ir_diffrence_amount($id, $issue_receive[0]->worker_id);
                $this->crud->delete('issue_receive_details', $where_array);
                $this->crud->delete('issue_receive', $where_array);
//                // Increase fine and amount in Department
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function check_default_item_receive_or_not($department_id, $category_id, $item_id, $touch_id){
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $other_purchase_items = $this->crud->get_other_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
//            echo '<pre>'; print_r($purchase_delete_array); exit;
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
                    if($purchase_item->type == 'R'){
                        $used_lineitem_ids[] = $purchase_item->ird_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'R'){
                        $used_lineitem_ids[] = $purchase_item->ird_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }
    
    function save_worker_hisab_details(){
        $is_module = $_POST['is_module'];
        $checked_ids = $_POST['checked_items'];
        $hisab_date = date('Y-m-d', strtotime($_POST['hisab_date']));
        $fine = number_format((float) $_POST['fine'], '3', '.', '');
        $worker_id = $_POST['worker_id'];
        $worker_hisab = array();
        $worker_hisab['department_id'] = $_POST['department_id'];
        $worker_hisab['worker_id'] = $worker_id;
        $worker_hisab['against_account_id'] = MF_LOSS_EXPENSE_ACCOUNT_ID;
        $worker_hisab['is_module'] = $is_module;
        $worker_hisab['hisab_date'] = $hisab_date;
        $worker_hisab['fine'] = $fine;
        $worker_hisab['created_at'] = $this->now_time;
        $worker_hisab['created_by'] = $this->logged_in_id;
        $worker_hisab['updated_at'] = $this->now_time;
        $worker_hisab['updated_by'] = $this->logged_in_id;
        $result = $this->crud->insert('worker_hisab', $worker_hisab);
        $worker_hisab_id = $this->db->insert_id();
        
        if(!empty($checked_ids)){
            $total_fine_adjusted = 0;
            foreach ($checked_ids as $checked_id){
                if($is_module == HISAB_DONE_IS_MODULE_MIR){
                    $checked_row = $this->crud->get_data_row_by_id('issue_receive', 'ir_id', $checked_id);
                } else if($is_module == HISAB_DONE_IS_MODULE_MIR_SILVER){
                    $checked_row = $this->crud->get_data_row_by_id('issue_receive_silver', 'irs_id', $checked_id);
                } else if($is_module == HISAB_DONE_IS_MODULE_MHM){
                    $checked_row = $this->crud->get_data_row_by_id('manu_hand_made', 'mhm_id', $checked_id);
                } else if($is_module == HISAB_DONE_IS_MODULE_MC){
                    $checked_row = $this->crud->get_data_row_by_id('machine_chain', 'machine_chain_id', $checked_id);
                } else if($is_module == HISAB_DONE_IS_MODULE_CASTING){
                    $checked_row = $this->crud->get_data_row_by_id('casting_entry', 'ce_id', $checked_id);
                }
                if(!empty($checked_row)){
                    $balance_fine = $checked_row->total_issue_fine - $checked_row->total_receive_fine;
                    $balance_fine = number_format($balance_fine, '3', '.', '');
                    $worker_hisab_detail = array();
                    $worker_hisab_detail['worker_hisab_id'] = $worker_hisab_id;
                    $worker_hisab_detail['worker_id'] = $worker_id;
                    $worker_hisab_detail['against_account_id'] = MF_LOSS_EXPENSE_ACCOUNT_ID;
                    $worker_hisab_detail['relation_id'] = $checked_id;
                    $worker_hisab_detail['is_module'] = $is_module;
                    $fine_adjusted = 0;
                    $type_id = 0;
                    if($balance_fine < 0){
                        $fine_adjusted = abs($balance_fine);
                        $type_id = MANUFACTURE_TYPE_ISSUE_ID;
                    } else {
                        $fine_adjusted = $balance_fine;
                        $type_id = MANUFACTURE_TYPE_RECEIVE_ID;
                    }
                    $worker_hisab_detail['balance_fine'] = $balance_fine;
                    $worker_hisab_detail['fine_adjusted'] = $fine_adjusted;
                    $worker_hisab_detail['type_id'] = $type_id;
                    $result = $this->crud->insert('worker_hisab_detail', $worker_hisab_detail);
                    $total_fine_adjusted = $total_fine_adjusted + $balance_fine;
                    $total_fine_adjusted = number_format((float) $total_fine_adjusted, '3', '.', '');
                    if($is_module == HISAB_DONE_IS_MODULE_MIR){
                        $this->crud->update('issue_receive', array('hisab_done' => '1'), array('ir_id' => $checked_id));
                    } else if($is_module == HISAB_DONE_IS_MODULE_MIR_SILVER){
                        $this->crud->update('issue_receive_silver', array('hisab_done' => '1'), array('irs_id' => $checked_id));
                    } else if($is_module == HISAB_DONE_IS_MODULE_MHM){
                        $this->crud->update('manu_hand_made', array('hisab_done' => '1'), array('mhm_id' => $checked_id));
                    } else if($is_module == HISAB_DONE_IS_MODULE_MC){
                        $this->crud->update('machine_chain', array('hisab_done' => '1'), array('machine_chain_id' => $checked_id));
                    } else if($is_module == HISAB_DONE_IS_MODULE_CASTING){
                        $this->crud->update('casting_entry', array('hisab_done' => '1'), array('ce_id' => $checked_id));
                    }
                }
            }
            $this->crud->update('worker_hisab', array('total_fine_adjusted' => $total_fine_adjusted), array('worker_hisab_id' => $worker_hisab_id));
            
            if($is_module == HISAB_DONE_IS_MODULE_MIR_SILVER) {  // For I/R Silver module : fine count in Silver
            
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_id);
                if(!empty($worker_accounts)){
                    $worker_silver_fine = number_format((float) $worker_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $worker_silver_fine = $worker_silver_fine + number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_silver_fine = $worker_silver_fine - number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_silver_fine = $worker_silver_fine + $fine;

                    // Update to Worker Account
                    $worker_silver_fine = number_format((float) $worker_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('silver_fine' => $worker_silver_fine), array('account_id' => $worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_silver_fine = number_format((float) $mf_loss_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $mf_loss_silver_fine = $mf_loss_silver_fine - number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_silver_fine = $mf_loss_silver_fine + number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_silver_fine = $mf_loss_silver_fine - $fine;

                    // Update to MF Loss Account
                    $mf_loss_silver_fine = number_format((float) $mf_loss_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('silver_fine' => $mf_loss_silver_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            } else {  // For other all module : fine count in Gold
            
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_id);
                if(!empty($worker_accounts)){
                    $worker_gold_fine = number_format((float) $worker_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $worker_gold_fine = $worker_gold_fine + number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_gold_fine = $worker_gold_fine - number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_gold_fine = $worker_gold_fine + $fine;

                    // Update to Worker Account
                    $worker_gold_fine = number_format((float) $worker_gold_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $worker_gold_fine), array('account_id' => $worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_gold_fine = number_format((float) $mf_loss_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $mf_loss_gold_fine = $mf_loss_gold_fine - number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_gold_fine = $mf_loss_gold_fine + number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_gold_fine = $mf_loss_gold_fine - $fine;

                    // Update to MF Loss Account
                    $mf_loss_gold_fine = number_format((float) $mf_loss_gold_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $mf_loss_gold_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            }
        }
        if ($result) {
            $return['success'] = "Added";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Worker Hisab Save Successfully!');
        }
        echo json_encode($return);
        exit;
    }
    
    function hisab_total_list() {
        if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab") || $this->applib->have_access_role(MANUFACTURE_MODULE_ID,"worker_hisab_i_r_silver") || 
            $this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_handmade") || $this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_machine_chain")) {
            $data = array();
            set_page('manufacture/hisab_total_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function hisab_total_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'worker_hisab wh';
        $config['select'] = 'wh.*,a.account_name AS worker';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = wh.worker_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'DATE_FORMAT(wh.hisab_date,"%d-%m-%Y")', 'wh.fine');
        $config['column_order'] = array(null,'a.account_name', 'wh.hisab_date', 'wh.fine');
        $config['order'] = array('wh.worker_hisab_id' => 'desc');
        if (isset($post_data['hisab_done_from']) && !empty($post_data['hisab_done_from'])) {
            $config['wheres'][] = array('column_name' => 'wh.is_module', 'column_value' => $post_data['hisab_done_from']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'. $this->db->last_query(); exit;
        $data = array();

        foreach ($list as $hisab_row) {
            $row = array();
            $action = '';
            $action .= '<a href="javascript:void(0);" class="delete_wh" data-href="' . base_url('manufacture/delete_hisab/' . $hisab_row->worker_hisab_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
            $row[] = $action;
            $module_name = '';
            if($hisab_row->is_module == HISAB_DONE_IS_MODULE_MIR){
                $module_name = 'M';
            } else if($hisab_row->is_module == HISAB_DONE_IS_MODULE_MIR_SILVER){
                $module_name = 'MIR S';
            } else if($hisab_row->is_module == HISAB_DONE_IS_MODULE_MHM){
                $module_name = 'MHM';
            } else if($hisab_row->is_module == HISAB_DONE_IS_MODULE_MC){
                $module_name = 'MMC';
            } else if($hisab_row->is_module == HISAB_DONE_IS_MODULE_CASTING){
                $module_name = 'CASTING';
            }
            $row[] = $module_name;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-is_module="' . $hisab_row->is_module . '"  data-worker_hisab_id="' . $hisab_row->worker_hisab_id . '" data-fine="' . number_format($hisab_row->fine, 3, '.', '') . '" >' . $hisab_row->worker. '</a>';
            $row[] = (!empty(strtotime($hisab_row->hisab_date))) ? date('d-m-Y', strtotime($hisab_row->hisab_date)) : '';
            $row[] = number_format($hisab_row->fine, 3, '.', '');
            $row[] = number_format($hisab_row->total_fine_adjusted, 3, '.', '');
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
    
    function hisab_detail_datatable() {
        $post_data = $this->input->post();
        
        $config['table'] = 'worker_hisab_detail whd';
        if (isset($post_data['is_module']) && $post_data['is_module'] == HISAB_DONE_IS_MODULE_MIR) {
            $config['select'] = 'whd.*,ir.*,ir.ir_date as row_date,a.account_name AS worker,aa.account_name AS department,IF(ir.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(ir.hisab_done = 0,"No","Yes") AS is_hisab_done';
            $config['joins'][] = array('join_table' => 'issue_receive ir', 'join_by' => 'ir.ir_id = whd.relation_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = ir.worker_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = ir.department_id', 'join_type' => 'left');
            $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(ir.ir_date,"%d-%m-%Y")', 'ir.reference_no');
            $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'ir.ir_date', 'ir.reference_no');
        }
        if (isset($post_data['is_module']) && $post_data['is_module'] == HISAB_DONE_IS_MODULE_MIR_SILVER) {
            $config['select'] = 'whd.*,irs.*,irs.irs_date as row_date,a.account_name AS worker,aa.account_name AS department,IF(irs.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(irs.hisab_done = 0,"No","Yes") AS is_hisab_done';
            $config['joins'][] = array('join_table' => 'issue_receive_silver irs', 'join_by' => 'irs.irs_id = whd.relation_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = irs.worker_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = irs.department_id', 'join_type' => 'left');
            $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(irs.irs_date,"%d-%m-%Y")', 'irs.reference_no');
            $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'irs.irs_date', 'irs.reference_no');
        }
        if (isset($post_data['is_module']) && $post_data['is_module'] == HISAB_DONE_IS_MODULE_MHM) {
            $config['select'] = 'whd.*,mhm.*,mhm.mhm_date as row_date,a.account_name AS worker,aa.account_name AS department,IF(mhm.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(mhm.hisab_done = 0,"No","Yes") AS is_hisab_done';
            $config['joins'][] = array('join_table' => 'manu_hand_made mhm', 'join_by' => 'mhm.mhm_id = whd.relation_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = mhm.worker_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = mhm.department_id', 'join_type' => 'left');
            $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(mhm.mhm_date,"%d-%m-%Y")', 'mhm.reference_no');
            $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'mhm.mhm_date', 'mhm.reference_no');
        }
        if (isset($post_data['is_module']) && $post_data['is_module'] == HISAB_DONE_IS_MODULE_MC) {
            $config['select'] = 'whd.*,mc.*,mc.machine_chain_date as row_date,a.account_name AS worker,aa.account_name AS department,IF(mc.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(mc.hisab_done = 0,"No","Yes") AS is_hisab_done';
            $config['joins'][] = array('join_table' => 'machine_chain mc', 'join_by' => 'mc.machine_chain_id = whd.relation_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = mc.worker_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = mc.department_id', 'join_type' => 'left');
            $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(mc.machine_chain_date,"%d-%m-%Y")', 'mc.reference_no');
            $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'mc.machine_chain_date', 'mc.reference_no');
        }
        if (isset($post_data['is_module']) && $post_data['is_module'] == HISAB_DONE_IS_MODULE_CASTING) {
            $config['select'] = 'whd.*,mc.*,mc.ce_date as row_date,a.account_name AS worker,"CASTING" AS department,IF(mc.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(mc.hisab_done = 0,"No","Yes") AS is_hisab_done';
            $config['joins'][] = array('join_table' => 'casting_entry mc', 'join_by' => 'mc.ce_id = whd.relation_id', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = mc.worker_id', 'join_type' => 'left');
            $config['column_search'] = array('a.account_name','DATE_FORMAT(mc.ce_date,"%d-%m-%Y")', 'mc.reference_no');
            $config['column_order'] = array(null,'a.account_name',null,'mc.ce_date', 'mc.reference_no');
        }
        $config['order'] = array('whd.wsd_id' => 'desc');
        if (isset($post_data['worker_hisab_id']) && !empty($post_data['worker_hisab_id'])) {
            $config['wheres'][] = array('column_name' => 'whd.worker_hisab_id', 'column_value' => $post_data['worker_hisab_id']);
        }
        $this->load->library('datatables',  $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query(); exit;
        $data = array();
        
        foreach ($list as $hisab_detail_row) {
            $row = array();
            $action = '';
//            if(count($list) > 1){
                $action .= '&nbsp;&nbsp;<input type="checkbox" name="uncheck_row[]" class="icheckbox_flat-blue uncheck_row" checked="" value="'.$hisab_detail_row->wsd_id.'">';
//            }
            $row[] = $action;
            $row[] = $hisab_detail_row->worker;
            $row[] = $hisab_detail_row->department;
            $row[] = (!empty(strtotime($hisab_detail_row->row_date))) ? date('d-m-Y', strtotime($hisab_detail_row->row_date)) : '';
            $row[] = $hisab_detail_row->reference_no;
            if(!empty($hisab_detail_row->total_issue_fine) && !empty($hisab_detail_row->total_issue_net_wt)){
                $issue_avg_tunch = $hisab_detail_row->total_issue_fine * 100 / $hisab_detail_row->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            if(!empty($hisab_detail_row->total_receive_fine) && !empty($hisab_detail_row->total_receive_net_wt)){
                $receive_avg_tunch = $hisab_detail_row->total_receive_fine * 100 / $hisab_detail_row->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $balance_weight = $hisab_detail_row->total_issue_net_wt - $hisab_detail_row->total_receive_net_wt;
            $row[] = number_format($balance_weight, 3, '.', '');
            $balance_fine = $hisab_detail_row->total_issue_fine - $hisab_detail_row->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $row[] = $hisab_detail_row->is_lott_complete;
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
    
    function remove_worker_hisab() {
        $checked_ids = isset($_POST['checked_items']) ? $_POST['checked_items'] : '';
        $worker_hisab_id = $_POST['worker_hisab_id'];
        $total_balance_fine = 0;
        if(!empty($checked_ids)){
            foreach ($checked_ids as $checked_id){
                $worker_hisab_detail_row = $this->crud->get_data_row_by_id('worker_hisab_detail', 'wsd_id', $checked_id);
                if(!empty($worker_hisab_detail_row)){
                    $total_balance_fine = $total_balance_fine + $worker_hisab_detail_row->balance_fine;
                    if($worker_hisab_detail_row->is_module == HISAB_DONE_IS_MODULE_MIR){
                        $this->crud->update('issue_receive', array('hisab_done' => '0'), array('ir_id' => $worker_hisab_detail_row->relation_id));
                    } else if($worker_hisab_detail_row->is_module == HISAB_DONE_IS_MODULE_MIR_SILVER){
                        $this->crud->update('issue_receive_silver', array('hisab_done' => '0'), array('irs_id' => $worker_hisab_detail_row->relation_id));
                    } else if($worker_hisab_detail_row->is_module == HISAB_DONE_IS_MODULE_MHM){
                        $this->crud->update('manu_hand_made', array('hisab_done' => '0'), array('mhm_id' => $worker_hisab_detail_row->relation_id));
                    } else if($worker_hisab_detail_row->is_module == HISAB_DONE_IS_MODULE_MC){
                        $this->crud->update('machine_chain', array('hisab_done' => '0'), array('machine_chain_id' => $worker_hisab_detail_row->relation_id));
                    } else if($worker_hisab_detail_row->is_module == HISAB_DONE_IS_MODULE_CASTING){
                        $this->crud->update('casting_entry', array('hisab_done' => '0'), array('ce_id' => $worker_hisab_detail_row->relation_id));
                    }
                    $result = $this->crud->delete('worker_hisab_detail', array('wsd_id' => $checked_id));
                }
            }
        }
        if(!empty($worker_hisab_id)){
            $worker_hisab_row = $this->crud->get_data_row_by_id('worker_hisab', 'worker_hisab_id', $worker_hisab_id);
            $total_fine_adjusted = $worker_hisab_row->total_fine_adjusted - $total_balance_fine;
            $total_fine_adjusted = number_format($total_fine_adjusted, 3, '.', '');
            $this->crud->update('worker_hisab', array('fine' => $_POST['fine'], 'total_fine_adjusted' => $total_fine_adjusted, 'updated_by' => $this->logged_in_id, 'updated_at' => $this->now_time), array('worker_hisab_id' => $worker_hisab_id));

            $fine = $worker_hisab_row->fine - $_POST['fine'];
            
            if($worker_hisab_row->is_module == HISAB_DONE_IS_MODULE_MIR_SILVER) {  // For I/R Silver module : fine count in Silver
                
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_row->worker_id);
                if(!empty($worker_accounts)){
                    $worker_silver_fine = number_format((float) $worker_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_balance_fine < 0){ // Rereive is more
                        $total_balance_fine_positive = abs($total_balance_fine);
                        $worker_silver_fine = $worker_silver_fine - number_format((float) $total_balance_fine_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_silver_fine = $worker_silver_fine + number_format((float) $total_balance_fine, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_silver_fine = number_format((float) $worker_silver_fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');

                    // Update to Worker Account
                    $worker_silver_fine = number_format((float) $worker_silver_fine, '3', '.', '');
                    $result = $this->crud->update('account', array('silver_fine' => $worker_silver_fine), array('account_id' => $worker_hisab_row->worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_silver_fine = number_format((float) $mf_loss_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_balance_fine < 0){ // Rereive is more
                        $total_balance_fine_positive = abs($total_balance_fine);
                        $mf_loss_silver_fine = $mf_loss_silver_fine + number_format((float) $total_balance_fine_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_silver_fine = $mf_loss_silver_fine - number_format((float) $total_balance_fine, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_silver_fine = number_format((float) $mf_loss_silver_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');

                    // Update to MF Loss Account
                    $mf_loss_silver_fine = number_format((float) $mf_loss_silver_fine, '3', '.', '');
                    $result = $this->crud->update('account', array('silver_fine' => $mf_loss_silver_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            } else {  // For other all module : fine count in Gold
            
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_row->worker_id);
                if(!empty($worker_accounts)){
                    $worker_gold_fine = number_format((float) $worker_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_balance_fine < 0){ // Rereive is more
                        $total_balance_fine_positive = abs($total_balance_fine);
                        $worker_gold_fine = $worker_gold_fine - number_format((float) $total_balance_fine_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_gold_fine = $worker_gold_fine + number_format((float) $total_balance_fine, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_gold_fine = number_format((float) $worker_gold_fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');

                    // Update to Worker Account
                    $worker_gold_fine = number_format((float) $worker_gold_fine, '3', '.', '');
                    $result = $this->crud->update('account', array('gold_fine' => $worker_gold_fine), array('account_id' => $worker_hisab_row->worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_gold_fine = number_format((float) $mf_loss_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_balance_fine < 0){ // Rereive is more
                        $total_balance_fine_positive = abs($total_balance_fine);
                        $mf_loss_gold_fine = $mf_loss_gold_fine + number_format((float) $total_balance_fine_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_gold_fine = $mf_loss_gold_fine - number_format((float) $total_balance_fine, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_gold_fine = number_format((float) $mf_loss_gold_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');

                    // Update to MF Loss Account
                    $mf_loss_gold_fine = number_format((float) $mf_loss_gold_fine, '3', '.', '');
                    $result = $this->crud->update('account', array('gold_fine' => $mf_loss_gold_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            }
        }
        if ($result) {
            $return['success'] = "Added";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Worker Hisab Updated Successfully!');
        }
        echo json_encode($return);
        exit;
    }
    
    function delete_hisab($id = '') {
        $worker_hisab_data = $this->crud->get_data_row_by_id('worker_hisab', 'worker_hisab_id', $id);
        if(!empty($worker_hisab_data)){
            
            $worker_hisab_detail = $this->crud->get_row_by_id('worker_hisab_detail', array('worker_hisab_id' => $id));
            if(!empty($worker_hisab_detail)){
                foreach($worker_hisab_detail as $worker_hisab){
                    if($worker_hisab->is_module == HISAB_DONE_IS_MODULE_MIR){
                        $this->crud->update('issue_receive', array('hisab_done' => '0'), array('ir_id' => $worker_hisab->relation_id));
                    } else if($worker_hisab->is_module == HISAB_DONE_IS_MODULE_MIR_SILVER){
                        $this->crud->update('issue_receive_silver', array('hisab_done' => '0'), array('irs_id' => $worker_hisab->relation_id));
                    } else if($worker_hisab->is_module == HISAB_DONE_IS_MODULE_MHM){
                        $this->crud->update('manu_hand_made', array('hisab_done' => '0'), array('mhm_id' => $worker_hisab->relation_id));
                    } else if($worker_hisab->is_module == HISAB_DONE_IS_MODULE_MC){
                        $this->crud->update('machine_chain', array('hisab_done' => '0'), array('machine_chain_id' => $worker_hisab->relation_id));
                    } else if($worker_hisab->is_module == HISAB_DONE_IS_MODULE_CASTING){
                        $this->crud->update('casting_entry', array('hisab_done' => '0'), array('ce_id' => $worker_hisab->relation_id));
                    }
                }
            }

            $worker_id = $worker_hisab_data->worker_id;
            $fine = $worker_hisab_data->fine;
            $total_fine_adjusted = $worker_hisab_data->total_fine_adjusted;
            
            if($worker_hisab_data->is_module == HISAB_DONE_IS_MODULE_MIR_SILVER) {  // For I/R Silver module : fine count in Silver
            
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_id);
                if(!empty($worker_accounts)){
                    $worker_silver_fine = number_format((float) $worker_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $worker_silver_fine = $worker_silver_fine - number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_silver_fine = $worker_silver_fine + number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_silver_fine = number_format((float) $worker_silver_fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');

                    // Update to Worker Account
                    $worker_silver_fine = number_format((float) $worker_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('silver_fine' => $worker_silver_fine), array('account_id' => $worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_silver_fine = number_format((float) $mf_loss_accounts->silver_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $mf_loss_silver_fine = $mf_loss_silver_fine + number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_silver_fine = $mf_loss_silver_fine - number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_silver_fine = number_format((float) $mf_loss_silver_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');

                    // Update to MF Loss Account
                    $mf_loss_silver_fine = number_format((float) $mf_loss_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('silver_fine' => $mf_loss_silver_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            } else {  // For other all module : fine count in Gold
            
                // Increase and Decrease Fine in Worker Account on Hisab Done
                $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_id);
                if(!empty($worker_accounts)){
                    $worker_gold_fine = number_format((float) $worker_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $worker_gold_fine = $worker_gold_fine - number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $worker_gold_fine = $worker_gold_fine + number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $worker_gold_fine = number_format((float) $worker_gold_fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');

                    // Update to Worker Account
                    $worker_gold_fine = number_format((float) $worker_gold_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $worker_gold_fine), array('account_id' => $worker_id));
                }

                // Increase and Decrease Fine in MF Loss Account on Hisab Done
                $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                if(!empty($mf_loss_accounts)){
                    $mf_loss_gold_fine = number_format((float) $mf_loss_accounts->gold_fine, '3', '.', '');

                    // Update Checked total fine = $total_fine_adjusted
                    if($total_fine_adjusted < 0){ // Rereive is more
                        $total_fine_adjusted_positive = abs($total_fine_adjusted);
                        $mf_loss_gold_fine = $mf_loss_gold_fine + number_format((float) $total_fine_adjusted_positive, '3', '.', '');
                    } else { // Issue is more
                        $mf_loss_gold_fine = $mf_loss_gold_fine - number_format((float) $total_fine_adjusted, '3', '.', '');
                    }

                    // Update Hisab fine = $fine
                    $mf_loss_gold_fine = number_format((float) $mf_loss_gold_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');

                    // Update to MF Loss Account
                    $mf_loss_gold_fine = number_format((float) $mf_loss_gold_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $mf_loss_gold_fine), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                }
                
            }

            $this->crud->delete('worker_hisab_detail', array('worker_hisab_id' => $id));
            $this->crud->delete('worker_hisab', array('worker_hisab_id' => $id));
            $return['success'] = 'Deleted';
        } else {
            $return['error'] = 'some error occurred';
        }
        echo json_encode($return);
        exit;
    }
    
    function get_item_stock(){
        $where_array = array();
        $where_array['department_id '] = $_POST['department_id'];
        $where_array['category_id'] = $this->crud->get_column_value_by_id('item_master','category_id',array('item_id' => $_POST['item_id']));
        $where_array['item_id'] = $_POST['item_id'];
        $where_array['tunch'] = $_POST['touch_id'];
        $grwt_in_stock = array();
        $grwt_in_stock = $this->crud->get_columns_val_by_where('item_stock','grwt', $where_array);
//        echo '<pre>'; print_r($grwt_in_stock); exit;
        $grwt_in_old_sell_item = 0;
        if(isset($_POST['ird_id']) && !empty($_POST['ird_id'])){
            $grwt_in_old_sell_item = $this->crud->get_id_by_val('issue_receive_details', 'weight', 'ird_id', $_POST['ird_id']);
        }
        if(empty($grwt_in_stock)){
            $grwt_in_stock['grwt'] = 0;
        } else {
            $grwt_in_stock['grwt'] = $grwt_in_stock[0]['grwt'] + $grwt_in_old_sell_item;
        }
        print json_encode($grwt_in_stock);
        exit;
    }
    
    function get_default_department_of_worker($worker_id){
        $data = array();
        if(!empty($worker_id)){
            $department_ids = $this->applib->current_user_department_ids();
            $this->db->select('u.default_department_id');
            $this->db->from('account a');
            $this->db->join('user_master u', 'u.user_id = a.user_id', 'left');
            $this->db->where('a.account_id', $worker_id);
            $this->db->where_in('u.default_department_id', $department_ids);
            $query = $this->db->get();
            $data['default_department_id'] =  $query->row('default_department_id');
        }
        echo json_encode($data);
        exit;
    }
    
    function get_category_from_item(){
        $data = array();
        $item_id = $_POST['item_id'];
        $category_id = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $item_id));
        if(isset($category_id) && !empty($category_id)){
            $data['category_id'] = $category_id;
        }
        echo json_encode($data);
        exit;
    }
}
