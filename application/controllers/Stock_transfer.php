<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_transfer extends CI_Controller {

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

    function stock_transfer($stock_transfer_id = '') {
        $data = array();
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $data['use_category'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_category'));
        if (isset($stock_transfer_id) && !empty($stock_transfer_id)) {
            if ($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID, "edit")) {
                $stock_transfer_data = $this->crud->get_data_row_by_id('stock_transfer', 'stock_transfer_id', $stock_transfer_id);
                $stock_transfer_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$stock_transfer_data->created_by));
                if($stock_transfer_data->created_by != $stock_transfer_data->updated_by){
                   $stock_transfer_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$stock_transfer_data->updated_by)); 
                }else{
                   $stock_transfer_data->updated_by_name = $stock_transfer_data->created_by_name;
                }
                $data['stock_transfer_data'] = $stock_transfer_data;
                $stock_transfer_details = $this->crud->get_row_by_id('stock_transfer_detail', array('stock_transfer_id' => $stock_transfer_id));
                $stock_transfer_detail_arr = array();
                foreach($stock_transfer_details as $stock_transfer_detail){
                    $stock_transfer_detail->stock_item_delete = 'allow';
                    
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $stock_transfer_detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                    
                        $stock_details = $this->crud->getFromSQL("SELECT s.stock_transfer_id, sd.grwt AS total_grwt FROM `stock_transfer` `s` LEFT JOIN `stock_transfer_detail` `sd` ON `sd`.`stock_transfer_id`=`s`.`stock_transfer_id` WHERE s.from_department = '".$stock_transfer_data->to_department."' AND `sd`.`category_id` = '".$stock_transfer_detail->category_id."' AND `sd`.`item_id` = '".$stock_transfer_detail->item_id."' AND `sd`.`tunch` = '".$stock_transfer_detail->tunch."'");
                        $stock_transfer_detail->total_grwt_sell = 0;
                        if(!empty($stock_details)){
                            $stock_det = $this->crud->get_row_by_id('stock_transfer_detail', array('category_id' => $stock_transfer_detail->category_id, 'item_id' => $stock_transfer_detail->item_id, 'tunch' => $stock_transfer_detail->tunch, 'stock_transfer_id >' => $stock_transfer_id));
                            if(empty($stock_det)){
                                $stock_transfer_detail->stock_item_delete = 'allow';
                            } else {
                                $stock_transfer_detail->stock_item_delete = 'not_allow';
                                foreach ($stock_det as $stock_dete){
                                    $stock_transfer_detail->total_grwt_sell = (float) $stock_transfer_detail->total_grwt_sell + (float) $stock_dete->grwt;
                                }
                            }
                        } elseif (empty($stock_details) && !empty($stock_det)) {
                            $stock_transfer_detail->stock_item_delete = 'not_allow';
                        }
                        $stock_det_sell = $this->crud->get_stock_transfer_to_sell($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
//                        echo '<pre>'.$this->db->last_query(); exit;
//                        echo '<pre>'; print_r($stock_det_sell); exit;
                        if(!empty($stock_det_sell)){
                            $stock_transfer_detail->stock_item_delete = 'not_allow';
                            foreach ($stock_det_sell as $stock_det_sel){
                                $stock_transfer_detail->total_grwt_sell = (float) $stock_transfer_detail->total_grwt_sell + (float) $stock_det_sel->grwt;
                            }
//                            echo '<pre>'; print_r($stock_transfer_detail->stock_item_delete); exit;
                        }
                        $stock_det_irs = $this->crud->get_stock_transfer_to_issue_receive($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
                        if(!empty($stock_det_irs)){
                            $stock_transfer_detail->stock_item_delete = 'not_allow';
                            foreach ($stock_det_irs as $stock_det_ir){
                                $stock_transfer_detail->total_grwt_sell = (float) $stock_transfer_detail->total_grwt_sell + (float) $stock_det_ir->grwt;
                            }

                        }
                        $stock_det_mhms = $this->crud->get_stock_transfer_to_manu_hand_made($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
                        if(!empty($stock_det_mhms)){
                            $stock_transfer_detail->stock_item_delete = 'not_allow';
                            foreach ($stock_det_mhms as $stock_det_mhm){
                                $stock_transfer_detail->total_grwt_sell = (float) $stock_transfer_detail->total_grwt_sell + (float) $stock_det_mhm->grwt;
                            }

                        }
                    } else { 
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $stock_transfer_detail->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($stock_transfer_data->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id);
                                $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);

                                uasort($purchase_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
                    //            print_r($purchase_items); exit;
                                $purchase_grwt = 0;
                                $first_check_purchase_grwt = 0;

                                foreach ($purchase_delete_array as $purchase_item){
                                    $purchase_grwt = $purchase_grwt + $purchase_item->grwt;
                                    if($purchase_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0){
                                        if($purchase_item->type == 'ST'){
                                            $used_lineitem_ids[] = $purchase_item->transfer_detail_id;
                                            if($stock_transfer_detail->transfer_detail_id == $purchase_item->transfer_detail_id){
                                                $stock_transfer_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $purchase_item->grwt;
                                            }
                                        }
                                        $first_check_purchase_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($purchase_item->type == 'ST'){
                                            $used_lineitem_ids[] = $purchase_item->transfer_detail_id;
                                            if($stock_transfer_detail->transfer_detail_id == $purchase_item->transfer_detail_id){
                                                $stock_transfer_detail->total_grwt_sell = $purchase_item->grwt;
                                            }
                                        }

                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($stock_transfer_detail->transfer_detail_id, $used_lineitem_ids)){
                                $stock_transfer_detail->stock_item_delete = 'not_allow';
                            }
                        }
                    }
                    
//                    echo '<pre>'; print_r($stock_transfer_detail->total_grwt_sell); exit;
                    $stock_transfer_detail->tunch_textbox = (isset($stock_transfer_detail->tunch_textbox) && $stock_transfer_detail->tunch_textbox == '1') ? '1' : '0';
                    $stock_transfer_detail->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $stock_transfer_detail->category_id));
                    $stock_transfer_detail->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $stock_transfer_detail->category_id));
                    $stock_transfer_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $stock_transfer_detail->item_id));
                    $stock_transfer_detail->tunch_name = $stock_transfer_detail->tunch;
                    $stock_transfer_detail->grwt = number_format($stock_transfer_detail->grwt, 3, '.', '');
                    $stock_transfer_detail->less = number_format($stock_transfer_detail->less, 3, '.', '');
                    $stock_transfer_detail->net_wt = number_format($stock_transfer_detail->ntwt, 3, '.', '');
                    $stock_transfer_detail->fine = number_format($stock_transfer_detail->fine, 3, '.', '');
                    $stock_transfer_detail->from_item_stock_rfid_id = (isset($stock_transfer_detail->from_item_stock_rfid_id) && !empty($stock_transfer_detail->from_item_stock_rfid_id)) ? $stock_transfer_detail->from_item_stock_rfid_id : NULL;
                    $stock_transfer_detail->to_item_stock_rfid_id = (isset($stock_transfer_detail->to_item_stock_rfid_id) && !empty($stock_transfer_detail->to_item_stock_rfid_id)) ? $stock_transfer_detail->to_item_stock_rfid_id : NULL;
                    $stock_transfer_detail->rfid_number = (isset($stock_transfer_detail->rfid_number) && !empty($stock_transfer_detail->rfid_number)) ? $stock_transfer_detail->rfid_number : NULL;
                    $stock_transfer_detail_arr[] = json_encode($stock_transfer_detail);
                }
                $data['stock_transfer_detail_arr'] = implode(',', $stock_transfer_detail_arr);
                set_page('stock_transfer/stock_transfer', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID, "add")) {
                set_page('stock_transfer/stock_transfer', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_stock_transfer() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($line_items_data); exit;

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

        $post_data['from_department'] = isset($post_data['from_department']) && !empty($post_data['from_department']) ? $post_data['from_department'] : NULL;
        $post_data['to_department'] = isset($post_data['to_department']) && !empty($post_data['to_department']) ? $post_data['to_department'] : NULL;
        $post_data['transfer_date'] = isset($post_data['transfer_date']) && !empty($post_data['transfer_date']) ? date('Y-m-d', strtotime($post_data['transfer_date'])) : NULL;
        if (isset($post_data['stock_transfer_id']) && !empty($post_data['stock_transfer_id'])) {
            
            // Increase fine in From Department
            $stock_transfer_data = $this->crud->get_data_row_by_id('stock_transfer', 'stock_transfer_id', $post_data['stock_transfer_id']);
            $from_departments = $this->crud->get_row_by_id('account', array('account_id' => $stock_transfer_data->from_department));
            if (!empty($from_departments)) {
                $depart_gold_fine = (float) $from_departments[0]->gold_fine + (float) $stock_transfer_data->total_gold_fine;
                $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                $depart_silver_fine = (float) $from_departments[0]->silver_fine + (float) $stock_transfer_data->total_silver_fine;
                $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $stock_transfer_data->from_department));
            }

            // Decrease fine in To Department
            $to_departments = $this->crud->get_row_by_id('account', array('account_id' => $stock_transfer_data->to_department));
            if (!empty($to_departments)) {
                $depart_gold_fine_to = (float) $to_departments[0]->gold_fine - (float) $stock_transfer_data->total_gold_fine;
                $depart_gold_fine_to = number_format((float) $depart_gold_fine_to, '3', '.', '');
                $depart_silver_fine_to = (float) $to_departments[0]->silver_fine - (float) $stock_transfer_data->total_silver_fine;
                $depart_silver_fine_to = number_format((float) $depart_silver_fine_to, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $depart_gold_fine_to, 'silver_fine' => $depart_silver_fine_to), array('account_id' => $stock_transfer_data->to_department));
            }
            
            $update_arr = array();
            $update_arr['from_department'] = $post_data['from_department'];
            $update_arr['to_department'] = $post_data['to_department'];
            $update_arr['transfer_date'] = $post_data['transfer_date'];
            $update_arr['narration'] = $post_data['narration'];
            $update_arr['total_gold_fine'] = $post_data['total_gold_fine'];
            $update_arr['total_silver_fine'] = $post_data['total_silver_fine'];
            $update_arr['guard_checked'] = '0';
            $update_arr['guard_checked_last_at'] = $this->now_time;
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('stock_transfer', $update_arr, array('stock_transfer_id' => $post_data['stock_transfer_id']));
            $stock_transfer_id = $post_data['stock_transfer_id'];
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Stock Transfer Updated Successfully');
                
                $stock_transfer_details = $this->crud->get_row_by_id('stock_transfer_detail', array('stock_transfer_id' => $stock_transfer_id));
