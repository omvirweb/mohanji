<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Worker extends CI_Controller {

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

    function add($worker_entry_id = '') {
        $data = array();
        if (isset($worker_entry_id) && !empty($worker_entry_id)) {
            if($this->applib->have_access_role(WORKER_MODULE_ID,"edit")) {
                $worker_data = $this->crud->get_row_by_id('worker_entry', array('worker_entry_id' => $worker_entry_id));
                $worker_data = $worker_data[0];
                $data['worker_data'] = $worker_data;
                set_page('worker/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(WORKER_MODULE_ID,"add")) {
                set_page('worker/add', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
        
    }

    function save_worker_entry() {
        $return = array();
        $post_data = $this->input->post();
//        echo '<pre>';        print_r($post_data); exit;
//        echo '<pre>'; print_r($_FILES); exit;
        if (isset($post_data['worker_entry_id']) && !empty($post_data['worker_entry_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['worker_entry_id'] = $post_data['worker_entry_id'];
            $result = $this->crud->update('worker_entry', $post_data, $where_array);
            echo $this->db->last_query();
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Worker Entry Updated Successfully');
            }
        } else {
            $worker_data = array();
            $worker_data['person_name'] = $post_data['person_name'];
            $worker_data['process_id'] = $post_data['process_id'];
            $worker_data['salary'] = $post_data['salary'];
            $worker_data['worker_type_id'] = $post_data['worker_type_id'];
            $worker_data['created_at'] = $this->now_time;
            $worker_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('worker_entry', $worker_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Worker Entry Added Successfully');
                $last_query_id = $this->db->insert_id();
            } else {
                $return['error'] = "errorAdded";
            }
        }
        if ($result) {
            if (isset($_FILES) && !empty($_FILES)) {
                $file_names_arr = array();
                $i = '1';
                foreach ($_FILES as $key => $files_name) {
                    $_FILES[$key]['name'] = $last_query_id.'_'.$i.'_'.$_FILES[$key]['name'];
                    $upload_name = $this->crud->upload_file($key, 'uploads/worker_images');
                    if(!empty($upload_name)){
                        $file_names_arr[] = $upload_name;
                    }
                    $i++;
                }
            }
            if(!empty($file_names_arr)){
                $file_names['files'] = implode(',', $file_names_arr);
                $where_array['worker_entry_id'] = $last_query_id;
                $this->crud->update('worker_entry', $file_names, $where_array);
            }
        }
        print json_encode($return);
        exit;
    }
    
    function worker_list() {
        if($this->applib->have_access_role(WORKER_MODULE_ID,"view")) {
            set_page('worker/worker_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }   
        
    function Worker_entry_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'worker_entry we';
        $config['select'] = 'we.*, pm.account_name AS process_name';
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = we.process_id');
        $config['column_search'] = array('we.person_name' ,'pm.account_name','we.salary', 'we.worker_type_id');
        $config['column_order'] = array(null,'we.person_name' ,'pm.account_name','we.salary', 'we.worker_type_id', null);
        $config['order'] = array('we.worker_entry_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(WORKER_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(WORKER_MODULE_ID, "edit");
        foreach ($list as $worker_entry) {
            $row = array();
            $action = '';
            if($role_edit){
//            $action .= '<a href="' . base_url("worker/add/" . $worker_entry->worker_entry_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('worker/delete_worker/' . $worker_entry->worker_entry_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }

            $row[] = $action;
            $row[] = $worker_entry->person_name;
            $row[] = $worker_entry->process_name;
            $row[] = $worker_entry->salary;
            if($worker_entry->worker_type_id == 1){
                $worker_entry->worker_type_id = 'Worker';
            } else {
                $worker_entry->worker_type_id = 'Sales';
            }
            $row[] = $worker_entry->worker_type_id;
            $image_popup = '';
            if(!empty($worker_entry->files)){
                $files = explode(",",$worker_entry->files);
                foreach ($files as $file){
                    $image_popup .= '<a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="'.IMAGE_URL.'worker_images/'.$file.'" ><i class="fa fa-image"></i></a> &nbsp;';
                }
            }
            $row[] = $image_popup;
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
       $this->crud->delete($table, array($id_name => $id));
   }
   
    function delete_worker($id = '') {
       $table = $_POST['table_name'];
       $id_name = $_POST['id_name'];
       $worker_data = $this->crud->get_row_by_id('worker_entry', array('worker_entry_id' => $id));
       if(!empty($worker_data[0]->files)){
           $files = explode(",",$worker_data[0]->files);
           foreach ($files as $file){
               unlink(PUBPATH."/uploads/worker_images/".$file);
           }
       }
       $this->crud->delete($table, array($id_name => $id));
   }
   
   
}
