<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class New_order extends CI_Controller {

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
    }

    function add($order_id = '') {
        $data = array();
        $oreder_lot_item = new \stdClass();
        $category = $this->crud->get_all_records('category', 'category_id', '');        
        $data['category'] = $category;
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');   
        $data['touch'] = $touch;
         if (!empty($order_id)) {
            if($this->applib->have_access_role(ORDER_MODULE_ID,"edit") || $this->applib->have_access_role(ORDER_MENU_ID,'view')) {
                $new_order_data = $this->crud->get_row_by_id('new_order', array('order_id' => $order_id));
                $order_lot_items = $this->crud->get_row_by_id('order_lot_item', array('order_id' => $order_id));
                $new_order_data = $new_order_data[0];
                $new_order_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$new_order_data->created_by));
                if($new_order_data->created_by != $new_order_data->updated_by){
                    $new_order_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $new_order_data->updated_by));
                }else{
                    $new_order_data->updated_by_name = $new_order_data ->created_by_name;
                }
                $data['new_order_data'] = $new_order_data;
                $lineitems = array();
                foreach($order_lot_items as $lot_item){
                    $oreder_lot_item->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                    $oreder_lot_item->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                    $oreder_lot_item->purity = $this->crud->get_column_value_by_id('carat', 'purity', array('carat_id' => $lot_item->touch_id));
                    $oreder_lot_item->item_status = $this->crud->get_column_value_by_id('order_status', 'status', array('order_status_id' => $lot_item->item_status_id));
                    $oreder_lot_item->weight = $lot_item->weight;
                    $oreder_lot_item->pcs = $lot_item->pcs;
                    $oreder_lot_item->size = $lot_item->size;
                    $oreder_lot_item->length = $lot_item->length;
                    $oreder_lot_item->hook_style = $lot_item->hook_style;
                    $oreder_lot_item->lot_remark = $lot_item->lot_remark;
                    $oreder_lot_item->image = $lot_item->image;
                    $oreder_lot_item->category_id = $lot_item->category_id;
                    $oreder_lot_item->item_id = $lot_item->item_id;
                    $oreder_lot_item->item_status_id = $lot_item->item_status_id;
                    $oreder_lot_item->touch_id = $lot_item->touch_id;
                    $oreder_lot_item->order_lot_item_id = $lot_item->order_lot_item_id;
                    $oreder_lot_item->is_sell = 0;
                    $order_sell = $this->crud->get_column_value_by_id('sell_items', 'order_lot_item_id', array('order_lot_item_id' => $lot_item->order_lot_item_id));
                    if(!empty($order_sell)){
                        $oreder_lot_item->is_sell = 1;
                    }
                    $lineitems[] = json_encode($oreder_lot_item);
                }
                $data['order_lot_item'] = implode(',', $lineitems);
                set_page('new_order/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(ORDER_MODULE_ID,"add") || $this->applib->have_access_role(ORDER_MENU_ID,'view')) {
                $lineitems = array();
                $order = $this->crud->get_max_number('new_order', 'order_no');
                $order_no = 1;
                if ($order->order_no > 0) {
                    $order_no = $order->order_no + 1;
                }
                $data['order_no'] = $order_no;
                set_page('new_order/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_new_order() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($post_data); exit;
//        echo '<pre>'; print_r($line_items_data); exit;
        
        $post_data['order_status_id'] = isset($post_data['order_status_id']) && !empty($post_data['order_status_id']) ? $post_data['order_status_id'] : NULL;
        if (isset($post_data['order_id']) && !empty($post_data['order_id'])) {

            $update_arr = array();
            $update_arr['process_id'] = $post_data['department_id'];
            $update_arr['order_date'] = date('Y-m-d', strtotime($post_data['order_date']));
            $update_arr['delivery_date'] = !empty($post_data['delivery_date']) ? date('Y-m-d', strtotime($post_data['delivery_date'])) : null;
            $update_arr['supplier_delivery_date'] = !empty($post_data['supplier_delivery_date']) ? date('Y-m-d', strtotime($post_data['supplier_delivery_date'])) : null;
            $update_arr['real_delivery_date'] = !empty($post_data['real_delivery_date']) ? date('Y-m-d', strtotime($post_data['real_delivery_date'])) : null;
            $update_arr['party_id'] = $post_data['party_id'];
            $update_arr['supplier_id'] = isset($post_data['supplier_id'])?$post_data['supplier_id']:null;
            $update_arr['gold_price'] = $post_data['gold_price'];
            $update_arr['silver_price'] = $post_data['silver_price'];
            $update_arr['remark']= $post_data['remark'];
            $update_arr['total_weight']= $post_data['total_weight'];
            $update_arr['total_pcs']= $post_data['total_pcs'];
            $update_arr['order_status_id']= $post_data['order_status_id'];
            $update_arr['order_type']= $post_data['order_type'];
            if($post_data['order_status_id'] == '2'){
                $update_arr['reason'] = $post_data['reason'];
            }
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['order_id'] = $post_data['order_id'];


            $new_delivery_date = $update_arr['delivery_date'];
            $old_delivery_date = $this->crud->get_id_by_val('new_order', 'delivery_date', 'order_id', $post_data['order_id']);;

            $result = $this->crud->update('new_order', $update_arr, $where_array);
            $deleted_oli_ids = $post_data['deleted_oli_ids'];

            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Order Updated Successfully');

                if(!empty($deleted_oli_ids)) {
                    $deleted_oli_ids = explode(',', $deleted_oli_ids);
                    foreach($deleted_oli_ids as $id){
                        $where_array = array('order_lot_item_id' => $id);
                        $this->crud->delete('order_lot_item', $where_array);
                    }
                }

                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        $update_item = array();
                        $update_item['order_id'] = $post_data['order_id'];
                        $update_item['category_id'] = $lineitem->category_id;
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['touch_id'] = $lineitem->touch_id;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['pcs'] = $lineitem->pcs;
                        $update_item['size'] = $lineitem->size;
                        $update_item['length'] = $lineitem->length;
                        $update_item['hook_style'] = $lineitem->hook_style;
                        $update_item['lot_remark'] = $lineitem->lot_remark;
                        $update_item['image'] = $lineitem->image;

                        if($lineitem->order_lot_item_id != ''){
                            if($post_data['pre_order_status_id'] == 1 && $post_data['order_status_id'] == 3){
                                $status_data = $this->crud->get_row_by_id('order_lot_item', array('order_lot_item_id' => $lineitem->order_lot_item_id));
                                $item_status_id = $status_data[0]->item_status_id;
                                if($item_status_id == 1){
                                    $update_item['item_status_id'] = 3;
                                } else {
                                    $update_item['item_status_id'] = $lineitem->item_status_id ? $lineitem->item_status_id : 1;
                                }
                            } else {
                                $update_item['item_status_id'] = $lineitem->item_status_id ? $lineitem->item_status_id : 1;
                            }

                            $where = array("order_lot_item_id" => $lineitem->order_lot_item_id);
                            $update_item['updated_at'] = $this->now_time;
                            $update_item['updated_by'] = $this->logged_in_id;
                            $this->crud->update("order_lot_item",$update_item , $where);
                        }
                        else{
                            $order_itm = $this->crud->get_last_record_where('order_lot_item', 'order_item_no', 'order_lot_item_id', array('order_id' => $post_data['order_id']));
                            $order_item_no = 1;
                            if (!empty($order_itm) && $order_itm->order_item_no > 0) {
                                $order_item_no = explode('/', $order_itm->order_item_no);
                                $order_item_no = $order_item_no[1];
                                $order_item_no = $order_item_no + 1;
                            }
                            $update_item['order_item_no'] = $post_data["order_no"].'/'.$order_item_no;
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $update_item['updated_at'] = $this->now_time;
                            $update_item['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('order_lot_item', $update_item);
                        }
                        //$this->crud->insert('order_lot_item', $update_item);
                    }
                }
            }
//            $status = $this->crud->getFromSQL('SELECT COUNT(item_status_id) AS item_status FROM order_lot_item WHERE order_id='.$post_data['order_id'].' AND `item_status_id`!= 3');
//            if($status[0]->item_status == 0){
//                $this->crud->update('new_order',array('order_status_id' => '3'),array('order_id'=>$post_data['order_id']));
//            }
            if(!empty($post_data['send_sms'])){
                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['party_id']);
                $party_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $post_data['party_id']);
                
                if (!empty($mobile_no)) {

                    /*SMS when date change : `Sorry for Inconvenience. Your Order No. 232 Date changed to : 25-10-2019`*/
                    if(strtotime($new_delivery_date) > 0 && strtotime($new_delivery_date) != strtotime($old_delivery_date)) {
                        $sms = SEND_ORDER_DELIVERY_DATE_CHANGE_SMS;
                        $vars = array(
                            '{{party_name}}' => $party_name,
                            '{{order_no}}' => $post_data['order_no'],
                            '{{delivery_date}}' => date('d-m-Y',strtotime($new_delivery_date)),
                        );
                        $sms = strtr($sms, $vars);
                        $this->applib->send_sms($mobile_no, $sms);
                    }

                    if(!empty($post_data['order_type']) && $post_data['order_type'] == 1){
                        if(!empty($post_data['order_status_id']) && $post_data['order_status_id'] == '2'){
                            $sms = SEND_ORDER_CANCEL_SMS;
                            $vars = array(
                                '{{party_name}}' => $party_name,
                                '{{reason}}' => $post_data['reason'],
                            );
                            $sms = strtr($sms, $vars);
                            $this->applib->send_sms($mobile_no, $sms);
                        } else if(!empty($post_data['order_status_id']) && $post_data['order_status_id'] == '3'){
                            $sms = SEND_ORDER_COMPLETE_SMS;
                            $vars = array(
                                '{{party_name}}' => $party_name,
                                '{{order_no}}' => $post_data['order_no'],
                            );
                            $sms = strtr($sms, $vars);
                            $this->applib->send_sms($mobile_no, $sms);
                        }
                    }
                }
            }
