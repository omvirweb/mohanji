<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_new_sp3 extends CI_Controller {

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

    // Daybook related Functions
    function daybook($daybook_id = '') {
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(DAYBOOK_MODULE_ID, 'view')) {
            $data = array();
            $account = $this->crud->get_all_with_where('account', '', '', array('account_group_id' => CUSTOMER_GROUP));
            $data['account'] = $account;
            set_page('reports/daybook', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function calculations() {
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(CALCULATIONS_MODULE_ID, 'view')) {
            $data = array();
            set_page('reports/calculations', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
        
    }
    
    function daybook_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'sell s';
        $config['select'] = 'a.account_name, s.sell_no, SUM(IF(si.type = 1, si.grwt, CONCAT("-",si.grwt))) AS grwt, SUM(IF(si.type = 1, si.less,CONCAT("-",si.less))) AS less, SUM(IF(si.type = 1, si.net_wt,CONCAT("-",si.net_wt))) AS net_wt, IF(s.total_gold_fine >= 0, `s`.`total_gold_fine` ,"") AS dr_total_gold_fine, IF(s.total_gold_fine < 0, `s`.`total_gold_fine` ,"") AS cr_total_gold_fine, IF(s.total_silver_fine >= 0, `s`.`total_silver_fine` ,"") AS dr_total_silver_fine, IF(s.total_silver_fine < 0, `s`.`total_silver_fine` ,"") AS cr_total_silver_fine, IF(s.total_amount >= 0, `s`.`total_amount` ,"") AS dr_total_amount, IF(s.total_amount < 0, `s`.`total_amount` ,"") AS cr_total_amount, s.sell_id, s.sell_date';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_items si', 'join_by' => 'si.sell_id = s.sell_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 's.sell_no', 'IF(s.total_gold_fine >= 0, `s`.`total_gold_fine` ,"")', 'IF(s.total_gold_fine < 0, `s`.`total_gold_fine`, "")', 'IF(s.total_silver_fine >= 0, `s`.`total_silver_fine`, "")', 'IF(s.total_silver_fine < 0, `s`.`total_silver_fine`, "")', 'IF(s.total_amount >= 0, `s`.`total_amount`, "")', 'IF(s.total_amount < 0, `s`.`total_amount`, "")');
        $config['column_order'] = array('a.account_name', 's.sell_no', NULL, NULL, NULL, 'IF(s.total_gold_fine >= 0, `s`.`total_gold_fine` ,"")', 'IF(s.total_gold_fine < 0, `s`.`total_gold_fine`, "")', 'IF(s.total_silver_fine >= 0, `s`.`total_silver_fine`, "")', 'IF(s.total_silver_fine < 0, `s`.`total_silver_fine`, "")', 'IF(s.total_amount >= 0, `s`.`total_amount`, "")', 'IF(s.total_amount < 0, `s`.`total_amount`, "")');
        if (!empty($post_data['from_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 's.account_id <=', 'column_value' => $post_data['account_id']);
        }
        $config['group_by'] = 's.sell_id';
        $config['order'] = array('s.sell_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'.$this->db->last_query(); exit;

        $data = array();
        foreach ($list as $day_book) {
            $gross_wt = $day_book->grwt;
            $net_et = $day_book->net_wt;
            $metal_data = $this->crud->get_row_by_id('metal_payment_receipt', array('sell_id' => $day_book->sell_id));
            if (!empty($metal_data)) {
                foreach ($metal_data as $metal) {
                    if ($metal->metal_payment_receipt == '1') {
                        $gross_wt = $gross_wt + $metal->metal_grwt;
                        $net_et = $net_et + $metal->metal_grwt;
                    } else {
                        $gross_wt = $gross_wt - $metal->metal_grwt;
                        $net_et = $net_et - $metal->metal_grwt;
                    }
                }
            }
            $row = array();
            $row[] = $day_book->account_name;
            $row[] = $day_book->sell_no;
            $row[] = number_format($gross_wt, 3, '.', '');
            $row[] = number_format($day_book->less, 3, '.', '');
            $row[] = number_format($net_et, 3, '.', '');
            $row[] = (!empty($day_book->dr_total_gold_fine) ? number_format($day_book->dr_total_gold_fine, 3, '.', '') : '');
            $row[] = (!empty($day_book->cr_total_gold_fine) ? number_format($day_book->cr_total_gold_fine, 3, '.', '') : '');
            $row[] = (!empty($day_book->dr_total_silver_fine) ? number_format($day_book->dr_total_silver_fine, 3, '.', '') : '');
            $row[] = (!empty($day_book->cr_total_silver_fine) ? number_format($day_book->cr_total_silver_fine, 3, '.', '') : '');
            $row[] = (!empty($day_book->dr_total_amount)) ? $day_book->dr_total_amount : '';
            $row[] = (!empty($day_book->cr_total_amount)) ? $day_book->cr_total_amount : '';
            $data[] = $row;
        }
        $where = '';
        if (!empty($post_data['from_date'])) {
            $where .= ' s.journal_date >= "' . $from_date . '"';
        }
        if (!empty($post_data['to_date'])) {
            $where .= ' AND s.journal_date <= "' . $to_date . '"';
        }
        $payment_receipt = $this->crud->getFromSQL("SELECT a.account_name,IF(si.`type` = 1, `si`.`amount`, '') AS dr_total_amount, IF(si.`type` = 1, '', `si`.`amount`) AS cr_total_amount FROM `journal` `s` LEFT JOIN `journal_details` `si` ON `si`.`journal_id` = `s`.`journal_id` LEFT JOIN `account` `a` ON `a`.`account_id` = `si`.`account_id` WHERE " . $where);
        foreach ($payment_receipt as $day_book) {
            $row = array();
            $row[] = $day_book->account_name;
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = (!empty($day_book->dr_total_amount)) ? $day_book->dr_total_amount : '';
            $row[] = (!empty($day_book->cr_total_amount)) ? $day_book->cr_total_amount : '';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list), //$this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    // Goldbook related Functions
    function goldbook() {
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(GOLDBOOK_MODULE_ID, 'view')) {
            $data = array();
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            $data['gold_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_min'));
            $data['gold_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_max'));
            $data['silver_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_min'));
            $data['silver_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_max'));
            set_page('reports/goldbook', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_goldbook_opening_closing_balance() {
        $data = array();
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $date = date('Y-m-d');

        $where = '';
        if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
            $where .= ' AND s.process_id = "'.$_POST['department_id'].'"';
        }
        if(isset($_POST['account_id']) && !empty($_POST['account_id'])){
            $where .= ' AND s.account_id = "'.$_POST['account_id'].'"';
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $where .= ' AND s.process_id IN('.$department_ids.')';
        }
        $open_sell = $this->crud->getFromSQL('SELECT SUM(gb.gold_weight) AS open_gold_weight, SUM(gb.gold_value) AS open_gold_value FROM gold_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.gold_sale_purchase = 1 AND s.sell_date < "' . $from_date . '" '. $where . ' ');
        $open_purchase = $this->crud->getFromSQL('SELECT SUM(gb.gold_weight) AS open_gold_weight, SUM(gb.gold_value) AS open_gold_value FROM gold_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.gold_sale_purchase = 2 AND s.sell_date < "' . $from_date . '" '. $where . ' ');
        $open_gold_weight = (float) $open_purchase[0]->open_gold_weight - (float) $open_sell[0]->open_gold_weight;
        $open_gold_value = (float) $open_purchase[0]->open_gold_value - (float) $open_sell[0]->open_gold_value;
        $data['opening_gold_weight'] = $open_gold_weight;
        $data['opening_gold_value'] = $open_gold_value;
        if(!empty($open_gold_value) && !empty($open_gold_weight)){
            $data['opening_gold_rate'] = $open_gold_value * 10 / $open_gold_weight;
        } else {
            $data['opening_gold_rate'] = 0;
        }
        
        $close_sell = $this->crud->getFromSQL('SELECT SUM(gb.gold_weight) AS close_gold_weight, SUM(gb.gold_value) AS close_gold_value FROM gold_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.gold_sale_purchase = 1 AND s.sell_date >= "' . $from_date . '" AND s.sell_date <= "' . $to_date . '" '. $where . ' ');
        $close_purchase = $this->crud->getFromSQL('SELECT SUM(gb.gold_weight) AS close_gold_weight, SUM(gb.gold_value) AS close_gold_value FROM gold_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.gold_sale_purchase = 2 AND s.sell_date >= "' . $from_date . '"AND s.sell_date <= "' . $to_date . '" '. $where . ' ');
        $close_gold_weight = (float) $close_purchase[0]->close_gold_weight - (float) $close_sell[0]->close_gold_weight;
        $close_gold_value = (float) $close_purchase[0]->close_gold_value - (float) $close_sell[0]->close_gold_value;
        $data['closeing_gold_weight'] = (float) $open_gold_weight + (float) $close_gold_weight;
        $data['closeing_gold_value'] = (float) $open_gold_value + (float) $close_gold_value;
        if(!empty($data['closeing_gold_value']) && !empty($data['closeing_gold_weight'])){
            $data['closeing_gold_rate'] = (float) $data['closeing_gold_value'] * 10 / (float) $data['closeing_gold_weight'];
        } else {
            $data['closeing_gold_rate'] = 0;
        }
        
        $data['date_range_gold_weight'] = $close_gold_weight;
        $data['date_range_gold_value'] = $close_gold_value;
        if(!empty($close_gold_value) && !empty($close_gold_weight)){
            $data['date_range_gold_rate'] = (float) $close_gold_value * 10 / (float) $close_gold_weight;
        } else {
            $data['date_range_gold_rate'] = 0;
        }
        
        print json_encode($data);
        exit;
    }
    
    function goldbook_purchase_sell_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'gold_bhav gb';
        $config['select'] = 'gb.*, d.account_name as department, a.account_name, s.sell_no, s.sell_date';
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = gb.sell_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account d', 'join_by' => 'd.account_id = s.process_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.account_id ', 'join_type' => 'left');
        $config['column_search'] = array('DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 's.sell_no', 'a.account_name', 'gb.gold_weight', 'gb.gold_rate', 'gb.gold_value');
        $config['column_order'] = array(null, 's.sell_date', 's.sell_no', 'a.account_name', 'gb.gold_weight', 'gb.gold_rate', 'gb.gold_value');
        $config['order'] = array('gb.gold_id' => 'asc');
        $config['wheres'][] = array('column_name' => 'gb.gold_sale_purchase', 'column_value' => $post_data['gold_sale_purchase']);
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 's.process_id IN('.$department_ids.')';
        }
        if (!empty($post_data['from_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['department_id'])) {
            $config['wheres'][] = array('column_name' => 's.process_id', 'column_value' => $post_data['department_id']);
        }
        if (!empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 's.account_id', 'column_value' => $post_data['account_id']);
        }
        $config['column_search'] = array('s.sell_no','DATE_FORMAT(s.sell_date,"%d-%m-%Y")','a.account_name','gb.gold_weight','gb.gold_rate','gb.gold_value');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        foreach ($list as $goldbook) {
            $row = array();
            $action = '';
            if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit")){
                $action .= '<a href="' . base_url("sell/add/" . $goldbook->sell_id) . '" target="_blank" ><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $goldbook->sell_date ? date('d-m-Y', strtotime($goldbook->sell_date)) : '';
            $row[] = $goldbook->sell_no;
            $row[] = $goldbook->account_name;
            $row[] = number_format($goldbook->gold_weight, '3', '.', '');
            $row[] = $goldbook->gold_rate;
            $row[] = $goldbook->gold_value;
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
    
    // Silverbook related Functions
    function silverbook() {
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(GOLDBOOK_MODULE_ID, 'view')) {
            $data = array();
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            $data['silver_min'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_min'));
            $data['silver_max'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_max'));
            set_page('reports/silverbook', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_silverbook_opening_closing_balance() {
        $data = array();
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $date = date('Y-m-d');

        $where = '';
        if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
            $where .= ' AND s.process_id = "'.$_POST['department_id'].'"';
        }
        if(isset($_POST['account_id']) && !empty($_POST['account_id'])){
            $where .= ' AND s.account_id = "'.$_POST['account_id'].'"';
        }
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $where .= ' AND s.process_id IN('.$department_ids.')';
        }
        $open_sell = $this->crud->getFromSQL('SELECT SUM(gb.silver_weight) AS open_silver_weight, SUM(gb.silver_value) AS open_silver_value FROM silver_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.silver_sale_purchase = 1 AND s.sell_date < "' . $from_date . '" '. $where . ' ');
        $open_purchase = $this->crud->getFromSQL('SELECT SUM(gb.silver_weight) AS open_silver_weight, SUM(gb.silver_value) AS open_silver_value FROM silver_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.silver_sale_purchase = 2 AND s.sell_date < "' . $from_date . '" '. $where . ' ');
        $open_silver_weight = (float) $open_purchase[0]->open_silver_weight - (float) $open_sell[0]->open_silver_weight;
        $open_silver_value = (float) $open_purchase[0]->open_silver_value - (float) $open_sell[0]->open_silver_value;
        $data['opening_silver_weight'] = $open_silver_weight;
        $data['opening_silver_value'] = $open_silver_value;
        if(!empty($open_silver_value) && !empty($open_silver_weight)){
            $data['opening_silver_rate'] = (float) $open_silver_value * 10 / (float) $open_silver_weight;
        } else {
            $data['opening_silver_rate'] = 0;
        }
        
        $close_sell = $this->crud->getFromSQL('SELECT SUM(gb.silver_weight) AS close_silver_weight, SUM(gb.silver_value) AS close_silver_value FROM silver_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.silver_sale_purchase = 1 AND s.sell_date >= "' . $from_date . '" AND s.sell_date <= "' . $to_date . '" '. $where . ' ');
        $close_purchase = $this->crud->getFromSQL('SELECT SUM(gb.silver_weight) AS close_silver_weight, SUM(gb.silver_value) AS close_silver_value FROM silver_bhav gb JOIN sell s ON s.sell_id = gb.sell_id WHERE gb.silver_sale_purchase = 2 AND s.sell_date >= "' . $from_date . '"AND s.sell_date <= "' . $to_date . '" '. $where . ' ');
        $close_silver_weight = (float) $close_purchase[0]->close_silver_weight - (float) $close_sell[0]->close_silver_weight;
        $close_silver_value = (float) $close_purchase[0]->close_silver_value - (float) $close_sell[0]->close_silver_value;
        $data['closeing_silver_weight'] = (float) $open_silver_weight + (float) $close_silver_weight;
        $data['closeing_silver_value'] = (float) $open_silver_value + (float) $close_silver_value;
        if(!empty($data['closeing_silver_value']) && !empty($data['closeing_silver_weight'])){
            $data['closeing_silver_rate'] = (float) $data['closeing_silver_value'] * 10 / (float) $data['closeing_silver_weight'];
        } else {
            $data['closeing_silver_rate'] = 0;
        }
        
        $data['date_range_silver_weight'] = $close_silver_weight;
        $data['date_range_silver_value'] = $close_silver_value;
        if(!empty($close_silver_value) && !empty($close_silver_weight)){
            $data['date_range_silver_rate'] = (float) $close_silver_value * 10 / (float) $close_silver_weight;
        } else {
            $data['date_range_silver_rate'] = 0;
        }
        
        print json_encode($data);
        exit;
    }
    
    function silverbook_purchase_sell_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'silver_bhav gb';
        $config['select'] = 'gb.*, d.account_name as department, a.account_name, s.sell_no, s.sell_date';
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = gb.sell_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account d', 'join_by' => 'd.account_id = s.process_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.account_id ', 'join_type' => 'left');
        $config['column_search'] = array('DATE_FORMAT(s.sell_date,"%d-%m-%Y")', 's.sell_no', 'a.account_name', 'gb.silver_weight', 'gb.silver_rate', 'gb.silver_value');
        $config['column_order'] = array(null, 's.sell_date', 's.sell_no', 'a.account_name', 'gb.silver_weight', 'gb.silver_rate', 'gb.silver_value');
        $config['order'] = array('gb.silver_id' => 'asc');
        $config['wheres'][] = array('column_name' => 'gb.silver_sale_purchase', 'column_value' => $post_data['silver_sale_purchase']);
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 's.process_id IN('.$department_ids.')';
        }
        if (!empty($post_data['from_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['department_id'])) {
            $config['wheres'][] = array('column_name' => 's.process_id', 'column_value' => $post_data['department_id']);
        }
        if (!empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 's.account_id', 'column_value' => $post_data['account_id']);
        }
        $config['column_search'] = array('s.sell_no','DATE_FORMAT(s.sell_date,"%d-%m-%Y")','a.account_name','gb.silver_weight','gb.silver_rate','gb.silver_value');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        foreach ($list as $silverbook) {
            $row = array();
            $action = '';
            if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit")){
                $action .= '<a href="' . base_url("sell/add/" . $silverbook->sell_id) . '" target="_blank" ><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $silverbook->sell_date ? date('d-m-Y', strtotime($silverbook->sell_date)) : '';
            $row[] = $silverbook->sell_no;
            $row[] = $silverbook->account_name;
            $row[] = number_format($silverbook->silver_weight, '3', '.', '');
            $row[] = $silverbook->silver_rate;
            $row[] = $silverbook->silver_value;
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
    
    // Stock Status related Functions
    function stock_status() {
        if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "view") && $this->applib->have_access_role(REPORT_MODULE_ID, 'view')) {
            $data = array();
            $data['process'] = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            $data['category'] = $this->crud->get_all_records('category', 'category_id', '');
            $data['items'] = $this->crud->get_all_records('item_master', 'item_id', '');
            $data['carat'] = $this->crud->get_all_records('carat', 'purity', 'ASC');
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            set_page('reports/stock_status', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function stock_status_datatable() {
        $post_data = $this->input->post();
        $gold_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $silver_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));

        $use_rfid = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_rfid'));
        $use_barcode = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_barcode'));
        
        $config['table'] = 'item_stock s';
        if($post_data['include_wstg'] == 'true'){
            $config['select'] = '`s`.`department_id`, `s`.`category_id`, `s`.`item_id`, `s`.`item_stock_id`, `s`.`tunch`, `s`.`rfid_created_grwt`,`cat`.`category_name`, `im`.`item_name`, `im`.`stock_method`, `cat`.`category_group_id`,SUM(s.grwt) AS grwt,SUM(s.ntwt) AS ntwt,sum(s.less) AS less, SUM((s.ntwt * (s.tunch + im.default_wastage))/100) AS fine';
        } else {
            $config['select'] = '`s`.`department_id`, `s`.`category_id`, `s`.`item_id`, `s`.`item_stock_id`, `s`.`tunch`, `s`.`rfid_created_grwt`,`cat`.`category_name`, `im`.`item_name`, `im`.`stock_method`, `cat`.`category_group_id`,SUM(s.grwt) AS grwt,SUM(s.ntwt) AS ntwt,sum(s.less) AS less, SUM((s.ntwt * s.tunch)/100) AS fine';
        }
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = s.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = s.department_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = s.category_id', 'join_type' => 'left');
        $config['column_search'] = array('cat.category_name', 'im.item_name', 's.grwt', 's.less', 's.ntwt', 's.tunch', 's.fine');
        $config['column_order'] = array('cat.category_name', 'im.item_name', 'grwt', null, 's.less', 's.ntwt', 's.tunch', 's.fine', 's.fine');
        $config['custom_where'] = ' (im.stock_method = 1 AND (s.grwt = 0 OR s.grwt != 0) OR (im.stock_method = 2 AND s.grwt != 0) OR (im.stock_method = 3 AND s.grwt != 0))';
//        $config['custom_where'] = ' 1 ';
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= 'AND s.department_id IN('.$department_ids.')';
        }
        if (isset($post_data['category_id']) && !empty($post_data['category_id'])) {
            $config['wheres'][] = array('column_name' => 's.category_id', 'column_value' => $post_data['category_id']);
        }
        if (isset($post_data['item_id']) && !empty($post_data['item_id'])) {
            $config['wheres'][] = array('column_name' => 's.item_id', 'column_value' => $post_data['item_id']);
        }
        if (isset($post_data['tunch']) && !empty($post_data['tunch'])) {
            $config['wheres'][] = array('column_name' => 's.tunch', 'column_value' => $post_data['tunch']);
        }
        if (isset($post_data['department_id']) && !empty($post_data['department_id'])) {
            $config['wheres'][] = array('column_name' => 's.department_id', 'column_value' => $post_data['department_id']);
        }
        if($post_data['in_stock'] == 'true'){
            $config['custom_where'] .= ' AND s.grwt != 0';
        }
        if (isset($post_data['department_id']) && !empty($post_data['department_id'])) {
            if (isset($post_data['item_wise']) && $post_data['item_wise'] == 'true') {
                $config['group_by'] = 's.category_id,s.item_id, if(`im`.`stock_method` = 1, `s`.`tunch`, ""), if(`im`.`stock_method` = 2, `s`.`item_stock_id`, "")';
            } else {
                $config['group_by'] = 's.category_id,s.item_id, if(`im`.`stock_method` = 1, `s`.`tunch`, "")';
            }
        } else {
            if (isset($post_data['item_wise']) && $post_data['item_wise'] == 'true') {
                $config['group_by'] = 'if(`im`.`stock_method` = 2, s.department_id, ""), s.category_id, s.item_id, if(`im`.`stock_method` = 1, `s`.`tunch`, ""), if(`im`.`stock_method` = 2, `s`.`item_stock_id`, "")';
            } else {
                $config['group_by'] = 's.category_id, s.item_id, if(`im`.`stock_method` = 1, `s`.`tunch`, "")';
            }
        }
        if (isset($post_data['rfid_filter']) && $post_data['rfid_filter'] == '1') {
            $config['custom_where'] .= ' AND s.item_stock_id IN ( SELECT `item_stock_id` FROM `item_stock_rfid` WHERE `rfid_used` = 0)';
        } else if (isset($post_data['rfid_filter']) && $post_data['rfid_filter'] == '2') {
            $config['custom_where'] .= ' AND s.item_stock_id NOT IN ( SELECT `item_stock_id` FROM `item_stock_rfid` WHERE `rfid_used` = 0)';
        }
        $config['order'] = array('s.item_stock_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'.$this->db->last_query(); exit;
//        echo '<pre>'; print_r($list); exit;
        $data = array();
        $gold_grwt = 0;
        $gold_rfid_created_grwt = 0;
        $gold_rfid_not_created_grwt = 0;
        $gold_ntwt = 0;
        $gold_tunch = 0;
        $gold_fine = 0;
        $silver_grwt = 0;
        $silver_rfid_created_grwt = 0;
        $silver_rfid_not_created_grwt = 0;
        $silver_ntwt = 0;
        $silver_tunch = 0;
        $silver_fine = 0;
        $gold_amount = 0;
        $silver_amount = 0;
        foreach ($list as $stock) {
            $gold = 0;
            $silver = 0;
            $less = 0;
            $ntwt = 0;
            $tunch = 0;
            $less = 0;
            $date_wise_stock = '';
            $stock_adjust_btn = '';
//            if (isset($post_data['stock_status_upto']) && !empty($post_data['stock_status_upto'])) {
//                $stock_status_upto = date('Y-m-d', strtotime("+1 day", strtotime($post_data['stock_status_upto'])));
//                if ($stock->stock_method == '2' && isset($post_data['item_wise']) && $post_data['item_wise'] != 'true') {
//                    $date_wise_stock = $this->get_opening_stock_ledger($stock_status_upto, $stock->department_id, $stock->category_id, $stock->item_id, $stock->tunch='', '0', $type_sort = '', $include_wastage = 'false');
//                } else {
//                    $date_wise_stock = $this->get_opening_stock_ledger($stock_status_upto, $stock->department_id, $stock->category_id, $stock->item_id, $stock->tunch, '0', $type_sort = '', $include_wastage = 'false');   
//                }
////                echo '<pre>'; print_r($date_wise_stock); exit;
//                $grwt = number_format($date_wise_stock[0]->balance_grwt, 3, '.', '');
//                $less = number_format($date_wise_stock[0]->less, 3, '.', '');
//                $ntwt = number_format($date_wise_stock[0]->balance_net_wt, 3, '.', '');
//                if ($stock->category_group_id == CATEGORY_GROUP_GOLD_ID) {
//                    $gold = number_format($date_wise_stock[0]->balance_fine, 3, '.', '');
//                } else if ($stock->category_group_id == CATEGORY_GROUP_SILVER_ID) {
//                    $silver = number_format($date_wise_stock[0]->balance_fine, 3, '.', '');
//                }
//                $tunch = number_format($date_wise_stock[0]->touch_id, 2, '.', '');
////                echo '<pre>'; print_r($date_wise_stock[0]->touch_id); exit;
//                if($stock->stock_method == STOCK_METHOD_ITEM_WISE){
//                    if (isset($post_data['item_wise']) && $post_data['item_wise'] != 'true') {
//                        if($date_wise_stock[0]->net_wt != '0.000'){
//                            $tunch = number_format(($date_wise_stock[0]->balance_fine / $date_wise_stock[0]->balance_net_wt) * 100, 2, '.', '');
//                        }
//                    }
//                } else {
//                    $tunch = number_format($stock->tunch, 2, '.', '');
//                    if(!empty($grwt) && $grwt != '0.000'){
//                        if($this->app_model->have_access_role(STOCK_ADJUST_ID, "view")){
//                            $less_allow = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $stock->item_id));
//                            $wstg = $this->crud->get_min_value('sell_items', 'wstg');
//                            $wstg = $wstg->wstg;
//                            $stock_adjust_btn = '<a href="javascript:void(0);" class="btn btn-primary btn-xs item_stock_row" data-department_id="' . $stock->department_id . '" data-category_id="' . $stock->category_id . '" data-item_id="' . $stock->item_id . '" data-category_group_id="' . $stock->category_group_id . '" data-grwt="' . $grwt . '" data-less_allow="' . $less_allow . '" data-less="' . $less . '" data-ntwt="' . $ntwt . '" data-tunch="' . $stock->tunch . '" data-wstg="' . $wstg . '" data-fine="' . number_format($date_wise_stock[0]->balance_fine, 3, '.', '') . '" data-item_stock_id="' . $stock->item_stock_id . '" > Adjust </a>';
//                        }
//                    }
//                }
//            } else {
                $less_allow = 0;
                $wstg = '0';
                $grwt = number_format((float) $stock->grwt, 3, '.', '');
                $less = number_format((float) $stock->less, 3, '.', '');
                $ntwt = number_format((float) $stock->ntwt, 3, '.', '');
                if ($stock->category_group_id == 1) {
                    $gold = number_format((float) $stock->fine, 3, '.', '');
                } else if ($stock->category_group_id == 2) {
                    $silver = number_format((float) $stock->fine, 3, '.', '');
                }
                $tunch = number_format((float) $stock->tunch, 2, '.', '');
                
                if($stock->stock_method == '3'){
                    if($grwt != '0.000'){
                        if($this->app_model->have_access_role(STOCK_ADJUST_ID, "view")){
                            if($ntwt != '0.000'){
                                $tunch = number_format(((float) $stock->fine / (float) $ntwt) * 100, 2, '.', '');
                            }
                            $less_allow = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $stock->item_id));
    //                        $wstg = $this->crud->get_min_value('sell_items', 'wstg');
    //                        $wstg = $wstg->wstg;
                            $stock_adjust_btn = '<a href="javascript:void(0);" class="btn btn-primary btn-xs item_stock_row pull-left" data-department_id="' . $stock->department_id . '" data-category_id="' . $stock->category_id . '" data-item_id="' . $stock->item_id . '" data-category_group_id="' . $stock->category_group_id . '" data-grwt="' . $grwt . '" data-less_allow="' . $less_allow . '" data-less="' . $less . '" data-ntwt="' . $ntwt . '" data-tunch="' . $stock->tunch . '" data-wstg="' . $wstg . '" data-fine="' . number_format($stock->fine, 3, '.', '') . '" data-item_stock_id="' . $stock->item_stock_id . '" > Adjust </a>';
                        }
                    }
                } else if($stock->stock_method == '2'){
                    if (isset($post_data['item_wise']) && $post_data['item_wise'] != 'true') {
                        if($ntwt != '0.000'){
                            $tunch = number_format(((float) $stock->fine / (float) $ntwt) * 100, 2, '.', '');
                        }
                    }
                } else {
                    $tunch = number_format((float) $stock->tunch, 2, '.', '');
                    if(!empty($grwt) && $grwt != '0.000'){
                        if($this->app_model->have_access_role(STOCK_ADJUST_ID, "view")){
                            $less_allow = $this->crud->get_column_value_by_id('item_master', 'less', array('item_id' => $stock->item_id));
//                            $wstg = $this->crud->get_min_value('sell_items', 'wstg');
//                            $wstg = $wstg->wstg;
                            $stock_adjust_btn = '<a href="javascript:void(0);" class="btn btn-primary btn-xs item_stock_row pull-left" data-department_id="' . $stock->department_id . '" data-category_id="' . $stock->category_id . '" data-item_id="' . $stock->item_id . '" data-category_group_id="' . $stock->category_group_id . '" data-grwt="' . $grwt . '" data-less_allow="' . $less_allow . '" data-less="' . $less . '" data-ntwt="' . $ntwt . '" data-tunch="' . $stock->tunch . '" data-wstg="' . $wstg . '" data-fine="' . number_format($stock->fine, 3, '.', '') . '" data-item_stock_id="' . $stock->item_stock_id . '" > Adjust </a>';
                        }
                    }
                }
//            }
//            echo '<pre>'; print_r($tunch); exit;
            if($use_rfid == 1 || $use_barcode == 1) {
                if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "rfid_view")) {
                    if ($stock->stock_method != STOCK_METHOD_ITEM_WISE) {
                        $stock_adjust_btn .=  ' &nbsp; <a href="javascript:void(0);" class="btn btn-primary btn-xs item_rfid_detail pull-left" data-category_name="' . $stock->category_name . '" data-item_name="' . $stock->item_name . '" data-department_id="' . $stock->department_id . '" data-category_id="' . $stock->category_id . '" data-item_id="' . $stock->item_id . '" data-category_group_id="' . $stock->category_group_id . '" data-grwt="' . $grwt . '" data-less_allow="' . $less_allow . '" data-less="' . $less . '" data-ntwt="' . $ntwt . '" data-tunch="' . (($tunch > 100 ) ? number_format((float) 100, 2, '.', '') : $tunch) . '" data-wstg="' . $wstg . '" data-fine="' . number_format($stock->fine, 3, '.', '') . '" data-item_stock_id="' . $stock->item_stock_id . '" style="margin: 0px 3px;" > RFID </a>';
                    } else if ($stock->stock_method == STOCK_METHOD_ITEM_WISE && $post_data['item_wise'] == 'true') {
                        $stock_adjust_btn .=  ' &nbsp; <a href="javascript:void(0);" class="btn btn-primary btn-xs item_rfid_detail pull-left" data-category_name="' . $stock->category_name . '" data-item_name="' . $stock->item_name . '" data-department_id="' . $stock->department_id . '" data-category_id="' . $stock->category_id . '" data-item_id="' . $stock->item_id . '" data-category_group_id="' . $stock->category_group_id . '" data-grwt="' . $grwt . '" data-less_allow="' . $less_allow . '" data-less="' . $less . '" data-ntwt="' . $ntwt . '" data-tunch="' . (($tunch > 100 ) ? number_format((float) 100, 2, '.', '') : $tunch) . '" data-wstg="' . $wstg . '" data-fine="' . number_format($stock->fine, 3, '.', '') . '" data-item_stock_id="' . $stock->item_stock_id . '" style="margin: 0px 3px;" > RFID </a>';
                    }
                    $item_stock_rfid_data = $this->crud->getFromSQL('SELECT COUNT(`item_stock_rfid_id`) as rfid_count FROM `item_stock_rfid` WHERE `item_stock_id`="' . $stock->item_stock_id . '" AND `rfid_used` = 0 ');
                    $rfid_count = (isset($item_stock_rfid_data[0]->rfid_count) && !empty($item_stock_rfid_data[0]->rfid_count)) ? $item_stock_rfid_data[0]->rfid_count : "0";
                    $stock_adjust_btn .=  '<span class="pull-right">&nbsp;'. $rfid_count .'&nbsp;</span>';
                }
            }
            $row = array();
            $row[] = $stock->category_name;
            if ($stock->stock_method == '3') {
                $row[] = '<a href="'. base_url('reports/stock_ledger/' . $stock->item_stock_id.'/0/' . $post_data['department_id']) .'" >' . $stock->item_name . '</a>';
            } else if ($stock->stock_method == '2' && isset($post_data['item_wise']) && $post_data['item_wise'] != 'true') {
                $row[] = '<a href="'. base_url('reports/stock_ledger/' . $stock->item_stock_id.'/0/' . $post_data['department_id']) .'" >' . $stock->item_name . '</a>';
            } else {
                $row[] = '<a href="'. base_url('reports/stock_ledger/' . $stock->item_stock_id.'/1/' . $post_data['department_id']) .'" >' . $stock->item_name . '</a>';
            }
            $row[] = $grwt;
            $stock->rfid_created_grwt = number_format((float) $stock->rfid_created_grwt, 3, '.', '');
            $row[] = $stock->rfid_created_grwt;
            $rfid_not_created_grwt = (float) $grwt - (float) $stock->rfid_created_grwt;
            $rfid_not_created_grwt = number_format((float) $rfid_not_created_grwt, 3, '.', '');
            $row[] = $rfid_not_created_grwt;
            if (isset($post_data['department_id']) && !empty($post_data['department_id'])) {
                $row[] = $stock_adjust_btn;
            } else {
                $row[] = '';
            }
            $row[] = $less;
            $row[] = $ntwt;
            $row[] = ($tunch > 100 ) ? number_format((float) 100, 2, '.', '') : $tunch;
            $row[] = $gold;
            $row[] = $silver;
            
            if ($stock->category_group_id == 1) {
                $gold_grwt = (float) $gold_grwt + (float) $grwt;
                $gold_rfid_created_grwt = (float) $gold_rfid_created_grwt + (float) $stock->rfid_created_grwt;
                $gold_rfid_not_created_grwt = (float) $gold_rfid_not_created_grwt + (float) $rfid_not_created_grwt;
                $gold_ntwt = (float) $gold_ntwt + (float) $ntwt;
                $gold_fine = (float) $gold_fine + (float) $gold;
                if($gold_ntwt != 0 || $gold_ntwt != ''){
                    $gold_tunch = ((float) $gold_fine) / (float) $gold_ntwt * 100;
                }
                $amount = (float) $gold * (float) $gold_rate / 10;
                $row[] = number_format((float) $amount, 2, '.', '');
                $gold_amount = (float) $gold_amount + $amount;
                
            } else if ($stock->category_group_id == 2) {
                $silver_grwt = (float) $silver_grwt + (float) $grwt;
                $silver_rfid_created_grwt = (float) $silver_rfid_created_grwt + (float) $stock->rfid_created_grwt;
                $silver_rfid_not_created_grwt = (float) $silver_rfid_not_created_grwt + (float) $rfid_not_created_grwt;
                $silver_ntwt = (float) $silver_ntwt + (float) $ntwt;
                $silver_fine = (float) $silver_fine + (float) $silver;
                if($silver_ntwt != 0 || $silver_ntwt != ''){
                    $silver_tunch = ((float) $silver_fine) / (float) $silver_ntwt * 100;
                }
                $amount = (float) $silver * (float) $silver_rate / 10;
                $row[] = number_format((float) $amount, 2, '.', '');
                $silver_amount = (float) $silver_amount + $amount;
            } else {
                $row[] = 0;
            }
            $data[] = $row;
        }
        $row = array();
        $row[] = '<b>Gold Total<b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $gold_grwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $gold_rfid_created_grwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $gold_rfid_not_created_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $gold_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $gold_tunch, 2, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $gold_fine, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $gold_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        
        $row = array();
        $row[] = '<b>Silver Total</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $silver_grwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $silver_rfid_created_grwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $silver_rfid_not_created_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $silver_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $silver_tunch, 2, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $silver_fine, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $silver_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        
        // Get Worker Gold and Silver from manufacture data.
        $worker_stock_data = $this->get_worker_stock_status_from_manufacture_data();
        $worker_gold_grwt = $worker_stock_data['worker_gold_grwt'];
        $worker_gold_ntwt = $worker_stock_data['worker_gold_ntwt'];
        $worker_gold_fine = $worker_stock_data['worker_gold_fine'];
        $worker_gold_tunch = 0;
        if(!empty($worker_gold_fine) && !empty($worker_gold_ntwt)){
            $worker_gold_tunch = $worker_gold_fine * 100 / $worker_gold_ntwt;
        }
        $worker_gold_amount = (float) $worker_gold_fine * (float) $gold_rate / 10;
        
        $worker_silver_grwt = $worker_stock_data['worker_silver_grwt'];
        $worker_silver_ntwt = $worker_stock_data['worker_silver_ntwt'];
        $worker_silver_fine = $worker_stock_data['worker_silver_fine'];
        $worker_silver_tunch = 0;
        if(!empty($worker_silver_fine) && !empty($worker_silver_ntwt)){
            $worker_silver_tunch = $worker_silver_fine * 100 / $worker_silver_ntwt;
        }
        $worker_silver_amount = (float) $worker_silver_fine * (float) $silver_rate / 10;
        
        $row = array();
        $row[] = '<b>Worker Gold</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_gold_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_gold_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $worker_gold_tunch, 2, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $worker_gold_fine, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_gold_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        $row = array();
        $row[] = '<b>Worker Silver</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_silver_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_silver_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $worker_silver_tunch, 2, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $worker_silver_fine, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $worker_silver_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        
        $dept_worker_gold_grwt = number_format((float) $gold_grwt, 3, '.', '') + number_format((float) $worker_gold_grwt, 3, '.', '');
        $dept_worker_gold_ntwt = number_format((float) $gold_ntwt, 3, '.', '') + number_format((float) $worker_gold_ntwt, 3, '.', '');
        $dept_worker_gold_fine = number_format((float) $gold_fine, 3, '.', '') + number_format((float) $worker_gold_fine, 3, '.', '');
        $dept_worker_gold_tunch = 0;
        if(!empty($dept_worker_gold_fine) && !empty($dept_worker_gold_ntwt)){
            $dept_worker_gold_tunch = $dept_worker_gold_fine * 100 / $dept_worker_gold_ntwt;
        }
        $dept_worker_gold_amount = number_format((float) $gold_amount, 2, '.', '') + number_format((float) $worker_gold_amount, 2, '.', '');
        
        $dept_worker_silver_grwt = number_format((float) $silver_grwt, 3, '.', '') + number_format((float) $worker_silver_grwt, 3, '.', '');
        $dept_worker_silver_ntwt = number_format((float) $silver_ntwt, 3, '.', '') + number_format((float) $worker_silver_ntwt, 3, '.', '');
        $dept_worker_silver_fine = number_format((float) $silver_fine, 3, '.', '') + number_format((float) $worker_silver_fine, 3, '.', '');
        $dept_worker_silver_tunch = 0;
        if(!empty($dept_worker_silver_fine) && !empty($dept_worker_silver_ntwt)){
            $dept_worker_silver_tunch = $dept_worker_silver_fine * 100 / $dept_worker_silver_ntwt;
        }
        $dept_worker_silver_amount = number_format((float) $silver_amount, 2, '.', '') + number_format((float) $worker_silver_amount, 2, '.', '');
        $row = array();
        $row[] = '<b>Dept. + Worker Gold</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_gold_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_gold_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $dept_worker_gold_tunch, 2, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $dept_worker_gold_fine, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_gold_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        $row = array();
        $row[] = '<b>Dept. + Worker Silver</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_silver_grwt, 3, '.', '') .'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_silver_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $dept_worker_silver_tunch, 2, '.', '') .'</b>';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $dept_worker_silver_fine, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $dept_worker_silver_amount, 2, '.', '') .'</b>';
        $data[] = $row;
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function get_worker_stock_status_from_manufacture_data(){
        $worker_gold_grwt = 0;
        $worker_gold_ntwt = 0;
        $worker_gold_fine = 0;
        $worker_silver_grwt = 0;
        $worker_silver_ntwt = 0;
        $worker_silver_fine = 0;
        $issue_receive = $this->crud->getFromSQL("SELECT SUM(`total_issue_net_wt` - `total_receive_net_wt`) as balance_net_wt, SUM(`total_issue_fine` - `total_receive_fine`) as balance_fine FROM `issue_receive` WHERE `lott_complete` = 0");
        if(!empty($issue_receive)){
            $issue_receive_balance_net_wt = number_format((float) $issue_receive[0]->balance_net_wt, 3, '.', '');
            $issue_receive_balance_fine = number_format((float) $issue_receive[0]->balance_fine, 3, '.', '');
            $worker_gold_grwt = $worker_gold_grwt + $issue_receive_balance_net_wt;
            $worker_gold_ntwt = $worker_gold_grwt;
            $worker_gold_fine = $worker_gold_fine + $issue_receive_balance_fine;
        }
        $manu_hand_made = $this->crud->getFromSQL("SELECT SUM(`total_issue_net_wt` - `total_receive_net_wt`) as balance_net_wt, SUM(`total_issue_fine` - `total_receive_fine`) as balance_fine FROM `manu_hand_made` WHERE `lott_complete` = 0");
        if(!empty($manu_hand_made)){
            $manu_hand_made_balance_net_wt = number_format((float) $manu_hand_made[0]->balance_net_wt, 3, '.', '');
            $manu_hand_made_balance_fine = number_format((float) $manu_hand_made[0]->balance_fine, 3, '.', '');
            $worker_gold_grwt = $worker_gold_grwt + $manu_hand_made_balance_net_wt;
            $worker_gold_ntwt = $worker_gold_grwt;
            $worker_gold_fine = $worker_gold_fine + $manu_hand_made_balance_fine;
        }
        $casting_entry = $this->crud->getFromSQL("SELECT SUM(`total_issue_net_wt` - `total_receive_net_wt`) as balance_net_wt, SUM(`total_issue_fine` - `total_receive_fine`) as balance_fine FROM `casting_entry` WHERE `lott_complete` = 0");
        if(!empty($casting_entry)){
            $casting_entry_balance_net_wt = number_format((float) $casting_entry[0]->balance_net_wt, 3, '.', '');
            $casting_entry_balance_fine = number_format((float) $casting_entry[0]->balance_fine, 3, '.', '');
            $worker_gold_grwt = $worker_gold_grwt + $casting_entry_balance_net_wt;
            $worker_gold_ntwt = $worker_gold_grwt;
            $worker_gold_fine = $worker_gold_fine + $casting_entry_balance_fine;
        }
        $machine_chain = $this->crud->getFromSQL("SELECT SUM(`total_issue_net_wt` - `total_receive_net_wt`) as balance_net_wt, SUM(`total_issue_fine` - `total_receive_fine`) as balance_fine FROM `machine_chain` WHERE `lott_complete` = 0");
        if(!empty($machine_chain)){
            $machine_chain_balance_net_wt = number_format((float) $machine_chain[0]->balance_net_wt, 3, '.', '');
            $machine_chain_balance_fine = number_format((float) $machine_chain[0]->balance_fine, 3, '.', '');
            $worker_gold_grwt = $worker_gold_grwt + $machine_chain_balance_net_wt;
            $worker_gold_ntwt = $worker_gold_grwt;
            $worker_gold_fine = $worker_gold_fine + $machine_chain_balance_fine;
        }
        $issue_receive_silver = $this->crud->getFromSQL("SELECT SUM(`total_issue_net_wt` - `total_receive_net_wt`) as balance_net_wt, SUM(`total_issue_fine` - `total_receive_fine`) as balance_fine FROM `issue_receive_silver` WHERE `lott_complete` = 0");
        if(!empty($issue_receive_silver)){
            $issue_receive_balance_net_wt = number_format((float) $issue_receive_silver[0]->balance_net_wt, 3, '.', '');
            $issue_receive_balance_fine = number_format((float) $issue_receive_silver[0]->balance_fine, 3, '.', '');
            $worker_silver_grwt = $worker_silver_grwt + $issue_receive_balance_net_wt;
            $worker_silver_ntwt = $worker_silver_grwt;
            $worker_silver_fine = $worker_silver_fine + $issue_receive_balance_fine;
        }
        
        $worker_stock_data = array();
        $worker_stock_data['worker_gold_grwt'] = $worker_gold_grwt;
        $worker_stock_data['worker_gold_ntwt'] = $worker_gold_ntwt;
        $worker_stock_data['worker_gold_fine'] = $worker_gold_fine;
        $worker_stock_data['worker_silver_grwt'] = $worker_silver_grwt;
        $worker_stock_data['worker_silver_ntwt'] = $worker_silver_ntwt;
        $worker_stock_data['worker_silver_fine'] = $worker_silver_fine;
        return $worker_stock_data;
    }

    function stock_ledger($item_stock_id, $item_wise, $department_id = '') {
        if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "view") && $this->applib->have_access_role(REPORT_MODULE_ID, 'view')) {
            $data = array();
            $item_stock_data = $this->crud->get_data_row_by_id('item_stock', 'item_stock_id', $item_stock_id);
            $data['stock_method'] = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $item_stock_data->item_id));
            $item_stock_data->item_stock_id = $item_stock_id;
            $data['item_stock_data'] = $item_stock_data;
            $data['department_id'] = $department_id;     
            $data['process'] = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            $data['category'] = $this->crud->get_all_records('category', 'category_id', '');
            $data['items'] = $this->crud->get_all_records('item_master', 'item_id', '');
            $data['carat'] = $this->crud->get_all_records('carat', 'purity', 'ASC');
            $data['item_wise'] = $item_wise;
            set_page('reports/stock_ledger', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function stock_ledger_datatable() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        if(!empty($post_data['from_date'])){
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if(!empty($post_data['to_date'])){
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $type_sort = $post_data['type_sort'];
        $display_opening = 1;
//        $display_opening = 0;
//        if(!empty($post_data['category_id']) && !empty($post_data['item_id']) && !empty($post_data['tunch']) && empty($post_data['account_id']) && empty($type_sort)){
            
//        }
//        if($display_opening == 1){
            $opening_data = $this->get_opening_stock_ledger($from_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], $type_sort, $post_data['include_wastage']);
//            echo $this->db->last_query(); exit;
//        } else {
//            $opening_data = array();
//        }
        $p_data = array();
        if($type_sort == '' || $type_sort == 'P'){
            $p_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'P');
//            echo $this->db->last_query(); exit;
        }
        $s_data = array();
        if($type_sort == '' || $type_sort == 'S'){
            $s_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'S');
        }
        $e_data = array();
        if($type_sort == '' || $type_sort == 'E'){
            $e_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'E');
        }
        $m_r_data = array();
        if($type_sort == '' || $type_sort == 'M R'){
            $m_r_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'M R');
        }
        $m_p_data = array();
        if($type_sort == '' || $type_sort == 'M P'){
            $m_p_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'M P');
        }
        $f_t_data = array();
        if($type_sort == '' || $type_sort == 'F T'){
            $f_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'F T');
        }
        $t_t_data = array();
        if($type_sort == '' || $type_sort == 'T T'){
            $t_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'T T');
        }
        $mfi_data = array();
        if($type_sort == '' || $type_sort == 'MFI'){
            $mfi_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MFI');
        }
        $mfr_data = array();
        if($type_sort == '' || $type_sort == 'MFR'){
            $mfr_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MFR');
        }
        $mfis_data = array();
        if($type_sort == '' || $type_sort == 'MFIS'){
            $mfis_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MFIS');
        }
        $mfrs_data = array();
        if($type_sort == '' || $type_sort == 'MFRS'){
            $mfrs_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MFRS');
        }
        $mhmifw_data = array();
        if($type_sort == '' || $type_sort == 'MHMIFW'){
            $mhmifw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MHMIFW');
        }
        $mhmis_data = array();
        if($type_sort == '' || $type_sort == 'MHMIS'){
            $mhmis_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MHMIS');
        }
        $mhmrfw_data = array();
        if($type_sort == '' || $type_sort == 'MHMRFW'){
            $mhmrfw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MHMRFW');
        }
        $mhmrs_data = array();
        if($type_sort == '' || $type_sort == 'MHMRS'){
            $mhmrs_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MHMRS');
        }
        $castingifw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIFW'){
            $castingifw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'CASTINGIFW');
        }
        $castingis_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIS'){
            $castingis_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'CASTINGIS');
        }
        $castingrfw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRFW'){
            $castingrfw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'CASTINGRFW');
        }
        $castingrs_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRS'){
            $castingrs_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'CASTINGRS');
        }
        $mchainifw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIFW'){
            $mchainifw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MCHAINIFW');
        }
        $mchainis_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIS'){
            $mchainis_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MCHAINIS');
        }
        $mchainrfw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRFW'){
            $mchainrfw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MCHAINRFW');
        }
        $mchainrs_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRS'){
            $mchainrs_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'MCHAINRS');
        }
        $o_p_data = array();
        if($type_sort == '' || $type_sort == 'O P'){
            $o_p_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'O P');
        }
        $o_s_data = array();
        if($type_sort == '' || $type_sort == 'O S'){
            $o_s_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $post_data['department_id'], $post_data['category_id'], $post_data['item_id'], $post_data['tunch'], $post_data['account_id'], 'O S');
        }
        $stock_ledger_data = array_merge($p_data, $s_data, $e_data, $m_r_data, $m_p_data, $f_t_data, $t_t_data, $mfi_data, $mfr_data, $mfis_data, $mfrs_data, $mhmifw_data, $mhmis_data, $mhmrfw_data, $mhmrs_data, $castingifw_data, $castingis_data, $castingrfw_data, $castingrs_data, $mchainifw_data, $mchainis_data, $mchainrfw_data, $mchainrs_data, $o_p_data, $o_s_data);
