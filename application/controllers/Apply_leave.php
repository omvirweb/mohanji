<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apply_leave extends CI_Controller {

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

    function apply_leave($apply_leave_id = '') {
        $data = array();
        $data['user_name'] = $this->crud->get_column_value_by_id('account','account_name',array('user_id' =>$this->logged_in_id));
        if (isset($apply_leave_id) && !empty($apply_leave_id)) {
            if($this->applib->have_access_role(APPLY_LEAVE_ID,"edit") || $this->applib->have_access_role(APPLY_LEAVE_ID,"view")) {
                $apply_leave_data = $this->crud->get_row_by_id('hr_apply_leave', array('apply_leave_id' => $apply_leave_id));
                $apply_leave_data = $apply_leave_data[0];
                $apply_leave_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $apply_leave_data->created_by));
                if ($apply_leave_data->created_by != $apply_leave_data->updated_by) {
                    $apply_leave_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $apply_leave_data->updated_by));
                } else {
                    $apply_leave_data->updated_by_name = $result->created_by_name;
                }
                $data['apply_leave_data'] = $apply_leave_data;

                set_page('apply_leave/apply_leave', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(APPLY_LEAVE_ID,"add") || $this->applib->have_access_role(APPLY_LEAVE_ID,"view")) {
                set_page('apply_leave/apply_leave', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
        //set_page('apply_leave/apply_leave', $data);
    }

    function save_apply_leave(){
        $post_data = $this->input->post();
        $post_data['from_date'] = date('Y-m-d', strtotime($post_data['from_date']));
        $post_data['to_date'] = date('Y-m-d', strtotime($post_data['to_date']));

        if (isset($post_data['apply_leave_id']) && !empty($post_data['apply_leave_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['apply_leave_id'] = $post_data['apply_leave_id'];
            $result = $this->crud->update('hr_apply_leave', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Leave Updated Successfully');
            }
        } else {
            $post_data['status'] = 0;
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('hr_apply_leave', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function apply_leave_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hr_apply_leave hl';
        $config['select'] = 'hl.*, a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.user_id = hl.user_id', 'join_type' => 'LEFT');
        $config['column_search'] = array('a.account_name', 'DATE_FORMAT(hl.from_date,"%d-%m-%Y")', 'DATE_FORMAT(hl.to_date,"%d-%m-%Y")', 'hl.no_of_days', 'hl.reason');
        $config['column_order'] = array(null, 'a.account_name', 'hl.from_date', 'hl.to_date', 'hl.no_of_days', 'hl.reason');
        if($this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'] == USER_TYPE_USER || $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_type'] == USER_TYPE_WORKER){
            $config['wheres'][] = array('column_name' => 'hl.user_id', 'column_value' => $this->logged_in_id);
        }
        $config['order'] = array('hl.apply_leave_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();

        $data = array();
        
        $role_delete = $this->app_model->have_access_role(APPLY_LEAVE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(APPLY_LEAVE_ID, "edit");
        $is_approve = $this->app_model->have_access_role(APPLY_LEAVE_ID, "approve");
        $status = array('0' => 'Pending', '1' => 'Approved', '2' => 'Disapproved');
        foreach ($list as $leave) {
            $row = array();
            $action = '';
            if($role_edit && $leave->status == 0){
                $action .= '<a href="' . base_url("apply_leave/apply_leave/" . $leave->apply_leave_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete && $leave->status == 0){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('apply_leave/delete_plan/' . $leave->apply_leave_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            if($is_approve){
                if($leave->status == 0) {
                    $action .= '<a href="javascript:void(0);" class="approve_leave_btn" data-status="1" data-href="' . base_url('apply_leave/approve_disapprove_leave/' . $leave->apply_leave_id) . '" data-toggle="tooltip" data-placement="right" title="Approve Leave"><span class="glyphicon glyphicon-ok" style="color : green">&nbsp;</span></a>';
                    $action .= '<a href="javascript:void(0);" class="approve_leave_btn" data-status="2" data-href="' . base_url('apply_leave/approve_disapprove_leave/' . $leave->apply_leave_id) . '" data-toggle="tooltip" data-placement="right" title="Disapprove Leave"><span class="glyphicon glyphicon-remove" style="color : red">&nbsp;</span></a>';
                } else if($leave->status == 1) {
                    $action .= '<a href="javascript:void(0);" class="approve_leave_btn" data-status="2" data-href="' . base_url('apply_leave/approve_disapprove_leave/' . $leave->apply_leave_id) . '" data-toggle="tooltip" data-placement="right" title="Disapprove Leave"><span class="glyphicon glyphicon-remove" style="color : red">&nbsp;</span></a>';
                } else if($leave->status == 2) {
                    $action .= '<a href="javascript:void(0);" class="approve_leave_btn" data-status="1" data-href="' . base_url('apply_leave/approve_disapprove_leave/' . $leave->apply_leave_id) . '" data-toggle="tooltip" data-placement="right" title="Approve Leave"><span class="glyphicon glyphicon-ok" style="color : green">&nbsp;</span></a>';
                }
            }
            $row[] = $action;
            $row[] = $leave->account_name;
            $row[] = (!empty(strtotime($leave->from_date))) ? '<span class="text-nowrap">'.date('d-m-Y', strtotime($leave->from_date)).'</span>' : '';
            $row[] = (!empty(strtotime($leave->to_date))) ? '<span class="text-nowrap">'.date('d-m-Y', strtotime($leave->to_date)).'</span>' : '';
            $row[] = $leave->no_of_days;
            $row[] = $leave->reason;
            $row[] = $leave->status != null ? $status[$leave->status] : '';
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

     function delete_plan($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $return = $this->crud->delete($table, array($id_name => $id));
        print json_encode($return);
        exit;
    }

    function approve_disapprove_leave($id = ''){
        $update_data = array();
        $update_data['status'] = $_POST['status'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('hr_apply_leave', $update_data, array('apply_leave_id' => $id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }
}