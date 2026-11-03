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

        /**
     * Backup DB with mysqldump (gzip) and email it to config 'send_mail_to'.
     * CLI: php index.php cron db_backup_email
     */
    function db_backup_email(){
        @set_time_limit(0);

        // ---- Load SMTP config + recipient ----
        $this->config->load('db_backup', TRUE);
        $config  = $this->config->item('email', 'db_backup');
        $to      = $config['send_mail_to'];
        unset($config['send_mail_to']); // not an email-library setting

        // ---- Paths ----
        $db_name  = $this->db->database;
        $tmp_dir  = rtrim(sys_get_temp_dir(), '/');
        $base     = $tmp_dir . '/' . $db_name . '_' . date('Y-m-d_H-i-s');
        $sql_path = $base . '.sql';
        $gz_path  = $sql_path . '.gz';
        $err_path = $base . '.err';
        $cnf_path = tempnam($tmp_dir, 'mycnf_');

        // ---- Credentials file (keeps password out of process list) ----
        $cnf  = "[client]\n";
        $cnf .= 'user="' . addcslashes($this->db->username, '"\\') . "\"\n";
        $cnf .= 'password="' . addcslashes($this->db->password, '"\\') . "\"\n";
        $cnf .= 'host="' . $this->db->hostname . "\"\n";
        if (!empty($this->db->port)) {
            $cnf .= 'port=' . (int) $this->db->port . "\n";
        }
        file_put_contents($cnf_path, $cnf);
        chmod($cnf_path, 0600);

        // ---- 1. Dump ----
        $cmd = 'mysqldump --defaults-extra-file=' . escapeshellarg($cnf_path)
             . ' --single-transaction --quick --routines --triggers '
             . escapeshellarg($db_name)
             . ' > ' . escapeshellarg($sql_path)
             . ' 2> ' . escapeshellarg($err_path);
        exec($cmd, $out, $code);
        @unlink($cnf_path);

        if ($code !== 0 || !file_exists($sql_path) || filesize($sql_path) === 0) {
            $err = file_exists($err_path) ? file_get_contents($err_path) : 'mysqldump not found or exec() disabled';
            echo "<span style='color:red;'><b>Error : Database backup failed.</b></span><pre>" . htmlspecialchars($err) . "</pre>";
            @unlink($sql_path);
            @unlink($err_path);
            return;
        }
        @unlink($err_path);

        // ---- 2. Compress ----
        exec('gzip -f ' . escapeshellarg($sql_path), $out2, $code2);
        if ($code2 !== 0 || !file_exists($gz_path)) {
            echo "<span style='color:red;'><b>Error : Could not compress backup.</b></span>";
            @unlink($sql_path);
            return;
        }

        // Gmail attachment limit is 25 MB
        $size_mb = filesize($gz_path) / 1048576;
        if ($size_mb > 24) {
            @unlink($gz_path);
            echo "<span style='color:red;'><b>Error : Backup is " . round($size_mb, 2) . " MB, too large to email (Gmail limit 25 MB).</b></span>";
            return;
        }

        // ---- 3. Send email ----
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from('backup@sala.local', 'DB Backup');
        $this->email->to($to);
        $this->email->subject('DB Backup: ' . $db_name . ' - ' . date('d-m-Y H:i'));
        $this->email->message(
            "Hi,\n\n" .
            "Database backup is attached.\n\n" .
            "Database: " . $db_name . "\n" .
            "Date: " . date('d-m-Y H:i:s') . "\n" .
            "Size: " . round($size_mb, 2) . " MB\n"
        );
        $this->email->attach($gz_path);

        if ($this->email->send()) {
            echo "Success : Backup emailed to " . $to;
        } else {
            echo "<span style='color:red;'><b>Error : Email failed.</b></span><pre>" .
                 $this->email->print_debugger(array('headers')) . "</pre>";
        }

        // ---- 4. Cleanup ----
        @unlink($gz_path);
    }

    function test_mail(){
    $this->config->load('db_backup', TRUE);
    $config = $this->config->item('email', 'db_backup');
    $to = $config['send_mail_to']; unset($config['send_mail_to']);
    $this->load->library('email');
    $this->email->initialize($config);
    $this->email->from($config['smtp_user'], 'Test');
    $this->email->to($to);
    $this->email->subject('SMTP test');
    $this->email->message('works');
    echo $this->email->send() ? 'SENT' : '<pre>'.$this->email->print_debugger().'</pre>';
}
}