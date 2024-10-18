-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 13, 2020 at 06:26 AM
-- Server version: 5.7.29-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-23+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gurulog`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_group_log`
--

CREATE TABLE `account_group_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `account_group_id` int(11) NOT NULL,
  `parent_group_id` int(11) DEFAULT NULL COMMENT '0 = Is Parent',
  `account_group_name` varchar(255) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `is_display_in_balance_sheet` tinyint(1) NOT NULL DEFAULT '1',
  `use_in_profit_loss` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not use in profit loss, 1 = use in profit loss',
  `move_data_opening_zero` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Deletable, 0 = Not Deletable',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `account_log`
--

CREATE TABLE `account_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `account_name` varchar(222) DEFAULT NULL,
  `account_phone` varchar(222) DEFAULT NULL,
  `account_mobile` varchar(255) DEFAULT NULL,
  `account_email_ids` varchar(222) DEFAULT NULL,
  `account_address` text,
  `account_state` int(11) DEFAULT NULL,
  `account_city` int(11) DEFAULT NULL,
  `account_postal_code` varchar(50) DEFAULT NULL,
  `account_gst_no` varchar(222) DEFAULT NULL,
  `account_pan` varchar(22) DEFAULT NULL,
  `account_aadhaar` varchar(22) DEFAULT NULL,
  `account_contect_person_name` varchar(222) DEFAULT NULL,
  `account_group_id` int(11) DEFAULT NULL,
  `opening_balance` double DEFAULT NULL,
  `interest` double DEFAULT NULL,
  `credit_debit` tinyint(1) DEFAULT NULL,
  `opening_balance_in_gold` double DEFAULT NULL,
  `gold_ob_credit_debit` tinyint(1) DEFAULT NULL,
  `opening_balance_in_silver` double DEFAULT NULL,
  `silver_ob_credit_debit` tinyint(1) DEFAULT NULL,
  `opening_balance_in_rupees` double DEFAULT NULL,
  `rupees_ob_credit_debit` tinyint(1) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `bank_interest` double DEFAULT NULL,
  `gold_fine` double DEFAULT '0',
  `silver_fine` double DEFAULT '0',
  `amount` double DEFAULT '0',
  `credit_limit` double DEFAULT '0',
  `balance_date` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 = Approved, 2 = Not Approved',
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `is_supplier` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Supplier, 1 = Supplier',
  `password` varchar(255) DEFAULT NULL,
  `min_price` double DEFAULT NULL,
  `chhijjat_per_100_ad` double DEFAULT '0',
  `meena_charges` double DEFAULT '0',
  `price_per_pcs` double DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not Active, 1 = Active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ad_log`
--

CREATE TABLE `ad_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `ad_name` varchar(255) DEFAULT NULL,
  `ad_description` text,
  `is_nang_setting` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_sell_purchase_ad_charges` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_sell_purchase_less_ad_details` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `carat_log`
--

CREATE TABLE `carat_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `carat_id` int(11) NOT NULL,
  `purity` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry_design_files_log`
--

CREATE TABLE `casting_entry_design_files_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `design_file_id` int(11) DEFAULT NULL,
  `ce_id` int(11) DEFAULT NULL,
  `design_file_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry_details_log`
--

CREATE TABLE `casting_entry_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ce_detail_id` int(11) DEFAULT NULL,
  `ce_id` int(11) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Issue Finish Work, 2 = Issue Scrap, 3 = Receive Finish Work, 4 = Receive Scrap',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `actual_tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `pcs` double DEFAULT NULL,
  `ad_weight` double DEFAULT NULL,
  `ad_pcs` double DEFAULT NULL,
  `ce_detail_date` date DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `ce_detail_remark` text,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive, 5 = Receive Finish, 6 = Receive Scrap',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry_log`
--

CREATE TABLE `casting_entry_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ce_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `from_casting_status_id` int(11) DEFAULT NULL,
  `to_casting_status_id` int(11) DEFAULT NULL,
  `cad_worker_id` int(11) DEFAULT NULL,
  `ce_date` date DEFAULT NULL,
  `reference_no` int(11) DEFAULT NULL,
  `lott_complete` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `hisab_done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `ce_remark` text,
  `total_issue_net_wt` double DEFAULT NULL,
  `total_receive_net_wt` double DEFAULT NULL,
  `total_issue_fine` double DEFAULT NULL,
  `total_receive_fine` double DEFAULT NULL,
  `design_files` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry_order_items_log`
--

CREATE TABLE `casting_entry_order_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ce_oi_id` int(11) DEFAULT NULL,
  `ce_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_lot_item_id` int(11) DEFAULT NULL,
  `is_ahead` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category_group_log`
--

CREATE TABLE `category_group_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `category_group_id` int(11) NOT NULL,
  `category_group_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category_log`
--

CREATE TABLE `category_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_group_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city_log`
--

CREATE TABLE `city_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `city_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_name` varchar(222) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `design_master_log`
--

CREATE TABLE `design_master_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `design_id` int(11) NOT NULL,
  `design_no` varchar(255) DEFAULT NULL,
  `file_no` varchar(255) DEFAULT NULL,
  `stl_3dm_no` varchar(255) DEFAULT NULL,
  `die_making` varchar(255) DEFAULT NULL,
  `die_no` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_log`
--

