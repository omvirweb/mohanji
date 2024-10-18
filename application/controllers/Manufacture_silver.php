<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manufacture_silver extends CI_Controller {

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

    function issue_receive_silver($irs_id = '') {
        $data = array();
        $issue_receive_silver_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');   
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if (!empty($irs_id)) {
            if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"edit")) {
                $issue_receive_silver_data = $this->crud->get_row_by_id('issue_receive_silver', array('irs_id' => $irs_id));
                $issue_receive_silver_details = $this->crud->get_row_by_id('issue_receive_silver_details', array('irs_id' => $irs_id));
                $issue_receive_silver_data = $issue_receive_silver_data[0];

                $issue_receive_silver_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$issue_receive_silver_data->created_by));
                if($issue_receive_silver_data->created_by != $issue_receive_silver_data->updated_by){
                    $issue_receive_silver_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $issue_receive_silver_data->updated_by));
                }else{
                    $issue_receive_silver_data->updated_by_name = $issue_receive_silver_data->created_by_name;
                }
                
                $data['irs_data'] = $issue_receive_silver_data;
                $lineitems = array();
                foreach($issue_receive_silver_details as $detail){
                    
                    $issue_receive_silver_detail->irs_item_delete = 'allow';
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){

                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $issue_receive_silver_detail->irs_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_transfer)){
                            $issue_receive_silver_detail->irs_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_issue_receive)){
                            $issue_receive_silver_detail->irs_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_manu_hand_made)){
                            $manu_hand_made_detail->irs_item_delete = 'not_allow';
                        }
//                        echo '<pre>'. $this->db->last_query(); exit;
//                        echo '<pre>'; print_r($issue_receive_silver_detail->irs_item_delete); exit;
                        $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$detail->irsd_id.'" AND s.process_id = "'.$issue_receive_silver_data->department_id.'"');
                        $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="'.$detail->irsd_id.'" AND s.from_department = "'.$issue_receive_silver_data->department_id.'"');
                        $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="'.$detail->irsd_id.'" AND ir.department_id = "'.$issue_receive_silver_data->department_id.'"');
                        $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$detail->irsd_id.'" AND mhm.department_id = "'.$issue_receive_silver_data->department_id.'"');
                        $issue_receive_silver_detail->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt;
                    } else {
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $issue_receive_silver_detail->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($issue_receive_silver_data->department_id, $detail->category_id, $detail->item_id);
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
                                            $used_lineitem_ids[] = $receive_item->irsd_id;
                                            if($detail->irsd_id == $receive_item->irsd_id){
                                                $issue_receive_silver_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $receive_item->grwt;
                                            }
                                        }
                                        $first_check_receive_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($receive_item->type == 'R'){
                                            $used_lineitem_ids[] = $receive_item->irsd_id;
                                            if($detail->irsd_id == $receive_item->irsd_id){
                                                $issue_receive_silver_detail->total_grwt_sell = $receive_item->grwt;
                                            }
                                        }

                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($detail->irsd_id, $used_lineitem_ids)){
                                $issue_receive_silver_detail->irs_item_delete = 'not_allow';
                            }
                        }
                    }