//                $this->update_stock_on_stock_transfer_insert_and_delete($stock_transfer_details, $post_data['to_department'], $post_data['from_department'], 'delete');
                $this->delete_stock_from_stock_transfer($stock_transfer_details, $stock_transfer_data->from_department, $stock_transfer_data->to_department);
                if(isset($post_data['deleted_transfer_detail_id'])){
                    $this->db->where_in('transfer_detail_id', $post_data['deleted_transfer_detail_id']);
                    $this->db->delete('stock_transfer_detail');
                }
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['stock_transfer_id'] = $stock_transfer_id;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['ntwt'] = $lineitem->net_wt;
                        $insert_item['tunch'] = $lineitem->tunch;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $insert_item['fine'] = $lineitem->fine;
                        if(isset($lineitem->from_item_stock_rfid_id)){
                            $insert_item['from_item_stock_rfid_id'] = (isset($lineitem->from_item_stock_rfid_id) && !empty($lineitem->from_item_stock_rfid_id)) ? $lineitem->from_item_stock_rfid_id : NULL;
                        }
                        if(isset($lineitem->to_item_stock_rfid_id)){
                            $insert_item['to_item_stock_rfid_id'] = (isset($lineitem->to_item_stock_rfid_id) && !empty($lineitem->to_item_stock_rfid_id)) ? $lineitem->to_item_stock_rfid_id : NULL;
                        }
                        if(isset($lineitem->rfid_number)){
                            $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        }
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->transfer_detail_id) && !empty($lineitem->transfer_detail_id)){
                            $this->db->where('transfer_detail_id', $lineitem->transfer_detail_id);
                            $this->db->update('stock_transfer_detail', $insert_item);
                            $last_inserted_id = $lineitem->transfer_detail_id;
                        } else {
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('stock_transfer_detail', $insert_item);
                            $last_inserted_id = $this->db->insert_id();
                            $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                        }
                        
                    }
                    $this->update_stock_on_stock_transfer_insert($line_items_data, $post_data['from_department'], $post_data['to_department'], 'insert');

                    // Decrease fine in From Department
                    $from_departments = $this->crud->get_row_by_id('account', array('account_id' => $post_data['from_department']));
                    if (!empty($from_departments)) {
                        $depart_gold_fine = number_format((float) $from_departments[0]->gold_fine, '3', '.', '') - number_format((float) $post_data['total_gold_fine'], '3', '.', '');
                        $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                        $depart_silver_fine = number_format((float) $from_departments[0]->silver_fine, '3', '.', '') - number_format((float) $post_data['total_silver_fine'], '3', '.', '');
                        $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $post_data['from_department']));
                    }

                    // Increase fine in To Department
                    $to_departments = $this->crud->get_row_by_id('account', array('account_id' => $post_data['to_department']));
                    if (!empty($to_departments)) {
                        $depart_gold_fine_to = number_format((float) $to_departments[0]->gold_fine, '3', '.', '') + number_format((float) $post_data['total_gold_fine'], '3', '.', '');
                        $depart_gold_fine_to = number_format((float) $depart_gold_fine_to, '3', '.', '');
                        $depart_silver_fine_to = number_format((float) $to_departments[0]->silver_fine, '3', '.', '') + number_format((float) $post_data['total_silver_fine'], '3', '.', '');
                        $depart_silver_fine_to = number_format((float) $depart_silver_fine_to, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine_to, 'silver_fine' => $depart_silver_fine_to), array('account_id' => $post_data['to_department']));
                    }
                }
            }
            
        } else {
            $insert_arr = array();
            $insert_arr['from_department'] = $post_data['from_department'];
            $insert_arr['to_department'] = $post_data['to_department'];
            $insert_arr['transfer_date'] = $post_data['transfer_date'];
            $insert_arr['narration'] = $post_data['narration'];
            $insert_arr['total_gold_fine'] = $post_data['total_gold_fine'];
            $insert_arr['total_silver_fine'] = $post_data['total_silver_fine'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('stock_transfer', $insert_arr);
            $stock_transfer_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Stock Transfer Added Successfully');
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['stock_transfer_id'] = $stock_transfer_id;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['ntwt'] = $lineitem->net_wt;
                        $insert_item['tunch'] = $lineitem->tunch;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['from_item_stock_rfid_id'] = (isset($lineitem->from_item_stock_rfid_id) && !empty($lineitem->from_item_stock_rfid_id)) ? $lineitem->from_item_stock_rfid_id : NULL;
                        $insert_item['to_item_stock_rfid_id'] = (isset($lineitem->to_item_stock_rfid_id) && !empty($lineitem->to_item_stock_rfid_id)) ? $lineitem->to_item_stock_rfid_id : NULL;
                        $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('stock_transfer_detail', $insert_item);
                        $last_inserted_id = $this->db->insert_id();
//                        $line_items_data[$key]->purchase_item_id = $lot_item_id;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                        
                    }
                    $this->update_stock_on_stock_transfer_insert($line_items_data, $post_data['from_department'], $post_data['to_department'], 'insert');

                    // Decrease fine in From Department
                    $from_departments = $this->crud->get_row_by_id('account', array('account_id' => $post_data['from_department']));
                    if (!empty($from_departments)) {
                        $depart_gold_fine = number_format((float) $from_departments[0]->gold_fine, '3', '.', '') - number_format((float) $post_data['total_gold_fine'], '3', '.', '');
                        $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                        $depart_silver_fine = number_format((float) $from_departments[0]->silver_fine, '3', '.', '') - number_format((float) $post_data['total_silver_fine'], '3', '.', '');
                        $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $post_data['from_department']));
                    }

                    // Increase fine in To Department
                    $to_departments = $this->crud->get_row_by_id('account', array('account_id' => $post_data['to_department']));
                    if (!empty($to_departments)) {
                        $depart_gold_fine_to = number_format((float) $to_departments[0]->gold_fine, '3', '.', '') + number_format((float) $post_data['total_gold_fine'], '3', '.', '');
                        $depart_gold_fine_to = number_format((float) $depart_gold_fine_to, '3', '.', '');
                        $depart_silver_fine_to = number_format((float) $to_departments[0]->silver_fine, '3', '.', '') + number_format((float) $post_data['total_silver_fine'], '3', '.', '');
                        $depart_silver_fine_to = number_format((float) $depart_silver_fine_to, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine_to, 'silver_fine' => $depart_silver_fine_to), array('account_id' => $post_data['to_department']));
                    }
                }
            }
        }
        print json_encode($return);
        exit;
