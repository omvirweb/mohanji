<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_new extends CI_Controller {

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
    }

    // Customer Ledger Report related Functions
    function customer_ledger() {
        if($this->applib->have_access_role(ACCOUNT_MODULE_ID,"customer ledger")) {
            $data = array();
            $data['account_id'] = isset($_GET['account_id']) && !empty($_GET['account_id']) ? $_GET['account_id'] : '';
            $account_ids = $this->applib->current_user_account_ids();
            $data['allow_cash_customer'] = 0;
            if($account_ids == "allow_all_accounts") {
                $data['allow_cash_customer'] = 1;
            } elseif(!empty($account_ids)) {
                if (in_array(CASE_CUSTOMER_ACCOUNT_ID, $account_ids)){
                    $data['allow_cash_customer'] = 1;
                }
            } else {
                $data['allow_cash_customer'] = 0;
            }
            $data['c_r_amount_separate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'c_r_amount_separate'));
            set_page('reports_new/customer_ledger_new', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function customer_ledger_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $type_sort = $post_data['type_sort'];
        $display_opening = 0;
        if (!empty($post_data['account_id']) && empty($type_sort)) {
            $display_opening = 1;
        }
//        if ($post_data['from_zero'] == 1) {
//            $display_opening = 0;
//        }
        $offset = $post_data['offset'];
        if ($display_opening == 1 && $offset == 0) {
            $opening_data = $this->get_opening_customer_ledger($from_date, $post_data['account_id'], $type_sort, $post_data['view_only_hisab']);
        } else {
            $opening_data = array();
        }
        $account_group_id = $this->crud->get_column_value_by_id('account', 'account_group_id', array('account_id' => $post_data['account_id']));
        if($account_group_id == DEPARTMENT_GROUP){
            $customer_ledger_data = $this->applib->get_customer_ledger_department_data_arr($from_date, $to_date, $post_data['account_id'], $type_sort, $offset);
        } else {
            $customer_ledger_data = $this->applib->get_customer_ledger_data_arr($from_date, $to_date, $post_data['account_id'], $type_sort, $post_data['view_only_hisab'], $offset);
        }
        //echo '<pre>'; print_r($customer_ledger_data); exit;
        uasort($customer_ledger_data, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });
        usort($customer_ledger_data, function($a, $b) {
            if ($a->st_date < $b->st_date) {
                $retval = -1;
            } elseif ($a->st_date > $b->st_date) {
                $retval = 1;
            } else {
                $retval = 0;
            }
            if ($retval == 0) {
                $value1 = strtotime($a->updated_at);
                $value2 = strtotime($b->updated_at);
                $retval =  $value1 - $value2;
                return $retval;
            }
            return $retval;
        });
        $display_line_item_remark_in_ledger = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'display_line_item_remark_in_ledger'));

        $customer_ledger_data = array_merge($opening_data, $customer_ledger_data);
        $customer_ledger_data = array_values($customer_ledger_data);
//        echo '<pre>'; print_r($customer_ledger_data); exit;
        $data = array();
        $total_grwt = 0;
        $total_less = 0;
        $total_net_wt = 0;
        $total_gold_fine = 0;
        $daywise_total_gold_fine = 0;
        $daywise_total_silver_fine = 0;
        $daywise_total_amount = 0;
        $total_silver_fine = 0;
        $total_amount = 0;
        $total_c_amount = 0;
        $total_r_amount = 0;
        
        $gold_total_grwt = 0;
        $gold_total_less = 0;
        $gold_total_net_wt = 0;
        $gold_total_gold_fine = 0;
        $gold_total_silver_fine = 0;
        
        $silver_total_grwt = 0;
        $silver_total_less = 0;
        $silver_total_net_wt = 0;
        $silver_total_gold_fine = 0;
        $silver_total_silver_fine = 0;

        $daterange_total_amount = 0;
        $current_date = '';
//        echo '<pre>'; print_r($customer_ledger_data); exit;
        foreach ($customer_ledger_data as $key => $customer_ledger) {
            $action = '';
            $department_ids = $this->applib->current_user_department_ids();
            if($display_opening != 1 || ($display_opening == 1 && $key != '0')){
                    if($customer_ledger->type_sort == 'ST F' || $customer_ledger->type_sort == 'ST T'){
                        if($this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                $action .= '<a href="' . base_url("stock_transfer/stock_transfer/" . $customer_ledger->stock_transfer_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                            }
                        }
                    } else if($customer_ledger->type_sort == 'MFI' || $customer_ledger->type_sort == 'MFR' || $customer_ledger->type_sort == 'IRKW'){
                        if($this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                if($customer_ledger->hisab_done != '1'){
                                    $action .= '<a href="' . base_url("manufacture/issue_receive/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    } else if($customer_ledger->type_sort == 'MFIS' || $customer_ledger->type_sort == 'MFRS'){
                        if($this->app_model->have_access_role(ISSUE_RECEIVE_SILVER_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                if($customer_ledger->hisab_done != '1'){
                                    $action .= '<a href="' . base_url("manufacture_silver/issue_receive_silver/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    } else if($customer_ledger->type_sort == 'MHMIFW' || $customer_ledger->type_sort == 'MHMIS' || $customer_ledger->type_sort == 'MHMRFW' || $customer_ledger->type_sort == 'MHMRS'){
                        if($this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                if($customer_ledger->hisab_done != '1'){
                                    $action .= '<a href="' . base_url("manu_hand_made/manu_hand_made/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    } else if($customer_ledger->type_sort == 'CASTINGIFW' || $customer_ledger->type_sort == 'CASTINGIS' || $customer_ledger->type_sort == 'CASTINGRFW' || $customer_ledger->type_sort == 'CASTINGRS'){
                        if($this->app_model->have_access_role(CASTING_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                if($customer_ledger->hisab_done != '1'){
                                    $action .= '<a href="' . base_url("casting/casting_entry/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    } else if($customer_ledger->type_sort == 'MCHAINIFW' || $customer_ledger->type_sort == 'MCHAINIS' || $customer_ledger->type_sort == 'MCHAINRFW' || $customer_ledger->type_sort == 'MCHAINRS'){
                        if($this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                if($customer_ledger->hisab_done != '1'){
                                    $action .= '<a href="' . base_url("machine_chain/machine_chain/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    } else if($customer_ledger->type_sort == 'O P' || $customer_ledger->type_sort == 'O S' || $customer_ledger->type_sort == 'O Payment' || $customer_ledger->type_sort == 'O Receipt'){
                        if($this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "edit")){
                            if (in_array($customer_ledger->department_id, $department_ids)){
                                $action .= '<a href="' . base_url("other/add/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                            }
                        }
                    } else if($customer_ledger->type_sort == 'Hisab Fine' || $customer_ledger->type_sort == 'HD-I' || $customer_ledger->type_sort == 'HD-R' || $customer_ledger->type_sort == 'Hisab Fine S' || $customer_ledger->type_sort == 'HD-I S' || $customer_ledger->type_sort == 'HD-R S' || $customer_ledger->type_sort == 'MHM Hisab Fine' || $customer_ledger->type_sort == 'MHM HD-I' || $customer_ledger->type_sort == 'MHM HD-R' || $customer_ledger->type_sort == 'CASTING Hisab Fine' || $customer_ledger->type_sort == 'CASTING HD-I' || $customer_ledger->type_sort == 'CASTING HD-R' || $customer_ledger->type_sort == 'MCHAIN Hisab Fine' || $customer_ledger->type_sort == 'MCHAIN HD-I' || $customer_ledger->type_sort == 'MCHAIN HD-R'){
                        $action .= '';
                    } else {
                        if(isset($customer_ledger->journal_id) && !empty($customer_ledger->journal_id)){
                            if($this->app_model->have_access_role(JOURNAL_MODULE_ID, "edit")){
                                if (in_array($customer_ledger->department_id, $department_ids)){
                                    $action .= '<a href="' . base_url("journal/add/" . $customer_ledger->journal_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        } else if(isset($customer_ledger->st_id) && !empty($customer_ledger->st_id)){
                            if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit")){
                                if (in_array($customer_ledger->department_id, $department_ids)){
                                    $action .= '<a href="' . base_url("sell_purchase_type_2/add/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        } else if(isset($customer_ledger->pay_rece_id) && !empty($customer_ledger->pay_rece_id)){
                            if($this->app_model->have_access_role(CASHBOOK_MODULE_ID, "edit")){
                                if (in_array($customer_ledger->department_id, $department_ids)){
                                    $action .= '<a href="' . base_url("reports/cashbook/" . $customer_ledger->pay_rece_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        } else if(isset($customer_ledger->xrf_id) && !empty($customer_ledger->xrf_id)){
                            if($this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "edit")){
                                if (in_array(XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID, $department_ids)){
                                    $action .= '<a href="' . base_url("hallmark/xrf/" . $customer_ledger->xrf_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                                }
                            }
                        }
                    }
                }
                    
//            if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount) && $post_data['from_zero'] == 1 ){
            if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount) && $post_data['from_zero'] == 1 && isset($customer_ledger->group_name) ? $customer_ledger->group_name != '3' : ''){
                $data = array();
            } else {
                
                if($current_date != '' && $current_date != $customer_ledger->st_date){
                    $row = array();
                    $row[] = '';
                    $row[] = '';
                    $row[] = '<b>Total</b>';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
//                    $row[] = '<b>' . number_format((float) $total_grwt, 3, '.', '') . '</b>';
//                    $row[] = '<b>' . number_format((float) $total_less, 3, '.', '') . '</b>';
//                    $row[] = '<b>' . number_format((float) $total_net_wt, 3, '.', '') . '</b>';
                    if ($total_gold_fine != 0 && $total_net_wt != 0) {
                        $tunch = number_format((float) $total_gold_fine * 100 / (float) $total_net_wt, 2, '.', '');
                    } else {
                        $tunch = 0;
                    }
//                    $row[] = '<b>' . $tunch . '</b>';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '<b>' . number_format((float) $total_gold_fine, 3, '.', '') . '</b>';
                    $row[] = '<b>' . number_format((float) $total_silver_fine, 3, '.', '') . '</b>';
                    $row[] = '<b>' . number_format((float) $total_amount, 2, '.', '') . '</b>';
                    $row[] = '<b>' . number_format((float) $total_c_amount, 2, '.', '') . '</b>';
                    $row[] = '<b>' . number_format((float) $total_r_amount, 2, '.', '') . '</b>';
                    $row[] = '';
                    $row[] = '';
                    $row[] = $total_gold_fine;
                    $row[] = $total_silver_fine;
                    $row[] = $total_amount;
                    $data[] = $row;
                    $daywise_total_gold_fine = $daywise_total_gold_fine + $total_gold_fine;
                    $daywise_total_silver_fine = $daywise_total_silver_fine + $total_silver_fine;
                    $daywise_total_amount = $daywise_total_amount + $total_amount;
                } 
                $current_date = $customer_ledger->st_date;
                if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount) && $post_data['from_zero'] == 1 && isset($customer_ledger->group_name) ? $customer_ledger->group_name == '3' : ''){
                    $gold_total_grwt = 0;
                    $gold_total_less = 0;
                    $gold_total_net_wt = 0;

                    $silver_total_grwt = 0;
                    $silver_total_less = 0;
                    $silver_total_net_wt = 0;
                }
//                if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount) && $post_data['from_zero'] == 1){
                if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount) && $post_data['from_zero'] == 1 && isset($customer_ledger->group_name) ? $customer_ledger->group_name != '3' : ''){
                    $total_grwt = 0;
                    $total_less = 0;
                    $total_net_wt = 0;
                    $total_gold_fine = 0;
                    $total_silver_fine = 0;
                    $total_amount = 0;
                    
                    $gold_total_grwt = 0;
                    $gold_total_less = 0;
                    $gold_total_net_wt = 0;
                    $gold_total_gold_fine = 0;
                    $gold_total_silver_fine = 0;
                    $daterange_total_amount = 0;

                    $silver_total_grwt = 0;
                    $silver_total_less = 0;
                    $silver_total_net_wt = 0;
                    $silver_total_gold_fine = 0;
                    $silver_total_silver_fine = 0;
//                    $silver_total_amount = 0;
                } else {
                    if (($display_opening != 1 || ($display_opening == 1 && $key != '0')) || ($display_opening == 1 && $key == '0')) {
                        $total_grwt = number_format((float) $total_grwt, 3, '.', '') + number_format((float) $customer_ledger->grwt, 3, '.', '');
                        $total_less = number_format((float) $total_less, 3, '.', '') + number_format((float) $customer_ledger->less, 3, '.', '');
                        $total_net_wt = number_format((float) $total_net_wt, 3, '.', '') + number_format((float) $customer_ledger->net_wt, 3, '.', '');
                        $total_gold_fine = number_format((float) $total_gold_fine, 3, '.', '') + number_format((float) $customer_ledger->gold_fine, 3, '.', '');
                        $total_silver_fine = number_format((float) $total_silver_fine, 3, '.', '') + number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                        $total_amount = number_format((float) $total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                        
                        if($key != '0'){
                            if($customer_ledger->group_name == CATEGORY_GROUP_GOLD_ID){
                                $gold_total_grwt = number_format((float) $gold_total_grwt, 3, '.', '') + number_format((float) $customer_ledger->grwt, 3, '.', '');
                                $gold_total_less = number_format((float) $gold_total_less, 3, '.', '') + number_format((float) $customer_ledger->less, 3, '.', '');
                                $gold_total_net_wt = number_format((float) $gold_total_net_wt, 3, '.', '') + number_format((float) $customer_ledger->net_wt, 3, '.', '');
                                $gold_total_gold_fine = number_format((float) $gold_total_gold_fine, 3, '.', '') + number_format((float) $customer_ledger->gold_fine, 3, '.', '');
//                                $gold_total_silver_fine = number_format((float) $gold_total_silver_fine, 3, '.', '') + number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                                $daterange_total_amount = number_format((float) $daterange_total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                            } else if($customer_ledger->group_name == CATEGORY_GROUP_SILVER_ID){
                                $silver_total_grwt = number_format((float) $silver_total_grwt, 3, '.', '') + number_format((float) $customer_ledger->grwt, 3, '.', '');
                                $silver_total_less = number_format((float) $silver_total_less, 3, '.', '') + number_format((float) $customer_ledger->less, 3, '.', '');
                                $silver_total_net_wt = number_format((float) $silver_total_net_wt, 3, '.', '') + number_format((float) $customer_ledger->net_wt, 3, '.', '');
//                                $silver_total_gold_fine = number_format((float) $silver_total_gold_fine, 3, '.', '') + number_format((float) $customer_ledger->gold_fine, 3, '.', '');
                                $silver_total_silver_fine = number_format((float) $silver_total_silver_fine, 3, '.', '') + number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                                $daterange_total_amount = number_format((float) $daterange_total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                            } else if($customer_ledger->group_name == CATEGORY_GROUP_OTHER_ID){
                                $daterange_total_amount = number_format((float) $daterange_total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                            } else if($customer_ledger->group_name == '4'){ // Payment Receipt, Journal, Cashbook
                                if($customer_ledger->type_sort == 'Payment' || $customer_ledger->type_sort == 'Receipt' || $customer_ledger->type_sort == 'J Naam' || $customer_ledger->type_sort == 'J Jama' || $customer_ledger->type_sort == 'C R' || $customer_ledger->type_sort == 'C P' || $customer_ledger->type_sort == 'O Payment' || $customer_ledger->type_sort == 'O Receipt'){
                                    $daterange_total_amount = number_format((float) $daterange_total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                                } elseif ($customer_ledger->type_sort == 'TR Naam' || $customer_ledger->type_sort == 'TR Jama') {
                                    $gold_total_gold_fine = number_format((float) $gold_total_gold_fine, 3, '.', '') + number_format((float) $customer_ledger->gold_fine, 3, '.', '');
                                    $silver_total_silver_fine = number_format((float) $silver_total_silver_fine, 3, '.', '') + number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                                    $daterange_total_amount = number_format((float) $daterange_total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                                }
                            }
                        }
                    }
                }
                
                $row = array();
                $row[] = $action;
                $row[] = (!empty(strtotime($customer_ledger->st_date))) ? date('d-m-Y', strtotime($customer_ledger->st_date)) : '';
                $particular = ($customer_ledger->account_name != 'account_name') ? $customer_ledger->account_name : '' ;
                $particular .= (isset($customer_ledger->interest_account_id) && $customer_ledger->interest_account_id == $customer_ledger->account_id) ? ' <a href="' . base_url("reports/interest/" . $customer_ledger->journal_id . "/" . $customer_ledger->account_id ) . '" target="_blank" title="Interest" ><b>Interest</b></a>' : '' ;
                if($customer_ledger->type_sort == 'E' || $customer_ledger->type_sort == 'P' || $customer_ledger->type_sort == 'S'){
                    if($display_line_item_remark_in_ledger == '1'){
                        $particular .= '<small> - '.$customer_ledger->li_narration . '</small>';
                    }
                }
                if($customer_ledger->type_sort == 'GB S' || $customer_ledger->type_sort == 'GB P'){
                    $particular .= ' @'.$customer_ledger->gold_rate;
                }
                if($customer_ledger->type_sort == 'SB S' || $customer_ledger->type_sort == 'SB P'){
                    $particular .= ' @'.$customer_ledger->silver_rate;
                }
                $row[] = $particular;
                $row[] = $customer_ledger->type_sort;
                if($key == '0' && $customer_ledger->account_name == 'Opening Balance'){
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                } else {
                    $row[] = number_format((float) $customer_ledger->grwt, 3, '.', '');
                    $row[] = isset($customer_ledger->stone_wt) ? number_format((float) $customer_ledger->stone_wt, 3, '.', '') : '';
                    $row[] = isset($customer_ledger->sijat) ? number_format((float) $customer_ledger->sijat, 3, '.', '') : '';
                    $row[] = number_format((float) $customer_ledger->less, 3, '.', '');
                    $row[] = number_format((float) $customer_ledger->net_wt, 3, '.', '');
                    $row[] = number_format((float) $customer_ledger->touch_id, 2, '.', '');
                    $wastage_labour = '';
                    if(isset($customer_ledger->wastage_labour)){
                        $wastage_labour = ($customer_ledger->wastage_labour == '1') ? 'Wastage' : 'Labour';
                    }
                    $row[] = $wastage_labour;
                    $row[] = isset($customer_ledger->wastage_labour_value) ? number_format((float) $customer_ledger->wastage_labour_value, 3, '.', '') : number_format((float) $customer_ledger->wstg, 3, '.', '');
                    $row[] = isset($customer_ledger->labour_amount) ? number_format((float) $customer_ledger->labour_amount, 3, '.', '') : '';
                }
//                $row[] = number_format((float) $customer_ledger->grwt, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->less, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->net_wt, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->touch_id, 2, '.', '');
//                $row[] = number_format((float) $customer_ledger->wstg, 3, '.', '');
                $row[] = isset($customer_ledger->stone_qty) ? number_format((float) $customer_ledger->stone_qty, 3, '.', '') : '';
                $row[] = isset($customer_ledger->stone_rs) ? number_format((float) $customer_ledger->stone_rs, 3, '.', '') : '';
                $row[] = number_format((float) $customer_ledger->gold_fine, 3, '.', '');
                $row[] = number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                $row[] = number_format((float) $customer_ledger->amount, 2, '.', '');
                $c_amt = '';
                if(isset($customer_ledger->c_amt)){
                    $c_amt = number_format((float) $customer_ledger->c_amt, 2, '.', '');
                    $total_c_amount = $total_c_amount + $c_amt;
                }
                $row[] = $c_amt;
                $r_amt = '';
                if(isset($customer_ledger->r_amt)){
                    $r_amt = number_format((float) $customer_ledger->r_amt, 2, '.', '');
                    $total_r_amount = $total_r_amount + $r_amt;
                }
                $row[] = $r_amt;
                $row[] = $customer_ledger->created_at;
                $row[] = $customer_ledger->reference_no;
                $row[] = $total_gold_fine;
                $row[] = $total_silver_fine;
                $row[] = $total_amount;
                $data[] = $row;
            }
        }
        //print_r($post_data);die;
        if (isset($post_data['order'][0]['column']) && isset($post_data['order'][0]['dir']) && $post_data['order'][0]['dir'] == 'asc') {
            $this->applib->array_sort_by_column($data, $post_data['order'][0]['column'], SORT_ASC);
        }
        if (isset($post_data['order'][0]['column']) && isset($post_data['order'][0]['dir']) && $post_data['order'][0]['dir'] == 'desc') {
            $this->applib->array_sort_by_column($data, $post_data['order'][0]['column'], SORT_DESC);
        }
        
        $row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Closing Balance</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
//        $row[] = '<b>' . number_format((float) $total_grwt, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format((float) $total_less, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format((float) $total_net_wt, 3, '.', '') . '</b>';
        if ($total_gold_fine != 0 && $total_net_wt != 0) {
            $tunch = number_format($total_gold_fine * 100 / $total_net_wt, 2, '.', '');
        } else {
            $tunch = 0;
        }
//        $row[] = '<b>' . $tunch . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>' . number_format((float) $total_gold_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $total_silver_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $total_amount, 2, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $total_c_amount, 2, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $total_r_amount, 2, '.', '') . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        $daywise_total_gold_fine = $daywise_total_gold_fine + $total_gold_fine;
        $daywise_total_silver_fine = $daywise_total_silver_fine + $total_silver_fine;
        $daywise_total_amount = $daywise_total_amount + $total_amount;
        /*$row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Date Range Gold Total</b>';
        $row[] = '';
        $row[] = '<b>' . number_format((float) $gold_total_grwt, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $gold_total_less, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $gold_total_net_wt, 3, '.', '') . '</b>';
        if ($gold_total_gold_fine != 0 && $gold_total_net_wt != 0) {
            $tunch = number_format($gold_total_gold_fine * 100 / $gold_total_net_wt, 2, '.', '');
        } else {
            $tunch = 0;
        }
//        $row[] = '<b>' . $tunch . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>' . number_format((float) $gold_total_gold_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $gold_total_silver_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $daterange_total_amount, 2, '.', '') . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;

        $row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Date Range Silver Total</b>';
        $row[] = '';
        $row[] = '<b>' . number_format((float) $silver_total_grwt, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $silver_total_less, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $silver_total_net_wt, 3, '.', '') . '</b>';
        if ($silver_total_gold_fine != 0 && $silver_total_net_wt != 0) {
            $tunch = number_format($silver_total_gold_fine * 100 / $silver_total_net_wt, 2, '.', '');
        } else {
            $tunch = 0;
        }
//        $row[] = '<b>' . $tunch . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>' . number_format((float) $silver_total_gold_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $silver_total_silver_fine, 3, '.', '') . '</b>';
        $row[] = '<b>' . number_format((float) $daterange_total_amount, 2, '.', '') . '</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;*/

//        if($display_opening == 1){
//            $total_grwt = $total_grwt- number_format($customer_ledger_data[0]->grwt, 3, '.', '');
//            $total_less = $total_less - number_format($customer_ledger_data[0]->less, 3, '.', '');
//            $total_net_wt = $total_net_wt - number_format($customer_ledger_data[0]->net_wt, 3, '.', '');
//            $total_gold_fine = $total_gold_fine - number_format($customer_ledger_data[0]->gold_fine, 3, '.', '');
//            $total_silver_fine = $total_silver_fine - number_format($customer_ledger_data[0]->silver_fine, 3, '.', '');
//            $total_amount = $total_amount - number_format($customer_ledger_data[0]->amount, 2, '.', '');
//        }
//        $row = array();
//        $row[] = '';
//        $row[] = '';
//        $row[] = '<b>Date Range Gold Total</b>';
//        $row[] = '';
//        $row[] = '<b>' . number_format($total_grwt, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format($total_less, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format($total_net_wt, 3, '.', '') . '</b>';
////        if ($total_gold_fine != 0 && $total_net_wt != 0) {
////            $tunch = number_format($total_gold_fine * 100 / $total_net_wt, 2, '.', '');
////        } else {
////            $tunch = 0;
////        }
////        $row[] = '<b>' . $tunch . '</b>';
//        $row[] = '';
//        $row[] = '';
//        $row[] = '<b>' . number_format($total_gold_fine, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format($total_silver_fine, 3, '.', '') . '</b>';
//        $row[] = '<b>' . number_format($total_amount, 2, '.', '') . '</b>';
//        $row[] = '';
//        $row[] = '';
//        $row[] = '';
//        $row[] = '';
//        $row[] = '';
//        $data[] = $row;
//        echo '<pre>'; print_r($data); exit;
        if($post_data['from_zero'] == 1){
            $reverse_data = array_reverse($data);
            $from_zero_arr = $this->applib->multi_array_search($reverse_data, array('2' => '<b>Total</b>', '9' => '<b>0.000</b>', '10' => '<b>0.000</b>', '11' => '<b>0.00</b>'));
//            $from_zero_arr = $this->applib->multi_array_search($reverse_data, array('14' => '0', '15' => '0', '16' => '0'));
            if(!empty($from_zero_arr)){
                $arr_key_upto = $from_zero_arr[0];
                $reverse_data = array_slice($reverse_data, 0, $arr_key_upto);
                $data = array_reverse($reverse_data);
            }
        }
        $no_of_days = $this->daysBetween($from_date,$to_date);
        $average_gold_fine = 0;
        $average_silver_fine = 0;
        $average_amount = 0;

        if($no_of_days > 0) {
            $average_gold_fine = $daywise_total_gold_fine / $no_of_days;
            $average_silver_fine = $daywise_total_silver_fine / $no_of_days;
            $average_amount = $daywise_total_amount / $no_of_days;
        }

        $output = array(
            "no_of_days" => $no_of_days,
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
            "customer_ledger_data" => $customer_ledger_data,
            "drt_gold_total_grwt" => number_format((float) $gold_total_grwt, 3, '.', ''),
            "drt_gold_total_less" => number_format((float) $gold_total_less, 3, '.', ''),
            "drt_gold_total_net_wt" => number_format((float) $gold_total_net_wt, 3, '.', ''),
            "drt_gold_total_gold_fine" => number_format((float) $gold_total_gold_fine, 3, '.', ''),
            "drt_silver_total_grwt" => number_format((float) $silver_total_grwt, 3, '.', ''),
            "drt_silver_total_less" => number_format((float) $silver_total_less, 3, '.', ''),
            "drt_silver_total_net_wt" => number_format((float) $silver_total_net_wt, 3, '.', ''),
            "drt_silver_total_silver_fine" => number_format((float) $silver_total_silver_fine, 3, '.', ''),
            "drt_daterange_total_amount" => number_format((float) $daterange_total_amount, 2, '.', ''),

            "average_gold_fine" => number_format((float) $average_gold_fine, 3, '.', ''),
            "average_silver_fine" => number_format((float) $average_silver_fine, 3, '.', ''),
            "average_amount" => number_format((float) $average_amount, 2, '.', ''),
        );
        echo json_encode($output);
    }

    function daysBetween($from_date = '',$to_date = '') {
        if($from_date == '') {
            $from_date = date("Y-m-d");
        }
        if($to_date == '') {
            $to_date = date("Y-m-d");
        }
        $from_date = strtotime(date('Y-m-d',strtotime($from_date)));
        $to_date = strtotime(date('Y-m-d',strtotime($to_date)));

        $datediff = $to_date - $from_date;
        
        return 1 + round($datediff / (60 * 60 * 24));
    }

    function get_opening_customer_ledger($from_date, $account_id, $type_sort, $view_only_hisab) {
        $to_date = '';
        $account_group_id = $this->crud->get_column_value_by_id('account', 'account_group_id', array('account_id' => $account_id));
        if($account_group_id == DEPARTMENT_GROUP){
            $customer_ledger_data = $this->applib->get_customer_ledger_department_data_arr($from_date, $to_date, $account_id, $type_sort, '0');
        } else {
            $customer_ledger_data = $this->applib->get_customer_ledger_data_arr($from_date, $to_date, $account_id, $type_sort, $view_only_hisab, '0');
        }
        $total_grwt = 0;
        $total_less = 0;
        $total_net_wt = 0;
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $total_amount = 0;
        $total_c_amount = 0;
        $total_r_amount = 0;
        foreach ($customer_ledger_data as $key => $customer_ledger) {
            $total_grwt = (float) $total_grwt + (float) $customer_ledger->grwt;
            $total_less = (float) $total_less + (float) $customer_ledger->less;
            $total_net_wt = (float) $total_net_wt + (float) $customer_ledger->net_wt;
            $total_gold_fine = (float) $total_gold_fine + (float) $customer_ledger->gold_fine;
            $total_silver_fine = (float) $total_silver_fine + (float) $customer_ledger->silver_fine;
            $total_amount = (float) $total_amount + (float) $customer_ledger->amount;
            if(isset($customer_ledger->c_amt)){
                $total_c_amount = (float) $total_c_amount + (float) $customer_ledger->c_amt;
            }
            if(isset($customer_ledger->r_amt)){
                $total_r_amount = (float) $total_r_amount + (float) $customer_ledger->r_amt;
            }
        }
        $account_data = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
        if(!empty($account_data)){
            if($account_data[0]->gold_ob_credit_debit == '1'){
                $total_gold_fine = (float) $total_gold_fine - (float) $account_data[0]->opening_balance_in_gold;
            } else { 
                $total_gold_fine = (float) $total_gold_fine + (float) $account_data[0]->opening_balance_in_gold;
            }
            if($account_data[0]->silver_ob_credit_debit == '1'){
                $total_silver_fine = (float) $total_silver_fine - (float) $account_data[0]->opening_balance_in_silver;
            } else { 
                $total_silver_fine = (float) $total_silver_fine + (float) $account_data[0]->opening_balance_in_silver;
            }
            if($account_data[0]->rupees_ob_credit_debit == '1'){
                $total_amount = (float) $total_amount - (float) $account_data[0]->opening_balance_in_rupees;
            } else { 
                $total_amount = (float) $total_amount + (float) $account_data[0]->opening_balance_in_rupees;
            }
            if($account_data[0]->c_amount_ob_credit_debit == '1'){
                $total_c_amount = (float) $total_c_amount - (float) $account_data[0]->opening_balance_in_c_amount;
            } else { 
                $total_c_amount = (float) $total_c_amount + (float) $account_data[0]->opening_balance_in_c_amount;
            }
            if($account_data[0]->r_amount_ob_credit_debit == '1'){
                $total_r_amount = (float) $total_r_amount - (float) $account_data[0]->opening_balance_in_r_amount;
            } else { 
                $total_r_amount = (float) $total_r_amount + (float) $account_data[0]->opening_balance_in_r_amount;
            }
        }
        $tunch = 0;
        $opening_data = new stdClass();
        $opening_data->st_id = '';
        $opening_data->st_date = '';
        $opening_data->account_name = 'Opening Balance';
        $opening_data->type_sort = '';
        $opening_data->grwt = '';//$total_grwt;
        $opening_data->less = '';//$total_less;
        $opening_data->net_wt = '';//$total_net_wt;
        if ($total_gold_fine != 0 && $total_net_wt != 0) {
            $tunch = number_format((float) $total_gold_fine * 100 / (float) $total_net_wt, 2, '.', '');
        }
        $opening_data->touch_id = '';//$tunch;
        $opening_data->wstg = '';//0;
        $opening_data->gold_fine = $total_gold_fine;
        $opening_data->silver_fine = $total_silver_fine;
        $opening_data->amount = $total_amount;
        $opening_data->c_amt = $total_c_amount;
        $opening_data->r_amt = $total_r_amount;
        $opening_data->created_at = '';
        $opening_data->reference_no = '';
        return $opening_data = array($opening_data);
    }

}