//        echo '<pre>'; print_r($stock_ledger_data); exit;
        
        uasort($stock_ledger_data, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });

        usort($stock_ledger_data, function($a, $b) {
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

        $stock_ledger_data = array_merge($opening_data, $stock_ledger_data);
        $stock_ledger_data = array_values($stock_ledger_data);
        $data = array();
        $total_plus_grwt = 0;
        $total_plus_less = 0;
        $total_plus_net_wt = 0;
        $total_plus_fine = 0;
        $total_minus_grwt = 0;
        $total_minus_less = 0;
        $total_minus_net_wt = 0;
        $total_minus_fine = 0;
        $pre_key = 0;
        $zero_value = 0;
        foreach ($stock_ledger_data as $key => $stock_ledger) {
            
            $grwt = number_format((float) $stock_ledger->grwt, 3, '.', '');
            $less = (is_numeric((float) $stock_ledger->less)) ? number_format((float) $stock_ledger->less, 3, '.', '') : 0;
            $net_wt = (!is_numeric((float) $stock_ledger->net_wt)) ? (float) $grwt - (float) $less : (float) $stock_ledger->net_wt;
            $net_wt = number_format((float) $net_wt, 3, '.', '');
            $touch_id = $stock_ledger->touch_id;
            $wstg = (is_numeric($stock_ledger->wstg)) ? $stock_ledger->wstg : '';
            $account_name = ($stock_ledger->account_name != 'account_name') ? $stock_ledger->account_name : '';
            $fine = number_format((float) $stock_ledger->fine, 3, '.', '');
            
            if($stock_ledger->type_sort == 'S' || $stock_ledger->type_sort == 'M P' || $stock_ledger->type_sort == 'F T' || $stock_ledger->type_sort == 'MFI' || $stock_ledger->type_sort == 'MFIS' || $stock_ledger->type_sort == 'MHMIFW' || $stock_ledger->type_sort == 'MHMIS' || $stock_ledger->type_sort == 'CASTINGIFW' || $stock_ledger->type_sort == 'CASTINGIS' || $stock_ledger->type_sort == 'MCHAINIFW' || $stock_ledger->type_sort == 'MCHAINIS' || $stock_ledger->type_sort == 'O S'){
                $grwt = $zero_value - (float) $grwt;
                $grwt = number_format((float) $grwt, 3, '.', '');
                $less = (!empty($less)) ? $zero_value - (float) $less : 0;
                $less = number_format((float) $less, 3, '.', '');
                $net_wt = $zero_value - (float) $net_wt;
                $net_wt = number_format((float) $net_wt, 3, '.', '');
                $fine = $zero_value - (float) $fine;
                $fine = number_format((float) $fine, 3, '.', '');
                
                $total_minus_grwt = number_format((float) $total_minus_grwt, '3', '.', '') + number_format((float) $grwt, '3', '.', '');
                $total_minus_less = number_format((float) $total_minus_less, '3', '.', '') + number_format((float) $less, '3', '.', '');
                $total_minus_net_wt = number_format((float) $total_minus_net_wt, '3', '.', '') + number_format((float) $net_wt, '3', '.', '');
                $total_minus_fine = number_format((float) $total_minus_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');
            } else {
                $total_plus_grwt = number_format((float) $total_plus_grwt, '3', '.', '') + number_format((float) $grwt, '3', '.', '');
                $total_plus_less = number_format((float) $total_plus_less, '3', '.', '') + number_format((float) $less, '3', '.', '');
                $total_plus_net_wt = number_format((float) $total_plus_net_wt, '3', '.', '') + number_format((float) $net_wt, '3', '.', '');
                $total_plus_fine = number_format((float) $total_plus_fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');
            }
            
            if($display_opening == 1){
                if($key == 0){
                    $balance_grwt = $stock_ledger_data[$key]->balance_grwt;
                    $balance_net_wt = $stock_ledger_data[$key]->balance_net_wt;
                    $balance_fine = $stock_ledger_data[$key]->balance_fine;
                } else {
                    $balance_grwt = $stock_ledger_data[$key]->balance_grwt = number_format((float) $stock_ledger_data[$pre_key]->balance_grwt, '3', '.', '') + number_format((float) $grwt, '3', '.', '');
                    $balance_net_wt = $stock_ledger_data[$key]->balance_net_wt = number_format((float) $stock_ledger_data[$pre_key]->balance_net_wt, '3', '.', '') + number_format((float) $net_wt, '3', '.', '');
                    
                    if($post_data['include_wastage'] == 'true'){
                        $default_wstg = $this->crud->get_column_value_by_id('item_master', 'default_wastage', array('item_id' => $stock_ledger->item_id));
                        $with_wastage_fine = number_format((float) $net_wt, '3', '.', '') * ((float) $touch_id + (float) $default_wstg) / 100;
                        $balance_fine = $stock_ledger_data[$key]->balance_fine = number_format((float) $stock_ledger_data[$pre_key]->balance_fine, '3', '.', '') + number_format((float) $with_wastage_fine, '3', '.', '');
                    } else {
                        $without_wastage_fine = (float) $net_wt * (float) $touch_id / 100;
                        $balance_fine = $stock_ledger_data[$key]->balance_fine = number_format((float) $stock_ledger_data[$pre_key]->balance_fine, '3', '.', '') + number_format((float) $without_wastage_fine, '3', '.', '');
                        $balance_fine = round((float) $balance_fine, 2);
                    }
                    $pre_key = $key;
                }
            } else {
                $balance_grwt = 0;
                $balance_net_wt = 0;
                $balance_fine = 0;
            }
            
            $row = array();
            $action = '';
            if($display_opening != 1 || ($display_opening == 1 && $key != '0')){
                if($stock_ledger->type_sort == 'F T' || $stock_ledger->type_sort == 'T T'){
                    if($this->app_model->have_access_role(STOCK_TRANSFER_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("stock_transfer/stock_transfer/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                } else if($stock_ledger->type_sort == 'MFI' || $stock_ledger->type_sort == 'MFR'){
                    if($this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit")){
                        if($stock_ledger->hisab_done != '1'){
                            $action .= '<a href="' . base_url("manufacture/issue_receive/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                        }
                    }
                } else if($stock_ledger->type_sort == 'MFIS' || $stock_ledger->type_sort == 'MFRS'){
                    if($this->app_model->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit")){
                        if($stock_ledger->hisab_done != '1'){
                            $action .= '<a href="' . base_url("manufacture_silver/issue_receive_silver/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                        }
                    }
                } else if($stock_ledger->type_sort == 'MHMIFW' || $stock_ledger->type_sort == 'MHMIS' || $stock_ledger->type_sort == 'MHMRFW' || $stock_ledger->type_sort == 'MHMRS'){
                    if($this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "edit")){
                        if($stock_ledger->hisab_done != '1'){
                            $action .= '<a href="' . base_url("manu_hand_made/manu_hand_made/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                        }
                    }
                } else if($stock_ledger->type_sort == 'CASTINGIFW' || $stock_ledger->type_sort == 'CASTINGIS' || $stock_ledger->type_sort == 'CASTINGRFW' || $stock_ledger->type_sort == 'CASTINGRS'){
                    if($this->app_model->have_access_role(CASTING_MODULE_ID, "edit")){
                        if($stock_ledger->hisab_done != '1'){
                            $action .= '<a href="' . base_url("casting/casting_entry/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                        }
                    }
                } else if($stock_ledger->type_sort == 'MCHAINIFW' || $stock_ledger->type_sort == 'MCHAINIS' || $stock_ledger->type_sort == 'MCHAINRFW' || $stock_ledger->type_sort == 'MCHAINRS'){
                    if($this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "edit")){
                        if($stock_ledger->hisab_done != '1'){
                            $action .= '<a href="' . base_url("machine_chain/machine_chain/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                        }
                    }
                } else if($stock_ledger->type_sort == 'O S' || $stock_ledger->type_sort == 'O P'){
                    if($this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("other/add/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                } else { 
                    if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("sell/add/" . $stock_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                }
            }
            $row[] = $action;
            $row[] = (!empty(strtotime($stock_ledger->st_date))) ? date('d-m-Y', strtotime($stock_ledger->st_date)) : '';
            $row[] = (isset($stock_ledger->created_at) && !empty(strtotime($stock_ledger->created_at))) ? date('d-m-Y H:i:s', strtotime($stock_ledger->created_at)) : '';
            $row[] = $account_name;
            $row[] = $stock_ledger->type_sort;
            $row[] = $grwt;
            $row[] = number_format((float) $less, 3, '.', '');
            $row[] = $net_wt;
            $row[] = number_format((float) $touch_id, 2, '.', '');
            $row[] = $wstg;
            $row[] = $fine;
            $row[] = number_format((float) $balance_grwt, 3, '.', '');
            $row[] = number_format((float) $balance_net_wt, 3, '.', '');
            $row[] = number_format((float) $balance_fine, 3, '.', '');
            
            if (isset($post_data['rfid_filter']) && $post_data['rfid_filter'] == '1' && isset($stock_ledger->rfid_number) && $stock_ledger->rfid_number != '' ) {
                $data[] = $row;
            } else if (isset($post_data['rfid_filter']) && $post_data['rfid_filter'] == '2' && ((isset($stock_ledger->rfid_number) && $stock_ledger->rfid_number == '') || !isset($stock_ledger->rfid_number)) ) {
                $data[] = $row;
            } else if (isset($post_data['rfid_filter']) && $post_data['rfid_filter'] == '0') {
                $data[] = $row;
            }
        }
        
        if(isset($post_data['order'][0]['column']) && isset($post_data['order'][0]['dir']) && $post_data['order'][0]['dir'] == 'asc'){
            $this->applib->array_sort_by_column_stock_ledger($data, $post_data['order'][0]['column'], SORT_ASC);
        }
        if(isset($post_data['order'][0]['column']) && isset($post_data['order'][0]['dir']) && $post_data['order'][0]['dir'] == 'desc'){
            $this->applib->array_sort_by_column_stock_ledger($data, $post_data['order'][0]['column'], SORT_DESC);
        }
        
        $row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Total Plus</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_plus_grwt, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_plus_less, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_plus_net_wt, 3, '.', '').'</b>';
        if($total_plus_fine != 0 && $total_plus_net_wt != 0){
            $tunch = number_format((float) $total_plus_fine * 100 / (float) $total_plus_net_wt, 2, '.', '');
        } else {
            $tunch = 0;
        }
        $row[] = ''; //'<b>'.$tunch.'</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_plus_fine, 3, '.', '').'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        $row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Total Minus</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_minus_grwt, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_minus_less, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_minus_net_wt, 3, '.', '').'</b>';
        if($total_minus_fine != 0 && $total_minus_net_wt != 0){
            $tunch = number_format((float) $total_minus_fine * 100 / (float) $total_minus_net_wt, 2, '.', '');
        } else {
            $tunch = 0;
        }
        $row[] = ''; //'<b>'.$tunch.'</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_minus_fine, 3, '.', '').'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        $row = array();
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>Grand Total</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_plus_grwt + (float) $total_minus_grwt, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_plus_less + (float) $total_minus_less, 3, '.', '').'</b>';
        $row[] = '<b>'.number_format((float) $total_plus_net_wt + (float) $total_minus_net_wt, 3, '.', '').'</b>';
        
        $avg_tunch = 0;
        $total_net_wt = (float) $total_plus_net_wt + (float) $total_minus_net_wt;
        if($total_net_wt != 0){
            $avg_tunch = ((float) $total_plus_fine + (float) $total_minus_fine) / ((float) $total_net_wt) * 100;
        }
        $row[] = ''; //'<b>'.number_format($avg_tunch, 2, '.', '').'</b>';
        $row[] = '';
        $row[] = '<b>'.number_format((float) $total_plus_fine + (float) $total_minus_fine, 3, '.', '').'</b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        // Profit Loss Row
//        if(!empty($post_data['department_id']) && !empty($post_data['category_id']) && !empty($post_data['item_id']) && !empty($post_data['tunch']) && empty($post_data['account_id']) && empty($type_sort)){
            $profit_loss = number_format((float) $balance_fine, '3', '.', '') - (number_format((float) $total_plus_fine, '3', '.', '') + number_format((float) $total_minus_fine, '3', '.', ''));
            $class = ($profit_loss >= 0) ? 'text-success' : 'text-danger';
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '<b class="'.$class.'">Profit/ Loss</b>';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '<b class="'.$class.'">'.number_format((float) $profit_loss, 3, '.', '').'</b>';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $data[] = $row;
//        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($stock_ledger_data),
            "recordsFiltered" => '',
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function get_opening_stock_ledger($from_date, $department_id, $category_id, $item_id, $tunch, $account_id, $type_sort, $include_wastage){
        $to_date = '';
        $os_data = array();
        if($type_sort == '' || $type_sort == 'Opening Stock'){
            $os_data = $this->crud->get_opening_stock_for_stock_ledger($department_id, $category_id, $item_id, $tunch, $account_id, 'Opening Stock');
        }
        $p_data = array();
        if($type_sort == '' || $type_sort == 'P'){
            $p_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'P');
        }
        $s_data = array();
        if($type_sort == '' || $type_sort == 'S'){
            $s_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'S');
        }
        $e_data = array();
        if($type_sort == '' || $type_sort == 'E'){
            $e_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'E');
        }
        $m_r_data = array();
        if($type_sort == '' || $type_sort == 'M R'){
            $m_r_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'M R');
        }
        $m_p_data = array();
        if($type_sort == '' || $type_sort == 'M P'){
            $m_p_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'M P');
        }
        $f_t_data = array();
        if($type_sort == '' || $type_sort == 'F T'){
            $f_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'F T');
        }
        $t_t_data = array();
        if($type_sort == '' || $type_sort == 'T T'){
            $t_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'T T');
        }
        $mfi_data = array();
        if($type_sort == '' || $type_sort == 'MFI'){
            $mfi_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFI');
        }
        $mfr_data = array();
        if($type_sort == '' || $type_sort == 'MFR'){
            $mfr_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFR');
        }
        $mfis_data = array();
        if($type_sort == '' || $type_sort == 'MFIS'){
            $mfis_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFIS');
        }
        $mfrs_data = array();
        if($type_sort == '' || $type_sort == 'MFRS'){
            $mfrs_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFRS');
        }
        $mhmifw_data = array();
        if($type_sort == '' || $type_sort == 'MHMIFW'){
            $mhmifw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMIFW');
        }
        $mhmis_data = array();
        if($type_sort == '' || $type_sort == 'MHMIS'){
            $mhmis_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMIS');
        }
        $mhmrfw_data = array();
        if($type_sort == '' || $type_sort == 'MHMRFW'){
            $mhmrfw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMRFW');
        }
        $mhmrs_data = array();
        if($type_sort == '' || $type_sort == 'MHMRS'){
            $mhmrs_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMRS');
        }
        $castingifw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIFW'){
            $castingifw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGIFW');
        }
        $castingis_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIS'){
            $castingis_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGIS');
        }
        $castingrfw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRFW'){
            $castingrfw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGRFW');
        }
        $castingrs_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRS'){
            $castingrs_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGRS');
        }
        $mchainifw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIFW'){
            $mchainifw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINIFW');
        }
        $mchainis_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIS'){
            $mchainis_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINIS');
        }
        $mchainrfw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRFW'){
            $mchainrfw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINRFW');
        }
        $mchainrs_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRS'){
            $mchainrs_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINRS');
        }
        $o_p_data = array();
        if($type_sort == '' || $type_sort == 'O P'){
            $o_p_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'O P');
        }
        $o_s_data = array();
        if($type_sort == '' || $type_sort == 'O S'){
            $o_s_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'O S');
        }
        $stock_ledger_data = array_merge($os_data, $p_data, $s_data, $e_data, $m_r_data, $m_p_data, $f_t_data, $t_t_data, $mfi_data, $mfr_data, $mfis_data, $mfrs_data, $mhmifw_data, $mhmis_data, $mhmrfw_data, $mhmrs_data, $castingifw_data, $castingis_data, $castingrfw_data, $castingrs_data, $mchainifw_data, $mchainis_data, $mchainrfw_data, $mchainrs_data, $o_p_data, $o_s_data);
