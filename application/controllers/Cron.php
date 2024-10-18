<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Cron extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
        $this->now_time = date('Y-m-d H:i:s');
    }
    
    function user_out_entry(){
        $today = date("Y-m-d");
        $out_time = $this->crud->get_val_by_id('settings', 'login_time_end', 'settings_key', 'settings_value');
        if(!empty($out_time)){
            $hr_att_data = $this->crud->getFromSQL("SELECT * FROM hr_attendance WHERE attendance_id IN (SELECT MAX(attendance_id) FROM hr_attendance WHERE attendance_date = '".$today."' GROUP BY account_id) AND attendance_date = '".$today."' GROUP BY account_id ");
            if(!empty($hr_att_data)){
                foreach ($hr_att_data as $att_data){
                    if($att_data->is_in_out == '1' || $att_data->is_in_out == '2' && $att_data->is_out_for_office == '1'){
                        $attendance_data = array(
                            'account_id' => $att_data->account_id,
                            'attendance_date' => $today,
                            'attendance_time' => date('H:i',strtotime($out_time)),
                            'is_in_out' => '2',
                            'is_out_for_office' => '2',
                            'is_cron_entry' => '1',
                            'created_at' => $this->now_time,
                            'created_by' => ADMINISTRATOR_USER_ID,
                        );
                        $this->db->insert('hr_attendance',$attendance_data);
                    }
                }
            }
            echo "Success";
        } else {
            echo "<span style='color:red;'><b>Error : 'Login To' time is not set in Setting master.</span></b>";
        }

        $this->load->library('email');
        $to_email_address = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_id' => '10'));
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.googlemail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '120';
        $config['smtp_user']    = 'omweb2013@gmail.com';
        $config['smtp_pass']    = '9374973952';
        $config['charset']    = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $config['validation'] = TRUE;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('omweb2013@gmail.com','HRDK');
        $this->email->to(array("omviryash@gmail.com","omvirprashant@gmail.com"));
        $this->email->subject('HRDK: User Out Entry');
        $message = "<html><head><head></head><body><p>Hi,</p>Message : User Out Entry Cron Success</body></html>";
        $this->email->message($message);
        $this->email->send();
        echo "Email Sent";
    }
    
}