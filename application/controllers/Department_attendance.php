<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Department_attendance
 * @property Crud $crud
 */

class Department_attendance extends CI_Controller {

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

    function department_attendance() {
        $data['department'] = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
        set_page('department_attendance/department_attendance', $data);
    }

    function present_datatable(){
        $post_data = $this->input->post();
        $config['table'] = 'hr_attendance ha';
        $config['select'] = 'a.account_name,a.account_id';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = ha.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'user_master um', 'join_by' => 'um.user_id = a.user_id', 'join_type' => 'left');
        $config['column_order'] = array(null,'a.account_name');
        $config['column_search'] = array('a.account_name');
        $config['order'] = array('a.account_id' => 'desc');
        $where_array = array();
        if(!empty($post_data['date'])){
             $where_array['ha.attendance_date'] = date('Y-m-d',strtotime($post_data['date']));
        } else {
            $where_array['ha.attendance_date'] = date('Y-m-d');
        }
        if(!empty($post_data['department_id'])){
            $where_array['um.default_department_id'] = $post_data['department_id'];
        }
        $where_array['um.status'] = 0;
        $config['custom_where'] = $where_array;
        $config['group_by'] = "ha.account_id";
        
        $this->load->library('datatables', $config, 'datatable');
        $user_data = $this->datatable->get_datatables();
//        echo $this->db->last_query(); exit;
        $data = array();
        $i=1;
        foreach ($user_data as $user_data_row) {
            $row = array();
            $row[] = $i++;
            $row[] = '<a href="javascript:void(0);" style="color: #ffffff;text-decoration: underline;" class="show_time" data-list_account_id="'.$user_data_row->account_id.'">'.$user_data_row->account_name.'</a>';
            $data[] = $row;
        }
        $total_rows = $this->crud->getFromSQL(" SELECT COUNT(user_id) as total_rows FROM user_master ");
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_rows[0]->total_rows,
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
        exit();
    }
    
    function day_attendance_datatable() {
        $post_data = $this->input->post();
        if(isset($post_data['date']) && !empty($post_data['date'])){
            $date = date('Y-m-d', strtotime($post_data['date']));
        } else {
            $date = '';
        }
        $config['select'] = 'hp.*';
        $config['table'] = 'hr_attendance hp';
        $config['column_order'] = array();
        $config['column_search'] = array();
        $config['wheres'][] = array('column_name' => 'hp.account_id', 'column_value' => $post_data['user_id']);
        if(!empty($date)){
            $config['wheres'][] = array('column_name' => 'hp.attendance_date', 'column_value' => $date);
        }
        $config['order'] = array('hp.attendance_id' => 'asc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $max_attendance_id = $this->get_day_max_attendance_id($post_data['user_id'],$date);
        $role_delete = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "delete");

        $last_entry_is_out = true;    

        foreach ($list as $entry_detail) {
            $row = array();
            $row[] = date('d-m-Y',strtotime($entry_detail->attendance_date));
            $row[] = date('h:i A',strtotime($entry_detail->attendance_time));
            $in_out = '';
            if($entry_detail->is_in_out == 1) {
                $row[] = 'In';
                $last_entry_is_out = false;
            } else {
                $row[] = 'Out';
                $last_entry_is_out = true;
            }

            if($entry_detail->is_out_for_office == 1) {
                $row[] = 'Yes';
            } else {
                $row[] = 'No';
            }


            if($role_delete && $max_attendance_id == $entry_detail->attendance_id) {
                $row[] = '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $entry_detail->attendance_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            } else {
                $row[] = '';
            }
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

    function absent_datatable(){
        $post_data = $this->input->post();
        if(!empty($post_data['date'])){
             $date = date('Y-m-d',strtotime($post_data['date']));
        } else {
            $date = date('Y-m-d');
        }
        $config['table'] = 'account a';
        $config['select'] = 'a.account_name';
        $config['joins'][] = array('join_table' => 'hr_attendance ha', 'join_by' => 'ha.account_id = a.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'user_master um', 'join_by' => 'um.user_id = a.user_id', 'join_type' => 'left');
        $config['column_order'] = array(null,'a.account_name');
        $config['column_search'] = array('a.account_name');
        $config['order'] = array('a.account_id' => 'desc');
        $config['group_by'] = 'a.account_id';
        if(!empty($post_data['department_id'])){
            $config['custom_where'] = " um.status = 0 AND um.default_department_id = ".$post_data['department_id']."  AND a.account_id NOT IN (SELECT account_id FROM hr_attendance WHERE attendance_date = '".$date."' GROUP BY account_id )  ";
        } else {
            $config['custom_where'] = " um.status = 0 AND a.account_id NOT IN (SELECT account_id FROM hr_attendance WHERE attendance_date = '".$date."' GROUP BY account_id )  ";
        }
        $this->load->library('datatables', $config, 'datatable');
        $user_data = $this->datatable->get_datatables();
        $data = array();
        $i=1;
        foreach ($user_data as $user_data_row) {
            $row = array();
            $row[] = $i++;
            $row[] = $user_data_row->account_name;
            $data[] = $row;
        }
        $total_rows = $this->crud->getFromSQL(" SELECT COUNT(user_id) as total_rows FROM user_master ");
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_rows[0]->total_rows,
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
        exit();
    }

}