//                    
                    $issue_receive_silver_detail->type_name = $detail->type_id == 1 ? 'Issue' : 'Receive';
                    $issue_receive_silver_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $issue_receive_silver_detail->purity = $detail->tunch;
                    $issue_receive_silver_detail->weight = $detail->weight;
                    $issue_receive_silver_detail->grwt = $detail->weight;
                    $issue_receive_silver_detail->less = $detail->less;
                    $issue_receive_silver_detail->net_wt = $detail->net_wt;
                    $issue_receive_silver_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $issue_receive_silver_detail->fine = $detail->fine;
                    $issue_receive_silver_detail->irsd_date = $detail->irsd_date ? date('d-m-Y', strtotime($detail->irsd_date)) : '';
                    $issue_receive_silver_detail->irsd_remark = $detail->irsd_remark;
                    $issue_receive_silver_detail->irsd_id = $detail->irsd_id;
                    $issue_receive_silver_detail->type_id = $detail->type_id;
                    $issue_receive_silver_detail->item_id = $detail->item_id;
                    $issue_receive_silver_detail->touch_id = $detail->tunch;
                    $issue_receive_silver_detail->wstg = '0';
                    $issue_receive_silver_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $issue_receive_silver_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $issue_receive_silver_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($issue_receive_silver_detail);
                }
                $data['issue_receive_silver_detail'] = implode(',', $lineitems);
                set_page('manufacture/issue_receive_silver/issue_receive_silver', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"add")) {
                $lineitems = array();
                set_page('manufacture/issue_receive_silver/issue_receive_silver', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_issue_receive_silver() {
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
        $post_data['irs_date'] = isset($post_data['irs_date']) && !empty($post_data['irs_date']) ? date('Y-m-d', strtotime($post_data['irs_date'])) : null;
        $post_data['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
        $post_data['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
        $post_data['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
        $post_data['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');
        if (isset($post_data['irs_id']) && !empty($post_data['irs_id'])) {
            
            // Increase fine in Account And Department
            $this->update_account_and_department_balance_on_update($post_data['irs_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_manufacture_update($post_data['irs_id']);
            $post_data['department_id'] = $this->crud->get_column_value_by_id('issue_receive_silver','department_id',array('irs_id' => $post_data['irs_id']));
            $update_arr = array();
            $update_arr['worker_id'] = $post_data['worker_id'];
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['irs_date'] = $post_data['irs_date'];           
//            $update_arr['reference_no'] = $post_data['reference_no'];
            $update_arr['lott_complete'] = $post_data['lott_complete'];
            $update_arr['irs_remark']= $post_data['irs_remark'];
            $update_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $update_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $update_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $update_arr['total_receive_fine']= $post_data['total_receive_fine'];
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['irs_id'] = $post_data['irs_id'];

            $result = $this->crud->update('issue_receive_silver', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Issue/Receive Updated Successfully');
                
                
                if(isset($post_data['deleted_lineitem_id'])){
                    $this->db->where_in('irsd_id', $post_data['deleted_lineitem_id']);
                    $this->db->delete('issue_receive_silver_details');
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $update_item = array();
                        $update_item['irs_id'] = $post_data['irs_id'];
                        $update_item['type_id'] = $lineitem->type_id;
                        $update_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['tunch'] = $lineitem->purity;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['less'] = $lineitem->less;
                        $update_item['net_wt'] = $lineitem->net_wt;
                        $update_item['actual_tunch'] = $lineitem->actual_tunch;
                        $update_item['fine'] = $lineitem->fine;
                        $update_item['irsd_date'] = !empty($lineitem->irsd_date) ? date('Y-m-d', strtotime($lineitem->irsd_date)) : null;
                        $update_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $update_item['irsd_remark'] = $lineitem->irsd_remark;
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
                        if(isset($lineitem->irsd_id) && !empty($lineitem->irsd_id)){
                            $this->db->where('irsd_id', $lineitem->irsd_id);
                            $this->db->update('issue_receive_silver_details', $update_item);
                        } else {
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('issue_receive_silver_details', $update_item);
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
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $account_silver_fine = number_format((float) $account->silver_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_silver_fine = number_format((float) $account_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine, 'silver_fine' => $account_silver_fine), array('account_id' => $post_data['worker_id']));
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
            $reference = $this->crud->get_max_number('issue_receive_silver', 'reference_no');
            $reference_no = 1;
            if ($reference->reference_no > 0) {
                $reference_no = $reference->reference_no + 1;
            }
            $insert_arr['worker_id'] = $post_data['worker_id'];
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['irs_date'] = $post_data['irs_date'];
            $insert_arr['reference_no'] = $reference_no;
            $insert_arr['lott_complete'] = $post_data['lott_complete'];
            $insert_arr['irs_remark'] = $post_data['irs_remark'];
            $insert_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $insert_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $insert_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $insert_arr['total_receive_fine']= $post_data['total_receive_fine'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('issue_receive_silver', $insert_arr);
            $irs_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Issue/Receive Added Successfully');
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['irs_id'] = $irs_id;
                        $insert_item['type_id'] = $lineitem->type_id;
                        $insert_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['tunch'] = $lineitem->purity;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['actual_tunch'] = $lineitem->actual_tunch;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['irsd_date'] = !empty($lineitem->irsd_date) ? date('Y-m-d', strtotime($lineitem->irsd_date)) : null;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['irsd_remark'] = $lineitem->irsd_remark;
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
                        $this->crud->insert('issue_receive_silver_details', $insert_item);
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
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $account_silver_fine = number_format((float) $account->silver_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_silver_fine = number_format((float) $account_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine, 'silver_fine' => $account_silver_fine), array('account_id' => $post_data['worker_id']));
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
                        } elseif(isset($lineitem->irsd_id) && !empty($lineitem->irsd_id)){
                            $purchase_item_id = $lineitem->irsd_id;
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
                        } elseif(isset($lineitem->irsd_id) && !empty($lineitem->irsd_id)){
                            $purchase_item_id = $lineitem->irsd_id;
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
    
    function update_stock_on_manufacture_update($irs_id =''){
        $issue_receive_silver_details = $this->crud->get_all_with_where('issue_receive_silver_details', '', '', array('irs_id' => $irs_id));
        if(!empty($issue_receive_silver_details)){
            foreach ($issue_receive_silver_details as $lineitem){
                
                $lineitem->fine = $lineitem->net_wt * $lineitem->tunch / 100;
                $lineitem->grwt = $lineitem->weight;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                $department_id = $this->crud->get_column_value_by_id('issue_receive_silver', 'department_id', array('irs_id' => $lineitem->irs_id));
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if($stock_method == '2'){
                    if(!empty($lineitem->purchase_sell_item_id)){
                        $irsd_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $irsd_id = $lineitem->irsd_id;
                    }
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $irsd_id);
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
    
    function update_account_and_department_balance_on_update($irs_id=''){
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $issue_receive_silver_details = $this->crud->get_all_with_where('issue_receive_silver_details', '', '', array('irs_id' => $irs_id));
//        echo '<pre>'; print_r($issue_receive_silver_details); exit;
        $department_id = $this->crud->get_column_value_by_id('issue_receive_silver', 'department_id', array('irs_id' => $irs_id));
        $account_id = $this->crud->get_column_value_by_id('issue_receive_silver', 'worker_id', array('irs_id' => $irs_id));
        if(!empty($issue_receive_silver_details)){
            foreach ($issue_receive_silver_details as $lineitem){
                
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
                $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '');
                $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                $account_silver_fine = number_format((float) $account->silver_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                $account_silver_fine = number_format((float) $account_silver_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $account_gold_fine, 'silver_fine' => $account_silver_fine), array('account_id' => $account_id));
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

    function issue_receive_silver_list() {
        if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"view")) {
            $data = array();
            set_page('manufacture/issue_receive_silver/issue_receive_silver_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function issue_receive_silver_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'issue_receive_silver irs';
        $config['select'] = 'irs.*,a.account_name AS worker,aa.account_name AS department,IF(irs.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(irs.hisab_done = 0,"No","Yes") AS is_hisab_done';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = irs.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = irs.department_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'aa.account_name', 'DATE_FORMAT(irs.irs_date,"%d-%m-%Y")', 'irs.reference_no', 'IF(irs.lott_complete = 0,"No","Yes")', 'IF(irs.hisab_done = 0,"No","Yes")', 'irs.irs_remark');
        $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'irs.irs_date', 'irs.reference_no', null, null, null, null, null, null, null, null,'irs.lott_complete', 'irs.hisab_done', 'irs.irs_remark');

        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 'irs.department_id IN('.$department_ids.')';
        }
        if(!empty($post_data['department_id'])){
            $config['wheres'][] = array('column_name' => 'irs.department_id', 'column_value' => $post_data['department_id']);
        }
        if(!empty($post_data['worker_id'])){
            $config['wheres'][] = array('column_name' => 'irs.worker_id', 'column_value' => $post_data['worker_id']);
        }
        if(isset($post_data['lott_complete'])){
            if($post_data['lott_complete'] == '2'){
                $config['wheres'][] = array('column_name' => 'irs.hisab_done', 'column_value' => '1');
            } else if($post_data['lott_complete'] == '1'){
                $config['wheres'][] = array('column_name' => 'irs.lott_complete', 'column_value' => '1');
                $config['wheres'][] = array('column_name' => 'irs.hisab_done', 'column_value' => '0');
            } else if($post_data['lott_complete'] == 'all'){
                $config['wheres'][] = array('column_name' => 'irs.hisab_done', 'column_value' => '0');
            } else {
                $config['wheres'][] = array('column_name' => 'irs.lott_complete', 'column_value' => $post_data['lott_complete']);
            }
        }

        $config['order'] = array('irs.irs_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'. $this->db->last_query(); exit;
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID, "edit");

        foreach ($list as $irs) {
            $row = array();
            $action = '';
            if($role_edit){
                if($irs->hisab_done != '1'){
                    $action .= '<a href="' . base_url("manufacture_silver/issue_receive_silver/" . $irs->irs_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if($role_delete){
                if($irs->hisab_done != '1'){
                    $action .= '<a href="javascript:void(0);" class="delete_irs" data-href="' . base_url('manufacture_silver/delete_manufacture_irs/' . $irs->irs_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                }
            }
            if($this->applib->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID,"worker_hisab_i_r_silver")) {
                if(isset($post_data['checked_or_not']) && $post_data['checked_or_not'] == '1' && $irs->hisab_done == '0'){
                    $action .= '&nbsp;&nbsp;<input type="checkbox" name="check_irs[]" id="checkbox_id_'.$irs->irs_id.'" class="icheckbox_flat-blue check_irs" value="'.$irs->irs_id.'" data-total_issue_net_wt="'. $irs->total_issue_net_wt .'" data-total_issue_fine="'. $irs->total_issue_fine .'" data-total_receive_net_wt="'. $irs->total_receive_net_wt .'" data-total_receive_fine="'. $irs->total_receive_fine .'">';
                }
            }
            $row[] = $action;
            $row[] = $irs->worker;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-irs_id="' . $irs->irs_id . '" >' . $irs->department. '</a>';
            $row[] = (!empty(strtotime($irs->irs_date))) ? date('d-m-Y', strtotime($irs->irs_date)) : '';
            $row[] = $irs->reference_no;
            if(!empty($irs->total_issue_fine) && !empty($irs->total_issue_net_wt)){
                $issue_avg_tunch = $irs->total_issue_fine * 100 / $irs->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            $row[] = number_format($issue_avg_tunch, 2, '.', '');
            $row[] = number_format($irs->total_issue_net_wt, 3, '.', '');
            $row[] = number_format($irs->total_issue_fine, 3, '.', '');
            if(!empty($irs->total_receive_fine) && !empty($irs->total_receive_net_wt)){
                $receive_avg_tunch = $irs->total_receive_fine * 100 / $irs->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $row[] = number_format($receive_avg_tunch, 2, '.', '');
            $row[] = number_format($irs->total_receive_net_wt, 3, '.', '');
            $row[] = number_format($irs->total_receive_fine, 3, '.', '');
            $balance_net_wt = $irs->total_issue_net_wt - $irs->total_receive_net_wt;
            $row[] = number_format($balance_net_wt, 3, '.', '');
            $balance_fine = $irs->total_issue_fine - $irs->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $lott_complete = $irs->is_lott_complete;
            if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) {
                if($irs->lott_complete == 0) {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-irs_id="'.$irs->irs_id.'" data-lott_complete="1" data-href="'. base_url('manufacture_silver/set_lott_complete_yes_no/'.$irs->irs_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;">';
                } else {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-irs_id="'.$irs->irs_id.'" data-lott_complete=0 data-href="'. base_url('manufacture_silver/set_lott_complete_yes_no/'.$irs->irs_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;" checked="checked">';
                }
            }
            $row[] = $lott_complete;
            $row[] = $irs->is_hisab_done;
            $row[] = $irs->irs_remark;
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
    
    function set_lott_complete_yes_no($irs_id= '') {
        $update_data = array();
        $update_data['lott_complete'] = $_POST['lott_complete'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('issue_receive_silver', $update_data, array('irs_id' => $irs_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function issue_receive_silver_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'issue_receive_silver_details irsd';
        $config['select'] = 'irsd.*,im.item_name, IF(irsd.type_id = 1,"Issue","Receive") AS type';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = irsd.item_id', 'join_type' => 'left');
        $config['column_search'] = array('IF(irsd.type_id = 0,"Issue","Receive")', 'im.item_name', 'irsd.weight', 'irsd.tunch', 'irsd.fine', 'DATE_FORMAT(irsd.irsd_date,"%d-%m-%Y")', 'irsd.irsd_remark');
        $config['column_order'] = array('irsd.type_id', 'im.item_name', 'irsd.weight', NULL, 'irsd.weight', 'irsd.tunch', 'irsd.fine', 'irsd.irsd_date', 'irsd.created_at', 'irsd.updated_at', 'irsd.irsd_remark');
        $config['order'] = array('irsd.irsd_id' => 'desc');
        if (isset($post_data['irs_id']) && !empty($post_data['irs_id'])) {
            $config['wheres'][] = array('column_name' => 'irsd.irs_id', 'column_value' => $post_data['irs_id']);
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
            $row[] = $detail->irsd_date ? date('d-m-Y', strtotime($detail->irsd_date)) : '';
            $row[] = $detail->created_at ? date('d-m-Y H:i:s', strtotime($detail->created_at)) : '';
            $row[] = $detail->updated_at ? date('d-m-Y H:i:s', strtotime($detail->updated_at)) : '';
            $row[] = $detail->irsd_remark;
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
    
    function delete_manufacture_irs($id = '') {
        $where_array = array('irs_id' => $id);
        $issue_receive_silver = $this->crud->get_row_by_id('issue_receive_silver', $where_array);
        $return = array();
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if(!empty($issue_receive_silver)){
            $found = false;
            $issue_receive_silver_details = $this->crud->get_row_by_id('issue_receive_silver_details', $where_array);
            if(!empty($issue_receive_silver_details)){
                foreach($issue_receive_silver_details as $irs_detail){
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $irs_detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $irs_detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $irs_detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $irs_detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $irs_detail->irsd_id, 'stock_type' => '4'));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                    } else if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_receive_or_not($issue_receive_silver[0]->department_id, $irs_detail->category_id, $irs_detail->item_id, $irs_detail->tunch);
                            if(!empty($used_lineitem_ids) && in_array($irs_detail->irsd_id, $used_lineitem_ids)){
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
                $this->crud->delete('issue_receive_silver_details', $where_array);
                $this->crud->delete('issue_receive_silver', $where_array);
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
                        $used_lineitem_ids[] = $purchase_item->irsd_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'R'){
                        $used_lineitem_ids[] = $purchase_item->irsd_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
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
