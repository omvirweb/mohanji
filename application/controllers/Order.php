<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

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

    function add($order_id = '') {
        $order_id = $order_id;
        $page_label = "Order";
        $page_shortcut = "";
        $list_page_url = base_url("order/order_list");
        $data = array();
        $data['page_label'] = $page_label;
        $data['page_shortcut'] = $page_shortcut;
        $data['list_page_url'] = $list_page_url;
        
        // Get Item Data : Start //
        $data['items_for_select'] = array();
        if(GET_ALL_DATA_IN_ITEM_SELECTION_IN_SELL_PURCHASE == '1') { 
            $select2_data = array();
            $where = "item_id IS NOT NULL ";
            $category_arr = $this->crud->getFromSQL('SELECT `category_id` FROM `category` WHERE `category_group_id` = "' . CATEGORY_GROUP_OTHER_ID . '"');
            foreach ($category_arr as $category){
                $category_ids[] = $category->category_id;
                $where .= " AND category_id !=".$category->category_id;
            }
            $this->db->select("item_id,item_name,display_item_in");
            $this->db->from("item_master");
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->order_by("item_name");
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $select2_data[] = array(
                        'item_id' => $row->item_id,
                        'item_name' => $row->item_name,
                    );
                }
            }
            $data['items_for_select'] = $select2_data;
        }
        // Get Item Data : End //
        
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
        
        if(!empty($order_id)){
            //----------------- Order Data -------------------
            if($this->applib->have_access_role(ORDER_MODULE_ID,"edit")) {
                $order_data = $this->crud->get_row_by_id('ordersell', array('order_id' => $order_id));
                $order_data = $order_data[0];
                $order_data->total_gold_fine = (!empty($order_data->total_gold_fine)) ? $order_data->total_gold_fine : 0;
                $order_data->total_silver_fine = (!empty($order_data->total_silver_fine)) ? $order_data->total_silver_fine : 0;
                $order_data->total_amount = (!empty($order_data->total_amount)) ? $order_data->total_amount : 0;
                if(PACKAGE_FOR == 'manek') {
                    $order_data->discount_amount = (!empty($order_data->discount_amount)) ? $order_data->discount_amount : 0;
                    $order_data->total_amount = $order_data->total_amount - $order_data->discount_amount;
                }
                $order_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$order_data->created_by));
                if($order_data->created_by != $order_data->updated_by){
                    $order_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $order_data->updated_by));
                }else{
                    $order_data->updated_by_name = $order_data ->created_by_name;
                }
                $data['order_data'] = $order_data;

                //----------------- Order Itemms -------------------
                $order_data_items = $this->crud->get_row_by_id('order_items', array('order_id' => $order_id));
                if(!empty($order_data_items)){
                    foreach($order_data_items as $lot_item){
                        $order_items = new \stdClass();
                        $order_items->order_item_delete = 'allow';
                        $order_items->type = $lot_item->type;
                        $type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $lot_item->type));
                        $order_items->type_name = $type_name[0];
                        $order_items->tunch_textbox = (isset($lot_item->tunch_textbox) && $lot_item->tunch_textbox == '1') ? '1' : '0';
                        $order_items->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                        $order_items->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lot_item->category_id));
                        $order_items->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                        $order_items->grwt = $lot_item->grwt;
                        $order_items->less = $lot_item->less;
                        $order_items->net_wt = $lot_item->net_wt;
                        $order_items->touch_id = $lot_item->touch_id;
                        $order_items->wstg = $lot_item->wstg;
                        $order_items->fine = $lot_item->fine;
                        $order_items->category_id = $lot_item->category_id;
                        $order_items->item_id = $lot_item->item_id;
                        $order_items->li_narration = (isset($lot_item->li_narration) && !empty($lot_item->li_narration)) ? $lot_item->li_narration : NULL;
                        $order_items->gold_silver_rate = (isset($lot_item->gold_silver_rate) && !empty($lot_item->gold_silver_rate)) ? $lot_item->gold_silver_rate : 0;
                        $order_items->order_item_id  = $lot_item->order_item_id ;
                        $order_items->item_stock_rfid_id = (isset($lot_item->item_stock_rfid_id) && !empty($lot_item->item_stock_rfid_id)) ? $lot_item->item_stock_rfid_id : NULL;
                        $order_items->rfid_number = (isset($lot_item->rfid_number) && !empty($lot_item->rfid_number)) ? $lot_item->rfid_number : NULL;
                        $order_items->charges_amt = (isset($lot_item->charges_amt) && !empty($lot_item->charges_amt)) ? $lot_item->charges_amt : 0;
                        $order_items->spi_pcs = (isset($lot_item->spi_pcs) && !empty($lot_item->spi_pcs)) ? $lot_item->spi_pcs : 0;
                        $order_items->spi_rate = (isset($lot_item->spi_rate) && !empty($lot_item->spi_rate)) ? $lot_item->spi_rate : 0;
                        $order_items->amount = (isset($lot_item->amount) && !empty($lot_item->amount)) ? $lot_item->amount : 0;
                        $order_items->image = $lot_item->image;
                        $order_lineitems[] = json_encode($order_items);
                    }
                    $data['order_item'] = implode(',', $order_lineitems);
                }
                set_page('order/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            //----------------- Data -------------------
            if($this->applib->have_access_role(ORDER_MODULE_ID,"add")) {
                set_page('order/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_order(){
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = array();

        if (isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])) {
            $line_items_data = json_decode($post_data['line_items_data']);
        }

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	    }

        if (isset($post_data['order_id']) && !empty($post_data['order_id'])) {
            $old_order_item_id_arr = array();
            $order_items = $this->crud->get_all_with_where('order_items', '', '', array('order_id' => $post_data['order_id']));
            if(!empty($order_items)){
                foreach ($order_items as $order_item){
                    $old_order_item_id_arr[] = $order_item->order_item_id;
                }
            }
            
            $update_arr['account_id'] = $post_data['account_id'];            
            $update_arr['process_id'] = $post_data['process_id'];            
            $update_arr['order_date'] = date('Y-m-d', strtotime($post_data['order_date']));
            $update_arr['order_remark'] = $post_data['order_remark']; 
            $update_arr['total_gold_fine'] = $post_data['sell_gold_fine'];
            $update_arr['total_silver_fine'] = $post_data['sell_silver_fine'];           
            $update_arr['total_amount'] = $post_data['order_amount'];
            $update_arr['total_c_amount'] = $post_data['bill_cr_c_amount'];
            $update_arr['total_r_amount'] = $post_data['bill_cr_r_amount'];
            if(isset($post_data['discount_amount'])){
                $post_data['discount_amount'] = (!empty($post_data['discount_amount'])) ? $post_data['discount_amount'] : 0;
                $update_arr['discount_amount'] = $post_data['discount_amount'];
                if(PACKAGE_FOR == 'manek') {
                    $post_data['order_amount'] = $post_data['order_amount'] - $post_data['discount_amount'];
                }
            }
            $update_arr['delivery_type'] = $post_data['delivery_type']; 
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('ordersell', $update_arr, array('order_id' => $post_data['order_id']));
            
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Order Updated Successfully');
                
                // Insert sell_purchase_items
                if(!empty($line_items_data)){
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['order_id'] = $post_data['order_id'];
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                        $insert_item['gold_silver_rate'] = (isset($lineitem->gold_silver_rate) && !empty($lineitem->gold_silver_rate)) ? $lineitem->gold_silver_rate : 0;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['touch_id'] = $lineitem->touch_id;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $account_item_wstg = $this->crud->get_column_value_by_id('party_item_details', 'wstg', array('account_id' => $post_data['account_id'],'item_id'=> $lineitem->item_id));
                        if(!empty($account_item_wstg)){
                            $lineitem_default_wstg = (!empty($account_item_wstg)) ? $account_item_wstg : '0';
                        } else {
                            $lineitem_default_wstg = $this->crud->get_val_by_id('item_master', $lineitem->item_id, 'item_id', 'default_wastage');
                        }
                        if($lineitem_default_wstg != '' && $lineitem->type == SELL_TYPE_SELL_ID){
                            if($lineitem_default_wstg != $lineitem->wstg){
                                $insert_item['wastage_change_approve'] = '1_0';
                            }
                        }
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                        $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        $insert_item['charges_amt'] = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $insert_item['spi_pcs'] = (isset($lineitem->spi_pcs) && !empty($lineitem->spi_pcs)) ? $lineitem->spi_pcs : 0;
                        $insert_item['spi_rate'] = (isset($lineitem->spi_rate) && !empty($lineitem->spi_rate)) ? $lineitem->spi_rate : 0;
                        $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                        $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
                        $insert_item['image'] = $lineitem->image;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->order_item_id) && !empty($lineitem->order_item_id)){
                            $this->crud->update('order_items', $insert_item, array('order_item_id' => $lineitem->order_item_id));
                            $old_order_item_id_arr = array_diff($old_order_item_id_arr, array($lineitem->order_item_id));
                            $order_item_id = $lineitem->order_item_id;
                        } else {
                            $lot = $this->crud->get_max_number('order_items', 'order_item_no');
                            $order_item_no = 1;
                            if ($lot->order_item_no > 0) {
                                $order_item_no = $lot->order_item_no + 1;
                            }
                            $insert_item['order_item_no'] = $order_item_no;
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('order_items', $insert_item);
                            $order_item_id = $this->db->insert_id();
                            $line_items_data[$key]->purchase_item_id = $order_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                    }
                }

                if (!empty($old_order_item_id_arr)) {
                    $this->crud->delete_where_in('order_items', 'order_item_id', $old_order_item_id_arr);
                }
            }
        } else {
            $order = $this->crud->get_max_number('ordersell', 'order_no');
            $order_no = 1;
            if ($order->order_no > 0) {
                $order_no = $order->order_no + 1;
            }

            $insert_arr = array();
            $insert_arr['order_no'] = $order_no;
            $insert_arr['account_id'] = $post_data['account_id'];            
            $insert_arr['process_id'] = $post_data['process_id'];            
            $insert_arr['order_date'] = date('Y-m-d', strtotime($post_data['order_date']));
            $insert_arr['order_remark'] = $post_data['order_remark'];
            $insert_arr['total_gold_fine'] = $post_data['sell_gold_fine'];
            $insert_arr['total_silver_fine'] = $post_data['sell_silver_fine'];
            $insert_arr['total_amount'] = $post_data['order_amount'];
            $insert_arr['total_c_amount'] = $post_data['bill_cr_c_amount'];
            $insert_arr['total_r_amount'] = $post_data['bill_cr_r_amount'];
            if(isset($post_data['discount_amount'])){
                $post_data['discount_amount'] = (!empty($post_data['discount_amount'])) ? $post_data['discount_amount'] : 0;
                $insert_arr['discount_amount'] = $post_data['discount_amount'];
                if(PACKAGE_FOR == 'manek') {
                    $post_data['order_amount'] = $post_data['order_amount'] - $post_data['discount_amount'];
                }
            }
            $insert_arr['delivery_type'] = $post_data['delivery_type'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
        
            $result = $this->crud->insert('ordersell', $insert_arr);
            $order_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Order Added Successfully');
                
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $lot = $this->crud->get_max_number('order_items', 'order_item_no');
                        $order_item_no = 1;
                        if ($lot->order_item_no > 0) {
                            $order_item_no = $lot->order_item_no + 1;
                        }
                        $insert_item = array();
                        $insert_item['order_id'] = $order_id;
                        $insert_item['order_item_no'] = $order_item_no;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                        $insert_item['gold_silver_rate'] = (isset($lineitem->gold_silver_rate) && !empty($lineitem->gold_silver_rate)) ? $lineitem->gold_silver_rate : 0;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['touch_id'] = $lineitem->touch_id;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $account_item_wstg = $this->crud->get_column_value_by_id('party_item_details', 'wstg', array('account_id' => $post_data['account_id'],'item_id'=> $lineitem->item_id));
                        if(!empty($account_item_wstg)){
                            $lineitem_default_wstg = (!empty($account_item_wstg)) ? $account_item_wstg : '0';
                        } else {
                            $lineitem_default_wstg = $this->crud->get_val_by_id('item_master', $lineitem->item_id, 'item_id', 'default_wastage');
                        }
                        if($lineitem_default_wstg != '' && $lineitem->type == SELL_TYPE_SELL_ID){
                            if($lineitem_default_wstg != $lineitem->wstg){
                                $insert_item['wastage_change_approve'] = '1_0';
                            }
                        }
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                        $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        $insert_item['charges_amt'] = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $insert_item['spi_pcs'] = (isset($lineitem->spi_pcs) && !empty($lineitem->spi_pcs)) ? $lineitem->spi_pcs : 0;
                        $insert_item['spi_rate'] = (isset($lineitem->spi_rate) && !empty($lineitem->spi_rate)) ? $lineitem->spi_rate : 0;
                        $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                        $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
                        $insert_item['image'] = $lineitem->image;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('order_items', $insert_item);
                        $order_item_id = $this->db->insert_id();
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function order_list($param1 = '') { 
        if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) {
            $sell_purchase = "sell_purchase";
            $view = $param1;
            $page_label = "Order";
            $entry_page_url = base_url("order/add");
            $data = array();
            $data['page_label'] = $page_label;
            $data['entry_page_url'] = $entry_page_url;
            $data['sell_purchase'] = $sell_purchase;
            if(!empty($view)){
                $data['view_not'] = $view;
            }
            set_page('order/order_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function order_datatable() {
        $post_data = $this->input->post();
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'ordersell o';
        $config['select'] = 'o.*,p.account_name,p.account_mobile,a.account_name AS process_name,IF(o.delivery_type = 1, "Delivered" ,"Not Delivered") AS delivery_type';
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = o.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.process_id', 'join_type' => 'left');
        $config['column_search'] = array('o.order_no','p.account_name','a.account_name','DATE_FORMAT(o.order_date,"%d-%m-%Y")', 'order_remark', 'IF(o.delivery_type = 1, "Delivered" ,"Not Delivered")');
        $config['column_order'] = array(null, 'p.account_name', 'a.account_name', 'o.order_no', 'o.order_date', 'order_remark', 'o.delivery_type');
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
            $config['custom_where'] .= ' AND o.process_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND o.process_id IN(-1)';
        }
        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date'])){
                $config['wheres'][] = array('column_name' => 'o.order_date >=', 'column_value' => $from_date);
            }
        }
        if(!empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 'o.order_date <=', 'column_value' => $to_date);
        }
        if(isset($post_data['account_id']) && !empty($post_data['account_id'])){
            $config['wheres'][] = array('column_name' => 'o.account_id', 'column_value' => $post_data['account_id']);
        }
        if(isset($post_data['delivery_type']) && !empty($post_data['delivery_type'])){
            $config['wheres'][] = array('column_name' => 'o.delivery_type', 'column_value' => $post_data['delivery_type']);
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 'o.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }

        $config['group_by'] = 'o.order_id';
        $config['order'] = array('o.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(ORDER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ORDER_MODULE_ID, "edit");
        foreach ($list as $order) {
            $row = array();
            $action = '';
            if($order->account_id != ADJUST_EXPENSE_ACCOUNT_ID){
                if($order->audit_status != AUDIT_STATUS_AUDITED){
                    if($role_edit){
                        $action .= '<a href="' . base_url("order/add/" . $order->order_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                    if($role_delete){
                        $action .= '<a href="javascript:void(0);" class="delete_order" data-sell_id="'.$order->order_id.'" data-href="' . base_url('order/delete_order/' . $order->order_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else {
                    if($role_edit){
                        $action .= '<a href="' . base_url("order/add/" . $order->order_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                }
            }
            
            $row[] = $action;
            $row[] = $order->account_name . ' - ' . $order->account_mobile;
            $row[] = $order->process_name;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-sell_id="' . $order->order_id . '" >' . $order->order_no . '</a>';
            $row[] = (!empty(strtotime($order->order_date))) ? date('d-m-Y', strtotime($order->order_date)) : '';
            $row[] = $order->order_remark;
            $row[] = $order->delivery_type;
            if(isset($post_data['check']) && $post_data['check'] == '1'){
                $row[] = '<a href="javascript:void(0);" class="update_delivery_type" data-href="' . base_url('sell/update_delivery_type/' . $order->order_id) . '"><input type="checkbox"  class="icheckbox_flat-blue check_delivery" value="'.$order->order_id.'"></a>';
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

    function delete_order($id = '', $have_rfid = '') {
        $where_array = array('order_id' => $id);
        $ordersell = $this->crud->get_row_by_id('ordersell', $where_array);
        $return = array();
        if(!empty($ordersell)){
            $this->crud->delete('order_items', $where_array);
            $this->crud->delete('ordersell', $where_array);
            $return['success'] = 'Deleted';
        }
        echo json_encode($return);
        exit;
    }

    function order_item_datatable() {
        $post_data = $this->input->post();
        if(isset($post_data['from_date']) && !empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(isset($post_data['to_date']) && !empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'order_items oi';
        $config['select'] = 'oi.*,p.account_name,a.account_name AS process_name,st.type_name,im.item_name,c.category_name,o.order_no,o.order_date';
        $config['joins'][] = array('join_table' => 'ordersell o', 'join_by' => 'o.order_id = oi.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = o.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.process_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_type st', 'join_by' => 'st.sell_type_id = oi.type', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = oi.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= oi.category_id', 'join_type' => 'left');
        $config['column_search'] = array('p.account_name','a.account_name','o.order_no','DATE_FORMAT(o.order_date,"%d-%m-%Y")','oi.order_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'oi.grwt', 'oi.less', 'oi.net_wt','oi.wstg','oi.fine');
        $config['column_order'] = array(null, 'p.account_name','a.account_name','o.order_no','o.order_date','oi.order_item_no', 'st.type_name', 'c.category_name', 'im.item_name', 'oi.grwt', 'oi.less','oi.net_wt','oi.wstg','oi.fine');
        $config['order'] = array('oi.order_item_no' => 'desc');
        
        if (isset($post_data['order_id']) && !empty($post_data['order_id'])) {
            $config['wheres'][] = array('column_name' => 'oi.order_id', 'column_value' => $post_data['order_id']);
        }
        if(isset($post_data['from_date']) && !empty($post_data['from_date'])){
            $config['wheres'][] = array('column_name' => 'o.order_date >=', 'column_value' => $from_date);
        }
        if(isset($post_data['to_date']) && !empty($post_data['to_date'])){
            $config['wheres'][] = array('column_name' => 'o.order_date <=', 'column_value' => $to_date);
        }
        if(isset($post_data['delivery_type']) && !empty($post_data['delivery_type'])){
            $config['wheres'][] = array('column_name' => 'o.delivery_type', 'column_value' => $post_data['delivery_type']);
        }
        if(isset($post_data['item_id']) && !empty($post_data['item_id'])){
            $config['wheres'][] = array('column_name' => 'oi.item_id', 'column_value' => $post_data['item_id']);
        }
        if(isset($post_data['sell_type']) && !empty($post_data['sell_type'])){
            $config['wheres'][] = array('column_name' => 'oi.type', 'column_value' => $post_data['sell_type']);
        }
        if(isset($post_data['account_id']) && !empty($post_data['account_id'])){
            $config['wheres'][] = array('column_name' => 'o.account_id', 'column_value' => $post_data['account_id']);
        }
        if(isset($post_data['wastage']) && !empty($post_data['wastage'])){
            $config['wheres'][] = array('column_name' => 'oi.wastage_change_approve', 'column_value' => $post_data['wastage']);
        }
        if(isset($post_data['touch_id']) && !empty($post_data['touch_id'])){
            $config['wheres'][] = array('column_name' => 'oi.touch_id', 'column_value' => $post_data['touch_id']);
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
            $config['custom_where'] .= ' AND o.process_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND o.process_id IN(-1)';
        }

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $role_delete = $this->app_model->have_access_role(ORDER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ORDER_MODULE_ID, "edit");
        foreach ($list as $order_detail) {
            $row = array();
            $action = '';
            if (isset($post_data['order_id']) && !empty($post_data['order_id'])) {} else{
                if($role_edit){
                    $action .= '<a href="' . base_url("order/add/" . $order_detail->order_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($order_detail->wastage_change_approve != '0_0') {
                    $checked = $order_detail->wastage_change_approve == '1_1' ? 'checked' : '';
                    $action .= '<input type="checkbox" class="wastage_change_approve" id="' . $order_detail->order_item_id . '" ' . $checked . '>';
                }
                $row[] = $action;
                $row[] = $order_detail->account_name;
                $row[] = $order_detail->process_name;
                $row[] = $order_detail->order_no;
                $row[] = (!empty(strtotime($order_detail->order_date))) ? date('d-m-Y', strtotime($order_detail->order_date)) : '';
            }
            $row[] = $order_detail->order_item_no;
            $row[] = $order_detail->type_name;
            $row[] = $order_detail->category_name;
            $row[] = $order_detail->item_name;
            $row[] = number_format($order_detail->grwt, 3, '.', '');
            $row[] = number_format($order_detail->less, 3, '.', '');
            $row[] = number_format($order_detail->net_wt, 3, '.', '');
            $row[] = $order_detail->touch_id;
            $row[] = number_format($order_detail->wstg, 3, '.', '');
            $row[] = number_format($order_detail->fine, 3, '.', '');
            $img_src = base_url('/uploads/sell_item_photo/'). $order_detail->image;
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
}
