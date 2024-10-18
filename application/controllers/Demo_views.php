<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Demo_views extends CI_Controller {

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
        $this->sell_purchase_difference = $this->session->userdata(PACKAGE_FOLDER_NAME.'sell_purchase_difference');
        $this->now_time = date('Y-m-d H:i:s');
        $this->zero_value = 0;
    }

    function view1() {
        $data = array();
        set_page('demo_views/view1', $data);
    }

    function sales1($param1 = '',$param2 = '',$param3 = '') {
        if(in_array($param1,array("sell","purchase")) && $this->sell_purchase_difference) {
            $sell_purchase = $param1;
            $sell_id = $param2;
            $order_id = $param3;
            if($sell_purchase == "sell") {
                $page_label = "Sell";
                $page_shortcut = "[CTRL + F1]";
                $list_page_url = base_url("sell/sell_list/sell");
            } else {
                $page_label = "Purchase";
                $page_shortcut = "[CTRL + F2]";
                $list_page_url = base_url("sell/sell_list/purchase");
            }
        } else {
            $sell_purchase = "sell_purchase";
            $sell_id = $param1;
            $order_id = $param2;
            $page_label = "Sales 1";
            $page_shortcut = "[CTRL + F1]";
            $list_page_url = base_url("sell/sell_list");
        }
        $data = array();
        $data['page_label'] = $page_label;
        $data['page_shortcut'] = $page_shortcut;
        $data['list_page_url'] = $list_page_url;
        $data['sell_purchase'] = $sell_purchase;

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
        
        $sell_lineitems = array();
        $sell_items = new \stdClass();
        $sell_items->item_name = 'Item Name Narration';
        $sell_items->hsn_code = '7113';
        $sell_items->grwt = '132.35';
        $sell_items->less = '0';
        $sell_items->net_wt = '82.78';
        $sell_items->type_name = 'S';
        $sell_items->item_id = '';
        $sell_items->sell_less_ad_details = '';
        $sell_items->rate_per_1_gram = '3814';
        $sell_items->gross_amount = '315722.92';
        $sell_items->gst_rate = '3';
        $sell_items->labout_other_charges = '10.76000121';
        $sell_items->amount = '349694.71';
        $sell_items->gst_amount = '0';
        $sell_lineitems[] = json_encode($sell_items);
        
        $sell_items = new \stdClass();
        $sell_items->item_name = 'Item Name Narration';
        $sell_items->hsn_code = '7113';
        $sell_items->grwt = '115.21';
        $sell_items->less = '0';
        $sell_items->net_wt = '72.16';
        $sell_items->type_name = 'S';
        $sell_items->item_id = '';
        $sell_items->sell_less_ad_details = '';
        $sell_items->rate_per_1_gram = '3814';
        $sell_items->gross_amount = '275218.24';
        $sell_items->gst_rate = '3';
        $sell_items->labout_other_charges = '10.82';
        $sell_items->amount = '304996.85';
        $sell_items->gst_amount = '0';
        $sell_lineitems[] = json_encode($sell_items);
        
        $data['sell_item'] = implode(',', $sell_lineitems);
        
//        echo '<pre>'; print_r($data); exit;
        set_page('demo_views/sales1', $data);
    }
    
    function sales2($param1 = '',$param2 = '',$param3 = '') {
        if(in_array($param1,array("sell","purchase")) && $this->sell_purchase_difference) {
            $sell_purchase = $param1;
            $sell_id = $param2;
            $order_id = $param3;
            if($sell_purchase == "sell") {
                $page_label = "Sell";
                $page_shortcut = "[CTRL + F1]";
                $list_page_url = base_url("sell/sell_list/sell");
            } else {
                $page_label = "Purchase";
                $page_shortcut = "[CTRL + F2]";
                $list_page_url = base_url("sell/sell_list/purchase");
            }
        } else {
            $sell_purchase = "sell_purchase";
            $sell_id = $param1;
            $order_id = $param2;
            $page_label = "Sales 2";
            $page_shortcut = "[CTRL + F1]";
            $list_page_url = base_url("sell/sell_list");
        }
        $data = array();
        $data['page_label'] = $page_label;
        $data['page_shortcut'] = $page_shortcut;
        $data['list_page_url'] = $list_page_url;
        $data['sell_purchase'] = $sell_purchase;

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
        
        $sell_lineitems = array();
        $sell_items = new \stdClass();
        $sell_items->item_name = 'Item Name Narration';
        $sell_items->hsn_code = '7113';
        $sell_items->grwt = '132.35';
        $sell_items->less = '0';
        $sell_items->net_wt = '82.78';
        $sell_items->type_name = 'S';
        $sell_items->item_id = '';
        $sell_items->sell_less_ad_details = '';
        $sell_items->rate_per_1_gram = '3814';
        $sell_items->gross_amount = '315722.92';
        $sell_items->gst_rate = '3';
        $sell_items->labout_other_charges = '10.76000121';
        $sell_items->amount = '349694.71';
        $sell_items->gst_amount = '0';
        $sell_lineitems[] = json_encode($sell_items);
        
        $sell_items = new \stdClass();
        $sell_items->item_name = 'Item Name Narration';
        $sell_items->hsn_code = '7113';
        $sell_items->grwt = '115.21';
        $sell_items->less = '0';
        $sell_items->net_wt = '72.16';
        $sell_items->type_name = 'S';
        $sell_items->item_id = '';
        $sell_items->sell_less_ad_details = '';
        $sell_items->rate_per_1_gram = '3814';
        $sell_items->gross_amount = '275218.24';
        $sell_items->gst_rate = '3';
        $sell_items->labout_other_charges = '10.82';
        $sell_items->amount = '304996.85';
        $sell_items->gst_amount = '0';
        $sell_lineitems[] = json_encode($sell_items);
        
        $data['sell_item'] = implode(',', $sell_lineitems);
        
//        echo '<pre>'; print_r($data); exit;
        set_page('demo_views/sales2', $data);
    }

}
