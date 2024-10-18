<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit', '1024M');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('PUBPATH',str_replace(SELF,'',FCPATH));

//define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/guru/');
//define('IMAGE_URL',BASE_URL.'uploads/');
//define('SLIDER_IMAGE_URL',BASE_URL.'assets/image/');

define('PACKAGE_NAME', 'Jewel Book');
define('SEND_BACKUP_EMAIL_ON', 'kgkatta@yahoo.com');

define('ACCOUNT_LIST_TABLE_HEIGHT', 350);//account group module table list height

// Order Status id
define('PENDING_STATUS', 1);
define('CANCELED_STATUS', 2);
define('COMPLETED_STATUS', 3);

//Account Group id
define('CUSTOMER_GROUP', 49);
define('BANK_ACCOUNT_GROUP', 21);
define('DEPARTMENT_GROUP', 50);
define('ADMIN_GROUP', 51);
define('USER_GROUP', 52);
define('WORKER_GROUP', 53);
define('SALESMAN_GROUP', 54);
define('EXPENSE_ACCOUNT_GROUP', 9);
define('INCOME_OTHER_THEN_SALES_ACCOUNT_GROUP', 15);
define('SUNDRY_CREDITORS_ACCOUNT_GROUP', 39);
define('SUNDRY_DEBTORS_ACCOUNT_GROUP', 42);

/****************** Account Ids Start *********************/
// Administrator User //
define('ADMINISTRATOR_USER_ID', 1);
define('STAFF_SUKHSHANTI_USER_ID', 10);
define('STAFF_LOVE_KUSH_USER_ID', 44);

// Cash Customer Account ID
define('CASE_CUSTOMER_ACCOUNT_ID', 1);
// Customer Monthly Interest Account ID
define('CUSTOMER_MONTHLY_INTEREST_ACCOUNT_ID', 2);
// Adjust Account ID
define('ADJUST_EXPENSE_ACCOUNT_ID', 3);
// Salary Expense Account ID
define('SALARY_EXPENSE_ACCOUNT_ID', 5);
// MF Loss Expense Account ID
define('MF_LOSS_EXPENSE_ACCOUNT_ID', 6);
// XRF / HM / Laser PL Expense Account ID
define('XRF_HM_LASER_PL_EXPENSE_ACCOUNT_ID', 7);

// CASTING Department Account ID
define('CASTING_DEPARTMENT_ACCOUNT_ID', 358);
// MACHIN CHAIN Department Account ID
define('MACHIN_CHAIN_DEPARTMENT_ACCOUNT_ID', 359);
// Sales MAIN Department Account ID
define('SALES_MAIN_DEPARTMENT_ACCOUNT_ID', 361);
// XRF / HM / Laser Department Account ID
define('XRF_HM_LASER_DEPARTMENT_ACCOUNT_ID', 8);

/****************** Account Ids End *********************/

//Setting values
define('GOLD_RATE', 1);
define('SILVER_RATE', 4);

//User Type
define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_USER', 2);
define('USER_TYPE_WORKER', 3);
define('USER_TYPE_SALESMAN', 4);

//----- Master Module Constants -----
define("MASTER_MODULE_ID", 1); //1
define("CATEGORY_MODULE_ID", 2); //1.1
define("SETTING_MODULE_ID", 3); //1.2
define("TUNCH_MODULE_ID", 4); //1.3
define("WORKER_MODULE_ID", 5); //1.4
define("ITEM_MASTER_MODULE_ID", 6); //1.5
define("USER_MASTER_MODULE_ID", 7); //1.6
define("DEPARTMENT_MODULE_ID", 8); //1.7
define("STATE_MODULE_ID", 9); //1.8
define("CITY_MODULE_ID", 10); //1.9
define("USER_RIGHTS_MODULE_ID", 11); //1.10
define("DESIGN_MODULE_ID", 26); //1.11
define("OPENING_STOCK_MODULE_ID", 34); //1.12
define("AD_MASTER_ID", 61); //1.13
define("STAMP_MODULE_ID", 62); //1.14
define("COMPANY_DETAILS_MODULE_ID", 64); //1.15

