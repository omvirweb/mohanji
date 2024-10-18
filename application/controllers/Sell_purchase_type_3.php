<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sell_purchase_type_3 extends CI_Controller {

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
                $list_page_url = base_url("sell_purchase_type_3/splist/sell");
            } else {
                $page_label = "Purchase";
                $page_shortcut = "[CTRL + F2]";
                $list_page_url = base_url("sell_purchase_type_3/splist/purchase");
            }
        } else if ($param1 == 'payment_receipt') {
            $sell_purchase = $param1;
            $sell_id = $param2;
            $order_id = $param3;
            $page_label = "Payment Receipt";
            $page_shortcut = "[F1]";
            $list_page_url = base_url("sell_purchase_type_3/splist/payment_receipt");
        } else if ($param1 == 'metal_issue_receive') {
            $sell_purchase = $param1;
            $sell_id = $param2;
            $order_id = $param3;
            $page_label = "Metal Issue Receive";
            $page_shortcut = "[F2]";
            $list_page_url = base_url("sell_purchase_type_3/splist/metal_issue_receive");
        } else {
            $sell_purchase = "sell_purchase";
            $sell_id = $param1;
            $order_id = $param2;
            $page_label = "Sell/Purchase";
            $page_shortcut = "[CTRL + F1]";
            $list_page_url = base_url("sell_purchase_type_3/splist");
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
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"edit") || $this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,'add')) {
                $new_order_data = $this->crud->get_row_by_id('new_order', array('order_id' => $order_id));
                $order_lot_items = $this->crud->get_row_by_id('order_lot_item', array('order_id' => $order_id, 'item_status_id' => '1'));
                $new_order_data = $new_order_data[0];
                $data['new_order_data'] = $new_order_data;

                foreach($order_lot_items as $lot_item){
                    $oreder_lot_item = new \stdClass();
                    $oreder_lot_item->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                    $oreder_lot_item->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lot_item->category_id));
                    $oreder_lot_item->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                    $oreder_lot_item->less = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $lot_item->item_id));
                    $oreder_lot_item->purity = $this->crud->get_val_by_id('carat', $lot_item->touch_id, 'carat_id', 'purity');
                    $oreder_lot_item->grwt = number_format($lot_item->weight, 3, '.', '');
                    $oreder_lot_item->net_wt = number_format($lot_item->weight, 3, '.', '');
                    $oreder_lot_item->type_name = 'S';
                    $oreder_lot_item->type = '1';
                    $oreder_lot_item->amount = '0';
                    $oreder_lot_item->image = $lot_item->image;
                    $oreder_lot_item->category_id = $lot_item->category_id;
                    $oreder_lot_item->item_id = $lot_item->item_id;
                    $oreder_lot_item->touch_id = $lot_item->touch_id;
                    $oreder_lot_item->order_lot_item_id = $lot_item->order_lot_item_id;
                    $oreder_lot_item->touch_id = $oreder_lot_item->purity;
                    unset($oreder_lot_item->purity);
                    $account_data = $this->crud->get_row_by_id('party_item_details',array('account_id' => $new_order_data->party_id,'item_id'=> $lot_item->item_id));
                    if(!empty($account_data)){
                        $wstg = $account_data[0]->wstg;
                    } else {
                        $item_data = $this->crud->get_val_by_id('item_master',$lot_item->item_id,'item_id','default_wastage');
                        $wstg = $item_data;
                    }

                    $oreder_lot_item->wstg = $wstg;
                    $oreder_lot_item->fine = $lot_item->weight * ($oreder_lot_item->touch_id + $wstg) / 100;
                    $oreder_lot_item->fine = number_format($oreder_lot_item->fine, 3, '.', '');
//                    $data['credit_limit'] = $this->crud->get_id_by_val('account', 'credit_limit', 'account_id', $account_id);
                    $order_lineitems[] = json_encode($oreder_lot_item);
                }
                $data['order_lot_item'] = implode(',', $order_lineitems);
                $data['order_to_sell_purchase'] = '1';
                set_page('sell/sell_purchase_type_3', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
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

                //----------------- Sell Itemms -------------------
                $sell_data_items = $this->crud->get_row_by_id('sell_items', array('sell_id' => $sell_id));
                if(!empty($sell_data_items)){
                    foreach($sell_data_items as $lot_item){
                        $sell_items = new \stdClass();
                        $sell_items->sell_item_delete = 'allow';
                        $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lot_item->item_id));
                        if($stock_method == STOCK_METHOD_ITEM_WISE){
                            $item_sells = $this->crud->getFromSQL('SELECT * FROM sell_items WHERE purchase_sell_item_id = '.$lot_item->sell_item_id.' AND stock_type IN (1,2)');
//                            $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $lot_item->sell_item_id));
                            if(!empty($item_sells)){
                                $sell_items->sell_item_delete = 'not_allow';
                            }
                            $item_transfer = $this->crud->getFromSQL('SELECT * FROM stock_transfer_detail WHERE purchase_sell_item_id = '.$lot_item->sell_item_id.' AND stock_type IN (1,2)');
//                            $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $lot_item->sell_item_id));
                            if(!empty($item_transfer)){
                                $sell_items->sell_item_delete = 'not_allow';
                            }
                            $item_issue_receive = $this->crud->getFromSQL('SELECT * FROM issue_receive_details WHERE purchase_sell_item_id = '.$lot_item->sell_item_id.' AND stock_type IN (1,2)');
//                            $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $lot_item->sell_item_id));
                            if(!empty($item_issue_receive)){
                                $sell_items->sell_item_delete = 'not_allow';
                            }
                            $item_manu_hand_made = $this->crud->getFromSQL('SELECT * FROM manu_hand_made_details WHERE purchase_sell_item_id = '.$lot_item->sell_item_id.' AND stock_type IN (1,2)');
                            if(!empty($item_manu_hand_made)){
                                $sell_items->sell_item_delete = 'not_allow';
                            }
//                            $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$lot_item->sell_item_id.'" AND s.process_id = "'.$sell_data->process_id.'"');
                            $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si LEFT JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$lot_item->sell_item_id.'"');
                            $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="'.$lot_item->sell_item_id.'" AND s.from_department = "'.$sell_data->process_id.'"');
                            $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="'.$lot_item->sell_item_id.'" AND ir.department_id = "'.$sell_data->process_id.'"');
                            $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$lot_item->sell_item_id.'" AND mhm.department_id = "'.$sell_data->process_id.'"');
                            $sell_items->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt;

                        } else {
                            if($data['without_purchase_sell_allow'] == '1'){
                                $total_sell_grwt = $this->crud->get_total_sell_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                $total_transfer_grwt = $this->crud->get_total_transfer_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                $total_metal_grwt = $this->crud->get_total_metal_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt;
    //                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt;
                                $used_lineitem_ids = array();
                                $sell_items->total_grwt_sell = 0;
                                if(!empty($total_sell_grwt)){
                                    $purchase_items = $this->crud->get_purchase_items_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                    $metal_items = $this->crud->get_metal_items_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                    $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                    $receive_items = $this->crud->get_receive_items_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                    $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($sell_data->process_id, $lot_item->category_id, $lot_item->item_id, $lot_item->touch_id);
                                    $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items);
    //                                $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items);

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
                                            if($purchase_item->type == 'P'){
                                                $used_lineitem_ids[] = $purchase_item->sell_item_id;
                                                if($lot_item->sell_item_id == $purchase_item->sell_item_id){
                                                    $sell_items->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $purchase_item->grwt;
                                                }
                                            }
                                            $first_check_purchase_grwt = 1;
                                        } else if($purchase_grwt <= $total_sell_grwt){
                                            if($purchase_item->type == 'P'){
                                                $used_lineitem_ids[] = $purchase_item->sell_item_id;
                                                if($lot_item->sell_item_id == $purchase_item->sell_item_id){
                                                    $sell_items->total_grwt_sell = $purchase_item->grwt;
                                                }
                                            }

                                        }
                                    }
                                }
                                if(!empty($used_lineitem_ids) && in_array($lot_item->sell_item_id, $used_lineitem_ids)){
                                    $sell_items->sell_item_delete = 'not_allow';
                                }
                            }
                        }
                        $sell_items->tunch_textbox = (isset($lot_item->tunch_textbox) && $lot_item->tunch_textbox == '1') ? '1' : '0';
                        $sell_items->type = $lot_item->type;
                        $type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $lot_item->type));
                        $sell_items->type_name = $type_name[0];
                        $sell_items->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $lot_item->category_id));
                        $sell_items->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lot_item->category_id));
                        $sell_items->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $lot_item->item_id));
                        //$sell_items->stamp_name = $this->crud->get_column_value_by_id('stamp', 'stamp_name', array('stamp_id' => $lot_item->stamp_id));
                        $sell_items->grwt = $lot_item->grwt;
                        $sell_items->less = $lot_item->less;
                        $sell_items->net_wt = $lot_item->net_wt;
                        $sell_items->spi_loss_for = $lot_item->spi_loss_for;
                        $sell_items->spi_loss = $lot_item->spi_loss;
                        $sell_items->touch_id = $lot_item->touch_id;
                        $sell_items->wstg = $lot_item->wstg;
                        $sell_items->fine = $lot_item->fine;
                        $sell_items->category_id = $lot_item->category_id;
                        $sell_items->item_id = $lot_item->item_id;
                        $sell_items->li_narration = (isset($lot_item->li_narration) && !empty($lot_item->li_narration)) ? $lot_item->li_narration : NULL;
                        $sell_items->stamp_id = $lot_item->stamp_id;
                        $sell_items->sell_item_id = $lot_item->sell_item_id;
                        $sell_items->item_stock_rfid_id = (isset($lot_item->item_stock_rfid_id) && !empty($lot_item->item_stock_rfid_id)) ? $lot_item->item_stock_rfid_id : NULL;
                        $sell_items->rfid_number = (isset($lot_item->rfid_number) && !empty($lot_item->rfid_number)) ? $lot_item->rfid_number : NULL;
                        $sell_items->charges_amt = (isset($lot_item->charges_amt) && !empty($lot_item->charges_amt)) ? $lot_item->charges_amt : 0;
                        $sell_items->spi_pcs = (isset($lot_item->spi_pcs) && !empty($lot_item->spi_pcs)) ? $lot_item->spi_pcs : 0;
                        $sell_items->spi_rate = (isset($lot_item->spi_rate) && !empty($lot_item->spi_rate)) ? $lot_item->spi_rate : 0;
                        $sell_items->spi_rate_of = (isset($lot_item->spi_rate_of) && !empty($lot_item->spi_rate_of)) ? $lot_item->spi_rate_of : 1;
                        $sell_items->spi_labour_on = (isset($lot_item->spi_labour_on) && !empty($lot_item->spi_labour_on)) ? $lot_item->spi_labour_on : 0;
                        $sell_items->labour_amount = (isset($lot_item->labour_amount) && !empty($lot_item->labour_amount)) ? $lot_item->labour_amount : 0;
                        $sell_items->amount = (isset($lot_item->amount) && !empty($lot_item->amount)) ? $lot_item->amount : 0;
                        $sell_items->image = $lot_item->image;
                        $sell_items->order_lot_item_id = $lot_item->order_lot_item_id;
                        $sell_items->purchase_sell_item_id = $lot_item->purchase_sell_item_id;
                        $sell_items->stock_type = $lot_item->stock_type;
                        
                        //----------------- Less Ad Details -------------------
                        $sell_items->sell_less_ad_details = '';
                        $sell_less_ad_details = $this->crud->get_row_by_id('sell_less_ad_details', array('sell_id' => $lot_item->sell_id, 'sell_item_id' => $lot_item->sell_item_id));
                        if(!empty($sell_less_ad_details)){
                            $less_ad_details_data = array();
                            foreach ($sell_less_ad_details as $sell_less_ad_detail){
                                $sell_less_ad_details_lineitems = new \stdClass();
                                $sell_less_ad_details_lineitems->sell_less_ad_details_id = $sell_less_ad_detail->sell_less_ad_details_id;
                                $sell_less_ad_details_lineitems->less_ad_details_delete = 'allow';
                                $sell_less_ad_details_lineitems->less_ad_details_ad_id = $sell_less_ad_detail->less_ad_details_ad_id;
                                $sell_less_ad_details_lineitems->less_ad_details_ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $sell_less_ad_detail->less_ad_details_ad_id));
                                $sell_less_ad_details_lineitems->less_ad_details_ad_pcs = (isset($sell_less_ad_detail->less_ad_details_ad_pcs) && !empty($sell_less_ad_detail->less_ad_details_ad_pcs)) ? $sell_less_ad_detail->less_ad_details_ad_pcs : NULL;
                                $sell_less_ad_details_lineitems->less_ad_details_ad_weight = $sell_less_ad_detail->less_ad_details_ad_weight;
                                $less_ad_details_data[] = json_encode($sell_less_ad_details_lineitems);
                            }
                            $sell_items->sell_less_ad_details = '['.implode(',', $less_ad_details_data).']';
                        }
                        
                        //----------------- Sell item charges Details -------------------
                        $sell_items->sell_item_charges_details = '';
                        $sell_item_charges_details = $this->crud->get_row_by_id('sell_item_charges_details', array('sell_id' => $lot_item->sell_id, 'sell_item_id' => $lot_item->sell_item_id));
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
                                if(isset($sell_item_charges_detail->sell_item_charges_details_remark)){
                                    $sell_item_charges_details_lineitems->sell_item_charges_details_remark = $sell_item_charges_detail->sell_item_charges_details_remark;
                                }
                                $sell_item_charges_details_data[] = json_encode($sell_item_charges_details_lineitems);
                            }
                            $sell_items->sell_item_charges_details = '['.implode(',', $sell_item_charges_details_data).']';
                        }

                        $sell_lineitems[] = json_encode($sell_items);
                    }
                    $data['sell_item'] = implode(',', $sell_lineitems);
                }

                //----------------- Payment Receipt -------------------
                $payment_receipt = $this->crud->get_row_by_id('payment_receipt', array('sell_id' => $sell_id));
                if(!empty($payment_receipt)){
                    $pay_lineitems = new \stdClass();
                    foreach ($payment_receipt as $value){
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
                        $pay_rec_lineitems[] = json_encode($pay_lineitems);
                    }
                    $data['pay_rec_data'] = implode(',', $pay_rec_lineitems);
                }

                //----------------- Metal Issue Receive -------------------
                $metal_payment_receipt = $this->crud->get_row_by_id('metal_payment_receipt', array('sell_id' => $sell_id));
//                echo '<pre>'; print_r($metal_payment_receipt); exit;
                if(!empty($metal_payment_receipt)){
                    $metal_lineitems = new \stdClass();
                    foreach ($metal_payment_receipt as $metal){
                        $metal_lineitems->metal_item_delete = 'allow';
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt;
    //                        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt;
                            $used_lineitem_ids = array();
                            $metal_lineitems->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                                $issue_receive_items = $this->crud->get_receive_items_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($sell_data->process_id, $metal->metal_category_id, $metal->metal_item_id, $metal->metal_tunch);
                                $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $issue_receive_items, $manu_hand_made_receive_items);
    //                            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items);

                                uasort($purchase_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
                                $metal_grwt = 0;
                                $first_check_metal_grwt = 0;
    //                            echo '<pre>'; print_r($purchase_delete_array); exit;
                                foreach ($purchase_delete_array as $metal_item){
                                    $metal_grwt = $metal_grwt + $metal_item->grwt;
                                    if($metal_grwt >= $total_sell_grwt && $first_check_metal_grwt == 0){
                                        if($metal_item->type == 'M'){
                                            $used_lineitem_ids[] = $metal_item->metal_pr_id;
                                            if($metal->metal_pr_id == $metal_item->metal_pr_id){
                                                $metal_lineitems->total_grwt_metal = (float) $total_sell_grwt - (float) $metal_grwt + (float) $metal_item->grwt;
                                            }
                                        }
                                        $first_check_metal_grwt = 1;
                                    } else if($metal_grwt <= $total_sell_grwt){
                                        if($metal_item->type == 'M'){
                                            $used_lineitem_ids[] = $metal_item->metal_pr_id;
                                            if($metal->metal_pr_id == $metal_item->metal_pr_id){
                                                $metal_lineitems->total_grwt_metal = $metal_item->grwt;
                                            }
                                        }
                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($metal->metal_pr_id, $used_lineitem_ids)){
                                $metal_lineitems->metal_item_delete = 'not_allow';
                            }
                        }
//                        echo '<pre>'; print_r($metal_lineitems->total_grwt_metal); exit;
                        $metal_lineitems->metal_item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $metal->metal_item_id));
                        $metal_lineitems->metal_payment_receipt = $metal->metal_payment_receipt;
                        $metal_lineitems->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $metal->metal_category_id));
                        $metal_lineitems->metal_item_id = $metal->metal_item_id;
                        $metal_lineitems->metal_grwt = $metal->metal_grwt;
                        $metal_lineitems->metal_tunch = $metal->metal_tunch;
                        $metal_lineitems->metal_fine = $metal->metal_fine;
                        $metal_lineitems->metal_narration = $metal->metal_narration;
                        $metal_lineitems->metal_pr_id = $metal->metal_pr_id;
                        $metal_pay_rec_lineitems[] = json_encode($metal_lineitems);
                    }
                    $data['metal_data'] = implode(',', $metal_pay_rec_lineitems);
                }

                //----------------- Gold Bhav -------------------
                $gold_bhav = $this->crud->get_row_by_id('gold_bhav', array('sell_id' => $sell_id));
                if(!empty($gold_bhav)){
                    $gold_bhav_lineitems = new \stdClass();
                    foreach ($gold_bhav as $gold){
                        $gold_bhav_lineitems->gold_sale_purchase = $gold->gold_sale_purchase;
                        $gold_bhav_lineitems->gold_weight = $gold->gold_weight;
                        $gold_bhav_lineitems->gold_rate = $gold->gold_rate;
                        $gold_bhav_lineitems->gold_value = $gold->gold_value;
                        $gold_bhav_lineitems->gold_cr_effect = $gold->gold_cr_effect;
                        $gold_bhav_lineitems->gold_narration = $gold->gold_narration;
                        $gold_bhav_lineitems->gold_id = $gold->gold_id;
                        $gold_lineitems[] = json_encode($gold_bhav_lineitems);
                    }
                    $data['gold_data'] = implode(',', $gold_lineitems);
                }

                //----------------- Silver Bhav -------------------
                $silver_bhav = $this->crud->get_row_by_id('silver_bhav', array('sell_id' => $sell_id));
                if(!empty($silver_bhav)){
                    $silver_bhav_lineitems = new \stdClass();
                    foreach ($silver_bhav as $silver){
                        $silver_bhav_lineitems->silver_sale_purchase = $silver->silver_sale_purchase;
                        $silver_bhav_lineitems->silver_weight = $silver->silver_weight;
                        $silver_bhav_lineitems->silver_rate = $silver->silver_rate;
                        $silver_bhav_lineitems->silver_value = $silver->silver_value;
                        $silver_bhav_lineitems->silver_cr_effect = $silver->silver_cr_effect;
                        $silver_bhav_lineitems->silver_narration = $silver->silver_narration;
                        $silver_bhav_lineitems->silver_id = $silver->silver_id;
                        $silver_lineitems[] = json_encode($silver_bhav_lineitems);
                    }
                    $data['silver_data'] = implode(',', $silver_lineitems);
                }

                //----------------- transfer -------------------
                $transfers = $this->crud->get_row_by_id('transfer', array('sell_id' => $sell_id));
                if(!empty($transfers)){
                    $transfers_lineitems = new \stdClass();
                    foreach ($transfers as $transfer){
                        $transfers_lineitems->naam_jama = $transfer->naam_jama;
                        $transfers_lineitems->party_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $transfer->transfer_account_id));
                        $transfers_lineitems->transfer_account_id = $transfer->transfer_account_id;
                        $transfers_lineitems->transfer_gold = $transfer->transfer_gold;
                        $transfers_lineitems->transfer_silver = $transfer->transfer_silver;
                        $transfers_lineitems->transfer_amount = $transfer->transfer_amount;
                        $transfers_lineitems->transfer_narration = $transfer->transfer_narration;
                        $transfers_lineitems->transfer_id = $transfer->transfer_id;
                        $transfers_lineitems->transfer_entry_id = $transfer->transfer_id;
                        $transfer_lineitems[] = json_encode($transfers_lineitems);
                    }
                    $data['transfer_data'] = implode(',', $transfer_lineitems);
                }

                //----------------- Ad Charges -------------------
                $sell_ad_charges = $this->crud->get_row_by_id('sell_ad_charges', array('sell_id' => $sell_id));
                if(!empty($sell_ad_charges)){
                    $ad_charges_data = array();
                    foreach ($sell_ad_charges as $sell_ad_charge){
                        $sell_ad_charges_lineitems = new \stdClass();
                        $sell_ad_charges_lineitems->sell_ad_charges_id = $sell_ad_charge->sell_ad_charges_id;
                        $sell_ad_charges_lineitems->ad_id = $sell_ad_charge->ad_id;
                        $sell_ad_charges_lineitems->ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $sell_ad_charge->ad_id));
                        $sell_ad_charges_lineitems->ad_pcs = $sell_ad_charge->ad_pcs;
                        $sell_ad_charges_lineitems->ad_rate = $sell_ad_charge->ad_rate;
                        $sell_ad_charges_lineitems->ad_amount = $sell_ad_charge->ad_amount;
                        $sell_ad_charges_lineitems->ad_charges_remark = (isset($sell_ad_charge->ad_charges_remark) && !empty($sell_ad_charge->ad_charges_remark)) ? $sell_ad_charge->ad_charges_remark : '';
                        $ad_charges_data[] = json_encode($sell_ad_charges_lineitems);
                    }
                    $data['ad_charges_data'] = implode(',', $ad_charges_data);
                }

                //----------------- Adjust CR -------------------
                $sell_adjust_cr = $this->crud->get_row_by_id('sell_adjust_cr', array('sell_id' => $sell_id));
                if(!empty($sell_adjust_cr)){
                    $adjust_cr_data = array();
                    foreach ($sell_adjust_cr as $sell_adjust_cr_row){
                        $sell_adjust_cr_lineitems = new \stdClass();
                        $sell_adjust_cr_lineitems->sell_adjust_cr_id = $sell_adjust_cr_row->sell_adjust_cr_id;
                        $sell_adjust_cr_lineitems->adjust_to = $sell_adjust_cr_row->adjust_to;
                        $sell_adjust_cr_lineitems->adjust_to_name = ($sell_adjust_cr_row->adjust_to == '1') ? 'R Amt To C Amt' : 'C Amt To R Amt';
                        $sell_adjust_cr_lineitems->adjust_cr_amount = $sell_adjust_cr_row->adjust_cr_amount;
                        $adjust_cr_data[] = json_encode($sell_adjust_cr_lineitems);
                    }
                    $data['adjust_cr_data'] = implode(',', $adjust_cr_data);
                }