//        echo '<pre>'; print_r($stock_ledger_data); exit;
        
        uasort($stock_ledger_data, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });
        $stock_ledger_data = array_values($stock_ledger_data);
//        echo '<pre>'; print_r($stock_ledger_data); exit;
        $total_plus_grwt = 0;
        $total_plus_less = 0;
        $total_plus_net_wt = 0;
        $total_plus_fine = 0;
        $total_minus_grwt = 0;
        $total_minus_less = 0;
        $total_minus_net_wt = 0;
        $total_minus_fine = 0;
        $pre_key = 0;
        $balance_grwt = 0;
        $balance_net_wt = 0;
        $balance_fine = 0;
        $zero_value = 0;
//        echo '<pre>'; print_r($stock_ledger_data); exit;
        foreach ($stock_ledger_data as $key => $stock_ledger) {
            
            $grwt = number_format($stock_ledger->grwt, 3, '.', '');
//            echo '<pre>'; print_r($grwt); exit;
            $less = (is_numeric((float) $stock_ledger->less)) ? number_format((float) $stock_ledger->less, 3, '.', '') : 0;
            $net_wt = (!is_numeric((float) $stock_ledger->net_wt)) ? (float) $grwt - (float) $less : (float) $stock_ledger->net_wt;
            $touch_id = $stock_ledger->touch_id;
            $wstg = (is_numeric((float) $stock_ledger->wstg)) ? number_format((float) $stock_ledger->wstg, 3, '.', '') : '';
            $account_name = ($stock_ledger->account_name != 'account_name') ? $stock_ledger->account_name : '';
            $fine = $stock_ledger->fine;
            
            if($stock_ledger->type_sort == 'S' || $stock_ledger->type_sort == 'M P' || $stock_ledger->type_sort == 'F T' || $stock_ledger->type_sort == 'MFI' || $stock_ledger->type_sort == 'MFIS' || $stock_ledger->type_sort == 'MHMIFW' || $stock_ledger->type_sort == 'MHMIS' || $stock_ledger->type_sort == 'CASTINGIFW' || $stock_ledger->type_sort == 'CASTINGIS' || $stock_ledger->type_sort == 'MCHAINIFW' || $stock_ledger->type_sort == 'MCHAINIS' || $stock_ledger->type_sort == 'O S' ){
                $grwt = $zero_value - (float) $grwt;
                $grwt = number_format((float) $grwt, 3, '.', '');
                $less = (!empty($less)) ? $zero_value - $less : 0;
                $less = number_format((float) $less, 3, '.', '');
                $net_wt = $zero_value - (float) $net_wt;
                $net_wt = number_format((float) $net_wt, 3, '.', '');
                $fine = $zero_value - (float) $fine;
                $fine = number_format((float) $fine, 3, '.', '');
                
                $total_minus_grwt = (float) $total_minus_grwt + (float) $grwt;
                $total_minus_less = (float) $total_minus_less + (float) $less;
                $total_minus_net_wt = (float) $total_minus_net_wt + (float) $net_wt;
                $total_minus_fine = (float) $total_minus_fine + (float) $fine;
            } else {
                $total_plus_grwt = (float) $total_plus_grwt + (float) $grwt;
                $total_plus_less = (float) $total_plus_less + (float) $less;
                $total_plus_net_wt = (float) $total_plus_net_wt + (float) $net_wt;
                $total_plus_fine = (float) $total_plus_fine + (float) $fine;
            }
            
            if($key == 0){
                $balance_grwt = $stock_ledger_data[$key]->balance_grwt = $grwt;
                $balance_net_wt = $stock_ledger_data[$key]->balance_net_wt = $net_wt;
                
                if($include_wastage == 'true'){
                    $default_wstg = $this->crud->get_column_value_by_id('item_master', 'default_wastage', array('item_id' => $stock_ledger->item_id));
                    $with_wastage_fine = (float) $net_wt * ((float) $touch_id + (float) $default_wstg) / 100;
                    $balance_fine = $stock_ledger_data[$key]->balance_fine = $with_wastage_fine;
                } else {
                    $without_wastage_fine = (float) $net_wt * (float) $touch_id / 100;
                    $balance_fine = $stock_ledger_data[$key]->balance_fine = $without_wastage_fine;
                    $balance_fine = round($balance_fine, 2);
                }
                
            } else {
                $balance_grwt = $stock_ledger_data[$key]->balance_grwt = (float) $stock_ledger_data[$pre_key]->balance_grwt + (float) $grwt;
                $balance_net_wt = $stock_ledger_data[$key]->balance_net_wt = (float) $stock_ledger_data[$pre_key]->balance_net_wt + (float) $net_wt;

                if($include_wastage == 'true'){
                    $default_wstg = $this->crud->get_column_value_by_id('item_master', 'default_wastage', array('item_id' => $stock_ledger->item_id));
                    $with_wastage_fine = (float) $net_wt * ((float) $touch_id + (float) $default_wstg) / 100;
                    $balance_fine = $stock_ledger_data[$key]->balance_fine = (float) $stock_ledger_data[$pre_key]->balance_fine + (float) $with_wastage_fine;
                } else {
                    $without_wastage_fine = (float) $net_wt * (float) $touch_id / 100;
                    $balance_fine = $stock_ledger_data[$key]->balance_fine = (float) $stock_ledger_data[$pre_key]->balance_fine + (float) $without_wastage_fine;
                    $balance_fine = round($balance_fine, 2);
                }
                $pre_key = $key;
            }
        }
        
        $wstg = $this->crud->get_val_by_id('item_master',$item_id,'item_id','default_wastage');
        $total_fine = $total_plus_fine + $total_minus_fine;
        $total_net_wt = $total_plus_net_wt + $total_minus_net_wt;
        $opening_data = new stdClass();
        $opening_data->st_id = '';
        $opening_data->st_date = '';
        $opening_data->account_name = 'Opening';
        $opening_data->type_sort = '';
        $opening_data->grwt = (float) $total_plus_grwt + (float) $total_minus_grwt;
        $opening_data->less = (float) $total_plus_less + (float) $total_minus_less;
        $opening_data->net_wt = $total_net_wt;
        $balance_net_wt = number_format(((float) $balance_net_wt), 3, '.', '');
        $balance_fine = number_format(((float) $balance_fine), 3, '.', '');
        if($balance_fine != 0 && $balance_net_wt != 0){
            $default_wstg = 0;
            if($include_wastage == 'true'){
                $default_wstg = $this->crud->get_column_value_by_id('item_master', 'default_wastage', array('item_id' => $item_id));
            }
            $tunch = number_format((((float) $balance_fine * 100 / (float) $balance_net_wt) - (float) $default_wstg), 2, '.', '');
        } else {
            $tunch = 0;
        }
        $opening_data->touch_id = $tunch;
        $opening_data->wstg = $wstg;
        $opening_data->fine = number_format(((float) $total_net_wt * (float) $tunch / 100), 3, '.', '');
        $opening_data->balance_grwt = $balance_grwt;
        $opening_data->balance_net_wt = $balance_net_wt;
        $opening_data->balance_fine = $balance_fine;
        return $opening_data = array($opening_data);
    }

    // Outstanding related Functions
    function outstanding() {
        $this->crud->execuetSQL(" DELETE FROM reminder WHERE account_id IN (SELECT account_id FROM `account` WHERE gold_fine = 0 AND silver_fine = 0 AND amount = 0) ");
        if ($this->applib->have_access_role(OUTSTANDING_MODULE_ID, "view") && $this->applib->have_access_role(OUTSTANDING_MODULE_ID, 'view')) {
            $data['account_groups'] = $this->crud->getFromSQL('SELECT g.account_group_id,g.account_group_name FROM `user_account_group` ug JOIN account_group g ON(g.account_group_id = ug.account_group_id) WHERE ug.user_id = "' . $this->logged_in_id . '"');
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            $data['display_net_amount_in_outstanding']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'display_net_amount_in_outstanding'));

            $data['account_id'] = isset($_GET['account_id']) && !empty($_GET['account_id']) ? $_GET['account_id'] : '';
            $data['account_group_id'] = isset($_GET['account_group_id']) && !empty($_GET['account_group_id']) ? $_GET['account_group_id'] : '';

            set_page('reports_new_sp3/outstanding_new_sp3',$data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function outstanding_datatable() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $upto_balance_date = date('Y-m-d', strtotime($post_data['upto_balance_date']));
        $gold_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $silver_rate= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
//        $accounts = $this->crud->get_columns_val_by_where('account', 'account_id,credit_limit', array());
        $accounts = $this->crud->get_account_for_outstanding($upto_balance_date);
        $all_account_arr = array();
//        echo '<pre>'.$this->db->last_query(); exit;
        if(!empty($accounts)){
            foreach ($accounts as $account) {
                $all_account_arr[$account['account_id']] = $account['credit_limit'];
            }
        }
        $account_group_id = $post_data['account_group_id'];
        if($account_group_id == DEPARTMENT_GROUP){
            $sell_items_data = $this->crud->get_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $payment_receipt_data = $this->crud->get_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $metal_payment_data = $this->crud->get_metal_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $from_stock_transfer_data = $this->crud->get_stock_transfer_for_outstanding_report_department($upto_balance_date, $account_group_id, 'ST F');
            $to_stock_transfer_data = $this->crud->get_stock_transfer_for_outstanding_report_department($upto_balance_date, $account_group_id, 'ST T');
            $cashbook_d_data = $this->crud->get_cashbook_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $cashbook_a_data = $this->crud->get_cashbook_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $manufacture_casting_data = $this->crud->get_manufacture_casting_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $other_sell_items_data = $this->crud->get_other_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $opening_data = $this->crud->get_opening_for_outstanding_report($upto_balance_date, $account_group_id);
            $list = array_merge($opening_data, $sell_items_data, $metal_payment_data, $payment_receipt_data, $cashbook_d_data, $cashbook_a_data, $manufacture_issue_receive_data, $manufacture_issue_receive_silver_data, $manufacture_manu_hand_made_data, $manufacture_casting_data, $manufacture_machin_chain_data, $from_stock_transfer_data, $to_stock_transfer_data, $other_sell_items_data, $other_payment_receipt_data);
        } else if($account_group_id == '0') {
            $d_sell_items_data = $this->crud->get_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_payment_receipt_data = $this->crud->get_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_metal_payment_data = $this->crud->get_metal_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_from_stock_transfer_data = $this->crud->get_stock_transfer_for_outstanding_report_department($upto_balance_date, $account_group_id, 'ST F');
            $d_to_stock_transfer_data = $this->crud->get_stock_transfer_for_outstanding_report_department($upto_balance_date, $account_group_id, 'ST T');
            $d_cashbook_data = $this->crud->get_cashbook_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_manufacture_casting_data = $this->crud->get_manufacture_casting_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_other_sell_items_data = $this->crud->get_other_sell_items_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_outstanding_report_department($upto_balance_date, $account_group_id);
            $d_opening_data = $this->crud->get_opening_for_outstanding_report($upto_balance_date, $account_group_id);
            $dipartment_list = array_merge($d_opening_data, $d_sell_items_data, $d_metal_payment_data, $d_payment_receipt_data, $d_cashbook_data, $d_manufacture_issue_receive_data, $d_manufacture_issue_receive_silver_data, $d_manufacture_manu_hand_made_data, $d_manufacture_casting_data, $d_manufacture_machin_chain_data, $d_from_stock_transfer_data, $d_to_stock_transfer_data, $d_other_sell_items_data, $d_other_payment_receipt_data);
            
            $sell_items_data1 = $this->crud->get_sell_items_for_outstanding_report($upto_balance_date, $account_group_id);
            $sell_items_data2 = $this->crud->get_sell_items_for_mfloss_outstanding_report($upto_balance_date, $account_group_id);
            $sp_discount_data = $this->crud->get_sell_discount_for_outstanding_report($upto_balance_date, $account_group_id);
            $sell_items_data = array_merge($sell_items_data1, $sell_items_data2, $sp_discount_data);
            $payment_receipt_data = $this->crud->get_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $payment_receipt_data_bank = $this->crud->get_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id);
            $metal_payment_data = $this->crud->get_metal_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $gold_bhav_data = $this->crud->get_gold_bhav_for_outstanding_report($upto_balance_date, $account_group_id);
            $silver_bhav_data = $this->crud->get_silver_bhav_for_outstanding_report($upto_balance_date, $account_group_id);
            $journal_data = $this->crud->get_journal_naam_jama_for_outstanding_report($upto_balance_date, $account_group_id);
            $cashbook_data = $this->crud->get_cashbook_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_casting_data = $this->crud->get_manufacture_casting_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_sell_items_data = $this->crud->get_other_sell_items_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_payment_receipt_data_bank = $this->crud->get_other_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id);
            if(empty($account_group_id)){
                $hisab_fine_data1 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_fine_data2 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_fine_data3 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_fine_data4 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_fine_data = array_merge($hisab_fine_data1, $hisab_fine_data2, $hisab_fine_data3, $hisab_fine_data4);
                $hisab_done_ir_data1 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_done_ir_data2 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_done_ir_data3 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_done_ir_data4 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_done_ir_data = array_merge($hisab_done_ir_data1, $hisab_done_ir_data2, $hisab_done_ir_data3, $hisab_done_ir_data4);
                
                $sell_ad_charges_data1 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id, 'account');
                $sell_ad_charges_data2 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $sell_ad_charges_data = array_merge($sell_ad_charges_data1, $sell_ad_charges_data2);

                $hallmark_xrf_data1 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id, 'account');
                $hallmark_xrf_data2 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id, 'xrf_hm_laser_pl');
                $hallmark_xrf_data = array_merge($hallmark_xrf_data1, $hallmark_xrf_data2);
            } else {
                $hisab_fine_data1 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_fine_data2 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_fine_data = array_merge($hisab_fine_data1, $hisab_fine_data2);
                $hisab_done_ir_data1 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_done_ir_data2 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_done_ir_data = array_merge($hisab_done_ir_data1, $hisab_done_ir_data2);
                
                $sell_ad_charges_data1 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id);
                $sell_ad_charges_data = array_merge($sell_ad_charges_data1);

                $hallmark_xrf_data1 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id);
                $hallmark_xrf_data = array_merge($hallmark_xrf_data1);
            }
            $acc_list = array_merge($sell_items_data, $metal_payment_data, $payment_receipt_data, $payment_receipt_data_bank, $gold_bhav_data, $silver_bhav_data, $journal_data, $cashbook_data, $manufacture_issue_receive_data, $manufacture_issue_receive_silver_data, $manufacture_manu_hand_made_data, $manufacture_casting_data, $manufacture_machin_chain_data, $other_sell_items_data, $other_payment_receipt_data, $other_payment_receipt_data_bank, $hisab_fine_data, $hisab_done_ir_data, $sell_ad_charges_data, $hallmark_xrf_data);
            $list = array_merge($dipartment_list, $acc_list);
        } else {
            $account_ids = $this->crud->get_account_ids_from_account_group_id($account_group_id);
            if(in_array(MF_LOSS_EXPENSE_ACCOUNT_ID, $account_ids)){
                $sell_items_data1 = $this->crud->get_sell_items_for_mfloss_outstanding_report($upto_balance_date, $account_group_id);
            } else {
                $sell_items_data1 = $this->crud->get_sell_items_for_outstanding_report($upto_balance_date, $account_group_id);
            }
            $sp_discount_data = $this->crud->get_sell_discount_for_outstanding_report($upto_balance_date, $account_group_id);
            $sell_items_data = array_merge($sell_items_data1, $sp_discount_data);
            $payment_receipt_data = $this->crud->get_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $payment_receipt_data_bank = $this->crud->get_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id);
            $metal_payment_data = $this->crud->get_metal_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $gold_bhav_data = $this->crud->get_gold_bhav_for_outstanding_report($upto_balance_date, $account_group_id);
            $silver_bhav_data = $this->crud->get_silver_bhav_for_outstanding_report($upto_balance_date, $account_group_id);
            $journal_data = $this->crud->get_journal_naam_jama_for_outstanding_report($upto_balance_date, $account_group_id);
            $cashbook_data = $this->crud->get_cashbook_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_casting_data = $this->crud->get_manufacture_casting_for_outstanding_report($upto_balance_date, $account_group_id);
            $manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_sell_items_data = $this->crud->get_other_sell_items_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_outstanding_report($upto_balance_date, $account_group_id);
            $other_payment_receipt_data_bank = $this->crud->get_other_payment_receipt_for_outstanding_report_bank($upto_balance_date, $account_group_id);
            if(empty($account_group_id)){
                $hisab_fine_data1 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_fine_data2 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_fine_data3 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_fine_data4 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_fine_data = array_merge($hisab_fine_data1, $hisab_fine_data2, $hisab_fine_data3, $hisab_fine_data4);
                $hisab_done_ir_data1 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_done_ir_data2 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_done_ir_data3 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id, 'worker');
                $hisab_done_ir_data4 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $hisab_done_ir_data = array_merge($hisab_done_ir_data1, $hisab_done_ir_data2, $hisab_done_ir_data3, $hisab_done_ir_data4);
                
                $sell_ad_charges_data1 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id, 'account');
                $sell_ad_charges_data2 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id, 'mfloss');
                $sell_ad_charges_data = array_merge($sell_ad_charges_data1, $sell_ad_charges_data2);

                $hallmark_xrf_data1 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id, 'account');
                $hallmark_xrf_data2 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id, 'xrf_hm_laser_pl');
                $hallmark_xrf_data = array_merge($hallmark_xrf_data1, $hallmark_xrf_data2);
            } else {
                $hisab_fine_data1 = $this->crud->get_hisab_fine_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_fine_data2 = $this->crud->get_hisab_fine_silver_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_fine_data = array_merge($hisab_fine_data1, $hisab_fine_data2);
                $hisab_done_ir_data1 = $this->crud->get_hisab_done_ir_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_done_ir_data2 = $this->crud->get_hisab_done_irs_for_outstanding_report($upto_balance_date, $account_group_id);
                $hisab_done_ir_data = array_merge($hisab_done_ir_data1, $hisab_done_ir_data2);
                
                $sell_ad_charges_data1 = $this->crud->get_sell_ad_charges_for_outstanding_report($upto_balance_date, $account_group_id);
                $sell_ad_charges_data = array_merge($sell_ad_charges_data1);

                $hallmark_xrf_data1 = $this->crud->get_hallmark_xrf_for_outstanding_report($upto_balance_date, $account_group_id);
                $hallmark_xrf_data = array_merge($hallmark_xrf_data1);
            }
            $opening_data = $this->crud->get_opening_for_outstanding_report($upto_balance_date, $account_group_id);
            $list = array_merge($opening_data, $sell_items_data, $metal_payment_data, $payment_receipt_data, $payment_receipt_data_bank, $gold_bhav_data, $silver_bhav_data, $journal_data, $cashbook_data, $manufacture_issue_receive_data, $manufacture_issue_receive_silver_data, $manufacture_manu_hand_made_data, $manufacture_casting_data, $manufacture_machin_chain_data, $other_sell_items_data, $other_payment_receipt_data, $other_payment_receipt_data_bank, $hisab_fine_data, $hisab_done_ir_data, $sell_ad_charges_data, $hallmark_xrf_data);
        }
