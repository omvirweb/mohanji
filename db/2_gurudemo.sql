--
-- Database: `gurudemo`
--

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_mobile`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `interest`, `credit_debit`, `opening_balance_in_gold`, `gold_ob_credit_debit`, `opening_balance_in_silver`, `silver_ob_credit_debit`, `opening_balance_in_rupees`, `rupees_ob_credit_debit`, `bank_name`, `bank_account_no`, `ifsc_code`, `bank_interest`, `gold_fine`, `silver_fine`, `amount`, `credit_limit`, `balance_date`, `status`, `user_id`, `user_name`, `is_supplier`, `password`, `min_price`, `chhijjat_per_100_ad`, `meena_charges`, `price_per_pcs`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Cash Customer', '7778889997', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(2, 'Customer Monthly Interest', '', '9999999999', '', '', NULL, NULL, '', '', '', '', '', 15, NULL, 0, NULL, 0, 1, 0, 1, 0, 1, '', '', '', 0, 0, 0, 0, 0, '2020-01-02 19:45:36', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2019-03-13 13:29:23', 1, '2019-03-13 13:29:23'),
(3, 'Adjust', '', NULL, '', '', NULL, NULL, '', '', '', '', '', 9, NULL, 0, NULL, 0, 1, 0, 1, 0, 1, '', '', '', 0, 0, 0, 0, 0, '2020-01-03 18:40:32', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2019-04-29 13:20:09', 1, '2019-04-29 13:20:09'),
(4, 'admin', NULL, '2912700007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 51, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '2020-01-01 17:09:06', 1, 1, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2019-09-16 17:57:53', NULL, NULL),
(5, 'Salary Expense', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '2019-12-22 12:41:36', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2019-07-27 15:30:00', 1, '2019-07-27 15:30:00'),
(6, 'MF Loss', '', NULL, '', '', NULL, NULL, '', '', '', '', '', 9, NULL, 0, NULL, 0, 1, 0, 1, 0, 1, '', '', '', 0, 0, 0, 0, 0, '2020-01-02 19:53:10', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2019-09-11 15:30:00', 1, '2019-09-11 15:30:00'),
(358, 'CASTING', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(359, 'MACHIN CHAIN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, '2019-12-19 17:49:09', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, NULL, NULL),
(361, 'Sales MAIN', '', '', NULL, '', NULL, NULL, '', '', '', '', '', 50, NULL, 0, NULL, 0, 1, 0, 1, 0, 2, '', '', '', 0, 0, 0, 0, 0, '2019-12-19 20:07:14', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, 1, '2019-09-17 19:20:04');

--
-- Dumping data for table `account_group`
--

INSERT INTO `account_group` (`account_group_id`, `parent_group_id`, `account_group_name`, `sequence`, `is_display_in_balance_sheet`, `use_in_profit_loss`, `move_data_opening_zero`, `is_deletable`, `is_deleted`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 10, 'Expenses (Direct)', 2, 0, 0, 0, 0, 0, 1, '2017-08-04 07:40:24', 1, '2019-10-23 13:23:05'),
(2, 10, 'Trading Account', 0, 0, 0, 0, 0, 0, 1, '2017-08-04 07:40:48', 1, '2019-10-23 13:24:38'),
(3, 2, 'General Trading Account', 99, 0, 0, 0, 0, 0, 1, '2017-08-04 07:41:06', 1, '2019-10-23 13:07:34'),
(4, 10, 'Income (Trading)', 2, 0, 0, 0, 0, 0, 1, '2017-08-04 07:41:33', 1, '2019-10-23 13:08:18'),
(5, 10, 'Jobwork Expense', 3, 0, 0, 0, 0, 0, 1, '2017-08-04 07:41:50', 1, '2019-10-23 13:08:00'),
(6, 10, 'Jobwork Income (Trading)', 3, 0, 0, 0, 0, 0, 1, '2017-08-04 07:42:06', 1, '2019-10-23 13:07:52'),
(7, 10, 'Purchase Account', 1, 0, 0, 0, 0, 0, 1, '2017-08-04 07:42:18', 1, '2019-10-23 13:08:10'),
(8, 10, 'Sales Account', 1, 0, 0, 0, 0, 0, 1, '2017-08-04 07:42:27', 1, '2019-10-23 13:07:41'),
(9, 11, 'Expense Account', 1, 0, 0, 0, 0, 0, 1, '2017-08-04 07:42:43', 1, '2019-10-23 13:26:18'),
(10, 0, 'Trading', 0, 0, 0, 0, 0, 0, 1, '2017-08-04 07:43:54', 1, '2019-10-23 13:06:48'),
(11, 0, 'Profit & Loss', 0, 0, 0, 0, 0, 0, 1, '2017-08-04 07:45:17', 1, '2019-10-23 13:07:12'),
(12, 0, 'Balance Sheet', 0, 1, 0, 0, 0, 0, 1, '2017-08-04 07:47:13', 1, '2017-08-04 07:47:13'),
(13, 9, 'Financial Expense', 3, 0, 0, 0, 0, 0, 1, '2017-08-04 07:47:42', 1, '2019-10-23 13:27:12'),
(14, 11, 'Income', 22, 0, 0, 0, 0, 0, 1, '2017-08-04 07:48:00', 1, '2019-10-23 13:25:17'),
(15, 11, 'Income (Other Then Sales)', 3, 0, 0, 0, 0, 0, 1, '2017-08-04 07:48:51', 1, '2019-10-23 12:50:29'),
(16, 11, 'Partner Interest', 4, 1, 0, 0, 0, 0, 1, '2017-08-04 09:17:14', 1, '2017-08-04 09:17:14'),
(17, 11, 'Partner Remuneration', 4, 1, 0, 0, 0, 0, 1, '2017-08-04 09:17:31', 1, '2017-08-04 09:17:31'),
(18, 11, 'Revenue Accounts', 1, 1, 0, 0, 0, 0, 1, '2017-08-04 09:17:59', 1, '2017-08-04 09:17:59'),
(19, 9, 'Salary Expense', 2, 0, 0, 0, 0, 0, 1, '2017-08-04 09:18:27', 1, '2019-10-23 13:09:14'),
(20, 12, 'Current Assets', 1, 1, 0, 0, 0, 0, 1, '2017-08-04 09:18:47', 1, '2017-08-04 09:18:47'),
(21, 20, 'Bank Accounts (Banks)', 8, 1, 0, 0, 0, 0, 1, '2017-08-04 09:19:11', 1, '2017-08-04 09:19:11'),
(22, 12, 'Loans (Liability)', 9, 0, 0, 0, 0, 0, 1, '2017-08-04 09:19:45', 1, '2019-10-23 13:07:26'),
(23, 22, 'Bank OCC a/c', 4, 1, 0, 0, 0, 0, 1, '2017-08-04 09:20:08', 1, '2017-08-04 09:20:08'),
(24, 12, 'Capital Account', 1, 1, 0, 0, 0, 0, 1, '2017-08-04 09:20:23', 1, '2017-08-04 09:20:23'),
(25, 12, 'Cash Ledger A/C.', 98, 1, 0, 0, 0, 0, 1, '2017-08-04 09:20:40', 1, '2017-08-04 09:20:40'),
(26, 20, 'Cash-in-hand', 9, 1, 0, 0, 0, 0, 1, '2017-08-04 09:21:01', 1, '2017-08-04 09:21:01'),
(27, 12, 'Current Liabilities', 5, 1, 0, 0, 0, 0, 1, '2017-08-04 09:21:18', 1, '2017-08-04 09:21:18'),
(28, 20, 'Deposits (Asset)', 4, 1, 0, 0, 0, 0, 1, '2017-08-04 09:21:41', 1, '2017-08-04 09:21:41'),
(29, 27, 'Duties & Taxes', 6, 1, 0, 0, 0, 0, 1, '2017-08-04 09:22:07', 1, '2017-08-04 09:22:07'),
(30, 12, 'Fixed Assets', 2, 1, 0, 0, 0, 0, 1, '2017-08-04 09:22:21', 1, '2017-08-04 09:22:21'),
(31, 12, 'Investments', 3, 1, 0, 0, 0, 0, 1, '2017-08-04 09:22:42', 1, '2017-08-04 09:22:42'),
(32, 20, 'Loans & Advances (Asset)', 5, 1, 0, 0, 0, 0, 1, '2017-08-04 09:23:01', 1, '2017-08-04 09:23:01'),
(33, 12, 'Misc. Expenses (Asset)', 6, 1, 0, 0, 0, 0, 1, '2017-08-04 09:23:52', 1, '2017-08-04 09:23:52'),
(34, 12, 'Profit & Loss A/c', 99, 0, 0, 0, 0, 0, 1, '2017-08-04 09:24:33', 1, '2019-10-23 13:25:41'),
(35, 27, 'Provisions', 7, 1, 0, 0, 0, 0, 1, '2017-08-04 09:24:47', 1, '2017-08-04 09:24:47'),
(36, 24, 'Reserves & Surplus', 2, 1, 0, 0, 0, 0, 1, '2017-08-04 09:25:05', 1, '2017-08-04 09:25:05'),
(37, 22, 'Secured Loans', 10, 1, 0, 0, 0, 0, 1, '2017-08-04 09:25:59', 1, '2017-08-04 09:25:59'),
(38, 12, 'Stock-in-hand', 10, 1, 0, 0, 0, 0, 1, '2017-08-04 09:26:11', 1, '2017-08-04 09:26:11'),
(39, 27, 'Sundry Creditors', 11, 1, 0, 0, 0, 0, 1, '2017-08-04 09:26:32', 1, '2017-08-04 09:26:32'),
(40, 27, 'Sundry Creditors (Others)', 12, 1, 0, 0, 0, 0, 1, '2017-08-04 09:26:52', 1, '2017-08-04 09:26:52'),
(41, 27, 'Sundry Creditors (Salary)', 13, 1, 0, 0, 0, 0, 1, '2017-08-04 09:27:26', 1, '2017-08-04 09:27:26'),
(42, 20, 'Sundry Debtors', 7, 1, 0, 0, 0, 0, 1, '2017-08-04 09:27:44', 1, '2017-08-04 09:27:44'),
(45, 12, 'Suspense Account', 8, 1, 0, 0, 0, 0, 1, '2017-08-04 09:28:34', 1, '2017-08-04 09:28:34'),
(46, 22, 'Unsecured Loans', 3, 1, 0, 0, 0, 0, 1, '2017-08-04 09:28:47', 1, '2017-08-04 09:28:47'),
(47, 27, 'Staff', 99, 1, 0, 0, 0, 0, 1, '2017-08-04 09:45:18', 1, '2017-08-04 09:45:18'),
(48, 27, 'Supplier', 99, 1, 0, 0, 0, 0, 1, '2017-08-04 09:45:26', 1, '2017-08-04 09:45:26'),
(49, 20, 'Customer', 99, 1, 0, 0, 0, 0, 1, '2017-08-04 09:45:47', 1, '2017-08-04 09:45:47'),
(50, NULL, 'Department', NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(51, NULL, 'Admin', NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(52, NULL, 'User', NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(53, NULL, 'Worker', NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(54, NULL, 'Salesman', NULL, 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL),
(55, NULL, 'Not Approved', NULL, 0, 0, 0, 0, 0, 1, '2019-12-09 00:00:00', 1, '2019-12-09 00:00:00');

--
-- Dumping data for table `ad`
--

INSERT INTO `ad` (`ad_id`, `ad_name`, `ad_description`, `is_nang_setting`, `is_sell_purchase_ad_charges`, `is_sell_purchase_less_ad_details`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'White', NULL, 0, 0, 0, NULL, 0, NULL, 0),
(2, 'Rudy', NULL, 0, 0, 0, NULL, 0, NULL, 0),
(3, 'Square', NULL, 0, 0, 0, NULL, 0, NULL, 0),
(4, 'Choki', NULL, 0, 0, 0, NULL, 0, NULL, 0);

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_group_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(8, 'Test Category', NULL, NULL, NULL, NULL, NULL),
(17, 'CASTING', 1, 1, '2018-11-21 18:13:19', NULL, NULL),
(19, 'CASTING GENTS RING', 1, 1, '2019-02-13 18:05:49', NULL, NULL),
(20, 'CASTING TOPS', 1, 1, '2019-02-13 20:00:11', 1, '2019-02-13 20:03:20'),
(21, 'CASTING LADIES RING', 1, 1, '2019-02-18 17:00:01', 1, '2019-02-22 11:13:24'),
(23, 'CATING MS PANDAL', 1, 1, '2019-03-04 11:01:38', NULL, NULL),
(24, 'EMBOSE', 1, 1, '2019-03-29 16:59:38', NULL, NULL),
(25, 'CASTING PUNCH ', 1, 1, '2019-04-22 16:06:31', 1, '2019-04-22 16:06:47'),
(26, 'COIN', 1, 1, '2019-05-01 11:57:41', 1, '2019-05-01 11:57:41'),
(27, 'FINE GOLD', 1, 1, '2019-05-01 12:06:08', 1, '2019-05-01 12:06:08'),
(28, 'FINE SILVER', 2, 1, '2019-05-01 12:06:18', 1, '2019-05-01 12:06:18'),
(29, 'HANDMADE CHAIN', 1, 1, '2019-05-01 12:15:45', 1, '2019-05-01 12:15:45'),
(32, 'WORK IN PROGRESS', 1, 1, '2019-05-01 12:56:00', 1, '2019-07-22 17:56:07'),
(33, 'MACHINE CHAIN', 1, 1, '2019-07-23 16:04:12', 1, '2019-07-23 16:04:12'),
(34, 'OTHER', 3, 1, '2019-08-23 14:06:37', 1, '2019-08-23 14:06:37');

--
-- Dumping data for table `category_group`
--

INSERT INTO `category_group` (`category_group_id`, `category_group_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Gold', NULL, NULL, NULL, NULL),
(2, 'Silver', NULL, NULL, NULL, NULL),
(3, 'Other', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `item_master`
--

INSERT INTO `item_master` (`item_id`, `item_name`, `short_item`, `category_id`, `image`, `die_no`, `design_no`, `min_order_qty`, `default_wastage`, `st_default_wastage`, `less`, `display_item_in`, `stock_method`, `metal_payment_receipt`, `sequence_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(406, 'GOLD', '', 27, NULL, '', '', 0, 0, 0, 0, NULL, 3, 1, NULL, 1, '2019-05-01 12:07:18', 1, '2019-11-13 17:52:39'),
(407, 'SILVER', '', 28, NULL, '', '', 0, 0, 0, 0, NULL, 3, 1, NULL, 1, '2019-05-01 12:07:46', 1, '2019-11-13 17:52:09');

--
-- Dumping data for table `machine_chain_operation`
--

INSERT INTO `machine_chain_operation` (`operation_id`, `operation_name`, `sequence_no`, `allow_only_1_order_item`, `direct_issue_allow`, `calculate_button`, `use_selected_tunch`, `issue_change_actual_tunch_allow`, `receive_change_actual_tunch_allow`, `remark`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(10, 'MELTING', 1, 0, 1, 0, 0, 1, 0, '', '2020-01-29 17:26:23', 1, '2020-01-29 17:26:23', 1),
(11, 'WIRE DRAW', 2, 0, 0, 0, 1, 0, 0, '', '2020-01-29 17:27:19', 1, '2020-01-29 17:27:19', 1),
(12, 'BOX CHAIN', 3, 0, 0, 0, 1, 0, 0, '', '2020-01-29 17:28:11', 1, '2020-01-29 17:28:11', 1),
(13, 'CURB & ANCHOR', 3, 0, 0, 0, 1, 0, 0, '', '2020-01-29 17:28:59', 1, '2020-01-29 17:28:59', 1),
(14, 'FINAL', 4, 0, 0, 0, 1, 0, 0, '', '2020-01-29 17:30:03', 1, '2020-01-29 17:30:03', 1),
(15, 'SOLDING', 5, 1, 0, 1, 0, 0, 0, '', '2020-01-29 17:30:48', 1, '2020-01-29 17:30:48', 1),
(16, 'JODAI', 6, 1, 0, 1, 0, 0, 0, '', '2020-01-29 17:31:37', 1, '2020-01-29 17:31:37', 1),
(17, 'FACTING', 7, 0, 0, 0, 1, 0, 0, '', '2020-01-29 17:32:16', 1, '2020-01-29 17:32:16', 1);

--
-- Dumping data for table `machine_chain_operation_department`
--

INSERT INTO `machine_chain_operation_department` (`od_id`, `operation_id`, `department_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(3, 10, 359, '2020-01-29 17:26:23', 1, NULL, NULL),
(4, 11, 359, '2020-01-29 17:27:19', 1, NULL, NULL),
(5, 12, 359, '2020-01-29 17:28:11', 1, NULL, NULL),
(6, 13, 359, '2020-01-29 17:28:59', 1, NULL, NULL),
(7, 14, 359, '2020-01-29 17:30:03', 1, NULL, NULL),
(8, 15, 359, '2020-01-29 17:30:48', 1, NULL, NULL),
(9, 16, 359, '2020-01-29 17:31:37', 1, NULL, NULL),
(10, 17, 359, '2020-01-29 17:32:16', 1, NULL, NULL);

--
-- Dumping data for table `machine_chain_operation_worker`
--

INSERT INTO `machine_chain_operation_worker` (`ow_id`, `operation_id`, `worker_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 10, 2409, '2020-01-29 17:26:23', 1, NULL, NULL),
(3, 11, 2410, '2020-01-29 17:27:19', 1, NULL, NULL),
(4, 12, 2412, '2020-01-29 17:28:11', 1, NULL, NULL),
(5, 13, 2411, '2020-01-29 17:28:59', 1, NULL, NULL),
(6, 14, 2408, '2020-01-29 17:30:03', 1, NULL, NULL),
(7, 15, 2408, '2020-01-29 17:30:48', 1, NULL, NULL),
(8, 16, 2544, '2020-01-29 17:31:37', 1, NULL, NULL),
(9, 17, 2407, '2020-01-29 17:32:16', 1, NULL, NULL);

--
-- Dumping data for table `manufacture_status`
--

INSERT INTO `manufacture_status` (`manufacture_status_id`, `manufacture_status_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Not Started', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(2, 'In CAD', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(3, 'Design Ready', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(4, 'In Wax', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(5, 'Wax Done', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(6, 'In Casting', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(7, 'Casting Done', 1, '2020-01-12 00:00:00', 1, '2020-01-12 00:00:00'),
(8, 'Filing', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(9, 'Filing Done', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(10, 'In Setting', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(11, 'Setting Done', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(12, 'In Polish', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(13, 'Polish Done', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36'),
(14, 'Order Complete', 1, '2020-02-04 19:14:36', 1, '2020-02-04 19:14:36');

--
-- Dumping data for table `module_roles`
--

INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES
(1, 'view', 'view', 1),
(2, 'view', 'view', 2),
(3, 'add', 'add', 2),
(4, 'edit', 'edit', 2),
(5, 'delete', 'delete', 2),
(6, 'view', 'view', 3),
(8, 'edit', 'edit', 3),
(10, 'view', 'view', 4),
(11, 'add', 'add', 4),
(12, 'edit', 'edit', 4),
(13, 'delete', 'delete', 4),
(14, 'view', 'view', 5),
(15, 'add', 'add', 5),
(16, 'edit', 'edit', 5),
(17, 'delete', 'delete', 5),
(18, 'view', 'view', 6),
(19, 'add', 'add', 6),
(20, 'edit', 'edit', 6),
(21, 'delete', 'delete', 6),
(22, 'view', 'view', 7),
(23, 'add', 'add', 7),
(24, 'edit', 'edit', 7),
(25, 'delete', 'delete', 7),
(26, 'view', 'view', 8),
(27, 'add', 'add', 8),
(28, 'edit', 'edit', 8),
(29, 'delete', 'delete', 8),
(30, 'view', 'view', 9),
(31, 'add', 'add', 9),
(32, 'edit', 'edit', 9),
(33, 'delete', 'delete', 9),
(34, 'view', 'view', 10),
(35, 'add', 'add', 10),
(36, 'edit', 'edit', 10),
(37, 'delete', 'delete', 10),
(38, 'allow', 'allow', 11),
(39, 'view', 'view', 12),
(40, 'add', 'add', 12),
(41, 'edit', 'edit', 12),
(42, 'delete', 'delete', 12),
(43, 'view', 'view', 13),
(44, 'view', 'view', 14),
(45, 'add', 'add', 14),
(46, 'edit', 'edit', 14),
(47, 'delete', 'delete', 14),
(48, 'view', 'view', 15),
(49, 'view', 'view', 16),
(50, 'add', 'add', 41),
(51, 'edit', 'edit', 41),
(52, 'delete', 'delete', 41),
(53, 'view', 'view', 17),
(54, 'add', 'add', 17),
(55, 'edit', 'edit', 17),
(56, 'delete', 'delete', 17),
(57, 'view', 'view', 18),
(58, 'view', 'view', 19),
(59, 'view', 'view', 20),
(60, 'view', 'view', 21),
(61, 'allow', 'allow', 22),
(62, 'view', 'view', 23),
(63, 'show party name', 'show party name', 14),
(64, 'view', 'view', 25),
(65, 'add', 'add', 25),
(66, 'edit', 'edit', 25),
(67, 'delete', 'delete', 25),
(68, 'approve', 'approve', 12),
(69, 'view', 'view', 26),
(70, 'add', 'add', 26),
(71, 'edit', 'edit', 26),
(72, 'delete', 'delete', 26),
(73, 'view', 'view', 27),
(74, 'view', 'view', 28),
(75, 'view', 'view', 29),
(76, 'add', 'add', 29),
(77, 'edit', 'edit', 29),
(78, 'delete', 'delete', 29),
(79, 'view', 'view', 30),
(80, 'view', 'view', 31),
(81, 'view', 'view', 32),
(82, 'add', 'add', 32),
(83, 'view', 'view', 33),
(84, 'add', 'add', 33),
(85, 'edit', 'edit', 33),
(86, 'delete', 'delete', 33),
(87, 'view', 'view', 34),
(88, 'add', 'add', 34),
(89, 'edit', 'edit', 34),
(90, 'delete', 'delete', 34),
(91, 'customer ledger', 'customer ledger', 12),
(92, 'view', 'view', 35),
(93, 'add', 'add', 35),
(94, 'view', 'view', 36),
(95, 'view', 'view', 37),
(96, 'add', 'add', 37),
(98, 'approve', 'approve', 33),
(99, 'view', 'view', 38),
(100, 'view', 'view', 39),
(101, 'view', 'view', 40),
(102, 'add', 'add', 40),
(103, 'edit', 'edit', 40),
(104, 'delete', 'delete', 40),
(106, 'worker_hisab', 'worker_hisab', 39),
(107, 'allow_add_opening', 'allow_add_opening', 12),
(108, 'view', 'view', 41),
(109, 'view', 'view', 42),
(110, 'add', 'add', 42),
(111, 'edit', 'edit', 42),
(112, 'delete', 'delete', 42),
(113, 'sell/purchase item list', 'sell/purchase item list', 41),
(114, 'view', 'view', 44),
(115, 'add', 'add', 44),
(116, 'edit', 'edit', 44),
(117, 'delete', 'delete', 44),
(118, 'view', 'view', 45),
(119, 'add', 'add', 45),
(120, 'edit', 'edit', 45),
(121, 'delete', 'delete', 45),
(122, 'allow logout option', 'allow logout option', 7),
(123, 'allow to save gold / silver bhav out of range', 'allow to save gold / silver bhav out of range', 41),
(124, 'allow to save out of credit limit', 'allow to save out of credit limit', 41),
(125, 'view', 'view', 46),
(126, 'add', 'add', 46),
(127, 'edit', 'edit', 46),
(128, 'delete', 'delete', 46),
(129, 'allow to update date', 'allow to update date', 46),
(130, 'allow to update time', 'allow to update time', 46),
(131, 'allow to lott complete', 'allow to lott complete', 39),
(132, 'view', 'view', 43),
(133, 'allow change wastage', 'allow change wastage', 41),
(134, 'allow change wastage', 'allow change wastage', 17),
(135, 'view', 'view', 47),
(136, 'worker_hisab_handmade', 'worker_hisab_handmade', 45),
(137, 'view', 'view', 48),
(138, 'add', 'add', 48),
(139, 'edit', 'edit', 48),
(140, 'delete', 'delete', 48),
(141, 'view', 'view', 49),
(142, 'view', 'view', 50),
(143, 'add', 'add', 50),
(144, 'edit', 'edit', 50),
(145, 'delete', 'delete', 50),
(146, 'view', 'view', 51),
(147, 'add', 'add', 51),
(148, 'edit', 'edit', 51),
(149, 'delete', 'delete', 51),
(150, 'is guard', 'is guard', 17),
(151, 'allow to audit / suspect', 'allow to audit / suspect', 29),
(152, 'allow audit / suspect to pending', 'allow audit / suspect to pending', 29),
(153, 'worker_hisab_machine_chain', 'worker_hisab_machine_chain', 51),
(154, 'view', 'view', 52),
(155, 'allow to audit / suspect', 'allow to audit / suspect', 25),
(156, 'allow audit / suspect to pending', 'allow audit / suspect to pending', 25),
(157, 'allow to audit / suspect', 'allow to audit / suspect', 17),
(158, 'allow audit / suspect to pending', 'allow audit / suspect to pending', 17),
(159, 'allow to audit / suspect', 'allow to audit / suspect', 41),
(160, 'allow audit / suspect to pending', 'allow audit / suspect to pending', 41),
(161, 'allow to lott complete', 'allow to lott complete', 51),
(162, 'view', 'view', 53),
(163, 'view', 'view', 54),
(164, 'view password', 'view password', 7),
(165, 'view', 'view', 55),
(166, 'add', 'add', 55),
(167, 'edit', 'edit', 55),
(168, 'delete', 'delete', 55),
(169, 'view', 'view', 56),
(170, 'add', 'add', 56),
(171, 'edit', 'edit', 56),
(172, 'delete', 'delete', 56),
(173, 'view', 'view', 57),
(174, 'add', 'add', 57),
(175, 'edit', 'edit', 57),
(176, 'delete', 'delete', 57),
(177, 'view', 'view', 58),
(178, 'add', 'add', 58),
(179, 'edit', 'edit', 58),
(180, 'delete', 'delete', 58),
(181, 'view', 'view', 59),
(182, 'add', 'add', 59),
(183, 'edit', 'edit', 59),
(184, 'delete', 'delete', 59),
(185, 'worker_hisab_i_r_silver', 'worker_hisab_i_r_silver', 59),
(186, 'worker_hisab_casting', 'worker_hisab_casting', 58),
(187, 'view', 'view', 60),
(188, 'add', 'add', 60),
(189, 'edit', 'edit', 60),
(190, 'delete', 'delete', 60),
(191, 'allow_change_date', 'allow_change_date', 41),
(192, 'allow_change_date', 'allow_change_date', 42),
(193, 'allow_change_date', 'allow_change_date', 25),
(194, 'allow_change_date', 'allow_change_date', 29),
(195, 'allow_change_date', 'allow_change_date', 17),
(196, 'allow_change_date', 'allow_change_date', 58),
(197, 'allow_change_date', 'allow_change_date', 40),
(198, 'allow_change_date', 'allow_change_date', 59),
(199, 'allow_change_date', 'allow_change_date', 51),
(200, 'rfid_view', 'rfid_view', 20),
(201, 'rfid_add', 'rfid_add', 20),
(202, 'rfid_edit', 'rfid_edit', 20),
(203, 'rfid_delete', 'rfid_delete', 20),
(204, 'view', 'view', 61),
(205, 'add', 'add', 61),
(206, 'edit', 'edit', 61),
(207, 'delete', 'delete', 61),
(208, 'stock_check', 'stock_check', 20),
(209, 'allow to lott complete mhm', 'allow to lott complete mhm', 45);

--
-- Dumping data for table `operation`
--

INSERT INTO `operation` (`operation_id`, `operation_name`, `fix_loss`, `fix_loss_per`, `max_loss_allow`, `max_loss_wt`, `issue_finish_fix_loss`, `issue_finish_fix_loss_per`, `remark`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Meena', 0, NULL, 0, NULL, 0, NULL, NULL, '2019-10-18 00:00:00', 1, '2019-10-18 00:00:00', 1),
(2, 'Nang Setting', 0, NULL, 0, NULL, 0, NULL, NULL, '2019-10-18 00:00:00', 1, '2019-10-18 00:00:00', 1);

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`order_status_id`, `status`) VALUES
(1, 'Pending'),
(2, 'Canceled'),
(3, 'Completed');

--
-- Dumping data for table `sell_type`
--

INSERT INTO `sell_type` (`sell_type_id`, `type_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Sell', NULL, NULL, NULL, NULL),
(2, 'Purchase', NULL, NULL, NULL, NULL),
(3, 'Exchange', NULL, NULL, NULL, NULL);

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`) VALUES
(1, 'gold_rate', 'Gold Rate', '39000'),
(2, 'gold_min', 'Gold Minimum', '38850'),
(3, 'gold_max', 'Gold Maximum', '39000'),
(4, 'silver_rate', 'Silver Rate', '450'),
(5, 'silver_min', 'Silver Minimum', '450'),
(6, 'silver_max', 'Silver Maximum', '455'),
(7, 'login_time_start', 'Login From', '09:00 AM'),
(8, 'login_time_end', 'Login To', '09:00 PM'),
(9, 'without_purchase_sell_allow', 'Allow Sell Only If Stock Available', '0'),
(10, 'set_backup_email', 'Set ReCalculate', ''),
(11, 'send_otp_mobile_no', 'Send OTP Mobile No', ''),
(12, 'worker_gold_rate', 'Worker Gold Rate', '38500'),
(13, 'machine_chain_operation_solding_curb_default_value', 'Machine Chain Operation Solding Curb Default Value', '0.2'),
(14, 'machine_chain_operation_solding_box_default_value', 'Machine Chain Operation Solding Box Default Value', '0.7'),
(15, 'hallmark_xrf_print_first_line', 'XRF Print First Line', 'Ph. 0291-2623521, 9413623521'),
(16, 'theme_color_code', 'Theme Color Code', '#3c8dbc'),
(17, 'enter_key_to_next', 'Click on enter key to next Object', '0'),
(18, 'use_rfid', 'Use RFID', '0'),
(19, 'use_barcode', 'Use Barcode', '0'),
(20, 'sell_purchase_difference', 'Sell/Purchase Difference', '0'),
(21, 'show_backup_email_menu', 'Show ReCalculate Menu', '0'),
(22, 'ask_ad_charges_in_sell_purchase', 'Ask Ad Charges In Sell / Purchase', '0'),
(23, 'ask_less_ad_details_in_sell_purchase', 'Ask Less Ad Details In Sell / Purchase', '0');

--
-- Dumping data for table `user_account_group`
--

INSERT INTO `user_account_group` (`ud_id`, `user_id`, `account_group_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(54, 1, 51, 1, '2020-03-20 10:30:26', NULL, NULL),
(55, 1, 12, 1, '2020-03-20 10:30:26', NULL, NULL),
(56, 1, 21, 1, '2020-03-20 10:30:26', NULL, NULL),
(57, 1, 23, 1, '2020-03-20 10:30:26', NULL, NULL),
(58, 1, 24, 1, '2020-03-20 10:30:26', NULL, NULL),
(59, 1, 25, 1, '2020-03-20 10:30:26', NULL, NULL),
(60, 1, 26, 1, '2020-03-20 10:30:26', NULL, NULL),
(61, 1, 20, 1, '2020-03-20 10:30:26', NULL, NULL),
(62, 1, 27, 1, '2020-03-20 10:30:26', NULL, NULL),
(63, 1, 49, 1, '2020-03-20 10:30:26', NULL, NULL),
(64, 1, 28, 1, '2020-03-20 10:30:26', NULL, NULL),
(65, 1, 29, 1, '2020-03-20 10:30:26', NULL, NULL),
(66, 1, 9, 1, '2020-03-20 10:30:26', NULL, NULL),
(67, 1, 1, 1, '2020-03-20 10:30:26', NULL, NULL),
(68, 1, 13, 1, '2020-03-20 10:30:26', NULL, NULL),
(69, 1, 30, 1, '2020-03-20 10:30:26', NULL, NULL),
(70, 1, 3, 1, '2020-03-20 10:30:26', NULL, NULL),
(71, 1, 14, 1, '2020-03-20 10:30:26', NULL, NULL),
(72, 1, 15, 1, '2020-03-20 10:30:26', NULL, NULL),
(73, 1, 4, 1, '2020-03-20 10:30:26', NULL, NULL),
(74, 1, 31, 1, '2020-03-20 10:30:26', NULL, NULL),
(75, 1, 5, 1, '2020-03-20 10:30:26', NULL, NULL),
(76, 1, 6, 1, '2020-03-20 10:30:26', NULL, NULL),
(77, 1, 32, 1, '2020-03-20 10:30:26', NULL, NULL),
(78, 1, 22, 1, '2020-03-20 10:30:26', NULL, NULL),
(79, 1, 33, 1, '2020-03-20 10:30:26', NULL, NULL),
(80, 1, 55, 1, '2020-03-20 10:30:26', NULL, NULL),
(81, 1, 16, 1, '2020-03-20 10:30:26', NULL, NULL),
(82, 1, 17, 1, '2020-03-20 10:30:26', NULL, NULL),
(83, 1, 11, 1, '2020-03-20 10:30:26', NULL, NULL),
(84, 1, 34, 1, '2020-03-20 10:30:26', NULL, NULL),
(85, 1, 35, 1, '2020-03-20 10:30:26', NULL, NULL),
(86, 1, 7, 1, '2020-03-20 10:30:26', NULL, NULL),
(87, 1, 36, 1, '2020-03-20 10:30:26', NULL, NULL),
(88, 1, 18, 1, '2020-03-20 10:30:26', NULL, NULL),
(89, 1, 19, 1, '2020-03-20 10:30:26', NULL, NULL),
(90, 1, 8, 1, '2020-03-20 10:30:26', NULL, NULL),
(91, 1, 54, 1, '2020-03-20 10:30:26', NULL, NULL),
(92, 1, 37, 1, '2020-03-20 10:30:26', NULL, NULL),
(93, 1, 47, 1, '2020-03-20 10:30:26', NULL, NULL),
(94, 1, 38, 1, '2020-03-20 10:30:26', NULL, NULL),
(95, 1, 39, 1, '2020-03-20 10:30:26', NULL, NULL),
(96, 1, 40, 1, '2020-03-20 10:30:26', NULL, NULL),
(97, 1, 41, 1, '2020-03-20 10:30:26', NULL, NULL),
(98, 1, 42, 1, '2020-03-20 10:30:26', NULL, NULL),
(99, 1, 48, 1, '2020-03-20 10:30:26', NULL, NULL),
(100, 1, 45, 1, '2020-03-20 10:30:26', NULL, NULL),
(101, 1, 10, 1, '2020-03-20 10:30:26', NULL, NULL),
(102, 1, 2, 1, '2020-03-20 10:30:26', NULL, NULL),
(103, 1, 46, 1, '2020-03-20 10:30:26', NULL, NULL),
(104, 1, 52, 1, '2020-03-20 10:30:26', NULL, NULL),
(105, 1, 53, 1, '2020-03-20 10:30:26', NULL, NULL);

--
-- Dumping data for table `user_department`
--

INSERT INTO `user_department` (`ud_id`, `user_id`, `department_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(346, 1, 358, 1, '2020-03-20 10:30:26', NULL, NULL),
(347, 1, 359, 1, '2020-03-20 10:30:26', NULL, NULL),
(349, 1, 361, 1, '2020-03-20 10:30:26', NULL, NULL);

--
-- Dumping data for table `user_master`
--

INSERT INTO `user_master` (`user_id`, `user_name`, `login_username`, `user_mobile`, `user_type`, `is_cad_designer`, `default_department_id`, `user_password`, `salary`, `blood_group`, `allow_all_accounts`, `selected_accounts`, `files`, `default_user_photo`, `status`, `is_login`, `socket_id`, `otp_value`, `otp_on_user`, `designation`, `aadhaar_no`, `pan_no`, `licence_no`, `voter_id_no`, `esi_no`, `pf_no`, `date_of_birth`, `order_display_only_assigned_account`, `bank_name`, `bank_branch`, `bank_acc_name`, `bank_acc_no`, `bank_ifsc`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'admin', 'admin', '2912700007', 1, 0, 361, 'admin', 10000, '', 1, NULL, NULL, NULL, 0, 0, '-44zIq4zzj-vF1OmAABG', '783869', NULL, 'md', '123456789123', '', '', '', '', '', NULL, 1, '', '', '', '', '', 1, '2019-09-16 17:57:53', 1, '2020-03-20 10:30:26');

--
-- Dumping data for table `user_order_department`
--

INSERT INTO `user_order_department` (`ud_id`, `user_id`, `department_id`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 1, 361, 1, '2020-03-20 10:30:26', NULL, NULL);

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `user_id`, `website_module_id`, `role_type_id`) VALUES
(5890, 1, 1, 1),
(5891, 1, 2, 2),
(5892, 1, 2, 3),
(5893, 1, 2, 4),
(5894, 1, 2, 5),
(5895, 1, 3, 6),
(5896, 1, 3, 8),
(5897, 1, 4, 10),
(5898, 1, 4, 11),
(5899, 1, 4, 12),
(5900, 1, 4, 13),
(5901, 1, 6, 18),
(5902, 1, 6, 19),
(5903, 1, 6, 20),
(5904, 1, 6, 21),
(5905, 1, 7, 22),
(5906, 1, 7, 23),
(5907, 1, 7, 24),
(5908, 1, 7, 25),
(5909, 1, 7, 122),
(5910, 1, 7, 164),
(5911, 1, 9, 30),
(5912, 1, 9, 31),
(5913, 1, 9, 32),
(5914, 1, 9, 33),
(5915, 1, 10, 34),
(5916, 1, 10, 35),
(5917, 1, 10, 36),
(5918, 1, 10, 37),
(5919, 1, 11, 38),
(5920, 1, 26, 69),
(5921, 1, 26, 70),
(5922, 1, 26, 71),
(5923, 1, 26, 72),
(5924, 1, 34, 87),
(5925, 1, 34, 88),
(5926, 1, 34, 89),
(5927, 1, 34, 90),
(5928, 1, 61, 204),
(5929, 1, 61, 205),
(5930, 1, 61, 206),
(5931, 1, 61, 207),
(5932, 1, 12, 39),
(5933, 1, 12, 40),
(5934, 1, 12, 41),
(5935, 1, 12, 42),
(5936, 1, 12, 68),
(5937, 1, 12, 91),
(5938, 1, 12, 107),
(5939, 1, 48, 137),
(5940, 1, 48, 138),
(5941, 1, 48, 139),
(5942, 1, 48, 140);

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `user_type`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Worker'),
(4, 'Salesman');

--
-- Dumping data for table `website_modules`
--

INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES
(1, 'Master', '1'),
(2, 'Master >> Category', '1.1'),
(3, 'Master >> Method', '1.2'),
(4, 'Master >> Tunch', '1.3'),
(6, 'Master >> Item Master', '1.5'),
(7, 'Master >> User Master', '1.6'),
(9, 'Master >> State', '1.8'),
(10, 'Master >> City', '1.9'),
(11, 'Master >> User Rights', '1.10'),
(12, 'Account', '2'),
(13, 'Order', '3'),
(14, 'Order >> Order', '3.1'),
(15, 'Order >> Order Slider', '3.2'),
(16, 'Sell/Purchase', '4'),
(17, 'Stock Transfer', '5'),
(18, 'Reports', '6'),
(19, 'Reports >> Daybook', '6.1'),
(20, 'Reports >> Stock Status', '6.2'),
(21, 'Reports >> Outstanding', '6.3'),
(22, 'Index', '7'),
(23, 'Demo', '8'),
(25, 'Journal', '9'),
(26, 'Master >> Design Master', '1.11'),
(27, 'Reports >> Goldbook', '6.2'),
(28, 'Reports >> Silverbook', '6.2'),
(29, 'Cashbook', '10'),
(30, 'Stock Adjust', '11'),
(31, 'Cash Adjust', '12'),
(32, 'Yearly Leaves', '13'),
(33, 'Apply Leave', '14'),
(34, 'Master >> Opening Stock', '1.12'),
(35, 'Present Hours', '15'),
(36, 'Reports >> Interest', '6.4'),
(37, 'Employee Salary', '16'),
(38, 'HR', '17'),
(39, 'Manufacture', '18'),
(40, 'Manufacture >> Issue/Receive', '18.1'),
(41, 'Sell/Purchase >> Sell/Purchase', '4.1'),
(42, 'Sell/Purchase >> Other Entry', '4.2'),
(43, 'Reports >> Balance Sheet', '6.3'),
(44, 'Manufacture >> Operation', '18.2'),
(45, 'Manufacture >> Hand Made', '18.3'),
(46, 'HR >> Attendance', '17.1'),
(47, 'Balance', '19'),
(48, 'Account >> Account Group', ' 2.1'),
(49, 'Server Shutdown', ' 20'),
(50, 'Manufacture >> Machine Chain Operation', '18.4'),
(51, 'Manufacture >> Machine Chain Entry', '18.5'),
(52, 'Reports >> Trading PL', '6.5'),
(53, 'Hallmark', '21'),
(54, 'Reports >> Calculations', '6.6'),
(55, 'Hallmark >> Receipt', '21.1'),
(56, 'Hallmark >> Xrf', '21.2'),
(57, 'Hallmark >> Item Master', '21.0'),
(58, 'Manufacture >> Casting', '18.3.1'),
(59, 'Manufacture >> I/R Silver', '18.1.1'),
(60, 'Reports >> Reminder', '6.7'),
(61, 'Master >> AD Master', '1.13');