//                echo '<pre>'; print_r($data); exit;
                set_page('sell/sell_purchase_type_3', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            //----------------- Data -------------------
            if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"add")) {
                set_page('sell/sell_purchase_type_3', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function get_item_data($item_id){
        if(!empty($item_id)){
            $item_data = $this->crud->get_data_row_by_id('item_master', 'item_id', $item_id);
        }
        print json_encode($item_data);
        exit;
    }
    
    function get_wstg_from_account($account_id='',$item_id=''){
        $data = array();
        $account_data = $this->crud->get_row_by_id('party_item_details',array('account_id' => $account_id,'item_id'=>$item_id));
        if(!empty($account_data)){
            $data['account_data'] = $account_data[0]->wstg;
        } else {
            $item_data = $this->crud->get_val_by_id('item_master',$item_id,'item_id','default_wastage');
            $data['account_data'] = $item_data;
        }
        print json_encode($data);
        exit;
    }
    
    function get_account_old_balance($account_id){
        $data = array();
        if(!empty($account_id)){
            $account_data = $this->crud->get_row_by_id('account',array('account_id' => $account_id));
            if(!empty($account_data)){
                $data['account_address'] = (!empty($account_data[0]->account_address)) ? $account_data[0]->account_address : '-';
                $data['account_state'] = $account_data[0]->account_state;
                $data['gold_fine'] = $account_data[0]->gold_fine;
                $data['silver_fine'] = $account_data[0]->silver_fine;
                $data['amount'] = $account_data[0]->amount;
                $data['c_amount'] = $account_data[0]->c_amount;
                $data['r_amount'] = $account_data[0]->r_amount;
                $data['credit_limit'] = $account_data[0]->credit_limit;
                $data['balance_date'] = (strtotime($account_data[0]->balance_date) > 0?date("d-m-Y",strtotime($account_data[0]->balance_date)):'-');
                $effective_credit_limit = $account_data[0]->credit_limit - $account_data[0]->amount;
                if($effective_credit_limit < 0){
                    $effective_credit_limit = 0;
                }
                $data['effective_credit_limit'] = $effective_credit_limit;
                $data['account_remarks'] = $account_data[0]->account_remarks;
            }
        }
        print json_encode($data);
        exit;
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

            $old_sell_item_id_arr = array();
            $sell_items = $this->crud->get_all_with_where('sell_items', '', '', array('sell_id' => $post_data['sell_id']));
            if(!empty($sell_items)){
                foreach ($sell_items as $sell_item){
                    $old_sell_item_id_arr[] = $sell_item->sell_item_id;
                }
            }

            $old_pay_rec_id_arr = array();
            $old_pay_rec_data = $this->crud->get_all_with_where('payment_receipt', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_pay_rec_data)) {
                foreach ($old_pay_rec_data as $old_pay_rec_row) {
                    $old_pay_rec_id_arr[] = $old_pay_rec_row->pay_rec_id;
                }
            }

            $old_metal_pr_id_arr = array();
            $old_metal_pr_data = $this->crud->get_all_with_where('metal_payment_receipt', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_metal_pr_data)) {
                foreach ($old_metal_pr_data as $old_metal_pr_row) {
                    $old_metal_pr_id_arr[] = $old_metal_pr_row->metal_pr_id;
                }
            }

            $old_gold_id_arr = array();
            $old_gold_bhav_data = $this->crud->get_all_with_where('gold_bhav', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_gold_bhav_data)) {
                foreach ($old_gold_bhav_data as $old_gold_bhav_row) {
                    $old_gold_id_arr[] = $old_gold_bhav_row->gold_id;
                }
            }

            $old_silver_id_arr = array();
            $old_silver_bhav_data = $this->crud->get_all_with_where('silver_bhav', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_silver_bhav_data)) {
                foreach ($old_silver_bhav_data as $old_silver_bhav_row) {
                    $old_silver_id_arr[] = $old_silver_bhav_row->silver_id;
                }
            }

            $old_transfer_id_arr = array();
            $old_transfer_data = $this->crud->get_all_with_where('transfer', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_transfer_data)) {
                foreach ($old_transfer_data as $old_transfer_row) {
                    $old_transfer_id_arr[] = $old_transfer_row->transfer_id;
                }
            }

            $old_sell_ad_charges_id_arr = array();
            $old_sell_ad_charges_data = $this->crud->get_all_with_where('sell_ad_charges', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_sell_ad_charges_data)) {
                foreach ($old_sell_ad_charges_data as $old_sell_ad_charges_row) {
                    $old_sell_ad_charges_id_arr[] = $old_sell_ad_charges_row->sell_ad_charges_id;
                }
            }

            $old_sell_adjust_cr_id_arr = array();
            $old_sell_adjust_cr_data = $this->crud->get_all_with_where('sell_adjust_cr', '', '', array('sell_id' => $post_data['sell_id']));
            if (!empty($old_sell_adjust_cr_data)) {
                foreach ($old_sell_adjust_cr_data as $old_sell_adjust_cr_row) {
                    $old_sell_adjust_cr_id_arr[] = $old_sell_adjust_cr_row->sell_adjust_cr_id;
                }
            }

            // Decrese fine and amount in Account
            $this->update_main_account_balance_on_update($post_data['sell_id']);
            // Increase fine and amount in Department
            $this->update_department_balance_on_update($post_data['sell_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_sell_item_update($post_data['sell_id']);
            // Decrese fine in Item Stock on metal edit
            $this->update_stock_on_update_of_metal($post_data['sell_id']);
            // Decrese fine and amount in Transfer Account
            $this->update_account_balance_on_update($post_data['sell_id']);
            // Decrese fine and amount in MF loss Account
            $this->update_ad_to_mfloss_balance_on_update($post_data['sell_id']);
            // Decrese Charges Amount in MF loss Account
            $this->update_charges_amt_to_mfloss_balance_on_update($post_data['sell_id']);
            
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
            $result = $this->crud->update('sell', $update_arr, array('sell_id' => $post_data['sell_id']));
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Sell/purchase Updated Successfully');
                if(isset($post_data['saveform_clickbtn']) && $post_data['saveform_clickbtn'] == 'saveformwithprint'){
                    $this->session->set_flashdata('saveformwithprint', true);
                    $this->session->set_flashdata('saveformwithprinturl', 'sell_purchase_type_3/sell_print/'.$post_data['sell_id']);
                }
                
                // Increase fine and amount in Account
//                $this->crud->update('account', array('gold_fine' => $post_data['gold_fine_total'], 'silver_fine' => $post_data['silver_fine_total'], 'amount' => $post_data['amount_total']), array('account_id' => $post_data['account_id']));
                $update_accounts = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['account_id']));
                if(!empty($update_accounts)){
                    $acc_gold_fine = number_format((float) $update_accounts[0]->gold_fine, '3', '.', '') + number_format((float) $post_data['sell_gold_fine'], '3', '.', '');
                    $acc_gold_fine = number_format((float) $acc_gold_fine, '3', '.', '');
                    $acc_silver_fine = number_format((float) $update_accounts[0]->silver_fine, '3', '.', '') + number_format((float) $post_data['sell_silver_fine'], '3', '.', '');
                    $acc_silver_fine = number_format((float) $acc_silver_fine, '3', '.', '');
                    $acc_amount = number_format((float) $update_accounts[0]->amount, '3', '.', '') + number_format((float) $post_data['sell_amount'], '3', '.', '');
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $acc_c_amount = number_format((float) $update_accounts[0]->c_amount, '3', '.', '') + number_format((float) $post_data['bill_cr_c_amount'], '3', '.', '');
                    $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                    $acc_r_amount = number_format((float) $update_accounts[0]->r_amount, '3', '.', '') + number_format((float) $post_data['bill_cr_r_amount'], '3', '.', '');
                    $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
                    $this->crud->update('account', array('gold_fine' => $acc_gold_fine, 'silver_fine' => $acc_silver_fine, 'amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $post_data['account_id']));
                }
                
                // Decrease fine and amount in Department
                $update_departments = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['process_id']));
                if(!empty($update_departments)){
                    $depart_gold_fine = number_format((float) $update_departments[0]->gold_fine, '3', '.', '') - number_format((float) $post_data['depart_gold_fine'], '3', '.', '');
                    $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                    $depart_silver_fine = number_format((float) $update_departments[0]->silver_fine, '3', '.', '') - number_format((float) $post_data['depart_silver_fine'], '3', '.', '');
                    $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $post_data['process_id']));
                }
                
                // Insert sell_purchase_items
                if(!empty($line_items_data)){
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $old_sell_item_grwt = 0;
                        $insert_item['sell_id'] = $post_data['sell_id'];
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['type'] = $lineitem->type;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($lineitem->type == SELL_TYPE_SELL_ID && $line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                        $insert_item['stamp_id'] = $lineitem->stamp_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['spi_loss_for'] = $lineitem->spi_loss_for;
                        $insert_item['spi_loss'] = $lineitem->spi_loss;
                        $insert_item['touch_id'] = $lineitem->touch_id;
                        $insert_item['wstg'] = $lineitem->wstg;
                        $insert_item['fine'] = $lineitem->fine;
                        if(isset($lineitem->item_stock_rfid_id)){
                            $insert_item['item_stock_rfid_id'] = (isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)) ? $lineitem->item_stock_rfid_id : NULL;
                        }
                        if(isset($lineitem->rfid_number)){
                            $insert_item['rfid_number'] = (isset($lineitem->rfid_number) && !empty($lineitem->rfid_number)) ? $lineitem->rfid_number : NULL;
                        }
                        $insert_item['charges_amt'] = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $insert_item['spi_pcs'] = (isset($lineitem->spi_pcs) && !empty($lineitem->spi_pcs)) ? $lineitem->spi_pcs : 0;
                        $insert_item['spi_rate'] = (isset($lineitem->spi_rate) && !empty($lineitem->spi_rate)) ? $lineitem->spi_rate : 0;
                        $insert_item['spi_rate_of'] = (isset($lineitem->spi_rate_of) && !empty($lineitem->spi_rate_of)) ? $lineitem->spi_rate_of : 1;
                        $insert_item['spi_labour_on'] = (isset($lineitem->spi_labour_on) && !empty($lineitem->spi_labour_on)) ? $lineitem->spi_labour_on : 0;
                        $insert_item['labour_amount'] = (isset($lineitem->labour_amount) && !empty($lineitem->labour_amount)) ? $lineitem->labour_amount : 0;
                        $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                        $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
                        $insert_item['image'] = $lineitem->image;
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
                            if($lineitem_default_wstg == $lineitem->wstg){
                                $insert_item['wastage_change_approve'] = '0_0';
                            }
                        }
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
                            $old_sell_item_grwt = $this->crud->get_column_value_by_id('sell_items', 'grwt', array('sell_item_id' => $lineitem->sell_item_id));
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
                            $line_items_data[$key]->purchase_item_id = $sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));

                        // Delete lineitems less details
                        $this->crud->delete('sell_less_ad_details', array('sell_id' => $post_data['sell_id'], 'sell_item_id' => $sell_item_id));
                        // Insert lineitems less details
                        if(isset($lineitem->sell_less_ad_details) && !empty($lineitem->sell_less_ad_details)){
                            $sell_less_ad_details = json_decode($lineitem->sell_less_ad_details);
                            foreach ($sell_less_ad_details as $less_ad_detail){
                                $insert_less_ad_detail = array();
                                $insert_less_ad_detail['sell_id'] = $post_data['sell_id'];
                                $insert_less_ad_detail['sell_item_id'] = $sell_item_id;
                                $insert_less_ad_detail['less_ad_details_ad_id'] = $less_ad_detail->less_ad_details_ad_id;
                                $insert_less_ad_detail['less_ad_details_ad_pcs'] = (isset($less_ad_detail->less_ad_details_ad_pcs) && !empty($less_ad_detail->less_ad_details_ad_pcs)) ? $less_ad_detail->less_ad_details_ad_pcs : NULL;
                                $insert_less_ad_detail['less_ad_details_ad_weight'] = $less_ad_detail->less_ad_details_ad_weight;
                                $insert_less_ad_detail['created_at'] = $this->now_time;
                                $insert_less_ad_detail['created_by'] = $this->logged_in_id;
                                $insert_less_ad_detail['updated_at'] = $this->now_time;
                                $insert_less_ad_detail['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('sell_less_ad_details', $insert_less_ad_detail);
                            }
                        }

                        // Delete lineitems charges details
                        $this->crud->delete('sell_item_charges_details', array('sell_id' => $post_data['sell_id'], 'sell_item_id' => $sell_item_id));
                        // Insert lineitems charges details
                        if(isset($lineitem->sell_item_charges_details) && !empty($lineitem->sell_item_charges_details)){
                            $sell_item_charges_details = json_decode($lineitem->sell_item_charges_details);
                            foreach ($sell_item_charges_details as $less_ad_detail){
                                $insert_less_ad_detail = array();
                                $insert_less_ad_detail['sell_id'] = $post_data['sell_id'];
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

                        // Update rfid_number rfid_used status and rfid_created_grwt
                        if(isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)){
                            $this->crud->update('item_stock_rfid', array('rfid_used' => '1', 'to_relation_id' => $sell_item_id, 'to_module' => RFID_RELATION_MODULE_SELL), array('item_stock_rfid_id' => $lineitem->item_stock_rfid_id));
                            $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $lineitem->item_stock_rfid_id);
                            $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $item_stock_rfid->item_stock_id));
                            $new_rfid_created_grwt = $old_rfid_created_grwt + $old_sell_item_grwt - $item_stock_rfid->rfid_grwt;
                            $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $item_stock_rfid->item_stock_id));
                        }

                    }
                    $this->update_stock_on_sell_item_insert($line_items_data,$post_data['process_id']);
                    $this->update_charges_amt_to_mfloss_balance_on_insert($line_items_data, $post_data['sell_id']);
                }
                
                // Delete Deleted lineitems
                if (!empty($old_sell_item_id_arr)) {
                    // Update rfid_number rfid_used status and rfid_created_grwt
                    $before_delete_sell_items_data = $this->crud->get_where_in_result('sell_items', 'sell_item_id', $old_sell_item_id_arr);
                    if(!empty($before_delete_sell_items_data)){
                        foreach ($before_delete_sell_items_data as $before_delete_sell_item){
                            if(isset($before_delete_sell_item->item_stock_rfid_id) && !empty($before_delete_sell_item->item_stock_rfid_id)){
                                $check_item_stock_rfid = $this->crud->get_row_by_id('item_stock_rfid', array('real_rfid' => $before_delete_sell_item->rfid_number, 'rfid_used' => '0'));
                                if(empty($check_item_stock_rfid)){
                                    if($have_rfid == '1'){
                                        $this->crud->update('item_stock_rfid', array('rfid_used' => '0', 'to_relation_id' => NULL, 'to_module' => NULL), array('item_stock_rfid_id' => $before_delete_sell_item->item_stock_rfid_id));
                                        $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $before_delete_sell_item->item_stock_rfid_id);
                                        $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $item_stock_rfid->item_stock_id));
                                        $new_rfid_created_grwt = $old_rfid_created_grwt + $item_stock_rfid->rfid_grwt;
                                        $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $item_stock_rfid->item_stock_id));
                                    } else {
                                        $this->crud->update('item_stock_rfid', array('to_relation_id' => NULL, 'to_module' => RFID_RELATION_MODULE_SELL_DELETE), array('item_stock_rfid_id' => $before_delete_sell_item->item_stock_rfid_id));
                                    }
                                } else {
                                    $this->crud->update('item_stock_rfid', array('to_relation_id' => NULL, 'to_module' => RFID_RELATION_MODULE_SELL_DELETE), array('item_stock_rfid_id' => $before_delete_sell_item->item_stock_rfid_id));
                                }
                            }
                        }
                    }
