<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Employee_salary
 * @property Crud $crud
 */

class Employee_salary extends CI_Controller
{
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

    public function index(){
        if($this->applib->have_access_role(EMPLOYEE_SALARY_MODULE_ID, "add") || $this->applib->have_access_role(EMPLOYEE_SALARY_MODULE_ID, "view")){
            set_page("employee_salary/index");
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
        
    }

    public function get_salary_data()
    {
        $post_data = $this->input->post();
        $data['month_year'] = $post_data['date'];
        $date = explode('-',$post_data['date']);

        if(!isset($date[0])){
            echo json_encode(array("success"=>false,"message"=>"Please select month"));
            exit();
        }

        if(!isset($date[1])){
            echo json_encode(array("success"=>false,"message"=>"Please select month"));
            exit();
        }

        $total_days = 30;
        $holidays = $this->crud->getFromSQL("select COUNT(`id`) as `total_leave` from hr_yearly_leave where MONTH(`leave_date`) = $date[0] and YEAR(`leave_date`) = $date[1]");
        if(isset($holidays[0]->total_leave)){
            $holidays = $holidays[0]->total_leave;
        }else{
            $holidays = 0;
        }
        $working_days = $total_days - $holidays;
        $data['total_days'] = $total_days;
        $data['holidays'] = $holidays;
        $data['working_days'] = $working_days;

        /* Employee Data */
        $employee = $this->crud->getFromSQL("select `a`.`account_name`,`a`.`account_id`,`u`.`user_id`,`u`.`user_name`,`u`.`salary` from `account` AS `a` INNER JOIN `user_master` AS `u` ON a.user_id = u.user_id where u.status = '0' ");
        $employee_salary_array = array();
        if(!empty($employee)){
            foreach($employee as $row){
                $user_id = $row->user_id;
                $account_id = $row->account_id;

                $att_data = $this->crud->getFromSQL("SELECT attendance_date,attendance_time,is_in_out FROM hr_attendance WHERE account_id = ".$account_id." AND MONTH(attendance_date) = '".$date[0]."' AND YEAR(attendance_date) = '".$date[1]."' AND is_out_for_office = 2 ORDER BY attendance_date ASC, attendance_time ASC ");
//                $att_data = $this->crud->getFromSQL("SELECT attendance_date,attendance_time,is_in_out FROM hr_attendance WHERE account_id = 467 AND MONTH(attendance_date) = '".$date[0]."' AND YEAR(attendance_date) = '".$date[1]."' AND is_out_for_office = 2 ORDER BY attendance_date ASC, attendance_time ASC ");
                $total_working_days = 0;
//                echo "<pre>"; print_r($att_data); exit;
                $all_date_arr = array();
                if(!empty($att_data)){
                    $a_date = '';
                    $today_hour = 0;
                    foreach ($att_data as $e_key => $entry){
                        if($e_key == '0'){
                            $a_date = $entry->attendance_date;
                        } else {
                            if($a_date == $entry->attendance_date){
                                if($att_data[$e_key - 1]->is_in_out == 1 && $entry->is_in_out == 2){
                                    $in_time = new DateTime($entry->attendance_date.' '.$att_data[$e_key - 1]->attendance_time);
                                    $out_time = new DateTime($entry->attendance_date.' '.$entry->attendance_time);
                                    $diff = $out_time->diff($in_time);
                                    $today_hour = $today_hour + $diff->format('%h');
                                }
                            } else {
                                if(!empty($today_hour)){
                                    if($today_hour >= 6){
                                        $total_working_days = $total_working_days + 1;
                                    } else {
                                        $total_working_days = $total_working_days + 0.5;
                                    }
                                }
                                $today_hour = 0;
                                $a_date = $entry->attendance_date;
                            }
                        }
                    }
                    if(!empty($today_hour)){
                        if($today_hour >= 6){
                            $total_working_days = $total_working_days + 1;
                        } else {
                            $total_working_days = $total_working_days + 0.5;
                        }
                    }
                    $today_hour = 0;
                }
                
                $give_salary = 0;
                if($total_working_days > 0){
                    $one_day_salary = $row->salary/30;
                    $total_working_days = $total_working_days + $holidays;
                    if($total_working_days > 30){
                        $total_working_days = 30;
                    }
                    $count_employee_leaves = 30 - $total_working_days;
                    $give_salary = $one_day_salary * ($total_working_days);
                    $total_working_days = $total_working_days - $holidays;
                    $give_salary = round($give_salary);
                } else {
                    $count_employee_leaves = 30;
                }
                
                $check_exists = $this->crud->check_is_exists('employee_salary',array("account_id"=>$row->account_id,"month_year"=>$post_data['date']));
                if($check_exists){

                    $employee_salary_data = $this->crud->get_row_by_id("employee_salary",array("account_id"=>$row->account_id,"month_year"=>$post_data['date']));

                    $employee_salary_array[] = array(
                        'employee_salary_id'=>$employee_salary_data[0]->employee_salary_id,
                        'user_id'=>$row->user_id,
                        'account_id'=>$row->account_id,
                        'account_name'=>$row->account_name,
                        'total_working_days'=>$total_working_days,
                        'salary'=>($row->salary=="")?0:$row->salary,
                        'salary_calculated'=>$give_salary,
                        'give_salary'=>$give_salary,
                        'leaves'=>$count_employee_leaves
                    );

                } else {
                    $employee_salary_array[] = array(
                        'employee_salary_id'=>'',
                        'user_id'=>$row->user_id,
                        'account_id'=>$row->account_id,
                        'account_name'=>$row->account_name,
                        'total_working_days'=>$total_working_days,
                        'salary'=>($row->salary=="")?0:$row->salary,
                        'salary_calculated'=>$give_salary,
                        'give_salary'=>$give_salary,
                        'leaves'=>$count_employee_leaves
                    );
                }
            }
        }

        $data['employee_salary_array'] = $employee_salary_array;
        $html = $this->load->view('employee_salary/employee_data_table',$data,true);
        unset($data['employee_salary_array']);
        $data['employee_html'] = $html;
        echo json_encode(array("success"=>true,"message"=>"","data"=>$data));
        exit();

    }

    public function save_salary()
    {
        $post_data = $this->input->post();

        //$department_id = $this->crud->get_column_value_by_id('user_master', 'default_department_id', array('user_id' => $this->logged_in_id));
        //$expense_account_id = $this->crud->get_column_value_by_id('account', 'account_id', array('account_name' => 'Salary Expense'));
        $result = '';

        if(!empty($post_data)){
//            echo "<pre>"; print_r($post_data); exit;
            foreach($post_data['account_id'] as $key=>$account_id){
                $department_id = $this->crud->getFromSQL('SELECT um.default_department_id FROM account a LEFT JOIN user_master as um ON um.user_id = a.user_id WHERE a.account_id ="'.$account_id.'"');
                if(!empty($department_id)) {
                    $department_id = $department_id[0]->default_department_id;
                } else {
                    $department_id = null;
                }
                if($post_data['monthly_salary'][$key] > 0 && $post_data['salary_calculated'][$key] > 0) {
                    $journal_id_from_salary = $this->crud->get_column_value_by_id('employee_salary', 'journal_id', array('account_id'=>$account_id,'month_year'=> $post_data['month_year']));
                    $journal_id = '';
                    if($journal_id_from_salary){
                        $journal_id = $this->crud->get_column_value_by_id('journal', 'journal_id', array('journal_id'=>$journal_id_from_salary));
                    }
                    if(!empty($journal_id)) {
                        $journal_array = array(
                                    'is_module'=>EMPLOYEE_SALARY_TO_JOURNAL_ID,
                                    'department_id'=>$department_id,
                                    'journal_date'=>date('Y-m-d'),
                                    'updated_at'=>$this->now_time,
                                    'updated_by'=>$this->logged_in_id
                                );
                        $this->crud->update("journal", $journal_array, array("journal_id" => $journal_id));

                        $old_amount = $this->crud->get_column_value_by_id('journal_details', 'amount', array('account_id'=>$account_id,'journal_id'=> $journal_id));

                        $journal_details_array = array(
                                        'type'=>1,
                                        'amount'=>$post_data['give_salary'][$key],
                                        'updated_at'=>$this->now_time,
                                        'updated_by'=>$this->logged_in_id
                                    );
                        $this->crud->update("journal_details", $journal_details_array, array("journal_id" => $journal_id, 'account_id'=>SALARY_EXPENSE_ACCOUNT_ID ));

                        $account = $this->crud->get_row_by_id('account', array('account_id' => SALARY_EXPENSE_ACCOUNT_ID));
                        $account = $account[0];
                        $acc_amount = $account->amount;
                        $acc_amount = $acc_amount - $old_amount;
                        $acc_amount = $acc_amount + $post_data['give_salary'][$key];
                        $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => SALARY_EXPENSE_ACCOUNT_ID));

                        $journal_details_array = array(
                                        'type'=>2,
                                        'amount'=>$post_data['give_salary'][$key],
                                        'updated_at'=>$this->now_time,
                                        'updated_by'=>$this->logged_in_id
                                    );
                        $this->crud->update("journal_details", $journal_details_array, array("journal_id" => $journal_id, 'account_id'=>$account_id));
                        $account = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
                        $account = $account[0];
                        $acc_amount = $account->amount;
                        $acc_amount = $acc_amount + $old_amount;
                        $acc_amount = $acc_amount - $post_data['give_salary'][$key];
                        $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => $account_id));
                    } else {
                        if($post_data['give_salary'][$key] > 0){
                            $journal_array = array(
                                        'department_id'=>$department_id,
                                        'is_module'=>EMPLOYEE_SALARY_TO_JOURNAL_ID,
                                        'journal_date'=>date('Y-m-d'),
                                        'created_at'=>$this->now_time,
                                        'created_by'=>$this->logged_in_id,
                                        'updated_at'=>$this->now_time,
                                        'updated_by'=>$this->logged_in_id
                                    );
                            $result = $this->crud->insert('journal', $journal_array);
                            $journal_id = $this->db->insert_id();

                            $journal_details_array = array(
                                            'journal_id'=>$journal_id,
                                            'type'=>1,
                                            'account_id'=>SALARY_EXPENSE_ACCOUNT_ID,
                                            'amount'=>$post_data['give_salary'][$key],
                                            'narration'=>$post_data['month_year'].' : Employee Salary',
                                            'created_at'=>$this->now_time,
                                            'created_by'=>$this->logged_in_id,
                                            'updated_at'=>$this->now_time,
                                            'updated_by'=>$this->logged_in_id
                                        );
                            $this->crud->insert('journal_details', $journal_details_array);

                            $account = $this->crud->get_row_by_id('account', array('account_id' => SALARY_EXPENSE_ACCOUNT_ID));
                            $account = $account[0];
                            $acc_amount = $account->amount;
                            $acc_amount = $acc_amount + $post_data['give_salary'][$key];
                            $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => SALARY_EXPENSE_ACCOUNT_ID));

                            $journal_details_array = array(
                                            'journal_id'=>$journal_id,
                                            'type'=>2,
                                            'account_id'=>$account_id,
                                            'amount'=>$post_data['give_salary'][$key],
                                            'narration'=>$post_data['month_year'].' : Employee Salary',
                                            'created_at'=>$this->now_time,
                                            'created_by'=>$this->logged_in_id,
                                            'updated_at'=>$this->now_time,
                                            'updated_by'=>$this->logged_in_id
                                        );
                            $this->crud->insert('journal_details', $journal_details_array);

                            $account = $this->crud->get_row_by_id('account', array('account_id' => $account_id));
                            $account = $account[0];
                            $acc_amount = $account->amount;
                            $acc_amount = $acc_amount - $post_data['give_salary'][$key];
                            $this->crud->update('account', array('amount' => $acc_amount), array('account_id' => $account_id));
                        }
                    }
                    if(empty($post_data['employee_salary_id'][$key])){
                        if(!empty($post_data['give_salary'][$key])) {
                            $data_array = array(
                                'account_id'=>$account_id,
                                'department_id'=>$department_id,
                                'month_year'=>$post_data['month_year'],
                                'worked_days'=>$post_data['worked_days'][$key],
                                'monthly_salary'=>$post_data['monthly_salary'][$key],
                                'salary_calculated'=>$post_data['salary_calculated'][$key],
                                'give_salary'=>$post_data['give_salary'][$key],
                                'leaves'=>$post_data['leaves'][$key],
                                'journal_id'=>$journal_id,
                                'created_at'=>$this->now_time,
                                'created_by'=>$this->logged_in_id,
                                'updated_at'=>$this->now_time,
                                'updated_by'=>$this->logged_in_id,
                            );
                            $result = $this->crud->insert("employee_salary",$data_array);
                        }
                    } else {
                        if(!empty($post_data['give_salary'][$key])) {
                            $data_array = array(
                                'account_id'=>$account_id,
                                'department_id'=>$department_id,
                                'month_year'=>$post_data['month_year'],
                                'worked_days'=>$post_data['worked_days'][$key],
                                'monthly_salary'=>$post_data['monthly_salary'][$key],
                                'salary_calculated'=>$post_data['salary_calculated'][$key],
                                'give_salary'=>$post_data['give_salary'][$key],
                                'leaves'=>$post_data['leaves'][$key],
                                'updated_at'=>$this->now_time,
                                'updated_by'=>$this->logged_in_id,
                            );
                            $result = $this->crud->update("employee_salary",$data_array,array("employee_salary_id"=>$post_data['employee_salary_id'][$key]));
                        } else {
                            $this->crud->delete('employee_salary', array('employee_salary_id' => $post_data['employee_salary_id'][$key]));
                            if(!empty($journal_id)){
                                $this->crud->delete('journal_details', array('journal_id' => $journal_id));
                                $this->crud->delete('journal', array('journal_id' => $journal_id));
                            }
                        }
                    }
                }
            }
            echo json_encode(array("success"=>true,"message"=>"Employee Salary Save Successfully"));
            exit();
        }else{
            echo json_encode(array("success"=>false,"message"=>"Something goes wrong"));
            exit();
        }
    }

    function get_days_in_month($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year %400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

}

?>