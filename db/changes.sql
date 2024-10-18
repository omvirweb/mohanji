-- Parag : 2022_01_21 05:41 PM

CREATE TABLE `ordersell` (
  `order_id` int(11) NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  `bill_financial_year` varchar(20) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `process_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_remark` varchar(255) DEFAULT NULL,
  `ship_to_name` varchar(255) DEFAULT NULL,
  `ship_to_address` text DEFAULT NULL,
  `total_gold_fine` double DEFAULT NULL,
  `total_silver_fine` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `total_c_amount` double NOT NULL DEFAULT 0,
  `total_r_amount` double NOT NULL DEFAULT 0,
  `delivery_type` tinyint(1) DEFAULT 1 COMMENT '1 = Delivered, 2 = Not Delivered',
  `audit_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Pending = 1, Audited = 2, Suspected = 3',
  `discount_amount` double NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `ordersell`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `process_id` (`process_id`);

ALTER TABLE `ordersell`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;


-- Parag : 2022_01_21 06:10 PM 


CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_item_no` int(11) DEFAULT NULL,
  `tunch_textbox` tinyint(1) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `stamp_id` int(11) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `grwt` double DEFAULT NULL,
  `stone_wt` double DEFAULT NULL,
  `sijat` double DEFAULT NULL,
  `less` double DEFAULT NULL,
  `net_wt` double DEFAULT NULL,
  `spi_loss_for` double NOT NULL DEFAULT 0,
  `spi_loss` double NOT NULL DEFAULT 0,
  `touch_id` double DEFAULT NULL,
  `wstg` double DEFAULT NULL,
  `fine` double DEFAULT NULL,
  `wastage_labour` int(11) DEFAULT NULL,
  `wastage_labour_value` double DEFAULT NULL,
  `spi_labour_on` tinyint(1) DEFAULT NULL COMMENT '1 = PCS, 2 = NetWt@1000',
  `labour_amount` double NOT NULL DEFAULT 0,
  `gold_silver_rate` double NOT NULL DEFAULT 0,
  `gold_silver_amount` double NOT NULL DEFAULT 0,
  `stone_qty` double NOT NULL DEFAULT 0,
  `stone_rs` double NOT NULL DEFAULT 0,
  `item_stock_rfid_id` int(11) DEFAULT NULL,
  `rfid_number` varchar(255) DEFAULT NULL,
  `spi_pcs` double NOT NULL DEFAULT 0,
  `spi_rate` double NOT NULL DEFAULT 0,
  `spi_rate_of` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Item Rate Of : 1 = Labour, 2 = Item',
  `charges_amt` double NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `rate_per_1_gram` double NOT NULL DEFAULT 0,
  `gross_amount` double NOT NULL DEFAULT 0,
  `labout_other_charges` double NOT NULL DEFAULT 0,
  `gst_rate` double NOT NULL DEFAULT 0,
  `tax` double NOT NULL DEFAULT 0,
  `amount` double NOT NULL DEFAULT 0,
  `c_amt` double NOT NULL DEFAULT 0,
  `r_amt` double NOT NULL DEFAULT 0,
  `li_narration` text DEFAULT NULL,
  `wastage_change_approve` varchar(100) DEFAULT '0_0' COMMENT '0_0 = Default Wastage Only, 1_0 = Only Pending Approve Diff Wastage, 1_1 = Approved Diff Wastage',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `item_id` (`item_id`);

ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;


-- Parag : 2022_01_22 10:54 AM


ALTER TABLE `order_items` ADD `type` INT NOT NULL AFTER `order_item_no`;


-- Parag : 2022_02_21 11:24 AM


INSERT INTO `sell_type` (`sell_type_id`, `type_name`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('4', 'Issue', NULL, NULL, NULL, NULL), ('5', 'Receive', NULL, NULL, NULL, NULL)

-- Parag : 2022_02_26 12:03 PM

ALTER TABLE `sell_type` ADD `type_name2` VARCHAR(255) NULL AFTER `type_name`;

UPDATE `sell_type` SET `type_name2` = 'Sel' WHERE `sell_type`.`sell_type_id` = 1; UPDATE `sell_type` SET `type_name2` = 'Pur' WHERE `sell_type`.`sell_type_id` = 2; UPDATE `sell_type` SET `type_name2` = 'Exch' WHERE `sell_type`.`sell_type_id` = 3; UPDATE `sell_type` SET `type_name2` = 'Issue' WHERE `sell_type`.`sell_type_id` = 4; UPDATE `sell_type` SET `type_name2` = 'Rec' WHERE `sell_type`.`sell_type_id` = 5;