CREATE TABLE `employee_salary_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `employee_salary_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `month_year` varchar(200) DEFAULT NULL,
  `worked_days` int(11) NOT NULL,
  `monthly_salary` double(20,2) NOT NULL,
  `salary_calculated` double(20,2) NOT NULL,
  `give_salary` double(20,2) NOT NULL,
  `leaves` int(11) NOT NULL,
  `journal_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_log`
--

CREATE TABLE `feedback_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `feedback_id` int(11) NOT NULL,
  `assign_id` int(11) DEFAULT NULL,
  `feedback_date` date DEFAULT NULL,
  `note` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gold_bhav_log`
--

CREATE TABLE `gold_bhav_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `gold_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `gold_sale_purchase` tinyint(1) DEFAULT NULL COMMENT '1 = Sale, 2 = Purchase',
  `gold_weight` double DEFAULT NULL,
  `gold_rate` double DEFAULT NULL,
  `gold_value` double DEFAULT NULL,
  `gold_narration` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_item_master_log`
--

CREATE TABLE `hallmark_item_master_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_receipt_details_log`
--

CREATE TABLE `hallmark_receipt_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `rd_id` int(11) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `receipt_weight` double DEFAULT NULL,
  `purity` double DEFAULT NULL,
  `box_no` double DEFAULT NULL,
  `pcs` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_receipt_log`
--

CREATE TABLE `hallmark_receipt_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_time` time DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `metal_id` int(11) DEFAULT NULL,
  `party_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_xrf_items_log`
--

CREATE TABLE `hallmark_xrf_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `xrf_item_id` int(11) DEFAULT NULL,
  `xrf_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `purity` int(11) DEFAULT NULL COMMENT 'carat_id',
  `rec_qty` double DEFAULT NULL,
  `price_per_pcs` double DEFAULT NULL,
  `item_amount` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `remark` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_xrf_log`
--

CREATE TABLE `hallmark_xrf_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `xrf_id` int(11) DEFAULT NULL,
  `posting_date` date DEFAULT NULL,
  `receipt_no` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1=Active',
  `receipt_time` time DEFAULT NULL,
  `taken_by_same` tinyint(1) DEFAULT NULL COMMENT '1-Yes, 0-No',
  `taken_by_name` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `box_no` double DEFAULT NULL,
  `min_price` double DEFAULT NULL,
  `price_per_pcs` double DEFAULT NULL,
  `total_item_amount` double DEFAULT NULL,
  `cgst_per` double DEFAULT NULL,
  `cgst_amount` double DEFAULT NULL,
  `sgst_per` double DEFAULT NULL,
  `sgst_amount` double DEFAULT NULL,
  `igst_per` double DEFAULT NULL,
  `igst_amount` double DEFAULT NULL,
  `other_charges` double DEFAULT NULL,
  `advance_rec_amount` double DEFAULT NULL,
  `pending_amount` double DEFAULT NULL,
  `remark` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hr_apply_leave_log`
--

CREATE TABLE `hr_apply_leave_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `apply_leave_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `reason` text,
  `status` tinyint(4) DEFAULT '0' COMMENT '0 = Pending, 1 = Approved, 2 = Disapproved',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hr_attendance_log`
--

CREATE TABLE `hr_attendance_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `attendance_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `attendance_time` time DEFAULT NULL,
  `is_in_out` tinyint(1) DEFAULT NULL COMMENT '1=IN, 2=OUT',
  `is_out_for_office` tinyint(1) DEFAULT NULL COMMENT '1=Yes, 2=No',
  `is_cron_entry` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hr_present_hours_log`
--

CREATE TABLE `hr_present_hours_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `present_hour_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `present_date` date DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `no_of_hours` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hr_yearly_leave_log`
--

CREATE TABLE `hr_yearly_leave_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `leave_id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue_receive_details_log`
--

CREATE TABLE `issue_receive_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ird_id` int(11) NOT NULL,
  `ir_id` int(11) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Issue 2 = Receive',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `actual_tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `ird_date` date DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `ird_remark` text,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue_receive_log`
--

CREATE TABLE `issue_receive_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ir_id` int(11) NOT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `ir_date` date DEFAULT NULL,
  `reference_no` int(11) DEFAULT NULL,
  `lott_complete` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `hisab_done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `ir_remark` text,
  `total_issue_net_wt` double DEFAULT NULL,
  `total_receive_net_wt` double DEFAULT NULL,
  `total_issue_fine` double DEFAULT NULL,
  `total_receive_fine` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue_receive_silver_details_log`
--

CREATE TABLE `issue_receive_silver_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `irsd_id` int(11) DEFAULT NULL,
  `irs_id` int(11) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Issue 2 = Receive',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `actual_tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `irsd_date` date DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `irsd_remark` text,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue_receive_silver_log`
--

CREATE TABLE `issue_receive_silver_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `irs_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `irs_date` date DEFAULT NULL,
  `reference_no` int(11) DEFAULT NULL,
  `lott_complete` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `hisab_done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `irs_remark` text,
  `total_issue_net_wt` double DEFAULT NULL,
  `total_receive_net_wt` double DEFAULT NULL,
  `total_issue_fine` double DEFAULT NULL,
  `total_receive_fine` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_master_log`
--

CREATE TABLE `item_master_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `short_item` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `die_no` varchar(255) DEFAULT NULL,
  `design_no` varchar(255) DEFAULT NULL,
  `min_order_qty` double DEFAULT NULL,
  `default_wastage` double DEFAULT NULL,
  `st_default_wastage` double DEFAULT NULL,
  `less` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `display_item_in` varchar(255) DEFAULT NULL,
  `stock_method` tinyint(1) DEFAULT NULL COMMENT '1 = Default, 2 = Item Wise, 3 = Combine',
  `metal_payment_receipt` tinyint(1) DEFAULT '0' COMMENT '1 = Yes, 0 = No',
  `sequence_no` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_stock_log`
--

CREATE TABLE `item_stock_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `item_stock_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `ntwt` double DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `purchase_sell_item_id` int(11) DEFAULT NULL,
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_stock_rfid_log`
--

