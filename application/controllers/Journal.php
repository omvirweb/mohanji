<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Journal extends CI_Controller {

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

    function add($journal_id = '') {
        $data = array();
        $journal_detail = new \stdClass();
        if (!empty($journal_id)) {
            if ($this->applib->have_access_role(JOURNAL_MODULE_ID, "edit") || $this->applib->have_access_role(JOURNAL_MODULE_ID, 'view')) {
                $journal_data = $this->crud->get_row_by_id('journal', array('journal_id' => $journal_id));
                $journal_items = $this->crud->get_row_by_id('journal_details', array('journal_id' => $journal_id));
                $journal_data = $journal_data[0];
                $journal_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $journal_data->created_by));
                if($journal_data->created_by != $journal_data->updated_by){
                   $journal_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$journal_data->updated_by)); 
                }else{
                   $journal_data->updated_by_name = $journal_data->created_by_name;
                }
                $data['journal_data'] = $journal_data;
                $lineitems = array();
                foreach ($journal_items as $journal_item) {
                    $journal_detail->account_name = $this->crud->get_column_value_by_id('account', 'account_name', array('account_id' => $journal_item->account_id));
                    $journal_detail->type = $journal_item->type;
                    $journal_detail->amount = $journal_item->amount;
                    $journal_detail->narration = $journal_item->narration;
                    $journal_detail->account_id = $journal_item->account_id;
                    $journal_detail->jd_id = $journal_item->jd_id;
                    $lineitems[] = json_encode($journal_detail);
                }
                $data['journal_detail'] = implode(',', $lineitems);
                set_page('journal/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(JOURNAL_MODULE_ID, "add") || $this->applib->have_access_role(JOURNAL_MODULE_ID, 'view')) {
                $lineitems = array();
                set_page('journal/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_journal() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($post_data); exit;
        //echo '<pre>'; print_r($line_items_data); exit;

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

        $is_bank_journal = 0;
        if (!empty($line_items_data)) {
            foreach ($line_items_data as $line_items_row) {
                $account_group_id = $this->crud->get_column_value_by_id('account', 'account_group_id', array('account_id' => $line_items_row->account_id));
                if($account_group_id == BANK_ACCOUNT_GROUP){
                    $is_bank_journal = 1;
                }
            }
        }
        //$post_data['order_status_id'] = isset($post_data['order_status_id']) && !empty($post_data['order_status_id']) ? $post_data['order_status_id'] : NULL;
        if (isset($post_data['journal_id']) && !empty($post_data['journal_id'])) {

            // Increase and Decrease amount in Account
            $journal_details_data = $this->crud->get_row_by_id('journal_details', array('journal_id' => $post_data['journal_id']));
            if (!empty($journal_details_data)) {
                foreach ($journal_details_data as $journal_detail) {
                    $account = $this->crud->get_row_by_id('account', array('account_id' => $journal_detail->account_id));
                    $account = $account[0];
                    $acc_amount = $account->amount;
                    $acc_c_amount = $account->c_amount;
                    $acc_r_amount = $account->r_amount;
                    if ($journal_detail->type == 1) {
                        $acc_amount = $acc_amount - $journal_detail->amount;
                        $acc_c_amount = $acc_c_amount - $journal_detail->c_amt;
                        $acc_r_amount = $acc_r_amount - $journal_detail->r_amt;
                    }
                    if ($journal_detail->type == 2) {
                        $acc_amount = $acc_amount + $journal_detail->amount;
                        $acc_c_amount = $acc_c_amount + $journal_detail->c_amt;
                        $acc_r_amount = $acc_r_amount + $journal_detail->r_amt;
                    }
                    $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                    $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                    $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
                    $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $journal_detail->account_id));
                }
            }

            $update_arr = array();
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['journal_date'] = date('Y-m-d', strtotime($post_data['journal_date']));
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['journal_id'] = $post_data['journal_id'];
            $result = $this->crud->update('journal', $update_arr, $where_array);
            $deleted_jd_ids = $post_data['deleted_jd_ids'];
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Journal Entry Updated Successfully');
                if (!empty($deleted_jd_ids)) {
                    $deleted_jd_ids = explode(',', $deleted_jd_ids);
                    foreach ($deleted_jd_ids as $id) {
                        $where_array = array('jd_id' => $id);
                        $this->crud->delete('journal_details', $where_array);
                    }
                }
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        $update_item = array();
                        $update_item['journal_id'] = $post_data['journal_id'];
                        $update_item['account_id'] = $lineitem->account_id;
                        $update_item['type'] = $lineitem->type;
                        $update_item['amount'] = $lineitem->amount;
                        $update_item['narration'] = $lineitem->narration;

                        if ($lineitem->jd_id != '') {
                            $where = array("jd_id" => $lineitem->jd_id);
                            $update_item['updated_at'] = $this->now_time;
                            $update_item['updated_by'] = $this->logged_in_id;
                            $this->crud->update("journal_details", $update_item, $where);
                            $jd_id = $lineitem->jd_id;
                        } else {
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $update_item['updated_at'] = $this->now_time;
                            $update_item['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('journal_details', $update_item);
                            $jd_id = $this->db->insert_id();
                        }
                        // Increase and Decrease amount in Account
                        $account = $this->crud->get_row_by_id('account', array('account_id' => $lineitem->account_id));
                        $account = $account[0];
                        $acc_amount = $account->amount;
                        $acc_c_amount = $account->c_amount;
                        $acc_r_amount = $account->r_amount;
                        $c_amt = 0;
                        $r_amt = 0;
                        if ($lineitem->type == 1) {
                            $acc_amount = $acc_amount + $lineitem->amount;
                            if($is_bank_journal == 1){
                                $r_amt = $lineitem->amount;
                                $acc_r_amount = $acc_r_amount + $lineitem->amount;
                            } else {
                                $c_amt = $lineitem->amount;
                                $acc_c_amount = $acc_c_amount + $lineitem->amount;
                            }
                        }
                        if ($lineitem->type == 2) {
                            $acc_amount = $acc_amount - $lineitem->amount;
                            if($is_bank_journal == 1){
                                $r_amt = $lineitem->amount;
                                $acc_r_amount = $acc_r_amount - $lineitem->amount;
                            } else {
                                $c_amt = $lineitem->amount;
                                $acc_c_amount = $acc_c_amount - $lineitem->amount;
                            }
                        }
                        $this->crud->update('journal_details', array('c_amt' => $c_amt, 'r_amt' => $r_amt), array('jd_id' => $jd_id));
                        $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                        $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                        $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $lineitem->account_id));
                    }
                }
            }
        } else {
            $insert_arr = array();
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['journal_date'] = date('Y-m-d', strtotime($post_data['journal_date']));
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('journal', $insert_arr);
            $journal_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Journal Entry Added Successfully');
                $item_inc = 1;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {

                        $insert_item = array();
                        $insert_item['journal_id'] = $journal_id;
                        $insert_item['account_id'] = $lineitem->account_id;
                        $insert_item['amount'] = $lineitem->amount;
                        $insert_item['type'] = $lineitem->type;
                        $insert_item['narration'] = $lineitem->narration;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;

                        $this->crud->insert('journal_details', $insert_item);
                        $jd_id = $this->db->insert_id();
                        // Increase and Decrease amount in Account
                        $account = $this->crud->get_row_by_id('account', array('account_id' => $lineitem->account_id));
                        $account = $account[0];
                        $acc_amount = $account->amount;
                        $acc_c_amount = $account->c_amount;
                        $acc_r_amount = $account->r_amount;
                        $c_amt = 0;
                        $r_amt = 0;
                        if ($lineitem->type == 1) {
                            $acc_amount = $acc_amount + $lineitem->amount;
                            if($is_bank_journal == 1){
                                $r_amt = $lineitem->amount;
                                $acc_r_amount = $acc_r_amount + $lineitem->amount;
                            } else {
                                $c_amt = $lineitem->amount;
                                $acc_c_amount = $acc_c_amount + $lineitem->amount;
                            }
                        }
                        if ($lineitem->type == 2) {
                            $acc_amount = $acc_amount - $lineitem->amount;
                            if($is_bank_journal == 1){
                                $r_amt = $lineitem->amount;
                                $acc_r_amount = $acc_r_amount - $lineitem->amount;
                            } else {
                                $c_amt = $lineitem->amount;
                                $acc_c_amount = $acc_c_amount - $lineitem->amount;
                            }
                        }
                        $this->crud->update('journal_details', array('c_amt' => $c_amt, 'r_amt' => $r_amt), array('jd_id' => $jd_id));
                        $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                        $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
                        $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $lineitem->account_id));
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function journal_list() {
        if ($this->applib->have_access_role(JOURNAL_MODULE_ID, "view")) {
            $data = array();
            set_page('journal/journal_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function journal_datatable() {
//        SELECT journal.*, GROUP_CONCAT(journal_details.narration), GROUP_CONCAT(account.account_name) FROM journal
//        INNER JOIN journal_details ON journal.journal_id = journal_details.journal_id
//        INNER JOIN account ON account.account_id = journal_details.account_id
//        GROUP BY journal.journal_id
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'journal j';
        $config['select'] = 'j.*,pm.account_name AS process_name, GROUP_CONCAT(a.account_name SEPARATOR "<br>") as account_names';
        $config['joins'][] = array('join_table' => 'journal_details jd', 'join_by' => 'j.journal_id = jd.journal_id', 'join_type' => 'inner');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = jd.account_id', 'join_type' => 'inner');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = j.department_id', 'join_type' => 'left');
        $config['column_search'] = array('pm.account_name', 'DATE_FORMAT(j.journal_date,"%d-%m-%Y")');
        $config['column_order'] = array(null, 'pm.account_name', 'j.journal_date');
        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 'j.department_id IN('.$department_ids.')';
        }
        if (!empty($post_data['department_id'])) {
            $config['wheres'][] = array('column_name' => 'j.department_id', 'column_value' => $post_data['department_id']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['from_date'])) {
                $config['wheres'][] = array('column_name' => 'j.journal_date >=', 'column_value' => $from_date);
            }
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 'j.journal_date <=', 'column_value' => $to_date);
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 'j.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }
        $config['group_by'] = 'j.journal_id';
        $config['order'] = array('j.journal_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(JOURNAL_MODULE_ID, "edit");

        foreach ($list as $journals) {
            $row = array();
            $action = '';
            
            if(!empty($journals->relation_id) && $journals->is_module == MHM_TO_JOURNAL_ID){ } else {
                if($journals->audit_status != AUDIT_STATUS_AUDITED){
                    if ($role_edit) {
                        $action .= '<a href="' . base_url("journal/add/" . $journals->journal_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                    }
                    if ($role_delete) {
                        $action .= '<a href="javascript:void(0);" class="delete_journal" data-href="' . base_url('journal/delete_journal/' . $journals->journal_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                    }
                } else {
                    $action .= '<a href="' . base_url("journal/add/" . $journals->journal_id) . '"><span class="edit_button glyphicon glyphicon-eye-open data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            $audit_status = '';
            if($journals->audit_status == AUDIT_STATUS_AUDITED){
                $audit_status = 'A';
            } else if($journals->audit_status == AUDIT_STATUS_SUSPECTED){
                $audit_status = 'S';
            } else {
                $audit_status = 'P';
            }
            $action .= '<a href="javascript:void(0);" class="audit_status_button" data-audit_status_pay_rec_id="' . $journals->journal_id . '" data-audit_status="' . $journals->audit_status . '" style="margin: 8px;">'. $audit_status .'</a>';
            $row[] = $action;

            $row[] = $journals->journal_id;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-journal_id="' . $journals->journal_id . '" >' . $journals->process_name . '</a>';
            $row[] = (!empty(strtotime($journals->journal_date))) ? date('d-m-Y', strtotime($journals->journal_date)) : '';
            $row[] = '<div style="font-size: 12px;">' . $journals->account_names . '</div>';
            $row[] = '<div style="font-size: 12px;">' . $journals->ibs_tran_particular . '</div>';
            $amount = $this->crud->getFromSQL('SELECT SUM(amount) AS amount FROM journal_details WHERE type = 1 AND journal_id = "' . $journals->journal_id . '"');
            $amount = $amount[0];
            if (!empty($amount->amount))
                $row[] = $amount->amount;
            else
                $row[] = 0;
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

    function journal_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'journal_details jd';
        $config['select'] = 'jd.*,a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = jd.account_id', 'join_type' => 'left');
        $config['column_order'] = array('a.account_name', 'jd.type', 'jd.amount', 'jd.narration');
        $config['column_search'] = array('a.account_name', 'jd.type', 'jd.amount', 'jd.narration');
        if (isset($post_data['journal_id']) && !empty($post_data['journal_id'])) {
            $config['wheres'][] = array('column_name' => 'jd.journal_id', 'column_value' => $post_data['journal_id']);
        }

        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $lot_detail) {
            $row = array();
            $row[] = $lot_detail->account_name;
            if ($lot_detail->type == 1) {
                $row[] = $lot_detail->amount;
            } else {
                $row[] = '';
            }
            if ($lot_detail->type == 2) {
                $row[] = $lot_detail->amount;
            } else {
                $row[] = '';
            }
            $row[] = $lot_detail->narration;
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
    
    function audit_status_journal() {
        $return = array();
        if(isset($_POST['audit_status_pay_rec_id']) && !empty($_POST['audit_status_pay_rec_id']) && isset($_POST['audit_status']) && !empty($_POST['audit_status'])){
            $result = $this->crud->update('journal', array('audit_status' => $_POST['audit_status']), array('journal_id' => $_POST['audit_status_pay_rec_id']));
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

    function delete_journal($id = '') {
        $where_array = array('journal_id' => $id);
        // Increase and Decrease amount in Account
        $journal_details_data = $this->crud->get_row_by_id('journal_details', $where_array);
        foreach ($journal_details_data as $journal_detail) {
            $account = $this->crud->get_row_by_id('account', array('account_id' => $journal_detail->account_id));
            $account = $account[0];
            $acc_amount = $account->amount;
            $acc_c_amount = $account->c_amount;
            $acc_r_amount = $account->r_amount;
            if ($journal_detail->type == 1) {
                $acc_amount = $acc_amount - $journal_detail->amount;
                $acc_c_amount = $acc_c_amount - $journal_detail->c_amt;
                $acc_r_amount = $acc_r_amount - $journal_detail->r_amt;
            }
            if ($journal_detail->type == 2) {
                $acc_amount = $acc_amount + $journal_detail->amount;
                $acc_c_amount = $acc_c_amount + $journal_detail->c_amt;
                $acc_r_amount = $acc_r_amount + $journal_detail->r_amt;
            }
            $acc_amount = number_format((float) $acc_amount, '2', '.', '');
            $acc_c_amount = number_format((float) $acc_c_amount, '2', '.', '');
            $acc_r_amount = number_format((float) $acc_r_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $acc_amount, 'c_amount' => $acc_c_amount, 'r_amount' => $acc_r_amount), array('account_id' => $journal_detail->account_id));
        }
        $this->crud->delete('journal_details', $where_array);
        $this->crud->delete('journal', $where_array);
    }

}