//----- OTHER Module Constants -----
define("ACCOUNT_MODULE_ID", 12); //2
define("ACCOUNT_GROUP_MODULE_ID", 48); //2.1

define("ORDER_MENU_ID", 13); //3
define("ORDER_MODULE_ID", 14); //3.1
define("ORDER_SLIDER_MODULE_ID", 15); //3.2
define("SELL_PURCHASE_MENU_ID", 16); //4
define("SELL_PURCHASE_MODULE_ID", 41); //4.1
define("OTHER_ENTRY_MODULE_ID", 42); //4.2
define("STOCK_TRANSFER_MODULE_ID", 17); //5
define("IMPORT_DATA_MODULE_ID", 63); //5.1

define("ENTRY_THROUGH_SELL_PURCHASE_DEFAULT", 1);
define("ENTRY_THROUGH_SELL_PURCHASE_TYPE_2", 2);
define("ENTRY_THROUGH_SELL_PURCHASE_TYPE_3", 3);
define("ENTRY_THROUGH_SELL_PURCHASE_ENTRY_WITH_GST", 4);

// --------Report Module Constants -----
define("REPORT_MODULE_ID", 18); //6
define("DAYBOOK_MODULE_ID", 19); //6
define("STOCK_STATUS_MODULE_ID", 20); //6
define("OUTSTANDING_MODULE_ID", 21); //6
define("BACKUP_MODULE_ID", 22); //6
define("DEMO_MODULE_ID", 23); //6
define("GOLDBOOK_MODULE_ID", 27); //6
define("SILVERBOOK_MODULE_ID", 28); //6
define("INTEREST_MODULE_ID", 36); //6.4
define("TRADING_PL_MODULE_ID", 52); //6.5
define("BALANCE_SHEET_MODULE_ID", 43); //6.3
define("CALCULATIONS_MODULE_ID", 54); //6.6
define("REMINDER_MODULE_ID", 60); //6.7

//
//Journal Module Id
define("JOURNAL_MODULE_ID", 25); //9

//Cashbook Module Id
define("CASHBOOK_MODULE_ID", 29); //10

//Stock Adjust Id
define("STOCK_ADJUST_ID", 30); //11

//Cash Adjust Id
define("CASH_ADJUST_ID", 31); //12

//Yearly Leave Id
define("YEARLY_LEAVES_ID", 32); //13

//Apply Leave Id
define("APPLY_LEAVE_ID", 33); //14

//Present Hours Module Id
define("PRESENT_HOURS_MODULE_ID", 35); //15

//Employee Salary
define("EMPLOYEE_SALARY_MODULE_ID", 37);

//HR
define("HR_MODULE_ID", 38);
define("HR_ATTENDANCE_MODULE_ID", 46); //17.1

//Hallmark
define("HALLMARK_MODULE_ID", 53); //21
define("HALLMARK_RECEIPT_MODULE_ID", 55); //21.1
define("HALLMARK_XRF_MODULE_ID", 56); //21.2
define("HALLMARK_ITEM_MASTER_MODULE_ID", 57); //21.0

define('HALLMARK_XRF_STATUS_ACTIVE', 1);

//Manufacture
define("MANUFACTURE_MODULE_ID", 39);
define("ISSUE_RECEIVE_MODULE_ID", 40);
define("ISSUE_RECEIVE_SILVER_MODULE_ID", 59); // 18.1.1
define("OPERATION_MODULE_ID", 44); //18.2
define("MANU_HAND_MADE_MODULE_ID", 45); //18.3
define("CASTING_MODULE_ID", 58); //18.3.1
define("REFINERY_MODULE_ID", 65); //18.4
define("MACHINE_CHAIN_OPERATION_MODULE_ID", 50); //18.4
define("MACHINE_CHAIN_MODULE_ID", 51); //18.5

