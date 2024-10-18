<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

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

    function transaction($transaction_id = '') {

        $data = array();
        $account = $this->crud->get_all_records('account', 'account_id', 'account_name');        
        $data['account'] = $account;
//            if (isset($category_id) && !empty($category_id)) {
//            $category_data = $this->crud->get_row_by_id('category', array('category_id' => $category_id));
//            $category_data = $category_data[0];
//            $data['category_data'] = $category_data;

            set_page('transaction/transaction', $data);
//             } else {
//            set_page('reports/daybook', $data);
        }
    
//    function stock_status() {
//        $data = array();
////        $process = $this->crud->get_all_records('process_master', 'process_id', '');        
////        $data['process'] = $process;
//        $category = $this->crud->get_all_records('category', 'category_id', '');        
//        $data['category'] = $category;
//        $items = $this->crud->get_all_records('item_master', 'item_id', '');        
//        $data['items'] = $items;
//        set_page('reports/stock_status', $data);
//    }
//    
//    function stock_status_datatable() {
//        $post_data = $this->input->post();
//        $config['table'] = 'item_stock s';
//        $config['select'] = 's.*,cat.category_name,im.item_name,cat.category_group_id';
//        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = s.item_id', 'join_type' => 'left');
////        $config['joins'][] = array('join_table' => 'process_master pm', 'join_by' => 'pm.process_id = s.department_id', 'join_type' => 'left');
//        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = s.category_id', 'join_type' => 'left');
//        $config['column_search'] = array('cat.category_name','im.item_name','s.grwt', 's.less', 's.ntwt', 's.tunch', 's.fine');
//        $config['column_order'] = array('cat.category_name', 'im.item_name', 's.grwt', 's.less', 's.ntwt', 's.tunch', 's.fine','s.fine');
////        if(!empty($post_data['department_id'])){
////            $config['wheres'][] = array('column_name' => 's.department_id', 'column_value' => $post_data['department_id']);
////        }
//        if(!empty($post_data['category_id'])){
//            $config['wheres'][] = array('column_name' => 's.category_id', 'column_value' => $post_data['category_id']);
//        }
//        if(!empty($post_data['item_id'])){
//            $config['wheres'][] = array('column_name' => 's.item_id', 'column_value' => $post_data['item_id']);
//        }
//        $config['order'] = array('s.item_stock_id' => 'desc');
//        $this->load->library('datatables', $config, 'datatable');
//        $list = $this->datatable->get_datatables();
//        $data = array();
//        foreach ($list as $stock) {
//            $row = array();
////            $row[] = $stock->process_name;
//            $row[] = $stock->category_name;
//            $row[] = $stock->item_name;
//            $row[] = number_format($stock->grwt, 3, '.', '');
//            $row[] = number_format($stock->less, 3, '.', '');
//            $row[] = number_format($stock->ntwt, 3, '.', '');
//            $row[] = number_format($stock->tunch, 2, '.', '');
//            $gold = '';
//            $silver = '';
//            if($stock->category_group_id == 1){
//                $gold = number_format($stock->fine, 3, '.', '');
//            } else if($stock->category_group_id == 2){
//                $silver = number_format($stock->fine, 3, '.', '');
//            } 
//            $row[] = $gold;
//            $row[] = $silver;
//            $data[] = $row;
//        }
//        $output = array(
//            "draw" => $_POST['draw'],
//            //"recordsTotal" => $this->datatable->count_all(),
//            "recordsFiltered" => $this->datatable->count_filtered(),
//            "data" => $data,
//        );
//        echo json_encode($output);
//    }
//    
        function transaction_datatable(){
            $post_data = $this->input->post();
            if(!empty($post_data['from_date'])){
                $from_date = date('Y-m-d', strtotime($post_data['from_date']));
            }
            if(!empty($post_data['to_date'])){
                $to_date = date('Y-m-d', strtotime($post_data['to_date']));
            }
            $config['table'] = 'sell s';
            $config['select'] = 's.*,a.account_name,si.type';
            $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.account_id ', 'join_type' => 'left');
            $config['joins'][] = array('join_table' => 'sell_items si', 'join_by' => 'si.sell_id = s.sell_id ', 'join_type' => 'left');
            $config['column_search'] = array('s.total_amount' ,'s.account_id' ,'s.total_gold_fine', 's.total_silver_fine');
            $config['column_order'] = array(null, 's.total_amount' ,'s.account_id' ,'s.total_gold_fine', 's.total_silver_fine');
            $config['order'] = array('s.sell_id' => 'desc');
            //$config['wheres'][] = array('column_name' => 's.sell_date', 'column_value' => $date);
            if(!empty($post_data['from_date'])){
                $config['wheres'][] = array('column_name' => 's.sell_date >=', 'column_value' => $from_date);
            }
            if(!empty($post_data['to_date'])){
                $config['wheres'][] = array('column_name' => 's.sell_date <=', 'column_value' => $to_date);
            }
            if(!empty($post_data['account_id'])){
                $config['wheres'][] = array('column_name' => 's.account_id ', 'column_value' => $post_data['account_id']);
            }
            $this->load->library('datatables', $config, 'datatable');
            $list = $this->datatable->get_datatables();
            $data = array();

            foreach ($list as $sell) {
                $row = array();
                $row[] = $sell->account_name;
                $row[] = $sell->sell_no;
                $row[] = $sell->type == 1 ? $sell->total_gold_fine : '';
                $row[] = ($sell->type == 2) || ($sell->type == 3) ? $sell->total_gold_fine : '';
                $row[] = $sell->type == 1 ? $sell->total_silver_fine : '';
                $row[] = ($sell->type == 2) || ($sell->type == 3) ? $sell->total_silver_fine : '';
                $row[] = $sell->type == 1 ? $sell->total_amount : '';
                $row[] = ($sell->type == 2) || ($sell->type == 3) ? $sell->total_amount : '';
                $data[] = $row;
            }
            if(empty($post_data['transaction_id']) || $post_data['transaction_id'] == '3'){
                $where = '';
                if(!empty($post_data['from_date'])){
                    $where .= ' j.journal_date >= "'.$from_date.'"';
                }
                if(!empty($post_data['to_date'])){
                    $where .= ' AND j.journal_date <= "'.$to_date.'"';
                }
                if(!empty($post_data['account_id'])){
                    $where .= ' AND jd.account_id = "'.$post_data['account_id'].'"';
                }
                $journals = $this->crud->getFromSQL("SELECT j.journal_date,j.journal_id , jd.type , jd.amount, jd.account_id, a.account_name FROM journal as j LEFT JOIN journal_details as jd ON jd.journal_id = j.journal_id LEFT JOIN account as a ON a.account_id = jd.account_id WHERE ".$where);
                foreach ($journals as $journal) {
                    $row = array();
                    $row[] = $journal->account_name;
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = $journal->type == 2 ? $journal->amount : '';
                    $row[] = $journal->type == 1 ? $journal->amount : '';
                    $data[] = $row;
                }
                $where = '';
                if(!empty($post_data['from_date'])){
                    $where .= ' c.date >= "'.$from_date.'"';
                }
                if(!empty($post_data['to_date'])){
                    $where .= ' AND c.date <= "'.$to_date.'"';
                }
                if(!empty($post_data['account_id'])){
                    $where .= ' AND c.account_id = "'.$post_data['account_id'].'"';
                }
                $cashbooks = $this->crud->getFromSQL("SELECT c.*,a.account_name FROM cashbook as c LEFT JOIN account as a ON a.account_id = c.account_id WHERE ".$where);
                foreach ($cashbooks as $cashbook) {
                    $row = array();
                    $row[] = $cashbook->account_name;
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                    $row[] = $cashbook->transaction_type == 1 ? $cashbook->amount : '';
                    $row[] = $cashbook->transaction_type == 2 ? $cashbook->amount : '';
                    $data[] = $row;
                }
            }
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data,
            );
            echo json_encode($output);
        }
}