//        echo '<pre>'; print_r($post_data); exit;
    }

    function update_stock_on_stock_transfer_insert($lineitem_data = '', $from_department = '', $to_department = '', $from = '') {
        if (!empty($lineitem_data)) {
//            echo '<pre>'; print_r($lineitem_data);
            foreach ($lineitem_data as $lineitem) {
                if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                    $wstg = $this->crud->get_id_by_val('sell_items', 'wstg', 'sell_item_id', $lineitem->purchase_sell_item_id);
                } else {
                    $wstg = $this->crud->get_min_value('sell_items', 'wstg');
                    $wstg = $wstg->wstg;
                }
                if($from == 'insert'){
                    $lineitem->ntwt = $lineitem->net_wt;
                }
                $lineitem->fine = $lineitem->ntwt *($lineitem->tunch + $wstg) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                // Decrease stock in From department
                $from_department_item_stock_id = '';
                if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                    $where_stock_array = array('department_id' => $from_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $lineitem->purchase_sell_item_id);
                } else {
                    $where_stock_array = array('department_id' => $from_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }
                $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
                if (!empty($exist_item_id)) {
                    $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                    $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                    $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->ntwt, '3', '.', '');
                    $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    $update_item_stock = array();
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($update_item_stock); exit;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    $from_department_item_stock_id = $exist_item_id[0]->item_stock_id;
                } else {
                    $current_stock_grwt = $this->zero_value - number_format((float) $lineitem->grwt, '3', '.', '');
                    $current_stock_less = $this->zero_value - number_format((float) $lineitem->less, '3', '.', '');
                    $current_stock_ntwt = $this->zero_value - number_format((float) $lineitem->ntwt, '3', '.', '');
                    $current_stock_fine = $this->zero_value - number_format((float) $lineitem->fine, '3', '.', '');
                    $insert_item_stock = array();
                    $insert_item_stock['department_id'] = $from_department;
                    $insert_item_stock['category_id'] = $lineitem->category_id;
                    $insert_item_stock['item_id'] = $lineitem->item_id;
                    $insert_item_stock['tunch'] = $lineitem->tunch;
                    $insert_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $insert_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $insert_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $insert_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                    }
                    $insert_item_stock['stock_type'] = '3';
                    $insert_item_stock['created_at'] = $this->now_time;
                    $insert_item_stock['created_by'] = $this->logged_in_id;
                    $insert_item_stock['updated_at'] = $this->now_time;
                    $insert_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($insert_item_stock); exit;
                    $this->crud->insert('item_stock', $insert_item_stock);
                    $from_department_item_stock_id = $this->db->insert_id();
                }
                
                // Increase stock in To department
                $to_department_item_stock_id = '';
                if((isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE)){
                    if(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $to_department;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->tunch;
                        $insert_item_stock['grwt'] = number_format((float) $lineitem->grwt, '3', '.', '');
                        $insert_item_stock['less'] = number_format((float) $lineitem->less, '3', '.', '');
                        $insert_item_stock['ntwt'] = number_format((float) $lineitem->ntwt, '3', '.', '');
                        $insert_item_stock['fine'] = number_format((float) $lineitem->fine, '3', '.', '');
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_item_id;
                        $insert_item_stock['stock_type'] = '3';
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('item_stock', $insert_item_stock);
                        $to_department_item_stock_id = $this->db->insert_id();
                    } else {
                        //if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                          //  $purchase_item_id = $lineitem->purchase_sell_item_id;
                        //} elseif(isset($lineitem->transfer_detail_id) && !empty($lineitem->transfer_detail_id)){
                        if(isset($lineitem->transfer_detail_id) && !empty($lineitem->transfer_detail_id)){
                            $purchase_item_id = $lineitem->transfer_detail_id;
                        } 
                        $where_stock_array = array('department_id' => $to_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id_for_pe = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                        echo '<pre>'; print_r($exist_item_id_for_pe); exit;
//                        echo '<pre>'.$this->db->last_query();
                        if(!empty($exist_item_id_for_pe)){
                            $current_stock_less = number_format((float) $exist_item_id_for_pe[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id_for_pe[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id_for_pe[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->ntwt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id_for_pe[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            $update_item_stock = array();
                            $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                            $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                            $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                            $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                            $update_item_stock['updated_at'] = $this->now_time;
                            $update_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id_for_pe[0]->item_stock_id));
                            $to_department_item_stock_id = $exist_item_id_for_pe[0]->item_stock_id;
                        } else {
                            $insert_item_stock = array();
                            $insert_item_stock['department_id'] = $to_department;
                            $insert_item_stock['category_id'] = $lineitem->category_id;
                            $insert_item_stock['item_id'] = $lineitem->item_id;
                            $insert_item_stock['tunch'] = $lineitem->tunch;
                            $insert_item_stock['grwt'] = number_format((float) $lineitem->grwt, '3', '.', '');
                            $insert_item_stock['less'] = number_format((float) $lineitem->less, '3', '.', '');
                            $insert_item_stock['ntwt'] = number_format((float) $lineitem->ntwt, '3', '.', '');
                            $insert_item_stock['fine'] = number_format((float) $lineitem->fine, '3', '.', '');
                            $insert_item_stock['purchase_sell_item_id'] = $purchase_item_id;
                            $insert_item_stock['stock_type'] = '3';
                            $insert_item_stock['created_at'] = $this->now_time;
                            $insert_item_stock['created_by'] = $this->logged_in_id;
                            $insert_item_stock['updated_at'] = $this->now_time;
                            $insert_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('item_stock', $insert_item_stock);
                            $to_department_item_stock_id = $this->db->insert_id();
                        }
                    }
                } else {
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $where_stock_array = array('department_id' => $to_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $lineitem->purchase_sell_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $to_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                    echo '<pre>'.$this->db->last_query(); exit;
                    if(!empty($exist_item_id)){
                        $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                        $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                        $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->ntwt, '3', '.', '');
                        $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                        $update_item_stock = array();
                        $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                        $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                        $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                        $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                        $update_item_stock['updated_at'] = $this->now_time;
                        $update_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                        $to_department_item_stock_id = $exist_item_id[0]->item_stock_id;
                    } else { 
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $to_department;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->tunch;
                        $insert_item_stock['grwt'] = number_format((float) $lineitem->grwt, '3', '.', '');
                        $insert_item_stock['less'] = number_format((float) $lineitem->less, '3', '.', '');
                        $insert_item_stock['ntwt'] = number_format((float) $lineitem->ntwt, '3', '.', '');
                        $insert_item_stock['fine'] = number_format((float) $lineitem->fine, '3', '.', '');
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $insert_item_stock['stock_type'] = '3';
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
    //                    echo '<pre>'; print_r($insert_item_stock); exit;
                        $this->crud->insert('item_stock', $insert_item_stock);
                        $to_department_item_stock_id = $this->db->insert_id();
                    }
                }
                
                if(isset($lineitem->from_item_stock_rfid_id) && !empty($lineitem->from_item_stock_rfid_id)){
                    
                    if(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                        $transfer_detail_id = $lineitem->purchase_item_id;
                    } else {
                        $transfer_detail_id = $lineitem->transfer_detail_id;
                    }
                    
                    // Update rfid_number rfid_used status for From Department with rfid_created_grwt in item_stock
                    $this->crud->update('item_stock_rfid', array('rfid_used' => '1', 'to_relation_id' => $transfer_detail_id, 'to_module' => RFID_RELATION_MODULE_STOCK_TRANSFER), array('item_stock_rfid_id' => $lineitem->from_item_stock_rfid_id));
                    $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $lineitem->from_item_stock_rfid_id);
                    $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $from_department_item_stock_id));
                    $old_transfer_item_grwt = 0;
//                    if(isset($lineitem->transfer_detail_id) && !empty($lineitem->transfer_detail_id)){
//                        $old_transfer_item_grwt = $this->crud->get_column_value_by_id('stock_transfer_detail', 'grwt', array('transfer_detail_id' => $lineitem->transfer_detail_id));
//                    }
                    $new_rfid_created_grwt = $old_rfid_created_grwt + $old_transfer_item_grwt - $item_stock_rfid->rfid_grwt;
                    $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $from_department_item_stock_id));
                
                    // Create New rfid_number for To Department with rfid_created_grwt in item_stock
                    $to_department_item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $to_department_item_stock_id);
                    $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $lineitem->from_item_stock_rfid_id);
                    
                    $insert_arr = array();
                    $insert_arr['item_stock_id'] = $to_department_item_stock_id;
                    $insert_arr['rfid_grwt'] = number_format((float) $lineitem->grwt, '3', '.', '');
                    $insert_arr['rfid_less'] = number_format((float) $lineitem->less, '3', '.', '');
                    $insert_arr['rfid_add'] = 0;
                    $insert_arr['rfid_ntwt'] = number_format((float) $lineitem->ntwt, '3', '.', '');
                    $insert_arr['rfid_tunch'] = $lineitem->tunch;
                    $insert_arr['rfid_fine'] = number_format((float) $lineitem->fine, '3', '.', '');
                    $insert_arr['real_rfid'] = $item_stock_rfid->real_rfid;
                    $insert_arr['rfid_charges'] = $item_stock_rfid->rfid_charges;
                    $insert_arr['rfid_ad_id'] = $item_stock_rfid->rfid_ad_id;
                    $insert_arr['from_relation_id'] = $transfer_detail_id;
                    $insert_arr['from_module'] = RFID_RELATION_MODULE_STOCK_TRANSFER;
                    $insert_arr['created_at'] = $this->now_time;
                    $insert_arr['created_by'] = $this->logged_in_id;
                    $insert_arr['updated_at'] = $this->now_time;
                    $insert_arr['updated_by'] = $this->logged_in_id;
                    $result = $this->crud->insert('item_stock_rfid', $insert_arr);
                    $item_stock_rfid_id = $this->db->insert_id();
                    
                    $new_rfid_created_grwt = $to_department_item_stock_data->rfid_created_grwt + number_format((float) $lineitem->grwt, '3', '.', '');
                    $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $to_department_item_stock_id));
                    
                    // update to rfid id in stock transfer lineitem
                    $this->crud->update('stock_transfer_detail', array('to_item_stock_rfid_id' => $item_stock_rfid_id), array('transfer_detail_id' => $transfer_detail_id));
                }
            }
        }
    }
    
    function delete_stock_from_stock_transfer($lineitem_data = '', $from_department = '', $to_department = ''){
        if (!empty($lineitem_data)) {
            foreach ($lineitem_data as $lineitem) {
                if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                    $wstg = $this->crud->get_id_by_val('sell_items', 'wstg', 'sell_item_id', $lineitem->purchase_sell_item_id);
                } else {
                    $wstg = $this->crud->get_min_value('sell_items', 'wstg');
                    $wstg = $wstg->wstg;
                }
//                if($from == 'insert'){
//                    $lineitem->ntwt = $lineitem->net_wt;
//                }
                $lineitem->fine = $lineitem->ntwt *($lineitem->tunch + $wstg) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                // Decrease stock in To department
                $to_department_item_stock_id = '';
                if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                    if(isset($lineitem->transfer_detail_id) && !empty($lineitem->transfer_detail_id)){
                        $where_stock_array = array('department_id' => $to_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $lineitem->transfer_detail_id);
                    }
                } else {
                    $where_stock_array = array('department_id' => $to_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }
                $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
//                echo '<pre>'.$this->db->last_query(); exit;
                if (!empty($exist_item_id)) {
                    $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                    $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                    $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->ntwt, '3', '.', '');
                    $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    $update_item_stock = array();
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($update_item_stock); exit;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    $to_department_item_stock_id = $exist_item_id[0]->item_stock_id;
                } else {
                    $current_stock_grwt = $this->zero_value - number_format((float) $lineitem->grwt, '3', '.', '');
                    $current_stock_less = $this->zero_value - number_format((float) $lineitem->less, '3', '.', '');
                    $current_stock_ntwt = $this->zero_value - number_format((float) $lineitem->ntwt, '3', '.', '');
                    $current_stock_fine = $this->zero_value - number_format((float) $lineitem->fine, '3', '.', '');
                    $insert_item_stock = array();
                    $insert_item_stock['department_id'] = $to_department;
                    $insert_item_stock['category_id'] = $lineitem->category_id;
                    $insert_item_stock['item_id'] = $lineitem->item_id;
                    $insert_item_stock['tunch'] = $lineitem->tunch;
                    $insert_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $insert_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $insert_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $insert_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                    }
                    $insert_item_stock['stock_type'] = '3';
                    $insert_item_stock['created_at'] = $this->now_time;
                    $insert_item_stock['created_by'] = $this->logged_in_id;
                    $insert_item_stock['updated_at'] = $this->now_time;
                    $insert_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($insert_item_stock); exit;
                    $this->crud->insert('item_stock', $insert_item_stock);
                    $to_department_item_stock_id = $this->db->insert_id();
                }
                
                // Increase stock in From department
                $from_department_item_stock_id = '';
                if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                    $where_stock_array = array('department_id' => $from_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $lineitem->purchase_sell_item_id);
                } else {
                    $where_stock_array = array('department_id' => $from_department, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }

                $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
//                echo '<pre>'.$this->db->last_query(); exit;
                if (!empty($exist_item_id)) {
                    $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                    $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                    $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->ntwt, '3', '.', '');
                    $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    $update_item_stock = array();
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($update_item_stock); exit;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    $from_department_item_stock_id = $exist_item_id[0]->item_stock_id;
                } else {
                    $insert_item_stock = array();
                    $insert_item_stock['department_id'] = $from_department;
                    $insert_item_stock['category_id'] = $lineitem->category_id;
                    $insert_item_stock['item_id'] = $lineitem->item_id;
                    $insert_item_stock['tunch'] = $lineitem->tunch;
                    $insert_item_stock['grwt'] = number_format((float) $lineitem->grwt, '3', '.', '');
                    $insert_item_stock['less'] = number_format((float) $lineitem->less, '3', '.', '');
                    $insert_item_stock['ntwt'] = number_format((float) $lineitem->ntwt, '3', '.', '');
                    $insert_item_stock['fine'] = number_format((float) $lineitem->fine, '3', '.', '');
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                    }
                    $insert_item_stock['stock_type'] = '3';
                    $insert_item_stock['created_at'] = $this->now_time;
                    $insert_item_stock['created_by'] = $this->logged_in_id;
                    $insert_item_stock['updated_at'] = $this->now_time;
                    $insert_item_stock['updated_by'] = $this->logged_in_id;