//                    $old_sell_item_ids = implode(',', $old_sell_item_id_arr);
                    $this->crud->delete_where_in('sell_items', 'sell_item_id', $old_sell_item_id_arr);
                    // Delete lineitems less ad details
                    $this->crud->delete_where_in('sell_less_ad_details', 'sell_item_id', $old_sell_item_id_arr);
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

                        if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                            $depart_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $post_data['process_id']));
                            if($pay_rec->payment_receipt == '1'){
                                $depart_amount = $depart_amount - $pay_rec->amount;
                            } else {
                                $depart_amount = $depart_amount + $pay_rec->amount;
                            }
                            $depart_amount = number_format((float) $depart_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $post_data['process_id']));
                        }
                        if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                            $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $pay_rec->bank_id));
                            if($pay_rec->payment_receipt == '1'){
                                $bank_amount = $bank_amount - $pay_rec->amount;
                            } else {
                                $bank_amount = $bank_amount + $pay_rec->amount;
                            }
                            $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $pay_rec->bank_id));
                        }
                    }
                }
                // Delete Deleted Payment Receipt Data
                if (!empty($old_pay_rec_id_arr)) {
//                    $old_pay_rec_ids = implode(',', $old_pay_rec_id_arr);
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
                    }
                    $this->update_stock_on_insert_of_metal($metal_data, $post_data['process_id']);
                }
                // Delete Deleted Payment Receipt Data
                if (!empty($old_metal_pr_id_arr)) {
//                    $old_metal_pr_ids = implode(',', $old_metal_pr_id_arr);
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
                    }
                }
                // Delete Deleted Gold bhav Data
                if (!empty($old_gold_id_arr)) {
//                    $old_gold_ids = implode(',', $old_gold_id_arr);
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
                    }
                }
                // Delete Deleted Silver bhav Data
                if (!empty($old_silver_id_arr)) {
//                    $old_silver_ids = implode(',', $old_silver_id_arr);
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
                    }
                    $this->update_account_balance_on_insert($transfer_data, $post_data['sell_id']);
                }
                // Delete Deleted Transfer Data
                if (!empty($old_transfer_id_arr)) {
//                    $old_transfer_ids = implode(',', $old_transfer_id_arr);
                    $this->crud->delete_where_in('transfer', 'transfer_id', $old_transfer_id_arr);
                }

                // Insert Lineitem Ad Charges Data
                if(!empty($ad_lineitem_data)){
//                    echo '<pre>'; print_r($ad_lineitem_data); exit;
                    foreach ($ad_lineitem_data as $ad_lineitem){
                        $insert_ad_charges = array();
                        $insert_ad_charges['sell_id'] = $post_data['sell_id'];
                        $insert_ad_charges['ad_id'] = $ad_lineitem->ad_id;
                        $insert_ad_charges['ad_pcs'] = $ad_lineitem->ad_pcs;
                        $insert_ad_charges['ad_rate'] = $ad_lineitem->ad_rate;
                        $insert_ad_charges['ad_amount'] = $ad_lineitem->ad_amount;
                        $insert_ad_charges['c_amt'] = (isset($ad_lineitem->c_amt) && !empty($ad_lineitem->c_amt)) ? abs($ad_lineitem->c_amt) : 0;
                        $insert_ad_charges['r_amt'] = (isset($ad_lineitem->r_amt) && !empty($ad_lineitem->r_amt)) ? abs($ad_lineitem->r_amt) : 0;
                        $insert_ad_charges['ad_charges_remark'] = (isset($ad_lineitem->ad_charges_remark) && !empty($ad_lineitem->ad_charges_remark)) ? $ad_lineitem->ad_charges_remark : null;
                        $insert_ad_charges['updated_at'] = $this->now_time;
                        $insert_ad_charges['updated_by'] = $this->logged_in_id;
                        if(isset($ad_lineitem->sell_ad_charges_id) && !empty($ad_lineitem->sell_ad_charges_id)){
                            $this->crud->update('sell_ad_charges', $insert_ad_charges, array('sell_ad_charges_id' => $ad_lineitem->sell_ad_charges_id));
                            $old_sell_ad_charges_id_arr = array_diff($old_sell_ad_charges_id_arr, array($ad_lineitem->sell_ad_charges_id));
                        } else {
                            $insert_ad_charges['created_at'] = $this->now_time;
                            $insert_ad_charges['created_by'] = $this->logged_in_id;
                            $result = $this->crud->insert('sell_ad_charges', $insert_ad_charges);
                        }
                    }
                    $this->update_ad_to_mfloss_balance_on_insert($ad_lineitem_data, $post_data['sell_id']);
                }
                // Delete Deleted Lineitem Ad Charges Data
                if (!empty($old_sell_ad_charges_id_arr)) {
//                    $old_sell_ad_charges_ids = implode(',', $old_sell_ad_charges_id_arr);
                    $this->crud->delete_where_in('sell_ad_charges', 'sell_ad_charges_id', $old_sell_ad_charges_id_arr);
                }

                // Insert Lineitem Adjust CR Data
                if(!empty($adjust_cr_lineitem_data)){
//                    echo '<pre>'; print_r($adjust_cr_lineitem_data); exit;
                    foreach ($adjust_cr_lineitem_data as $adjust_cr_lineitem){
                        $insert_adjust_cr_charges = array();
                        $insert_adjust_cr_charges['sell_id'] = $post_data['sell_id'];
                        $insert_adjust_cr_charges['adjust_to'] = $adjust_cr_lineitem->adjust_to;
                        $insert_adjust_cr_charges['adjust_cr_amount'] = $adjust_cr_lineitem->adjust_cr_amount;
                        $insert_adjust_cr_charges['c_amt'] = (isset($adjust_cr_lineitem->c_amt) && !empty($adjust_cr_lineitem->c_amt)) ? abs($adjust_cr_lineitem->c_amt) : 0;
                        $insert_adjust_cr_charges['r_amt'] = (isset($adjust_cr_lineitem->r_amt) && !empty($adjust_cr_lineitem->r_amt)) ? abs($adjust_cr_lineitem->r_amt) : 0;
                        $insert_adjust_cr_charges['updated_at'] = $this->now_time;
                        $insert_adjust_cr_charges['updated_by'] = $this->logged_in_id;
                        if(isset($adjust_cr_lineitem->sell_adjust_cr_id) && !empty($adjust_cr_lineitem->sell_adjust_cr_id)){
                            $this->crud->update('sell_adjust_cr', $insert_adjust_cr_charges, array('sell_adjust_cr_id' => $adjust_cr_lineitem->sell_adjust_cr_id));
                            $old_sell_adjust_cr_id_arr = array_diff($old_sell_adjust_cr_id_arr, array($adjust_cr_lineitem->sell_adjust_cr_id));
                        } else {
                            $insert_adjust_cr_charges['created_at'] = $this->now_time;
                            $insert_adjust_cr_charges['created_by'] = $this->logged_in_id;
                            $result = $this->crud->insert('sell_adjust_cr', $insert_adjust_cr_charges);
                        }
                    }
                }
                // Delete Deleted Lineitem Adjust CR Data
                if (!empty($old_sell_adjust_cr_id_arr)) {
//                    $old_sell_adjust_cr_ids = implode(',', $old_sell_adjust_cr_id_arr);
                    $this->crud->delete_where_in('sell_adjust_cr', 'sell_adjust_cr_id', $old_sell_adjust_cr_id_arr);
                }
            }
            
        } else {
            $sell_no = 1;
            $no_for = SELL_NO_FOR_GENERAL_NO_ID;
            if (isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == 'sell') {
                $sell = $this->crud->get_max_number_where('sell', 'sell_no', array('no_for' => SELL_NO_FOR_ONLY_SELL_ID));
                $no_for = SELL_NO_FOR_ONLY_SELL_ID;
            } else if (isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == 'purchase') {
                $sell = $this->crud->get_max_number_where('sell', 'sell_no', array('no_for' => SELL_NO_FOR_ONLY_PURCHASE_ID));
                $no_for = SELL_NO_FOR_ONLY_PURCHASE_ID;
            } else if (isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == 'payment_receipt') {
                $sell = $this->crud->get_max_number_where('sell', 'sell_no', array('no_for' => SELL_NO_FOR_ONLY_PAYMENT_RECEIPT_ID));
                $no_for = SELL_NO_FOR_ONLY_PAYMENT_RECEIPT_ID;
            } else if (isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == 'metal_issue_receive') {
                $sell = $this->crud->get_max_number_where('sell', 'sell_no', array('no_for' => SELL_NO_FOR_ONLY_METAL_ISSUE_RECEIVE_ID));
                $no_for = SELL_NO_FOR_ONLY_METAL_ISSUE_RECEIVE_ID;
            } else {
                $sell = $this->crud->get_max_number_where('sell', 'sell_no', array('no_for' => SELL_NO_FOR_GENERAL_NO_ID));
            }
            if ($sell->sell_no > 0) {
                $sell_no = $sell->sell_no + 1;
            }
            $insert_arr = array();
            $insert_arr['sell_no'] = $sell_no;
            $insert_arr['no_for'] = $no_for;
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
            $insert_arr['entry_through'] = ENTRY_THROUGH_SELL_PURCHASE_TYPE_3;
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
                    $this->session->set_flashdata('saveformwithprinturl', 'sell_purchase_type_3/sell_print/'.$sell_id);
                }