//        echo '<pre>'; print_r($list); exit;
        
        uasort($list, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });
        $list = array_values($list);
        $role_add_reminder = $this->app_model->have_access_role(REMINDER_MODULE_ID, "add");
        $data = array();
        $outstanding_data_arr = array();
        $account_id_arr = array();
        $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
//        echo '<pre>'; print_r($list); exit;
        foreach ($list as $key => $list_row) {
//            echo '<pre>'; print_r($list_row); exit;
            $account_id = $list_row->account_id;
            if(!empty($post_data['account_id']) && $post_data['account_id'] != $account_id){
                continue;
            }
            if($sell_purchase_type_3_menu == '1' ) {
                if(isset($list_row->account_group_id) && in_array($list_row->account_group_id, array(CUSTOMER_GROUP, SUNDRY_CREDITORS_ACCOUNT_GROUP, SUNDRY_DEBTORS_ACCOUNT_GROUP))){ } else {
                    continue;
                }
            }
            $outstanding_data_arr[$account_id]['account_name'] = '<a href="'.base_url().'reports_new_sp3/customer_ledger?account_id='.$list_row->account_id.'" target="_blank">'.$list_row->account_name.'</a>';
            $outstanding_data_arr[$account_id]['account_mobile'] = $list_row->account_mobile;
            $account_address = $this->crud->get_column_value_by_id('account', 'account_address', array('account_id' => $list_row->account_id));
            $outstanding_data_arr[$account_id]['account_address'] = (!empty($account_address)) ? $account_address : '-';
                
            if (in_array($account_id, $account_id_arr) && !empty($outstanding_data_arr[$account_id])) {

                if (strtotime($list_row->st_date) > strtotime($outstanding_data_arr[$account_id]['st_date'])) {
                    $outstanding_data_arr[$account_id]['st_date'] = (isset($list_row->st_date) && !empty($list_row->st_date)) ? date('d-m-Y', strtotime($list_row->st_date)) : '-';
                }
                $outstanding_data_arr[$account_id]['gold_fine'] = number_format((float) $outstanding_data_arr[$account_id]['gold_fine'], 3, '.', '') + number_format((float) $list_row->gold_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['silver_fine'] = number_format((float) $outstanding_data_arr[$account_id]['silver_fine'], 3, '.', '') + number_format((float) $list_row->silver_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['amount'] = number_format((float) $outstanding_data_arr[$account_id]['amount'], 2, '.', '') + number_format((float) $list_row->amount, 2, '.', '');

                $gold_amount = (float) $outstanding_data_arr[$account_id]['gold_fine'] * (float) $gold_rate / 10;
                $silver_amount = (float) $outstanding_data_arr[$account_id]['silver_fine'] * (float) $silver_rate / 10;
                $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_data_arr[$account_id]['amount'];
                $outstanding_data_arr[$account_id]['net_amount'] = $net_amount;

                $outstanding_data_arr[$account_id]['account_id'] = $account_id;
                $outstanding_data_arr[$account_id]['account_group_id'] = $list_row->account_group_id;

            } else {

                $outstanding_data_arr[$account_id]['st_date'] = (isset($list_row->st_date) && !empty($list_row->st_date)) ? date('d-m-Y', strtotime($list_row->st_date)) : '-';
                $outstanding_data_arr[$account_id]['gold_fine'] = number_format((float) $list_row->gold_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['silver_fine'] = number_format((float) $list_row->silver_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['amount'] = number_format((float) $list_row->amount, 2, '.', '');

                $gold_amount = (float) $list_row->gold_fine * (float) $gold_rate / 10;
                $silver_amount = (float) $list_row->silver_fine * (float) $silver_rate / 10;
                $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $list_row->amount;
                $outstanding_data_arr[$account_id]['net_amount'] = $net_amount;

                $outstanding_data_arr[$account_id]['account_id'] = $account_id;
                $outstanding_data_arr[$account_id]['account_group_id'] = $list_row->account_group_id;
                $account_id_arr[] = $account_id;
            }
        }
        $foot_total_gold_fine = 0;
        $foot_total_silver_fine = 0;
        $foot_total_amount = 0;
        $total_net_amount = 0;
        $foot_total_credit_gold_fine = 0;
        $foot_total_credit_silver_fine = 0;
        $foot_total_credit_amount = 0;
        $total_credit_net_amount = 0;
        $foot_total_debit_gold_fine = 0;
        $foot_total_debit_silver_fine = 0;
        $foot_total_debit_amount = 0;
        $total_debit_net_amount = 0;
        
        foreach($outstanding_data_arr as $outstanding_row){
            $row = array();
            if(isset($outstanding_row['account_id']) && !empty($outstanding_row['account_id'])){
                $credit_limit = $all_account_arr[$outstanding_row['account_id']];
                if($credit_limit == null || $credit_limit == ''){ $credit_limit = 0; }
                $tr_naam_data = $this->crud->get_transfer_naam_jama_for_outstanding_report($upto_balance_date, $outstanding_row['account_id'], 'TR Naam', $account_group_id);
                $tr_jama_data = $this->crud->get_transfer_naam_jama_for_outstanding_report($upto_balance_date, $outstanding_row['account_id'], 'TR Jama', $account_group_id);
                if(!empty($tr_naam_data)){
                    foreach ($tr_naam_data as $naam) {
                        $outstanding_row['gold_fine'] = number_format((float) $outstanding_row['gold_fine'], 3, '.', '') + number_format((float) $naam->gold_fine, 3, '.', '');
                        $outstanding_row['silver_fine'] = number_format((float) $outstanding_row['silver_fine'], 3, '.', '') + number_format((float) $naam->silver_fine, 3, '.', '');
                        $outstanding_row['amount'] = number_format((float) $outstanding_row['amount'], 2, '.', '') + number_format((float) $naam->amount, 2, '.', '');
                        $gold_amount = (float) $outstanding_row['gold_fine'] * (float) $gold_rate / 10;
                        $silver_amount = (float) $outstanding_row['silver_fine'] * (float) $silver_rate / 10;
                        $outstanding_row['net_amount'] = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_row['amount'];
                    }
                }
                if(!empty($tr_jama_data)){
                    foreach ($tr_jama_data as $jama) {
                        $outstanding_row['gold_fine'] = number_format((float) $outstanding_row['gold_fine'], 3, '.', '') + number_format((float) $jama->gold_fine, 3, '.', '');
                        $outstanding_row['silver_fine'] = number_format((float) $outstanding_row['silver_fine'], 3, '.', '') + number_format((float) $jama->silver_fine, 3, '.', '');
                        $outstanding_row['amount'] = number_format((float) $outstanding_row['amount'], 2, '.', '') + number_format((float) $jama->amount, 2, '.', '');
                        $gold_amount = (float) $outstanding_row['gold_fine'] * (float) $gold_rate / 10;
                        $silver_amount = (float) $outstanding_row['silver_fine'] * (float) $silver_rate / 10;
                        $outstanding_row['net_amount'] = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_row['amount'];
                    }
                }
    //            echo '<pre>'; print_r($outstanding_row);
                $str_gold = ($outstanding_row['gold_fine'] >= 0) ? ' (Dr)' : ' (Cr)';
                $str_gold = number_format((float) $outstanding_row['gold_fine'], 3, '.', '') . ' ' . $str_gold;
                $str_silver = ($outstanding_row['silver_fine'] >= 0) ? ' (Dr)' : ' (Cr)';
                $str_silver = number_format((float) $outstanding_row['silver_fine'], 3, '.', '') . ' ' . $str_silver;
                $str_amount = ($outstanding_row['amount'] >= 0) ? ' (Dr)' : ' (Cr)';
                $str_amount = number_format((float) $outstanding_row['amount'], 2, '.', '') . ' ' . $str_amount;
                $str_net_amount = ($outstanding_row['net_amount'] >= 0) ? ' (Dr)' : ' (Cr)';
                $str_net_amount = number_format((float) $outstanding_row['net_amount'], 2, '.', '') . ' ' . $str_net_amount;

                $today_date = date('Y-m-d');
                if($upto_balance_date == $today_date && !empty($outstanding_row['net_amount']) || $upto_balance_date != $today_date){  // Check selected date and today date same then Not entry net_balance condition consider.
                    $is_reminder = $this->crud->getFromSQL(" SELECT reminder_id FROM reminder WHERE account_id = ".$outstanding_row['account_id']." AND date >= '".date('Y-m-d')."' LIMIT 1 ");
                    if($post_data['credit_limit'] == '1'){
                        $row[] = '<span style="text-align: center;">
                                        <div class="form-group">
                                            <label for="" class="col-sm-12 input-sm">
                                                <input type="checkbox" name="send_sms[]" id="send_sms" data-acc_id="'.$outstanding_row['account_id'].'" data-bal_date="'.$outstanding_row['st_date'].'" data-gold="'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'" data-silver="'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'" data-amount="'.$outstanding_row['amount'].'" class="send_sms" checked="">
                                            </label>
                                        </div>
                                    </span>';
                        $row[] = '<span style="text-align: center;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-12 input-sm text-green">
                                            <a href="'.base_url().'reports/send_whatsapp_sms/'.$outstanding_row['account_id'].'/'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'/'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'/'.$outstanding_row['amount'].'/'.$outstanding_row['st_date'].'/" alt="Send WhatsApp SMS" title="Click to Send WhatsApp SMS" target="_blank" ><img src="'.base_url().'assets/dist/img/whatsapp_icon.png" style="width:30px;" ></a>
                                        </label>
                                    </div>
                                </span>';
                        if(!empty($is_reminder)){
                            $row[] = "<span class='reminder_class'>".$outstanding_row['account_name']."</span>";
                        } else {
                            $row[] = $outstanding_row['account_name'];
                        }
                        $row[] = '<span class="text-aqua account_address" data-toggle="tooltip" title="<strong>Address :</strong><br>' . $outstanding_row['account_address'] . '" data-html="true" data-placement="right" >' . $outstanding_row['account_mobile'] . '</span>';
                        $row[] = $outstanding_row['account_mobile'];
                        $row[] = $outstanding_row['account_address'];
                        if($role_add_reminder){
                            $row[] = '<a href="#" data-account_id="'.$outstanding_row['account_id'].'" data-amount="'.$outstanding_row['net_amount'].'" class="reminder"> '.$outstanding_row['st_date'].' </a>';
                        } else {
                            $row[] = $outstanding_row['st_date'];
                        }
                        $row[] = $str_gold;
                        $row[] = $str_silver;
                        $row[] = $str_amount;
                        $row[] = $str_net_amount;
                        $data[] = $row;
                        $foot_total_gold_fine = number_format((float) $foot_total_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        $foot_total_silver_fine = number_format((float) $foot_total_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        $foot_total_amount = number_format((float) $foot_total_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        $total_net_amount = number_format((float) $total_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        
                        if($outstanding_row['gold_fine'] >= 0) {
                            $foot_total_debit_gold_fine = number_format((float) $foot_total_debit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_gold_fine = number_format((float) $foot_total_credit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        }
                        if($outstanding_row['silver_fine'] >= 0) {
                            $foot_total_debit_silver_fine = number_format((float) $foot_total_debit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_silver_fine = number_format((float) $foot_total_credit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        }
                        if($outstanding_row['amount'] >= 0) {
                            $foot_total_debit_amount = number_format((float) $foot_total_debit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        } else {
                            $foot_total_credit_amount = number_format((float) $foot_total_credit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        }
                        if($outstanding_row['net_amount'] >= 0) {
                            $total_debit_net_amount = number_format((float) $total_debit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        } else {
                            $total_credit_net_amount = number_format((float) $total_credit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        }

                    } else if($post_data['credit_limit'] == '2' && $credit_limit > $outstanding_row['net_amount']){
                        $row[] = '<span style="text-align: center;">
                                        <div class="form-group">
                                            <label for="" class="col-sm-12 input-sm">
                                                <input type="checkbox" name="send_sms[]" id="send_sms" data-acc_id="'.$outstanding_row['account_id'].'" data-bal_date="'.$outstanding_row['st_date'].'" data-gold="'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'" data-silver="'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'" data-amount="'.$outstanding_row['amount'].'" class="send_sms" checked="">
                                            </label>
                                        </div>
                                    </span>';
                        $row[] = '<span style="text-align: center;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-12 input-sm text-green">
                                            <a href="'.base_url().'reports/send_whatsapp_sms/'.$outstanding_row['account_id'].'/'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'/'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'/'.$outstanding_row['amount'].'/'.$outstanding_row['st_date'].'/" alt="Send WhatsApp SMS" title="Click to Send WhatsApp SMS" target="_blank" ><img src="'.base_url().'assets/dist/img/whatsapp_icon.png" style="width:30px;" ></a>
                                        </label>
                                    </div>
                                </span>';
                        if(!empty($is_reminder)){
                            $row[] = "<span class='reminder_class'>".$outstanding_row['account_name']."</span>";
                        } else {
                            $row[] = $outstanding_row['account_name'];
                        }
                        $row[] = '<span class="text-aqua account_address" data-toggle="tooltip" title="<strong>Address :</strong><br>' . $outstanding_row['account_address'] . '" data-html="true" data-placement="right" >' . $outstanding_row['account_mobile'] . '</span>';
                        $row[] = $outstanding_row['account_mobile'];
                        $row[] = $outstanding_row['account_address'];
                        if($role_add_reminder){
                            $row[] = '<a href="#" data-account_id="'.$outstanding_row['account_id'].'" data-amount="'.$outstanding_row['net_amount'].'" class="reminder"> '.$outstanding_row['st_date'].' </a>';
                        } else {
                            $row[] = $outstanding_row['st_date'];
                        }
                        $row[] = $str_gold;
                        $row[] = $str_silver;
                        $row[] = $str_amount;
                        $row[] = $str_net_amount;
                        $data[] = $row;
                        $foot_total_gold_fine = number_format((float) $foot_total_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        $foot_total_silver_fine = number_format((float) $foot_total_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        $foot_total_amount = number_format((float) $foot_total_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        $total_net_amount = number_format((float) $total_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        
                        if($outstanding_row['gold_fine'] >= 0) {
                            $foot_total_debit_gold_fine = number_format((float) $foot_total_debit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_gold_fine = number_format((float) $foot_total_credit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        }
                        if($outstanding_row['silver_fine'] >= 0) {
                            $foot_total_debit_silver_fine = number_format((float) $foot_total_debit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_silver_fine = number_format((float) $foot_total_credit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        }
                        if($outstanding_row['amount'] >= 0) {
                            $foot_total_debit_amount = number_format((float) $foot_total_debit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        } else {
                            $foot_total_credit_amount = number_format((float) $foot_total_credit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        }
                        if($outstanding_row['net_amount'] >= 0) {
                            $total_debit_net_amount = number_format((float) $total_debit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        } else {
                            $total_credit_net_amount = number_format((float) $total_credit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        }
                        
                    } else if($post_data['credit_limit'] == '3' && $credit_limit < $outstanding_row['net_amount']){
                        $row[] = '<span style="text-align: center;">
                                        <div class="form-group">
                                            <label for="" class="col-sm-12 input-sm">
                                                <input type="checkbox" name="send_sms[]" id="send_sms" data-acc_id="'.$outstanding_row['account_id'].'" data-bal_date="'.$outstanding_row['st_date'].'" data-gold="'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'" data-silver="'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'" data-amount="'.$outstanding_row['amount'].'" class="send_sms" checked="">
                                            </label>
                                        </div>
                                    </span>';
                        $row[] = '<span style="text-align: center;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-12 input-sm text-green">
                                            <a href="'.base_url().'reports/send_whatsapp_sms/'.$outstanding_row['account_id'].'/'.number_format((float) $outstanding_row['gold_fine'], 3, '.', '').'/'.number_format((float) $outstanding_row['silver_fine'], 3, '.', '').'/'.$outstanding_row['amount'].'/'.$outstanding_row['st_date'].'/" alt="Send WhatsApp SMS" title="Click to Send WhatsApp SMS" target="_blank" ><img src="'.base_url().'assets/dist/img/whatsapp_icon.png" style="width:30px;" ></a>
                                        </label>
                                    </div>
                                </span>';
                        if(!empty($is_reminder)){
                            $row[] = "<span class='reminder_class'>".$outstanding_row['account_name']."</span>";
                        } else {
                            $row[] = $outstanding_row['account_name'];
                        }
                        $row[] = '<span class="text-aqua account_address" data-toggle="tooltip" title="<strong>Address :</strong><br>' . $outstanding_row['account_address'] . '" data-html="true" data-placement="right" >' . $outstanding_row['account_mobile'] . '</span>';
                        $row[] = $outstanding_row['account_mobile'];
                        $row[] = $outstanding_row['account_address'];
                        if($role_add_reminder){
                            $row[] = '<a href="#" data-account_id="'.$outstanding_row['account_id'].'" data-amount="'.$outstanding_row['net_amount'].'" class="reminder"> '.$outstanding_row['st_date'].' </a>';
                        } else {
                            $row[] = $outstanding_row['st_date'];
                        }
                        $row[] = $str_gold;
                        $row[] = $str_silver;
                        $row[] = $str_amount;
                        $row[] = $str_net_amount;
                        $data[] = $row;
                        $foot_total_gold_fine = number_format((float) $foot_total_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        $foot_total_silver_fine = number_format((float) $foot_total_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        $foot_total_amount = number_format((float) $foot_total_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        $total_net_amount = number_format((float) $total_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        
                        if($outstanding_row['gold_fine'] >= 0) {
                            $foot_total_debit_gold_fine = number_format((float) $foot_total_debit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_gold_fine = number_format((float) $foot_total_credit_gold_fine, 3, '.', '') + number_format((float) $outstanding_row['gold_fine'], 3, '.', '');
                        }
                        if($outstanding_row['silver_fine'] >= 0) {
                            $foot_total_debit_silver_fine = number_format((float) $foot_total_debit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        } else {
                            $foot_total_credit_silver_fine = number_format((float) $foot_total_credit_silver_fine, 3, '.', '') + number_format((float) $outstanding_row['silver_fine'], 3, '.', '');
                        }
                        if($outstanding_row['amount'] >= 0) {
                            $foot_total_debit_amount = number_format((float) $foot_total_debit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        } else {
                            $foot_total_credit_amount = number_format((float) $foot_total_credit_amount, 3, '.', '') + number_format((float) $outstanding_row['amount'], 3, '.', '');
                        }
                        if($outstanding_row['net_amount'] >= 0) {
                            $total_debit_net_amount = number_format((float) $total_debit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        } else {
                            $total_credit_net_amount = number_format((float) $total_credit_net_amount, 3, '.', '') + number_format((float) $outstanding_row['net_amount'], 3, '.', '');
                        }
                        
                    }
                } // Check selected date and today date same then Not entry net_balance condition consider.
            }
        }
        if (isset($post_data['order'][0]['column']) && isset($post_data['order'][0]['dir'])){

            if($post_data['order'][0]['column'] == '7' || $post_data['order'][0]['column'] == '8' || $post_data['order'][0]['column'] == '9' || $post_data['order'][0]['column'] == '10'){
                $sort_type = SORT_NUMERIC;
            } else {
                $sort_type = SORT_REGULAR;
            }
            
            if ($post_data['order'][0]['dir'] == 'asc') {
                $this->applib->array_sort_by_column_outstanding($data, $post_data['order'][0]['column'], SORT_ASC, $sort_type);
            }

            if ($post_data['order'][0]['dir'] == 'desc') {
                $this->applib->array_sort_by_column_outstanding($data, $post_data['order'][0]['column'], SORT_DESC, $sort_type);
            }
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
            "foot_total_gold_fine" => number_format((float) $foot_total_gold_fine, 3, '.', ''),
            "foot_total_silver_fine" => number_format((float) $foot_total_silver_fine, 3, '.', ''),
            "foot_total_amount" => number_format((float) $foot_total_amount, 2, '.', ''),
            "total_net_amount" => number_format((float) $total_net_amount, 2, '.', ''),
            "foot_total_credit_gold_fine" => number_format((float) $foot_total_credit_gold_fine, 3, '.', ''),
            "foot_total_credit_silver_fine" => number_format((float) $foot_total_credit_silver_fine, 3, '.', ''),
            "foot_total_credit_amount" => number_format((float) $foot_total_credit_amount, 2, '.', ''),
            "total_credit_net_amount" => number_format((float) $total_credit_net_amount, 2, '.', ''),
            "foot_total_debit_gold_fine" => number_format((float) $foot_total_debit_gold_fine, 3, '.', ''),
            "foot_total_debit_silver_fine" => number_format((float) $foot_total_debit_silver_fine, 3, '.', ''),
            "foot_total_debit_amount" => number_format((float) $foot_total_debit_amount, 2, '.', ''),
            "total_debit_net_amount" => number_format((float) $total_debit_net_amount, 2, '.', ''),
        );
        echo json_encode($output);
    }
    
    // Cashbook related Functions
    function cashbook($pay_rec_id = '') {
        if ($this->applib->have_access_role(CASHBOOK_MODULE_ID, 'view')) {
            $data = array();
            if (isset($pay_rec_id) && !empty($pay_rec_id)) {
                $payment_receipt_data = $this->crud->get_data_row_by_id('payment_receipt', 'pay_rec_id', $pay_rec_id);
                $payment_receipt_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$payment_receipt_data->created_by));
                if($payment_receipt_data->created_by != $payment_receipt_data->updated_by){
                   $payment_receipt_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$payment_receipt_data->updated_by)); 
                }else{
                   $payment_receipt_data->updated_by_name = $payment_receipt_data->created_by_name;
                }
//                echo '<pre>'; print_r($payment_receipt_data); exit;
                $data['payment_receipt_data'] = $payment_receipt_data;
            }
            set_page('reports/cashbook', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function save_cashbook() {
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $post_data['transaction_date'] = isset($post_data['transaction_date']) && !empty($post_data['transaction_date']) ? date('Y-m-d', strtotime($post_data['transaction_date'])) : NULL;
        $post_data['department_id'] = isset($post_data['department_id']) && !empty($post_data['department_id']) ? $post_data['department_id'] : NULL;
        $post_data['account_id'] = isset($post_data['account_id']) && !empty($post_data['account_id']) ? $post_data['account_id'] : NULL;
        $post_data['on_behalf_of'] = isset($post_data['on_behalf_of']) && !empty($post_data['on_behalf_of']) ? $post_data['on_behalf_of'] : NULL;
        if (isset($post_data['pay_rec_id']) && !empty($post_data['pay_rec_id'])) {
            
            // Increase/Decrease stock in Department and Account
            $this->update_account_amount_on_delete($post_data['pay_rec_id']);
            
            $update_arr = array();
            $update_arr['payment_receipt'] = $post_data['payment_receipt'];
            $update_arr['transaction_date'] = $post_data['transaction_date'];
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['account_id'] = $post_data['account_id'];
            $update_arr['on_behalf_of'] = $post_data['on_behalf_of'];
            $update_arr['amount'] = $post_data['amount'];
            $update_arr['c_amt'] = $post_data['amount'];
            $update_arr['narration'] = $post_data['narration'];
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['pay_rec_id'] = $post_data['pay_rec_id'];
            $result = $this->crud->update('payment_receipt', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Cashbook Updated Successfully');
                
                // Increase/Decrease stock in Department and Account
                $this->update_account_amount_on_insert($post_data['pay_rec_id']);
            }
        } else {
            $payment_receipt = $this->crud->get_max_number('payment_receipt', 'voucher_no');
            $voucher_no = 1;
            if ($payment_receipt->voucher_no > 0) {
                $voucher_no = $payment_receipt->voucher_no + 1;
            }
            $insert_arr = array();
            $insert_arr['payment_receipt'] = $post_data['payment_receipt'];
            $insert_arr['cash_cheque'] = '1';
            $insert_arr['voucher_no'] = $voucher_no;
            $insert_arr['transaction_date'] = $post_data['transaction_date'];
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['account_id'] = $post_data['account_id'];
            $insert_arr['on_behalf_of'] = $post_data['on_behalf_of'];
            $insert_arr['amount'] = $post_data['amount'];
            $insert_arr['c_amt'] = $post_data['amount'];
            $insert_arr['narration'] = $post_data['narration'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('payment_receipt', $insert_arr);
            $pay_rec_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";

                // Increase/Decrease stock in Department and Account
                $this->update_account_amount_on_insert($pay_rec_id);
            }
        }
        print json_encode($return);
        exit;
    }

    function update_account_amount_on_insert($pay_rec_id) {
        $payment_receipt_data = $this->crud->get_data_row_by_id('payment_receipt', 'pay_rec_id', $pay_rec_id);
        $department_amount = $this->crud->get_id_by_val('account', 'amount', 'account_id', $payment_receipt_data->department_id);
        $account_amount = $this->crud->get_id_by_val('account', 'amount', 'account_id', $payment_receipt_data->account_id);
        if ($payment_receipt_data->payment_receipt == '1') {
            $department_amount = (float) $department_amount - (float) $payment_receipt_data->amount;
            $account_amount = (float) $account_amount + (float) $payment_receipt_data->amount;
        } else {
            $department_amount = (float) $department_amount + (float) $payment_receipt_data->amount;
            $account_amount = (float) $account_amount - (float) $payment_receipt_data->amount;
        }
        $department_amount = number_format((float) $department_amount, '2', '.', '');
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $department_amount), array('account_id' => $payment_receipt_data->department_id));
        $this->crud->update('account', array('amount' => $account_amount, 'c_amount' => $account_amount), array('account_id' => $payment_receipt_data->account_id));
    }
    
    function update_account_amount_on_delete($pay_rec_id) {
        $payment_receipt_data = $this->crud->get_data_row_by_id('payment_receipt', 'pay_rec_id', $pay_rec_id);
        $department_amount = $this->crud->get_id_by_val('account', 'amount', 'account_id', $payment_receipt_data->department_id);
        $account_amount = $this->crud->get_id_by_val('account', 'amount', 'account_id', $payment_receipt_data->account_id);
        if ($payment_receipt_data->payment_receipt == '1') {
            $department_amount = (float) $department_amount + (float) $payment_receipt_data->amount;
            $account_amount = (float) $account_amount - (float) $payment_receipt_data->amount;
        } else {
            $department_amount = (float) $department_amount - (float) $payment_receipt_data->amount;
            $account_amount = (float) $account_amount + (float) $payment_receipt_data->amount;
        }
        $department_amount = number_format((float) $department_amount, '2', '.', '');
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $department_amount), array('account_id' => $payment_receipt_data->department_id));
        $this->crud->update('account', array('amount' => $account_amount, 'c_amount' => $account_amount), array('account_id' => $payment_receipt_data->account_id));
    }
    
    function payment_receipt_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'payment_receipt pr';
        $config['select'] = 'pr.*, d.account_name as department, a.account_name, s.sell_no, o.other_no, xrf.receipt_no';
        $config['joins'][] = array('join_table' => 'account d', 'join_by' => 'd.account_id = pr.department_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = pr.account_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell s', 'join_by' => 's.sell_id = pr.sell_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'other o', 'join_by' => 'o.other_id = pr.other_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'hallmark_xrf xrf', 'join_by' => 'xrf.xrf_id = pr.xrf_id ', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'd.account_name', 'pr.amount', 'pr.voucher_no', 'pr.narration');
        $config['column_order'] = array(null, 'a.account_name', 'd.account_name', 'pr.amount', 'pr.voucher_no', 'pr.narration');
        $config['order'] = array('pr.pay_rec_id' => 'desc');
//        $config['wheres'][] = array('column_name' => 'pr.payment_receipt', 'column_value' => $post_data['payment_receipt']);
        $config['wheres'][] = array('column_name' => 'pr.cash_cheque', 'column_value' => '1');
        $config['custom_where'] = ' 1 ';
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= ' AND (pr.department_id IN('.$department_ids.') OR pr.account_id IN('.$department_ids.'))';
        }
        if (!empty($post_data['from_amount'])) {
            $config['wheres'][] = array('column_name' => 'pr.amount >=', 'column_value' => $post_data['from_amount']);
        }
        if (!empty($post_data['to_amount'])) {
            $config['wheres'][] = array('column_name' => 'pr.amount <=', 'column_value' => $post_data['to_amount']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['from_date'])) {
                $config['wheres'][] = array('column_name' => 'pr.transaction_date >=', 'column_value' => $from_date);
            }
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 'pr.transaction_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['department_filter'])) {
//            $config['wheres'][] = array('column_name' => 'pr.department_id', 'column_value' => $post_data['department_filter']);
            if($post_data['payment_receipt'] == '1'){
                $config['custom_where'] .= ' AND ( (pr.payment_receipt = 1 AND pr.department_id = '. $post_data['department_filter'] .') OR (pr.payment_receipt = 2 AND pr.account_id = '. $post_data['department_filter'] .') )';
            } else {
                $config['custom_where'] .= ' AND ( (pr.payment_receipt = 2 AND pr.department_id = '. $post_data['department_filter'] .') OR (pr.payment_receipt = 1 AND pr.account_id = '. $post_data['department_filter'] .') )';
            }
        } else {
//            $config['wheres'][] = array('column_name' => 'pr.payment_receipt', 'column_value' => $post_data['payment_receipt']);
            $config['custom_where'] .= ' AND pr.payment_receipt = '.$post_data['payment_receipt'].' AND a.account_group_id != '. DEPARTMENT_GROUP .' ';
        }
        if (!empty($post_data['account_filter'])) {
//            if($post_data['payment_receipt'] == '1'){
//                $config['wheres'][] = array('column_name' => 'pr.payment_receipt', 'column_value' => '2');
//            } else {
//                $config['wheres'][] = array('column_name' => 'pr.payment_receipt', 'column_value' => '1');
//            }
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $post_data['account_filter']);
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 'pr.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'.$this->db->last_query(); exit;
        $data = array();

        foreach ($list as $cashbook) {
            $row = array();
            $action = '';
            if($cashbook->audit_status != AUDIT_STATUS_AUDITED){
                if(empty($cashbook->sell_id) && empty($cashbook->other_id) && empty($cashbook->xrf_id)){
                    if($this->app_model->have_access_role(CASHBOOK_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("reports/cashbook/" . $cashbook->pay_rec_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                    if($this->app_model->have_access_role(CASHBOOK_MODULE_ID, "delete")){
                        $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('reports/delete_cashbook/' . $cashbook->pay_rec_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else if(!empty($cashbook->xrf_id)){
                    if($this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("hallmark/xrf/" . $cashbook->xrf_id) . '" target="_blank" ><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                } else if(!empty($cashbook->other_id)){
                    if($this->app_model->have_access_role(OTHER_ENTRY_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("other/add/" . $cashbook->other_id) . '" target="_blank" ><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                } else {
                    if($this->app_model->have_access_role(SELL_PURCHASE_MODULE_ID, "edit")){
                        $action .= '<a href="' . base_url("sell/add/" . $cashbook->sell_id) . '" target="_blank" ><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                }
            } else {
                $action .= '<a href="' . base_url("reports/cashbook/" . $cashbook->pay_rec_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
//            $checked = $cashbook->is_payment_received == 1 && empty($cashbook->sell_id) ? 'checked' : '';
            $checked = ($cashbook->is_payment_received == 1) ? 'checked' : '';
            $action .= '<input type="checkbox" class="received" id="' . $cashbook->pay_rec_id . '" ' . $checked . '>';
            
            $audit_status = '';
            if($cashbook->audit_status == AUDIT_STATUS_AUDITED){
                $audit_status = 'A';
            } else if($cashbook->audit_status == AUDIT_STATUS_SUSPECTED){
                $audit_status = 'S';
            } else {
                $audit_status = 'P';
            }
            $action .= '<a href="javascript:void(0);" class="audit_status_button" data-audit_status_pay_rec_id="' . $cashbook->pay_rec_id . '" data-audit_status="' . $cashbook->audit_status . '" style="margin: 8px;">'. $audit_status .'</a>';
            
            $row[] = $action;
            $row[] = $cashbook->account_name;
            $row[] = $cashbook->department;
            $row[] = $cashbook->amount;
            if(empty($cashbook->sell_id) && empty($cashbook->other_id) && empty($cashbook->xrf_id)){
                $row[] = $cashbook->voucher_no;
            } else if(!empty($cashbook->xrf_id)){
                $row[] = $cashbook->other_no;
            } else if(!empty($cashbook->other_id)){
                $row[] = $cashbook->other_no;
            } else {
                $row[] = $cashbook->sell_no;
            }
            $row[] = $cashbook->narration;
            $row[] = $cashbook->transaction_date ? date('d-m-Y', strtotime($cashbook->transaction_date)) : '';
            $row[] = $cashbook->is_payment_received;
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

    function get_opening_closing_balance() {
        $data = array();
        if($_POST['from_date'] == 'everything_from_start'){
            $from_date = '1970-01-01';
        } else {
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        }
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $date = date('Y-m-d');

        $payment_where = '';
        $receipt_where = '';
        if(isset($_POST['department_filter']) && !empty($_POST['department_filter'])){
            $payment_where .= ' AND ( (p.payment_receipt = 1 AND p.department_id = '. $_POST['department_filter'] .') OR (p.payment_receipt = 2 AND p.account_id = '. $_POST['department_filter'] .') )';
            $receipt_where .= ' AND ( (p.payment_receipt = 2 AND p.department_id = '. $_POST['department_filter'] .') OR (p.payment_receipt = 1 AND p.account_id = '. $_POST['department_filter'] .') )';
        } else {
            $payment_where .= ' AND p.payment_receipt = 1';
            $receipt_where .= ' AND p.payment_receipt = 2';
            $all_department_ids = $this->applib->all_department_ids();
            if(!empty($all_department_ids)){
                $all_department_ids = implode(',', $all_department_ids);
                $payment_where .= ' AND p.account_id NOT IN ('. $all_department_ids .') ';
                $receipt_where .= ' AND p.account_id NOT IN ('. $all_department_ids .') ';
            }
        }
        $where = '';
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $where .= ' AND (p.department_id IN('.$department_ids.') OR p.account_id IN('.$department_ids.'))';
        }
        if (isset($_POST['account_filter']) && !empty($_POST['account_filter'])) {
            $where .= ' AND p.account_id = '. $_POST['account_filter'] .' ';
        }
        
        $opening_balance_in_rupees = 0;
        if(isset($_POST['department_filter']) && !empty($_POST['department_filter'])){
            $account_data = $this->crud->get_row_by_id('account', array('account_id' => $_POST['department_filter']));
            if(!empty($account_data)){
                if($account_data[0]->rupees_ob_credit_debit == '1'){
                    $opening_balance_in_rupees = (float) $opening_balance_in_rupees - (float) $account_data[0]->opening_balance_in_rupees;
                } else {
                    $opening_balance_in_rupees = (float) $opening_balance_in_rupees + (float) $account_data[0]->opening_balance_in_rupees;
                }
            }
        } else {
            $account_data = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            if(!empty($account_data)){
                foreach ($account_data as $account_row){
                    if($account_row->rupees_ob_credit_debit == '1'){
                        $opening_balance_in_rupees = (float) $opening_balance_in_rupees - (float) $account_row->opening_balance_in_rupees;
                    } else {
                        $opening_balance_in_rupees = (float) $opening_balance_in_rupees + (float) $account_row->opening_balance_in_rupees;
                    }
                }
            }
        }
        
        $open_payment = $this->crud->getFromSQL('SELECT SUM(p.amount) AS open_payment FROM payment_receipt p WHERE p.cash_cheque = 1 AND p.transaction_date < "' . $from_date . '" '. $where . ' '. $payment_where . ' ');
        $open_receipt = $this->crud->getFromSQL('SELECT SUM(p.amount) AS open_receipt FROM payment_receipt p WHERE p.cash_cheque = 1 AND p.transaction_date < "' . $from_date . '" '. $where . ' '. $receipt_where . ' ');
        $opening_balance = $open_receipt[0]->open_receipt - $open_payment[0]->open_payment;
        $opening_balance = (float) $opening_balance + (float) $opening_balance_in_rupees;
        $data['opening_balance'] = number_format((float) $opening_balance, '2', '.', '');

        $close_payment = $this->crud->getFromSQL('SELECT SUM(p.amount) AS close_payment FROM payment_receipt p WHERE p.cash_cheque = 1 AND p.transaction_date >= "' . $from_date . '" AND p.transaction_date <= "' . $to_date . '" '. $where . ' '. $payment_where . ' ');
        $close_receipt = $this->crud->getFromSQL('SELECT SUM(p.amount) AS close_receipt FROM payment_receipt p WHERE p.cash_cheque = 1 AND p.transaction_date >= "' . $from_date . '" AND p.transaction_date <= "' . $to_date . '" '. $where . ' '. $receipt_where . ' ');
        $closing_balance = (float) $close_receipt[0]->close_receipt - (float) $close_payment[0]->close_payment;
        $final_closing_balance = (float) $closing_balance + (float) $opening_balance;
        $data['closing_balance'] = number_format((float) $final_closing_balance, '2', '.', '');
        $data['today_balance'] = number_format((float) $closing_balance, '2', '.', '');

        print json_encode($data);
        exit;
    }

    function delete_cashbook($pay_rec_id = '') {
        // Increase/Decrease stock in Department and Account
        $this->update_account_amount_on_delete($pay_rec_id);
        
        $this->crud->delete('payment_receipt', array('pay_rec_id' => $pay_rec_id));
    }

    function audit_status_cashbook() {
        $return = array();
        if(isset($_POST['audit_status_pay_rec_id']) && !empty($_POST['audit_status_pay_rec_id']) && isset($_POST['audit_status']) && !empty($_POST['audit_status'])){
            $result = $this->crud->update('payment_receipt', array('audit_status' => $_POST['audit_status']), array('pay_rec_id' => $_POST['audit_status_pay_rec_id']));
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

    function get_department_cash($to_site_id) {
        $data = array();
        if (!empty($to_site_id)) {
            $amount = $this->crud->getFromSQL('SELECT amount FROM account WHERE account_id = "' . $to_site_id . '"');
            $amount = !empty($amount) ? $amount[0] : 0;
            $data['amount'] = $amount->amount;
        }
        print json_encode($data);
        exit;
    }

    function check_account_group() {
        $return_value = '0';
        $account_id = $_POST['account_id'];
        $account_group_id = $this->crud->get_id_by_val('account', 'account_group_id', 'account_id', $account_id);
        if($account_group_id != CUSTOMER_GROUP){
            $return_value = '1';
        }
        echo $return_value;
    }
    
    function receive_payment() {
        $pr_id = $_POST['pr_id'];
        $is_checked = $_POST['is_checked'];
        $this->crud->update('payment_receipt', array('is_payment_received' => $is_checked), array('pay_rec_id' => $pr_id));
    }

    function send_sms(){
        $data = array();
        $post_data = $this->input->post();
        $accounts = json_decode($post_data['accounts']);
//        echo "<pre>"; print_r($accounts); exit;
        if (!empty($accounts)) {
            foreach($accounts as $account){
                $account_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $account->id);
                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $account->id);
                if(!empty($mobile_no)){
                    $sms = SEND_OUTSTANDING_SMS;
                    $vars = array(
                        '{{customer_name}}' => $account_name,
                        '{{gold_fine}}' => $account->gold,
                        '{{silver_fine}}' => $account->silver,
                        '{{amount}}' => $account->amount,
                        '{{date}}' => $account->bal_date,
                    );
                    $sms = strtr($sms, $vars);
                    $this->applib->send_sms($mobile_no, $sms);
                }
            }
        }
        $data['success'] = "sent";
        print json_encode($data);
        exit;
    }
    
    function send_whatsapp_sms($account_id = '', $gold = 0, $silver = 0, $amount = 0, $bal_date = ''){
        if (!empty($account_id)) {
            $account_name = $this->crud->get_id_by_val('account', 'account_name', 'account_id', $account_id);
            $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $account_id);
            if(!empty($mobile_no)){
                $sms = SEND_OUTSTANDING_SMS;
                $vars = array(
                    '{{customer_name}}' => $account_name,
                    '{{gold_fine}}' => $gold,
                    '{{silver_fine}}' => $silver,
                    '{{amount}}' => $amount,
                    '{{date}}' => $bal_date,
                );
                $sms = strtr($sms, $vars);
                redirect('https://api.whatsapp.com/send?phone=91'.$mobile_no.'&text='.$sms);
            }
        }
    }
    
    // Interest Report related Functions
    function interest($journal_id = '', $account_id = '') {
        if ($this->applib->have_access_role(INTEREST_MODULE_ID, "view")) {
            $data = array();

//            $account = $this->crud->getFromSQL('SELECT a.* FROM `account` a JOIN user_account_group g ON(g.account_group_id = a.account_group_id) WHERE g.user_id = "' . $this->logged_in_id . '" AND a.account_group_id="'.CUSTOMER_GROUP.'" ORDER BY a.account_name');

            //$account = $this->crud->get_all_with_where('account', '', '', array('account_group_id' => CUSTOMER_GROUP));
//            $data['account'] = $account;
            
            if(!empty($journal_id) && !empty($account_id)){
                $journal_data = $this->crud->get_row_by_id('journal', array('journal_id' => $journal_id));
                if(!empty($journal_data)){
                    $journal_data = $journal_data[0];
                    $data['account_id'] = $account_id;
                    $data['gold_rate'] = $journal_data->gold_rate;
                    $data['silver_rate'] = $journal_data->silver_rate;
                    $data['interest_rate'] = $journal_data->interest_rate;
                    $data['journal_date'] = $journal_data->journal_date;
                }
            } else {
                $data['gold_rate'] = $this->crud->get_id_by_val('settings', 'settings_value', 'settings_key', 'gold_rate');
                $data['silver_rate'] = $this->crud->get_id_by_val('settings', 'settings_value', 'settings_key', 'silver_rate');
            }
            set_page('reports_new_sp3/interest_new_sp3',$data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_interest_rate() {
        $data = array();
        $post_data = $this->input->post();
        $account_int = $this->crud->get_id_by_val('account', 'interest', 'account_id', $post_data['account_id']);
        if(empty($account_int)){
           $account_int = '0'; 
        }
        $data['interest_rate'] = $account_int;
        print json_encode($data);
        exit;
    }
    
    function interest_datatable() {
        $post_data = $this->input->post();
        $from = '01-'.$post_data['month'];
        $from_date = date('Y-m-d', strtotime($from));
        $to_date = date('Y-m-d', strtotime('last day of this month', strtotime($from_date)));
        $return_data = $this->get_monthly_interest_data($from_date, $to_date, $post_data['account_id'], $post_data['gold_rate'], $post_data['silver_rate'], $post_data['int_rate']);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($return_data[0]),
            "recordsFiltered" => count($return_data[0]),
            "data" => $return_data[0],
        );
        echo json_encode($output);
    }
    
    function generate_interest($month = '') {
        if ($this->applib->have_access_role(INTEREST_MODULE_ID, "view")) {
            $data = array();
            $data['month'] = $month;
            $data['gold_rate'] = $gold_rate = $this->crud->get_id_by_val('settings', 'settings_value', 'settings_key', 'gold_rate');
            $data['silver_rate'] = $silver_rate = $this->crud->get_id_by_val('settings', 'settings_value', 'settings_key', 'silver_rate');

            $from = '01-' . $month;
            $from_date = date('Y-m-d', strtotime($from));
            $to_date = date('Y-m-d', strtotime('last day of this month', strtotime($from_date)));

            $interest_accounts = array();
//            $accounts = $this->crud->get_all_with_where('account', '', '', array('account_group_id' => CUSTOMER_GROUP));
            $accounts = $this->crud->get_where_in_result('account', 'account_group_id', array(CUSTOMER_GROUP, SUNDRY_CREDITORS_ACCOUNT_GROUP, SUNDRY_DEBTORS_ACCOUNT_GROUP));
            foreach ($accounts as $account) {
                if (!empty($account->interest)) {
//                    $acc_row = array();
//                    $acc_row['account_id'] = $account->account_id;
//                    $acc_row['account_name'] = $account->account_name;
//                    $acc_row['interest_per'] = '';
//                    $acc_row['month_interest'] = '';
//                    $interest_accounts[] = $acc_row;
                    $return_data = $this->get_monthly_interest_data($from_date, $to_date, $account->account_id, $gold_rate, $silver_rate, $account->interest);
                    if ($return_data[1] > 0) {
                        $acc_row = array();
                        $acc_row['account_id'] = $account->account_id;
                        $acc_row['account_name'] = $account->account_name;
                        $acc_row['interest_per'] = $account->interest;
                        $acc_row['month_interest'] = round(abs($return_data[1]));
                        $interest_accounts[] = $acc_row;
                    }
                }
            }
            $data['interest_accounts'] = $interest_accounts;
            set_page('reports_new_sp3/generate_interest_new_sp3', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function store_interest_in_journal() {
        $return = array();
        $from = '01-' . $_POST['month'];
        $from_date = date('Y-m-d', strtotime($from));
        $to_date = date('Y-m-d', strtotime('last day of this month', strtotime($from_date)));
        $nextmonth_firstdate = date('Y-m-d', strtotime('+1 month', strtotime($from)));
        $gold_rate = $_POST['gold_rate'];
        $silver_rate = $_POST['silver_rate'];
        $account_ids = $_POST['account_id'];
        $interest_pers = $_POST['interest_per'];
        $real_interests = $_POST['real_interest'];
        foreach ($account_ids as $account_key => $account_id) {
            
            // Revert journal Entry
            $journal_id = $this->crud->get_column_value_by_id('journal', 'journal_id', array('interest_account_id' => $account_id, 'journal_date' => $nextmonth_firstdate));
            if (isset($journal_id) && !empty($journal_id)) {
                $journal_detail_data = $this->crud->get_row_by_id('journal_details', array('journal_id' => $journal_id));
                if (!empty($journal_detail_data)) {
                    $journal_detail_data = $journal_detail_data[0];
                    // Update Worker Account Amount
                    $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $account_id));
                    $account_amount = $account_amount -  (float) $journal_detail_data->amount;
                    $account_amount = number_format((float) $account_amount, '2', '.', '');
                    $account_c_amount = $this->crud->get_column_value_by_id('account', 'c_amount', array('account_id' => $account_id));
                    $account_c_amount = $account_c_amount -  (float) $journal_detail_data->amount;
                    $account_c_amount = number_format((float) $account_c_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $account_amount, 'c_amount' => $account_c_amount), array('account_id' => $account_id));

                    // Update MF Loss Account Amount
                    $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID));
                    $account_amount = $account_amount + (float) $journal_detail_data->amount;
                    $account_amount = number_format((float) $account_amount, '2', '.', '');
                    $account_c_amount = $this->crud->get_column_value_by_id('account', 'c_amount', array('account_id' => CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID));
                    $account_c_amount = $account_c_amount +  (float) $journal_detail_data->amount;
                    $account_c_amount = number_format((float) $account_c_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $account_amount, 'c_amount' => $account_c_amount), array('account_id' => CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID));

                    $this->crud->delete('journal_details', array('journal_id' => $journal_id));
                }
                $this->crud->delete('journal', array('journal_id' => $journal_id));
            }
            
            $interest_per = $interest_pers[$account_key];
            $month_interest = $real_interests[$account_key];
            if(!empty($month_interest)){
                $insert_arr = array();
                $insert_arr['department_id'] = SALES_MAIN_DEPARTMENT_ACCOUNT_ID;
                $insert_arr['journal_date'] = $nextmonth_firstdate;
                $insert_arr['interest_account_id'] = $account_id;
                $insert_arr['gold_rate'] = $gold_rate;
                $insert_arr['silver_rate'] = $silver_rate;
                $insert_arr['interest_rate'] = $interest_per;
                $insert_arr['created_at'] = $this->now_time;
                $insert_arr['created_by'] = $this->logged_in_id;
                $insert_arr['updated_at'] = $this->now_time;
                $insert_arr['updated_by'] = $this->logged_in_id;
                $result = $this->crud->insert('journal', $insert_arr);
                $journal_id = $this->db->insert_id();
                if ($result) {
                    $return['success'] = "Added";
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'Monthly Interest Stored Successfully');

                    $insert_item = array();
                    $insert_item['journal_id'] = $journal_id;
                    $insert_item['account_id'] = $account_id;
                    $insert_item['amount'] = $month_interest;
                    $insert_item['c_amt'] = $month_interest;
                    $insert_item['type'] = '1';
                    $insert_item['narration'] = STORE_INTEREST_NARRATION;
                    $insert_item['created_at'] = $this->now_time;
                    $insert_item['created_by'] = $this->logged_in_id;
                    $insert_item['updated_at'] = $this->now_time;
                    $insert_item['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('journal_details', $insert_item);

                    // Increase amount in Account
                    $account_data = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
                    $account_data = $account_data[0];
                    $acc_amount = (float) $account_data->amount + (float) $month_interest;
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $acc_c_amount = (float) $account_data->c_amount + (float) $month_interest;
                    $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount), array('account_id' => $account_id));

                    $insert_item = array();
                    $insert_item['journal_id'] = $journal_id;
                    $insert_item['account_id'] = CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID;
                    $insert_item['amount'] = $month_interest;
                    $insert_item['c_amt'] = $month_interest;
                    $insert_item['type'] = '2';
                    $insert_item['narration'] = STORE_INTEREST_NARRATION;
                    $insert_item['created_at'] = $this->now_time;
                    $insert_item['created_by'] = $this->logged_in_id;
                    $insert_item['updated_at'] = $this->now_time;
                    $insert_item['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('journal_details', $insert_item);

                    // Decrease amount in Account
                    $account_data = $this->crud->get_row_by_id('account', array('account_id' => CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID));
                    $account_data = $account_data[0];
                    $acc_amount = (float) $account_data->amount - (float) $month_interest;
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $acc_c_amount = (float) $account_data->c_amount - (float) $month_interest;
                    $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount), array('account_id' => CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID));
                }
            }
        }
//        echo '<pre>'; print_r($return); exit;
        echo json_encode($return);
    }

    function get_monthly_interest_data($from_date, $to_date, $account_id, $gold_rate, $silver_rate, $int_rate) {
//        $offset = $post_data['offset'];
        $opening_data = $this->get_opening_customer_ledger($from_date, $account_id, '', '0');
        $customer_ledger_data = $this->applib->get_customer_ledger_data_arr($from_date, $to_date, $account_id, '', '0', '0');
        $customer_ledger_data = array_merge($opening_data, $customer_ledger_data);
        uasort($customer_ledger_data, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });
        $customer_ledger_data = array_values($customer_ledger_data);
//        echo '<pre>'; print_r($customer_ledger_data); exit;
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $total_amount = 0;
        $total_net_amount = 0;
        $daily_interest = 0;
        $current_date = '';
        $net_amount = 0;
        $interest_data_arr = array();
        if(!empty($customer_ledger_data)){
            foreach ($customer_ledger_data as $key => $customer_ledger) {

                if(empty($customer_ledger->gold_fine) && empty($customer_ledger->silver_fine) && empty($customer_ledger->amount)){
                } else {
                    if($current_date != '' && $current_date != $customer_ledger->st_date){
                        $row[] = (!empty(strtotime($current_date))) ? date('d-m-Y', strtotime($current_date)) : '';
                        $row[] = $total_gold_fine;
                        $row[] = $total_silver_fine;
                        $row[] = $total_amount;
                        $row[] = $total_net_amount;
                        $row[] = $daily_interest;
                        $interest_data_arr[] = $row;
                    }
                    $total_net_amount = 0;
                    $current_date = $customer_ledger->st_date;
                    $total_gold_fine = number_format((float) $total_gold_fine, 3, '.', '') + number_format((float) $customer_ledger->gold_fine, 3, '.', '');
                    $total_silver_fine = number_format((float) $total_silver_fine, 3, '.', '') + number_format((float) $customer_ledger->silver_fine, 3, '.', '');
                    $total_amount = number_format((float) $total_amount, 2, '.', '') + number_format((float) $customer_ledger->amount, 2, '.', '');
                    $gold_amount = (float) $total_gold_fine * (float) $gold_rate / 10;
                    $silver_amount = (float) $total_silver_fine * (float) $silver_rate / 10;
                    $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $total_amount;
                    $total_net_amount = (float) $total_net_amount + (float) $net_amount;
                    $daily_interest = (((float) $net_amount * (float) $int_rate) / 100) / 365;
                    $row = array();
                }
            }
            $row = array();
            $row[] = (!empty(strtotime($customer_ledger->st_date))) ? date('d-m-Y', strtotime($customer_ledger->st_date)) : '';
            $row[] = $total_gold_fine;
            $row[] = $total_silver_fine;
            $row[] = $total_amount;
            $row[] = $net_amount;
            $row[] = $daily_interest;
            $interest_data_arr[] = $row;
        }
//        print_r($interest_data_arr);
        $data = array();
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date = date('d-m-Y', strtotime($to_date));
        $old_get_transaction = array($from_date, '0.000', '0.000', '0.00', '0.00', '0.00');
        $total_month_interest = 0;
        $transaction_inc = 0;
        while (strtotime($from_date) <= strtotime($to_date)) {
            $get_transaction = array_filter(
                $interest_data_arr,
                function ($e) use ($from_date) {
                    return ($e[0] == $from_date) ? $e : array();
                }
            );
            if(!empty($get_transaction)){
                $old_get_transaction = $get_transaction[$transaction_inc];
                $transaction_inc++;
            } else {
                if($transaction_inc == 0){
                    if(isset($opening_data[0]) && !empty($opening_data[0])){
                        $opening_gold_fine = $opening_data[0]->gold_fine;
                        $opening_silver_fine = $opening_data[0]->silver_fine;
                        $opening_amount = $opening_data[0]->amount;
                        $gold_amount = (float) $opening_gold_fine * (float) $gold_rate / 10;
                        $silver_amount = (float) $opening_silver_fine * (float) $silver_rate / 10;
                        $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $opening_amount;
                        $daily_interest = (((float) $net_amount * (float) $int_rate) / 100) / 365;

                        $old_get_transaction = array($from_date, $opening_gold_fine, $opening_silver_fine, $opening_amount, $net_amount, $daily_interest);
                    }
                }
            }
            $row = array();
            $row[] = $from_date;
            $row[] = number_format((float) $old_get_transaction[1], 3, '.', '');
            $row[] = number_format((float) $old_get_transaction[2], 3, '.', '');
            $row[] = number_format((float) $old_get_transaction[3], 2, '.', '');
            $row[] = number_format((float) $old_get_transaction[4], 2, '.', '');
            $row[] = number_format((float) $old_get_transaction[5], 2, '.', '');
            $data[] = $row;
            $from_date = date ("d-m-Y", strtotime("+1 day", strtotime($from_date)));
            $total_month_interest = (float) $total_month_interest + number_format((float) $old_get_transaction[5], 2, '.', '');
        }
        $return_data = array();
        $return_data[] = $data;
        $return_data[] = number_format((float) $total_month_interest, 2, '.', '');
        return $return_data;
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
            set_page('reports_new_sp3/customer_ledger_new_sp3', $data);
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
                                    $action .= '<a href="' . base_url("sell_purchase_type_3/add/" . $customer_ledger->st_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
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
                $particular .= (isset($customer_ledger->interest_account_id) && $customer_ledger->interest_account_id == $customer_ledger->account_id) ? ' <a href="' . base_url("reports_new_sp3/interest/" . $customer_ledger->journal_id . "/" . $customer_ledger->account_id ) . '" target="_blank" title="Interest" ><b>Interest</b></a>' : '' ;
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
                } else {
                    $row[] = number_format((float) $customer_ledger->grwt, 3, '.', '');
                    $row[] = number_format((float) $customer_ledger->less, 3, '.', '');
                    $row[] = number_format((float) $customer_ledger->net_wt, 3, '.', '');
                    $row[] = number_format((float) $customer_ledger->touch_id, 2, '.', '');
                    $row[] = number_format((float) $customer_ledger->wstg, 3, '.', '');
                }
//                $row[] = number_format((float) $customer_ledger->grwt, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->less, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->net_wt, 3, '.', '');
//                $row[] = number_format((float) $customer_ledger->touch_id, 2, '.', '');
//                $row[] = number_format((float) $customer_ledger->wstg, 3, '.', '');
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
    
    function get_item_current_grwt($item_stock_id) {
        $data = array();
        if (!empty($item_stock_id)) {
            $amount = $this->crud->getFromSQL('SELECT grwt, less FROM item_stock WHERE item_stock_id = "' . $item_stock_id . '"');
            $data['grwt'] = $amount[0]->grwt;
            $data['less'] = $amount[0]->less;
        }
        print json_encode($data);
        exit;
    }

    // Balance Sheet related Functions
    function balance_sheet() {
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(BALANCE_SHEET_MODULE_ID, 'view')) {
            $data = array();
            $date = date('Y-m-d');
            $data['from_date'] = $this->applib->get_financial_start_date_by_date($date);
            $data['account_groups'] = $this->crud->get_columns_val_by_where('account_group', 'account_group_id,account_group_name', array());
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            set_page('reports_new_sp3/balance_sheet_new_sp3', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function balance_sheet_datatable() {
        $post_data = $this->input->post();

        $gold_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $silver_rate= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));

        $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        $upto_balance_date = date('Y-m-d', strtotime($post_data['to_date']));
        
        $accounts = $this->crud->get_account_for_balance_sheet($upto_balance_date);
        $all_account_arr = array();
        $balance_sheet_acc_ids = array();
        if(!empty($accounts)){
            foreach ($accounts as $account) {
                $all_account_arr[$account['account_id']] = $account['credit_limit'];
                $balance_sheet_acc_ids[] = $account['account_id'];
            }
        }

        $account_group_id = 0;

        $d_sell_items_data = $this->crud->get_sell_items_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_payment_receipt_data = $this->crud->get_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_metal_payment_data = $this->crud->get_metal_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_from_stock_transfer_data = $this->crud->get_stock_transfer_for_balance_sheet_report_department($upto_balance_date, $account_group_id, 'ST F');
        $d_to_stock_transfer_data = $this->crud->get_stock_transfer_for_balance_sheet_report_department($upto_balance_date, $account_group_id, 'ST T');
        $d_cashbook_data = $this->crud->get_cashbook_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_manufacture_casting_data = $this->crud->get_manufacture_casting_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_other_sell_items_data = $this->crud->get_other_sell_items_for_balance_sheet_report_department($upto_balance_date, $account_group_id);
        $d_other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_balance_sheet_report_department($upto_balance_date, $account_group_id);

        $opening_data = $this->crud->get_opening_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $dipartment_list = array_merge($opening_data, $d_sell_items_data, $d_metal_payment_data, $d_payment_receipt_data, $d_cashbook_data, $d_manufacture_issue_receive_data, $d_manufacture_issue_receive_silver_data, $d_manufacture_manu_hand_made_data, $d_manufacture_casting_data, $d_manufacture_machin_chain_data, $d_from_stock_transfer_data, $d_to_stock_transfer_data, $d_other_sell_items_data, $d_other_payment_receipt_data);
        
        $sell_items_data1 = $this->crud->get_sell_items_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $sp_discount_data = $this->crud->get_sell_discount_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $sell_items_data = array_merge($sell_items_data1, $sp_discount_data);
        $payment_receipt_data = $this->crud->get_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $payment_receipt_data_bank = $this->crud->get_payment_receipt_for_balance_sheet_report_bank($upto_balance_date, $account_group_id);
        $metal_payment_data = $this->crud->get_metal_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $gold_bhav_data = $this->crud->get_gold_bhav_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $silver_bhav_data = $this->crud->get_silver_bhav_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $journal_data = $this->crud->get_journal_naam_jama_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $cashbook_data = $this->crud->get_cashbook_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $manufacture_issue_receive_data = $this->crud->get_manufacture_issue_receive_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $manufacture_issue_receive_silver_data = $this->crud->get_manufacture_issue_receive_silver_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $manufacture_manu_hand_made_data = $this->crud->get_manufacture_manu_hand_made_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $manufacture_casting_data = $this->crud->get_manufacture_casting_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $manufacture_machin_chain_data = $this->crud->get_manufacture_machin_chain_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $other_sell_items_data = $this->crud->get_other_sell_items_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $other_payment_receipt_data = $this->crud->get_other_payment_receipt_for_balance_sheet_report($upto_balance_date, $account_group_id);
        $other_payment_receipt_data_bank = $this->crud->get_other_payment_receipt_for_balance_sheet_report_bank($upto_balance_date, $account_group_id);
        
        $hisab_fine_data_w = $this->crud->get_hisab_fine_for_balance_sheet_report($upto_balance_date, WORKER_GROUP);
        $hisab_fine_data_d = $this->crud->get_hisab_fine_for_balance_sheet_report($upto_balance_date, DEPARTMENT_GROUP);
        $hisab_fine_data_ea = $this->crud->get_hisab_fine_for_balance_sheet_report($upto_balance_date, EXPENSE_ACCOUNT_GROUP);
        $hisab_fine_data_w_s = $this->crud->get_hisab_fine_silver_for_balance_sheet_report($upto_balance_date, WORKER_GROUP);
        $hisab_fine_data_d_s = $this->crud->get_hisab_fine_silver_for_balance_sheet_report($upto_balance_date, DEPARTMENT_GROUP);
        $hisab_fine_data_ea_s = $this->crud->get_hisab_fine_silver_for_balance_sheet_report($upto_balance_date, EXPENSE_ACCOUNT_GROUP);
        $hisab_fine_data = array_merge($hisab_fine_data_w, $hisab_fine_data_d, $hisab_fine_data_ea, $hisab_fine_data_w_s, $hisab_fine_data_d_s, $hisab_fine_data_ea_s);
        
        $hisab_done_ir_data_w = $this->crud->get_hisab_done_ir_for_balance_sheet_report($upto_balance_date, WORKER_GROUP);
        $hisab_done_ir_data_d = $this->crud->get_hisab_done_ir_for_balance_sheet_report($upto_balance_date, DEPARTMENT_GROUP);
        $hisab_done_ir_data_ea = $this->crud->get_hisab_done_ir_for_balance_sheet_report($upto_balance_date, EXPENSE_ACCOUNT_GROUP);
        $hisab_done_ir_data_w_s = $this->crud->get_hisab_done_irs_for_balance_sheet_report($upto_balance_date, WORKER_GROUP);
        $hisab_done_ir_data_d_s = $this->crud->get_hisab_done_irs_for_balance_sheet_report($upto_balance_date, DEPARTMENT_GROUP);
        $hisab_done_ir_data_ea_s = $this->crud->get_hisab_done_irs_for_balance_sheet_report($upto_balance_date, EXPENSE_ACCOUNT_GROUP);
        $hisab_done_ir_data = array_merge($hisab_done_ir_data_w, $hisab_done_ir_data_d, $hisab_done_ir_data_ea, $hisab_done_ir_data_w_s, $hisab_done_ir_data_d_s, $hisab_done_ir_data_ea_s);
        
        $sell_ad_charges_data1 = $this->crud->get_sell_ad_charges_for_balance_sheet_report($upto_balance_date, CUSTOMER_GROUP);
        $sell_ad_charges_data2 = $this->crud->get_sell_ad_charges_for_balance_sheet_report($upto_balance_date, EXPENSE_ACCOUNT_GROUP);
        $sell_ad_charges_data = array_merge($sell_ad_charges_data1, $sell_ad_charges_data2);
        
        $acc_list = array_merge($sell_items_data, $metal_payment_data, $payment_receipt_data, $payment_receipt_data_bank, $gold_bhav_data, $silver_bhav_data, $journal_data, $cashbook_data, $manufacture_issue_receive_data, $manufacture_issue_receive_silver_data, $manufacture_manu_hand_made_data, $manufacture_casting_data, $manufacture_machin_chain_data, $other_sell_items_data, $other_payment_receipt_data, $other_payment_receipt_data_bank, $hisab_fine_data, $hisab_done_ir_data, $sell_ad_charges_data);
        $list = array_merge($dipartment_list, $acc_list);
        uasort($list, function($a, $b) {
            $value1 = strtotime($a->st_date);
            $value2 = strtotime($b->st_date);
            return $value1 - $value2;
        });
        $list = array_values($list);
        $outstanding_data_arr = array();
        $account_id_arr = array();
        $capital = 0.0;
        
        $balance_sheet_account_groups = $this->crud->get_columns_val_by_where('account_group', 'account_group_id,account_group_name', array('is_display_in_balance_sheet' => 1));
        $balance_sheet_account_groups = array_column($balance_sheet_account_groups,'account_group_id');
        /*echo "<pre>";
        print_r($balance_sheet_account_groups);
        die();*/
        $issue_rows = array();
        foreach ($list as $key => $list_row) {

            if(!in_array($list_row->account_id,$balance_sheet_acc_ids)) {
                continue;
            }
            
            if((isset($list_row->st_date) && strtotime($list_row->st_date) > 0) || isset($list_row->is_opening_balance)) {

            } else {
                $issue_rows[] = $list_row;
                continue;
            }

            $account_id = $list_row->account_id;

            if(!in_array($list_row->account_group_id,$balance_sheet_account_groups)) {
                continue;
            }

            $account_group_id_department = 0;
            if($list_row->account_group_id == DEPARTMENT_GROUP){
                $account_group_id_department = 1;
            }

            /*---- Date Range Validation ----*/
            //Find Net Profit / Net Loss for < From Date
            if((isset($list_row->st_date) && strtotime($list_row->st_date) > 0 && strtotime($list_row->st_date) < strtotime($from_date)) || isset($list_row->is_opening_balance)) {
                $gold_amount = (float) $list_row->gold_fine * (float) $gold_rate / 10;
                $silver_amount = (float) $list_row->silver_fine * (float) $silver_rate / 10;
                if($account_group_id_department == 1){ // Use only Cash Amount of Department
                    $tmp_net_amount = (float) $list_row->amount;
                } else {
                    $tmp_net_amount = (float) $gold_amount + (float) $silver_amount + (float) $list_row->amount;
                }
                $capital += $tmp_net_amount;
            }

            $outstanding_data_arr[$account_id]['account_name'] = '<a href="'.base_url().'reports_new_sp3/outstanding?account_id='.$list_row->account_id.'" target="_blank">'.$list_row->account_name.'</a>';
            $outstanding_data_arr[$account_id]['account_mobile'] = $list_row->account_mobile;

            if (in_array($account_id, $account_id_arr) && !empty($outstanding_data_arr[$account_id])) {

                if (strtotime($list_row->st_date) > strtotime($outstanding_data_arr[$account_id]['st_date'])) {
                    $outstanding_data_arr[$account_id]['st_date'] = (isset($list_row->st_date) && !empty($list_row->st_date)) ? date('d-m-Y', strtotime($list_row->st_date)) : '-';
                }

                $outstanding_data_arr[$account_id]['gold_fine'] = number_format((float) $outstanding_data_arr[$account_id]['gold_fine'], 3, '.', '') + number_format((float) $list_row->gold_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['silver_fine'] = number_format((float) $outstanding_data_arr[$account_id]['silver_fine'], 3, '.', '') + number_format((float) $list_row->silver_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['amount'] = number_format((float) $outstanding_data_arr[$account_id]['amount'], 2, '.', '') + number_format((float) $list_row->amount, 2, '.', '');

                $gold_amount = (float) $outstanding_data_arr[$account_id]['gold_fine'] * (float) $gold_rate / 10;
                $silver_amount = (float) $outstanding_data_arr[$account_id]['silver_fine'] * (float) $silver_rate / 10;
                if($account_group_id_department == 1){ // Use only Cash Amount of Department
                    $net_amount = (float) $outstanding_data_arr[$account_id]['amount'];
                } else {
                    $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_data_arr[$account_id]['amount'];
                }
                $outstanding_data_arr[$account_id]['net_amount'] = $net_amount;

                $outstanding_data_arr[$account_id]['account_id'] = $account_id;
                $outstanding_data_arr[$account_id]['account_group_id'] = $list_row->account_group_id;

            } else {
                $outstanding_data_arr[$account_id]['st_date'] = (isset($list_row->st_date) && !empty($list_row->st_date)) ? date('d-m-Y', strtotime($list_row->st_date)) : '-';
                $outstanding_data_arr[$account_id]['gold_fine'] = number_format((float) $list_row->gold_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['silver_fine'] = number_format((float) $list_row->silver_fine, 3, '.', '');
                $outstanding_data_arr[$account_id]['amount'] = number_format((float) $list_row->amount, 2, '.', '');

                $gold_amount = (float) $list_row->gold_fine * (float) $gold_rate / 10;
                $silver_amount = (float) $list_row->silver_fine * (float) $silver_rate / 10;
                if($account_group_id_department == 1){ // Use only Cash Amount of Department
                    $net_amount = (float) $list_row->amount;
                } else {
                    $net_amount = (float) $gold_amount + (float) $silver_amount + (float) $list_row->amount;
                }
                $outstanding_data_arr[$account_id]['net_amount'] = $net_amount;

                $outstanding_data_arr[$account_id]['account_id'] = $account_id;
                $outstanding_data_arr[$account_id]['account_group_id'] = $list_row->account_group_id;
                $account_id_arr[] = $account_id;
            }
        }

        $account_arr = array();
        $cr_account_arr = array();
        $dr_account_arr = array();

        $total_credit_amount = 0;
        $total_debit_amount = 0;

        foreach($outstanding_data_arr as $outstanding_row){
            
            if(isset($outstanding_row['account_id']) && !empty($outstanding_row['account_id'])){
                $credit_limit = $all_account_arr[$outstanding_row['account_id']];
                if($credit_limit == null || $credit_limit == ''){ $credit_limit = 0; }

                $tr_naam_data = $this->crud->get_transfer_naam_jama_for_balance_sheet_report($upto_balance_date, $outstanding_row['account_id'], 'TR Naam', $account_group_id);
                $tr_jama_data = $this->crud->get_transfer_naam_jama_for_balance_sheet_report($upto_balance_date, $outstanding_row['account_id'], 'TR Jama', $account_group_id);

                if(!empty($tr_naam_data)){
                    foreach ($tr_naam_data as $naam) {
                        if(isset($naam->st_date) && strtotime($naam->st_date) > 0 && strtotime($naam->st_date) < strtotime($from_date)) {
                            $tmp_gold_amount = (float) $naam->gold_fine * (float) $gold_rate / 10;
                            $tmp_silver_amount = (float) $naam->silver_fine * (float) $silver_rate / 10;
                            $tmp_net_amount = (float) $tmp_gold_amount + (float) $tmp_silver_amount + (float) $naam->amount;
                            $capital += $tmp_net_amount;
                        }

                        $outstanding_row['gold_fine'] = number_format((float) $outstanding_row['gold_fine'], 3, '.', '') + number_format((float) $naam->gold_fine, 3, '.', '');
                        $outstanding_row['silver_fine'] = number_format((float) $outstanding_row['silver_fine'], 3, '.', '') + number_format((float) $naam->silver_fine, 3, '.', '');
                        $outstanding_row['amount'] = number_format((float) $outstanding_row['amount'], 2, '.', '') + number_format((float) $naam->amount, 2, '.', '');
                        $gold_amount = (float) $outstanding_row['gold_fine'] * (float) $gold_rate / 10;
                        $silver_amount = (float) $outstanding_row['silver_fine'] * (float) $silver_rate / 10;
                        $outstanding_row['net_amount'] = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_row['amount'];
                    }
                }

                if(!empty($tr_jama_data)){
                    foreach ($tr_jama_data as $jama) {
                        if(isset($jama->st_date) && strtotime($jama->st_date) > 0 && strtotime($jama->st_date) < strtotime($from_date)) {
                            $tmp_gold_amount = (float) $jama->gold_fine * (float) $gold_rate / 10;
                            $tmp_silver_amount = (float) $jama->silver_fine * (float) $silver_rate / 10;
                            $tmp_net_amount = (float) $tmp_gold_amount + (float) $tmp_silver_amount + (float) $jama->amount;
                            $capital += $tmp_net_amount;
                        }

                        $outstanding_row['gold_fine'] = number_format((float) $outstanding_row['gold_fine'], 3, '.', '') + number_format((float) $jama->gold_fine, 3, '.', '');
                        $outstanding_row['silver_fine'] = number_format((float) $outstanding_row['silver_fine'], 3, '.', '') + number_format((float) $jama->silver_fine, 3, '.', '');
                        $outstanding_row['amount'] = number_format((float) $outstanding_row['amount'], 2, '.', '') + number_format((float) $jama->amount, 2, '.', '');
                        $gold_amount = (float) $outstanding_row['gold_fine'] * (float) $gold_rate / 10;
                        $silver_amount = (float) $outstanding_row['silver_fine'] * (float) $silver_rate / 10;
                        $outstanding_row['net_amount'] = (float) $gold_amount + (float) $silver_amount + (float) $outstanding_row['amount'];
                    }
                }

                if(!empty($outstanding_row['net_amount'])){

                    $account_arr_row = array();
                    $account_arr_row['account_group_id'] = $outstanding_row['account_group_id'];
                    $account_arr_row['account_name'] = $outstanding_row['account_name'];
                    $account_arr_row['net_amount'] = number_format((float) abs($outstanding_row['net_amount']), 2, '.', '');
                    $account_arr_row['actual_net_amount'] = $outstanding_row['net_amount'];


                    if($outstanding_row['net_amount'] >= 0) {
                        $total_debit_amount += $outstanding_row['net_amount'];
                        $account_arr_row['debit_or_credit'] = "debit";
                        $dr_account_arr[] = $account_arr_row;
                    } else {
                        $total_credit_amount += abs($outstanding_row['net_amount']);
                        $account_arr_row['debit_or_credit'] = "credit";
                        $cr_account_arr[] = $account_arr_row;
                    }
                    $account_arr[$outstanding_row['account_id']] = $account_arr_row; 
                }
            }
        }
        
        // Gold Sliver Stock Calculation Start
        $upto_to_date = date('Y-m-d', strtotime($upto_balance_date . ' +1 day'));
        $gold_sliver_stock_amounts = $this->stock_ledger_for_balance_sheet($upto_to_date, '');
        
        $gold_stock_arr = array();
        $gold_stock_arr['account_group_id'] = '-1';
        $gold_stock_arr['account_name'] = '<a href="'.base_url().'reports/stock_status" target="_blank">Gold Stock</a>';
        $gold_net_amount = $gold_sliver_stock_amounts['total_gold_fine'] * (float) $gold_rate / 10;
        $gold_stock_arr['net_amount'] = number_format((float) abs($gold_net_amount), 2, '.', '');
        $gold_stock_arr['actual_net_amount'] = $gold_net_amount;
        if($gold_net_amount >= 0) {
            $total_debit_amount += $gold_net_amount;
            $gold_stock_arr['debit_or_credit'] = "debit";
            $dr_account_arr[] = $gold_stock_arr;
        } else {
            $total_credit_amount += abs($gold_net_amount);
            $gold_stock_arr['debit_or_credit'] = "credit";
            $cr_account_arr[] = $gold_stock_arr;
        }
        $account_arr[] = $gold_stock_arr;
        
        $silver_stock_arr = array();
        $silver_stock_arr['account_group_id'] = '-2';
        $silver_stock_arr['account_name'] = '<a href="'.base_url().'reports/stock_status" target="_blank">Silver Stock</a>';
        $silver_net_amount = $gold_sliver_stock_amounts['total_silver_fine'] * (float) $silver_rate / 10;
        $silver_stock_arr['net_amount'] = number_format((float) abs($silver_net_amount), 2, '.', '');
        $silver_stock_arr['actual_net_amount'] = $silver_net_amount;
        if($silver_net_amount >= 0) {
            $total_debit_amount += $silver_net_amount;
            $silver_stock_arr['debit_or_credit'] = "debit";
            $dr_account_arr[] = $silver_stock_arr;
        } else {
            $total_credit_amount += abs($silver_net_amount);
            $silver_stock_arr['debit_or_credit'] = "credit";
            $cr_account_arr[] = $silver_stock_arr;
        }
        $account_arr[] = $silver_stock_arr;
        
        // Get up to from date Gold Sliver Stock and increase in Capital
        $past_gold_sliver_stock_amounts = $this->stock_ledger_for_balance_sheet($from_date, '');
        $gold_net_amount_for_capital = $past_gold_sliver_stock_amounts['total_gold_fine'] * (float) $gold_rate / 10;
        $capital += number_format((float) $gold_net_amount_for_capital, 2, '.', '');
        
        $silver_net_amount_for_capital = $past_gold_sliver_stock_amounts['total_silver_fine'] * (float) $silver_rate / 10;
        $capital += number_format((float) $silver_net_amount_for_capital, 2, '.', '');
        
//        $os_data = $this->crud->get_opening_stock_for_stock_ledger($department_id = '', $category_id = '', $item_id = '', $tunch = '', $account_id = '', 'Opening Stock');
//        $gold_sliver_stock_for_capital_amounts = $this->get_total_gold_fine_total_silver_fine($os_data);
//        
//        $gold_net_amount_for_capital = $gold_sliver_stock_amounts['total_gold_fine'] * (float) $gold_rate / 10;
//        $capital += number_format((float) $gold_net_amount_for_capital, 2, '.', '');
//        
//        $silver_net_amount_for_capital = $gold_sliver_stock_amounts['total_silver_fine'] * (float) $silver_rate / 10;
//        $capital += number_format((float) $silver_net_amount_for_capital, 2, '.', '');
        // Gold Sliver Stock Calculation End
        
        if($post_data['balance_sheet_format'] == "group") {
            
            /*--- Group Wise Data ---*/
            $account_groups_data = $this->crud->get_columns_val_by_where('account_group', 'account_group_id,account_group_name', array());
            $account_groups_name_arr = array();
            foreach ($account_groups_data as $key => $account_groups_row) {
                $account_groups_name_arr[$account_groups_row['account_group_id']] = '<a href="'.base_url().'reports_new_sp3/outstanding?account_group_id='.$account_groups_row['account_group_id'].'" target="_blank">'.$account_groups_row['account_group_name'].'</a>';
            }


            $account_group_arr = array();
            
            foreach ($account_arr as $key => $account_arr_row) {

                if(isset($account_group_arr[$account_arr_row['account_group_id']])) {
                    $account_group_arr[$account_arr_row['account_group_id']]['net_amount'] += $account_arr_row['actual_net_amount'];
                    $account_group_arr[$account_arr_row['account_group_id']]['accounts'][] = $account_arr_row;
                } else {
                    if(isset($account_groups_name_arr[$account_arr_row['account_group_id']])){
                        $account_group_arr[$account_arr_row['account_group_id']] = array(
                            'account_group_name' => $account_groups_name_arr[$account_arr_row['account_group_id']],
                            'net_amount' => 0,
                            'accounts' => array(),
                        );
                    } else {
                        $account_group_arr[$account_arr_row['account_group_id']] = array(
                            'account_group_name' => $account_arr_row['account_name'],
                            'net_amount' => 0,
                            'accounts' => array(),
                        );
                    }

                    $account_group_arr[$account_arr_row['account_group_id']]['net_amount'] += $account_arr_row['actual_net_amount'];
                    $account_group_arr[$account_arr_row['account_group_id']]['accounts'][] = $account_arr_row;
                }
            }
            $cr_account_group_arr = array();
            $dr_account_group_arr = array();
            $total_debit_amount = 0;
            $total_credit_amount = 0;

            foreach ($account_group_arr as $key => $account_group_row) {
                $tmp_account_group_row = array();
                $tmp_account_group_row['account_group_name'] = $account_group_row['account_group_name'];
                $tmp_account_group_row['accounts'] = $account_group_row['accounts'];
                $tmp_account_group_row['net_amount'] = number_format((float) abs($account_group_row['net_amount']), 2, '.', '');

                if($account_group_row['net_amount'] >= 0) {
                    $total_debit_amount += $account_group_row['net_amount'];
                    $tmp_account_group_row['debit_or_credit'] = "debit";
                    $dr_account_group_arr[] = $tmp_account_group_row;
                } else {
                    $total_credit_amount += abs($account_group_row['net_amount']);
                    $tmp_account_group_row['debit_or_credit'] = "credit";
                    $cr_account_group_arr[] = $tmp_account_group_row;
                }
            }
            /*echo "<pre>";
            print_r($account_group_arr);
            die();*/
            /*--- Group Wise Data ---*/

            if($capital >= 0) {
                $total_credit_amount += $capital;
                $cr_account_group_arr[] = array(
                    "account_group_name" => "CAPITAL",
                    "net_amount" => number_format((float) abs($capital), 2, '.', '')
                );
            } else {
                $total_credit_amount += $capital;
                $cr_account_group_arr[] = array(
                    "account_group_name" => "CAPITAL",
                    "net_amount" => number_format((float) $capital, 2, '.', '')
                );
            }

            $data = array();

            /*---- Find Net Profit/Loss ----*/
            if($total_debit_amount != $total_credit_amount) {
                if($total_debit_amount > $total_credit_amount) { //Profit

                    $total_net_amount = $total_debit_amount;
                    $net_profit = $total_debit_amount - $total_credit_amount;

                    $data[] = array(
                        'Net Profit',
                        number_format((float) $net_profit, 2, '.', ''),
                        '',
                        '',
                        ''
                    );
                } else {

                    $total_net_amount = $total_credit_amount;
                    $net_loss = $total_credit_amount - $total_debit_amount;

                    $data[] = array(
                        '',
                        '',
                        '',
                        'Net Loss',
                        number_format((float) $net_loss, 2, '.', ''),
                    );
                }
            } else {
                $total_net_amount = $total_credit_amount;
            }
            /*---- Find Net Profit/Loss ----*/

            $for_loop_limit = (count($dr_account_group_arr) > count($cr_account_group_arr) ?count($dr_account_group_arr):count($cr_account_group_arr));

            for ($i=0; $i < $for_loop_limit; $i++) { 
                $row = array();
                
                if(isset($cr_account_group_arr[$i]['account_group_name'])) {
                    $row[] = $cr_account_group_arr[$i]['account_group_name'];
                    $row[] = $cr_account_group_arr[$i]['net_amount'];
                } else {
                    $row[] = '';
                    $row[] = '';
                }
                
                $row[] = '';

                if(isset($dr_account_group_arr[$i]['account_group_name'])) {
                    $row[] = $dr_account_group_arr[$i]['account_group_name'];
                    $row[] = $dr_account_group_arr[$i]['net_amount'];
                } else {
                    $row[] = '';
                    $row[] = '';
                }

                $data[] = $row;
            }

            $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

            $output = array(
                "draw" => $_POST['draw'],
                "capital" => $capital,
                "total_net_amount" => $total_net_amount,
                "total_debit_amount" => $total_debit_amount,
                "total_credit_amount" => $total_credit_amount,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data,
                "issue_rows" => $issue_rows
            );
            echo json_encode($output);

        } else {

            if($capital >= 0) {
                $total_credit_amount += $capital;
                $cr_account_arr[] = array(
                    "account_name" => "CAPITAL",
                    "net_amount" => number_format((float) abs($capital), 2, '.', '')
                );
            } else {
                $total_credit_amount += $capital;
                $cr_account_arr[] = array(
                    "account_name" => "CAPITAL",
                    "net_amount" => number_format((float) $capital, 2, '.', '')
                );
            }

            $data = array();

            /*---- Find Net Profit/Loss ----*/
            if($total_debit_amount != $total_credit_amount) {
                if($total_debit_amount > $total_credit_amount) { //Profit

                    $total_net_amount = $total_debit_amount;
                    $net_profit = $total_debit_amount - $total_credit_amount;

                    $data[] = array(
                        'Net Profit',
                        number_format((float) $net_profit, 2, '.', ''),
                        '',
                        '',
                        ''
                    );
                } else {

                    $total_net_amount = $total_credit_amount;
                    $net_loss = $total_credit_amount - $total_debit_amount;

                    $data[] = array(
                        '',
                        '',
                        '',
                        'Net Loss',
                        number_format((float) $net_loss, 2, '.', ''),
                    );
                }
            } else {
                $total_net_amount = $total_credit_amount;
            }
            /*---- Find Net Profit/Loss ----*/

            $for_loop_limit = (count($dr_account_arr) > count($cr_account_arr) ?count($dr_account_arr):count($cr_account_arr));

            for ($i=0; $i < $for_loop_limit; $i++) { 
                $row = array();
                
                if(isset($cr_account_arr[$i]['account_name'])) {
                    $row[] = $cr_account_arr[$i]['account_name'];
                    $row[] = $cr_account_arr[$i]['net_amount'];
                } else {
                    $row[] = '';
                    $row[] = '';
                }
                
                $row[] = '';

                if(isset($dr_account_arr[$i]['account_name'])) {
                    $row[] = $dr_account_arr[$i]['account_name'];
                    $row[] = $dr_account_arr[$i]['net_amount'];
                } else {
                    $row[] = '';
                    $row[] = '';
                }

                $data[] = $row;
            }

            $total_net_amount = number_format((float) $total_net_amount, 2, '.', '');

            $output = array(
                "draw" => $_POST['draw'],
                "capital" => $capital,
                "total_net_amount" => $total_net_amount,
                "total_debit_amount" => $total_debit_amount,
                "total_credit_amount" => $total_credit_amount,
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data,
                "issue_rows" => $issue_rows
            );
            echo json_encode($output);

        }
    }
    
    function stock_ledger_for_balance_sheet($from_date, $to_date) {
        $department_id = '';
        $category_id = '';
        $item_id = '';
        $tunch = '';
        $account_id = '';
        $os_data = array();
        $os_data = $this->crud->get_opening_stock_for_stock_ledger($department_id, $category_id, $item_id, $tunch, $account_id, 'Opening Stock');
        $p_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'P');
        $s_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'S');
        $e_data = $this->crud->get_sell_items_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'E');
        $m_r_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'M R');
        $m_p_data = $this->crud->get_metal_payment_receipt_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'M P');
        $f_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'F T');
        $t_t_data = $this->crud->get_stock_transfer_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'T T');
        $mfi_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFI');
        $mfr_data = $this->crud->get_manufacture_issue_receive_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFR');
        $mfis_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFIS');
        $mfrs_data = $this->crud->get_manufacture_issue_receive_silver_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MFRS');
        $mhmifw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMIFW');
        $mhmis_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMIS');
        $mhmrfw_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMRFW');
        $mhmrs_data = $this->crud->get_manufacture_manu_hand_made_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MHMRS');
        $castingifw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGIFW');
        $castingis_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGIS');
        $castingrfw_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGRFW');
        $castingrs_data = $this->crud->get_manufacture_casting_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'CASTINGRS');
        $mchainifw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINIFW');
        $mchainis_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINIS');
        $mchainrfw_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINRFW');
        $mchainrs_data = $this->crud->get_manufacture_machin_chain_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'MCHAINRS');
        $o_p_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'O P');
        $o_s_data = $this->crud->get_other_sell_item_for_stock_ledger($from_date, $to_date, $department_id, $category_id, $item_id, $tunch, $account_id, 'O S');
        $stock_ledger_data = array_merge($os_data, $p_data, $s_data, $e_data, $m_r_data, $m_p_data, $f_t_data, $t_t_data, $mfi_data, $mfr_data, $mfis_data, $mfrs_data, $mhmifw_data, $mhmis_data, $mhmrfw_data, $mhmrs_data, $castingifw_data, $castingis_data, $castingrfw_data, $castingrs_data, $mchainifw_data, $mchainis_data, $mchainrfw_data, $mchainrs_data, $o_p_data, $o_s_data);
        
        $data = $this->get_total_gold_fine_total_silver_fine($stock_ledger_data);
        return $data;
        exit;
    }
    
    function get_total_gold_fine_total_silver_fine($stock_ledger_data) {
        $category_group_gold_item_ids = $this->crud->get_item_ids_by_category_group(CATEGORY_GROUP_GOLD_ID);
        $category_group_gold_item_ids = array_column($category_group_gold_item_ids, 'item_id');
        $category_group_silver_item_ids = $this->crud->get_item_ids_by_category_group(CATEGORY_GROUP_SILVER_ID);
        $category_group_silver_item_ids = array_column($category_group_silver_item_ids, 'item_id');
        
        $data = array();
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $zero_value = 0;
        foreach ($stock_ledger_data as $key => $stock_ledger) {
            
            $grwt = number_format($stock_ledger->grwt, 3, '.', '');
            if(!empty($grwt)){
                $less = (is_numeric((float) $stock_ledger->less)) ? number_format((float) $stock_ledger->less, 3, '.', '') : 0;
                $net_wt = (!is_numeric((float) $stock_ledger->net_wt)) ? (float) $grwt - (float) $less : (float) $stock_ledger->net_wt;
                $net_wt = number_format((float) $net_wt, 3, '.', '');
                $touch_id = $stock_ledger->touch_id;
                $fine = number_format((float) $stock_ledger->fine, 3, '.', '');
                if($stock_ledger->type_sort == 'S' || $stock_ledger->type_sort == 'M P' || $stock_ledger->type_sort == 'F T' || $stock_ledger->type_sort == 'MFI' || $stock_ledger->type_sort == 'MFIS' || $stock_ledger->type_sort == 'MHMIFW' || $stock_ledger->type_sort == 'MHMIS' || $stock_ledger->type_sort == 'CASTINGIFW' || $stock_ledger->type_sort == 'CASTINGIS' || $stock_ledger->type_sort == 'MCHAINIFW' || $stock_ledger->type_sort == 'MCHAINIS' || $stock_ledger->type_sort == 'O S'){
                    $grwt = $zero_value - (float) $grwt;
                    $grwt = number_format((float) $grwt, 3, '.', '');
                    $less = (!empty($less)) ? $zero_value - (float) $less : 0;
                    $less = number_format((float) $less, 3, '.', '');
                    $net_wt = $zero_value - (float) $net_wt;
                    $net_wt = number_format((float) $net_wt, 3, '.', '');
                    $fine = $zero_value - (float) $fine;
                    $fine = number_format((float) $fine, 3, '.', '');
                }
                if(!empty($stock_ledger->item_id)){
                    if(in_array($stock_ledger->item_id, $category_group_gold_item_ids)){
                        $without_wastage_fine = (float) $net_wt * (float) $touch_id / 100;
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $without_wastage_fine, '3', '.', '');
                    } else if(in_array($stock_ledger->item_id, $category_group_silver_item_ids)){
                        $without_wastage_fine = (float) $net_wt * (float) $touch_id / 100;
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $without_wastage_fine, '3', '.', '');
                    }
                }
            }
        }
        $data['total_gold_fine'] = $total_gold_fine;
        $data['total_silver_fine'] = $total_silver_fine;
        return $data;
        exit;
    }
    
    function trading_pl(){
        $data = array();
        if ($this->applib->have_access_role(TRADING_PL_MODULE_ID, "view")) {
            set_page('reports/trading_pl', $data);
        } else {
            redirect("/");
        }
    }
    
    function trading_pl_datatable(){
        $post_data = $this->input->post();
        $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        
        $pre_sell_amt = 0;
        $pre_purchase_ex_amt = 0;
        $pre_metal_pay_amt = 0;
        $pre_metal_re_amt = 0;
        
        $gold_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $silver_rate = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
        
        $sell_pre_data = $this->crud->getFromSQL("SELECT si.*,c.category_group_id FROM sell as s LEFT JOIN sell_items as si ON si.sell_id = s.sell_id LEFT JOIN category as c ON c.category_id = si.category_id WHERE s.sell_date < '".$from_date."' ");
        if(!empty($sell_pre_data)){
            foreach ($sell_pre_data as $sell){
                if($sell->type == 1){
                    if($sell->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $pre_sell_amt = $pre_sell_amt + ($sell->fine * $gold_rate / 10);
                    } else if($sell->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $pre_sell_amt = $pre_sell_amt + ($sell->fine * $silver_rate / 10);
                    }
                } else if($sell->type == 2 || $sell->type == 3){
                    if($sell->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $pre_purchase_ex_amt = $pre_purchase_ex_amt + ($sell->fine * $gold_rate / 10);
                    } else if($sell->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $pre_purchase_ex_amt = $pre_purchase_ex_amt + ($sell->fine * $silver_rate / 10);
                    }
                }
            }
        }
        
        $metal_pre_data = $this->crud->getFromSQL("SELECT si.*,c.category_group_id FROM sell as s LEFT JOIN metal_payment_receipt as si ON si.sell_id = s.sell_id LEFT JOIN category as c ON c.category_id = si.metal_category_id WHERE s.sell_date < '".$from_date."' ");
        if(!empty($metal_pre_data)){
            foreach ($metal_pre_data as $metal){
                if($metal->metal_payment_receipt == 1){
                    if($metal->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $pre_metal_pay_amt = $pre_metal_pay_amt + ($metal->metal_fine * $gold_rate / 10);
                    } else if($metal->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $pre_metal_pay_amt = $pre_metal_pay_amt + ($metal->metal_fine * $silver_rate / 10);
                    }
                } else if($metal->metal_payment_receipt == 2){
                    if($metal->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $pre_metal_re_amt = $pre_metal_re_amt + ($metal->metal_fine * $gold_rate / 10);
                    } else if($metal->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $pre_metal_re_amt = $pre_metal_re_amt + ($metal->metal_fine * $silver_rate / 10);
                    }
                }
            }
        }
        $op_stock = $pre_purchase_ex_amt + $pre_metal_re_amt - $pre_sell_amt - $pre_metal_pay_amt;
        $sell_amt = 0;
        $purchase_ex_amt = 0;
        $metal_re_amt = 0;
        $metal_pay_amt = 0;
        $closing_stock = 0;
        $total_debit = 0;
        $total_credit = 0;
        $gross_profit = 0;
        $gross_loss = 0;
        $total_debit_2 = 0;
        $total_credit_2 = 0;
        $expens = 0;
        $net_profit = 0;
        $net_loss = 0;
        $trading_data = array();
        
        //gold amt = fine * gold rate / 10; //
        $sell_data = $this->crud->getFromSQL("SELECT si.*,c.category_group_id FROM sell as s LEFT JOIN sell_items as si ON si.sell_id = s.sell_id LEFT JOIN category as c ON c.category_id = si.category_id WHERE s.sell_date >= '".$from_date."' AND s.sell_date <= '".$to_date."' ");
        if(!empty($sell_data)){
            foreach ($sell_data as $sell){
                if($sell->type == 1){
                    if($sell->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $sell_amt = $sell_amt + ($sell->fine * $gold_rate / 10);
                    } else if($sell->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $sell_amt = $sell_amt + ($sell->fine * $silver_rate / 10);
                    }
                } else if($sell->type == 2 || $sell->type == 3){
                    if($sell->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $purchase_ex_amt = $purchase_ex_amt + ($sell->fine * $gold_rate / 10);
                    } else if($sell->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $purchase_ex_amt = $purchase_ex_amt + ($sell->fine * $silver_rate / 10);
                    }
                }
            }
        }
        
        $metal_data = $this->crud->getFromSQL("SELECT si.*,c.category_group_id FROM sell as s LEFT JOIN metal_payment_receipt as si ON si.sell_id = s.sell_id LEFT JOIN category as c ON c.category_id = si.metal_category_id WHERE s.sell_date >= '".$from_date."' AND s.sell_date <= '".$to_date."' ");
        if(!empty($metal_data)){
            foreach ($metal_data as $metal){
                if($metal->metal_payment_receipt == 1){
                    if($metal->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $metal_pay_amt = $metal_pay_amt + ($metal->metal_fine * $gold_rate / 10);
                    } else if($metal->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $metal_pay_amt = $metal_pay_amt + ($metal->metal_fine * $silver_rate / 10);
                    }
                } else if($metal->metal_payment_receipt == 2){
                    if($metal->category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $metal_re_amt = $metal_re_amt + ($metal->metal_fine * $gold_rate / 10);
                    } else if($metal->category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $metal_re_amt = $metal_re_amt + ($metal->metal_fine * $silver_rate / 10);
                    }
                }
            }
        }
        if($op_stock >= 0){
            $closing_stock = $op_stock + $purchase_ex_amt + $metal_re_amt - $sell_amt - $metal_pay_amt;
        } else {
            $closing_stock = $purchase_ex_amt + $metal_re_amt - abs($op_stock) - $sell_amt - $metal_pay_amt;
        }
//        echo "<pre>"; print_r($closing_stock); exit;
        $data = array();
        
        $row = array();
        $row[] = $op_stock >= 0 ? 'Opening Stock' : '';
        $row[] = $op_stock >= 0 ? number_format($op_stock, 2, '.', '') : '';
        $row[] = $op_stock < 0 ? 'Opening Stock' : '';
        $row[] = $op_stock < 0 ? number_format(abs($op_stock), 2, '.', '') : '';
        $data[] = $row;
        
        $row = array();
        $row[] = 'Exchange / Purchase';
        $row[] = number_format($purchase_ex_amt, 2, '.', '');
        $row[] = 'Sell';
        $row[] = number_format($sell_amt, 2, '.', '');
        $data[] = $row;
        
        $row = array();
        $row[] = 'Metal Receive';
        $row[] = number_format($metal_re_amt, 2, '.', '');
        $row[] = 'Metal Issue';
        $row[] = number_format($metal_pay_amt, 2, '.', '');
        $data[] = $row;
        
        $row = array();
        $row[] = $closing_stock >= 0 ? 'Closing Stock' : '';
        $row[] = $closing_stock >= 0 ? number_format($closing_stock, 2, '.', '') : '';
        $row[] = $closing_stock < 0 ? 'Closing Stock' : '';
        $row[] = $closing_stock < 0 ? number_format(abs($closing_stock), 2, '.', '') : '';
        $data[] = $row;
        
        $total_debit = $purchase_ex_amt + $metal_re_amt;
        $total_credit = $sell_amt + $metal_pay_amt;
        $row = array();
        $row[] = 'Date Range Total';
        $row[] = number_format($total_debit, 2, '.', '');
        $row[] = 'Date Range Total';
        $row[] = number_format($total_credit, 2, '.', '');
        $data[] = $row;
        
        $gross_profit_loss = $total_credit - $total_debit;
        $row = array();
        $row[] = $gross_profit_loss >= 0 ? 'Gross Profit' : '';
        $row[] = $gross_profit_loss >= 0 ? number_format($gross_profit_loss, 2, '.', '') : '';
        $row[] = $gross_profit_loss < 0 ? 'Gross Loss' : '';
        $row[] = $gross_profit_loss < 0 ? number_format(abs($gross_profit_loss), 2, '.', '') : '';
        $data[] = $row;
        
        $plus_op = $op_stock >= 0 ? number_format($op_stock, 2, '.', '') : 0;
        $plus_cl = $closing_stock >= 0 ? number_format($closing_stock, 2, '.', '') : 0;
        $minus_op = $op_stock < 0 ? number_format($op_stock, 2, '.', '') : 0;
        $minus_cl = $closing_stock < 0 ? number_format($closing_stock, 2, '.', '') : 0;
        $total_debit_2 = $plus_op + $plus_cl + $total_debit;
        $total_credit_2 = abs($minus_op) + abs($minus_cl) + abs($total_credit);
        $row = array();
        $row[] = 'Total';
        $row[] = number_format($total_debit_2, 2, '.', '');
        $row[] = 'Total';
        $row[] = number_format($total_credit_2, 2, '.', '');
        $data[] = $row;
        
        $row = array();
        $row[] = 'Expenses';
        $row[] = number_format($expens, 2, '.', '');
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        $row = array();
        $row[] = '&nbsp;';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        $net_profit_loss = $gross_profit_loss - $expens;
        $row = array();
        $row[] = $net_profit_loss >= 0 ? 'Net Profit' : '';
        $row[] = $net_profit_loss >= 0 ? number_format($net_profit_loss, 2, '.', '') : '';
        $row[] = $net_profit_loss < 0 ? 'Net Loss' : '';
        $row[] = $net_profit_loss < 0 ? number_format(abs($net_profit_loss), 2, '.', '') : '';
        $data[] = $row;
        
        $row = array();
        $row[] = '&nbsp;';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $data[] = $row;
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => '',
            "recordsFiltered" => '',
            "data" => $data,
        );
        echo json_encode($output);
        
    }
    
    function create_rfid() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $post_data['rfid_grwt'] = isset($post_data['rfid_grwt']) && !empty($post_data['rfid_grwt']) ? $post_data['rfid_grwt'] : 0;
        $post_data['rfid_less'] = isset($post_data['rfid_less']) && !empty($post_data['rfid_less']) ? $post_data['rfid_less'] : 0;
        $post_data['rfid_add'] = isset($post_data['rfid_add']) && !empty($post_data['rfid_add']) ? $post_data['rfid_add'] : 0;
        $post_data['rfid_ntwt'] = $post_data['rfid_grwt'] - $post_data['rfid_less'] + $post_data['rfid_add'];
        $post_data['rfid_tunch'] = isset($post_data['rfid_tunch']) && !empty($post_data['rfid_tunch']) ? $post_data['rfid_tunch'] : 0;
        $post_data['rfid_fine'] = !empty($post_data['rfid_ntwt']) ? ($post_data['rfid_ntwt'] * $post_data['rfid_tunch']) / 100 : 0;
        $post_data['rfid_fine'] = number_format((float) $post_data['rfid_fine'], 3, '.', '');
        $post_data['rfid_size'] = isset($post_data['rfid_size']) && !empty($post_data['rfid_size']) ? $post_data['rfid_size'] : NULL;
        $post_data['rfid_charges'] = isset($post_data['rfid_charges']) && !empty($post_data['rfid_charges']) ? $post_data['rfid_charges'] : 0;
        $post_data['rfid_ad_id'] = isset($post_data['rfid_ad_id']) && !empty($post_data['rfid_ad_id']) ? $post_data['rfid_ad_id'] : NULL;
        
        // Update RFID Created grwt
        $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $post_data['rfid_item_stock_id']));
        if(isset($post_data['item_stock_rfid_id']) && !empty($post_data['item_stock_rfid_id'])){
            $old_rfid_grwt = $this->crud->get_column_value_by_id('item_stock_rfid', 'rfid_grwt', array('item_stock_rfid_id' => $post_data['item_stock_rfid_id']));
            $new_rfid_created_grwt = $old_rfid_created_grwt - $old_rfid_grwt + $post_data['rfid_grwt'];
        } else {
            $new_rfid_created_grwt = $old_rfid_created_grwt + $post_data['rfid_grwt'];
        }
        
        $exist_where_array = array('real_rfid' => $post_data['real_rfid']);
        if(isset($post_data['item_stock_rfid_id']) && !empty($post_data['item_stock_rfid_id'])){
            $exist_where_array['item_stock_rfid_id !='] = $post_data['item_stock_rfid_id'];
        }
        $exist_where_array['rfid_used'] = '0';
        $exist_item_stock_rfid = $this->crud->get_row_by_id('item_stock_rfid', $exist_where_array);
        if(!empty($exist_item_stock_rfid)){
            $return['error'] = "Exist";
        } else {
            $insert_arr = array();
            $insert_arr['item_stock_id'] = $post_data['rfid_item_stock_id'];
            $insert_arr['rfid_grwt'] = $post_data['rfid_grwt'];
            $insert_arr['rfid_less'] = $post_data['rfid_less'];
            $insert_arr['rfid_add'] = $post_data['rfid_add'];
            $insert_arr['rfid_ntwt'] = $post_data['rfid_ntwt'];
            $insert_arr['rfid_tunch'] = $post_data['rfid_tunch'];
            $insert_arr['rfid_fine'] = $post_data['rfid_fine'];
            $insert_arr['real_rfid'] = $post_data['real_rfid'];
            $insert_arr['rfid_size'] = $post_data['rfid_size'];
            $insert_arr['rfid_charges'] = $post_data['rfid_charges'];
            $insert_arr['rfid_ad_id'] = $post_data['rfid_ad_id'];
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            if(isset($post_data['item_stock_rfid_id']) && !empty($post_data['item_stock_rfid_id'])){
                $this->db->where('item_stock_rfid_id', $post_data['item_stock_rfid_id']);
                $result = $this->db->update('item_stock_rfid', $insert_arr);
                $item_stock_rfid_id = $post_data['item_stock_rfid_id'];
            } else {
                $insert_arr['created_at'] = $this->now_time;
                $insert_arr['created_by'] = $this->logged_in_id;
                $result = $this->crud->insert('item_stock_rfid', $insert_arr);
                $item_stock_rfid_id = $this->db->insert_id();
            }
            if ($result) {
                $return['success'] = "Added";
                $return['item_stock_rfid_id'] = $item_stock_rfid_id;
                
                // Update RFID Created grwt
                $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $post_data['rfid_item_stock_id']));
                
                // Update RFID from relation_id and module
                $this->crud->update('item_stock_rfid', array('from_relation_id' => $item_stock_rfid_id, 'from_module' => RFID_RELATION_MODULE_RFID_CREATE), array('item_stock_rfid_id' => $item_stock_rfid_id));
            }
        }
        print json_encode($return);
        exit;
    }
    
    function get_created_rfid_data($item_stock_rfid_id = '') {
        $data = array();
        if(!empty($item_stock_rfid_id)){
            $data['item_stock_rfid'] = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $item_stock_rfid_id);
        }
        print json_encode($data);
        exit;
    }
    
    function get_created_rfid_list() {
        $post_data = $this->input->post();
        $config['table'] = 'item_stock_rfid isr';
        $config['select'] = 'isr.*, is.tunch, ad.ad_name';
        $config['joins'][] = array('join_table' => 'item_stock is', 'join_by' => 'is.item_stock_id = isr.item_stock_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'ad', 'join_by' => 'ad.ad_id = isr.rfid_ad_id', 'join_type' => 'left');
        $config['column_search'] = array('isr.real_rfid', 'isr.rfid_grwt', 'isr.rfid_less', 'isr.rfid_add', 'isr.rfid_charges', 'ad.ad_name');
        $config['column_order'] = array(null, null, 'isr.real_rfid', 'isr.rfid_grwt', 'isr.rfid_less', 'isr.rfid_add', 'isr.rfid_charges', 'ad.ad_name');
        if (isset($post_data['rfid_item_stock_id']) && !empty($post_data['rfid_item_stock_id'])) {
            $config['wheres'][] = array('column_name' => 'isr.item_stock_id', 'column_value' => $post_data['rfid_item_stock_id']);
        } else {
            $config['wheres'][] = array('column_name' => 'isr.item_stock_id', 'column_value' => '-1');
        }
        $config['wheres'][] = array('column_name' => 'isr.rfid_used', 'column_value' => '0');
        $config['order'] = array('isr.item_stock_rfid_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $created_rfid_list = $this->datatable->get_datatables();
//        echo '<pre>'.$this->db->last_query(); exit;
        $data = array();
        $total_rfid_grwt = 0;
        $total_rfid_less = 0;
        $total_rfid_add = 0;
        $total_rfid_ntwt = 0;
        $total_rfid_fine = 0;
        $total_rfid_charges = 0;
        $rfid_pcs = 1;
        foreach ($created_rfid_list as $created_rfid) {
            $rfid_action_btn = '';
            if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "rfid_edit")) {
                $rfid_action_btn .=  '<a href="javascript:void(0);" class="edit_rfid" data-item_stock_rfid_id="' . $created_rfid->item_stock_rfid_id . '" ><i class="glyphicon glyphicon-edit"></i></a>';
            }
            $rfid_action_btn .=  ' &nbsp; <a href="'.base_url('reports/print_item_rfid/'.$created_rfid->item_stock_rfid_id).'" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-print"></i></a>';
            if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "rfid_delete")) {
                $rfid_action_btn .=  ' &nbsp; <a href="javascript:void(0);" class="delete_rfid" data-href="' . base_url('reports/delete_rfid/' . $created_rfid->item_stock_rfid_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row = array();
            $row[] = $rfid_action_btn;
            $row[] = $rfid_pcs;
            $row[] = $created_rfid->item_stock_rfid_id;
            $row[] = $created_rfid->real_rfid;
            $row[] = $created_rfid->rfid_grwt;
            $row[] = $created_rfid->rfid_less;
            $row[] = $created_rfid->rfid_add;
            $row[] = $created_rfid->rfid_ntwt;
            $row[] = $created_rfid->rfid_fine;
            $row[] = $created_rfid->rfid_charges;
            $row[] = $created_rfid->ad_name;
            $data[] = $row;
            
            $total_rfid_grwt = $total_rfid_grwt + $created_rfid->rfid_grwt;
            $total_rfid_less = $total_rfid_less + $created_rfid->rfid_less;
            $total_rfid_add = $total_rfid_add + $created_rfid->rfid_add;
            $total_rfid_ntwt = $total_rfid_ntwt + $created_rfid->rfid_ntwt;
            $total_rfid_fine = $total_rfid_fine + $created_rfid->rfid_fine;
            $total_rfid_charges = $total_rfid_charges + $created_rfid->rfid_charges;
            $rfid_pcs++;
        }
        $row = array();
        $row[] = '<b>Total<b>';
        $row[] = '';
        $row[] = '';
        $row[] = '';
        $row[] = '<b>'. number_format((float) $total_rfid_grwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $total_rfid_less, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $total_rfid_add, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $total_rfid_ntwt, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $total_rfid_fine, 3, '.', '') .'</b>';
        $row[] = '<b>'. number_format((float) $total_rfid_charges, 2, '.', '') .'</b>';
        $row[] = '';
        $data[] = $row;
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($created_rfid_list),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
            "rfid_stock" => number_format((float) $total_rfid_grwt, 3, '.', ''),
            "rfid_pcs" => $rfid_pcs - 1,
        );
        echo json_encode($output);
    }
    
    function print_item_rfid($item_stock_rfid_id = ''){
        if(!empty($item_stock_rfid_id)){
            $data = array();
            $data['item_stock_rfid'] = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $item_stock_rfid_id);
            $data['use_barcode'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'use_barcode'));
            $this->load->library('m_pdf');
            $pdf = new mPDF('utf-8', array(60,12));
            $pdf->AddPage(
                'P', //orientation
                '', //type
                '', //resetpagenum
                '', //pagenumstyle
                '', //suppress
                '1px', //margin-left
                '1px', //margin-right
                '1px', //margin-top
                '1px', //margin-bottom
                0, //margin-header
                0 //margin-footer
            );
//            print_r($data); exit;
            $html = $this->load->view('reports/print_item_rfid', $data, true);
            $pdf->WriteHTML($html);
            $pdfFilePath = "RFID.pdf";
            $pdf->Output($pdfFilePath, "I");
        }
    }
    
    function delete_rfid($item_stock_rfid_id = '') {
        $return = array();
        
        // Update RFID Created grwt
        if(isset($item_stock_rfid_id) && !empty($item_stock_rfid_id)){
            $item_stock_rfid = $this->crud->get_data_row_by_id('item_stock_rfid', 'item_stock_rfid_id', $item_stock_rfid_id);
            $old_rfid_created_grwt = $this->crud->get_column_value_by_id('item_stock', 'rfid_created_grwt', array('item_stock_id' => $item_stock_rfid->item_stock_id));
            $new_rfid_created_grwt = $old_rfid_created_grwt - $item_stock_rfid->rfid_grwt;
            $this->crud->update('item_stock', array('rfid_created_grwt' => $new_rfid_created_grwt), array('item_stock_id' => $item_stock_rfid->item_stock_id));
        }
        $where_array = array('item_stock_rfid_id' => $item_stock_rfid_id);
        $this->crud->delete('item_stock_rfid', $where_array);
        $return['success'] = 'Deleted';
        echo json_encode($return);
        exit;
    }
    
    function log(){
        $data = array();
        if ($this->applib->have_access_role(TRADING_PL_MODULE_ID, "view")) {
            set_page('reports/log', $data);
        } else {
            redirect("/");
        }
    }
    
    function cash_receipt_log(){
        $data = array();
        if ($this->applib->have_access_role(TRADING_PL_MODULE_ID, "view")) {
            set_page('reports/cash_receipt_log', $data);
        } else {
            redirect("/");
        }
    }
    
    function cash_receipt_log_datatable() {
        
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['database'] = 'gurulog';
        $config['table'] = 'payment_receipt_log pr';
        $config['select'] = 'pr.*, d.account_name as department, a.account_name, s.sell_no, o.other_no';
        $config['joins'][] = array('join_table' => 'account_log d', 'join_by' => 'd.account_id = pr.department_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account_log a', 'join_by' => 'a.account_id = pr.account_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'sell_log s', 'join_by' => 's.sell_id = pr.sell_id ', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'other_log o', 'join_by' => 'o.other_id = pr.other_id ', 'join_type' => 'left');
//        $config['column_search'] = array('a.account_name', 'd.account_name', 'pr.amount', 'pr.voucher_no', 'pr.narration');
//        $config['column_order'] = array(null, 'a.account_name', 'd.account_name', 'pr.amount', 'pr.voucher_no', 'pr.narration');
        $config['order'] = array('pr.pay_rec_id' => 'desc');
        $config['wheres'][] = array('column_name' => 'pr.cash_cheque', 'column_value' => '1');
        $config['wheres'][] = array('column_name' => 'pr.sell_id', 'column_value' => '1040');
        $config['custom_where'] = ' 1 ';
//        $department_ids = $this->applib->current_user_department_ids();
//        if(!empty($department_ids)){
//            $department_ids = implode(',', $department_ids);
//            $config['custom_where'] .= ' AND pr.department_id IN('.$department_ids.')';
//        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['from_date'])) {
                $config['wheres'][] = array('column_name' => 'pr.transaction_date >=', 'column_value' => $from_date);
            }
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 'pr.transaction_date <=', 'column_value' => $to_date);
        }
        $config['custom_where'] .= ' AND pr.payment_receipt = '.$post_data['payment_receipt'].' AND a.account_group_id != '. DEPARTMENT_GROUP .' ';
        if (!empty($post_data['account_filter'])) {
            $config['wheres'][] = array('column_name' => 'a.account_id', 'column_value' => $post_data['account_filter']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        $this->db = $this->load->database('gurulog',true);
        echo '<pre>'.$this->db->last_query(); exit;
        echo '<pre>'; print_r($list); exit;
        $data = array();

        foreach ($list as $cashbook) {
            $row = array();
            $action = '';
            $row[] = $action;
            $row[] = $cashbook->account_name;
            $row[] = $cashbook->department;
            $row[] = $cashbook->amount;
            if(empty($cashbook->sell_id) && empty($cashbook->other_id)){
                $row[] = $cashbook->voucher_no;
            } else if(!empty($cashbook->other_id)){
                $row[] = $cashbook->other_no;
            } else {
                $row[] = $cashbook->sell_no;
            }
            $row[] = $cashbook->narration;
            $row[] = $cashbook->transaction_date ? date('d-m-Y', strtotime($cashbook->transaction_date)) : '';
            $row[] = $cashbook->is_payment_received;
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
    
    function save_reminder(){
//        echo '<pre>'; print_r($_POST); exit;
        $return = array();
        $reminder_arr = array();
        $reminder_arr['account_id'] = $_POST['account_id'];
        $reminder_arr['debit_credit'] = $_POST['debit_credit'];
        $reminder_arr['gold'] = $_POST['gold'];
        $reminder_arr['silver'] = $_POST['silver'];
        $reminder_arr['amount'] = $_POST['amount'];
        $reminder_arr['remarks'] = $_POST['remarks'];
        $reminder_arr['date'] = date('Y-m-d', strtotime($_POST['date']));
        $reminder_arr['created_at'] = $this->now_time;
        $reminder_arr['created_by'] = $this->logged_in_id;
        $reminder_arr['updated_at'] = $this->now_time;
        $reminder_arr['updated_by'] = $this->logged_in_id;
        $result = $this->crud->insert('reminder', $reminder_arr);
        if ($result) {
            $return['success'] = "Added";
            $return['date'] = date('d-m-Y', strtotime("+1 day"));
        }
        echo json_encode($return);
    }
    
    function reminder() {
        $data = array();
        $this->crud->execuetSQL(" DELETE FROM reminder WHERE account_id IN (SELECT account_id FROM `account` WHERE gold_fine = 0 AND silver_fine = 0 AND amount = 0) ");
        if ($this->applib->have_access_role(REPORT_MODULE_ID, "view") && $this->applib->have_access_role(REMINDER_MODULE_ID, 'view')) {
            set_page('reports/reminder', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function reminder_datatable() {
        $post_data = $this->input->post();
        $upto_date = '';
        if (!empty($post_data['reminder_date'])) {
            $upto_date = date('Y-m-d', strtotime($post_data['reminder_date']));
        }
        $config['table'] = 'reminder r';
        $config['select'] = 'r.*,a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = r.account_id', 'join_type' => 'left');
        $config['column_search'] = array('DATE_FORMAT(r.date,"%d-%m-%Y")', 'a.account_name', 'r.remarks', 'r.gold','r.silver');
        $config['column_order'] = array(NULL, 'r.date', 'a.account_name','r.silver','r.silver','r.gold','r.gold','r.amount','r.amount', 'r.remarks');
        if (!empty($upto_date)) {
            $config['wheres'][] = array('column_name' => 'r.date <=', 'column_value' => $upto_date);
        }
        if (!empty($post_data['account_id'])) {
            $config['wheres'][] = array('column_name' => 'r.account_id', 'column_value' => $post_data['account_id']);
        }
        $config['order'] = array('r.date' => 'asc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $role_delete = $this->app_model->have_access_role(REMINDER_MODULE_ID, "delete");
        $data = array();
        foreach ($list as $reminder) {
            $row = array();
            $action = '';
            if($role_delete){
                $action .= '<input type="checkbox" name="delete_multiple[]" id="delete_multiple" data-reminder_id="'.$reminder->reminder_id.'"  class="delete_multiple" style="width: 20px; height: 20px;" > &nbsp; ';
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('reports/delete_reminder/' . $reminder->reminder_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = date('d-m-Y', strtotime($reminder->date));
            $row[] = $reminder->account_name;
            if($reminder->debit_credit == '2'){
                $row[] = number_format($reminder->gold, 3, '.', '');
                $row[] = number_format('0', 3, '.', '');
                $row[] = number_format($reminder->silver, 3, '.', '');
                $row[] = number_format('0', 3, '.', '');
                $row[] = number_format($reminder->amount, 2, '.', '');
                $row[] = number_format('0', 2, '.', '');
            } else {
                $row[] = number_format('0', 3, '.', '');
                $row[] = number_format($reminder->gold, 3, '.', '');
                $row[] = number_format('0', 3, '.', '');
                $row[] = number_format($reminder->silver, 3, '.', '');
                $row[] = number_format('0', 2, '.', '');
                $row[] = number_format($reminder->amount, 2, '.', '');
            }
            $row[] = $reminder->remarks;
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
    
    function delete_reminder($reminder_id = '') {
        $this->crud->delete('reminder', array('reminder_id' => $reminder_id));
    }
    
    function delete_multiple_reminder() {
        $ids = json_decode($_POST['reminder_ids']);
        $this->crud->delete_where_in('reminder', 'reminder_id',$ids);
        echo json_encode(array('success' => 'deleted'));
    }
    
    function stock_check() {
        if ($this->applib->have_access_role(STOCK_STATUS_MODULE_ID, "stock_check") && $this->applib->have_access_role(REPORT_MODULE_ID, 'view')) {
            $data = array();
            $data['process'] = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            $data['category'] = $this->crud->get_all_records('category', 'category_id', '');
            $data['items'] = $this->crud->get_all_records('item_master', 'item_id', '');
            $data['carat'] = $this->crud->get_all_records('carat', 'purity', 'ASC');
            $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
            $data['silver_rate']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'silver_rate'));
            set_page('reports/stock_check', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function get_rfid_row_for_stock_check() {
        $data = array();
        $data['result_status'] = '';
        $data['rfid_grwt'] = 0;
        $rfid_number = $_POST['rfid_number'];
        $data['rfid_number'] = $rfid_number;
        $item_stock_rfid_data = $this->crud->getFromSQL('SELECT * FROM `item_stock_rfid` WHERE `real_rfid` = "'.$rfid_number.'" AND `rfid_used` = 0');
        if (!empty($item_stock_rfid_data)) {
            foreach ($item_stock_rfid_data as $item_stock_rfid){
                $data['item_stock_rfid_id'] = $item_stock_rfid->item_stock_rfid_id;
                $data['rfid_grwt'] = number_format($item_stock_rfid->rfid_grwt, 3, '.', '');
                $data['result_status'] = 'Wrong Place';
            }
        } else {
            $data['result_status'] = 'New RFID';
        }
        print json_encode($data);
        exit;
    }
    
    function get_rfid_data_list() {
        $data = array();
        $department_id = $_POST['department_id'];
        $category_id = $_POST['category_id'];
        $item_id = $_POST['item_id'];
        $tunch = $_POST['tunch'];
        $item_stock_rfid_sql = 'SELECT isr.item_stock_rfid_id, isr.rfid_grwt, isr.real_rfid FROM `item_stock_rfid` isr ';
        $item_stock_rfid_sql .= 'JOIN `item_stock` i_s ON i_s.`item_stock_id` = isr.`item_stock_id` ';
        $item_stock_rfid_sql .= 'WHERE isr.`rfid_used` = 0';
        if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
            $item_stock_rfid_sql .= ' AND i_s.`department_id` = "'.$_POST['department_id'].'"';
        }
        if(isset($_POST['category_id']) && !empty($_POST['category_id'])){
            $item_stock_rfid_sql .= ' AND i_s.`category_id` = "'.$_POST['category_id'].'"';
        }
        if(isset($_POST['item_id']) && !empty($_POST['item_id'])){
            $item_stock_rfid_sql .= ' AND i_s.`item_id` = "'.$_POST['item_id'].'"';
        }
        if(isset($_POST['tunch']) && !empty($_POST['tunch'])){
            $item_stock_rfid_sql .= ' AND i_s.`tunch` = "'.$_POST['tunch'].'"';
        }
        $item_stock_rfid_data = $this->crud->getFromSQL($item_stock_rfid_sql);
        if (!empty($item_stock_rfid_data)) {
            foreach ($item_stock_rfid_data as $item_stock_rfid){
                $rfid_row = array();
                $rfid_row['rfid_grwt'] = number_format($item_stock_rfid->rfid_grwt, 3, '.', '');
                $rfid_row['item_stock_rfid_id'] = $item_stock_rfid->item_stock_rfid_id;
                $rfid_row['rfid_number'] = $item_stock_rfid->real_rfid;
                $rfid_row['rfid_checked'] = '0';
                $data[] = $rfid_row;
            }
        }
        print json_encode($data);
        exit;
    }
    
}