CREATE TABLE `item_stock_rfid_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `item_stock_rfid_id` int(11) NOT NULL,
  `item_stock_id` int(11) DEFAULT NULL,
  `rfid_grwt` double NOT NULL DEFAULT '0',
  `rfid_less` double NOT NULL DEFAULT '0',
  `rfid_add` double DEFAULT '0',
  `rfid_ntwt` double NOT NULL DEFAULT '0',
  `rfid_tunch` double NOT NULL DEFAULT '0',
  `rfid_fine` double NOT NULL DEFAULT '0',
  `real_rfid` varchar(255) DEFAULT NULL,
  `rfid_charges` double DEFAULT '0',
  `rfid_ad_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `journal_details_log`
--

CREATE TABLE `journal_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `jd_id` int(11) NOT NULL,
  `journal_id` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1=naam, 2=jama',
  `account_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `narration` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `journal_log`
--

CREATE TABLE `journal_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `journal_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `journal_date` date DEFAULT NULL,
  `interest_account_id` int(11) DEFAULT NULL COMMENT 'Monthly Interest of this Account',
  `gold_rate` double DEFAULT NULL,
  `silver_rate` double DEFAULT NULL,
  `interest_rate` double DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `is_module` int(10) DEFAULT NULL COMMENT '1 = Manufacture Hand Made, 2=Employee Salary',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_details_log`
--

CREATE TABLE `machine_chain_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `machine_chain_detail_id` int(11) DEFAULT NULL,
  `machine_chain_id` int(11) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Issue Finish Work, 2 = Issue Scrap, 3 = Receive Finish Work, 4 = Receive Scrap',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `actual_tunch` double DEFAULT NULL,
  `real_actual_tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `pcs` double DEFAULT NULL,
  `machine_chain_detail_date` date DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `machine_chain_detail_remark` text,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive, 5 = Receive Finish, 6 = Receive Scrap, 7 = MC Receive Finish, 8 = MC Receive Scrap',
  `is_forwarded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `forwarded_from_mcd_id` int(11) DEFAULT NULL,
  `added_from_ifw_mcd_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_detail_order_items_log`
--

CREATE TABLE `machine_chain_detail_order_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `machine_chain_detail_oi_id` int(11) DEFAULT NULL,
  `machine_chain_id` int(11) DEFAULT NULL,
  `machine_chain_detail_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_lot_item_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_log`
--

CREATE TABLE `machine_chain_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `machine_chain_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `machine_chain_date` date DEFAULT NULL,
  `reference_no` int(11) DEFAULT NULL,
  `lott_complete` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `curb_box` tinyint(4) DEFAULT NULL COMMENT '1=Curb, 2=Box',
  `hisab_done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `machine_chain_remark` text,
  `total_receive_fw_weight` double DEFAULT NULL,
  `total_issue_weight` double DEFAULT NULL,
  `total_receive_weight` double DEFAULT NULL,
  `total_issue_net_wt` double DEFAULT NULL,
  `total_receive_net_wt` double DEFAULT NULL,
  `total_issue_fine` double DEFAULT NULL,
  `total_receive_fine` double DEFAULT NULL,
  `is_calculated` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `is_forwarded` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `forwarded_from_mc_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_operation_department_log`
--

CREATE TABLE `machine_chain_operation_department_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `od_id` int(11) DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_operation_log`
--

CREATE TABLE `machine_chain_operation_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `operation_name` varchar(255) DEFAULT NULL,
  `sequence_no` int(11) DEFAULT NULL,
  `allow_only_1_order_item` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `direct_issue_allow` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `calculate_button` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `use_selected_tunch` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `issue_change_actual_tunch_allow` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `receive_change_actual_tunch_allow` tinyint(1) DEFAULT '0' COMMENT '1-Yes, 0-No',
  `remark` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_operation_worker_log`
--

CREATE TABLE `machine_chain_operation_worker_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ow_id` int(11) DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `machine_chain_order_items_log`
--

CREATE TABLE `machine_chain_order_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `machine_chain_oi_id` int(11) DEFAULT NULL,
  `machine_chain_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_lot_item_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manufacture_status_log`
--

CREATE TABLE `manufacture_status_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `manufacture_status_id` int(11) DEFAULT NULL,
  `manufacture_status_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manu_hand_made_ads_log`
--

CREATE TABLE `manu_hand_made_ads_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `mhm_ad_id` int(11) NOT NULL,
  `mhm_id` int(11) NOT NULL DEFAULT '0',
  `ad_id` int(11) NOT NULL DEFAULT '0',
  `ad_pcs` int(11) NOT NULL DEFAULT '0',
  `ad_rate` double NOT NULL DEFAULT '0',
  `ad_amount` double NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manu_hand_made_details_log`
--

