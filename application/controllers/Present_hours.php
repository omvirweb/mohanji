<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Present_hours extends CI_Controller {

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

    function present_hours() {
        if($this->applib->have_access_role(PRESENT_HOURS_MODULE_ID,"add") || $this->applib->have_access_role(PRESENT_HOURS_MODULE_ID,"view")) {
            $data = array();
            $last_rec = $this->crud->getFromSQL('SELECT user_id FROM hr_present_hours ORDER BY present_hour_id DESC LIMIT 1');
            if(!empty($last_rec)){
                $last_rec = $last_rec[0];
                $data['last_user_id'] = $last_rec->user_id;
            }
            set_page('present_hours/present_hours', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function save_present_hours() {
        $post_data = $this->input->post();
        //print_r($post_data);die;
        $post_data['present_date'] = date('Y-m-d', strtotime($post_data['present_date']));

        if (isset($post_data['present_hour_id']) && !empty($post_data['present_hour_id'])) {
            /**$update_data['user_id'] = $post_data['user_id'];
            $update_data['in_time'] = $post_data['in_time'];
            $update_data['out_time'] = $post_data['out_time'];
            $update_data['no_of_hours'] = $post_data['no_of_hours'];
            $update_data['present_date'] = $post_data['present_date'];
            $update_data['updated_at'] = $this->now_time;
            $update_data['updated_by'] = $this->logged_in_id;
            $where_array['present_hour_id'] = $post_data['present_hour_id'];
            $result = $this->crud->update('hr_present_hours', $update_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Present Hours Updated Successfully');
            }**/
        } else {
            if(!empty($post_data['user'])) {
                $users = $post_data['user'];
                foreach ($users as $key => $value) {
                    $present_hr_id = $this->crud->get_column_value_by_id('hr_present_hours','present_hour_id',array('user_id' => $value, 'present_date' => $post_data['present_date'], 'department_id' => $post_data['department_id']));
                    if(empty($present_hr_id)) {
                        $insert_data = array();
                        $insert_data['department_id'] = $post_data['department_id'];
                        $insert_data['user_id'] = $value;
                        $insert_data['in_time'] = $post_data['in_time_'.$value];
                        $insert_data['out_time'] = $post_data['out_time_'.$value];
                        $insert_data['no_of_hours'] = $post_data['no_of_hours_'.$value];
                        $insert_data['present_date'] = $post_data['present_date'];
                        $insert_data['created_at'] = $this->now_time;
                        $insert_data['created_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('hr_present_hours', $insert_data);
                        if ($result) {
                            $return['success'] = "Added";
                            $this->session->set_flashdata('success', true);
                            $this->session->set_flashdata('message', 'Present Hours Added Successfully');
                        }
                    } else {
                        $update_data = array();
                        $update_data['department_id'] = $post_data['department_id'];
                        $update_data['user_id'] = $value;
                        $update_data['in_time'] = $post_data['in_time_'.$value];
                        $update_data['out_time'] = $post_data['out_time_'.$value];
                        $update_data['no_of_hours'] = $post_data['no_of_hours_'.$value];
                        $update_data['present_date'] = $post_data['present_date'];
                        $update_data['updated_at'] = $this->now_time;
                        $update_data['updated_by'] = $this->logged_in_id;
                        $where_array['present_hour_id'] = $present_hr_id;
                        $result = $this->crud->update('hr_present_hours', $update_data, $where_array);
                        if ($result) {
                            $return['success'] = "Updated";
                            $this->session->set_flashdata('success', true);
                            $this->session->set_flashdata('message', 'Present Hours Updated Successfully');
                        }
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function present_hour_datatable() {
        $post_data = $this->input->post();
        $data = array();

        $hours = array();
        if((isset($post_data['user_id']) && !empty($post_data['user_id'])) && (isset($post_data['year']) && !empty($post_data['year']))) {
            $list = $this->crud->getFromSQL('SELECT * FROM  `hr_present_hours` WHERE YEAR( present_date ) = "'.$post_data["year"].'" AND user_id = "'.$post_data["user_id"].'"');
            
            foreach ($list as $value) {
                $day = date('d-m', strtotime($value->present_date));
                $hours[$day] = $value->no_of_hours;
            }
        }

        $months = array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        for($i=1 ; $i <= 12 ; $i++){
            $row = array();
            $row[] = $months[$i];
            $i = $i <= 9 ? '0'.$i : $i;
            for($j=1 ; $j <= 31 ; $j++){
                $j = $j <= 9 ? '0'.$j : $j;
                $date = $post_data['year'] ? $post_data['year'].'-'.$i.'-'.$j : '';
                $row[] = isset($hours[$j.'-'.$i]) && $hours[$j.'-'.$i] ? '<a href="javascript:void(0);" class="show_time" data-date="'.$date.'">P</a>' : 'A';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function entry_datatable() {
        $post_data = $this->input->post();
        $date = date('Y-m-d', strtotime($post_data['date']));
        $config['table'] = 'hr_present_hours hp';
        $config['select'] = 'hp.*';
        //$config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = jd.account_id', 'join_type' => 'left');
        $config['column_order'] = array('hp.in_time', 'hp.out_time', 'hp.no_of_hours');
        $config['column_search'] = array('DATE_FORMAT(hp.in_time,"%h:%i %p")', 'DATE_FORMAT(hp.out_time,"%h:%i %p")', 'hp.no_of_hours');
        $config['wheres'][] = array('column_name' => 'hp.user_id', 'column_value' => $post_data['user_id']);
        $config['wheres'][] = array('column_name' => 'hp.present_date', 'column_value' => $date);
        $config['order'] = array('hp.present_hour_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//        echo $this->db->last_query();
        $data = array();

        foreach ($list as $entry_detail) {
            $row = array();
            $row[] = $entry_detail->in_time ? date('h:i a', strtotime($entry_detail->in_time)) : '';
            $row[] = $entry_detail->out_time ? date('h:i a', strtotime($entry_detail->out_time)) : '';
            $row[] = $entry_detail->no_of_hours;
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

    function get_users_from_department() {
        $post_data = $this->input->post();
        $list = $this->crud->getFromSQL('SELECT a.user_id, a.account_name FROM  `user_master` as u INNER JOIN account as a ON a.user_id = u.user_id WHERE u.default_department_id = "'.$post_data["department_id"].'" AND u.status = 0 ORDER BY u.user_id DESC');
        if(!empty($list)){
            $html = '';
            $html .= '<div class="col-md-12" style="margin-bottom: 20px;">
                        <input id="all" type="checkbox"><label for="all">&nbsp;&nbsp;All</label><br/>
                      </div>';
            foreach ($list as $value) {
                $html .= '<div class="col-md-12">
                                <div class="col-md-3 form-group">
                                    <div class="form-group row">
                                        <input type="checkbox" name="user[]" class="selected_users" id="selected_users" value="'.$value->user_id.'">
                                        <label for="selected_users">'.$value->account_name.'</label>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="form-group row">
                                        <label for="in_time" class="col-sm-6 col-form-label">In Time<span class="required-sign">&nbsp;*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input id="in_time_'.$value->user_id.'" type="text" data-id="'.$value->user_id.'" class="form-control in_time input-small timepicker1" value="'.date("h:i A", strtotime('today 10am')).'" required="">
                                                <input type="hidden" name="in_time_'.$value->user_id.'" type="text" value="'.date("h:i A", strtotime('today 10am')).'" >
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="form-group row">
                                        <label for="out_time" class="col-sm-4 col-form-label">Out Time<span class="required-sign">&nbsp;*</span></label>
                                        <div class="col-sm-7">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input id="out_time_'.$value->user_id.'" type="text" data-id="'.$value->user_id.'" class="form-control out_time input-small timepicker1" value="'.date("h:i A", strtotime('today 8pm')).'" required="">
                                                <input type="hidden" name="out_time_'.$value->user_id.'" type="text" value="'.date("h:i A", strtotime('today 8pm')).'" >
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="form-group row">
                                        <label for="out_time" class="col-sm-4 col-form-label">Present Hours</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="no_of_hours_'.$value->user_id.'" id="hours_'.$value->user_id.'" class="form-control hours" value="0" readonly=""><br />
                                        </div>
                                    </div>
                                </div>
                            </div>';
            }
            $return['success'] = 'success';
            $return['html'] = $html;
        } else {
            $return['error'] = 'error';
        }
        print json_encode($return);
        exit();
    }
}