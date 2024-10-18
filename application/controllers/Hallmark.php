<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hallmark extends CI_Controller {

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
        $this->zero_value = 0;
    }

    function item_master($item_id = '') {
        $data = array();
        if (isset($item_id) && !empty($item_id)) {
            if($this->applib->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID,"edit") || $this->applib->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID,"view")) {
                $item_data = $this->crud->get_row_by_id('hallmark_item_master', array('item_id' => $item_id));
                $item_data = $item_data[0];
                $item_data->created_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$item_data->created_by));
                if($item_data->created_by != $item_data->updated_by){
                    $item_data->updated_by_name= $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' => $item_data->updated_by));
                }else{
                    $item_data->updated_by_name = $item_data->created_by_name;
                }
                $data['item_data'] = $item_data;
                set_page('hallmark/hallmark_item_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if($this->applib->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID,"add") || $this->applib->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID,"view")) {
                set_page('hallmark/hallmark_item_master', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_item_master() {
        $return = array();
        $post_data = $this->input->post();
        if (isset($post_data['item_name']) && !empty($post_data['item_name'])) {
            if (isset($post_data['item_id']) && !empty($post_data['item_id'])) {
                $size_duplication = $this->crud->get_row_by_id('hallmark_item_master', array('item_name' => $post_data['item_name'], 'item_id !=' => $post_data['item_id']));
            } else {
                $size_duplication = $this->crud->get_row_by_id('hallmark_item_master', array('item_name' => $post_data['item_name']));
            }
            if (isset($size_duplication) && !empty($size_duplication)) {
                $return['error'] = "Exist";
                $return['error_exist'] = 'Item Is Already Exist';
                print json_encode($return);
                exit;
            }
        }
        if (isset($post_data['item_id']) && !empty($post_data['item_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['item_id'] = $post_data['item_id'];
            $result = $this->crud->update('hallmark_item_master', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Item Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('hallmark_item_master', $post_data);
            if ($result) {
                $return['success'] = "Added";
            }
        }
        print json_encode($return);
        exit;
    }
    
    function item_master_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hallmark_item_master st';
        $config['select'] = 'st.*';
        $config['column_search'] = array('st.item_name');
        $config['column_order'] = array(null, 'st.item_name');
        $config['order'] = array('st.item_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        $role_delete = $this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(HALLMARK_ITEM_MASTER_MODULE_ID, "edit");
        foreach ($list as $item_master) {
            $row = array();
            $action = '';
            if($role_edit) {
                $action .= '<a href="' . base_url("hallmark/item_master/" . $item_master->item_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('hallmark/delete_item_master/' . $item_master->item_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }

            $row[] = $action;
            $row[] = $item_master->item_name;
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
    
    function delete_item_master($id=''){
        $return = array();
        $result = $this->crud->delete('hallmark_item_master', array('item_id' => $id));
        if(isset($result['error'])){
            $return['error'] = "Error";
        } else {
            $return['success'] = "Deleted";
        }
        echo json_encode($return);
        
    }
    
    function receipt($receipt_id = '') {
        $data = array();
        if ($this->applib->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "add") || $this->applib->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "edit")) {
            if(!empty($receipt_id)){
                $data['receipt_data'] = '';
            }
            set_page('hallmark/receipt', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
        
    }
    
    function save_receipt() {
        $return = array();
        $post_data = $this->input->post();
        $line_items_data = json_decode($post_data['line_items_data']);
//        echo '<pre>'; print_r($post_data); exit;
        //echo '<pre>'; print_r($line_items_data); exit;
        if (isset($post_data['receipt_id']) && !empty($post_data['receipt_id'])) {
            
        } else {
            $insert_arr = array();
            $insert_arr['receipt_date'] = (isset($post_data['receipt_date']) && !empty($post_data['receipt_date'])) ? date('Y-m-d', strtotime($post_data['receipt_date'])) : NULL;
            $insert_arr['delivery_date'] = (isset($post_data['delivery_date']) && !empty($post_data['delivery_date'])) ? date('Y-m-d', strtotime($post_data['delivery_date'])) : NULL;
            $insert_arr['receipt_time'] = (isset($post_data['receipt_time']) && !empty($post_data['receipt_time'])) ? date('H:i',strtotime($post_data['receipt_time'])) : NULL;
            $insert_arr['delivery_time'] = (isset($post_data['delivery_time']) && !empty($post_data['delivery_time'])) ? date('H:i',strtotime($post_data['delivery_time'])) : NULL;
            $insert_arr['metal_id'] = $post_data['metal_id'];
            $insert_arr['party_id'] = $post_data['party_id'];
            $insert_arr['created_at'] = $this->now_time;
            $insert_arr['created_by'] = $this->logged_in_id;
            $insert_arr['updated_at'] = $this->now_time;
            $insert_arr['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('hallmark_receipt', $insert_arr);
            $receipt_id = $this->db->insert_id();
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Receipt Entry Added Successfully');
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $lineitem) {
                        $insert_item = array();
                        $insert_item['receipt_id'] = $receipt_id;
                        $insert_item['article_id'] = $lineitem->article_id;
                        $insert_item['receipt_weight'] = $lineitem->receipt_weight;
                        $insert_item['purity'] = $lineitem->purity;
                        $insert_item['box_no'] = $lineitem->box_no;
                        $insert_item['pcs'] = $lineitem->pcs;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('hallmark_receipt_details', $insert_item);
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }
    
    function receipt_list() {
        $data = array();
        if ($this->applib->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "view")) {
            set_page('hallmark/receipt_list', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function receipt_datatable() {
        $post_data = $this->input->post();
        if (!empty($post_data['from_date'])) {
            $from_date = date('Y-m-d', strtotime($post_data['from_date']));
        }
        if (!empty($post_data['to_date'])) {
            $to_date = date('Y-m-d', strtotime($post_data['to_date']));
        }
        $config['table'] = 'hallmark_receipt r';
        $config['select'] = 'r.*,a.account_name,IF(r.metal_id = 1,"Gold", "Silver") as metal';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = r.party_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'DATE_FORMAT(r.receipt_date,"%d-%m-%Y")', 'DATE_FORMAT(r.receipt_time,"%h:%a%A")', 'IF(r.metal_id = 1,"Gold", "Silver")', 'DATE_FORMAT(r.delivery_date,"%d-%m-%Y")', 'DATE_FORMAT(r.delivery_time,"%h:%a%A")');
        $config['column_order'] = array(null, null,'r.receipt_id','a.account_name', 'DATE_FORMAT(r.receipt_date,"%d-%m-%Y")', 'DATE_FORMAT(r.receipt_time,"%h:%a%A")', 'IF(r.metal_id = 1,"Gold", "Silver")', 'DATE_FORMAT(r.delivery_date,"%d-%m-%Y")', 'DATE_FORMAT(r.delivery_time,"%h:%a%A")');
        if (!empty($post_data['party_id'])) {
            $config['wheres'][] = array('column_name' => 'r.party_id', 'column_value' => $post_data['party_id']);
        }
        if ($post_data['everything_from_start'] != 'true'){
            if (!empty($post_data['from_date'])) {
                $config['wheres'][] = array('column_name' => 'r.receipt_date >=', 'column_value' => $from_date);
            }
        }
        if (!empty($post_data['to_date'])) {
            $config['wheres'][] = array('column_name' => 'r.receipt_date <=', 'column_value' => $to_date);
        }
        $config['order'] = array('r.receipt_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(HALLMARK_RECEIPT_MODULE_ID, "edit");

        foreach ($list as $receipt) {
            $row = array();
            $action = '';
            if ($role_edit) {
//                $action .= '<a href="' . base_url("hallmark/receipt/" . $receipt->receipt_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if ($role_delete) {
                $action .= '<a href="javascript:void(0);" class="delete_receipt" data-href="' . base_url('hallmark/delete_receipt/' . $receipt->receipt_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            $action .= '<a href="javascript:void(0);" class="item_row" data-receipt_id="' . $receipt->receipt_id . '" ><span class="glyphicon glyphicon-eye-open" style="color : #419bf4">&nbsp;</span></a>';
            $row[] = $action;
            $row[] = '<span class="text-center"><input type="checkbox" id="" class="icheckbox_flat-blue" autocomplete="off"></span>';
            $row[] = $receipt->receipt_id;
            $row[] = $receipt->account_name;
            $row[] = (!empty(strtotime($receipt->receipt_date))) ? date('d-m-Y', strtotime($receipt->receipt_date)) : '';
            $row[] = (!empty(strtotime($receipt->receipt_time))) ? date('h:i A', strtotime($receipt->receipt_time)) : '';
            $row[] = $receipt->metal;
            $row[] = (!empty(strtotime($receipt->delivery_date))) ? date('d-m-Y', strtotime($receipt->delivery_date)) : '';
            $row[] = (!empty(strtotime($receipt->delivery_time))) ? date('h:i A', strtotime($receipt->delivery_time)) : '';
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
    
    function receipt_detail_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hallmark_receipt_details rd';
        $config['select'] = 'rd.*,i.item_name,c.purity as purity_name';
        $config['joins'][] = array('join_table' => 'item_master i', 'join_by' => 'i.item_id = rd.article_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = rd.purity', 'join_type' => 'left');
        $config['column_order'] = array('i.item_name', 'rd.receipt_weight', 'c.purity', 'rd.box_no', 'rd.pcs');
        $config['column_search'] = array('i.item_name', 'rd.receipt_weight', 'c.purity', 'rd.box_no', 'rd.pcs');
        if (isset($post_data['receipt_id']) && !empty($post_data['receipt_id'])) {
            $config['wheres'][] = array('column_name' => 'rd.receipt_id', 'column_value' => $post_data['receipt_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $lot_detail) {
            $row = array();
            $row[] = $lot_detail->item_name;
            $row[] = number_format((float) $lot_detail->receipt_weight, 3, '.', '');
            $row[] = number_format((float) $lot_detail->purity_name, 3, '.', '');
            $row[] = $lot_detail->box_no;
            $row[] = $lot_detail->pcs;
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
    
    function delete_receipt($id = '') {
        $where_array = array('receipt_id' => $id);
        $this->crud->delete('hallmark_receipt_details', $where_array);
        $this->crud->delete('hallmark_receipt', $where_array);
    }
    
    function xrf($xrf_id = '') {
        $data = array();
        $hm_ls_option = $this->crud->getFromSQL('SELECT `hm_ls_option` FROM `hallmark_xrf_items` ORDER BY `hallmark_xrf_items`.`updated_at` DESC, xrf_item_id DESC LIMIT 1');
        if(!empty($hm_ls_option)){
            $hm_ls_option = $hm_ls_option[0]->hm_ls_option;
        } else {
            $hm_ls_option = 1;
        }
        $data['hm_ls_option'] = $hm_ls_option;
        $data['xrf_box_no_mandatory'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'xrf_box_no_mandatory'));

        if (!empty($xrf_id)) {
            if ($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID, "edit")) {
                $xrf_data = $this->crud->get_row_by_id('hallmark_xrf', array('xrf_id' => $xrf_id));
                $xrf_data = $xrf_data[0];
                $data['xrf_data'] = $xrf_data;

                $hallmark_xrf_items = $this->crud->get_row_by_id('hallmark_xrf_items', array('xrf_id' => $xrf_id));

                $lineitems = array();
                foreach($hallmark_xrf_items as $xrf_item_row){
                    $xrf_item = new \stdClass();
                    $xrf_item->xrf_item_id = $xrf_item_row->xrf_item_id;
                    $xrf_item->item_id = $xrf_item_row->item_id;
                    $xrf_item->item_name = $this->crud->get_column_value_by_id('hallmark_item_master', 'item_name', array('item_id' => $xrf_item->item_id));

                    $xrf_item->purity = $xrf_item_row->purity;
                    $xrf_item->purity_name = $this->crud->get_column_value_by_id('carat', 'purity', array('carat_id' => $xrf_item->purity));
                    $xrf_item->rec_qty = $xrf_item_row->rec_qty;
                    $xrf_item->price_per_pcs = $xrf_item_row->price_per_pcs;
                    $xrf_item->item_amount = $xrf_item_row->item_amount;
                    $xrf_item->rec_weight = $xrf_item_row->rec_weight;
                    $xrf_item->paid_weight = '';
                    $xrf_item->hm_ls_option = $xrf_item_row->hm_ls_option;
                    if($xrf_item->hm_ls_option == '1'){
                        $xrf_item->hm_ls_option_text = 'Hallmark';
                    } else if($xrf_item->hm_ls_option == '2'){
                        $xrf_item->hm_ls_option_text = 'Laser Solding';
                    } else {
                        $xrf_item->hm_ls_option_text = 'Tunch';
                    }
                    $xrf_item->remark = $xrf_item_row->remark;
                    $lineitems[] = json_encode($xrf_item);
                }
                $data['xrf_detail_arr'] = implode(',', $lineitems);
                set_page('hallmark/xrf', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            if ($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID, "add")) {
                set_page('hallmark/xrf', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }
    
    function xrf_list() {
        if ($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID, "view")) {
            set_page('hallmark/xrf_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function xrf_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hallmark_xrf s';
        $config['select'] = 's.*,a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = s.account_id', 'join_type' => 'left');
        $config['column_search'] = array('s.xrf_id', 'a.account_name','s.box_no','DATE_FORMAT(s.posting_date,"%d-%m-%Y")','DATE_FORMAT(s.receipt_date,"%d-%m-%Y")','s.advance_rec_amount','s.pending_amount','s.taken_by_name');
        $config['column_order'] = array(null,'s.xrf_id', 'a.account_name','s.box_no','s.posting_date','s.receipt_date','s.advance_rec_amount','s.pending_amount','s.taken_by_name');
        
        if ($post_data['everything_from_start'] != 'true'){
            if(!empty($post_data['from_date']) && strtotime($post_data['from_date']) > 0){
                $config['wheres'][] = array('column_name' => 's.posting_date >=', 'column_value' => date("Y-m-d",strtotime($post_data['from_date'])));
            }
        }
        if(!empty($post_data['to_date']) && strtotime($post_data['to_date']) > 0){
            $config['wheres'][] = array('column_name' => 's.posting_date <=', 'column_value' => date("Y-m-d",strtotime($post_data['to_date'])));
        }
        $config['order'] = array('s.xrf_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(HALLMARK_XRF_MODULE_ID, "edit");
        foreach ($list as $xrf_row) {
            $row = array();
            
            $total_rec_qty = 0;
            $this->db->select("SUM(rec_qty) AS total_rec_qty");
            $this->db->from('hallmark_xrf_items');            
            $this->db->where('xrf_id',$xrf_row->xrf_id);
            $tmp_query = $this->db->get();
            $total_rec_qty = $tmp_query->row()->total_rec_qty;

            $action = '';
            if ($role_edit) {
                $action .= '<a href="' . base_url("hallmark/xrf/" . $xrf_row->xrf_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" >&nbsp;</span></a>';
            }
            if ($role_delete) {
                $action .= '<a href="javascript:void(0);" class="delete_xrf" data-href="' . base_url('hallmark/delete_xrf/' . $xrf_row->xrf_id) . '"><span class="glyphicon glyphicon-trash" style="color : red">&nbsp;</span></a>';
            }
            
            $action .= '<a[ href="javascript:void(0);" class="print_xrf_receipt" data-xrf_id="' . $xrf_row->xrf_id . '"><span class="glyphicon glyphicon-print" style="">&nbsp;</span></a>';

            $action .= '<a href="javascript:void(0);" class="item_row" data-xrf_id="' . $xrf_row->xrf_id . '" ><span class="glyphicon glyphicon-eye-open" style="color : #419bf4">&nbsp;</span></a>';

            $row[] = $action;
            $row[] = '<a href="javascript:void(0);" class="update_row" data-xrf_id="' . $xrf_row->xrf_id . '" >'.$xrf_row->receipt_no.'</a>';
            $row[] = $xrf_row->account_name;
            $row[] = $xrf_row->box_no;
            $row[] = (!empty(strtotime($xrf_row->posting_date))) ? date('d-m-Y', strtotime($xrf_row->posting_date)) : '';
            $row[] = (!empty(strtotime($xrf_row->receipt_date))) ? date('d-m-Y', strtotime($xrf_row->receipt_date)) : '';
            $row[] = $total_rec_qty;
            $row[] = number_format((float) $xrf_row->total_item_amount, '2', '.', '');
            $row[] = number_format((float) $xrf_row->other_charges, '2', '.', '');
            $row[] = number_format((float) $xrf_row->advance_rec_amount, '2', '.', '');
            $row[] = number_format((float) $xrf_row->pending_amount, '2', '.', '');
            $row[] = ($xrf_row->taken_by_same == 1?$xrf_row->account_name:$xrf_row->taken_by_name);
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
    
    function xrf_items_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hallmark_xrf_items rd';
        $config['select'] = 'rd.*,i.item_name,rd.purity,c.purity as purity_name';
        $config['joins'][] = array('join_table' => 'hallmark_item_master i', 'join_by' => 'i.item_id = rd.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = rd.purity', 'join_type' => 'left');
        $config['column_order'] = array('i.item_name', 'rd.purity', 'rd.rec_qty', 'rd.rec_weight', 'rd.hm_ls_option');
        $config['column_search'] = array('i.item_name', 'rd.purity', 'rd.rec_qty', 'rd.rec_weight','rd.hm_ls_option');

        if (isset($post_data['xrf_id']) && !empty($post_data['xrf_id'])) {
            $config['wheres'][] = array('column_name' => 'rd.xrf_id', 'column_value' => $post_data['xrf_id']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        foreach ($list as $lot_detail) {
            $row = array();
            if($lot_detail->hm_ls_option == '1'){
                $hm_ls_option = 'Hallmark';
            } else if($lot_detail->hm_ls_option == '2'){
                $hm_ls_option = 'Laser Solding';
            } else {
                $hm_ls_option = 'Tunch';
            }
            $row[] = $hm_ls_option;
            $row[] = $lot_detail->item_name;
            $row[] = number_format((float) $lot_detail->rec_weight, 3, '.', '');
            $row[] = number_format((float) $lot_detail->purity_name, 3, '.', '');
            $row[] = $lot_detail->rec_qty;
            $row[] = $lot_detail->price_per_pcs;
            $row[] = $lot_detail->item_amount;
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

    function print_xrf($xrf_id) {
        $data = array();
        $data['first_line'] = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'hallmark_xrf_print_first_line'));

        $this->db->select("x.*,a.account_name");
        $this->db->from('hallmark_xrf x');
        $this->db->join('account a','a.account_id = x.account_id');
        $this->db->where('x.xrf_id',$xrf_id);
        $query = $this->db->get();
        $xrf_row = $query->row();

        $data['xrf_row'] = $xrf_row;
        
        
        $this->db->select("xi.*,i.item_name");
        $this->db->from('hallmark_xrf_items xi');
        $this->db->join('hallmark_item_master i','i.item_id = xi.item_id');
        $this->db->where('xi.xrf_id',$xrf_id);
        $query = $this->db->get();
        $xrf_item_res = $query->result();
        $data['xrf_item_res'] = $xrf_item_res;

        $html = $this->load->view('hallmark/xrf_receipt', $data, true);
        echo $html;
        exit();

        /*$pdfFilePath = "xrf_receipt.pdf";
        $this->load->library('m_pdf');
        $m_pdf=new mPDF('utf-8', array(100,200),'', '', '',
                      5, // margin_left
                      0, // margin right
                      5, // margin top
                      2, // margin bottom
                      5 // margin header
                    );
        $m_pdf->falseBoldWeight = 8;
        $m_pdf->WriteHTML($html);
        $m_pdf->Output($pdfFilePath, 'I');*/
    }

    function save_xrf() {
        $return = array();
        $post_data = $this->input->post();

        $line_items_data = array();
        if(isset($post_data['line_items_data']) && !empty($post_data['line_items_data'])){
            $line_items_data = json_decode($post_data['line_items_data']);
        }
        $xrf_data = array();
        $xrf_data['posting_date'] = date("Y-m-d",strtotime($post_data['posting_date']));
        $xrf_data['receipt_date'] = date("Y-m-d",strtotime($post_data['receipt_date']));
        $xrf_data['account_id'] = $post_data['account_id'];
        $xrf_data['status'] = $post_data['status'];
        $xrf_data['receipt_time'] = date('H:i',strtotime($post_data['receipt_time']));
        $xrf_data['gst_no'] = $post_data['gst_no'];
        $xrf_data['box_no'] = $post_data['box_no'];
        $xrf_data['total_item_amount'] = $post_data['total_item_amount'];
        $xrf_data['other_charges'] = $post_data['other_charges'];
        $xrf_data['total_amount'] = $post_data['total_amount'];
        $xrf_data['advance_rec_amount'] = $post_data['advance_rec_amount'];
        $xrf_data['pending_amount'] = $post_data['pending_amount'];
        $xrf_data['remark'] = $post_data['remark'];
        $xrf_data['taken_by_same'] = isset($post_data['taken_by_same'])?1:0;
        $xrf_data['taken_by_name'] = $post_data['taken_by_name'];

        if (isset($post_data['xrf_id']) && !empty($post_data['xrf_id'])) {
            $xrf_id = $post_data['xrf_id'];

            // Old XRF data
            $old_xrf_data = $this->crud->get_data_row_by_id('hallmark_xrf', 'xrf_id', $xrf_id);
            if(!empty($old_xrf_data)){

                // Revert : Cash receipt cashbook Customer ledger Credit
                if (!empty($old_xrf_data->advance_rec_amount)) {
                    // Decrease in Department
                    $this->applib->update_account_balance_decrease(XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID, '', '', $old_xrf_data->advance_rec_amount);
                    // Increase in Account
                    $this->applib->update_account_balance_increase($old_xrf_data->account_id, '', '', $old_xrf_data->advance_rec_amount);
                }

                // Revert : Customer ledger debit XRF / HM / Laser PL account credit
                if (!empty($old_xrf_data->total_amount)) {
                    // Decrease in Selected Account
                    $this->applib->update_account_balance_decrease($old_xrf_data->account_id, '', '', $old_xrf_data->total_amount);
                    // Increase in XRF / HM / Laser PL Account
                    $this->applib->update_account_balance_increase(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, '', '', $old_xrf_data->total_amount);
                }

            }

            $id_result = $this->crud->get_id_by_val_count('hallmark_xrf','xrf_id','xrf_id != '.$xrf_id.' AND box_no = '.$xrf_data['box_no']);
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                $return['error_exist'] = "Box No. Already Exist.";
                print json_encode($return);
                exit;
            }

            $xrf_data['updated_at'] = $this->now_time;
            $xrf_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->update('hallmark_xrf', $xrf_data,array("xrf_id" => $xrf_id));
            if ($result) {
                $return['success'] = "Updated";
                $return['xrf_id'] = $xrf_id;
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Xrf Updated Successfully');

                // Cash receipt cashbook Customer ledger Credit
                if (!empty($xrf_data['advance_rec_amount'])) {
                    $insert_arr = array();
                    $insert_arr['xrf_id'] = $xrf_id;
                    $insert_arr['payment_receipt'] = '2';
                    $insert_arr['cash_cheque'] = '1';
                    $insert_arr['transaction_date'] = $xrf_data['posting_date'];
                    $insert_arr['department_id'] = XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID;
                    $insert_arr['account_id'] = $xrf_data['account_id'];
                    $insert_arr['on_behalf_of'] = XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID;
                    $insert_arr['amount'] = $xrf_data['advance_rec_amount'];
                    $insert_arr['narration'] = '';
                    $insert_arr['updated_at'] = $this->now_time;
                    $insert_arr['updated_by'] = $this->logged_in_id;

                    $old_payment_receipt_data = $this->crud->get_data_row_by_id('payment_receipt', 'xrf_id', $xrf_id);
                    if(!empty($old_payment_receipt_data)){
                        $cashbook_result = $this->crud->update('payment_receipt', $insert_arr, $old_payment_receipt_data->pay_rec_id);
                    } else {
                        $insert_arr['created_at'] = $this->now_time;
                        $insert_arr['created_by'] = $this->logged_in_id;
                        $cashbook_result = $this->crud->insert('payment_receipt', $insert_arr);
                    }
                    if ($cashbook_result) {
                        // Increase in Department
                        $this->applib->update_account_balance_increase(XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID, '', '', $xrf_data['advance_rec_amount']);
                        // Decrease in Account
                        $this->applib->update_account_balance_decrease($xrf_data['account_id'], '', '', $xrf_data['advance_rec_amount']);
                    }
                } else {
                    $this->crud->delete('payment_receipt', array('xrf_id' => $xrf_id));
                }

                // Customer ledger debit XRF / HM / Laser PL account credit
                if (!empty($xrf_data['total_amount'])) {
                    // Increase in Selected Account
                    $this->applib->update_account_balance_increase($xrf_data['account_id'], '', '', $xrf_data['total_amount']);
                    // Decrease in XRF / HM / Laser PL Account
                    $this->applib->update_account_balance_decrease(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, '', '', $xrf_data['total_amount']);
                }

                $this->crud->delete('hallmark_xrf_items',array("xrf_id" => $xrf_id));
                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['xrf_id'] = $xrf_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['purity'] = $lineitem->purity;
                        $insert_item['rec_qty'] = $lineitem->rec_qty;
                        $insert_item['price_per_pcs'] = $lineitem->price_per_pcs;
                        $insert_item['item_amount'] = $lineitem->item_amount;
                        $insert_item['rec_weight'] = $lineitem->rec_weight;
                        $insert_item['paid_weight'] = '';
                        $insert_item['hm_ls_option'] = $lineitem->hm_ls_option;
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('hallmark_xrf_items', $insert_item);
                    }
                }
            }
        } else {
            $id_result = $this->crud->get_id_by_val_count('hallmark_xrf','xrf_id','box_no = '.$xrf_data['box_no']);
            if (!empty($id_result)) {
                $return['error'] = "Exist";
                $return['error_exist'] = "Box No. Already Exist.";
                print json_encode($return);
                exit;
            }

            $hallmark_xrf = $this->crud->get_max_number('hallmark_xrf', 'receipt_no');
            $receipt_no = 1;
            if ($hallmark_xrf->receipt_no > 0) {
                $receipt_no = $hallmark_xrf->receipt_no + 1;
            }
            $xrf_data['receipt_no'] = $receipt_no;
            $xrf_data['created_at'] = $this->now_time;
            $xrf_data['created_by'] = $this->logged_in_id;
            $result = $this->crud->insert('hallmark_xrf', $xrf_data);
            $xrf_id = $this->db->insert_id();

            if ($result) {
                $return['success'] = "Added";
                $return['xrf_id'] = $xrf_id;
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Xrf Added Successfully');

                // Cash receipt cashbook Customer ledger Credit
                if (!empty($xrf_data['advance_rec_amount'])) {
                    $insert_arr = array();
                    $insert_arr['xrf_id'] = $xrf_id;
                    $insert_arr['payment_receipt'] = '2';
                    $insert_arr['cash_cheque'] = '1';
                    $insert_arr['transaction_date'] = $xrf_data['posting_date'];
                    $insert_arr['department_id'] = XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID;
                    $insert_arr['account_id'] = $xrf_data['account_id'];
                    $insert_arr['on_behalf_of'] = XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID;
                    $insert_arr['amount'] = $xrf_data['advance_rec_amount'];
                    $insert_arr['narration'] = '';
                    $insert_arr['created_at'] = $this->now_time;
                    $insert_arr['created_by'] = $this->logged_in_id;
                    $insert_arr['updated_at'] = $this->now_time;
                    $insert_arr['updated_by'] = $this->logged_in_id;
                    $cashbook_result = $this->crud->insert('payment_receipt', $insert_arr);
                    if ($cashbook_result) {
                        // Increase in Department
                        $this->applib->update_account_balance_increase(XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID, '', '', $xrf_data['advance_rec_amount']);
                        // Decrease in Account
                        $this->applib->update_account_balance_decrease($xrf_data['account_id'], '', '', $xrf_data['advance_rec_amount']);
                    }
                }

                // Customer ledger debit XRF / HM / Laser PL account credit
                if (!empty($xrf_data['total_amount'])) {
                    // Increase in Selected Account
                    $this->applib->update_account_balance_increase($xrf_data['account_id'], '', '', $xrf_data['total_amount']);
                    // Decrease in XRF / HM / Laser PL Account
                    $this->applib->update_account_balance_decrease(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, '', '', $xrf_data['total_amount']);
                }

                if (!empty($line_items_data)) {
                    foreach ($line_items_data as $key => $lineitem) {
                        $insert_item = array();
                        $insert_item['xrf_id'] = $xrf_id;
                        $insert_item['item_id'] = $lineitem->item_id;
                        $insert_item['purity'] = $lineitem->purity;
                        $insert_item['rec_qty'] = $lineitem->rec_qty;
                        $insert_item['price_per_pcs'] = $lineitem->price_per_pcs;
                        $insert_item['item_amount'] = $lineitem->item_amount;
                        $insert_item['rec_weight'] = $lineitem->rec_weight;
                        $insert_item['paid_weight'] = '';
                        $insert_item['created_at'] = $this->now_time;
                        $insert_item['created_by'] = $this->logged_in_id;
                        $insert_item['updated_at'] = $this->now_time;
                        $insert_item['updated_by'] = $this->logged_in_id;
                        $this->crud->insert('hallmark_xrf_items', $insert_item);
                    }
                }
            }
        }
        print json_encode($return);
        exit;
    }

    function delete_xrf($xrf_id = ''){
        $return = array();

        // Old XRF data
        $old_xrf_data = $this->crud->get_data_row_by_id('hallmark_xrf', 'xrf_id', $xrf_id);
        if(!empty($old_xrf_data)){

            // Revert : Cash receipt cashbook Customer ledger Credit
            if (!empty($old_xrf_data->advance_rec_amount)) {
                // Decrease in Department
                $this->applib->update_account_balance_decrease(XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID, '', '', $old_xrf_data->advance_rec_amount);
                // Increase in Account
                $this->applib->update_account_balance_increase($old_xrf_data->account_id, '', '', $old_xrf_data->advance_rec_amount);
            }
            $this->crud->delete('payment_receipt', array('xrf_id' => $xrf_id));

            // Revert : Customer ledger debit XRF / HM / Laser PL account credit
            if (!empty($old_xrf_data->total_amount)) {
                // Decrease in Selected Account
                $this->applib->update_account_balance_decrease($old_xrf_data->account_id, '', '', $old_xrf_data->total_amount);
                // Increase in XRF / HM / Laser PL Account
                $this->applib->update_account_balance_increase(XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID, '', '', $old_xrf_data->total_amount);
            }

        }

        $where_array = array('xrf_id' => $xrf_id);
        $this->crud->delete('hallmark_xrf_items', $where_array);
        $this->crud->delete('hallmark_xrf', $where_array);
        $return['success'] = 'Deleted';
        echo json_encode($return);
        exit;
    }
    
    function xrf_items() {
        if ($this->applib->have_access_role(HALLMARK_XRF_MODULE_ID, "view")) {
            set_page('hallmark/xrf_items');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function xrf_items_page_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'hallmark_xrf_items hxi';
        $config['select'] = 'hxi.*, hx.posting_date, i.item_name, hxi.purity, a.account_name,c.purity as purity_name';
        $config['joins'][] = array('join_table' => 'hallmark_xrf hx', 'join_by' => 'hx.xrf_id = hxi.xrf_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = hx.account_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'hallmark_item_master i', 'join_by' => 'i.item_id = hxi.item_id', 'join_type' => 'left');
        $config['joins'][] = array('join_table' => 'carat c', 'join_by' => 'c.carat_id = hxi.purity', 'join_type' => 'left');
        $config['column_order'] = array(null,'a.account_name', 'hx.posting_date', 'i.item_name', 'hxi.purity', 'hxi.rec_qty', 'hxi.rec_weight', 'hxi.hm_ls_option');
        $config['column_search'] = array('a.account_name','i.item_name', 'hxi.purity', 'hxi.rec_qty', 'hxi.rec_weight', 'hxi.hm_ls_option');

        if ($post_data['everything_from_start'] != 'true') {
            if (!empty($post_data['from_date']) && strtotime($post_data['from_date']) > 0) {
                $config['wheres'][] = array('column_name' => 'hx.posting_date >=', 'column_value' => date("Y-m-d", strtotime($post_data['from_date'])));
            }
        }
        if (!empty($post_data['to_date']) && strtotime($post_data['to_date']) > 0) {
            $config['wheres'][] = array('column_name' => 'hx.posting_date <=', 'column_value' => date("Y-m-d", strtotime($post_data['to_date'])));
        }
        if (!empty($post_data['filter_deliver_status']) && $post_data['filter_deliver_status'] != 'all') {
            if ($post_data['filter_deliver_status'] == '1') {
                $config['wheres'][] = array('column_name' => 'hxi.deliver_status', 'column_value' => '1');
            } else {
                $config['wheres'][] = array('column_name' => 'hxi.deliver_status', 'column_value' => '0');
            }
        }
        if (!empty($post_data['filter_hm_ls_option']) && $post_data['filter_hm_ls_option'] != 'all') {
            $config['wheres'][] = array('column_name' => 'hxi.hm_ls_option', 'column_value' => $post_data['filter_hm_ls_option']);
        }
        $this->load->library('datatables', $config, 'datatable');
        $xrf_items = $this->datatable->get_datatables();
        $data = array();
        foreach ($xrf_items as $xrf_item) {
            $row = array();
            $deliver_status = '';
            if ($xrf_item->deliver_status == 0) {
                $deliver_status .= ' <input type="checkbox" name="deliver_status" data-xrf_item_id="' . $xrf_item->xrf_item_id . '" data-deliver_status="1" data-href="' . base_url('hallmark/set_deliver_status/' . $xrf_item->xrf_item_id) . '" class="set_deliver_status" style="height: 20px; width: 20px;">';
            } else {
                $deliver_status .= ' <input type="checkbox" name="deliver_status" data-xrf_item_id="' . $xrf_item->xrf_item_id . '" data-deliver_status=0 data-href="' . base_url('hallmark/set_deliver_status/' . $xrf_item->xrf_item_id) . '" class="set_deliver_status" style="height: 20px; width: 20px;" checked="checked">';
            }
            $row[] = $deliver_status;
            $row[] = $xrf_item->account_name;
            $row[] = date('d-m-Y', strtotime($xrf_item->posting_date));
            if ($xrf_item->hm_ls_option == '1') {
                $hm_ls_option = 'Hallmark';
            } else if ($xrf_item->hm_ls_option == '2') {
                $hm_ls_option = 'Laser Solding';
            } else {
                $hm_ls_option = 'Tunch';
            }
            $row[] = $hm_ls_option;
            $row[] = $xrf_item->item_name;
            $row[] = number_format((float) $xrf_item->rec_weight, 3, '.', '');
            $row[] = number_format((float) $xrf_item->purity_name, 3, '.', '');
            $row[] = $xrf_item->rec_qty;
            $row[] = $xrf_item->price_per_pcs;
            $row[] = $xrf_item->item_amount;
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

    function set_deliver_status($xrf_item_id = '') {
        $update_data = array();
        $update_data['deliver_status'] = $_POST['deliver_status'];
        $update_data['updated_at'] = $this->now_time;
        $update_data['updated_by'] = $this->logged_in_id;
        
        $result = $this->crud->update('hallmark_xrf_items', $update_data, array('xrf_item_id' => $xrf_item_id));
        if ($result) {
            $return['success'] = "Updated";
        } else {
            $return['error'] = "Error";
        }
        print json_encode($return);
        exit;
    }
    
    function complete_report() {
        $data = array();
        set_page('hallmark/complete_report', $data);
    }
    
    function job_cash_received() {
        $data = array();
        set_page('hallmark/job_cash_received', $data);
    }
    
    function voucher_transaction() {
        $data = array();
        set_page('hallmark/voucher_transaction', $data);
    }
    
    function cash_receipt_entry() {
        $data = array();
        set_page('hallmark/cash_receipt_entry', $data);
    }
}