CREATE TABLE `manu_hand_made_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `mhm_detail_id` int(11) NOT NULL,
  `mhm_id` int(11) DEFAULT NULL,
  `type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Issue Finish Work, 2 = Issue Scrap, 3 = Receive Finish Work, 4 = Receive Scrap',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `actual_tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `pcs` double DEFAULT NULL,
  `ad_weight` double DEFAULT NULL,
  `including_ad_wt` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Including, 1 = Including',
  `mhm_detail_date` date DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `mhm_detail_remark` text,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive, 5 = Receive Finish, 6 = Receive Scrap',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manu_hand_made_log`
--

CREATE TABLE `manu_hand_made_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `mhm_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `mhm_date` date DEFAULT NULL,
  `reference_no` int(11) DEFAULT NULL,
  `lott_complete` tinyint(1) DEFAULT NULL COMMENT '0 = No, 1 = Yes',
  `hisab_done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `mhm_diffrence` double DEFAULT NULL,
  `worker_gold_rate` double DEFAULT NULL,
  `mhm_remark` text,
  `total_issue_net_wt` double DEFAULT NULL,
  `total_receive_net_wt` double DEFAULT NULL,
  `total_issue_fine` double DEFAULT NULL,
  `total_receive_fine` double DEFAULT NULL,
  `journal_id` int(11) DEFAULT NULL COMMENT 'Lott Complete to diff. fine amount Worker <> MF Loss journal_id',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manu_hand_made_order_items_log`
--

CREATE TABLE `manu_hand_made_order_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `mhm_oi_id` int(11) NOT NULL,
  `mhm_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_lot_item_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `metal_payment_receipt_log`
--

CREATE TABLE `metal_payment_receipt_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `metal_pr_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `metal_payment_receipt` tinyint(1) DEFAULT NULL COMMENT '1 = Payment, 2 = Receive',
  `metal_category_id` int(11) DEFAULT NULL,
  `metal_item_id` int(11) DEFAULT NULL,
  `metal_grwt` double DEFAULT NULL,
  `metal_ntwt` double DEFAULT NULL,
  `metal_tunch` double DEFAULT NULL,
  `metal_fine` double DEFAULT NULL,
  `metal_narration` text,
  `total_gold_fine` double DEFAULT NULL,
  `total_silver_fine` double DEFAULT NULL,
  `total_other_fine` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module_roles_log`
--

CREATE TABLE `module_roles_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `module_role_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `new_order_log`
--

CREATE TABLE `new_order_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `real_delivery_date` date DEFAULT NULL,
  `party_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL COMMENT 'acoount_id from account table',
  `supplier_delivery_date` date DEFAULT NULL,
  `gold_price` int(11) DEFAULT NULL,
  `silver_price` int(11) DEFAULT NULL,
  `remark` text,
  `reason` text,
  `total_weight` double DEFAULT NULL,
  `total_pcs` double DEFAULT NULL,
  `order_status_id` int(11) DEFAULT '1',
  `order_type` tinyint(1) DEFAULT '1' COMMENT '1 = Order, 2 = Inquiry',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `opening_stock_log`
--

CREATE TABLE `opening_stock_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `opening_stock_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `ntwt` double DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation_department_log`
--

CREATE TABLE `operation_department_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `od_id` int(11) NOT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation_log`
--

CREATE TABLE `operation_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `operation_id` int(11) NOT NULL,
  `operation_name` varchar(255) DEFAULT NULL,
  `fix_loss` tinyint(1) DEFAULT NULL COMMENT 'Comment 0 = No, 1 = Yes',
  `fix_loss_per` double DEFAULT NULL,
  `max_loss_allow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not allow, 1 = Allow',
  `max_loss_wt` double DEFAULT NULL,
  `issue_finish_fix_loss` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes',
  `issue_finish_fix_loss_per` double DEFAULT NULL,
  `remark` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operation_worker_log`
--

CREATE TABLE `operation_worker_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ow_id` int(11) NOT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_lot_item_log`
--

CREATE TABLE `order_lot_item_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `order_lot_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_item_no` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `touch_id` int(11) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `length` varchar(255) DEFAULT NULL,
  `hook_style` varchar(255) DEFAULT NULL,
  `item_status_id` int(11) NOT NULL DEFAULT '1',
  `image` varchar(255) DEFAULT NULL,
  `lot_remark` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_log`
--

CREATE TABLE `order_status_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `order_status_id` int(11) NOT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `other_items_log`
--

CREATE TABLE `other_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `other_item_id` int(11) NOT NULL,
  `other_id` int(11) DEFAULT NULL,
  `other_item_no` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1 = Sell, 2 = Purchase',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `other_log`
--

CREATE TABLE `other_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `other_id` int(11) NOT NULL,
  `other_no` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `other_date` date DEFAULT NULL,
  `other_remark` varchar(255) DEFAULT NULL,
  `total_grwt` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `party_item_details_log`
--

CREATE TABLE `party_item_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `party_item_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `wstg` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_receipt_log`
--

CREATE TABLE `payment_receipt_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `pay_rec_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `other_id` int(11) DEFAULT NULL,
  `payment_receipt` tinyint(1) DEFAULT NULL COMMENT '1 = Payment, 2 = Rexeipt',
  `cash_cheque` tinyint(1) DEFAULT NULL COMMENT '1= Case, 2 = Cheque',
  `bank_id` int(11) DEFAULT NULL,
  `voucher_no` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `on_behalf_of` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `narration` text,
  `is_payment_received` tinyint(1) DEFAULT '0' COMMENT 'is_received =1,not_received =0',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `process_master_log`
--

