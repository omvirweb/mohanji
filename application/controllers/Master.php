<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

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

    function category($category_id = '') {
        $data = array();
        if (isset($category_id) && !empty($category_id)) {
            if($this->applib->have_access_role(CATEGORY_MODULE_ID,"edit") || $this->applib->have_access_role(CATEGORY_MODULE_ID,"view")) {
                $category_data = $this->crud->get_row_by_id('category', array('category_id' => $category_id));
                $category_data = $category_data[0];
                $category_data->created_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$category_data->created_by));
                if($category_data->created_by !=$category_data->updated_by){
                    $category_data->updated_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $category_data->updated_by));
                }else{
                    $category_data->updated_by_name = $category_data->created_by_name;
                }
                $data['category_data'] = $category_data;
                set_page('master/category', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(CATEGORY_MODULE_ID,"add") || $this->applib->have_access_role(CATEGORY_MODULE_ID,"view")) {
                set_page('master/category', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_category() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['category_name']) && !empty($post_data['category_name'])) {
            if (isset($post_data['category_id']) && !empty($post_data['category_id'])) {
                $size_duplication = $this->crud->get_row_by_id('category', array('category_name' => $post_data['category_name'], 'category_id !=' => $post_data['category_id']));
            } else {
                $size_duplication = $this->crud->get_row_by_id('category', array('category_name' => $post_data['category_name']));
            }
            if (isset($size_duplication) && !empty($size_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Category Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['category_id']) && !empty($post_data['category_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['category_id'] = $post_data['category_id'];
            $result = $this->crud->update('category', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Category Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('category', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }
    
    function category_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'category c';
        $config['select'] = 'c.*, cg.category_group_name';
        $config['joins'][] = array('join_table' => 'category_group cg', 'join_by' => 'cg.category_group_id = c.category_group_id ');
        $config['column_search'] = array('c.category_name', 'cg.category_group_name', 'c.hsn_code', 'c.gst_rate');
        $config['column_order'] = array(null, 'c.category_name', 'cg.category_group_name', 'c.hsn_code', 'c.gst_rate');
        $config['order'] = array('c.category_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(CATEGORY_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(CATEGORY_MODULE_ID, "edit");
        foreach ($list as $category) {
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("master/category/" . $category->category_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $category->category_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $category->category_name;
            $row[] = $category->category_group_name;
            $row[] = $category->hsn_code;
            $row[] = $category->gst_rate;

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
    
    function delete_category($id=''){
        $this->crud->delete('item_master', array('category_id' => $id));
        $this->crud->delete('category', array('category_id' => $id));
    }
    
    function design_master($design_id = '') {
        $data = array();
        if (isset($design_id) && !empty($design_id)) {
            if($this->applib->have_access_role(DESIGN_MODULE_ID,"edit") || $this->applib->have_access_role(DESIGN_MODULE_ID,"view")) {
                $design_data = $this->crud->get_row_by_id('design_master', array('design_id' => $design_id));
                $design_data = $design_data[0];
                $design_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$design_data ->created_by));
                if($design_data->created_by != $design_data->updated_by){
                    $design_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id'=>$design_data->updated_by));
                }else{
                    $design_data->updated_by_name = $design_data->created_by_name;
                }
                $data['design_data'] = $design_data;
                set_page('master/design_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(DESIGN_MODULE_ID,"add") || $this->applib->have_access_role(DESIGN_MODULE_ID,"view")) {
                set_page('master/design_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }
    
    function save_design_master() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['design_no']) && !empty($post_data['design_no'])) {
            if (isset($post_data['design_id']) && !empty($post_data['design_id'])) {
                $design_no_duplication = $this->crud->get_row_by_id('design_master', array('design_no' => $post_data['design_no'], 'design_id !=' => $post_data['design_id']));
            } else {
                $design_no_duplication = $this->crud->get_row_by_id('design_master', array('design_no' => $post_data['design_no']));
            }
            if (isset($design_no_duplication) && !empty($design_no_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Design No Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['design_id']) && !empty($post_data['design_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['design_id'] = $post_data['design_id'];
            $result = $this->crud->update('design_master', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Design Master Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('design_master', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function design_master_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'design_master dm';
        $config['select'] = 'dm.*';
        //$config['joins'][] = array('join_table' => 'category_group cg', 'join_by' => 'cg.category_group_id = st.category_group_id ');
        $config['column_search'] = array('dm.design_no', 'dm.file_no', 'dm.stl_3dm_no', 'dm.die_making', 'dm.die_no');
        $config['column_order'] = array(null, 'dm.design_no', 'dm.file_no', 'dm.stl_3dm_no', 'dm.die_making', 'dm.die_no');
        $config['order'] = array('dm.design_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(DESIGN_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(DESIGN_MODULE_ID, "edit");
        foreach ($list as $design) {
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("master/design_master/" . $design->design_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_design_master/' . $design->design_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $design->design_no;
            $row[] = $design->file_no;
            $row[] = $design->stl_3dm_no;
            $row[] = $design->die_making;
            $row[] = $design->die_no;

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

    function delete_design_master($id=''){
        $this->crud->delete('design_master', array('design_id' => $id));
    }
    
    function opening_stock($opening_stock_id = '') {
        $data = array();
        $data['filter_category'] = $this->crud->get_all_records('category', 'category_id', '');
        $data['filter_items'] = $this->crud->get_all_records('item_master', 'item_id', '');
        $touch = $this->crud->get_all_records('carat', 'purity', 'ASC');        
        $data['touch'] = $touch;
        if (isset($opening_stock_id) && !empty($opening_stock_id)) {
            if($this->applib->have_access_role(OPENING_STOCK_MODULE_ID,"edit") || $this->applib->have_access_role(OPENING_STOCK_MODULE_ID,"view")) {
                $opening_stock_data = $this->crud->get_row_by_id('opening_stock', array('opening_stock_id' => $opening_stock_id));
                $opening_stock_data = $opening_stock_data[0];
                $opening_stock_data->created_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$opening_stock_data->created_by));
                if($opening_stock_data->created_by !=$opening_stock_data->updated_by){
                    $opening_stock_data->updated_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $opening_stock_data->updated_by));
                }else{
                    $opening_stock_data->updated_by_name = $opening_stock_data->created_by_name;
                }
                $data['opening_stock_data'] = $opening_stock_data;
                set_page('master/opening_stock', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(OPENING_STOCK_MODULE_ID,"add") || $this->applib->have_access_role(OPENING_STOCK_MODULE_ID,"view")) {
                set_page('master/opening_stock', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_opening_stock() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['opening_stock_id']) && !empty($post_data['opening_stock_id'])) {
            $opening_stock_id = $post_data['opening_stock_id'];
            $opening_stock = $this->crud->get_row_by_id('opening_stock', array('opening_stock_id' => $opening_stock_id));
            $post_data['department_id'] = $opening_stock[0]->department_id;
            $post_data['category_id'] = $opening_stock[0]->category_id;
            $post_data['item_id'] = $opening_stock[0]->item_id;
            $post_data['tunch'] = $post_data['tunch'];
        } else {
            $post_data['department_id'] = isset($post_data['department_id']) && !empty($post_data['department_id']) ? $post_data['department_id'] : NULL;
            $post_data['category_id'] = isset($post_data['category_id']) && !empty($post_data['category_id']) ? $post_data['category_id'] : NULL;
            $post_data['item_id'] = isset($post_data['item_id']) && !empty($post_data['item_id']) ? $post_data['item_id'] : NULL;
            $post_data['tunch'] = isset($post_data['tunch']) && !empty($post_data['tunch']) ? $post_data['tunch'] : NULL;
            $post_data['design_no'] = isset($post_data['design_no']) && !empty($post_data['design_no']) ? $post_data['design_no'] : NULL;
        }
        $item_stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $post_data['item_id']));
        if($item_stock_method == STOCK_METHOD_ITEM_WISE){ } else {
            if(isset($opening_stock_id) && !empty($opening_stock_id)){
                $item_stock_duplication = $this->crud->get_row_by_id('opening_stock' , array('department_id' => $post_data['department_id'], 'category_id' => $post_data['category_id'], 'item_id' => $post_data['item_id'], 'tunch' => $post_data['tunch'], 'design_no' => $post_data['design_no'], 'opening_stock_id !=' => $opening_stock_id));
            } else {
                $item_stock_duplication = $this->crud->get_row_by_id('opening_stock' , array('department_id' => $post_data['department_id'], 'category_id' => $post_data['category_id'], 'item_id' => $post_data['item_id'], 'tunch' => $post_data['tunch'], 'design_no' => $post_data['design_no']));
            }
            if(isset($item_stock_duplication) && !empty($item_stock_duplication)){
                $return['error'] = "Exist";
                $return['error_exist'] = 'Opening Stock Already Exist for same Department, Category, Item and Tunch, Design No.';
                print json_encode($return);
                exit;
            }
        }
        if (isset($opening_stock_id) && !empty($opening_stock_id)) {
            $update_data = array();
            $update_data['grwt'] = $post_data['grwt'] = number_format((float) $post_data['grwt'], '3', '.', '');
            $update_data['less'] = $post_data['less'] = number_format((float) $post_data['less'], '3', '.', '');
            $update_data['ntwt'] = $post_data['ntwt'] = number_format((float) $post_data['ntwt'], '3', '.', '');
            $update_data['fine'] = $post_data['fine'] = number_format((float) $post_data['fine'], '3', '.', '');
            $update_data['design_no'] = $post_data['design_no'];
            $update_data['rfid_number'] = $post_data['rfid_number'];
            $update_data['opening_pcs'] = $post_data['opening_pcs'];
            $update_data['updated_at'] = $this->now_time;
            $update_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('opening_stock', $update_data, array('opening_stock_id' => $opening_stock_id));
            
            unset($post_data['design_no']);
            unset($update_data['design_no']);
            unset($post_data['rfid_number']);
            unset($update_data['rfid_number']);
            unset($post_data['opening_pcs']);
            unset($update_data['opening_pcs']);

            if($item_stock_method == STOCK_METHOD_ITEM_WISE){
                $exist_item_stock = $this->crud->get_row_by_id('item_stock', array('purchase_sell_item_id' => $opening_stock_id, 'stock_type' => STOCK_TYPE_OPENING_STOCK_ID));
                if(!empty($exist_item_stock)){
                    $exist_item_stock = $exist_item_stock[0];
                    $current_stock_grwt = number_format((float) $post_data['grwt'], '3', '.', '');
                    $current_stock_less = number_format((float) $post_data['less'], '3', '.', '');
                    $current_stock_ntwt = number_format((float) $post_data['ntwt'], '3', '.', '');
                    $current_stock_fine = number_format((float) $post_data['fine'], '3', '.', '');
                    $update_item_stock = array();
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_stock->item_stock_id));
                } else {
                    $post_data['created_at'] = $this->now_time;
                    $post_data['created_by'] = $this->logged_in_id;
                    $post_data['updated_at'] = $this->now_time;
                    $post_data['updated_by'] = $this->logged_in_id;
                    $post_data['purchase_sell_item_id'] = $opening_stock_id;
                    $post_data['stock_type'] = STOCK_TYPE_OPENING_STOCK_ID;
                    $this->crud->insert('item_stock', $post_data);
                }
            } else {
                $opening_stock_data = $this->crud->get_row_by_id('opening_stock', array('opening_stock_id' => $opening_stock_id));
                $this->crud->update('item_stock', $update_data, array('department_id' => $opening_stock_data[0]->department_id, 'category_id' => $opening_stock_data[0]->category_id, 'item_id' => $opening_stock_data[0]->item_id, 'tunch' => $opening_stock_data[0]->tunch));
            }
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Opening Stock Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('opening_stock', $post_data);
            $opening_stock_id = $this->db->insert_id();
            
            unset($post_data['design_no']);
            unset($post_data['rfid_number']);
            unset($post_data['opening_pcs']);

            if($item_stock_method == STOCK_METHOD_ITEM_WISE){
                $post_data['purchase_sell_item_id'] = $opening_stock_id;
                $post_data['stock_type'] = STOCK_TYPE_OPENING_STOCK_ID;
                $this->crud->insert('item_stock', $post_data);
            } else {
                $where_opening = array('department_id' => $post_data['department_id'], 'category_id' => $post_data['category_id'], 'item_id' => $post_data['item_id'], 'tunch' => $post_data['tunch']);
                $exist_opening = $this->crud->get_row_by_id('item_stock', $where_opening);
                if(!empty($exist_opening)){
                    $exist_opening = $exist_opening[0];
                    $current_stock_grwt = number_format((float) $exist_opening->grwt, '3', '.', '') + number_format((float) $post_data['grwt'], '3', '.', '');
                    $current_stock_less = number_format((float) $exist_opening->less, '3', '.', '') + number_format((float) $post_data['less'], '3', '.', '');
                    $current_stock_ntwt = number_format((float) $exist_opening->ntwt, '3', '.', '') + number_format((float) $post_data['ntwt'], '3', '.', '');
                    $current_stock_fine = number_format((float) $exist_opening->fine, '3', '.', '') + number_format((float) $post_data['fine'], '3', '.', '');
                    $update_item_stock = array();
                    $update_item_stock['grwt'] = number_format((float) $current_stock_grwt, '3', '.', '');
                    $update_item_stock['less'] = number_format((float) $current_stock_less, '3', '.', '');
                    $update_item_stock['ntwt'] = number_format((float) $current_stock_ntwt, '3', '.', '');
                    $update_item_stock['fine'] = number_format((float) $current_stock_fine, '3', '.', '');
                    $update_item_stock['updated_at'] = $this->now_time;
                    $update_item_stock['updated_by'] = $this->logged_in_id;
                    $this->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_opening->item_stock_id));
                } else {
                    $this->crud->insert('item_stock', $post_data);
                }
            }
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }
    
    function opening_stock_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'opening_stock i';
        $config['select'] = 'i.*,a.account_name AS department,im.item_name,c.category_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = i.department_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = i.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category c', 'join_by' => 'c.category_id= i.category_id', 'join_type' => 'left');
        $config['custom_where'] = ' i.grwt != 0 AND i.ntwt != 0 AND i.fine != 0';
        $config['column_search'] = array('a.account_name', 'c.category_name', 'im.item_name', 'i.grwt', 'i.less', 'i.ntwt', 'i.tunch', 'i.fine', 'i.design_no', 'i.rfid_number', 'i.opening_pcs');
        $config['column_order'] = array(null, 'a.account_name', 'c.category_name', 'im.item_name', 'i.grwt', 'i.less', 'i.ntwt', 'i.tunch', 'i.fine', 'i.design_no', 'i.rfid_number', 'i.opening_pcs');
        if (isset($post_data['filter_department_id']) && !empty($post_data['filter_department_id'])) {
            $config['wheres'][] = array('column_name' => 'i.department_id', 'column_value' => $post_data['filter_department_id']);
        }
        if (isset($post_data['filter_item_id']) && !empty($post_data['filter_item_id'])) {
            $config['wheres'][] = array('column_name' => 'i.item_id', 'column_value' => $post_data['filter_item_id']);
        }
        if (isset($post_data['filter_category_id']) && !empty($post_data['filter_category_id'])) {
            $config['wheres'][] = array('column_name' => 'i.category_id', 'column_value' => $post_data['filter_category_id']);
        }
        $config['order'] = array('i.opening_stock_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(OPENING_STOCK_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(OPENING_STOCK_MODULE_ID, "edit");
        foreach ($list as $opening_stock) {
            $row = array();
            $action = '';
            $found = false;
            $item_sells = $this->crud->get_used_stock_from_sell($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_sells)){
                $found = true;
            }
            $item_metal = $this->crud->get_used_stock_from_metal($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_metal)){
                $found = true;
            }
            $item_st = $this->crud->get_total_transfer_grwt($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_st)){
                $found = true;
            }
            $item_ir = $this->crud->get_used_stock_from_issue_receive($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_ir)){
                $found = true;
            }
            $item_mhm = $this->crud->get_used_stock_from_manu_hand_made($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_mhm)){
                $found = true;
            }
            $item_mc = $this->crud->get_used_stock_from_machine_chain($opening_stock->department_id,$opening_stock->category_id,$opening_stock->item_id,$opening_stock->tunch);
            if(!empty($item_mc)){
                $found = true;
            }
//            $stock_method = $this->crud->get_column_value_by_id('item_master', 'stock_method', array('item_id' => $opening_stock->item_id));
//            if($stock_method == STOCK_METHOD_ITEM_WISE){
//                $found = true;
//            }
            if($found != true){
                if($role_edit){
                    $action .= '<a href="' . base_url("master/opening_stock/" . $opening_stock->opening_stock_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
                if($role_delete){
                    $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_opening_stock/' . $opening_stock->opening_stock_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                }
            }
            $row[] = $action;
            $row[] = $opening_stock->department;
            $row[] = $opening_stock->category_name;
            $row[] = $opening_stock->item_name;
            $row[] = number_format($opening_stock->grwt, 3, '.', '');
            $row[] = number_format($opening_stock->less, 3, '.', '');
            $row[] = number_format($opening_stock->ntwt, 3, '.', '');
            $row[] = number_format($opening_stock->tunch, 2, '.', '');
            $row[] = number_format($opening_stock->fine, 3, '.', '');
            $row[] = $opening_stock->design_no;
            $row[] = $opening_stock->rfid_number;
            $row[] = $opening_stock->opening_pcs;

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
    
    function delete_opening_stock($id=''){
        $return = array();
        $opening_stock = $this->crud->get_row_by_id('opening_stock',array('opening_stock_id' => $id));
        if(!empty($opening_stock)){
            $item_stock_method = $this->crud->get_column_value_by_id('item_master','stock_method',array('item_id' => $opening_stock[0]->item_id));
            if($item_stock_method == STOCK_METHOD_ITEM_WISE){
                $this->crud->delete('item_stock', array('purchase_sell_item_id' => $id, 'stock_type' => STOCK_TYPE_OPENING_STOCK_ID));
            } else {
                $this->crud->delete('item_stock', array('department_id' => $opening_stock[0]->department_id, 'category_id' => $opening_stock[0]->category_id, 'item_id' => $opening_stock[0]->item_id, 'tunch' => $opening_stock[0]->tunch));
            }
            $this->crud->delete('opening_stock', array('opening_stock_id' => $id));
            $return['success'] = 'Deleted';
        }
        echo json_encode($return);
        exit;
    }

    function setting() {
        $data = array();
        if($this->applib->have_access_role(SETTING_MODULE_ID,"edit") || $this->applib->have_access_role(SETTING_MODULE_ID, 'view')) {
            $setting_data = $this->crud->getFromSQL('SELECT * FROM `settings` ORDER BY `fields_section` ASC, `settings_id` ASC');
//            echo '<pre>'; print_r($setting_data); exit;
            $data['setting_data'] = $setting_data;
            $setting_mac_address = $this->crud->get_all_records('setting_mac_address', 'id', 'asc');
            $lineitems = array();
            if(!empty($setting_mac_address)){
                foreach($setting_mac_address as $lot_item){
                    $lineitems[] = json_encode($lot_item);
                }
            }
            $data['order_lot_item'] = implode(',', $lineitems);
            set_page('master/setting', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function update_setting() {
        $post_data = $this->input->post();
        $line_items_data = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
        }
//        echo '<pre>'; print_r($post_data); exit;
        if(!empty($post_data['settings_value']['without_purchase_sell_allow'])){
            $post_data['settings_value']['without_purchase_sell_allow'] = '1';
        } else {
            $post_data['settings_value']['without_purchase_sell_allow'] = '0';
        }
        if(!empty($post_data['settings_value']['enter_key_to_next'])){
            $post_data['settings_value']['enter_key_to_next'] = '1';
        } else {
            $post_data['settings_value']['enter_key_to_next'] = '0';
        }
        if(!empty($post_data['settings_value']['use_rfid'])){
            $post_data['settings_value']['use_rfid'] = '1';
        } else {
            $post_data['settings_value']['use_rfid'] = '0';
        }
        if(!empty($post_data['settings_value']['use_barcode'])){
            $post_data['settings_value']['use_barcode'] = '1';
        } else {
            $post_data['settings_value']['use_barcode'] = '0';
        }
        if(!empty($post_data['settings_value']['sell_purchase_difference'])){
            $post_data['settings_value']['sell_purchase_difference'] = '1';
        } else {
            $post_data['settings_value']['sell_purchase_difference'] = '0';
        }
        if(!empty($post_data['settings_value']['show_backup_email_menu'])){
            $post_data['settings_value']['show_backup_email_menu'] = '1';
        } else {
            $post_data['settings_value']['show_backup_email_menu'] = '0';
        }
        if(!empty($post_data['settings_value']['ask_ad_charges_in_sell_purchase'])){
            $post_data['settings_value']['ask_ad_charges_in_sell_purchase'] = '1';
        } else {
            $post_data['settings_value']['ask_ad_charges_in_sell_purchase'] = '0';
        }
        if(!empty($post_data['settings_value']['ask_less_ad_details_in_sell_purchase'])){
            $post_data['settings_value']['ask_less_ad_details_in_sell_purchase'] = '1';
        } else {
            $post_data['settings_value']['ask_less_ad_details_in_sell_purchase'] = '0';
        }
        $post_data['settings_value']['use_category'] = (isset($post_data['settings_value']['use_category']) && !empty($post_data['settings_value']['use_category'])) ? '1' : '0';
        $post_data['settings_value']['display_net_amount_in_outstanding'] = (isset($post_data['settings_value']['display_net_amount_in_outstanding']) && !empty($post_data['settings_value']['display_net_amount_in_outstanding'])) ? '1' : '0';
        $post_data['settings_value']['account_mobile_no_is_required'] = (isset($post_data['settings_value']['account_mobile_no_is_required']) && !empty($post_data['settings_value']['account_mobile_no_is_required'])) ? '1' : '0';
        $post_data['settings_value']['ledger_print_in_page_a5'] = (isset($post_data['settings_value']['ledger_print_in_page_a5']) && !empty($post_data['settings_value']['ledger_print_in_page_a5'])) ? '1' : '0';
        $post_data['settings_value']['default_from_financial_start_year'] = (isset($post_data['settings_value']['default_from_financial_start_year']) && !empty($post_data['settings_value']['default_from_financial_start_year'])) ? '1' : '0';
        $post_data['settings_value']['inventory_data_modules'] = (isset($post_data['settings_value']['inventory_data_modules']) && !empty($post_data['settings_value']['inventory_data_modules'])) ? '1' : '0';
        $post_data['settings_value']['department_2'] = (isset($post_data['settings_value']['department_2']) && !empty($post_data['settings_value']['department_2'])) ? '1' : '0';
        $post_data['settings_value']['remark_2'] = (isset($post_data['settings_value']['remark_2']) && !empty($post_data['settings_value']['remark_2'])) ? '1' : '0';
        $post_data['settings_value']['delivered_not_2'] = (isset($post_data['settings_value']['delivered_not_2']) && !empty($post_data['settings_value']['delivered_not_2'])) ? '1' : '0';
        $post_data['settings_value']['tunch_textbox_2'] = (isset($post_data['settings_value']['tunch_textbox_2']) && !empty($post_data['settings_value']['tunch_textbox_2'])) ? '1' : '0';
        $post_data['settings_value']['charges_2'] = (isset($post_data['settings_value']['charges_2']) && !empty($post_data['settings_value']['charges_2'])) ? '1' : '0';
        $post_data['settings_value']['less_netwt_2'] = (isset($post_data['settings_value']['less_netwt_2']) && !empty($post_data['settings_value']['less_netwt_2'])) ? '1' : '0';
        $post_data['settings_value']['wstg_2'] = (isset($post_data['settings_value']['wstg_2']) && !empty($post_data['settings_value']['wstg_2'])) ? '1' : '0';
        $post_data['settings_value']['lineitem_image_2'] = (isset($post_data['settings_value']['lineitem_image_2']) && !empty($post_data['settings_value']['lineitem_image_2'])) ? '1' : '0';
        $post_data['settings_value']['ask_discount_in_sell_purchase'] = (isset($post_data['settings_value']['ask_discount_in_sell_purchase']) && !empty($post_data['settings_value']['ask_discount_in_sell_purchase'])) ? '1' : '0';
        $post_data['settings_value']['c_r_amount_separate'] = (isset($post_data['settings_value']['c_r_amount_separate']) && !empty($post_data['settings_value']['c_r_amount_separate'])) ? '1' : '0';
        $post_data['settings_value']['approx_amount'] = (isset($post_data['settings_value']['approx_amount']) && !empty($post_data['settings_value']['approx_amount'])) ? '1' : '0';
        $post_data['settings_value']['sell_purchase_type_2'] = (isset($post_data['settings_value']['sell_purchase_type_2']) && !empty($post_data['settings_value']['sell_purchase_type_2'])) ? '1' : '0';
        $post_data['settings_value']['sell_purchase_type_3'] = (isset($post_data['settings_value']['sell_purchase_type_3']) && !empty($post_data['settings_value']['sell_purchase_type_3'])) ? '1' : '0';
        $post_data['settings_value']['line_item_remark'] = (isset($post_data['settings_value']['line_item_remark']) && !empty($post_data['settings_value']['line_item_remark'])) ? '1' : '0';
        $post_data['settings_value']['line_item_gold_silver_rate'] = (isset($post_data['settings_value']['line_item_gold_silver_rate']) && !empty($post_data['settings_value']['line_item_gold_silver_rate'])) ? '1' : '0';
        $post_data['settings_value']['display_line_item_remark_in_ledger'] = (isset($post_data['settings_value']['display_line_item_remark_in_ledger']) && !empty($post_data['settings_value']['display_line_item_remark_in_ledger'])) ? '1' : '0';
        $post_data['settings_value']['display_line_item_remark_in_print'] = (isset($post_data['settings_value']['display_line_item_remark_in_print']) && !empty($post_data['settings_value']['display_line_item_remark_in_print'])) ? '1' : '0';
        $post_data['settings_value']['sell_purchase_entry_with_gst'] = (isset($post_data['settings_value']['sell_purchase_entry_with_gst']) && !empty($post_data['settings_value']['sell_purchase_entry_with_gst'])) ? '1' : '0';
        $post_data['settings_value']['sell_purchase_print_display_gold_fine_column'] = (isset($post_data['settings_value']['sell_purchase_print_display_gold_fine_column']) && !empty($post_data['settings_value']['sell_purchase_print_display_gold_fine_column'])) ? '1' : '0';
        $post_data['settings_value']['xrf_box_no_mandatory'] = (isset($post_data['settings_value']['xrf_box_no_mandatory']) && !empty($post_data['settings_value']['xrf_box_no_mandatory'])) ? '1' : '0';
        
        foreach ($post_data['settings_value'] as $key => $val) {
            $result = $this->crud->update('settings', array('settings_value' => $val), array('settings_key' => $key));
        }
        if(isset($post_data['deleted_mac_aadress_id'])){
            $this->db->where_in('id', $post_data['deleted_mac_aadress_id']);
            $this->db->delete('setting_mac_address');
        }
        if (!empty($line_items_data)) {
            foreach ($line_items_data as $lineitem) {
                if(isset($lineitem->id) && !empty($lineitem->id)){
                    $this->db->where('id', $lineitem->id);
                    $this->db->update('setting_mac_address', array('mac_address' => $lineitem->mac_address));
                } else {
                    $this->crud->insert('setting_mac_address', array('mac_address' => $lineitem->mac_address));
                }
            }
        }
        if ($result) {
            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'theme_color_code', $post_data['settings_value']['theme_color_code']);
            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'sell_purchase_difference', $post_data['settings_value']['sell_purchase_difference']);
            $this->session->set_flashdata('success', true);
            $this->session->set_flashdata('message', 'Setting Data Updated Successfully');
        }
    }
    
    function ad_master() {
        $data = array();
        if ($this->applib->have_access_role(AD_MASTER_ID, "view") || $this->applib->have_access_role(AD_MASTER_ID, "add")) {
            if (!empty($_POST['ad_id']) && isset($_POST['ad_id'])) {
                if ($this->applib->have_access_role(AD_MASTER_ID, "edit")) {
                    $result = $this->crud->get_data_row_by_id('ad', 'ad_id', $_POST['ad_id']);
                    $data = array(
                        'ad_id' => $result->ad_id,
                        'ad_name' => $result->ad_name,
                        'ad_description' => $result->ad_description,
                        'is_nang_setting' => $result->is_nang_setting,
                        'is_sell_purchase_ad_charges' => $result->is_sell_purchase_ad_charges,
                        'is_sell_purchase_less_ad_details' => $result->is_sell_purchase_less_ad_details,
                    );
                    set_page('master/ad_master', $data);
                } else {
                    $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                    redirect("/");
                }
            } else {
                set_page('master/ad_master', $data);
            }
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function save_ad() {
        $return = array();
        $post_data = $this->input->post();
        if(isset($post_data['is_nang_setting'])){
            unset($post_data['is_nang_setting']);
            $data['is_nang_setting'] = '1';
        } else {
            $data['is_nang_setting'] = '0';
        }
        if(isset($post_data['is_sell_purchase_ad_charges'])){
            unset($post_data['is_sell_purchase_ad_charges']);
            $data['is_sell_purchase_ad_charges'] = '1';
        } else {
            $data['is_sell_purchase_ad_charges'] = '0';
        }
        if(isset($post_data['is_sell_purchase_less_ad_details'])){
            unset($post_data['is_sell_purchase_less_ad_details']);
            $data['is_sell_purchase_less_ad_details'] = '1';
        } else {
            $data['is_sell_purchase_less_ad_details'] = '0';
        }
        if (isset($post_data['ad_id']) && !empty($post_data['ad_id'])) {
            $id_result = $this->crud->getFromSQL(" SELECT ad_id FROM ad WHERE ad_name = '".trim($post_data['ad_name'])."' AND ad_id != ".$post_data['ad_id']." ");
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['ad_name'] = $post_data['ad_name'];
            $data['ad_description'] = $post_data['ad_description'];
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = $this->logged_in_id;
            $where_array['ad_id'] = $post_data['ad_id'];
            $result = $this->crud->update('ad', $data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'AD Updated Successfully');
            }
        } else {
            $id_result = $this->crud->getFromSQL(" SELECT ad_id FROM ad WHERE ad_name = '".trim($post_data['ad_name'])."' ");
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                print json_encode($return);
                exit;
            }
            $data['ad_name'] = $post_data['ad_name'];
            $data['ad_description'] = $post_data['ad_description'];
            $data['created_at'] = $this->now_time;
            $data['updated_at'] = $this->now_time;
            $data['updated_by'] = $this->logged_in_id;
            $data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('ad', $data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'AD Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }
    
    function ad_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'ad ad';
        $config['select'] = 'ad.*, IF(ad.is_nang_setting = 1,"Yes", "No") as is_nang_setting, IF(ad.is_sell_purchase_ad_charges = 1,"Yes", "No") as is_sell_purchase_ad_charges, IF(ad.is_sell_purchase_less_ad_details = 1,"Yes", "No") as is_sell_purchase_less_ad_details ';
        $config['column_search'] = array('ad.ad_name','ad.ad_description','IF(ad.is_nang_setting = 1,"Yes", "No")', 'IF(ad.is_sell_purchase_ad_charges = 1,"Yes", "No")', 'IF(ad.is_sell_purchase_less_ad_details = 1,"Yes", "No")');
        $config['column_order'] = array(null, 'ad.ad_name','ad.ad_description','ad.is_nang_setting','ad.is_sell_purchase_ad_charges','ad.is_sell_purchase_less_ad_details');
        $config['order'] = array('ad.ad_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $role_delete = $this->app_model->have_access_role(AD_MASTER_ID, "delete");
        $role_edit = $this->app_model->have_access_role(AD_MASTER_ID, "edit");
        foreach ($list as $ad) {
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<form id="edit_' . $ad->ad_id . '" method="post" action="' . base_url() . 'master/ad_master" class="pull-left">
                    <input type="hidden" name="ad_id" id="ad_id" value="' . $ad->ad_id . '">
                    <a class="edit_button btn-primary btn-xs" href="javascript:{}" onclick="document.getElementById(\'edit_' . $ad->ad_id . '\').submit();" title="Edit AD"><i class="fa fa-edit"></i></a>
                </form>';    
            }
            if($role_delete){
                $action .= ' | <a href="javascript:void(0);" class="delete_button btn-danger btn-xs" data-href="' . base_url('master/delete_plan/' . $ad->ad_id) . '"><i class="fa fa-trash"></i></a>';
            }
            $row[] = $action;
            $row[] = $ad->ad_name;
            $row[] = $ad->ad_description;
            $row[] = $ad->is_nang_setting;
            $row[] = $ad->is_sell_purchase_ad_charges;
            $row[] = $ad->is_sell_purchase_less_ad_details;
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
        
    function tunch($carat_id = '') {
        $data = array();
        if (isset($carat_id) && !empty($carat_id)) {
            if($this->applib->have_access_role(TUNCH_MODULE_ID,"edit") || $this->applib->have_access_role(TUNCH_MODULE_ID,"view")) {
                $carat_data = $this->crud->get_row_by_id('carat', array('carat_id' => $carat_id));
                $carat_data = $carat_data[0];
                $carat_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$carat_data->created_by));
                if($carat_data->created_at != $carat_data->updated_at){
                    $carat_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$carat_data->updated_by));
                }else{
                    $carat_data->updated_by_name = $carat_data->created_by_name;
                }
                $data['carat_data'] = $carat_data;
                set_page('master/tunch', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(TUNCH_MODULE_ID,"add") || $this->applib->have_access_role(TUNCH_MODULE_ID,"view")) {
                set_page('master/tunch', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_tunch() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['purity']) && !empty($post_data['purity'])) {
            if (isset($post_data['carat_id']) && !empty($post_data['carat_id'])) {
                $size_duplication = $this->crud->get_row_by_id('carat', array('purity' => $post_data['purity'], 'carat_id !=' => $post_data['carat_id']));
            } else {
                $size_duplication = $this->crud->get_row_by_id('carat', array('purity' => $post_data['purity']));
            }
            if (isset($size_duplication) && !empty($size_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Tunch Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if(isset($post_data['show_in_xrf'])){
            $post_data['show_in_xrf'] = '1';
        } else {
            $post_data['show_in_xrf'] = '0';
        }
        if (isset($post_data['carat_id']) && !empty($post_data['carat_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['carat_id'] = $post_data['carat_id'];
            $result = $this->crud->update('carat', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Tunch Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('carat', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function carat_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'carat c';
        $config['select'] = 'c.*,IF(c.show_in_xrf = 1,"Yes", "No") as show_in_xrf';
        $config['column_search'] = array('c.purity','IF(c.show_in_xrf = 1,"Yes", "No")');
        $config['column_order'] = array(null, 'c.purity','c.show_in_xrf');
        $config['order'] = array('c.carat_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(TUNCH_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(TUNCH_MODULE_ID, "edit");
        foreach ($list as $carat) {
            $row = array();
            $action = '';
            $is_delete = 0;
            $table_names = explode(',', TUNCH_USED_TABLE);
            if(!empty($table_names)){
                foreach ($table_names as $tunch_tbl){
                    $table_field_d = explode('>>', $tunch_tbl);
                    $table_t_name = $table_field_d[0];
                    $table_t_field = $table_field_d[1];
                    if($table_t_name == 'order_lot_item'){
                        $tn_data = $this->crud->getFromSQL("SELECT * FROM ".$table_t_name." WHERE ".$table_t_field." = ".$carat->carat_id." ");
                    } else {
                        $tn_data = $this->crud->getFromSQL("SELECT * FROM ".$table_t_name." WHERE ".$table_t_field." = ".$carat->purity." ");
                    }
                    if(!empty($tn_data)){
                        $is_delete = 1;
                        break;
                    }
                }
            }
            if($role_edit){
                if($is_delete == 0){
                    $action .= '<a href="' . base_url("master/tunch/" . $carat->carat_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
                }
            }
            if($role_delete){
                if($is_delete == 0){
                    $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $carat->carat_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
                }
            }
            $row[] = $action;
            $row[] = number_format($carat->purity, 2, '.', '');
            $row[] = $carat->show_in_xrf;
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

    function item_master($item_id = '') {
        $data = array();
        if (isset($item_id) && !empty($item_id)) {
            if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"edit")) {   
                $item_master_data = $this->crud->get_row_by_id('item_master', array('item_id' => $item_id));
                $item_master_data = $item_master_data[0];
                $item_master_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$item_master_data->created_by));
                if($item_master_data->created_by != $item_master_data->updated_by){
                   $item_master_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$item_master_data->created_by)); 
                }else{
                    $item_master_data->updated_by_name = $item_master_data->created_by_name;
                }
                $item_exist = $this->crud->get_column_value_by_id('item_stock','item_stock_id',array('item_id' => $item_id));
                if(empty($item_exist)){
                    $item_master_data->item_exist = '0';
                } else {
                    $item_master_data->item_exist = '1';
                }
                $data['item_master_data'] = $item_master_data;
                $display_items = explode(',', $item_master_data->display_item_in);
                $data['display_items'] = $display_items;
                
//                echo '<pre>'; print_r($data); exit;
                set_page('master/item_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"add")) {
                set_page('master/item_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_item_master() {
        $return = array();
        $post_data = $this->input->post();
        $post_data['display_item_in'] = isset($post_data['display_item_in']) && !empty($post_data['display_item_in']) ? $post_data['display_item_in'] : null;
        $post_data['metal_payment_receipt'] = isset($post_data['metal_payment_receipt']) && !empty($post_data['metal_payment_receipt']) ? $post_data['metal_payment_receipt'] : null;
//        echo '<pre>';        print_r($post_data); exit;
//        echo '<pre>'; print_r($_FILES['image']); exit;
        if (isset($_FILES['image'])) {
            $out_dir = "uploads/order_item_photo/";
            if (!file_exists($out_dir)) {
                mkdir($out_dir, '0777', true);
            }
            $config['upload_path'] = './uploads/order_item_photo/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '';
            $config['overwrite'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = date('Y_m_d_H_i_s_U');

            $this->load->library('upload', $config);

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
//                echo $error['error'];
            } else {
                $upload_data = $this->upload->data();
                $post_data['image'] = $out_dir . '' . $upload_data['file_name'];
            }
        }
        if (isset($post_data['item_name']) && !empty($post_data['item_name'])) {
            if (isset($post_data['item_id']) && !empty($post_data['item_id'])) {
                $item_name_duplication = $this->crud->get_row_by_id('item_master', array('item_name' => $post_data['item_name'], 'item_id !=' => $post_data['item_id']));
            } else {
                $item_name_duplication = $this->crud->get_row_by_id('item_master', array('item_name' => $post_data['item_name']));
            }
            if (isset($item_name_duplication) && !empty($item_name_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Item Name Already Exist';
                print json_encode($return);
                exit;
            }
        }
        $display_item_in = isset($post_data['display_item_in']) && !empty($post_data['display_item_in']) ? $post_data['display_item_in'] : null;
        if (isset($post_data['item_id']) && !empty($post_data['item_id'])) {
            if (!empty($display_item_in)) {
                $display_item = implode(',', $display_item_in);
                $post_data['display_item_in'] = $display_item;
            }
            if(isset($post_data['metal_payment_receipt'])){
                $post_data['metal_payment_receipt'] = '1';
            } else {
                $post_data['metal_payment_receipt'] = '0';
            }
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['item_id'] = $post_data['item_id'];
            $result = $this->crud->update('item_master', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Item Updated Successfully');
            }
        } else {
            if (!empty($display_item_in)) {
                $display_item = implode(',', $display_item_in);
                $post_data['display_item_in'] = $display_item;
            }
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('item_master', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Item Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function item_master_list() {
        if($this->applib->have_access_role(ITEM_MASTER_MODULE_ID,"view")) {
            set_page('master/item_master_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function item_master_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'item_master im';
        $config['select'] = 'im.*, st.category_name,IF(im.metal_payment_receipt = 1, "Yes" ,"No") AS metal_receipt_payment';
        $config['joins'][] = array('join_table' => 'category st', 'join_by' => 'st.category_id = im.category_id ');
        $config['column_search'] = array('st.category_name', 'im.item_name' ,'im.short_item', 'im.die_no', 'im.design_no', 'im.min_order_qty', 'im.default_wastage', 'im.st_default_wastage', 'IF(im.less = 0,"No","Yes")','IF(im.stock_method = 1,"Default",IF(im.stock_method = 2,"Item Wise","Combine"))','IF(im.metal_payment_receipt = 1, "Yes" ,"No")','im.display_item_in','im.sequence_no','im.rate_on');
        $config['column_order'] = array(null,'st.category_name', 'im.item_name', 'im.short_item', 'im.die_no', 'im.design_no', 'im.min_order_qty', 'im.default_wastage', 'im.st_default_wastage', 'im.less', null, null, null, null ,'im.sequence_no' ,'im.rate_on');
        $config['order'] = array('im.item_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(ITEM_MASTER_MODULE_ID, "edit");
        foreach ($list as $item_master) {
            $display_item_in = explode(",", $item_master->display_item_in);
            foreach ($display_item_in as $item_id) {
                $item = $this->crud->get_column_value_by_id('item_master', 'display_item_in', array('item_id' => $item_id));
                if (!empty($item)) {
                    if (display_item_in == "") {
                        $display_item_in = $item;
                    } else {
                        $display_item_in = $display_item_in . ", " . $item;
                    }
                }
            }
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("master/item_master/" . $item_master->item_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                if(in_array($item_master->item_id, array(GOLD_FINE_ITEM_ID, SILVER_FINE_ITEM_ID))){ } else {
                    $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $item_master->item_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
                }
            }

            $row[] = $action;
            $row[] = $item_master->category_name;
            $row[] = $item_master->item_name;
            $row[] = $item_master->short_item;
            $row[] = $item_master->die_no;
            $row[] = $item_master->design_no;
            $row[] = $item_master->min_order_qty;
            $row[] = $item_master->default_wastage;
            $row[] = $item_master->st_default_wastage;
            if ($item_master->less == 0) {
                $item_master->less = 'No';
            } else {
                $item_master->less = 'Yes';
            }
            $row[] = $item_master->less;

            $row[] = $display_item_in;
            $stock_method = '';
            if ($item_master->stock_method == 1) {
                $stock_method = 'Default';
            } else if ($item_master->stock_method == 2) {
                $stock_method = 'Item Wise';
            } else if ($item_master->stock_method == 3) {
                $stock_method = 'Combine';
            }
            $row[] = $stock_method;
            $row[] = $item_master->metal_receipt_payment;
            if(!empty($item_master->image) && file_exists($item_master->image)) {
                $img_src = base_url(). $item_master->image;
                $row[] = '<a href="javascript:void(0);" class="image_model" data-img_src="' .$img_src.'" ><img src="' . base_url($item_master->image) . '" alt="" height="42" width="42"></a> ';
            } else {
                $row[] = '';
            }
            $row[] = $item_master->sequence_no;
            $row[] = $item_master->rate_on;
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
    
    function user_master($user_master_id = '') {
        $data = array();
        $image_lineitem = new \stdClass();

        $account_group_res = $this->crud->get_all_with_where('account_group','account_group_name','asc',array('account_group_id !=' => DEPARTMENT_GROUP));
        $account_group_ids = array();
        foreach($account_group_res as $account_group_row) { 
            $account_group_ids[] = $account_group_row->account_group_id;
        }
        $data['account_group_res'] = $account_group_res;

        $this->db->select("a.account_id,a.account_name,a.account_group_id");
        $this->db->from("account a");
        $this->db->where("a.account_group_id !=",NOT_APPROVED_ACCOUNT_GROUP_ID);
        $this->db->where_in("a.account_group_id",$account_group_ids);
        $this->db->order_by("a.account_name");
        $query = $this->db->get();
        $account_res = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $account_res[] = array(
                   'id' => $row->account_id,
                   'account_group_id' => $row->account_group_id,
                   'text' => $row->account_id.' - '.$row->account_name,
                );
            }
        }
        $data['account_res'] = $account_res;

        $user_department = $this->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
        $data['user_department'] = $user_department;

        if (isset($user_master_id) && !empty($user_master_id)) {
            if($this->applib->have_access_role(USER_MASTER_MODULE_ID,"edit")) {
                $user_master_data = $this->crud->get_row_by_id('user_master', array('user_id' => $user_master_id));
                $user_master_data = $user_master_data[0];
                $account_data = $this->crud->get_row_by_id('account', array('user_id' => $user_master_id));
                if(isset($account_data) && !empty($account_data)) {
                    $account_data = $account_data[0];
                    $user_master_data->opening_balance_in_rupees = $account_data->opening_balance_in_rupees ? $account_data->opening_balance_in_rupees : 0;
                    $user_master_data->rupees_ob_credit_debit = $account_data->rupees_ob_credit_debit;
                }
                $user_master_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$user_master_data->created_by));
                if($user_master_data->created_by != $user_master_data->updated_by){
                    $user_master_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$user_master_data->created_by));
                }else{
                    $user_master_data->updated_by_name = $user_master_data->created_by_name;  
                }
                $data['user_master_data'] = $user_master_data;
                $department = $this->crud->get_columns_val_by_where('user_department','department_id', array('user_id' => $user_master_id));
                if(!empty($department)){
                    $department = array_column($department, 'department_id');
                    $data['department'] = $department;
                }
                $order_department = $this->crud->get_columns_val_by_where('user_order_department','department_id', array('user_id' => $user_master_id));
                if(!empty($order_department)){
                    $order_department = array_column($order_department, 'department_id');
                    $data['order_department'] = $order_department;
                }
                $user_account_group = $this->crud->get_columns_val_by_where('user_account_group','account_group_id', array('user_id' => $user_master_id));
                if(!empty($user_account_group)){
                    $user_account_group = array_column($user_account_group, 'account_group_id');
                    $data['user_account_group'] = $user_account_group;
                }

                $lineitems = array();
                if(isset($user_master_data->files) && !empty($user_master_data->files)){
                    $image_lineitems = explode(',', $user_master_data->files);
                    foreach($image_lineitems as $image_item){
                        $image_lineitem->image = $image_item;
                        $image_lineitem->user_id = $user_master_id;
                        if($image_item == $user_master_data->default_user_photo){
                            $image_lineitem->default_image = 1;
                        } else {
                            $image_lineitem->default_image = 0;
                        }
                        $lineitems[] = json_encode($image_lineitem);
                    }
                }
                $data['image_lineitems'] = implode(',', $lineitems);
                
                $family_data = $this->crud->get_all_with_where('user_family_member','','',array('user_id' => $user_master_id));
                $family_lineitems = array();
                $family_item_detail = new \stdClass();
                if (!empty($family_data)) {
                    foreach ($family_data as $f_detail) {
                        $family_item_detail->fm_id = $f_detail->fm_id;
                        $family_item_detail->member_name = $f_detail->member_name;
                        $family_item_detail->member_phone_no = $f_detail->member_phone_no;
                        $family_lineitems[] = json_encode($family_item_detail);
                    }
                    $data['member_lineitems'] = implode(',', $family_lineitems);
                }
//                echo '<pre>'; print_r($data); exit;
                set_page('master/user_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(USER_MASTER_MODULE_ID,"add")) {
                set_page('master/user_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_user_master() {
        $return = array();
        $post_data = $this->input->post();
        $post_data['opening_balance_in_rupees'] = $post_data['opening_balance_in_rupees'] ? $post_data['opening_balance_in_rupees'] : 0;
        $line_items_data = array();
        $deleted_lineitems = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
        }
        if(isset($post_data['deleted_lineitems']) && !empty($post_data['deleted_lineitems'])){
            $deleted_lineitems = json_decode($post_data['deleted_lineitems']);
            if(!empty($deleted_lineitems)){
                foreach ($deleted_lineitems as $deleted_item){
                    if (file_exists(PUBPATH."/uploads/worker_images/".$deleted_item)) {
                        unlink(PUBPATH."/uploads/worker_images/".$deleted_item);
                    }
                }
            }
        }
        if(isset($post_data['family_deleted_lineitems']) && !empty($post_data['family_deleted_lineitems'])){
            $deleted_lineitems = json_decode($post_data['family_deleted_lineitems']);
            if(!empty($deleted_lineitems)){
                foreach ($deleted_lineitems as $deleted_item){
                    $this->crud->delete("user_family_member", array('fm_id' => $deleted_item));
                }
            }
        }
//        echo "<pre>"; print_r($deleted_lineitems); exit;
        if (isset($post_data['user_mobile']) && !empty($post_data['user_mobile'])) {
            if (isset($post_data['user_id']) && !empty($post_data['user_id'])) {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_mobile' => $post_data['user_mobile'], 'user_id !=' => $post_data['user_id']));
            } else {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_mobile' => $post_data['user_mobile']));
            }
            if (isset($acc_duplication) && !empty($acc_duplication)) {
                $acc_duplication = $acc_duplication[0];
                $return['error'] = "mobileExist";
                $return['msg'] = $acc_duplication->account_name;
                print json_encode($return);
                exit;
            }
        }
        
        if (isset($post_data['user_name']) && !empty($post_data['user_name'])) {
            if (isset($post_data['user_id']) && !empty($post_data['user_id'])) {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_name' => $post_data['user_name'], 'user_id !=' => $post_data['user_id']));
            } else {
                $acc_duplication = $this->crud->get_row_by_id('account', array('account_name' => $post_data['user_name']));
            }
            if (isset($acc_duplication) && !empty($acc_duplication)) {
                $return['error'] = "accountExist";
                print json_encode($return);
                exit;
            }
        }
        
        if($post_data['user_type'] == '1'){
            $user_type = ADMIN_GROUP;
        } else if($post_data['user_type'] == '2'){
            $user_type = USER_GROUP;
        } else if($post_data['user_type'] == '3'){
            $user_type = WORKER_GROUP;
        } else {
            $user_type = SALESMAN_GROUP;
        }
        $post_data['default_department_id'] = isset($post_data['default_department_id']) && !empty($post_data['default_department_id']) ? $post_data['default_department_id'] : '';
        if (isset($post_data['user_id']) && !empty($post_data['user_id'])) {
            $last_query_id = $post_data['user_id'];
            $worker_data = $this->crud->get_row_by_id('user_master', array('user_id' => $post_data['user_id']));
            if($post_data['old_user_type_id'] != $post_data['user_type']) {
                $user_id = $post_data['user_id'];
            }
            unset($post_data['old_user_type_id']);
            $update_user['default_department_id'] = $post_data['default_department_id'];
            $update_user['user_name'] = $post_data['user_name'];
            $update_user['login_username'] = $post_data['login_username'];
            $update_user['user_type'] = $post_data['user_type'];
            $update_user['user_password'] = $post_data['user_password'];
            $update_user['user_mobile'] = $post_data['user_mobile'];
            $update_user['otp_on_user'] = isset($post_data['otp_on_user']) ? $post_data['otp_on_user'] : NULL;
            $update_user['is_cad_designer'] = isset($post_data['is_cad_designer'])  ? '1' : '0';
            $update_user['order_display_only_assigned_account'] = isset($post_data['order_display_only_assigned_account'])  ? '1' : '0';
            $update_user['salary'] = $post_data['salary'];
            $update_user['blood_group'] = $post_data['blood_group'];
            $update_user['allow_all_accounts'] = $post_data['allow_all_accounts'];
            if($post_data['allow_all_accounts'] == ALLOW_ONLY_SELECTED_ACCOUNTS && !empty($post_data['account_id']) && is_array($post_data['account_id'])){
                $update_user['selected_accounts'] = implode(',',$post_data['account_id']);
            } else {
                $update_user['selected_accounts'] = null;
            }
            $update_user['designation'] = $post_data['designation'];
            $update_user['aadhaar_no'] = $post_data['aadhaar_no'];
            $update_user['pan_no'] = $post_data['pan_no'];
            $update_user['licence_no'] = $post_data['licence_no'];
            $update_user['voter_id_no'] = $post_data['voter_id_no'];
            $update_user['esi_no'] = $post_data['esi_no'];
            $update_user['pf_no'] = $post_data['pf_no'];
            $update_user['date_of_birth'] = !empty($post_data['date_of_birth']) ? date('Y-m-d', strtotime($post_data['date_of_birth'])) : NULL;
            $update_user['bank_name'] = $post_data['bank_name'];
            $update_user['bank_branch'] = $post_data['bank_branch'];
            $update_user['bank_acc_name'] = $post_data['bank_acc_name'];
            $update_user['bank_acc_no'] = $post_data['bank_acc_no'];
            $update_user['bank_ifsc'] = $post_data['bank_ifsc'];
            $update_user['updated_at'] = $this->now_time;
            $update_user['updated_by'] = $this->logged_in_id;
            $where_array['user_id'] = $post_data['user_id'];
            $result = $this->crud->update('user_master', $update_user, $where_array);
            $this->crud->update('user_master', array('default_user_photo' => NULL), $where_array);
            $family_line_items_data = json_decode($post_data['family_line_items_data']);
//            echo "<pre>"; print_r($family_line_items_data); exit;
            if (!empty($family_line_items_data)) {
                foreach ($family_line_items_data as $lineitem) {
                    if(isset($lineitem->fm_id) && !empty($lineitem->fm_id)){
                        $update_item_arr['user_id'] = $post_data['user_id'];
                        $update_item_arr['member_name'] = $lineitem->member_name;
                        $update_item_arr['member_phone_no'] = $lineitem->member_phone_no;
                        $update_item_arr['updated_at'] = $this->now_time;
                        $update_item_arr['updated_by'] = $this->logged_in_id;
                        $where_ar['fm_id'] = $lineitem->fm_id;
                        $this->crud->update('user_family_member', $update_item_arr, $where_ar);
                    } else {
                        $insert_item_arr = array();
                        $insert_item_arr['user_id'] = $post_data['user_id'];
                        $insert_item_arr['member_name'] = $lineitem->member_name;
                        $insert_item_arr['member_phone_no'] = $lineitem->member_phone_no;
                        $insert_item_arr['created_at'] = $this->now_time;
                        $insert_item_arr['created_by'] = $this->logged_in_id;
                        $insert_item_arr['updated_at'] = $this->now_time;
                        $insert_item_arr['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('user_family_member', $insert_item_arr);
                    }
                }
            }
            if (!empty($line_items_data)) {
                $insert_item = array();
                $default_user_photo = NULL;
                foreach ($line_items_data as $lineitem) {
                    $insert_item[] = $lineitem->image;
                    if($lineitem->default_image == 1){
                        $default_user_photo = $lineitem->image;
                    }
                }
                $image_implode = implode(',', $insert_item);
                $this->crud->update('user_master', array('files' => $image_implode, 'default_user_photo' => $default_user_photo), $where_array);
            } 
//            else if(isset($post_data['deleted_user_id']) && !empty($post_data['deleted_user_id'])) {
//                foreach ($post_data['deleted_user_id'] as $key => $val) {
//                    $this->crud->update('user_master', array('files' => NULL, 'default_user_photo' => NULL), array('user_id' => $val));
//                }
//            }
            
            $update_account = array();

            $old_opening_balance_in_rupees = $this->crud->get_column_value_by_id('account', 'opening_balance_in_rupees', array('user_id' => $post_data['user_id']));
            $old_rupees_ob_credit_debit = $this->crud->get_column_value_by_id('account', 'rupees_ob_credit_debit', array('user_id' => $post_data['user_id']));
            $old_amount = $this->crud->get_column_value_by_id('account', 'amount', array('user_id' => $post_data['user_id']));
            $old_amount = $old_amount ? $old_amount : 0;
            $old_opening_balance_in_rupees = $old_opening_balance_in_rupees ? $old_opening_balance_in_rupees : 0;

            $update_account['opening_balance_in_rupees'] = $post_data['opening_balance_in_rupees'];
            $update_account['rupees_ob_credit_debit'] = $post_data['rupees_ob_credit_debit'];

            if ($old_rupees_ob_credit_debit == '1') { 
                $old_amount = $old_amount + $old_opening_balance_in_rupees;
            } else {
                $old_amount = $old_amount - $old_opening_balance_in_rupees;
            }

            if ($post_data['rupees_ob_credit_debit'] == '1') {
                $update_account['amount'] = $old_amount - $post_data['opening_balance_in_rupees'];
            } else {
                $update_account['amount'] = $old_amount + $post_data['opening_balance_in_rupees'];
            }

            $update_account['account_name'] = $post_data['user_name'];
            $update_account['account_mobile'] = $post_data['user_mobile'];
            $update_account['account_group_id'] = $user_type;
            $where_array_account['user_id'] = $post_data['user_id'];
//            echo '<pre>'; print_r($update_account); exit;
            $result = $this->crud->update('account', $update_account, $where_array_account);
            if ($result) {
                $where = array("user_id" => $post_data['user_id']);
                $this->crud->delete("user_department", $where);

                if(!empty($post_data['department_id'])){
                    foreach ($post_data['department_id'] as $department){
                        $insert_depart = array();
                        $insert_depart['user_id'] = $post_data['user_id'];
                        $insert_depart['department_id'] = $department;
                        $insert_depart['created_at'] = $this->now_time;
                        $insert_depart['created_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('user_department', $insert_depart);
                    }
                }

                $where = array("user_id" => $post_data['user_id']);
                $this->crud->delete("user_order_department", $where);
                
                if(!empty($post_data['order_department_id'])){
                    foreach ($post_data['order_department_id'] as $department){
                        $insert_depart = array();
                        $insert_depart['user_id'] = $post_data['user_id'];
                        $insert_depart['department_id'] = $department;
                        $insert_depart['created_at'] = $this->now_time;
                        $insert_depart['created_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('user_order_department', $insert_depart);
                    }
                }

                $where = array("user_id" => $post_data['user_id']);
                $this->crud->delete("user_account_group", $where);
                
                if(!empty($post_data['account_group_id'])){
                    foreach ($post_data['account_group_id'] as $account_group){
                        $insert_depart = array();
                        $insert_depart['user_id'] = $post_data['user_id'];
                        $insert_depart['account_group_id'] = $account_group;
                        $insert_depart['created_at'] = $this->now_time;
                        $insert_depart['created_by'] = $this->logged_in_id;
                        $result = $this->crud->insert('user_account_group', $insert_depart);
                    }
                }

                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'User Updated Successfully');
            }
        } else {
            $insert_user['default_department_id'] = $post_data['default_department_id'];
            $insert_user['user_name'] = $post_data['user_name'];
            $insert_user['login_username'] = $post_data['login_username'];
            $insert_user['user_type'] = $post_data['user_type'];
            $insert_user['user_password'] = $post_data['user_password'];
            $insert_user['user_mobile'] = $post_data['user_mobile'];
            $insert_user['salary'] = $post_data['salary'];
            $insert_user['blood_group'] = $post_data['blood_group'];
            $insert_user['allow_all_accounts'] = $post_data['allow_all_accounts'];
            if($post_data['allow_all_accounts'] == ALLOW_ONLY_SELECTED_ACCOUNTS && !empty($post_data['account_id']) && is_array($post_data['account_id'])){
                $insert_user['selected_accounts'] = implode(',',$post_data['account_id']);
            } else {
                $insert_user['selected_accounts'] = null;
            }
            $insert_user['designation'] = $post_data['designation'];
            $insert_user['aadhaar_no'] = $post_data['aadhaar_no'];
            $insert_user['pan_no'] = $post_data['pan_no'];
            $insert_user['licence_no'] = $post_data['licence_no'];
            $insert_user['voter_id_no'] = $post_data['voter_id_no'];
            $insert_user['esi_no'] = $post_data['esi_no'];
            $insert_user['pf_no'] = $post_data['pf_no'];
            $insert_user['date_of_birth'] = !empty($post_data['date_of_birth']) ? date('Y-m-d', strtotime($post_data['date_of_birth'])) : NULL;
            $insert_user['bank_name'] = $post_data['bank_name'];
            $insert_user['bank_branch'] = $post_data['bank_branch'];
            $insert_user['bank_acc_name'] = $post_data['bank_acc_name'];
            $insert_user['bank_acc_no'] = $post_data['bank_acc_no'];
            $insert_user['bank_ifsc'] = $post_data['bank_ifsc'];
            $insert_user['is_cad_designer'] = isset($post_data['is_cad_designer'])  ? '1' : '0';
            $insert_user['order_display_only_assigned_account'] = isset($post_data['order_display_only_assigned_account'])  ? '1' : '0';
            $insert_user['otp_on_user'] = isset($post_data['otp_on_user']) ? $post_data['otp_on_user'] : NULL;
            if($post_data['user_type'] == USER_TYPE_WORKER || $post_data['user_type'] == USER_TYPE_SALESMAN){
                $insert_user['status'] = 1;
            } else {
                $insert_user['status'] = 0;
            }
            $insert_user['created_at'] = $this->now_time;
            $insert_user['created_by'] = $this->logged_in_id;
            $insert_user['updated_at'] = $this->now_time;
            $insert_user['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('user_master', $insert_user);
            $last_query_id = $this->db->insert_id();
            $family_line_items_data = json_decode($post_data['family_line_items_data']);
            if (!empty($family_line_items_data)) {
                foreach ($family_line_items_data as $lineitem) {
                    $insert_item_arr = array();
                    $insert_item_arr['user_id'] = $last_query_id;
                    $insert_item_arr['member_name'] = $lineitem->member_name;
                    $insert_item_arr['member_phone_no'] = $lineitem->member_phone_no;
                    $insert_item_arr['created_at'] = $this->now_time;
                    $insert_item_arr['created_by'] = $this->logged_in_id;
                    $insert_item_arr['updated_at'] = $this->now_time;
                    $insert_item_arr['updated_by'] = $this->logged_in_id;
                    $this->crud->insert('user_family_member', $insert_item_arr);
                }
            }
            if (!empty($line_items_data)) {
                $insert_item = array();
                $default_user_photo = NULL;
                foreach ($line_items_data as $lineitem) {
                    $insert_item[] = $lineitem->image;
                    if($lineitem->default_image == 1){
                        $default_user_photo = $lineitem->image;
                    }
                }
//              
                $image_implode = implode(',', $insert_item);
                $where_array['user_id'] = $last_query_id;
                $this->crud->update('user_master', array('files' => $image_implode, 'default_user_photo' => $default_user_photo), $where_array);
            }
            $insert_account = array();
            $zero_value = 0;
            $insert_account['account_name'] = $post_data['user_name'];
            $insert_account['account_mobile'] = $post_data['user_mobile'];
            $insert_account['account_group_id'] = $user_type;
            $insert_account['user_id'] = $last_query_id;
            $insert_account['opening_balance_in_rupees'] = $post_data['opening_balance_in_rupees'];
            $insert_account['rupees_ob_credit_debit'] = $post_data['rupees_ob_credit_debit'];
            if ($post_data['rupees_ob_credit_debit'] == '1') {
                $insert_account['amount'] = $zero_value - $post_data['opening_balance_in_rupees'];
            } else {
                $insert_account['amount'] = $post_data['opening_balance_in_rupees'];
            }
            $insert_account['created_at'] = $this->now_time;
            $insert_account['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('account', $insert_account);
            
            if(!empty($post_data['department_id'])){
                foreach ($post_data['department_id'] as $department){
                    $insert_depart = array();
                    $insert_depart['user_id'] = $last_query_id;
                    $insert_depart['department_id'] = $department;
                    $insert_depart['created_at'] = $this->now_time;
                    $insert_depart['created_by'] = $this->logged_in_id;
                    $result = $this->crud->insert('user_department', $insert_depart);
                }
            }
            
            if(!empty($post_data['order_department_id'])){
                foreach ($post_data['order_department_id'] as $department){
                    $insert_depart = array();
                    $insert_depart['user_id'] = $last_query_id;
                    $insert_depart['department_id'] = $department;
                    $insert_depart['created_at'] = $this->now_time;
                    $insert_depart['created_by'] = $this->logged_in_id;
                    $result = $this->crud->insert('user_order_department', $insert_depart);
                }
            }

            if(!empty($post_data['account_group_id'])){
                foreach ($post_data['account_group_id'] as $account_group){
                    $insert_depart = array();
                    $insert_depart['user_id'] = $last_query_id;
                    $insert_depart['account_group_id'] = $account_group;
                    $insert_depart['created_at'] = $this->now_time;
                    $insert_depart['created_by'] = $this->logged_in_id;
                    $result = $this->crud->insert('user_account_group', $insert_depart);
                }
            }

            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'User Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function user_master_list() {
        if($this->applib->have_access_role(USER_MASTER_MODULE_ID,"view")) {
            set_page('master/user_master_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function user_master_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'user_master um';
        $config['select'] = "um.*, ut.user_type,pm.account_name AS process_name,GROUP_CONCAT(DISTINCT ord_pu.account_name SEPARATOR ' , ') AS order_department,GROUP_CONCAT(DISTINCT pu.account_name SEPARATOR ' , ') AS department, a_u.account_id as user_no,a_u.amount as balance";
        
        $config['joins'][] = array('join_table' => 'account pm', 'join_by' => 'pm.account_id = um.default_department_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'user_department ud', 'join_by' => 'ud.user_id = um.user_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account pu', 'join_by' => 'pu.account_id = ud.department_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'user_order_department uod', 'join_by' => 'uod.user_id = um.user_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account ord_pu', 'join_by' => 'ord_pu.account_id = uod.department_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a_u', 'join_by' => 'a_u.user_id = um.user_id','join_type' => 'left');
        $config['joins'][] = array('join_table' => 'user_type ut', 'join_by' => 'ut.user_type_id = um.user_type','join_type' => 'left');
        $config['column_search'] = array('ut.user_type', 'a_u.account_id', 'um.user_name', 'um.user_mobile',  'pm.account_name', 'pu.account_name', 'um.user_password', 'um.salary', 'a_u.amount');
        $config['column_order'] = array(null, 'ut.user_type', 'a_u.account_id', 'um.user_name', 'um.user_mobile',  'pm.account_name', 'pu.account_name', 'order_department', 'um.user_password', 'um.salary', 'a_u.amount', null);
        
        if(isset($post_data['user_type']) && $post_data['user_type'] != ''){
            $config['wheres'][] = array('column_name' => 'um.is_login', 'column_value' => $post_data['user_type']);
        }
        if(isset($post_data['default_department_id']) && $post_data['default_department_id'] != ''){
            $config['wheres'][] = array('column_name' => 'um.default_department_id', 'column_value' => $post_data['default_department_id']);
        }

        $config['group_by'] = 'um.user_id';
        $config['order'] = array('um.user_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();

        $data = array();
        $role_delete = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "edit");
        $role_logout = $this->app_model->have_access_role(USER_MASTER_MODULE_ID, "allow logout option");
        foreach ($list as $user_master) {
            $row = array();
            $action = '';
//            if($user_master->user_type != 'Worker' && $user_master->user_type != 'Salesman'){
                if($role_edit){
                    $action .= '<a href="' . base_url("master/user_master/" . $user_master->user_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4; display: inherit;" >&nbsp;</span></a>';
                }
//            }
            if($role_delete){
                if($user_master->user_id != ADMINISTRATOR_USER_ID){
                    $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_users/' . $user_master->user_id) . '"><span class="glyphicon glyphicon-trash" style="color : red; display: inherit;"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;';
                }
            }
            if($this->user_type == USER_TYPE_ADMIN){
                if($user_master->status == 0) {
                    $action .= '<a href="javascript:void(0);" class="status_btn" data-status="1" data-href="'. base_url('master/active_inactive_userstatus/'.$user_master->user_id).'"><span class="glyphicon glyphicon-ok-circle" style="color : green">&nbsp;</span></a>&nbsp;';
                } else {
                    $action .= '<a href="javascript:void(0);" class="status_btn" data-status="0" data-href="'. base_url('master/active_inactive_userstatus/'.$user_master->user_id).'"><span class="glyphicon glyphicon-remove-circle" style="color : red">&nbsp;</span></a>&nbsp;';
                }
            }
            if($role_logout && $user_master->is_login == 1) {
                $action .= '<a href="javascript:void(0);" class="is_login" data-user_id="'. $user_master->user_id .'"><i class="fa fa-power-off"></i></a>';
            }
            $row[] = $action;
            $row[] = $user_master->user_type;
            $row[] = $user_master->user_no;
            $row[] = $user_master->user_name;
            $row[] = $user_master->user_mobile;
            $row[] = $user_master->process_name;
            $row[] = $user_master->department;
            $row[] = $user_master->order_department;
            $row[] = $user_master->user_password;
            $row[] = $user_master->salary;
            $row[] = $user_master->balance;
            $image_popup = '';
            if(!empty($user_master->files)){
                $files = explode(",",$user_master->files);
                foreach ($files as $file){
                    $image_popup .= '<a href="javascript:void(0)" class="btn btn-xs btn-primary image_model" data-img_src="'.base_url().'uploads/worker_images/'.$file.'" ><i class="fa fa-image"></i></a> &nbsp;';
                }
            }
            $row[] = $image_popup;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),//$this->datatable->count_all(),
            "recordsFiltered" => $this->datatable->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function delete_users($id=''){
        $return = array();
        $result = $this->crud->delete('account', array('user_id' => $id));
        if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
            $worker_data = $this->crud->get_row_by_id('user_master', array('user_id' => $id));
            if(!empty($worker_data[0]->files)){
                $files = explode(",",$worker_data[0]->files);
                foreach ($files as $file){
                    if (file_exists(PUBPATH."/uploads/worker_images/".$file)) {
                        unlink(PUBPATH."/uploads/worker_images/".$file);
                    }
                }
            }
            $this->crud->delete('user_department', array('user_id' => $id));
            $result = $this->crud->delete('user_master', array('user_id' => $id));
            if ($result) {
                $return['success'] = "Deleted";
            } else {
                $return['error'] = "Error";
            }
        }
        print json_encode($return);
        exit;
    }

    function unlink_user_image(){
        if(!empty($_POST['image'])){
            $file = $_POST['image'];
            unlink(PUBPATH."/uploads/worker_images/".$file);
        }
    }

    function delete_row($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $this->crud->delete($table, array($id_name => $id));
    }

    function process_master($process_id = '') {
        $data = array();
        if (isset($process_id) && !empty($process_id)) {
            if($this->applib->have_access_role(DEPARTMENT_MODULE_ID,"edit") || $this->applib->have_access_role(DEPARTMENT_MODULE_ID,'view')) {
                $process_master_data = $this->crud->get_row_by_id('process_master', array('process_id' => $process_id));
                $process_master_data = $process_master_data[0];
                $data['process_master_data'] = $process_master_data;
                set_page('master/process_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(DEPARTMENT_MODULE_ID,"add") || $this->applib->have_access_role(DEPARTMENT_MODULE_ID,'view')) {
                set_page('master/process_master', $data);
            }
        }
    }

    function save_process_master() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['process_name']) && !empty($post_data['process_name'])) {
            if (isset($post_data['process_id']) && !empty($post_data['process_id'])) {
                $process_name_duplication = $this->crud->get_row_by_id('process_master', array('process_name' => $post_data['process_name'], 'process_id !=' => $post_data['process_id']));
            } else {
                $process_name_duplication = $this->crud->get_row_by_id('process_master', array('process_name' => $post_data['process_name']));
            }
            if (isset($process_name_duplication) && !empty($process_name_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Process Name Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['process_id']) && !empty($post_data['process_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['process_id'] = $post_data['process_id'];
            $result = $this->crud->update('process_master', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Process Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('process_master', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function process_master_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'process_master pm';
        $config['select'] = 'pm.*';
        $config['column_search'] = array('pm.process_name');
        $config['column_order'] = array(null, 'pm.process_name');
        $config['order'] = array('pm.process_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(DEPARTMENT_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(DEPARTMENT_MODULE_ID, "edit");
        foreach ($list as $process_master) {
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("master/process_master/" . $process_master->process_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $process_master->process_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $process_master->process_name;
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

    function party_item_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'party_item_details si';
        $config['select'] = 'si.*,im.item_name,st.category_name';
        $config['joins'][] = array('join_table' => 'item_master im', 'join_by' => 'im.item_id = si.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'category st', 'join_by' => 'st.category_id= si.category_id', 'join_type' => 'left');
        $config['column_order'] = array('st.category_name', 'im.item_name', 'si.wstg');
        $config['column_search'] = array('st.category_name', 'im.item_name', 'si.wstg');
        $config['order'] = array('si.party_item_id' => 'desc');
        if (isset($post_data['party_id']) && !empty($post_data['party_id'])) {
            $config['wheres'][] = array('column_name' => 'si.party_id', 'column_value' => $post_data['party_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
//            echo $this->db->last_query();
        $data = array();
        foreach ($list as $sell_detail) {
            $row = array();
            $row[] = $sell_detail->category_name;
            $row[] = $sell_detail->item_name;
            $row[] = number_format($sell_detail->wstg, 3, '.', '');
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

    function state($state_id = '') {
        $data = array();
        if (isset($state_id) && !empty($state_id)) {
            if($this->applib->have_access_role(STATE_MODULE_ID,"edit") || $this->applib->have_access_role(STATE_MODULE_ID,'view')) {
                $state_data = $this->crud->get_row_by_id('state', array('state_id' => $state_id));
                $state_data = $state_data[0];
                $state_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $state_data->created_by));
                if($state_data->created_by != $state_data->updated_by){
                    $state_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $state_data->updated_by));
                } else {
                    $state_data->updated_by_name = $state_data->created_by_name;
                }
                $data['state_data'] = $state_data;
                set_page('master/state', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            } 
        } else {
            if($this->applib->have_access_role(STATE_MODULE_ID,"add") || $this->applib->have_access_role(STATE_MODULE_ID,'view')) {
                set_page('master/state', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_state() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['state_name']) && !empty($post_data['state_name'])) {
            if (isset($post_data['state_id']) && !empty($post_data['state_id'])) {
               $size_duplication = $this->crud->get_row_by_id('state', array('state_name' => $post_data['state_name'], 'state_id !=' => $post_data['state_id']));
            } else {
                $size_duplication = $this->crud->get_row_by_id('state', array('state_name' => $post_data['state_name']));
            }
            if (isset($size_duplication) && !empty($size_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'State Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['state_id']) && !empty($post_data['state_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['state_id'] = $post_data['state_id'];
            $result = $this->crud->update('state', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'State Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('state', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function state_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'state s';
        $config['select'] = 's.*';
        $config['column_search'] = array('s.state_name');
        $config['column_order'] = array(null, 's.state_name');
        $config['order'] = array('s.state_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        foreach ($list as $state) {
            $row = array();
            $action = '';
            $role_delete = $this->app_model->have_access_role(STATE_MODULE_ID, "delete");
            $role_edit = $this->app_model->have_access_role(STATE_MODULE_ID, "edit");
            if($role_edit){
                $action .= '<a href="' . base_url("master/state/" . $state->state_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $state->state_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $state->state_name;

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
    
    function city($city_id = '') {
        $data = array();
        if (isset($city_id) && !empty($city_id)) {
            if($this->applib->have_access_role(CITY_MODULE_ID,"edit") || $this->applib->have_access_role(CITY_MODULE_ID,'view')) {
                $city_data = $this->crud->get_row_by_id('city', array('city_id' => $city_id));
                $city_data = $city_data[0];
                $city_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $city_data->created_by));
                if($city_data->created_by != $city_data->updated_by){
                    $city_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $city_data->updated_by));
                } else {
                    $city_data->updated_by_name = $city_data->created_by_name;
                }
                $data['city_data'] = $city_data;
                set_page('master/city', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(CITY_MODULE_ID,"add") || $this->applib->have_access_role(CITY_MODULE_ID,'view')) {
                set_page('master/city', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            } 
        }
    }

    function save_city() {
        $return = array();
        $post_data = $this->input->post();
        if(isset($post_data['city_name']) && !empty($post_data['city_name'])) {
            if(isset($post_data['city_id']) && !empty($post_data['city_id'])){
                $city_type_duplication = $this->crud->get_row_by_id('city' , array('city_name' => $post_data['city_name'], 'state_id' => $post_data['state_id'], 'city_id !=' => $post_data['city_id']));
            } else {
                $city_type_duplication = $this->crud->get_row_by_id('city' , array('city_name' => $post_data['city_name'], 'state_id' => $post_data['state_id']));
            }
            if(isset($city_type_duplication) && !empty($city_type_duplication)){
                $return['error'] = "Exist";
                $return['error_exist'] = 'city Type Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['city_id']) && !empty($post_data['city_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['city_id'] = $post_data['city_id'];
            $result = $this->crud->update('city', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'City Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('city', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'City Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function city_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'city c';
        $config['select'] = 'c.*, s.state_name';
        $config['joins'][] = array('join_table' => 'state s', 'join_by' => 's.state_id = c.state_id', 'join_type' => 'left');
        $config['column_search'] = array('s.state_name', 'c.city_name');
        $config['column_order'] = array(null, 's.state_name', 'c.city_name');
        $config['order'] = array('c.city_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        $role_delete = $this->app_model->have_access_role(CITY_MODULE_ID, "delete");
		$role_edit = $this->app_model->have_access_role(CITY_MODULE_ID, "edit");
        foreach ($list as $city) {
             $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("master/city/" . $city->city_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_row/' . $city->city_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }

            $row[] = $action;
            $row[] = $city->state_name;
            $row[] = $city->city_name;
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

    function stamp($stamp_id = '') {
        $data = array();
        if (isset($stamp_id) && !empty($stamp_id)) {
            if ($this->applib->have_access_role(STAMP_MODULE_ID, "edit") || $this->applib->have_access_role(STAMP_MODULE_ID, "view")) {
                $stamp_data = $this->crud->get_row_by_id('stamp', array('stamp_id' => $stamp_id));
                $stamp_data = $stamp_data[0];
                $stamp_data->created_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $stamp_data->created_by));
                if ($stamp_data->created_at != $stamp_data->updated_at) {
                    $stamp_data->updated_by_name = $this->crud->get_column_value_by_id('user_master', 'user_name', array('user_id' => $stamp_data->updated_by));
                } else {
                    $stamp_data->updated_by_name = $stamp_data->created_by_name;
                }
                $data['stamp_data'] = $stamp_data;
                set_page('master/stamp', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(STAMP_MODULE_ID, "add") || $this->applib->have_access_role(STAMP_MODULE_ID, "view")) {
                set_page('master/stamp', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_stamp() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['stamp_name']) && !empty($post_data['stamp_name'])) {
            if (isset($post_data['stamp_id']) && !empty($post_data['stamp_id'])) {
                $stamp_duplication = $this->crud->get_row_by_id('stamp', array('stamp_name' => $post_data['stamp_name'], 'stamp_id !=' => $post_data['stamp_id']));
            } else {
                $stamp_duplication = $this->crud->get_row_by_id('stamp', array('stamp_name' => $post_data['stamp_name']));
            }
            if (isset($stamp_duplication) && !empty($stamp_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Stamp Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['stamp_id']) && !empty($post_data['stamp_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['stamp_id'] = $post_data['stamp_id'];
            $result = $this->crud->update('stamp', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Stamp Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('stamp', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }

    function stamp_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'stamp s';
        $config['select'] = 's.*';
        $config['column_search'] = array('s.stamp_name');
        $config['column_order'] = array(null, 's.stamp_name');
        $config['order'] = array('s.stamp_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(STAMP_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(STAMP_MODULE_ID, "edit");
        foreach ($list as $stamp) {
            $row = array();
            $action = '';
            if ($role_edit) {
                $action .= '<a href="' . base_url("master/stamp/" . $stamp->stamp_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if ($role_delete) {
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('master/delete_plan/' . $stamp->stamp_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $row[] = $action;
            $row[] = $stamp->stamp_name;
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

    function slider($order_no = '') {
        $data = array();
        if($this->applib->have_access_role(ORDER_SLIDER_MODULE_ID,"view")) {
//            if($this->user_type == USER_TYPE_USER){
//                $where_clause = "AND no.process_id = ". $this->department_id."";
//            } else {
//                $where_clause = '';
//            }

            $custom_where = '';
            $account_groups = $this->applib->current_user_account_group_ids();
            if(!empty($account_groups)) {
                $custom_where .= ' AND a.account_group_id IN ('.implode(',',$account_groups).')';
            } else {
                $custom_where .= ' AND a.account_group_id IN(-1)';
            }

            $account_ids = $this->applib->current_user_account_ids();
            if($account_ids == "allow_all_accounts") {
                
            } elseif(!empty($account_ids)) {
                $custom_where .= ' AND a.account_id IN ('.implode(',',$account_ids).')';
            } else {
                $custom_where .= ' AND a.account_id IN(-1)';
            }
        
            $department_ids = $this->applib->current_user_order_department_ids();
            if(!empty($department_ids)){
                $department_ids = implode(',', $department_ids);
                $custom_where .= ' AND no.process_id IN('.$department_ids.')';
            } else {
                $custom_where .= ' AND no.process_id IN(-1)';
            }

            if(isset($order_no) && !empty($order_no)){
                $sql = "
                    SELECT no.order_id, no.order_no, no.delivery_date, im.design_no, im.die_no, im.image as item_image, c.purity, oi.weight, oi.pcs, oi.size, oi.length, oi.hook_style as hook, no.remark, oi.image as order_image
                    FROM new_order no
                    LEFT JOIN account a ON a.account_id = no.party_id
                    LEFT JOIN order_lot_item oi ON oi.order_id = no.order_id
                    LEFT JOIN item_master im ON im.item_id = oi.item_id
                    LEFT JOIN carat c ON c.carat_id = oi.touch_id
                    WHERE oi.item_status_id = 1 AND no.order_status_id = '" . PENDING_STATUS . "' AND no.order_no = ".$order_no." ".$custom_where."
                    ORDER BY  no.order_id ASC;
                ";
                $data['order_no'] = $order_no;
            } else {
                $sql = "
                    SELECT no.order_id, no.order_no, no.delivery_date, im.design_no, im.die_no, im.image as item_image, c.purity, oi.weight, oi.pcs, oi.size, oi.length, oi.hook_style as hook, no.remark, oi.image as order_image
                    FROM new_order no
                    LEFT JOIN account a ON a.account_id = no.party_id
                    LEFT JOIN order_lot_item oi ON oi.order_id = no.order_id
                    LEFT JOIN item_master im ON im.item_id = oi.item_id
                    LEFT JOIN carat c ON c.carat_id = oi.touch_id
                    WHERE oi.item_status_id = 1 AND no.order_status_id = '" . PENDING_STATUS . "' ".$custom_where."
                    ORDER BY  no.order_id ASC;
                ";
            }
            $data['orders'] = $this->crud->getFromSQL($sql);
            set_page('master/slider', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function user_rights(){
        if ($this->app_model->have_access_role(USER_RIGHTS_MODULE_ID, "allow")) {
            $data = array();
            $data['users'] = $this->crud->getFromSQL("SELECT * FROM `user_master` WHERE `user_type` != 3 AND `status` = 0 ORDER BY `user_name` ASC ");
            $role_type_id = isset($_GET['user_type']) ? $_GET['user_type']:0;
            $data['user_type_id'] = $role_type_id;
            $data['modules_roles'] = $this->app_model->getModuleRoles();
            $data['user_roles'] = $this->app_model->getUserRoleIDS($role_type_id);
//            echo '<pre>'; print_r($data['user_roles']);
//            echo '<pre>'; print_r($data['modules_roles']); exit;
            set_page('master/user_rights', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function update_roles(){
//        echo "<pre>"; print_r($this->input->post()); exit;
        $status = 1;
        $msg = "Roles has been updated successfully.";
        $user_id = $this->input->post("user_type");

        if(intval($user_id) > 0){
            $roles = $this->input->post("roles");
            
            $sql = "DELETE FROM user_roles WHERE user_id='$user_id'";
            $this->crud->execuetSQL($sql);
//            echo '<pre>'; print_r($roles); exit;
            // add new roles
            $dataToInsert = array();
            if(is_array($roles) && count($roles) > 0){
                if(!empty($user_id)){
                    
                    if (is_array($roles) && count($roles) > 0) {
                        $user_role_data = array();
                        foreach ($roles as $module_id => $role_id) {
                            $tmp = explode("_", $module_id);
                            $module_id = $tmp[1];
                            $data = array(
                                'user_id' => $user_id,
                                'website_module_id' => $module_id,
                                'role_type_id' => $role_id,
                            );
                            array_push($user_role_data, $data);
                        }
//                        echo "<pre>"; print_r($user_role_data); exit;
                        $this->db->insert_batch('user_roles', $user_role_data);
//                        echo $this->db->last_query(); exit;
                        $sql = "
                            SELECT
                                    ur.user_id,ur.website_module_id,ur.role_type_id, LOWER(r.title) as role, LOWER(m.title) as module
                            FROM user_roles ur
                            INNER JOIN website_modules m ON ur.website_module_id = m.website_module_id
                            INNER JOIN module_roles r ON ur.role_type_id = r.module_role_id WHERE ur.user_id = $user_id;
                        ";
                        $results = $this->crud->getFromSQL($sql);

                        $roles = array();
                        foreach ($results as $row) {
                            $roles[$row->website_module_id][] = $row->role;
                        }
                        $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'user_roles', $roles);
                    }
                }
            }
        }else{
            $status = 0;
            $msg = "Please Select User.";
        }

        echo json_encode(array("status" => $status,"msg" => $msg));
        exit;
    }

    function active_inactive_userstatus($user_id= '') {
        $update_data = array();
        $update_data['status'] = $_POST['status'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('user_master', $update_data, array('user_id' => $user_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }
    
    function get_temp_path_image() {
        $data = '';
//        echo "<pre>"; print_r($_FILES); exit;
        if (isset($_FILES['file_upload']['name']) && !empty($_FILES['file_upload']['name'])) {
            $file_element_name = 'file_upload';
            $config['upload_path'] = './uploads/worker_images/';
            $config['allowed_types'] = '*';
            $config['overwrite'] = TRUE;
            $config['encrypt_name'] = FALSE;
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = date('Y_m_d_H_i_s_U');
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file_element_name)) {
                $return['Uploaderror'] = $this->upload->display_errors();
            }
            $file_data = $this->upload->data();
            @unlink($_FILES[$file_element_name]);
            $data = $file_data['file_name'];
        }
        
        print $data;
        exit;
    }
    
    function upload_domestic_item_file($item_id = 0){
        if ($item_id != 0) {
            $config['upload_path'] = '.uploads/worker_images';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('item_file')) {
                    return false;
            }
            $data = $this->upload->data();
            $this->db->insert('item_files',array('item_id'=>$item_id,'file_url'=>'.uploads/worker_images'.$data['file_name'],'item_type'=>'Domestic'));
            $file_id = $this->db->insert_id();
            $file_data['files'][] = array(
                    'name' => $data['file_name'],
                    'size' => $data['file_size'] * 1024,
                    'type' => $data['file_type'],
                    'url' => base_url().'uploads/'.$data['file_name'],
                    'deleteType' => 'POST',
                    'deleteUrl' => base_url().'item/remove_item_file/'.$file_id,
            );
            echo json_encode($file_data);
            exit();
        }
    }
    
    function remove_item_file($file_id){
        $file_url = $this->crud->get_all_with_where('item_files','','',array('id' => $file_id));
        $file_url = BASEPATH."../".ltrim($file_url[0]->file_url, './');
        if(file_exists($file_url)){
            unlink($file_url);
        }
        $this->db->where('id',$file_id);
        $this->db->delete('item_files');
        echo json_encode(array('success'=>true,'message'=>"File removed successfully!"));
    }

    function get_item_less_info(){
        $item_id = $this->input->post('item_id');
        $less = $this->crud->get_val_by_id('item_master', $item_id, 'item_id', 'less');
        echo json_encode(array('less'=>$less));
    }
    
    function get_category_group($category_id){
        $data = array();
        $category_group_id = $this->crud->get_column_value_by_id('category','category_group_id',array('category_id' => $category_id));
        if(!empty($category_group_id)){
            $data['category_group_id'] = $category_group_id;
        }
        echo json_encode($data);
        exit;
    }
}
