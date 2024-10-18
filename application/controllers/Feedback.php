<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends CI_Controller {

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

    function feedback($feedback_id = '') {
        $data = array();
        if (isset($feedback_id) && !empty($feedback_id)) {
            $feedback_data = $this->crud->get_row_by_id('feedback', array('feedback_id' => $feedback_id));
            $feedback_data = $feedback_data[0];
            $data['feedback_data'] = $feedback_data;
        }
        set_page('feedback/feedback', $data);
    }

    function save_feedback() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>'; print_r($post_data); exit;
        $post_data['feedback_date'] = (isset($post_data['feedback_date']) && !empty($post_data['feedback_date'])) ? date('Y-m-d', strtotime($post_data['feedback_date'])) : NULL;
        if (isset($post_data['feedback_id']) && !empty($post_data['feedback_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['feedback_id'] = $post_data['feedback_id'];
            $result = $this->crud->update('feedback', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Feedback Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('feedback', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Feedback Added Successfully');
            } else {
                $return['error'] = "errorAdded";
            }
        }
        print json_encode($return);
        exit;
    }

    function feedback_list() {
        set_page('feedback/feedback_list');
    }

    function feedback_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'feedback f';
        $config['select'] = 'f.*,u.user_name AS created_by';
        $config['joins'][] = array('join_table' => 'user_master u', 'join_by' => 'u.user_id = f.assign_id', 'join_type' => 'left');
        $config['column_search'] = array('f.assign_id', 'f.feedback_date', 'f.note','u.user_name');
        $config['column_order'] = array(null, 'f.assign_id', 'f.feedback_date', 'f.note');
        $config['order'] = array('f.feedback_id' => 'desc');
        $config['group_by'] = 'f.feedback_id';
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo $this->db->last_query(); exit;
        foreach ($list as $feedback) {
            $assigned_name = '';
            $feedback_data = $this->crud->getFromSQL('SELECT r.reply_id,u.user_name FROM reply r JOIN user_master u ON u.user_id=r.assign_to_id WHERE `r`.`reply_id` IN (SELECT MAX(rr.reply_id) FROM reply rr GROUP BY rr.feedback_id) AND r.feedback_id='. $feedback->feedback_id);
            if(!empty($feedback_data)){
                $assigned_name = $feedback_data[0]->user_name;
            }
            $row = array();
            $action = '';
            $action .= '<a href="' . base_url("feedback/feedback/" . $feedback->feedback_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('feedback/delete_feedback/' . $feedback->feedback_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            $feedback_date = (!empty(strtotime($feedback->feedback_date))) ? date('d-m-Y', strtotime($feedback->feedback_date)) : '';
            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="feedback_row" data-feedback_id="' . $feedback->feedback_id . '" >' . $feedback->created_by . '</a>';
            $row[] = '<a href="javascript:void(0);" class="feedback_row" data-feedback_id="' . $feedback->feedback_id . '" >' . $assigned_name . '</a>';
            $row[] = '<a href="javascript:void(0);" class="feedback_row" data-feedback_id="' . $feedback->feedback_id . '" >' . $feedback_date . '</a>';
            $row[] = '<a href="javascript:void(0);" class="feedback_row" data-feedback_id="' . $feedback->feedback_id . '" >' . $feedback->note . '</a>';
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


    function delete_feedback($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $feedback_data = $this->crud->get_row_by_id('feedback', array('feedback_id' => $id));
        $this->crud->delete($table, array($id_name => $id));
    }

    function reply($reply_id = '') {
        $data = array();
        
        if (isset($reply_id) && !empty($reply_id)) {
            $reply_data = $this->crud->get_row_by_id('reply', array('reply_id' => $reply_id));
            $reply_data = $reply_data[0];
            $data['reply_data'] = $reply_data;
            $feedback_data = $this->crud->get_row_by_id('feedback', array('feedback_id' => $reply_data->feedback_id));
            $feedback_data = $feedback_data[0];
            $data['feedback_data'] = $feedback_data;
            set_page('feedback/feedback_list', $data);
        } else {
            set_page('feedback/feedback_list', $data);
        }
    }

    function save_reply() {
        $post_data = $this->input->post();
//        $return = array();
        $post_data['reply_date'] = (isset($post_data['reply_date']) && !empty($post_data['reply_date'])) ? date('Y-m-d', strtotime($post_data['reply_date'])) : NULL;
        if (isset($post_data['reply_id']) && !empty($post_data['reply_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['reply_id'] = $post_data['reply_id'];
            $result = $this->crud->update('reply', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
//                $this->session->set_flashdata('success', true);
//                $this->session->set_flashdata('message', 'Reply Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('reply', $post_data);
            if ($result) {
                $return['success'] = "Added";
//                $this->session->set_flashdata('success', true);
//                $this->session->set_flashdata('message', 'Reply Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function reply_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'reply r';
        $config['select'] = 'r.*, u.user_name';
        $config['joins'][] = array('join_table' => 'user_master u', 'join_by' => 'u.user_id = r.assign_to_id', 'join_type' => 'left');
        $config['column_search'] = array('r.assign_to_id', 'DATE_FORMAT(r.reply_date, "%d-%m-%Y")', 'r.reply');
        $config['column_order'] = array(null, 'r.assign_to_id', 'r.reply_date', 'r.reply');
        $config['order'] = array('r.reply_id' => 'desc');
        if (isset($post_data['feedback_id']) && !empty($post_data['feedback_id'])) {
            $config['wheres'][] = array('column_name' => 'r.feedback_id', 'column_value' => $post_data['feedback_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
//        echo $this->db->last_query();
        
        foreach ($list as $reply) {
            $row = array();
            $action = '';
            $action .= '<a href="' . base_url("feedback/reply/" . $reply->reply_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('feedback/delete_row/' . $reply->reply_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';

            $row[] = $action;
            $row[] = $reply->user_name;
            $row[] = (!empty(strtotime($reply->reply_date))) ? date('d-m-Y', strtotime($reply->reply_date)) : '';
            $row[] = $reply->reply;
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

    function delete_row($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $this->crud->delete($table, array($id_name => $id));
    }

}
