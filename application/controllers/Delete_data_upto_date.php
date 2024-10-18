<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_data_upto_date extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->gurulog_db = $this->load->database("gurulog",true);
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }
    
    function index(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            $data = array();
            $data['account_res'] = $this->get_only_customers_accounts_with_ids();
            set_page('delete_data_upto_date', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_without_customers_accounts_with_ids(){
        $this->db->select("a.account_id,a.account_name,a.account_group_id");
        $this->db->from("account a");
//        $this->db->where("a.account_group_id !=", NOT_APPROVED_ACCOUNT_GROUP_ID);
//        $this->db->where("a.account_group_id !=", DEPARTMENT_GROUP);
        $this->db->where_not_in("a.account_group_id", array(CUSTOMER_GROUP, SUNDRY_CREDITORS_ACCOUNT_GROUP, SUNDRY_DEBTORS_ACCOUNT_GROUP, BANK_ACCOUNT_GROUP));
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        $account_res = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $account_res[] = array(
                   'account_id' => $row->account_id,
                   'account_group_id' => $row->account_group_id,
                   'text' => $row->account_id.' - '.$row->account_name,
                );
            }
        }
        return $account_res;
    }
        
    function get_only_customers_accounts_with_ids(){
        $this->db->select("a.account_id,a.account_name,a.account_group_id");
        $this->db->from("account a");
        $this->db->where_in("a.account_group_id", array(CUSTOMER_GROUP, SUNDRY_CREDITORS_ACCOUNT_GROUP, SUNDRY_DEBTORS_ACCOUNT_GROUP));
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        $account_res = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $account_res[] = array(
                   'account_id' => $row->account_id,
                   'account_group_id' => $row->account_group_id,
                   'text' => $row->account_id.' - '.$row->account_name,
                );
            }
        }
        return $account_res;
    }
        
    function delete_selected_data(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            
            $return = array();
            $post_data = $this->input->post();
//            print_r($post_data); exit;
            $data_deleted = '0';
            if(!empty($post_data['delete_upto'])){
                $delete_upto = date('Y-m-d', strtotime($post_data['delete_upto']));
                $module_names = $post_data['module_names'];
                
                // Delete Item Stock Duplicate Data
                $select_duplicate_item_stock_sql = "SELECT COUNT(*) item_stock_row_count, `item_stock`.* FROM `item_stock` "
                        . "WHERE `category_id` = " . EXCHANGE_DEFAULT_CATEGORY_ID . " AND `item_id` = " . EXCHANGE_DEFAULT_ITEM_ID . " AND `grwt` != 0 AND `ntwt` != 0 "
                        . "GROUP BY `department_id`, `tunch`, abs(`grwt`), abs(`ntwt`) "
                        . "HAVING item_stock_row_count > 1";
                $select_result = $this->db->query($select_duplicate_item_stock_sql)->result_array();
                $item_stock_ids = array();
                if(!empty($select_result)){
                    foreach($select_result as $select_row){
                        if($select_row['item_stock_row_count'] == '2'){
                            $get_item_stock_id_sql = "SELECT `item_stock_id` FROM `item_stock` "
                                . "WHERE `department_id` = '" . $select_row['department_id'] . "' AND `category_id` = " . EXCHANGE_DEFAULT_CATEGORY_ID . " AND `item_id` = " . EXCHANGE_DEFAULT_ITEM_ID . " AND "
                                    . "`grwt` = '-" . $select_row['grwt'] . "' AND `ntwt` = '-" . $select_row['ntwt'] . "' AND `tunch` = '" . $select_row['tunch'] . "' ";
                            $get_item_stock_id_result = $this->db->query($get_item_stock_id_sql)->row();
                            if(!empty($get_item_stock_id_result)){
                                $item_stock_ids[] = $select_row['item_stock_id'];
                                $item_stock_ids[] = $get_item_stock_id_result->item_stock_id;
                            }
                        }
                    }
                    $item_stock_ids = implode(',', $item_stock_ids);
//                    print_r($item_stock_ids); exit;
                    $delete_duplicate_item_stock_sql = "DELETE FROM `item_stock` WHERE `item_stock_id` IN (" . $item_stock_ids . ")";
                    $this->db->query($delete_duplicate_item_stock_sql);
                }

                if(in_array('module_order', $module_names)){
                    // SELECT * FROM `new_order` WHERE `order_date`<='2019-12-15' AND (`order_status_id` = 2 OR `order_status_id` = 3);
                    // SELECT * FROM `order_lot_item` WHERE `order_id` NOT IN( SELECT `order_id` FROM `new_order`);
                    
                    $order_ids = array();
                    $select_order_sql = "SELECT `order_id` FROM `new_order` WHERE `order_date`<='".$delete_upto."' AND (`order_status_id` = 2 OR `order_status_id` = 3)";
                    $select_orders = $this->crud->getFromSQL($select_order_sql);
                    if(!empty($select_orders)){
                        foreach ($select_orders as $select_order){
                            $order_ids[] = $select_order->order_id;
                            
                            $select_order_item_sql = "SELECT `image` FROM `order_lot_item` WHERE `order_id`='".$select_order->order_id."' ";
                            $select_order_items = $this->crud->getFromSQL($select_order_item_sql);
                            if(!empty($select_order_items)){
                                foreach ($select_order_items as $select_order_item){
                                    if(!empty($select_order_item->image)){
                                        unlink(APPPATH. '/../'.$select_order_item->image);
                                    }
                                }
                            }
                        }
                    
                        $delete_order_sql = "DELETE FROM `new_order` WHERE `order_date`<='".$delete_upto."' AND (`order_status_id` = 2 OR `order_status_id` = 3)";
                        $delete_order_result = $this->db->query($delete_order_sql);
                        $delete_orderitem_sql = "DELETE FROM `order_lot_item` WHERE `order_id` NOT IN( SELECT `order_id` FROM `new_order`)";
                        $delete_orderitem_result = $this->db->query($delete_orderitem_sql);
                        $data_deleted = '1';

                        if($delete_order_result && $delete_orderitem_result){
                            if(!empty($order_ids)){
                                $order_ids = implode(',', $order_ids);
                                $delete_order_log_sql = "DELETE FROM `new_order_log` WHERE `order_id` IN('".$order_ids."')";
                                $this->gurulog_db->query($delete_order_log_sql);
                                $delete_orderitem_log_sql = "DELETE FROM `order_lot_item_log` WHERE `order_id` NOT IN( SELECT `order_id` FROM `new_order_log`)";
                                $this->gurulog_db->query($delete_orderitem_log_sql);
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `new_order_log` WHERE `order_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `order_lot_item_log` WHERE `order_id` NOT IN( SELECT `order_id` FROM `new_order_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_issue_receive', $module_names)){
                    // SELECT * FROM `issue_receive` WHERE `ir_date`<='2019-12-15';
                    // SELECT * FROM `issue_receive_details` WHERE `ir_id` NOT IN ( SELECT `ir_id` FROM `issue_receive`);
                    
                    $this->db->select("*");
                    $this->db->from("issue_receive");
                    $this->db->where('ir_date <=', $delete_upto);
                    $this->db->where('hisab_done', '1');
                    $issue_receive_query = $this->db->get();
                    $issue_receives = $issue_receive_query->result();
                    if (!empty($issue_receives)) {
                        foreach ($issue_receives as $issue_receive_row) {
                            
                            $this->db->select("*");
                            $this->db->from("issue_receive_details");
                            $this->db->where('ir_id', $issue_receive_row->ir_id);
                            $issue_receive_details_query = $this->db->get();
                            $issue_receive_details = $issue_receive_details_query->result();
                            if (!empty($issue_receive_details)) {
                                foreach ($issue_receive_details as $issue_receive_detail_row) {
//                                    print_r($issue_receive_row);
//                                    print_r($issue_receive_detail_row);
//                                    echo '<br>';
                                    
                                    // If Type Issue then stock in minus
                                    if($issue_receive_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                        $issue_receive_grwt = ZERO_VALUE - $issue_receive_detail_row->weight;
                                        $issue_receive_less = ZERO_VALUE - $issue_receive_detail_row->less;
                                        $issue_receive_ntwt = ZERO_VALUE - $issue_receive_detail_row->net_wt;
                                        $issue_receive_fine = ZERO_VALUE - $issue_receive_detail_row->fine;
                                    } else {
                                        $issue_receive_grwt = $issue_receive_detail_row->weight;
                                        $issue_receive_less = $issue_receive_detail_row->less;
                                        $issue_receive_ntwt = $issue_receive_detail_row->net_wt;
                                        $issue_receive_fine = $issue_receive_detail_row->fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $issue_receive_detail_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = NULL;
                                        if($issue_receive_detail_row->type_id == MANUFACTURE_TYPE_RECEIVE_ID){
                                            $stock_type = STOCK_TYPE_IR_RECEIVE_ID;
                                        }
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$issue_receive_row->department_id."' AND `category_id` = '".$issue_receive_detail_row->category_id."' AND `item_id` = '".$issue_receive_detail_row->item_id."' AND `tunch` = '".$issue_receive_detail_row->tunch."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $issue_receive_detail_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $issue_receive_detail_row->ird_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $issue_receive_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $issue_receive_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $issue_receive_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $issue_receive_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $issue_receive_row->department_id;
                                            $insert_opening_stock['category_id'] = $issue_receive_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $issue_receive_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $issue_receive_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $issue_receive_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $issue_receive_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $issue_receive_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $issue_receive_fine, '3', '.', '');
                                            if(isset($issue_receive_detail_row->purchase_sell_item_id) && !empty($issue_receive_detail_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $issue_receive_detail_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $issue_receive_detail_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $issue_receive_detail_row->ird_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $issue_receive_row->department_id, 'category_id' => $issue_receive_detail_row->category_id, 'item_id' => $issue_receive_detail_row->item_id, 'tunch' => $issue_receive_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $issue_receive_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $issue_receive_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $issue_receive_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $issue_receive_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $issue_receive_row->department_id;
                                            $insert_opening_stock['category_id'] = $issue_receive_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $issue_receive_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $issue_receive_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $issue_receive_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $issue_receive_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $issue_receive_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $issue_receive_fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                      
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $issue_receive_row->department_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $issue_receive_detail_row->category_id));
                                        
                                        if($issue_receive_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->department_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->department_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Worker Opening balance
                                    $worker_data = $this->crud->get_data_row_by_id('account', 'account_id', $issue_receive_row->worker_id);
                                    if(!empty($worker_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $issue_receive_detail_row->category_id));
                                        // Manufacture Issue Receive ma Worker ma Silver ne Gold ma count krvanu hovathi category group ni condition nthi
                                        if($issue_receive_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->worker_id));
                                            }
                                            
                                        } else {
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $issue_receive_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_row->worker_id));
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            
                            // Delete issue_receive and issue_receive_details data
                            $this->db->where('ir_id', $issue_receive_row->ir_id)->delete('issue_receive_details');
                            $result = $this->db->where('ir_id', $issue_receive_row->ir_id)->delete('issue_receive');
                            if($result){
                                $data_deleted = '1';
                                // Delete issue_receive and issue_receive_details Log data
                                $this->gurulog_db->where('ir_id', $issue_receive_row->ir_id)->delete('issue_receive_details_log');
                                $this->gurulog_db->where('ir_id', $issue_receive_row->ir_id)->delete('issue_receive_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    $this->db->select("whd.*");
                    $this->db->from("worker_hisab_detail whd");
                    $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
                    $this->db->where('wh.hisab_date <=', $delete_upto);
                    $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR);
                    $worker_hisab_detail_query = $this->db->get();
                    $worker_hisab_details = $worker_hisab_detail_query->result();
                    if (!empty($worker_hisab_details)) {
                        foreach ($worker_hisab_details as $worker_hisab_detail_key => $worker_hisab_detail_row) {

                            // Increase and Decrease Fine in Worker Account on Hisab Done
                            $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_detail_row->worker_id);
                            if(!empty($worker_accounts)){
                                $worker_opening_balance_in_gold = number_format((float) $worker_accounts->opening_balance_in_gold, '3', '.', '');
                                if($worker_accounts->gold_ob_credit_debit == '1'){
                                    $worker_opening_balance_in_gold = ZERO_VALUE - $worker_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold - number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + $worker_hisabs[0]->fine;
                                }

                                // Update to Worker Account
                                $worker_opening_balance_in_gold = number_format((float) $worker_opening_balance_in_gold, '3', '.', '');
                                if($worker_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $worker_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                }
                            }

                            // Increase and Decrease Fine in MF Loss Account on Hisab Done
                            $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                            if(!empty($mf_loss_accounts)){
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_accounts->opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_accounts->gold_ob_credit_debit == '1'){
                                    $mf_loss_opening_balance_in_gold = ZERO_VALUE - $mf_loss_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold + number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - $worker_hisabs[0]->fine;
                                }
                                
                                // Update to MF Loss Account
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($mf_loss_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $mf_loss_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                }
                            }

                            // Delete worker_hisab data
                            $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab');
                            // Delete worker_hisab_log data
                            $this->gurulog_db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab_log');
                            
                        }
                        // Delete worker_hisab_detail data
//                        $result = $this->db->where('relation_id', $issue_receive_row->ir_id)->where('is_module', HISAB_DONE_IS_MODULE_MIR)->delete('worker_hisab_detail');
//                        if($result){
//                            $data_deleted = '1';
//                        }
                    }
                    // Delete worker_hisab_detail data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab`)";
                    $this->crud->execuetSQL($delete_orderitem_sql);
                    // Delete worker_hisab_detail_log data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail_log` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab_log`)";
                    $this->gurulog_db->query($delete_orderitem_sql);
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `issue_receive_log` WHERE `ir_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `issue_receive_details_log` WHERE `ir_id` NOT IN( SELECT `ir_id` FROM `issue_receive_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_sale_purchase', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("sell");
                    $this->db->where('sell_date <=', $delete_upto);
                    $this->db->where('delivery_type', '1');
//                    $this->db->where('sell_date', $delete_upto);
                    $sell_query = $this->db->get();
                    $sell_results = $sell_query->result();
                    if (!empty($sell_results)) {
                        foreach ($sell_results as $sell_row) {
//                            print_r($sell_row);
//                            echo '<br>';
                            
                            // Sell Line item Data
                            $this->db->select("*");
                            $this->db->from("sell_items");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $sell_items_query = $this->db->get();
                            $sell_items = $sell_items_query->result();
                            if (!empty($sell_items)) {
                                foreach ($sell_items as $sell_item_row) {
//                                    print_r($sell_item_row);
//                                    echo '<br>';
                                    
                                    // If Type Sell then stock in minus
                                    if($sell_item_row->type == SELL_TYPE_SELL_ID){
                                        $sell_item_grwt = ZERO_VALUE - $sell_item_row->grwt;
                                        $sell_item_less = ZERO_VALUE - $sell_item_row->less;
                                        $sell_item_ntwt = ZERO_VALUE - $sell_item_row->net_wt;
                                        $sell_item_fine = ZERO_VALUE - $sell_item_row->fine;
                                    } else {
                                        $sell_item_grwt = $sell_item_row->grwt;
                                        $sell_item_less = $sell_item_row->less;
                                        $sell_item_ntwt = $sell_item_row->net_wt;
                                        $sell_item_fine = $sell_item_row->fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $sell_item_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = NULL;
                                        if($sell_item_row->type == SELL_TYPE_PURCHASE_ID){
                                            $stock_type = STOCK_TYPE_PURCHASE_ID;
                                        } else if($sell_item_row->type == SELL_TYPE_EXCHANGE_ID){
                                            $stock_type = STOCK_TYPE_EXCHANGE_ID;
                                        }
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$sell_row->process_id."' AND `category_id` = '".$sell_item_row->category_id."' AND `item_id` = '".$sell_item_row->item_id."' AND `tunch` = '".$sell_item_row->touch_id."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $sell_item_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $sell_item_row->sell_item_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $sell_item_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $sell_item_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $sell_item_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $sell_item_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $sell_row->process_id;
                                            $insert_opening_stock['category_id'] = $sell_item_row->category_id;
                                            $insert_opening_stock['item_id'] = $sell_item_row->item_id;
                                            $insert_opening_stock['tunch'] = $sell_item_row->touch_id;
                                            $insert_opening_stock['grwt'] = number_format((float) $sell_item_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $sell_item_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $sell_item_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $sell_item_fine, '3', '.', '');
                                            if(isset($sell_item_row->purchase_sell_item_id) && !empty($sell_item_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $sell_item_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $sell_item_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $sell_item_row->sell_item_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $sell_row->process_id, 'category_id' => $sell_item_row->category_id, 'item_id' => $sell_item_row->item_id, 'tunch' => $sell_item_row->touch_id);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $sell_item_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $sell_item_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $sell_item_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $sell_item_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $sell_row->process_id;
                                            $insert_opening_stock['category_id'] = $sell_item_row->category_id;
                                            $insert_opening_stock['item_id'] = $sell_item_row->item_id;
                                            $insert_opening_stock['tunch'] = $sell_item_row->touch_id;
                                            $insert_opening_stock['grwt'] = number_format((float) $sell_item_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $sell_item_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $sell_item_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $sell_item_fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                    
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->process_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $sell_item_row->category_id));
                                        
                                        if($sell_item_row->type == SELL_TYPE_SELL_ID){
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $sell_item_row->category_id));
                                        
                                        if($sell_item_row->type == SELL_TYPE_SELL_ID){
                                            
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                            }
                                            
                                            
                                        } else {
                                            
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            }
                                            
                                        }
                                        
                                        // Sell Lineitem charges_amt Effect in Selected Customer and MF Loss Account
                                        if(isset($sell_item_row->charges_amt) && !empty($sell_item_row->charges_amt)){
                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $sell_item_row->charges_amt, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                            }
                                            // charges_amt Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_item_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                            }
                                            
                                            $mf_loss_data = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                                            if(!empty($mf_loss_data)){
                                                if($mf_loss_data->rupees_ob_credit_debit == '1'){
                                                    $mf_loss_data->opening_balance_in_rupees = ZERO_VALUE - $mf_loss_data->opening_balance_in_rupees;
                                                }
                                                $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_item_row->charges_amt, '2', '.', '');
                                                $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '');
                                                if($mf_loss_data->opening_balance_in_rupees < 0){
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($mf_loss_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => $mf_loss_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                                }
                                            }
                                        }
                                        
                                    }
                                }
                            }
                            // Delete sell_items data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_items');
                            // Delete sell_items_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_items_log');
                            // Delete sell_less_ad_details data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_less_ad_details');
                            // Delete sell_less_ad_details_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_less_ad_details_log');
                            // Delete sell_item_charges_details data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_item_charges_details');
                            
                            // Sell Payment Receipt Data
                            $this->db->select("*");
                            $this->db->from("payment_receipt");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $payment_receipt_query = $this->db->get();
                            $payment_receipts = $payment_receipt_query->result();
                            if (!empty($payment_receipts)) {
                                foreach ($payment_receipts as $payment_receipt_row) {
//                                    print_r($payment_receipt_row);
//                                    echo '<br>';
                                    
                                    // If Type receipt then amount in minus
                                    if($payment_receipt_row->payment_receipt == '2'){
                                        $payment_receipt_amount = ZERO_VALUE - $payment_receipt_row->amount;
                                        $payment_receipt_c_amt = ZERO_VALUE - $payment_receipt_row->c_amt;
                                        $payment_receipt_r_amt = ZERO_VALUE - $payment_receipt_row->r_amt;
                                    } else {
                                        $payment_receipt_amount = $payment_receipt_row->amount;
                                        $payment_receipt_c_amt = $payment_receipt_row->c_amt;
                                        $payment_receipt_r_amt = $payment_receipt_row->r_amt;
                                    }
                                    
                                    if($payment_receipt_row->cash_cheque == '1'){  // Cash
                                        // Update Department Opening balance
                                        $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->department_id);
                                        if(!empty($department_data)){

                                            if($department_data->rupees_ob_credit_debit == '1'){
                                                $department_data->opening_balance_in_rupees = ZERO_VALUE - $department_data->opening_balance_in_rupees;
                                            }
                                            $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                            $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '');
                                            if($department_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($department_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->department_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $department_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->department_id));
                                            }

                                        }
                                    }
                                    
                                    if($payment_receipt_row->cash_cheque == '2'){  // Cheque
                                        // Update Bank Opening balance
                                        $bank_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->bank_id);
                                        if(!empty($bank_data)){

                                            if($bank_data->rupees_ob_credit_debit == '1'){
                                                $bank_data->opening_balance_in_rupees = ZERO_VALUE - $bank_data->opening_balance_in_rupees;
                                            }
                                            $bank_data->opening_balance_in_rupees = number_format((float) $bank_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                            $bank_data->opening_balance_in_rupees = number_format((float) $bank_data->opening_balance_in_rupees, '2', '.', '');
                                            if($bank_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($bank_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->bank_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $bank_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->bank_id));
                                            }

                                        }
                                    }
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->account_id);
                                    if(!empty($account_data)){
                                            
                                        if($account_data->rupees_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                        }
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $payment_receipt_amount, '2', '.', '');
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                        if($account_data->opening_balance_in_rupees < 0){
                                            $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                        }

                                        // amount Effect for c_amt
                                        if($payment_receipt_row->cash_cheque == '1'){  // Cash
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $payment_receipt_c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                            }
                                        }

                                        // amount Effect for r_amt
                                        if($payment_receipt_row->cash_cheque == '2'){  // Cheque
                                            if($account_data->r_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                            }
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $payment_receipt_r_amt, '2', '.', '');
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_r_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_r_amount' => abs($account_data->opening_balance_in_r_amount), 'r_amount_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_r_amount' => $account_data->opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                            }
                                        }

                                    }
                                }
                            }
                            // Delete payment_receipt data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('payment_receipt');
                            // Delete payment_receipt_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('payment_receipt_log');
                            
                            // Sell Metal Payment Receipt Data
                            $this->db->select("*");
                            $this->db->from("metal_payment_receipt");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $metal_payment_receipt_query = $this->db->get();
                            $metal_payment_receipts = $metal_payment_receipt_query->result();
                            if (!empty($metal_payment_receipts)) {
                                foreach ($metal_payment_receipts as $metal_payment_receipt_row) {
//                                    print_r($metal_payment_receipt_row);
//                                    echo '<br>';
                                    
                                    // If Type Payment then stock in minus
                                    if($metal_payment_receipt_row->metal_payment_receipt == '1'){
                                        $metal_payment_receipt_grwt = ZERO_VALUE - $metal_payment_receipt_row->metal_grwt;
                                        $metal_payment_receipt_ntwt = ZERO_VALUE - $metal_payment_receipt_row->metal_ntwt;
                                        $metal_payment_receipt_fine = ZERO_VALUE - $metal_payment_receipt_row->metal_fine;
                                    } else {
                                        $metal_payment_receipt_grwt = $metal_payment_receipt_row->metal_grwt;
                                        $metal_payment_receipt_ntwt = $metal_payment_receipt_row->metal_ntwt;
                                        $metal_payment_receipt_fine = $metal_payment_receipt_row->metal_fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $metal_payment_receipt_row->metal_item_id));
                                    if($stock_method != STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $where_opening = array('department_id' => $sell_row->process_id, 'category_id' => $metal_payment_receipt_row->metal_category_id, 'item_id' => $metal_payment_receipt_row->metal_item_id, 'tunch' => $metal_payment_receipt_row->metal_tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                    }
                                    if(!empty($exist_opening)){
                                        $exist_opening = $exist_opening[0];
                                        $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $metal_payment_receipt_grwt, '3', '.', '');
                                        $opening_stock_less = '0';
                                        $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $metal_payment_receipt_ntwt, '3', '.', '');
                                        $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $metal_payment_receipt_fine, '3', '.', '');
                                        $update_opening_stock = array();
                                        $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                        $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                        $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                        $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                        $update_opening_stock['updated_at'] = $this->now_time;
                                        $update_opening_stock['updated_by'] = $this->logged_in_id;
                                        $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                    } else {
                                        $insert_opening_stock = array();
                                        $insert_opening_stock['department_id'] = $sell_row->process_id;
                                        $insert_opening_stock['category_id'] = $metal_payment_receipt_row->metal_category_id;
                                        $insert_opening_stock['item_id'] = $metal_payment_receipt_row->metal_item_id;
                                        $insert_opening_stock['tunch'] = $metal_payment_receipt_row->metal_tunch;
                                        $insert_opening_stock['grwt'] = number_format((float) $metal_payment_receipt_grwt, '3', '.', '');
                                        $insert_opening_stock['less'] = '0';
                                        $insert_opening_stock['ntwt'] = number_format((float) $metal_payment_receipt_ntwt, '3', '.', '');
                                        $insert_opening_stock['fine'] = number_format((float) $metal_payment_receipt_fine, '3', '.', '');
                                        $insert_opening_stock['created_at'] = $this->now_time;
                                        $insert_opening_stock['created_by'] = $this->logged_in_id;
                                        $insert_opening_stock['updated_at'] = $this->now_time;
                                        $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                        $this->crud->insert('opening_stock', $insert_opening_stock);
                                    }
                                    
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->process_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $metal_payment_receipt_row->metal_category_id));
                                        
                                        if($metal_payment_receipt_row->metal_payment_receipt == '1'){
                                            
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $metal_payment_receipt_row->metal_category_id));
                                        
                                        if($metal_payment_receipt_row->metal_payment_receipt == '1'){
                                            
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                            }
                                            
                                        } else {
                                            
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            // Delete metal_payment_receipt data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('metal_payment_receipt');
                            // Delete metal_payment_receipt_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('metal_payment_receipt_log');
                            
                            // Sell Gold Bhav Data
                            $this->db->select("*");
                            $this->db->from("gold_bhav");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $gold_bhav_query = $this->db->get();
                            $gold_bhav_data = $gold_bhav_query->result();
                            if (!empty($gold_bhav_data)) {
                                foreach ($gold_bhav_data as $gold_bhav_row) {
//                                    print_r($gold_bhav_row);
//                                    echo '<br>';
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        if($gold_bhav_row->gold_sale_purchase == '1'){ // Sell
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $gold_bhav_row->gold_weight, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $gold_bhav_row->gold_value, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            if($gold_bhav_row->gold_cr_effect == '2'){ // r_amt
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $gold_bhav_row->r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                            } else { // c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $gold_bhav_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                    'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                            
                                        } else {  // Purchase
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $gold_bhav_row->gold_weight, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $gold_bhav_row->gold_value, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            if($gold_bhav_row->gold_cr_effect == '2'){ // r_amt
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $gold_bhav_row->r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                            } else { // c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $gold_bhav_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                    'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                        }
                                    }
                                }
                            }
                            // Delete gold_bhav data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('gold_bhav');
                            // Delete gold_bhav_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('gold_bhav_log');
                            
                            // Sell Silver Bhav Data
                            $this->db->select("*");
                            $this->db->from("silver_bhav");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $silver_bhav_query = $this->db->get();
                            $silver_bhav_data = $silver_bhav_query->result();
                            if (!empty($silver_bhav_data)) {
                                foreach ($silver_bhav_data as $silver_bhav_row) {
//                                    print_r($silver_bhav_row);
//                                    echo '<br>';
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        if($silver_bhav_row->silver_sale_purchase == '1'){ // Sell
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $silver_bhav_row->silver_weight, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $silver_bhav_row->silver_value, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            if($silver_bhav_row->silver_cr_effect == '2'){ // r_amt
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $silver_bhav_row->r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                            } else { // c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $silver_bhav_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                    'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                        } else {  // Purchase
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $silver_bhav_row->silver_weight, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $silver_bhav_row->silver_value, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            if($silver_bhav_row->silver_cr_effect == '2'){ // r_amt
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $silver_bhav_row->r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                            } else { // c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $silver_bhav_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                    'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                        }
                                    }
                                }
                            }
                            // Delete silver_bhav data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('silver_bhav');
                            // Delete silver_bhav_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('silver_bhav_log');
                            
                            // Sell Transfer Data
                            $this->db->select("*");
                            $this->db->from("transfer");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $transfer_query = $this->db->get();
                            $transfer_data = $transfer_query->result();
                            if (!empty($transfer_data)) {
                                foreach ($transfer_data as $transfer_row) {
//                                    print_r($transfer_row);
//                                    echo '<br>';
                                    
                                    // Update From Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        if($transfer_row->naam_jama == '1'){ // Naam
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            // transfer_amount Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $transfer_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                        } else {  // Jama
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            // transfer_amount Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $transfer_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_row->account_id)
                                            );
                                            
                                        }
                                    }
                                    
                                    // Update Transfer To Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $transfer_row->transfer_account_id);
                                    if(!empty($account_data)){
                                        if($transfer_row->naam_jama == '1'){ // Naam
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            // transfer_amount Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $transfer_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $transfer_row->transfer_account_id)
                                            );
                                            
                                        } else {  // Jama
                                            
                                            if($account_data->gold_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                            }
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                            $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                            if($account_data->opening_balance_in_gold < 0){
                                                $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                $gold_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                $gold_ob_credit_debit = '2';
                                            }
                                            
                                            if($account_data->silver_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                            }
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                            $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                            if($account_data->opening_balance_in_silver < 0){
                                                $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                $silver_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                $silver_ob_credit_debit = '2';
                                            }

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                $rupees_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                $rupees_ob_credit_debit = '2';
                                            }
                                            // transfer_amount Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $transfer_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                    'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                    'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $transfer_row->transfer_account_id)
                                            );
                                            
                                        }
                                    }
                                    
                                }
                            }
                            // Delete transfer data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('transfer');
                            // Delete transfer_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('transfer_log');
                            
                            // Sell Ad Charges Data
                            $this->db->select("sac.*, s.account_id");
                            $this->db->from("sell_ad_charges sac");
                            $this->db->join('sell s', 's.sell_id = sac.sell_id', 'left');
                            $this->db->where('sac.sell_id', $sell_row->sell_id);
                            $sell_ad_charges_query = $this->db->get();
                            $sell_ad_charges = $sell_ad_charges_query->result();
                            if (!empty($sell_ad_charges)) {
                                foreach ($sell_ad_charges as $sell_ad_charges_row) {
//                                    print_r($sell_ad_charges_row);
//                                    echo '<br>';
                                    
                                    // Update MF Loss Opening balance
                                    $mf_loss_data = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                                    if(!empty($mf_loss_data)){

                                        if($mf_loss_data->rupees_ob_credit_debit == '1'){
                                            $mf_loss_data->opening_balance_in_rupees = ZERO_VALUE - $mf_loss_data->opening_balance_in_rupees;
                                        }
                                        $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_ad_charges_row->ad_amount, '2', '.', '');
                                        $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '');
                                        if($mf_loss_data->opening_balance_in_rupees < 0){
                                            $this->crud->update('account', array('opening_balance_in_rupees' => abs($mf_loss_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_rupees' => $mf_loss_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                        }

                                    }
                                    
                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_ad_charges_row->account_id);
                                    if(!empty($account_data)){
                                            
                                        if($account_data->rupees_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                        }
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $sell_ad_charges_row->ad_amount, '2', '.', '');
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                        if($account_data->opening_balance_in_rupees < 0){
                                            $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_ad_charges_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_ad_charges_row->account_id));
                                        }
                                        // ad_amount Effect for c_amt
                                        if($account_data->c_amount_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                        }
                                        $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_ad_charges_row->c_amt, '2', '.', '');
                                        $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                        if($account_data->opening_balance_in_c_amount < 0){
                                            $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_ad_charges_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_ad_charges_row->account_id));
                                        }
                                    }
                                }
                            }
                            // Delete sell_ad_charges data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_ad_charges');
                            // Delete sell_ad_charges_log data
                            $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_ad_charges_log');

                            // Sell Adjust CR Data
                            $this->db->select("a_cr.*, s.account_id");
                            $this->db->from("sell_adjust_cr a_cr");
                            $this->db->join('sell s', 's.sell_id = a_cr.sell_id', 'left');
                            $this->db->where('a_cr.sell_id', $sell_row->sell_id);
                            $sell_adjust_cr_query = $this->db->get();
                            $sell_adjust_cr = $sell_adjust_cr_query->result();
                            if (!empty($sell_adjust_cr)) {
                                foreach ($sell_adjust_cr as $sell_adjust_cr_row) {
//                                    print_r($sell_adjust_cr_row);
//                                    echo '<br>';

                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_adjust_cr_row->account_id);
                                    if(!empty($account_data)){

                                        if($sell_adjust_cr_row->adjust_to == '2'){ // c_amt to r_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }
                                            if($account_data->r_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                            }
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_r_amount < 0){
                                                $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                $r_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = '2';
                                            }
                                        } else { // r_amt to c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                $c_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                $c_amount_ob_credit_debit = '2';
                                            }
                                            if($account_data->r_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                            }
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_r_amount < 0){
                                                $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                $r_amount_ob_credit_debit = '1';
                                            } else {
                                                $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                $r_amount_ob_credit_debit = '2';
                                            }
                                        }

                                        $this->crud->update('account', 
                                            array(
                                                'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                            ), 
                                            array('account_id' => $sell_adjust_cr_row->account_id)
                                        );
                                    }
                                }
                            }
                            // Delete sell_adjust_cr data
                            $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_adjust_cr');

                            // discount_amount Effect
                            if(isset($sell_row->discount_amount) && !empty($sell_row->discount_amount)){
                                $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                if(!empty($account_data)){
                                    if($account_data->rupees_ob_credit_debit == '1'){
                                        $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                    }
                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_row->discount_amount, '2', '.', '');
                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                    if($account_data->opening_balance_in_rupees < 0){
                                        $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                    } else {
                                        $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                    }

                                    // discount_amount Effect for c_amt
                                    if($account_data->c_amount_ob_credit_debit == '1'){
                                        $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                    }
                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $sell_row->discount_amount, '2', '.', '');
                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                    if($account_data->opening_balance_in_c_amount < 0){
                                        $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                    } else {
                                        $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                    }
                                }
                            }

                            // Delete sell data
                            $result = $this->db->where('sell_id', $sell_row->sell_id)->delete('sell');
                            if($result){
                                $data_deleted = '1';
                                // Delete sell_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `sell_log` WHERE `sell_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `sell_items_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                        $delete_details_log_sql1 = "DELETE FROM `payment_receipt_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql1);
                        $delete_details_log_sql2 = "DELETE FROM `metal_payment_receipt_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql2);
                        $delete_details_log_sql3 = "DELETE FROM `gold_bhav_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql3);
                        $delete_details_log_sql4 = "DELETE FROM `silver_bhav_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql4);
                        $delete_details_log_sql5 = "DELETE FROM `transfer_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql5);
                        $delete_details_log_sql6 = "DELETE FROM `sell_ad_charges_log` WHERE `sell_id` NOT IN( SELECT `sell_id` FROM `sell_log`)";
                        $this->gurulog_db->query($delete_details_log_sql6);
                    }
                    
                }
                
                if(in_array('module_cashbook', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("payment_receipt");
                    $this->db->where('transaction_date <=', $delete_upto);
//                    $this->db->where('transaction_date', $delete_upto);
                    $this->db->where('sell_id IS NULL', null, false);
                    $this->db->where('other_id IS NULL', null, false);
                    $cashbook_query = $this->db->get();
                    $cashbook_results = $cashbook_query->result();
                    if (!empty($cashbook_results)) {
                        foreach ($cashbook_results as $cashbook_row) {
//                            print_r($cashbook_row);
//                            echo '<br>';
                            
                            // If Type receipt then amount in minus
                            if($cashbook_row->payment_receipt == '2'){
                                $payment_receipt_amount = ZERO_VALUE - $cashbook_row->amount;
                                $payment_receipt_c_amt = ZERO_VALUE - $cashbook_row->c_amt;
                                $payment_receipt_r_amt = ZERO_VALUE - $cashbook_row->r_amt;
                            } else {
                                $payment_receipt_amount = $cashbook_row->amount;
                                $payment_receipt_c_amt = $cashbook_row->c_amt;
                                $payment_receipt_r_amt = $cashbook_row->r_amt;
                            }

                            // Update Department Opening balance
                            $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $cashbook_row->department_id);
                            if(!empty($department_data)){

                                if($department_data->rupees_ob_credit_debit == '1'){
                                    $department_data->opening_balance_in_rupees = ZERO_VALUE - $department_data->opening_balance_in_rupees;
                                }
                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '');
                                if($department_data->opening_balance_in_rupees < 0){
                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($department_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->department_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_rupees' => $department_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->department_id));
                                }

                            }
                            
                            // Update Account Opening balance
                            $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $cashbook_row->account_id);
                            if(!empty($account_data)){

                                if($account_data->rupees_ob_credit_debit == '1'){
                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                }
                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $payment_receipt_amount, '2', '.', '');
                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                if($account_data->opening_balance_in_rupees < 0){
                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->account_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->account_id));
                                }

                                // amount Effect for c_amt
                                if($account_data->c_amount_ob_credit_debit == '1'){
                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                }
                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $payment_receipt_c_amt, '2', '.', '');
                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                if($account_data->opening_balance_in_c_amount < 0){
                                    $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->account_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->account_id));
                                }

                            }
                            // Delete payment_receipt data
                            $result = $this->db->where('pay_rec_id', $cashbook_row->pay_rec_id)->delete('payment_receipt');
                            if($result){
                                $data_deleted = '1';
                                // Delete payment_receipt_log data
                                $this->gurulog_db->where('pay_rec_id', $cashbook_row->pay_rec_id)->delete('payment_receipt_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `payment_receipt_log` WHERE `transaction_date`<='".$delete_upto."' AND sell_id IS NULL AND other_id IS NULL ";
                        $this->gurulog_db->query($delete_log_sql);
                    }
                    
                }
                
                if(in_array('module_stock_transfer', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("stock_transfer");
                    $this->db->where('transfer_date <=', $delete_upto);
//                    $this->db->where('transfer_date', $delete_upto);
                    $stock_transfer_query = $this->db->get();
                    $stock_transfer_results = $stock_transfer_query->result();
                    if (!empty($stock_transfer_results)) {

                        // Delete Empty Opening Stock Data of itemwise item
                        $this->db->where('category_id', EXCHANGE_DEFAULT_CATEGORY_ID)->where('item_id', EXCHANGE_DEFAULT_ITEM_ID)->where('grwt', '0')->where('ntwt', '0')->delete('opening_stock');

                        foreach ($stock_transfer_results as $stock_transfer_row) {
//                            print_r($stock_transfer_row);
//                            echo '<br>';
                            
                            // Stock Transfer Line item Data
                            $this->db->select("*");
                            $this->db->from("stock_transfer_detail");
                            $this->db->where('stock_transfer_id', $stock_transfer_row->stock_transfer_id);
                            $stock_transfer_detail_query = $this->db->get();
                            $stock_transfer_details = $stock_transfer_detail_query->result();
                            if (!empty($stock_transfer_details)) {
                                foreach ($stock_transfer_details as $stock_transfer_detail_row) {
//                                    print_r($stock_transfer_row);
//                                    echo '<br>';
                                    
                                    // Check and update Item Opening stock in From Department
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $stock_transfer_detail_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = NULL;
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$stock_transfer_row->from_department."' AND `category_id` = '".$stock_transfer_detail_row->category_id."' AND `item_id` = '".$stock_transfer_detail_row->item_id."' AND `tunch` = '".$stock_transfer_detail_row->tunch."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $stock_transfer_detail_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $stock_transfer_detail_row->transfer_detail_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') - number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') - number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') - number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $stock_transfer_row->from_department;
                                            $insert_opening_stock['category_id'] = $stock_transfer_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $stock_transfer_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $stock_transfer_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            if(isset($stock_transfer_detail_row->purchase_sell_item_id) && !empty($stock_transfer_detail_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $stock_transfer_detail_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $stock_transfer_detail_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $stock_transfer_detail_row->transfer_detail_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $stock_transfer_row->from_department, 'category_id' => $stock_transfer_detail_row->category_id, 'item_id' => $stock_transfer_detail_row->item_id, 'tunch' => $stock_transfer_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') - number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') - number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') - number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $stock_transfer_row->from_department;
                                            $insert_opening_stock['category_id'] = $stock_transfer_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $stock_transfer_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $stock_transfer_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = ZERO_VALUE - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                    
                                    // Check and update Item Opening stock in To Department
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $stock_transfer_detail_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = STOCK_TYPE_STOCK_TRANSFER_ID;
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$stock_transfer_row->to_department."' AND `category_id` = '".$stock_transfer_detail_row->category_id."' AND `item_id` = '".$stock_transfer_detail_row->item_id."' AND `tunch` = '".$stock_transfer_detail_row->tunch."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $stock_transfer_detail_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $stock_transfer_detail_row->transfer_detail_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $stock_transfer_row->to_department;
                                            $insert_opening_stock['category_id'] = $stock_transfer_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $stock_transfer_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $stock_transfer_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            if(isset($stock_transfer_detail_row->purchase_sell_item_id) && !empty($stock_transfer_detail_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $stock_transfer_detail_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $stock_transfer_detail_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $stock_transfer_detail_row->transfer_detail_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $stock_transfer_row->to_department, 'category_id' => $stock_transfer_detail_row->category_id, 'item_id' => $stock_transfer_detail_row->item_id, 'tunch' => $stock_transfer_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $stock_transfer_row->to_department;
                                            $insert_opening_stock['category_id'] = $stock_transfer_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $stock_transfer_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $stock_transfer_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $stock_transfer_detail_row->grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $stock_transfer_detail_row->less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $stock_transfer_detail_row->ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                    
                                    // Update into From Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $stock_transfer_row->from_department);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $stock_transfer_detail_row->category_id));
                                        
                                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                            if($department_data->gold_ob_credit_debit == '1'){
                                                $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                            }
                                            $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                            if($department_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $stock_transfer_row->from_department));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $stock_transfer_row->from_department));
                                            }

                                        } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                            if($department_data->silver_ob_credit_debit == '1'){
                                                $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                            }
                                            $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                            if($department_data->opening_balance_in_silver < 0){
                                                $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $stock_transfer_row->from_department));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $stock_transfer_row->from_department));
                                            }
                                        }
                                    }
                                    
                                    // Update into To Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $stock_transfer_row->to_department);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $stock_transfer_detail_row->category_id));
                                        
                                        if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                            if($department_data->gold_ob_credit_debit == '1'){
                                                $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                            }
                                            $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                            if($department_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $stock_transfer_row->to_department));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $stock_transfer_row->to_department));
                                            }

                                        } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                            if($department_data->silver_ob_credit_debit == '1'){
                                                $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                            }
                                            $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $stock_transfer_detail_row->fine, '3', '.', '');
                                            $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                            if($department_data->opening_balance_in_silver < 0){
                                                $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $stock_transfer_row->to_department));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $stock_transfer_row->to_department));
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            // Delete stock_transfer_detail data
                            $this->db->where('stock_transfer_id', $stock_transfer_row->stock_transfer_id)->delete('stock_transfer_detail');
                            
                            // Delete stock_transfer data
                            $result = $this->db->where('stock_transfer_id', $stock_transfer_row->stock_transfer_id)->delete('stock_transfer');
                            if($result){
                                $data_deleted = '1';
                                // Delete stock_transfer_detail_log data
                                $this->gurulog_db->where('stock_transfer_id', $stock_transfer_row->stock_transfer_id)->delete('stock_transfer_detail_log');
                                // Delete stock_transfer_log data
                                $this->gurulog_db->where('stock_transfer_id', $stock_transfer_row->stock_transfer_id)->delete('stock_transfer_log');

                                // Delete Empty Item Stock Data of itemwise item
                                $this->db->where('category_id', EXCHANGE_DEFAULT_CATEGORY_ID)->where('item_id', EXCHANGE_DEFAULT_ITEM_ID)->where('grwt', '0')->where('ntwt', '0')->delete('item_stock');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `stock_transfer_log` WHERE `transfer_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `stock_transfer_detail_log` WHERE `stock_transfer_id` NOT IN( SELECT `stock_transfer_id` FROM `stock_transfer_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_journal', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("journal");
                    $this->db->where('journal_date <=', $delete_upto);
//                    $this->db->where('journal_date', $delete_upto);
                    $this->db->where('relation_id IS NULL', null, false);
                    $journal_query = $this->db->get();
                    $journal_results = $journal_query->result();
                    if (!empty($journal_results)) {
                        foreach ($journal_results as $journal_row) {
//                            print_r($journal_row);
//                            echo '<br>';
                            
                            // Journal Lineitem Data
                            $this->db->select("*");
                            $this->db->from("journal_details");
                            $this->db->where('journal_id', $journal_row->journal_id);
                            $journal_details_query = $this->db->get();
                            $journal_details = $journal_details_query->result();
                            if (!empty($journal_details)) {
                                foreach ($journal_details as $journal_detail_row) {
//                                    print_r($journal_detail_row);
//                                    echo '<br>';

                                    // If Type Naam/Jama then amount in plus/minus
                                    if($journal_detail_row->type == '1'){ // Naam
                                        $journal_amount = $journal_detail_row->amount;
                                        $journal_c_amt = $journal_detail_row->c_amt;
                                        $journal_r_amt = $journal_detail_row->r_amt;
                                    } else { // Jama
                                        $journal_amount = ZERO_VALUE - $journal_detail_row->amount;
                                        $journal_c_amt = ZERO_VALUE - $journal_detail_row->c_amt;
                                        $journal_r_amt = ZERO_VALUE - $journal_detail_row->r_amt;
                                    }

                                    // Update Account Opening balance
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $journal_detail_row->account_id);
                                    if(!empty($account_data)){
                                        if($account_data->rupees_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                        }
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $journal_amount, '2', '.', '');
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                        if($account_data->opening_balance_in_rupees < 0){
                                            $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                        }

                                        // amount Effect for c_amt
                                        if(!empty($journal_c_amt) && $journal_c_amt != '0'){
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $journal_c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                            }
                                        }

                                        // amount Effect for r_amt
                                        if(!empty($journal_r_amt) && $journal_r_amt != '0'){
                                            if($account_data->r_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                            }
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $journal_r_amt, '2', '.', '');
                                            $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_r_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_r_amount' => abs($account_data->opening_balance_in_r_amount), 'r_amount_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_r_amount' => $account_data->opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                            }
                                        }
                                    }
                                }
                            }
                            // Delete journal_details data
                            $this->db->where('journal_id', $journal_row->journal_id)->delete('journal_details');

                            // Delete journal data
                            $result = $this->db->where('journal_id', $journal_row->journal_id)->delete('journal');
                            if($result){
                                $data_deleted = '1';
                                // Delete journal_details_log data
                                $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_details_log');
                                // Delete journal_log data
                                $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_log');
                            }
                            
                        }
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `journal_log` WHERE `journal_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `journal_details_log` WHERE `journal_id` NOT IN( SELECT `journal_id` FROM `journal_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_hand_made', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("manu_hand_made");
                    $this->db->where('mhm_date <=', $delete_upto);
                    $this->db->where('hisab_done', '1');
                    $manu_hand_made_query = $this->db->get();
                    $manu_hand_mades = $manu_hand_made_query->result();
                    if (!empty($manu_hand_mades)) {
                        foreach ($manu_hand_mades as $manu_hand_made_row) {
//                            print_r($manu_hand_made_row);
//                            echo '<br>';
                            
                            // Menufacture Hand Made Lineitem Data
                            $this->db->select("*");
                            $this->db->from("manu_hand_made_details");
                            $this->db->where('mhm_id', $manu_hand_made_row->mhm_id);
                            $manu_hand_made_details_query = $this->db->get();
                            $manu_hand_made_details = $manu_hand_made_details_query->result();
                            if (!empty($manu_hand_made_details)) {
                                foreach ($manu_hand_made_details as $manu_hand_made_detail_row) {
//                                    print_r($manu_hand_made_detail_row);
//                                    echo '<br>';
                                    
                                    // If Type Issue then stock in minus
                                    if($manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                                        $manu_hand_made_grwt = ZERO_VALUE - $manu_hand_made_detail_row->weight;
                                        $manu_hand_made_less = ZERO_VALUE - $manu_hand_made_detail_row->less;
                                        $manu_hand_made_ntwt = ZERO_VALUE - $manu_hand_made_detail_row->net_wt;
                                        $manu_hand_made_fine = ZERO_VALUE - $manu_hand_made_detail_row->fine;
                                    } else {
                                        $manu_hand_made_grwt = $manu_hand_made_detail_row->weight;
                                        $manu_hand_made_less = $manu_hand_made_detail_row->less;
                                        $manu_hand_made_ntwt = $manu_hand_made_detail_row->net_wt;
                                        $manu_hand_made_fine = $manu_hand_made_detail_row->fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $manu_hand_made_detail_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = NULL;
                                        if($manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                                            $stock_type = STOCK_TYPE_MHM_RECEIVE_FINISH_ID;
                                        } else if($manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
                                            $stock_type = STOCK_TYPE_MHM_RECEIVE_SCRAP_ID;
                                        }
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$manu_hand_made_row->department_id."' AND `category_id` = '".$manu_hand_made_detail_row->category_id."' AND `item_id` = '".$manu_hand_made_detail_row->item_id."' AND `tunch` = '".$manu_hand_made_detail_row->tunch."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $manu_hand_made_detail_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $manu_hand_made_detail_row->mhm_detail_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $manu_hand_made_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $manu_hand_made_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $manu_hand_made_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $manu_hand_made_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $manu_hand_made_row->department_id;
                                            $insert_opening_stock['category_id'] = $manu_hand_made_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $manu_hand_made_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $manu_hand_made_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $manu_hand_made_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $manu_hand_made_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $manu_hand_made_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $manu_hand_made_fine, '3', '.', '');
                                            if(isset($manu_hand_made_detail_row->purchase_sell_item_id) && !empty($manu_hand_made_detail_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $manu_hand_made_detail_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $manu_hand_made_detail_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $manu_hand_made_detail_row->mhm_detail_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $manu_hand_made_row->department_id, 'category_id' => $manu_hand_made_detail_row->category_id, 'item_id' => $manu_hand_made_detail_row->item_id, 'tunch' => $manu_hand_made_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $manu_hand_made_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $manu_hand_made_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $manu_hand_made_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $manu_hand_made_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $manu_hand_made_row->department_id;
                                            $insert_opening_stock['category_id'] = $manu_hand_made_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $manu_hand_made_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $manu_hand_made_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $manu_hand_made_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $manu_hand_made_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $manu_hand_made_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $manu_hand_made_fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                    
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $manu_hand_made_row->department_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $manu_hand_made_detail_row->category_id));
                                        
                                        if($manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->department_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->department_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Worker Opening balance
                                    $worker_data = $this->crud->get_data_row_by_id('account', 'account_id', $manu_hand_made_row->worker_id);
                                    if(!empty($worker_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $manu_hand_made_detail_row->category_id));
                                        // Manufacture Hand Made ma Worker ma Silver ne Gold ma count krvanu hovathi category group ni condition nthi
                                        if($manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $manu_hand_made_detail_row->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->worker_id));
                                            }
                                            
                                        } else {
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $manu_hand_made_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $manu_hand_made_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $manu_hand_made_row->worker_id));
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            
                            // Delete manu_hand_made and manu_hand_made_details and manu_hand_made_order_items data
                            $this->db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_order_items');
                            $this->db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_details');
                            $this->db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_ads');
                            $result = $this->db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made');
                            if($result){
                                $data_deleted = '1';
                                
                                // Menufacture Hand Made Lott Complete to Journal Data
                                $this->db->select("*");
                                $this->db->from("journal");
                                $this->db->where('relation_id', $manu_hand_made_row->mhm_id);
                                $this->db->where('is_module', '1');
                                $mhm_journal_query = $this->db->get();
                                $mhm_journal_data = $mhm_journal_query->result();
                                if (!empty($mhm_journal_data)) {
                                    foreach ($mhm_journal_data as $mhm_journal_row) {
//                                        print_r($mhm_journal_row);
//                                        echo '<br>';
                                        
                                        // Journal Lineitem Data
                                        $this->db->select("*");
                                        $this->db->from("journal_details");
                                        $this->db->where('journal_id', $mhm_journal_row->journal_id);
                                        $mhm_journal_details_query = $this->db->get();
                                        $mhm_journal_details = $mhm_journal_details_query->result();
                                        if (!empty($mhm_journal_details)) {
                                            foreach ($mhm_journal_details as $mhm_journal_detail_row) {
//                                                print_r($mhm_journal_detail_row);
//                                                echo '<br>';

                                                // If Type Naam/Jama then amount in plus/minus
                                                if($mhm_journal_detail_row->type == '1'){ // Naam
                                                    $journal_amount = $mhm_journal_detail_row->amount;
                                                } else { // Jama
                                                    $journal_amount = ZERO_VALUE - $mhm_journal_detail_row->amount;
                                                }

                                                // Update Account Opening balance
                                                $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $mhm_journal_detail_row->account_id);
                                                if(!empty($account_data)){
                                                    if($account_data->rupees_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                    }
                                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $journal_amount, '2', '.', '');
                                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                    if($account_data->opening_balance_in_rupees < 0){
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $mhm_journal_detail_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $mhm_journal_detail_row->account_id));
                                                    }
                                                }
                                            }
                                        }
                                        // Delete journal_details data
                                        $this->db->where('journal_id', $mhm_journal_row->journal_id)->delete('journal_details');

                                        // Delete journal data
                                        $result = $this->db->where('journal_id', $mhm_journal_row->journal_id)->delete('journal');
                                        if($result){
                                            $data_deleted = '1';
                                            // Delete journal_details_log data
                                            $this->gurulog_db->where('journal_id', $mhm_journal_row->journal_id)->delete('journal_details_log');
                                            // Delete journal_log data
                                            $this->gurulog_db->where('journal_id', $mhm_journal_row->journal_id)->delete('journal_log');
                                        }
                                    }
                                }
                                
                                // Delete manu_hand_made and manu_hand_made_details and manu_hand_made_order_items Log data
                                $this->gurulog_db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_order_items_log');
                                $this->gurulog_db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_details_log');
                                $this->gurulog_db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_ads_log');
                                $this->gurulog_db->where('mhm_id', $manu_hand_made_row->mhm_id)->delete('manu_hand_made_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    $this->db->select("whd.*");
                    $this->db->from("worker_hisab_detail whd");
                    $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
                    $this->db->where('wh.hisab_date <=', $delete_upto);
                    $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MHM);
                    $worker_hisab_detail_query = $this->db->get();
                    $worker_hisab_details = $worker_hisab_detail_query->result();
                    if (!empty($worker_hisab_details)) {
                        foreach ($worker_hisab_details as $worker_hisab_detail_key => $worker_hisab_detail_row) {

                            // Increase and Decrease Fine in Worker Account on Hisab Done
                            $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_detail_row->worker_id);
                            if(!empty($worker_accounts)){
                                $worker_opening_balance_in_gold = number_format((float) $worker_accounts->opening_balance_in_gold, '3', '.', '');
                                if($worker_accounts->gold_ob_credit_debit == '1'){
                                    $worker_opening_balance_in_gold = ZERO_VALUE - $worker_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold - number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + $worker_hisabs[0]->fine;
                                }

                                // Update to Worker Account
                                $worker_opening_balance_in_gold = number_format((float) $worker_opening_balance_in_gold, '3', '.', '');
                                if($worker_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $worker_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                }
                            }

                            // Increase and Decrease Fine in MF Loss Account on Hisab Done
                            $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                            if(!empty($mf_loss_accounts)){
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_accounts->opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_accounts->gold_ob_credit_debit == '1'){
                                    $mf_loss_opening_balance_in_gold = ZERO_VALUE - $mf_loss_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold + number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - $worker_hisabs[0]->fine;
                                }
                                
                                // Update to MF Loss Account
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($mf_loss_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $mf_loss_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                }
                            }

                            // Delete worker_hisab data
                            $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab');
                            // Delete worker_hisab_log data
                            $this->gurulog_db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab_log');
                            
                        }
                    }
                    // Delete worker_hisab_detail data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab`)";
                    $this->crud->execuetSQL($delete_orderitem_sql);
                    // Delete worker_hisab_detail_log data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail_log` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab_log`)";
                    $this->gurulog_db->query($delete_orderitem_sql);
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `manu_hand_made_log` WHERE `mhm_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `manu_hand_made_details_log` WHERE `mhm_id` NOT IN( SELECT `mhm_id` FROM `manu_hand_made_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                        $delete_details_log_sql1 = "DELETE FROM `manu_hand_made_order_items_log` WHERE `mhm_id` NOT IN( SELECT `mhm_id` FROM `manu_hand_made_log`)";
                        $this->gurulog_db->query($delete_details_log_sql1);
                        $delete_details_log_sql2 = "DELETE FROM `manu_hand_made_ads_log` WHERE `mhm_id` NOT IN( SELECT `mhm_id` FROM `manu_hand_made_log`)";
                        $this->gurulog_db->query($delete_details_log_sql2);
                        
                        $delete_log_sql = "DELETE FROM `journal_log` WHERE `journal_date`<='".$delete_upto."' AND `is_module`= ".MHM_TO_JOURNAL_ID." ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `journal_details_log` WHERE `journal_id` NOT IN( SELECT `journal_id` FROM `journal_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_machine_chain', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("machine_chain");
                    $this->db->where('machine_chain_date <=', $delete_upto);
                    $this->db->where('hisab_done', '1');
                    $machine_chain_query = $this->db->get();
                    $machine_chains = $machine_chain_query->result();
                    if (!empty($machine_chains)) {
                        foreach ($machine_chains as $machine_chain_row) {
//                            print_r($machine_chain_row);
//                            echo '<br>';
                            
                            // Menufacture Machine Chain Lineitem Data
                            $this->db->select("*");
                            $this->db->from("machine_chain_details");
                            $this->db->where('machine_chain_id', $machine_chain_row->machine_chain_id);
                            $machine_chain_details_query = $this->db->get();
                            $machine_chain_details = $machine_chain_details_query->result();
                            if (!empty($machine_chain_details)) {
                                foreach ($machine_chain_details as $machine_chain_detail_row) {
//                                    print_r($machine_chain_detail_row);
//                                    echo '<br>';
                                    
                                    // If Type Issue then stock in minus
                                    if($machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
                                        $machine_chain_grwt = ZERO_VALUE - $machine_chain_detail_row->weight;
                                        $machine_chain_less = ZERO_VALUE - $machine_chain_detail_row->less;
                                        $machine_chain_ntwt = ZERO_VALUE - $machine_chain_detail_row->net_wt;
                                        $machine_chain_fine = ZERO_VALUE - $machine_chain_detail_row->fine;
                                    } else {
                                        $machine_chain_grwt = $machine_chain_detail_row->weight;
                                        $machine_chain_less = $machine_chain_detail_row->less;
                                        $machine_chain_ntwt = $machine_chain_detail_row->net_wt;
                                        $machine_chain_fine = $machine_chain_detail_row->fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $machine_chain_detail_row->item_id));
                                    if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $stock_type = NULL;
                                        if($machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID){
                                            $stock_type = STOCK_TYPE_MC_RECEIVE_FINISH_ID;
                                        } else if($machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID){
                                            $stock_type = STOCK_TYPE_MC_RECEIVE_SCRAP_ID;
                                        }
                                        $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                        $opening_stock_sql .= " WHERE `department_id` = '".$machine_chain_row->department_id."' AND `category_id` = '".$machine_chain_detail_row->category_id."' AND `item_id` = '".$machine_chain_detail_row->item_id."' AND `tunch` = '".$machine_chain_detail_row->tunch."' ";
                                        $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $machine_chain_detail_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $machine_chain_detail_row->machine_chain_detail_id ."') AND `stock_type` IS NOT NULL";
                                        $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $machine_chain_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $machine_chain_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $machine_chain_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $machine_chain_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $machine_chain_row->department_id;
                                            $insert_opening_stock['category_id'] = $machine_chain_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $machine_chain_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $machine_chain_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $machine_chain_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $machine_chain_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $machine_chain_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $machine_chain_fine, '3', '.', '');
                                            if(isset($machine_chain_detail_row->purchase_sell_item_id) && !empty($machine_chain_detail_row->purchase_sell_item_id)){
                                                $insert_opening_stock['purchase_sell_item_id'] = $machine_chain_detail_row->purchase_sell_item_id;
                                                $insert_opening_stock['stock_type'] = $machine_chain_detail_row->stock_type;
                                            } else {
                                                $insert_opening_stock['purchase_sell_item_id'] = $machine_chain_detail_row->machine_chain_detail_id;
                                                $insert_opening_stock['stock_type'] = $stock_type;
                                            }
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    } else {
                                        $where_opening = array('department_id' => $machine_chain_row->department_id, 'category_id' => $machine_chain_detail_row->category_id, 'item_id' => $machine_chain_detail_row->item_id, 'tunch' => $machine_chain_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $machine_chain_grwt, '3', '.', '');
                                            $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $machine_chain_less, '3', '.', '');
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $machine_chain_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $machine_chain_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $machine_chain_row->department_id;
                                            $insert_opening_stock['category_id'] = $machine_chain_detail_row->category_id;
                                            $insert_opening_stock['item_id'] = $machine_chain_detail_row->item_id;
                                            $insert_opening_stock['tunch'] = $machine_chain_detail_row->tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $machine_chain_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = number_format((float) $machine_chain_less, '3', '.', '');
                                            $insert_opening_stock['ntwt'] = number_format((float) $machine_chain_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $machine_chain_fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }
                                    }
                                    
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $machine_chain_row->department_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $machine_chain_detail_row->category_id));
                                        
                                        if($machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->department_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->department_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Worker Opening balance
                                    $worker_data = $this->crud->get_data_row_by_id('account', 'account_id', $machine_chain_row->worker_id);
                                    if(!empty($worker_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $machine_chain_detail_row->category_id));
                                        // Manufacture Machin Chain ma Worker ma Silver ne Gold ma count krvanu hovathi category group ni condition nthi
                                        if($machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $machine_chain_detail_row->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->worker_id));
                                            }
                                            
                                        } else {
                                            
                                            if($worker_data->gold_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_gold = ZERO_VALUE - $worker_data->opening_balance_in_gold;
                                            }
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $machine_chain_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_gold = number_format((float) $worker_data->opening_balance_in_gold, '3', '.', '');
                                            if($worker_data->opening_balance_in_gold < 0){
                                                $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $machine_chain_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_gold' => $worker_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $machine_chain_row->worker_id));
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            
                            // Delete machine_chain, machine_chain_order_items, machine_chain_details and machine_chain_detail_order_items data
                            $this->db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_order_items');
                            $this->db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_detail_order_items');
                            $this->db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_details');
                            $result = $this->db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain');
                            if($result){
                                $data_deleted = '1';
                                
                                // Delete machine_chain, machine_chain_order_items, machine_chain_details and machine_chain_detail_order_items Log data
                                $this->gurulog_db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_order_items_log');
                                $this->gurulog_db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_detail_order_items_log');
                                $this->gurulog_db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_details_log');
                                $this->gurulog_db->where('machine_chain_id', $machine_chain_row->machine_chain_id)->delete('machine_chain_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    $this->db->select("whd.*");
                    $this->db->from("worker_hisab_detail whd");
                    $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
                    $this->db->where('wh.hisab_date <=', $delete_upto);
                    $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MC);
                    $worker_hisab_detail_query = $this->db->get();
                    $worker_hisab_details = $worker_hisab_detail_query->result();
                    if (!empty($worker_hisab_details)) {
                        foreach ($worker_hisab_details as $worker_hisab_detail_key => $worker_hisab_detail_row) {

                            // Increase and Decrease Fine in Worker Account on Hisab Done
                            $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_detail_row->worker_id);
                            if(!empty($worker_accounts)){
                                $worker_opening_balance_in_gold = number_format((float) $worker_accounts->opening_balance_in_gold, '3', '.', '');
                                if($worker_accounts->gold_ob_credit_debit == '1'){
                                    $worker_opening_balance_in_gold = ZERO_VALUE - $worker_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold - number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $worker_opening_balance_in_gold = $worker_opening_balance_in_gold + $worker_hisabs[0]->fine;
                                }

                                // Update to Worker Account
                                $worker_opening_balance_in_gold = number_format((float) $worker_opening_balance_in_gold, '3', '.', '');
                                if($worker_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($worker_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $worker_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                }
                            }

                            // Increase and Decrease Fine in MF Loss Account on Hisab Done
                            $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                            if(!empty($mf_loss_accounts)){
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_accounts->opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_accounts->gold_ob_credit_debit == '1'){
                                    $mf_loss_opening_balance_in_gold = ZERO_VALUE - $mf_loss_opening_balance_in_gold;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold + number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $mf_loss_opening_balance_in_gold = $mf_loss_opening_balance_in_gold - $worker_hisabs[0]->fine;
                                }
                                
                                // Update to MF Loss Account
                                $mf_loss_opening_balance_in_gold = number_format((float) $mf_loss_opening_balance_in_gold, '3', '.', '');
                                if($mf_loss_opening_balance_in_gold < 0){
                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($mf_loss_opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_gold' => $mf_loss_opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                }
                            }

                            // Delete worker_hisab data
                            $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab');
                            // Delete worker_hisab_log data
                            $this->gurulog_db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab_log');
                            
                        }
                    }
                    // Delete worker_hisab_detail data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab`)";
                    $this->crud->execuetSQL($delete_orderitem_sql);
                    // Delete worker_hisab_detail_log data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail_log` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab_log`)";
                    $this->gurulog_db->query($delete_orderitem_sql);
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `machine_chain_log` WHERE `machine_chain_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `machine_chain_details_log` WHERE `machine_chain_id` NOT IN( SELECT `machine_chain_id` FROM `machine_chain_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                        $delete_details_log_sql1 = "DELETE FROM `machine_chain_order_items_log` WHERE `machine_chain_id` NOT IN( SELECT `machine_chain_id` FROM `machine_chain_log`)";
                        $this->gurulog_db->query($delete_details_log_sql1);
                        $delete_details_log_sql2 = "DELETE FROM `machine_chain_detail_order_items_log` WHERE `machine_chain_id` NOT IN( SELECT `machine_chain_id` FROM `machine_chain_log`)";
                        $this->gurulog_db->query($delete_details_log_sql2);
                    }
                    
                }
                
                if(in_array('module_issue_receive_silver', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("issue_receive_silver");
                    $this->db->where('irs_date <=', $delete_upto);
                    $this->db->where('hisab_done', '1');
                    $issue_receive_silver_query = $this->db->get();
                    $issue_receive_silvers = $issue_receive_silver_query->result();
                    if (!empty($issue_receive_silvers)) {
                        foreach ($issue_receive_silvers as $issue_receive_silver_row) {
//                            print_r($issue_receive_silver_row);
//                            echo '<br>';
                            
                            $this->db->select("*");
                            $this->db->from("issue_receive_silver_details");
                            $this->db->where('irs_id', $issue_receive_silver_row->irs_id);
                            $issue_receive_silver_details_query = $this->db->get();
                            $issue_receive_silver_details = $issue_receive_silver_details_query->result();
                            if (!empty($issue_receive_silver_details)) {
                                foreach ($issue_receive_silver_details as $issue_receive_silver_detail_row) {
//                                    print_r($issue_receive_silver_detail_row);
//                                    echo '<br>';
                                    
                                    // If Type Issue then stock in minus
                                    if($issue_receive_silver_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                        $issue_receive_silver_grwt = ZERO_VALUE - $issue_receive_silver_detail_row->weight;
                                        $issue_receive_silver_less = ZERO_VALUE - $issue_receive_silver_detail_row->less;
                                        $issue_receive_silver_ntwt = ZERO_VALUE - $issue_receive_silver_detail_row->net_wt;
                                        $issue_receive_silver_fine = ZERO_VALUE - $issue_receive_silver_detail_row->fine;
                                    } else {
                                        $issue_receive_silver_grwt = $issue_receive_silver_detail_row->weight;
                                        $issue_receive_silver_less = $issue_receive_silver_detail_row->less;
                                        $issue_receive_silver_ntwt = $issue_receive_silver_detail_row->net_wt;
                                        $issue_receive_silver_fine = $issue_receive_silver_detail_row->fine;
                                    }
                                    
                                    // Check and update Item Opening stock
                                    $exist_opening = array();
                                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $issue_receive_silver_detail_row->item_id));
                                    if($stock_method != STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                        $where_opening = array('department_id' => $issue_receive_silver_row->department_id, 'category_id' => $issue_receive_silver_detail_row->category_id, 'item_id' => $issue_receive_silver_detail_row->item_id, 'tunch' => $issue_receive_silver_detail_row->tunch);
                                        $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                    }
                                    if(!empty($exist_opening)){
                                        $exist_opening = $exist_opening[0];
                                        $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $issue_receive_silver_grwt, '3', '.', '');
                                        $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $issue_receive_silver_less, '3', '.', '');
                                        $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $issue_receive_silver_ntwt, '3', '.', '');
                                        $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $issue_receive_silver_fine, '3', '.', '');
                                        $update_opening_stock = array();
                                        $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                        $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                        $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                        $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                        $update_opening_stock['updated_at'] = $this->now_time;
                                        $update_opening_stock['updated_by'] = $this->logged_in_id;
                                        $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                    } else {
                                        $insert_opening_stock = array();
                                        $insert_opening_stock['department_id'] = $issue_receive_silver_row->department_id;
                                        $insert_opening_stock['category_id'] = $issue_receive_silver_detail_row->category_id;
                                        $insert_opening_stock['item_id'] = $issue_receive_silver_detail_row->item_id;
                                        $insert_opening_stock['tunch'] = $issue_receive_silver_detail_row->tunch;
                                        $insert_opening_stock['grwt'] = number_format((float) $issue_receive_silver_grwt, '3', '.', '');
                                        $insert_opening_stock['less'] = number_format((float) $issue_receive_silver_less, '3', '.', '');
                                        $insert_opening_stock['ntwt'] = number_format((float) $issue_receive_silver_ntwt, '3', '.', '');
                                        $insert_opening_stock['fine'] = number_format((float) $issue_receive_silver_fine, '3', '.', '');
                                        $insert_opening_stock['created_at'] = $this->now_time;
                                        $insert_opening_stock['created_by'] = $this->logged_in_id;
                                        $insert_opening_stock['updated_at'] = $this->now_time;
                                        $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                        $this->crud->insert('opening_stock', $insert_opening_stock);
                                    }
                                    
                                    // Update Department Opening balance
                                    $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $issue_receive_silver_row->department_id);
                                    if(!empty($department_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $issue_receive_silver_detail_row->category_id));
                                        
                                        if($issue_receive_silver_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->department_id));
                                                }
                                            }
                                        } else {
                                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                if($department_data->gold_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                }
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                if($department_data->opening_balance_in_gold < 0){
                                                    $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->department_id));
                                                }
                                                
                                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                if($department_data->silver_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                }
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                                $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                if($department_data->opening_balance_in_silver < 0){
                                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->department_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Update Worker Opening balance
                                    $worker_data = $this->crud->get_data_row_by_id('account', 'account_id', $issue_receive_silver_row->worker_id);
                                    if(!empty($worker_data)){
                                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $issue_receive_silver_detail_row->category_id));
                                        // Manufacture Issue Receive ma Worker ma Silver ma count krvanu hovathi category group ni condition nthi
                                        if($issue_receive_silver_detail_row->type_id == MANUFACTURE_TYPE_ISSUE_ID){
                                            
                                            if($worker_data->silver_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_silver = ZERO_VALUE - $worker_data->opening_balance_in_silver;
                                            }
                                            $worker_data->opening_balance_in_silver = number_format((float) $worker_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_silver = number_format((float) $worker_data->opening_balance_in_silver, '3', '.', '');
                                            if($worker_data->opening_balance_in_silver < 0){
                                                $this->crud->update('account', array('opening_balance_in_silver' => abs($worker_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_silver' => $worker_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->worker_id));
                                            }
                                            
                                        } else {
                                            
                                            if($worker_data->silver_ob_credit_debit == '1'){
                                                $worker_data->opening_balance_in_silver = ZERO_VALUE - $worker_data->opening_balance_in_silver;
                                            }
                                            $worker_data->opening_balance_in_silver = number_format((float) $worker_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $issue_receive_silver_detail_row->fine, '3', '.', '');
                                            $worker_data->opening_balance_in_silver = number_format((float) $worker_data->opening_balance_in_silver, '3', '.', '');
                                            if($worker_data->opening_balance_in_silver < 0){
                                                $this->crud->update('account', array('opening_balance_in_silver' => abs($worker_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $issue_receive_silver_row->worker_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_silver' => $worker_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $issue_receive_silver_row->worker_id));
                                            }
                                            
                                        }
                                    }
                                }
                            }
                            
                            // Delete issue_receive_silver and issue_receive_silver_details data
                            $this->db->where('irs_id', $issue_receive_silver_row->irs_id)->delete('issue_receive_silver_details');
                            $result = $this->db->where('irs_id', $issue_receive_silver_row->irs_id)->delete('issue_receive_silver');
                            if($result){
                                $data_deleted = '1';
                                // Delete issue_receive_silver and issue_receive_silver_details Log data
                                $this->gurulog_db->where('irs_id', $issue_receive_silver_row->irs_id)->delete('issue_receive_silver_details_log');
                                $this->gurulog_db->where('irs_id', $issue_receive_silver_row->irs_id)->delete('issue_receive_silver_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    $this->db->select("whd.*");
                    $this->db->from("worker_hisab_detail whd");
                    $this->db->join('worker_hisab wh', 'wh.worker_hisab_id = whd.worker_hisab_id', 'left');
                    $this->db->where('wh.hisab_date <=', $delete_upto);
                    $this->db->where('whd.is_module', HISAB_DONE_IS_MODULE_MIR_SILVER);
                    $worker_hisab_detail_query = $this->db->get();
                    $worker_hisab_details = $worker_hisab_detail_query->result();
                    if (!empty($worker_hisab_details)) {
                        foreach ($worker_hisab_details as $worker_hisab_detail_key => $worker_hisab_detail_row) {

                            // Increase and Decrease Fine in Worker Account on Hisab Done
                            $worker_accounts = $this->crud->get_data_row_by_id('account', 'account_id', $worker_hisab_detail_row->worker_id);
                            if(!empty($worker_accounts)){
                                $worker_opening_balance_in_silver = number_format((float) $worker_accounts->opening_balance_in_silver, '3', '.', '');
                                if($worker_accounts->silver_ob_credit_debit == '1'){
                                    $worker_opening_balance_in_silver = ZERO_VALUE - $worker_opening_balance_in_silver;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $worker_opening_balance_in_silver = $worker_opening_balance_in_silver + number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $worker_opening_balance_in_silver = $worker_opening_balance_in_silver - number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $worker_opening_balance_in_silver = $worker_opening_balance_in_silver + $worker_hisabs[0]->fine;
                                }

                                // Update to Worker Account
                                $worker_opening_balance_in_silver = number_format((float) $worker_opening_balance_in_silver, '3', '.', '');
                                if($worker_opening_balance_in_silver < 0){
                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($worker_opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_silver' => $worker_opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $worker_hisab_detail_row->worker_id));
                                }
                            }

                            // Increase and Decrease Fine in MF Loss Account on Hisab Done
                            $mf_loss_accounts = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                            if(!empty($mf_loss_accounts)){
                                $mf_loss_opening_balance_in_silver = number_format((float) $mf_loss_accounts->opening_balance_in_silver, '3', '.', '');
                                if($mf_loss_accounts->silver_ob_credit_debit == '1'){
                                    $mf_loss_opening_balance_in_silver = ZERO_VALUE - $mf_loss_opening_balance_in_silver;
                                }

                                // Update Checked total fine = balance_fine
                                if($worker_hisab_detail_row->balance_fine < 0){ // Receive is more
                                    $balance_fine_positive = abs($worker_hisab_detail_row->balance_fine);
                                    $mf_loss_opening_balance_in_silver = $mf_loss_opening_balance_in_silver - number_format((float) $balance_fine_positive, '3', '.', '');
                                } else { // Issue is more
                                    $mf_loss_opening_balance_in_silver = $mf_loss_opening_balance_in_silver + number_format((float) $worker_hisab_detail_row->balance_fine, '3', '.', '');
                                }

                                // Update Hisab fine = $fine
                                $this->db->select("*");
                                $this->db->from("worker_hisab");
                                $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id);
                                $worker_hisab_query = $this->db->get();
                                $worker_hisabs = $worker_hisab_query->result();
                                if (!empty($worker_hisabs)) {
                                    $mf_loss_opening_balance_in_silver = $mf_loss_opening_balance_in_silver - $worker_hisabs[0]->fine;
                                }
                                
                                // Update to MF Loss Account
                                $mf_loss_opening_balance_in_silver = number_format((float) $mf_loss_opening_balance_in_silver, '3', '.', '');
                                if($mf_loss_opening_balance_in_silver < 0){
                                    $this->crud->update('account', array('opening_balance_in_silver' => abs($mf_loss_opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_silver' => $mf_loss_opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                }
                            }

                            // Delete worker_hisab data
                            $this->db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab');
                            // Delete worker_hisab_log data
                            $this->gurulog_db->where('worker_hisab_id', $worker_hisab_detail_row->worker_hisab_id)->delete('worker_hisab_log');
                            
                        }
                    }
                    // Delete worker_hisab_detail data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab`)";
                    $this->crud->execuetSQL($delete_orderitem_sql);
                    // Delete worker_hisab_detail_log data
                    $delete_orderitem_sql = "DELETE FROM `worker_hisab_detail_log` WHERE `worker_hisab_id` NOT IN( SELECT `worker_hisab_id` FROM `worker_hisab_log`)";
                    $this->gurulog_db->query($delete_orderitem_sql);
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `issue_receive_silver_log` WHERE `irs_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `issue_receive_silver_details_log` WHERE `irs_id` NOT IN( SELECT `irs_id` FROM `issue_receive_silver_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
                if(in_array('module_hr_attendance', $module_names)){
                    
                    $this->db->select("*");
                    $this->db->from("hr_attendance");
                    $this->db->where('attendance_date <=', $delete_upto);
//                    $this->db->where('attendance_date', $delete_upto);
                    $hr_attendance_query = $this->db->get();
                    $hr_attendance_results = $hr_attendance_query->result();
                    if (!empty($hr_attendance_results)) {
                        foreach ($hr_attendance_results as $hr_attendance_row) {
//                            print_r($hr_attendance_row);
//                            echo '<br>';
                            
                            // Delete hr_attendance data
                            $result = $this->db->where('attendance_id', $hr_attendance_row->attendance_id)->delete('hr_attendance');
                            if($result){
                                $data_deleted = '1';
                                // Delete hr_attendance_log data
                                $this->gurulog_db->where('attendance_id', $hr_attendance_row->attendance_id)->delete('hr_attendance_log');
                            }
                            
                        }
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `hr_attendance_log` WHERE `attendance_date`<='".$delete_upto."' ";
                        $this->gurulog_db->query($delete_log_sql);
                    }
                    
                }
                
                if(in_array('module_employee_salary', $module_names)){
                    
                    $select_employee_salary_sql = "SELECT * FROM `employee_salary` WHERE `created_at`<='".$delete_upto." 23:59:59' ";
                    $select_employee_salarys = $this->crud->getFromSQL($select_employee_salary_sql);
                    if(!empty($select_employee_salarys)){
                        foreach ($select_employee_salarys as $select_employee_salary){
                            
                            if(isset($select_employee_salary->journal_id) && !empty($select_employee_salary->journal_id)){
                                $this->db->select("*");
                                $this->db->from("journal");
                                $this->db->where('journal_id', $select_employee_salary->journal_id);
                                $journal_query = $this->db->get();
                                $journal_results = $journal_query->result();
                                if (!empty($journal_results)) {
                                    foreach ($journal_results as $journal_row) {
            //                            print_r($journal_row);
            //                            echo '<br>';

                                        // Journal Lineitem Data
                                        $this->db->select("*");
                                        $this->db->from("journal_details");
                                        $this->db->where('journal_id', $journal_row->journal_id);
                                        $journal_details_query = $this->db->get();
                                        $journal_details = $journal_details_query->result();
                                        if (!empty($journal_details)) {
                                            foreach ($journal_details as $journal_detail_row) {
            //                                    print_r($journal_detail_row);
            //                                    echo '<br>';

                                                // If Type Naam/Jama then amount in plus/minus
                                                if($journal_detail_row->type == '1'){ // Naam
                                                    $journal_amount = $journal_detail_row->amount;
                                                } else { // Jama
                                                    $journal_amount = ZERO_VALUE - $journal_detail_row->amount;
                                                }

                                                // Update Account Opening balance
                                                $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $journal_detail_row->account_id);
                                                if(!empty($account_data)){
                                                    if($account_data->rupees_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                    }
                                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $journal_amount, '2', '.', '');
                                                    $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                    if($account_data->opening_balance_in_rupees < 0){
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                                    }
                                                }
                                            }
                                        }
                                        // Delete journal_details data
                                        $this->db->where('journal_id', $journal_row->journal_id)->delete('journal_details');

                                        // Delete journal data
                                        $result = $this->db->where('journal_id', $journal_row->journal_id)->delete('journal');
                                        if($result){
                                            $data_deleted = '1';
                                            // Delete journal_details_log data
                                            $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_details_log');
                                            // Delete journal_log data
                                            $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_log');
                                        }

                                    }
                                } else {
                                    if($data_deleted != '1'){
                                        $data_deleted = '2';
                                    }
                                }
                            }
                            
                            // Delete employee_salary data
                            $result = $this->db->where('employee_salary_id', $select_employee_salary->employee_salary_id)->delete('employee_salary');
                            if($result){
                                $data_deleted = '1';
                                // Delete employee_salary_log data
                                $this->gurulog_db->where('employee_salary_id', $select_employee_salary->employee_salary_id)->delete('employee_salary_log');
                            }
                            
                        }
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                    if(isset($post_data['delete_all_log_upto_selected_date']) && $post_data['delete_all_log_upto_selected_date'] == '1'){
                        $delete_log_sql = "DELETE FROM `employee_salary_log` WHERE `created_at`<='".$delete_upto." 23:59:59' ";
                        $this->gurulog_db->query($delete_log_sql);
                        
                        $delete_log_sql = "DELETE FROM `journal_log` WHERE `journal_date`<='".$delete_upto."' AND `is_module`=".EMPLOYEE_SALARY_TO_JOURNAL_ID." ";
                        $this->gurulog_db->query($delete_log_sql);
                        $delete_details_log_sql = "DELETE FROM `journal_details_log` WHERE `journal_id` NOT IN( SELECT `journal_id` FROM `journal_log`)";
                        $this->gurulog_db->query($delete_details_log_sql);
                    }
                    
                }
                
//                if(isset($post_data['make_account_opening_0']) && $post_data['make_account_opening_0'] == '1'){
//                    
//                    $this->db->select("a.*");
//                    $this->db->from("account a");
//                    $this->db->join('account_group ag', 'ag.account_group_id = a.account_group_id', 'left');
//                    $this->db->where('ag.is_display_in_balance_sheet', '0');
//                    $account_query = $this->db->get();
//                    $account_results = $account_query->result();
//                    if (!empty($account_results)) {
//                        foreach ($account_results as $account_row) {
//                            
//                            if($account_row->gold_ob_credit_debit == '1'){
//                                $account_row->gold_fine = number_format((float) $account_row->gold_fine, '3', '.', '') + number_format((float) $account_row->opening_balance_in_gold, '3', '.', '');
//                            } else if($account_row->gold_ob_credit_debit == '2'){
//                                $account_row->gold_fine = number_format((float) $account_row->gold_fine, '3', '.', '') - number_format((float) $account_row->opening_balance_in_gold, '3', '.', '');
//                            }
//                            $account_row->gold_fine = number_format((float) $account_row->gold_fine, '3', '.', '');
//                                
//                            if($account_row->silver_ob_credit_debit == '1'){
//                                $account_row->silver_fine = number_format((float) $account_row->silver_fine, '3', '.', '') + number_format((float) $account_row->opening_balance_in_silver, '3', '.', '');
//                            } else if($account_row->silver_ob_credit_debit == '2'){
//                                $account_row->silver_fine = number_format((float) $account_row->silver_fine, '3', '.', '') - number_format((float) $account_row->opening_balance_in_silver, '3', '.', '');
//                            }
//                            $account_row->silver_fine = number_format((float) $account_row->silver_fine, '3', '.', '');
//                            
//                            if($account_row->rupees_ob_credit_debit == '1'){
//                                $account_row->amount = number_format((float) $account_row->amount, '2', '.', '') + number_format((float) $account_row->opening_balance_in_rupees, '2', '.', '');
//                            } else if($account_row->rupees_ob_credit_debit == '2'){
//                                $account_row->amount = number_format((float) $account_row->amount, '2', '.', '') - number_format((float) $account_row->opening_balance_in_rupees, '2', '.', '');
//                            }
//                            $account_row->amount = number_format((float) $account_row->amount, '2', '.', '');
//                            
//                            $this->crud->update('account', array('opening_balance_in_gold' => 0, 'gold_fine' => $account_row->gold_fine, 'opening_balance_in_silver' => 0, 'silver_fine' => $account_row->silver_fine, 'opening_balance_in_rupees' => 0, 'amount' => $account_row->amount), array('account_id' => $account_row->account_id));
//                        }
//                    }
//                    
//                }
                
            }
            
            if($data_deleted == '1'){
                $return['success'] = "Deleted";
            } else if($data_deleted == '2'){
                $return['empty'] = "Empty";
            } else {
                $return['error'] = "Error";
            }
            print json_encode($return);
            exit;
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function delete_selected_accounts_data(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            
            $return = array();
            $post_data = $this->input->post();
//            print_r($post_data); exit;
            $data_deleted = '0';
            if(!empty($post_data['delete_upto_selected'])){
                $delete_upto_selected = date('Y-m-d', strtotime($post_data['delete_upto_selected']));
                
                // Delete Item Stock Duplicate Data
                $select_duplicate_item_stock_sql = "SELECT COUNT(*) item_stock_row_count, `item_stock`.* FROM `item_stock` "
                        . "WHERE `category_id` = " . EXCHANGE_DEFAULT_CATEGORY_ID . " AND `item_id` = " . EXCHANGE_DEFAULT_ITEM_ID . " AND `grwt` != 0 AND `ntwt` != 0 "
                        . "GROUP BY `department_id`, `tunch`, abs(`grwt`), abs(`ntwt`) "
                        . "HAVING item_stock_row_count > 1";
                $select_result = $this->db->query($select_duplicate_item_stock_sql)->result_array();
                $item_stock_ids = array();
                if(!empty($select_result)){
                    foreach($select_result as $select_row){
                        if($select_row['item_stock_row_count'] == '2'){
                            $get_item_stock_id_sql = "SELECT `item_stock_id` FROM `item_stock` "
                                . "WHERE `department_id` = '" . $select_row['department_id'] . "' AND `category_id` = " . EXCHANGE_DEFAULT_CATEGORY_ID . " AND `item_id` = " . EXCHANGE_DEFAULT_ITEM_ID . " AND "
                                    . "`grwt` = '-" . $select_row['grwt'] . "' AND `ntwt` = '-" . $select_row['ntwt'] . "' AND `tunch` = '" . $select_row['tunch'] . "' ";
                            $get_item_stock_id_result = $this->db->query($get_item_stock_id_sql)->row();
                            if(!empty($get_item_stock_id_result)){
                                $item_stock_ids[] = $select_row['item_stock_id'];
                                $item_stock_ids[] = $get_item_stock_id_result->item_stock_id;
                            }
                        }
                    }
                    $item_stock_ids = implode(',', $item_stock_ids);
//                    print_r($item_stock_ids); exit;
                    $delete_duplicate_item_stock_sql = "DELETE FROM `item_stock` WHERE `item_stock_id` IN (" . $item_stock_ids . ")";
                    $this->db->query($delete_duplicate_item_stock_sql);
                }

                $without_customers_accounts_data = $this->get_without_customers_accounts_with_ids();
                $without_customers_account_ids = array_column($without_customers_accounts_data, 'account_id');
                
                $allow_all_accounts = $post_data['allow_all_accounts'];
                if($allow_all_accounts == '2'){
                    if(isset($post_data['account_id']) && !empty($post_data['account_id'])){
                        $only_customers_account_ids = $post_data['account_id'];
                    }
                } else {
                    $accounts_data = $this->get_only_customers_accounts_with_ids();
                    $only_customers_account_ids = array_column($accounts_data, 'account_id');
                }
                
                $account_ids = array();
                $account_ids = array_merge($without_customers_account_ids, $only_customers_account_ids);
                
                $delete_only_for_balance_0 = '0';
                if(isset($post_data['delete_only_for_balance_0']) && $post_data['delete_only_for_balance_0'] == '1'){
                    $delete_only_for_balance_0 = '1';
                    
                    $this->db->select("account_id");
                    $this->db->from("account");
                    $this->db->where("gold_fine", '0');
                    $this->db->where("silver_fine", '0');
                    $this->db->where("amount", '0');
                    $this->db->where_in("account_id", $account_ids);
                    $balance_0_account_query= $this->db->get();
                    if ($balance_0_account_query->num_rows() > 0) {
                        $account_ids = array_column($balance_0_account_query->result_array(), 'account_id');
                    } else {
                        $account_ids = array();
                    }
                }
//                print_r($account_ids); exit;
                
                $module_names_selected = $post_data['module_names_selected'];
                
                if(in_array('module_sale_purchase_selected', $module_names_selected)){
                    
                    $this->db->select("*");
                    $this->db->from("sell");
                    $this->db->where('sell_date <=', $delete_upto_selected);
                    $this->db->where('delivery_type', '1');
//                    $this->db->where('sell_date', $delete_upto_selected);
                    if(!empty($account_ids)){
                        $this->db->where_in("account_id", $account_ids);
                    } else {
                        $this->db->where("account_id", '-1');
                    }
                    $sell_query = $this->db->get();
                    $sell_results = $sell_query->result();
                    if (!empty($sell_results)) {
                        foreach ($sell_results as $sell_row) {
//                            print_r($sell_row);
//                            echo '<br>';
                            
                            // Check Journal details accounts in selected accounts
                            $is_in_account_array = '1';
                            
                            // Sell Payment Receipt Data
                            $this->db->select("*");
                            $this->db->from("payment_receipt");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $payment_receipt_query = $this->db->get();
                            $payment_receipts = $payment_receipt_query->result();
                            if (!empty($payment_receipts)) {
                                foreach ($payment_receipts as $payment_receipt_row) {
                                    if(!empty($payment_receipt_row->bank_id)){
                                        if(!empty($payment_receipt_row->bank_id) && in_array($payment_receipt_row->bank_id, $account_ids)){
                                        } else {
                                            $is_in_account_array = '0';
                                        }
                                    }
                                }
                            }
                            
                            // Sell Transfer Data
                            $this->db->select("*");
                            $this->db->from("transfer");
                            $this->db->where('sell_id', $sell_row->sell_id);
                            $transfer_query = $this->db->get();
                            $transfer_data = $transfer_query->result();
                            if (!empty($transfer_data)) {
                                foreach ($transfer_data as $transfer_row) {
                                    if(!empty($transfer_row->transfer_account_id) && in_array($transfer_row->transfer_account_id, $account_ids)){
                                    } else {
                                        $is_in_account_array = '0';
                                    }
                                }
                            }
                            
                            if($is_in_account_array == '1'){
                            
                                // Sell Line item Data
                                $this->db->select("*");
                                $this->db->from("sell_items");
                                $this->db->where('sell_id', $sell_row->sell_id);
                                $sell_items_query = $this->db->get();
                                $sell_items = $sell_items_query->result();
                                if (!empty($sell_items)) {
                                    foreach ($sell_items as $sell_item_row) {
    //                                    print_r($sell_item_row);
    //                                    echo '<br>';

                                        // If Type Sell then stock in minus
                                        if($sell_item_row->type == SELL_TYPE_SELL_ID){
                                            $sell_item_grwt = ZERO_VALUE - $sell_item_row->grwt;
                                            $sell_item_less = ZERO_VALUE - $sell_item_row->less;
                                            $sell_item_ntwt = ZERO_VALUE - $sell_item_row->net_wt;
                                            $sell_item_fine = ZERO_VALUE - $sell_item_row->fine;
                                        } else {
                                            $sell_item_grwt = $sell_item_row->grwt;
                                            $sell_item_less = $sell_item_row->less;
                                            $sell_item_ntwt = $sell_item_row->net_wt;
                                            $sell_item_fine = $sell_item_row->fine;
                                        }

                                        // Check and update Item Opening stock
                                        $exist_opening = array();
                                        $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $sell_item_row->item_id));
                                        if($stock_method == STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                            $stock_type = NULL;
                                            if($sell_item_row->type == SELL_TYPE_PURCHASE_ID){
                                                $stock_type = STOCK_TYPE_PURCHASE_ID;
                                            } else if($sell_item_row->type == SELL_TYPE_EXCHANGE_ID){
                                                $stock_type = STOCK_TYPE_EXCHANGE_ID;
                                            }
                                            $opening_stock_sql = "SELECT * FROM `opening_stock` ";
                                            $opening_stock_sql .= " WHERE `department_id` = '".$sell_row->process_id."' AND `category_id` = '".$sell_item_row->category_id."' AND `item_id` = '".$sell_item_row->item_id."' AND `tunch` = '".$sell_item_row->touch_id."' ";
                                            $opening_stock_sql .= " AND (`purchase_sell_item_id` = '". $sell_item_row->purchase_sell_item_id ."' OR `purchase_sell_item_id` = '". $sell_item_row->sell_item_id ."') AND `stock_type` IS NOT NULL";
                                            $exist_opening = $this->crud->getFromSQL($opening_stock_sql);
                                            if(!empty($exist_opening)){
                                                $exist_opening = $exist_opening[0];
                                                $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $sell_item_grwt, '3', '.', '');
                                                $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $sell_item_less, '3', '.', '');
                                                $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $sell_item_ntwt, '3', '.', '');
                                                $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $sell_item_fine, '3', '.', '');
                                                $update_opening_stock = array();
                                                $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                                $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                                $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                                $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                                $update_opening_stock['updated_at'] = $this->now_time;
                                                $update_opening_stock['updated_by'] = $this->logged_in_id;
                                                $this->crud->update('opening_stock', $update_opening_stock, array('opening_stock_id' => $exist_opening->opening_stock_id));
                                            } else {
                                                $insert_opening_stock = array();
                                                $insert_opening_stock['department_id'] = $sell_row->process_id;
                                                $insert_opening_stock['category_id'] = $sell_item_row->category_id;
                                                $insert_opening_stock['item_id'] = $sell_item_row->item_id;
                                                $insert_opening_stock['tunch'] = $sell_item_row->touch_id;
                                                $insert_opening_stock['grwt'] = number_format((float) $sell_item_grwt, '3', '.', '');
                                                $insert_opening_stock['less'] = number_format((float) $sell_item_less, '3', '.', '');
                                                $insert_opening_stock['ntwt'] = number_format((float) $sell_item_ntwt, '3', '.', '');
                                                $insert_opening_stock['fine'] = number_format((float) $sell_item_fine, '3', '.', '');
                                                if(isset($sell_item_row->purchase_sell_item_id) && !empty($sell_item_row->purchase_sell_item_id)){
                                                    $insert_opening_stock['purchase_sell_item_id'] = $sell_item_row->purchase_sell_item_id;
                                                    $insert_opening_stock['stock_type'] = $sell_item_row->stock_type;
                                                } else {
                                                    $insert_opening_stock['purchase_sell_item_id'] = $sell_item_row->sell_item_id;
                                                    $insert_opening_stock['stock_type'] = $stock_type;
                                                }
                                                $insert_opening_stock['created_at'] = $this->now_time;
                                                $insert_opening_stock['created_by'] = $this->logged_in_id;
                                                $insert_opening_stock['updated_at'] = $this->now_time;
                                                $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                                $this->crud->insert('opening_stock', $insert_opening_stock);
                                            }
                                        } else {
                                            $where_opening = array('department_id' => $sell_row->process_id, 'category_id' => $sell_item_row->category_id, 'item_id' => $sell_item_row->item_id, 'tunch' => $sell_item_row->touch_id);
                                            $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                            if(!empty($exist_opening)){
                                                $exist_opening = $exist_opening[0];
                                                $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $sell_item_grwt, '3', '.', '');
                                                $opening_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $sell_item_less, '3', '.', '');
                                                $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $sell_item_ntwt, '3', '.', '');
                                                $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $sell_item_fine, '3', '.', '');
                                                $update_opening_stock = array();
                                                $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                                $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                                $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                                $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                                $update_opening_stock['updated_at'] = $this->now_time;
                                                $update_opening_stock['updated_by'] = $this->logged_in_id;
                                                $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                            } else {
                                                $insert_opening_stock = array();
                                                $insert_opening_stock['department_id'] = $sell_row->process_id;
                                                $insert_opening_stock['category_id'] = $sell_item_row->category_id;
                                                $insert_opening_stock['item_id'] = $sell_item_row->item_id;
                                                $insert_opening_stock['tunch'] = $sell_item_row->touch_id;
                                                $insert_opening_stock['grwt'] = number_format((float) $sell_item_grwt, '3', '.', '');
                                                $insert_opening_stock['less'] = number_format((float) $sell_item_less, '3', '.', '');
                                                $insert_opening_stock['ntwt'] = number_format((float) $sell_item_ntwt, '3', '.', '');
                                                $insert_opening_stock['fine'] = number_format((float) $sell_item_fine, '3', '.', '');
                                                $insert_opening_stock['created_at'] = $this->now_time;
                                                $insert_opening_stock['created_by'] = $this->logged_in_id;
                                                $insert_opening_stock['updated_at'] = $this->now_time;
                                                $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                                $this->crud->insert('opening_stock', $insert_opening_stock);
                                            }
                                        }
                                        
                                        // Update Department Opening balance
                                        $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->process_id);
                                        if(!empty($department_data)){
                                            $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $sell_item_row->category_id));

                                            if($sell_item_row->type == SELL_TYPE_SELL_ID){
                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($department_data->gold_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                    }
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                    if($department_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($department_data->silver_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                    }
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                    if($department_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }
                                                }
                                            } else {
                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($department_data->gold_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                    }
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                    if($department_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($department_data->silver_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                    }
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                    if($department_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }
                                                }
                                            }
                                        }

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                        if(!empty($account_data)){
                                            $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $sell_item_row->category_id));

                                            if($sell_item_row->type == SELL_TYPE_SELL_ID){

                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($account_data->gold_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                    }
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                    if($account_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($account_data->silver_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                    }
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                    if($account_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }
                                                }


                                            } else {

                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($account_data->gold_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                    }
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                    if($account_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($account_data->silver_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                    }
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $sell_item_row->fine, '3', '.', '');
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                    if($account_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                }

                                            }
                                            
                                            // Sell Lineitem charges_amt Effect in Selected Customer and MF Loss Account
                                            if(isset($sell_item_row->charges_amt) && !empty($sell_item_row->charges_amt)){
                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $sell_item_row->charges_amt, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }
                                                // charges_amt Effect for c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_item_row->charges_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                }

                                                $mf_loss_data = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                                                if(!empty($mf_loss_data)){
                                                    if($mf_loss_data->rupees_ob_credit_debit == '1'){
                                                        $mf_loss_data->opening_balance_in_rupees = ZERO_VALUE - $mf_loss_data->opening_balance_in_rupees;
                                                    }
                                                    $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_item_row->charges_amt, '2', '.', '');
                                                    $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '');
                                                    if($mf_loss_data->opening_balance_in_rupees < 0){
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => abs($mf_loss_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_rupees' => $mf_loss_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                                    }
                                                }
                                            }
                                            
                                        }
                                    }
                                }
                                // Delete sell_items data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_items');
                                // Delete sell_items_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_items_log');
                                // Delete sell_less_ad_details data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_less_ad_details');
                                // Delete sell_less_ad_details_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_less_ad_details_log');
                                // Delete sell_item_charges_details data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_item_charges_details');

                                // Sell Payment Receipt Data
                                if (!empty($payment_receipts)) {
                                    foreach ($payment_receipts as $payment_receipt_row) {
    //                                    print_r($payment_receipt_row);
    //                                    echo '<br>';

                                        // If Type receipt then amount in minus
                                        if($payment_receipt_row->payment_receipt == '2'){
                                            $payment_receipt_amount = ZERO_VALUE - $payment_receipt_row->amount;
                                            $payment_receipt_c_amt = ZERO_VALUE - $payment_receipt_row->c_amt;
                                            $payment_receipt_r_amt = ZERO_VALUE - $payment_receipt_row->r_amt;
                                        } else {
                                            $payment_receipt_amount = $payment_receipt_row->amount;
                                            $payment_receipt_c_amt = $payment_receipt_row->c_amt;
                                            $payment_receipt_r_amt = $payment_receipt_row->r_amt;
                                        }

                                        if($payment_receipt_row->cash_cheque == '1'){  // Cash
                                            // Update Department Opening balance
                                            $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->department_id);
                                            if(!empty($department_data)){

                                                if($department_data->rupees_ob_credit_debit == '1'){
                                                    $department_data->opening_balance_in_rupees = ZERO_VALUE - $department_data->opening_balance_in_rupees;
                                                }
                                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '');
                                                if($department_data->opening_balance_in_rupees < 0){
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($department_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->department_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => $department_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->department_id));
                                                }

                                            }
                                        }

                                        if($payment_receipt_row->cash_cheque == '2'){  // Cheque
                                            // Update Bank Opening balance
                                            $bank_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->bank_id);
                                            if(!empty($bank_data)){

                                                if($bank_data->rupees_ob_credit_debit == '1'){
                                                    $bank_data->opening_balance_in_rupees = ZERO_VALUE - $bank_data->opening_balance_in_rupees;
                                                }
                                                $bank_data->opening_balance_in_rupees = number_format((float) $bank_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                                $bank_data->opening_balance_in_rupees = number_format((float) $bank_data->opening_balance_in_rupees, '2', '.', '');
                                                if($bank_data->opening_balance_in_rupees < 0){
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($bank_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->bank_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_rupees' => $bank_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->bank_id));
                                                }

                                            }
                                        }

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $payment_receipt_row->account_id);
                                        if(!empty($account_data)){

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $payment_receipt_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                            }

                                            // amount Effect for c_amt
                                            if($payment_receipt_row->cash_cheque == '1'){  // Cash
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $payment_receipt_c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                                }
                                            }

                                            // amount Effect for r_amt
                                            if($payment_receipt_row->cash_cheque == '2'){  // Cheque
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $payment_receipt_r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $this->crud->update('account', array('opening_balance_in_r_amount' => abs($account_data->opening_balance_in_r_amount), 'r_amount_ob_credit_debit' => '1'), array('account_id' => $payment_receipt_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_r_amount' => $account_data->opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => '2'), array('account_id' => $payment_receipt_row->account_id));
                                                }
                                            }
                                        }
                                    }
                                }
                                // Delete payment_receipt data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('payment_receipt');
                                // Delete payment_receipt_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('payment_receipt_log');

                                // Sell Metal Payment Receipt Data
                                $this->db->select("*");
                                $this->db->from("metal_payment_receipt");
                                $this->db->where('sell_id', $sell_row->sell_id);
                                $metal_payment_receipt_query = $this->db->get();
                                $metal_payment_receipts = $metal_payment_receipt_query->result();
                                if (!empty($metal_payment_receipts)) {
                                    foreach ($metal_payment_receipts as $metal_payment_receipt_row) {
    //                                    print_r($metal_payment_receipt_row);
    //                                    echo '<br>';

                                        // If Type Payment then stock in minus
                                        if($metal_payment_receipt_row->metal_payment_receipt == '1'){
                                            $metal_payment_receipt_grwt = ZERO_VALUE - $metal_payment_receipt_row->metal_grwt;
                                            $metal_payment_receipt_ntwt = ZERO_VALUE - $metal_payment_receipt_row->metal_ntwt;
                                            $metal_payment_receipt_fine = ZERO_VALUE - $metal_payment_receipt_row->metal_fine;
                                        } else {
                                            $metal_payment_receipt_grwt = $metal_payment_receipt_row->metal_grwt;
                                            $metal_payment_receipt_ntwt = $metal_payment_receipt_row->metal_ntwt;
                                            $metal_payment_receipt_fine = $metal_payment_receipt_row->metal_fine;
                                        }

                                        // Check and update Item Opening stock
                                        $exist_opening = array();
                                        $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $metal_payment_receipt_row->metal_item_id));
                                        if($stock_method != STOCK_METHOD_ITEM_WISE){ // Item Wise Item Na Data delete thai tyare opening stock ma New Record Insert thse
                                            $where_opening = array('department_id' => $sell_row->process_id, 'category_id' => $metal_payment_receipt_row->metal_category_id, 'item_id' => $metal_payment_receipt_row->metal_item_id, 'tunch' => $metal_payment_receipt_row->metal_tunch);
                                            $exist_opening = $this->crud->get_row_by_id('opening_stock', $where_opening);
                                        }
                                        if(!empty($exist_opening)){
                                            $exist_opening = $exist_opening[0];
                                            $opening_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $metal_payment_receipt_grwt, '3', '.', '');
                                            $opening_stock_less = '0';
                                            $opening_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $metal_payment_receipt_ntwt, '3', '.', '');
                                            $opening_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $metal_payment_receipt_fine, '3', '.', '');
                                            $update_opening_stock = array();
                                            $update_opening_stock['grwt'] = number_format((float) $opening_stock_grwt, '3', '.', '');
                                            $update_opening_stock['less'] = number_format((float) $opening_stock_less, '3', '.', '');
                                            $update_opening_stock['ntwt'] = number_format((float) $opening_stock_ntwt, '3', '.', '');
                                            $update_opening_stock['fine'] = number_format((float) $opening_stock_fine, '3', '.', '');
                                            $update_opening_stock['updated_at'] = $this->now_time;
                                            $update_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->update('opening_stock', $update_opening_stock, $where_opening);
                                        } else {
                                            $insert_opening_stock = array();
                                            $insert_opening_stock['department_id'] = $sell_row->process_id;
                                            $insert_opening_stock['category_id'] = $metal_payment_receipt_row->metal_category_id;
                                            $insert_opening_stock['item_id'] = $metal_payment_receipt_row->metal_item_id;
                                            $insert_opening_stock['tunch'] = $metal_payment_receipt_row->metal_tunch;
                                            $insert_opening_stock['grwt'] = number_format((float) $metal_payment_receipt_grwt, '3', '.', '');
                                            $insert_opening_stock['less'] = '0';
                                            $insert_opening_stock['ntwt'] = number_format((float) $metal_payment_receipt_ntwt, '3', '.', '');
                                            $insert_opening_stock['fine'] = number_format((float) $metal_payment_receipt_fine, '3', '.', '');
                                            $insert_opening_stock['created_at'] = $this->now_time;
                                            $insert_opening_stock['created_by'] = $this->logged_in_id;
                                            $insert_opening_stock['updated_at'] = $this->now_time;
                                            $insert_opening_stock['updated_by'] = $this->logged_in_id;
                                            $this->crud->insert('opening_stock', $insert_opening_stock);
                                        }

                                        // Update Department Opening balance
                                        $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->process_id);
                                        if(!empty($department_data)){
                                            $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $metal_payment_receipt_row->metal_category_id));

                                            if($metal_payment_receipt_row->metal_payment_receipt == '1'){

                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($department_data->gold_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                    }
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                    if($department_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($department_data->silver_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                    }
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                    if($department_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }
                                                }
                                            } else {
                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($department_data->gold_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_gold = ZERO_VALUE - $department_data->opening_balance_in_gold;
                                                    }
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $department_data->opening_balance_in_gold = number_format((float) $department_data->opening_balance_in_gold, '3', '.', '');
                                                    if($department_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($department_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $department_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($department_data->silver_ob_credit_debit == '1'){
                                                        $department_data->opening_balance_in_silver = ZERO_VALUE - $department_data->opening_balance_in_silver;
                                                    }
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $department_data->opening_balance_in_silver = number_format((float) $department_data->opening_balance_in_silver, '3', '.', '');
                                                    if($department_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($department_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->process_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $department_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->process_id));
                                                    }
                                                }
                                            }
                                        }

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                        if(!empty($account_data)){
                                            $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $metal_payment_receipt_row->metal_category_id));

                                            if($metal_payment_receipt_row->metal_payment_receipt == '1'){

                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($account_data->gold_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                    }
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                    if($account_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($account_data->silver_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                    }
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                    if($account_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }
                                                }

                                            } else {

                                                if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                                    if($account_data->gold_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                    }
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                    if($account_data->opening_balance_in_gold < 0){
                                                        $this->crud->update('account', array('opening_balance_in_gold' => abs($account_data->opening_balance_in_gold), 'gold_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_gold' => $account_data->opening_balance_in_gold, 'gold_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                                    if($account_data->silver_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                    }
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $metal_payment_receipt_row->metal_fine, '3', '.', '');
                                                    $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                    if($account_data->opening_balance_in_silver < 0){
                                                        $this->crud->update('account', array('opening_balance_in_silver' => abs($account_data->opening_balance_in_silver), 'silver_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                                    } else {
                                                        $this->crud->update('account', array('opening_balance_in_silver' => $account_data->opening_balance_in_silver, 'silver_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                                    }

                                                }

                                            }
                                        }
                                    }
                                }
                                // Delete metal_payment_receipt data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('metal_payment_receipt');
                                // Delete metal_payment_receipt_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('metal_payment_receipt_log');

                                // Sell Gold Bhav Data
                                $this->db->select("*");
                                $this->db->from("gold_bhav");
                                $this->db->where('sell_id', $sell_row->sell_id);
                                $gold_bhav_query = $this->db->get();
                                $gold_bhav_data = $gold_bhav_query->result();
                                if (!empty($gold_bhav_data)) {
                                    foreach ($gold_bhav_data as $gold_bhav_row) {
    //                                    print_r($gold_bhav_row);
    //                                    echo '<br>';

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                        if(!empty($account_data)){
                                            if($gold_bhav_row->gold_sale_purchase == '1'){ // Sell

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $gold_bhav_row->gold_weight, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $gold_bhav_row->gold_value, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                if($gold_bhav_row->gold_cr_effect == '2'){ // r_amt
                                                    if($account_data->r_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                    }
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $gold_bhav_row->r_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_r_amount < 0){
                                                        $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                        $r_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                        $r_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                                } else { // c_amt
                                                    if($account_data->c_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                    }
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $gold_bhav_row->c_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_c_amount < 0){
                                                        $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                        $c_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                        $c_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                        'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );


                                            } else {  // Purchase

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $gold_bhav_row->gold_weight, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $gold_bhav_row->gold_value, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                if($gold_bhav_row->gold_cr_effect == '2'){ // r_amt
                                                    if($account_data->r_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                    }
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $gold_bhav_row->r_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_r_amount < 0){
                                                        $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                        $r_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                        $r_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                                } else { // c_amt
                                                    if($account_data->c_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                    }
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $gold_bhav_row->c_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_c_amount < 0){
                                                        $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                        $c_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                        $c_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                        'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );

                                            }
                                        }
                                    }
                                }
                                // Delete gold_bhav data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('gold_bhav');
                                // Delete gold_bhav_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('gold_bhav_log');

                                // Sell Silver Bhav Data
                                $this->db->select("*");
                                $this->db->from("silver_bhav");
                                $this->db->where('sell_id', $sell_row->sell_id);
                                $silver_bhav_query = $this->db->get();
                                $silver_bhav_data = $silver_bhav_query->result();
                                if (!empty($silver_bhav_data)) {
                                    foreach ($silver_bhav_data as $silver_bhav_row) {
    //                                    print_r($silver_bhav_row);
    //                                    echo '<br>';

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                        if(!empty($account_data)){
                                            if($silver_bhav_row->silver_sale_purchase == '1'){ // Sell

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $silver_bhav_row->silver_weight, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $silver_bhav_row->silver_value, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                if($silver_bhav_row->silver_cr_effect == '2'){ // r_amt
                                                    if($account_data->r_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                    }
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $silver_bhav_row->r_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_r_amount < 0){
                                                        $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                        $r_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                        $r_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                                } else { // c_amt
                                                    if($account_data->c_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                    }
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $silver_bhav_row->c_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_c_amount < 0){
                                                        $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                        $c_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                        $c_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                        'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );

                                            } else {  // Purchase

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $silver_bhav_row->silver_weight, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $silver_bhav_row->silver_value, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                if($silver_bhav_row->silver_cr_effect == '2'){ // r_amt
                                                    if($account_data->r_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                    }
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $silver_bhav_row->r_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_r_amount < 0){
                                                        $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                        $r_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                        $r_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = $account_data->c_amount_ob_credit_debit;
                                                } else { // c_amt
                                                    if($account_data->c_amount_ob_credit_debit == '1'){
                                                        $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                    }
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $silver_bhav_row->c_amt, '2', '.', '');
                                                    $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                    if($account_data->opening_balance_in_c_amount < 0){
                                                        $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                        $c_amount_ob_credit_debit = '1';
                                                    } else {
                                                        $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                        $c_amount_ob_credit_debit = '2';
                                                    }
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = $account_data->r_amount_ob_credit_debit;
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                        'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );

                                            }
                                        }
                                    }
                                }
                                // Delete silver_bhav data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('silver_bhav');
                                // Delete silver_bhav_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('silver_bhav_log');

                                // Sell Transfer Data
                                if (!empty($transfer_data)) {
                                    foreach ($transfer_data as $transfer_row) {
    //                                    print_r($transfer_row);
    //                                    echo '<br>';

                                        // Update From Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                        if(!empty($account_data)){
                                            if($transfer_row->naam_jama == '1'){ // Naam

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                // transfer_amount Effect for c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $transfer_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );

                                            } else {  // Jama

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                // transfer_amount Effect for c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $transfer_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $sell_row->account_id)
                                                );

                                            }
                                        }

                                        // Update Transfer To Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $transfer_row->transfer_account_id);
                                        if(!empty($account_data)){
                                            if($transfer_row->naam_jama == '1'){ // Naam

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') + number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') + number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                // transfer_amount Effect for c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $transfer_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $transfer_row->transfer_account_id)
                                                );

                                            } else {  // Jama

                                                if($account_data->gold_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_gold = ZERO_VALUE - $account_data->opening_balance_in_gold;
                                                }
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '') - number_format((float) $transfer_row->transfer_gold, '3', '.', '');
                                                $account_data->opening_balance_in_gold = number_format((float) $account_data->opening_balance_in_gold, '3', '.', '');
                                                if($account_data->opening_balance_in_gold < 0){
                                                    $opening_balance_in_gold = abs($account_data->opening_balance_in_gold);
                                                    $gold_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_gold = $account_data->opening_balance_in_gold;
                                                    $gold_ob_credit_debit = '2';
                                                }

                                                if($account_data->silver_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_silver = ZERO_VALUE - $account_data->opening_balance_in_silver;
                                                }
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '') - number_format((float) $transfer_row->transfer_silver, '3', '.', '');
                                                $account_data->opening_balance_in_silver = number_format((float) $account_data->opening_balance_in_silver, '3', '.', '');
                                                if($account_data->opening_balance_in_silver < 0){
                                                    $opening_balance_in_silver = abs($account_data->opening_balance_in_silver);
                                                    $silver_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_silver = $account_data->opening_balance_in_silver;
                                                    $silver_ob_credit_debit = '2';
                                                }

                                                if($account_data->rupees_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                                }
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $transfer_row->transfer_amount, '2', '.', '');
                                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                                if($account_data->opening_balance_in_rupees < 0){
                                                    $opening_balance_in_rupees = abs($account_data->opening_balance_in_rupees);
                                                    $rupees_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_rupees = $account_data->opening_balance_in_rupees;
                                                    $rupees_ob_credit_debit = '2';
                                                }
                                                // transfer_amount Effect for c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $transfer_row->c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }

                                                $this->crud->update('account', 
                                                    array(
                                                        'opening_balance_in_gold' => $opening_balance_in_gold, 'gold_ob_credit_debit' => $gold_ob_credit_debit, 
                                                        'opening_balance_in_silver' => $opening_balance_in_silver, 'silver_ob_credit_debit' => $silver_ob_credit_debit, 
                                                        'opening_balance_in_rupees' => $opening_balance_in_rupees, 'rupees_ob_credit_debit' => $rupees_ob_credit_debit,
                                                        'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit
                                                    ), 
                                                    array('account_id' => $transfer_row->transfer_account_id)
                                                );

                                            }
                                        }

                                    }
                                }
                                // Delete transfer data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('transfer');
                                // Delete transfer_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('transfer_log');

                                // Sell Ad Charges Data
                                $this->db->select("sac.*, s.account_id");
                                $this->db->from("sell_ad_charges sac");
                                $this->db->join('sell s', 's.sell_id = sac.sell_id', 'left');
                                $this->db->where('sac.sell_id', $sell_row->sell_id);
                                $sell_ad_charges_query = $this->db->get();
                                $sell_ad_charges = $sell_ad_charges_query->result();
                                if (!empty($sell_ad_charges)) {
                                    foreach ($sell_ad_charges as $sell_ad_charges_row) {
//                                        print_r($sell_ad_charges_row);
//                                        echo '<br>';

                                        // Update MF Loss Opening balance
                                        $mf_loss_data = $this->crud->get_data_row_by_id('account', 'account_id', MF_LOSS_EXPENSE_ACCOUNT_ID);
                                        if(!empty($mf_loss_data)){

                                            if($mf_loss_data->rupees_ob_credit_debit == '1'){
                                                $mf_loss_data->opening_balance_in_rupees = ZERO_VALUE - $mf_loss_data->opening_balance_in_rupees;
                                            }
                                            $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_ad_charges_row->ad_amount, '2', '.', '');
                                            $mf_loss_data->opening_balance_in_rupees = number_format((float) $mf_loss_data->opening_balance_in_rupees, '2', '.', '');
                                            if($mf_loss_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($mf_loss_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $mf_loss_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                                            }

                                        }

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_ad_charges_row->account_id);
                                        if(!empty($account_data)){

                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $sell_ad_charges_row->ad_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_ad_charges_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_ad_charges_row->account_id));
                                            }
                                            // ad_amount Effect for c_amt
                                            if($account_data->c_amount_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                            }
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_ad_charges_row->c_amt, '2', '.', '');
                                            $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                            if($account_data->opening_balance_in_c_amount < 0){
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_ad_charges_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_ad_charges_row->account_id));
                                            }
                                        }
                                    }
                                }
                                // Delete sell_ad_charges data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_ad_charges');
                                // Delete sell_ad_charges_log data
                                $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_ad_charges_log');

                                // Sell Adjust CR Data
                                $this->db->select("a_cr.*, s.account_id");
                                $this->db->from("sell_adjust_cr a_cr");
                                $this->db->join('sell s', 's.sell_id = a_cr.sell_id', 'left');
                                $this->db->where('a_cr.sell_id', $sell_row->sell_id);
                                $sell_adjust_cr_query = $this->db->get();
                                $sell_adjust_cr = $sell_adjust_cr_query->result();
                                if (!empty($sell_adjust_cr)) {
                                    foreach ($sell_adjust_cr as $sell_adjust_cr_row) {
    //                                    print_r($sell_adjust_cr_row);
    //                                    echo '<br>';

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_adjust_cr_row->account_id);
                                        if(!empty($account_data)){

                                            if($sell_adjust_cr_row->adjust_to == '2'){ // c_amt to r_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                            } else { // r_amt to c_amt
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $opening_balance_in_c_amount = abs($account_data->opening_balance_in_c_amount);
                                                    $c_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_c_amount = $account_data->opening_balance_in_c_amount;
                                                    $c_amount_ob_credit_debit = '2';
                                                }
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') - number_format((float) $sell_adjust_cr_row->adjust_cr_amount, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $opening_balance_in_r_amount = abs($account_data->opening_balance_in_r_amount);
                                                    $r_amount_ob_credit_debit = '1';
                                                } else {
                                                    $opening_balance_in_r_amount = $account_data->opening_balance_in_r_amount;
                                                    $r_amount_ob_credit_debit = '2';
                                                }
                                            }

                                            $this->crud->update('account', 
                                                array(
                                                    'opening_balance_in_c_amount' => $opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => $c_amount_ob_credit_debit,
                                                    'opening_balance_in_r_amount' => $opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => $r_amount_ob_credit_debit
                                                ), 
                                                array('account_id' => $sell_adjust_cr_row->account_id)
                                            );
                                        }
                                    }
                                }
                                // Delete sell_adjust_cr data
                                $this->db->where('sell_id', $sell_row->sell_id)->delete('sell_adjust_cr');

                                // discount_amount Effect
                                if(isset($sell_row->discount_amount) && !empty($sell_row->discount_amount)){
                                    $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $sell_row->account_id);
                                    if(!empty($account_data)){
                                        if($account_data->rupees_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                        }
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $sell_row->discount_amount, '2', '.', '');
                                        $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                        if($account_data->opening_balance_in_rupees < 0){
                                            $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                        }

                                        // discount_amount Effect for c_amt
                                        if($account_data->c_amount_ob_credit_debit == '1'){
                                            $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                        }
                                        $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') - number_format((float) $sell_row->discount_amount, '2', '.', '');
                                        $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                        if($account_data->opening_balance_in_c_amount < 0){
                                            $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $sell_row->account_id));
                                        } else {
                                            $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $sell_row->account_id));
                                        }
                                    }
                                }

                                // Delete sell data
                                $result = $this->db->where('sell_id', $sell_row->sell_id)->delete('sell');
                                if($result){
                                    $data_deleted = '1';
                                    // Delete sell_log data
                                    $this->gurulog_db->where('sell_id', $sell_row->sell_id)->delete('sell_log');
                                }
                                
                            } else {
                                if($data_deleted != '1'){
                                    $data_deleted = '2';
                                }
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                }
                
                if(in_array('module_cashbook_selected', $module_names_selected)){
                    
                    $this->db->select("*");
                    $this->db->from("payment_receipt");
                    $this->db->where('transaction_date <=', $delete_upto_selected);
//                    $this->db->where('transaction_date', $delete_upto_selected);
                    $this->db->where('sell_id IS NULL', null, false);
                    $this->db->where('other_id IS NULL', null, false);
                    if(!empty($account_ids)){
                        $this->db->where_in("account_id", $account_ids);
                    } else {
                        $this->db->where("account_id", '-1');
                    }
                    $cashbook_query = $this->db->get();
                    $cashbook_results = $cashbook_query->result();
                    if (!empty($cashbook_results)) {
                        foreach ($cashbook_results as $cashbook_row) {
//                            print_r($cashbook_row);
//                            echo '<br>';
                            
                            // If Type receipt then amount in minus
                            if($cashbook_row->payment_receipt == '2'){
                                $payment_receipt_amount = ZERO_VALUE - $cashbook_row->amount;
                                $payment_receipt_c_amt = ZERO_VALUE - $cashbook_row->c_amt;
                                $payment_receipt_r_amt = ZERO_VALUE - $cashbook_row->r_amt;
                            } else {
                                $payment_receipt_amount = $cashbook_row->amount;
                                $payment_receipt_c_amt = $cashbook_row->c_amt;
                                $payment_receipt_r_amt = $cashbook_row->r_amt;
                            }

                            // Update Department Opening balance
                            $department_data = $this->crud->get_data_row_by_id('account', 'account_id', $cashbook_row->department_id);
                            if(!empty($department_data)){

                                if($department_data->rupees_ob_credit_debit == '1'){
                                    $department_data->opening_balance_in_rupees = ZERO_VALUE - $department_data->opening_balance_in_rupees;
                                }
                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '') - number_format((float) $payment_receipt_amount, '2', '.', '');
                                $department_data->opening_balance_in_rupees = number_format((float) $department_data->opening_balance_in_rupees, '2', '.', '');
                                if($department_data->opening_balance_in_rupees < 0){
                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($department_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->department_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_rupees' => $department_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->department_id));
                                }

                            }
                            
                            // Update Account Opening balance
                            $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $cashbook_row->account_id);
                            if(!empty($account_data)){

                                if($account_data->rupees_ob_credit_debit == '1'){
                                    $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                }
                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $payment_receipt_amount, '2', '.', '');
                                $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                if($account_data->opening_balance_in_rupees < 0){
                                    $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->account_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->account_id));
                                }

                                // amount Effect for c_amt
                                if($account_data->c_amount_ob_credit_debit == '1'){
                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                }
                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $payment_receipt_c_amt, '2', '.', '');
                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                if($account_data->opening_balance_in_c_amount < 0){
                                    $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $cashbook_row->account_id));
                                } else {
                                    $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $cashbook_row->account_id));
                                }

                            }
                            // Delete payment_receipt data
                            $result = $this->db->where('pay_rec_id', $cashbook_row->pay_rec_id)->delete('payment_receipt');
                            if($result){
                                $data_deleted = '1';
                                // Delete payment_receipt_log data
                                $this->gurulog_db->where('pay_rec_id', $cashbook_row->pay_rec_id)->delete('payment_receipt_log');
                            }
                        }
                        
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                }
                
                if(in_array('module_journal_selected', $module_names_selected)){
                    
                    $this->db->select("*");
                    $this->db->from("journal");
                    $this->db->where('journal_date <=', $delete_upto_selected);
//                    $this->db->where('journal_date', $delete_upto_selected);
                    $this->db->where('relation_id IS NULL', null, false);
                    $journal_query = $this->db->get();
                    $journal_results = $journal_query->result();
                    if (!empty($journal_results)) {
                        foreach ($journal_results as $journal_row) {
//                            print_r($journal_row);
//                            echo '<br>';
                            
                            // Journal Lineitem Data
                            $this->db->select("*");
                            $this->db->from("journal_details");
                            $this->db->where('journal_id', $journal_row->journal_id);
                            $journal_details_query = $this->db->get();
                            $journal_details = $journal_details_query->result();
                            if (!empty($journal_details)) {
                                
                                // Check Journal details accounts in selected accounts
                                $is_in_account_array = '1';
                                foreach ($journal_details as $journal_detail_row) {
                                    if(in_array($journal_detail_row->account_id, $account_ids)){
                                    } else {
                                        $is_in_account_array = '0';
                                    }
                                }
                                
                                if($is_in_account_array == '1'){
                                    foreach ($journal_details as $journal_detail_row) {
//                                        print_r($journal_detail_row);
//                                        echo '<br>';

                                        // If Type Naam/Jama then amount in plus/minus
                                        if($journal_detail_row->type == '1'){ // Naam
                                            $journal_amount = $journal_detail_row->amount;
                                            $journal_c_amt = $journal_detail_row->c_amt;
                                            $journal_r_amt = $journal_detail_row->r_amt;
                                        } else { // Jama
                                            $journal_amount = ZERO_VALUE - $journal_detail_row->amount;
                                            $journal_c_amt = $journal_detail_row->c_amt;
                                            $journal_r_amt = $journal_detail_row->r_amt;
                                        }

                                        // Update Account Opening balance
                                        $account_data = $this->crud->get_data_row_by_id('account', 'account_id', $journal_detail_row->account_id);
                                        if(!empty($account_data)){
                                            if($account_data->rupees_ob_credit_debit == '1'){
                                                $account_data->opening_balance_in_rupees = ZERO_VALUE - $account_data->opening_balance_in_rupees;
                                            }
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '') + number_format((float) $journal_amount, '2', '.', '');
                                            $account_data->opening_balance_in_rupees = number_format((float) $account_data->opening_balance_in_rupees, '2', '.', '');
                                            if($account_data->opening_balance_in_rupees < 0){
                                                $this->crud->update('account', array('opening_balance_in_rupees' => abs($account_data->opening_balance_in_rupees), 'rupees_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                            } else {
                                                $this->crud->update('account', array('opening_balance_in_rupees' => $account_data->opening_balance_in_rupees, 'rupees_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                            }

                                            // amount Effect for c_amt
                                            if(!empty($journal_c_amt) && $journal_c_amt != '0'){
                                                if($account_data->c_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_c_amount = ZERO_VALUE - $account_data->opening_balance_in_c_amount;
                                                }
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '') + number_format((float) $journal_c_amt, '2', '.', '');
                                                $account_data->opening_balance_in_c_amount = number_format((float) $account_data->opening_balance_in_c_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_c_amount < 0){
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => abs($account_data->opening_balance_in_c_amount), 'c_amount_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_c_amount' => $account_data->opening_balance_in_c_amount, 'c_amount_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                                }
                                            }

                                            // amount Effect for r_amt
                                            if(!empty($journal_r_amt) && $journal_r_amt != '0'){
                                                if($account_data->r_amount_ob_credit_debit == '1'){
                                                    $account_data->opening_balance_in_r_amount = ZERO_VALUE - $account_data->opening_balance_in_r_amount;
                                                }
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '') + number_format((float) $journal_r_amt, '2', '.', '');
                                                $account_data->opening_balance_in_r_amount = number_format((float) $account_data->opening_balance_in_r_amount, '2', '.', '');
                                                if($account_data->opening_balance_in_r_amount < 0){
                                                    $this->crud->update('account', array('opening_balance_in_r_amount' => abs($account_data->opening_balance_in_r_amount), 'r_amount_ob_credit_debit' => '1'), array('account_id' => $journal_detail_row->account_id));
                                                } else {
                                                    $this->crud->update('account', array('opening_balance_in_r_amount' => $account_data->opening_balance_in_r_amount, 'r_amount_ob_credit_debit' => '2'), array('account_id' => $journal_detail_row->account_id));
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Delete journal_details data
                                    $this->db->where('journal_id', $journal_row->journal_id)->delete('journal_details');

                                    // Delete journal data
                                    $result = $this->db->where('journal_id', $journal_row->journal_id)->delete('journal');
                                    if($result){
                                        $data_deleted = '1';
                                        // Delete journal_details_log data
                                        $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_details_log');
                                        // Delete journal_log data
                                        $this->gurulog_db->where('journal_id', $journal_row->journal_id)->delete('journal_log');
                                    }
                                } else {
                                    if($data_deleted != '1'){
                                        $data_deleted = '2';
                                    }
                                }
                            }
                            
                        }
                    } else {
                        if($data_deleted != '1'){
                            $data_deleted = '2';
                        }
                    }
                    
                }
                
            }
            
            if($data_deleted == '1'){
                $return['success'] = "Deleted";
            } else if($data_deleted == '2'){
                $return['empty'] = "Empty";
            } else {
                $return['error'] = "Error";
            }
            print json_encode($return);
            exit;
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
}