<?php

/**
 * Class AppLib
 * &@property CI_Controller $ci
 */
class AppLib
{
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->ci->load->model('Crud', 'crud');
        $this->ci->load->library('session');
        $this->user_id = $this->ci->session->userdata(PACKAGE_FOLDER_NAME.'is_logged_in')['user_id'];
        $this->now_date = date('Y-m-d');
        $this->now_time = date('Y-m-d H:i:s');
    }

    /***
     * @param $file_url
     * @return bool
     */
    function unlink_file($file_url)
    {
        if (file_exists($file_url)) {
            if (unlink($file_url)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $image
     * @param $upload_url
     * @param bool $return_with_url
     * @return bool|string
     */
    function upload_image($file, $upload_url, $return_with_url = true){
		$config['upload_path'] = $upload_url;
		$config['allowed_types'] = '*';
        $config['encrypt_name'] = TRUE;
		$this->ci->load->library('upload', $config);
        $this->ci->upload->initialize($config);
        if (!$this->ci->upload->do_upload($file)) {
			print_r($this->ci->upload->display_errors());exit;
            $error = array('error' => $this->upload->display_errors());
            return false;
        }
        $data = $this->ci->upload->data();
        $file_name = $data ['file_name'];
        if ($return_with_url) {
            return $upload_url . $file_name;
        } else {
            return $file_name;
        }

    }

    /**
     * @param string $date
     * @return bool|string
     * DD/MM/YYYY To YYYY-MM-DD
     */
    function to_sql_date($date = '')
    {
        return date('Y-m-d', strtotime($date));
    }

    /**
     * @param string $date
     * @return bool|string
     * YYYY-MM-DD To DD/MM/YYYY
     */
    function to_simple_date($date = '')
    {
        return date('d/m/Y', strtotime(str_replace('-', '/', $date)));
    }

    /**
     * @param $SearchResults
     * @param $SortResults
     * @return array
     */
    function order_search($SearchResults, $SortResults){
        return array_values(array_intersect($SearchResults, $SortResults));
    }
    
    function have_access_role($module, $role){
        $status = 0;
        $user_roles = $this->ci->session->userdata(PACKAGE_FOLDER_NAME . 'user_roles');
        //echo '<pre>';print_r($user_roles);die();
        if(isset($user_roles[$module]) && in_array($role, $user_roles[$module]))
        {
            $status = 1;
        }
        return $status;
    }
    
    function send_sms($mobile_no, $sms, $from_per = ''){
//        $api_params = 'UserID=' . SEND_SMS_USER_ID . '&UserPassword=' . SEND_SMS_USERPASSWORD . '&PhoneNumber=' . $mobile_no . '&Text=' . urlencode($sms) . '&SenderId=' . SEND_SMS_SENDER_ID . '&AccountType=2';
//        $smsGatewayUrl = "http://sms.infisms.co.in/API/SendSMS.aspx?";
//        $url = $smsGatewayUrl . $api_params;
////        echo '<pre>'; print_r($url); exit;
//        $ch = curl_init();                       // initialize CURL
//        curl_setopt($ch, CURLOPT_POST, false);
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $result = curl_exec($ch);
//        curl_close($ch);                         // Close CURL
//        if (!$result) {
//            $result = file_get_contents($url);
//        }
////        if($from_per == 'for_login_otp' || $from_per == 'manu_hand_made'){
////        } else {
////            if ($result) {
////                echo 'sms sent';
////            } else {
////                echo 'sms Not Sent';
////            }
////            exit;
////        }
    }
    
    function sendemail_complain($email_id,$email){
//        $this->ci->load->library('email');
//        $config['protocol']    = 'smtp';
//        $config['smtp_host']    = 'ssl://smtpout.secureserver.net';
//        $config['smtp_port']    = '465';
//        $config['smtp_timeout'] = '60';
//        $config['smtp_user']    = 'support@contactelectrotech.com';
//        $config['smtp_pass']    = 'SupporT';
//        $config['charset']    = 'utf-8';
//        $config['wordwrap'] = TRUE;
//        $config['newline']    = "\r\n";
//        $config['mailtype'] = 'html'; // or html
//        $config['validation'] = TRUE; // bool whether to validate email or not
//        $this->ci->email->initialize($config);
//
//        $this->ci->email->from('support@contactelectrotech.com', 'Contact Electrotech');
//        $this->ci->email->to($email_id); 
//        $this->ci->email->bcc('manishrajkot135@gmail.com');
//        $this->ci->email->subject('Complain Received');
//        $message = "<html><head><head></head><body><p>Hi,</p>".$email."</body></html>";
//        $this->ci->email->message($message);
//        $this->ci->email->send();
        
        
//        if ($this->ci->email->send()){
//            echo "hi its works";
//        }else{
//            show_error($this->ci->email->print_debugger());
//        }
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }
    
    function array_sort_by_column_stock_ledger(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            if($col == 1){ // For Date column Sorting
                $sort_col[$key] = date('Y-m-d', strtotime($row[$col]));
            } elseif($col == 2){ // For Date column Sorting
                $sort_col[$key] = strip_tags($row[$col]);
                $sort_col[$key] = strtoupper(trim($sort_col[$key]));
            } else {
                $sort_col[$key] = $row[$col];
            }
        }
        array_multisort($sort_col, $dir,$arr);
    }
    
    function array_sort_by_column_outstanding(&$arr, $col, $dir = SORT_ASC, $sort_type) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            if($col == 4){ // For Date column Sorting
                $sort_col[$key] = date('Y-m-d', strtotime($row[$col]));
            } elseif($col == 2){ // For Date column Sorting
                $sort_col[$key] = strip_tags($row[$col]);
                $sort_col[$key] = strtoupper(trim($sort_col[$key]));
            } else {
                $sort_col[$key] = $row[$col];
            }
        }
        array_multisort($sort_col, $dir, $sort_type, $arr);
    }
    
    function all_department_ids(){
        $department_ids = array();
        $department_arr = $this->ci->crud->getFromSQL('SELECT `account_id` FROM `account` WHERE `account_group_id` = "' . DEPARTMENT_GROUP . '"');
        foreach ($department_arr as $department){
            $department_ids[] = $department->account_id;
        }
        return $department_ids;
    }
    
    function current_user_department_ids(){
        $department_ids = array();
        $department_arr = $this->ci->crud->getFromSQL('SELECT `department_id` FROM `user_department` WHERE `user_id` = "' . $this->user_id . '"');
        foreach ($department_arr as $department){
            $department_ids[] = $department->department_id;
        }
        return $department_ids;
    }
    
    function current_user_order_department_ids(){
        $department_ids = array();
        $department_arr = $this->ci->crud->getFromSQL('SELECT `department_id` FROM `user_order_department` WHERE `user_id` = "' . $this->user_id . '"');
        foreach ($department_arr as $department){
            $department_ids[] = $department->department_id;
        }
        return $department_ids;
    }
    
    function current_user_account_group_ids(){
        $account_group_ids = array();
        $account_group_ids[] = DEPARTMENT_GROUP;
        $account_group_arr = $this->ci->crud->getFromSQL('SELECT `account_group_id` FROM `user_account_group` WHERE `user_id` = "' . $this->user_id . '"');
        foreach ($account_group_arr as $account_group){
            $account_group_ids[] = $account_group->account_group_id;
        }
        $account_group_ids[] = NOT_APPROVED_ACCOUNT_GROUP_ID;
        return $account_group_ids;
    }
    
    function current_user_account_ids(){
        $user_row = $this->ci->crud->get_data_row_by_id('user_master','user_id',$this->user_id);
        $account_ids = array();
        if($user_row->allow_all_accounts == ALLOW_ALL_ACCOUNTS) {
            return "allow_all_accounts";

        } elseif ($user_row->allow_all_accounts == ALLOW_ONLY_SELECTED_ACCOUNTS) {
            if(!empty($user_row->selected_accounts)) {
                $account_ids = explode(',',$user_row->selected_accounts);
            }
            $user_department = $this->ci->crud->get_row_by_id('account', array('account_group_id' => DEPARTMENT_GROUP));
            if(!empty($user_department)) {
                foreach ($user_department as $key => $row) {
                    $account_ids[] = $row->account_id;
                }
            }
        }
        return $account_ids;
    }
    
    function current_worker_department_ids($worker_id){
        $department_ids = array();
        $department_arr = $this->ci->crud->getFromSQL('SELECT u.department_id FROM account a LEFT JOIN user_department u ON u.user_id=a.user_id WHERE a.account_id = "' . $worker_id . '"');
        foreach ($department_arr as $department){
            $department_ids[] = $department->department_id;
        }
        return $department_ids;
    }
    
    function get_customer_ledger_data_arr($from_date, $to_date, $account_id, $type_sort, $view_only_hisab, $offset = 0){
        $p_data = array();
        if (($type_sort == '' || $type_sort == 'P') && $view_only_hisab == '0') {
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $p_data = $this->ci->crud->get_sell_items_for_mfloss_customer_ledger($from_date, $to_date, $account_id, 'P', $offset);
            } else {
                $p_data = $this->ci->crud->get_sell_items_for_customer_ledger($from_date, $to_date, $account_id, 'P', $offset);
            }
        }
        $s_data = array();
        if (($type_sort == '' || $type_sort == 'S') && $view_only_hisab == '0') {
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $s_data = $this->ci->crud->get_sell_items_for_mfloss_customer_ledger($from_date, $to_date, $account_id, 'S', $offset);
            } else {
                $s_data = $this->ci->crud->get_sell_items_for_customer_ledger($from_date, $to_date, $account_id, 'S', $offset);
            }
        }
        $e_data = array();
        if (($type_sort == '' || $type_sort == 'E') && $view_only_hisab == '0') {
            if($account_id == MF_LOSS_EXPENSE_ACCOUNT_ID){
                $e_data = $this->ci->crud->get_sell_items_for_mfloss_customer_ledger($from_date, $to_date, $account_id, 'E', $offset);
            } else {
                $e_data = $this->ci->crud->get_sell_items_for_customer_ledger($from_date, $to_date, $account_id, 'E', $offset);
            }
        }
        $sp_discount_data = array();
        if (($type_sort == '' || $type_sort == 'SP Discount') && $view_only_hisab == '0') {
            $sp_discount_data = $this->ci->crud->get_sell_discount_for_customer_ledger($from_date, $to_date, $account_id, 'SP Discount', $offset);
        }
        $sell_with_gst_amount_data = array();
        if (($type_sort == '' || $type_sort == 'GST Bill') && $view_only_hisab == '0') {
            $sell_with_gst_amount_data = $this->ci->crud->get_sell_with_gst_amount_for_customer_ledger($from_date, $to_date, $account_id, 'GST Bill', $offset);
        }
        $m_r_data = array();
        if (($type_sort == '' || $type_sort == 'M R') && $view_only_hisab == '0') {
            $m_r_data = $this->ci->crud->get_metal_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'M R', $offset);
        }
        $m_p_data = array();
        if (($type_sort == '' || $type_sort == 'M P') && $view_only_hisab == '0') {
            $m_p_data = $this->ci->crud->get_metal_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'M P', $offset);
        }
        $p_payment_data = array();
        $p_payment_data_bank = array();
        if (($type_sort == '' || $type_sort == 'Payment') && $view_only_hisab == '0') {
            $p_payment_data = $this->ci->crud->get_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'Payment', $offset);
            $p_payment_data_bank = $this->ci->crud->get_payment_receipt_for_customer_ledger_bank($from_date, $to_date, $account_id, 'Payment', $offset);
        }
        $r_receipt_data = array();
        $r_receipt_data_bank = array();
        if (($type_sort == '' || $type_sort == 'Receipt') && $view_only_hisab == '0') {
            $r_receipt_data = $this->ci->crud->get_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'Receipt', $offset);
            $r_receipt_data_bank = $this->ci->crud->get_payment_receipt_for_customer_ledger_bank($from_date, $to_date, $account_id, 'Receipt', $offset);
        }
        $gb_s_data = array();
        if (($type_sort == '' || $type_sort == 'GB S') && $view_only_hisab == '0') {
            $gb_s_data = $this->ci->crud->get_gold_bhav_for_customer_ledger($from_date, $to_date, $account_id, 'GB S', $offset);
        }
        $gb_p_data = array();
        if (($type_sort == '' || $type_sort == 'GB P') && $view_only_hisab == '0') {
            $gb_p_data = $this->ci->crud->get_gold_bhav_for_customer_ledger($from_date, $to_date, $account_id, 'GB P', $offset);
        }
        $sb_s_data = array();
        if (($type_sort == '' || $type_sort == 'SB S') && $view_only_hisab == '0') {
            $sb_s_data = $this->ci->crud->get_silver_bhav_for_customer_ledger($from_date, $to_date, $account_id, 'SB S', $offset);
        }
        $sb_p_data = array();
        if (($type_sort == '' || $type_sort == 'SB P') && $view_only_hisab == '0') {
            $sb_p_data = $this->ci->crud->get_silver_bhav_for_customer_ledger($from_date, $to_date, $account_id, 'SB P', $offset);
        }
        $tr_naam_data = array();
        if (($type_sort == '' || $type_sort == 'TR Naam') && $view_only_hisab == '0') {
            $tr_naam_data = $this->ci->crud->get_transfer_naam_jama_for_customer_ledger($from_date, $to_date, $account_id, 'TR Naam', $offset);
        }
        $tr_jama_data = array();
        if (($type_sort == '' || $type_sort == 'TR Jama') && $view_only_hisab == '0') {
            $tr_jama_data = $this->ci->crud->get_transfer_naam_jama_for_customer_ledger($from_date, $to_date, $account_id, 'TR Jama', $offset);
        }
        $sell_ad_charges_data = array();
        if (($type_sort == '' || $type_sort == 'Ad Charges') && $view_only_hisab == '0') {
            $sell_ad_charges_data = $this->ci->crud->get_sell_ad_charges_for_customer_ledger($from_date, $to_date, $account_id, 'Ad Charges', $offset);
        }
        $sell_adjust_cr_data = array();
        if (($type_sort == '' || $type_sort == 'Adjust CR') && $view_only_hisab == '0') {
            $sell_adjust_cr_data = $this->ci->crud->get_sell_adjust_cr_for_customer_ledger($from_date, $to_date, $account_id, 'Adjust CR', $offset);
        }
        $j_naam_data = array();
        if (($type_sort == '' || $type_sort == 'J Naam') && $view_only_hisab == '0') {
            $j_naam_data = $this->ci->crud->get_journal_naam_jama_for_customer_ledger($from_date, $to_date, $account_id, 'J Naam', $offset);
        }
        $j_jama_data = array();
        if (($type_sort == '' || $type_sort == 'J Jama') && $view_only_hisab == '0') {
            $j_jama_data = $this->ci->crud->get_journal_naam_jama_for_customer_ledger($from_date, $to_date, $account_id, 'J Jama', $offset);
        }
        $c_r_data = array();
        if (($type_sort == '' || $type_sort == 'C R') && $view_only_hisab == '0') {
            $c_r_data = $this->ci->crud->get_cashbook_for_customer_ledger($from_date, $to_date, $account_id, 'C R', $offset);
        }
        $c_p_data = array();
        if (($type_sort == '' || $type_sort == 'C P') && $view_only_hisab == '0') {
            $c_p_data = $this->ci->crud->get_cashbook_for_customer_ledger($from_date, $to_date, $account_id, 'C P', $offset);
        }
        $mfi_data = array();
        if (($type_sort == '' || $type_sort == 'MFI') && $view_only_hisab == '0') {
            $mfi_data = $this->ci->crud->get_manufacture_issue_receive_for_customer_ledger($from_date, $to_date, $account_id, 'MFI', $offset);
        }
        $mfr_data = array();
        if (($type_sort == '' || $type_sort == 'MFR') && $view_only_hisab == '0') {
            $mfr_data = $this->ci->crud->get_manufacture_issue_receive_for_customer_ledger($from_date, $to_date, $account_id, 'MFR', $offset);
        }
        $irkw_data = array();
        if (($type_sort == '' || $type_sort == 'IRKW') && $view_only_hisab == '0') {
            $irkw_data = $this->ci->crud->get_issue_receive_karigar_wastage_for_customer_ledger($from_date, $to_date, $account_id, 'IRKW', $offset);
        }
        $mfis_data = array();
        if (($type_sort == '' || $type_sort == 'MFIS') && $view_only_hisab == '0') {
            $mfis_data = $this->ci->crud->get_manufacture_issue_receive_silver_for_customer_ledger($from_date, $to_date, $account_id, 'MFIS', $offset);
        }
        $mfrs_data = array();
        if (($type_sort == '' || $type_sort == 'MFRS') && $view_only_hisab == '0') {
            $mfrs_data = $this->ci->crud->get_manufacture_issue_receive_silver_for_customer_ledger($from_date, $to_date, $account_id, 'MFRS', $offset);
        }
        $mhmifw_data = array();
        if(($type_sort == '' || $type_sort == 'MHMIFW') && $view_only_hisab == '0'){
            $mhmifw_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger($from_date, $to_date, $account_id, 'MHMIFW', $offset);
        }
        $mhmis_data = array();
        if(($type_sort == '' || $type_sort == 'MHMIS') && $view_only_hisab == '0'){
            $mhmis_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger($from_date, $to_date, $account_id, 'MHMIS', $offset);
        }
        $mhmrfw_data = array();
        if(($type_sort == '' || $type_sort == 'MHMRFW') && $view_only_hisab == '0'){
            $mhmrfw_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger($from_date, $to_date, $account_id, 'MHMRFW', $offset);
        }
        $mhmrs_data = array();
        if(($type_sort == '' || $type_sort == 'MHMRS') && $view_only_hisab == '0'){
            $mhmrs_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger($from_date, $to_date, $account_id, 'MHMRS', $offset);
        }
        $castingifw_data = array();
        if(($type_sort == '' || $type_sort == 'CASTINGIFW') && $view_only_hisab == '0'){
            $castingifw_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger($from_date, $to_date, $account_id, 'CASTINGIFW', $offset);
        }
        $castingis_data = array();
        if(($type_sort == '' || $type_sort == 'CASTINGIS') && $view_only_hisab == '0'){
            $castingis_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger($from_date, $to_date, $account_id, 'CASTINGIS', $offset);
        }
        $castingrfw_data = array();
        if(($type_sort == '' || $type_sort == 'CASTINGRFW') && $view_only_hisab == '0'){
            $castingrfw_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger($from_date, $to_date, $account_id, 'CASTINGRFW', $offset);
        }
        $castingrs_data = array();
        if(($type_sort == '' || $type_sort == 'CASTINGRS') && $view_only_hisab == '0'){
            $castingrs_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger($from_date, $to_date, $account_id, 'CASTINGRS', $offset);
        }
        $mchainifw_data = array();
        if(($type_sort == '' || $type_sort == 'MCHAINIFW') && $view_only_hisab == '0'){
            $mchainifw_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAINIFW', $offset);
        }
        $mchainis_data = array();
        if(($type_sort == '' || $type_sort == 'MCHAINIS') && $view_only_hisab == '0'){
            $mchainis_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAINIS', $offset);
        }
        $mchainrfw_data = array();
        if(($type_sort == '' || $type_sort == 'MCHAINRFW') && $view_only_hisab == '0'){
            $mchainrfw_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAINRFW', $offset);
        }
        $mchainrs_data = array();
        if(($type_sort == '' || $type_sort == 'MCHAINRS') && $view_only_hisab == '0'){
            $mchainrs_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAINRS', $offset);
        }
        $o_p_data = array();
        if (($type_sort == '' || $type_sort == 'O P') && $view_only_hisab == '0') {
            $o_p_data = $this->ci->crud->get_other_sell_item_for_customer_ledger($from_date, $to_date, $account_id, 'O P', $offset);
        }
        $o_s_data = array();
        if (($type_sort == '' || $type_sort == 'O S') && $view_only_hisab == '0') {
            $o_s_data = $this->ci->crud->get_other_sell_item_for_customer_ledger($from_date, $to_date, $account_id, 'O S', $offset);
        }
        $o_payment_data = array();
        $o_payment_data_bank = array();
        if (($type_sort == '' || $type_sort == 'O Payment') && $view_only_hisab == '0') {
            $o_payment_data = $this->ci->crud->get_other_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'O Payment', $offset);
            $o_payment_data_bank = $this->ci->crud->get_other_payment_receipt_for_customer_ledger_bank($from_date, $to_date, $account_id, 'O Payment', $offset);
        }
        $o_receipt_data = array();
        $o_receipt_data_bank = array();
        if (($type_sort == '' || $type_sort == 'O Receipt') && $view_only_hisab == '0') {
            $o_receipt_data = $this->ci->crud->get_other_payment_receipt_for_customer_ledger($from_date, $to_date, $account_id, 'O Receipt', $offset);
            $o_receipt_data_bank = $this->ci->crud->get_other_payment_receipt_for_customer_ledger_bank($from_date, $to_date, $account_id, 'O Receipt', $offset);
        }
        $hisab_fine_data = array();
        if (($type_sort == '' || $type_sort == 'Hisab Fine') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_fine_data = $this->ci->crud->get_hisab_fine_for_customer_ledger($from_date, $to_date, $account_id, 'Hisab Fine', $offset);
        }
        $hisab_done_i_data = array();
        if (($type_sort == '' || $type_sort == 'HD-I') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_i_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'HD-I', $offset);
        }
        $hisab_done_r_data = array();
        if (($type_sort == '' || $type_sort == 'HD-R') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_r_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'HD-R', $offset);
        }
        $hisab_fine_s_data = array();
        if (($type_sort == '' || $type_sort == 'Hisab Fine S') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_fine_s_data = $this->ci->crud->get_hisab_fine_silver_for_customer_ledger($from_date, $to_date, $account_id, 'Hisab Fine S', $offset);
        }
        $hisab_done_i_s_data = array();
        if (($type_sort == '' || $type_sort == 'HD-I S') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_i_s_data = $this->ci->crud->get_hisab_done_irs_for_customer_ledger($from_date, $to_date, $account_id, 'HD-I S', $offset);
        }
        $hisab_done_r_s_data = array();
        if (($type_sort == '' || $type_sort == 'HD-R S') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_r_s_data = $this->ci->crud->get_hisab_done_irs_for_customer_ledger($from_date, $to_date, $account_id, 'HD-R S', $offset);
        }
        $hisab_fine_mhm_data = array();
        if (($type_sort == '' || $type_sort == 'MHM Hisab Fine') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_fine_mhm_data = $this->ci->crud->get_hisab_fine_for_customer_ledger($from_date, $to_date, $account_id, 'MHM Hisab Fine', $offset);
        }
        $hisab_done_mhm_i_data = array();
        if (($type_sort == '' || $type_sort == 'MHM HD-I') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_mhm_i_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'MHM HD-I', $offset);
        }
        $hisab_done_mhm_r_data = array();
        if (($type_sort == '' || $type_sort == 'MHM HD-R') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_mhm_r_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'MHM HD-R', $offset);
        }
        $hisab_fine_casting_data = array();
        if (($type_sort == '' || $type_sort == 'CASTING Hisab Fine') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_fine_casting_data = $this->ci->crud->get_hisab_fine_for_customer_ledger($from_date, $to_date, $account_id, 'CASTING Hisab Fine', $offset);
        }
        $hisab_done_casting_i_data = array();
        if (($type_sort == '' || $type_sort == 'CASTING HD-I') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_casting_i_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'CASTING HD-I', $offset);
        }
        $hisab_done_casting_r_data = array();
        if (($type_sort == '' || $type_sort == 'CASTING HD-R') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_casting_r_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'CASTING HD-R', $offset);
        }
        $hisab_fine_mchain_data = array();
        if (($type_sort == '' || $type_sort == 'MCHAIN Hisab Fine') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_fine_mchain_data = $this->ci->crud->get_hisab_fine_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAIN Hisab Fine', $offset);
        }
        $hisab_done_mchain_i_data = array();
        if (($type_sort == '' || $type_sort == 'MCHAIN HD-I') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_mchain_i_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAIN HD-I', $offset);
        }
        $hisab_done_mchain_r_data = array();
        if (($type_sort == '' || $type_sort == 'MCHAIN HD-R') && ($view_only_hisab == '0' || $view_only_hisab == '1')) {
            $hisab_done_mchain_r_data = $this->ci->crud->get_hisab_done_ir_for_customer_ledger($from_date, $to_date, $account_id, 'MCHAIN HD-R', $offset);
        }
        $xrf_data = array();
        if (($type_sort == '' || $type_sort == 'XRF') && $view_only_hisab == '0') {
            $xrf_data = $this->ci->crud->get_hallmark_xrf_for_customer_ledger($from_date, $to_date, $account_id, 'XRF', $offset);
        }
        $customer_ledger_data = array_merge($p_data, $s_data, $e_data, $sp_discount_data, $sell_with_gst_amount_data, $m_r_data, $m_p_data, $p_payment_data, $p_payment_data_bank, $r_receipt_data, $r_receipt_data_bank, $gb_s_data, $gb_p_data, $sb_s_data, $sb_p_data, $tr_naam_data, $tr_jama_data, $sell_ad_charges_data, $sell_adjust_cr_data, $j_naam_data, $j_jama_data, $c_r_data, $c_p_data, $mfi_data, $mfr_data, $irkw_data, $mfis_data, $mfrs_data, $mhmifw_data, $mhmis_data, $mhmrfw_data, $mhmrs_data, $castingifw_data, $castingis_data, $castingrfw_data, $castingrs_data, $mchainifw_data, $mchainis_data, $mchainrfw_data, $mchainrs_data, $o_p_data, $o_s_data, $o_payment_data, $o_payment_data_bank, $o_receipt_data, $o_receipt_data_bank, $hisab_fine_data, $hisab_done_i_data, $hisab_done_r_data, $hisab_fine_s_data, $hisab_done_i_s_data, $hisab_done_r_s_data, $hisab_fine_mhm_data, $hisab_done_mhm_i_data, $hisab_done_mhm_r_data, $hisab_fine_casting_data, $hisab_done_casting_i_data, $hisab_done_casting_r_data, $hisab_fine_mchain_data, $hisab_done_mchain_i_data, $hisab_done_mchain_r_data, $xrf_data);
        return $customer_ledger_data;
    }
    
    function get_customer_ledger_department_data_arr($from_date, $to_date, $department_id, $type_sort, $offset = 0){
        $p_data = array();
        if ($type_sort == '' || $type_sort == 'P') {
            $p_data = $this->ci->crud->get_sell_items_for_customer_ledger_department($from_date, $to_date, $department_id, 'P', $offset);
        }
        $s_data = array();
        if ($type_sort == '' || $type_sort == 'S') {
            $s_data = $this->ci->crud->get_sell_items_for_customer_ledger_department($from_date, $to_date, $department_id, 'S', $offset);
        }
        $e_data = array();
        if ($type_sort == '' || $type_sort == 'E') {
            $e_data = $this->ci->crud->get_sell_items_for_customer_ledger_department($from_date, $to_date, $department_id, 'E', $offset);
        }
        $sell_with_gst_amount_data = array();
        if (($type_sort == '' || $type_sort == 'GST Bill')) {
            $sell_with_gst_amount_data = $this->ci->crud->get_sell_with_gst_amount_for_customer_ledger_department($from_date, $to_date, $department_id, 'GST Bill', $offset);
        }
        $m_r_data = array();
        if ($type_sort == '' || $type_sort == 'M R') {
            $m_r_data = $this->ci->crud->get_metal_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'M R', $offset);
        }
        $m_p_data = array();
        if ($type_sort == '' || $type_sort == 'M P') {
            $m_p_data = $this->ci->crud->get_metal_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'M P', $offset);
        }
        $p_payment_data = array();
        if ($type_sort == '' || $type_sort == 'Payment') {
            $p_payment_data = $this->ci->crud->get_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'Payment', $offset);
        }
        $r_receipt_data = array();
        if ($type_sort == '' || $type_sort == 'Receipt') {
            $r_receipt_data = $this->ci->crud->get_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'Receipt', $offset);
        }
        $c_r_data = array();
        if ($type_sort == '' || $type_sort == 'C R') {
            $c_r_d_data = $this->ci->crud->get_cashbook_for_customer_ledger_department($from_date, $to_date, $department_id, 'C R', $offset);
            $c_r_a_data = $this->ci->crud->get_cashbook_for_customer_ledger($from_date, $to_date, $department_id, 'C R', $offset);
            $c_r_data = array_merge($c_r_d_data, $c_r_a_data);
        }
        $c_p_data = array();
        if ($type_sort == '' || $type_sort == 'C P') {
            $c_p_d_data = $this->ci->crud->get_cashbook_for_customer_ledger_department($from_date, $to_date, $department_id, 'C P', $offset);
            $c_p_a_data = $this->ci->crud->get_cashbook_for_customer_ledger($from_date, $to_date, $department_id, 'C P', $offset);
            $c_p_data = array_merge($c_p_d_data, $c_p_a_data);
        }
        $st_f_data = array();
        if ($type_sort == '' || $type_sort == 'ST F') {
            $st_f_data = $this->ci->crud->get_stock_transfer_for_customer_ledger_department($from_date, $to_date, $department_id, 'ST F', $offset);
        }
        $st_t_data = array();
        if ($type_sort == '' || $type_sort == 'ST T') {
            $st_t_data = $this->ci->crud->get_stock_transfer_for_customer_ledger_department($from_date, $to_date, $department_id, 'ST T', $offset);
        }
        $mfi_data = array();
        if ($type_sort == '' || $type_sort == 'MFI') {
            $mfi_data = $this->ci->crud->get_manufacture_issue_receive_for_customer_ledger_department($from_date, $to_date, $department_id, 'MFI', $offset);
        }
        $mfr_data = array();
        if ($type_sort == '' || $type_sort == 'MFR') {
            $mfr_data = $this->ci->crud->get_manufacture_issue_receive_for_customer_ledger_department($from_date, $to_date, $department_id, 'MFR', $offset);
        }
        $mfis_data = array();
        if ($type_sort == '' || $type_sort == 'MFIS') {
            $mfis_data = $this->ci->crud->get_manufacture_issue_receive_silver_for_customer_ledger_department($from_date, $to_date, $department_id, 'MFIS', $offset);
        }
        $mfrs_data = array();
        if ($type_sort == '' || $type_sort == 'MFRS') {
            $mfrs_data = $this->ci->crud->get_manufacture_issue_receive_silver_for_customer_ledger_department($from_date, $to_date, $department_id, 'MFRS', $offset);
        }
        $mhmifw_data = array();
        if($type_sort == '' || $type_sort == 'MHMIFW'){
            $mhmifw_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger_department($from_date, $to_date, $department_id, 'MHMIFW', $offset);
        }
        $mhmis_data = array();
        if($type_sort == '' || $type_sort == 'MHMIS'){
            $mhmis_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger_department($from_date, $to_date, $department_id, 'MHMIS', $offset);
        }
        $mhmrfw_data = array();
        if($type_sort == '' || $type_sort == 'MHMRFW'){
            $mhmrfw_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger_department($from_date, $to_date, $department_id, 'MHMRFW', $offset);
        }
        $mhmrs_data = array();
        if($type_sort == '' || $type_sort == 'MHMRS'){
            $mhmrs_data = $this->ci->crud->get_manufacture_manu_hand_made_for_customer_ledger_department($from_date, $to_date, $department_id, 'MHMRS', $offset);
        }
        $castingifw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIFW'){
            $castingifw_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger_department($from_date, $to_date, $department_id, 'CASTINGIFW', $offset);
        }
        $castingis_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGIS'){
            $castingis_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger_department($from_date, $to_date, $department_id, 'CASTINGIS', $offset);
        }
        $castingrfw_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRFW'){
            $castingrfw_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger_department($from_date, $to_date, $department_id, 'CASTINGRFW', $offset);
        }
        $castingrs_data = array();
        if($type_sort == '' || $type_sort == 'CASTINGRS'){
            $castingrs_data = $this->ci->crud->get_manufacture_casting_for_customer_ledger_department($from_date, $to_date, $department_id, 'CASTINGRS', $offset);
        }
        $mchainifw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIFW'){
            $mchainifw_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger_department($from_date, $to_date, $department_id, 'MCHAINIFW', $offset);
        }
        $mchainis_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINIS'){
            $mchainis_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger_department($from_date, $to_date, $department_id, 'MCHAINIS', $offset);
        }
        $mchainrfw_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRFW'){
            $mchainrfw_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger_department($from_date, $to_date, $department_id, 'MCHAINRFW', $offset);
        }
        $mchainrs_data = array();
        if($type_sort == '' || $type_sort == 'MCHAINRS'){
            $mchainrs_data = $this->ci->crud->get_manufacture_machin_chain_for_customer_ledger_department($from_date, $to_date, $department_id, 'MCHAINRS', $offset);
        }
        $o_p_data = array();
        if ($type_sort == '' || $type_sort == 'O P') {
            $o_p_data = $this->ci->crud->get_other_sell_item_for_customer_ledger_department($from_date, $to_date, $department_id, 'O P', $offset);
        }
        $o_s_data = array();
        if ($type_sort == '' || $type_sort == 'O S') {
            $o_s_data = $this->ci->crud->get_other_sell_item_for_customer_ledger_department($from_date, $to_date, $department_id, 'O S', $offset);
        }
        $o_payment_data = array();
        if ($type_sort == '' || $type_sort == 'O Payment') {
            $o_payment_data = $this->ci->crud->get_other_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'O Payment', $offset);
        }
        $o_receipt_data = array();
        if ($type_sort == '' || $type_sort == 'O Receipt') {
            $o_receipt_data = $this->ci->crud->get_other_payment_receipt_for_customer_ledger_department($from_date, $to_date, $department_id, 'O Receipt', $offset);
        }
        $customer_ledger_data = array_merge($p_data, $s_data, $e_data, $sell_with_gst_amount_data, $m_r_data, $m_p_data, $p_payment_data, $r_receipt_data, $c_r_data, $c_p_data, $st_f_data, $st_t_data, $mfi_data, $mfr_data, $mfis_data, $mfrs_data, $mhmifw_data, $mhmis_data, $mhmrfw_data, $mhmrs_data, $castingifw_data, $castingis_data, $castingrfw_data, $castingrs_data, $mchainifw_data, $mchainis_data, $mchainrfw_data, $mchainrs_data, $o_p_data, $o_s_data, $o_payment_data, $o_receipt_data);
        return $customer_ledger_data;
    }
    
    function multi_array_search($array, $search) {
        // Create the result array
        $result = array();
        // Iterate over each array element
        foreach ($array as $key => $value) {

            // Iterate over each search condition
            foreach ($search as $k => $v) {

                // If the array element does not meet the search condition then continue to the next element
                if (!isset($value[$k]) || $value[$k] != $v) {
                    continue 2;
                }
            }
            // Add the array element's key to the result array
            $result[] = $key;
        }
        // Return the result array
        return $result;
    }

    function get_financial_start_date_by_date($date){
        $time = strtotime($date);
        $month = date("m",$time);
        $year = date("Y",$time);
        if ($month <= 3) {
            $financial_start_date = '01-04-'.($year-1);
        } else {
            $financial_start_date = '01-04-'.$year;
        }
        return $financial_start_date;
    }

    function update_account_balance_increase($account_id, $gold_fine = '', $silver_fine = '', $amount = ''){
        $account_data = $this->ci->crud->get_data_row_by_id('account', 'account_id', $account_id);
        if(!empty($account_data)){
            $update_array = array();
            if($gold_fine != ''){
                $acc_gold_fine = number_format((float) $account_data->gold_fine, '3', '.', '') + number_format((float) $gold_fine, '3', '.', '');
                $acc_gold_fine = number_format((float) $acc_gold_fine, '3', '.', '');
                $update_array['gold_fine'] = $acc_gold_fine;
            }
            if($silver_fine != ''){
                $acc_silver_fine = number_format((float) $account_data->silver_fine, '3', '.', '') + number_format((float) $silver_fine, '3', '.', '');
                $acc_silver_fine = number_format((float) $acc_silver_fine, '3', '.', '');
                $update_array['silver_fine'] = $acc_silver_fine;
            }
            if($amount != ''){
                $acc_amount = number_format((float) $account_data->amount, '2', '.', '') + number_format((float) $amount, '2', '.', '');
                $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                $update_array['amount'] = $acc_amount;
            }
            $update_array['balance_date'] = $this->now_date;
            $this->ci->crud->update('account', $update_array, array('account_id' => $account_id));
        }
    }

    function update_account_balance_decrease($account_id, $gold_fine = '', $silver_fine = '', $amount = ''){
        $account_data = $this->ci->crud->get_data_row_by_id('account', 'account_id', $account_id);
        if(!empty($account_data)){
            $update_array = array();
            if($gold_fine != ''){
                $acc_gold_fine = number_format((float) $account_data->gold_fine, '3', '.', '') - number_format((float) $gold_fine, '3', '.', '');
                $acc_gold_fine = number_format((float) $acc_gold_fine, '3', '.', '');
                $update_array['gold_fine'] = $acc_gold_fine;
            }
            if($silver_fine != ''){
                $acc_silver_fine = number_format((float) $account_data->silver_fine, '3', '.', '') - number_format((float) $silver_fine, '3', '.', '');
                $acc_silver_fine = number_format((float) $acc_silver_fine, '3', '.', '');
                $update_array['silver_fine'] = $acc_silver_fine;
            }
            if($amount != ''){
                $acc_amount = number_format((float) $account_data->amount, '2', '.', '') - number_format((float) $amount, '2', '.', '');
                $acc_amount = number_format((float) $acc_amount, '2', '.', '');
                $update_array['amount'] = $acc_amount;
            }
            $update_array['balance_date'] = $this->now_date;
            $this->ci->crud->update('account', $update_array, array('account_id' => $account_id));
        }
    }

    function update_item_stock_increase($department_id, $category_id, $item_id, $grwt, $less, $ntwt = 0, $tunch = 100, $fine){
        $where_stock_array = array('department_id' => $department_id, 'category_id' => $category_id, 'item_id' => $item_id, 'tunch' => $tunch);
        $fine = $ntwt * $tunch / 100;
        $exist_item_stock = $this->ci->crud->get_row_by_where('item_stock', $where_stock_array);
        if(!empty($exist_item_stock)){
            $grwt = number_format((float) $exist_item_stock->grwt, '3', '.', '') + number_format((float) $grwt, '3', '.', '');
            $less = number_format((float) $exist_item_stock->less, '3', '.', '') + number_format((float) $less, '3', '.', '');
            $ntwt = number_format((float) $exist_item_stock->ntwt, '3', '.', '') + number_format((float) $ntwt, '3', '.', '');
            $fine = number_format((float) $exist_item_stock->fine, '3', '.', '') + number_format((float) $fine, '3', '.', '');
            $update_item_stock = array();
            $update_item_stock['grwt'] = number_format((float) $grwt, '3', '.', '');
            $update_item_stock['less'] = number_format((float) $less, '3', '.', '');
            $update_item_stock['ntwt'] = number_format((float) $ntwt, '3', '.', '');
            $update_item_stock['fine'] = number_format((float) $fine, '3', '.', '');
            $update_item_stock['updated_at'] = $this->now_time;
            $update_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_stock->item_stock_id));
        } else {
            $insert_item_stock = array();
            $insert_item_stock['department_id'] = $department_id;
            $insert_item_stock['category_id'] = $category_id;
            $insert_item_stock['item_id'] = $item_id;
            $insert_item_stock['grwt'] = number_format((float) $grwt, '3', '.', '');
            $insert_item_stock['less'] = number_format((float) $less, '3', '.', '');
            $insert_item_stock['ntwt'] = number_format((float) $ntwt, '3', '.', '');
            $insert_item_stock['tunch'] = $tunch;
            $insert_item_stock['fine'] = number_format((float) $fine, '3', '.', '');
            $insert_item_stock['created_at'] = $this->now_time;
            $insert_item_stock['created_by'] = $this->user_id;
            $insert_item_stock['updated_at'] = $this->now_time;
            $insert_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->insert('item_stock', $insert_item_stock);
        }
    }

    function update_item_stock_decrease($department_id, $category_id, $item_id, $grwt, $less, $ntwt = 0, $tunch = 100, $fine){
        $where_stock_array = array('department_id' => $department_id, 'category_id' => $category_id, 'item_id' => $item_id, 'tunch' => $tunch);
        $fine = $ntwt * $tunch / 100;
        $exist_item_stock = $this->ci->crud->get_row_by_where('item_stock', $where_stock_array);
        if(!empty($exist_item_stock)){
            $grwt = number_format((float) $exist_item_stock->grwt, '3', '.', '') - number_format((float) $grwt, '3', '.', '');
            $less = number_format((float) $exist_item_stock->less, '3', '.', '') - number_format((float) $less, '3', '.', '');
            $ntwt = number_format((float) $exist_item_stock->ntwt, '3', '.', '') - number_format((float) $ntwt, '3', '.', '');
            $fine = number_format((float) $exist_item_stock->fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');
            $update_item_stock = array();
            $update_item_stock['grwt'] = number_format((float) $grwt, '3', '.', '');
            $update_item_stock['less'] = number_format((float) $less, '3', '.', '');
            $update_item_stock['ntwt'] = number_format((float) $ntwt, '3', '.', '');
            $update_item_stock['fine'] = number_format((float) $fine, '3', '.', '');
            $update_item_stock['updated_at'] = $this->now_time;
            $update_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_stock->item_stock_id));
        } else {
            $insert_item_stock = array();
            $insert_item_stock['department_id'] = $department_id;
            $insert_item_stock['category_id'] = $category_id;
            $insert_item_stock['item_id'] = $item_id;
            $insert_item_stock['grwt'] = ZERO_VALUE - number_format((float) $grwt, '3', '.', '');
            $insert_item_stock['less'] = ZERO_VALUE - number_format((float) $less, '3', '.', '');
            $insert_item_stock['ntwt'] = ZERO_VALUE - number_format((float) $ntwt, '3', '.', '');
            $insert_item_stock['tunch'] = $tunch;
            $insert_item_stock['fine'] = ZERO_VALUE - number_format((float) $fine, '3', '.', '');
            $insert_item_stock['created_at'] = $this->now_time;
            $insert_item_stock['created_by'] = $this->user_id;
            $insert_item_stock['updated_at'] = $this->now_time;
            $insert_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->insert('item_stock', $insert_item_stock);
        }
    }

    function update_itemwise_item_stock_increase($department_id, $category_id, $item_id, $grwt, $less, $ntwt = 0, $tunch = 100, $fine, $purchase_sell_item_id = NULL, $stock_type = NULL){
        $fine = $ntwt * $tunch / 100;
        $insert_item_stock = array();
        $insert_item_stock['department_id'] = $department_id;
        $insert_item_stock['category_id'] = $category_id;
        $insert_item_stock['item_id'] = $item_id;
        $insert_item_stock['grwt'] = number_format((float) $grwt, '3', '.', '');
        $insert_item_stock['less'] = number_format((float) $less, '3', '.', '');
        $insert_item_stock['ntwt'] = number_format((float) $ntwt, '3', '.', '');
        $insert_item_stock['tunch'] = $tunch;
        $insert_item_stock['fine'] = number_format((float) $fine, '3', '.', '');
        $insert_item_stock['purchase_sell_item_id'] = $purchase_sell_item_id;
        $insert_item_stock['stock_type'] = $stock_type;
        $insert_item_stock['created_at'] = $this->now_time;
        $insert_item_stock['created_by'] = $this->user_id;
        $insert_item_stock['updated_at'] = $this->now_time;
        $insert_item_stock['updated_by'] = $this->user_id;
        $this->ci->crud->insert('item_stock', $insert_item_stock);
    }

    function update_itemwise_item_stock_decrease($department_id, $category_id, $item_id, $grwt, $less, $ntwt = 0, $tunch = 100, $fine, $purchase_sell_item_id = NULL, $stock_type = NULL){
        $where_stock_array = array('department_id' => $department_id, 'category_id' => $category_id, 'item_id' => $item_id, 'tunch' => $tunch, 'purchase_sell_item_id' => $purchase_sell_item_id);
        $fine = $ntwt * $tunch / 100;
        $exist_item_stock = $this->ci->crud->get_row_by_where('item_stock', $where_stock_array);
        if(!empty($exist_item_stock)){
            $grwt = number_format((float) $exist_item_stock->grwt, '3', '.', '') - number_format((float) $grwt, '3', '.', '');
            $less = number_format((float) $exist_item_stock->less, '3', '.', '') - number_format((float) $less, '3', '.', '');
            $ntwt = number_format((float) $exist_item_stock->ntwt, '3', '.', '') - number_format((float) $ntwt, '3', '.', '');
            $fine = number_format((float) $exist_item_stock->fine, '3', '.', '') - number_format((float) $fine, '3', '.', '');
            $update_item_stock = array();
            $update_item_stock['grwt'] = number_format((float) $grwt, '3', '.', '');
            $update_item_stock['less'] = number_format((float) $less, '3', '.', '');
            $update_item_stock['ntwt'] = number_format((float) $ntwt, '3', '.', '');
            $update_item_stock['fine'] = number_format((float) $fine, '3', '.', '');
            $update_item_stock['updated_at'] = $this->now_time;
            $update_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->update('item_stock', $update_item_stock, array('item_stock_id' => $exist_item_stock->item_stock_id));
        } else {
            $insert_item_stock = array();
            $insert_item_stock['department_id'] = $department_id;
            $insert_item_stock['category_id'] = $category_id;
            $insert_item_stock['item_id'] = $item_id;
            $insert_item_stock['grwt'] = ZERO_VALUE - number_format((float) $grwt, '3', '.', '');
            $insert_item_stock['less'] = ZERO_VALUE - number_format((float) $less, '3', '.', '');
            $insert_item_stock['ntwt'] = ZERO_VALUE - number_format((float) $ntwt, '3', '.', '');
            $insert_item_stock['tunch'] = $tunch;
            $insert_item_stock['fine'] = ZERO_VALUE - number_format((float) $fine, '3', '.', '');
            $insert_item_stock['purchase_sell_item_id'] = $purchase_sell_item_id;
            $insert_item_stock['stock_type'] = $stock_type;
            $insert_item_stock['created_at'] = $this->now_time;
            $insert_item_stock['created_by'] = $this->user_id;
            $insert_item_stock['updated_at'] = $this->now_time;
            $insert_item_stock['updated_by'] = $this->user_id;
            $this->ci->crud->insert('item_stock', $insert_item_stock);
        }
    }

}
