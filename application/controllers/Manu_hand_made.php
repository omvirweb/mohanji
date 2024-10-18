<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manu_hand_made extends CI_Controller {

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
        $this->zero_value = 0;
    }
    
    function operation($operation_id = ''){
        $data = array();
        
        if ($this->applib->have_access_role(OPERATION_MODULE_ID, "edit") || $this->applib->have_access_role(OPERATION_MODULE_ID, 'add')) {
            $user_department = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            $data['user_department'] = $user_department;
            $user_worker = $this->crud->get_row_by_id('account', array('account_group_id' => WORKER_GROUP));
            $is_supplier = $this->crud->get_row_by_id('account', array('is_supplier' => '1'));
            $data['user_worker'] = array_merge($user_worker, $is_supplier);
            if(isset($operation_id) && !empty($operation_id)){
                if ($this->applib->have_access_role(OPERATION_MODULE_ID, "edit")){
                    $op_data = $this->crud->get_row_by_id('operation', array('operation_id' => $operation_id));
                    $op_data = $op_data[0];
                    $op_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$op_data->created_by));
                    if($op_data->created_by != $op_data->updated_by){
                        $op_data->updated_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $op_data->updated_by));
                    }else{
                        $op_data->updated_by_name = $op_data->created_by_name;
                    }
                    
                    $department = $this->crud->getFromSQL("SELECT department_id FROM operation_department WHERE operation_id= ".$operation_id." ");
                    $department_ids = array();
                    if(!empty($department)){
                        foreach ($department as $dp){
                            $department_ids[] = $dp->department_id;
                        }
                    }
                    $w_data = $this->crud->getFromSQL("SELECT worker_id FROM operation_worker WHERE operation_id= ".$operation_id." ");
                    $data['department'] = $department_ids;
                    $data['op_data'] = $op_data;
                    $w_name = array();
                    if(!empty($w_data)){
                        foreach ($w_data as $w){
                            $w_name[] = $w->worker_id;
                        }
                    }
                    $data['worker'] = $w_name;
                    set_page('manufacture/operation', $data);
                } else {
                    $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                    redirect("/");
                }
            } else {
                set_page('manufacture/operation', $data);
            }
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    } 
    