CREATE TABLE `process_master_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `process_id` int(11) NOT NULL,
  `process_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reminder_log`
--

CREATE TABLE `reminder_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `reminder_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `debit_credit` tinyint(1) DEFAULT NULL COMMENT '1 = Debit, 2 = Credit',
  `amount` double DEFAULT NULL,
  `gold` double DEFAULT NULL,
  `silver` double DEFAULT NULL,
  `remarks` text,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reply_log`
--

CREATE TABLE `reply_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `reply_id` int(11) NOT NULL,
  `feedback_id` int(11) DEFAULT NULL,
  `assign_to_id` int(11) DEFAULT NULL,
  `reply_date` date DEFAULT NULL,
  `reply` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sell_ad_charges_log`
--

CREATE TABLE `sell_ad_charges_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `sell_ad_charges_id` int(11) NOT NULL,
  `sell_id` int(11) NOT NULL DEFAULT '0',
  `ad_id` int(11) NOT NULL DEFAULT '0',
  `ad_pcs` double NOT NULL DEFAULT '0',
  `ad_rate` double NOT NULL DEFAULT '0',
  `ad_amount` double NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sell_items_log`
--

CREATE TABLE `sell_items_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `sell_item_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `sell_item_no` int(11) DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Sell, 2=Purchase, 3=Exchange',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `touch_id` double DEFAULT NULL,
  `wstg` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order_lot_item_id` int(11) DEFAULT NULL COMMENT 'Order to Sell : order_lot_item_id',
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `wastage_change_approve` varchar(100) DEFAULT '0_0' COMMENT '0_0 = Default Wastage Only, 1_0 = Only Pending Approve Diff Wastage, 1_1 = Approved Diff Wastage',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sell_less_ad_details_log`
--

CREATE TABLE `sell_less_ad_details_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `sell_less_ad_details_id` int(11) DEFAULT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `sell_item_id` int(11) DEFAULT NULL,
  `less_ad_details_ad_id` int(11) DEFAULT NULL,
  `less_ad_details_ad_pcs` double DEFAULT NULL,
  `less_ad_details_ad_weight` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sell_log`
--

CREATE TABLE `sell_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `sell_id` int(11) NOT NULL,
  `sell_no` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `sell_date` date DEFAULT NULL,
  `sell_remark` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'Order to Sell : order_id',
  `total_gold_fine` double DEFAULT NULL,
  `total_silver_fine` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `delivery_type` tinyint(1) DEFAULT '1' COMMENT '1 = Delivered, 2 = Not Delivered',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sell_type_log`
--

CREATE TABLE `sell_type_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `sell_type_id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings_log`
--

CREATE TABLE `settings_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `settings_id` int(11) NOT NULL,
  `settings_key` varchar(255) DEFAULT NULL,
  `settings_label` varchar(255) DEFAULT NULL,
  `settings_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting_mac_address_log`
--

CREATE TABLE `setting_mac_address_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `setting_mac_address_id` int(11) NOT NULL,
  `mac_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `silver_bhav_log`
--

CREATE TABLE `silver_bhav_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `silver_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `silver_sale_purchase` tinyint(1) DEFAULT NULL COMMENT '1 = Sale, 2 = Purchase',
  `silver_weight` double DEFAULT NULL,
  `silver_rate` double DEFAULT NULL,
  `silver_value` double DEFAULT NULL,
  `silver_narration` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `state_log`
--

CREATE TABLE `state_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `state_name` varchar(222) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_detail_log`
--

CREATE TABLE `stock_transfer_detail_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `transfer_detail_id` int(11) NOT NULL,
  `stock_transfer_id` int(11) DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `tunch` double DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `ntwt` double DEFAULT NULL,
  `wstg` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_log`
--

CREATE TABLE `stock_transfer_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `stock_transfer_id` int(11) NOT NULL,
  `transfer_date` date DEFAULT NULL,
  `from_department` int(11) DEFAULT NULL,
  `to_department` int(11) DEFAULT NULL,
  `narration` text,
  `total_gold_fine` double NOT NULL DEFAULT '0',
  `total_silver_fine` double NOT NULL DEFAULT '0',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `guard_checked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Checked, 1 = Checked',
  `guard_checked_narration` text,
  `guard_checked_first_at` datetime DEFAULT NULL,
  `guard_checked_last_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_log`
--

CREATE TABLE `transfer_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `transfer_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `naam_jama` tinyint(1) DEFAULT NULL COMMENT '1 = Naam, 2 = Jama',
  `transfer_account_id` int(11) DEFAULT NULL,
  `transfer_gold` double DEFAULT NULL,
  `transfer_silver` double DEFAULT NULL,
  `transfer_amount` double DEFAULT NULL,
  `transfer_narration` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `twilio_webhook_demo_log`
--

CREATE TABLE `twilio_webhook_demo_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ai_id` int(11) DEFAULT NULL,
  `webhook_type` varchar(255) DEFAULT NULL,
  `webhook_content` text CHARACTER SET utf8,
  `message_from` varchar(100) DEFAULT NULL,
  `message_to` varchar(100) DEFAULT NULL,
  `message_body` text CHARACTER SET utf8,
  `message_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_account_group_log`
--

CREATE TABLE `user_account_group_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ud_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_group_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_department_log`
--

CREATE TABLE `user_department_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ud_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_family_member_log`
--