define("BALANCE_ID", 47); //18.3
define("SERVER_SHUTDOWN_MODULE_ID", 49); //20
define('SERVER_SHUTDOWN_URL','http://'.$_SERVER['HTTP_HOST'].'/hrdshut/shutdown.php');

// SMS API
define('SEND_SMS_USER_ID', 'GOLDAC');
define('SEND_SMS_USERPASSWORD', 'GOLDAC@123');
define('SEND_SMS_SENDER_ID', 'HRDKBR');

// SMS
define("SEND_ORDER_CREATE_SMS", 'Dear {{party_name}} You Order Has Been Created With Order No : {{order_no}} Total Weight  : {{total_weight}} Total Pcs : {{total_pcs}} Delivery Date : {{delvery_date}}.');
define("SEND_ORDER_CANCEL_SMS", 'Dear {{party_name}} Your Order Has Been Cancelled Due To Reason : {{reason}}.');
//define("SEND_ORDER_COMPLETE_SMS", 'Dear {{party_name}} Your Order Has Been Completed By Us. Your Order Is Ready To Deliver.');
define("SEND_ORDER_COMPLETE_SMS", 'Dear {{party_name}} Your Order No {{order_no}} Has Been Completed By Us Your Order Is Ready To Deliver.');
define("SEND_ORDER_DELETE_SMS", 'Dear {{party_name}} Your Order Has Been Deleted. Sorry For inconvenience.');
define("SEND_SELL_CREATE_SMS", 'Your account balance : Gold {{gold_balance}}, Silver {{silver_balance}}, Amount {{amount}} Your order is {{delivery_type}} Thanks- hrdk.');
define("SEND_OUTSTANDING_SMS", 'Hello {{customer_name}}, Your Outstanding Balance Gold {{gold_fine}}, Silver {{silver_fine}}, Amount {{amount}}, As on {{date}}.');
define("SEND_OTP_SMS", 'User {{user_name}} (Mobile No. {{user_mobile}}) Software Login OTP is : {{otp_value}}.');
define("SEND_ORDER_DELIVERY_DATE_CHANGE_SMS", 'Sorry for Inconvenience. Your Order No. {{order_no}} Date changed to : {{delivery_date}}.');
define("SEND_MHM_LOTT_COMPLETE_CREDITED_AMOUNT", 'Your account is credited with {{mhm_diffrence_amount}} amount.');
define("SEND_MHM_LOTT_COMPLETE_DEBITED_AMOUNT", 'Your account is debited with {{mhm_diffrence_amount}} amount.');
    
//Category Group Gold and Silver Id
define('CATEGORY_GROUP_GOLD_ID', 1);
define('CATEGORY_GROUP_SILVER_ID', 2);
define('CATEGORY_GROUP_OTHER_ID', 3);

//Sell Type ID
define('SELL_TYPE_SELL_ID', 1);
define('SELL_TYPE_PURCHASE_ID', 2);
define('SELL_TYPE_EXCHANGE_ID', 3);
define('SELL_TYPE_ISSUE_ID', 4);
define('SELL_TYPE_REC_ID', 5);

//Sell No For
define('SELL_NO_FOR_GENERAL_NO_ID', 1);
define('SELL_NO_FOR_ONLY_PURCHASE_ID', 2);
define('SELL_NO_FOR_ONLY_SELL_ID', 3);
define('SELL_NO_FOR_ONLY_PAYMENT_RECEIPT_ID', 4);
define('SELL_NO_FOR_ONLY_METAL_ISSUE_RECEIVE_ID', 5);

// Tunch used table name for Restrict Tunch Master
define('TUNCH_USED_TABLE', 'order_lot_item>>touch_id,sell_items>>touch_id,stock_transfer_detail>>tunch,opening_stock>>tunch,issue_receive_details>>tunch');

// Store All Monthly Interest in Journal : Journal detail table row narration
define('STORE_INTEREST_NARRATION', 'Monthly Interest');

$http_host = explode(':', $_SERVER['HTTP_HOST']);
$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

