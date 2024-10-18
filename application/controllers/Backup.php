<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Backup
 * &@property Crud $crud
 * &@property AppLib $applib
 */
class Backup extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->model('Crud', 'crud');
    }
    
    function index(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }

        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'download_backup_db')) {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }

        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {

            $this->session->unset_userdata(PACKAGE_FOLDER_NAME.'download_backup_db');

            $this->load->helper('file');
            $this->load->dbutil();
            $file_name = 'hrdk_' . date("Y-m-d-H-i-s") . '.sql.zip';
            $prefs = array(
                'format' => 'zip',
                'filename' => $file_name,
                'add_drop' => FALSE,
                'foreign_key_checks' => FALSE,
            );
            $backup = $this->dbutil->backup($prefs);
            $this->load->helper('download');
            force_download($file_name, $backup);   
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function index_old(){
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            
            $DBUSER=$this->db->username;
            $DBPASSWD=$this->db->password;
            $DATABASE=$this->db->database;
            
            $filename = $DATABASE . "-" . date("Y-m-d_H-i-s") . ".sql.gz";
            $mime = "application/x-gzip";

            header( "Content-Type: " . $mime );
            header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

            $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE --skip-add-drop-table | gzip --best";   

            passthru( $cmd );

            exit(0);
            
            //            $this->load->dbutil();
            //            $prefs = array(     
            //                'format'      => 'zip',             
            //                'filename'    => 'database_backup.sql',
            //                'foreign_key_checks' => FALSE,
            //                'add_drop' => TRUE,
            //                'add_insert' => TRUE,
            //            );
            //            $backup =& $this->dbutil->backup($prefs);
            //            $db_name = 'backup_'. date("d-M-Y H:i") .'.zip';
            //            $save = 'pathtobkfolder/'.$db_name;
            //
            //            $this->load->helper('file');
            //            write_file($save, $backup);
            //            $this->load->helper('download');
            //            force_download($db_name, $backup); 
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            redirect("/");
        }
    }
    
    function email(){
//        if($this->applib->have_access_role(BACKUP_MODULE_ID,"allow")) {
            $dir = './backup_email/';
            if (!(file_exists($dir))) {
                mkdir($dir, 0777);
            }
            $this->load->helper('file');
            $this->load->dbutil();
            $file_name = 'hrdk_'.PACKAGE_FOLDER_NAME.'_' . date("Y-m-d-H-i-s") . '.sql.zip';
            $prefs = array(
                'format' => 'zip',
                'filename' => $file_name,
                'add_drop' => FALSE,
                'foreign_key_checks' => FALSE,
            );
            $backup = $this->dbutil->backup($prefs);
            $way = $dir . $file_name;
            $handle = fopen($way, 'w+');
            fwrite($handle, $backup);
            fclose($handle);
            $this->load->library('email');
            $to_email_address = SEND_BACKUP_EMAIL_ON;
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'ssl://smtp.googlemail.com';
            $config['smtp_port']    = '465';
            $config['smtp_timeout'] = '120';
            $config['smtp_user']    = 'omweb2013@gmail.com';
            $config['smtp_pass']    = 'Omvir@123';
            $config['charset']    = 'utf-8';
            $config['wordwrap'] = TRUE;
    //        $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->from('omweb2013@gmail.com','HRDK');
            $this->email->to($to_email_address);

            $this->email->subject('Database Backup of HRDK : '. PACKAGE_FOLDER_NAME);
            $message = "<html><head><head></head><body><p>Hi,</p>Database Backup of HRDK : ".PACKAGE_FOLDER_NAME."</body></html>";
            $this->email->message($message);
            $this->email->attach($way);
            $this->email->send();
            unlink($way);
            redirect('/');

//        } else {
//            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
//            redirect("/");
//        }
    }

    function check_password()
    {
        $email = $this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_name'];
        $pass = $_POST['user_password'];
        $this->load->model("Appmodel", "app_model");
        $response = $this->app_model->login($email,$pass);
        if ($response) {
            $this->session->set_userdata(PACKAGE_FOLDER_NAME . 'download_backup_db',1);

            echo json_encode(array("status" => "success"));
            exit();
        } else {
            echo json_encode(array("status" => "fail"));
            exit();
        }
    }

    function data_blank()
    {
        if (!$this->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')) {
            redirect('/auth/login/');
        }
        if($this->applib->have_access_role(USER_RIGHTS_MODULE_ID,"allow")) {
            $sell_purchase_type_3_menu = $this->crud->get_column_value_by_id('settings', 'settings_value', array('settings_key' => 'sell_purchase_type_3'));
            if($sell_purchase_type_3_menu == '1' ) { // Method : Sell/Purchase Type 3 Checkbox
                $sql_queries = array();
                $sql_queries[] = "TRUNCATE `casting_entry`;";
                $sql_queries[] = "TRUNCATE `casting_entry_design_files`;";
                $sql_queries[] = "TRUNCATE `casting_entry_details`;";
                $sql_queries[] = "TRUNCATE `casting_entry_order_items`;";
                $sql_queries[] = "TRUNCATE `gold_bhav`;";
                $sql_queries[] = "TRUNCATE `hallmark_item_master`;";
                $sql_queries[] = "TRUNCATE `hallmark_receipt`;";
                $sql_queries[] = "TRUNCATE `hallmark_receipt_details`;";
                $sql_queries[] = "TRUNCATE `hallmark_xrf`;";
                $sql_queries[] = "TRUNCATE `hallmark_xrf_items`;";
                $sql_queries[] = "TRUNCATE `hr_apply_leave`;";
                $sql_queries[] = "TRUNCATE `hr_attendance`;";
                $sql_queries[] = "TRUNCATE `hr_present_hours`;";
                $sql_queries[] = "TRUNCATE `hr_yearly_leave`;";
                $sql_queries[] = "TRUNCATE `issue_receive`;";
                $sql_queries[] = "TRUNCATE `issue_receive_details`;";
                $sql_queries[] = "TRUNCATE `issue_receive_silver`;";
                $sql_queries[] = "TRUNCATE `issue_receive_silver_details`;";
                $sql_queries[] = "TRUNCATE `item_stock`;";
                $sql_queries[] = "TRUNCATE `item_stock_rfid`;";
                $sql_queries[] = "TRUNCATE `journal`;";
                $sql_queries[] = "TRUNCATE `journal_details`;";
                $sql_queries[] = "TRUNCATE `machine_chain`;";
                $sql_queries[] = "TRUNCATE `machine_chain_details`;";
                $sql_queries[] = "TRUNCATE `machine_chain_detail_order_items`;";
                $sql_queries[] = "TRUNCATE `machine_chain_operation`;";
                $sql_queries[] = "TRUNCATE `machine_chain_operation_department`;";
                $sql_queries[] = "TRUNCATE `machine_chain_operation_worker`;";
                $sql_queries[] = "TRUNCATE `machine_chain_order_items`;";
                $sql_queries[] = "TRUNCATE `manu_hand_made`;";
                $sql_queries[] = "TRUNCATE `manu_hand_made_ads`;";
                $sql_queries[] = "TRUNCATE `manu_hand_made_details`;";
                $sql_queries[] = "TRUNCATE `manu_hand_made_order_items`;";
                $sql_queries[] = "TRUNCATE `metal_payment_receipt`;";
                $sql_queries[] = "TRUNCATE `new_order`;";
                $sql_queries[] = "TRUNCATE `opening_stock`;";
                $sql_queries[] = "TRUNCATE `operation`;";
                $sql_queries[] = "TRUNCATE `operation_department`;";
                $sql_queries[] = "TRUNCATE `operation_worker`;";
                $sql_queries[] = "TRUNCATE `order_lot_item`;";
                $sql_queries[] = "TRUNCATE `other`;";
                $sql_queries[] = "TRUNCATE `other_items`;";
                $sql_queries[] = "TRUNCATE `payment_receipt`;";
                $sql_queries[] = "TRUNCATE `refinery_entry`;";
                $sql_queries[] = "TRUNCATE `reminder`;";
                $sql_queries[] = "TRUNCATE `sell`;";
                $sql_queries[] = "TRUNCATE `sell_adjust_cr`;";
                $sql_queries[] = "TRUNCATE `sell_ad_charges`;";
                $sql_queries[] = "TRUNCATE `sell_items`;";
                $sql_queries[] = "TRUNCATE `sell_items_with_gst`;";
                $sql_queries[] = "TRUNCATE `sell_item_charges_details`;";
                $sql_queries[] = "TRUNCATE `sell_less_ad_details`;";
                $sql_queries[] = "TRUNCATE `sell_with_gst`;";
                $sql_queries[] = "TRUNCATE `silver_bhav`;";
                $sql_queries[] = "TRUNCATE `stock_transfer`;";
                $sql_queries[] = "TRUNCATE `stock_transfer_detail`;";
                $sql_queries[] = "TRUNCATE `transfer`;";
                $sql_queries[] = "TRUNCATE `tunch_testing`;";
                $sql_queries[] = "TRUNCATE `worker_entry`;";
                $sql_queries[] = "TRUNCATE `worker_hisab`;";
                $sql_queries[] = "TRUNCATE `worker_hisab_detail`;";

                $sql_queries[] = "UPDATE `account` SET `opening_balance_in_gold`= 0,`gold_ob_credit_debit`=2,`opening_balance_in_silver`=0,`silver_ob_credit_debit`=2,`opening_balance_in_rupees`=0,`rupees_ob_credit_debit`=2,`gold_fine`=0,`silver_fine`=0,`amount`=0 WHERE 1;";
                $sql_queries[] = "UPDATE `account` SET `opening_balance_in_c_amount`= 0,`c_amount_ob_credit_debit`=2,`opening_balance_in_r_amount`=0,`r_amount_ob_credit_debit`=2,`c_amount`=0,`r_amount`=0 WHERE 1;";

                $this->db->query('SET FOREIGN_KEY_CHECKS=0;');
                foreach ($sql_queries as $sql_query){
                    $result = $this->db->query($sql_query);
                }
                $this->db->query('SET FOREIGN_KEY_CHECKS=1;');
                if($result){
                    $this->session->set_flashdata('success_message', 'Data Blank Successfully.');
                } else {
                    $this->session->set_flashdata('error_message', 'some error occurred, Try again...');
                }
            } else {
                $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
            }
        } else {
            $this->session->set_flashdata('error_message', 'You have not permission to access this page.');
        }
        redirect("/");
    }
}