//    function operation($operation_id = ''){
//        $data = array();
//        
//        if ($this->applib->have_access_role(OPERATION_MODULE_ID, "edit") || $this->applib->have_access_role(OPERATION_MODULE_ID, 'add')) {
//            $user_department = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
//            $data['user_department'] = $user_department;
//            $user_worker = $this->crud->get_row_by_id('account', array('account_group_id' => WORKER_GROUP));
//            $data['user_worker'] = $user_worker;
//            if(isset($operation_id) && !empty($operation_id)){
//                if ($this->applib->have_access_role(OPERATION_MODULE_ID, "edit")){
//                    $op_data = $this->crud->get_row_by_id('operation', array('operation_id' => $operation_id));
//                    $department = $this->crud->getFromSQL("SELECT department_id FROM operation_department WHERE operation_id= ".$operation_id." ");
//                    $department_ids = array();
//                    if(!empty($department)){
//                        foreach ($department as $dp){
//                            $department_ids[] = $dp->department_id;
//                        }
//                    }
//                    $w_data = $this->crud->getFromSQL("SELECT worker_id FROM operation_worker WHERE operation_id= ".$operation_id." ");
//                    $data['department'] = $department_ids;
//                    $data['op_data'] = $op_data[0];
//                    $w_name = array();
//                    if(!empty($w_data)){
//                        foreach ($w_data as $w){
//                            $w_name[] = $w->worker_id;
//                        }
//                    }
//                    $data['w_ids'] = json_encode($w_name);
//                    set_page('manufacture/operation', $data);
//                } else {
//                    $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
//                    redirect("/");
//                }
//            } else {
//                set_page('manufacture/operation', $data);
//            }
//        } else {
//            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
//            redirect("/");
//        }
//    } 
    
    function save_operation(){
//        echo "<pre>"; print_r($_POST);  exit;
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['operation_id']) && !empty($post_data['operation_id'])) {
            $insert_arr = array();
            $insert_arr['operation_name'] = $post_data['operation_name'];
            if(isset($post_data['fix_loss']) && !empty($post_data['fix_loss'])){
                $f_loss = '1';
                $fix_loss_per = $post_data['fix_loss_per'];
                if(isset($post_data['max_loss_allow']) && !empty($post_data['max_loss_allow'])){
                    $max_loss_allow = '1';
                    $max_loss_wt = $post_data['max_loss_wt'];
                } else {
                    $max_loss_allow = '0';
                    $max_loss_wt = NULL;
                }
            } else {
                $f_loss = '0';
                $fix_loss_per = NULL;
                $max_loss_allow = '0';
                $max_loss_wt = NULL;
            }
            $insert_arr['fix_loss'] = $f_loss;
            $insert_arr['fix_loss_per'] = $fix_loss_per;
            $insert_arr['max_loss_allow'] = $max_loss_allow;
            $insert_arr['max_loss_wt'] = $max_loss_wt;
            if(isset($post_data['issue_finish_fix_loss']) && !empty($post_data['issue_finish_fix_loss'])){
                $issue_finish_fix_loss = '1';
                $issue_finish_fix_loss_per = $post_data['issue_finish_fix_loss_per'];
            } else {
                $issue_finish_fix_loss = '0';
                $issue_finish_fix_loss_per = NULL;
            }
            $insert_arr['issue_finish_fix_loss'] = $issue_finish_fix_loss;
            $insert_arr['issue_finish_fix_loss_per'] = $issue_finish_fix_loss_per;
            $insert_arr['remark'] = $post_data['remark'];
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('operation', $insert_arr, array('operation_id' => $post_data['operation_id']));
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Operation Updated Successfully');
                if(!empty($post_data['department_id'])){
                    $department = $this->crud->getFromSQL("SELECT department_id FROM operation_department WHERE operation_id= ".$post_data['operation_id']." ");
                    $old_ids = array();
                    $new_ids = array();
                    if(!empty($department)){
                        foreach ($department as $depart){
                            $old_ids[] = $depart->department_id;
                        }
                    }
                    foreach ($post_data['department_id'] as $department_id){
                        $old_data = $this->crud->get_row_by_id('operation_department', array('operation_id' => $post_data['operation_id'], 'department_id' => $department_id));
                        if(!empty($old_data)){
                            $dp_arr = array();
                            $dp_arr['updated_at'] = $this->now_time;
                            $dp_arr['updated_by'] = $this->logged_in_id;
                            $this->crud->update('operation_department', $dp_arr, array('operation_id' => $post_data['operation_id'], 'department_id' => $department_id));
                            $new_ids[] = $department_id;
                        } else {
                            $dp_arr = array();
                            $dp_arr['operation_id'] = $post_data['operation_id'];
                            $dp_arr['department_id'] = $department_id;
                            $dp_arr['created_at'] = $this->now_time;
                            $dp_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('operation_department', $dp_arr);
                        }
                    }
                    $ids_for_delete = array_diff($old_ids, $new_ids);
                    if (!empty($ids_for_delete)) {
                        foreach ($ids_for_delete as $id_for_delete) {
                            $this->crud->delete('operation_department', array('operation_id' => $post_data['operation_id'], 'department_id' => $id_for_delete));
                        }
                    }
                }
                if(!empty($post_data['worker_id'])){
                    $worker = $this->crud->getFromSQL("SELECT worker_id FROM operation_worker WHERE operation_id= ".$post_data['operation_id']." ");
                    $w_old_ids = array();
                    $w_new_ids = array();
                    if(!empty($department)){
                        foreach ($worker as $work){
                            $w_old_ids[] = $work->worker_id;
                        }
                    }
                    foreach ($post_data['worker_id'] as $worker_id){
                        $old_data = $this->crud->get_row_by_id('operation_worker', array('operation_id' => $post_data['operation_id'], 'worker_id' => $worker_id));
                        if(!empty($old_data)){
                            $w_arr = array();
                            $w_arr['updated_at'] = $this->now_time;
                            $w_arr['updated_by'] = $this->logged_in_id;
                            $this->crud->update('operation_worker', $w_arr, array('operation_id' => $post_data['operation_id'], 'worker_id' => $worker_id));
                            $w_new_ids[] = $worker_id;
                        } else {
                            $w_arr = array();
                            $w_arr['operation_id'] = $post_data['operation_id'];
                            $w_arr['worker_id'] = $worker_id;
                            $w_arr['created_at'] = $this->now_time;
                            $w_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('operation_worker', $w_arr);
                        }
                    }
                    $ids_for_delete = array();
                    $ids_for_delete = array_diff($w_old_ids, $w_new_ids);
                    if (!empty($ids_for_delete)) {
                        foreach ($ids_for_delete as $id_for_delete) {
                            $this->crud->delete('operation_worker', array('operation_id' => $post_data['operation_id'], 'worker_id' => $id_for_delete));
                        }
                    }
                }
            }
        } else {
            $insert_arr = array();
            $insert_arr['operation_name'] = $post_data['operation_name'];
            if(isset($post_data['fix_loss']) && !empty($post_data['fix_loss'])){
                $f_loss = '1';
                $fix_loss_per = $post_data['fix_loss_per'];
                if(isset($post_data['max_loss_allow']) && !empty($post_data['max_loss_allow'])){
                    $max_loss_allow = '1';
                    $max_loss_wt = $post_data['max_loss_wt'];
                } else {
                    $max_loss_allow = '0';
                    $max_loss_wt = NULL;
                }
            } else {
                $f_loss = '0';
                $fix_loss_per = NULL;
                $max_loss_allow = '0';
                $max_loss_wt = NULL;
            }
            $insert_arr['fix_loss'] = $f_loss;
            $insert_arr['fix_loss_per'] = $fix_loss_per;
            $insert_arr['max_loss_allow'] = $max_loss_allow;
            $insert_arr['max_loss_wt'] = $max_loss_wt;
            if(isset($post_data['issue_finish_fix_loss']) && !empty($post_data['issue_finish_fix_loss'])){
                $issue_finish_fix_loss = '1';
                $issue_finish_fix_loss_per = $post_data['issue_finish_fix_loss_per'];
            } else {
                $issue_finish_fix_loss = '0';
                $issue_finish_fix_loss_per = NULL;
            }
            $insert_arr['issue_finish_fix_loss'] = $issue_finish_fix_loss;
            $insert_arr['issue_finish_fix_loss_per'] = $issue_finish_fix_loss_per;
            $insert_arr['remark'] = $post_data['remark'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('operation', $insert_arr);
            $last_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Operation Added Successfully');
                if(!empty($post_data['department_id'])){
                    foreach ($post_data['department_id'] as $department_id){
                        $dp_arr = array();
                        $dp_arr['operation_id'] = $last_id;
                        $dp_arr['department_id'] = $department_id;
                        $dp_arr['created_at'] = $this->now_time;
                        $dp_arr['created_by'] = $this->logged_in_id;
                        $this->crud->insert('operation_department', $dp_arr);
                    }
                }
                if(!empty($post_data['department_id'])){
                    foreach ($post_data['worker_id'] as $worker_id){
                        $w_arr = array();
                        $w_arr['operation_id'] = $last_id;
                        $w_arr['worker_id'] = $worker_id;
                        $w_arr['created_at'] = $this->now_time;
                        $w_arr['created_by'] = $this->logged_in_id;
                        $this->crud->insert('operation_worker', $w_arr);
                    }
                }
                
            }
        }
        print json_encode($return);
        exit;
    }
    
    function operation_list(){
        if ($this->applib->have_access_role(OPERATION_MODULE_ID, "view")) {
            $data = array();
            set_page('manufacture/operation_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function operation_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'operation o';
        $config['select'] = 'o.*, IF(o.fix_loss = 0,"No","Yes") AS is_fix_loss, IF(o.max_loss_allow = 0,"No","Yes") AS allow_max_loss, IF(o.issue_finish_fix_loss = 0,"No","Yes") AS is_issue_finish_fix_loss';
        $config['column_search'] = array('o.operation_name','IF(o.fix_loss = 0,"No","Yes")','o.fix_loss_per','IF(o.max_loss_allow = 0,"No","Yes")','o.max_loss_wt','IF(o.issue_finish_fix_loss = 0,"No","Yes")','o.issue_finish_fix_loss_per');
        $config['column_order'] = array(null, 'o.operation_name', null, null, 'o.fix_loss', 'o.fix_loss_per','o.max_loss_allow','o.max_loss_wt', null, 'o.issue_finish_fix_loss_per');

        $config['order'] = array('o.operation_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(OPERATION_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(OPERATION_MODULE_ID, "edit");

        foreach ($list as $operation) {
            $row = array();
            $action = '';
            if ($role_edit) {
                $action .= '<a href="' . base_url("manu_hand_made/operation/" . $operation->operation_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if ($role_delete) {
                if ($operation->operation_id != MANUFACTURE_HM_OPERATION_MEENA_ID && $operation->operation_id != MANUFACTURE_HM_OPERATION_NANG_SETTING_ID) {
                    $action .= '<a href="javascript:void(0);" class="delete_operation" data-href="' . base_url('manu_hand_made/delete_operation/' . $operation->operation_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            }
            $row[] = $action;
            $row[] = $operation->operation_name;
            $dp_ids = $this->crud->getFromSQL("SELECT a.account_name FROM operation_department od LEFT JOIN account as a ON od.department_id = a.account_id WHERE od.operation_id = ".$operation->operation_id." ");
            $dp_name = '';
            if(!empty($dp_ids)){
                foreach ($dp_ids as $dp){
                    $dp_name .= $dp->account_name.', ';
                }
            }
            $row[] = $dp_name;
            $w_ids = $this->crud->getFromSQL("SELECT a.account_name FROM operation_worker od LEFT JOIN account as a ON od.worker_id = a.account_id WHERE od.operation_id = ".$operation->operation_id." ");
            $w_name = '';
            if(!empty($w_ids)){
                foreach ($w_ids as $w){
                    $w_name .= $w->account_name.', ';
                }
            }
            $row[] = $w_name;
            $row[] = $operation->is_fix_loss;
            $row[] = $operation->fix_loss_per;
            $row[] = $operation->allow_max_loss;
            $row[] = $operation->max_loss_wt;
            $row[] = $operation->is_issue_finish_fix_loss;
            $row[] = $operation->issue_finish_fix_loss_per;
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
        $return = $this->crud->delete('operation', array('operation_id' => $id));
        if(isset($return['success'])) {
            $where_array = array('operation_id' => $id);
            $this->crud->delete('operation_department', $where_array);
            $this->crud->delete('operation_worker', $where_array); 
        }        
        print json_encode($return);
        exit;

        
    }
    
    function get_operation_detail($operation_id){
        $data = $this->crud->get_data_row_by_id('operation', 'operation_id', $operation_id);
        print json_encode($data);
        exit;
    }
        
    function new_order_item_datatable($department_id = '', $mhm_id = '') {
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
        if(!empty($department_id)){
//            $config['wheres'][] = array('column_name' => 'o.process_id', 'column_value' => $department_id);
            $config['custom_where'] .= ' AND o.process_id = '.$department_id.' ';
        }
        $checked_order_items = array();
        $manu_hand_made_order_items = $this->crud->get_row_by_id('manu_hand_made_order_items', array('mhm_id' => $mhm_id));
        if(!empty($manu_hand_made_order_items)){
            foreach ($manu_hand_made_order_items as $order_item){
                $checked_order_items[] = $order_item->order_lot_item_id;
            }
        }
        $config['custom_where'] .= ' AND li.item_status_id = 1 ';
        if(!empty($checked_order_items)){
            $checked_order_items = implode(',', $checked_order_items);
            $config['custom_where'] .= ' OR (li.order_lot_item_id IN('.$checked_order_items.'))';
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
            $row['account_name'] = $lot_detail->account_name. ' - ' .$lot_detail->account_mobile;
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
    
    function manu_hand_made($mhm_id = '') {
        $data = array();
        $manu_hand_made_detail = new \stdClass();
        $items = $this->crud->get_all_records('item_master', 'item_id', '');
        $data['items'] = $items;
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');
        $data['touch'] = $touch;
        $data['gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'gold_rate'));
        $data['worker_gold_rate'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'worker_gold_rate'));
        $data['without_purchase_sell_allow'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        $data['manufacture_lott_complete_in'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'manufacture_lott_complete_in'));
        if (!empty($mhm_id)) {
            if($this->applib->have_access_role(ISSUE_RECEIVE_MODULE_ID,"edit")) {
                $manu_hand_made_data = $this->crud->get_row_by_id('manu_hand_made', array('mhm_id' => $mhm_id));
                $manu_hand_made_order_items = $this->crud->get_row_by_id('manu_hand_made_order_items', array('mhm_id' => $mhm_id));
                $manu_hand_made_details = $this->crud->get_row_by_id('manu_hand_made_details', array('mhm_id' => $mhm_id));
                $manu_hand_made_data = $manu_hand_made_data[0];

                $manu_hand_made_data ->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$manu_hand_made_data->created_by));
                if($manu_hand_made_data->created_by != $manu_hand_made_data->updated_by){
                    $manu_hand_made_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $manu_hand_made_data->updated_by));
                }else{
                    $manu_hand_made_data->updated_by_name = $manu_hand_made_data->created_by_name;
                }
                if(!empty($manu_hand_made_data ->worker_gold_rate)){
                    $data['worker_gold_rate'] = $manu_hand_made_data ->worker_gold_rate;
                }
                
                $data['mhm_data'] = $manu_hand_made_data;
                
                $checked_order_items = array();
                if(!empty($manu_hand_made_order_items)){
                    foreach ($manu_hand_made_order_items as $order_item){
                        $checked_order_items[] = json_encode($order_item);
                    }
                }
                $data['checked_order_items'] = implode(',', $checked_order_items);
                
                $lineitems = array();
                foreach($manu_hand_made_details as $detail){
                    
                    $manu_hand_made_detail->mhm_item_delete = 'allow';
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){

                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_transfer)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_transfer = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_transfer)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_issue_receive)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_issue_receive = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_issue_receive)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_manu_hand_made)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
                        $item_manu_hand_made = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_manu_hand_made)){
                            $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                        }
//                        echo '<pre>'. $this->db->last_query(); exit;
//                        echo '<pre>'; print_r($manu_hand_made_detail->mhm_item_delete); exit;
                        $sell_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM sell_items si JOIN sell s ON s.sell_id = si.sell_item_id WHERE si.purchase_sell_item_id ="'.$detail->mhm_detail_id.'" AND s.process_id = "'.$manu_hand_made_data->department_id.'"');
                        $stock_transfer_info = $this->crud->getFromSQL('SELECT SUM(si.grwt) as total_grwt FROM stock_transfer_detail si JOIN stock_transfer s ON s.stock_transfer_id = si.stock_transfer_id WHERE si.purchase_sell_item_id ="'.$detail->mhm_detail_id.'" AND s.from_department = "'.$manu_hand_made_data->department_id.'"');
                        $issue_receive_info = $this->crud->getFromSQL('SELECT SUM(ird.weight) as total_grwt FROM issue_receive_details ird JOIN issue_receive ir ON ir.ir_id = ird.ir_id WHERE ird.purchase_sell_item_id ="'.$detail->mhm_detail_id.'" AND ir.department_id = "'.$manu_hand_made_data->department_id.'"');
                        $manu_hand_made_info = $this->crud->getFromSQL('SELECT SUM(mhm_detail.weight) as total_grwt FROM manu_hand_made_details mhm_detail JOIN manu_hand_made mhm ON mhm.mhm_id = mhm_detail.mhm_id WHERE mhm_detail.purchase_sell_item_id ="'.$detail->mhm_detail_id.'" AND mhm.department_id = "'.$manu_hand_made_data->department_id.'"');
                        $manu_hand_made_detail->total_grwt_sell = $sell_info[0]->total_grwt + $stock_transfer_info[0]->total_grwt + $issue_receive_info[0]->total_grwt + $manu_hand_made_info[0]->total_grwt;
                    } else {
                        if($data['without_purchase_sell_allow'] == '1'){
                            $total_sell_grwt = $this->crud->get_total_sell_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_transfer_grwt = $this->crud->get_total_transfer_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_metal_grwt = $this->crud->get_total_metal_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                            $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id);
                            $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
                            $used_lineitem_ids = array();
                            $manu_hand_made_detail->total_grwt_sell = 0;
                            if(!empty($total_sell_grwt)){
                                $purchase_items = $this->crud->get_purchase_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $metal_items = $this->crud->get_metal_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $receive_items = $this->crud->get_receive_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id, $detail->tunch);
                                $other_purchase_items = $this->crud->get_other_purchase_items_grwt($manu_hand_made_data->department_id, $detail->category_id, $detail->item_id);
                                $receive_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
    //                            echo '<pre>'; print_r($receive_delete_array); exit;
                                uasort($receive_delete_array, function($a, $b) {
                                    $value1 = strtotime($a->created_at);
                                    $value2 = strtotime($b->created_at);
                                    return $value1 - $value2;
                                });
    //                            print_r($receive_delete_array); exit;
                                $purchase_grwt = 0;
                                $first_check_receive_grwt = 0;

                                foreach ($receive_delete_array as $receive_item){
                                    $purchase_grwt = $purchase_grwt + $receive_item->grwt;
                                    if($purchase_grwt >= $total_sell_grwt && $first_check_receive_grwt == 0){
                                        if($receive_item->type == 'MHM_R'){
                                            $used_lineitem_ids[] = $receive_item->mhm_detail_id;
                                            if($detail->mhm_detail_id == $receive_item->mhm_detail_id){
                                                $manu_hand_made_detail->total_grwt_sell = (float) $total_sell_grwt - (float) $purchase_grwt + (float) $receive_item->grwt;
                                            }
                                        }
                                        $first_check_receive_grwt = 1;
                                    } else if($purchase_grwt <= $total_sell_grwt){
                                        if($receive_item->type == 'MHM_R'){
                                            $used_lineitem_ids[] = $receive_item->mhm_detail_id;
                                            if($detail->mhm_detail_id == $receive_item->mhm_detail_id){
                                                $manu_hand_made_detail->total_grwt_sell = $receive_item->grwt;
                                            }
                                        }
                                    }
                                }
                            }
                            if(!empty($used_lineitem_ids) && in_array($detail->mhm_detail_id, $used_lineitem_ids)){
                                $manu_hand_made_detail->mhm_item_delete = 'not_allow';
                            }
                        }
                    }
