-- Avinash : 2020_03_16 11:00 AM
ALTER TABLE `manu_hand_made` ADD `audit_status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2' AFTER `total_receive_fine`;

-- Avinash : 2020_03_17 05:45 AM
ALTER TABLE `settings` ADD `fields_section` INT(11) NOT NULL DEFAULT '0' AFTER `settings_value`;
ALTER TABLE `settings` CHANGE `fields_section` `fields_section` INT(11) NOT NULL DEFAULT '0' COMMENT '0 = General, 1 = Rate, 2 = Sell/Purchase, 3 = Login';

-- Chirag : 2020_03_16 05:15 PM
CREATE TABLE `bullion` (
  `bullion_id` int(11) NOT NULL,
  `sell_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `remark` text,
  `purchase_gr_wt` double DEFAULT NULL,
  `purchase_tunch` double DEFAULT NULL,
  `purchase_fine` double DEFAULT NULL,
  `payment_amount` double DEFAULT NULL,
  `receipt_amount` double DEFAULT NULL,
  `gold_bhav_type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Sell, 2 = Purchase',
  `gold_bhav_gr_wt` double DEFAULT NULL,
  `gold_bhav_rate` double DEFAULT NULL,
  `gold_bhav_amount` double DEFAULT NULL,
  `sell_gr_wt` double DEFAULT NULL,
  `sell_tunch` double DEFAULT NULL,
  `sell_fine` double DEFAULT NULL,
  `auto_fill_pending_wt` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `bullion`
  ADD PRIMARY KEY (`bullion_id`);

ALTER TABLE `bullion`
  MODIFY `bullion_id` int(11) NOT NULL AUTO_INCREMENT;

-- Chirag : 2020_03_17 05:15 PM
ALTER TABLE `bullion` ADD `payment_no` INT(11) NULL DEFAULT NULL AFTER `purchase_fine`;

-- Chirag : 2020_03_18 12:15 PM
DROP TABLE IF EXISTS `bullion`;
CREATE TABLE `tunch_testing` (
  `tunch_testing_id` int(11) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `remark` text,
  `purchase_gr_wt` double DEFAULT NULL,
  `purchase_tunch` double DEFAULT NULL,
  `purchase_fine` double DEFAULT NULL,
  `payment_no` int(11) DEFAULT NULL,
  `payment_amount` double DEFAULT NULL,
  `receipt_amount` double DEFAULT NULL,
  `gold_bhav_type_id` tinyint(1) DEFAULT NULL COMMENT '1 = Sell, 2 = Purchase',
  `gold_bhav_gr_wt` double DEFAULT NULL,
  `gold_bhav_rate` double DEFAULT NULL,
  `gold_bhav_amount` double DEFAULT NULL,
  `sell_gr_wt` double DEFAULT NULL,
  `sell_tunch` double DEFAULT NULL,
  `sell_fine` double DEFAULT NULL,
  `auto_fill_pending_wt` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tunch_testing`
  ADD PRIMARY KEY (`tunch_testing_id`);

ALTER TABLE `tunch_testing`
  MODIFY `tunch_testing_id` int(11) NOT NULL AUTO_INCREMENT;

-- Chirag : 2020_03_26 05:10 PM
ALTER TABLE `carat` ADD `show_in_xrf` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = No, 1 = Yes' AFTER `purity`;

-- Avinash : 2020_04_22 06:30 AM
ALTER TABLE `settings` CHANGE `fields_section` `fields_section` INT(11) NOT NULL DEFAULT '0' COMMENT '0 = General, 1 = Rate, 2 = Sell/Purchase, 3 = Login, 4 = Manufacturing';


-- --------------------------------------------------------

--
-- Table structure for table `kaleyra_webhook_demo`
--