//                $this->crud->update('account', array('gold_fine' => $post_data['gold_fine_total'], 'silver_fine' => $post_data['silver_fine_total'], 'amount' => $post_data['amount_total']), array('account_id' => $post_data['account_id']));
                $accounts = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['account_id']));
                if(!empty($accounts)){
                    $acc_gold_fine = number_format((float) $accounts[0]->gold_fine, '3', '.', '') + number_format((float) $post_data['sell_gold_fine'], '3', '.', '');
                    $acc_gold_fine = number_format((float) $acc_gold_fine, '3', '.', '');
                    $acc_silver_fine = number_format((float) $accounts[0]->silver_fine, '3', '.', '') + number_format((float) $post_data['sell_silver_fine'], '3', '.', '');
                    $acc_silver_fine = number_format((float) $acc_silver_fine, '3', '.', '');
                    $acc_amount = number_format((float) $accounts[0]->amount, '3', '.', '') + number_format((float) $post_data['sell_amount'], '3', '.', '');
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $acc_c_amount = number_format((float) $accounts[0]->c_amount, '3', '.', '') + number_format((float) $post_data['bill_cr_c_amount'], '3', '.', '');
                    $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                    $acc_r_amount = number_format((float) $accounts[0]->r_amount, '3', '.', '') + number_format((float) $post_data['bill_cr_r_amount'], '3', '.', '');
                    $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
                    $this->crud->update('account', array('gold_fine' => $acc_gold_fine, 'silver_fine' => $acc_silver_fine, 'amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $post_data['account_id']));
                }
                
                // Decrease fine and amount in Department
                $departments = $this->crud->get_row_by_id('account', array('account_id'=> $post_data['process_id']));
                if(!empty($departments)){
                    $depart_gold_fine = number_format((float) $departments[0]->gold_fine, '3', '.', '') - number_format((float) $post_data['depart_gold_fine'], '3', '.', '');
                    $depart_gold_fine = number_format((float) $depart_gold_fine, '3', '.', '');
                    $depart_silver_fine = number_format((float) $departments[0]->silver_fine, '3', '.', '') - number_format((float) $post_data['depart_silver_fine'], '3', '.', '');
                    $depart_silver_fine = number_format((float) $depart_silver_fine, '3', '.', '');
                    $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine), array('account_id' => $post_data['process_id']));
//                    $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $post_data['process_id']));
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
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if($lineitem->type == SELL_TYPE_SELL_ID && $line_items_data[$key]->stock_method == '2'){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        } 
                        $insert_item['category_id'] = $lineitem->category_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['li_narration'] = (isset($lineitem->li_narration) && !empty($lineitem->li_narration)) ? $lineitem->li_narration : NULL;
                        $insert_item['stamp_id'] = $lineitem->stamp_id;
                        $insert_item['grwt'] = $lineitem->grwt;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['spi_loss_for'] = $lineitem->spi_loss_for;
                        $insert_item['spi_loss'] = $lineitem->spi_loss;
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
                        $insert_item['spi_rate_of'] = (isset($lineitem->spi_rate_of) && !empty($lineitem->spi_rate_of)) ? $lineitem->spi_rate_of : 1;
                        $insert_item['spi_labour_on'] = (isset($lineitem->spi_labour_on) && !empty($lineitem->spi_labour_on)) ? $lineitem->spi_labour_on : 0;
                        $insert_item['labour_amount'] = (isset($lineitem->labour_amount) && !empty($lineitem->labour_amount)) ? $lineitem->labour_amount : 0;
                        $insert_item['amount'] = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $insert_item['c_amt'] = (isset($lineitem->c_amt) && !empty($lineitem->c_amt)) ? abs($lineitem->c_amt) : 0;
                        $insert_item['r_amt'] = (isset($lineitem->r_amt) && !empty($lineitem->r_amt)) ? abs($lineitem->r_amt) : 0;
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
                        $line_items_data[$key]->purchase_item_id = $sell_item_id;
                        
                        if(isset($lineitem->sell_less_ad_details) && !empty($lineitem->sell_less_ad_details)){
                            $sell_less_ad_details = json_decode($lineitem->sell_less_ad_details);
                            foreach ($sell_less_ad_details as $less_ad_detail){
                                $insert_less_ad_detail = array();
                                $insert_less_ad_detail['sell_id'] = $sell_id;
                                $insert_less_ad_detail['sell_item_id'] = $sell_item_id;
                                $insert_less_ad_detail['less_ad_details_ad_id'] = $less_ad_detail->less_ad_details_ad_id;
                                $insert_less_ad_detail['less_ad_details_ad_pcs'] = (isset($less_ad_detail->less_ad_details_ad_pcs) && !empty($less_ad_detail->less_ad_details_ad_pcs)) ? $less_ad_detail->less_ad_details_ad_pcs : NULL;
                                $insert_less_ad_detail['less_ad_details_ad_weight'] = $less_ad_detail->less_ad_details_ad_weight;
                                $insert_less_ad_detail['created_at'] = $this->now_time;
                                $insert_less_ad_detail['created_by'] = $this->logged_in_id;
                                $insert_less_ad_detail['updated_at'] = $this->now_time;
                                $insert_less_ad_detail['updated_by'] = $this->logged_in_id;
                                $result = $this->crud->insert('sell_less_ad_details', $insert_less_ad_detail);
                            }
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
                    
                        // Update rfid_number rfid_used status and rfid_created_grwt
                        if(isset($lineitem->item_stock_rfid_id) && !empty($lineitem->item_stock_rfid_id)){
                            $this->crud->update('item_stock_rfid', array('rfid_used' => '1', 'to_relation_id' => $sell_item_id, 'to_module' => RFID_RELATION_MODULE_SELL), array('item_stock_rfid_id' => $lineitem->item_stock_rfid_id));
                            $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $lineitem->item_stock_rfid_id);
                            $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $item_stock_rfid->item_stock_id));
                            $new_rfid_created_grwt = $old_rfid_created_grwt - $item_stock_rfid->rfid_grwt;
                            $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $item_stock_rfid->item_stock_id));
                        }
                        
                    }
                    $this->update_stock_on_sell_item_insert($line_items_data,$post_data['process_id']);
                    $this->update_charges_amt_to_mfloss_balance_on_insert($line_items_data, $sell_id);
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

                        if($pay_rec->cash_cheque == '1'){ // Update Department Amount
                            $depart_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $post_data['process_id']));
                            if($pay_rec->payment_receipt == '1'){
                                $depart_amount = $depart_amount - $pay_rec->amount;
                            } else {
                                $depart_amount = $depart_amount + $pay_rec->amount;
                            }
                            $depart_amount = number_format((float) $depart_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $depart_amount), array('account_id' => $post_data['process_id']));
                        }
                        if($pay_rec->cash_cheque == '2'){ // Update Bank Amount
                            $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $pay_rec->bank_id));
                            if($pay_rec->payment_receipt == '1'){
                                $bank_amount = $bank_amount - $pay_rec->amount;
                            } else {
                                $bank_amount = $bank_amount + $pay_rec->amount;
                            }
                            $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                            $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $pay_rec->bank_id));
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
                    }
                    $this->update_stock_on_insert_of_metal($metal_data,$post_data['process_id']);
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
                    }
                    $this->update_account_balance_on_insert($transfer_data, $sell_id);
                }
                
                if(!empty($ad_lineitem_data)){
//                    echo '<pre>'; print_r($ad_lineitem_data); exit;
                    foreach ($ad_lineitem_data as $ad_lineitem){
                        $insert_ad_charges = array();
                        $insert_ad_charges['sell_id'] = $sell_id;
                        $insert_ad_charges['ad_id'] = $ad_lineitem->ad_id;
                        $insert_ad_charges['ad_pcs'] = $ad_lineitem->ad_pcs;
                        $insert_ad_charges['ad_rate'] = $ad_lineitem->ad_rate;
                        $insert_ad_charges['ad_amount'] = $ad_lineitem->ad_amount;
                        $insert_ad_charges['c_amt'] = (isset($ad_lineitem->c_amt) && !empty($ad_lineitem->c_amt)) ? abs($ad_lineitem->c_amt) : 0;
                        $insert_ad_charges['r_amt'] = (isset($ad_lineitem->r_amt) && !empty($ad_lineitem->r_amt)) ? abs($ad_lineitem->r_amt) : 0;
                        $insert_ad_charges['ad_charges_remark'] = (isset($ad_lineitem->ad_charges_remark) && !empty($ad_lineitem->ad_charges_remark)) ? $ad_lineitem->ad_charges_remark : null;
                        $insert_ad_charges['created_at'] = $this->now_time;
                        $insert_ad_charges['created_by'] = $this->logged_in_id;
                        $insert_ad_charges['updated_at'] = $this->now_time;
                        $insert_ad_charges['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('sell_ad_charges', $insert_ad_charges);
                    }
                    $this->update_ad_to_mfloss_balance_on_insert($ad_lineitem_data, $sell_id);
                }

                // Insert Lineitem Adjust CR Data
                if(!empty($adjust_cr_lineitem_data)){
//                    echo '<pre>'; print_r($adjust_cr_lineitem_data); exit;
                    foreach ($adjust_cr_lineitem_data as $adjust_cr_lineitem){
                        $insert_adjust_cr_charges = array();
                        $insert_adjust_cr_charges['sell_id'] = $sell_id;
                        $insert_adjust_cr_charges['adjust_to'] = $adjust_cr_lineitem->adjust_to;
                        $insert_adjust_cr_charges['adjust_cr_amount'] = $adjust_cr_lineitem->adjust_cr_amount;
                        $insert_adjust_cr_charges['c_amt'] = (isset($adjust_cr_lineitem->c_amt) && !empty($adjust_cr_lineitem->c_amt)) ? abs($adjust_cr_lineitem->c_amt) : 0;
                        $insert_adjust_cr_charges['r_amt'] = (isset($adjust_cr_lineitem->r_amt) && !empty($adjust_cr_lineitem->r_amt)) ? abs($adjust_cr_lineitem->r_amt) : 0;
                        $insert_adjust_cr_charges['created_at'] = $this->now_time;
                        $insert_adjust_cr_charges['created_by'] = $this->logged_in_id;
                        $insert_adjust_cr_charges['updated_at'] = $this->now_time;
                        $insert_adjust_cr_charges['updated_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('sell_adjust_cr', $insert_adjust_cr_charges);
                    }
                }
            }
            if(!empty($post_data['send_sms'])){
                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['account_id']);
                
                $order_status = $this->crud->get_id_by_val('new_order', 'order_status_id', 'order_id', $order_id);
                if($order_status == '3' && !empty($mobile_no)){
                    $sms = SEND_ORDER_COMPLETE_SMS;
                    $account_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $post_data['account_id']);
                    $order_no = $this->crud->get_id_by_val('new_order', 'order_no', 'order_id', $order_id);
                    $vars = array(
                        '{{party_name}}' => $account_name,
                        '{{order_no}}' => $order_no,
                    );
                    $sms = strtr($sms, $vars);
                    $this->applib->send_sms($mobile_no, $sms);
                }
                
                $amt = $post_data['amount_total'];
                if($amt[0] == '-'){
                    $type = substr($amt, 1);
                    $amount = $type.'Cr';
                } else {
                    $amount = $amt.'Dr';
                }
                $gold_fine_total = $post_data['gold_fine_total'];
                if($gold_fine_total[0] == '-'){
                    $gold_type = substr($gold_fine_total, 1);
                    $gold_fine = $gold_type.'Cr';
                } else {
                    $gold_fine = $gold_fine_total.'Dr';
                }
                $silver_fine_total = $post_data['silver_fine_total'];
                if($silver_fine_total[0] == '-'){
                    $silver_type = substr($silver_fine_total, 1);
                    $silver_fine = $silver_type.'Cr';
                } else {
                    $silver_fine = $silver_fine_total.' Dr';
                }
                if($post_data['delivery_type'] == '1'){
                    $delivery_type = 'Delivered';
                } else {
                    $delivery_type = 'ready, but Not delivered';
                }
                if (!empty($mobile_no)) {
                    $sms = SEND_SELL_CREATE_SMS;
                    $vars = array(
                        '{{amount}}' => $amount,
                        '{{gold_balance}}' => $gold_fine,
                        '{{silver_balance}}' => $silver_fine,
                        '{{delivery_type}}' => $delivery_type,
                    );
                    $sms = strtr($sms, $vars);
                    $this->applib->send_sms($mobile_no, $sms);
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function update_stock_on_sell_item_insert($lineitem_data='',$department_id=''){
        if (!empty($lineitem_data)) {
//            echo '<pre>'; print_r($lineitem_data); exit;
            foreach ($lineitem_data as $lineitem) {
                
                if($lineitem->type == '1'){
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $wstg = $this->crud->get_id_by_val('sell_items', 'wstg', 'sell_item_id', $lineitem->purchase_sell_item_id);
                    } else {
                        $wstg = $this->crud->get_min_value('sell_items', 'wstg');
                        $wstg = $wstg->wstg;
                    }
                } else {
                    $wstg = (!empty($lineitem->wstg)) ? $lineitem->wstg : 0;
                }
                $lineitem->fine = $lineitem->net_wt *($lineitem->touch_id + $wstg) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2) , 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                if((isset($lineitem->stock_method) && $lineitem->stock_method == '2') && $lineitem->type != SELL_TYPE_SELL_ID){
                    if(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $department_id;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->touch_id;
                        $insert_item_stock['grwt'] = $lineitem->grwt;
                        $insert_item_stock['less'] = $lineitem->less;
                        $insert_item_stock['ntwt'] = $lineitem->net_wt;
                        $insert_item_stock['fine'] = $lineitem->fine;
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_item_id;
                        if($lineitem->type == '2'){
                            $insert_item_stock['stock_type'] = '1';
                        } elseif ($lineitem->type == '3'){
                            $insert_item_stock['stock_type'] = '2';
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('item_stock', $insert_item_stock);
                    } else {
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->sell_item_id) && !empty($lineitem->sell_item_id)){
                            $purchase_item_id = $lineitem->sell_item_id;
                        } 
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id_for_pe = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                        if(!empty($exist_item_id_for_pe)){
                            if($lineitem->type == '1'){
                                $current_stock_less = number_format((float) $exist_item_id_for_pe[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id_for_pe[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id_for_pe[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id_for_pe[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else {
                                $current_stock_less = number_format((float) $exist_item_id_for_pe[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id_for_pe[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id_for_pe[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id_for_pe[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
                            }
                            $update_item_stock = array();
                            $update_item_stock['ntwt'] = $current_stock_ntwt;
                            $update_item_stock['fine'] = $current_stock_fine;
                            $update_item_stock['grwt'] = $current_stock_grwt;
                            $update_item_stock['less'] = $current_stock_less;
                            $update_item_stock['updated_at'] = $this->now_time;
                            $update_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id_for_pe[0]->item_stock_id));
                        } else {
                            $insert_item_stock = array();
                            $insert_item_stock['department_id'] = $department_id;
                            $insert_item_stock['category_id'] = $lineitem->category_id;
                            $insert_item_stock['item_id'] = $lineitem->item_id;
                            $insert_item_stock['tunch'] = $lineitem->touch_id;
                            $insert_item_stock['grwt'] = $lineitem->grwt;
                            $insert_item_stock['less'] = $lineitem->less;
                            $insert_item_stock['ntwt'] = $lineitem->net_wt;
                            $insert_item_stock['fine'] = $lineitem->fine;
                            $insert_item_stock['purchase_sell_item_id'] = $purchase_item_id;
                            if($lineitem->type == '2'){
                                $insert_item_stock['stock_type'] = '1';
                            } elseif ($lineitem->type == '3'){
                                $insert_item_stock['stock_type'] = '2';
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
                        } elseif(isset($lineitem->sell_item_id) && !empty($lineitem->sell_item_id)){
                            $purchase_item_id = $lineitem->sell_item_id;
                        } elseif(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                            $purchase_item_id = $lineitem->purchase_item_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                    if(!empty($exist_item_id)){
                        if($lineitem->type == '1'){
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->grwt, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                        } else {
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->grwt, '3', '.', '');
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
                        if($lineitem->type == '1'){
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
                        if($lineitem->type == '2'){
                            $insert_item_stock['stock_type'] = '1';
                        } elseif ($lineitem->type == '3'){
                            $insert_item_stock['stock_type'] = '2';
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
    //                    echo '<pre>'; print_r($insert_item_stock); exit;
                        $this->crud->insert('item_stock', $insert_item_stock);
                    }
                }
            }
        }
    }
    
    function update_stock_on_insert_of_metal($metal_data='',$department_id=''){
        if (!empty($metal_data)) {
            foreach ($metal_data as $metal) {
                $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $metal->metal_item_id);
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $category_id));
                if($category_group_id == 1){
                    $metal->metal_fine = number_format(round($metal->metal_fine, 2), 3, '.', '');
                } else {
                    $metal->metal_fine = number_format(round($metal->metal_fine, 1), 3, '.', '');
                }
                $where_metal_stock_array = array('department_id' => $department_id, 'category_id' => $category_id, 'item_id' => $metal->metal_item_id, 'tunch' => $metal->metal_tunch);
                $exist_metal_item_id = $this->crud->get_row_by_id('item_stock',$where_metal_stock_array);
                if(!empty($exist_metal_item_id)){
                    if($metal->metal_payment_receipt == '1'){
                        $current_metal_stock_grwt = number_format((float) $exist_metal_item_id[0]->grwt, '3', '.', '') - number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_ntwt = number_format((float) $exist_metal_item_id[0]->ntwt, '3', '.', '') - number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_fine = number_format((float) $exist_metal_item_id[0]->fine, '3', '.', '') - number_format((float) $metal->metal_fine, '3', '.', '');
                    } else {
                        $current_metal_stock_grwt = number_format((float) $exist_metal_item_id[0]->grwt, '3', '.', '') + number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_ntwt = number_format((float) $exist_metal_item_id[0]->ntwt, '3', '.', '') + number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_fine = number_format((float) $exist_metal_item_id[0]->fine, '3', '.', '') + number_format((float) $metal->metal_fine, '3', '.', '');
                    }
                    $update_metal_item_stock = array();
                    $update_metal_item_stock['grwt'] = $current_metal_stock_grwt;
                    $update_metal_item_stock['ntwt'] = $current_metal_stock_ntwt;
                    $update_metal_item_stock['fine'] = $current_metal_stock_fine;
                    $update_metal_item_stock['updated_at'] = $this->now_time;
                    $update_metal_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_metal_item_stock, array('item_stock_id' => $exist_metal_item_id[0]->item_stock_id));
                } else { 
                    if($metal->metal_payment_receipt == '1'){
                        $metal->metal_grwt = $this->zero_value - number_format((float) $metal->metal_grwt, '3', '.', '');
                        $metal->metal_fine = $this->zero_value - number_format((float) $metal->metal_fine, '3', '.', '');
                    }
                    $insert_metal_item_stock = array();
                    $insert_metal_item_stock['department_id'] = $department_id;
                    $insert_metal_item_stock['category_id'] = $category_id;
                    $insert_metal_item_stock['item_id'] = $metal->metal_item_id;
                    $insert_metal_item_stock['tunch'] = $metal->metal_tunch;
                    $insert_metal_item_stock['grwt'] = $metal->metal_grwt;
                    $insert_metal_item_stock['less'] = '0';
                    $insert_metal_item_stock['ntwt'] = $metal->metal_grwt;
                    $insert_metal_item_stock['fine'] = $metal->metal_fine;
                    $insert_metal_item_stock['created_at'] = $this->now_time;
                    $insert_metal_item_stock['created_by'] = $this->logged_in_id;
                    $insert_metal_item_stock['updated_at'] = $this->now_time;
                    $insert_metal_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('item_stock', $insert_metal_item_stock);
                }
            }
        }
    }
    
    function update_stock_on_sell_item_update($sell_id =''){
//        echo $sell_id; exit;
        $sell_items = $this->crud->get_all_with_where('sell_items', '', '', array('sell_id' => $sell_id));
//        echo '<pre>'; print_r($sell_items); exit;
        if(!empty($sell_items)){
            foreach ($sell_items as $lineitem){
                
                if($lineitem->type == '1'){
                    if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                        $wstg = $this->crud->get_id_by_val('sell_items', 'wstg', 'sell_item_id', $lineitem->purchase_sell_item_id);
                    } else {
                        $wstg = $this->crud->get_min_value('sell_items', 'wstg');
                        $wstg = $wstg->wstg;
                    }
                } else {
                    $wstg = (!empty($lineitem->wstg)) ? $lineitem->wstg : 0;
                }
                $lineitem->fine = $lineitem->net_wt *($lineitem->touch_id + $wstg) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                // On Sell item And delete Sell : add order lot item status to Pending
                if(isset($lineitem->order_lot_item_id) && !empty($lineitem->order_lot_item_id)){
                    $this->crud->update('order_lot_item', array('item_status_id' => '1'), array('order_lot_item_id' => $lineitem->order_lot_item_id));
                }
                
                $department_id = $this->crud->get_column_value_by_id('sell', 'process_id', array('sell_id' => $lineitem->sell_id));
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if($stock_method == '2'){
                    if(!empty($lineitem->purchase_sell_item_id)){
                        $sell_item_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $sell_item_id = $lineitem->sell_item_id;
                    }
                    $where_metal_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $sell_item_id);
                } else {
                    $where_metal_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                }
                
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_metal_stock_array);
//                echo $this->db->last_query(); exit;
                if(!empty($exist_item_id)){
                    if($lineitem->type == '1'){
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
    
    function update_stock_on_update_of_metal($sell_id=''){
        $metal_payment_receipt = $this->crud->get_all_with_where('metal_payment_receipt', '', '', array('sell_id' => $sell_id));
        if(!empty($metal_payment_receipt)){
            foreach ($metal_payment_receipt as $metal){
                $department_id = $this->crud->get_column_value_by_id('sell', 'process_id', array('sell_id' => $metal->sell_id));
                $where_metal_stock_array = array('department_id' => $department_id, 'category_id' => $metal->metal_category_id, 'item_id' => $metal->metal_item_id, 'tunch' => $metal->metal_tunch);
                $exist_metal_item_id = $this->crud->get_row_by_id('item_stock',$where_metal_stock_array);
                if(!empty($exist_metal_item_id)){
//                    echo '<pre>'; print_r($metal->metal_payment_receipt); exit;
                    
                    $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $metal->metal_category_id));
                    if($category_group_id == 1){
                        $metal->metal_fine = number_format(round($metal->metal_fine, 2), 3, '.', '');
                    } else {
                        $metal->metal_fine = number_format(round($metal->metal_fine, 1), 3, '.', '');
                    }
                    
                    if($metal->metal_payment_receipt == '1'){
                        $current_metal_stock_grwt = number_format((float) $exist_metal_item_id[0]->grwt, '3', '.', '') + number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_ntwt = number_format((float) $exist_metal_item_id[0]->ntwt, '3', '.', '') + number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_fine = number_format((float) $exist_metal_item_id[0]->fine, '3', '.', '') + number_format((float) $metal->metal_fine, '3', '.', '');
                    } else {
                        $current_metal_stock_grwt = number_format((float) $exist_metal_item_id[0]->grwt, '3', '.', '') - number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_ntwt = number_format((float) $exist_metal_item_id[0]->ntwt, '3', '.', '') - number_format((float) $metal->metal_grwt, '3', '.', '');
                        $current_metal_stock_fine = number_format((float) $exist_metal_item_id[0]->fine, '3', '.', '') - number_format((float) $metal->metal_fine, '3', '.', '');
                    }
                    $update_metal_item_stock = array();
                    $update_metal_item_stock['grwt'] = $current_metal_stock_grwt;
                    $update_metal_item_stock['ntwt'] = $current_metal_stock_ntwt;
                    $update_metal_item_stock['fine'] = $current_metal_stock_fine;
                    $update_metal_item_stock['updated_at'] = $this->now_time;
                    $update_metal_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_metal_item_stock, array('item_stock_id' => $exist_metal_item_id[0]->item_stock_id));
//                    echo '<pre>'; print_r
                }
            }
        }
    }
    
    function update_account_balance_on_insert($transfer_data='', $sell_id){
        if(!empty($transfer_data)){
//            $process_id = $this->crud->get_id_by_val('sell', 'process_id', 'sell_id', $sell_id);
            foreach ($transfer_data as $transfer){
                $exist_transfer_account_id = $this->crud->get_row_by_id('account',array('account_id' => $transfer->transfer_account_id));
                if(!empty($exist_transfer_account_id)){
                    if($transfer->naam_jama == '1'){
                        $transfer_gold_fine = number_format((float) $exist_transfer_account_id[0]->gold_fine, '3', '.', '') + number_format((float) $transfer->transfer_gold, '3', '.', '');
                        $transfer_silver_fine = number_format((float) $exist_transfer_account_id[0]->silver_fine, '3', '.', '') + number_format((float) $transfer->transfer_silver, '3', '.', '');
                        $transfer_amount = number_format((float) $exist_transfer_account_id[0]->amount, '2', '.', '') + number_format((float) $transfer->transfer_amount, '2', '.', '');
                        $transfer_c_amount = number_format((float) $exist_transfer_account_id[0]->c_amount, '2', '.', '') + number_format((float) $transfer->transfer_amount, '2', '.', '');
                    } else {
                        $transfer_gold_fine = number_format((float) $exist_transfer_account_id[0]->gold_fine, '3', '.', '') - number_format((float) $transfer->transfer_gold, '3', '.', '');
                        $transfer_silver_fine = number_format((float) $exist_transfer_account_id[0]->silver_fine, '3', '.', '') - number_format((float) $transfer->transfer_silver, '3', '.', '');
                        $transfer_amount = number_format((float) $exist_transfer_account_id[0]->amount, '2', '.', '') - number_format((float) $transfer->transfer_amount, '2', '.', '');
                        $transfer_c_amount = number_format((float) $exist_transfer_account_id[0]->c_amount, '2', '.', '') - number_format((float) $transfer->transfer_amount, '2', '.', '');
                    }
                    $transfer_gold_fine = number_format((float) $transfer_gold_fine, '3', '.', '');
                    $transfer_silver_fine = number_format((float) $transfer_silver_fine, '3', '.', '');
                    $transfer_amount = number_format((float) $transfer_amount, '2', '.', '');
                    $transfer_c_amount = number_format((float) $transfer_c_amount, '2', '.', '');
                    $this->crud->update('account', array('gold_fine' => $transfer_gold_fine, 'silver_fine' => $transfer_silver_fine, 'amount' => $transfer_amount, 'c_amount' => $transfer_c_amount), array('account_id' => $transfer->transfer_account_id));
                
                    // Increase/Decrease fine and amount in Department
//                    $departments = $this->crud->get_row_by_id('account', array('account_id'=> $process_id));
//                    if(!empty($departments)){
//                        if($transfer->naam_jama == '1'){
//                            $depart_gold_fine = $departments[0]->gold_fine - $transfer->transfer_gold;
//                            $depart_silver_fine = $departments[0]->silver_fine - $transfer->transfer_silver;
//                            $depart_amount = $departments[0]->amount - $transfer->transfer_amount;
//                        } else {
//                            $depart_gold_fine = $departments[0]->gold_fine + $transfer->transfer_gold;
//                            $depart_silver_fine = $departments[0]->silver_fine + $transfer->transfer_silver;
//                            $depart_amount = $departments[0]->amount + $transfer->transfer_amount;
//                        }
//                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine, 'amount' => $depart_amount), array('account_id' => $process_id));
//                    }
                }
            }
        }
    }
    
    function update_account_balance_on_update($sell_id=''){
        $transfers = $this->crud->get_all_with_where('transfer', '', '', array('sell_id' => $sell_id));
        if(!empty($transfers)){
//            $process_id = $this->crud->get_id_by_val('sell', 'process_id', 'sell_id', $sell_id);
            foreach ($transfers as $transfer){
                $exist_transfer_account_id = $this->crud->get_row_by_id('account',array('account_id' => $transfer->transfer_account_id));
                if(!empty($exist_transfer_account_id)){
                    if($transfer->naam_jama == '1'){
                        $transfer_gold_fine = number_format((float) $exist_transfer_account_id[0]->gold_fine, '3', '.', '') - number_format((float) $transfer->transfer_gold, '3', '.', '');
                        $transfer_silver_fine = number_format((float) $exist_transfer_account_id[0]->silver_fine, '3', '.', '') - number_format((float) $transfer->transfer_silver, '3', '.', '');
                        $transfer_amount = number_format((float) $exist_transfer_account_id[0]->amount, '2', '.', '') - number_format((float) $transfer->transfer_amount, '2', '.', '');
                        $transfer_c_amount = number_format((float) $exist_transfer_account_id[0]->c_amount, '2', '.', '') - number_format((float) $transfer->transfer_amount, '2', '.', '');
                    } else {
                        $transfer_gold_fine = number_format((float) $exist_transfer_account_id[0]->gold_fine, '3', '.', '') + number_format((float) $transfer->transfer_gold, '3', '.', '');
                        $transfer_silver_fine = number_format((float) $exist_transfer_account_id[0]->silver_fine, '3', '.', '') + number_format((float) $transfer->transfer_silver, '3', '.', '');
                        $transfer_amount = number_format((float) $exist_transfer_account_id[0]->amount, '2', '.', '') + number_format((float) $transfer->transfer_amount, '2', '.', '');
                        $transfer_c_amount = number_format((float) $exist_transfer_account_id[0]->c_amount, '2', '.', '') + number_format((float) $transfer->transfer_amount, '2', '.', '');
                    }
                    $transfer_gold_fine = number_format((float) $transfer_gold_fine, '3', '.', '');
                    $transfer_silver_fine = number_format((float) $transfer_silver_fine, '3', '.', '');
                    $transfer_amount = number_format((float) $transfer_amount, '2', '.', '');
                    $transfer_c_amount = number_format((float) $transfer_c_amount, '2', '.', '');
                    $this->crud->update('account', array('gold_fine' => $transfer_gold_fine, 'silver_fine' => $transfer_silver_fine, 'amount' => $transfer_amount, 'c_amount' => $transfer_c_amount), array('account_id' => $transfer->transfer_account_id));
                    
                    // Increase/Decrease fine and amount in Department
//                    $departments = $this->crud->get_row_by_id('account', array('account_id'=> $process_id));
//                    if(!empty($departments)){
//                        if($transfer->naam_jama == '1'){
//                            $depart_gold_fine = $departments[0]->gold_fine + $transfer->transfer_gold;
//                            $depart_silver_fine = $departments[0]->silver_fine + $transfer->transfer_silver;
//                            $depart_amount = $departments[0]->amount + $transfer->transfer_amount;
//                        } else {
//                            $depart_gold_fine = $departments[0]->gold_fine - $transfer->transfer_gold;
//                            $depart_silver_fine = $departments[0]->silver_fine - $transfer->transfer_silver;
//                            $depart_amount = $departments[0]->amount - $transfer->transfer_amount;
//                        }
//                        $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine, 'amount' => $depart_amount), array('account_id' => $process_id));
//                    }
                }
            }
        }
    }
    
    function update_ad_to_mfloss_balance_on_insert($ad_lineitem_data='', $sell_id){
        if(!empty($ad_lineitem_data)){
            foreach ($ad_lineitem_data as $ad_lineitem){
                if(isset($ad_lineitem->ad_amount) && !empty($ad_lineitem->ad_amount)){
                    $exist_mfloss_account_id = $this->crud->get_row_by_id('account',array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    if(!empty($exist_mfloss_account_id)){
                        $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') - number_format((float) $ad_lineitem->ad_amount, '3', '.', '');
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    }
                }
            }
        }
    }
    
    function update_ad_to_mfloss_balance_on_update($sell_id){
        $sell_ad_charges = $this->crud->get_all_with_where('sell_ad_charges', '', '', array('sell_id' => $sell_id));
        if(!empty($sell_ad_charges)){
            foreach ($sell_ad_charges as $ad_lineitem){
                if(isset($ad_lineitem->ad_amount) && !empty($ad_lineitem->ad_amount)){
                    $exist_mfloss_account_id = $this->crud->get_row_by_id('account',array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    if(!empty($exist_mfloss_account_id)){
                        $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') + number_format((float) $ad_lineitem->ad_amount, '3', '.', '');
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    }
                }
            }
        }
    }
    
    function update_charges_amt_to_mfloss_balance_on_insert($line_items_data='', $sell_id){
        if (!empty($line_items_data)) {
            foreach ($line_items_data as $lineitem) {
                if(isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)){
                    $exist_mfloss_account_id = $this->crud->get_row_by_id('account',array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    if(!empty($exist_mfloss_account_id)){
                        if($lineitem->type == SELL_TYPE_SELL_ID){
                            $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') - number_format((float) $lineitem->charges_amt, '3', '.', '');
                        } else {
                            $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') + number_format((float) $lineitem->charges_amt, '3', '.', '');
                        }
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    }
                }
                if(PACKAGE_FOR == 'manek'){
                    $process_id = $this->crud->get_column_value_by_id('sell', 'process_id', array('sell_id' => $sell_id));
                    $exist_department_id = $this->crud->get_row_by_id('account',array('account_id' => $process_id));
                    if(!empty($exist_department_id)){
                        $charges_amt = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $amount = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $amount = $amount - $charges_amt;
                        if($lineitem->type == SELL_TYPE_SELL_ID){
                            $new_amount = number_format((float) $exist_department_id[0]->amount, '3', '.', '') - number_format((float) $amount, '3', '.', '');
                        } else {
                            $new_amount = number_format((float) $exist_department_id[0]->amount, '3', '.', '') + number_format((float) $amount, '3', '.', '');
                        }
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => $process_id));
                    }
                }
            }
        }
    }
    
    function update_charges_amt_to_mfloss_balance_on_update($sell_id){
        $sell_items = $this->crud->get_all_with_where('sell_items', '', '', array('sell_id' => $sell_id));
        if(!empty($sell_items)){
            foreach ($sell_items as $lineitem){
                if(isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)){
                    $exist_mfloss_account_id = $this->crud->get_row_by_id('account',array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    if(!empty($exist_mfloss_account_id)){
                        if($lineitem->type == SELL_TYPE_SELL_ID){
                            $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') + number_format((float) $lineitem->charges_amt, '3', '.', '');
                        } else {
                            $new_amount = number_format((float) $exist_mfloss_account_id[0]->amount, '3', '.', '') - number_format((float) $lineitem->charges_amt, '3', '.', '');
                        }
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                    }
                }
                if(PACKAGE_FOR == 'manek'){
                    $process_id = $this->crud->get_column_value_by_id('sell', 'process_id', array('sell_id' => $sell_id));
                    $exist_department_id = $this->crud->get_row_by_id('account',array('account_id' => $process_id));
                    if(!empty($exist_department_id)){
                        $charges_amt = (isset($lineitem->charges_amt) && !empty($lineitem->charges_amt)) ? $lineitem->charges_amt : 0;
                        $amount = (isset($lineitem->amount) && !empty($lineitem->amount)) ? $lineitem->amount : 0;
                        $amount = $amount - $charges_amt;
                        if($lineitem->type == SELL_TYPE_SELL_ID){
                            $new_amount = number_format((float) $exist_department_id[0]->amount, '3', '.', '') + number_format((float) $amount, '3', '.', '');
                        } else {
                            $new_amount = number_format((float) $exist_department_id[0]->amount, '3', '.', '') - number_format((float) $amount, '3', '.', '');
                        }
                        $new_amount = number_format((float) $new_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $new_amount), array('account_id' => $process_id));
                    }
                }
            }
        }
    }
    
    function update_department_balance_on_update($sell_id=''){
        $minus_gold_fine = 0;
        $minus_silver_fine = 0;
        $total_amount = 0;
        $sell_data = $this->crud->get_row_by_id('sell', array('sell_id'=> $sell_id));
        $sell_items_data = $this->crud->get_row_by_id('sell_items', array('sell_id'=> $sell_id));
        if(!empty($sell_items_data)){
            foreach ($sell_items_data as $sell_item){
                if($sell_item->spi_rate_of == '2' && $sell_item->spi_rate != 0){ } else {
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_item->category_id));
                if($sell_item->type == SELL_TYPE_SELL_ID){
                    if($category_group_id == 1){
                        if(PACKAGE_FOR == 'manek'){
                            if(isset($sell_item->spi_rate) && empty($sell_item->spi_rate)){
                                $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') + number_format((float) $sell_item->fine, 3, '.', '');
                            }
                        } else {
                            $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') + number_format((float) $sell_item->fine, 3, '.', '');
                        }
                    } else {
                        if(PACKAGE_FOR == 'manek'){
                            if(isset($sell_item->spi_rate) && empty($sell_item->spi_rate)){
                                $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') + number_format((float) $sell_item->fine, 3, '.', '');
                            }
                        } else {
                            $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') + number_format((float) $sell_item->fine, 3, '.', '');
                        }
                    }
                } else {
                    if($category_group_id == 1){
                        if(PACKAGE_FOR == 'manek'){
                            if(isset($sell_item->spi_rate) && empty($sell_item->spi_rate)){
                                $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') - number_format((float) $sell_item->fine, 3, '.', '');
                            }
                        } else {
                            $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') - number_format((float) $sell_item->fine, 3, '.', '');
                        }
                    } else {
                        if(PACKAGE_FOR == 'manek'){
                            if(isset($sell_item->spi_rate) && empty($sell_item->spi_rate)){
                                $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') - number_format((float) $sell_item->fine, 3, '.', '');
                            }
                        } else {
                            $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') - number_format((float) $sell_item->fine, 3, '.', '');
                        }
                    }
                }
                }
            }
        }
        $metal_payment_receipt_data = $this->crud->get_row_by_id('metal_payment_receipt', array('sell_id'=> $sell_id));
        if(!empty($metal_payment_receipt_data)){
            foreach ($metal_payment_receipt_data as $metal_pay_rec_data){
                $metal_category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $metal_pay_rec_data->metal_category_id));
                if($metal_pay_rec_data->metal_payment_receipt == '1'){
                    if($metal_category_group_id == 1){
                        $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') + number_format($metal_pay_rec_data->metal_fine, 3, '.', '');
                    } else {
                        $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') + number_format($metal_pay_rec_data->metal_fine, 3, '.', '');
                    }
                } else {
                    if($metal_category_group_id == 1){
                        $minus_gold_fine = number_format((float) $minus_gold_fine, 3, '.', '') - number_format((float) $metal_pay_rec_data->metal_fine, 3, '.', '');
                    } else {
                        $minus_silver_fine = number_format((float) $minus_silver_fine, 3, '.', '') - number_format((float) $metal_pay_rec_data->metal_fine, 3, '.', '');
                    }
                }
            }
        }
        
        $payment_receipt_data = $this->crud->get_row_by_id('payment_receipt', array('sell_id'=> $sell_id));
        if(!empty($payment_receipt_data)){
            foreach ($payment_receipt_data as $payment_rec_data){
                if($payment_rec_data->cash_cheque == '2'){ // if payment_receipt type Cheque
                    $bank_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $payment_rec_data->bank_id));
                    if($payment_rec_data->payment_receipt == '1'){
                        $bank_amount = $bank_amount + $payment_rec_data->amount;
                    } else {
                        $bank_amount = $bank_amount - $payment_rec_data->amount;
                    }
                    $bank_amount = number_format((float) $bank_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $bank_amount), array('account_id' => $payment_rec_data->bank_id));
                } else {
                    if($payment_rec_data->payment_receipt == '1'){
                        $total_amount = $total_amount + $payment_rec_data->amount;
                    } else {
                        $total_amount = $total_amount - $payment_rec_data->amount;
                    }
                }
            }
        }
        $departments = $this->crud->get_row_by_id('account', array('account_id'=> $sell_data[0]->process_id));
        if(!empty($departments)){
            $depart_gold_fine = number_format($departments[0]->gold_fine, '3', '.', '') + number_format($minus_gold_fine, '3', '.', '');
            $depart_gold_fine = number_format($depart_gold_fine, '3', '.', '');
            $depart_silver_fine = number_format($departments[0]->silver_fine, '3', '.', '') + number_format($minus_silver_fine, '3', '.', '');
            $depart_silver_fine = number_format($depart_silver_fine, '3', '.', '');
            $depart_amount = number_format($departments[0]->amount, '3', '.', '') + number_format($total_amount, '3', '.', '');
            $depart_amount = number_format($depart_amount, '2', '.', '');
            $this->crud->update('account', array('gold_fine' => $depart_gold_fine, 'silver_fine' => $depart_silver_fine, 'amount' => $depart_amount), array('account_id' => $sell_data[0]->process_id));
        }
    }
    
    function update_main_account_balance_on_update($sell_id=''){
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $total_amount = 0;
        $sell = $this->crud->get_row_by_id('sell', array('sell_id'=> $sell_id));
        $exist_account_id = $this->crud->get_row_by_id('account',array('account_id' => $sell[0]->account_id));
        if(!empty($exist_account_id)){
            $total_gold_fine = number_format((float) $exist_account_id[0]->gold_fine, '3', '.', '') - number_format((float) $sell[0]->total_gold_fine, '3', '.', '');
            $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '');
            $total_silver_fine = number_format((float) $exist_account_id[0]->silver_fine, '3', '.', '') - number_format((float) $sell[0]->total_silver_fine, '3', '.', '');
            $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '');
            $total_amount = number_format((float) $exist_account_id[0]->amount, '3', '.', '') - number_format((float) $sell[0]->total_amount, '3', '.', '');
            if(PACKAGE_FOR == 'manek') {
                $sell[0]->discount_amount = (!empty($sell[0]->discount_amount)) ? $sell[0]->discount_amount : 0;
                $total_amount = $total_amount + $sell[0]->discount_amount;
            }
            $total_amount = number_format((float) $total_amount, '2', '.', '');
            $total_c_amount = number_format((float) $exist_account_id[0]->c_amount, '3', '.', '') - number_format((float) $sell[0]->total_c_amount, '3', '.', '');
            $total_c_amount = number_format((float) $total_c_amount, '2', '.', '');
            $total_r_amount = number_format((float) $exist_account_id[0]->r_amount, '3', '.', '') - number_format((float) $sell[0]->total_r_amount, '3', '.', '');
            $total_r_amount = number_format((float) $total_r_amount, '2', '.', '');
            $this->crud->update('account', array('gold_fine' => $total_gold_fine, 'silver_fine' => $total_silver_fine, 'amount' => $total_amount, 'c_amount' => $total_c_amount, 'r_amount' => $total_r_amount), array('account_id' => $sell[0]->account_id));
        }
    }
    
    function splist($param1 = '') { 
        if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"view")) {
            if(in_array($param1,array("sell","purchase")) && $this->sell_purchase_difference) {
                $sell_purchase = $param1;
                $view = '';
                if($sell_purchase == "sell") {
                    $page_label = "Sell";
                    $entry_page_url = base_url("sell_purchase_type_3/add/sell");
                } else {
                    $page_label = "Purchase";
                    $entry_page_url = base_url("sell_purchase_type_3/add/purchase");
                }
            } else if ($param1 == 'payment_receipt') {
                $sell_purchase = $param1;
                $page_label = "Payment Receipt";
                $entry_page_url = base_url("sell_purchase_type_3/add/payment_receipt");
            } else if ($param1 == 'metal_issue_receive') {
                $sell_purchase = $param1;
                $page_label = "Metal Issue Receive";
                $entry_page_url = base_url("sell_purchase_type_3/add/metal_issue_receive");
            } else {
                $sell_purchase = "sell_purchase";
                $view = $param1;
                $page_label = "Sell/Purchase";
                $entry_page_url = base_url("sell_purchase_type_3/add");
            }

            $data = array();

            $data['page_label'] = $page_label;
            $data['entry_page_url'] = $entry_page_url;
            $data['sell_purchase'] = $sell_purchase;

            if(!empty($view)){
                $data['view_not'] = $view;
            }
            set_page('sell/sell_purchase_type_3_list', $data);
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

        $config['wheres'][] = array('column_name' => 's.entry_through', 'column_value' => ENTRY_THROUGH_SELL_PURCHASE_TYPE_3);
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
            $config['custom_where'] .= ' AND s.no_for = ' . SELL_NO_FOR_ONLY_SELL_ID . ' ';
        } else if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
            $config['custom_where'] .= ' AND s.no_for = ' . SELL_NO_FOR_ONLY_PURCHASE_ID . ' ';
        } else if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "payment_receipt") {
            $config['custom_where'] .= ' AND s.no_for = ' . SELL_NO_FOR_ONLY_PAYMENT_RECEIPT_ID . ' ';
        } else if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "metal_issue_receive") {
            $config['custom_where'] .= ' AND s.no_for = ' . SELL_NO_FOR_ONLY_METAL_ISSUE_RECEIVE_ID . ' ';
        } else {
            $config['custom_where'] .= ' AND s.no_for = ' . SELL_NO_FOR_GENERAL_NO_ID . ' ';
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
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';                        
                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';

                    } else {
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }

                    if($role_delete){
                        $action .= '<a href="javascript:void(0);" class="delete_sell" data-sell_id="'.$sell->sell_id.'" data-href="' . base_url('sell_purchase_type_3/delete_sell/' . $sell->sell_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else {
                    if(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "sell") {
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/sell/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    
                    } elseif(isset($post_data['sell_purchase']) && $post_data['sell_purchase'] == "purchase") {
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/purchase/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    
                    } else {
                        $action .= '<a href="' . base_url("sell_purchase_type_3/add/" . $sell->sell_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                    
                }
            }
            $action .= '<a href="' . base_url("sell_purchase_type_3/sell_print/" . $sell->sell_id) . '" target="_blank" title="Sell/Purchase Print" alt="Sell/Purchase Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            if(PACKAGE_FOR != 'manek') {
                $action .= '<a href="' . base_url("sell_purchase_type_3/sell_print/" . $sell->sell_id . '/isimage/') . '" target="_blank" title="Sell/Purchase Print as a Image" alt="Sell/Purchase Print as a Image"><span class="glyphicon glyphicon-picture" style="color : #419bf4">&nbsp</a>';
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
                $row[] = '<a href="javascript:void(0);" class="update_delivery_type" data-href="' . base_url('sell_purchase_type_3/update_delivery_type/' . $sell->sell_id) . '"><input type="checkbox"  class="icheckbox_flat-blue check_delivery" value="'.$sell->sell_id.'"></a>';
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
    
    function audit_status_sell() {
        $return = array();

        if(isset($_POST['audit_status_sell_id']) && !empty($_POST['audit_status_sell_id']) && isset($_POST['audit_status']) && !empty($_POST['audit_status'])){
            $result = $this->crud->update('sell', array('audit_status' => $_POST['audit_status']), array('sell_id' => $_POST['audit_status_sell_id']));
            if ($result) {
                $this->crud->update('payment_receipt', array('audit_status' => $_POST['audit_status']), array('sell_id' => $_POST['audit_status_sell_id']));
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
    
    function update_delivery_type($sell_id = ''){
        if(!empty($sell_id)){
            $this->crud->update('sell', array('delivery_type' => '1'), array('sell_id' => $sell_id));
            
//            $sell = $this->crud->get_row_by_id('sell', array('sell_id' => $sell_id));
//            if(!empty($sell)){
//                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $sell[0]->account_id);
//                $amt = $sell[0]->amount_total;
//                if($amt[0] == '-'){
//                    $type = substr($amt, 1);
//                    $amount = $type.'Cr';
//                } else {
//                    $amount = $amt.'Dr';
//                }
//                $gold_fine_total = $sell[0]->gold_fine_total;
//                if($gold_fine_total[0] == '-'){
//                    $gold_type = substr($gold_fine_total, 1);
//                    $gold_fine = $gold_type.'Cr';
//                } else {
//                    $gold_fine = $gold_fine_total.'Dr';
//                }
//                $silver_fine_total = $sell[0]->silver_fine_total;
//                if($silver_fine_total[0] == '-'){
//                    $silver_type = substr($silver_fine_total, 1);
//                    $silver_fine = $silver_type.'Cr';
//                } else {
//                    $silver_fine = $silver_fine_total.' Dr';
//                }
//                $delivery_type = 'Delivered';
//                if (!empty($mobile_no)) {
//                    $sms = SEND_SELL_CREATE_SMS;
//                    $vars = array(
//                        '{{amount}}' => $amount,
//                        '{{gold_balance}}' => $gold_fine,
//                        '{{silver_balance}}' => $silver_fine,
//                        '{{delivery_type}}' => $delivery_type,
//                    );
//                    $sms = strtr($sms, $vars);
//                    $this->applib->send_sms($mobile_no, $sms);
//                }
//            }
        }
    }

    function sell_item_datatable() {
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
                    $action .= '<a href="' . base_url("sell_purchase_type_3/add/" . $sell_detail->sell_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
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
            $row[] = number_format($sell_detail->less, 3, '.', '');
            $row[] = number_format($sell_detail->net_wt, 3, '.', '');
            $row[] = $sell_detail->touch_id;
            $row[] = number_format($sell_detail->wstg, 3, '.', '');
            $row[] = number_format($sell_detail->fine, 3, '.', '');
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
    
    function pay_rec_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'payment_receipt pr';
        $config['select'] = 'pr.*, IF(pr.payment_receipt = 1, "Payment" ,"Receipt") AS payment_receipt, IF(pr.cash_cheque = 1, "Cash" ,"Cheque") AS cash_cheque, a.bank_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pr.bank_id', 'join_type' => 'left');
        $config['column_order'] = array('pr.payment_receipt', 'pr.cash_cheque', 'a.bank_name', 'pr.amount', 'pr.narration');
        $config['column_search'] = array('IF(pr.payment_receipt = 1, "Payment" ,"Receipt")', 'IF(pr.cash_cheque = 1, "Cash" ,"Cheque")', 'a.bank_name', 'pr.amount', 'pr.narration');
        $config['order'] = array('pr.pay_rec_id' => 'desc');
        if (isset($post_data['pay_rec_id']) && !empty($post_data['pay_rec_id'])) {
            $config['wheres'][] = array('column_name' => 'pr.sell_id', 'column_value' => $post_data['pay_rec_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->payment_receipt;
            $row[] = $sell_detail->cash_cheque;
            $row[] = $sell_detail->bank_name;
            $row[] = $sell_detail->amount;
            $row[] = $sell_detail->narration;
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
    
    function metal_pr_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'metal_payment_receipt m';
        $config['select'] = 'm.*, IF(m.metal_payment_receipt = 1, "Issue" ,"Receive") AS payment_receipt, im.item_name';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = m.metal_item_id', 'join_type' => 'left');
//        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= m.metal_category_id', 'join_type' => 'left');
        $config['column_order'] = array('m.metal_payment_receipt', 'im.item_name', 'm.metal_grwt', 'm.metal_tunch', 'm.metal_fine','m.metal_narration');
        $config['column_search'] = array('IF(m.metal_payment_receipt = 1, "Issue" ,"Receive")', 'im.item_name', 'm.metal_grwt', 'm.metal_tunch', 'm.metal_fine','m.metal_narration');
        $config['order'] = array('m.metal_pr_id' => 'desc');
        if (isset($post_data['metal_pr_id']) && !empty($post_data['metal_pr_id'])) {
            $config['wheres'][] = array('column_name' => 'm.sell_id', 'column_value' => $post_data['metal_pr_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->payment_receipt;
            $row[] = $sell_detail->item_name;
            $row[] = number_format($sell_detail->metal_grwt, 3, '.', '');
            $row[] = $sell_detail->metal_tunch;
            $row[] = number_format($sell_detail->metal_fine, 3, '.', '');
            $row[] = $sell_detail->metal_narration;
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
    
    function gold_bhav_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'gold_bhav g';
        $config['select'] = 'g.*, IF(g.gold_sale_purchase = 1, "Sell" ,"Purchase") AS gold_sale_purchase';
        $config['column_order'] = array('g.gold_sale_purchase', 'g.gold_weight', 'g.gold_rate', 'g.gold_value', 'g.gold_narration');
        $config['column_search'] = array('IF(g.gold_sale_purchase = 1, "Sell" ,"Purchase")', 'g.gold_weight', 'g.gold_rate', 'g.gold_value', 'g.gold_narration');
        $config['order'] = array('g.gold_id' => 'desc');
        if (isset($post_data['gold_id']) && !empty($post_data['gold_id'])) {
            $config['wheres'][] = array('column_name' => 'g.sell_id', 'column_value' => $post_data['gold_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $gold_detail) {
            $row = array();
            $row[] = $gold_detail->gold_sale_purchase;
            $row[] = number_format($gold_detail->gold_weight, 3, '.', '');
            $row[] = $gold_detail->gold_rate;
            $row[] = number_format($gold_detail->gold_value, 3, '.', '');
            $row[] = $gold_detail->gold_narration;
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
    
    function silver_bhav_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'silver_bhav g';
        $config['select'] = 'g.*, IF(g.silver_sale_purchase = 1, "Sell" ,"Purchase") AS silver_sale_purchase';
        $config['column_order'] = array('g.silver_sale_purchase', 'g.silver_weight', 'g.silver_rate', 'g.silver_value', 'g.silver_narration');
        $config['column_search'] = array('IF(g.silver_sale_purchase = 1, "Sale" ,"Purchase")', 'g.silver_weight', 'g.silver_rate', 'g.silver_value', 'g.silver_narration');
        $config['order'] = array('g.silver_id' => 'desc');
        if (isset($post_data['silver_id']) && !empty($post_data['silver_id'])) {
            $config['wheres'][] = array('column_name' => 'g.sell_id', 'column_value' => $post_data['silver_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $silver_detail) {
            $row = array();
            $row[] = $silver_detail->silver_sale_purchase;
            $row[] = number_format($silver_detail->silver_weight, 3, '.', '');
            $row[] = $silver_detail->silver_rate;
            $row[] = number_format($silver_detail->silver_value, 3, '.', '');
            $row[] = $silver_detail->silver_narration;
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
    
    function transfer_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'transfer t';
        $config['select'] = 't.*, IF(t.naam_jama = 1, "Naam" ,"Jama") AS naam_jama, a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.transfer_account_id', 'join_type' => 'left');
        $config['column_order'] = array('t.naam_jama', 'a.account_name', 't.transfer_gold', 't.transfer_silver', 't.transfer_amount', 't.transfer_narration');
        $config['column_search'] = array('IF(t.naam_jama = 1, "Naam" ,"Jama")', 'a.account_name', 't.transfer_gold', 't.transfer_silver', 't.transfer_amount', 't.transfer_narration');
        $config['order'] = array('t.transfer_id' => 'desc');
        if (isset($post_data['transfer_id']) && !empty($post_data['transfer_id'])) {
            $config['wheres'][] = array('column_name' => 't.sell_id', 'column_value' => $post_data['transfer_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $transfer_detail) {
            $row = array();
            $row[] = $transfer_detail->naam_jama;
            $row[] = $transfer_detail->account_name;
            $row[] = number_format($transfer_detail->transfer_gold, 3, '.', '');
            $row[] = number_format($transfer_detail->transfer_silver, 3, '.', '');
            $row[] = $transfer_detail->transfer_amount;
            $row[] = $transfer_detail->transfer_narration;
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
    
    function check_rfid_in_lineitems_or_not($id = '') {
        $where_array = array('sell_id' => $id);
        $sell = $this->crud->get_row_by_id('sell', $where_array);
        $return = array();
        $return['have_rfid'] = '0';
        if(!empty($sell)){
            $sell_items = $this->crud->get_row_by_id('sell_items', $where_array);
            if(!empty($sell_items)){
                foreach($sell_items as $sell_item){
                    if(isset($sell_item->item_stock_rfid_id) && !empty($sell_item->item_stock_rfid_id)){
                        $return['have_rfid'] = '1';
                    }
                }
            }
        }
        echo json_encode($return);
        exit;
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
//                $total_gold_fine = 0;
//                $total_silver_fine = 0;
//                $total_amount = 0;
//                $exist_account_id = $this->crud->get_row_by_id('account',array('account_id' => $sell[0]->account_id));
//                if(!empty($exist_account_id)){
//                    $total_gold_fine = (float) $exist_account_id[0]->gold_fine - (float) $sell[0]->total_gold_fine;
//                    $total_silver_fine = (float) $exist_account_id[0]->silver_fine - (float) $sell[0]->total_silver_fine;
//                    $total_amount = (float) $exist_account_id[0]->amount - (float) $sell[0]->total_amount;
//                    $this->crud->update('account', array('gold_fine' => $total_gold_fine, 'silver_fine' => $total_silver_fine, 'amount' => $total_amount), array('account_id' => $sell[0]->account_id));
//                }
                
                // Increase fine and amount in Department And Account
                $this->update_main_account_balance_on_update($id);
                $this->update_department_balance_on_update($id);
                $this->update_account_balance_on_update($id);
                $this->update_stock_on_update_of_metal($id);
                $this->update_stock_on_sell_item_update($id);
                $this->update_ad_to_mfloss_balance_on_update($id);
                $this->update_charges_amt_to_mfloss_balance_on_update($id);
                $this->crud->delete('sell_ad_charges', $where_array);
                $this->crud->delete('transfer', $where_array);
                $this->crud->delete('silver_bhav', $where_array);
                $this->crud->delete('gold_bhav', $where_array);
                $this->crud->delete('metal_payment_receipt', $where_array);
                $this->crud->delete('payment_receipt', $where_array);
                $this->crud->delete('sell_items', $where_array);
                // Delete lineitems less ad details
                $this->crud->delete('sell_less_ad_details', $where_array);
                // Delete sell item charges details
                $this->crud->delete('sell_item_charges_details', $where_array);
                $this->crud->delete('sell', $where_array);
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function get_category_group($item_id = ''){
        $category_id = $this->crud->get_id_by_val('item_master', 'category_id', 'item_id', $item_id);
        $category_group = $this->crud->get_id_by_val('category', 'category_group_id', 'category_id', $category_id);
        print json_decode($category_group);
        exit;
    }
        
    function get_temp_path_image() {
        $data = '';
//        echo "<pre>"; print_r($_FILES); exit;
        if (isset($_FILES['file_upload']['name']) && !empty($_FILES['file_upload']['name'])) {
            $file_element_name = 'file_upload';
            $config['upload_path'] = './uploads/sell_item_photo/';
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
            $data = $file_data['file_name'];
        }
        
        print $data;
        exit;
    }
    
    public function cluster($order_update_id=''){
        $query = $this->db->query("CALL `update_order_status_id`(".$order_update_id.")");
        $result = $query->result();
        $query->next_result(); 
        $query->free_result();
        return $result;
    }
    
    function get_purchase_to_sell_pending_item(){
        $item_id = $_POST['item_id'];
        $department_id = $_POST['department_id'];
        
        // Get Old Sell Purchase Items ids
        $old_sell_item_ids = array();
        $old_purchase_sell_item_ids = array();
        if(isset($_POST['sell_id'])){
            $old_sell_item_ids = $this->crud->get_sell_item_ids($_POST['sell_id']);
            $old_purchase_sell_item_ids = $this->crud->get_purchase_sell_item_ids($_POST['sell_id']);
            $get_old_sell_item_ids = $this->crud->get_columns_val_by_where('sell_items','sell_item_id',array('sell_id' => $_POST['sell_id']));
        }
        
        // Get Old Stock Transfer Items ids
        $old_stock_transfer_detail_ids = array();
        $old_stock_transfer_detail_purchase_sell_item_ids = array();
        if(isset($_POST['stock_transfer_id'])){
            $old_stock_transfer_detail_ids = $this->crud->get_stock_transfer_detail_ids($_POST['stock_transfer_id']);
            $old_stock_transfer_detail_purchase_sell_item_ids = $this->crud->get_stock_transfer_detail_purchase_sell_item_ids($_POST['stock_transfer_id']);
            $get_old_stock_transfer_detail_ids = $this->crud->get_columns_val_by_where('stock_transfer_detail','transfer_detail_id',array('stock_transfer_id' => $_POST['stock_transfer_id']));
        }
        
        // Get Old Manufacture Issue Receive Items ids
        $old_issue_receive_item_ids = array();
        $old_issue_receive_purchase_sell_item_ids = array();
        if(isset($_POST['ir_id'])){
            $old_issue_receive_item_ids = $this->crud->get_issue_receive_item_ids($_POST['ir_id']);
            $old_issue_receive_purchase_sell_item_ids = $this->crud->get_issue_receive_purchase_sell_item_ids($_POST['ir_id']);
            $get_old_issue_receive_item_ids = $this->crud->get_columns_val_by_where('issue_receive_details','ird_id',array('ir_id' => $_POST['ir_id']));
        }
        
        // Get Old Manufacture Hand Made Items ids
        $old_manu_hand_made_item_ids = array();
        $old_manu_hand_made_purchase_sell_item_ids = array();
        if(isset($_POST['mhm_id'])){
            $old_manu_hand_made_item_ids = $this->crud->get_manu_hand_made_item_ids($_POST['mhm_id']);
            $old_manu_hand_made_purchase_sell_item_ids = $this->crud->get_manu_hand_made_purchase_sell_item_ids($_POST['mhm_id']);
            $get_old_manu_hand_made_item_ids = $this->crud->get_columns_val_by_where('manu_hand_made_details','mhm_detail_id',array('mhm_id' => $_POST['mhm_id']));
        }
        
        // Get Old Machine Chain Items ids
        $old_machine_chain_item_ids = array();
        $old_machine_chain_purchase_sell_item_ids = array();
        if(isset($_POST['machine_chain_id'])){
            $old_machine_chain_item_ids = $this->crud->get_machine_chain_item_ids($_POST['machine_chain_id']);
            $old_machine_chain_purchase_sell_item_ids = $this->crud->get_machine_chain_purchase_sell_item_ids($_POST['machine_chain_id']);
            $get_old_machine_chain_item_ids = $this->crud->get_columns_val_by_where('machine_chain_details','machine_chain_detail_id',array('machine_chain_id' => $_POST['machine_chain_id']));
        }
        $data = array();
        $sell_items = $this->crud->get_purchase_to_sell_items($department_id, $item_id);
        $item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $item_id));
        $less_allow = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $item_id));
        $sell_lineitems = array();
        if(!empty($sell_items)){
            foreach($sell_items as $sell_item){
                $pts_items = new \stdClass();
                $pts_items->total_grwt = 0;
                $pts_items->total_less = 0;
                
                // Check and Get Sell Purchase total grwt
//                if(isset($get_old_sell_item_id) && !empty($get_old_sell_item_id)){
                if(isset($get_old_sell_item_ids) && !empty($get_old_sell_item_ids) && in_array($sell_item->sell_item_id, $get_old_sell_item_ids)){
//                if(isset($get_old_sell_item_id) && !empty($get_old_sell_item_id) && $sell_item->sell_item_id == $get_old_sell_item_id){
                    foreach ($get_old_sell_item_ids as $get_old_sell_item_id){
                        $pts_grwt_item_wise = $this->crud->get_pts_total_grwt_less($get_old_sell_item_id, $sell_item->stock_type, $old_sell_item_ids);
                        $pts_items = $pts_items + $pts_grwt_item_wise;
                    }
                    
                } else {
                    $pts_items = $this->crud->get_pts_total_grwt_less($sell_item->sell_item_id, $sell_item->stock_type, $old_sell_item_ids);
//                    $pts_items->total_grwt = (float) $pts_items->total_grwt - (float) $sell_item->grwt;
//                    $pts_items->total_less = $pts_items->total_less - (float) $sell_item->less;
                }
                if(empty( (array) $pts_items)){
                    $pts_items->total_grwt = 0;
                    $pts_items->total_less = 0;
                }
                
                // Check and Get Stock Transfer total grwt
                $ptst_items = new \stdClass();
                if(isset($get_old_stock_transfer_detail_ids) && !empty($get_old_stock_transfer_detail_ids) && in_array($sell_item->sell_item_id, $get_old_stock_transfer_detail_ids)){
                    foreach ($get_old_stock_transfer_detail_ids as $get_old_stock_transfer_detail_id){
                        $ptst_grwt_item_wise = $this->crud->get_ptst_total_grwt_less($get_old_stock_transfer_detail_id, $sell_item->stock_type, $old_stock_transfer_detail_ids);
                        $ptst_items = $ptst_items + $ptst_grwt_item_wise;
                    }
                } else {
                    $ptst_items = $this->crud->get_ptst_total_grwt_less($sell_item->sell_item_id, $sell_item->stock_type, $old_stock_transfer_detail_ids);
                } 
                if(empty( (array) $ptst_items)){
                    $ptst_items->total_grwt = 0;
                    $ptst_items->total_less = 0;
                }
                
                // Check and Get Manufacture Issue Receive total grwt
                $ptir_items = new \stdClass();
                if(isset($get_old_issue_receive_item_ids) && !empty($get_old_issue_receive_item_ids) && in_array($sell_item->sell_item_id, $get_old_issue_receive_item_ids)){
                    foreach ($get_old_issue_receive_item_ids as $get_old_issue_receive_item_id){
                        $ptir_grwt_item_wise = $this->crud->get_ptir_total_grwt_less($get_old_issue_receive_item_id, $sell_item->stock_type, $old_issue_receive_item_ids);
                        $ptir_items = $ptir_items + $ptir_grwt_item_wise;
                    }
                } else {
                    $ptir_items = $this->crud->get_ptir_total_grwt_less($sell_item->sell_item_id, $sell_item->stock_type, $old_issue_receive_item_ids);
                }
                
                if(empty( (array) $ptir_items)){
                    $ptir_items->total_grwt = 0;
                    $ptir_items->total_less = 0;
                }
                
                // Check and Get Manufacture Hand Made total grwt
                $ptmhm_items = new \stdClass();
                if(isset($get_old_manu_hand_made_item_ids) && !empty($get_old_manu_hand_made_item_ids) && in_array($sell_item->sell_item_id, $get_old_manu_hand_made_item_ids)){
                    foreach ($get_old_manu_hand_made_item_ids as $get_old_manu_hand_made_item_id){
                        $ptmhm_grwt_item_wise = $this->crud->get_ptmhm_total_grwt_less($get_old_manu_hand_made_item_id, $sell_item->stock_type, $old_manu_hand_made_item_ids);
                        $ptmhm_items = $ptmhm_items + $ptmhm_grwt_item_wise;
                    }
                } else {
                    $ptmhm_items = $this->crud->get_ptmhm_total_grwt_less($sell_item->sell_item_id, $sell_item->stock_type, $old_manu_hand_made_item_ids);
                }
                if(empty( (array) $ptmhm_items)){
                    $ptmhm_items->total_grwt = 0;
                    $ptmhm_items->total_less = 0;
                }
                
                // Check and Get Machine Chain total grwt
                $ptmc_items = new \stdClass();
                if(isset($get_old_machine_chain_item_ids) && !empty($get_old_machine_chain_item_ids) && in_array($sell_item->sell_item_id, $get_old_machine_chain_item_ids)){
                    foreach ($get_old_machine_chain_item_ids as $get_old_machine_chain_item_id){
                        $ptmc_grwt_item_wise = $this->crud->get_ptmc_total_grwt_less($get_old_machine_chain_item_id, $sell_item->stock_type, $old_machine_chain_item_ids);
                        $ptmc_items = $ptmc_items + $ptmc_grwt_item_wise;
                    }
                } else {
                    $ptmc_items = $this->crud->get_ptmc_total_grwt_less($sell_item->sell_item_id, $sell_item->stock_type, $old_machine_chain_item_ids);
                }
                if(empty( (array) $ptmc_items)){
                    $ptmc_items->total_grwt = 0;
                    $ptmc_items->total_less = 0;
                }
                
                // Check If total grwt not_in array then set 0
                if(in_array($sell_item->sell_item_id, $old_purchase_sell_item_ids)){ } else {
                    $pts_items->total_grwt = 0;
                    $pts_items->total_less = 0;
                }
                if(in_array($sell_item->sell_item_id, $old_stock_transfer_detail_purchase_sell_item_ids)){ } else {
                    $ptst_items->total_grwt = 0;
                    $ptst_items->total_less = 0;
                }
                if(in_array($sell_item->sell_item_id, $old_issue_receive_purchase_sell_item_ids)){ } else {
                    $ptir_items->total_grwt = 0;
                    $ptir_items->total_less = 0;
                }
                if(in_array($sell_item->sell_item_id, $old_manu_hand_made_purchase_sell_item_ids)){ } else {
                    $ptmhm_items->total_grwt = 0;
                    $ptmhm_items->total_less = 0;
                }
                if(in_array($sell_item->sell_item_id, $old_machine_chain_purchase_sell_item_ids)){ } else {
                    $ptmc_items->total_grwt = 0;
                    $ptmc_items->total_less = 0;
                }
                
                $grwt = (float) $sell_item->grwt + (float) $pts_items->total_grwt + (float) $ptst_items->total_grwt + (float) $ptir_items->total_grwt + (float) $ptmhm_items->total_grwt + (float) $ptmc_items->total_grwt;
                $less = (float) $sell_item->less + (float) $pts_items->total_less + (float) $ptst_items->total_less + (float) $ptir_items->total_less + (float) $ptmhm_items->total_less + (float) $ptmc_items->total_less;
                if(!empty($grwt)){
                    
                    $wstg = 0;
                    if(isset($_POST['account_id']) && !empty($_POST['account_id'])){
                        $account_data = $this->crud->get_row_by_id('party_item_details',array('account_id' => $_POST['account_id'], 'item_id'=> $item_id));
                        if(!empty($account_data)){
                            $wstg = $account_data[0]->wstg;
                        } else {
                            $item_data = $this->crud->get_val_by_id('item_master',$item_id,'item_id','default_wastage');
                            $wstg = $item_data;
                        }
                    } else {
                        $item_data = $this->crud->get_val_by_id('item_master',$item_id,'item_id','default_wastage');
                        $wstg = $item_data;
                    }

                    $sell_item->net_wt = $grwt - $less;
                    if(isset($_POST['do_not_count_wstg']) && $_POST['do_not_count_wstg'] == '1'){
                        $sell_item->fine = $sell_item->net_wt * ($sell_item->touch_id) / 100;
                    } else {
                        $sell_item->fine = $sell_item->net_wt * ($sell_item->touch_id + $wstg) / 100;
                    }
                    
//                if(!empty($sell_item->grwt)){
                    $sell_lineitem['category_name'] = $sell_item->category_name;
                    $sell_lineitem['group_name'] = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_item->category_id));
                    $sell_lineitem['account_name'] = $sell_item->account_name;
                    $sell_lineitem['sell_date'] = (!empty($sell_item->sell_date)) ? date('d-m-Y', strtotime($sell_item->sell_date)) : '';
                    $sell_lineitem['item_name'] = $item_name;
                    $sell_lineitem['less_allow'] = $less_allow;
                    $sell_lineitem['grwt'] = number_format($grwt, 3, '.', '');
                    $sell_lineitem['less'] = number_format($less, 3, '.', '');
                    $sell_lineitem['net_wt'] = number_format($sell_item->net_wt, 3, '.', '');
                    $sell_lineitem['wstg'] = $wstg;
                    $sell_lineitem['fine'] = number_format($sell_item->fine, 3, '.', '');
                    $sell_lineitem['type_name'] = 'S';
                    $sell_lineitem['type'] = '1';
                    $sell_lineitem['amount'] = '0';
                    $sell_lineitem['image'] = $sell_item->image;
                    $sell_lineitem['category_id'] = $sell_item->category_id;
                    $sell_lineitem['item_id'] = $sell_item->item_id;
                    $sell_lineitem['touch_id'] = $sell_item->touch_id;
                    $sell_lineitem['purchase_sell_item_id'] = $sell_item->sell_item_id;
                    $sell_lineitem['stock_type'] = $sell_item->stock_type;
                    $sell_lineitem['default_wstg'] = $wstg;
                    $sell_lineitem['design_no'] = isset($sell_item->design_no) ? $sell_item->design_no : '';
                    $sell_lineitem['rfid_number'] = isset($sell_item->rfid_number) ? $sell_item->rfid_number : '';
                    $sell_lineitems[] = $sell_lineitem;
                }
            }
        }
//        echo '<pre>'; print_r($sell_lineitems); exit;
        $data['sell_lineitems'] = $sell_lineitems;
        print json_encode($data);
        exit;
    }
    
    function get_item_stock(){
        $where_array = array();
        $where_array['department_id '] = $_POST['process_id'];
        $where_array['category_id'] = $_POST['category_id'];
        $where_array['item_id'] = $_POST['item_id'];
        $where_array['tunch'] = $_POST['touch_id'];
        $grwt_in_stock = array();
        $grwt_in_stock = $this->crud->get_columns_val_by_where('item_stock','grwt', $where_array);
        $grwt_in_old_sell_item = 0;
        if(isset($_POST['sell_item_id']) && !empty($_POST['sell_item_id'])){
            $grwt_in_old_sell_item = $this->crud->get_id_by_val('sell_items', 'grwt', 'sell_item_id', $_POST['sell_item_id']);
        }
        $grwt_in_old_stock_transfer_detail = 0;
        if(isset($_POST['transfer_detail_id']) && !empty($_POST['transfer_detail_id'])){
            $grwt_in_old_stock_transfer_detail = $this->crud->get_id_by_val('stock_transfer_detail', 'grwt', 'transfer_detail_id', $_POST['transfer_detail_id']);
        }
//        echo '<pre>'; print_r($_POST); exit;
        $grwt_in_old_issue_receive_detail = 0;
        if(isset($_POST['ird_id']) && !empty($_POST['ird_id'])){
            $grwt_in_old_issue_receive_detail = $this->crud->get_id_by_val('issue_receive_details', 'weight', 'ird_id', $_POST['ird_id']);
        }
        $grwt_in_old_issue_receive_silver_detail = 0;
        if(isset($_POST['irsd_id']) && !empty($_POST['irsd_id'])){
            $grwt_in_old_issue_receive_silver_detail = $this->crud->get_id_by_val('issue_receive_silver_details', 'weight', 'irsd_id', $_POST['irsd_id']);
        }
        $grwt_in_old_manu_hand_made_detail = 0;
        if(isset($_POST['mhm_detail_id']) && !empty($_POST['mhm_detail_id'])){
            $grwt_in_old_manu_hand_made_detail = $this->crud->get_id_by_val('manu_hand_made_details', 'weight', 'mhm_detail_id', $_POST['mhm_detail_id']);
        }
        $grwt_in_old_machine_chain_detail = 0;
        if(isset($_POST['machine_chain_detail_id']) && !empty($_POST['machine_chain_detail_id'])){
            $grwt_in_old_machine_chain_detail = $this->crud->get_id_by_val('machine_chain_details', 'weight', 'machine_chain_detail_id', $_POST['machine_chain_detail_id']);
        }
        $grwt_in_old_other_entry_detail = 0;
        if(isset($_POST['other_item_id']) && !empty($_POST['other_item_id'])){
            $grwt_in_old_other_entry_detail = $this->crud->get_id_by_val('other_items', 'grwt', 'other_item_id', $_POST['other_item_id']);
        }
        
        if(empty($grwt_in_stock)){
            $grwt_in_stock['grwt'] = 0;
        } else {
            $grwt_in_stock['grwt'] = $grwt_in_stock[0]['grwt'] + $grwt_in_old_sell_item + $grwt_in_old_stock_transfer_detail + $grwt_in_old_issue_receive_detail + $grwt_in_old_issue_receive_silver_detail + $grwt_in_old_manu_hand_made_detail + $grwt_in_old_machine_chain_detail + $grwt_in_old_other_entry_detail;
        }
        print json_encode($grwt_in_stock);
        exit;
    }
    
    function check_default_item_sell_or_not($department_id, $category_id, $item_id, $touch_id){
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt;
//        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items);
//            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items);
            
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
                    if($purchase_item->type == 'P'){
                        $used_lineitem_ids[] = $purchase_item->sell_item_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'P'){
                        $used_lineitem_ids[] = $purchase_item->sell_item_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }
    
    function check_default_item_metal_or_not($department_id, $category_id, $item_id, $touch_id){
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt;
//        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items);
//            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items);
            
            uasort($purchase_delete_array, function($a, $b) {
                $value1 = strtotime($a->created_at);
                $value2 = strtotime($b->created_at);
                return $value1 - $value2;
            });
            
            $metal_grwt = 0;
            $first_check_metal_grwt = 0;

            foreach ($purchase_delete_array as $metal_item){
                $metal_grwt = $metal_grwt + $metal_item->grwt;
                if($metal_grwt >= $total_sell_grwt && $first_check_metal_grwt == 0){
                    if($metal_item->type == 'M'){
                        $used_lineitem_ids[] = $metal_item->metal_pr_id;
                    }
                    $first_check_metal_grwt = 1;
                } else if($metal_grwt <= $total_sell_grwt){
                    if($metal_item->type == 'M'){
                        $used_lineitem_ids[] = $metal_item->metal_pr_id;
                    }
                }
            }
        }
        return $used_lineitem_ids;
        exit;
    }
    
    function get_stock_for_metal_receipt($department_id, $item_id, $tunch, $metal_pr_id){
        $grwt_total = 0;
        $category_id = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $item_id));
        $grwt_for_metal = $this->crud->get_column_value_by_id('item_stock', 'grwt', array('department_id' => $department_id, 'category_id' => $category_id,'item_id' => $item_id, 'tunch' => $tunch));
        $grwt_for_metal_items = $this->crud->get_column_value_by_id('metal_payment_receipt', 'metal_grwt', array('metal_pr_id' => $metal_pr_id));
        $grwt_total = $grwt_for_metal + $grwt_for_metal_items;
        echo json_encode($grwt_total);
        exit;
    }

    function sell_item_list(){
        if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"sell/purchase item list")) {
            $data = array();
            set_page('sell/sell_item', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function item_datatable(){
        $post_data = $this->input->post();
        $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        $to_date = date('Y-m-d', strtotime($post_data['to_date']));

        $config['table'] = 'sell_items si';
        $config['select'] = 'si.item_id, i.item_name, c.category_name, c.category_group_id, SUM(IF(si.type = '.SELL_TYPE_SELL_ID.', si.net_wt, 0)) as total_sell_net_wt, SUM(IF(si.type = '.SELL_TYPE_SELL_ID.', si.fine, 0)) as total_sell_fine, SUM(IF(si.type = '.SELL_TYPE_PURCHASE_ID.', si.net_wt, 0)) as total_purchase_net_wt, SUM(IF(si.type = '.SELL_TYPE_PURCHASE_ID.', si.fine, 0)) as total_purchase_fine, SUM(IF(si.type = '.SELL_TYPE_EXCHANGE_ID.', si.net_wt, 0)) as total_exchange_net_wt, SUM(IF(si.type = '.SELL_TYPE_EXCHANGE_ID.', si.fine, 0)) as total_exchange_fine';
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = si.sell_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account p', 'join_by' => 'p.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.process_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master i', 'join_by' => 'si.item_id = i.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'i.category_id = c.category_id', 'join_type' => 'left');
        $config['column_search'] = array('i.item_name','c.category_name');
        $config['column_order'] = array('i.item_name','c.category_name');

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
        $config['group_by'] = 'si.item_id';
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $gold_total_exchange_net_wt = 0;
        $gold_total_exchange_fine = 0;
        $gold_total_purchase_net_wt = 0;
        $gold_total_purchase_fine = 0;
        $gold_total_sell_net_wt = 0;
        $gold_total_sell_fine = 0;
        $silver_total_exchange_net_wt = 0;
        $silver_total_exchange_fine = 0;
        $silver_total_purchase_net_wt = 0;
        $silver_total_purchase_fine = 0;
        $silver_total_sell_net_wt = 0;
        $silver_total_sell_fine = 0;
        foreach ($list as $item_detail) {
            $row = array();
            $row[] = $item_detail->category_name;
            $row[] = '<a href="'. base_url("sell/sell_item_list_from_item/".$item_detail->item_id.'/'.$from_date.'/'.$to_date).'" target="_blank">'.$item_detail->item_name.'</a>';
            $row[] = number_format($item_detail->total_exchange_net_wt, 3, '.', '');
            $row[] = number_format($item_detail->total_exchange_fine, 3, '.', '');
            $row[] = number_format($item_detail->total_purchase_net_wt, 3, '.', '');
            $row[] = number_format($item_detail->total_purchase_fine, 3, '.', '');
            $row[] = number_format($item_detail->total_sell_net_wt, 3, '.', '');
            $row[] = number_format($item_detail->total_sell_fine, 3, '.', '');
            $data[] = $row;
            
            if($item_detail->category_group_id == CATEGORY_GROUP_GOLD_ID){
                $gold_total_exchange_net_wt = number_format((float) $gold_total_exchange_net_wt, 3, '.', '') + number_format((float) $item_detail->total_exchange_net_wt, 3, '.', '');
                $gold_total_exchange_fine = number_format((float) $gold_total_exchange_fine, 3, '.', '') + number_format((float) $item_detail->total_exchange_fine, 3, '.', '');
                $gold_total_purchase_net_wt = number_format((float) $gold_total_purchase_net_wt, 3, '.', '') + number_format((float) $item_detail->total_purchase_net_wt, 3, '.', '');
                $gold_total_purchase_fine = number_format((float) $gold_total_purchase_fine, 3, '.', '') + number_format((float) $item_detail->total_purchase_fine, 3, '.', '');
                $gold_total_sell_net_wt = number_format((float) $gold_total_sell_net_wt, 3, '.', '') + number_format((float) $item_detail->total_sell_net_wt, 3, '.', '');
                $gold_total_sell_fine = number_format((float) $gold_total_sell_fine, 3, '.', '') + number_format((float) $item_detail->total_sell_fine, 3, '.', '');
            } else if($item_detail->category_group_id == CATEGORY_GROUP_SILVER_ID){
                $silver_total_exchange_net_wt = number_format((float) $silver_total_exchange_net_wt, 3, '.', '') + number_format((float) $item_detail->total_exchange_net_wt, 3, '.', '');
                $silver_total_exchange_fine = number_format((float) $silver_total_exchange_fine, 3, '.', '') + number_format((float) $item_detail->total_exchange_fine, 3, '.', '');
                $silver_total_purchase_net_wt = number_format((float) $silver_total_purchase_net_wt, 3, '.', '') + number_format((float) $item_detail->total_purchase_net_wt, 3, '.', '');
                $silver_total_purchase_fine = number_format((float) $silver_total_purchase_fine, 3, '.', '') + number_format((float) $item_detail->total_purchase_fine, 3, '.', '');
                $silver_total_sell_net_wt = number_format((float) $silver_total_sell_net_wt, 3, '.', '') + number_format((float) $item_detail->total_sell_net_wt, 3, '.', '');
                $silver_total_sell_fine = number_format((float) $silver_total_sell_fine, 3, '.', '') + number_format((float) $item_detail->total_sell_fine, 3, '.', '');
            }
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
            "gold_total_exchange_net_wt" => number_format((float) $gold_total_exchange_net_wt, 3, '.', ''),
            "gold_total_exchange_fine" => number_format((float) $gold_total_exchange_fine, 3, '.', ''),
            "gold_total_purchase_net_wt" => number_format((float) $gold_total_purchase_net_wt, 3, '.', ''),
            "gold_total_purchase_fine" => number_format((float) $gold_total_purchase_fine, 3, '.', ''),
            "gold_total_sell_net_wt" => number_format((float) $gold_total_sell_net_wt, 3, '.', ''),
            "gold_total_sell_fine" => number_format((float) $gold_total_sell_fine, 3, '.', ''),
            "silver_total_exchange_net_wt" => number_format((float) $silver_total_exchange_net_wt, 3, '.', ''),
            "silver_total_exchange_fine" => number_format((float) $silver_total_exchange_fine, 3, '.', ''),
            "silver_total_purchase_net_wt" => number_format((float) $silver_total_purchase_net_wt, 3, '.', ''),
            "silver_total_purchase_fine" => number_format((float) $silver_total_purchase_fine, 3, '.', ''),
            "silver_total_sell_net_wt" => number_format((float) $silver_total_sell_net_wt, 3, '.', ''),
            "silver_total_sell_fine" => number_format((float) $silver_total_sell_fine, 3, '.', ''),
        );
        echo json_encode($output);
    }

    function sell_item_list_from_item($item_id='',$from_date='',$to_date=''){
        if($this->applib->have_access_role(SELL_PURCHASE_MODULE_ID,"sell/purchase item list")) {
            $data = array();
            $data['item_id'] = $item_id;
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
            $data['touch'] = $touch;
            set_page('sell/sell_item_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function item_wastage_approve_action(){
        $sell_item_id = $_POST['sell_item_id'];
        $wastage = $_POST['wastage'];
        $this->crud->update('sell_items', array('wastage_change_approve' => $wastage), array('sell_item_id' => $sell_item_id));
    }
    
    function get_lineitem_based_on_rfid() {
        $sell_lineitem = array();
        $department_id = $_POST['department_id'];
        $item_stock_rfid_id = $_POST['item_stock_rfid_id'];
        $rfid_number = $_POST['rfid_number'];
        $item_stock_rfid_sql = 'SELECT isr.* FROM `item_stock_rfid` isr ';
        $item_stock_rfid_sql .= 'JOIN `item_stock` i_s ON i_s.`item_stock_id` = isr.`item_stock_id` ';
        $item_stock_rfid_sql .= 'WHERE (`real_rfid` = "'.$rfid_number.'" OR `item_stock_rfid_id` = "'.$rfid_number.'")';
        $item_stock_rfid_sql .= ' AND i_s.`department_id` = "'.$department_id.'"';
        $item_stock_rfid_sql .= ' ORDER BY `isr`.`item_stock_rfid_id` DESC';
        $item_stock_rfid_data = $this->crud->getFromSQL($item_stock_rfid_sql);
        if (!empty($item_stock_rfid_data)) {
            foreach ($item_stock_rfid_data as $item_stock_rfid){
                if($item_stock_rfid->rfid_used == '1' && empty($item_stock_rfid_id)){
                    $check_rfid_used_json = $this->check_where_rfid_used($item_stock_rfid->to_module, $item_stock_rfid->to_relation_id);
                    print $check_rfid_used_json;
                    exit;
                }
                $item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $item_stock_rfid->item_stock_id);
                $wstg = 0;
                if (isset($_POST['account_id']) && !empty($_POST['account_id'])) {
                    $account_data = $this->crud->get_row_by_id('party_item_details', array('account_id' => $_POST['account_id'], 'item_id' => $item_stock_data->item_id));
                    if (!empty($account_data)) {
                        $wstg = $account_data[0]->wstg;
                    } else {
                        $item_data = $this->crud->get_val_by_id('item_master', $item_stock_data->item_id, 'item_id', 'default_wastage');
                        $wstg = $item_data;
                    }
                } else {
                    $item_data = $this->crud->get_val_by_id('item_master', $item_stock_data->item_id, 'item_id', 'default_wastage');
                    $wstg = $item_data;
                }

                $sell_lineitem['type'] = SELL_TYPE_SELL_ID;
                $sell_lineitem['category_id'] = $item_stock_data->category_id;
                $sell_lineitem['item_id'] = $item_stock_data->item_id;
                $sell_lineitem['less_allow'] = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $item_stock_data->item_id));
                $sell_lineitem['grwt'] = number_format($item_stock_rfid->rfid_grwt, 3, '.', '');
                $less = $item_stock_rfid->rfid_less - $item_stock_rfid->rfid_add;
                $sell_lineitem['less'] = number_format($less, 3, '.', '');
                $sell_lineitem['net_wt'] = number_format($item_stock_rfid->rfid_ntwt, 3, '.', '');
                $sell_lineitem['wstg'] = $wstg;
                $sell_lineitem['touch_id'] = $item_stock_rfid->rfid_tunch;
                $sell_lineitem['fine'] = number_format($item_stock_rfid->rfid_fine, 3, '.', '');
                $sell_lineitem['default_wstg'] = $wstg;
                $sell_lineitem['charges_amt'] = $item_stock_rfid->rfid_charges;
                $sell_lineitem['item_stock_rfid_id'] = $item_stock_rfid->item_stock_rfid_id;
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

    function sell_print($sell_id = '', $isimage = '') {
        $data = array();

        $setting_data = $this->crud->get_all_records('settings', 'fields_section', 'asc');
        foreach($setting_data as $setting_row){
            if($setting_row->settings_key == 'without_purchase_sell_allow' || $setting_row->settings_key == 'use_category' || $setting_row->fields_section == '1' || $setting_row->fields_section == '2'){
                $data[$setting_row->settings_key] = $setting_row->settings_value;
            }
        }

        if(!empty($sell_id)){
            $sell_data = $this->crud->get_row_by_id('sell', array('sell_id' => $sell_id));
            $sell_data = $sell_data[0];
            $sell_data->account_name = '';
            $sell_data->old_gold_fine = 0;
            $sell_data->old_silver_fine = 0;
            $sell_data->old_amount = 0;
            $account_data = $this->crud->get_row_by_id('account',array('account_id' => $sell_data->account_id));
            if(!empty($account_data)){
                $sell_data->account_name = $account_data[0]->account_name;
                $sell_data->old_gold_fine = $account_data[0]->gold_fine;
                $sell_data->old_silver_fine = $account_data[0]->silver_fine;
                if(PACKAGE_FOR == 'manek') {
                    $sell_data->old_amount = $account_data[0]->amount;
                } else {
                    $sell_data->old_amount = number_format($account_data[0]->amount, 0, '.', '');
                }
            }
            $sell_data->total_gold_fine = (!empty($sell_data->total_gold_fine)) ? $sell_data->total_gold_fine : 0;
            $sell_data->total_silver_fine = (!empty($sell_data->total_silver_fine)) ? $sell_data->total_silver_fine : 0;
            $sell_data->total_amount = (!empty($sell_data->total_amount)) ? $sell_data->total_amount : 0;
            if(PACKAGE_FOR == 'manek') {
                $sell_data->discount_amount = (!empty($sell_data->discount_amount)) ? $sell_data->discount_amount : 0;
                $sell_data->total_amount = $sell_data->total_amount - $sell_data->discount_amount;
            }
            $sell_data->old_gold_fine = $sell_data->old_gold_fine - $sell_data->total_gold_fine;
            $sell_data->old_silver_fine = $sell_data->old_silver_fine - $sell_data->total_silver_fine;
            $sell_data->old_amount = $sell_data->old_amount - $sell_data->total_amount;
            $data['sell_data'] = $sell_data;
//            print_r($data['sell_data']); exit;

            //----------------- Sell Itemms -------------------
            $sell_data_items = $this->crud->get_row_by_id('sell_items', array('sell_id' => $sell_id));
            if(!empty($sell_data_items)){
                foreach($sell_data_items as $sell_data_item){
                    $sell_items = new \stdClass();
                    $sell_items->sell_item_delete = 'allow';
                    $sell_items->tunch_textbox = (isset($sell_data_item->tunch_textbox) && $sell_data_item->tunch_textbox == '1') ? '1' : '0';
                    $sell_items->type = $sell_data_item->type;
                    $sell_items->type_name = $this->crud->get_column_value_by_id('sell_type', 'type_name', array('sell_type_id' => $sell_data_item->type));
                    if(!empty($sell_items->type_name)) {
                        $sell_items->type_name = substr($sell_items->type_name, 0, 1);
                    }
                    $sell_items->category_name = $this->crud->get_column_value_by_id('category', 'category_name', array('category_id' => $sell_data_item->category_id));
                    $sell_items->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $sell_data_item->category_id));
                    $sell_items->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $sell_data_item->item_id));
                    $sell_items->stamp_name = $this->crud->get_column_value_by_id('stamp', 'stamp_name', array('stamp_id' => $sell_data_item->stamp_id));
                    $sell_items->charges_amt = (isset($sell_data_item->charges_amt) && !empty($sell_data_item->charges_amt)) ? $sell_data_item->charges_amt : 0;
                    if($sell_items->type != SELL_TYPE_SELL_ID){
                        $sell_items->grwt = ZERO_VALUE - $sell_data_item->grwt;
                        $sell_items->net_wt = ZERO_VALUE - $sell_data_item->net_wt;
                        $sell_items->wstg = ZERO_VALUE - $sell_data_item->wstg;
                        $sell_items->fine = ZERO_VALUE - $sell_data_item->fine;
                        $sell_items->charges_amt = ZERO_VALUE - $sell_items->charges_amt;
                    } else {
                        $sell_items->grwt = $sell_data_item->grwt;
                        $sell_items->net_wt = $sell_data_item->net_wt;
                        $sell_items->wstg = $sell_data_item->wstg;
                        $sell_items->fine = $sell_data_item->fine;
                    }
                    $sell_items->charges_amt = number_format($sell_items->charges_amt, 2, '.', '');
                    $sell_items->grwt = number_format($sell_items->grwt, 3, '.', '');
                    $sell_items->net_wt = number_format($sell_items->net_wt, 3, '.', '');
                    $sell_items->wstg = number_format($sell_items->wstg, 3, '.', '');
                    $sell_items->fine = number_format($sell_items->fine, 3, '.', '');
                    $sell_items->less = number_format($sell_data_item->less, 3, '.', '');
                    $sell_items->spi_loss_for = number_format($sell_data_item->spi_loss_for, 3, '.', '');
                    $sell_items->touch_id = $sell_data_item->touch_id;
                    $sell_items->category_id = $sell_data_item->category_id;
                    $sell_items->item_id = $sell_data_item->item_id;
                    $sell_items->li_narration = (isset($sell_data_item->li_narration) && !empty($sell_data_item->li_narration)) ? $sell_data_item->li_narration : NULL;
                    $sell_items->sell_item_id = $sell_data_item->sell_item_id;
                    $sell_items->item_stock_rfid_id = (isset($sell_data_item->item_stock_rfid_id) && !empty($sell_data_item->item_stock_rfid_id)) ? $sell_data_item->item_stock_rfid_id : NULL;
                    $sell_items->rfid_number = (isset($sell_data_item->rfid_number) && !empty($sell_data_item->rfid_number)) ? $sell_data_item->rfid_number : NULL;
                    $sell_items->spi_pcs = (isset($sell_data_item->spi_pcs) && !empty($sell_data_item->spi_pcs)) ? $sell_data_item->spi_pcs : 0;
                    $sell_items->spi_rate = (isset($sell_data_item->spi_rate) && !empty($sell_data_item->spi_rate)) ? $sell_data_item->spi_rate : 0;
                    $sell_items->spi_rate_of = (isset($sell_data_item->spi_rate_of) && !empty($sell_data_item->spi_rate_of)) ? $sell_data_item->spi_rate_of : 1;
                    $sell_items->spi_labour_on = (isset($sell_data_item->spi_labour_on) && !empty($sell_data_item->spi_labour_on)) ? $sell_data_item->spi_labour_on : 0;
                    $sell_items->amount = (isset($sell_data_item->amount) && !empty($sell_data_item->amount)) ? $sell_data_item->amount : 0;
                    $sell_items->amount = number_format($sell_items->amount, 2, '.', '');
                    $sell_items->image = $sell_data_item->image;
                    $sell_items->order_lot_item_id = $sell_data_item->order_lot_item_id;
                    $sell_items->purchase_sell_item_id = $sell_data_item->purchase_sell_item_id;
                    $sell_items->stock_type = $sell_data_item->stock_type;

                    //----------------- Less Ad Details -------------------
                    $sell_items->sell_less_ad_details = '';
                    $sell_less_ad_details = $this->crud->get_row_by_id('sell_less_ad_details', array('sell_id' => $sell_data_item->sell_id, 'sell_item_id' => $sell_data_item->sell_item_id));
                    if(!empty($sell_less_ad_details)){
                        $less_ad_details_data = array();
                        foreach ($sell_less_ad_details as $sell_less_ad_detail){
                            $sell_less_ad_details_lineitems = new \stdClass();
                            $sell_less_ad_details_lineitems->sell_less_ad_details_id = $sell_less_ad_detail->sell_less_ad_details_id;
                            $sell_less_ad_details_lineitems->less_ad_details_delete = 'allow';
                            $sell_less_ad_details_lineitems->less_ad_details_ad_id = $sell_less_ad_detail->less_ad_details_ad_id;
                            $sell_less_ad_details_lineitems->less_ad_details_ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $sell_less_ad_detail->less_ad_details_ad_id));
                            $sell_less_ad_details_lineitems->less_ad_details_ad_pcs = (isset($sell_less_ad_detail->less_ad_details_ad_pcs) && !empty($sell_less_ad_detail->less_ad_details_ad_pcs)) ? $sell_less_ad_detail->less_ad_details_ad_pcs : 0;
                            $sell_less_ad_details_lineitems->less_ad_details_ad_weight = (isset($sell_less_ad_detail->less_ad_details_ad_weight) && !empty($sell_less_ad_detail->less_ad_details_ad_weight)) ? $sell_less_ad_detail->less_ad_details_ad_weight : 0;
                            $sell_less_ad_details_lineitems->less_ad_details_ad_pwt = $sell_less_ad_details_lineitems->less_ad_details_ad_pcs * $sell_less_ad_details_lineitems->less_ad_details_ad_weight;
                            $sell_less_ad_details_lineitems->less_ad_details_ad_pwt = number_format($sell_less_ad_details_lineitems->less_ad_details_ad_pwt, 3, '.', '');
                            $less_ad_details_data[] = json_encode($sell_less_ad_details_lineitems);
                        }
                        $sell_items->sell_less_ad_details = '['.implode(',', $less_ad_details_data).']';
                    }

                    $sell_lineitems[] = $sell_items;
                }
                $data['sell_item'] = $sell_lineitems;
            }

            //----------------- Payment Receipt -------------------
            $payment_receipt = $this->crud->get_row_by_id('payment_receipt', array('sell_id' => $sell_id));
            if(!empty($payment_receipt)){
                foreach ($payment_receipt as $value){
                    $pay_lineitems = new \stdClass();
                    $pay_lineitems->payment_receipt = $value->payment_receipt;
                    $pay_lineitems->payment_receipt_name = ($value->payment_receipt == '1') ? 'Payment' : 'Receipt';
                    $pay_lineitems->cash_cheque = ($value->cash_cheque == '1') ? 'Cash' : 'Cheque';
                    if(isset($value->bank_id) && !empty($value->bank_id)){
                        $pay_lineitems->bank_name = ' - ' . $this->crud->get_column_value_by_id('account', 'bank_name', array('account_id' => $value->bank_id));
                    } else {
                        $pay_lineitems->bank_name = '';
                    }
                    $pay_lineitems->pay_rec_id = $value->pay_rec_id;
                    $pay_lineitems->bank_id = $value->bank_id;
                    if($pay_lineitems->payment_receipt == '2'){
                        $pay_lineitems->amount = ZERO_VALUE - $value->amount;
                    } else {
                        $pay_lineitems->amount = $value->amount;
                    }
                    $pay_lineitems->amount = number_format($pay_lineitems->amount, 2, '.', '');
                    $pay_lineitems->narration = $value->narration;
                    $pay_rec_lineitems[] = $pay_lineitems;
                }
                $data['pay_rec_data'] = $pay_rec_lineitems;
            }

            //----------------- Metal Issue Receive -------------------
            $metal_payment_receipt = $this->crud->get_row_by_id('metal_payment_receipt', array('sell_id' => $sell_id));
//                echo '<pre>'; print_r($metal_payment_receipt); exit;
            if(!empty($metal_payment_receipt)){
                foreach ($metal_payment_receipt as $metal){
                    $metal_lineitems = new \stdClass();
                    $metal_lineitems->metal_item_delete = 'allow';
                    $metal_lineitems->metal_item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $metal->metal_item_id));
                    $metal_lineitems->metal_payment_receipt = $metal->metal_payment_receipt;
                    $metal_lineitems->metal_payment_receipt_name = ($metal->metal_payment_receipt == '1') ? 'Metal Issue' : 'Metal Receive';
                    $metal_lineitems->group_name = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $metal->metal_category_id));
                    $metal_lineitems->metal_item_id = $metal->metal_item_id;
                    if($metal->metal_payment_receipt == '2'){
                        $metal_lineitems->metal_grwt = ZERO_VALUE - $metal->metal_grwt;
                        $metal_lineitems->metal_fine = ZERO_VALUE - $metal->metal_fine;
                    } else {
                        $metal_lineitems->metal_grwt = $metal->metal_grwt;
                        $metal_lineitems->metal_fine = $metal->metal_fine;
                    }
                    $metal_lineitems->metal_grwt = number_format($metal_lineitems->metal_grwt, 3, '.', '');
                    $metal_lineitems->metal_fine = number_format($metal_lineitems->metal_fine, 3, '.', '');
                    $metal_lineitems->metal_tunch = $metal->metal_tunch;
                    $metal_lineitems->metal_narration = $metal->metal_narration;
                    $metal_lineitems->metal_pr_id = $metal->metal_pr_id;
                    $metal_pay_rec_lineitems[] = $metal_lineitems;
                }
                $data['metal_data'] = $metal_pay_rec_lineitems;
            }

            //----------------- Gold Bhav -------------------
            $gold_bhav = $this->crud->get_row_by_id('gold_bhav', array('sell_id' => $sell_id));
            if(!empty($gold_bhav)){
                foreach ($gold_bhav as $gold){
                    $gold_bhav_lineitems = new \stdClass();
                    $gold_bhav_lineitems->gold_sale_purchase = $gold->gold_sale_purchase;
                    $gold_bhav_lineitems->gold_sale_purchase_name = ($gold->gold_sale_purchase == '1') ? 'Sell' : 'Purchase';
                    if($gold_bhav_lineitems->gold_sale_purchase == '1'){
                        $gold_bhav_lineitems->gold_weight = ZERO_VALUE - $gold->gold_weight;
                        $gold_bhav_lineitems->gold_value = $gold->gold_value;
                    } else {
                        $gold_bhav_lineitems->gold_weight = $gold->gold_weight;
                        $gold_bhav_lineitems->gold_value = ZERO_VALUE - $gold->gold_value;
                    }
                    $gold_bhav_lineitems->gold_weight = number_format($gold_bhav_lineitems->gold_weight, 3, '.', '');
                    $gold_bhav_lineitems->gold_value = number_format($gold_bhav_lineitems->gold_value, 2, '.', '');
                    $gold_bhav_lineitems->gold_rate = $gold->gold_rate;
                    $gold_bhav_lineitems->gold_cr_effect = $gold->gold_cr_effect;
                    $gold_bhav_lineitems->gold_narration = $gold->gold_narration;
                    $gold_bhav_lineitems->gold_id = $gold->gold_id;
                    $gold_lineitems[] = $gold_bhav_lineitems;
                }
                $data['gold_data'] = $gold_lineitems;
            }

            //----------------- Silver Bhav -------------------
            $silver_bhav = $this->crud->get_row_by_id('silver_bhav', array('sell_id' => $sell_id));
            if(!empty($silver_bhav)){
                foreach ($silver_bhav as $silver){
                    $silver_bhav_lineitems = new \stdClass();
                    $silver_bhav_lineitems->silver_sale_purchase = $silver->silver_sale_purchase;
                    $silver_bhav_lineitems->silver_sale_purchase_name = ($silver->silver_sale_purchase == '1') ? 'Sell' : 'Purchase';
                    if($silver_bhav_lineitems->silver_sale_purchase == '1'){
                        $silver_bhav_lineitems->silver_weight = ZERO_VALUE - $silver->silver_weight;
                        $silver_bhav_lineitems->silver_value = $silver->silver_value;
                    } else {
                        $silver_bhav_lineitems->silver_weight = $silver->silver_weight;
                        $silver_bhav_lineitems->silver_value = ZERO_VALUE - $silver->silver_value;
                    }
                    $silver_bhav_lineitems->silver_weight = number_format($silver_bhav_lineitems->silver_weight, 3, '.', '');
                    $silver_bhav_lineitems->silver_value = number_format($silver_bhav_lineitems->silver_value, 2, '.', '');
                    $silver_bhav_lineitems->silver_rate = $silver->silver_rate;
                    $silver_bhav_lineitems->silver_cr_effect = $silver->silver_cr_effect;
                    $silver_bhav_lineitems->silver_narration = $silver->silver_narration;
                    $silver_bhav_lineitems->silver_id = $silver->silver_id;
                    $silver_lineitems[] = $silver_bhav_lineitems;
                }
                $data['silver_data'] = $silver_lineitems;
            }

            //----------------- transfer -------------------
            $transfers = $this->crud->get_row_by_id('transfer', array('sell_id' => $sell_id));
            if(!empty($transfers)){
                foreach ($transfers as $transfer){
                    $transfers_lineitems = new \stdClass();
                    $transfers_lineitems->naam_jama = $transfer->naam_jama;
                    $transfers_lineitems->naam_jama_name = ($transfer->naam_jama == '1') ? 'Naam (Dr)' : 'Jama (Cr)';
                    $transfers_lineitems->party_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $transfer->transfer_account_id));
                    $transfers_lineitems->transfer_account_id = $transfer->transfer_account_id;
                    if($transfers_lineitems->naam_jama == '1'){
                        $transfers_lineitems->transfer_gold = ZERO_VALUE - $transfer->transfer_gold;
                        $transfers_lineitems->transfer_silver = ZERO_VALUE - $transfer->transfer_silver;
                        $transfers_lineitems->transfer_amount = ZERO_VALUE - $transfer->transfer_amount;
                    } else {
                        $transfers_lineitems->transfer_gold = $transfer->transfer_gold;
                        $transfers_lineitems->transfer_silver = $transfer->transfer_silver;
                        $transfers_lineitems->transfer_amount = $transfer->transfer_amount;
                    }
                    $transfers_lineitems->transfer_gold = number_format($transfers_lineitems->transfer_gold, 3, '.', '');
                    $transfers_lineitems->transfer_silver = number_format($transfers_lineitems->transfer_silver, 3, '.', '');
                    $transfers_lineitems->transfer_amount = number_format($transfers_lineitems->transfer_amount, 2, '.', '');
                    $transfers_lineitems->transfer_narration = $transfer->transfer_narration;
                    $transfers_lineitems->transfer_id = $transfer->transfer_id;
                    $transfers_lineitems->transfer_entry_id = $transfer->transfer_id;
                    $transfer_lineitems[] = $transfers_lineitems;
                }
                $data['transfer_data'] = $transfer_lineitems;
            }

            //----------------- Ad Charges -------------------
            $sell_ad_charges = $this->crud->get_row_by_id('sell_ad_charges', array('sell_id' => $sell_id));
            if(!empty($sell_ad_charges)){
                $ad_charges_data = array();
                foreach ($sell_ad_charges as $sell_ad_charge){
                    $sell_ad_charges_lineitems = new \stdClass();
                    $sell_ad_charges_lineitems->sell_ad_charges_id = $sell_ad_charge->sell_ad_charges_id;
                    $sell_ad_charges_lineitems->ad_id = $sell_ad_charge->ad_id;
                    $sell_ad_charges_lineitems->ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $sell_ad_charge->ad_id));
                    $sell_ad_charges_lineitems->ad_pcs = $sell_ad_charge->ad_pcs;
                    $sell_ad_charges_lineitems->ad_rate = $sell_ad_charge->ad_rate;
                    $sell_ad_charges_lineitems->ad_amount = number_format($sell_ad_charge->ad_amount, 2, '.', '');
                    $sell_ad_charges_lineitems->ad_charges_remark = (isset($sell_ad_charge->ad_charges_remark) && !empty($sell_ad_charge->ad_charges_remark)) ? $sell_ad_charge->ad_charges_remark : '';
                    $ad_charges_data[] = $sell_ad_charges_lineitems;
                }
                $data['ad_charges_data'] = $ad_charges_data;
            }

            //----------------- Adjust CR -------------------
            $sell_adjust_cr = $this->crud->get_row_by_id('sell_adjust_cr', array('sell_id' => $sell_id));
            if(!empty($sell_adjust_cr)){
                $adjust_cr_data = array();
                foreach ($sell_adjust_cr as $sell_adjust_cr_row){
                    $sell_adjust_cr_lineitems = new \stdClass();
                    $sell_adjust_cr_lineitems->sell_adjust_cr_id = $sell_adjust_cr_row->sell_adjust_cr_id;
                    $sell_adjust_cr_lineitems->adjust_to = $sell_adjust_cr_row->adjust_to;
                    $sell_adjust_cr_lineitems->adjust_to_name = ($sell_adjust_cr_row->adjust_to == '1') ? 'R Amt To C Amt' : 'C Amt To R Amt';
                    $sell_adjust_cr_lineitems->adjust_cr_amount = $sell_adjust_cr_row->adjust_cr_amount;
                    $adjust_cr_data[] = json_encode($sell_adjust_cr_lineitems);
                }
                $data['adjust_cr_data'] = implode(',', $adjust_cr_data);
            }
        }
//        print_r($data); exit;
        $data['company_details'] = $this->crud->get_data_row_by_id('company_details', 'company_id', '1');
        $data['state_name'] = $this->crud->get_column_value_by_id('state', 'state_name', array('state_id' => $data['company_details']->company_state_id));
        $data['city_name'] = $this->crud->get_column_value_by_id('city', 'city_name', array('city_id' => $data['company_details']->company_city_id));
        $data['ask_discount_in_sell_purchase'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'ask_discount_in_sell_purchase'));

        if($isimage == 'isimage'){
            $data['isimage'] = 'isimage';
            $this->load->view('sell/sell_print_for_type_3', $data);
        } else {

            $html = $this->load->view('sell/sell_print_for_type_3', $data, true);
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
            $mpdf->defHTMLHeaderByName('myHeader2','<div style="text-align: center; font-weight: bold;">Invoice</div>');
            $mpdf->WriteHTML($html);
            $mpdf->Output();

        }
    }

}