//                  
                    $detail_type = '';
                    if($detail->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID){
                        $detail_type = 'Issue Finish Work';
                    } else if($detail->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                        $detail_type = 'Issue Scrap';
                    } else if($detail->type_id == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                        $detail_type = 'Receive Finish Work';
                    } else if($detail->type_id == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
                        $detail_type = 'Receive Scrap';
                    }
                    $manu_hand_made_detail->type_name = $detail_type;
                    $manu_hand_made_detail->item_name = $this->crud->get_column_value_by_id('item_master', 'item_name', array('item_id' => $detail->item_id));
                    $manu_hand_made_detail->purity = $detail->tunch;
                    $manu_hand_made_detail->weight = $detail->weight;
                    $manu_hand_made_detail->grwt = $detail->weight;
                    $manu_hand_made_detail->less = $detail->less;
                    $manu_hand_made_detail->net_wt = $detail->net_wt;
                    $manu_hand_made_detail->actual_tunch = !empty($detail->actual_tunch) ? $detail->actual_tunch : 0;
                    $manu_hand_made_detail->fine = $detail->fine;
                    $manu_hand_made_detail->pcs = $detail->pcs;
                    $manu_hand_made_detail->ad_weight = !empty($detail->ad_weight) ? $detail->ad_weight : 0;
                    $manu_hand_made_detail->including_ad_wt = (isset($detail->including_ad_wt) && $detail->including_ad_wt == '1') ? '1' : '0';
                    $manu_hand_made_detail->mhm_detail_date = $detail->mhm_detail_date ? date('d-m-Y', strtotime($detail->mhm_detail_date)) : '';
                    $manu_hand_made_detail->mhm_detail_remark = $detail->mhm_detail_remark;
                    $manu_hand_made_detail->mhm_detail_id = $detail->mhm_detail_id;
                    $manu_hand_made_detail->type_id = $detail->type_id;
                    $manu_hand_made_detail->item_id = $detail->item_id;
                    $manu_hand_made_detail->touch_id = $detail->tunch;
                    $manu_hand_made_detail->wstg = '0';
                    $manu_hand_made_detail->tunch_textbox = (isset($detail->tunch_textbox) && $detail->tunch_textbox == '1') ? '1' : '0';
                    $manu_hand_made_detail->purchase_sell_item_id = $detail->purchase_sell_item_id;
                    $manu_hand_made_detail->stock_type = $detail->stock_type;
                    $lineitems[] = json_encode($manu_hand_made_detail);
                }
                $data['manu_hand_made_detail'] = implode(',', $lineitems);
                
                $manu_hand_made_ads = $this->crud->get_row_by_id('manu_hand_made_ads', array('mhm_id' => $mhm_id));
                $ad_lineitems = array();
                foreach($manu_hand_made_ads as $ad_row){
                    $ad_row->ad_lineitem_delete = 'allow';
                    $ad_row->ad_name = $this->crud->get_column_value_by_id('ad', 'ad_name', array('ad_id' => $ad_row->ad_id));
                    $ad_row->ad_pcs = $ad_row->ad_pcs;
                    $ad_row->ad_rate = $ad_row->ad_rate;
                    $ad_row->ad_amount = $ad_row->ad_amount;
                    $ad_lineitems[] = json_encode($ad_row);
                }
                $data['manu_hand_made_ads'] = implode(',', $ad_lineitems);
                set_page('manufacture/manu_hand_made', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"add")) {
                $lineitems = array();
                set_page('manufacture/manu_hand_made', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }
    
    function save_manu_hand_made() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
        $ad_lineitem_data = json_decode($post_data['ad_lineitem_data']);
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
        $post_data['mhm_date'] = isset($post_data['mhm_date']) && !empty($post_data['mhm_date']) ? date('Y-m-d', strtotime($post_data['mhm_date'])) : null;
        $post_data['total_issue_net_wt'] = number_format((float) $post_data['total_issue_net_wt'], '3', '.', '');
        $post_data['total_receive_net_wt'] = number_format((float) $post_data['total_receive_net_wt'], '3', '.', '');
        $post_data['total_issue_fine'] = number_format((float) $post_data['total_issue_fine'], '3', '.', '');
        $post_data['total_receive_fine'] = number_format((float) $post_data['total_receive_fine'], '3', '.', '');
        
        $manufacture_lott_complete_in = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'manufacture_lott_complete_in'));

        //Update chhijjat_per_100_ad value
        if(isset($post_data['chhijjat_per_100_ad'])){
            $this->crud->update('account', array('chhijjat_per_100_ad' => $post_data['chhijjat_per_100_ad']), array('account_id' => $post_data['worker_id']));
        }
        
        //Update meena_charges value
        if(isset($post_data['meena_charges'])){
            $this->crud->update('account', array('meena_charges' => $post_data['meena_charges']), array('account_id' => $post_data['worker_id']));
        }
        
        if (isset($post_data['mhm_id']) && !empty($post_data['mhm_id'])) {
            
            // Increase fine in Account And Department
            $this->update_account_and_department_balance_on_update($post_data['mhm_id']);
            // Decrese fine in Item Stock on lineitem edit
            $this->update_stock_on_manufacture_update($post_data['mhm_id']);
            // Revert Journal for MHM diff. Amount // Ad Amount // Meena Charges
            $old_worker_id = $this->crud->get_column_value_by_id('manu_hand_made','worker_id',array('mhm_id' => $post_data['mhm_id']));
            $this->revert_journal_for_mhm_diffrence_amount($post_data['mhm_id'], $old_worker_id);
            
            $post_data['department_id'] = $this->crud->get_column_value_by_id('manu_hand_made','department_id',array('mhm_id' => $post_data['mhm_id']));
            $update_arr = array();
            $update_arr['department_id'] = $post_data['department_id'];
            $update_arr['operation_id'] = $post_data['operation_id'];
            $update_arr['worker_id'] = $post_data['worker_id'];
            $update_arr['mhm_date'] = $post_data['mhm_date'];
            if(isset($post_data['lott_complete'])){
                $update_arr['lott_complete'] = $post_data['lott_complete'];
            }
            $update_arr['mhm_diffrence'] = $post_data['mhm_diffrence'];
            if(isset($post_data['worker_gold_rate'])){
                $update_arr['worker_gold_rate'] = $post_data['worker_gold_rate'];
            }
            $update_arr['mhm_remark']= $post_data['mhm_remark'];
            $update_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $update_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $update_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $update_arr['total_receive_fine']= $post_data['total_receive_fine'];
            if(isset($post_data['audit_status']) && $post_data['audit_status'] == 'on'){
                $update_arr['audit_status'] = '2';
            } else {
                $update_arr['audit_status'] = '1';
            }
            $update_arr['updated_at'] = $this->now_time;
            $update_arr['updated_by'] = $this->logged_in_id;
            $where_array['mhm_id'] = $post_data['mhm_id'];

            $result = $this->crud->update('manu_hand_made', $update_arr, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Manufacture Hand Made Updated Successfully');
                
                if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID){
                    if(!empty($post_data['total_ad_amount_for_journal']) && !empty($post_data['total_ad_amount_for_journal'])){
                        $total_ad_amount_for_journal = number_format((float) $post_data['total_ad_amount_for_journal'], '2', '.', '');
                        if(isset($post_data['lott_complete']) && $post_data['lott_complete'] == 1 && $total_ad_amount_for_journal != 0){
                            $this->add_journal_for_mhm_ad_amount($post_data['mhm_id'], $post_data['department_id'], $post_data['worker_id'], $total_ad_amount_for_journal);
                        }
                    }
                } else if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_MEENA_ID){
                    if(!empty($post_data['total_meena_charges_for_journal']) && !empty($post_data['total_meena_charges_for_journal'])){
                        $total_meena_charges_for_journal = number_format((float) $post_data['total_meena_charges_for_journal'], '2', '.', '');
                        if(isset($post_data['lott_complete']) && $post_data['lott_complete'] == 1 && $total_meena_charges_for_journal != 0){
                            $this->add_journal_for_mhm_meena_charges_amount($post_data['mhm_id'], $post_data['department_id'], $post_data['worker_id'], $total_meena_charges_for_journal);
                        }
                    }
                } else {
                    if(!empty($post_data['mhm_diffrence']) && !empty($post_data['worker_gold_rate'])){
                        $mhm_diffrence_amount = $post_data['mhm_diffrence'] * $post_data['worker_gold_rate'] / 10;
                        $mhm_diffrence_amount = number_format((float) $mhm_diffrence_amount, '2', '.', '');
                        if(isset($post_data['lott_complete']) && $post_data['lott_complete'] == 1 && $mhm_diffrence_amount != 0){
                            $this->add_journal_for_mhm_diffrence_amount($post_data['mhm_id'], $post_data['department_id'], $post_data['worker_id'], $mhm_diffrence_amount);
                        }
                        if(!empty($post_data['lott_complete_sms'])){
                            $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['worker_id']);
                            if(!empty($mobile_no)){
                                if($mhm_diffrence_amount > 0){
                                    $sms = SEND_MHM_LOTT_COMPLETE_DEBITED_AMOUNT;
                                } else {
                                    $mhm_diffrence_amount = abs($mhm_diffrence_amount);
                                    $sms = SEND_MHM_LOTT_COMPLETE_CREDITED_AMOUNT;
                                }
                                $vars = array(
                                    '{{mhm_diffrence_amount}}' => $mhm_diffrence_amount,
                                );
                                $sms = strtr($sms, $vars);
                                $this->applib->send_sms($mobile_no, $sms, 'manu_hand_made');
                            }
                        }
                    }
                }
                
                $update_mhm_oi_ids = array('0');
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if(!empty($checked_order_items)){
                    foreach ($checked_order_items as $order_item){
                        $order_item_arr = array();
                        $order_item_arr['mhm_id'] = $post_data['mhm_id'];
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        if(isset($order_item->mhm_oi_id) && !empty($order_item->mhm_oi_id)){
                            $this->db->where('mhm_oi_id', $order_item->mhm_oi_id);
                            $this->db->update('manu_hand_made_order_items', $order_item_arr);
                            $update_mhm_oi_ids[] = $order_item->mhm_oi_id;
                        } else {
                            $order_item_arr['created_at'] = $this->now_time;
                            $order_item_arr['created_by'] = $this->logged_in_id;
                            $this->crud->insert('manu_hand_made_order_items', $order_item_arr);
                            $update_mhm_oi_ids[] = $this->db->insert_id();
                        }
                    }
                }
                $this->db->where('mhm_id', $post_data['mhm_id']);
                $this->db->where_not_in('mhm_oi_id', $update_mhm_oi_ids);
                $this->db->delete('manu_hand_made_order_items');
                
                if(isset($post_data['deleted_lineitem_id'])){
                    $this->db->where_in('mhm_detail_id', $post_data['deleted_lineitem_id']);
                    $this->db->delete('manu_hand_made_details');
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $update_item = array();
                        $update_item['mhm_id'] = $post_data['mhm_id'];
                        $update_item['type_id'] = $lineitem->type_id;
                        $update_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $update_item['item_id'] = $lineitem->item_id;
                        $update_item['tunch'] = $lineitem->purity;
                        $update_item['weight'] = $lineitem->weight;
                        $update_item['less'] = $lineitem->less;
                        $update_item['net_wt'] = $lineitem->net_wt;
                        $update_item['actual_tunch'] = $lineitem->actual_tunch;
                        $update_item['fine'] = $lineitem->fine;
                        $update_item['pcs'] = $lineitem->pcs;
                        $update_item['ad_weight'] = !empty($lineitem->ad_weight) ? $lineitem->ad_weight : '0';
                        $update_item['including_ad_wt'] = (isset($lineitem->including_ad_wt) && $lineitem->including_ad_wt == '1') ? '1' : '0';
                        $update_item['mhm_detail_date'] = !empty($lineitem->mhm_detail_date) ? date('Y-m-d', strtotime($lineitem->mhm_detail_date)) : null;
                        $update_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $update_item['mhm_detail_remark'] = $lineitem->mhm_detail_remark;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $update_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                            if(isset($lineitem->stock_type)){
                                $update_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $update_item['updated_at'] = $this->now_time;
                        $update_item['updated_by'] = $this->logged_in_id;
                        if(isset($lineitem->mhm_detail_id) && !empty($lineitem->mhm_detail_id)){
                            $this->db->where('mhm_detail_id', $lineitem->mhm_detail_id);
                            $this->db->update('manu_hand_made_details', $update_item);
                        } else {
                            $update_item['created_at'] = $this->now_time;
                            $update_item['created_by'] = $this->logged_in_id;
                            $this->crud->insert('manu_hand_made_details', $update_item);
                            $last_inserted_id = $this->db->insert_id();
                            $line_items_data[$key]->purchase_item_id = $last_inserted_id;
                        }
                        $line_items_data[$key]->category_id = $update_item['category_id'];
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }
                    
                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if(!empty($account)){
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }
                    
                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data,$post_data['department_id']);
                }
                
                if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID){
                    if(isset($post_data['deleted_ad_lineitem_id'])){
                        $this->db->where_in('mhm_ad_id', $post_data['deleted_ad_lineitem_id']);
                        $this->db->delete('manu_hand_made_ads');
                    }
                    if (!empty($ad_lineitem_data)) {
                        foreach ($ad_lineitem_data as $key => $ad_lineitem) {
                            $update_item = array();
                            $update_item['mhm_id'] = $post_data['mhm_id'];
                            $update_item['ad_id'] = $ad_lineitem->ad_id;
                            $update_item['ad_pcs'] = $ad_lineitem->ad_pcs;
                            $update_item['ad_rate'] = $ad_lineitem->ad_rate;
                            $update_item['ad_amount'] = $ad_lineitem->ad_amount;
                            $update_item['updated_at'] = $this->now_time;
                            $update_item['updated_by'] = $this->logged_in_id;
                            if(isset($ad_lineitem->mhm_ad_id) && !empty($ad_lineitem->mhm_ad_id)){
                                $this->db->where('mhm_ad_id', $ad_lineitem->mhm_ad_id);
                                $this->db->update('manu_hand_made_ads', $update_item);
                            } else {
                                $update_item['created_at'] = $this->now_time;
                                $update_item['created_by'] = $this->logged_in_id;
                                $this->crud->insert('manu_hand_made_ads', $update_item);
                                $last_inserted_id = $this->db->insert_id();
                            }
                        }
                    }
                } else {
                    $this->db->where_in('mhm_id', $post_data['mhm_id']);
                    $this->db->delete('manu_hand_made_ads');
                }
            }
        } else {

            $insert_arr = array();
            $reference = $this->crud->get_max_number('manu_hand_made', 'reference_no');
            $reference_no = 1;
            if ($reference->reference_no > 0) {
                $reference_no = $reference->reference_no + 1;
            }
            $insert_arr['department_id'] = $post_data['department_id'];
            $insert_arr['operation_id'] = $post_data['operation_id'];
            $insert_arr['worker_id'] = $post_data['worker_id'];
            $insert_arr['mhm_date'] = $post_data['mhm_date'];
            $insert_arr['reference_no'] = $reference_no;
            if(isset($post_data['lott_complete'])){
                $insert_arr['lott_complete'] = $post_data['lott_complete'];
            }
            $insert_arr['mhm_diffrence'] = $post_data['mhm_diffrence'];
            if(isset($post_data['worker_gold_rate'])){
                $insert_arr['worker_gold_rate'] = $post_data['worker_gold_rate'];
            }
            $insert_arr['mhm_remark'] = $post_data['mhm_remark'];
            $insert_arr['total_issue_net_wt']= $post_data['total_issue_net_wt'];
            $insert_arr['total_receive_net_wt']= $post_data['total_receive_net_wt'];
            $insert_arr['total_issue_fine']= $post_data['total_issue_fine'];
            $insert_arr['total_receive_fine']= $post_data['total_receive_fine'];
            if(isset($post_data['audit_status']) && $post_data['audit_status'] == 'on'){
                $insert_arr['audit_status'] = '2';
            } else {
                $insert_arr['audit_status'] = '1';
            }
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            
            $result = $this->crud->insert('manu_hand_made', $insert_arr);
            $mhm_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $return['mhm_id'] = $mhm_id;
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Manufacture Hand Made Added Successfully');
                
                if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID){
                    if(!empty($post_data['total_ad_amount_for_journal']) && !empty($post_data['total_ad_amount_for_journal'])){
                        $total_ad_amount_for_journal = number_format((float) $post_data['total_ad_amount_for_journal'], '2', '.', '');
                        if($post_data['lott_complete'] == 1 && $total_ad_amount_for_journal != 0){
                            $this->add_journal_for_mhm_ad_amount($mhm_id, $post_data['department_id'], $post_data['worker_id'], $total_ad_amount_for_journal);
                        }
                    }
                } else if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_MEENA_ID){
                    if(!empty($post_data['total_meena_charges_for_journal']) && !empty($post_data['total_meena_charges_for_journal'])){
                        $total_meena_charges_for_journal = number_format((float) $post_data['total_meena_charges_for_journal'], '2', '.', '');
                        if($post_data['lott_complete'] == 1 && $total_meena_charges_for_journal != 0){
                            $this->add_journal_for_mhm_meena_charges_amount($mhm_id, $post_data['department_id'], $post_data['worker_id'], $total_meena_charges_for_journal);
                        }
                    }
                } else {
                    if($manufacture_lott_complete_in == '3'){
                        if(!empty($post_data['mhm_diffrence']) && isset($post_data['worker_gold_rate']) && !empty($post_data['worker_gold_rate'])){
                            $mhm_diffrence_amount = $post_data['mhm_diffrence'] * $post_data['worker_gold_rate'] / 10;
                            $mhm_diffrence_amount = number_format((float) $mhm_diffrence_amount, '2', '.', '');
                            if($post_data['lott_complete'] == 1 && $mhm_diffrence_amount != 0){
                                $this->add_journal_for_mhm_diffrence_amount($mhm_id, $post_data['department_id'], $post_data['worker_id'], $mhm_diffrence_amount);
                            }
                            if(!empty($post_data['lott_complete_sms'])){
                                $mobile_no = $this->crud->get_id_by_val('account', 'account_mobile', 'account_id', $post_data['worker_id']);
                                if(!empty($mobile_no)){
                                    if($mhm_diffrence_amount > 0){
                                        $sms = SEND_MHM_LOTT_COMPLETE_DEBITED_AMOUNT;
                                    } else {
                                        $mhm_diffrence_amount = abs($mhm_diffrence_amount);
                                        $sms = SEND_MHM_LOTT_COMPLETE_CREDITED_AMOUNT;
                                    }
                                    $vars = array(
                                        '{{mhm_diffrence_amount}}' => $mhm_diffrence_amount,
                                    );
                                    $sms = strtr($sms, $vars);
                                    $this->applib->send_sms($mobile_no, $sms, 'manu_hand_made');
                                }
                            }
                        }
                    }
                }
                
                $checked_order_items = json_decode($post_data['checked_order_items']);
                if(!empty($checked_order_items)){
                    foreach ($checked_order_items as $order_item){
                        $order_item_arr = array();
                        $order_item_arr['mhm_id'] = $mhm_id;
                        $order_item_arr['order_id'] = $order_item->order_id;
                        $order_item_arr['order_lot_item_id'] = $order_item->order_lot_item_id;
                        $order_item_arr['created_at'] = $this->now_time;
                        $order_item_arr['created_by'] = $this->logged_in_id;
                        $order_item_arr['updated_at'] = $this->now_time;
                        $order_item_arr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('manu_hand_made_order_items', $order_item_arr);
                    }
                }
                
                $total_gold_fine = 0;
                $total_silver_fine = 0;
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['mhm_id'] = $mhm_id;
                        $insert_item['type_id'] = $lineitem->type_id;
                        $insert_item['category_id'] = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $lineitem->item_id));
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['tunch'] = $lineitem->purity;
                        $insert_item['weight'] = $lineitem->weight;
                        $insert_item['less'] = $lineitem->less;
                        $insert_item['net_wt'] = $lineitem->net_wt;
                        $insert_item['actual_tunch'] = $lineitem->actual_tunch;
                        $insert_item['fine'] = $lineitem->fine;
                        $insert_item['pcs'] = $lineitem->pcs;
                        $insert_item['ad_weight'] = !empty($lineitem->ad_weight) ? $lineitem->ad_weight : '0';
                        $insert_item['including_ad_wt'] = (isset($lineitem->including_ad_wt) && $lineitem->including_ad_wt == '1') ? '1' : '0';
                        $insert_item['mhm_detail_date'] = !empty($lineitem->mhm_detail_date) ? date('Y-m-d', strtotime($lineitem->mhm_detail_date)) : null;
                        $insert_item['tunch_textbox'] = (isset($lineitem->tunch_textbox) && $lineitem->tunch_textbox == '1') ? '1' : '0';
                        $insert_item['mhm_detail_remark'] = $lineitem->mhm_detail_remark;
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $insert_item['purchase_sell_item_id'] = $lineitem->purchase_sell_item_id;
                        }
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $line_items_data[$key]->grwt = $lineitem->weight;
                        $line_items_data[$key]->touch_id = $lineitem->purity;
                        $line_items_data[$key]->category_id = $insert_item['category_id'];
                        $line_items_data[$key]->stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $lineitem->item_id));
                        if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID) && $line_items_data[$key]->stock_method == STOCK_METHOD_ITEM_WISE){
                            if(isset($lineitem->stock_type)){
                                $insert_item['stock_type'] = $lineitem->stock_type;
                            }
                        }
                        $this->crud->insert('manu_hand_made_details', $insert_item);
                        $last_inserted_item_id = $this->db->insert_id();
                        $line_items_data[$key]->purchase_item_id = $last_inserted_item_id;
                        
                        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        } else {
                            if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                                $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                                $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                            }
                        }
                    }
                    //Update Account Balance
                    $account = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['worker_id']);
                    if(!empty($account)){
                        $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                        $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $post_data['worker_id']));
                    }
                    
                    //Update Department Balance
                    $department = $this->crud->get_data_row_by_id('account', 'account_id', $post_data['department_id']);
                    if(!empty($department)){
                        $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                        $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                        $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                        $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $post_data['department_id']));
                    }
                    
                    $this->update_stock_on_manufacture_insert($line_items_data, $post_data['department_id']);
                }
                
                if($post_data['operation_id'] == MANUFACTURE_HM_OPERATION_NANG_SETTING_ID){
                    if (!empty($ad_lineitem_data)) {
                        foreach ($ad_lineitem_data as $key => $ad_lineitem) {
                            $insert_item = array();
                            $insert_item['mhm_id'] = $mhm_id;
                            $insert_item['ad_id'] = $ad_lineitem->ad_id;
                            $insert_item['ad_pcs'] = $ad_lineitem->ad_pcs;
                            $insert_item['ad_rate'] = $ad_lineitem->ad_rate;
                            $insert_item['ad_amount'] = $ad_lineitem->ad_amount;
                            $insert_item['created_at'] = $this->now_time;
                            $insert_item['created_by'] = $this->logged_in_id;
                            $insert_item['updated_at'] = $this->now_time;
                            $insert_item['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('manu_hand_made_ads', $insert_item);
                            $last_inserted_id = $this->db->insert_id();
                        }
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function revert_journal_for_mhm_diffrence_amount($mhm_id, $worker_id) {
        $journal_id = $this->crud->get_column_value_by_id('manu_hand_made', 'journal_id', array('mhm_id' => $mhm_id));
        if (!empty($journal_id)) {
            $journal_id = $this->crud->get_column_value_by_id('journal_details', 'journal_id', array('journal_id' => $journal_id));
            if ($journal_id) {
                
                $worker_journal_details = $this->crud->get_row_by_id('journal_details', array('journal_id' => $journal_id, 'account_id' => $worker_id));
                if(!empty($worker_journal_details)){
                    $worker_journal_details = $worker_journal_details[0];
                    if($worker_journal_details->type == 1){
                        
                        // Update Worker Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
                        $account_amount = $account_amount - $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));
                        
                        // Update MF Loss Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        $account_amount = $account_amount + $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        
                    } else {
                        
                        // Update Worker Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
                        $account_amount = $account_amount + $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));
                        
                        // Update MF Loss Account Amount
                        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        $account_amount = $account_amount - $worker_journal_details->amount;
                        $account_amount = number_format((float) $account_amount, '2', '.', '');
                        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
                        
                    }
                    $this->crud->delete('journal_details', array('journal_id' => $journal_id));
                }
                $this->crud->delete('journal', array('journal_id' => $journal_id));
            }
        }
    }
    
    function add_journal_for_mhm_diffrence_amount($mhm_id, $department_id, $worker_id, $mhm_diffrence_amount) {
        // Add Journal
        $journal_array = array(
            'department_id' => $department_id,
            'journal_date' => date('Y-m-d'),
            'relation_id' => $mhm_id,
            'is_module' => MHM_TO_JOURNAL_ID,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $result = $this->crud->insert('journal', $journal_array);
        $journal_id = $this->db->insert_id();
        $this->crud->update('manu_hand_made', array('journal_id' => $journal_id), array('mhm_id' => $mhm_id));
        $reference_no = $this->crud->get_column_value_by_id('manu_hand_made','reference_no',array('mhm_id' => $mhm_id));
        if($mhm_diffrence_amount > 0){  // If Diff Positive : Debit in Worker and Credit in MF Loss
            
            // Add Worker Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 2,
                'account_id' => $worker_id,
                'amount' => $mhm_diffrence_amount,
                'narration' => 'Manufacture Hand Made : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update Worker Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
            $account_amount = $account_amount - $mhm_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

            // Add MF Loss Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 1,
                'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
                'amount' => $mhm_diffrence_amount,
                'narration' => 'Manufacture Hand Made : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update MF Loss Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
            $account_amount = $account_amount + $mhm_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
        
        } else {   // If Diff Negative : Credit in Worker and Debit in MF Loss
            $mhm_diffrence_amount = abs($mhm_diffrence_amount);
            // Add Worker Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 1,
                'account_id' => $worker_id,
                'amount' => $mhm_diffrence_amount,
                'narration' => 'Manufacture Hand Made : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update Worker Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
            $account_amount = $account_amount + $mhm_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

            // Add MF Loss Journal Detail
            $journal_details_array = array(
                'journal_id' => $journal_id,
                'type' => 2,
                'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
                'amount' => $mhm_diffrence_amount,
                'narration' => 'Manufacture Hand Made : Reference No : ' . $reference_no,
                'created_at' => $this->now_time,
                'created_by' => $this->logged_in_id,
                'updated_at' => $this->now_time,
                'updated_by' => $this->logged_in_id,
            );
            $this->crud->insert('journal_details', $journal_details_array);

            // Update MF Loss Account Amount
            $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
            $account_amount = $account_amount - $mhm_diffrence_amount;
            $account_amount = number_format((float) $account_amount, '2', '.', '');
            $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
            
        }
    }
    
    function add_journal_for_mhm_ad_amount($mhm_id, $department_id, $worker_id, $total_ad_amount_for_journal) {
        // Add Journal
        $journal_array = array(
            'department_id' => $department_id,
            'journal_date' => date('Y-m-d'),
            'relation_id' => $mhm_id,
            'is_module' => MHM_TO_JOURNAL_ID,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $result = $this->crud->insert('journal', $journal_array);
        $journal_id = $this->db->insert_id();
        $this->crud->update('manu_hand_made', array('journal_id' => $journal_id), array('mhm_id' => $mhm_id));
        $reference_no = $this->crud->get_column_value_by_id('manu_hand_made','reference_no',array('mhm_id' => $mhm_id));
        // Credit in Worker and Debit in MF Loss
        $total_ad_amount_for_journal = abs($total_ad_amount_for_journal);
        // Add Worker Journal Detail
        $journal_details_array = array(
            'journal_id' => $journal_id,
            'type' => 2,
            'account_id' => $worker_id,
            'amount' => $total_ad_amount_for_journal,
            'narration' => 'Manufacture Hand Made Ad Amount : Reference No : ' . $reference_no,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $this->crud->insert('journal_details', $journal_details_array);

        // Update Worker Account Amount
        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
        $account_amount = $account_amount - $total_ad_amount_for_journal;
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

        // Add MF Loss Journal Detail
        $journal_details_array = array(
            'journal_id' => $journal_id,
            'type' => 1,
            'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
            'amount' => $total_ad_amount_for_journal,
            'narration' => 'Manufacture Hand Made Ad Amount : Reference No : ' . $reference_no,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $this->crud->insert('journal_details', $journal_details_array);

        // Update MF Loss Account Amount
        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
        $account_amount = $account_amount + $total_ad_amount_for_journal;
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
    }
    
    function add_journal_for_mhm_meena_charges_amount($mhm_id, $department_id, $worker_id, $total_meena_charges_for_journal) {
        // Add Journal
        $journal_array = array(
            'department_id' => $department_id,
            'journal_date' => date('Y-m-d'),
            'relation_id' => $mhm_id,
            'is_module' => MHM_TO_JOURNAL_ID,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $result = $this->crud->insert('journal', $journal_array);
        $journal_id = $this->db->insert_id();
        $this->crud->update('manu_hand_made', array('journal_id' => $journal_id), array('mhm_id' => $mhm_id));
        $reference_no = $this->crud->get_column_value_by_id('manu_hand_made','reference_no',array('mhm_id' => $mhm_id));
        // Credit in Worker and Debit in MF Loss
        $total_meena_charges_for_journal = abs($total_meena_charges_for_journal);
        // Add Worker Journal Detail
        $journal_details_array = array(
            'journal_id' => $journal_id,
            'type' => 2,
            'account_id' => $worker_id,
            'amount' => $total_meena_charges_for_journal,
            'narration' => 'Manufacture Hand Made Meena Charge : Reference No : ' . $reference_no,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $this->crud->insert('journal_details', $journal_details_array);

        // Update Worker Account Amount
        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => $worker_id));
        $account_amount = $account_amount - $total_meena_charges_for_journal;
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => $worker_id));

        // Add MF Loss Journal Detail
        $journal_details_array = array(
            'journal_id' => $journal_id,
            'type' => 1,
            'account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID,
            'amount' => $total_meena_charges_for_journal,
            'narration' => 'Manufacture Hand Made Meena Charge : Reference No : ' . $reference_no,
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
            'updated_at' => $this->now_time,
            'updated_by' => $this->logged_in_id,
        );
        $this->crud->insert('journal_details', $journal_details_array);

        // Update MF Loss Account Amount
        $account_amount = $this->crud->get_column_value_by_id('account', 'amount', array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
        $account_amount = $account_amount + $total_meena_charges_for_journal;
        $account_amount = number_format((float) $account_amount, '2', '.', '');
        $this->crud->update('account', array('amount' => $account_amount), array('account_id' => MF_LOSS_EXPENSE_ACCOUNT_ID));
    }

    function update_stock_on_manufacture_insert($lineitem_data ,$department_id){
//        echo '<pre>'; print_r($lineitem_data);
//        echo '<pre>'; print_r($department_id); exit;
        if (!empty($lineitem_data)) {
            foreach ($lineitem_data as $lineitem) {
                
                $lineitem->fine = $lineitem->net_wt * ($lineitem->touch_id) / 100;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                if((isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE) && $lineitem->type_id != MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID && $lineitem->type_id != MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                    
                    if(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
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
                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_FINISH_ID;
                        }
                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_SCRAP_ID;
                        }
                        $insert_item_stock['created_at'] = $this->now_time;
                        $insert_item_stock['created_by'] = $this->logged_in_id;
                        $insert_item_stock['updated_at'] = $this->now_time;
                        $insert_item_stock['updated_by'] = $this->logged_in_id;
                        
                        $this->crud->insert('item_stock', $insert_item_stock);
                    } else {
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->mhm_detail_id) && !empty($lineitem->mhm_detail_id)){
                            $purchase_item_id = $lineitem->mhm_detail_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                        $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
                        if(!empty($exist_item_id)){
                            if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID)){
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
                            if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                                $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_FINISH_ID;
                            }
                            if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
                                $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_SCRAP_ID;
                            }
                            $insert_item_stock['created_at'] = $this->now_time;
                            $insert_item_stock['created_by'] = $this->logged_in_id;
                            $insert_item_stock['updated_at'] = $this->now_time;
                            $insert_item_stock['updated_by'] = $this->logged_in_id;
                            $this->crud->insert('item_stock', $insert_item_stock);
                        }
                    }
                } else {
                    if(isset($lineitem->stock_method) && $lineitem->stock_method == STOCK_METHOD_ITEM_WISE){
                        if(isset($lineitem->purchase_sell_item_id) && !empty($lineitem->purchase_sell_item_id)){
                            $purchase_item_id = $lineitem->purchase_sell_item_id;
                        } elseif(isset($lineitem->mhm_detail_id) && !empty($lineitem->mhm_detail_id)){
                            $purchase_item_id = $lineitem->mhm_detail_id;
                        } elseif(isset($lineitem->purchase_item_id) && !empty($lineitem->purchase_item_id)){
                            $purchase_item_id = $lineitem->purchase_item_id;
                        }
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id, 'purchase_sell_item_id' => $purchase_item_id);
                    } else {
                        $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->touch_id);
                    }
                    $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                    echo '<pre>'.$this->db->last_query(); exit;
                    if(!empty($exist_item_id)){
                        if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID)){
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
                        if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID)){
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
                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_FINISH_ID;
                        }
                        if($lineitem->type_id == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
                            $insert_item_stock['stock_type'] = STOCK_TYPE_MHM_RECEIVE_SCRAP_ID;
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
    
    function update_stock_on_manufacture_update($mhm_id =''){
        $manu_hand_made_details = $this->crud->get_all_with_where('manu_hand_made_details', '', '', array('mhm_id' => $mhm_id));
        if(!empty($manu_hand_made_details)){
            foreach ($manu_hand_made_details as $lineitem){
                
                $lineitem->fine = $lineitem->net_wt * $lineitem->tunch / 100;
                $lineitem->grwt = $lineitem->weight;
                $category_group_id = $this->crud->get_column_value_by_id('category', 'category_group_id', array('category_id' => $lineitem->category_id));
                if($category_group_id == 1){
                    $lineitem->fine = number_format(round($lineitem->fine, 2), 3, '.', '');
                } else {
                    $lineitem->fine = number_format(round($lineitem->fine, 1), 3, '.', '');
                }
                
                $department_id = $this->crud->get_column_value_by_id('manu_hand_made', 'department_id', array('mhm_id' => $lineitem->mhm_id));
                $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $lineitem->item_id));
                if($stock_method == STOCK_METHOD_ITEM_WISE){
                    if(!empty($lineitem->purchase_sell_item_id)){
                        $mhm_detail_id = $lineitem->purchase_sell_item_id;
                    } else {
                        $mhm_detail_id = $lineitem->mhm_detail_id;
                    }
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch, 'purchase_sell_item_id' => $mhm_detail_id);
                } else {
                    $where_stock_array = array('department_id' => $department_id, 'category_id' => $lineitem->category_id, 'item_id' => $lineitem->item_id, 'tunch' => $lineitem->tunch);
                }
                
                $exist_item_id = $this->crud->get_row_by_id('item_stock',$where_stock_array);
//                echo $this->db->last_query(); exit;
                if(!empty($exist_item_id)){
                    if(($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID)){
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
    
    function update_account_and_department_balance_on_update($mhm_id=''){
        $total_gold_fine = 0;
        $total_silver_fine = 0;
        $manu_hand_made_details = $this->crud->get_all_with_where('manu_hand_made_details', '', '', array('mhm_id' => $mhm_id));
//        echo '<pre>'; print_r($manu_hand_made_details); exit;
        $department_id = $this->crud->get_column_value_by_id('manu_hand_made', 'department_id', array('mhm_id' => $mhm_id));
        $account_id = $this->crud->get_column_value_by_id('manu_hand_made', 'worker_id', array('mhm_id' => $mhm_id));
        if(!empty($manu_hand_made_details)){
            foreach ($manu_hand_made_details as $lineitem){
                
                $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $lineitem->category_id));

                if($lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID || $lineitem->type_id == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                    if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') - number_format((float) $lineitem->fine, '3', '.', '');
                    }
                } else {
                    if($category_group_id == CATEGORY_GROUP_GOLD_ID){
                        $total_gold_fine = number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    } else if($category_group_id == CATEGORY_GROUP_SILVER_ID){
                        $total_silver_fine = number_format((float) $total_silver_fine, '3', '.', '') + number_format((float) $lineitem->fine, '3', '.', '');
                    }
                }
            }
            
//            echo '<pre>'; print_r($total_gold_fine);
//            echo '<pre>'; print_r($total_silver_fine); exit;
            //Update Account Balance
            $account = $this->crud->get_data_row_by_id('account', 'account_id', $account_id);
            if(!empty($account)){
                $account_gold_fine = number_format((float) $account->gold_fine, '3', '.', '') + number_format((float) $total_gold_fine, '3', '.', '') + number_format((float) $total_silver_fine, '3', '.', '');
                $account_gold_fine = number_format((float) $account_gold_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $account_gold_fine), array('account_id' => $account_id));
            }
//            echo '<pre>'. $this->db->last_query(); exit;

            //Update Department Balance
            $department = $this->crud->get_data_row_by_id('account', 'account_id', $department_id);
            if(!empty($department)){
                $department_gold_fine = number_format((float) $department->gold_fine, '3', '.', '') - number_format((float) $total_gold_fine, '3', '.', '');
                $department_gold_fine = number_format((float) $department_gold_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department->silver_fine, '3', '.', '') - number_format((float) $total_silver_fine, '3', '.', '');
                $department_silver_fine = number_format((float) $department_silver_fine, '3', '.', '');
                $this->crud->update('account', array('gold_fine' => $department_gold_fine, 'silver_fine' => $department_silver_fine), array('account_id' => $department_id));
            }
        }
    }

    function manu_hand_made_list() {
        if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"view")) {
            $data = array();
            set_page('manufacture/manu_hand_made_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function manu_hand_made_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'manu_hand_made mhm';
        $config['select'] = 'mhm.*,a.account_name AS worker,aa.account_name AS department, op.operation_name, IF(mhm.lott_complete = 0,"No","Yes") AS is_lott_complete, IF(mhm.hisab_done = 0,"No","Yes") AS is_hisab_done';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = mhm.worker_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account aa', 'join_by' => 'aa.account_id = mhm.department_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'operation op', 'join_by' => 'op.operation_id = mhm.operation_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'aa.account_name', 'op.operation_name', 'DATE_FORMAT(mhm.mhm_date,"%d-%m-%Y")', 'mhm.reference_no', 'IF(mhm.lott_complete = 0,"No","Yes")', 'IF(mhm.hisab_done = 0,"No","Yes")', 'mhm.mhm_remark');
        $config['column_order'] = array(null,'a.account_name', 'aa.account_name', 'op.operation_name','mhm.mhm_date', 'mhm.reference_no', null, null, null, null, null, null, null, null,'mhm.lott_complete', 'mhm.hisab_done', 'mhm.mhm_remark');

        $department_ids = $this->applib->current_user_department_ids();
        if(!empty($department_ids)){
            $department_ids = implode(',', $department_ids);
            $config['custom_where'] = 'mhm.department_id IN('.$department_ids.')';
        }
        if(!empty($post_data['department_id'])){
            $config['wheres'][] = array('column_name' => 'mhm.department_id', 'column_value' => $post_data['department_id']);
        }
        if(!empty($post_data['worker_id'])){
            $config['wheres'][] = array('column_name' => 'mhm.worker_id', 'column_value' => $post_data['worker_id']);
        }
        if(!empty($post_data['operation_id'])){
            $config['wheres'][] = array('column_name' => 'mhm.operation_id', 'column_value' => $post_data['operation_id']);
        }
        if(isset($post_data['lott_complete'])){
            if($post_data['lott_complete'] == '2'){
                $config['wheres'][] = array('column_name' => 'mhm.hisab_done', 'column_value' => '1');
            } else if($post_data['lott_complete'] == '1'){
                $config['wheres'][] = array('column_name' => 'mhm.lott_complete', 'column_value' => '1');
                $config['wheres'][] = array('column_name' => 'mhm.hisab_done', 'column_value' => '0');
            } else if($post_data['lott_complete'] == 'all'){
                $config['wheres'][] = array('column_name' => 'mhm.hisab_done', 'column_value' => '0');
            } else {
                $config['wheres'][] = array('column_name' => 'mhm.lott_complete', 'column_value' => $post_data['lott_complete']);
            }
        }
        if (!empty($post_data['audit_status_filter']) && $post_data['audit_status_filter'] != 'all') {
            $config['wheres'][] = array('column_name' => 'mhm.audit_status', 'column_value' => $post_data['audit_status_filter']);
        }

        $config['order'] = array('mhm.mhm_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo '<pre>'. $this->db->last_query(); exit;
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(MANU_HAND_MADE_MODULE_ID, "edit");

        foreach ($list as $mhm) {
            $row = array();
            $action = '';
            if($role_edit){
                if($mhm->hisab_done != '1'){
                    $action .= '<a href="' . base_url("manu_hand_made/manu_hand_made/" . $mhm->mhm_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if($role_delete){
                if($mhm->hisab_done != '1'){
                    $action .= '<a href="javascript:void(0);" class="delete_mhm" data-href="' . base_url('manu_hand_made/delete/' . $mhm->mhm_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                }
            }
            if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"worker_hisab_handmade")) {
                if(isset($post_data['checked_or_not']) && $post_data['checked_or_not'] == '1'){
                    $action .= '&nbsp;&nbsp;<input type="checkbox" name="check_mhm[]" id="checkbox_id_'.$mhm->mhm_id.'" class="icheckbox_flat-blue check_mhm" value="'.$mhm->mhm_id.'" data-total_issue_net_wt="'. $mhm->total_issue_net_wt .'" data-total_issue_fine="'. $mhm->total_issue_fine .'" data-total_receive_net_wt="'. $mhm->total_receive_net_wt .'" data-total_receive_fine="'. $mhm->total_receive_fine .'">';
                }
            }
            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="item_row" data-mhm_id="' . $mhm->mhm_id . '" >' . $mhm->department. '</a>';
            $row[] = $mhm->operation_name;
            $row[] = $mhm->worker;
            $row[] = (!empty(strtotime($mhm->mhm_date))) ? date('d-m-Y', strtotime($mhm->mhm_date)) : '';
            $row[] = $mhm->reference_no;
            if(!empty($mhm->total_issue_fine) && !empty($mhm->total_issue_net_wt)){
                $issue_avg_tunch = $mhm->total_issue_fine * 100 / $mhm->total_issue_net_wt;
            } else {
                $issue_avg_tunch = 0;
            }
            $row[] = number_format($issue_avg_tunch, 2, '.', '');
            $row[] = number_format($mhm->total_issue_net_wt, 3, '.', '');
            $row[] = number_format($mhm->total_issue_fine, 3, '.', '');
            if(!empty($mhm->total_receive_fine) && !empty($mhm->total_receive_net_wt)){
                $receive_avg_tunch = $mhm->total_receive_fine * 100 / $mhm->total_receive_net_wt;
            } else {
                $receive_avg_tunch = 0;
            }
            $row[] = number_format($receive_avg_tunch, 2, '.', '');
            $row[] = number_format($mhm->total_receive_net_wt, 3, '.', '');
            $row[] = number_format($mhm->total_receive_fine, 3, '.', '');
            $balance_net_wt = $mhm->total_issue_net_wt - $mhm->total_receive_net_wt;
            $row[] = number_format($balance_net_wt, 3, '.', '');
            $balance_fine = $mhm->total_issue_fine - $mhm->total_receive_fine;
            $row[] = number_format($balance_fine, 3, '.', '');
            $lott_complete = $mhm->is_lott_complete;
//            if($this->applib->have_access_role(MANUFACTURE_MODULE_ID,"allow to lott complete")) {
//                if($mhm->lott_complete == 0) {
//                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-mhm_id="'.$mhm->mhm_id.'" data-lott_complete="1" data-href="'. base_url('manu_hand_made/set_lott_complete_yes_no/'.$mhm->mhm_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;">';
//                } else {
//                    $lott_complete .= ' <input type="checkbox" name="lott_complete[]" data-mhm_id="'.$mhm->mhm_id.'" data-lott_complete=0 data-href="'. base_url('manu_hand_made/set_lott_complete_yes_no/'.$mhm->mhm_id).'" class="set_lott_complete_yes_no" style="height: 20px; width: 20px;" checked="checked">';
//                }
//            }
            if($this->applib->have_access_role(MANU_HAND_MADE_MODULE_ID,"allow to audit mhm")) {
                if($mhm->lott_complete == '1') {
                    if($mhm->audit_status == '1') {
                        $lott_complete .= ' <input type="checkbox" name="audit_status[]" data-mhm_id="'.$mhm->mhm_id.'" data-audit_status="2" data-href="'. base_url('manu_hand_made/set_audit_status_yes_no/'.$mhm->mhm_id).'" class="set_audit_status_yes_no" style="height: 20px; width: 20px;">';
                    } else {
                        $lott_complete .= ' <input type="checkbox" name="audit_status[]" data-mhm_id="'.$mhm->mhm_id.'" data-audit_status="1" data-href="'. base_url('manu_hand_made/set_audit_status_yes_no/'.$mhm->mhm_id).'" class="set_audit_status_yes_no" style="height: 20px; width: 20px;" checked="checked">';
                    }
                }
            }
            $row[] = $lott_complete;
            $row[] = $mhm->is_hisab_done;
            $row[] = $mhm->mhm_remark;
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
    
    function set_lott_complete_yes_no($mhm_id= '') {
        $update_data = array();
        $update_data['lott_complete'] = $_POST['lott_complete'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('manu_hand_made', $update_data, array('mhm_id' => $mhm_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }
    
    function set_audit_status_yes_no($mhm_id= '') {
        $update_data = array();
        $update_data['audit_status'] = $_POST['audit_status'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('manu_hand_made', $update_data, array('mhm_id' => $mhm_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }

    function manu_hand_made_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'manu_hand_made_details mhm_detail';
        $config['select'] = 'mhm_detail.*,im.item_name, mhm_detail.type_id AS type';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = mhm_detail.item_id', 'join_type' => 'left');
        $config['column_search'] = array('IF(mhm_detail.type_id = 0,"Issue","Receive")', 'im.item_name', 'mhm_detail.weight', 'mhm_detail.tunch', 'mhm_detail.fine', 'DATE_FORMAT(mhm_detail.mhm_detail_date,"%d-%m-%Y")', 'mhm_detail.mhm_detail_remark');
        $config['column_order'] = array('mhm_detail.type_id', 'im.item_name', 'mhm_detail.weight', NULL, 'mhm_detail.weight', 'mhm_detail.tunch', 'mhm_detail.fine', 'mhm_detail.mhm_detail_date', 'mhm_detail.created_at', 'mhm_detail.updated_at', 'mhm_detail.mhm_detail_remark');
        $config['order'] = array('mhm_detail.mhm_detail_id' => 'desc');
        if (isset($post_data['mhm_id']) && !empty($post_data['mhm_id'])) {
            $config['wheres'][] = array('column_name' => 'mhm_detail.mhm_id', 'column_value' => $post_data['mhm_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $detail) {
            $row = array();
            $detail_type = '';
            if($detail->type == MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID){
                $detail_type = 'Issue Finish Work';
            } else if($detail->type == MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID){
                $detail_type = 'Issue Scrap';
            } else if($detail->type == MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID){
                $detail_type = 'Receive Finish Work';
            } else if($detail->type == MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID){
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
            $row[] = $detail->pcs;
            $row[] = $detail->mhm_detail_date ? date('d-m-Y', strtotime($detail->mhm_detail_date)) : '';
            $row[] = $detail->created_at ? date('d-m-Y H:i:s', strtotime($detail->created_at)) : '';
            $row[] = $detail->updated_at ? date('d-m-Y H:i:s', strtotime($detail->updated_at)) : '';
            $row[] = $detail->mhm_detail_remark;
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
        $where_array = array('mhm_id' => $id);
        $manu_hand_made = $this->crud->get_row_by_id('manu_hand_made', $where_array);
        $return = array();
        $without_purchase_sell_allow = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'without_purchase_sell_allow'));
        if(!empty($manu_hand_made)){
            $found = false;
            $manu_hand_made_details = $this->crud->get_row_by_id('manu_hand_made_details', $where_array);
            if(!empty($manu_hand_made_details)){
                foreach($manu_hand_made_details as $mhm_detail){
                    $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $mhm_detail->item_id));
                    if($stock_method == STOCK_METHOD_ITEM_WISE){
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('sell_items', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('stock_transfer_detail', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('issue_receive_details', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_FINISH_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                        $item_sells = $this->crud->get_row_by_id('manu_hand_made_details', array('purchase_sell_item_id' => $mhm_detail->mhm_detail_id, 'stock_type' => STOCK_TYPE_MHM_RECEIVE_SCRAP_ID));
                        if(!empty($item_sells)){
                            $found = true;
                        }
                    } else if($stock_method == STOCK_METHOD_DEFAULT || $stock_method == STOCK_METHOD_COMBINE){
                        if($without_purchase_sell_allow == '1'){
                            $used_lineitem_ids = $this->check_default_item_receive_or_not($manu_hand_made[0]->department_id, $mhm_detail->category_id, $mhm_detail->item_id, $mhm_detail->tunch);
                            if(!empty($used_lineitem_ids) && in_array($mhm_detail->mhm_detail_id, $used_lineitem_ids)){
                                $found = true;
                            }
                        } 
                    }
                }
            }
            if($found == true){
                $return['error'] = 'Error';
            } else {
                // Increase fine and amount in Department
                $this->update_account_and_department_balance_on_update($id);
//                // Increase Item Stock
                $this->update_stock_on_manufacture_update($id);
                // Revert Journal for MHM diff. Amount // Ad Amount // Meena Charges
                $this->revert_journal_for_mhm_diffrence_amount($id, $manu_hand_made[0]->worker_id);
                $this->crud->delete('manu_hand_made_order_items', $where_array);
                $this->crud->delete('manu_hand_made_details', $where_array);
                $this->crud->delete('manu_hand_made_ads', $where_array);
                $this->crud->delete('manu_hand_made', $where_array);
//                // Increase fine and amount in Department
                $return['success'] = 'Deleted';
            }
        }
        echo json_encode($return);
        exit;
    }
    
    function check_default_item_receive_or_not($department_id, $category_id, $item_id, $touch_id){
        $total_sell_grwt = $this->crud->get_total_sell_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_transfer_grwt = $this->crud->get_total_transfer_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_metal_grwt = $this->crud->get_total_metal_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_issue_receive_grwt = $this->crud->get_total_issue_receive_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_manu_hand_made_grwt = $this->crud->get_total_manu_hand_made_grwt($department_id, $category_id, $item_id, $touch_id);
        $total_other_sell_grwt = $this->crud->get_total_other_sell_grwt($department_id, $category_id, $item_id);
        $total_sell_grwt = $total_sell_grwt + $total_transfer_grwt + $total_metal_grwt + $total_issue_receive_grwt + $total_manu_hand_made_grwt + $total_other_sell_grwt;
        $used_lineitem_ids = array();
        if(!empty($total_sell_grwt)){
            $purchase_items = $this->crud->get_purchase_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $metal_items = $this->crud->get_metal_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $stock_transfer_items = $this->crud->get_stock_transfer_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $receive_items = $this->crud->get_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $manu_hand_made_receive_items = $this->crud->get_manu_hand_made_receive_items_grwt($department_id, $category_id, $item_id, $touch_id);
            $other_purchase_items = $this->crud->get_other_purchase_items_grwt($department_id, $category_id, $item_id);
            $purchase_delete_array = array_merge($purchase_items, $metal_items, $stock_transfer_items, $receive_items, $manu_hand_made_receive_items, $other_purchase_items);
//            echo '<pre>'; print_r($purchase_delete_array); exit;
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
                    if($purchase_item->type == 'MHM_R'){
                        $used_lineitem_ids[] = $purchase_item->mhm_detail_id;
                    }
                    $first_check_purchase_grwt = 1;
                } else if($purchase_grwt <= $total_sell_grwt){
                    if($purchase_item->type == 'MHM_R'){
                        $used_lineitem_ids[] = $purchase_item->mhm_detail_id;
                    }
                }
            }
        }
        
        return $used_lineitem_ids;
        exit;
    }

    function get_category_from_item(){
        $data = array();
        $item_id = $_POST['item_id'];
        $category_id = $this->crud->get_column_value_by_id('item_master', 'category_id', array('item_id' => $item_id));
        if(isset($category_id) && !empty($category_id)){
            $data['category_id'] = $category_id;
        }
        echo json_encode($data);
        exit;
    }

    function get_worker_chhijjat_per_100_ad(){
        $data = array();
        $worker_id = $_POST['worker_id'];
        $data['chhijjat_per_100_ad'] = $this->crud->get_column_value_by_id('account', 'chhijjat_per_100_ad', array('account_id' => $worker_id));
        echo json_encode($data);
        exit;
    }

    function get_worker_meena_charges(){
        $data = array();
        $worker_id = $_POST['worker_id'];
        $data['meena_charges'] = $this->crud->get_column_value_by_id('account', 'meena_charges', array('account_id' => $worker_id));
        echo json_encode($data);
        exit;
    }
    
}
