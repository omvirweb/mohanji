<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_chain extends CI_Controller {

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
        $this->zero_value = 0;
    }

    function operation($operation_id = '') {
        $data = array();

        if ($this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "edit") || $this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, 'add')) {
            $user_department = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            $data['user_department'] = $user_department;
            $user_worker = $this->crud->get_row_by_id('account', array('account_group_id' => WORKER_GROUP));
            $data['user_worker'] = $user_worker;
            if (isset($operation_id) && !empty($operation_id)) {
                if ($this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "edit")) {
                    $op_data = $this->crud->get_row_by_id('machine_chain_operation', array('operation_id' => $operation_id));
                    $op_data = $op_data[0];
                    $op_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $op_data->created_by));
                    if ($op_data->created_by != $op_data->updated_by) {
                        $op_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $op_data->updated_by));
                    } else {
                        $op_data->updated_by_name = $op_data->created_by_name;
                    }
                    $data['op_data'] = $op_data;

                    $department = $this->crud->getFromSQL("SELECT department_id FROM machine_chain_operation_department WHERE operation_id= " . $operation_id . " ");
                    $department_ids = array();
                    if (!empty($department)) {
                        foreach ($department as $dp) {
                            $department_ids[] = $dp->department_id;
                        }
                    }
                    $data['department'] = $department_ids;

                    $w_data = $this->crud->getFromSQL("SELECT worker_id FROM machine_chain_operation_worker WHERE operation_id= " . $operation_id . " ");
                    $w_name = array();
                    if (!empty($w_data)) {
                        foreach ($w_data as $w) {
                            $w_name[] = $w->worker_id;
                        }
                    }
                    $data['worker'] = $w_name;

                    set_page('manufacture/machine_chain/machine_chain_operation', $data);
                } else {
                    $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                    redirect("/");
                }
            } else {
                set_page('manufacture/machine_chain/machine_chain_operation', $data);
            }
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function save_operation() {
//        echo "<pre>"; print_r($_POST);  exit;
        $return = array();
        $post_data = $this->input->post();
        $insert_arr = array();
        $insert_arr['operation_name'] = $post_data['operation_name'];
        $insert_arr['sequence_no'] = $post_data['sequence_no'];
        $insert_arr['allow_only_1_order_item'] = (isset($post_data['allow_only_1_order_item']) ? 1 : 0);
        $insert_arr['direct_issue_allow'] = (isset($post_data['direct_issue_allow']) ? 1 : 0);
        $insert_arr['calculate_button'] = (isset($post_data['calculate_button']) ? 1 : 0);
        $insert_arr['use_selected_tunch'] = (isset($post_data['use_selected_tunch']) ? 1 : 0);
        $insert_arr['issue_change_actual_tunch_allow'] = (isset($post_data['issue_change_actual_tunch_allow']) ? 1 : 0);
        $insert_arr['receive_change_actual_tunch_allow'] = (isset($post_data['receive_change_actual_tunch_allow']) ? 1 : 0);
        $insert_arr['remark'] = $post_data['remark'];
        if (isset($post_data['operation_id']) && !empty($post_data['operation_id'])) {
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('machine_chain_operation', $insert_arr, array('operation_id' => $post_data['operation_id']));
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Machine Chain Operation Updated Successfully');

                if (!empty($post_data['department_id'])) {
                    $department = $this->crud->getFromSQL("SELECT department_id FROM machine_chain_operation_department WHERE operation_id= " . $post_data['operation_id'] . " ");
                    $old_ids = array();
                    $new_ids = array();
                    if (!empty($department)) {
                        foreach ($department as $depart) {
                            $old_ids[] = $depart->department_id;
                        }
                    }
                    foreach ($post_data['department_id'] as $department_id) {
                        $old_data = $this->crud->get_row_by_id('machine_chain_operation_department', array('operation_id' => $post_data['operation_id'], 'department_id' => $department_id));
                        if (!empty($old_data)) {
                            $dp_arr = array();
                            $dp_arr['updated_at'] = $this->now_time;
                            $dp_arr['updated_by'] = $this->logged_in_id;
                            $this->crud->update('machine_chain_operation_department', $dp_arr, array('operation_id' => $post_data['operation_id'], 'department_id' => $department_id));
                            $new_ids[] = $department_id;
                        } else {
                            $dp_arr = array();
                            $dp_arr['operation_id'] = $post_data['operation_id'];
                            $dp_arr['department_id'] = $department_id;
                            $dp_arr['created_at'] = $this->now_time;
                            $dp_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('machine_chain_operation_department', $dp_arr);
                        }
                    }
                    $ids_for_delete = array_diff($old_ids, $new_ids);
                    if (!empty($ids_for_delete)) {
                        foreach ($ids_for_delete as $id_for_delete) {
                            $this->crud->delete('machine_chain_operation_department', array('operation_id' => $post_data['operation_id'], 'department_id' => $id_for_delete));
                        }
                    }
                }

                if (!empty($post_data['worker_id'])) {
                    $worker = $this->crud->getFromSQL("SELECT worker_id FROM machine_chain_operation_worker WHERE operation_id= " . $post_data['operation_id'] . " ");
                    $w_old_ids = array();
                    $w_new_ids = array();
                    if (!empty($department)) {
                        foreach ($worker as $work) {
                            $w_old_ids[] = $work->worker_id;
                        }
                    }
                    foreach ($post_data['worker_id'] as $worker_id) {
                        $old_data = $this->crud->get_row_by_id('machine_chain_operation_worker', array('operation_id' => $post_data['operation_id'], 'worker_id' => $worker_id));
                        if (!empty($old_data)) {
                            $dp_arr = array();
                            $dp_arr['updated_at'] = $this->now_time;
                            $dp_arr['updated_by'] = $this->logged_in_id;
                            $this->crud->update('machine_chain_operation_worker', $dp_arr, array('operation_id' => $post_data['operation_id'], 'worker_id' => $worker_id));
                            $w_new_ids[] = $worker_id;
                        } else {
                            $dp_arr = array();
                            $dp_arr['operation_id'] = $post_data['operation_id'];
                            $dp_arr['worker_id'] = $worker_id;
                            $dp_arr['created_at'] = $this->now_time;
                            $dp_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('machine_chain_operation_worker', $dp_arr);
                        }
                    }
                    $ids_for_delete = array();
                    $ids_for_delete = array_diff($w_old_ids, $w_new_ids);
                    if (!empty($ids_for_delete)) {
                        foreach ($ids_for_delete as $id_for_delete) {
                            $this->crud->delete('machine_chain_operation_worker', array('operation_id' => $post_data['operation_id'], 'worker_id' => $id_for_delete));
                        }
                    }
                }
            }
        } else {
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('machine_chain_operation', $insert_arr);
            $last_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Machine Chain Operation Added Successfully');

                if (!empty($post_data['department_id'])) {
                    foreach ($post_data['department_id'] as $department_id) {
                        $dp_arr = array();
                        $dp_arr['operation_id'] = $last_id;
                        $dp_arr['department_id'] = $department_id;
                        $dp_arr['created_at'] = $this->now_time;
                        $dp_arr['created_by'] = $this->logged_in_id;
                        $this->crud->insert('machine_chain_operation_department', $dp_arr);
                    }
                }
                if (!empty($post_data['department_id'])) {
                    foreach ($post_data['worker_id'] as $worker_id) {
                        $w_arr = array();
                        $w_arr['operation_id'] = $last_id;
                        $w_arr['worker_id'] = $worker_id;
                        $w_arr['created_at'] = $this->now_time;
                        $w_arr['created_by'] = $this->logged_in_id;
                        $this->crud->insert('machine_chain_operation_worker', $w_arr);
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function operation_list() {
        if ($this->applib->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "view")) {
            $data = array();
            set_page('manufacture/machine_chain/machine_chain_operation_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function operation_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'machine_chain_operation mco';
        $config['select'] = 'mco.*, IF(mco.allow_only_1_order_item = 0,"No","Yes") AS allow_only_1_order_item, IF(mco.direct_issue_allow = 0,"No","Yes") AS direct_issue_allow, IF(mco.calculate_button = 0,"No","Yes") AS calculate_button';
        $config['column_search'] = array('mco.operation_name', 'mco.sequence_no');
        $config['column_order'] = array(null, 'mco.operation_name',null,null,'mco.sequence_no');
        $config['order'] = array('mco.operation_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(MACHINE_CHAIN_OPERATION_MODULE_ID, "edit");

        foreach ($list as $operation) {
            $row = array();
            $action = '';
            if ($role_edit) {
                $action .= '<a href="' . base_url("machine_chain/operation/" . $operation->operation_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if ($role_delete) {
                if ($operation->operation_id != MACHINE_CHAIN_OPERATION_SOLDING_ID) {
                    $action .= '<a href="javascript:void(0);" class="delete_operation" data-href="' . base_url('machine_chain/delete_operation/' . $operation->operation_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            }
            $row[] = $action;
            $row[] = $operation->operation_name;
            $dp_ids = $this->crud->getFromSQL("SELECT a.account_name FROM machine_chain_operation_department od LEFT JOIN account as a ON od.department_id = a.account_id WHERE od.operation_id = " . $operation->operation_id . " ");
            $dp_name = '';
            if (!empty($dp_ids)) {
                foreach ($dp_ids as $dp) {
                    $dp_name .= $dp->account_name . ', ';
                }
            }
            $row[] = $dp_name;

            $w_ids = $this->crud->getFromSQL("SELECT a.account_name FROM machine_chain_operation_worker od LEFT JOIN account as a ON od.worker_id = a.account_id WHERE od.operation_id = " . $operation->operation_id . " ");
            $w_name = '';
            if (!empty($w_ids)) {
                foreach ($w_ids as $w) {
                    $w_name .= $w->account_name . ', ';
                }
            }
            $row[] = $w_name;
            $row[] = $operation->sequence_no;
            $row[] = $operation->allow_only_1_order_item;
            $row[] = $operation->direct_issue_allow;
            $row[] = $operation->calculate_button;
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

    function delete_operation($id = '') {
        $return = $this->crud->delete('machine_chain_operation', array('operation_id' => $id));
        if (isset($return['success'])) {
            $where_array = array('operation_id' => $id);
            $this->crud->delete('machine_chain_operation_department', $where_array);
            $this->crud->delete('machine_chain_operation_worker', $where_array);
        }
        print json_encode($return);
        exit;
    }

    function get_machine_chain_operation_detail($operation_id) {
        $data = $this->crud->get_data_row_by_id('machine_chain_operation', 'operation_id', $operation_id);
        print json_encode($data);
        exit;
    }

    function new_order_item_datatable($department_id = '', $machine_chain_id = '', $forwarded_from_mc_id = '') {
        $return = array();
        $post_data = $this->input->post();
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,o.order_date,o.order_no,o.delivery_date,a.account_name,a.account_mobile,pm.account_name AS process_name,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'new_order o', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.party_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = o.process_id', 'join_type' => 'left');
        $config['custom_where'] = ' 1 ';
        if (!empty($department_id)) {
//            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $department_id);
            $config['custom_where'] .= ' AND o.process_id = ' . $department_id . ' ';
        }
        $checked_order_items = array();
        if(!empty($forwarded_from_mc_id)){
            $machine_chain_id = $forwarded_from_mc_id;
        }
        $machine_chain_order_items = $this->crud->get_row_by_id('machine_chain_order_items', array('machine_chain_id' => $machine_chain_id));
        if (!empty($machine_chain_order_items)) {
            foreach ($machine_chain_order_items as $order_item) {
                $checked_order_items[] = $order_item->order_lot_item_id;
            }
        }
        $config['custom_where'] .= ' AND li.item_status_id = 1 ';
        if (!empty($checked_order_items)) {
            $checked_order_items = implode(',', $checked_order_items);
            if(!empty($forwarded_from_mc_id)){
                $config['custom_where'] .= ' AND (li.order_lot_item_id IN(' . $checked_order_items . '))';
            } else {
                $config['custom_where'] .= ' OR (li.order_lot_item_id IN(' . $checked_order_items . '))';
            }
        }
//        $config['wheres'][] = array('column_name' => 'li.item_status_id', 'column_value' => '1');
        $config['order'] = array('li.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo '<pre>'; print_r($list); exit;
//        echo $this->db->last_query(); exit;
        $total_purity = 0;
        $total_weight = 0;
        $total_pcs = 0;
        foreach ($list as $lot_detail) {
            $row = array();
            $row['order_id'] = $lot_detail->order_id;
            $row['item_id'] = $lot_detail->item_id;
            $row['item_name'] = $lot_detail->item_name;
            $row['category_name'] = $lot_detail->category_name;
            $row['account_name'] = $lot_detail->account_name . ' - ' . $lot_detail->account_mobile;
            $row['order_no'] = $lot_detail->order_no;
            $row['order_date'] = (!empty(strtotime($lot_detail->order_date))) ? date('d-m-Y', strtotime($lot_detail->order_date)) : '';
            $row['delivery_date'] = (!empty(strtotime($lot_detail->delivery_date))) ? date('d-m-Y', strtotime($lot_detail->delivery_date)) : '';
            $row['purity'] = $lot_detail->purity;
            $total_purity = $total_purity + $lot_detail->purity;
            $row['weight'] = number_format($lot_detail->weight, 3, '.', '');
            $total_weight = $total_weight + number_format($lot_detail->weight, 3, '.', '');
            $row['pcs'] = $lot_detail->pcs;
            $row['order_lot_item_id'] = $lot_detail->order_lot_item_id;
            $total_pcs = $total_pcs + $lot_detail->pcs;
            $data[] = $row;
        }
//        echo '<pre>'; print_r($data); exit;
        $return['order_items'] = $data;
        $return['total_weight'] = number_format($total_weight, 3, '.', '');
        $return['total_pcs'] = $total_pcs;
        echo json_encode($return);
    }
    
    function get_selected_order_items() {
        $return = array();
        $post_data = $this->input->post();
        $config['table'] = 'order_lot_item li';
        $config['select'] = 'li.*,o.order_date,o.order_no,o.delivery_date,a.account_name,a.account_mobile,pm.account_name AS process_name,im.item_name,c.purity,cat.category_name,im.design_no,im.die_no,os.status';
        $config['joins'][] = array('join_table' => 'order_status os', 'join_by' => 'os.order_status_id = li.item_status_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = li.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = li.touch_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category cat', 'join_by' => 'cat.category_id = li.category_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'new_order o', 'join_by' => 'li.order_id = o.order_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = o.party_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = o.process_id', 'join_type' => 'left');
        $config['custom_where'] = ' 1 ';
        if (!empty($department_id)) {
//            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $department_id);
            $config['custom_where'] .= ' AND o.process_id = ' . $department_id . ' ';
        }
        $checked_order_items = array();
        if (!empty($post_data['checked_order_items'])) {
            foreach ($post_data['checked_order_items'] as $order_item) {
                $checked_order_items[] = $order_item['order_lot_item_id'];
            }
        }
        $config['custom_where'] .= ' AND li.item_status_id = 1 ';
        if (!empty($checked_order_items)) {
            $checked_order_items = implode(',', $checked_order_items);
            $config['custom_where'] .= ' AND (li.order_lot_item_id IN(' . $checked_order_items . '))';
        } else {
            $config['custom_where'] .= ' AND li.order_lot_item_id = -1';
        }
//        $config['wheres'][] = array('column_name' => 'li.item_status_id', 'column_value' => '1');
        $config['order'] = array('li.order_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo '<pre>'; print_r($list); exit;
        $total_purity = 0;
        $total_weight = 0;
        $total_pcs = 0;
        foreach ($list as $lot_detail) {
            $row = array();
            $row['order_id'] = $lot_detail->order_id;
            $row['item_id'] = $lot_detail->item_id;
            $row['item_name'] = $lot_detail->item_name;
            $row['category_name'] = $lot_detail->category_name;
            $row['account_name'] = $lot_detail->account_name . ' - ' . $lot_detail->account_mobile;
            $row['order_no'] = $lot_detail->order_no;
            $row['order_date'] = (!empty(strtotime($lot_detail->order_date))) ? date('d-m-Y', strtotime($lot_detail->order_date)) : '';
            $row['delivery_date'] = (!empty(strtotime($lot_detail->delivery_date))) ? date('d-m-Y', strtotime($lot_detail->delivery_date)) : '';
            $row['purity'] = $lot_detail->purity;
            $total_purity = $total_purity + $lot_detail->purity;
            $row['weight'] = number_format($lot_detail->weight, 3, '.', '');
            $total_weight = $total_weight + number_format($lot_detail->weight, 3, '.', '');
            $row['pcs'] = $lot_detail->pcs;
            $row['order_lot_item_id'] = $lot_detail->order_lot_item_id;
            $total_pcs = $total_pcs + $lot_detail->pcs;
            $data[] = $row;
        }
//        echo '<pre>'; print_r($data); exit;
        $return['order_items'] = $data;
        $return['total_weight'] = number_format($total_weight, 3, '.', '');
        $return['total_pcs'] = $total_pcs;
        echo json_encode($return);
    }

    function machine_chain($machine_chain_id = '') {
        $data = array();
        $post_data = $this->input->post();
        $machine_chain_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if (!empty($machine_chain_id)) {
            if ($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID, "edit")) {
                $machine_chain_data = $this->crud->get_row_by_id('machine_chain', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_order_items = $this->crud->get_row_by_id('machine_chain_order_items', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_details = $this->crud->get_row_by_id('machine_chain_details', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_data = $machine_chain_data[0];

                $machine_chain_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $machine_chain_data->created_by));
                if ($machine_chain_data->created_by != $machine_chain_data->updated_by) {
                    $machine_chain_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $machine_chain_data->updated_by));
                } else {
                    $machine_chain_data->updated_by_name = $machine_chain_data->created_by_name;
                }

                $machine_chain_data->entry_mode = 'edit';
                $data['machine_chain_data'] = $machine_chain_data;
                $data['machine_chain_operation_data'] = $this->crud->get_data_row_by_id('machine_chain_operation', 'operation_id', $machine_chain_data->operation_id);

                $checked_order_items = array();
                if (!empty($machine_chain_order_items)) {
                    foreach ($machine_chain_order_items as $order_item) {
                        $checked_order_items[] = json_encode($order_item);
                    }
                }
                $data['checked_order_items'] = implode(',', $checked_order_items);

                $lineitems = array();
                foreach ($machine_chain_details as $detail) {

                    $machine_chain_detail->machine_chain_item_delete = 'allow';
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $detail->item_id));
                    if ($stock_method == STOCK_METHOD_ITEM_WISE) {

                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_sells)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_sells)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_transfer)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_transfer)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_issue_receive)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_issue_receive)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if(!empty($item_manu_hand_made)){
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if(!empty($item_manu_hand_made)){
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_machine_chain = $this->crud->get_row_by_id('machine_chain_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_machine_chain)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
                        $item_machine_chain = $this->crud->get_row_by_id('machine_chain_details', array('purchase_sell_item_id' => $detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_machine_chain)) {
                            $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                        }
//                        echo '<pre>'. $this->db->last_query(); exit;
//                        echo '<pre>'; print_r($machine_chain_detail->machine_chain_item_delete); exit;
                        $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="' . $detail->machine_chain_detail_id . '" AND s.process_id = "' . $machine_chain_data->department_id . '"');
                        $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="' . $detail->machine_chain_detail_id . '" AND s.from_department = "' . $machine_chain_data->department_id . '"');
                        $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="' . $detail->machine_chain_detail_id . '" AND ir.department_id = "' . $machine_chain_data->department_id . '"');
                        $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$detail->machine_chain_detail_id.'" AND mhm.department_id = "'.$machine_chain_data->department_id.'"');
                        $machine_chain_info = $this->crud->getFromSQL('SELECT SUM(machine_chain_detail.weight) as total_grwt FROM machine_chain_details machine_chain_detail JOIN machine_chain machine_chain ON machine_chain.machine_chain_id = machine_chain_detail.machine_chain_id WHERE machine_chain_detail.purchase_sell_item_id ="' . $detail->machine_chain_detail_id . '" AND machine_chain.department_id = "' . $machine_chain_data->department_id . '"');
                        $machine_chain_detail->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt + $machine_chain_info[0]->total_grwt;
                    } else {
                        if ($data['without_purchase_sell_allow'] == '1') {
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_machine_chain_grwt = $this->crud->get_total_machine_chain_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt+ $total_manu_hand_made_grwt + $total_machine_chain_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $machine_chain_detail->total_grwt_sell = 0;
                            if (!empty($total_sell_grwt)) {
                                $purchase_items = $this->crud->get_purchase_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $machine_chain_receive_items = $this->crud->get_machine_chain_receive_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($machine_chain_data->department_id, $detail->category_id, $detail->item_id);
                                $receive_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $machine_chain_receive_items, $other_purchase_items);
                                //                            echo '<pre>'; print_r($receive_delete_array); exit;
                                uasort($receive_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
                                //                            print_r($receive_delete_array); exit;
                                $purchase_grwt = 0;
                                $first_check_receive_grwt = 0;

                                /*foreach ($receive_delete_array as $receive_item) {
                                    $purchase_grwt = $purchase_grwt + $receive_item->grwt;
                                    if ($purchase_grwt >= $total_sell_grwt && $first_check_receive_grwt == 0) {
                                        if ($receive_item->type == 'MC_R') {
                                            $used_lineitem_ids[] = $receive_item->machine_chain_detail_id;
                                            if ($detail->machine_chain_detail_id == $receive_item->machine_chain_detail_id) {
                                                $machine_chain_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $receive_item->grwt;
                                            }
                                        }
                                        $first_check_receive_grwt = 1;
                                    } else if ($purchase_grwt <= $total_sell_grwt) {
                                        if ($receive_item->type == 'MC_R') {
                                            $used_lineitem_ids[] = $receive_item->machine_chain_detail_id;
                                            if ($detail->machine_chain_detail_id == $receive_item->machine_chain_detail_id) {
                                                $machine_chain_detail->total_grwt_sell = $receive_item->grwt;
                                            }
                                        }
                                    }
                                }*/
                            }
                            if (!empty($used_lineitem_ids) && in_array($detail->machine_chain_detail_id, $used_lineitem_ids)) {
                                $machine_chain_detail->machine_chain_item_delete = 'not_allow';
                            }
                        }
                    }
//                  
                    $detail_type = '';
                    if ($detail->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID) {
                        $detail_type = 'Issue Finish Work';
                    } else if ($detail->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {
                        $detail_type = 'Issue Scrap';
                    } else if ($detail->type_id == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID) {
                        $detail_type = 'Receive Finish Work';
                    } else if ($detail->type_id == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID) {
                        $detail_type = 'Receive Scrap';
                    }

                    $mcd_checked_order_items = $this->crud->get_row_by_id('machine_chain_detail_order_items', array('machine_chain_detail_id' => $detail->machine_chain_detail_id));
                    $mcd_checked_order_items = !empty($mcd_checked_order_items)?$mcd_checked_order_items:array();

                    $machine_chain_detail->mcd_checked_order_items = $mcd_checked_order_items;
                    $machine_chain_detail->type_name = $detail_type;
                    $machine_chain_detail->added_from_ifw_mcd_id = $detail->added_from_ifw_mcd_id;
                    $machine_chain_detail->forwarded_from_mcd_id = $detail->forwarded_from_mcd_id;
                    $machine_chain_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $machine_chain_detail->purity = $detail->tunch;
                    $machine_chain_detail->weight = $detail->weight;

                    $mcd_weight_data = $this->get_available_weight_for_forward($detail->machine_chain_detail_id);
                    $machine_chain_detail->forwarded_weight = $mcd_weight_data['forwarded_weight'];
                    $machine_chain_detail->available_weight_for_forward = $mcd_weight_data['available_weight_for_forward'];

                    if($machine_chain_detail->forwarded_from_mcd_id > 0) {
                        $parent_mcd_weight_data = $this->get_available_weight_for_forward($machine_chain_detail->forwarded_from_mcd_id);
                        $machine_chain_detail->parent_forwarded_weight = $parent_mcd_weight_data['forwarded_weight'];
                        $machine_chain_detail->parent_available_weight_for_forward = $parent_mcd_weight_data['available_weight_for_forward'];
                        $machine_chain_detail->weight_limit = $machine_chain_detail->weight + $machine_chain_detail->parent_available_weight_for_forward;
                    }
                    
                    $machine_chain_detail->grwt = $detail->weight;
                    $machine_chain_detail->less = $detail->less;
                    $machine_chain_detail->net_wt = $detail->net_wt;
                    $machine_chain_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $machine_chain_detail->real_actual_tunch = !empty($detail->real_actual_tunch) ? $detail->real_actual_tunch : 0;
                    $machine_chain_detail->fine = $detail->fine;
                    $machine_chain_detail->pcs = $detail->pcs;
                    $machine_chain_detail->machine_chain_detail_date = $detail->machine_chain_detail_date ? date('d-m-Y', strtotime($detail->machine_chain_detail_date)) : '';
                    $machine_chain_detail->machine_chain_detail_remark = $detail->machine_chain_detail_remark;
                    $machine_chain_detail->machine_chain_detail_id = $detail->machine_chain_detail_id;
                    $machine_chain_detail->type_id = $detail->type_id;
                    $machine_chain_detail->item_id = $detail->item_id;
                    $machine_chain_detail->touch_id = $detail->tunch;
                    $machine_chain_detail->wstg = '0';
                    $machine_chain_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $machine_chain_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $machine_chain_detail->stock_type = $detail->stock_type;
                    $machine_chain_detail->is_forwarded = $detail->is_forwarded;
                    $lineitems[] = json_encode($machine_chain_detail);
                }
                

                /*---- Forward to old ----*/
                if(isset($post_data['machine_chain_id']) && isset($post_data['forward_wt_arr'])) {
                    $forwarded_from_mc_id = $post_data['machine_chain_id'];
                    $forward_wt_arr = $post_data['forward_wt_arr'];

                    if(!empty($forward_wt_arr)) {
                        $f_machine_chain_details = array();
                        $forward_mcd_ids = array();
                        foreach ($forward_wt_arr as $mcd_id => $forward_wt) {
                            $forward_mcd_ids[] = $mcd_id;
                        }

                        $this->db->select("*");
                        $this->db->from('machine_chain_details');
                        $this->db->where_in('machine_chain_detail_id',$forward_mcd_ids);
                        $this->db->order_by('machine_chain_detail_id');
                        $query = $this->db->get();
                        if ($query->num_rows() > 0){
                            $f_machine_chain_details = $query->result();
                        }

                        $f_machine_chain_detail = new \stdClass();
                        foreach ($f_machine_chain_details as $detail) {
                            $forward_wt = $forward_wt_arr[$detail->machine_chain_detail_id];

                            $f_machine_chain_detail->machine_chain_item_delete = 'allow';
                            $f_machine_chain_detail->type_name = 'Issue Finish Work';
                            $f_machine_chain_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                            $f_machine_chain_detail->purity = $detail->tunch;
                            $f_machine_chain_detail->weight = $forward_wt;
                            $f_machine_chain_detail->grwt = $forward_wt;
                            $f_machine_chain_detail->less = $detail->less;
                            $net_wt = $forward_wt - $detail->less;
                            $f_machine_chain_detail->net_wt = $net_wt;
                            $f_machine_chain_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                            $f_machine_chain_detail->real_actual_tunch = !empty($detail->real_actual_tunch) ? $detail->real_actual_tunch : 0;
                            $fine = $net_wt * $detail->tunch / 100;
                            $f_machine_chain_detail->fine = number_format((float) $fine, '2', '.', '');
                            $f_machine_chain_detail->pcs = $detail->pcs;
                            $f_machine_chain_detail->machine_chain_detail_date = $detail->machine_chain_detail_date ? date('d-m-Y', strtotime($detail->machine_chain_detail_date)) : '';
                            $f_machine_chain_detail->machine_chain_detail_remark = $detail->machine_chain_detail_remark;
                            $f_machine_chain_detail->forwarded_from_mcd_id = $detail->machine_chain_detail_id;
                            $f_machine_chain_detail->type_id = MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID;
                            $f_machine_chain_detail->item_id = $detail->item_id;
                            $f_machine_chain_detail->touch_id = $detail->tunch;
                            $f_machine_chain_detail->wstg = '0';
                            $f_machine_chain_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                            $f_machine_chain_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                            $f_machine_chain_detail->stock_type = $detail->stock_type;
                            $lineitems[] = json_encode($f_machine_chain_detail);
                        }
                    }
                    /*echo "<pre>";
                    print_r($lineitems);
                    die();*/
                }
                /*----/Forward to old ----*/

                $data['machine_chain_detail'] = implode(',', $lineitems);
                set_page('manufacture/machine_chain/machine_chain', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID, "add")) {
                $lineitems = array();
                set_page('manufacture/machine_chain/machine_chain', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function get_available_weight_for_forward($machine_chain_detail_id) {
        $forwarded_weight = 0;
        $weight = 0;

        $this->db->select('weight');
        $this->db->from('machine_chain_details');
        $this->db->where('machine_chain_detail_id',$machine_chain_detail_id);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $weight = $query->row()->weight;
        }

        $this->db->select('ROUND(SUM(weight),3) as forwarded_weight');
        $this->db->from('machine_chain_details');
        $this->db->where('forwarded_from_mcd_id',$machine_chain_detail_id);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $forwarded_weight = $query->row()->forwarded_weight;
        }


        if($forwarded_weight > 0) {
            $available_weight_for_forward = $weight - $forwarded_weight;
            if($available_weight_for_forward < 0) {
                $available_weight_for_forward = 0;
            }
        } else {
            $available_weight_for_forward = $weight;
        }

        $forwarded_weight = number_format($forwarded_weight, 3, '.', '');
        $available_weight_for_forward = number_format($available_weight_for_forward, 3, '.', '');

        return array('weight' => $weight,'forwarded_weight' => $forwarded_weight,'available_weight_for_forward' => $available_weight_for_forward);
    }
    
    function machine_chain_forward_multiple($machine_chain_id = '', $machine_chain_detail_id = '', $forward_wt = '') {
        $post_data = $this->input->post();
        $machine_chain_id = isset($post_data['machine_chain_id'])?$post_data['machine_chain_id']:0;
        $forward_wt_arr = isset($post_data['forward_wt_arr'])?$post_data['forward_wt_arr']:array();
        $machine_chain_details = array();

        if(!empty($forward_wt_arr)) {
            $mcd_ids = array();
            foreach ($forward_wt_arr as $mcd_id => $forward_wt) {
                $mcd_ids[] = $mcd_id;
            }
            
            $this->db->select("*");
            $this->db->from('machine_chain_details');
            $this->db->where_in('machine_chain_detail_id',$mcd_ids);
            $this->db->order_by('machine_chain_detail_id');
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                $machine_chain_details = $query->result();
            }
        }

        $data = array();
        $machine_chain_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));

        if (!empty($machine_chain_id) && !empty($machine_chain_details)) {
            if ($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit")) {
                $machine_chain_data = $this->crud->get_row_by_id('machine_chain', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_order_items = $this->crud->get_row_by_id('machine_chain_order_items', array('machine_chain_id' => $machine_chain_id));

                $machine_chain_data = $machine_chain_data[0];
                $machine_chain_data->entry_mode = 'forward';
                $machine_chain_data->lott_complete = 0;
                $machine_chain_data->is_calculated = '0';
                $data['machine_chain_data'] = $machine_chain_data;
                
                $next_operation_data = $this->crud->get_next_machine_chain_operation_data($machine_chain_data->operation_id);
                if(!empty($next_operation_data)){
                    $machine_chain_data->operation_id = $next_operation_data->operation_id;
                    $data['machine_chain_operation_data'] = $next_operation_data;
                }

                $checked_order_items = array();
                if (!empty($machine_chain_order_items)) {
                    foreach ($machine_chain_order_items as $order_item) {
                        $checked_order_items[] = json_encode($order_item);
                    }
                }
                $data['checked_order_items'] = implode(',', $checked_order_items);

                $lineitems = array();
                foreach ($machine_chain_details as $detail) {
                    $forward_wt = $forward_wt_arr[$detail->machine_chain_detail_id];

                    $machine_chain_detail->machine_chain_item_delete = 'allow';
                    $machine_chain_detail->type_name = 'Issue Finish Work';
                    $machine_chain_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $machine_chain_detail->purity = $detail->tunch;
                    $machine_chain_detail->weight = $forward_wt;
                    $machine_chain_detail->grwt = $forward_wt;
                    $machine_chain_detail->less = $detail->less;
                    $net_wt = $forward_wt - $detail->less;
                    $machine_chain_detail->net_wt = $net_wt;
                    $machine_chain_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $machine_chain_detail->real_actual_tunch = !empty($detail->real_actual_tunch) ? $detail->real_actual_tunch : 0;
                    $fine = $net_wt * $detail->tunch / 100;
                    $machine_chain_detail->fine = number_format((float) $fine, '2', '.', '');
                    $machine_chain_detail->pcs = $detail->pcs;
                    $machine_chain_detail->machine_chain_detail_date = $detail->machine_chain_detail_date ? date('d-m-Y', strtotime($detail->machine_chain_detail_date)) : '';
                    $machine_chain_detail->machine_chain_detail_remark = $detail->machine_chain_detail_remark;
                    $machine_chain_detail->forwarded_from_mcd_id = $detail->machine_chain_detail_id;
                    $machine_chain_detail->type_id = MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID;
                    $machine_chain_detail->item_id = $detail->item_id;
                    $machine_chain_detail->touch_id = $detail->tunch;
                    $machine_chain_detail->wstg = '0';
                    $machine_chain_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $machine_chain_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $machine_chain_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($machine_chain_detail);
                }
                $data['machine_chain_detail'] = implode(',', $lineitems);
                set_page('manufacture/machine_chain/machine_chain', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function machine_chain_forward($machine_chain_id = '', $machine_chain_detail_id = '', $forward_wt = '') {
        $data = array();
        $machine_chain_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
        $data['touch'] = $touch;
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if (!empty($machine_chain_id) && !empty($machine_chain_detail_id) && !empty($forward_wt)) {
            if ($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID, "edit")) {
                $machine_chain_data = $this->crud->get_row_by_id('machine_chain', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_order_items = $this->crud->get_row_by_id('machine_chain_order_items', array('machine_chain_id' => $machine_chain_id));
                $machine_chain_details = $this->crud->get_row_by_id('machine_chain_details', array('machine_chain_detail_id' => $machine_chain_detail_id));
                $machine_chain_data = $machine_chain_data[0];
                $machine_chain_data->entry_mode = 'forward';
                $machine_chain_data->lott_complete = 0;
                $machine_chain_data->is_calculated = '0';
                $data['machine_chain_data'] = $machine_chain_data;
                
                $next_operation_data = $this->crud->get_next_machine_chain_operation_data($machine_chain_data->operation_id);
                if(!empty($next_operation_data)){
                    $machine_chain_data->operation_id = $next_operation_data->operation_id;
                    $data['machine_chain_operation_data'] = $next_operation_data;
                }

                $checked_order_items = array();
                if (!empty($machine_chain_order_items)) {
                    foreach ($machine_chain_order_items as $order_item) {
                        $checked_order_items[] = json_encode($order_item);
                    }
                }
                $data['checked_order_items'] = implode(',', $checked_order_items);

                $lineitems = array();
                foreach ($machine_chain_details as $detail) {
                    $machine_chain_detail->machine_chain_item_delete = 'allow';
                    $machine_chain_detail->type_name = 'Issue Finish Work';
                    $machine_chain_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $machine_chain_detail->purity = $detail->tunch;
                    $machine_chain_detail->weight = $forward_wt;
                    $machine_chain_detail->grwt = $forward_wt;
                    $machine_chain_detail->less = $detail->less;
                    $net_wt = $forward_wt - $detail->less;
                    $machine_chain_detail->net_wt = $net_wt;
                    $machine_chain_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $machine_chain_detail->real_actual_tunch = !empty($detail->real_actual_tunch) ? $detail->real_actual_tunch : 0;
                    $fine = $net_wt * $detail->tunch / 100;
                    $machine_chain_detail->fine = number_format((float) $fine, '2', '.', '');
                    $machine_chain_detail->pcs = $detail->pcs;
                    $machine_chain_detail->machine_chain_detail_date = $detail->machine_chain_detail_date ? date('d-m-Y', strtotime($detail->machine_chain_detail_date)) : '';
                    $machine_chain_detail->machine_chain_detail_remark = $detail->machine_chain_detail_remark;
                    $machine_chain_detail->forwarded_from_mcd_id = $detail->machine_chain_detail_id;
                    $machine_chain_detail->type_id = MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID;
                    $machine_chain_detail->item_id = $detail->item_id;
                    $machine_chain_detail->touch_id = $detail->tunch;
                    $machine_chain_detail->wstg = '0';
                    $machine_chain_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $machine_chain_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $machine_chain_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($machine_chain_detail);
                }
                $data['machine_chain_detail'] = implode(',', $lineitems);
                set_page('manufacture/machine_chain/machine_chain', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID, "add")) {
                $lineitems = array();
                set_page('manufacture/machine_chain/machine_chain', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_machine_chain() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($post_data); exit;
//        echo '<pre>'; print_r($line_items_data); exit;

        if (empty($line_items_data)) {
            $return['error'] = "Something went Wrong";
            print json_encode($return);
            exit;
	}

        $post_data['department_id'] = isset($post_data['department_id']) && !empty($post_data['department_id']) ? $post_data['department_id'] : null;
        $post_data['operation_id'] = isset($post_data['operation_id']) && !empty($post_data['operation_id']) ? $post_data['operation_id'] : null;
        $post_data['worker_id'] = isset($post_data['worker_id']) && !empty($post_data['worker_id']) ? $post_data['worker_id'] : null;
        $post_data['machine_chain_date'] = isset($post_data['machine_chain_date']) && !empty($post_data['machine_chain_date']) ? date('Y-m-d', strtotime($post_data['machine_chain_date'])) : null;
        $post_data['total_receive_fw_weight'] = number_format((float) $post_data['total_receive_fw_weight'], '3', '.', '');
        $post_data['total_issue_weight'] = number_format((float) $post_data['total_issue_weight'], '3', '.', '');
        $post_data['total_receive_weight'] = number_format((float) $post_data['total_receive_weight'], '3', '.', '');
        $post_data['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
        $post_data['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
        $post_data['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
        $post_data['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');
        $post_data['is_calculated'] = isset($post_data['is_calculated']) && !empty($post_data['is_calculated']) ? $post_data['is_calculated'] : '0';
        $post_data['forwarded_from_mc_id'] = isset($post_data['forwarded_from_mc_id']) && !empty($post_data['forwarded_from_mc_id']) ? $post_data['forwarded_from_mc_id'] : null;
        if (isset($post_data['machine_chain_id']) && !empty($post_data['machine_chain_id'])) {

            // Increase fine in Account And Department
            $this->update_account_and_department_balance_on_update($post_data['machine_chain_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_manufacture_update($post_data['machine_chain_id']);
            
            $post_data['department_id'] = $this->crud->get_column_value_by_id('machine_chain', 'department_id', array('machine_chain_id' => $post_data['machine_chain_id']));
            $update_arr = array();
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['operation_id'] = $post_data['operation_id'];
            $update_arr['worker_id'] = $post_data['worker_id'];
            $update_arr['machine_chain_date'] = $post_data['machine_chain_date'];
            $update_arr['lott_complete'] = $post_data['lott_complete'];
            if($post_data['operation_id'] == MACHINE_CHAIN_OPERATION_SOLDING_ID) {
                $update_arr['curb_box'] = $post_data['curb_box'];
            } else {
                $update_arr['curb_box'] = null;
            }            
            $update_arr['machine_chain_remark'] = $post_data['machine_chain_remark'];
            $update_arr['total_receive_fw_weight'] = $post_data['total_receive_fw_weight'];
            $update_arr['total_issue_weight'] = $post_data['total_issue_weight'];
            $update_arr['total_receive_weight'] = $post_data['total_receive_weight'];
            $update_arr['total_issue_net_wt'] = $post_data['total_issue_net_wt'];
            $update_arr['total_receive_net_wt'] = $post_data['total_receive_net_wt'];
            $update_arr['total_issue_fine'] = $post_data['total_issue_fine'];
            $update_arr['total_receive_fine'] = $post_data['total_receive_fine'];
            $update_arr['is_calculated'] = $post_data['is_calculated'];
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['machine_chain_id'] = $post_data['machine_chain_id'];

            $result = $this->crud->update('machine_chain', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $return['machine_chain_id'] = $post_data['machine_chain_id'];
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Machine Chain Updated Successfully');

                $update_machine_chain_oi_ids = array('0');
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if (!empty($checked_order_items)) {
                    foreach ($checked_order_items as $order_item) {
                        $order_item_arr = array();
                        $order_item_arr['machine_chain_id'] = $post_data['machine_chain_id'];
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        if (isset($order_item->machine_chain_oi_id) && !empty($order_item->machine_chain_oi_id)) {
                            $this->db->where('machine_chain_oi_id', $order_item->machine_chain_oi_id);
                            $this->db->update('machine_chain_order_items', $order_item_arr);
                            $update_machine_chain_oi_ids[] = $order_item->machine_chain_oi_id;
                        } else {
                            $order_item_arr['created_at'] = $this->now_time;
                            $order_item_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('machine_chain_order_items', $order_item_arr);
                            $update_machine_chain_oi_ids[] = $this->db->insert_id();
                        }
                    }
                }
                $this->db->where('machine_chain_id', $post_data['machine_chain_id']);
                $this->db->where_not_in('machine_chain_oi_id', $update_machine_chain_oi_ids);
                $this->db->delete('machine_chain_order_items');

                if (isset($post_data['deleted_lineitem_id'])) {
                    $this->db->where_in('machine_chain_detail_id', $post_data['deleted_lineitem_id']);
                    $this->db->delete('machine_chain_details');
                }

                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    $mcd_index_mcd_ids = array();
                    foreach ($line_items_data as $key => $lineitem) {
                        $update_item = array();
                        $update_item['machine_chain_id'] = $post_data['machine_chain_id'];
                        $update_item['type_id'] = $lineitem->type_id;
                        if(!empty($lineitem->added_from_ifw_mcd_id)) {
                            $update_item['added_from_ifw_mcd_id'] = $lineitem->added_from_ifw_mcd_id;

                        } elseif(!empty($lineitem->added_from_ifw_mcd_index) && !empty($mcd_index_mcd_ids[$lineitem->added_from_ifw_mcd_index])) {
                            $update_item['added_from_ifw_mcd_id'] = $mcd_index_mcd_ids[$lineitem->added_from_ifw_mcd_index];
                        } else {
                            $update_item['added_from_ifw_mcd_id'] = null;
                        }
                        
                        $update_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['tunch'] = $lineitem->purity;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['less'] = $lineitem->less;
                        $update_item['net_wt'] = $lineitem->net_wt;
                        $update_item['actual_tunch'] = $lineitem->actual_tunch;
                        $update_item['real_actual_tunch'] = $lineitem->real_actual_tunch;
                        $update_item['fine'] = $lineitem->fine;
                        $update_item['pcs'] = $lineitem->pcs;
                        $update_item['machine_chain_detail_date'] = !empty($lineitem->machine_chain_detail_date) ? date('Y-m-d', strtotime($lineitem->machine_chain_detail_date)) : null;
                        $update_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $update_item['machine_chain_detail_remark'] = $lineitem->machine_chain_detail_remark;
                        if (isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)) {
                            $update_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                        if (($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE) {
                            if (isset($lineitem->stock_type)) {
                                $update_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $update_item['updated_at'] = $this->now_time;
                        $update_item['updated_by'] = $this->logged_in_id;
                        if (isset($lineitem->machine_chain_detail_id) && !empty($lineitem->machine_chain_detail_id)) {
                            $this->db->where('machine_chain_detail_id', $lineitem->machine_chain_detail_id);
                            $this->db->update('machine_chain_details', $update_item);

                            /*---- Order Items ----*/
                            $mcd_checked_order_items = isset($lineitem->mcd_checked_order_items)?$lineitem->mcd_checked_order_items:array();
                            $machine_chain_detail_oi_ids = array();
                            if (!empty($mcd_checked_order_items)) {
                                foreach ($mcd_checked_order_items as $order_item) {
                                    $order_item_arr = array();
                                    $order_item_arr['machine_chain_id'] = $post_data['machine_chain_id'];
                                    $order_item_arr['machine_chain_detail_id'] = $lineitem->machine_chain_detail_id;
                                    $order_item_arr['order_id'] = $order_item->order_id;
                                    $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                                    $order_item_arr['updated_at'] = $this->now_time;
                                    $order_item_arr['updated_by'] = $this->logged_in_id;
                                    if (isset($order_item->machine_chain_detail_oi_id) && !empty($order_item->machine_chain_detail_oi_id)) {
                                        $this->db->where('machine_chain_detail_oi_id', $order_item->machine_chain_detail_oi_id);
                                        $this->db->update('machine_chain_detail_order_items', $order_item_arr);
                                        $machine_chain_detail_oi_ids[] = $order_item->machine_chain_detail_oi_id;
                                    } else {
                                        $order_item_arr['created_at'] = $this->now_time;
                                        $order_item_arr['created_by'] = $this->logged_in_id;
                                        $this->crud->insert('machine_chain_detail_order_items', $order_item_arr);
                                        $machine_chain_detail_oi_ids[] = $this->db->insert_id();
                                    }
                                }
                            }
                            /*---- Order Items ----*/
                            if(!empty($machine_chain_detail_oi_ids)) {
                                $this->db->where('machine_chain_detail_id', $lineitem->machine_chain_detail_id);
                                $this->db->where_not_in('machine_chain_detail_oi_id', $machine_chain_detail_oi_ids);
                                $this->db->delete('machine_chain_detail_order_items');    
                            } else {
                                $this->db->where('machine_chain_detail_id', $lineitem->machine_chain_detail_id);
                                $this->db->delete('machine_chain_detail_order_items');    
                            }
                            

                            $mcd_index_mcd_ids[$key] = $lineitem->machine_chain_detail_id;
                        } else {
                            $update_item['forwarded_from_mcd_id'] = (isset($lineitem->forwarded_from_mcd_id) && !empty($lineitem->forwarded_from_mcd_id)) ? $lineitem->forwarded_from_mcd_id : null;
                            
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('machine_chain_details', $update_item);
                            $last_inserted_id = $this->db->insert_id();

                            if(!empty($lineitem->forwarded_from_mcd_id)){
                                $this->crud->update('machine_chain_details', array('is_forwarded' => '1'), array('machine_chain_detail_id' => $lineitem->forwarded_from_mcd_id));
                            }

                            $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                            $mcd_index_mcd_ids[$key] = $last_inserted_id;

                            /*---- Order Items ----*/
                            $mcd_checked_order_items = isset($lineitem->mcd_checked_order_items)?$lineitem->mcd_checked_order_items:array();
                            if (!empty($mcd_checked_order_items)) {
                                foreach ($mcd_checked_order_items as $order_item) {
                                    $order_item_arr = array();
                                    $order_item_arr['machine_chain_id'] = $post_data['machine_chain_id'];
                                    $order_item_arr['machine_chain_detail_id'] = $last_inserted_id;
                                    $order_item_arr['order_id'] = $order_item->order_id;
                                    $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                                    $order_item_arr['updated_at'] = $this->now_time;
                                    $order_item_arr['updated_by'] = $this->logged_in_id;
                                    $order_item_arr['created_at'] = $this->now_time;
                                    $order_item_arr['created_by'] = $this->logged_in_id;
                                    $this->crud->insert('machine_chain_detail_order_items', $order_item_arr);
                                }
                            }
                            /*---- Order Items ----*/
                        }
                        $line_items_data[$key]->category_id = $update_item['category_id'];
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));

                        $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));

                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {
                            if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }

                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if (!empty($account)) {
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }

                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if (!empty($department)) {
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }

                    $this->update_stock_on_manufacture_insert($line_items_data, $post_data['department_id']);
                }
            }
        } else {

            $insert_arr = array();
            $reference = $this->crud->get_max_number('machine_chain', 'reference_no');
            $reference_no = 1;
            if ($reference->reference_no > 0) {
                $reference_no = $reference->reference_no + 1;
            }
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['operation_id'] = $post_data['operation_id'];
            $insert_arr['worker_id'] = $post_data['worker_id'];
            $insert_arr['machine_chain_date'] = $post_data['machine_chain_date'];
            $insert_arr['reference_no'] = $reference_no;
            $insert_arr['lott_complete'] = $post_data['lott_complete'];
            if($post_data['operation_id'] == MACHINE_CHAIN_OPERATION_SOLDING_ID) {
                $insert_arr['curb_box'] = $post_data['curb_box'];
            } else {
                $insert_arr['curb_box'] = null;
            }
            $insert_arr['machine_chain_remark'] = $post_data['machine_chain_remark'];
            $insert_arr['total_receive_fw_weight'] = $post_data['total_receive_fw_weight'];
            $insert_arr['total_issue_weight'] = $post_data['total_issue_weight'];
            $insert_arr['total_receive_weight'] = $post_data['total_receive_weight'];
            $insert_arr['total_issue_net_wt'] = $post_data['total_issue_net_wt'];
            $insert_arr['total_receive_net_wt'] = $post_data['total_receive_net_wt'];
            $insert_arr['total_issue_fine'] = $post_data['total_issue_fine'];
            $insert_arr['total_receive_fine'] = $post_data['total_receive_fine'];
            $insert_arr['is_calculated'] = $post_data['is_calculated'];
            $insert_arr['forwarded_from_mc_id'] = $post_data['forwarded_from_mc_id'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('machine_chain', $insert_arr);
            $machine_chain_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $return['machine_chain_id'] = $machine_chain_id;
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Machine Chain Added Successfully');

                if(!empty($post_data['forwarded_from_mc_id'])){
                    $this->crud->update('machine_chain', array('is_forwarded' => '1'), array('machine_chain_id' => $post_data['forwarded_from_mc_id']));
                }
                
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if (!empty($checked_order_items)) {
                    foreach ($checked_order_items as $order_item) {
                        $order_item_arr = array();
                        $order_item_arr['machine_chain_id'] = $machine_chain_id;
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['created_at'] = $this->now_time;
                        $order_item_arr['created_by'] = $this->logged_in_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('machine_chain_order_items', $order_item_arr);
                    }
                }

                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['machine_chain_id'] = $machine_chain_id;
                        $insert_item['type_id'] = $lineitem->type_id;
                        $insert_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['tunch'] = $lineitem->purity;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['actual_tunch'] = $lineitem->actual_tunch;
                        $insert_item['real_actual_tunch'] = $lineitem->real_actual_tunch;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['pcs'] = $lineitem->pcs;
                        $insert_item['machine_chain_detail_date'] = !empty($lineitem->machine_chain_detail_date) ? date('Y-m-d', strtotime($lineitem->machine_chain_detail_date)) : null;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['machine_chain_detail_remark'] = $lineitem->machine_chain_detail_remark;
                        if (isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)) {
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $insert_item['forwarded_from_mcd_id'] = (isset($lineitem->forwarded_from_mcd_id) && !empty($lineitem->forwarded_from_mcd_id)) ? $lineitem->forwarded_from_mcd_id : null;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->touch_id = $lineitem->purity;
                        $line_items_data[$key]->category_id = $insert_item['category_id'];
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                        if (($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE) {
                            if (isset($lineitem->stock_type)) {
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $this->crud->insert('machine_chain_details', $insert_item);
                        $last_inserted_item_id = $this->db->insert_id();


                        /*---- Order Items ----*/
                        $mcd_checked_order_items = isset($lineitem->mcd_checked_order_items)?$lineitem->mcd_checked_order_items:array();
                        if (!empty($mcd_checked_order_items)) {
                            foreach ($mcd_checked_order_items as $order_item) {
                                $order_item_arr = array();
                                $order_item_arr['machine_chain_id'] = $machine_chain_id;
                                $order_item_arr['machine_chain_detail_id'] = $last_inserted_item_id;
                                $order_item_arr['order_id'] = $order_item->order_id;
                                $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                                $order_item_arr['updated_at'] = $this->now_time;
                                $order_item_arr['updated_by'] = $this->logged_in_id;
                                $order_item_arr['created_at'] = $this->now_time;
                                $order_item_arr['created_by'] = $this->logged_in_id;
                                $this->crud->insert('machine_chain_detail_order_items', $order_item_arr);
                            }
                        }
                        /*---- Order Items ----*/
                        
                        $line_items_data[$key]->purchase_item_id = $last_inserted_item_id;

                        if(!empty($lineitem->forwarded_from_mcd_id)){
                            $this->crud->update('machine_chain_details', array('is_forwarded' => '1'), array('machine_chain_detail_id' => $lineitem->forwarded_from_mcd_id));
                        }
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));

                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {
                            if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }
                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if (!empty($account)) {
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }

                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if (!empty($department)) {
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }

                    $this->update_stock_on_manufacture_insert($line_items_data, $post_data['department_id']);
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function update_stock_on_manufacture_insert($lineitem_data, $department_id) {
//        echo '<pre>'; print_r($lineitem_data);
//        echo '<pre>'; print_r($department_id); exit;
        if (!empty($lineitem_data)) {
            foreach ($lineitem_data as $lineitem) {

                $lineitem->fine = $lineitem->net_wt * ($lineitem->touch_id) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if ($category_group_id == 1) {
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                if ((isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE) && $lineitem->type_id != MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID && $lineitem->type_id != MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {

                    if (isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)) {
                        $insert_item_stock = array();
                        $insert_item_stock['department_id'] = $department_id;
                        $insert_item_stock['category_id'] = $lineitem->category_id;
                        $insert_item_stock['item_id'] = $lineitem->item_id;
                        $insert_item_stock['tunch'] = $lineitem->touch_id;
                        $insert_item_stock['grwt'] = $lineitem->weight;
                        $insert_item_stock['less'] = $lineitem->less;
                        $insert_item_stock['ntwt'] = $lineitem->net_wt;
                        $insert_item_stock['fine'] = $lineitem->fine;
                        $insert_item_stock['purchase_sell_item_id'] = $lineitem->purchase_item_id;
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID) {
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_FINISH_ID;
                        }
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID) {
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_SCRAP_ID;
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;

                        $this->crud->insert('item_stock', $insert_item_stock);
                    } else {
                        if (isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)) {
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif (isset($lineitem->machine_chain_detail_id) && !empty($lineitem->machine_chain_detail_id)) {
                            $purchase_item_id = $lineitem->machine_chain_detail_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
                        if (!empty($exist_item_id)) {
                            if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
                                $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->weight, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else {
                                $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                                $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                                $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                                $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->weight, '3', '.', '');
                            }
                            $update_item_stock = array();
                            $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                            $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                            $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                            $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                            $update_item_stock['updated_at'] = $this->now_time;
                            $update_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                        } else {
                            $insert_item_stock = array();
                            $insert_item_stock['department_id'] = $department_id;
                            $insert_item_stock['category_id'] = $lineitem->category_id;
                            $insert_item_stock['item_id'] = $lineitem->item_id;
                            $insert_item_stock['tunch'] = $lineitem->touch_id;
                            $insert_item_stock['grwt'] = $lineitem->weight;
                            $insert_item_stock['less'] = $lineitem->less;
                            $insert_item_stock['ntwt'] = $lineitem->net_wt;
                            $insert_item_stock['fine'] = $lineitem->fine;
                            $insert_item_stock['purchase_sell_item_id'] = $purchase_item_id;
                            if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID) {
                                $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_FINISH_ID;
                            }
                            if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID) {
                                $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_SCRAP_ID;
                            }
                            $insert_item_stock['created_at'] = $this->now_time;
                            $insert_item_stock['created_by'] = $this->logged_in_id;
                            $insert_item_stock['updated_at'] = $this->now_time;
                            $insert_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('item_stock', $insert_item_stock);
                        }
                    }
                } else {
                    if (isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE) {
                        if (isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)) {
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif (isset($lineitem->machine_chain_detail_id) && !empty($lineitem->machine_chain_detail_id)) {
                            $purchase_item_id = $lineitem->machine_chain_detail_id;
                        } elseif (isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)) {
                            $purchase_item_id = $lineitem->purchase_item_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
//                    echo '<pre>'.$this->db->last_query(); exit;
                    if (!empty($exist_item_id)) {
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') - number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') - number_format((float) $lineitem->weight, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') - number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                        } else {
                            $current_stock_less = number_format((float) $exist_item_id[0]->less, '3', '.', '') + number_format((float) $lineitem->less, '3', '.', '');
                            $current_stock_ntwt = number_format((float) $exist_item_id[0]->ntwt, '3', '.', '') + number_format((float) $lineitem->net_wt, '3', '.', '');
                            $current_stock_fine = number_format((float) $exist_item_id[0]->fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            $current_stock_grwt = number_format((float) $exist_item_id[0]->grwt, '3', '.', '') + number_format((float) $lineitem->weight, '3', '.', '');
                        }
                        $update_item_stock = array();
                        $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                        $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                        $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                        $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                        $update_item_stock['updated_at'] = $this->now_time;
                        $update_item_stock['updated_by'] = $this->logged_in_id;
                        $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
                    } else {
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
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
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID) {
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_FINISH_ID;
                        }
                        if ($lineitem->type_id == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID) {
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MC_RECEIVE_SCRAP_ID;
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
//                        echo '<pre>'; print_r($insert_item_stock); exit;
                        $this->crud->insert('item_stock', $insert_item_stock);
                    }
                }
            }
        }
    }

    function update_stock_on_manufacture_update($machine_chain_id = '') {
        $machine_chain_details = $this->crud->get_all_with_where('machine_chain_details', '', '', array('machine_chain_id' => $machine_chain_id));
        if (!empty($machine_chain_details)) {
            foreach ($machine_chain_details as $lineitem) {

                $lineitem->fine = $lineitem->net_wt * $lineitem->tunch / 100;
                $lineitem->grwt = $lineitem->weight;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if ($category_group_id == 1) {
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }

                $department_id = $this->crud->get_column_value_by_id('machine_chain', 'department_id', array('machine_chain_id' => $lineitem->machine_chain_id));
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if ($stock_method == STOCK_METHOD_ITEM_WISE) {
                    if (!empty($lineitem->purchase_sell_item_id)) {
                        $machine_chain_detail_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $machine_chain_detail_id = $lineitem->machine_chain_detail_id;
                    }
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $machine_chain_detail_id);
                } else {
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }

                $exist_item_id = $this->crud->get_row_by_id('item_stock', $where_stock_array);
//                echo $this->db->last_query(); exit;
                if (!empty($exist_item_id)) {
                    if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID){
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
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_id[0]->item_stock_id));
//                    echo '<pre>'.$this->db->last_query(); exit;
                }
            }
        }
    }

    function update_account_and_department_balance_on_update($machine_chain_id = '') {
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $machine_chain_details = $this->crud->get_all_with_where('machine_chain_details', '', '', array('machine_chain_id' => $machine_chain_id));
//        echo '<pre>'; print_r($machine_chain_details); exit;
        $department_id = $this->crud->get_column_value_by_id('machine_chain', 'department_id', array('machine_chain_id' => $machine_chain_id));
        $account_id = $this->crud->get_column_value_by_id('machine_chain', 'worker_id', array('machine_chain_id' => $machine_chain_id));
        if (!empty($machine_chain_details)) {
            foreach ($machine_chain_details as $lineitem) {

                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));

                if ($lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {
                    if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    }
                } else {
                    if ($category_group_id == CATEGORY_GROUP_GOLD_ID) {
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    } else if ($category_group_id == CATEGORY_GROUP_SILVER_ID) {
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    }
                }
            }

//            echo '<pre>'; print_r($total_gold_fine);
//            echo '<pre>'; print_r($total_silver_fine); exit;
            //Update Account Balance
            $account = $this->crud->get_data_row_by_id('account', 'account_id', $account_id);
            if (!empty($account)) {
                $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $account_id));
            }
//            echo '<pre>'. $this->db->last_query(); exit;
            //Update Department Balance
            $department = $this->crud->get_data_row_by_id('account', 'account_id', $department_id);
            if (!empty($department)) {
                $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $department_id));
            }
        }
    }

    function machine_chain_list() {
        if ($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID, "view")) {
            $data = array();
            $data['curb_default_value'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'machine_chain_operation_solding_curb_default_value'));
            $data['box_default_value']= $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'machine_chain_operation_solding_box_default_value'));
            set_page('manufacture/machine_chain/machine_chain_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function machine_chain_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'machine_chain machine_chain';
        $config['select'] = 'machine_chain.*,a.account_name AS worker,aa.account_name AS department, mco.operation_name, IF(machine_chain.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(machine_chain.hisab_done = 0,"No","Yes") AS is_hisab_done';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = machine_chain.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = machine_chain.department_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'machine_chain_operation mco', 'join_by' => 'mco.operation_id = machine_chain.operation_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'aa.account_name', 'mco.operation_name', 'DATE_FORMAT(machine_chain.machine_chain_date,"%d-%m-%Y")', 'machine_chain.reference_no', 'IF(machine_chain.lott_complete = 0,"No","Yes")', 'IF(machine_chain.hisab_done = 0,"No","Yes")', 'machine_chain.machine_chain_remark');
        $config['column_order'] = array(null, 'a.account_name', 'aa.account_name', 'mco.operation_name', 'machine_chain.machine_chain_date', 'machine_chain.reference_no', null, null, null, null, null, null, null, null, 'machine_chain.lott_complete', 'machine_chain.hisab_done', 'machine_chain.machine_chain_remark');

        $department_ids = $this->applib->current_user_department_ids();
        if (!empty($department_ids)) {
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 'machine_chain.department_id IN(' . $department_ids . ')';
        }
        if (!empty($post_data['department_id'])) {
            $config['wheres'][] = array('column_name' => 'machine_chain.department_id', 'column_value' => $post_data['department_id']);
        }
        if (!empty($post_data['worker_id'])) {
            $config['wheres'][] = array('column_name' => 'machine_chain.worker_id', 'column_value' => $post_data['worker_id']);
        }
        if(!empty($post_data['operation_id'])){
            $config['wheres'][] = array('column_name' => 'machine_chain.operation_id', 'column_value' => $post_data['operation_id']);
        }
        if (isset($post_data['lott_complete'])) {
            if ($post_data['lott_complete'] == '2') {
                $config['wheres'][] = array('column_name' => 'machine_chain.hisab_done', 'column_value' => '1');
            } else if ($post_data['lott_complete'] == '1') {
                $config['wheres'][] = array('column_name' => 'machine_chain.lott_complete', 'column_value' => '1');
                $config['wheres'][] = array('column_name' => 'machine_chain.hisab_done', 'column_value' => '0');
            } else if ($post_data['lott_complete'] == 'all') {
                $config['wheres'][] = array('column_name' => 'machine_chain.hisab_done', 'column_value' => '0');
            } else {
                $config['wheres'][] = array('column_name' => 'machine_chain.lott_complete', 'column_value' => $post_data['lott_complete']);
            }
        }

        if (isset($post_data['curb_box']) && !empty($post_data['curb_box']) && !empty($post_data['out_of_curb_box'])) {

            $out_of_curb_box = $post_data['out_of_curb_box'];
            
            $config['wheres'][] = array('column_name' => 'machine_chain.operation_id', 'column_value' => MACHINE_CHAIN_OPERATION_SOLDING_ID);
            $config['wheres'][] = array('column_name' => 'machine_chain.curb_box', 'column_value' => $post_data['curb_box']);
            $config['wheres'][] = 'machine_chain.total_receive_fw_weight IS NOT NULL AND ((machine_chain.total_receive_fw_weight * '.$out_of_curb_box.' / 100) >= (machine_chain.total_issue_weight - machine_chain.total_receive_weight))';

            if($post_data['curb_box'] == MCO_SOLDING_CURB_ID) {
                $this->crud->update('settings',array('settings_value' => $post_data['out_of_curb_box']),array('settings_key' => 'machine_chain_operation_solding_curb_default_value'));
            
            } else if($post_data['curb_box'] == MCO_SOLDING_BOX_ID) {
                $this->crud->update('settings',array('settings_value' => $post_data['out_of_curb_box']),array('settings_key' => 'machine_chain_operation_solding_box_default_value'));   
            }
        }

        $config['order'] = array('machine_chain.machine_chain_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'. $this->db->last_query(); exit;
        $data = array();

        $role_delete = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(MACHINE_CHAIN_MODULE_ID, "edit");

        foreach ($list as $machine_chain) {
            $row = array();
            $action = '';
            if ($role_edit) {
                if ($machine_chain->hisab_done != '1') {
                    $action .= '<a href="' . base_url("machine_chain/machine_chain/" . $machine_chain->machine_chain_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if ($role_delete) {
                if ($machine_chain->hisab_done != '1') {

                    $is_forwaded_machine_chain = $this->is_forwaded_machine_chain($machine_chain->machine_chain_id);

                    if($is_forwaded_machine_chain == false) {
                        $action .= '<a href="javascript:void(0);" class="delete_machine_chain" data-href="' . base_url('machine_chain/delete/' . $machine_chain->machine_chain_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';    
                    }                    
                }
            }
            if ($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID, "worker_hisab_machine_chain")) {
                if (isset($post_data['checked_or_not']) && $post_data['checked_or_not'] == '1') {
                    $action .= '&nbsp;&nbsp;<input type="checkbox" name="check_machine_chain[]" id="checkbox_id_' . $machine_chain->machine_chain_id . '" class="icheckbox_flat-blue check_machine_chain" value="' . $machine_chain->machine_chain_id . '" data-total_issue_net_wt="' . $machine_chain->total_issue_net_wt . '" data-total_issue_fine="' . $machine_chain->total_issue_fine . '" data-total_receive_net_wt="' . $machine_chain->total_receive_net_wt . '" data-total_receive_fine="' . $machine_chain->total_receive_fine . '">';
                }
            }
            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-machine_chain_id="' . $machine_chain->machine_chain_id . '" >' . $machine_chain->department . '</a>';
            $row[] = $machine_chain->operation_name;
            $row[] = $machine_chain->worker;
            $row[] = (!empty(strtotime($machine_chain->machine_chain_date))) ? date('d-m-Y', strtotime($machine_chain->machine_chain_date)) : '';
            $row[] = $machine_chain->reference_no;
            if (!empty($machine_chain->total_issue_fine) && !empty($machine_chain->total_issue_net_wt)) {
                $issue_avg_tunch = $machine_chain->total_issue_fine * 100 / $machine_chain->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            $row[] = number_format($issue_avg_tunch, 2, '.', '');
            $row[] = number_format($machine_chain->total_issue_net_wt, 3, '.', '');
            $row[] = number_format($machine_chain->total_issue_fine, 3, '.', '');
            if (!empty($machine_chain->total_receive_fine) && !empty($machine_chain->total_receive_net_wt)) {
                $receive_avg_tunch = $machine_chain->total_receive_fine * 100 / $machine_chain->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $row[] = number_format($receive_avg_tunch, 2, '.', '');
            $row[] = number_format($machine_chain->total_receive_net_wt, 3, '.', '');
            $row[] = number_format($machine_chain->total_receive_fine, 3, '.', '');
            $balance_net_wt = $machine_chain->total_issue_net_wt - $machine_chain->total_receive_net_wt;
            $row[] = number_format($balance_net_wt, 3, '.', '');
            $balance_fine = $machine_chain->total_issue_fine - $machine_chain->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $lott_complete = $machine_chain->is_lott_complete;
            if($this->applib->have_access_role(MACHINE_CHAIN_MODULE_ID,"allow to lott complete")) {
                if($machine_chain->lott_complete == 0) {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-machine_chain_id="'.$machine_chain->machine_chain_id.'" data-lott_complete="1" data-href="'. base_url('machine_chain/set_lott_complete_yes_no/'.$machine_chain->machine_chain_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;">';
                } else {
                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-machine_chain_id="'.$machine_chain->machine_chain_id.'" data-lott_complete=0 data-href="'. base_url('machine_chain/set_lott_complete_yes_no/'.$machine_chain->machine_chain_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;" checked="checked">';
                }
            }
            $row[] = $lott_complete;
            $row[] = $machine_chain->is_hisab_done;
            $row[] = $machine_chain->machine_chain_remark;
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

    function is_forwaded_machine_chain($machine_chain_id)
    {
        $sql = "SELECT machine_chain_detail_id FROM machine_chain_details WHERE forwarded_from_mcd_id IN(SELECT machine_chain_detail_id FROM machine_chain_details WHERE machine_chain_id = ".$machine_chain_id.") LIMIT 1";
        $res = $this->crud->getFromSQL($sql);
        if(!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    function machine_chain_selection_list() {
        $post_data = $this->input->post();
        $config['table'] = 'machine_chain machine_chain';
        $config['select'] = 'machine_chain.*,a.account_name AS worker,aa.account_name AS department, mco.operation_name, IF(machine_chain.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(machine_chain.hisab_done = 0,"No","Yes") AS is_hisab_done';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = machine_chain.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = machine_chain.department_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'machine_chain_operation mco', 'join_by' => 'mco.operation_id = machine_chain.operation_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'mco.operation_name', 'aa.account_name', 'DATE_FORMAT(machine_chain.machine_chain_date,"%d-%m-%Y")', 'machine_chain.reference_no');
        $config['column_order'] = array(null, 'aa.account_name', 'mco.operation_name', 'a.account_name', 'machine_chain.machine_chain_date', 'machine_chain.reference_no');

        $config['custom_where'] = ' lott_complete = 0 ';
        $department_ids = $this->applib->current_user_department_ids();
        if (!empty($department_ids)) {
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] .= ' AND machine_chain.department_id IN(' . $department_ids . ')';
        }

        if(!empty($post_data['machine_chain_id'])) {
            $config['custom_where'] .= ' AND  machine_chain.machine_chain_id != "'.$post_data['machine_chain_id'].'"';
        }
        $config['order'] = array('machine_chain.machine_chain_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');

        $list = $this->datatable->get_datatables();
        $data = array();

        foreach ($list as $machine_chain) {
            $row = array();
            $action = '<input type="checkbox" class="forwarded_to_mc" value="'.$machine_chain->machine_chain_id.'" style="height:20px; width:20px">';
            $row[] = $action;
            $row[] = $machine_chain->department;
            $row[] = $machine_chain->operation_name;
            $row[] = $machine_chain->worker;
            $row[] = (!empty(strtotime($machine_chain->machine_chain_date))) ? date('d-m-Y', strtotime($machine_chain->machine_chain_date)) : '';
            $row[] = $machine_chain->reference_no;
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

    function set_lott_complete_yes_no($machine_chain_id = '') {
        $update_data = array();
        $update_data['lott_complete'] = $_POST['lott_complete'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;

        $result = $this->crud->update('machine_chain', $update_data, array('machine_chain_id' => $machine_chain_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function machine_chain_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'machine_chain_details machine_chain_detail';
        $config['select'] = 'machine_chain_detail.*,im.item_name, machine_chain_detail.type_id AS type';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = machine_chain_detail.item_id', 'join_type' => 'left');
        $config['column_search'] = array('IF(machine_chain_detail.type_id = 0,"Issue","Receive")', 'im.item_name', 'machine_chain_detail.weight', 'machine_chain_detail.tunch', 'machine_chain_detail.fine', 'DATE_FORMAT(machine_chain_detail.machine_chain_detail_date,"%d-%m-%Y")', 'machine_chain_detail.machine_chain_detail_remark');
        $config['column_order'] = array('machine_chain_detail.type_id', 'im.item_name', 'machine_chain_detail.weight', NULL, 'machine_chain_detail.weight', 'machine_chain_detail.tunch', 'machine_chain_detail.fine', 'machine_chain_detail.machine_chain_detail_date', 'machine_chain_detail.created_at', 'machine_chain_detail.updated_at', 'machine_chain_detail.machine_chain_detail_remark');
        $config['order'] = array('machine_chain_detail.machine_chain_detail_id' => 'desc');
        if (isset($post_data['machine_chain_id']) && !empty($post_data['machine_chain_id'])) {
            $config['wheres'][] = array('column_name' => 'machine_chain_detail.machine_chain_id', 'column_value' => $post_data['machine_chain_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $detail) {
            $row = array();
            $detail_type = '';
            if ($detail->type == MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID) {
                $detail_type = 'Issue Finish Work';
            } else if ($detail->type == MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID) {
                $detail_type = 'Issue Scrap';
            } else if ($detail->type == MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID) {
                $detail_type = 'Receive Finish Work';
            } else if ($detail->type == MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID) {
                $detail_type = 'Receive Scrap';
            }
            $row[] = $detail_type;
            $row[] = $detail->item_name;
            $row[] = number_format($detail->weight, 3, '.', '');
            $row[] = number_format($detail->less, 3, '.', '');
            $row[] = number_format($detail->net_wt, 3, '.', '');
            $row[] = number_format($detail->tunch, 2, '.', '');
            $row[] = number_format($detail->actual_tunch, 2, '.', '');
            $row[] = number_format($detail->fine, 3, '.', '');
            $actual_fine = $detail->net_wt * $detail->actual_tunch / 100;
            $row[] = number_format($actual_fine, 3, '.', '');
            $row[] = $detail->pcs;
            $row[] = $detail->machine_chain_detail_date ? date('d-m-Y', strtotime($detail->machine_chain_detail_date)) : '';
            $row[] = $detail->created_at ? date('d-m-Y H:i:s', strtotime($detail->created_at)) : '';
            $row[] = $detail->updated_at ? date('d-m-Y H:i:s', strtotime($detail->updated_at)) : '';
            $row[] = $detail->machine_chain_detail_remark;
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

    function delete($id = '') {
        $where_array = array('machine_chain_id' => $id);
        $machine_chain = $this->crud->get_row_by_id('machine_chain', $where_array);
        $return = array();
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if (!empty($machine_chain)) {
            
            $forwarded_from_mc_result = $this->crud->get_id_by_val_not('machine_chain', 'machine_chain_id', 'forwarded_from_mc_id', $machine_chain[0]->forwarded_from_mc_id, array($id));
            if(empty($forwarded_from_mc_result)){
                $this->crud->update('machine_chain', array('is_forwarded' => '0'), array('machine_chain_id' => $machine_chain[0]->forwarded_from_mc_id));
            }
            
            $found = false;
            $machine_chain_details = $this->crud->get_row_by_id('machine_chain_details', $where_array);
            if (!empty($machine_chain_details)) {
                foreach ($machine_chain_details as $machine_chain_detail) {
                    
                    $forwarded_from_mcd_result = $this->crud->get_id_by_val_not('machine_chain_details', 'machine_chain_detail_id', 'forwarded_from_mcd_id', $machine_chain_detail->forwarded_from_mcd_id, array($machine_chain_detail->machine_chain_detail_id));
                    if(empty($forwarded_from_mcd_result)){
                        $this->crud->update('machine_chain_details', array('is_forwarded' => '0'), array('machine_chain_detail_id' => $machine_chain_detail->forwarded_from_mcd_id));
                    }
                    
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $machine_chain_detail->item_id));
                    if ($stock_method == STOCK_METHOD_ITEM_WISE) {
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('machine_chain_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_FINISH_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('machine_chain_details', array('purchase_sell_item_id' => $machine_chain_detail->machine_chain_detail_id, 'stock_type' => STOCK_TYPE_MC_RECEIVE_SCRAP_ID));
                        if (!empty($item_sells)) {
                            $found = true;
                        }
                    } else if ($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE) {
                        if ($without_purchase_sell_allow == '1') {
                            $used_lineitem_ids = $this->check_default_item_receive_or_not($machine_chain[0]->department_id, $machine_chain_detail->category_id, $machine_chain_detail->item_id, $machine_chain_detail->tunch);
                            if (!empty($used_lineitem_ids) && in_array($machine_chain_detail->machine_chain_detail_id, $used_lineitem_ids)) {
                                $found = true;
                            }
                        }
                    }
                }
            }
            if ($found == true) {
                $return['error'] = 'Error';
            } else {
                // Increase fine and amount in Department
                $this->update_account_and_department_balance_on_update($id);
//                // Increase Item Stock
                $this->update_stock_on_manufacture_update($id);
                $this->crud->delete('machine_chain_order_items', $where_array);
                $this->crud->delete('machine_chain_details', $where_array);
                $this->crud->delete('machine_chain', $where_array);
//                // Increase fine and amount in Department
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }

    function check_default_item_receive_or_not($department_id, $category_id, $item_id, $touch_id) {
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_machine_chain_grwt = $this->crud->get_total_machine_chain_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_machine_chain_grwt + $total_other_sell_grwt;
        $used_lineitem_ids = array();
        if (!empty($total_sell_grwt)) {
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $machine_chain_receive_items = $this->crud->get_machine_chain_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $other_purchase_items = $this->crud->get_other_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $machine_chain_receive_items, $other_purchase_items);
//            echo '<pre>'; print_r($purchase_delete_array); exit;
            uasort($purchase_delete_array, function($a, $b) {
                $value1 = strtotime($a->created_at);
                $value2 = strtotime($b->created_at);
                return $value1 - $value2;
            });

            $purchase_grwt = 0;
            $first_check_purchase_grwt = 0;

            foreach ($purchase_delete_array as $purchase_item) {
                $purchase_grwt = $purchase_grwt + $purchase_item->grwt;
                if ($purchase_grwt >= $total_sell_grwt && $first_check_purchase_grwt == 0) {
                    if ($purchase_item->type == 'MC_R') {
                        $used_lineitem_ids[] = $purchase_item->machine_chain_detail_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if ($purchase_grwt <= $total_sell_grwt) {
                    if ($purchase_item->type == 'MC_R') {
                        $used_lineitem_ids[] = $purchase_item->machine_chain_detail_id;
                    }
                }
            }
        }

        return $used_lineitem_ids;
        exit;
    }

    function get_category_from_item() {
        $data = array();
        $item_id = $_POST['item_id'];
        $category_id = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $item_id));
        if (isset($category_id) && !empty($category_id)) {
            $data['category_id'] = $category_id;
        }
        echo json_encode($data);
        exit;
    }

}