//            $order_update_id = $post_data['order_id'];
//            $oredr_status = $this->db->query("CALL `update_order_status_id`(".$order_update_id.")");
        } else {
            $order = $this->crud->get_max_number('new_order', 'order_no');
            $order_no = 1;
            if ($order->order_no > 0) {
                $order_no = $order->order_no + 1;
            }
            $insert_arr = array();
            $insert_arr['order_no'] = $order_no;            
            $insert_arr['process_id'] = $post_data['department_id'];
            $insert_arr['order_date'] = date('Y-m-d', strtotime($post_data['order_date']));
            $insert_arr['delivery_date'] = !empty($post_data['delivery_date']) ? date('Y-m-d', strtotime($post_data['delivery_date'])) : null;
            $insert_arr['supplier_delivery_date'] = !empty($post_data['supplier_delivery_date']) ? date('Y-m-d', strtotime($post_data['supplier_delivery_date'])) : null;
            $insert_arr['real_delivery_date'] = !empty($post_data['real_delivery_date']) ? date('Y-m-d', strtotime($post_data['real_delivery_date'])) : null;
            $insert_arr['party_id'] = $post_data['party_id'];
            $insert_arr['supplier_id'] = isset($post_data['supplier_id'])?$post_data['supplier_id']:null;
            $insert_arr['gold_price'] = $post_data['gold_price'];
            $insert_arr['silver_price'] = $post_data['silver_price'];
            $insert_arr['remark']= $post_data['remark'];
            $insert_arr['total_weight']= $post_data['total_weight'];
            $insert_arr['total_pcs']= $post_data['total_pcs'];
            $insert_arr['order_type']= $post_data['order_type'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('new_order', $insert_arr);
            $order_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Order Added Successfully');

                $item_inc = 1;
                $image = '';
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        if(empty($lineitem->image)){
//                            echo '<pre>'; print_r('rfg'); exit;
                            $item_data = $this->crud->get_row_by_id('item_master', array('category_id' => $lineitem->category_id,'item_id'=>$lineitem->item_id));
//                            echo '<pre>'; print_r($item_data); exit;
                            $image = $item_data[0]->image;
                        } else { 
                            $image = $lineitem->image;
                        }
                        $order_itm = $this->crud->get_last_record_where('order_lot_item', 'order_item_no', 'order_lot_item_id', array('order_id' => $order_id));
                        $order_item_no = 1;
                        if (!empty($order_itm) && $order_itm->order_item_no > 0) {
                            $order_item_no = explode('/', $order_itm->order_item_no);
                            $order_item_no = $order_item_no[1];
                            $order_item_no = $order_item_no + 1;
                        }
                        $insert_item = array();
                        $insert_item['order_id'] = $order_id;
                        $insert_item['order_item_no'] = $order_no.'/'.$order_item_no;
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['touch_id'] = $lineitem->touch_id;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['pcs'] = $lineitem->pcs;
                        $insert_item['size'] = $lineitem->size;
                        $insert_item['length'] = $lineitem->length;
                        $insert_item['hook_style'] = $lineitem->hook_style;
                        $insert_item['lot_remark'] = $lineitem->lot_remark;
                        $insert_item['image'] = $image;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
//                        echo '<pre>'; print_r($insert_item); exit;
                        $this->crud->insert('order_lot_item', $insert_item);
                    }
                }
            }
            if(!empty($post_data['send_sms'])){
                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['party_id']);
                $party_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $post_data['party_id']);
                if (!empty($mobile_no)) {
                    if(!empty($post_data['order_type']) && $post_data['order_type'] == 1){
                        $sms = SEND_ORDER_CREATE_SMS;
                        $vars = array(
                            '{{party_name}}' => $party_name,
                            '{{order_no}}' => $order_no,
                            '{{total_weight}}' => $post_data['total_weight'],
                            '{{total_pcs}}' => $post_data['total_pcs'],
                            '{{delvery_date}}' => $post_data['delivery_date'],
                        );
                        $sms = strtr($sms, $vars);
                        $this->applib->send_sms($mobile_no, $sms);
                    }
                }
            }
            if(!empty($post_data['send_whatsapp_sms'])){
                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['party_id']);
                $party_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $post_data['party_id']);
                if (!empty($mobile_no)) {
                    if(!empty($post_data['order_type']) && $post_data['order_type'] == 1){
                        $sms = SEND_ORDER_CREATE_SMS;
                        $vars = array(
                            '{{party_name}}' => $party_name,
                            '{{order_no}}' => $order_no,
                            '{{total_weight}}' => $post_data['total_weight'],
                            '{{total_pcs}}' => $post_data['total_pcs'],
                            '{{delvery_date}}' => $post_data['delivery_date'],
                        );
                        $sms = strtr($sms, $vars);
                        $return['send_whatsapp_sms_url'] = 'https://api.whatsapp.com/send?phone=91'.$mobile_no.'&text='.$sms;
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function new_order_list() {
        if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) {
            $data = array();
            $category = $this->crud->get_all_records('category', 'category_id', '');        
            $data['category'] = $category;
            $items = $this->crud->get_all_records('item_master', 'item_id', '');        
            $data['items'] = $items;
            $data['type'] = '1';
            $department_ids = $this->applib->current_user_order_department_ids();
            $data['department_ids'] = json_encode($department_ids);
            set_page('new_order/new_order_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function inquiry_list() {
        if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) {
            $data = array();
            $category = $this->crud->get_all_records('category', 'category_id', '');        
            $data['category'] = $category;
            $items = $this->crud->get_all_records('item_master', 'item_id', '');        
            $data['items'] = $items;
            $data['type'] = '2';
            $department_ids = $this->applib->current_user_order_department_ids();
            $data['department_ids'] = json_encode($department_ids);
            set_page('new_order/new_order_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function get_item_name($category_id){
        $data = array();
        if($category_id > 0){
            $items = $this->crud->get_all_with_where('item_master', '', '', array('category_id'=>$category_id));            
        }else{
            $items = $this->crud->get_all_records('item_master', 'item_id', 'asc');           
        }
        $data['items'] = $items;
        print json_encode($data);
        exit;
    }
    
    function new_order_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'new_order o';
        $config['select'] = 'o.*,a.account_name,s.account_name as supplier_name,a.account_mobile,aa.account_name AS process_name,o.total_weight,o.total_pcs,os.status,IF(o.order_type = 1,"Order","Inquiry") AS type';

        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = o.order_status_id', 'join_type' => 'left');

        $config['joins'][] = array('join_table' => 'order_lot_item ol', 'join_by' => 'ol.order_id = o.order_id', 'join_type' => 'left');

        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.party_id', 'join_type' => 'left');
        
        $config['joins'][] = array('join_table' => 'account s', 'join_by' => 's.account_id = o.supplier_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = o.process_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'order_lot_item li', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        
        $config['column_search'] = array('a.account_name', 'a.account_mobile', 'aa.account_name', 'o.order_no', 'DATE_FORMAT(o.order_date,"%d-%m-%Y")', 'DATE_FORMAT(o.delivery_date,"%d-%m-%Y")','s.account_name','DATE_FORMAT(o.supplier_delivery_date,"%d-%m-%Y")','o.total_weight','o.total_pcs','os.status','IF(o.order_type = 1,"Order","Inquiry")');
        
        $config['column_order'] = array(null, 'a.account_name', 'aa.account_name', 'o.order_no', 'o.order_date', 'o.delivery_date','o.real_delivery_date','s.account_name','o.supplier_delivery_date','o.total_weight', 'o.total_pcs','os.status', 'IF(o.order_type = 1,"Order","Inquiry")');
        /**if($this->user_type == USER_TYPE_USER){
            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $this->department_id);
        }**/

        $config['custom_where'] = '1=1';
        $account_groups = $this->applib->current_user_account_group_ids();
        if(!empty($account_groups)) {
            $config['custom_where'] .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
        } else {
            $config['custom_where'] .= ' AND a.account_group_id IN(-1)';
        }

        $account_ids = $this->applib->current_user_account_ids();
        if($account_ids == "allow_all_accounts"){

        } elseif(!empty($account_ids)){
            $config['custom_where'] .= ' AND a.account_id IN('.implode(',',$account_ids).')';
        } else {
            $config['custom_where'] .= ' AND a.account_id IN(-1)';
        }

        $department_ids = $this->applib->current_user_order_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= ' AND o.process_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND o.process_id IN(-1)';
        }
        
        if(!empty($post_data['department_id'])){
            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $post_data['department_id']);
        }
        if(!empty($post_data['item_id'])){
            $config['wheres'][] = array('column_name' => 'ol.item_id', 'column_value' => $post_data['item_id']);
        }
        if(!empty($post_data['category_id'])){
            $config['wheres'][] = array('column_name' => 'ol.category_id', 'column_value' => $post_data['category_id']);
        }
        if(!empty($post_data['order_status_id'])){
            $config['wheres'][] = array('column_name' => 'o.order_status_id', 'column_value' => $post_data['order_status_id']);
        }
        if(!empty($post_data['order_type'])){
            $config['wheres'][] = array('column_name' => 'o.order_type', 'column_value' => $post_data['order_type']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date']) && strtotime($post_data['from_date']) > 0){
                $config['wheres'][] = array('column_name' => 'o.order_date >=', 'column_value' => date("Y-m-d",strtotime($post_data['from_date'])));
            }
        }
        if(!empty($post_data['to_date']) && strtotime($post_data['to_date']) > 0){
            $config['wheres'][] = array('column_name' => 'o.order_date <=', 'column_value' => date("Y-m-d",strtotime($post_data['to_date'])));
        }

        if(isset($post_data['supplier_id'])) {
            if($post_data['supplier_id'] == '' || $post_data['supplier_id'] == "ALL") {

            } elseif ($post_data['supplier_id'] == 'OnlySupplier') {
                $config['wheres'][] = '(o.supplier_id != "" AND o.supplier_id IS NOT NULL)';   
            
            } elseif ($post_data['supplier_id'] == 'ExcludeSupplier') {
                $config['wheres'][] = '(o.supplier_id = "" OR o.supplier_id IS NULL)';   
            
            } else {
                $config['wheres'][] = array('column_name' => 'o.supplier_id', 'column_value' => $post_data['supplier_id']);
            }
        }


        $config['group_by'] = 'o.order_id';
        $config['order'] = array('o.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(ORDER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ORDER_MODULE_ID, "edit");
        $role_sell = $this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "add");
//        $entry_department_ids = $this->applib->current_user_department_ids();
        foreach ($list as $orders) {
            $row = array();
            $action = '';
//            if(in_array($orders->process_id, $entry_department_ids)){
                if($role_edit){
                    $action .= '<a href="' . base_url("new_order/add/" . $orders->order_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($role_delete){
                    $action .= '<a href="javascript:void(0);" class="delete_order" data-href="' . base_url('new_order/delete_order/' . $orders->order_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
                $status = $this->crud->get_row_by_id('new_order', array('order_id' => $orders->order_id));
                if($role_sell){
                    if(!empty($status) && $status[0]->order_status_id == PENDING_STATUS && $status[0]->order_type == '1'){
                        $action .= '<a href="' . base_url("sell/add/0/" . $orders->order_id) . '" title="Order To Sell/Purchase"><span class="fa fa-mail-forward" style="color : #279B8D;">&nbsp;</span></a>';
                    }
                }
                if(!empty($status) && $status[0]->order_type == '2'){
                    $action .= '<a href="javascript:void(0);" class="change_order_type" title="Convert To Order" data-href="' . base_url('new_order/change_order_type/' . $orders->order_id) . '"><span class="fa fa-reply" style="color : #4267b2">&nbsp;</span></a>';
                }
//            }
            $row[] = $action;
            $row[] = $orders->account_name. ' - ' .$orders->account_mobile;
            $row[] = $orders->process_name;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-order_id="' . $orders->order_id . '" >' . $orders->order_no . '</a>';
            $row[] = (!empty(strtotime($orders->order_date))) ? date('d-m-Y', strtotime($orders->order_date)) : '';
            $row[] = (!empty(strtotime($orders->delivery_date))) ? date('d-m-Y', strtotime($orders->delivery_date)) : '';
            $row[] = (!empty(strtotime($orders->real_delivery_date))) ? date('d-m-Y', strtotime($orders->real_delivery_date)) : '';
            $row[] = $orders->supplier_name;
            $row[] = (!empty(strtotime($orders->supplier_delivery_date))) ? date('d-m-Y', strtotime($orders->supplier_delivery_date)) : '';
            $row[] = number_format($orders->total_weight, 3, '.', '');
            $row[] = $orders->total_pcs;
            $row[] = $orders->status;
            $row[] = $orders->type;
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

    function order_lot_item_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['column_order'] = array(null, 'cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.hook_style', 'li.lot_remark');
        $config['column_search'] = array('cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.lot_remark');
        $config['order'] = array('li.order_lot_item_id' => 'desc');
        if (isset($post_data['order_id']) && !empty($post_data['order_id'])) {
            $config['wheres'][] = array('column_name' => 'li.order_id', 'column_value' => $post_data['order_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $lot_detail) {
            $row = array();
            $action = '<a href="' . base_url("new_order/order_list_print/" . $lot_detail->order_lot_item_id) . '" target="_blank" title="Order List Print" alt="Call Slip Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';

            $action .= '&nbsp; <a href="' . base_url("new_order/order_supplier_list_print/" . $lot_detail->order_lot_item_id) . '" target="_blank" title="Order Supplier List Print" alt="Order Supplier List Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';   

            $row[] = $action;
            $row[] = $lot_detail->order_item_no;
            $row[] = $lot_detail->category_name;
            $row[] = $lot_detail->item_name;
            $row[] = $lot_detail->design_no;
            $row[] = $lot_detail->die_no;
            $row[] = $lot_detail->purity;
            $row[] = number_format($lot_detail->weight, 3, '.', '');
            $row[] = $lot_detail->pcs;
            $row[] = $lot_detail->size;
            $row[] = $lot_detail->length;
            $row[] = $lot_detail->hook_style;
            $row[] = $lot_detail->lot_remark;
            $row[] = $lot_detail->status;
            if(!empty($lot_detail->image) && file_exists($lot_detail->image)) {
                $img_src = base_url(). $lot_detail->image;
                $row[] = '<a href="javascript:void(0);" class="image_model" data-img_src="' .$img_src.'" ><img src="' . base_url($lot_detail->image) . '" alt="" height="42" width="42"></a> ';    
            } else {
                $row[] = '';    
            }            
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
    
    function delete_order($id = '') {
        $sell_data = $this->crud->get_all_with_where('sell', '', '', array('order_id' => $id));
        if(!empty($sell_data)){
            $return['error'] = 'Error';
        } else {
            $new_order = $this->crud->get_all_with_where('new_order', '', '', array('order_id' => $id));
            $lot_item_detail = $this->crud->get_all_with_where('order_lot_item', '', '', array('order_id' => $id));
            foreach ($lot_item_detail as $lot_item){
                if(!empty($lot_item->image)){
                    if (file_exists(PUBPATH."uploads/order_item_photo/".$lot_item->image)) {
                        unlink(PUBPATH."uploads/order_item_photo/".$lot_item->image);
                    }
                }
            }
            $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $new_order[0]->party_id);
            $party_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $new_order[0]->party_id);
            if (!empty($mobile_no)) {
    //            echo '<pre>'; print_r($party_name); exit;
                $sms = SEND_ORDER_DELETE_SMS;
                $vars = array(
                    '{{party_name}}' => $party_name,
                );
                $sms = strtr($sms, $vars);
                $this->applib->send_sms($mobile_no, $sms);
            }
            $where_array = array('order_id' => $id);
            $this->crud->delete('order_lot_item', $where_array);
            $this->crud->delete('new_order', $where_array);
            $return['success'] = 'Deleted';
        }
        echo json_encode($return);
        exit;
    }
    
    function change_order_type($id = '') {
        $this->crud->update('new_order',array('order_type' => '1'), array('order_id' => $id));
    }

    function get_temp_path_image() {
        $data = '';
        if (isset($_FILES['file_upload']['name']) && !empty($_FILES['file_upload']['name'])) {
            $file_element_name = 'file_upload';
            $out_dir = "uploads/order_item_photo/";
            $config['upload_path'] = './uploads/order_item_photo/';
            $config['allowed_types'] = '*';
            $config['overwrite'] = TRUE;
            $config['encrypt_name'] = FALSE;
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = date('Y_m_d_H_i_s_U');
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file_element_name)) {
                $return['Uploaderror'] = $this->upload->display_errors();
            }
            $file_data = $this->upload->data();
            @unlink($_FILES[$file_element_name]);
            $data = $out_dir.''.$file_data['file_name'];
        }
        
        print $data;
        exit;
    }

    function order_list_print($order_lot_item_id = '') {
        $data = array();
        if(!empty($order_lot_item_id)){
            $order_lot_item_data = $this->crud->get_all_with_where('order_lot_item', '', '', array('order_lot_item_id' => $order_lot_item_id));
            $new_order_data = $this->crud->get_all_with_where('new_order', '', '', array('order_id' => $order_lot_item_data[0]->order_id));
            $item_master_data = $this->crud->get_all_with_where('item_master', '', '', array('item_id' => $order_lot_item_data[0]->item_id));
            $data['item_master_data'] = $item_master_data[0];
            $data['new_order_data'] = $new_order_data[0];
            $data['order_lot_item_data'] = $order_lot_item_data[0];
            $order_lot_item_data[0]->tunch = $this->crud->get_column_value_by_id('carat','purity',array('carat_id'=>$order_lot_item_data[0]->touch_id));
        }
        $html = $this->load->view('new_order/order_list_print', $data, true);
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function order_supplier_list_print($order_lot_item_id = '') {
        $data = array();
        if(!empty($order_lot_item_id)){
            $order_lot_item_data = $this->crud->get_all_with_where('order_lot_item', '', '', array('order_lot_item_id' => $order_lot_item_id));
            $new_order_data = $this->crud->get_all_with_where('new_order', '', '', array('order_id' => $order_lot_item_data[0]->order_id));
            $item_master_data = $this->crud->get_all_with_where('item_master', '', '', array('item_id' => $order_lot_item_data[0]->item_id));

            $data['item_master_data'] = $item_master_data[0];
            $data['new_order_data'] = $new_order_data[0];
            $data['order_lot_item_data'] = $order_lot_item_data[0];
            $order_lot_item_data[0]->tunch = $this->crud->get_column_value_by_id('carat','purity',array('carat_id'=>$order_lot_item_data[0]->touch_id));

            if(isset($new_order_data[0]->supplier_id)) {
                $data['supplier_name'] = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $new_order_data[0]->supplier_id);
            } else {
                $data['supplier_name'] = '';    
            }
            
        }
        $html = $this->load->view('new_order/order_supplier_list_print', $data, true);
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    function new_order_item_list($delivery_date = '') {
        if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) {
            $data = array();
            $category = $this->crud->get_all_records('category', 'category_id', '');        
            $data['category'] = $category;
            $items = $this->crud->get_all_records('item_master', 'item_id', '');        
            $data['items'] = $items;
            $data['delivery_date'] = $delivery_date;
            set_page('new_order/new_order_item_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function new_order_item_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['date'])) {
            $date = date('Y-m-d', strtotime($post_data['date']));
        }
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,o.order_date,o.order_no,o.delivery_date,a.account_name,a.account_mobile,pm.account_name AS process_name,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'new_order o', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.party_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = o.process_id', 'join_type' => 'left');
        
        $config['column_search'] = array('a.account_name', 'a.account_mobile', 'pm.account_name', 'o.order_no', 'DATE_FORMAT(o.order_date,"%d-%m-%Y")', 'DATE_FORMAT(o.delivery_date,"%d-%m-%Y")','cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.lot_remark');
        $config['column_order'] = array(null, 'a.account_name', 'pm.account_name', null, null, 'o.order_no', 'o.order_date', 'o.delivery_date','cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.lot_remark');

        $config['custom_where'] = '1=1';
        $account_groups = $this->applib->current_user_account_group_ids();
        if(!empty($account_groups)) {
            $config['custom_where'] .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
        } else {
            $config['custom_where'] .= ' AND a.account_group_id IN(-1)';
        }

        $account_ids = $this->applib->current_user_account_ids();
        if($account_ids == "allow_all_accounts") {
            
        } elseif(!empty($account_ids)) {
            $config['custom_where'] .= ' AND a.account_id IN('.implode(',',$account_ids).')';
        } else {
            $config['custom_where'] .= ' AND a.account_id IN(-1)';
        }

        $department_ids = $this->applib->current_user_order_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= ' AND o.process_id IN('.$department_ids.')';
        } else {
            $config['custom_where'] .= ' AND o.process_id IN(-1)';
        }
        
//        if($this->user_type == USER_TYPE_USER){
//            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $this->department_id);
//        }
        if(!empty($post_data['item_id'])){
            $config['wheres'][] = array('column_name' => 'li.item_id', 'column_value' => $post_data['item_id']);
        }
        if(!empty($post_data['category_id'])){
            $config['wheres'][] = array('column_name' => 'li.category_id', 'column_value' => $post_data['category_id']);
        }
        if(!empty($post_data['order_status_id'])){
            $config['wheres'][] = array('column_name' => 'li.item_status_id', 'column_value' => $post_data['order_status_id']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['date'])) {
                $config['wheres'][] = array('column_name' => 'o.delivery_date <=', 'column_value' => $date);
            }
        }
        $config['order'] = array('li.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        //echo '<pre>'.$this->db->last_query(); exit;
        foreach ($list as $lot_detail) {
            $row = array();
            $action = '<a href="' . base_url("new_order/order_list_print/" . $lot_detail->order_lot_item_id) . '" target="_blank" title="Order List Print" alt="Call Slip Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';   
            $type = $lot_detail->item_status_id;
            if($type == 1){
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="2" class="btn btn-primary btn-xs change_item_status" style="margin-bottom: 3px;">Cancel</a>';
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="3" class="btn btn-primary btn-xs change_item_status">Complete</a>';
            }
            if($type == 2){
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="1" class="btn btn-primary btn-xs change_item_status" style="margin-bottom: 3px;">Pending</a>';
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="3" class="btn btn-primary btn-xs change_item_status">Complete</a>';
            }
            if($type == 3){
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="1" class="btn btn-primary btn-xs change_item_status" style="margin-bottom: 3px;">Pending</a>';
                $action .= '<a href="javascript:void(0);" data-href="' . base_url("new_order/change_item_status/" . $lot_detail->order_lot_item_id) . '" status="2" class="btn btn-primary btn-xs change_item_status">Cancel</a>';
            }
            $row[] = $action;
            $row[] = $lot_detail->account_name. ' - ' .$lot_detail->account_mobile;
            $row[] = $lot_detail->process_name;
            if(!empty($lot_detail->image) && file_exists($lot_detail->image)) {
                $img_src = base_url(). $lot_detail->image;
                $row[] = '<a href="javascript:void(0);" class="image_model" data-img_src="' .$img_src.'" ><img src="' . base_url($lot_detail->image) . '" alt="" height="42" width="42"></a> ';    
            } else {
                $row[] = '';
            }
            
            $row[] = $lot_detail->status;
            $row[] = $lot_detail->order_item_no;
            $row[] = (!empty(strtotime($lot_detail->order_date))) ? date('d-m-Y', strtotime($lot_detail->order_date)) : '';
            $row[] = (!empty(strtotime($lot_detail->delivery_date))) ? date('d-m-Y', strtotime($lot_detail->delivery_date)) : '';
            $row[] = $lot_detail->category_name;
            $row[] = $lot_detail->item_name;
            $row[] = $lot_detail->design_no;
            $row[] = $lot_detail->die_no;
            $row[] = $lot_detail->purity;
            $row[] = number_format($lot_detail->weight, 3, '.', '');
            $row[] = $lot_detail->pcs;
            $total_weight = $lot_detail->weight * $lot_detail->pcs;
            $row[] = number_format($total_weight, 3, '.', '');;
            $row[] = $lot_detail->size;
            $row[] = $lot_detail->length;
            $row[] = $lot_detail->hook_style;
            $row[] = $lot_detail->lot_remark;
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

    function change_item_status($id= '') {
        $where = array("order_lot_item_id" => $id);
        $update_item['item_status_id'] = $_POST['status'];
        $update_item['updated_at'] = $this->now_time;
        $update_item['updated_by'] = $this->logged_in_id;
        $this->crud->update("order_lot_item",$update_item , $where);
    }
    
    function casting_item_list(){
        if($this->applib->have_access_role(ORDER_MODULE_ID,"view")) {
            $data = array();
            $category = $this->crud->get_all_records('category', 'category_id', '');        
            $data['category'] = $category;
            $items = $this->crud->get_all_records('item_master', 'item_id', '');        
            $data['items'] = $items;
            set_page('new_order/casting_item_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function casting_item_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['date'])) {
            $date = date('Y-m-d', strtotime($post_data['date']));
        }
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,o.order_date,o.order_no,o.delivery_date,a.account_name,a.account_mobile,pm.account_name AS process_name,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'new_order o', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.party_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = o.process_id', 'join_type' => 'left');
        
        $config['column_search'] = array('a.account_name', 'a.account_mobile', 'pm.account_name', 'o.order_no', 'DATE_FORMAT(o.order_date,"%d-%m-%Y")', 'DATE_FORMAT(o.delivery_date,"%d-%m-%Y")','cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.lot_remark');
        $config['column_order'] = array(null, 'a.account_name', 'pm.account_name', null, null, 'o.order_no', 'o.order_date', 'o.delivery_date','cat.category_name', 'im.item_name', 'im.design_no', 'im.die_no', 'c.purity', 'li.weight', 'li.pcs','li.size', 'li.length','li.hook_style', 'li.lot_remark');
        $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => CASTING_DEPARTMENT_ACCOUNT_ID);
        
        if(!empty($post_data['item_id'])){
            $config['wheres'][] = array('column_name' => 'li.item_id', 'column_value' => $post_data['item_id']);
        }
        if(!empty($post_data['category_id'])){
            $config['wheres'][] = array('column_name' => 'li.category_id', 'column_value' => $post_data['category_id']);
        }
        if(!empty($post_data['order_status_id'])){
            $config['wheres'][] = array('column_name' => 'li.item_status_id', 'column_value' => $post_data['order_status_id']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['date'])) {
                $config['wheres'][] = array('column_name' => 'o.delivery_date <=', 'column_value' => $date);
            }
        }
        $config['order'] = array('li.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        //echo '<pre>'.$this->db->last_query(); exit;
        foreach ($list as $lot_detail) {
            $row = array();
            $action = '';
//            $action .= '<a href="javascript:void(0);" status="1" class="btn btn-primary btn-xs change_item_status" style="margin-bottom: 3px;">Not Started</a>';
            $action .= '<span style="text-align: center;">
                                    <input type="checkbox" name="change_casting_status[]" id=""  class="change_casting_status">
                        </span>';
            $row[] = $action;
            if(!empty($lot_detail->image) && file_exists($lot_detail->image)) {
                $img_src = base_url(). $lot_detail->image;
                $row[] = '<a href="javascript:void(0);" class="image_model" data-img_src="' .$img_src.'" ><img src="' . base_url($lot_detail->image) . '" alt="" height="42" width="42"></a> ';    
            } else {
                $row[] = '';
            }
            
            $row[] = 'Not Started';
            $row[] = $lot_detail->status;
            $row[] = $lot_detail->order_no;
            $row[] = (!empty(strtotime($lot_detail->order_date))) ? date('d-m-Y', strtotime($lot_detail->order_date)) : '';
            $row[] = (!empty(strtotime($lot_detail->delivery_date))) ? date('d-m-Y', strtotime($lot_detail->delivery_date)) : '';
            $row[] = $lot_detail->category_name;
            $row[] = $lot_detail->item_name;
            $row[] = $lot_detail->design_no;
            $row[] = $lot_detail->die_no;
            $row[] = $lot_detail->purity;
            $row[] = number_format($lot_detail->weight, 3, '.', '');
            $row[] = $lot_detail->pcs;
            $total_weight = $lot_detail->weight * $lot_detail->pcs;
            $row[] = number_format($total_weight, 3, '.', '');;
            $row[] = $lot_detail->size;
            $row[] = $lot_detail->length;
            $row[] = $lot_detail->hook_style;
            $row[] = $lot_detail->lot_remark;
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
    
    
}
