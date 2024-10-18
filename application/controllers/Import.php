<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

    public $logged_in_id = null;
    public $now_time = null;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('Appmodel', 'app_model');
        $this->load->model('Crud', 'crud');
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')) {
            redirect('/auth/login/');
        }
        $this->logged_in_id = $this->session->userdata(PACKAGE_FOLDER_NAME . 'is_logged_in')['user_id'];
        $this->now_time = date('Y-m-d H:i:s');
    }

    function import() {
        $data = array();
        set_page('import/import', $data);
    }

    function import_data() {
        $return = array();
        $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");

        if (isset($_REQUEST['type']) && $_REQUEST['type'] == '1') {
            $row_count = 0;
            $import_allow = '1';
            $row_duplicate_arr = array();
            while ($csv_line = fgetcsv($fp, 1024)) {
                $row_count++;
                if ($row_count == 1) { // Skip Heading row
                    continue;
                }
                if (empty(trim($csv_line[0]))) { // Department
                    $import_allow = '0';
                    break;
                }
                if (empty(trim($csv_line[2]))) { // Category
                    $import_allow = '0';
                    break;
                }
                if (empty(trim($csv_line[3]))) { // Item
                    $import_allow = '0';
                    break;
                }
                $item_id = $this->crud->get_column_value_by_id('item_master', 'item_id', array('item_name' => trim($csv_line[3])));
                if (empty($item_id)) {
                    if (empty(trim($csv_line[10]))) { // Stock Method
                        $import_allow = '0';
                        break;
                    }
                }
                if (empty(trim($csv_line[4]))) { // Gr.Wt
                    $import_allow = '0';
                    break;
                }
                if (empty(trim($csv_line[7]))) { // Tunch
                    $import_allow = '0';
                    break;
                }

                // Check Duplicate Entry are there or not
                $department_id = $this->crud->get_column_value_by_id('account', 'account_id', array('account_name' => trim($csv_line[0]), 'account_group_id' => DEPARTMENT_GROUP));
                $category_id = $this->crud->get_column_value_by_id('category', 'category_id', array('category_name' => trim($csv_line[2])));
                $tunch = $this->crud->get_column_value_by_id('carat', 'purity', array('purity' => trim($csv_line[7])));
                if (!empty($department_id) && !empty($category_id) && !empty($item_id) && !empty($tunch)) {
                    $item_stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $item_id));
                    if ($item_stock_method == STOCK_METHOD_ITEM_WISE) {

                    } else {
                        $item_stock_duplication = $this->crud->get_row_by_id('opening_stock', array('department_id' => $department_id, 'category_id' => $category_id, 'item_id' => $item_id, 'tunch' => $tunch, 'design_no' => trim($csv_line[8])));
                        if (isset($item_stock_duplication) && !empty($item_stock_duplication)) {
                            $row_duplicate_arr[] = $row_count;
                        }
                    }
                }
            }
            fclose($fp) or die("can't close file");

            if ($import_allow == '0') {
                $return['error'] = "File is Improper";
                print json_encode($return);
                exit;
            }
            if (!empty($row_duplicate_arr)) {
                $return['error'] = "Exist";
                $return['row_duplicate_data'] = json_encode($row_duplicate_arr);
                $return['error_exist'] = 'Opening Stock Already Exist for same Department, Category, Item and Tunch, Design No.';
                print json_encode($return);
                exit;
            }

            $fp = fopen($_FILES['userfile']['tmp_name'], 'r') or die("can't open file");
            $row_count = 0;
            while ($csv_line = fgetcsv($fp, 1024)) {

                $row_count++;
                if ($row_count == 1) { // Skip Heading row
                    continue;
                }

                $department_id = $this->crud->get_column_value_by_id('account', 'account_id', array('account_name' => trim($csv_line[0]), 'account_group_id' => DEPARTMENT_GROUP));
                if (empty($department_id)) {
                    $insert_new = array();
                    $insert_new['account_name'] = trim($csv_line[0]);
                    $insert_new['account_group_id'] = DEPARTMENT_GROUP;
                    $insert_new['created_at'] = $this->now_time;
                    $insert_new['created_by'] = $this->logged_in_id;
                    $insert_new['updated_at'] = $this->now_time;
                    $insert_new['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('account', $insert_new);
                    $department_id = $this->db->insert_id();
                }
                $category_id = $this->crud->get_column_value_by_id('category', 'category_id', array('category_name' => trim($csv_line[2]), 'category_group_id' => trim($csv_line[1])));
                if (empty($category_id)) {
                    $insert_new = array();
                    $insert_new['category_name'] = trim($csv_line[2]);
                    $insert_new['category_group_id'] = trim($csv_line[1]);
                    $insert_new['created_at'] = $this->now_time;
                    $insert_new['created_by'] = $this->logged_in_id;
                    $insert_new['updated_at'] = $this->now_time;
                    $insert_new['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('category', $insert_new);
                    $category_id = $this->db->insert_id();
                }
                $item_id = $this->crud->get_column_value_by_id('item_master', 'item_id', array('item_name' => trim($csv_line[3])));
                if (empty($item_id)) {
                    $insert_new = array();
                    $insert_new['item_name'] = trim($csv_line[3]);
                    $insert_new['category_id'] = $category_id;
                    $insert_new['min_order_qty'] = '0';
                    $insert_new['default_wastage'] = '0';
                    $insert_new['st_default_wastage'] = '0';
                    $insert_new['stock_method'] = (trim($csv_line[10]) == 'Item Wise') ? STOCK_METHOD_ITEM_WISE : STOCK_METHOD_DEFAULT;
                    $insert_new['created_at'] = $this->now_time;
                    $insert_new['created_by'] = $this->logged_in_id;
                    $insert_new['updated_at'] = $this->now_time;
                    $insert_new['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('item_master', $insert_new);
                    $item_id = $this->db->insert_id();
                }
                $tunch = $this->crud->get_column_value_by_id('carat', 'purity', array('purity' => trim($csv_line[7])));
                if (empty($tunch)) {
                    $insert_new = array();
                    $insert_new['purity'] = trim($csv_line[7]);
                    $insert_new['created_at'] = $this->now_time;
                    $insert_new['created_by'] = $this->logged_in_id;
                    $insert_new['updated_at'] = $this->now_time;
                    $insert_new['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('carat', $insert_new);
                    $tunch = trim($csv_line[7]);
                }

                $insert_new = array();
                $insert_new['department_id'] = $department_id;
                $insert_new['category_id'] = $category_id;
                $insert_new['item_id'] = $item_id;
                $insert_new['tunch'] = $tunch;
                $insert_new['grwt'] = number_format((float) trim($csv_line[4]), '3', '.', '');
                $insert_new['less'] = number_format((float) trim($csv_line[5]), '3', '.', '');
                $insert_new['ntwt'] = number_format((float) trim($csv_line[6]), '3', '.', '');
                $fine = $insert_new['ntwt'] * $insert_new['tunch'] / 100;
                $insert_new['fine'] = number_format((float) $fine, '3', '.', '');
                $insert_new['design_no'] = trim($csv_line[8]);
                $insert_new['rfid_number'] = trim($csv_line[9]);
                $insert_new['opening_pcs'] = trim($csv_line[11]);
                $insert_new['created_at'] = $this->now_time;
                $insert_new['created_by'] = $this->logged_in_id;
                $insert_new['updated_at'] = $this->now_time;
                $insert_new['updated_by'] = $this->logged_in_id;
                $this->crud->insert('opening_stock', $insert_new);
                $opening_stock_id = $this->db->insert_id();

                unset($insert_new['design_no']);
                unset($insert_new['rfid_number']);
                unset($insert_new['opening_pcs']);

                $item_stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $item_id));
                if ($item_stock_method == STOCK_METHOD_ITEM_WISE) {
                    $insert_new['purchase_sell_item_id'] = $opening_stock_id;
                    $insert_new['stock_type'] = STOCK_TYPE_OPENING_STOCK_ID;
                    $this->crud->insert('item_stock', $insert_new);
                } else {
                    $where_opening = array('department_id' => $insert_new['department_id'], 'category_id' => $insert_new['category_id'], 'item_id' => $insert_new['item_id'], 'tunch' => $insert_new['tunch']);
                    $exist_opening = $this->crud->get_row_by_id('item_stock', $where_opening);
                    if (!empty($exist_opening)) {
                        $exist_opening = $exist_opening[0];
                        $current_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $insert_new['grwt'], '3', '.', '');
                        $current_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $insert_new['less'], '3', '.', '');
                        $current_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $insert_new['ntwt'], '3', '.', '');
                        $current_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $insert_new['fine'], '3', '.', '');
                        $update_item_stock = array();
                        $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                        $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                        $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                        $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                        $update_item_stock['updated_at'] = $this->now_time;
                        $update_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_opening->item_stock_id));
                    } else {
                        $this->crud->insert('item_stock', $insert_new);
                    }
                }
            }

            fclose($fp) or die("can't close file");
            $return['success'] = "Added";
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Opening Data Successfully Imported');
            print json_encode($return);
            exit;
        }
    }

    function get_import_bank_statement_data() {
        $data = array();
        $post_data = $this->input->post();
        $fp = fopen($_FILES['ibs_file']['tmp_name'], 'r') or die("can't open file");
        $cnt = 0;
        $ibs_data = array();
        $statement_title = '';
        $get_data_result = array();
        while ($csv_line = fgetcsv($fp, 1024, '|')) {
            
            $cnt++;
//            echo "<pre>"; print_r($csv_line);
            if ($cnt == 6) {
                $date_range = explode('range', $csv_line[0]);
                $date_range = explode('to', $date_range[1]);
                $from_date = trim($date_range[0]);
                $to_date = str_replace('.', '', trim($date_range[1]));
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date = date('Y-m-d', strtotime($to_date));

                $get_data_sql = "SELECT j.`journal_id`, j.`journal_date`, jd.`jd_id`, jd.`type`, jd.`amount` FROM `journal` j"
                        . " JOIN `journal_details` jd ON jd.`journal_id` = j.`journal_id`"
                        . " WHERE j.`journal_date` >='" . $from_date . "' AND j.`journal_date` <='" . $to_date . "' AND jd.`account_id` = '" . $post_data['ibs_bank_id'] . "' ";
                $get_data_result = $this->crud->getFromSQLArray($get_data_sql);
//                print_r($get_data_result); exit;
            }
            if ($cnt == 1 || $cnt == 2 || $cnt == 3 || $cnt == 4 || $cnt == 7 || $cnt == 8 || $cnt == 9) {
                continue;
            }
            if ($cnt == 5 ) {
                $statement_title .= $csv_line[0] . '<br>';
            }
            if (isset($csv_line[2]) && !empty($csv_line[2]) && trim($csv_line[5]) != 'B/F') {
                
                $row = array();
                $journal_date = trim($csv_line[2]);
                $journal_date = date('Y-m-d', strtotime($journal_date));
                $ibs_cr_amount = '0.00';
                $ibs_cr_amount_display = '0.00';
                $ibs_dr_amount = '0.00';
                $ibs_dr_amount_display = '0.00';
                $amt = preg_replace("/(\,)/", "", trim($csv_line[7]));
                $amt = number_format((float) $amt, '2', '.', '');
                if($csv_line[6] == 'CR'){
                    $ibs_cr_amount = (Float) $amt;
                    $ibs_cr_amount_display = $amt;
                }
                if($csv_line[6] == 'DR'){
                    $ibs_dr_amount = (Float) $amt;
                    $ibs_dr_amount_display = $amt;
                }
                if ($ibs_dr_amount != '0.00') {
                    $type = 1;
                    $amount = $ibs_dr_amount;
                } else {
                    $type = 2;
                    $amount = $ibs_cr_amount;
                }
                $search_result = $this->applib->multi_array_search($get_data_result, array('journal_date' => $journal_date, 'amount' => $amount));
                if (!empty($search_result)) {
                    $get_data_key = $search_result[0];
                    $get_sql = "SELECT * FROM `journal_details`"
                            . " WHERE `journal_id` ='" . $get_data_result[$get_data_key]['journal_id'] . "' AND `account_id` !='" . $post_data['ibs_bank_id'] . "' ";
                    $get_result = $this->crud->getFromSQLArray($get_sql);
                    if (!empty($get_result)) {
                        $row['jd_id'] = $get_result[0]['jd_id'];
                        $row['account_id'] = $get_result[0]['account_id'];
                        unset($get_data_result[$get_data_key]);
                    } else {
                        $row['jd_id'] = '0';
                        $row['account_id'] = '0';
                    }
                } else {
                    $row['jd_id'] = '0';
                    $row['account_id'] = '0';
                }

                $row['trans_id'] = trim($csv_line[1]);
                $row['journal_date'] = trim($csv_line[2]);
                $row['ibs_tran_particular'] = trim($csv_line[5]);
                $row['cheque_no'] = trim($csv_line[4]);
                $row['ibs_inst_num'] = '';
                $row['ibs_dr_amount'] = $ibs_dr_amount_display;
                $row['ibs_cr_amount'] = $ibs_cr_amount_display;
                $row['ibs_deposit_branch'] = '';
                $ibs_data[] = $row;
            } else {
//                if (isset($csv_line[5]) && trim($csv_line[5]) == 'B/F') {
//                    $statement_title .= $csv_line[0] . ' | ' . $csv_line[1] . ' | ' . $csv_line[2] . ' | ' . $csv_line[3] . ' | ' . $csv_line[4] . ' | ' . $csv_line[5] . ' | ' . $csv_line[6] . ' | ' . $csv_line[7] . '<br>';
//                } else {
//                    $statement_title .= $csv_line[0] . '<br>';
//                }
            }
        }
        // Not in Import Bank Statement, But it's in Database
        if (!empty($get_data_result)) {
            foreach ($get_data_result as $get_data_row) {
                $get_sql = "SELECT * FROM `journal_details`"
                        . " WHERE `journal_id` ='" . $get_data_row['journal_id'] . "' AND `account_id` !='" . $post_data['ibs_bank_id'] . "' ";
                $get_result = $this->crud->getFromSQLArray($get_sql);
                if (!empty($get_result)) {
                    $row = array();
                    $row['journal_id'] = $get_result[0]['journal_id'];
                    $row['account_id'] = $get_result[0]['account_id'];
                    $row['journal_date'] = $get_data_row['journal_date'];
                    $row['ibs_tran_particular'] = 'This is not in Bank Statement, But in our Data base, Delete me';
                    $row['ibs_inst_num'] = '';
                    if ($get_result[0]['type'] == 1) {
                        $row['ibs_dr_amount'] = $get_result[0]['amount'];
                        $row['ibs_cr_amount'] = '0.00';
                    } else {
                        $row['ibs_dr_amount'] = '0.00';
                        $row['ibs_cr_amount'] = $get_result[0]['amount'];
                    }
                    $row['ibs_deposit_branch'] = '';
                    $ibs_data[] = $row;
                }
            }
        }

        $data['ibs_data'] = $ibs_data;
        $data['statement_title'] = $statement_title;
        fclose($fp) or die("can't close file");
        echo json_encode($data);
        exit;
    }

    function save_import_bank_statement_data() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;

        if (isset($post_data['ibs_account_id'])) {
            foreach ($post_data['ibs_account_id'] as $key => $value) {
                if (is_null($value) || $value == '') {
                    unset($post_data['ibs_account_id'][$key]);
                }
            }
        }
        $ibs_account_id_count = isset($post_data['ibs_account_id']) ? count($post_data['ibs_account_id']) : 0;
        $journal_date_count = count($post_data['journal_date']);
        if (!empty($post_data['ibs_account_id']) && $ibs_account_id_count == $journal_date_count) {
            $jd_ids = $post_data['jd_id'];
            foreach ($jd_ids as $key => $jd_id) {
                $ibs_account_id = $post_data['ibs_account_id'][$key];

                if ($jd_id != '0') {

                    $get_sql = "SELECT * FROM `journal_details` WHERE `jd_id` ='" . $jd_id . "' ";
                    $get_result = $this->crud->getFromSQLArray($get_sql);
                    if (!empty($get_result)) {
                        if ($ibs_account_id != $get_result[0]['account_id']) {
                            // Revert amount effect in old account
                            // Increase and Decrease amount in new selected Account
                            if ($get_result[0]['type'] == 1) {
                                $this->applib->update_account_balance_decrease($get_result[0]['account_id'], '', '', $get_result[0]['amount']);
                                $this->applib->update_account_balance_increase($ibs_account_id, '', '', $get_result[0]['amount']);
                            } else {
                                $this->applib->update_account_balance_increase($get_result[0]['account_id'], '', '', $get_result[0]['amount']);
                                $this->applib->update_account_balance_decrease($ibs_account_id, '', '', $get_result[0]['amount']);
                            }
                            $this->crud->update('journal_details', array('account_id' => $ibs_account_id), array('jd_id' => $jd_id));
                        }
                    }
                    $return['success'] = "Added";
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'Data Saved');
                } else {

                    // Journal Master Entry
                    $insert_arr = array();
                    $insert_arr['department_id'] = $post_data['ibs_department_id'];
                    $insert_arr['journal_date'] = date('Y-m-d', strtotime($post_data['journal_date'][$key]));
                    $insert_arr['ibs_tran_particular'] = $post_data['ibs_tran_particular'][$key];
                    $insert_arr['ibs_inst_num'] = $post_data['ibs_inst_num'][$key];
//                    $insert_arr['ibs_deposit_branch'] = $post_data['ibs_deposit_branch'][$key];
                    $insert_arr['created_at'] = $this->now_time;
                    $insert_arr['created_by'] = $this->logged_in_id;
                    $insert_arr['updated_at'] = $this->now_time;
                    $insert_arr['updated_by'] = $this->logged_in_id;
                    $result = $this->crud->insert('journal', $insert_arr);
                    $journal_id = $this->db->insert_id();

                    if ($result) {
                        $return['success'] = "Added";
                        $this->session->set_flashdata('success', true);
                        $this->session->set_flashdata('message', 'Data Saved');

                        $ibs_dr_amount = (Float) $post_data['ibs_dr_amount'][$key];
                        $ibs_cr_amount = (Float) $post_data['ibs_cr_amount'][$key];
                        if (!empty($ibs_dr_amount)) {
                            $type = 1;
                            $amount = $ibs_dr_amount;
                        } else {
                            $type = 2;
                            $amount = $ibs_cr_amount;
                        }

                        // Journal Account Entry
                        $insert_item = array();
                        $insert_item['journal_id'] = $journal_id;
                        $insert_item['account_id'] = $ibs_account_id;
                        $insert_item['amount'] = $amount;
                        $insert_item['type'] = $type;
                        $insert_item['narration'] = '';
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('journal_details', $insert_item);
                        // Increase and Decrease amount in Account
                        if ($type == 1) {
                            $this->applib->update_account_balance_increase($ibs_account_id, '', '', $amount);
                            $b_type = 2;
                        } else {
                            $this->applib->update_account_balance_decrease($ibs_account_id, '', '', $amount);
                            $b_type = 1;
                        }

                        // Journal Bank Entry
                        $insert_item = array();
                        $insert_item['journal_id'] = $journal_id;
                        $insert_item['account_id'] = $post_data['ibs_bank_id'];
                        $insert_item['amount'] = $amount;
                        $insert_item['type'] = $b_type;
                        $insert_item['narration'] = '';
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('journal_details', $insert_item);
                        // Increase and Decrease amount in Account
                        if ($b_type == 1) {
                            $this->applib->update_account_balance_increase($post_data['ibs_bank_id'], '', '', $amount);
                        } else {
                            $this->applib->update_account_balance_decrease($post_data['ibs_bank_id'], '', '', $amount);
                        }
                    }
                }
            }
        } else {
            $return['error'] = "Error";
        }
        echo json_encode($return);
        exit;
    }

}
