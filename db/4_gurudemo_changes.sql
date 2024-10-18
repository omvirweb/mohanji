-- Avinash : 2020_03_16 11:00 AM
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'allow to audit mhm', 'allow to audit mhm', '45');

-- Avinash : 2020_03_17 05:45 AM
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 1;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 2;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 3;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 4;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 5;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 6;
UPDATE `settings` SET `fields_section` = '2' WHERE `settings`.`settings_id` = 20;
UPDATE `settings` SET `fields_section` = '2' WHERE `settings`.`settings_id` = 22;
UPDATE `settings` SET `fields_section` = '2' WHERE `settings`.`settings_id` = 23;
UPDATE `settings` SET `fields_section` = '1' WHERE `settings`.`settings_id` = 12;
UPDATE `settings` SET `settings_label` = 'Sell/Purchase Menu Separately' WHERE `settings`.`settings_id` = 20;
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (24, 'use_category', 'Use Category', '1', '0');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (25, 'department_2', 'Department', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (26, 'remark_2', 'Remark', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (27, 'delivered_not_2', 'Delivered / Not', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (28, 'tunch_textbox_2', 'Tunch Textbox', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (29, 'charges_2', 'Charges', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (30, 'less_netwt_2', 'Less NetWt', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (31, 'wstg_2', 'Wstg', '1', '2');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (32, 'lineitem_image_2', 'Lineitem Image', '1', '2');
UPDATE `settings` SET `fields_section` = '3' WHERE `settings`.`settings_id` = 7;
UPDATE `settings` SET `fields_section` = '3' WHERE `settings`.`settings_id` = 8;
UPDATE `settings` SET `fields_section` = '3' WHERE `settings`.`settings_id` = 11;

-- Chirag : 2020_03_26 05:10 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`) VALUES (33, 'price_per_pcs', 'Price / Per Pcs', '0');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'Allow To change Price / Per Pcs', 'Allow To change Price / Per Pcs', '56');

-- Avinash : 2020_04_22 06:30 AM
UPDATE `settings` SET `fields_section` = '4' WHERE `settings`.`settings_id` = 13;
UPDATE `settings` SET `fields_section` = '4' WHERE `settings`.`settings_id` = 14;

-- Avinash : 2020_05_11 09:45 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (34, 'manu_hand_made_lott_complete_in', 'Manu. Hand Made Lott Complete in', '3', '4');

-- Avinash : 2020_05_11 09:45 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (35, 'xrf_box_no_mandatory', 'XRF Box No. Mandatory', '0', '5');
UPDATE `settings` SET `fields_section` = '5' WHERE `settings`.`settings_id` = 15;

-- Avinash : 2020_05_23 02:00 AM
INSERT INTO `account` (`account_id`, `account_name`, `account_phone`, `account_mobile`, `account_email_ids`, `account_address`, `account_state`, `account_city`, `account_postal_code`, `account_gst_no`, `account_pan`, `account_aadhaar`, `account_contect_person_name`, `account_group_id`, `opening_balance`, `interest`, `credit_debit`, `opening_balance_in_gold`, `gold_ob_credit_debit`, `opening_balance_in_silver`, `silver_ob_credit_debit`, `opening_balance_in_rupees`, `rupees_ob_credit_debit`, `bank_name`, `bank_account_no`, `ifsc_code`, `bank_interest`, `gold_fine`, `silver_fine`, `amount`, `credit_limit`, `balance_date`, `status`, `user_id`, `user_name`, `is_supplier`, `password`, `min_price`, `chhijjat_per_100_ad`, `meena_charges`, `price_per_pcs`, `is_active`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(7, 'XRF / HM / Laser PL', '', NULL, '', '', NULL, NULL, '', '', '', '', '', 9, NULL, 0, NULL, 0, 1, 0, 1, 0, 1, '', '', '', 0, 0, 0, 0, 0, '2020-05-23 14:15:10', 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, 1, '2020-05-23 14:15:10', 1, '2020-05-23 14:15:10'),
(8, 'XRF / HM / Laser', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, NULL, NULL, NULL, 0, 1, 0, 1, 0, 1, NULL, NULL, NULL, NULL, 0, 0, 0, 0, NULL, 1, 0, NULL, 0, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, NULL, NULL);

-- Avinash : 2020_05_26 10:15 AM
UPDATE `hallmark_xrf_items` LEFT JOIN `carat` ON `carat`.`carat_id` = `hallmark_xrf_items`.`purity` SET `hallmark_xrf_items`.`purity`= `carat`.`purity`;

-- Avinash : 2020_05_27 02:30 PM
UPDATE `account` SET `account_state` = '2' WHERE `account`.`account_id` = 1;
UPDATE `account` SET `account_state` = '2' WHERE `account`.`account_id` = 4;

-- Avinash : 2020_06_11 07:15 PM
UPDATE `settings` SET `settings_label` = 'Manufacture Lott Complete in' WHERE `settings`.`settings_id` = 34;
UPDATE `settings` SET `settings_key` = 'manufacture_lott_complete_in' WHERE `settings`.`settings_id` = 34;

-- Avinash : 2020_06_16 01:20 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (36, 'company_name', 'Company Name', 'Jewellers', '6');

-- Avinash : 2020_06_16 04:00 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (37, 'company_contact', 'Company Contact', '', '6');
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (38, 'company_address', 'Company Address', '', '6');

-- Avinash : 2020_06_16 09:45 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (39, 'rate_on', 'Rate on', '2', 1);

-- Avinash : 2020_06_26 09:50 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (40, 'issue_receive_karigar_wastage', 'Issue Receive Karigar Wastage Post To Account?', '0', 4);

-- Avinash : 2020_06_27 06:10 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (41, 'ask_discount_in_sell_purchase', 'Ask Discount in Sell / Purchase', '0', 2);

-- Avinash : 2020_07_22 04:05 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (42, 'display_net_amount_in_outstanding', 'Display Net Amount in Outstanding', '1', 0);

-- Avinash : 2020_08_02 09:30 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (43, 'c_r_amount_separate', 'C/R Amount Separate', '0', 2);

-- Avinash : 2020_08_13 11:30 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (44, 'approx_amount', 'Approx Amount', '1', 2);

-- Avinash : 2020_08_17 09:30 AM
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'adjust c/r amount allowed', 'adjust c/r amount allowed', '41');

-- Avinash : 2020_10_26 04:15 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (45, 'sell_purchase_type_2', 'Sell/Purchase Type 2', '0', 2);

-- Avinash : 2021_03_09 01:50 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (46, 'sell_purchase_type_3', 'Sell/Purchase Type 3', '0', 2);

-- Avinash : 2021_03_09 04:30 PM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES (62, 'Master >> Stamp', '1.14');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '62');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '62');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '62');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '62');

-- Avinash : 2021_03_25 03:45 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (47, 'account_mobile_no_is_required', 'Account Mobile No is Required', '1', 0);
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (48, 'line_item_remark', 'Line Item Remark', '0', 2);

-- Avinash : 2021_04_12 10:30 AM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES (63, 'Import Data', '5.1');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'allow', 'allow', '63');

-- Avinash : 2021_03_25 03:45 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (49, 'line_item_gold_silver_rate', 'Line Item Rate', '0', 2);

-- Chirag : 2021_04_30 12:30 PM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES (64, 'Master >> company Details', '1.15');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '64');

INSERT INTO `company_details` (`company_id`, `company_name`, `company_gst_no`, `company_phone`, `company_mobile`, `company_address`, `company_postal_code`, `company_state_id`, `company_city_id`, `updated_by`, `updated_at`) VALUES
(1, 'ABC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Avinash : 2021_05_03 10:00 AM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (50, 'display_line_item_remark_in_ledger', 'Display Line Item Remark in Ledger', '0', 2);
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (51, 'display_line_item_remark_in_print', 'Display Line Item Remark in Print', '0', 2);

-- Avinash : 2021_05_05 06:20 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (52, 'sell_purchase_entry_with_gst', 'Sell Purchase Entry with GST', '0', 2);

-- Chirag : 2021_05_06 08:30 PM
INSERT INTO `website_modules` (`website_module_id`, `title`, `main_module`) VALUES (65, 'Manufacture >> Refinery', '18.4');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'view', 'view', '65');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'add', 'add', '65');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'edit', 'edit', '65');
INSERT INTO `module_roles` (`module_role_id`, `title`, `role_name`, `website_module_id`) VALUES (NULL, 'delete', 'delete', '65');

-- Avinash : 2021_07_09 12:20 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (53, 'sell_purchase_print_display_gold_fine_column', 'Sell Purchase Print Display Gold Fine Column', '1', 2);

-- Avinash : 2021_07_09 07:00 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (54, 'ledger_print_in_page_a5', 'Ledger Print in Page A5(default A4)', '0', 0);

-- Avinash : 2021_07_26 01:45 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (55, 'default_from_financial_start_year', 'Default From "01-04-Financial Start Year"', '0', 0);

-- Avinash : 2021_08_12 03:40 PM
INSERT INTO `settings` (`settings_id`, `settings_key`, `settings_label`, `settings_value`, `fields_section`) VALUES (56, 'inventory_data_modules', 'Inventory Data Modules', '0', 0);
