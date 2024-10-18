<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Yearly_leaves extends CI_Controller {

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

    function yearly_leaves($worker_entry_id = '') {
        $data = array();
        if($this->app_model->have_access_role(YEARLY_LEAVES_ID, "add") || $this->app_model->have_access_role(YEARLY_LEAVES_ID, "view")) {
            $yearData = $this->crud->getFromSQL("SELECT id, event_name, leave_date FROM hr_yearly_leave ORDER BY leave_date ASC");
            if (empty($yearData)) {
                $data['eventData'] = '[]';
            } else {
                $eventData = '';
                foreach ($yearData as $event) {
                    $date = date('Y, m, d', strtotime($event->leave_date));
                    if (empty($eventData)) {
                        $eventData =  "[{id:{$event->id}, name:'{$event->event_name}', startDate: new Date('{$date}'), endDate: new Date('{$date}'), from_db: 1}";
                    } else {
                        $eventData .=  ", {id:{$event->id}, name:'{$event->event_name}', startDate: new Date('{$date}'), endDate: new Date('{$date}'), from_db: 1}";
                    }   
                }
                $eventData .= ']';
                $data['eventData'] = $eventData;
            }
            set_page('yearly_leaves/yearly_leaves', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function save_yearly_leaves() {
        $return = array();
        $post_data = $this->input->post();

        $leave = json_decode($post_data['dataSource']);
//        echo '<pre>'; print_r($dataSource->name); exit;
        if(!empty($leave)){
            $where_array = array('id' => $leave->id, 'leave_date' => date('Y-m-d', strtotime($leave->startDate)));
            $yearly_data = $this->crud->get_row_by_id('hr_yearly_leave', $where_array);
            if(empty($yearly_data)){
                $leave_data = array();
                $leave_data['event_name'] = $leave->name;
                $leave_data['leave_date'] = date('Y-m-d', strtotime($leave->startDate));
                $leave_data['created_at'] = $this->now_time;
                $leave_data['created_by'] = $this->logged_in_id;
                $this->crud->insert('hr_yearly_leave', $leave_data);
                $id = $this->db->insert_id();
                $return['id'] = $id;
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Yearly Leave Information Added Successfully');
            } 
            else {
                $where_array = array('id' => $leave->id, 'event_name' => $leave->name, 'leave_date' => date('Y-m-d', strtotime($leave->startDate)));
                $yearly_data = $this->crud->get_row_by_id('hr_yearly_leave', $where_array);
                if(empty($yearly_data)) {
                    $leave_data = array();
                    $leave_data['event_name'] = $leave->name;
                    $leave_data['updated_at'] = $this->now_time;
                    $leave_data['updated_by'] = $this->logged_in_id;
                    $this->crud->update('hr_yearly_leave', $leave_data, array('id' => $leave->id));
                    $return['success'] = "Updated";
                    $this->session->set_flashdata('success', true);
                    $this->session->set_flashdata('message', 'Yearly Leave Information Updated Successfully');
                }
            }
        }
 
        print json_encode($return);
        exit;
    }

    function delete_yearly_leaves($id = ''){
        $return = $this->crud->delete('hr_yearly_leave', array('id' => $id));
        print json_encode($return);
        exit;
    }
}