//                    echo '<pre>'; print_r($insert_item_stock); exit;
                    $this->crud->insert('item_stock', $insert_item_stock);
                    $from_department_item_stock_id = $this->db->insert_id();
                }
                
                if(isset($lineitem->from_item_stock_rfid_id) && !empty($lineitem->from_item_stock_rfid_id)){
                    
                    // Update rfid_number rfid_used status for To Department with rfid_created_grwt in item_stock
                    $from_department_item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $to_department_item_stock_id);
                    $new_rfid_created_grwt = $from_department_item_stock_data->rfid_created_grwt - number_format((float) $lineitem->grwt, '3', '.', '');
                    $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $to_department_item_stock_id));
                    // Delete To rfid id
                    $this->crud->delete('item_stock_rfid', array('item_stock_rfid_id' => $lineitem->to_item_stock_rfid_id));
                
                    // Update rfid_number rfid_used status for From Department with rfid_created_grwt in item_stock
                    $check_item_stock_rfid = $this->crud->get_row_by_id('item_stock_rfid', array('real_rfid' => $lineitem->rfid_number, 'rfid_used' => '0'));
                    if(empty($check_item_stock_rfid)){
                        $this->crud->update('item_stock_rfid', array('rfid_used' => '0', 'to_relation_id' => NULL, 'to_module' => NULL), array('item_stock_rfid_id' => $lineitem->from_item_stock_rfid_id));

                        $from_department_item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $from_department_item_stock_id);
                        $new_rfid_created_grwt = $from_department_item_stock_data->rfid_created_grwt + number_format((float) $lineitem->grwt, '3', '.', '');
                        $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $from_department_item_stock_id));
                    } else {
                        $this->crud->update('item_stock_rfid', array('to_relation_id' => NULL, 'to_module' => RFID_RELATION_MODULE_STOCK_TRANSFER_DELETE), array('item_stock_rfid_id' => $lineitem->from_item_stock_rfid_id));
                    }
                }
                
            }
            // Delete Empty Item Stock Data of itemwise item
            $this->db->where('category_id', EXCHANGE_DEFAULT_CATEGORY_ID)->where('item_id', EXCHANGE_DEFAULT_ITEM_ID)->where('grwt', '0')->where('ntwt', '0')->delete('item_stock');
        }
    }

    function stock_transfer_list() {
        if ($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID, "view")) {
            set_page('stock_transfer/stock_transfer_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function stock_transfer_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'stock_transfer s';
        $config['select'] = 's.*,pm.account_name as from,p.account_name as to';
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = s.from_department', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.to_department', 'join_type' => 'left');
        $config['column_search'] = array('s.stock_transfer_id', 'pm.account_name', 'p.account_name', 'DATE_FORMAT(s.transfer_date,"%   d-%m-%Y")', 's.narration');
        $config['column_order'] = array(null, 's.guard_checked', 's.stock_transfer_id', 'p.account_name', 'pm.account_name', 's.transfer_date', 's.narration');
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 's.from_department IN('.$department_ids.')';
        }
        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date']) && strtotime($post_data['from_date']) > 0){
                $config['wheres'][] = array('column_name' => 's.transfer_date >=', 'column_value' => date("Y-m-d",strtotime($post_data['from_date'])));
            }
        }
        if(!empty($post_data['to_date']) && strtotime($post_data['to_date']) > 0){
            $config['wheres'][] = array('column_name' => 's.transfer_date <=', 'column_value' => date("Y-m-d",strtotime($post_data['to_date'])));
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 's.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }
        if (isset($post_data['guard_checked_filter']) && $post_data['guard_checked_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 's.guard_checked', 'column_value' => $post_data['guard_checked_filter']);
        }
        $config['order'] = array('s.stock_transfer_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "edit");
        foreach ($list as $sell) {
            $row = array();
            $date = (!empty(strtotime($sell->transfer_date))) ? date('d-m-Y', strtotime($sell->transfer_date)) : '';
            $action = '';
            
            if($sell->audit_status != AUDIT_STATUS_AUDITED){
                if ($role_edit) {
                    $action .= '<a href="' . base_url("stock_transfer/stock_transfer/" . $sell->stock_transfer_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if ($role_delete) {
                    $action .= '<a href="javascript:void(0);" class="delete_stock_transfer" data-href="' . base_url('stock_transfer/delete_stock_transfer/' . $sell->stock_transfer_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            } else {
                $action .= '<a href="' . base_url("stock_transfer/stock_transfer/" . $sell->stock_transfer_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            $audit_status = '';
            if($sell->audit_status == AUDIT_STATUS_AUDITED){
                $audit_status = 'A';
            } else if($sell->audit_status == AUDIT_STATUS_SUSPECTED){
                $audit_status = 'S';
            } else {
                $audit_status = 'P';
            }
            $action .= '<a href="javascript:void(0);" class="audit_status_button" data-audit_status_pay_rec_id="' . $sell->stock_transfer_id . '" data-audit_status="' . $sell->audit_status . '" style="margin: 8px;">'. $audit_status .'</a> &nbsp; ';
            $guard = '';
            if ($this->applib->have_access_role(STOCK_TRANSFER_MODULE_ID, "is guard")) {
                $guard_checked = '';
                if($sell->guard_checked == '1'){
                    $guard_checked = 'G &nbsp; <i class="fa fa-check text-green"></i> &nbsp;';
                } else {
                    $guard_checked = 'G &nbsp; <i class="fa fa-close text-red"></i> &nbsp;';
                }
                $guard .= '<a href="javascript:void(0);" class="guard_checked_button" data-guard_checked_stock_transfer_id="' . $sell->stock_transfer_id . '" data-guard_checked="' . $sell->guard_checked . '" style="margin: 8px;">'. $guard_checked .'</a> &nbsp;';
            }
            
            $row[] = $action;
            $row[] = $guard;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->stock_transfer_id . '" >' . $sell->stock_transfer_id . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->stock_transfer_id . '" >' . $sell->from . '</a>';
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $sell->stock_transfer_id . '" >' . $sell->to . '</a>';
            $row[] = $date;
            $row[] = $sell->narration;
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

    function stock_transfer_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'stock_transfer_detail si';
        $config['select'] = 'si.*,im.item_name,c.category_name';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = si.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= si.category_id', 'join_type' => 'left');
        $config['column_order'] = array('c.category_name', 'im.item_name', 'si.tunch', 'si.grwt', 'si.less', 'si.ntwt', 'si.wstg', 'si.fine');
        $config['column_search'] = array('c.category_name', 'im.item_name', 'si.tunch', 'si.grwt', 'si.less', 'si.ntwt', 'si.wstg', 'si.fine');
        $config['order'] = array('si.transfer_detail_id' => 'desc');
        if (isset($post_data['stock_transfer_id']) && !empty($post_data['stock_transfer_id'])) {
            $config['wheres'][] = array('column_name' => 'si.stock_transfer_id', 'column_value' => $post_data['stock_transfer_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->category_name;
            $row[] = $sell_detail->item_name;
            $row[] = $sell_detail->tunch;
            $row[] = number_format($sell_detail->grwt, 3, '.', '');
            $row[] = number_format($sell_detail->less, 3, '.', '');
            $row[] = number_format($sell_detail->ntwt, 3, '.', '');
            $row[] = $sell_detail->wstg;
            $row[] = number_format($sell_detail->fine, 3, '.', '');
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
    
    function audit_status_stock() {
        $return = array();
        if(isset($_POST['audit_status_pay_rec_id']) && !empty($_POST['audit_status_pay_rec_id']) && isset($_POST['audit_status']) && !empty($_POST['audit_status'])){
            $result = $this->crud->update('stock_transfer', array('audit_status' => $_POST['audit_status']), array('stock_transfer_id' => $_POST['audit_status_pay_rec_id']));
            if ($result) {
                $return['success'] = "Changed";
            } else {
                $return['errro'] = "Error";
            }
        } else {
            $return['errro'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function delete_stock_transfer($id = '') {
        $return = array();
        $where_array = array('stock_transfer_id' => $id);
        $stock_transfer = $this->crud->get_data_row_by_id('stock_transfer', 'stock_transfer_id', $id);
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if(!empty($stock_transfer)){
            $stock_transfer_details = $this->crud->get_row_by_id('stock_transfer_detail', array('stock_transfer_id' => $id));
            $found = false;
            if(!empty($stock_transfer_details)){
                foreach ($stock_transfer_details as $stock_transfer_detail){
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $stock_transfer_detail->item_id));
                    if($without_purchase_sell_allow == '0' && ($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE)){
                        $found = false;
                    } else {
                        $stock_details = $this->crud->getFromSQL("SELECT s.stock_transfer_id FROM `stock_transfer` `s` LEFT JOIN `stock_transfer_detail` `sd` ON `sd`.`stock_transfer_id`=`s`.`stock_transfer_id` WHERE s.from_department = '".$stock_transfer->to_department."' AND `sd`.`category_id` = '".$stock_transfer_detail->category_id."' AND `sd`.`item_id` = '".$stock_transfer_detail->item_id."' AND `sd`.`tunch` = '".$stock_transfer_detail->tunch."'");
                        $stock_det = $this->crud->get_row_by_id('stock_transfer_detail', array('category_id' => $stock_transfer_detail->category_id, 'item_id' => $stock_transfer_detail->item_id, 'tunch' => $stock_transfer_detail->tunch, 'stock_transfer_id > ' => $id));
                        if(!empty($stock_details)){
                            if(!empty($stock_det)){
                                $found = true;
                            } 
                        } //elseif (empty($stock_details) && !empty($stock_det)) {
                            //$found = true;
                        //} 
                        $stock_det_sell = $this->crud->get_stock_transfer_to_sell($stock_transfer->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
                        if(!empty($stock_det_sell)){
                            $found = true;
                        }
                        $stock_det_ir = $this->crud->get_stock_transfer_to_issue_receive($stock_transfer->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
                        if(!empty($stock_det_ir)){
                            $found = true;
                        }
                        $stock_det_mhm = $this->crud->get_stock_transfer_to_manu_hand_made($stock_transfer->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch, $stock_transfer_detail->transfer_detail_id);
                        if(!empty($stock_det_mhm)){
                            $found = true;
                        }
                    }

                    
                    if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_sell_or_not($stock_transfer->to_department, $stock_transfer_detail->category_id, $stock_transfer_detail->item_id, $stock_transfer_detail->tunch);
                            if(!empty($used_lineitem_ids) && in_array($stock_transfer_detail->transfer_detail_id, $used_lineitem_ids)){
                                $found = true;
                            }
                        }
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {

                // Increase fine in From Department
                $from_departments = $this->crud->get_data_row_by_id('account', 'account_id', $stock_transfer->from_department);
                if (!empty($from_departments)) {
                    $depart_gold_fine = number_format((float) $from_departments->gold_fine, '3', '.', '') + number_format((float) $stock_transfer->total_gold_fine, '3', '.', '');
                    $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                    $depart_silver_fine = number_format((float) $from_departments->silver_fine, '3', '.', '') + number_format((float) $stock_transfer->total_silver_fine, '3', '.', '');
                    $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $stock_transfer->from_department));
                }

                // Decrease fine in To Department
                $to_departments = $this->crud->get_data_row_by_id('account', 'account_id', $stock_transfer->to_department);
                if (!empty($to_departments)) {
                    $depart_gold_fine_to = number_format((float) $to_departments->gold_fine, '3', '.', '') - number_format((float) $stock_transfer->total_gold_fine, '3', '.', '');
                    $depart_gold_fine_to = number_format((float) $depart_gold_fine_to, '3', '.', '');
                    $depart_silver_fine_to = number_format((float) $to_departments->silver_fine, '3', '.', '') - number_format((float) $stock_transfer->total_silver_fine, '3', '.', '');
                    $depart_silver_fine_to = number_format((float) $depart_silver_fine_to, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $depart_gold_fine_to, 'silver_fine' => $depart_silver_fine_to), array('account_id' => $stock_transfer->to_department));
                }

//                $this->update_stock_on_stock_transfer_insert_and_delete($stock_transfer_details, $stock_transfer->to_department, $stock_transfer->from_department, 'delete');
                $this->delete_stock_from_stock_transfer($stock_transfer_details, $stock_transfer->from_department, $stock_transfer->to_department);
                $this->crud->delete('stock_transfer_detail', $where_array);
                $this->crud->delete('stock_transfer', $where_array);
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function check_default_item_sell_or_not($department_id, $category_id, $item_id, $touch_id){
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
            $transfer_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
            
            uasort($transfer_delete_array, function($a, $b) {
                $value1 = strtotime($a->created_at);
                $value2 = strtotime($b->created_at);
                return $value1 - $value2;
            });
            
            $transfer_grwt = 0;
            $first_check_purchase_grwt = 0;
        
            foreach ($transfer_delete_array as $transfer_item){
                $transfer_grwt = $transfer_grwt + $transfer_item->grwt;
                if($transfer_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0){
                    if($transfer_item->type == 'ST'){
                        $used_lineitem_ids[] = $transfer_item->transfer_detail_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($transfer_grwt <= $total_sell_grwt){
                    if($transfer_item->type == 'ST'){
                        $used_lineitem_ids[] = $transfer_item->transfer_detail_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }

    function get_ntwt_and_fine_from_stock() {
//        echo '<pre>'; print_r($_POST); exit;
        $data = array();
        $stock_data = $this->crud->get_all_with_where('item_stock', '', '', array('department_id' => $_POST['from_department'], 'category_id' => $_POST['category_id'], 'item_id' => $_POST['item_id'], 'tunch' => $_POST['tunch']));
        $st_default_wastage = $this->crud->get_column_value_by_id('item_master', 'st_default_wastage', array('category_id' => $_POST['category_id'], 'item_id' => $_POST['item_id']));
        if(!empty($stock_data)){
            $data['grwt'] = $stock_data[0]->grwt;
            $data['less'] = $stock_data[0]->less;
            $data['net_weight'] = $stock_data[0]->ntwt;
            $data['fine'] = $stock_data[0]->fine;
            $data['st_default_wastage'] = isset($st_default_wastage) && !empty($st_default_wastage) ? $st_default_wastage : '';
        }
        print json_encode($data);
        exit;
    }
    
    function guard_checked_stock_transfer() {
        $return = array();
        if(isset($_POST['guard_checked_stock_transfer_id']) && !empty($_POST['guard_checked_stock_transfer_id'])){
            $guard_checked = (isset($_POST['guard_checked']) && !empty($_POST['guard_checked'])) ? 1 : 0;
            $guard_checked_narration = (isset($_POST['guard_checked_narration']) && !empty($_POST['guard_checked_narration'])) ? $_POST['guard_checked_narration'] : NULL;
            $update_data_arr = array();
            $update_data_arr['guard_checked'] = $guard_checked;
            $update_data_arr['guard_checked_narration'] = $guard_checked_narration;
            if(isset($_POST['guard_checked_first_at_hidden']) && empty($_POST['guard_checked_first_at_hidden'])){
                $update_data_arr['guard_checked_first_at'] = $this->now_time;
            }
            $update_data_arr['guard_checked_last_at'] = $this->now_time;
            $result = $this->crud->update('stock_transfer', $update_data_arr, array('stock_transfer_id' => $_POST['guard_checked_stock_transfer_id']));
            if ($result) {
                $return['success'] = "Changed";
            } else {
                $return['errro'] = "Error";
            }
        } else {
            $return['errro'] = "Error";
        }
        print json_encode($return);
        exit;
    }
    
    function get_stock_transfer_details() {
        $return = array();
        if(isset($_POST['guard_checked_stock_transfer_id']) && !empty($_POST['guard_checked_stock_transfer_id'])){
            $stock_transfer_data = $this->crud->get_data_row_by_id('stock_transfer', 'stock_transfer_id', $_POST['guard_checked_stock_transfer_id']);
            $stock_transfer_data->guard_checked_first_at = !empty($stock_transfer_data->guard_checked_first_at) ? date ('d-m-Y h:i A', strtotime($stock_transfer_data->guard_checked_first_at)) : '';
            $stock_transfer_data->guard_checked_last_at = !empty($stock_transfer_data->guard_checked_last_at) ? date ('d-m-Y h:i A', strtotime($stock_transfer_data->guard_checked_last_at)) : '';
            $return = $stock_transfer_data;
        }
        print json_encode($return);
        exit;
    }
    
    function get_lineitem_based_on_rfid() {
        $sell_lineitem = array();
        $from_department = $_POST['from_department'];
        $from_item_stock_rfid_id = $_POST['from_item_stock_rfid_id'];
        $rfid_number = $_POST['rfid_number'];
        $item_stock_rfid_sql = 'SELECT isr.* FROM `item_stock_rfid` isr ';
        $item_stock_rfid_sql .= 'JOIN `item_stock` i_s ON i_s.`item_stock_id` = isr.`item_stock_id` ';
        $item_stock_rfid_sql .= 'WHERE (`real_rfid` = "'.$rfid_number.'" OR `item_stock_rfid_id` = "'.$rfid_number.'")';
        $item_stock_rfid_sql .= ' AND i_s.`department_id` = "'.$from_department.'"';
        $item_stock_rfid_sql .= ' ORDER BY `isr`.`item_stock_rfid_id` DESC';
        $item_stock_rfid_data = $this->crud->getFromSQL($item_stock_rfid_sql);
        if (!empty($item_stock_rfid_data)) {
            foreach ($item_stock_rfid_data as $item_stock_rfid){
                if($item_stock_rfid->rfid_used == '1' && empty($from_item_stock_rfid_id)){
                    $check_rfid_used_json = $this->check_where_rfid_used($item_stock_rfid->to_module, $item_stock_rfid->to_relation_id);
                    print $check_rfid_used_json;
                    exit;
                }
                $item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $item_stock_rfid->item_stock_id);
                $sell_lineitem['category_id'] = $item_stock_data->category_id;
                $sell_lineitem['item_id'] = $item_stock_data->item_id;
                $sell_lineitem['less_allow'] = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $item_stock_data->item_id));
                $sell_lineitem['grwt'] = number_format($item_stock_rfid->rfid_grwt, 3, '.', '');
                $less = $item_stock_rfid->rfid_less - $item_stock_rfid->rfid_add;
                $sell_lineitem['less'] = number_format($less, 3, '.', '');
                $sell_lineitem['net_wt'] = number_format($item_stock_rfid->rfid_ntwt, 3, '.', '');
                $sell_lineitem['wstg'] = '0';
                $sell_lineitem['touch_id'] = $item_stock_rfid->rfid_tunch;
                $sell_lineitem['fine'] = number_format($item_stock_rfid->rfid_fine, 3, '.', '');
                $sell_lineitem['default_wstg'] = '';
                $sell_lineitem['charges_amt'] = $item_stock_rfid->rfid_charges;
                $sell_lineitem['from_item_stock_rfid_id'] = $item_stock_rfid->item_stock_rfid_id;
                $sell_lineitem['rfid_number'] = $item_stock_rfid->real_rfid;
//                echo '<pre>'; print_r($sell_lineitem); exit;
                print json_encode($sell_lineitem);
                exit;
            }
        }
        print json_encode($sell_lineitem);
        exit;
    }
    
    function check_where_rfid_used($to_module, $to_relation_id) {
        $check_rfid_used_arr = array();
        $check_rfid_used_arr['rfid_used'] = '1';
        $check_rfid_used_arr['rfid_used_msg'] = 'RFID Used!!';
        if($to_module == RFID_RELATION_MODULE_SELL){
            $check_rfid_used_sql = 'SELECT s.`sell_no` FROM `sell` s ';
            $check_rfid_used_sql .= 'JOIN `sell_items` si ON si.`sell_id` = s.`sell_id` ';
            $check_rfid_used_sql .= 'WHERE si.`sell_item_id` = "'.$to_relation_id.'"';
            $check_rfid_used_data = $this->crud->getFromSQL($check_rfid_used_sql);
            if(!empty($check_rfid_used_data)){
                $check_rfid_used_arr['rfid_used_msg'] = 'RFID Used in '. $check_rfid_used_data[0]->sell_no . ' Sell No. !!';
            }
        } else if($to_module == RFID_RELATION_MODULE_STOCK_TRANSFER){
            $check_rfid_used_sql = 'SELECT st.`stock_transfer_id` FROM `stock_transfer` st ';
            $check_rfid_used_sql .= 'JOIN `stock_transfer_detail` std ON std.`stock_transfer_id` = st.`stock_transfer_id` ';
            $check_rfid_used_sql .= 'WHERE std.`transfer_detail_id` = "'.$to_relation_id.'"';
            $check_rfid_used_data = $this->crud->getFromSQL($check_rfid_used_sql);
            if(!empty($check_rfid_used_data)){
                $check_rfid_used_arr['rfid_used_msg'] = 'RFID Used in '. $check_rfid_used_data[0]->stock_transfer_id . ' Stock Transfer Ref. No. !!';
            }
        }
        return json_encode($check_rfid_used_arr);
    }

}