CREATE TABLE `kaleyra_webhook_demo` (
  `id` int(11) NOT NULL,
  `webhook_content` text CHARACTER SET utf8,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `kaleyra_webhook_demo`
--
ALTER TABLE `kaleyra_webhook_demo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `kaleyra_webhook_demo`
--
ALTER TABLE `kaleyra_webhook_demo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Avinash : 2020_05_09 05:00 AM
ALTER TABLE `opening_stock` ADD `purchase_sell_item_id` INT(11) NULL DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id : From where stock build' AFTER `fine`, ADD `stock_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive , 5 = MHM Receive Finish, 6 = MHM Receive Scrap, 7 = MC Receive Finish, 8 = MC Receive Scrap, 9 = Casting Entry Receive Finish, 10 = Casting Entry Receive Scrap, 11 = Opening Stock' AFTER `purchase_sell_item_id`;

-- Avinash : 2020_05_21 05:00 PM
ALTER TABLE `hallmark_xrf_items` ADD `hm_ls_option` TINYINT(1) NOT NULL DEFAULT '1' AFTER `remark`;
ALTER TABLE `hallmark_xrf_items` ADD `deliver_status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `hm_ls_option`;

-- Avinash : 2020_05_23 02:30 AM
ALTER TABLE `payment_receipt` ADD `xrf_id` INT(11) NULL DEFAULT NULL AFTER `other_id`;
ALTER TABLE `hallmark_xrf` ADD `total_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `other_charges`;

-- Avinash : 2020_05_26 10:15 AM
ALTER TABLE `hallmark_xrf_items` CHANGE `purity` `purity` INT(11) NULL DEFAULT NULL COMMENT 'purity';

-- Avinash : 2020_05_28 08:50 AM
ALTER TABLE `hallmark_xrf_items` CHANGE `weight` `rec_weight` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `hallmark_xrf_items` ADD `paid_weight` DOUBLE NULL DEFAULT NULL AFTER `rec_weight`;

-- Avinash : 2020_05_28 05:00 PM
ALTER TABLE `sell` ADD `li_total_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `audit_status`, ADD `net_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `li_total_amount`, ADD `cgst_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `net_amount`, ADD `sgst_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `cgst_amount`, ADD `igst_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `sgst_amount`, ADD `bill_total_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `igst_amount`;
ALTER TABLE `sell` ADD `discount_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `li_total_amount`;
ALTER TABLE `sell_items` ADD `rate_per_1_gram` DOUBLE NOT NULL DEFAULT '0' AFTER `image`, ADD `gross_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `rate_per_1_gram`, ADD `gst_rate_in` TINYINT(1) NOT NULL DEFAULT '2' COMMENT '1 = Including, 2 = Excluding' AFTER `gross_amount`, ADD `gst_per` DOUBLE NOT NULL DEFAULT '0' AFTER `gst_rate_in`, ADD `labout_other_charges` DOUBLE NOT NULL DEFAULT '0' AFTER `gst_per`, ADD `amount` DOUBLE NOT NULL DEFAULT '0' AFTER `labout_other_charges`, ADD `li_narration` TEXT NULL DEFAULT NULL AFTER `amount`;

--
-- Avinash : 2020_06_02 02:30 PM
-- Table structure for table `sell_item_charges_details`
--

CREATE TABLE `sell_item_charges_details` (
  `sell_item_charges_details_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `sell_item_id` int(11) DEFAULT NULL,
  `sell_item_charges_details_ad_id` int(11) DEFAULT NULL,
  `sell_item_charges_details_ad_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `sell_item_charges_details`
--
ALTER TABLE `sell_item_charges_details`
  ADD PRIMARY KEY (`sell_item_charges_details_id`),
  ADD KEY `sell_item_charges_details_ad_id` (`sell_item_charges_details_ad_id`);

--
-- AUTO_INCREMENT for table `sell_item_charges_details`
--
ALTER TABLE `sell_item_charges_details`
  MODIFY `sell_item_charges_details_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `sell_item_charges_details`
--
ALTER TABLE `sell_item_charges_details`
  ADD CONSTRAINT `Fk_sell_item_charges_details` FOREIGN KEY (`sell_item_charges_details_ad_id`) REFERENCES `ad` (`ad_id`);

-- Avinash : 2020_06_09 08:45 AM
ALTER TABLE `sell_item_charges_details` ADD `sell_item_charges_details_net_wt` DOUBLE NULL DEFAULT NULL AFTER `sell_item_charges_details_ad_id`, ADD `sell_item_charges_details_per_gram` DOUBLE NULL DEFAULT NULL AFTER `sell_item_charges_details_net_wt`;

-- Avinash : 2020_06_12 11:00 AM
ALTER TABLE `issue_receive_details` ADD `wastage` DOUBLE NULL DEFAULT NULL AFTER `fine`, ADD `calculated_wastage` DOUBLE NULL DEFAULT NULL AFTER `wastage`;
ALTER TABLE `issue_receive` ADD `ir_diffrence` DOUBLE NULL DEFAULT NULL AFTER `hisab_done`, ADD `worker_gold_rate` DOUBLE NULL DEFAULT NULL AFTER `ir_diffrence`;
ALTER TABLE `journal` CHANGE `is_module` `is_module` INT(10) NULL DEFAULT NULL COMMENT '1 = Manufacture Hand Made, 2=Employee Salary, 3 = Issue/Receive';
ALTER TABLE `issue_receive` ADD `journal_id` INT(11) NULL DEFAULT NULL COMMENT 'Lott Complete to diff. fine amount Worker <> MF Loss journal_id ' AFTER `total_receive_fine`;

-- Avinash : 2020_06_16 01:20 PM
ALTER TABLE `settings` CHANGE `fields_section` `fields_section` INT(11) NOT NULL DEFAULT '0' COMMENT '0 = General, 1 = Rate, 2 = Sell/Purchase, 3 = Login, 4 = Manufacturing, 5 = XRF / HM / Laser, 6 = Company Details';

-- Avinash : 2020_06_18 09:30 AM
ALTER TABLE `opening_stock` ADD `design_no` VARCHAR(255) NULL DEFAULT NULL AFTER `fine`, ADD `rfid_number` VARCHAR(255) NULL DEFAULT NULL AFTER `design_no`;

-- Avinash : 2020_06_26 01:30 AM
ALTER TABLE `sell_items` ADD `spi_pcs` DOUBLE NOT NULL DEFAULT '0' AFTER `rfid_number`, ADD `spi_rate` DOUBLE NOT NULL DEFAULT '0' AFTER `spi_pcs`;

-- Avinash : 2020_06_28 09:40 AM
ALTER TABLE `account` ADD `account_remarks` TEXT NULL DEFAULT NULL AFTER `account_group_id`;

-- Avinash : 2020_07_21 10:15 AM
ALTER TABLE `sell` DROP `cgst_amount`, DROP `sgst_amount`, DROP `igst_amount`;
ALTER TABLE `sell_items` DROP `gst_rate_in`, DROP `gst_per`;
ALTER TABLE `sell` ADD `bill_financial_year` VARCHAR(20) NULL DEFAULT NULL AFTER `sell_no`;
ALTER TABLE `sell` DROP `li_total_amount`, DROP `net_amount`, DROP `bill_total_amount`;

-- Avinash : 2020_08_10 11:00 AM
-- Table structure for table `sell_adjust_cr`
--

CREATE TABLE `sell_adjust_cr` (
  `sell_adjust_cr_id` int(11) NOT NULL,
  `sell_id` int(11) NOT NULL DEFAULT '0',
  `adjust_to` int(11) NOT NULL DEFAULT '1' COMMENT '1 = R Amt To C Amt, 2 = C Amt To R Amt',
  `adjust_cr_amount` double NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `sell_adjust_cr`
--
ALTER TABLE `sell_adjust_cr`
  ADD PRIMARY KEY (`sell_adjust_cr_id`);

--
-- AUTO_INCREMENT for table `sell_adjust_cr`
--
ALTER TABLE `sell_adjust_cr`
  MODIFY `sell_adjust_cr_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `gold_bhav` ADD `gold_cr_effect` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = C Amt, 2 = R Amt' AFTER `gold_value`;
ALTER TABLE `silver_bhav` ADD `silver_cr_effect` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = C Amt, 2 = R Amt' AFTER `silver_value`;

-- Avinash : 2020_08_15 03:30 PM
ALTER TABLE `account` ADD `c_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `amount`, ADD `r_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amount`;
UPDATE `account` SET `c_amount` = `amount` WHERE 1;
ALTER TABLE `sell` ADD `total_c_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `total_amount`, ADD `total_r_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `total_c_amount`;

-- Avinash : 2020_08_18 03:30 PM
ALTER TABLE `sell_item_charges_details` ADD `sell_item_charges_details_remark` TEXT NULL DEFAULT NULL AFTER `sell_item_charges_details_ad_amount`;

-- Avinash : 2020_08_21 03:30 AM
ALTER TABLE `opening_stock` ADD `opening_pcs` DOUBLE NOT NULL DEFAULT '0' AFTER `rfid_number`;

-- Avinash : 2020_08_24 02:00 AM
ALTER TABLE `sell_items` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `payment_receipt` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `gold_bhav` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `gold_cr_effect`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `silver_bhav` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `silver_cr_effect`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `transfer` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `transfer_amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `sell_ad_charges` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `ad_amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
ALTER TABLE `sell_adjust_cr` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `adjust_cr_amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;
UPDATE `account` SET `c_amount` = 0, `r_amount` = 0 WHERE 1;
UPDATE `account` SET `c_amount` = `amount` WHERE 1;

ALTER TABLE `account` ADD `opening_balance_in_c_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `rupees_ob_credit_debit`, ADD `c_amount_ob_credit_debit` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = Credit, 2 = Debit' AFTER `opening_balance_in_c_amount`, ADD `opening_balance_in_r_amount` INT NOT NULL DEFAULT '0' AFTER `c_amount_ob_credit_debit`, ADD `r_amount_ob_credit_debit` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = Credit, 2 = Debit' AFTER `opening_balance_in_r_amount`;
UPDATE `account` SET `opening_balance_in_c_amount` = ABS(`amount`), `c_amount_ob_credit_debit` = IF(`amount` < 0, 1, 2) WHERE 1;

-- Avinash : 2020_08_25 02:30 PM
ALTER TABLE `journal_details` ADD `c_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `amount`, ADD `r_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `c_amt`;

-- Avinash : 2020_09_11 09:40 AM
ALTER TABLE `sell_ad_charges` ADD `ad_charges_remark` TEXT NULL DEFAULT NULL AFTER `r_amt`;

-- Avinash : 2020_11_10 06:00 PM
ALTER TABLE `sell_items` ADD `stone_wt` DOUBLE NULL DEFAULT NULL AFTER `grwt`, ADD `sijat` DOUBLE NULL DEFAULT NULL AFTER `stone_wt`;
ALTER TABLE `sell_items` ADD `wastage_labour` INT(11) NULL DEFAULT NULL AFTER `fine`, ADD `wastage_labour_value` DOUBLE NULL DEFAULT NULL AFTER `wastage_labour`, ADD `labour_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `wastage_labour_value`, ADD `gold_silver_rate` DOUBLE NOT NULL DEFAULT '0' AFTER `labour_amount`, ADD `gold_silver_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `gold_silver_rate`, ADD `stone_qty` DOUBLE NOT NULL DEFAULT '0' AFTER `gold_silver_amount`, ADD `stone_rs` DOUBLE NOT NULL DEFAULT '0' AFTER `stone_qty`;

-- Avinash : 2021_03_09 04:30 PM
-- --------------------------------------------------------
-- Table structure for table `stamp`
--

CREATE TABLE `stamp` (
  `stamp_id` int(11) NOT NULL,
  `stamp_name` varchar(222) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '1=deleted,0=not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `stamp`
--
ALTER TABLE `stamp`
  ADD PRIMARY KEY (`stamp_id`);

--
-- AUTO_INCREMENT for table `stamp`
--
ALTER TABLE `stamp`
  MODIFY `stamp_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sell_items` ADD `stamp_id` INT(11) NULL DEFAULT NULL AFTER `item_id`;
ALTER TABLE `sell_items` ADD `spi_loss_for` DOUBLE NOT NULL DEFAULT '0' AFTER `net_wt`, ADD `spi_loss` DOUBLE NOT NULL DEFAULT '0' AFTER `spi_loss_for`;
ALTER TABLE `sell_items` ADD `spi_labour_on` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = PCS, 2 = NetWt@1000' AFTER `wastage_labour_value`;

-- Avinash : 2021_03_23 05:30 PM
ALTER TABLE `journal` ADD `ibs_tran_particular` TEXT NULL DEFAULT NULL AFTER `audit_status`, ADD `ibs_inst_num` VARCHAR(255) NULL DEFAULT NULL AFTER `ibs_tran_particular`, ADD `ibs_deposit_branch` VARCHAR(255) NULL DEFAULT NULL AFTER `ibs_inst_num`;

ALTER TABLE `kaleyra_webhook_demo` ADD `message_body` TEXT NULL DEFAULT NULL AFTER `id`, ADD `message_from` VARCHAR(15) NULL DEFAULT NULL AFTER `message_body`, ADD `message_name` VARCHAR(255) NULL DEFAULT NULL AFTER `message_from`, ADD `message_type` VARCHAR(255) NULL DEFAULT NULL AFTER `message_name`, ADD `message_created_at` VARCHAR(255) NULL DEFAULT NULL AFTER `message_type`, ADD `message_wanumber` VARCHAR(15) NULL DEFAULT NULL AFTER `message_created_at`;

ALTER TABLE `kaleyra_webhook_demo` ADD `message_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `kaleyra_webhook_demo` ADD `message_status` VARCHAR(50) NULL DEFAULT NULL AFTER `message_type`;

-- Avinash : 2021_04_17 06:30 PM
ALTER TABLE `gold_bhav` ADD `through_lineitem` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = Gold Bhav entry through Lineitem' AFTER `gold_narration`;
ALTER TABLE `silver_bhav` ADD `through_lineitem` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = Silver Bhav entry through Lineitem' AFTER `silver_narration`;

-- Chirag : 2021_04_30 12:30 PM
CREATE TABLE `company_details` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_gst_no` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_mobile` varchar(255) DEFAULT NULL,
  `company_address` text,
  `company_postal_code` varchar(255) DEFAULT NULL,
  `company_state_id` int(11) DEFAULT NULL,
  `company_city_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `company_details`
  ADD PRIMARY KEY (`company_id`);

ALTER TABLE `company_details`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

-- Avinash : 2021_04_30 10:15 AM
ALTER TABLE `item_master` ADD `rate_on` DOUBLE NOT NULL DEFAULT '1' AFTER `sequence_no`;
ALTER TABLE `other_items` ADD `rate_on` DOUBLE NOT NULL DEFAULT '1' AFTER `rate`;

-- Avinash : 2021_05_03 12:15 PM
ALTER TABLE `category` ADD `hsn_code` VARCHAR(255) NULL DEFAULT NULL AFTER `category_group_id`, ADD `gst_rate` DOUBLE NOT NULL DEFAULT '0' AFTER `hsn_code`;

-- Avinash : 2021_05_05 10:30 AM
ALTER TABLE `sell` ADD `ship_to_name` VARCHAR(255) NULL DEFAULT NULL AFTER `sell_remark`, ADD `ship_to_address` TEXT NULL DEFAULT NULL AFTER `ship_to_name`;
ALTER TABLE `sell_items` ADD `hsn_code` VARCHAR(255) NULL DEFAULT NULL AFTER `stamp_id`;
ALTER TABLE `sell_items` ADD `gst_rate` DOUBLE NOT NULL DEFAULT '0' AFTER `labout_other_charges`, ADD `tax` DOUBLE NOT NULL DEFAULT '0' AFTER `gst_rate`;
ALTER TABLE `sell` ADD `entry_through` TINYINT(2) NOT NULL DEFAULT '1' COMMENT '1 = Sell/Purchase Default, 2 = Sell/Purchase Type 2, 3 = Sell/Purchase Type 3, 4 = Sell/Purchase Entry with GST' AFTER `discount_amount`;

-- Chirag : 2021_05_06 08:00 PM
ALTER TABLE `company_details` ADD `company_cin` VARCHAR(255) NULL DEFAULT NULL AFTER `company_city_id`, ADD `company_reg_no` VARCHAR(255) NULL DEFAULT NULL AFTER `company_cin`;

CREATE TABLE `refinery_entry` (
  `r_entry_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `r_hsn_sac_code` varchar(255) DEFAULT NULL,
  `r_old_jewels_weight` double DEFAULT NULL,
  `r_stones_dust_weights_loss` double DEFAULT NULL,
  `r_before_melting_weight` double DEFAULT NULL,
  `r_after_melting_weight` double DEFAULT NULL,
  `r_testing_purity_per` double DEFAULT NULL,
  `r_net_fine_gold` double DEFAULT NULL,
  `d_hsn_sac_code` double DEFAULT NULL,
  `d_given_fine_gold_purity` double DEFAULT NULL,
  `d_melting_charges_weight` double DEFAULT NULL,
  `d_melting_charges_per_gram` double DEFAULT NULL,
  `d_melting_charges_total` double DEFAULT NULL,
  `d_refining_charges_weight` double DEFAULT NULL,
  `d_refining_charges_per_gram` double DEFAULT NULL,
  `d_refining_charges_total` double DEFAULT NULL,
  `gst_type_id` int(11) DEFAULT NULL COMMENT '1 = SGST + CGST, 2 = IGST',
  `gst_per` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `sgst` double DEFAULT NULL,
  `sgst_per` double DEFAULT NULL,
  `cgst` double DEFAULT NULL,
  `cgst_per` double DEFAULT NULL,
  `igst` double DEFAULT NULL,
  `igst_per` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `refinery_entry`
  ADD PRIMARY KEY (`r_entry_id`);

ALTER TABLE `refinery_entry`
  MODIFY `r_entry_id` int(11) NOT NULL AUTO_INCREMENT;

-- Avinash : 2021_05_10 06:15 PM
-- --------------------------------------------------------
-- Table structure for table `sell_with_gst`
--

CREATE TABLE `sell_with_gst` (
  `sell_id` int(11) NOT NULL,
  `sell_no` int(11) DEFAULT NULL,
  `bill_financial_year` varchar(20) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `sell_date` date DEFAULT NULL,
  `sell_remark` varchar(255) DEFAULT NULL,
  `ship_to_name` varchar(255) DEFAULT NULL,
  `ship_to_address` text,
  `order_id` int(11) DEFAULT NULL COMMENT 'Order to Sell : order_id',
  `total_gold_fine` double DEFAULT NULL,
  `total_silver_fine` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `total_c_amount` double NOT NULL DEFAULT '0',
  `total_r_amount` double NOT NULL DEFAULT '0',
  `delivery_type` tinyint(1) DEFAULT '1' COMMENT '1 = Delivered, 2 = Not Delivered',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `discount_amount` double NOT NULL DEFAULT '0',
  `round_of_amount` double NOT NULL DEFAULT '0',
  `entry_through` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1 = Sell/Purchase Default, 2 = Sell/Purchase Type 2, 3 = Sell/Purchase Type 3, 4 = Sell/Purchase Entry with GST',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `sell_with_gst`
--
ALTER TABLE `sell_with_gst`
  ADD PRIMARY KEY (`sell_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `process_id` (`process_id`),
  ADD KEY `order_id` (`order_id`);

-- AUTO_INCREMENT for table `sell_with_gst`
--
ALTER TABLE `sell_with_gst`
  MODIFY `sell_id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `sell_items_with_gst`
--

CREATE TABLE `sell_items_with_gst` (
  `sell_item_id` int(11) NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `sell_item_no` int(11) DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '1=Sell, 2=Purchase, 3=Exchange',
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `stamp_id` int(11) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `stone_wt` double DEFAULT NULL,
  `sijat` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `spi_loss_for` double NOT NULL DEFAULT '0',
  `spi_loss` double NOT NULL DEFAULT '0',
  `touch_id` double DEFAULT NULL,
  `wstg` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `wastage_labour` int(11) DEFAULT NULL,
  `wastage_labour_value` double DEFAULT NULL,
  `spi_labour_on` tinyint(1) DEFAULT NULL COMMENT '1 = PCS, 2 = NetWt@1000',
  `labour_amount` double NOT NULL DEFAULT '0',
  `gold_silver_rate` double NOT NULL DEFAULT '0',
  `gold_silver_amount` double NOT NULL DEFAULT '0',
  `stone_qty` double NOT NULL DEFAULT '0',
  `stone_rs` double NOT NULL DEFAULT '0',
  `item_stock_rfid_id` int(11) DEFAULT NULL,
  `rfid_number` varchar(255) DEFAULT NULL,
  `spi_pcs` double NOT NULL DEFAULT '0',
  `spi_rate` double NOT NULL DEFAULT '0',
  `charges_amt` double NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `rate_per_1_gram` double NOT NULL DEFAULT '0',
  `gross_amount` double NOT NULL DEFAULT '0',
  `labout_other_charges` double NOT NULL DEFAULT '0',
  `gst_rate` double NOT NULL DEFAULT '0',
  `tax` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `c_amt` double NOT NULL DEFAULT '0',
  `r_amt` double NOT NULL DEFAULT '0',
  `li_narration` text,
  `order_lot_item_id` int(11) DEFAULT NULL COMMENT 'Order to Sell : order_lot_item_id',
  `purchase_sell_item_id` int(11) DEFAULT NULL COMMENT 'Purchase to Sell : purchase_sell_item_id',
  `stock_type` tinyint(1) DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive',
  `wastage_change_approve` varchar(100) DEFAULT '0_0' COMMENT '0_0 = Default Wastage Only, 1_0 = Only Pending Approve Diff Wastage, 1_1 = Approved Diff Wastage',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `sell_items_with_gst`
--
ALTER TABLE `sell_items_with_gst`
  ADD PRIMARY KEY (`sell_item_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `purchase_sell_item_id` (`purchase_sell_item_id`),
  ADD KEY `order_lot_item_id` (`order_lot_item_id`);

--
-- AUTO_INCREMENT for table `sell_items_with_gst`
--
ALTER TABLE `sell_items_with_gst`
  MODIFY `sell_item_id` int(11) NOT NULL AUTO_INCREMENT;

-- Avinash : 2021_05_21 11:00 AM
ALTER TABLE `sell_with_gst` ADD `tcs_per` DOUBLE NOT NULL DEFAULT '0' AFTER `round_of_amount`, ADD `tcs_amount` DOUBLE NOT NULL DEFAULT '0' AFTER `tcs_per`;

-- Chirag : 2021_05_25 03:20 PM
ALTER TABLE `refinery_entry` ADD `invoice_no` DOUBLE NULL DEFAULT NULL AFTER `r_entry_id`;
ALTER TABLE `refinery_entry` ADD `r_melting_loss` DOUBLE NULL DEFAULT NULL AFTER `r_before_melting_weight`;

-- Avinash : 2021_06_09 09:30 AM
ALTER TABLE `item_master` ADD `rate_of` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Labour, 2 = Item' AFTER `rate_on`;
ALTER TABLE `sell_items` ADD `spi_rate_of` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Item Rate Of : 1 = Labour, 2 = Item' AFTER `spi_rate`;

-- Avinash : 2021_08_04 07:50 AM
ALTER TABLE `sell` ADD `no_for` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = General No, 2 = Only Purchase, 3 = Only Sell, 4 = Only Payment Receipt, 5 = Only Metal Issue Receive' AFTER `sell_no`;

-- Avinash : 2021_08_13 03:00 PM
ALTER TABLE `issue_receive_details` ADD `huid` VARCHAR(15) NULL DEFAULT NULL AFTER `stock_type`;
ALTER TABLE `item_stock` ADD `huid` VARCHAR(15) NULL DEFAULT NULL AFTER `stock_type`;
