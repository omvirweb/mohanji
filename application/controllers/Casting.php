<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Casting extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;
    public $casting_department_id = 0;

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
        $this->casting_department_id = CASTING_DEPARTMENT_ACCOUNT_ID;
    }

    
    function casting_entry($ce_id = '') {
        $data = array();
        $casting_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if (!empty($ce_id)) {
            if($this->applib->have_access_role(CASTING_MODULE_ID,"edit")) {

                $casting_data = $this->crud->get_row_by_id('casting_entry', array('ce_id' => $ce_id));
                $casting_order_items = $this->crud->get_row_by_id('casting_entry_order_items', array('ce_id' => $ce_id));
                $casting_details = $this->crud->get_row_by_id('casting_entry_details', array('ce_id' => $ce_id));
                $casting_files_details = $this->crud->get_row_by_id('casting_entry_design_files', array('ce_id' => $ce_id));
                $data['casting_files_details'] = $casting_files_details;
                $casting_data = $casting_data[0];

                $casting_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$casting_data->created_by));
                if($casting_data->created_by != $casting_data->updated_by){
                    $casting_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $casting_data->updated_by));
                }else{
                    $casting_data->updated_by_name = $casting_data->created_by_name;
                }
                
                $data['casting_data'] = $casting_data;
                
                $checked_order_items = array();
                if(!empty($casting_order_items)){
                    foreach ($casting_order_items as $order_item){
                        $checked_order_items[] = json_encode($order_item);
                    }
                }
                $data['checked_order_items'] = implode(',', $checked_order_items);
                
                $lineitems = array();
                foreach($casting_details as $detail){
                    
                    $casting_detail->ce_item_delete = 'allow';
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){

                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_transfer)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_transfer)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_issue_receive)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_issue_receive)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_manu_hand_made)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_manu_hand_made)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_casting_entry = $this->crud->get_row_by_id('casting_entry_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_casting_entry)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }
                        $item_casting_entry = $this->crud->get_row_by_id('casting_entry_details', array('purchase_sell_item_id' => $detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_casting_entry)){
                            $casting_detail->ce_item_delete = 'not_allow';
                        }

                        $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$detail->ce_detail_id.'" AND s.process_id = "'.$this->casting_department_id.'"');
                        $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="'.$detail->ce_detail_id.'" AND s.from_department = "'.$this->casting_department_id.'"');
                        $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="'.$detail->ce_detail_id.'" AND ir.department_id = "'.$this->casting_department_id.'"');
                        $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$detail->ce_detail_id.'" AND mhm.department_id = "'.$this->casting_department_id.'"');
                        $casting_entry_info = $this->crud->getFromSQL('SELECT SUM(ce_detail.weight) as total_grwt FROM casting_entry_details ce_detail JOIN casting_entry ce ON ce.ce_id = ce_detail.ce_id WHERE ce_detail.purchase_sell_item_id ="'.$detail->ce_detail_id.'" AND ce.department_id = "'.$this->casting_department_id.'"');
                        $casting_detail->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt + $casting_entry_info[0]->total_grwt;
                    } else {
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_casting_entry_grwt = $this->crud->get_total_casting_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($this->casting_department_id, $detail->category_id, $detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_casting_entry_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $casting_detail->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $casting_entry_receive_items = $this->crud->get_casting_receive_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($this->casting_department_id, $detail->category_id, $detail->item_id);
                                $receive_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $casting_entry_receive_items, $other_purchase_items);
    
                                uasort($receive_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
    
                                $purchase_grwt = 0;
                                $first_check_receive_grwt = 0;

                                /*foreach ($receive_delete_array as $receive_item){
                                    $purchase_grwt = $purchase_grwt + $receive_item->grwt;
                                    if($purchase_grwt >= $total_sell_grwt && $first_check_receive_grwt == 0){
                                        if($receive_item->type == 'CASTING_R'){
                                            $used_lineitem_ids[] = $receive_item->ce_detail_id;
                                            if($detail->ce_detail_id == $receive_item->ce_detail_id){
                                                $casting_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $receive_item->grwt;
                                            }
                                        }
                                        $first_check_receive_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($receive_item->type == 'CASTING_R'){
                                            $used_lineitem_ids[] = $receive_item->ce_detail_id;
                                            if($detail->ce_detail_id == $receive_item->ce_detail_id){
                                                $casting_detail->total_grwt_sell = $receive_item->grwt;
                                            }
                                        }
                                    }
                                }*/
                            }
                            if(!empty($used_lineitem_ids) && in_array($detail->ce_detail_id, $used_lineitem_ids)){
                                $casting_detail->ce_item_delete = 'not_allow';
                            }
                        }
                    }
                
                    $detail_type = '';
                    if($detail->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID){
                        $detail_type = 'Issue Finish Work';
                    } else if($detail->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
                        $detail_type = 'Issue Scrap';
                    } else if($detail->type_id == CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID){
                        $detail_type = 'Receive Finish Work';
                    } else if($detail->type_id == CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID){
                        $detail_type = 'Receive Scrap';
                    }
                    $casting_detail->type_name = $detail_type;
                    $casting_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $casting_detail->purity = $detail->tunch;
                    $casting_detail->weight = $detail->weight;
                    $casting_detail->grwt = $detail->weight;
                    $casting_detail->less = $detail->less;
                    $casting_detail->net_wt = $detail->net_wt;
                    $casting_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $casting_detail->fine = $detail->fine;
                    $casting_detail->pcs = $detail->pcs;
                    $casting_detail->ad_pcs = $detail->ad_pcs;
                    $casting_detail->ad_weight = $detail->ad_weight;
                    $casting_detail->ce_detail_date = $detail->ce_detail_date ? date('d-m-Y', strtotime($detail->ce_detail_date)) : '';
                    $casting_detail->ce_detail_remark = $detail->ce_detail_remark;
                    $casting_detail->ce_detail_id = $detail->ce_detail_id;
                    $casting_detail->type_id = $detail->type_id;
                    $casting_detail->item_id = $detail->item_id;
                    $casting_detail->touch_id = $detail->tunch;
                    $casting_detail->wstg = '0';
                    $casting_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $casting_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $casting_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($casting_detail);
                }
                $data['ce_detail'] = implode(',', $lineitems);
                set_page('casting/casting_entry', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(CASTING_MODULE_ID,"add")) {
                $lineitems = array();
                set_page('casting/casting_entry', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }
    
    function new_order_casting_item_datatable($from_casting_status_id = '', $ce_id = '') {
        $return = array();
        $post_data = $this->input->post();
//        echo "<pre>"; print_r($post_data); exit;
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,o.order_date,o.order_no,o.delivery_date,pm.account_name AS process_name,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'new_order o', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = o.process_id', 'join_type' => 'left');
//        $config['joins'][] = array('join_table' => 'casting_entry_order_items ceoi', 'join_by' => 'ceoi.order_lot_item_id = li.order_lot_item_id', 'join_type' => 'left');
        $config['custom_where'] = ' 1 ';
        $config['custom_where'] .= ' AND o.process_id = '.$this->casting_department_id.' ';
        $config['custom_where'] .= ' AND li.item_status_id = 1 ';
        if(!empty($ce_id)){
            if($from_casting_status_id == '1'){
                $ce_ch_items = $this->crud->getFromSQL(' SELECT order_lot_item_id FROM casting_entry_order_items WHERE ce_id = '.$ce_id.' ');
                $ce_other_not_items = $this->crud->getFromSQL(' SELECT order_lot_item_id FROM casting_entry_order_items WHERE ce_id != '.$ce_id.' ');
                $ce_other_not_items_arr = array();
                if(!empty($ce_other_not_items) && !empty($ce_ch_items)){
                    foreach ($ce_other_not_items as $key => $ce_other_not_itm){
                        foreach ($ce_ch_items as $ce_ch_item){
                            if($ce_ch_item->order_lot_item_id == $ce_other_not_itm->order_lot_item_id){
                                if(isset($ce_other_not_items[$key])){
                                    unset($ce_other_not_items[$key]);
                                } else {
                                    $ce_other_not_items_arr[] = $ce_other_not_itm->order_lot_item_id;
                                }
                            }
                        }
                        
                    }
                }
                if(empty($ce_other_not_items_arr)){
                    $ce_other_not_items_arr[] = '-1';
                }
                $ce_other_not_items_arr = implode(',', $ce_other_not_items_arr);
                $config['custom_where'] .= ' AND (li.order_lot_item_id NOT IN ('.$ce_other_not_items_arr.' ))';
            } else {
                $ce_ch_items = $this->crud->getFromSQL(' SELECT order_lot_item_id FROM casting_entry_order_items WHERE ce_id = '.$ce_id.' ');
                $ce_other_items = $this->crud->getFromSQL(' SELECT ceoi.order_lot_item_id FROM casting_entry as ce LEFT JOIN casting_entry_order_items as ceoi ON ceoi.ce_id = ce.ce_id WHERE ceoi.is_ahead = 0 AND ce.to_casting_status_id = '.$from_casting_status_id.' ');
                $ce_items_arr = array();
                if(!empty($ce_other_items)){
                    foreach ($ce_other_items as $key => $ce_other_itm){
                        $ce_items_arr[] = $ce_other_itm->order_lot_item_id;
                    }
                }
                if(!empty($ce_ch_items)){
                    foreach ($ce_ch_items as $ce_ch_item){
                        $ce_items_arr[] = $ce_ch_item->order_lot_item_id;
                    }
                }
                if(empty($ce_items_arr)){
                    $ce_items_arr[] = '-1';
                }
                $ce_items_arr = implode(',', $ce_items_arr);
                $config['custom_where'] .= ' AND (li.order_lot_item_id IN ('.$ce_items_arr.'))';
            }
        } else {
            if($from_casting_status_id == '1'){
                $config['custom_where'] .= ' AND (li.order_lot_item_id NOT IN (SELECT order_lot_item_id FROM casting_entry_order_items))';
            } else {
                $config['custom_where'] .= ' AND (li.order_lot_item_id IN (SELECT ceoi.order_lot_item_id FROM casting_entry as ce LEFT JOIN casting_entry_order_items as ceoi ON ceoi.ce_id = ce.ce_id WHERE ceoi.is_ahead = 0 AND ce.to_casting_status_id = '.$from_casting_status_id.'))';
            }
        }
        $config['order'] = array('li.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $total_purity = 0;
        $total_weight = 0;
        $total_pcs = 0;
        foreach ($list as $lot_detail) {
            $row = array();
            $row['order_id'] = $lot_detail->order_id;
            $row['item_id'] = $lot_detail->item_id;
            $row['item_name'] = $lot_detail->item_name;
            $row['category_name'] = $lot_detail->category_name;
            $row['order_no'] = $lot_detail->order_no;
            $row['order_date'] = (!empty(strtotime($lot_detail->order_date))) ? date('d-m-Y', strtotime($lot_detail->order_date)) : '';
            $row['delivery_date'] = (!empty(strtotime($lot_detail->delivery_date))) ? date('d-m-Y', strtotime($lot_detail->delivery_date)) : '';
            $row['purity'] = $lot_detail->purity;
            $total_purity = $total_purity + $lot_detail->purity;
            $row['weight'] = number_format($lot_detail->weight, 3, '.', '');
            $total_weight = $total_weight + number_format($lot_detail->weight, 3, '.', '');
            $row['pcs'] = $lot_detail->pcs;
            $row['item_img'] = base_url($lot_detail->image);
            $row['order_lot_item_id'] = $lot_detail->order_lot_item_id;
            $total_pcs = $total_pcs + $lot_detail->pcs;
            $data[] = $row;
        }

        $return['order_items'] = $data;
        $return['total_weight'] = number_format($total_weight, 3, '.', '');
        $return['total_pcs'] = $total_pcs;
        echo json_encode($return);
    }
    
    function save_casting_entry(){
        $return = array();
        $post_data = $this->input->post();
//        echo "<pre>"; print_r(json_decode($post_data['line_items_data'])); exit;
        
        // For design file upload >> Start << //
        $extension = array("jpeg","jpg","png");
        $new_file_names = array();
        $img_inc = 0;
        foreach($_FILES["design_files"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["design_files"]["name"][$key];
            $file_tmp = $_FILES["design_files"]["tmp_name"][$key];
            $ext = pathinfo($file_name,PATHINFO_EXTENSION);
            if(in_array($ext,$extension)) {
                $filename = substr(basename($file_name,$ext), 0, -1);
                $newFileName = $filename."_".time()."_".$img_inc.".".$ext;
                move_uploaded_file($file_tmp=$_FILES["design_files"]["tmp_name"][$key],"uploads/casting_images/".$newFileName);
                $new_file_names[] = $newFileName;
            }
            $img_inc++;
        }
        // For design file upload >> End << //
//        echo "<pre>"; print_r($new_file_names); exit;
        $line_items_data = json_decode($post_data['line_items_data']);

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

        $post_data['worker_id'] = isset($post_data['worker_id']) && !empty($post_data['worker_id']) ? $post_data['worker_id'] : null;
        $post_data['ce_date'] = isset($post_data['ce_date']) && !empty($post_data['ce_date']) ? date('Y-m-d', strtotime($post_data['ce_date'])) : null;
        $post_data['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
        $post_data['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
        $post_data['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
        $post_data['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');

        if(isset($post_data['ce_id']) && !empty($post_data['ce_id'])){
            $old_status = $this->crud->get_row_by_id('casting_entry',array('ce_id' => $post_data['ce_id']));
            if($old_status[0]->to_casting_status_id == MANUFACTURE_STATUS_DESIGN_READY && $post_data['to_casting_status_id'] != MANUFACTURE_STATUS_DESIGN_READY){
                $d_design_file_ids = $this->crud->get_row_by_id('casting_entry_design_files',array('ce_id' => $post_data['ce_id']));
                foreach ($d_design_file_ids as $delete_design_file){
                    unlink('./uploads/casting_images/' . $delete_design_file->design_file_name);
                    $this->crud->delete('casting_entry_design_files', array('design_file_id' => $delete_design_file->design_file_id));
                }
            } else {
                $delete_design_file_ids = json_decode($post_data['delete_design_file_ids']);
                if(!empty($delete_design_file_ids)){
                    foreach ($delete_design_file_ids as $delete_design_file){
                        $d_file_name = $this->crud->get_row_by_id('casting_entry_design_files',array('design_file_id' => $delete_design_file));
                        unlink('./uploads/casting_images/' . $d_file_name[0]->design_file_name);
                        $this->crud->delete('casting_entry_design_files', array('design_file_id' => $delete_design_file));
                    }
                }
            }
            
            // Increase fine in Account And Department
            $this->update_account_and_department_balance_on_update($post_data['ce_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_manufacture_update($post_data['ce_id']);
            
            $update_arr = array();
            $update_arr['worker_id'] = $post_data['worker_id'];
            $update_arr['to_casting_status_id'] = $post_data['to_casting_status_id'];
            $update_arr['cad_worker_id'] = isset($post_data['cad_worker_id'])?$post_data['cad_worker_id']:'';
            $update_arr['ce_date'] = isset($post_data['ce_date']) && !empty($post_data['ce_date']) ? date('Y-m-d', strtotime($post_data['ce_date'])) : null;
            $update_arr['lott_complete'] = $post_data['lott_complete'];
            $update_arr['ce_remark'] = $post_data['ce_remark'];
            $update_arr['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
            $update_arr['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
            $update_arr['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
            $update_arr['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['ce_id'] = $post_data['ce_id'];
            $result = $this->crud->update('casting_entry', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Casting Updated Successfully');
                
                $update_ce_oi_ids = array('0');
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if(!empty($checked_order_items)){
                    foreach ($checked_order_items as $order_item){
                        $order_item_arr = array();
                        
                        $order_item_arr['ce_id'] = $post_data['ce_id'];
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        if(isset($order_item->ce_oi_id) && !empty($order_item->ce_oi_id)){
                            $this->db->where('ce_oi_id', $order_item->ce_oi_id);
                            $this->db->update('casting_entry_order_items', $order_item_arr);
                            $update_ce_oi_ids[] = $order_item->ce_oi_id;
                        } else {
                            $this->crud->update('casting_entry_order_items', array('is_ahead' => '1'), array('order_lot_item_id' => $order_item->order_lot_item_id));
                            $order_item_arr['created_at'] = $this->now_time;
                            $order_item_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('casting_entry_order_items', $order_item_arr);
                            $update_ce_oi_ids[] = $this->db->insert_id();
                        }
                    }
                }
                if($post_data['from_casting_status_id'] != MANUFACTURE_STATUS_NOT_STARTED){
                    $get_old_data = $this->crud->getFromSQL("SELECT order_lot_item_id FROM casting_entry_order_items WHERE ce_oi_id NOT IN (".implode(',', $update_ce_oi_ids).") AND ce_id = ".$post_data['ce_id']." ");
                    $del_order_lot_item_id_arr = array();
                    if(!empty($get_old_data)){
                        foreach ($get_old_data as $old_data_id){
                            $del_order_lot_item_id_arr[] = $old_data_id->order_lot_item_id;
                        }
                        $check_old_data = $this->crud->getFromSQL("SELECT * FROM casting_entry_order_items WHERE ce_id IN (SELECT ce_id FROM casting_entry WHERE to_casting_status_id = ".$post_data['from_casting_status_id'].") AND order_lot_item_id IN (".implode(',', $del_order_lot_item_id_arr).") ");
                        if(!empty($check_old_data)){
                            foreach ($check_old_data as $check_old_dta){
                                $this->crud->update('casting_entry_order_items', array('is_ahead' => '0'), array('ce_oi_id' => $check_old_dta->ce_oi_id));
                            }
                        }
                    }
                }
                $this->db->where('ce_id', $post_data['ce_id']);
                $this->db->where_not_in('ce_oi_id', $update_ce_oi_ids);
                $this->db->delete('casting_entry_order_items');
                
                if(isset($post_data['deleted_lineitem_id'])){
                    $this->db->where_in('ce_detail_id', $post_data['deleted_lineitem_id']);
                    $this->db->delete('casting_entry_details');
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $update_item = array();
                        $update_item['ce_id'] = $post_data['ce_id'];
                        $update_item['type_id'] = $lineitem->type_id;
                        $update_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['tunch'] = $lineitem->purity;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['less'] = $lineitem->less;
                        $update_item['net_wt'] = $lineitem->net_wt;
                        $update_item['actual_tunch'] = $lineitem->actual_tunch;
                        $update_item['fine'] = $lineitem->fine;
                        $update_item['pcs'] = $lineitem->pcs;
                        $update_item['ad_weight'] = $lineitem->ad_weight;
                        $update_item['ad_pcs'] = $lineitem->ad_pcs;
                        $update_item['ce_detail_date'] = !empty($lineitem->ce_detail_date) ? date('Y-m-d', strtotime($lineitem->ce_detail_date)) : null;
                        $update_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $update_item['ce_detail_remark'] = $lineitem->ce_detail_remark;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $update_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if(($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                            if(isset($lineitem->stock_type)){
                                $update_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $update_item['updated_at'] = $this->now_time;
                        $update_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->ce_detail_id) && !empty($lineitem->ce_detail_id)){
                            $this->db->where('ce_detail_id', $lineitem->ce_detail_id);
                            $this->db->update('casting_entry_details', $update_item);
                        } else {
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('casting_entry_details', $update_item);
                            $last_inserted_id = $this->db->insert_id();
                            $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                        }
                        $line_items_data[$key]->category_id = $update_item['category_id'];
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $this->casting_department_id);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $this->casting_department_id));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data,$this->casting_department_id);
                }
            }
            if(!empty($new_file_names)){
                foreach ($new_file_names as $new_file_name){
                    $file_arr = array();
                    $file_arr['ce_id'] = $post_data['ce_id'];
                    $file_arr['design_file_name'] = $new_file_name;
                    $file_arr['created_at'] = $this->now_time;
                    $file_arr['created_by'] = $this->logged_in_id;
                    $file_arr['updated_at'] = $this->now_time;
                    $file_arr['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('casting_entry_design_files', $file_arr);
                }
            }
        } else {
            $reference = $this->crud->get_max_number('casting_entry', 'reference_no');
            $reference_no = 1;
            if ($reference->reference_no > 0) {
                $reference_no = $reference->reference_no + 1;
            }

            $ce_arr = array();
            $ce_arr['reference_no'] = $reference_no;
            $ce_arr['worker_id'] = $post_data['worker_id'];
            $ce_arr['department_id'] = $this->casting_department_id;
            $ce_arr['from_casting_status_id'] = $post_data['from_casting_status_id'];
            $ce_arr['to_casting_status_id'] = $post_data['to_casting_status_id'];
            $ce_arr['cad_worker_id'] = isset($post_data['cad_worker_id'])?$post_data['cad_worker_id']:'';
            $ce_arr['ce_date'] = isset($post_data['ce_date']) && !empty($post_data['ce_date']) ? date('Y-m-d', strtotime($post_data['ce_date'])) : null;
            $ce_arr['lott_complete'] = $post_data['lott_complete'];
            $ce_arr['ce_remark'] = $post_data['ce_remark'];
            $ce_arr['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
            $ce_arr['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
            $ce_arr['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
            $ce_arr['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');
            $ce_arr['created_at'] = $this->now_time;
            $ce_arr['created_by'] = $this->logged_in_id;
            $ce_arr['updated_at'] = $this->now_time;
            $ce_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('casting_entry', $ce_arr);
            $ce_id = $this->db->insert_id();
            if ($result) {
                if(!empty($new_file_names)){
                    foreach ($new_file_names as $new_file_name){
                        $file_arr = array();
                        $file_arr['ce_id'] = $ce_id;
                        $file_arr['design_file_name'] = $new_file_name;
                        $file_arr['created_at'] = $this->now_time;
                        $file_arr['created_by'] = $this->logged_in_id;
                        $file_arr['updated_at'] = $this->now_time;
                        $file_arr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('casting_entry_design_files', $file_arr);
                    }
                }
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Casting Entry Added Successfully');
                
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if(!empty($checked_order_items)){
                    foreach ($checked_order_items as $order_item){
                        $this->crud->update('casting_entry_order_items', array('is_ahead' => '1'), array('order_lot_item_id' => $order_item->order_lot_item_id));
                        $order_item_arr = array();
                        $order_item_arr['ce_id'] = $ce_id;
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['is_ahead'] = '0';
                        $order_item_arr['created_at'] = $this->now_time;
                        $order_item_arr['created_by'] = $this->logged_in_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('casting_entry_order_items', $order_item_arr);
                    }
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['ce_id'] = $ce_id;
                        $insert_item['type_id'] = $lineitem->type_id;
                        $insert_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['tunch'] = $lineitem->purity;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['actual_tunch'] = $lineitem->actual_tunch;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['pcs'] = $lineitem->pcs;
                        $insert_item['ad_pcs'] = $lineitem->ad_pcs;
                        $insert_item['ad_weight'] = $lineitem->ad_weight;
                        $insert_item['ce_detail_date'] = !empty($lineitem->ce_detail_date) ? date('Y-m-d', strtotime($lineitem->ce_detail_date)) : null;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['ce_detail_remark'] = $lineitem->ce_detail_remark;
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
                        if(($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $this->crud->insert('casting_entry_details', $insert_item);
                        $last_inserted_item_id = $this->db->insert_id();
                        $line_items_data[$key]->purchase_item_id = $last_inserted_item_id;
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $this->casting_department_id);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $this->casting_department_id));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data, $this->casting_department_id);
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function update_stock_on_manufacture_insert($lineitem_data ,$department_id){
        if (!empty($lineitem_data)) {
            foreach ($lineitem_data as $lineitem) {
                $lineitem->fine = $lineitem->net_wt * ($lineitem->touch_id) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                if((isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE) && $lineitem->type_id != CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID && $lineitem->type_id != CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
                    
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
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID;
                        }
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID;
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
                        
                        $this->crud->insert('item_stock', $insert_item_stock);
                    } else {
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->ce_detail_id) && !empty($lineitem->ce_detail_id)){
                            $purchase_item_id = $lineitem->ce_detail_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                        if(!empty($exist_item_id)){
                            if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                            $update_item_stock['ntwt'] = $current_stock_ntwt;
                            $update_item_stock['fine'] = $current_stock_fine;
                            $update_item_stock['grwt'] = $current_stock_grwt;
                            $update_item_stock['less'] = $current_stock_less;
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
                            if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID){
                                $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID;
                            }
                            if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID){
                                $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID;
                            }
                            $insert_item_stock['created_at'] = $this->now_time;
                            $insert_item_stock['created_by'] = $this->logged_in_id;
                            $insert_item_stock['updated_at'] = $this->now_time;
                            $insert_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('item_stock', $insert_item_stock);
                        }
                    }
                } else {
                    if(isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE){
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->ce_detail_id) && !empty($lineitem->ce_detail_id)){
                            $purchase_item_id = $lineitem->ce_detail_id;
                        } elseif(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                            $purchase_item_id = $lineitem->purchase_item_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);

                    if(!empty($exist_item_id)){
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                        $update_item_stock['ntwt'] = $current_stock_ntwt;
                        $update_item_stock['fine'] = $current_stock_fine;
                        $update_item_stock['grwt'] = $current_stock_grwt;
                        $update_item_stock['less'] = $current_stock_less;
                        $update_item_stock['updated_at'] = $this->now_time;
                        $update_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    } else { 
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID;
                        }
                        if($lineitem->type_id == CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID;
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;

                        $this->crud->insert('item_stock', $insert_item_stock);
                    }
                }
            }
        }
    }

    function update_account_and_department_balance_on_update($ce_id=''){
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $casting_entry_details = $this->crud->get_all_with_where('casting_entry_details', '', '', array('ce_id' => $ce_id));
        $department_id = $this->casting_department_id;
        $account_id = $this->crud->get_column_value_by_id('casting_entry', 'worker_id', array('ce_id' => $ce_id));
        if(!empty($casting_entry_details)){
            foreach ($casting_entry_details as $lineitem){
                
                $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
            
            //Update Account Balance
            $account = $this->crud->get_data_row_by_id('account', 'account_id', $account_id);
            if(!empty($account)){
                $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $account_id));
            }

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

    function update_stock_on_manufacture_update($ce_id =''){
        $casting_entry_details = $this->crud->get_all_with_where('casting_entry_details', '', '', array('ce_id' => $ce_id));
        if(!empty($casting_entry_details)){
            foreach ($casting_entry_details as $lineitem){
                
                $lineitem->fine = $lineitem->net_wt * $lineitem->tunch / 100;
                $lineitem->grwt = $lineitem->weight;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                $department_id = $this->casting_department_id;
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if($stock_method == STOCK_METHOD_ITEM_WISE){
                    if(!empty($lineitem->purchase_sell_item_id)){
                        $ce_detail_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $ce_detail_id = $lineitem->ce_detail_id;
                    }
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $ce_detail_id);
                } else {
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }
                
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                if(!empty($exist_item_id)){
                    if($lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
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
                    $update_item_stock['ntwt'] = $current_stock_ntwt;
                    $update_item_stock['fine'] = $current_stock_fine;
                    $update_item_stock['grwt'] = $current_stock_grwt;
                    $update_item_stock['less'] = $current_stock_less;
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                }
            }
        }
    }
    
    function delete($id = '') {
        $where_array = array('ce_id' => $id);
        $casting = $this->crud->get_row_by_id('casting_entry', $where_array);
        $return = array();
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if(!empty($casting)){
            $found = false;
            $casting_details = $this->crud->get_row_by_id('casting_entry_details', $where_array);
            if(!empty($casting_details)){
                foreach($casting_details as $ce_detail){
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $ce_detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $ce_detail->ce_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                    } else if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_receive_or_not($this->casting_department_id, $ce_detail->category_id, $ce_detail->item_id, $ce_detail->tunch);
                            if(!empty($used_lineitem_ids) && in_array($ce_detail->ce_detail_id, $used_lineitem_ids)){
                                $found = true;
                            }
                        } 
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {
                $this->update_account_and_department_balance_on_update($id);
                $this->update_stock_on_manufacture_update($id);
                $this->crud->delete('casting_entry_design_files', $where_array);
                $from_status_id = $this->crud->get_column_value_by_id('casting_entry','from_casting_status_id',array('ce_id' =>$id));
                if($from_status_id != MANUFACTURE_STATUS_NOT_STARTED){
                    $get_old_data = $this->crud->getFromSQL("SELECT * FROM casting_entry_order_items WHERE ce_id = ".$id." ");
                    $del_order_lot_item_id_arr = array();
                    if(!empty($get_old_data)){
                        foreach ($get_old_data as $old_data_id){
                            $del_order_lot_item_id_arr[] = $old_data_id->order_lot_item_id;
                        }
                        $check_old_data = $this->crud->getFromSQL("SELECT * FROM casting_entry_order_items WHERE ce_id IN (SELECT ce_id FROM casting_entry WHERE to_casting_status_id = ".$from_status_id.") AND order_lot_item_id IN (".implode(',', $del_order_lot_item_id_arr).") ");
                        if(!empty($check_old_data)){
                            foreach ($check_old_data as $check_old_dta){
                                $this->crud->update('casting_entry_order_items', array('is_ahead' => '0'), array('ce_oi_id' => $check_old_dta->ce_oi_id));
                            }
                        }
                    }
                }
                $this->crud->delete('casting_entry_order_items', $where_array);
                $this->crud->delete('casting_entry_details', $where_array);
                $this->crud->delete('casting_entry', $where_array);
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function check_default_item_receive_or_not($department_id, $category_id, $item_id, $touch_id) {
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_casting_entry_grwt = $this->crud->get_total_casting_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_casting_entry_grwt + $total_other_sell_grwt;
        $used_lineitem_ids = array();
        if (!empty($total_sell_grwt)) {
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $casting_entry_receive_items = $this->crud->get_casting_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $other_purchase_items = $this->crud->get_other_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $casting_entry_receive_items, $other_purchase_items);
//            echo '<pre>'; print_r($purchase_delete_array); exit;
            uasort($purchase_delete_array, function($a, $b) {
                $value1 = strtotime($a->created_at);
                $value2 = strtotime($b->created_at);
                return $value1 - $value2;
            });

            $purchase_grwt = 0;
            $first_check_purchase_grwt = 0;

            foreach ($purchase_delete_array as $purchase_item) {
                $purchase_grwt = $purchase_grwt + $purchase_item->grwt;
                if ($purchase_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0) {
                    if ($purchase_item->type == 'CASTING_R') {
                        $used_lineitem_ids[] = $purchase_item->ce_detail_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if ($purchase_grwt <= $total_sell_grwt) {
                    if ($purchase_item->type == 'CASTING_R') {
                        $used_lineitem_ids[] = $purchase_item->ce_detail_id;
                    }
                }
            }
        }

        return $used_lineitem_ids;
        exit;
    }
    
    function casting_entry_list() {
        if($this->applib->have_access_role(CASTING_MODULE_ID,"view")) {
            $data = array();
            set_page('casting/casting_entry_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function casting_entry_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'casting_entry ce';
        $config['select'] = 'ce.*,a.account_name AS worker, IF(ce.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(ce.hisab_done = 0,"No","Yes") AS is_hisab_done, fms.manufacture_status_name as from_casting_status_name, tms.manufacture_status_name as to_casting_status_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = ce.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'manufacture_status fms', 'join_by' => 'fms.manufacture_status_id = ce.from_casting_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'manufacture_status tms', 'join_by' => 'tms.manufacture_status_id = ce.to_casting_status_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name','DATE_FORMAT(ce.ce_date,"%d-%m-%Y")','ce.reference_no','IF(ce.lott_complete = 0,"No","Yes")', 'IF(ce.hisab_done = 0,"No","Yes")', 'ce.ce_remark');
        $config['column_order'] = array(null,'a.account_name','ce.ce_date','ce.reference_no', null, null, null, null, null, null, null,'ce.lott_complete', 'ce.hisab_done', 'ce.ce_remark');

        if(!empty($post_data['worker_id'])){
            $config['wheres'][] = array('column_name' => 'ce.worker_id', 'column_value' => $post_data['worker_id']);
        }
        if(isset($post_data['lott_complete'])){
            if($post_data['lott_complete'] == '2'){
                $config['wheres'][] = array('column_name' => 'ce.hisab_done', 'column_value' => '1');
            } else if($post_data['lott_complete'] == '1'){
                $config['wheres'][] = array('column_name' => 'ce.lott_complete', 'column_value' => '1');
                $config['wheres'][] = array('column_name' => 'ce.hisab_done', 'column_value' => '0');
            } else if($post_data['lott_complete'] == 'all'){
                $config['wheres'][] = array('column_name' => 'ce.hisab_done', 'column_value' => '0');
            } else {
                $config['wheres'][] = array('column_name' => 'ce.lott_complete', 'column_value' => $post_data['lott_complete']);
            }
        }
        if(!empty($post_data['to_casting_status_id'])){
            $config['wheres'][] = array('column_name' => 'ce.to_casting_status_id', 'column_value' => $post_data['to_casting_status_id']);
        }
        $config['order'] = array('ce.ce_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(CASTING_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(CASTING_MODULE_ID, "edit");

        foreach ($list as $ce) {
            $row = array();
            $action = '';
            if($role_edit){
                if($ce->hisab_done != '1'){
                    $action .= '<a href="' . base_url("casting/casting_entry/" . $ce->ce_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if($role_delete){
                if($ce->hisab_done != '1'){
                    $get_order_data = $this->crud->getFromSQL("SELECT ce_id FROM casting_entry_order_items WHERE ce_id = ".$ce->ce_id." AND is_ahead = 1 LIMIT 1 ");
                    if(empty($get_order_data)){
                        $action .= '<a href="javascript:void(0);" class="delete_ce" data-href="' . base_url('casting/delete/' . $ce->ce_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                    }
                }
            }
            if($this->applib->have_access_role(CASTING_MODULE_ID,"worker_hisab_casting")) {
                if(isset($post_data['checked_or_not']) && $post_data['checked_or_not'] == '1'){
                    $action .= '&nbsp;&nbsp;<input type="checkbox" name="check_ce[]" id="checkbox_id_'.$ce->ce_id.'" class="icheckbox_flat-blue check_ce" value="'.$ce->ce_id.'" data-total_issue_net_wt="'. $ce->total_issue_net_wt .'" data-total_issue_fine="'. $ce->total_issue_fine .'" data-total_receive_net_wt="'. $ce->total_receive_net_wt .'" data-total_receive_fine="'. $ce->total_receive_fine .'">';
                }
            }
            $row[] = $action;
            $row[] = $ce->from_casting_status_name;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-ce_id="' . $ce->ce_id . '" >'.$ce->to_casting_status_name.'</a>';
            $row[] = $ce->worker;
            $row[] = (!empty(strtotime($ce->ce_date))) ? date('d-m-Y', strtotime($ce->ce_date)) : '';
            $row[] = $ce->reference_no;
            if(!empty($ce->total_issue_fine) && !empty($ce->total_issue_net_wt)){
                $issue_avg_tunch = $ce->total_issue_fine * 100 / $ce->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            $row[] = number_format($issue_avg_tunch, 2, '.', '');
            $row[] = number_format($ce->total_issue_net_wt, 3, '.', '');
            $row[] = number_format($ce->total_issue_fine, 3, '.', '');
            if(!empty($ce->total_receive_fine) && !empty($ce->total_receive_net_wt)){
                $receive_avg_tunch = $ce->total_receive_fine * 100 / $ce->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $row[] = number_format($receive_avg_tunch, 2, '.', '');
            $row[] = number_format($ce->total_receive_net_wt, 3, '.', '');
            $row[] = number_format($ce->total_receive_fine, 3, '.', '');
            $balance_net_wt = $ce->total_issue_net_wt - $ce->total_receive_net_wt;
            $row[] = number_format($balance_net_wt, 3, '.', '');
            $balance_fine = $ce->total_issue_fine - $ce->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $lott_complete = $ce->is_lott_complete;
            if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) {
               if($ce->lott_complete == 0) {
                   $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-ce_id="'.$ce->ce_id.'" data-lott_complete="1" data-href="'. base_url('casting/set_lott_complete_yes_no/'.$ce->ce_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;">';
               } else {
                   $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-ce_id="'.$ce->ce_id.'" data-lott_complete=0 data-href="'. base_url('casting/set_lott_complete_yes_no/'.$ce->ce_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;" checked="checked">';
               }
            }
            $row[] = $lott_complete;
            $row[] = $ce->is_hisab_done;
            $row[] = $ce->ce_remark;
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
    
    function set_lott_complete_yes_no($ce_id= '') {
        $update_data = array();
        $update_data['lott_complete'] = $_POST['lott_complete'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('casting_entry', $update_data, array('ce_id' => $ce_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function casting_entry_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'casting_entry_details ce_detail';
        $config['select'] = 'ce_detail.*,im.item_name, ce_detail.type_id AS type';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = ce_detail.item_id', 'join_type' => 'left');
        $config['column_search'] = array('IF(ce_detail.type_id = 0,"Issue","Receive")', 'im.item_name', 'ce_detail.weight', 'ce_detail.tunch', 'ce_detail.fine', 'DATE_FORMAT(ce_detail.ce_detail_date,"%d-%m-%Y")', 'ce_detail.ce_detail_remark');
        $config['column_order'] = array('ce_detail.type_id', 'im.item_name', 'ce_detail.weight', NULL, 'ce_detail.weight', 'ce_detail.tunch', 'ce_detail.fine', 'ce_detail.ce_detail_date', 'ce_detail.created_at', 'ce_detail.updated_at', 'ce_detail.ce_detail_remark');
        $config['order'] = array('ce_detail.ce_detail_id' => 'desc');
        if (isset($post_data['ce_id']) && !empty($post_data['ce_id'])) {
            $config['wheres'][] = array('column_name' => 'ce_detail.ce_id', 'column_value' => $post_data['ce_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();

        $data = array();

        foreach ($list as $detail) {
            $row = array();
            $detail_type = '';
            if($detail->type == CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID){
                $detail_type = 'Issue Finish Work';
            } else if($detail->type == CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID){
                $detail_type = 'Issue Scrap';
            } else if($detail->type == CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID){
                $detail_type = 'Receive Finish Work';
            } else if($detail->type == CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID){
                $detail_type = 'Receive Scrap';
            }
            $row[] = $detail_type;
            $row[] = $detail->item_name;
            $row[] = number_format($detail->weight, 3, '.', '');
            $row[] = number_format($detail->less, 3, '.', '');
            $row[] = number_format($detail->net_wt, 3, '.', '');
            $row[] = number_format($detail->tunch, 2, '.', '');
            $row[] = number_format($detail->actual_tunch, 2, '.', '');
            $row[] = number_format($detail->fine, 3, '.', '');
            $row[] = $detail->pcs;
            $row[] = $detail->ce_detail_date ? date('d-m-Y', strtotime($detail->ce_detail_date)) : '';
            $row[] = $detail->created_at ? date('d-m-Y H:i:s', strtotime($detail->created_at)) : '';
            $row[] = $detail->updated_at ? date('d-m-Y H:i:s', strtotime($detail->updated_at)) : '';
            $row[] = $detail->ce_detail_remark;
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
}
