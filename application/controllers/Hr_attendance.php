<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_attendance extends CI_Controller {

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

    function index() {
        $data = array();
        if ($this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID, "view") || $this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID, "add") || $this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID, "edit")){
            set_page('hr_attendance/hr_attendance', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }

    function save_hr_attendance() {
        $post_data = $this->input->post();

        $account_id = $post_data['account_id'];
        $attendance_date = date('Y-m-d',strtotime($post_data['attendance_date']));
        $attendance_time = date('H:i',strtotime($post_data['attendance_time']));
        $is_in_out = $post_data['is_in_out'];
        $is_out_for_office = (isset($post_data['is_out_for_office'])?1:2);

        /*---- Validation ----*/
        $this->db->select('*');
        $this->db->from('hr_attendance');
        $this->db->where('attendance_date',date('Y-m-d',strtotime($attendance_date)));
        $this->db->where('account_id',$account_id);
        $this->db->order_by('attendance_id','desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $attendance_row = $query->row_array();

            $last_attendance_time = date('Y-m-d H:i:s',strtotime($attendance_row['attendance_date'].' '.$attendance_row['attendance_time']));
            $curr_attendance_time = date('Y-m-d H:i:s',strtotime($attendance_date.' '.$attendance_time));

            if(($attendance_row['is_in_out'] == 1 && $is_in_out == 1) || ($attendance_row['is_in_out'] == 2 && $attendance_row['is_out_for_office'] == 1 && $is_in_out == 1)) {
                if($attendance_row['is_in_out'] == 2 && $is_in_out == 1 && $is_out_for_office == 1){} else {
                    $error_message = 'In Entry Already Exists.';
                }

            } elseif ($attendance_row['is_in_out'] == 2 && $attendance_row['is_out_for_office'] == 2 && $is_in_out == 2) {
                $error_message = 'Out Entry Already Exists.';

            } elseif ($attendance_row['is_in_out'] == 2 && $attendance_row['is_out_for_office'] == 1 && $is_in_out == 2 && $is_out_for_office == 1) {
                $error_message = 'Out For Office Entry Already Exists.';
                
            }
            if (strtotime($curr_attendance_time) <= strtotime($last_attendance_time)) {
                $error_message = 'Entry Already Exists For This Time.';
            }

        } else {
            if($is_in_out == 2) {
                $error_message = 'Out Entry Not Allowed Without In Entry.';
            } else if($is_in_out == 1 && $is_out_for_office == 1) {
                $error_message = 'Out For Office Entry Not Allowed Without In Entry.';
            }   
        }

        if(isset($error_message)) {
            $response['status'] = 'error';
            $response['error_message'] = $error_message;
            echo json_encode($response);
            exit;    
        }
        
        /*---- Validation ----*/

        $attendance_data = array(
            'account_id' => $post_data['account_id'],
            'attendance_date' => date('Y-m-d',strtotime($post_data['attendance_date'])),
            'attendance_time' => date('H:i',strtotime($post_data['attendance_time'])),
            'is_in_out' => $post_data['is_in_out'],
            'is_out_for_office' => (isset($post_data['is_out_for_office'])?1:2),
            'created_at' => $this->now_time,
            'created_by' => $this->logged_in_id,
        );

        $isDateChange = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "allow to update date");
        if($isDateChange == 0) {
            $attendance_data['attendance_date'] = date("Y-m-d");
        }

        $isTimeChange = $this->app_model->have_access_role(HR_ATTENDANCE_MODULE_ID, "allow to update time");
        if($isTimeChange == 0) {
            $attendance_data['attendance_time'] = date("H:i");
        }

        $this->db->insert('hr_attendance',$attendance_data);
        $response['status'] = 'success';
        echo json_encode($response);
        exit;
    }

    function get_user_default_image_url($account_id) {
        $this->db->select("u.default_user_photo");
        $this->db->from("account a");
        $this->db->join('user_master u', 'u.user_id = a.user_id');
        $this->db->where("a.account_id", $account_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $default_image_url_tmp = $query->row()->default_user_photo; 
            if (!empty($default_image_url_tmp) && file_exists(PUBPATH."/uploads/worker_images/".$default_image_url_tmp)) {
                $default_image_url = base_url("/uploads/worker_images/".$default_image_url_tmp);
            }
        }

        if(!isset($default_image_url)) {
            $response['status'] = 'success';
            $response['default_image_url'] = base_url("/assets/image/profile-pic.jpg");
        } else {
            $response['status'] = 'success';
            $response['default_image_url'] = $default_image_url;
        }
        echo json_encode($response);
        exit();
    }

    function attendance_datatable() {
        $post_data = $this->input->post();
        $data = array();
        $hours = array();
        if(!empty($post_data['account_id']) && !empty($post_data['year'])) {
            $list = $this->crud->getFromSQL('SELECT * FROM  `hr_attendance` WHERE YEAR(attendance_date) = "'.$post_data["year"].'" AND account_id = "'.$post_data["account_id"].'" ORDER BY attendance_date ASC,attendance_id ASC');
            
            $in_time = 0;
            $out_time = 0;

            $days_with_only_in_entry = array();
            foreach ($list as $value) {

//                if(strtotime($value->attendance_date) >= strtotime(date('Y-m-d'))) {
//                    continue;
//                }

                $day = date('d-m', strtotime($value->attendance_date));
                if($value->is_in_out == 1) {
                    $days_with_only_in_entry[$day] = 1;
                }
                if($value->is_in_out == 2) {
                    unset($days_with_only_in_entry[$day]);
                }
            }


            foreach ($list as $value) {
                
                if($value->is_out_for_office == 1) {
                    continue;
                }

                $day = date('d-m', strtotime($value->attendance_date));
                
                if($value->is_in_out == 1) {
                    $in_time = date('Y-m-d H:i:s',strtotime($value->attendance_date.' '.$value->attendance_time));
                }
                
                if($value->is_in_out == 2) {
                    $out_time = date('Y-m-d H:i:s',strtotime($value->attendance_date.' '.$value->attendance_time));
                }

                if($in_time > 0 && $out_time > 0) {
                    $no_of_hours = $this->getInOutHours($in_time,$out_time);
                    if(!isset($hours[$day])) {
                        $hours[$day] = 0;
                    }
                    $hours[$day] = $hours[$day] + $no_of_hours;

                    $in_time = 0;
                    $out_time = 0;
                }
            }
        }

        $months = array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        $att_data = $this->crud->getFromSQL('SELECT attendance_date FROM  `hr_attendance` WHERE YEAR(attendance_date) = "'.$post_data["year"].'" AND account_id = "'.$post_data["account_id"].'" GROUP BY attendance_date');
        $att_date_arr = array();
        if(!empty($att_data)){
            foreach ($att_data as $att){
                $att_date_arr[] = $att->attendance_date;
            }
        }
        for($i=1 ; $i <= 12 ; $i++){
            $row = array();
            $row[] = $months[$i];
            $i = $i <= 9 ? '0'.$i : $i;
            $total_monthly_hours = 0;
            for($j=1 ; $j <= 32 ; $j++){
                $j = $j <= 9 ? '0'.$j : $j;
                $date = $post_data['year'] ? $post_data['year'].'-'.$i.'-'.$j : '';
                $is_present = 'A';
                if(in_array($date, $att_date_arr)){
                    $is_present = '<a href="javascript:void(0);" class="show_time" data-date="'.$date.'">P</a>';
                }
                $total_monthly_hours = $total_monthly_hours + (isset($hours[$j.'-'.$i]) && $hours[$j.'-'.$i] ? $hours[$j.'-'.$i] : 0);
                if($j == 32){
                    $row[] = number_format((float) $total_monthly_hours, 2, ".", "");
                } else {
                    $row[] = isset($hours[$j.'-'.$i]) && $hours[$j.'-'.$i] ? '<a href="javascript:void(0);" class="show_time" data-date="'.$date.'">'.number_format((float) $hours[$j.'-'.$i], 2, ".", "").'</a>' : $is_present;
                }
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
            "hours" => $hours,
        );
        echo json_encode($output);
    }

    function getInOutHours($t1,$t2){

        $hours = round((strtotime($t2) - strtotime($t1))/3600,2);
        /*echo "t2 ". $t2;
        echo "<br/>";
        echo "t1 ". $t1;
        echo "<br/>";
        echo "hours ". $hours;
        echo "<br/>";*/
        return $hours;
    }

    function day_attendance_datatable() {
        $post_data = $this->input->post();
        $date = date('Y-m-d', strtotime($post_data['date']));
        $config['select'] = 'hp.*';
        $config['table'] = 'hr_attendance hp';
        $config['column_order'] = array();
        $config['column_search'] = array();
        $config['wheres'][] = array('column_name' => 'hp.account_id', 'column_value' => $post_data['user_id']);
        $config['wheres'][] = array('column_name' => 'hp.attendance_date', 'column_value' => $date);
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

    function get_day_max_attendance_id($account_id,$account_date) 
    {
        $this->db->select('MAX(attendance_id) as attendance_id');
        $this->db->from('hr_attendance');
        $this->db->where('attendance_date',date('Y-m-d',strtotime($account_date)));
        $this->db->where('account_id',$account_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            return $query->row()->attendance_id;
        } else {
            return 0;
        }
    }
    
    function attendance_report() {
        $data = array();
        if ($this->applib->have_access_role(HR_ATTENDANCE_MODULE_ID, "view")){
            set_page('hr_attendance/attendance_report', $data);
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function attendance_report_datatable() {
        $post_data = $this->input->post();
        $date = date('Y-m-d', strtotime($post_data['date']));
        $config['table'] = 'hr_attendance hra';
        $config['select'] = 'hra.*,a.account_name,a.account_id, IF(hra.is_in_out = 1,"In","Out") as in_out, IF(hra.is_out_for_office = 1,"Yes","No") as out_for_office, IF(hra.is_cron_entry = 1,"Yes","No") as cron_entry';
        $config['joins'][] = array('join_table' => 'account a', 'join_by' => 'a.account_id = hra.account_id ');
        $config['column_order'] = array();
        $config['column_search'] = array();
        $config['wheres'][] = array('column_name' => 'hra.account_id', 'column_value' => $post_data['user_id']);
        $config['wheres'][] = array('column_name' => 'hra.attendance_date', 'column_value' => $date);
        if(!empty($post_data['is_cron_entry'])){
            $config['wheres'][] = array('column_name' => 'hra.is_cron_entry', 'column_value' => $post_data['is_cron_entry']);
        }
        $config['order'] = array('hra.attendance_id' => 'asc');
        $this->load->library('datatables', $config, 'datatable');
        $list = $this->datatable->get_datatables();
        $data = array();
        
        foreach ($list as $entry_detail) {
            $row = array();
            $row[] = $entry_detail->account_name;
            $row[] = $entry_detail->account_id;
            $row[] = date('d-m-Y',strtotime($entry_detail->attendance_date));
            $row[] = date('h:i A',strtotime($entry_detail->attendance_time));
            $row[] = $entry_detail->in_out;
            $row[] = $entry_detail->out_for_office;
            $row[] = $entry_detail->cron_entry;
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

    function get_date_time() 
    {
        echo json_encode(array('attendance_date' => date('d-m-Y'),'attendance_time' => date('h:i A')));
    }
    
}