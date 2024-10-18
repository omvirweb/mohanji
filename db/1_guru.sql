-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 13, 2020 at 06:21 AM
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
-- Database: `guru`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
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
-- Table structure for table `account_group`
--

CREATE TABLE `account_group` (
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
-- Table structure for table `ad`
--

CREATE TABLE `ad` (
  `ad_id` int(11) NOT NULL,
  `ad_name` varchar(255) NOT NULL,
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
-- Table structure for table `carat`
--

CREATE TABLE `carat` (
  `carat_id` int(11) NOT NULL,
  `purity` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry`
--

CREATE TABLE `casting_entry` (
  `ce_id` int(11) NOT NULL,
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
-- Table structure for table `casting_entry_design_files`
--

CREATE TABLE `casting_entry_design_files` (
  `design_file_id` int(11) NOT NULL,
  `ce_id` int(11) DEFAULT NULL,
  `design_file_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `casting_entry_details`
--

CREATE TABLE `casting_entry_details` (
  `ce_detail_id` int(11) NOT NULL,
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
-- Table structure for table `casting_entry_order_items`
--

CREATE TABLE `casting_entry_order_items` (
  `ce_oi_id` int(11) NOT NULL,
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
-- Table structure for table `category`
--

CREATE TABLE `category` (
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
-- Table structure for table `category_group`
--

CREATE TABLE `category_group` (
  `category_group_id` int(11) NOT NULL,
  `category_group_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
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
-- Table structure for table `design_master`
--

CREATE TABLE `design_master` (
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
-- Table structure for table `employee_salary`
--

CREATE TABLE `employee_salary` (
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
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
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
-- Table structure for table `gold_bhav`
--

CREATE TABLE `gold_bhav` (
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
-- Table structure for table `hallmark_item_master`
--

CREATE TABLE `hallmark_item_master` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hallmark_receipt`
--

CREATE TABLE `hallmark_receipt` (
  `receipt_id` int(11) NOT NULL,
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
-- Table structure for table `hallmark_receipt_details`
--

CREATE TABLE `hallmark_receipt_details` (
  `rd_id` int(11) NOT NULL,
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
-- Table structure for table `hallmark_xrf`
--

CREATE TABLE `hallmark_xrf` (
  `xrf_id` int(11) NOT NULL,
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
-- Table structure for table `hallmark_xrf_items`
--

CREATE TABLE `hallmark_xrf_items` (
  `xrf_item_id` int(11) NOT NULL,
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
-- Table structure for table `hr_apply_leave`
--

CREATE TABLE `hr_apply_leave` (
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
-- Table structure for table `hr_attendance`
--

CREATE TABLE `hr_attendance` (
  `attendance_id` int(11) NOT NULL,
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
-- Table structure for table `hr_present_hours`
--

CREATE TABLE `hr_present_hours` (
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
-- Table structure for table `hr_yearly_leave`
--

CREATE TABLE `hr_yearly_leave` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `issue_receive`
--

CREATE TABLE `issue_receive` (
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
-- Table structure for table `issue_receive_details`
--

CREATE TABLE `issue_receive_details` (
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
-- Table structure for table `issue_receive_silver`
--

CREATE TABLE `issue_receive_silver` (
  `irs_id` int(11) NOT NULL,
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
-- Table structure for table `issue_receive_silver_details`
--

CREATE TABLE `issue_receive_silver_details` (
  `irsd_id` int(11) NOT NULL,
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
-- Table structure for table `item_master`
--

CREATE TABLE `item_master` (
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
-- Table structure for table `item_stock`
--

CREATE TABLE `item_stock` (
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
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive , 5 = MHM Receive Finish, 6 = MHM Receive Scrap, 7 = MC Receive Finish, 8 = MC Receive Scrap, 9 = Casting Entry Receive Finish, 10 = Casting Entry Receive Scrap, 11 = Opening Stock',
  `rfid_created_grwt` double NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_stock_rfid`
--

CREATE TABLE `item_stock_rfid` (
  `item_stock_rfid_id` int(11) NOT NULL,
  `item_stock_id` int(11) DEFAULT NULL,
  `rfid_grwt` double NOT NULL DEFAULT '0',
  `rfid_less` double NOT NULL DEFAULT '0',
  `rfid_add` double DEFAULT '0',
  `rfid_ntwt` double NOT NULL DEFAULT '0',
  `rfid_tunch` double NOT NULL DEFAULT '0',
  `rfid_fine` double NOT NULL DEFAULT '0',
  `real_rfid` varchar(255) DEFAULT NULL,
  `rfid_size` varchar(255) DEFAULT NULL,
  `rfid_charges` double DEFAULT '0',
  `rfid_ad_id` int(11) DEFAULT NULL,
  `rfid_used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Used, 1 = Used',
  `from_relation_id` int(11) DEFAULT NULL,
  `from_module` int(11) DEFAULT NULL COMMENT '1 = RFID Create, 2 = Sell, 3 = Stock Transfer',
  `to_relation_id` int(11) DEFAULT NULL,
  `to_module` int(11) DEFAULT NULL COMMENT '1 = RFID Create, 2 = Sell, 3 = Stock Transfer',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `journal`
--

CREATE TABLE `journal` (
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
-- Table structure for table `journal_details`
--

CREATE TABLE `journal_details` (
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
-- Table structure for table `machine_chain`
--

CREATE TABLE `machine_chain` (
  `machine_chain_id` int(11) NOT NULL,
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
-- Table structure for table `machine_chain_details`
--

CREATE TABLE `machine_chain_details` (
  `machine_chain_detail_id` int(11) NOT NULL,
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
-- Table structure for table `machine_chain_detail_order_items`
--

CREATE TABLE `machine_chain_detail_order_items` (
  `machine_chain_detail_oi_id` int(11) NOT NULL,
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
-- Table structure for table `machine_chain_operation`
--

CREATE TABLE `machine_chain_operation` (
  `operation_id` int(11) NOT NULL,
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
-- Table structure for table `machine_chain_operation_department`
--

CREATE TABLE `machine_chain_operation_department` (
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
-- Table structure for table `machine_chain_operation_worker`
--

CREATE TABLE `machine_chain_operation_worker` (
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
-- Table structure for table `machine_chain_order_items`
--

CREATE TABLE `machine_chain_order_items` (
  `machine_chain_oi_id` int(11) NOT NULL,
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
-- Table structure for table `manufacture_status`
--

CREATE TABLE `manufacture_status` (
  `manufacture_status_id` int(11) NOT NULL,
  `manufacture_status_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manu_hand_made`
--

CREATE TABLE `manu_hand_made` (
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
-- Table structure for table `manu_hand_made_ads`
--

CREATE TABLE `manu_hand_made_ads` (
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
-- Table structure for table `manu_hand_made_details`
--

CREATE TABLE `manu_hand_made_details` (
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
-- Table structure for table `manu_hand_made_order_items`
--

CREATE TABLE `manu_hand_made_order_items` (
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
-- Table structure for table `metal_payment_receipt`
--

CREATE TABLE `metal_payment_receipt` (
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
-- Table structure for table `module_roles`
--

CREATE TABLE `module_roles` (
  `module_role_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `new_order`
--

CREATE TABLE `new_order` (
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
-- Table structure for table `opening_stock`
--

CREATE TABLE `opening_stock` (
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
-- Table structure for table `operation`
--

CREATE TABLE `operation` (
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
-- Table structure for table `operation_department`
--

CREATE TABLE `operation_department` (
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
-- Table structure for table `operation_worker`
--

CREATE TABLE `operation_worker` (
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
-- Table structure for table `order_lot_item`
--

CREATE TABLE `order_lot_item` (
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
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `other`
--

CREATE TABLE `other` (
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
-- Table structure for table `other_items`
--

CREATE TABLE `other_items` (
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
-- Table structure for table `party_item_details`
--

CREATE TABLE `party_item_details` (
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
-- Table structure for table `payment_receipt`
--

CREATE TABLE `payment_receipt` (
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
-- Table structure for table `process_master`
--

CREATE TABLE `process_master` (
  `process_id` int(11) NOT NULL,
  `process_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `reminder_id` int(11) NOT NULL,
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
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
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
-- Table structure for table `sell`
--

CREATE TABLE `sell` (
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
-- Table structure for table `sell_ad_charges`
--

CREATE TABLE `sell_ad_charges` (
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
-- Table structure for table `sell_items`
--

CREATE TABLE `sell_items` (
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
  `item_stock_rfid_id` int(11) DEFAULT NULL,
  `rfid_number` varchar(255) DEFAULT NULL,
  `charges_amt` double NOT NULL DEFAULT '0',
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
-- Table structure for table `sell_less_ad_details`
--

CREATE TABLE `sell_less_ad_details` (
  `sell_less_ad_details_id` int(11) NOT NULL,
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
-- Table structure for table `sell_type`
--

CREATE TABLE `sell_type` (
  `sell_type_id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL,
  `settings_key` varchar(255) DEFAULT NULL,
  `settings_label` varchar(255) DEFAULT NULL,
  `settings_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting_mac_address`
--

CREATE TABLE `setting_mac_address` (
  `id` int(11) NOT NULL,
  `mac_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `silver_bhav`
--

CREATE TABLE `silver_bhav` (
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
-- Table structure for table `state`
--

CREATE TABLE `state` (
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
-- Table structure for table `stock_transfer`
--

CREATE TABLE `stock_transfer` (
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
-- Table structure for table `stock_transfer_detail`
--

CREATE TABLE `stock_transfer_detail` (
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
  `from_item_stock_rfid_id` int(11) DEFAULT NULL,
  `to_item_stock_rfid_id` int(11) DEFAULT NULL,
  `rfid_number` varchar(255) DEFAULT NULL,
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

CREATE TABLE `transfer` (
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
-- Table structure for table `twilio_webhook_demo`
--

CREATE TABLE `twilio_webhook_demo` (
  `id` int(11) NOT NULL,
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
-- Table structure for table `user_account_group`
--

CREATE TABLE `user_account_group` (
  `ud_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_group_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_department`
--

CREATE TABLE `user_department` (
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
-- Table structure for table `user_family_member`
--

CREATE TABLE `user_family_member` (
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
-- Table structure for table `user_master`
--

CREATE TABLE `user_master` (
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
-- Table structure for table `user_order_department`
--

CREATE TABLE `user_order_department` (
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
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `website_module_id` int(11) DEFAULT NULL,
  `role_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website_modules`
--

CREATE TABLE `website_modules` (
  `website_module_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `main_module` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `worker_entry`
--

CREATE TABLE `worker_entry` (
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
-- Table structure for table `worker_hisab`
--

CREATE TABLE `worker_hisab` (
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

-- --------------------------------------------------------

--
-- Table structure for table `worker_hisab_detail`
--

CREATE TABLE `worker_hisab_detail` (
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `account_group_id` (`account_group_id`);

--
-- Indexes for table `account_group`
--
ALTER TABLE `account_group`
  ADD PRIMARY KEY (`account_group_id`);

--
-- Indexes for table `ad`
--
ALTER TABLE `ad`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `carat`
--
ALTER TABLE `carat`
  ADD PRIMARY KEY (`carat_id`);

--
-- Indexes for table `casting_entry`
--
ALTER TABLE `casting_entry`
  ADD PRIMARY KEY (`ce_id`);

--
-- Indexes for table `casting_entry_design_files`
--
ALTER TABLE `casting_entry_design_files`
  ADD PRIMARY KEY (`design_file_id`);

--
-- Indexes for table `casting_entry_details`
--
ALTER TABLE `casting_entry_details`
  ADD PRIMARY KEY (`ce_detail_id`);

--
-- Indexes for table `casting_entry_order_items`
--
ALTER TABLE `casting_entry_order_items`
  ADD PRIMARY KEY (`ce_oi_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `category_group`
--
ALTER TABLE `category_group`
  ADD PRIMARY KEY (`category_group_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `design_master`
--
ALTER TABLE `design_master`
  ADD PRIMARY KEY (`design_id`);

--
-- Indexes for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD PRIMARY KEY (`employee_salary_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `gold_bhav`
--
ALTER TABLE `gold_bhav`
  ADD PRIMARY KEY (`gold_id`);

--
-- Indexes for table `hallmark_item_master`
--
ALTER TABLE `hallmark_item_master`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `hallmark_receipt`
--
ALTER TABLE `hallmark_receipt`
  ADD PRIMARY KEY (`receipt_id`);

--
-- Indexes for table `hallmark_receipt_details`
--
ALTER TABLE `hallmark_receipt_details`
  ADD PRIMARY KEY (`rd_id`);

--
-- Indexes for table `hallmark_xrf`
--
ALTER TABLE `hallmark_xrf`
  ADD PRIMARY KEY (`xrf_id`);

--
-- Indexes for table `hallmark_xrf_items`
--
ALTER TABLE `hallmark_xrf_items`
  ADD PRIMARY KEY (`xrf_item_id`);

--
-- Indexes for table `hr_apply_leave`
--
ALTER TABLE `hr_apply_leave`
  ADD PRIMARY KEY (`apply_leave_id`);

--
-- Indexes for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `hr_present_hours`
--
ALTER TABLE `hr_present_hours`
  ADD PRIMARY KEY (`present_hour_id`);

--
-- Indexes for table `hr_yearly_leave`
--
ALTER TABLE `hr_yearly_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_receive`
--
ALTER TABLE `issue_receive`
  ADD PRIMARY KEY (`ir_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `issue_receive_details`
--
ALTER TABLE `issue_receive_details`
  ADD PRIMARY KEY (`ird_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `issue_receive_silver`
--
ALTER TABLE `issue_receive_silver`
  ADD PRIMARY KEY (`irs_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `issue_receive_silver_details`
--
ALTER TABLE `issue_receive_silver_details`
  ADD PRIMARY KEY (`irsd_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item_master`
--
ALTER TABLE `item_master`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `category_id_2` (`category_id`),
  ADD KEY `category_id_3` (`category_id`),
  ADD KEY `category_id_4` (`category_id`);

--
-- Indexes for table `item_stock`
--
ALTER TABLE `item_stock`
  ADD PRIMARY KEY (`item_stock_id`);

--
-- Indexes for table `item_stock_rfid`
--
ALTER TABLE `item_stock_rfid`
  ADD PRIMARY KEY (`item_stock_rfid_id`),
  ADD KEY `rfid_ad_id` (`rfid_ad_id`);

--
-- Indexes for table `journal`
--
ALTER TABLE `journal`
  ADD PRIMARY KEY (`journal_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `journal_details`
--
ALTER TABLE `journal_details`
  ADD PRIMARY KEY (`jd_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `machine_chain`
--
ALTER TABLE `machine_chain`
  ADD PRIMARY KEY (`machine_chain_id`),
  ADD KEY `operation_id` (`operation_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `machine_chain_details`
--
ALTER TABLE `machine_chain_details`
  ADD PRIMARY KEY (`machine_chain_detail_id`);

--
-- Indexes for table `machine_chain_detail_order_items`
--
ALTER TABLE `machine_chain_detail_order_items`
  ADD PRIMARY KEY (`machine_chain_detail_oi_id`);

--
-- Indexes for table `machine_chain_operation`
--
ALTER TABLE `machine_chain_operation`
  ADD PRIMARY KEY (`operation_id`);

--
-- Indexes for table `machine_chain_operation_department`
--
ALTER TABLE `machine_chain_operation_department`
  ADD PRIMARY KEY (`od_id`);

--
-- Indexes for table `machine_chain_operation_worker`
--
ALTER TABLE `machine_chain_operation_worker`
  ADD PRIMARY KEY (`ow_id`);

--
-- Indexes for table `machine_chain_order_items`
--
ALTER TABLE `machine_chain_order_items`
  ADD PRIMARY KEY (`machine_chain_oi_id`);

--
-- Indexes for table `manufacture_status`
--
ALTER TABLE `manufacture_status`
  ADD PRIMARY KEY (`manufacture_status_id`);

--
-- Indexes for table `manu_hand_made`
--
ALTER TABLE `manu_hand_made`
  ADD PRIMARY KEY (`mhm_id`),
  ADD KEY `operation_id` (`operation_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `manu_hand_made_ads`
--
ALTER TABLE `manu_hand_made_ads`
  ADD PRIMARY KEY (`mhm_ad_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `manu_hand_made_details`
--
ALTER TABLE `manu_hand_made_details`
  ADD PRIMARY KEY (`mhm_detail_id`);

--
-- Indexes for table `manu_hand_made_order_items`
--
ALTER TABLE `manu_hand_made_order_items`
  ADD PRIMARY KEY (`mhm_oi_id`);

--
-- Indexes for table `metal_payment_receipt`
--
ALTER TABLE `metal_payment_receipt`
  ADD PRIMARY KEY (`metal_pr_id`);

--
-- Indexes for table `module_roles`
--
ALTER TABLE `module_roles`
  ADD PRIMARY KEY (`module_role_id`);

--
-- Indexes for table `new_order`
--
ALTER TABLE `new_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `process_id` (`process_id`),
  ADD KEY `process_id_2` (`process_id`),
  ADD KEY `party_id` (`party_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `opening_stock`
--
ALTER TABLE `opening_stock`
  ADD PRIMARY KEY (`opening_stock_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`operation_id`);

--
-- Indexes for table `operation_department`
--
ALTER TABLE `operation_department`
  ADD PRIMARY KEY (`od_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `operation_worker`
--
ALTER TABLE `operation_worker`
  ADD PRIMARY KEY (`ow_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `worker_id_2` (`worker_id`);

--
-- Indexes for table `order_lot_item`
--
ALTER TABLE `order_lot_item`
  ADD PRIMARY KEY (`order_lot_item_id`),
  ADD KEY `touch_id` (`touch_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `touch_id_2` (`touch_id`),
  ADD KEY `touch_id_3` (`touch_id`),
  ADD KEY `item_id_2` (`item_id`),
  ADD KEY `item_id_3` (`item_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`order_status_id`);

--
-- Indexes for table `other`
--
ALTER TABLE `other`
  ADD PRIMARY KEY (`other_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `other_items`
--
ALTER TABLE `other_items`
  ADD PRIMARY KEY (`other_item_id`);

--
-- Indexes for table `party_item_details`
--
ALTER TABLE `party_item_details`
  ADD PRIMARY KEY (`party_item_id`);

--
-- Indexes for table `payment_receipt`
--
ALTER TABLE `payment_receipt`
  ADD PRIMARY KEY (`pay_rec_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `process_master`
--
ALTER TABLE `process_master`
  ADD PRIMARY KEY (`process_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `sell`
--
ALTER TABLE `sell`
  ADD PRIMARY KEY (`sell_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `process_id` (`process_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sell_ad_charges`
--
ALTER TABLE `sell_ad_charges`
  ADD PRIMARY KEY (`sell_ad_charges_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `sell_items`
--
ALTER TABLE `sell_items`
  ADD PRIMARY KEY (`sell_item_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `purchase_sell_item_id` (`purchase_sell_item_id`),
  ADD KEY `order_lot_item_id` (`order_lot_item_id`);

--
-- Indexes for table `sell_less_ad_details`
--
ALTER TABLE `sell_less_ad_details`
  ADD PRIMARY KEY (`sell_less_ad_details_id`),
  ADD KEY `less_ad_details_ad_id` (`less_ad_details_ad_id`);

--
-- Indexes for table `sell_type`
--
ALTER TABLE `sell_type`
  ADD PRIMARY KEY (`sell_type_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `setting_mac_address`
--
ALTER TABLE `setting_mac_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `silver_bhav`
--
ALTER TABLE `silver_bhav`
  ADD PRIMARY KEY (`silver_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD PRIMARY KEY (`stock_transfer_id`),
  ADD KEY `from_department` (`from_department`),
  ADD KEY `to_department` (`to_department`);

--
-- Indexes for table `stock_transfer_detail`
--
ALTER TABLE `stock_transfer_detail`
  ADD PRIMARY KEY (`transfer_detail_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `twilio_webhook_demo`
--
ALTER TABLE `twilio_webhook_demo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account_group`
--
ALTER TABLE `user_account_group`
  ADD PRIMARY KEY (`ud_id`);

--
-- Indexes for table `user_department`
--
ALTER TABLE `user_department`
  ADD PRIMARY KEY (`ud_id`);

--
-- Indexes for table `user_family_member`
--
ALTER TABLE `user_family_member`
  ADD PRIMARY KEY (`fm_id`);

--
-- Indexes for table `user_master`
--
ALTER TABLE `user_master`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_order_department`
--
ALTER TABLE `user_order_department`
  ADD PRIMARY KEY (`ud_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `website_modules`
--
ALTER TABLE `website_modules`
  ADD PRIMARY KEY (`website_module_id`);

--
-- Indexes for table `worker_entry`
--
ALTER TABLE `worker_entry`
  ADD PRIMARY KEY (`worker_entry_id`);

--
-- Indexes for table `worker_hisab`
--
ALTER TABLE `worker_hisab`
  ADD PRIMARY KEY (`worker_hisab_id`);

--
-- Indexes for table `worker_hisab_detail`
--
ALTER TABLE `worker_hisab_detail`
  ADD PRIMARY KEY (`wsd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_group`
--
ALTER TABLE `account_group`
  MODIFY `account_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad`
--
ALTER TABLE `ad`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carat`
--
ALTER TABLE `carat`
  MODIFY `carat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry`
--
ALTER TABLE `casting_entry`
  MODIFY `ce_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_design_files`
--
ALTER TABLE `casting_entry_design_files`
  MODIFY `design_file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_details`
--
ALTER TABLE `casting_entry_details`
  MODIFY `ce_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casting_entry_order_items`
--
ALTER TABLE `casting_entry_order_items`
  MODIFY `ce_oi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_group`
--
ALTER TABLE `category_group`
  MODIFY `category_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `design_master`
--
ALTER TABLE `design_master`
  MODIFY `design_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_salary`
--
ALTER TABLE `employee_salary`
  MODIFY `employee_salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gold_bhav`
--
ALTER TABLE `gold_bhav`
  MODIFY `gold_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_item_master`
--
ALTER TABLE `hallmark_item_master`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_receipt`
--
ALTER TABLE `hallmark_receipt`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_receipt_details`
--
ALTER TABLE `hallmark_receipt_details`
  MODIFY `rd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_xrf`
--
ALTER TABLE `hallmark_xrf`
  MODIFY `xrf_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hallmark_xrf_items`
--
ALTER TABLE `hallmark_xrf_items`
  MODIFY `xrf_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_apply_leave`
--
ALTER TABLE `hr_apply_leave`
  MODIFY `apply_leave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_present_hours`
--
ALTER TABLE `hr_present_hours`
  MODIFY `present_hour_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_yearly_leave`
--
ALTER TABLE `hr_yearly_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive`
--
ALTER TABLE `issue_receive`
  MODIFY `ir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_details`
--
ALTER TABLE `issue_receive_details`
  MODIFY `ird_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_silver`
--
ALTER TABLE `issue_receive_silver`
  MODIFY `irs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_receive_silver_details`
--
ALTER TABLE `issue_receive_silver_details`
  MODIFY `irsd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_master`
--
ALTER TABLE `item_master`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stock`
--
ALTER TABLE `item_stock`
  MODIFY `item_stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stock_rfid`
--
ALTER TABLE `item_stock_rfid`
  MODIFY `item_stock_rfid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal`
--
ALTER TABLE `journal`
  MODIFY `journal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_details`
--
ALTER TABLE `journal_details`
  MODIFY `jd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain`
--
ALTER TABLE `machine_chain`
  MODIFY `machine_chain_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_details`
--
ALTER TABLE `machine_chain_details`
  MODIFY `machine_chain_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_detail_order_items`
--
ALTER TABLE `machine_chain_detail_order_items`
  MODIFY `machine_chain_detail_oi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation`
--
ALTER TABLE `machine_chain_operation`
  MODIFY `operation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation_department`
--
ALTER TABLE `machine_chain_operation_department`
  MODIFY `od_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_operation_worker`
--
ALTER TABLE `machine_chain_operation_worker`
  MODIFY `ow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_chain_order_items`
--
ALTER TABLE `machine_chain_order_items`
  MODIFY `machine_chain_oi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacture_status`
--
ALTER TABLE `manufacture_status`
  MODIFY `manufacture_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made`
--
ALTER TABLE `manu_hand_made`
  MODIFY `mhm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_ads`
--
ALTER TABLE `manu_hand_made_ads`
  MODIFY `mhm_ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_details`
--
ALTER TABLE `manu_hand_made_details`
  MODIFY `mhm_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manu_hand_made_order_items`
--
ALTER TABLE `manu_hand_made_order_items`
  MODIFY `mhm_oi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metal_payment_receipt`
--
ALTER TABLE `metal_payment_receipt`
  MODIFY `metal_pr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module_roles`
--
ALTER TABLE `module_roles`
  MODIFY `module_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_order`
--
ALTER TABLE `new_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opening_stock`
--
ALTER TABLE `opening_stock`
  MODIFY `opening_stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation`
--
ALTER TABLE `operation`
  MODIFY `operation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_department`
--
ALTER TABLE `operation_department`
  MODIFY `od_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_worker`
--
ALTER TABLE `operation_worker`
  MODIFY `ow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_lot_item`
--
ALTER TABLE `order_lot_item`
  MODIFY `order_lot_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other`
--
ALTER TABLE `other`
  MODIFY `other_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_items`
--
ALTER TABLE `other_items`
  MODIFY `other_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `party_item_details`
--
ALTER TABLE `party_item_details`
  MODIFY `party_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_receipt`
--
ALTER TABLE `payment_receipt`
  MODIFY `pay_rec_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process_master`
--
ALTER TABLE `process_master`
  MODIFY `process_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell`
--
ALTER TABLE `sell`
  MODIFY `sell_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_ad_charges`
--
ALTER TABLE `sell_ad_charges`
  MODIFY `sell_ad_charges_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_items`
--
ALTER TABLE `sell_items`
  MODIFY `sell_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_less_ad_details`
--
ALTER TABLE `sell_less_ad_details`
  MODIFY `sell_less_ad_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_type`
--
ALTER TABLE `sell_type`
  MODIFY `sell_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting_mac_address`
--
ALTER TABLE `setting_mac_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `silver_bhav`
--
ALTER TABLE `silver_bhav`
  MODIFY `silver_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  MODIFY `stock_transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_detail`
--
ALTER TABLE `stock_transfer_detail`
  MODIFY `transfer_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `twilio_webhook_demo`
--
ALTER TABLE `twilio_webhook_demo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_account_group`
--
ALTER TABLE `user_account_group`
  MODIFY `ud_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_department`
--
ALTER TABLE `user_department`
  MODIFY `ud_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_family_member`
--
ALTER TABLE `user_family_member`
  MODIFY `fm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_master`
--
ALTER TABLE `user_master`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_order_department`
--
ALTER TABLE `user_order_department`
  MODIFY `ud_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_modules`
--
ALTER TABLE `website_modules`
  MODIFY `website_module_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_entry`
--
ALTER TABLE `worker_entry`
  MODIFY `worker_entry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_hisab`
--
ALTER TABLE `worker_hisab`
  MODIFY `worker_hisab_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_hisab_detail`
--
ALTER TABLE `worker_hisab_detail`
  MODIFY `wsd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `Fk_account_group` FOREIGN KEY (`account_group_id`) REFERENCES `account_group` (`account_group_id`);

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `FK_StateCity` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`);

--
-- Constraints for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD CONSTRAINT `Fk_EmpSalaryAccountId` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `issue_receive`
--
ALTER TABLE `issue_receive`
  ADD CONSTRAINT `Fk_AccountIrDepartment` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_IrWorker` FOREIGN KEY (`worker_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `issue_receive_details`
--
ALTER TABLE `issue_receive_details`
  ADD CONSTRAINT `Fk_ItemIr` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `item_master`
--
ALTER TABLE `item_master`
  ADD CONSTRAINT `FK_Item_masterCategory` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `item_stock_rfid`
--
ALTER TABLE `item_stock_rfid`
  ADD CONSTRAINT `Fk_item_stock_rfid` FOREIGN KEY (`rfid_ad_id`) REFERENCES `ad` (`ad_id`);

--
-- Constraints for table `journal`
--
ALTER TABLE `journal`
  ADD CONSTRAINT `Fk_Account_journal` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `journal_details`
--
ALTER TABLE `journal_details`
  ADD CONSTRAINT `Fk_AccountJournal` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `machine_chain`
--
ALTER TABLE `machine_chain`
  ADD CONSTRAINT `Fk_AccountMachine_chain_Worker` FOREIGN KEY (`worker_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_AccountMachine_chain_department` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_OperationMachineChain` FOREIGN KEY (`operation_id`) REFERENCES `machine_chain_operation` (`operation_id`);

--
-- Constraints for table `manu_hand_made`
--
ALTER TABLE `manu_hand_made`
  ADD CONSTRAINT `Fk_AccountManu_hand_made_Worker` FOREIGN KEY (`worker_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_AccountManu_hand_made_department` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_OperationManuHandMade` FOREIGN KEY (`operation_id`) REFERENCES `operation` (`operation_id`);

--
-- Constraints for table `manu_hand_made_ads`
--
ALTER TABLE `manu_hand_made_ads`
  ADD CONSTRAINT `Fk_manu_hand_made_ads` FOREIGN KEY (`ad_id`) REFERENCES `ad` (`ad_id`);

--
-- Constraints for table `new_order`
--
ALTER TABLE `new_order`
  ADD CONSTRAINT `Fk_AccountOrderParty` FOREIGN KEY (`party_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_AccountOrderProcess` FOREIGN KEY (`process_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_AccountOrderSupplier` FOREIGN KEY (`supplier_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `opening_stock`
--
ALTER TABLE `opening_stock`
  ADD CONSTRAINT `FK_itemOpening` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `operation_department`
--
ALTER TABLE `operation_department`
  ADD CONSTRAINT `Fk_AccountOperationDepartment` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `operation_worker`
--
ALTER TABLE `operation_worker`
  ADD CONSTRAINT `Fk_AccountOperationWorker` FOREIGN KEY (`worker_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_OperationWorker` FOREIGN KEY (`worker_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `order_lot_item`
--
ALTER TABLE `order_lot_item`
  ADD CONSTRAINT `Fk_ItemCaret` FOREIGN KEY (`touch_id`) REFERENCES `carat` (`carat_id`),
  ADD CONSTRAINT `Fk_ItemOrder` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `other`
--
ALTER TABLE `other`
  ADD CONSTRAINT `Fk_OtherAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_OtherDepartment` FOREIGN KEY (`department_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `payment_receipt`
--
ALTER TABLE `payment_receipt`
  ADD CONSTRAINT `Fk_AccountCashbook` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `sell`
--
ALTER TABLE `sell`
  ADD CONSTRAINT `Fk_AccountSellProcess` FOREIGN KEY (`process_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_SellAccount` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_SellOrder` FOREIGN KEY (`order_id`) REFERENCES `new_order` (`order_id`);

--
-- Constraints for table `sell_ad_charges`
--
ALTER TABLE `sell_ad_charges`
  ADD CONSTRAINT `Fk_sell_ad_charges` FOREIGN KEY (`ad_id`) REFERENCES `ad` (`ad_id`);

--
-- Constraints for table `sell_items`
--
ALTER TABLE `sell_items`
  ADD CONSTRAINT `FK_OrderSell` FOREIGN KEY (`order_lot_item_id`) REFERENCES `order_lot_item` (`order_lot_item_id`),
  ADD CONSTRAINT `Fk_ItemSell` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);

--
-- Constraints for table `sell_less_ad_details`
--
ALTER TABLE `sell_less_ad_details`
  ADD CONSTRAINT `Fk_sell_less_ad_details` FOREIGN KEY (`less_ad_details_ad_id`) REFERENCES `ad` (`ad_id`);

--
-- Constraints for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD CONSTRAINT `Fk_Account_from_stock_transfer` FOREIGN KEY (`from_department`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `Fk_Account_to_stock_transfer` FOREIGN KEY (`to_department`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `stock_transfer_detail`
--
ALTER TABLE `stock_transfer_detail`
  ADD CONSTRAINT `Fk_ItemStockTransfer` FOREIGN KEY (`item_id`) REFERENCES `item_master` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