CREATE TABLE `user_family_member_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `fm_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `member_name` varchar(255) DEFAULT NULL,
  `member_phone_no` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_master_log`
--

CREATE TABLE `user_master_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `login_username` varchar(255) DEFAULT NULL,
  `user_mobile` varchar(15) DEFAULT NULL,
  `user_type` tinyint(1) DEFAULT NULL COMMENT '1 = Admin, 2 = User',
  `is_cad_designer` tinyint(1) NOT NULL DEFAULT '0',
  `default_department_id` int(11) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `salary` double DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `allow_all_accounts` tinyint(1) DEFAULT '1' COMMENT '1=Allow All, 2=Allow Only Selected',
  `selected_accounts` text,
  `files` text,
  `default_user_photo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT 'Active=0; Inactive = 1;',
  `is_login` tinyint(1) DEFAULT '0' COMMENT '1 = login, 0 = not login',
  `socket_id` varchar(255) DEFAULT NULL,
  `otp_value` varchar(255) DEFAULT NULL,
  `otp_on_user` tinyint(1) DEFAULT NULL COMMENT '1=Yes;0=No',
  `designation` varchar(255) DEFAULT NULL,
  `aadhaar_no` varchar(100) DEFAULT NULL,
  `pan_no` varchar(100) DEFAULT NULL,
  `licence_no` varchar(100) DEFAULT NULL,
  `voter_id_no` varchar(100) DEFAULT NULL,
  `esi_no` varchar(255) DEFAULT NULL,
  `pf_no` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `order_display_only_assigned_account` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = Not Checked, 1 = Checked',
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `bank_acc_name` varchar(255) DEFAULT NULL,
  `bank_acc_no` varchar(255) DEFAULT NULL,
  `bank_ifsc` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_order_department_log`
--

CREATE TABLE `user_order_department_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `ud_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles_log`
--

CREATE TABLE `user_roles_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL,
  `role_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_type_log`
--

CREATE TABLE `user_type_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website_modules_log`
--

CREATE TABLE `website_modules_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `website_module_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `main_module` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worker_entry_log`
--

CREATE TABLE `worker_entry_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `worker_entry_id` int(11) NOT NULL,
  `person_name` varchar(255) DEFAULT NULL,
  `process_id` int(111) NOT NULL,
  `salary` double DEFAULT NULL,
  `worker_type_id` int(11) DEFAULT NULL,
  `files` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worker_hisab_detail_log`
--

CREATE TABLE `worker_hisab_detail_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `wsd_id` int(11) NOT NULL,
  `worker_hisab_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `against_account_id` int(11) DEFAULT NULL COMMENT 'MF LOSS account_id',
  `relation_id` int(11) DEFAULT NULL,
  `is_module` int(10) DEFAULT NULL COMMENT '1 = Manufacture Issue/Receive, 2 = Manufacture Hand Made',
  `balance_fine` double DEFAULT NULL,
  `fine_adjusted` double DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL COMMENT '1 = Issue, 2 = Receive'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worker_hisab_log`
--