$_SERVER['REQUEST_SCHEME'] = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : $https;
define('SERVER_REQUEST_SCHEME', $_SERVER["REQUEST_SCHEME"]);
define('HTTP_HOST', $http_host[0]);
// Port Number For Node server
define('PORT_NUMBER', 3035);

//Issue Receive Type ID
define('MANUFACTURE_TYPE_ISSUE_ID', 1);
define('MANUFACTURE_TYPE_RECEIVE_ID', 2);

//Manu. Hand made Type ID
define('MANUFACTURE_HM_TYPE_ISSUE_FINISH_WORK_ID', 1);
define('MANUFACTURE_HM_TYPE_ISSUE_SCRAP_ID', 2);
define('MANUFACTURE_HM_TYPE_RECEIVE_FINISH_WORK_ID', 3);
define('MANUFACTURE_HM_TYPE_RECEIVE_SCRAP_ID', 4);

//Machine Chain Type ID
define('MACHINE_CHAIN_TYPE_ISSUE_FINISH_WORK_ID', 1);
define('MACHINE_CHAIN_TYPE_ISSUE_SCRAP_ID', 2);
define('MACHINE_CHAIN_TYPE_RECEIVE_FINISH_WORK_ID', 3);
define('MACHINE_CHAIN_TYPE_RECEIVE_SCRAP_ID', 4);

define('MANUFACTURE_HM_OPERATION_MEENA_ID', 1);
define('MANUFACTURE_HM_OPERATION_NANG_SETTING_ID', 2);

define('MACHINE_CHAIN_OPERATION_SOLDING_ID', 15);
define('MCO_SOLDING_CURB_ID', 1);
define('MCO_SOLDING_CURB_DEFAULT_VAL', 0.2);
define('MCO_SOLDING_BOX_ID', 2);
define('MCO_SOLDING_BOX_DEFAULT_VAL', 0.7);

//Casting Entry Type ID
define('CASTING_ENTRY_TYPE_ISSUE_FINISH_WORK_ID', 1);
define('CASTING_ENTRY_TYPE_ISSUE_SCRAP_ID', 2);
define('CASTING_ENTRY_TYPE_RECEIVE_FINISH_WORK_ID', 3);
define('CASTING_ENTRY_TYPE_RECEIVE_SCRAP_ID', 4);

define('HISAB_DONE_IS_MODULE_MIR', 1);
define('HISAB_DONE_IS_MODULE_MHM', 2);
define('HISAB_DONE_IS_MODULE_MC', 3);
define('HISAB_DONE_IS_MODULE_MIR_SILVER', 4);
define('HISAB_DONE_IS_MODULE_CASTING', 5);

//Stock Method
define('STOCK_METHOD_DEFAULT', 1);
define('STOCK_METHOD_ITEM_WISE', 2);
define('STOCK_METHOD_COMBINE', 3);

define('ZERO_VALUE', 0);

//Other Sell Type ID
define('OTHER_TYPE_SELL_ID', 1);
define('OTHER_TYPE_PURCHASE_ID', 2);

// Sell/Purchase if Sell Type Exchange that time Default need to select Category and Item ID
define('EXCHANGE_DEFAULT_CATEGORY_ID', 32);
define('EXCHANGE_DEFAULT_ITEM_ID', 420);

// Sell/Purchase in Matel Payment Receipt Popup Default need to select Item ID
define('METAL_PAYMENT_RECEIPT_DEFAULT_ITEM_ID', 406);

//Stock Type ID : Increase by
define('STOCK_TYPE_PURCHASE_ID', 1);
define('STOCK_TYPE_EXCHANGE_ID', 2);
define('STOCK_TYPE_STOCK_TRANSFER_ID', 3);
define('STOCK_TYPE_IR_RECEIVE_ID', 4);
define('STOCK_TYPE_MHM_RECEIVE_FINISH_ID', 5);
define('STOCK_TYPE_MHM_RECEIVE_SCRAP_ID', 6);
define('STOCK_TYPE_MC_RECEIVE_FINISH_ID', 7);
define('STOCK_TYPE_MC_RECEIVE_SCRAP_ID', 8);
define('STOCK_TYPE_CASTING_ENTRY_RECEIVE_FINISH_ID', 9);
define('STOCK_TYPE_CASTING_ENTRY_RECEIVE_SCRAP_ID', 10);
define('STOCK_TYPE_OPENING_STOCK_ID', 11);

