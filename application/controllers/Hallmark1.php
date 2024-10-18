<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hallmark1 extends CI_Controller {

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

    function add() {
        $data = array();
        set_page('hallmark1/add', $data);
    }

    function save_entry() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        if (isset($post_data['journal_id']) && !empty($post_data['journal_id'])) {

        } else {
            if(!empty($post_data['purchase_gr_wt']) && !empty($post_data['purchase_tunch']) && !empty($post_data['purchase_fine'])){} else {
                $post_data['purchase_gr_wt'] = 0;
                $post_data['purchase_tunch'] = 0;
                $post_data['purchase_fine'] = 0;
            }
            if(!empty($post_data['gold_bhav_type_id']) && !empty($post_data['gold_bhav_gr_wt']) && !empty($post_data['gold_bhav_rate']) && !empty($post_data['gold_bhav_amount'])){} else {
                $post_data['gold_bhav_type_id'] = 0;
                $post_data['gold_bhav_gr_wt'] = 0;
                $post_data['gold_bhav_rate'] = 0;
                $post_data['gold_bhav_amount'] = 0;
            }
            if(!empty($post_data['sell_gr_wt']) && !empty($post_data['sell_tunch']) && !empty($post_data['sell_fine'])){} else {
                $post_data['sell_gr_wt'] = 0;
                $post_data['sell_tunch'] = 0;
                $post_data['sell_fine'] = 0;
            }
            $in_arr = array();
            if (isset($post_data['auto_fill_pending_wt'])) {
                unset($post_data['auto_fill_pending_wt']);
                $in_arr['auto_fill_pending_wt'] = 1;
            } else {
                $in_arr['auto_fill_pending_wt'] = 0;
            }
            $in_arr['entry_date'] = date('Y-m-d', strtotime($post_data['entry_date']));
            $new_no = null;
            if(!empty($post_data['payment_amount'])){
                $max_pay_no_sql = $this->crud->getFromSQL(" SELECT MAX(payment_no) as max_payment_no FROM tunch_testing WHERE entry_date = '".$in_arr['entry_date']."' ");
                $new_no = 1;
                if(!empty($max_pay_no_sql[0]->max_payment_no)){
                    $new_no = $max_pay_no_sql[0]->max_payment_no + 1;
                }
            }
            $in_arr['payment_no'] = $new_no;
            $in_arr['account_id'] = $post_data['account_id'];
            $in_arr['item_id'] = $post_data['item_id'];
            $in_arr['remark'] = $post_data['remark'];
            $in_arr['purchase_gr_wt'] = $post_data['purchase_gr_wt'];
            $in_arr['purchase_tunch'] = $post_data['purchase_tunch'];
            $in_arr['purchase_fine'] = $post_data['purchase_fine'];
            $in_arr['payment_amount'] = $post_data['payment_amount'];
            $in_arr['receipt_amount'] = $post_data['receipt_amount'];
            $in_arr['gold_bhav_type_id'] = $post_data['gold_bhav_type_id'];
            $in_arr['gold_bhav_gr_wt'] = $post_data['gold_bhav_gr_wt'];
            $in_arr['gold_bhav_rate'] = $post_data['gold_bhav_rate'];
            $in_arr['gold_bhav_amount'] = $post_data['gold_bhav_amount'];
            $in_arr['sell_gr_wt'] = $post_data['sell_gr_wt'];
            $in_arr['sell_tunch'] = $post_data['sell_tunch'];
            $in_arr['sell_fine'] = $post_data['sell_fine'];
            $in_arr['created_at'] = $this->now_time;
            $in_arr['created_by'] = $this->logged_in_id;
            $in_arr['updated_at'] = $this->now_time;
            $in_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('tunch_testing', $in_arr);
//            echo $this->db->last_query(); exit;
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Tunch Testing Entry Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function tunch_testing_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'tunch_testing t';
        $config['select'] = 't.*,a.account_name,i.item_name,c.category_group_id';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = t.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master i', 'join_by' => 'i.item_id = t.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id = i.category_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name','i.item_name', 'DATE_FORMAT(t.entry_date,"%d-%m-%Y")');
//        $config['column_order'] = array(null, 'pm.account_name', 'j.journal_date');
        $config['order'] = array('t.tunch_testing_id' => 'desc');
        $config['wheres'][] = array('column_name' => 't.entry_date', 'column_value' => date('Y-m-d', strtotime($post_data['entry_date'])));
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        foreach ($list as $entry) {
            $row = array();
            $action = '<a href="javascript:void(0);" class="delete_tunch_testing" data-href="' . base_url('tunch_testing/delete/' . $entry->tunch_testing_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            $row[] = $action;
            $row[] = $entry->account_name;
            $row[] = $entry->item_name;
            $row[] = number_format((float) $entry->purchase_gr_wt, 3, '.', '');
            $row[] = number_format((float) $entry->purchase_tunch, 3, '.', '');
            $row[] = number_format((float) $entry->purchase_fine, 3, '.', '');
            $row[] = $entry->payment_no;
            if($entry->category_group_id == CATEGORY_GROUP_GOLD_ID && $entry->gold_bhav_type_id == '1'){
                $row[] = number_format((float) $entry->gold_bhav_gr_wt, 3, '.', '');
                $row[] = number_format((float) $entry->gold_bhav_rate, 3, '.', '');
                $row[] = number_format((float) $entry->gold_bhav_amount, 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
                $row[] = '';
            }
            if($entry->category_group_id == CATEGORY_GROUP_SILVER_ID && $entry->gold_bhav_type_id == '1'){
                $row[] = number_format((float) $entry->gold_bhav_gr_wt, 3, '.', '');
                $row[] = number_format((float) $entry->gold_bhav_rate, 3, '.', '');
                $row[] = number_format((float) $entry->gold_bhav_amount, 2, '.', '');
            } else {
                $row[] = '';
                $row[] = '';
                $row[] = '';
            }
            $row[] = number_format((float) $entry->payment_amount, 2, '.', '');
            $row[] = '';
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

    function delete($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $this->crud->delete($table, array($id_name => $id));
    }


}