CREATE TABLE `worker_hisab_log` (
  `id` int(11) NOT NULL,
  `trigger_status` varchar(255) NOT NULL,
  `trigger_run_at` datetime DEFAULT NULL,
  `worker_hisab_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL COMMENT 'This Record will Not come in Stock/Ledger of Department : We are storing this ID just for reference',
  `worker_id` int(11) DEFAULT NULL,
  `against_account_id` int(11) DEFAULT NULL COMMENT 'MF LOSS account_id',
  `is_module` int(10) DEFAULT NULL COMMENT '1 = Manufacture Issue/Receive, 2 = Manufacture Hand Made, 3 = Manufacture Machin chain, 4 = Manufacture I/R Silver',
  `hisab_date` date DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `total_fine_adjusted` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_group_log`
--
ALTER TABLE `account_group_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_log`
--
ALTER TABLE `account_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_group_id` (`account_group_id`);

--
-- Indexes for table `ad_log`
--
ALTER TABLE `ad_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carat_log`
--
ALTER TABLE `carat_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `casting_entry_design_files_log`
--
ALTER TABLE `casting_entry_design_files_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `casting_entry_details_log`
--
ALTER TABLE `casting_entry_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `casting_entry_log`
--
ALTER TABLE `casting_entry_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `casting_entry_order_items_log`
--
ALTER TABLE `casting_entry_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_group_log`
--
ALTER TABLE `category_group_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_group_id` (`category_group_id`);

--
-- Indexes for table `category_log`
--
ALTER TABLE `category_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `city_log`
--
ALTER TABLE `city_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `design_master_log`
--
ALTER TABLE `design_master_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `design_id` (`design_id`);

--
-- Indexes for table `employee_salary_log`
--
ALTER TABLE `employee_salary_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_salary_id` (`employee_salary_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `account_id_2` (`account_id`);

--
-- Indexes for table `feedback_log`
--
ALTER TABLE `feedback_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_bhav_log`
--
ALTER TABLE `gold_bhav_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hallmark_item_master_log`
--
ALTER TABLE `hallmark_item_master_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hallmark_receipt_details_log`
--
ALTER TABLE `hallmark_receipt_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hallmark_receipt_log`
--
ALTER TABLE `hallmark_receipt_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hallmark_xrf_items_log`
--
ALTER TABLE `hallmark_xrf_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hallmark_xrf_log`
--
ALTER TABLE `hallmark_xrf_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_apply_leave_log`
--
ALTER TABLE `hr_apply_leave_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apply_leave_id` (`apply_leave_id`);

--
-- Indexes for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_present_hours_log`
--
ALTER TABLE `hr_present_hours_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `present_hour_id` (`present_hour_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `hr_yearly_leave_log`
--
ALTER TABLE `hr_yearly_leave_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_id` (`leave_id`);

--
-- Indexes for table `issue_receive_details_log`
--
ALTER TABLE `issue_receive_details_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `ird_id` (`ird_id`),
  ADD KEY `ir_id` (`ir_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `issue_receive_log`
--
ALTER TABLE `issue_receive_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `ir_id` (`ir_id`);

--
-- Indexes for table `issue_receive_silver_details_log`
--
ALTER TABLE `issue_receive_silver_details_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `issue_receive_silver_log`
--
ALTER TABLE `issue_receive_silver_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `item_master_log`
--
ALTER TABLE `item_master_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item_stock_log`
--
ALTER TABLE `item_stock_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_stock_id` (`item_stock_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item_stock_rfid_log`
--
ALTER TABLE `item_stock_rfid_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_details_log`
--
ALTER TABLE `journal_details_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `jd_id` (`jd_id`),
  ADD KEY `journal_id` (`journal_id`);

--
-- Indexes for table `journal_log`
--
ALTER TABLE `journal_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_id` (`journal_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `interest_account_id` (`interest_account_id`);

--
-- Indexes for table `machine_chain_details_log`
--
ALTER TABLE `machine_chain_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_chain_detail_order_items_log`
--
ALTER TABLE `machine_chain_detail_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_chain_log`
--
ALTER TABLE `machine_chain_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operation_id` (`operation_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `machine_chain_operation_department_log`
--
ALTER TABLE `machine_chain_operation_department_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_chain_operation_log`
--
ALTER TABLE `machine_chain_operation_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_chain_operation_worker_log`
--
ALTER TABLE `machine_chain_operation_worker_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine_chain_order_items_log`
--
ALTER TABLE `machine_chain_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacture_status_log`
--
ALTER TABLE `manufacture_status_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manu_hand_made_ads_log`
--
ALTER TABLE `manu_hand_made_ads_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manu_hand_made_details_log`
--
ALTER TABLE `manu_hand_made_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manu_hand_made_log`
--
ALTER TABLE `manu_hand_made_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operation_id` (`operation_id`);

--
-- Indexes for table `manu_hand_made_order_items_log`
--
ALTER TABLE `manu_hand_made_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `metal_payment_receipt_log`
--
ALTER TABLE `metal_payment_receipt_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metal_pr_id` (`metal_pr_id`),
  ADD KEY `sell_id` (`sell_id`),
  ADD KEY `sell_id_2` (`sell_id`),
  ADD KEY `metal_category_id` (`metal_category_id`),
  ADD KEY `metal_item_id` (`metal_item_id`);

--
-- Indexes for table `module_roles_log`
--
ALTER TABLE `module_roles_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_order_log`
--
ALTER TABLE `new_order_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_id` (`process_id`),
  ADD KEY `process_id_2` (`process_id`),
  ADD KEY `party_id` (`party_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_status_id` (`order_status_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `opening_stock_log`
--
ALTER TABLE `opening_stock_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `opening_stock_id` (`opening_stock_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `operation_department_log`
--
ALTER TABLE `operation_department_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_log`
--
ALTER TABLE `operation_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_worker_log`
--
ALTER TABLE `operation_worker_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `order_lot_item_log`
--
ALTER TABLE `order_lot_item_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `touch_id` (`touch_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `touch_id_2` (`touch_id`),
  ADD KEY `touch_id_3` (`touch_id`),
  ADD KEY `item_id_2` (`item_id`),
  ADD KEY `item_id_3` (`item_id`);

--
-- Indexes for table `order_status_log`
--
ALTER TABLE `order_status_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_items_log`
--
ALTER TABLE `other_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_log`
--
ALTER TABLE `other_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `party_item_details_log`
--
ALTER TABLE `party_item_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_receipt_log`
--
ALTER TABLE `payment_receipt_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `process_master_log`
--
ALTER TABLE `process_master_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminder_log`
--
ALTER TABLE `reminder_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply_log`
--
ALTER TABLE `reply_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_items_log`
--
ALTER TABLE `sell_items_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `purchase_sell_item_id` (`purchase_sell_item_id`),
  ADD KEY `order_lot_item_id` (`order_lot_item_id`);

--
-- Indexes for table `sell_less_ad_details_log`
--
ALTER TABLE `sell_less_ad_details_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_log`
--
ALTER TABLE `sell_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `process_id` (`process_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sell_type_log`
--
ALTER TABLE `sell_type_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_log`
--
ALTER TABLE `settings_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting_mac_address_log`
--
ALTER TABLE `setting_mac_address_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `silver_bhav_log`
--
ALTER TABLE `silver_bhav_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state_log`
--
ALTER TABLE `state_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transfer_detail_log`
--
ALTER TABLE `stock_transfer_detail_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `stock_transfer_log`
--
ALTER TABLE `stock_transfer_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_log`
--
ALTER TABLE `transfer_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `twilio_webhook_demo_log`
--
ALTER TABLE `twilio_webhook_demo_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account_group_log`
--
ALTER TABLE `user_account_group_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_department_log`
--
ALTER TABLE `user_department_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_family_member_log`
--
ALTER TABLE `user_family_member_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_master_log`
--
ALTER TABLE `user_master_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_order_department_log`
--
ALTER TABLE `user_order_department_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles_log`
--
ALTER TABLE `user_roles_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type_log`
--
ALTER TABLE `user_type_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_modules_log`
--
ALTER TABLE `website_modules_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_entry_log`
--
ALTER TABLE `worker_entry_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_hisab_detail_log`
--
ALTER TABLE `worker_hisab_detail_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_hisab_log`
--
ALTER TABLE `worker_hisab_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_group_log`
--
ALTER TABLE `account_group_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_log`
--
ALTER TABLE `account_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad_log`
--
ALTER TABLE `ad_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carat_log`
--
ALTER TABLE `carat_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_design_files_log`
--
ALTER TABLE `casting_entry_design_files_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_details_log`
--
ALTER TABLE `casting_entry_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_log`
--
ALTER TABLE `casting_entry_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_order_items_log`
--
ALTER TABLE `casting_entry_order_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_group_log`
--
ALTER TABLE `category_group_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_log`
--
ALTER TABLE `category_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_log`
--
ALTER TABLE `city_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `design_master_log`
--
ALTER TABLE `design_master_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary_log`
--
ALTER TABLE `employee_salary_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback_log`
--
ALTER TABLE `feedback_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gold_bhav_log`
--
ALTER TABLE `gold_bhav_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_item_master_log`
--
ALTER TABLE `hallmark_item_master_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_receipt_details_log`
--
ALTER TABLE `hallmark_receipt_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_receipt_log`
--
ALTER TABLE `hallmark_receipt_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_xrf_items_log`
--
ALTER TABLE `hallmark_xrf_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_xrf_log`
--
ALTER TABLE `hallmark_xrf_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_apply_leave_log`
--
ALTER TABLE `hr_apply_leave_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_attendance_log`
--
ALTER TABLE `hr_attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_present_hours_log`
--
ALTER TABLE `hr_present_hours_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_yearly_leave_log`
--
ALTER TABLE `hr_yearly_leave_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_details_log`
--
ALTER TABLE `issue_receive_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_log`
--
ALTER TABLE `issue_receive_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_silver_details_log`
--
ALTER TABLE `issue_receive_silver_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_silver_log`
--
ALTER TABLE `issue_receive_silver_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_master_log`
--
ALTER TABLE `item_master_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stock_log`
--
ALTER TABLE `item_stock_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stock_rfid_log`
--
ALTER TABLE `item_stock_rfid_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_details_log`
--
ALTER TABLE `journal_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_log`
--
ALTER TABLE `journal_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_details_log`
--
ALTER TABLE `machine_chain_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_detail_order_items_log`
--
ALTER TABLE `machine_chain_detail_order_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_log`
--
ALTER TABLE `machine_chain_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation_department_log`
--
ALTER TABLE `machine_chain_operation_department_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation_log`
--
ALTER TABLE `machine_chain_operation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation_worker_log`
--
ALTER TABLE `machine_chain_operation_worker_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_order_items_log`
--
ALTER TABLE `machine_chain_order_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacture_status_log`
--
ALTER TABLE `manufacture_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_ads_log`
--
ALTER TABLE `manu_hand_made_ads_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_details_log`
--
ALTER TABLE `manu_hand_made_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_log`
--
ALTER TABLE `manu_hand_made_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_order_items_log`
--
ALTER TABLE `manu_hand_made_order_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metal_payment_receipt_log`
--
ALTER TABLE `metal_payment_receipt_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module_roles_log`
--
ALTER TABLE `module_roles_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_order_log`
--
ALTER TABLE `new_order_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opening_stock_log`
--
ALTER TABLE `opening_stock_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_department_log`
--
ALTER TABLE `operation_department_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_log`
--
ALTER TABLE `operation_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_worker_log`
--
ALTER TABLE `operation_worker_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_lot_item_log`
--
ALTER TABLE `order_lot_item_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_log`
--
ALTER TABLE `order_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_items_log`
--
ALTER TABLE `other_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_log`
--
ALTER TABLE `other_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `party_item_details_log`
--
ALTER TABLE `party_item_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_receipt_log`
--
ALTER TABLE `payment_receipt_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process_master_log`
--
ALTER TABLE `process_master_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminder_log`
--
ALTER TABLE `reminder_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_log`
--
ALTER TABLE `reply_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_items_log`
--
ALTER TABLE `sell_items_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_less_ad_details_log`
--
ALTER TABLE `sell_less_ad_details_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_log`
--
ALTER TABLE `sell_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_type_log`
--
ALTER TABLE `sell_type_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_log`
--
ALTER TABLE `settings_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting_mac_address_log`
--
ALTER TABLE `setting_mac_address_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `silver_bhav_log`
--
ALTER TABLE `silver_bhav_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `state_log`
--
ALTER TABLE `state_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_detail_log`
--
ALTER TABLE `stock_transfer_detail_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_log`
--
ALTER TABLE `stock_transfer_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_log`
--
ALTER TABLE `transfer_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `twilio_webhook_demo_log`
--
ALTER TABLE `twilio_webhook_demo_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_account_group_log`
--
ALTER TABLE `user_account_group_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_department_log`
--
ALTER TABLE `user_department_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_family_member_log`
--
ALTER TABLE `user_family_member_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_master_log`
--
ALTER TABLE `user_master_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_order_department_log`
--
ALTER TABLE `user_order_department_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles_log`
--
ALTER TABLE `user_roles_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type_log`
--
ALTER TABLE `user_type_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_modules_log`
--
ALTER TABLE `website_modules_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_entry_log`
--
ALTER TABLE `worker_entry_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_hisab_detail_log`
--
ALTER TABLE `worker_hisab_detail_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_hisab_log`
--
ALTER TABLE `worker_hisab_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
