<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refinery extends CI_Controller {

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

    
    function refinery_entry($r_entry_id = '') {
        $data = array();
        if (isset($r_entry_id) && !empty($r_entry_id)) {
            if($this->applib->have_access_role(REFINERY_MODULE_ID,"edit")) {   
                $r_entry_data = $this->crud->get_row_by_id('refinery_entry', array('r_entry_id' => $r_entry_id));
                $r_entry_data = $r_entry_data[0];
                $r_entry_data->created_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$r_entry_data->created_by));
                if($r_entry_data->created_by != $r_entry_data->updated_by){
                   $r_entry_data->updated_by_name = $this->crud->get_column_value_by_id('user_master','user_name',array('user_id' =>$r_entry_data->created_by)); 
                }else{
                    $r_entry_data->updated_by_name = $r_entry_data->created_by_name;
                }
                $data['refinery_data'] = $r_entry_data;
//                echo '<pre>'; print_r($data); exit;
                set_page('refinery/refinery_entry', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        } else {
            $max_invoice_no_sql = $this->crud->getFromSQL(" SELECT MAX(invoice_no) as max_invoice_no FROM refinery_entry ");
            $data['invoice_no'] = '1';
            if(!empty($max_invoice_no_sql[0]->max_invoice_no)){
                $data['invoice_no'] = $max_invoice_no_sql[0]->max_invoice_no + 1;
            }
            if($this->applib->have_access_role(REFINERY_MODULE_ID,"add")) {
                set_page('refinery/refinery_entry', $data);
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
                redirect("/");
            }
        }
    }

    function save_refinery() {
        $return = array();
        $post_data = $this->input->post();
        $post_data['entry_date'] = date('Y-m-d', strtotime($post_data['entry_date']));
//        echo '<pre>';        print_r($post_data); exit;
        if (isset($post_data['r_entry_id']) && !empty($post_data['r_entry_id'])) {
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $where_array['r_entry_id'] = $post_data['r_entry_id'];
            $result = $this->crud->update('refinery_entry', $post_data, $where_array);
            if ($result) {
                $return['success'] = "Updated";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Refinery Entry Updated Successfully');
            }
        } else {
            $post_data['created_at'] = $this->now_time;
            $post_data['created_by'] = $this->logged_in_id;
            $post_data['updated_at'] = $this->now_time;
            $post_data['updated_by'] = $this->logged_in_id;
            $result = $this->crud->insert('refinery_entry', $post_data);
            if ($result) {
                $return['success'] = "Added";
                $this->session->set_flashdata('success', true);
                $this->session->set_flashdata('message', 'Refinery Entry Added Successfully');
            }
        }
        print json_encode($return);
        exit;
    }

    function refinery_list() {
        if($this->applib->have_access_role(REFINERY_MODULE_ID,"view")) {
            set_page('refinery/refinery_entry_list');
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function refinery_entry_datatable() {
        $post_data = $this->input->post();
        $config['table'] = 'refinery_entry r';
        $config['select'] = 'r.*, a.account_name';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = r.account_id', 'join_type' => 'left');
        $config['column_search'] = array('a.account_name', 'r.r_entry_id');
//        $config['column_order'] = array(null,'st.category_name', 'im.item_name', 'im.short_item', 'im.die_no', 'im.design_no', 'im.min_order_qty', 'im.default_wastage', 'im.st_default_wastage', 'im.less', null, null, null, null ,'im.sequence_no' ,'im.rate_on');
        $config['order'] = array('r.r_entry_id' => 'desc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();

        $role_delete = $this->app_model->have_access_role(REFINERY_MODULE_ID, "delete");
        $role_edit = $this->app_model->have_access_role(REFINERY_MODULE_ID, "edit");
        foreach ($list as $r_entry) {
            $row = array();
            $action = '';
            if($role_edit){
                $action .= '<a href="' . base_url("refinery/refinery_entry/" . $r_entry->r_entry_id) . '"><span class="edit_button glyphicon glyphicon-edit data-href="#"" style="color : #419bf4" > </span></a> &nbsp;';
            }
            if($role_delete){
                $action .= '<a href="javascript:void(0);" class="delete_button" data-href="' . base_url('refinery/delete_refinery_entry/' . $r_entry->r_entry_id) . '"><span class="glyphicon glyphicon-trash" style="color : red"></span></a>';
            }
            $action .= '&nbsp; &nbsp;<a href="' . base_url("refinery/refinery_print/" . $r_entry->r_entry_id) . '" target="_blank" title="Print" alt="Print"><span class="glyphicon glyphicon-print" style="color : #419bf4">&nbsp</a>';
            $row[] = $action;
            $row[] = $r_entry->invoice_no;
            $row[] = $r_entry->account_name;
            $row[] = date('d-m-Y', strtotime($r_entry->entry_date));
            $row[] = number_format($r_entry->sub_total, 2, '.', '');
            $row[] = number_format($r_entry->total_amount, 2, '.', '');
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
    
    function delete_refinery_entry($id = '') {
        $table = $_POST['table_name'];
        $id_name = $_POST['id_name'];
        $return = $this->crud->delete($table, array($id_name => $id));
        print json_encode($return);
        exit;
    }
    
    function refinery_print($r_entry_id = '') {
        $data = array();
        $entry_data = $this->crud->get_row_by_id('refinery_entry', array('r_entry_id' => $r_entry_id));
        $entry_data = $entry_data[0];
        $entry_data->account_name = '';
        $entry_data->account_gst = '';
        $entry_data->account_address = '';
        $account_data = $this->crud->get_row_by_id('account',array('account_id' => $entry_data->account_id));
        if(!empty($account_data)){
            $entry_data->account_name = $account_data[0]->account_name;
            $entry_data->account_gst = $account_data[0]->account_gst_no;
            $entry_data->account_address = $account_data[0]->account_address;
        }
        $data['entry_data'] = $entry_data;
        $company_data = $this->crud->get_row_by_id('company_details',array('company_id' => '1'));
        $data['company_data'] = $company_data[0];
//            echo "<pre>"; print_r($data); exit;
        $html = $this->load->view('refinery/refinery_print', $data, true);
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage(
            'P', //orientation
            '', //type
            '', //resetpagenum
            '', //pagenumstyle
            '', //suppress
            5, //margin-left
            5, //margin-right
            5, //margin-top
            5, //margin-bottom
            0, //margin-header
            0 //margin-footer
        );
        $mpdf->defHTMLHeaderByName('myHeader2','<div style="text-align: center; font-weight: bold;">Invoice</div>');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
}