// Journal entry from other module ids
define('MHM_TO_JOURNAL_ID', 1);
define('EMPLOYEE_SALARY_TO_JOURNAL_ID', 2);
define('IR_TO_JOURNAL_ID', 3);

// Audit Status Values
define('AUDIT_STATUS_PENDING', 1);
define('AUDIT_STATUS_AUDITED', 2);
define('AUDIT_STATUS_SUSPECTED', 3);

// Audit Status Values
define('GOLD_FINE_CATEGORY_ID',27);
define('SILVER_FINE_CATEGORY_ID',28);
define('GOLD_FINE_ITEM_ID',406);
define('SILVER_FINE_ITEM_ID',407);

// Not Approved	Account Group
define('NOT_APPROVED_ACCOUNT_GROUP_ID',55);

//GST
define('HOME_STATE_ID',2); //Rajasthan
define('CGST_PER',9);
define('SGST_PER',9);
define('IGST_PER',18);

define('ALLOW_ALL_ACCOUNTS',1);
define('ALLOW_ONLY_SELECTED_ACCOUNTS',2);

// Manufacture Status //
define('MANUFACTURE_STATUS_NOT_STARTED',1);
define('MANUFACTURE_STATUS_IN_CAD',2);
define('MANUFACTURE_STATUS_DESIGN_READY',3);

// RFID Relation From/To Module number
define('RFID_RELATION_MODULE_RFID_CREATE', 1);
define('RFID_RELATION_MODULE_SELL', 2);
define('RFID_RELATION_MODULE_STOCK_TRANSFER', 3);
define('RFID_RELATION_MODULE_SELL_DELETE', 4);
define('RFID_RELATION_MODULE_STOCK_TRANSFER_DELETE', 5);

// pro_modules 'yes' means allow to get rights, and 'no' means not allow to display checkbox in User Rights
define('PRO_MODULE_HALLMARK', 'no');
define('PRO_MODULE_DEMOMENU', 'no');
define('PRO_MODULE_TRADING_PL', 'no');
define('PRO_MODULE_CALCULATIONS_REPORT', 'no');
define('PRO_MODULE_MANUFACTURE_MACHINE_CHAIN', 'no');
define('PRO_MODULE_MANUFACTURE_CASTING', 'no');

define('PRO_MODULE_ORDER', 'yes');

define('PRO_MODULE_SELL_PURCHASE_MENU', 'yes');
define('PRO_MODULE_SELL_PURCHASE', 'yes');
define('PRO_MODULE_SELL_PURCHASE_OTHER', 'yes');

define('ALLOW_SELL_PURCHASE_TYPE_2', 'no');
define('ALLOW_SELL_PURCHASE_TYPE_3', 'no');
define('ALLOW_INVENTORY_DATA_MODULES', 'yes');

define('PRO_MODULE_STOCK_TRANSFER', 'yes');
define('PRO_MODULE_JOURNAL', 'yes');
define('PRO_MODULE_CASHBOOK', 'yes');
define('PRO_MODULE_IMPORT_DATA', 'yes');

define('PRO_MODULE_HR', 'yes');

define('PRO_MODULE_MANUFACTURE_MENU', 'yes');
define('PRO_MODULE_MANUFACTURE_ISSUE_RECEIVE', 'yes');
define('PRO_MODULE_MANUFACTURE_ISSUE_RECEIVE_SILVER', 'yes');
define('PRO_MODULE_OPERATION', 'yes');
define('PRO_MODULE_MANU_HAND_MADE', 'yes');

define('PRO_MODULE_BACKUP', 'yes');

// How many User Allow to Login at a time
define('ALLOW_TO_LOGIN_AT_A_TIME', 'all'); // 'all' means All user allow, '1' means One user allow, '2' means Two user allow,...
