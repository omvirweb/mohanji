DELIMITER $$
CREATE TRIGGER `account_delete_after_trigger` AFTER DELETE ON `account`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `account_id`=OLD.account_id,
  `account_name`=OLD.account_name,
  `account_phone`=OLD.account_phone,
  `account_mobile`=OLD.account_mobile,
  `account_email_ids`=OLD.account_email_ids,
  `account_address`=OLD.account_address,
  `account_state`=OLD.account_state,
  `account_city`=OLD.account_city,
  `account_postal_code`=OLD.account_postal_code,
  `account_gst_no`=OLD.account_gst_no,
  `account_pan`=OLD.account_pan,
  `account_aadhaar`=OLD.account_aadhaar,
  `account_contect_person_name`=OLD.account_contect_person_name,
  `account_group_id`=OLD.account_group_id,
  `opening_balance`=OLD.opening_balance,
  `interest`=OLD.interest,
  `credit_debit`=OLD.credit_debit,
  `opening_balance_in_gold`=OLD.opening_balance_in_gold,
  `gold_ob_credit_debit`=OLD.gold_ob_credit_debit,
  `opening_balance_in_silver`=OLD.opening_balance_in_silver,
  `silver_ob_credit_debit`=OLD.silver_ob_credit_debit,
  `opening_balance_in_rupees`=OLD.opening_balance_in_rupees,
  `rupees_ob_credit_debit`=OLD.rupees_ob_credit_debit,
  `bank_name`=OLD.bank_name,
  `bank_account_no`=OLD.bank_account_no,
  `ifsc_code`=OLD.ifsc_code,
  `bank_interest`=OLD.bank_interest,
  `gold_fine`=OLD.gold_fine,
  `silver_fine`=OLD.silver_fine,
  `amount`=OLD.amount,
  `credit_limit`=OLD.credit_limit,
  `balance_date`=OLD.balance_date,
  `status`=OLD.status,
  `user_id`=OLD.user_id,
  `user_name`=OLD.user_name,
  `is_supplier`=OLD.is_supplier,
  `password`=OLD.password,
  `min_price`=OLD.min_price,
  `chhijjat_per_100_ad`=OLD.chhijjat_per_100_ad,
  `meena_charges`=OLD.meena_charges,
  `price_per_pcs`=OLD.price_per_pcs,
  `is_active`=OLD.is_active,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `account_group_delete_after_trigger` AFTER DELETE ON `account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_group_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `account_group_id`=OLD.account_group_id,
  `parent_group_id`=OLD.parent_group_id,
  `account_group_name`=OLD.account_group_name,
  `sequence`=OLD.sequence,
  `is_display_in_balance_sheet`=OLD.is_display_in_balance_sheet,
  `use_in_profit_loss`=OLD.use_in_profit_loss,
  `move_data_opening_zero`=OLD.move_data_opening_zero,
  `is_deletable`=OLD.is_deletable,
  `is_deleted`=OLD.is_deleted,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `account_group_insert_after_trigger` AFTER INSERT ON `account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_group_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `account_group_id`=NEW.account_group_id,
  `parent_group_id`=NEW.parent_group_id,
  `account_group_name`=NEW.account_group_name,
  `sequence`=NEW.sequence,
  `is_display_in_balance_sheet`=NEW.is_display_in_balance_sheet,
  `use_in_profit_loss`=NEW.use_in_profit_loss,
  `move_data_opening_zero`=NEW.move_data_opening_zero,
  `is_deletable`=NEW.is_deletable,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `account_update_after_trigger` AFTER UPDATE ON `account`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `account_id`=NEW.account_id,
  `account_name`=NEW.account_name,
  `account_phone`=NEW.account_phone,
  `account_mobile`=NEW.account_mobile,
  `account_email_ids`=NEW.account_email_ids,
  `account_address`=NEW.account_address,
  `account_state`=NEW.account_state,
  `account_city`=NEW.account_city,
  `account_postal_code`=NEW.account_postal_code,
  `account_gst_no`=NEW.account_gst_no,
  `account_pan`=NEW.account_pan,
  `account_aadhaar`=NEW.account_aadhaar,
  `account_contect_person_name`=NEW.account_contect_person_name,
  `account_group_id`=NEW.account_group_id,
  `opening_balance`=NEW.opening_balance,
  `interest`=NEW.interest,
  `credit_debit`=NEW.credit_debit,
  `opening_balance_in_gold`=NEW.opening_balance_in_gold,
  `gold_ob_credit_debit`=NEW.gold_ob_credit_debit,
  `opening_balance_in_silver`=NEW.opening_balance_in_silver,
  `silver_ob_credit_debit`=NEW.silver_ob_credit_debit,
  `opening_balance_in_rupees`=NEW.opening_balance_in_rupees,
  `rupees_ob_credit_debit`=NEW.rupees_ob_credit_debit,
  `bank_name`=NEW.bank_name,
  `bank_account_no`=NEW.bank_account_no,
  `ifsc_code`=NEW.ifsc_code,
  `bank_interest`=NEW.bank_interest,
  `gold_fine`=NEW.gold_fine,
  `silver_fine`=NEW.silver_fine,
  `amount`=NEW.amount,
  `credit_limit`=NEW.credit_limit,
  `balance_date`=NEW.balance_date,
  `status`=NEW.status,
  `user_id`=NEW.user_id,
  `user_name`=NEW.user_name,
  `is_supplier`=NEW.is_supplier,
  `password`=NEW.password,
  `min_price`=NEW.min_price,
  `chhijjat_per_100_ad`=NEW.chhijjat_per_100_ad,
  `meena_charges`=NEW.meena_charges,
  `price_per_pcs`=NEW.price_per_pcs,
  `is_active`=NEW.is_active,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `account_insert_after_trigger` AFTER INSERT ON `account`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `account_id`=NEW.account_id,
  `account_name`=NEW.account_name,
  `account_phone`=NEW.account_phone,
  `account_mobile`=NEW.account_mobile,
  `account_email_ids`=NEW.account_email_ids,
  `account_address`=NEW.account_address,
  `account_state`=NEW.account_state,
  `account_city`=NEW.account_city,
  `account_postal_code`=NEW.account_postal_code,
  `account_gst_no`=NEW.account_gst_no,
  `account_pan`=NEW.account_pan,
  `account_aadhaar`=NEW.account_aadhaar,
  `account_contect_person_name`=NEW.account_contect_person_name,
  `account_group_id`=NEW.account_group_id,
  `opening_balance`=NEW.opening_balance,
  `interest`=NEW.interest,
  `credit_debit`=NEW.credit_debit,
  `opening_balance_in_gold`=NEW.opening_balance_in_gold,
  `gold_ob_credit_debit`=NEW.gold_ob_credit_debit,
  `opening_balance_in_silver`=NEW.opening_balance_in_silver,
  `silver_ob_credit_debit`=NEW.silver_ob_credit_debit,
  `opening_balance_in_rupees`=NEW.opening_balance_in_rupees,
  `rupees_ob_credit_debit`=NEW.rupees_ob_credit_debit,
  `bank_name`=NEW.bank_name,
  `bank_account_no`=NEW.bank_account_no,
  `ifsc_code`=NEW.ifsc_code,
  `bank_interest`=NEW.bank_interest,
  `gold_fine`=NEW.gold_fine,
  `silver_fine`=NEW.silver_fine,
  `amount`=NEW.amount,
  `credit_limit`=NEW.credit_limit,
  `balance_date`=NEW.balance_date,
  `status`=NEW.status,
  `user_id`=NEW.user_id,
  `user_name`=NEW.user_name,
  `is_supplier`=NEW.is_supplier,
  `password`=NEW.password,
  `min_price`=NEW.min_price,
  `chhijjat_per_100_ad`=NEW.chhijjat_per_100_ad,
  `meena_charges`=NEW.meena_charges,
  `price_per_pcs`=NEW.price_per_pcs,
  `is_active`=NEW.is_active,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ad_delete_after_trigger` AFTER DELETE ON `ad`
 FOR EACH ROW INSERT INTO 
  gurulog.`ad_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ad_id`=OLD.ad_id,
  `ad_name`=OLD.ad_name,
  `ad_description`=OLD.ad_description,
  `is_nang_setting`=OLD.is_nang_setting,
  `is_sell_purchase_ad_charges`=OLD.is_sell_purchase_ad_charges,
  `is_sell_purchase_less_ad_details`=OLD.is_sell_purchase_less_ad_details,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ad_insert_after_trigger` AFTER INSERT ON `ad`
 FOR EACH ROW INSERT INTO 
  gurulog.`ad_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ad_id`=NEW.ad_id,
  `ad_name`=NEW.ad_name,
  `ad_description`=NEW.ad_description,
  `is_nang_setting`=NEW.is_nang_setting,
  `is_sell_purchase_ad_charges`=NEW.is_sell_purchase_ad_charges,
  `is_sell_purchase_less_ad_details`=NEW.is_sell_purchase_less_ad_details,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `account_group_update_after_trigger` AFTER UPDATE ON `account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`account_group_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `account_group_id`=NEW.account_group_id,
  `parent_group_id`=NEW.parent_group_id,
  `account_group_name`=NEW.account_group_name,
  `sequence`=NEW.sequence,
  `is_display_in_balance_sheet`=NEW.is_display_in_balance_sheet,
  `use_in_profit_loss`=NEW.use_in_profit_loss,
  `move_data_opening_zero`=NEW.move_data_opening_zero,
  `is_deletable`=NEW.is_deletable,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ad_update_after_trigger` AFTER UPDATE ON `ad`
 FOR EACH ROW INSERT INTO 
  gurulog.`ad_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ad_id`=NEW.ad_id,
  `ad_name`=NEW.ad_name,
  `ad_description`=NEW.ad_description,
  `is_nang_setting`=NEW.is_nang_setting,
  `is_sell_purchase_ad_charges`=NEW.is_sell_purchase_ad_charges,
  `is_sell_purchase_less_ad_details`=NEW.is_sell_purchase_less_ad_details,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `carat_delete_after_trigger` AFTER DELETE ON `carat`
 FOR EACH ROW INSERT INTO 
  gurulog.`carat_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `carat_id`=OLD.carat_id,
  `purity`=OLD.purity,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `carat_insert_after_trigger` AFTER INSERT ON `carat`
 FOR EACH ROW INSERT INTO 
  gurulog.`carat_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `carat_id`=NEW.carat_id,
  `purity`=NEW.purity,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `carat_update_after_trigger` AFTER UPDATE ON `carat`
 FOR EACH ROW INSERT INTO 
  gurulog.`carat_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `carat_id`=NEW.carat_id,
  `purity`=NEW.purity,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_delete_after_trigger` AFTER DELETE ON `casting_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ce_id`=OLD.ce_id,
  `department_id`=OLD.department_id,
  `worker_id`=OLD.worker_id,
  `from_casting_status_id`=OLD.from_casting_status_id,
  `to_casting_status_id`=OLD.to_casting_status_id,
  `cad_worker_id`=OLD.cad_worker_id,
  `ce_date`=OLD.ce_date,
  `reference_no`=OLD.reference_no,
  `lott_complete`=OLD.lott_complete,
  `hisab_done`=OLD.hisab_done,
  `ce_remark`=OLD.ce_remark,
  `total_issue_net_wt`=OLD.total_issue_net_wt,
  `total_receive_net_wt`=OLD.total_receive_net_wt,
  `total_issue_fine`=OLD.total_issue_fine,
  `total_receive_fine`=OLD.total_receive_fine,
  `design_files`=OLD.design_files,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_design_files_delete_after_trigger` AFTER DELETE ON `casting_entry_design_files`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_design_files_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `design_file_id`=OLD.design_file_id,
  `ce_id`=OLD.ce_id,
  `design_file_name`=OLD.design_file_name,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_design_files_insert_after_trigger` AFTER INSERT ON `casting_entry_design_files`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_design_files_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `design_file_id`=NEW.design_file_id,
  `ce_id`=NEW.ce_id,
  `design_file_name`=NEW.design_file_name,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_design_files_update_after_trigger` AFTER UPDATE ON `casting_entry_design_files`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_design_files_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `design_file_id`=NEW.design_file_id,
  `ce_id`=NEW.ce_id,
  `design_file_name`=NEW.design_file_name,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_details_delete_after_trigger` AFTER DELETE ON `casting_entry_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ce_detail_id`=OLD.ce_detail_id,
  `ce_id`=OLD.ce_id,
  `type_id`=OLD.type_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `weight`=OLD.weight,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `actual_tunch`=OLD.actual_tunch,
  `fine`=OLD.fine,
  `pcs`=OLD.pcs,
  `ad_weight`=OLD.ad_weight,
  `ad_pcs`=OLD.ad_pcs,
  `ce_detail_date`=OLD.ce_detail_date,
  `tunch_textbox`=OLD.tunch_textbox,
  `ce_detail_remark`=OLD.ce_detail_remark,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_details_insert_after_trigger` AFTER INSERT ON `casting_entry_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ce_detail_id`=NEW.ce_detail_id,
  `ce_id`=NEW.ce_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `ad_weight`=NEW.ad_weight,
  `ad_pcs`=NEW.ad_pcs,
  `ce_detail_date`=NEW.ce_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `ce_detail_remark`=NEW.ce_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_details_update_after_trigger` AFTER UPDATE ON `casting_entry_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ce_detail_id`=NEW.ce_detail_id,
  `ce_id`=NEW.ce_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `ad_weight`=NEW.ad_weight,
  `ad_pcs`=NEW.ad_pcs,
  `ce_detail_date`=NEW.ce_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `ce_detail_remark`=NEW.ce_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_insert_after_trigger` AFTER INSERT ON `casting_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ce_id`=NEW.ce_id,
  `department_id`=NEW.department_id,
  `worker_id`=NEW.worker_id,
  `from_casting_status_id`=NEW.from_casting_status_id,
  `to_casting_status_id`=NEW.to_casting_status_id,
  `cad_worker_id`=NEW.cad_worker_id,
  `ce_date`=NEW.ce_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `ce_remark`=NEW.ce_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `design_files`=NEW.design_files,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_order_items_delete_after_trigger` AFTER DELETE ON `casting_entry_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_order_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ce_oi_id`=OLD.ce_oi_id,
  `ce_id`=OLD.ce_id,
  `order_id`=OLD.order_id,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `is_ahead`=OLD.is_ahead,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_order_items_insert_after_trigger` AFTER INSERT ON `casting_entry_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_order_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ce_oi_id`=NEW.ce_oi_id,
  `ce_id`=NEW.ce_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `is_ahead`=NEW.is_ahead,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_order_items_update_after_trigger` AFTER UPDATE ON `casting_entry_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_order_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ce_oi_id`=NEW.ce_oi_id,
  `ce_id`=NEW.ce_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `is_ahead`=NEW.is_ahead,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `casting_entry_update_after_trigger` AFTER UPDATE ON `casting_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`casting_entry_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ce_id`=NEW.ce_id,
  `department_id`=NEW.department_id,
  `worker_id`=NEW.worker_id,
  `from_casting_status_id`=NEW.from_casting_status_id,
  `to_casting_status_id`=NEW.to_casting_status_id,
  `cad_worker_id`=NEW.cad_worker_id,
  `ce_date`=NEW.ce_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `ce_remark`=NEW.ce_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `design_files`=NEW.design_files,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_delete_after_trigger` AFTER DELETE ON `category`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `category_id`=OLD.category_id,
  `category_name`=OLD.category_name,
  `category_group_id`=OLD.category_group_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_group_delete_after_trigger` AFTER DELETE ON `category_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_group_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `category_group_id`=OLD.category_group_id,
  `category_group_name`=OLD.category_group_name,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_group_insert_after_trigger` AFTER INSERT ON `category_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_group_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `category_group_id`=NEW.category_group_id,
  `category_group_name`=NEW.category_group_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_group_update_after_trigger` AFTER UPDATE ON `category_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_group_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `category_group_id`=NEW.category_group_id,
  `category_group_name`=NEW.category_group_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_insert_after_trigger` AFTER INSERT ON `category`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `category_id`=NEW.category_id,
  `category_name`=NEW.category_name,
  `category_group_id`=NEW.category_group_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category_update_after_trigger` AFTER UPDATE ON `category`
 FOR EACH ROW INSERT INTO 
  gurulog.`category_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `category_id`=NEW.category_id,
  `category_name`=NEW.category_name,
  `category_group_id`=NEW.category_group_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `city_delete_after_trigger` AFTER DELETE ON `city`
 FOR EACH ROW INSERT INTO 
  gurulog.`city_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `city_id`=OLD.city_id,
  `state_id`=OLD.state_id,
  `city_name`=OLD.city_name,
  `is_deleted`=OLD.is_deleted,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `city_insert_after_trigger` AFTER INSERT ON `city`
 FOR EACH ROW INSERT INTO 
  gurulog.`city_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `city_id`=NEW.city_id,
  `state_id`=NEW.state_id,
  `city_name`=NEW.city_name,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `city_update_after_trigger` AFTER UPDATE ON `city`
 FOR EACH ROW INSERT INTO 
  gurulog.`city_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `city_id`=NEW.city_id,
  `state_id`=NEW.state_id,
  `city_name`=NEW.city_name,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `design_master_insert_after_trigger` AFTER INSERT ON `design_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`design_master_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `design_id`=NEW.design_id,
  `design_no`=NEW.design_no,
  `file_no`=NEW.file_no,
  `stl_3dm_no`=NEW.stl_3dm_no,
  `die_making`=NEW.die_making,
  `die_no`=NEW.die_no,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `design_master_delete_after_trigger` AFTER DELETE ON `design_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`design_master_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `design_id`=OLD.design_id,
  `design_no`=OLD.design_no,
  `file_no`=OLD.file_no,
  `stl_3dm_no`=OLD.stl_3dm_no,
  `die_making`=OLD.die_making,
  `die_no`=OLD.die_no,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `design_master_update_after_trigger` AFTER UPDATE ON `design_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`design_master_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `design_id`=NEW.design_id,
  `design_no`=NEW.design_no,
  `file_no`=NEW.file_no,
  `stl_3dm_no`=NEW.stl_3dm_no,
  `die_making`=NEW.die_making,
  `die_no`=NEW.die_no,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employee_salary_delete_after_trigger` AFTER DELETE ON `employee_salary`
 FOR EACH ROW INSERT INTO 
  gurulog.`employee_salary_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `employee_salary_id`=OLD.employee_salary_id,
  `account_id`=OLD.account_id,
  `department_id`=OLD.department_id,
  `month_year`=OLD.month_year,
  `worked_days`=OLD.worked_days,
  `monthly_salary`=OLD.monthly_salary,
  `salary_calculated`=OLD.salary_calculated,
  `give_salary`=OLD.give_salary,
  `leaves`=OLD.leaves,
  `journal_id`=OLD.journal_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employee_salary_insert_after_trigger` AFTER INSERT ON `employee_salary`
 FOR EACH ROW INSERT INTO 
  gurulog.`employee_salary_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `employee_salary_id`=NEW.employee_salary_id,
  `account_id`=NEW.account_id,
  `department_id`=NEW.department_id,
  `month_year`=NEW.month_year,
  `worked_days`=NEW.worked_days,
  `monthly_salary`=NEW.monthly_salary,
  `salary_calculated`=NEW.salary_calculated,
  `give_salary`=NEW.give_salary,
  `leaves`=NEW.leaves,
  `journal_id`=NEW.journal_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employee_salary_update_after_trigger` AFTER UPDATE ON `employee_salary`
 FOR EACH ROW INSERT INTO 
  gurulog.`employee_salary_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `employee_salary_id`=NEW.employee_salary_id,
  `account_id`=NEW.account_id,
  `department_id`=NEW.department_id,
  `month_year`=NEW.month_year,
  `worked_days`=NEW.worked_days,
  `monthly_salary`=NEW.monthly_salary,
  `salary_calculated`=NEW.salary_calculated,
  `give_salary`=NEW.give_salary,
  `leaves`=NEW.leaves,
  `journal_id`=NEW.journal_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `feedback_delete_after_trigger` AFTER DELETE ON `feedback`
 FOR EACH ROW INSERT INTO 
  gurulog.`feedback_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `feedback_id`=OLD.feedback_id,
  `assign_id`=OLD.assign_id,
  `feedback_date`=OLD.feedback_date,
  `note`=OLD.note,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `feedback_insert_after_trigger` AFTER INSERT ON `feedback`
 FOR EACH ROW INSERT INTO 
  gurulog.`feedback_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `feedback_id`=NEW.feedback_id,
  `assign_id`=NEW.assign_id,
  `feedback_date`=NEW.feedback_date,
  `note`=NEW.note,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `feedback_update_after_trigger` AFTER UPDATE ON `feedback`
 FOR EACH ROW INSERT INTO 
  gurulog.`feedback_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `feedback_id`=NEW.feedback_id,
  `assign_id`=NEW.assign_id,
  `feedback_date`=NEW.feedback_date,
  `note`=NEW.note,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `gold_bhav_insert_after_trigger` AFTER INSERT ON `gold_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`gold_bhav_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `gold_id`=NEW.gold_id,
  `sell_id`=NEW.sell_id,
  `gold_sale_purchase`=NEW.gold_sale_purchase,
  `gold_weight`=NEW.gold_weight,
  `gold_rate`=NEW.gold_rate,
  `gold_value`=NEW.gold_value,
  `gold_narration`=NEW.gold_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `gold_bhav_delete_after_trigger` AFTER DELETE ON `gold_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`gold_bhav_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `gold_id`=OLD.gold_id,
  `sell_id`=OLD.sell_id,
  `gold_sale_purchase`=OLD.gold_sale_purchase,
  `gold_weight`=OLD.gold_weight,
  `gold_rate`=OLD.gold_rate,
  `gold_value`=OLD.gold_value,
  `gold_narration`=OLD.gold_narration,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `gold_bhav_update_after_trigger` AFTER UPDATE ON `gold_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`gold_bhav_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `gold_id`=NEW.gold_id,
  `sell_id`=NEW.sell_id,
  `gold_sale_purchase`=NEW.gold_sale_purchase,
  `gold_weight`=NEW.gold_weight,
  `gold_rate`=NEW.gold_rate,
  `gold_value`=NEW.gold_value,
  `gold_narration`=NEW.gold_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_item_master_delete_after_trigger` AFTER DELETE ON `hallmark_item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_item_master_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `item_id`=OLD.item_id,
  `item_name`=OLD.item_name,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_item_master_insert_after_trigger` AFTER INSERT ON `hallmark_item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_item_master_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `item_id`=NEW.item_id,
  `item_name`=NEW.item_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_delete_after_trigger` AFTER DELETE ON `hallmark_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `receipt_id`=OLD.receipt_id,
  `receipt_date`=OLD.receipt_date,
  `receipt_time`=OLD.receipt_time,
  `delivery_date`=OLD.delivery_date,
  `delivery_time`=OLD.delivery_time,
  `metal_id`=OLD.metal_id,
  `party_id`=OLD.party_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_item_master_update_after_trigger` AFTER UPDATE ON `hallmark_item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_item_master_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `item_id`=NEW.item_id,
  `item_name`=NEW.item_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_details_insert_after_trigger` AFTER INSERT ON `hallmark_receipt_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `rd_id`=NEW.rd_id,
  `receipt_id`=NEW.receipt_id,
  `article_id`=NEW.article_id,
  `receipt_weight`=NEW.receipt_weight,
  `purity`=NEW.purity,
  `box_no`=NEW.box_no,
  `pcs`=NEW.pcs,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_details_delete_after_trigger` AFTER DELETE ON `hallmark_receipt_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `rd_id`=OLD.rd_id,
  `receipt_id`=OLD.receipt_id,
  `article_id`=OLD.article_id,
  `receipt_weight`=OLD.receipt_weight,
  `purity`=OLD.purity,
  `box_no`=OLD.box_no,
  `pcs`=OLD.pcs,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_details_update_after_trigger` AFTER UPDATE ON `hallmark_receipt_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `rd_id`=NEW.rd_id,
  `receipt_id`=NEW.receipt_id,
  `article_id`=NEW.article_id,
  `receipt_weight`=NEW.receipt_weight,
  `purity`=NEW.purity,
  `box_no`=NEW.box_no,
  `pcs`=NEW.pcs,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_insert_after_trigger` AFTER INSERT ON `hallmark_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `receipt_id`=NEW.receipt_id,
  `receipt_date`=NEW.receipt_date,
  `receipt_time`=NEW.receipt_time,
  `delivery_date`=NEW.delivery_date,
  `delivery_time`=NEW.delivery_time,
  `metal_id`=NEW.metal_id,
  `party_id`=NEW.party_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_receipt_update_after_trigger` AFTER UPDATE ON `hallmark_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_receipt_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `receipt_id`=NEW.receipt_id,
  `receipt_date`=NEW.receipt_date,
  `receipt_time`=NEW.receipt_time,
  `delivery_date`=NEW.delivery_date,
  `delivery_time`=NEW.delivery_time,
  `metal_id`=NEW.metal_id,
  `party_id`=NEW.party_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_delete_after_trigger` AFTER DELETE ON `hallmark_xrf`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `xrf_id`=OLD.xrf_id,
  `posting_date`=OLD.posting_date,
  `receipt_no`=OLD.receipt_no,
  `receipt_date`=OLD.receipt_date,
  `account_id`=OLD.account_id,
  `status`=OLD.status,
  `receipt_time`=OLD.receipt_time,
  `taken_by_same`=OLD.taken_by_same,
  `taken_by_name`=OLD.taken_by_name,
  `gst_no`=OLD.gst_no,
  `box_no`=OLD.box_no,
  `min_price`=OLD.min_price,
  `price_per_pcs`=OLD.price_per_pcs,
  `total_item_amount`=OLD.total_item_amount,
  `cgst_per`=OLD.cgst_per,
  `cgst_amount`=OLD.cgst_amount,
  `sgst_per`=OLD.sgst_per,
  `sgst_amount`=OLD.sgst_amount,
  `igst_per`=OLD.igst_per,
  `igst_amount`=OLD.igst_amount,
  `other_charges`=OLD.other_charges,
  `advance_rec_amount`=OLD.advance_rec_amount,
  `pending_amount`=OLD.pending_amount,
  `remark`=OLD.remark,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_insert_after_trigger` AFTER INSERT ON `hallmark_xrf`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `xrf_id`=NEW.xrf_id,
  `posting_date`=NEW.posting_date,
  `receipt_no`=NEW.receipt_no,
  `receipt_date`=NEW.receipt_date,
  `account_id`=NEW.account_id,
  `status`=NEW.status,
  `receipt_time`=NEW.receipt_time,
  `taken_by_same`=NEW.taken_by_same,
  `taken_by_name`=NEW.taken_by_name,
  `gst_no`=NEW.gst_no,
  `box_no`=NEW.box_no,
  `min_price`=NEW.min_price,
  `price_per_pcs`=NEW.price_per_pcs,
  `total_item_amount`=NEW.total_item_amount,
  `cgst_per`=NEW.cgst_per,
  `cgst_amount`=NEW.cgst_amount,
  `sgst_per`=NEW.sgst_per,
  `sgst_amount`=NEW.sgst_amount,
  `igst_per`=NEW.igst_per,
  `igst_amount`=NEW.igst_amount,
  `other_charges`=NEW.other_charges,
  `advance_rec_amount`=NEW.advance_rec_amount,
  `pending_amount`=NEW.pending_amount,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_items_delete_after_trigger` AFTER DELETE ON `hallmark_xrf_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `xrf_item_id`=OLD.xrf_item_id,
  `xrf_id`=OLD.xrf_id,
  `item_id`=OLD.item_id,
  `purity`=OLD.purity,
  `rec_qty`=OLD.rec_qty,
  `price_per_pcs`=OLD.price_per_pcs,
  `item_amount`=OLD.item_amount,
  `weight`=OLD.rec_weight,
  `remark`=OLD.remark,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_items_insert_after_trigger` AFTER INSERT ON `hallmark_xrf_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `xrf_item_id`=NEW.xrf_item_id,
  `xrf_id`=NEW.xrf_id,
  `item_id`=NEW.item_id,
  `purity`=NEW.purity,
  `rec_qty`=NEW.rec_qty,
  `price_per_pcs`=NEW.price_per_pcs,
  `item_amount`=NEW.item_amount,
  `weight`=NEW.rec_weight,
  `remark`=NEW.remark,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_items_update_after_trigger` AFTER UPDATE ON `hallmark_xrf_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `xrf_item_id`=NEW.xrf_item_id,
  `xrf_id`=NEW.xrf_id,
  `item_id`=NEW.item_id,
  `purity`=NEW.purity,
  `rec_qty`=NEW.rec_qty,
  `price_per_pcs`=NEW.price_per_pcs,
  `item_amount`=NEW.item_amount,
  `weight`=NEW.rec_weight,
  `remark`=NEW.remark,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hallmark_xrf_update_after_trigger` AFTER UPDATE ON `hallmark_xrf`
 FOR EACH ROW INSERT INTO 
  gurulog.`hallmark_xrf_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `xrf_id`=NEW.xrf_id,
  `posting_date`=NEW.posting_date,
  `receipt_no`=NEW.receipt_no,
  `receipt_date`=NEW.receipt_date,
  `account_id`=NEW.account_id,
  `status`=NEW.status,
  `receipt_time`=NEW.receipt_time,
  `taken_by_same`=NEW.taken_by_same,
  `taken_by_name`=NEW.taken_by_name,
  `gst_no`=NEW.gst_no,
  `box_no`=NEW.box_no,
  `min_price`=NEW.min_price,
  `price_per_pcs`=NEW.price_per_pcs,
  `total_item_amount`=NEW.total_item_amount,
  `cgst_per`=NEW.cgst_per,
  `cgst_amount`=NEW.cgst_amount,
  `sgst_per`=NEW.sgst_per,
  `sgst_amount`=NEW.sgst_amount,
  `igst_per`=NEW.igst_per,
  `igst_amount`=NEW.igst_amount,
  `other_charges`=NEW.other_charges,
  `advance_rec_amount`=NEW.advance_rec_amount,
  `pending_amount`=NEW.pending_amount,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_apply_leave_delete_after_trigger` AFTER DELETE ON `hr_apply_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_apply_leave_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `apply_leave_id`=OLD.apply_leave_id,
  `user_id`=OLD.user_id,
  `from_date`=OLD.from_date,
  `to_date`=OLD.to_date,
  `no_of_days`=OLD.no_of_days,
  `reason`=OLD.reason,
  `status`=OLD.status,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_apply_leave_insert_after_trigger` AFTER INSERT ON `hr_apply_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_apply_leave_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `apply_leave_id`=NEW.apply_leave_id,
  `user_id`=NEW.user_id,
  `from_date`=NEW.from_date,
  `to_date`=NEW.to_date,
  `no_of_days`=NEW.no_of_days,
  `reason`=NEW.reason,
  `status`=NEW.status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_apply_leave_update_after_trigger` AFTER UPDATE ON `hr_apply_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_apply_leave_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `apply_leave_id`=NEW.apply_leave_id,
  `user_id`=NEW.user_id,
  `from_date`=NEW.from_date,
  `to_date`=NEW.to_date,
  `no_of_days`=NEW.no_of_days,
  `reason`=NEW.reason,
  `status`=NEW.status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_attendance_delete_after_trigger` AFTER DELETE ON `hr_attendance`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_attendance_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `attendance_id`=OLD.attendance_id,
  `account_id`=OLD.account_id,
  `attendance_date`=OLD.attendance_date,
  `attendance_time`=OLD.attendance_time,
  `is_in_out`=OLD.is_in_out,
  `is_out_for_office`=OLD.is_out_for_office,
  `is_cron_entry`=OLD.is_cron_entry,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_attendance_insert_after_trigger` AFTER INSERT ON `hr_attendance`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_attendance_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `attendance_id`=NEW.attendance_id,
  `account_id`=NEW.account_id,
  `attendance_date`=NEW.attendance_date,
  `attendance_time`=NEW.attendance_time,
  `is_in_out`=NEW.is_in_out,
  `is_out_for_office`=NEW.is_out_for_office,
  `is_cron_entry`=NEW.is_cron_entry,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_attendance_update_after_trigger` AFTER UPDATE ON `hr_attendance`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_attendance_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `attendance_id`=NEW.attendance_id,
  `account_id`=NEW.account_id,
  `attendance_date`=NEW.attendance_date,
  `attendance_time`=NEW.attendance_time,
  `is_in_out`=NEW.is_in_out,
  `is_out_for_office`=NEW.is_out_for_office,
  `is_cron_entry`=NEW.is_cron_entry,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_present_hours_delete_after_trigger` AFTER DELETE ON `hr_present_hours`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_present_hours_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `present_hour_id`=OLD.present_hour_id,
  `user_id`=OLD.user_id,
  `department_id`=OLD.department_id,
  `present_date`=OLD.present_date,
  `in_time`=OLD.in_time,
  `out_time`=OLD.out_time,
  `no_of_hours`=OLD.no_of_hours,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_present_hours_insert_after_trigger` AFTER INSERT ON `hr_present_hours`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_present_hours_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `present_hour_id`=NEW.present_hour_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `present_date`=NEW.present_date,
  `in_time`=NEW.in_time,
  `out_time`=NEW.out_time,
  `no_of_hours`=NEW.no_of_hours,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_present_hours_update_after_trigger` AFTER UPDATE ON `hr_present_hours`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_present_hours_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `present_hour_id`=NEW.present_hour_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `present_date`=NEW.present_date,
  `in_time`=NEW.in_time,
  `out_time`=NEW.out_time,
  `no_of_hours`=NEW.no_of_hours,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_yearly_leave_delete_after_trigger` AFTER DELETE ON `hr_yearly_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_yearly_leave_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `leave_id`=OLD.id,
  `event_name`=OLD.event_name,
  `leave_date`=OLD.leave_date,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_yearly_leave_insert_after_trigger` AFTER INSERT ON `hr_yearly_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_yearly_leave_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `leave_id`=NEW.id,
  `event_name`=NEW.event_name,
  `leave_date`=NEW.leave_date,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hr_yearly_leave_update_after_trigger` AFTER UPDATE ON `hr_yearly_leave`
 FOR EACH ROW INSERT INTO 
  gurulog.`hr_yearly_leave_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `leave_id`=NEW.id,
  `event_name`=NEW.event_name,
  `leave_date`=NEW.leave_date,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_delete_after_trigger` AFTER DELETE ON `issue_receive`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ir_id`=OLD.ir_id,
  `worker_id`=OLD.worker_id,
  `department_id`=OLD.department_id,
  `ir_date`=OLD.ir_date,
  `reference_no`=OLD.reference_no,
  `lott_complete`=OLD.lott_complete,
  `hisab_done`=OLD.hisab_done,
  `ir_remark`=OLD.ir_remark,
  `total_issue_net_wt`=OLD.total_issue_net_wt,
  `total_receive_net_wt`=OLD.total_receive_net_wt,
  `total_issue_fine`=OLD.total_issue_fine,
  `total_receive_fine`=OLD.total_receive_fine,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_details_delete_after_trigger` AFTER DELETE ON `issue_receive_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ird_id`=OLD.ird_id,
  `ir_id`=OLD.ir_id,
  `type_id`=OLD.type_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `weight`=OLD.weight,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `actual_tunch`=OLD.actual_tunch,
  `fine`=OLD.fine,
  `ird_date`=OLD.ird_date,
  `tunch_textbox`=OLD.tunch_textbox,
  `ird_remark`=OLD.ird_remark,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_details_insert_after_trigger` AFTER INSERT ON `issue_receive_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ird_id`=NEW.ird_id,
  `ir_id`=NEW.ir_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `ird_date`=NEW.ird_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `ird_remark`=NEW.ird_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_details_update_after_trigger` AFTER UPDATE ON `issue_receive_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ird_id`=NEW.ird_id,
  `ir_id`=NEW.ir_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `ird_date`=NEW.ird_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `ird_remark`=NEW.ird_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_insert_after_trigger` AFTER INSERT ON `issue_receive`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ir_id`=NEW.ir_id,
  `worker_id`=NEW.worker_id,
  `department_id`=NEW.department_id,
  `ir_date`=NEW.ir_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `ir_remark`=NEW.ir_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_delete_after_trigger` AFTER DELETE ON `issue_receive_silver`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `irs_id`=OLD.irs_id,
  `worker_id`=OLD.worker_id,
  `department_id`=OLD.department_id,
  `irs_date`=OLD.irs_date,
  `reference_no`=OLD.reference_no,
  `lott_complete`=OLD.lott_complete,
  `hisab_done`=OLD.hisab_done,
  `irs_remark`=OLD.irs_remark,
  `total_issue_net_wt`=OLD.total_issue_net_wt,
  `total_receive_net_wt`=OLD.total_receive_net_wt,
  `total_issue_fine`=OLD.total_issue_fine,
  `total_receive_fine`=OLD.total_receive_fine,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_details_insert_after_trigger` AFTER INSERT ON `issue_receive_silver_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `irsd_id`=NEW.irsd_id,
  `irs_id`=NEW.irs_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `irsd_date`=NEW.irsd_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `irsd_remark`=NEW.irsd_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_details_delete_after_trigger` AFTER DELETE ON `issue_receive_silver_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `irsd_id`=OLD.irsd_id,
  `irs_id`=OLD.irs_id,
  `type_id`=OLD.type_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `weight`=OLD.weight,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `actual_tunch`=OLD.actual_tunch,
  `fine`=OLD.fine,
  `irsd_date`=OLD.irsd_date,
  `tunch_textbox`=OLD.tunch_textbox,
  `irsd_remark`=OLD.irsd_remark,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_details_update_after_trigger` AFTER UPDATE ON `issue_receive_silver_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `irsd_id`=NEW.irsd_id,
  `irs_id`=NEW.irs_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `irsd_date`=NEW.irsd_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `irsd_remark`=NEW.irsd_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_insert_after_trigger` AFTER INSERT ON `issue_receive_silver`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `irs_id`=NEW.irs_id,
  `worker_id`=NEW.worker_id,
  `department_id`=NEW.department_id,
  `irs_date`=NEW.irs_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `irs_remark`=NEW.irs_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_silver_update_after_trigger` AFTER UPDATE ON `issue_receive_silver`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_silver_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `irs_id`=NEW.irs_id,
  `worker_id`=NEW.worker_id,
  `department_id`=NEW.department_id,
  `irs_date`=NEW.irs_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `irs_remark`=NEW.irs_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `issue_receive_update_after_trigger` AFTER UPDATE ON `issue_receive`
 FOR EACH ROW INSERT INTO 
  gurulog.`issue_receive_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ir_id`=NEW.ir_id,
  `worker_id`=NEW.worker_id,
  `department_id`=NEW.department_id,
  `ir_date`=NEW.ir_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `ir_remark`=NEW.ir_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_master_delete_after_trigger` AFTER DELETE ON `item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_master_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `item_id`=OLD.item_id,
  `item_name`=OLD.item_name,
  `short_item`=OLD.short_item,
  `category_id`=OLD.category_id,
  `image`=OLD.image,
  `die_no`=OLD.die_no,
  `design_no`=OLD.design_no,
  `min_order_qty`=OLD.min_order_qty,
  `default_wastage`=OLD.default_wastage,
  `st_default_wastage`=OLD.st_default_wastage,
  `less`=OLD.less,
  `display_item_in`=OLD.display_item_in,
  `stock_method`=OLD.stock_method,
  `metal_payment_receipt`=OLD.metal_payment_receipt,
  `sequence_no`=OLD.sequence_no,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_master_insert_after_trigger` AFTER INSERT ON `item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_master_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `item_id`=NEW.item_id,
  `item_name`=NEW.item_name,
  `short_item`=NEW.short_item,
  `category_id`=NEW.category_id,
  `image`=NEW.image,
  `die_no`=NEW.die_no,
  `design_no`=NEW.design_no,
  `min_order_qty`=NEW.min_order_qty,
  `default_wastage`=NEW.default_wastage,
  `st_default_wastage`=NEW.st_default_wastage,
  `less`=NEW.less,
  `display_item_in`=NEW.display_item_in,
  `stock_method`=NEW.stock_method,
  `metal_payment_receipt`=NEW.metal_payment_receipt,
  `sequence_no`=NEW.sequence_no,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_master_update_after_trigger` AFTER UPDATE ON `item_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_master_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `item_id`=NEW.item_id,
  `item_name`=NEW.item_name,
  `short_item`=NEW.short_item,
  `category_id`=NEW.category_id,
  `image`=NEW.image,
  `die_no`=NEW.die_no,
  `design_no`=NEW.design_no,
  `min_order_qty`=NEW.min_order_qty,
  `default_wastage`=NEW.default_wastage,
  `st_default_wastage`=NEW.st_default_wastage,
  `less`=NEW.less,
  `display_item_in`=NEW.display_item_in,
  `stock_method`=NEW.stock_method,
  `metal_payment_receipt`=NEW.metal_payment_receipt,
  `sequence_no`=NEW.sequence_no,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_delete_after_trigger` AFTER DELETE ON `item_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `item_stock_id`=OLD.item_stock_id,
  `department_id`=OLD.department_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `ntwt`=OLD.ntwt,
  `grwt`=OLD.grwt,
  `less`=OLD.less,
  `tunch`=OLD.tunch,
  `fine`=OLD.fine,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_insert_after_trigger` AFTER INSERT ON `item_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `item_stock_id`=NEW.item_stock_id,
  `department_id`=NEW.department_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `ntwt`=NEW.ntwt,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `tunch`=NEW.tunch,
  `fine`=NEW.fine,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_rfid_delete_after_trigger` AFTER DELETE ON `item_stock_rfid`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_rfid_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `item_stock_rfid_id`=OLD.item_stock_rfid_id,
  `item_stock_id`=OLD.item_stock_id,
  `rfid_grwt`=OLD.rfid_grwt,
  `rfid_less`=OLD.rfid_less,
  `rfid_add`=OLD.rfid_add,
  `rfid_ntwt`=OLD.rfid_ntwt,
  `rfid_tunch`=OLD.rfid_tunch,
  `rfid_fine`=OLD.rfid_fine,
  `real_rfid`=OLD.real_rfid,
  `rfid_charges`=OLD.rfid_charges,
  `rfid_ad_id`=OLD.rfid_ad_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_rfid_insert_after_trigger` AFTER INSERT ON `item_stock_rfid`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_rfid_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `item_stock_rfid_id`=NEW.item_stock_rfid_id,
  `item_stock_id`=NEW.item_stock_id,
  `rfid_grwt`=NEW.rfid_grwt,
  `rfid_less`=NEW.rfid_less,
  `rfid_add`=NEW.rfid_add,
  `rfid_ntwt`=NEW.rfid_ntwt,
  `rfid_tunch`=NEW.rfid_tunch,
  `rfid_fine`=NEW.rfid_fine,
  `real_rfid`=NEW.real_rfid,
  `rfid_charges`=NEW.rfid_charges,
  `rfid_ad_id`=NEW.rfid_ad_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_rfid_update_after_trigger` AFTER UPDATE ON `item_stock_rfid`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_rfid_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `item_stock_rfid_id`=NEW.item_stock_rfid_id,
  `item_stock_id`=NEW.item_stock_id,
  `rfid_grwt`=NEW.rfid_grwt,
  `rfid_less`=NEW.rfid_less,
  `rfid_add`=NEW.rfid_add,
  `rfid_ntwt`=NEW.rfid_ntwt,
  `rfid_tunch`=NEW.rfid_tunch,
  `rfid_fine`=NEW.rfid_fine,
  `real_rfid`=NEW.real_rfid,
  `rfid_charges`=NEW.rfid_charges,
  `rfid_ad_id`=NEW.rfid_ad_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_delete_after_trigger` AFTER DELETE ON `journal`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `journal_id`=OLD.journal_id,
  `department_id`=OLD.department_id,
  `journal_date`=OLD.journal_date,
  `interest_account_id`=OLD.interest_account_id,
  `gold_rate`=OLD.gold_rate,
  `silver_rate`=OLD.silver_rate,
  `interest_rate`=OLD.interest_rate,
  `relation_id`=OLD.relation_id,
  `is_module`=OLD.is_module,
  `audit_status`=OLD.audit_status,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `item_stock_update_after_trigger` AFTER UPDATE ON `item_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`item_stock_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `item_stock_id`=NEW.item_stock_id,
  `department_id`=NEW.department_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `ntwt`=NEW.ntwt,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `tunch`=NEW.tunch,
  `fine`=NEW.fine,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_details_delete_after_trigger` AFTER DELETE ON `journal_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `jd_id`=OLD.jd_id,
  `journal_id`=OLD.journal_id,
  `type`=OLD.type,
  `account_id`=OLD.account_id,
  `amount`=OLD.amount,
  `narration`=OLD.narration,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_details_insert_after_trigger` AFTER INSERT ON `journal_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `jd_id`=NEW.jd_id,
  `journal_id`=NEW.journal_id,
  `type`=NEW.type,
  `account_id`=NEW.account_id,
  `amount`=NEW.amount,
  `narration`=NEW.narration,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_details_update_after_trigger` AFTER UPDATE ON `journal_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `jd_id`=NEW.jd_id,
  `journal_id`=NEW.journal_id,
  `type`=NEW.type,
  `account_id`=NEW.account_id,
  `amount`=NEW.amount,
  `narration`=NEW.narration,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_insert_after_trigger` AFTER INSERT ON `journal`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `journal_id`=NEW.journal_id,
  `department_id`=NEW.department_id,
  `journal_date`=NEW.journal_date,
  `interest_account_id`=NEW.interest_account_id,
  `gold_rate`=NEW.gold_rate,
  `silver_rate`=NEW.silver_rate,
  `interest_rate`=NEW.interest_rate,
  `relation_id`=NEW.relation_id,
  `is_module`=NEW.is_module,
  `audit_status`=NEW.audit_status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `journal_update_after_trigger` AFTER UPDATE ON `journal`
 FOR EACH ROW INSERT INTO 
  gurulog.`journal_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `journal_id`=NEW.journal_id,
  `department_id`=NEW.department_id,
  `journal_date`=NEW.journal_date,
  `interest_account_id`=NEW.interest_account_id,
  `gold_rate`=NEW.gold_rate,
  `silver_rate`=NEW.silver_rate,
  `interest_rate`=NEW.interest_rate,
  `relation_id`=NEW.relation_id,
  `is_module`=NEW.is_module,
  `audit_status`=NEW.audit_status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_delete_after_trigger` AFTER DELETE ON `machine_chain`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `machine_chain_id`=OLD.machine_chain_id,
  `department_id`=OLD.department_id,
  `operation_id`=OLD.operation_id,
  `worker_id`=OLD.worker_id,
  `machine_chain_date`=OLD.machine_chain_date,
  `reference_no`=OLD.reference_no,
  `lott_complete`=OLD.lott_complete,
  `curb_box`=OLD.curb_box,
  `hisab_done`=OLD.hisab_done,
  `machine_chain_remark`=OLD.machine_chain_remark,
  `total_receive_fw_weight`=OLD.total_receive_fw_weight,
  `total_issue_weight`=OLD.total_issue_weight,
  `total_receive_weight`=OLD.total_receive_weight,
  `total_issue_net_wt`=OLD.total_issue_net_wt,
  `total_receive_net_wt`=OLD.total_receive_net_wt,
  `total_issue_fine`=OLD.total_issue_fine,
  `total_receive_fine`=OLD.total_receive_fine,
  `is_calculated`=OLD.is_calculated,
  `is_forwarded`=OLD.is_forwarded,
  `forwarded_from_mc_id`=OLD.forwarded_from_mc_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_detail_order_items_delete_after_trigger` AFTER DELETE ON `machine_chain_detail_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_detail_order_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_oi_id`=OLD.machine_chain_detail_oi_id,
  `machine_chain_id`=OLD.machine_chain_id,
  `machine_chain_detail_id`=OLD.machine_chain_detail_id,
  `order_id`=OLD.order_id,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_detail_order_items_insert_after_trigger` AFTER INSERT ON `machine_chain_detail_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_detail_order_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_oi_id`=NEW.machine_chain_detail_oi_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `machine_chain_detail_id`=NEW.machine_chain_detail_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_detail_order_items_update_after_trigger` AFTER UPDATE ON `machine_chain_detail_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_detail_order_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_oi_id`=NEW.machine_chain_detail_oi_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `machine_chain_detail_id`=NEW.machine_chain_detail_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_details_delete_after_trigger` AFTER DELETE ON `machine_chain_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_id`=OLD.machine_chain_detail_id,
  `machine_chain_id`=OLD.machine_chain_id,
  `type_id`=OLD.type_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `weight`=OLD.weight,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `actual_tunch`=OLD.actual_tunch,
  `real_actual_tunch`=OLD.real_actual_tunch,
  `fine`=OLD.fine,
  `pcs`=OLD.pcs,
  `machine_chain_detail_date`=OLD.machine_chain_detail_date,
  `tunch_textbox`=OLD.tunch_textbox,
  `machine_chain_detail_remark`=OLD.machine_chain_detail_remark,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `is_forwarded`=OLD.is_forwarded,
  `forwarded_from_mcd_id`=OLD.forwarded_from_mcd_id,
  `added_from_ifw_mcd_id`=OLD.added_from_ifw_mcd_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_details_insert_after_trigger` AFTER INSERT ON `machine_chain_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_id`=NEW.machine_chain_detail_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `real_actual_tunch`=NEW.real_actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `machine_chain_detail_date`=NEW.machine_chain_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `machine_chain_detail_remark`=NEW.machine_chain_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `is_forwarded`=NEW.is_forwarded,
  `forwarded_from_mcd_id`=NEW.forwarded_from_mcd_id,
  `added_from_ifw_mcd_id`=NEW.added_from_ifw_mcd_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_details_update_after_trigger` AFTER UPDATE ON `machine_chain_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `machine_chain_detail_id`=NEW.machine_chain_detail_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `real_actual_tunch`=NEW.real_actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `machine_chain_detail_date`=NEW.machine_chain_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `machine_chain_detail_remark`=NEW.machine_chain_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `is_forwarded`=NEW.is_forwarded,
  `forwarded_from_mcd_id`=NEW.forwarded_from_mcd_id,
  `added_from_ifw_mcd_id`=NEW.added_from_ifw_mcd_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_insert_after_trigger` AFTER INSERT ON `machine_chain`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `machine_chain_id`=NEW.machine_chain_id,
  `department_id`=NEW.department_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `machine_chain_date`=NEW.machine_chain_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `curb_box`=NEW.curb_box,
  `hisab_done`=NEW.hisab_done,
  `machine_chain_remark`=NEW.machine_chain_remark,
  `total_receive_fw_weight`=NEW.total_receive_fw_weight,
  `total_issue_weight`=NEW.total_issue_weight,
  `total_receive_weight`=NEW.total_receive_weight,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `is_calculated`=NEW.is_calculated,
  `is_forwarded`=NEW.is_forwarded,
  `forwarded_from_mc_id`=NEW.forwarded_from_mc_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_delete_after_trigger` AFTER DELETE ON `machine_chain_operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `operation_id`=OLD.operation_id,
  `operation_name`=OLD.operation_name,
  `sequence_no`=OLD.sequence_no,
  `allow_only_1_order_item`=OLD.allow_only_1_order_item,
  `direct_issue_allow`=OLD.direct_issue_allow,
  `calculate_button`=OLD.calculate_button,
  `use_selected_tunch`=OLD.use_selected_tunch,
  `issue_change_actual_tunch_allow`=OLD.issue_change_actual_tunch_allow,
  `receive_change_actual_tunch_allow`=OLD.receive_change_actual_tunch_allow,
  `remark`=OLD.remark,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_department_insert_after_trigger` AFTER INSERT ON `machine_chain_operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_department_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `od_id`=NEW.od_id,
  `operation_id`=NEW.operation_id,
  `department_id`=NEW.department_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_department_delete_after_trigger` AFTER DELETE ON `machine_chain_operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_department_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `od_id`=OLD.od_id,
  `operation_id`=OLD.operation_id,
  `department_id`=OLD.department_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_department_update_after_trigger` AFTER UPDATE ON `machine_chain_operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_department_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `od_id`=NEW.od_id,
  `operation_id`=NEW.operation_id,
  `department_id`=NEW.department_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_insert_after_trigger` AFTER INSERT ON `machine_chain_operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `operation_id`=NEW.operation_id,
  `operation_name`=NEW.operation_name,
  `sequence_no`=NEW.sequence_no,
  `allow_only_1_order_item`=NEW.allow_only_1_order_item,
  `direct_issue_allow`=NEW.direct_issue_allow,
  `calculate_button`=NEW.calculate_button,
  `use_selected_tunch`=NEW.use_selected_tunch,
  `issue_change_actual_tunch_allow`=NEW.issue_change_actual_tunch_allow,
  `receive_change_actual_tunch_allow`=NEW.receive_change_actual_tunch_allow,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_worker_delete_after_trigger` AFTER DELETE ON `machine_chain_operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_worker_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ow_id`=OLD.ow_id,
  `operation_id`=OLD.operation_id,
  `worker_id`=OLD.worker_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_update_after_trigger` AFTER UPDATE ON `machine_chain_operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `operation_id`=NEW.operation_id,
  `operation_name`=NEW.operation_name,
  `sequence_no`=NEW.sequence_no,
  `allow_only_1_order_item`=NEW.allow_only_1_order_item,
  `direct_issue_allow`=NEW.direct_issue_allow,
  `calculate_button`=NEW.calculate_button,
  `use_selected_tunch`=NEW.use_selected_tunch,
  `issue_change_actual_tunch_allow`=NEW.issue_change_actual_tunch_allow,
  `receive_change_actual_tunch_allow`=NEW.receive_change_actual_tunch_allow,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_worker_update_after_trigger` AFTER UPDATE ON `machine_chain_operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_worker_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ow_id`=NEW.ow_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_order_items_delete_after_trigger` AFTER DELETE ON `machine_chain_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_order_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `machine_chain_oi_id`=OLD.machine_chain_oi_id,
  `machine_chain_id`=OLD.machine_chain_id,
  `order_id`=OLD.order_id,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_operation_worker_insert_after_trigger` AFTER INSERT ON `machine_chain_operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_operation_worker_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ow_id`=NEW.ow_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_order_items_insert_after_trigger` AFTER INSERT ON `machine_chain_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_order_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `machine_chain_oi_id`=NEW.machine_chain_oi_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_order_items_update_after_trigger` AFTER UPDATE ON `machine_chain_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_order_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `machine_chain_oi_id`=NEW.machine_chain_oi_id,
  `machine_chain_id`=NEW.machine_chain_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `machine_chain_update_after_trigger` AFTER UPDATE ON `machine_chain`
 FOR EACH ROW INSERT INTO 
  gurulog.`machine_chain_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `machine_chain_id`=NEW.machine_chain_id,
  `department_id`=NEW.department_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `machine_chain_date`=NEW.machine_chain_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `curb_box`=NEW.curb_box,
  `hisab_done`=NEW.hisab_done,
  `machine_chain_remark`=NEW.machine_chain_remark,
  `total_receive_fw_weight`=NEW.total_receive_fw_weight,
  `total_issue_weight`=NEW.total_issue_weight,
  `total_receive_weight`=NEW.total_receive_weight,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `is_calculated`=NEW.is_calculated,
  `is_forwarded`=NEW.is_forwarded,
  `forwarded_from_mc_id`=NEW.forwarded_from_mc_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_ads_delete_after_trigger` AFTER DELETE ON `manu_hand_made_ads`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_ads_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `mhm_ad_id`=OLD.mhm_ad_id,
  `mhm_id`=OLD.mhm_id,
  `ad_id`=OLD.ad_id,
  `ad_pcs`=OLD.ad_pcs,
  `ad_rate`=OLD.ad_rate,
  `ad_amount`=OLD.ad_amount,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_ads_insert_after_trigger` AFTER INSERT ON `manu_hand_made_ads`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_ads_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `mhm_ad_id`=NEW.mhm_ad_id,
  `mhm_id`=NEW.mhm_id,
  `ad_id`=NEW.ad_id,
  `ad_pcs`=NEW.ad_pcs,
  `ad_rate`=NEW.ad_rate,
  `ad_amount`=NEW.ad_amount,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_ads_update_after_trigger` AFTER UPDATE ON `manu_hand_made_ads`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_ads_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `mhm_ad_id`=NEW.mhm_ad_id,
  `mhm_id`=NEW.mhm_id,
  `ad_id`=NEW.ad_id,
  `ad_pcs`=NEW.ad_pcs,
  `ad_rate`=NEW.ad_rate,
  `ad_amount`=NEW.ad_amount,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_delete_after_trigger` AFTER DELETE ON `manu_hand_made`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `mhm_id`=OLD.mhm_id,
  `department_id`=OLD.department_id,
  `operation_id`=OLD.operation_id,
  `worker_id`=OLD.worker_id,
  `mhm_date`=OLD.mhm_date,
  `reference_no`=OLD.reference_no,
  `lott_complete`=OLD.lott_complete,
  `hisab_done`=OLD.hisab_done,
  `mhm_diffrence`=OLD.mhm_diffrence,
  `worker_gold_rate`=OLD.worker_gold_rate,
  `mhm_remark`=OLD.mhm_remark,
  `total_issue_net_wt`=OLD.total_issue_net_wt,
  `total_receive_net_wt`=OLD.total_receive_net_wt,
  `total_issue_fine`=OLD.total_issue_fine,
  `total_receive_fine`=OLD.total_receive_fine,
  `journal_id`=OLD.journal_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_details_delete_after_trigger` AFTER DELETE ON `manu_hand_made_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `mhm_detail_id`=OLD.mhm_detail_id,
  `mhm_id`=OLD.mhm_id,
  `type_id`=OLD.type_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `weight`=OLD.weight,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `actual_tunch`=OLD.actual_tunch,
  `fine`=OLD.fine,
  `pcs`=OLD.pcs,
  `ad_weight`=OLD.ad_weight,
  `including_ad_wt`=OLD.including_ad_wt,
  `mhm_detail_date`=OLD.mhm_detail_date,
  `tunch_textbox`=OLD.tunch_textbox,
  `mhm_detail_remark`=OLD.mhm_detail_remark,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_details_insert_after_trigger` AFTER INSERT ON `manu_hand_made_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `mhm_detail_id`=NEW.mhm_detail_id,
  `mhm_id`=NEW.mhm_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `ad_weight`=NEW.ad_weight,
  `including_ad_wt`=NEW.including_ad_wt,
  `mhm_detail_date`=NEW.mhm_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `mhm_detail_remark`=NEW.mhm_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_details_update_after_trigger` AFTER UPDATE ON `manu_hand_made_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `mhm_detail_id`=NEW.mhm_detail_id,
  `mhm_id`=NEW.mhm_id,
  `type_id`=NEW.type_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `weight`=NEW.weight,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `actual_tunch`=NEW.actual_tunch,
  `fine`=NEW.fine,
  `pcs`=NEW.pcs,
  `ad_weight`=NEW.ad_weight,
  `including_ad_wt`=NEW.including_ad_wt,
  `mhm_detail_date`=NEW.mhm_detail_date,
  `tunch_textbox`=NEW.tunch_textbox,
  `mhm_detail_remark`=NEW.mhm_detail_remark,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_insert_after_trigger` AFTER INSERT ON `manu_hand_made`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `mhm_id`=NEW.mhm_id,
  `department_id`=NEW.department_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `mhm_date`=NEW.mhm_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `mhm_diffrence`=NEW.mhm_diffrence,
  `worker_gold_rate`=NEW.worker_gold_rate,
  `mhm_remark`=NEW.mhm_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `journal_id`=NEW.journal_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_order_items_delete_after_trigger` AFTER DELETE ON `manu_hand_made_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_order_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `mhm_oi_id`=OLD.mhm_oi_id,
  `mhm_id`=OLD.mhm_id,
  `order_id`=OLD.order_id,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_order_items_insert_after_trigger` AFTER INSERT ON `manu_hand_made_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_order_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `mhm_oi_id`=NEW.mhm_oi_id,
  `mhm_id`=NEW.mhm_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_order_items_update_after_trigger` AFTER UPDATE ON `manu_hand_made_order_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_order_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `mhm_oi_id`=NEW.mhm_oi_id,
  `mhm_id`=NEW.mhm_id,
  `order_id`=NEW.order_id,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manu_hand_made_update_after_trigger` AFTER UPDATE ON `manu_hand_made`
 FOR EACH ROW INSERT INTO 
  gurulog.`manu_hand_made_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `mhm_id`=NEW.mhm_id,
  `department_id`=NEW.department_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `mhm_date`=NEW.mhm_date,
  `reference_no`=NEW.reference_no,
  `lott_complete`=NEW.lott_complete,
  `hisab_done`=NEW.hisab_done,
  `mhm_diffrence`=NEW.mhm_diffrence,
  `worker_gold_rate`=NEW.worker_gold_rate,
  `mhm_remark`=NEW.mhm_remark,
  `total_issue_net_wt`=NEW.total_issue_net_wt,
  `total_receive_net_wt`=NEW.total_receive_net_wt,
  `total_issue_fine`=NEW.total_issue_fine,
  `total_receive_fine`=NEW.total_receive_fine,
  `journal_id`=NEW.journal_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manufacture_status_delete_after_trigger` AFTER DELETE ON `manufacture_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`manufacture_status_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `manufacture_status_id`=OLD.manufacture_status_id,
  `manufacture_status_name`=OLD.manufacture_status_name,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manufacture_status_insert_after_trigger` AFTER INSERT ON `manufacture_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`manufacture_status_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `manufacture_status_id`=NEW.manufacture_status_id,
  `manufacture_status_name`=NEW.manufacture_status_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `manufacture_status_update_after_trigger` AFTER UPDATE ON `manufacture_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`manufacture_status_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `manufacture_status_id`=NEW.manufacture_status_id,
  `manufacture_status_name`=NEW.manufacture_status_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `metal_payment_receipt_delete_after_trigger` AFTER DELETE ON `metal_payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`metal_payment_receipt_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `metal_pr_id`=OLD.metal_pr_id,
  `sell_id`=OLD.sell_id,
  `metal_payment_receipt`=OLD.metal_payment_receipt,
  `metal_category_id`=OLD.metal_category_id,
  `metal_item_id`=OLD.metal_item_id,
  `metal_grwt`=OLD.metal_grwt,
  `metal_ntwt`=OLD.metal_ntwt,
  `metal_tunch`=OLD.metal_tunch,
  `metal_fine`=OLD.metal_fine,
  `metal_narration`=OLD.metal_narration,
  `total_gold_fine`=OLD.total_gold_fine,
  `total_silver_fine`=OLD.total_silver_fine,
  `total_other_fine`=OLD.total_other_fine,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `metal_payment_receipt_insert_after_trigger` AFTER INSERT ON `metal_payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`metal_payment_receipt_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `metal_pr_id`=NEW.metal_pr_id,
  `sell_id`=NEW.sell_id,
  `metal_payment_receipt`=NEW.metal_payment_receipt,
  `metal_category_id`=NEW.metal_category_id,
  `metal_item_id`=NEW.metal_item_id,
  `metal_grwt`=NEW.metal_grwt,
  `metal_ntwt`=NEW.metal_ntwt,
  `metal_tunch`=NEW.metal_tunch,
  `metal_fine`=NEW.metal_fine,
  `metal_narration`=NEW.metal_narration,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `total_other_fine`=NEW.total_other_fine,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `metal_payment_receipt_update_after_trigger` AFTER UPDATE ON `metal_payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`metal_payment_receipt_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `metal_pr_id`=NEW.metal_pr_id,
  `sell_id`=NEW.sell_id,
  `metal_payment_receipt`=NEW.metal_payment_receipt,
  `metal_category_id`=NEW.metal_category_id,
  `metal_item_id`=NEW.metal_item_id,
  `metal_grwt`=NEW.metal_grwt,
  `metal_ntwt`=NEW.metal_ntwt,
  `metal_tunch`=NEW.metal_tunch,
  `metal_fine`=NEW.metal_fine,
  `metal_narration`=NEW.metal_narration,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `total_other_fine`=NEW.total_other_fine,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `module_roles_delete_after_trigger` AFTER DELETE ON `module_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`module_roles_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `module_role_id`=OLD.module_role_id,
  `title`=OLD.title,
  `role_name`=OLD.role_name,
  `website_module_id`=OLD.website_module_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `module_roles_insert_after_trigger` AFTER INSERT ON `module_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`module_roles_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `module_role_id`=NEW.module_role_id,
  `title`=NEW.title,
  `role_name`=NEW.role_name,
  `website_module_id`=NEW.website_module_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `module_roles_update_after_trigger` AFTER UPDATE ON `module_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`module_roles_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `module_role_id`=NEW.module_role_id,
  `title`=NEW.title,
  `role_name`=NEW.role_name,
  `website_module_id`=NEW.website_module_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_order_delete_after_trigger` AFTER DELETE ON `new_order`
 FOR EACH ROW INSERT INTO 
  gurulog.`new_order_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `order_id`=OLD.order_id,
  `order_no`=OLD.order_no,
  `process_id`=OLD.process_id,
  `order_date`=OLD.order_date,
  `delivery_date`=OLD.delivery_date,
  `real_delivery_date`=OLD.real_delivery_date,
  `party_id`=OLD.party_id,
  `supplier_id`=OLD.supplier_id,
  `supplier_delivery_date`=OLD.supplier_delivery_date,
  `gold_price`=OLD.gold_price,
  `silver_price`=OLD.silver_price,
  `remark`=OLD.remark,
  `reason`=OLD.reason,
  `total_weight`=OLD.total_weight,
  `total_pcs`=OLD.total_pcs,
  `order_status_id`=OLD.order_status_id,
  `order_type`=OLD.order_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_order_insert_after_trigger` AFTER INSERT ON `new_order`
 FOR EACH ROW INSERT INTO 
  gurulog.`new_order_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `order_id`=NEW.order_id,
  `order_no`=NEW.order_no,
  `process_id`=NEW.process_id,
  `order_date`=NEW.order_date,
  `delivery_date`=NEW.delivery_date,
  `real_delivery_date`=NEW.real_delivery_date,
  `party_id`=NEW.party_id,
  `supplier_id`=NEW.supplier_id,
  `supplier_delivery_date`=NEW.supplier_delivery_date,
  `gold_price`=NEW.gold_price,
  `silver_price`=NEW.silver_price,
  `remark`=NEW.remark,
  `reason`=NEW.reason,
  `total_weight`=NEW.total_weight,
  `total_pcs`=NEW.total_pcs,
  `order_status_id`=NEW.order_status_id,
  `order_type`=NEW.order_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `opening_stock_delete_after_trigger` AFTER DELETE ON `opening_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`opening_stock_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `opening_stock_id`=OLD.opening_stock_id,
  `department_id`=OLD.department_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `ntwt`=OLD.ntwt,
  `grwt`=OLD.grwt,
  `less`=OLD.less,
  `tunch`=OLD.tunch,
  `fine`=OLD.fine,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `opening_stock_insert_after_trigger` AFTER INSERT ON `opening_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`opening_stock_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `opening_stock_id`=NEW.opening_stock_id,
  `department_id`=NEW.department_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `ntwt`=NEW.ntwt,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `tunch`=NEW.tunch,
  `fine`=NEW.fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `new_order_update_after_trigger` AFTER UPDATE ON `new_order`
 FOR EACH ROW INSERT INTO 
  gurulog.`new_order_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `order_id`=NEW.order_id,
  `order_no`=NEW.order_no,
  `process_id`=NEW.process_id,
  `order_date`=NEW.order_date,
  `delivery_date`=NEW.delivery_date,
  `real_delivery_date`=NEW.real_delivery_date,
  `party_id`=NEW.party_id,
  `supplier_id`=NEW.supplier_id,
  `supplier_delivery_date`=NEW.supplier_delivery_date,
  `gold_price`=NEW.gold_price,
  `silver_price`=NEW.silver_price,
  `remark`=NEW.remark,
  `reason`=NEW.reason,
  `total_weight`=NEW.total_weight,
  `total_pcs`=NEW.total_pcs,
  `order_status_id`=NEW.order_status_id,
  `order_type`=NEW.order_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `opening_stock_update_after_trigger` AFTER UPDATE ON `opening_stock`
 FOR EACH ROW INSERT INTO 
  gurulog.`opening_stock_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `opening_stock_id`=NEW.opening_stock_id,
  `department_id`=NEW.department_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `ntwt`=NEW.ntwt,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `tunch`=NEW.tunch,
  `fine`=NEW.fine,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_delete_after_trigger` AFTER DELETE ON `operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `operation_id`=OLD.operation_id,
  `operation_name`=OLD.operation_name,
  `fix_loss`=OLD.fix_loss,
  `fix_loss_per`=OLD.fix_loss_per,
  `max_loss_allow`=OLD.max_loss_allow,
  `max_loss_wt`=OLD.max_loss_wt,
  `issue_finish_fix_loss`=OLD.issue_finish_fix_loss,
  `issue_finish_fix_loss_per`=OLD.issue_finish_fix_loss_per,
  `remark`=OLD.remark,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_department_delete_after_trigger` AFTER DELETE ON `operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_department_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `od_id`=OLD.od_id,
  `operation_id`=OLD.operation_id,
  `department_id`=OLD.department_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_department_insert_after_trigger` AFTER INSERT ON `operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_department_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `od_id`=NEW.od_id,
  `operation_id`=NEW.operation_id,
  `department_id`=NEW.department_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_department_update_after_trigger` AFTER UPDATE ON `operation_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_department_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `od_id`=NEW.od_id,
  `operation_id`=NEW.operation_id,
  `department_id`=NEW.department_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_insert_after_trigger` AFTER INSERT ON `operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `operation_id`=NEW.operation_id,
  `operation_name`=NEW.operation_name,
  `fix_loss`=NEW.fix_loss,
  `fix_loss_per`=NEW.fix_loss_per,
  `max_loss_allow`=NEW.max_loss_allow,
  `max_loss_wt`=NEW.max_loss_wt,
  `issue_finish_fix_loss`=NEW.issue_finish_fix_loss,
  `issue_finish_fix_loss_per`=NEW.issue_finish_fix_loss_per,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_update_after_trigger` AFTER UPDATE ON `operation`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `operation_id`=NEW.operation_id,
  `operation_name`=NEW.operation_name,
  `fix_loss`=NEW.fix_loss,
  `fix_loss_per`=NEW.fix_loss_per,
  `max_loss_allow`=NEW.max_loss_allow,
  `max_loss_wt`=NEW.max_loss_wt,
  `issue_finish_fix_loss`=NEW.issue_finish_fix_loss,
  `issue_finish_fix_loss_per`=NEW.issue_finish_fix_loss_per,
  `remark`=NEW.remark,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_worker_delete_after_trigger` AFTER DELETE ON `operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_worker_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ow_id`=OLD.ow_id,
  `operation_id`=OLD.operation_id,
  `worker_id`=OLD.worker_id,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_worker_insert_after_trigger` AFTER INSERT ON `operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_worker_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ow_id`=NEW.ow_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `operation_worker_update_after_trigger` AFTER UPDATE ON `operation_worker`
 FOR EACH ROW INSERT INTO 
  gurulog.`operation_worker_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ow_id`=NEW.ow_id,
  `operation_id`=NEW.operation_id,
  `worker_id`=NEW.worker_id,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_lot_item_delete_after_trigger` AFTER DELETE ON `order_lot_item`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_lot_item_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `order_lot_item_id`=OLD.order_lot_item_id,
  `order_id`=OLD.order_id,
  `order_item_no`=OLD.order_item_no,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `touch_id`=OLD.touch_id,
  `weight`=OLD.weight,
  `pcs`=OLD.pcs,
  `size`=OLD.size,
  `length`=OLD.length,
  `hook_style`=OLD.hook_style,
  `item_status_id`=OLD.item_status_id,
  `image`=OLD.image,
  `lot_remark`=OLD.lot_remark,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_lot_item_insert_after_trigger` AFTER INSERT ON `order_lot_item`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_lot_item_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `order_lot_item_id`=NEW.order_lot_item_id,
  `order_id`=NEW.order_id,
  `order_item_no`=NEW.order_item_no,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `touch_id`=NEW.touch_id,
  `weight`=NEW.weight,
  `pcs`=NEW.pcs,
  `size`=NEW.size,
  `length`=NEW.length,
  `hook_style`=NEW.hook_style,
  `item_status_id`=NEW.item_status_id,
  `image`=NEW.image,
  `lot_remark`=NEW.lot_remark,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_lot_item_update_after_trigger` AFTER UPDATE ON `order_lot_item`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_lot_item_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `order_lot_item_id`=NEW.order_lot_item_id,
  `order_id`=NEW.order_id,
  `order_item_no`=NEW.order_item_no,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `touch_id`=NEW.touch_id,
  `weight`=NEW.weight,
  `pcs`=NEW.pcs,
  `size`=NEW.size,
  `length`=NEW.length,
  `hook_style`=NEW.hook_style,
  `item_status_id`=NEW.item_status_id,
  `image`=NEW.image,
  `lot_remark`=NEW.lot_remark,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_status_delete_after_trigger` AFTER DELETE ON `order_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_status_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `order_status_id`=OLD.order_status_id,
  `status`=OLD.status
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_status_insert_after_trigger` AFTER INSERT ON `order_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_status_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `order_status_id`=NEW.order_status_id,
  `status`=NEW.status
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_status_update_after_trigger` AFTER UPDATE ON `order_status`
 FOR EACH ROW INSERT INTO 
  gurulog.`order_status_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `order_status_id`=NEW.order_status_id,
  `status`=NEW.status
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_delete_after_trigger` AFTER DELETE ON `other`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `other_id`=OLD.other_id,
  `other_no`=OLD.other_no,
  `account_id`=OLD.account_id,
  `department_id`=OLD.department_id,
  `other_date`=OLD.other_date,
  `other_remark`=OLD.other_remark,
  `total_grwt`=OLD.total_grwt,
  `total_amount`=OLD.total_amount,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_insert_after_trigger` AFTER INSERT ON `other`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `other_id`=NEW.other_id,
  `other_no`=NEW.other_no,
  `account_id`=NEW.account_id,
  `department_id`=NEW.department_id,
  `other_date`=NEW.other_date,
  `other_remark`=NEW.other_remark,
  `total_grwt`=NEW.total_grwt,
  `total_amount`=NEW.total_amount,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_items_delete_after_trigger` AFTER DELETE ON `other_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `other_item_id`=OLD.other_item_id,
  `other_id`=OLD.other_id,
  `other_item_no`=OLD.other_item_no,
  `type`=OLD.type,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `grwt`=OLD.grwt,
  `rate`=OLD.rate,
  `amount`=OLD.amount,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_items_update_after_trigger` AFTER UPDATE ON `other_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `other_item_id`=NEW.other_item_id,
  `other_id`=NEW.other_id,
  `other_item_no`=NEW.other_item_no,
  `type`=NEW.type,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `grwt`=NEW.grwt,
  `rate`=NEW.rate,
  `amount`=NEW.amount,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_items_insert_after_trigger` AFTER INSERT ON `other_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `other_item_id`=NEW.other_item_id,
  `other_id`=NEW.other_id,
  `other_item_no`=NEW.other_item_no,
  `type`=NEW.type,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `grwt`=NEW.grwt,
  `rate`=NEW.rate,
  `amount`=NEW.amount,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `other_update_after_trigger` AFTER UPDATE ON `other`
 FOR EACH ROW INSERT INTO 
  gurulog.`other_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `other_id`=NEW.other_id,
  `other_no`=NEW.other_no,
  `account_id`=NEW.account_id,
  `department_id`=NEW.department_id,
  `other_date`=NEW.other_date,
  `other_remark`=NEW.other_remark,
  `total_grwt`=NEW.total_grwt,
  `total_amount`=NEW.total_amount,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `party_item_details_delete_after_trigger` AFTER DELETE ON `party_item_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`party_item_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `party_item_id`=OLD.party_item_id,
  `account_id`=OLD.account_id,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `wstg`=OLD.wstg,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `party_item_details_insert_after_trigger` AFTER INSERT ON `party_item_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`party_item_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `party_item_id`=NEW.party_item_id,
  `account_id`=NEW.account_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `wstg`=NEW.wstg,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `party_item_details_update_after_trigger` AFTER UPDATE ON `party_item_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`party_item_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `party_item_id`=NEW.party_item_id,
  `account_id`=NEW.account_id,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `wstg`=NEW.wstg,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `payment_receipt_delete_after_trigger` AFTER DELETE ON `payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`payment_receipt_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `pay_rec_id`=OLD.pay_rec_id,
  `sell_id`=OLD.sell_id,
  `other_id`=OLD.other_id,
  `payment_receipt`=OLD.payment_receipt,
  `cash_cheque`=OLD.cash_cheque,
  `bank_id`=OLD.bank_id,
  `voucher_no`=OLD.voucher_no,
  `transaction_date`=OLD.transaction_date,
  `department_id`=OLD.department_id,
  `account_id`=OLD.account_id,
  `on_behalf_of`=OLD.on_behalf_of,
  `amount`=OLD.amount,
  `narration`=OLD.narration,
  `is_payment_received`=OLD.is_payment_received,
  `audit_status`=OLD.audit_status,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `payment_receipt_insert_after_trigger` AFTER INSERT ON `payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`payment_receipt_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `pay_rec_id`=NEW.pay_rec_id,
  `sell_id`=NEW.sell_id,
  `other_id`=NEW.other_id,
  `payment_receipt`=NEW.payment_receipt,
  `cash_cheque`=NEW.cash_cheque,
  `bank_id`=NEW.bank_id,
  `voucher_no`=NEW.voucher_no,
  `transaction_date`=NEW.transaction_date,
  `department_id`=NEW.department_id,
  `account_id`=NEW.account_id,
  `on_behalf_of`=NEW.on_behalf_of,
  `amount`=NEW.amount,
  `narration`=NEW.narration,
  `is_payment_received`=NEW.is_payment_received,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `payment_receipt_update_after_trigger` AFTER UPDATE ON `payment_receipt`
 FOR EACH ROW INSERT INTO 
  gurulog.`payment_receipt_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `pay_rec_id`=NEW.pay_rec_id,
  `sell_id`=NEW.sell_id,
  `other_id`=NEW.other_id,
  `payment_receipt`=NEW.payment_receipt,
  `cash_cheque`=NEW.cash_cheque,
  `bank_id`=NEW.bank_id,
  `voucher_no`=NEW.voucher_no,
  `transaction_date`=NEW.transaction_date,
  `department_id`=NEW.department_id,
  `account_id`=NEW.account_id,
  `on_behalf_of`=NEW.on_behalf_of,
  `amount`=NEW.amount,
  `narration`=NEW.narration,
  `is_payment_received`=NEW.is_payment_received,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `process_master_delete_after_trigger` AFTER DELETE ON `process_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`process_master_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `process_id`=OLD.process_id,
  `process_name`=OLD.process_name,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `process_master_insert_after_trigger` AFTER INSERT ON `process_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`process_master_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `process_id`=NEW.process_id,
  `process_name`=NEW.process_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `process_master_update_after_trigger` AFTER UPDATE ON `process_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`process_master_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `process_id`=NEW.process_id,
  `process_name`=NEW.process_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reminder_delete_after_trigger` AFTER DELETE ON `reminder`
 FOR EACH ROW INSERT INTO 
  gurulog.`reminder_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `reminder_id`=OLD.reminder_id,
  `account_id`=OLD.account_id,
  `date`=OLD.date,
  `debit_credit`=OLD.debit_credit,
  `amount`=OLD.amount,
  `gold`=OLD.gold,
  `silver`=OLD.silver,
  `remarks`=OLD.remarks,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reminder_insert_after_trigger` AFTER INSERT ON `reminder`
 FOR EACH ROW INSERT INTO 
  gurulog.`reminder_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `reminder_id`=NEW.reminder_id,
  `account_id`=NEW.account_id,
  `date`=NEW.date,
  `debit_credit`=NEW.debit_credit,
  `amount`=NEW.amount,
  `gold`=NEW.gold,
  `silver`=NEW.silver,
  `remarks`=NEW.remarks,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reminder_update_after_trigger` AFTER UPDATE ON `reminder`
 FOR EACH ROW INSERT INTO 
  gurulog.`reminder_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `reminder_id`=NEW.reminder_id,
  `account_id`=NEW.account_id,
  `date`=NEW.date,
  `debit_credit`=NEW.debit_credit,
  `amount`=NEW.amount,
  `gold`=NEW.gold,
  `silver`=NEW.silver,
  `remarks`=NEW.remarks,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reply_delete_after_trigger` AFTER DELETE ON `reply`
 FOR EACH ROW INSERT INTO 
  gurulog.`reply_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `reply_id`=OLD.reply_id,
  `feedback_id`=OLD.feedback_id,
  `assign_to_id`=OLD.assign_to_id,
  `reply_date`=OLD.reply_date,
  `reply`=OLD.reply,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reply_insert_after_trigger` AFTER INSERT ON `reply`
 FOR EACH ROW INSERT INTO 
  gurulog.`reply_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `reply_id`=NEW.reply_id,
  `feedback_id`=NEW.feedback_id,
  `assign_to_id`=NEW.assign_to_id,
  `reply_date`=NEW.reply_date,
  `reply`=NEW.reply,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reply_update_after_trigger` AFTER UPDATE ON `reply`
 FOR EACH ROW INSERT INTO 
  gurulog.`reply_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `reply_id`=NEW.reply_id,
  `feedback_id`=NEW.feedback_id,
  `assign_to_id`=NEW.assign_to_id,
  `reply_date`=NEW.reply_date,
  `reply`=NEW.reply,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_ad_charges_delete_after_trigger` AFTER DELETE ON `sell_ad_charges`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_ad_charges_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `sell_ad_charges_id`=OLD.sell_ad_charges_id,
  `sell_id`=OLD.sell_id,
  `ad_id`=OLD.ad_id,
  `ad_pcs`=OLD.ad_pcs,
  `ad_rate`=OLD.ad_rate,
  `ad_amount`=OLD.ad_amount,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_ad_charges_insert_after_trigger` AFTER INSERT ON `sell_ad_charges`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_ad_charges_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `sell_ad_charges_id`=NEW.sell_ad_charges_id,
  `sell_id`=NEW.sell_id,
  `ad_id`=NEW.ad_id,
  `ad_pcs`=NEW.ad_pcs,
  `ad_rate`=NEW.ad_rate,
  `ad_amount`=NEW.ad_amount,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_ad_charges_update_after_trigger` AFTER UPDATE ON `sell_ad_charges`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_ad_charges_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `sell_ad_charges_id`=NEW.sell_ad_charges_id,
  `sell_id`=NEW.sell_id,
  `ad_id`=NEW.ad_id,
  `ad_pcs`=NEW.ad_pcs,
  `ad_rate`=NEW.ad_rate,
  `ad_amount`=NEW.ad_amount,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_delete_after_trigger` AFTER DELETE ON `sell`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `sell_id`=OLD.sell_id,
  `sell_no`=OLD.sell_no,
  `account_id`=OLD.account_id,
  `process_id`=OLD.process_id,
  `sell_date`=OLD.sell_date,
  `sell_remark`=OLD.sell_remark,
  `order_id`=OLD.order_id,
  `total_gold_fine`=OLD.total_gold_fine,
  `total_silver_fine`=OLD.total_silver_fine,
  `total_amount`=OLD.total_amount,
  `delivery_type`=OLD.delivery_type,
  `audit_status`=OLD.audit_status,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_insert_after_trigger` AFTER INSERT ON `sell`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `sell_id`=NEW.sell_id,
  `sell_no`=NEW.sell_no,
  `account_id`=NEW.account_id,
  `process_id`=NEW.process_id,
  `sell_date`=NEW.sell_date,
  `sell_remark`=NEW.sell_remark,
  `order_id`=NEW.order_id,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `total_amount`=NEW.total_amount,
  `delivery_type`=NEW.delivery_type,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_items_delete_after_trigger` AFTER DELETE ON `sell_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_items_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `sell_item_id`=OLD.sell_item_id,
  `sell_id`=OLD.sell_id,
  `sell_item_no`=OLD.sell_item_no,
  `tunch_textbox`=OLD.tunch_textbox,
  `type`=OLD.type,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `grwt`=OLD.grwt,
  `less`=OLD.less,
  `net_wt`=OLD.net_wt,
  `touch_id`=OLD.touch_id,
  `wstg`=OLD.wstg,
  `fine`=OLD.fine,
  `image`=OLD.image,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `wastage_change_approve`=OLD.wastage_change_approve,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_items_insert_after_trigger` AFTER INSERT ON `sell_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_items_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `sell_item_id`=NEW.sell_item_id,
  `sell_id`=NEW.sell_id,
  `sell_item_no`=NEW.sell_item_no,
  `tunch_textbox`=NEW.tunch_textbox,
  `type`=NEW.type,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `touch_id`=NEW.touch_id,
  `wstg`=NEW.wstg,
  `fine`=NEW.fine,
  `image`=NEW.image,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `wastage_change_approve`=NEW.wastage_change_approve,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_items_update_after_trigger` AFTER UPDATE ON `sell_items`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_items_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `sell_item_id`=NEW.sell_item_id,
  `sell_id`=NEW.sell_id,
  `sell_item_no`=NEW.sell_item_no,
  `tunch_textbox`=NEW.tunch_textbox,
  `type`=NEW.type,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `net_wt`=NEW.net_wt,
  `touch_id`=NEW.touch_id,
  `wstg`=NEW.wstg,
  `fine`=NEW.fine,
  `image`=NEW.image,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `wastage_change_approve`=NEW.wastage_change_approve,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_less_ad_details_delete_after_trigger` AFTER DELETE ON `sell_less_ad_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_less_ad_details_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `sell_less_ad_details_id`=OLD.sell_less_ad_details_id,
  `sell_id`=OLD.sell_id,
  `sell_item_id`=OLD.sell_item_id,
  `less_ad_details_ad_id`=OLD.less_ad_details_ad_id,
  `less_ad_details_ad_pcs`=OLD.less_ad_details_ad_pcs,
  `less_ad_details_ad_weight`=OLD.less_ad_details_ad_weight,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_less_ad_details_insert_after_trigger` AFTER INSERT ON `sell_less_ad_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_less_ad_details_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `sell_less_ad_details_id`=NEW.sell_less_ad_details_id,
  `sell_id`=NEW.sell_id,
  `sell_item_id`=NEW.sell_item_id,
  `less_ad_details_ad_id`=NEW.less_ad_details_ad_id,
  `less_ad_details_ad_pcs`=NEW.less_ad_details_ad_pcs,
  `less_ad_details_ad_weight`=NEW.less_ad_details_ad_weight,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_less_ad_details_update_after_trigger` AFTER UPDATE ON `sell_less_ad_details`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_less_ad_details_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `sell_less_ad_details_id`=NEW.sell_less_ad_details_id,
  `sell_id`=NEW.sell_id,
  `sell_item_id`=NEW.sell_item_id,
  `less_ad_details_ad_id`=NEW.less_ad_details_ad_id,
  `less_ad_details_ad_pcs`=NEW.less_ad_details_ad_pcs,
  `less_ad_details_ad_weight`=NEW.less_ad_details_ad_weight,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_type_delete_after_trigger` AFTER DELETE ON `sell_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_type_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `sell_type_id`=OLD.sell_type_id,
  `type_name`=OLD.type_name,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_type_insert_after_trigger` AFTER INSERT ON `sell_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_type_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `sell_type_id`=NEW.sell_type_id,
  `type_name`=NEW.type_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_type_update_after_trigger` AFTER UPDATE ON `sell_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_type_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `sell_type_id`=NEW.sell_type_id,
  `type_name`=NEW.type_name,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sell_update_after_trigger` AFTER UPDATE ON `sell`
 FOR EACH ROW INSERT INTO 
  gurulog.`sell_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `sell_id`=NEW.sell_id,
  `sell_no`=NEW.sell_no,
  `account_id`=NEW.account_id,
  `process_id`=NEW.process_id,
  `sell_date`=NEW.sell_date,
  `sell_remark`=NEW.sell_remark,
  `order_id`=NEW.order_id,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `total_amount`=NEW.total_amount,
  `delivery_type`=NEW.delivery_type,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `setting_mac_address_delete_after_trigger` AFTER DELETE ON `setting_mac_address`
 FOR EACH ROW INSERT INTO 
  gurulog.`setting_mac_address_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `setting_mac_address_id`=OLD.id,
  `mac_address`=OLD.mac_address
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `setting_mac_address_update_after_trigger` AFTER UPDATE ON `setting_mac_address`
 FOR EACH ROW INSERT INTO 
  gurulog.`setting_mac_address_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `setting_mac_address_id`=NEW.id,
  `mac_address`=NEW.mac_address
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `settings_delete_after_trigger` AFTER DELETE ON `settings`
 FOR EACH ROW INSERT INTO 
  gurulog.`settings_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `settings_id`=OLD.settings_id,
  `settings_key`=OLD.settings_key,
  `settings_label`=OLD.settings_label,
  `settings_value`=OLD.settings_value
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `setting_mac_address_insert_after_trigger` AFTER INSERT ON `setting_mac_address`
 FOR EACH ROW INSERT INTO 
  gurulog.`setting_mac_address_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `setting_mac_address_id`=NEW.id,
  `mac_address`=NEW.mac_address
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `settings_update_after_trigger` AFTER UPDATE ON `settings`
 FOR EACH ROW INSERT INTO 
  gurulog.`settings_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `settings_id`=NEW.settings_id,
  `settings_key`=NEW.settings_key,
  `settings_label`=NEW.settings_label,
  `settings_value`=NEW.settings_value
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `settings_insert_after_trigger` AFTER INSERT ON `settings`
 FOR EACH ROW INSERT INTO 
  gurulog.`settings_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `settings_id`=NEW.settings_id,
  `settings_key`=NEW.settings_key,
  `settings_label`=NEW.settings_label,
  `settings_value`=NEW.settings_value
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `silver_bhav_delete_after_trigger` AFTER DELETE ON `silver_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`silver_bhav_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `silver_id`=OLD.silver_id,
  `sell_id`=OLD.sell_id,
  `silver_sale_purchase`=OLD.silver_sale_purchase,
  `silver_weight`=OLD.silver_weight,
  `silver_rate`=OLD.silver_rate,
  `silver_value`=OLD.silver_value,
  `silver_narration`=OLD.silver_narration,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `silver_bhav_insert_after_trigger` AFTER INSERT ON `silver_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`silver_bhav_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `silver_id`=NEW.silver_id,
  `sell_id`=NEW.sell_id,
  `silver_sale_purchase`=NEW.silver_sale_purchase,
  `silver_weight`=NEW.silver_weight,
  `silver_rate`=NEW.silver_rate,
  `silver_value`=NEW.silver_value,
  `silver_narration`=NEW.silver_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `silver_bhav_update_after_trigger` AFTER UPDATE ON `silver_bhav`
 FOR EACH ROW INSERT INTO 
  gurulog.`silver_bhav_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `silver_id`=NEW.silver_id,
  `sell_id`=NEW.sell_id,
  `silver_sale_purchase`=NEW.silver_sale_purchase,
  `silver_weight`=NEW.silver_weight,
  `silver_rate`=NEW.silver_rate,
  `silver_value`=NEW.silver_value,
  `silver_narration`=NEW.silver_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `state_delete_after_trigger` AFTER DELETE ON `state`
 FOR EACH ROW INSERT INTO 
  gurulog.`state_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `state_id`=OLD.state_id,
  `state_name`=OLD.state_name,
  `is_deleted`=OLD.is_deleted,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `state_insert_after_trigger` AFTER INSERT ON `state`
 FOR EACH ROW INSERT INTO 
  gurulog.`state_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `state_id`=NEW.state_id,
  `state_name`=NEW.state_name,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `state_update_after_trigger` AFTER UPDATE ON `state`
 FOR EACH ROW INSERT INTO 
  gurulog.`state_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `state_id`=NEW.state_id,
  `state_name`=NEW.state_name,
  `is_deleted`=NEW.is_deleted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_delete_after_trigger` AFTER DELETE ON `stock_transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `stock_transfer_id`=OLD.stock_transfer_id,
  `transfer_date`=OLD.transfer_date,
  `from_department`=OLD.from_department,
  `to_department`=OLD.to_department,
  `narration`=OLD.narration,
  `total_gold_fine`=OLD.total_gold_fine,
  `total_silver_fine`=OLD.total_silver_fine,
  `audit_status`=OLD.audit_status,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at,
  `guard_checked`=OLD.guard_checked,
  `guard_checked_narration`=OLD.guard_checked_narration,
  `guard_checked_first_at`=OLD.guard_checked_first_at,
  `guard_checked_last_at`=OLD.guard_checked_last_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_detail_delete_after_trigger` AFTER DELETE ON `stock_transfer_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_detail_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `transfer_detail_id`=OLD.transfer_detail_id,
  `stock_transfer_id`=OLD.stock_transfer_id,
  `tunch_textbox`=OLD.tunch_textbox,
  `category_id`=OLD.category_id,
  `item_id`=OLD.item_id,
  `tunch`=OLD.tunch,
  `grwt`=OLD.grwt,
  `less`=OLD.less,
  `ntwt`=OLD.ntwt,
  `wstg`=OLD.wstg,
  `fine`=OLD.fine,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_detail_insert_after_trigger` AFTER INSERT ON `stock_transfer_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_detail_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `transfer_detail_id`=NEW.transfer_detail_id,
  `stock_transfer_id`=NEW.stock_transfer_id,
  `tunch_textbox`=NEW.tunch_textbox,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `ntwt`=NEW.ntwt,
  `wstg`=NEW.wstg,
  `fine`=NEW.fine,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_insert_after_trigger` AFTER INSERT ON `stock_transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `stock_transfer_id`=NEW.stock_transfer_id,
  `transfer_date`=NEW.transfer_date,
  `from_department`=NEW.from_department,
  `to_department`=NEW.to_department,
  `narration`=NEW.narration,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at,
  `guard_checked`=NEW.guard_checked,
  `guard_checked_narration`=NEW.guard_checked_narration,
  `guard_checked_first_at`=NEW.guard_checked_first_at,
  `guard_checked_last_at`=NEW.guard_checked_last_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_detail_update_after_trigger` AFTER UPDATE ON `stock_transfer_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_detail_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `transfer_detail_id`=NEW.transfer_detail_id,
  `stock_transfer_id`=NEW.stock_transfer_id,
  `tunch_textbox`=NEW.tunch_textbox,
  `category_id`=NEW.category_id,
  `item_id`=NEW.item_id,
  `tunch`=NEW.tunch,
  `grwt`=NEW.grwt,
  `less`=NEW.less,
  `ntwt`=NEW.ntwt,
  `wstg`=NEW.wstg,
  `fine`=NEW.fine,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stock_transfer_update_after_trigger` AFTER UPDATE ON `stock_transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`stock_transfer_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `stock_transfer_id`=NEW.stock_transfer_id,
  `transfer_date`=NEW.transfer_date,
  `from_department`=NEW.from_department,
  `to_department`=NEW.to_department,
  `narration`=NEW.narration,
  `total_gold_fine`=NEW.total_gold_fine,
  `total_silver_fine`=NEW.total_silver_fine,
  `audit_status`=NEW.audit_status,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at,
  `guard_checked`=NEW.guard_checked,
  `guard_checked_narration`=NEW.guard_checked_narration,
  `guard_checked_first_at`=NEW.guard_checked_first_at,
  `guard_checked_last_at`=NEW.guard_checked_last_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `transfer_delete_after_trigger` AFTER DELETE ON `transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`transfer_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `transfer_id`=OLD.transfer_id,
  `sell_id`=OLD.sell_id,
  `naam_jama`=OLD.naam_jama,
  `transfer_account_id`=OLD.transfer_account_id,
  `transfer_gold`=OLD.transfer_gold,
  `transfer_silver`=OLD.transfer_silver,
  `transfer_amount`=OLD.transfer_amount,
  `transfer_narration`=OLD.transfer_narration,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `transfer_insert_after_trigger` AFTER INSERT ON `transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`transfer_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `transfer_id`=NEW.transfer_id,
  `sell_id`=NEW.sell_id,
  `naam_jama`=NEW.naam_jama,
  `transfer_account_id`=NEW.transfer_account_id,
  `transfer_gold`=NEW.transfer_gold,
  `transfer_silver`=NEW.transfer_silver,
  `transfer_amount`=NEW.transfer_amount,
  `transfer_narration`=NEW.transfer_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `transfer_update_after_trigger` AFTER UPDATE ON `transfer`
 FOR EACH ROW INSERT INTO 
  gurulog.`transfer_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `transfer_id`=NEW.transfer_id,
  `sell_id`=NEW.sell_id,
  `naam_jama`=NEW.naam_jama,
  `transfer_account_id`=NEW.transfer_account_id,
  `transfer_gold`=NEW.transfer_gold,
  `transfer_silver`=NEW.transfer_silver,
  `transfer_amount`=NEW.transfer_amount,
  `transfer_narration`=NEW.transfer_narration,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `twilio_webhook_demo_delete_after_trigger` AFTER DELETE ON `twilio_webhook_demo`
 FOR EACH ROW INSERT INTO 
  gurulog.`twilio_webhook_demo_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ai_id`=OLD.id,
  `webhook_type`=OLD.webhook_type,
  `webhook_content`=OLD.webhook_content,
  `message_from`=OLD.message_from,
  `message_to`=OLD.message_to,
  `message_body`=OLD.message_body,
  `message_status`=OLD.message_status,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `twilio_webhook_demo_insert_after_trigger` AFTER INSERT ON `twilio_webhook_demo`
 FOR EACH ROW INSERT INTO 
  gurulog.`twilio_webhook_demo_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ai_id`=NEW.id,
  `webhook_type`=NEW.webhook_type,
  `webhook_content`=NEW.webhook_content,
  `message_from`=NEW.message_from,
  `message_to`=NEW.message_to,
  `message_body`=NEW.message_body,
  `message_status`=NEW.message_status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `twilio_webhook_demo_update_after_trigger` AFTER UPDATE ON `twilio_webhook_demo`
 FOR EACH ROW INSERT INTO 
  gurulog.`twilio_webhook_demo_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ai_id`=NEW.id,
  `webhook_type`=NEW.webhook_type,
  `webhook_content`=NEW.webhook_content,
  `message_from`=NEW.message_from,
  `message_to`=NEW.message_to,
  `message_body`=NEW.message_body,
  `message_status`=NEW.message_status,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_balance_date_before_insert` BEFORE INSERT ON `account`
 FOR EACH ROW IF (NEW.gold_fine != 0 OR NEW.silver_fine != 0 OR NEW.amount != 0) THEN
  SET  NEW.`balance_date`= NOW();
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_balance_date_before_update` BEFORE UPDATE ON `account`
 FOR EACH ROW IF (NEW.gold_fine != OLD.gold_fine OR NEW.silver_fine != OLD.silver_fine OR NEW.amount != OLD.amount) THEN
  SET  NEW.`balance_date`= NOW();
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_status_on_insert` AFTER INSERT ON `order_lot_item`
 FOR EACH ROW BEGIN
DECLARE realmID INT DEFAULT 0;
DECLARE completed INT DEFAULT 0;
DECLARE pending INT DEFAULT 0;

SET realmID = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id`NOT IN (2,3));

SET completed = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id`NOT IN (2));

SET pending = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id` = 1);

IF (pending > 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='1'
       WHERE `n`.`order_id`=NEW.order_id;
ELSEIF (completed = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='2'
       WHERE `n`.`order_id`=NEW.order_id;
ELSEIF (realmID = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='3'
       WHERE `n`.`order_id`=NEW.order_id;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_status_on_update` AFTER UPDATE ON `order_lot_item`
 FOR EACH ROW BEGIN
DECLARE realmID INT DEFAULT 0;
DECLARE completed INT DEFAULT 0;
DECLARE pending INT DEFAULT 0;

SET realmID = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id`NOT IN (2,3));

SET completed = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id`NOT IN (2));

SET pending = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=NEW.order_id AND `item_status_id` = 1);

IF (pending > 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='1'
       WHERE `n`.`order_id`=NEW.order_id;
ELSEIF (completed = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='2'
       WHERE `n`.`order_id`=NEW.order_id;
ELSEIF (realmID = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='3'
       WHERE `n`.`order_id`=NEW.order_id;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_account_group_delete_after_trigger` AFTER DELETE ON `user_account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_account_group_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ud_id`=OLD.ud_id,
  `user_id`=OLD.user_id,
  `account_group_id`=OLD.account_group_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_account_group_insert_after_trigger` AFTER INSERT ON `user_account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_account_group_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `account_group_id`=NEW.account_group_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_account_group_update_after_trigger` AFTER UPDATE ON `user_account_group`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_account_group_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `account_group_id`=NEW.account_group_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_department_insert_after_trigger` AFTER INSERT ON `user_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_department_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_department_delete_after_trigger` AFTER DELETE ON `user_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_department_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ud_id`=OLD.ud_id,
  `user_id`=OLD.user_id,
  `department_id`=OLD.department_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_department_update_after_trigger` AFTER UPDATE ON `user_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_department_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_family_member_delete_after_trigger` AFTER DELETE ON `user_family_member`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_family_member_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `fm_id`=OLD.fm_id,
  `user_id`=OLD.user_id,
  `member_name`=OLD.member_name,
  `member_phone_no`=OLD.member_phone_no,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_family_member_insert_after_trigger` AFTER INSERT ON `user_family_member`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_family_member_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `fm_id`=NEW.fm_id,
  `user_id`=NEW.user_id,
  `member_name`=NEW.member_name,
  `member_phone_no`=NEW.member_phone_no,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_family_member_update_after_trigger` AFTER UPDATE ON `user_family_member`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_family_member_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `fm_id`=NEW.fm_id,
  `user_id`=NEW.user_id,
  `member_name`=NEW.member_name,
  `member_phone_no`=NEW.member_phone_no,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_master_delete_after_trigger` AFTER DELETE ON `user_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_master_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `user_id`=OLD.user_id,
  `user_name`=OLD.user_name,
  `login_username`=OLD.login_username,
  `user_mobile`=OLD.user_mobile,
  `user_type`=OLD.user_type,
  `is_cad_designer`=OLD.is_cad_designer,
  `default_department_id`=OLD.default_department_id,
  `user_password`=OLD.user_password,
  `salary`=OLD.salary,
  `blood_group`=OLD.blood_group,
  `allow_all_accounts`=OLD.allow_all_accounts,
  `selected_accounts`=OLD.selected_accounts,
  `files`=OLD.files,
  `default_user_photo`=OLD.default_user_photo,
  `status`=OLD.status,
  `is_login`=OLD.is_login,
  `socket_id`=OLD.socket_id,
  `otp_value`=OLD.otp_value,
  `otp_on_user`=OLD.otp_on_user,
  `designation`=OLD.designation,
  `aadhaar_no`=OLD.aadhaar_no,
  `pan_no`=OLD.pan_no,
  `licence_no`=OLD.licence_no,
  `voter_id_no`=OLD.voter_id_no,
  `esi_no`=OLD.esi_no,
  `pf_no`=OLD.pf_no,
  `date_of_birth`=OLD.date_of_birth,
  `order_display_only_assigned_account`=OLD.order_display_only_assigned_account,
  `bank_name`=OLD.bank_name,
  `bank_branch`=OLD.bank_branch,
  `bank_acc_name`=OLD.bank_acc_name,
  `bank_acc_no`=OLD.bank_acc_no,
  `bank_ifsc`=OLD.bank_ifsc,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_master_insert_after_trigger` AFTER INSERT ON `user_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_master_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `user_id`=NEW.user_id,
  `user_name`=NEW.user_name,
  `login_username`=NEW.login_username,
  `user_mobile`=NEW.user_mobile,
  `user_type`=NEW.user_type,
  `is_cad_designer`=NEW.is_cad_designer,
  `default_department_id`=NEW.default_department_id,
  `user_password`=NEW.user_password,
  `salary`=NEW.salary,
  `blood_group`=NEW.blood_group,
  `allow_all_accounts`=NEW.allow_all_accounts,
  `selected_accounts`=NEW.selected_accounts,
  `files`=NEW.files,
  `default_user_photo`=NEW.default_user_photo,
  `status`=NEW.status,
  `is_login`=NEW.is_login,
  `socket_id`=NEW.socket_id,
  `otp_value`=NEW.otp_value,
  `otp_on_user`=NEW.otp_on_user,
  `designation`=NEW.designation,
  `aadhaar_no`=NEW.aadhaar_no,
  `pan_no`=NEW.pan_no,
  `licence_no`=NEW.licence_no,
  `voter_id_no`=NEW.voter_id_no,
  `esi_no`=NEW.esi_no,
  `pf_no`=NEW.pf_no,
  `date_of_birth`=NEW.date_of_birth,
  `order_display_only_assigned_account`=NEW.order_display_only_assigned_account,
  `bank_name`=NEW.bank_name,
  `bank_branch`=NEW.bank_branch,
  `bank_acc_name`=NEW.bank_acc_name,
  `bank_acc_no`=NEW.bank_acc_no,
  `bank_ifsc`=NEW.bank_ifsc,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_master_update_after_trigger` AFTER UPDATE ON `user_master`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_master_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `user_id`=NEW.user_id,
  `user_name`=NEW.user_name,
  `login_username`=NEW.login_username,
  `user_mobile`=NEW.user_mobile,
  `user_type`=NEW.user_type,
  `is_cad_designer`=NEW.is_cad_designer,
  `default_department_id`=NEW.default_department_id,
  `user_password`=NEW.user_password,
  `salary`=NEW.salary,
  `blood_group`=NEW.blood_group,
  `allow_all_accounts`=NEW.allow_all_accounts,
  `selected_accounts`=NEW.selected_accounts,
  `files`=NEW.files,
  `default_user_photo`=NEW.default_user_photo,
  `status`=NEW.status,
  `is_login`=NEW.is_login,
  `socket_id`=NEW.socket_id,
  `otp_value`=NEW.otp_value,
  `otp_on_user`=NEW.otp_on_user,
  `designation`=NEW.designation,
  `aadhaar_no`=NEW.aadhaar_no,
  `pan_no`=NEW.pan_no,
  `licence_no`=NEW.licence_no,
  `voter_id_no`=NEW.voter_id_no,
  `esi_no`=NEW.esi_no,
  `pf_no`=NEW.pf_no,
  `date_of_birth`=NEW.date_of_birth,
  `order_display_only_assigned_account`=NEW.order_display_only_assigned_account,
  `bank_name`=NEW.bank_name,
  `bank_branch`=NEW.bank_branch,
  `bank_acc_name`=NEW.bank_acc_name,
  `bank_acc_no`=NEW.bank_acc_no,
  `bank_ifsc`=NEW.bank_ifsc,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_order_department_delete_after_trigger` AFTER DELETE ON `user_order_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_order_department_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `ud_id`=OLD.ud_id,
  `user_id`=OLD.user_id,
  `department_id`=OLD.department_id,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_order_department_insert_after_trigger` AFTER INSERT ON `user_order_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_order_department_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_order_department_update_after_trigger` AFTER UPDATE ON `user_order_department`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_order_department_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `ud_id`=NEW.ud_id,
  `user_id`=NEW.user_id,
  `department_id`=NEW.department_id,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_roles_delete_after_trigger` AFTER DELETE ON `user_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_roles_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `user_role_id`=OLD.user_role_id,
  `user_id`=OLD.user_id,
  `website_module_id`=OLD.website_module_id,
  `role_type_id`=OLD.role_type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_roles_insert_after_trigger` AFTER INSERT ON `user_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_roles_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `user_role_id`=NEW.user_role_id,
  `user_id`=NEW.user_id,
  `website_module_id`=NEW.website_module_id,
  `role_type_id`=NEW.role_type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_roles_update_after_trigger` AFTER UPDATE ON `user_roles`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_roles_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `user_role_id`=NEW.user_role_id,
  `user_id`=NEW.user_id,
  `website_module_id`=NEW.website_module_id,
  `role_type_id`=NEW.role_type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_type_delete_after_trigger` AFTER DELETE ON `user_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_type_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `user_type_id`=OLD.user_type_id,
  `user_type`=OLD.user_type
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_type_insert_after_trigger` AFTER INSERT ON `user_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_type_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `user_type_id`=NEW.user_type_id,
  `user_type`=NEW.user_type
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_type_update_after_trigger` AFTER UPDATE ON `user_type`
 FOR EACH ROW INSERT INTO 
  gurulog.`user_type_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `user_type_id`=NEW.user_type_id,
  `user_type`=NEW.user_type
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `website_modules_delete_after_trigger` AFTER DELETE ON `website_modules`
 FOR EACH ROW INSERT INTO 
  gurulog.`website_modules_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `website_module_id`=OLD.website_module_id,
  `title`=OLD.title,
  `main_module`=OLD.main_module
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `website_modules_insert_after_trigger` AFTER INSERT ON `website_modules`
 FOR EACH ROW INSERT INTO 
  gurulog.`website_modules_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `website_module_id`=NEW.website_module_id,
  `title`=NEW.title,
  `main_module`=NEW.main_module
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `website_modules_update_after_trigger` AFTER UPDATE ON `website_modules`
 FOR EACH ROW INSERT INTO 
  gurulog.`website_modules_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `website_module_id`=NEW.website_module_id,
  `title`=NEW.title,
  `main_module`=NEW.main_module
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_entry_delete_after_trigger` AFTER DELETE ON `worker_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_entry_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `worker_entry_id`=OLD.worker_entry_id,
  `person_name`=OLD.person_name,
  `process_id`=OLD.process_id,
  `salary`=OLD.salary,
  `worker_type_id`=OLD.worker_type_id,
  `files`=OLD.files,
  `created_at`=OLD.created_at,
  `created_by`=OLD.created_by,
  `updated_at`=OLD.updated_at,
  `updated_by`=OLD.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_entry_update_after_trigger` AFTER UPDATE ON `worker_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_entry_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `worker_entry_id`=NEW.worker_entry_id,
  `person_name`=NEW.person_name,
  `process_id`=NEW.process_id,
  `salary`=NEW.salary,
  `worker_type_id`=NEW.worker_type_id,
  `files`=NEW.files,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_delete_after_trigger` AFTER DELETE ON `worker_hisab`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `worker_hisab_id`=OLD.worker_hisab_id,
  `department_id`=OLD.department_id,
  `worker_id`=OLD.worker_id,
  `against_account_id`=OLD.against_account_id,
  `is_module`=OLD.is_module,
  `hisab_date`=OLD.hisab_date,
  `fine`=OLD.fine,
  `total_fine_adjusted`=OLD.total_fine_adjusted,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_entry_insert_after_trigger` AFTER INSERT ON `worker_entry`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_entry_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `worker_entry_id`=NEW.worker_entry_id,
  `person_name`=NEW.person_name,
  `process_id`=NEW.process_id,
  `salary`=NEW.salary,
  `worker_type_id`=NEW.worker_type_id,
  `files`=NEW.files,
  `created_at`=NEW.created_at,
  `created_by`=NEW.created_by,
  `updated_at`=NEW.updated_at,
  `updated_by`=NEW.updated_by
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_detail_delete_after_trigger` AFTER DELETE ON `worker_hisab_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_detail_log` 
SET 
  trigger_status = 'DELETE',
  `trigger_run_at`=NOW(),
  `wsd_id`=OLD.wsd_id,
  `worker_hisab_id`=OLD.worker_hisab_id,
  `worker_id`=OLD.worker_id,
  `against_account_id`=OLD.against_account_id,
  `relation_id`=OLD.relation_id,
  `is_module`=OLD.is_module,
  `balance_fine`=OLD.balance_fine,
  `fine_adjusted`=OLD.fine_adjusted,
  `type_id`=OLD.type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_detail_insert_after_trigger` AFTER INSERT ON `worker_hisab_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_detail_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `wsd_id`=NEW.wsd_id,
  `worker_hisab_id`=NEW.worker_hisab_id,
  `worker_id`=NEW.worker_id,
  `against_account_id`=NEW.against_account_id,
  `relation_id`=NEW.relation_id,
  `is_module`=NEW.is_module,
  `balance_fine`=NEW.balance_fine,
  `fine_adjusted`=NEW.fine_adjusted,
  `type_id`=NEW.type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_detail_update_after_trigger` AFTER UPDATE ON `worker_hisab_detail`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_detail_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `wsd_id`=NEW.wsd_id,
  `worker_hisab_id`=NEW.worker_hisab_id,
  `worker_id`=NEW.worker_id,
  `against_account_id`=NEW.against_account_id,
  `relation_id`=NEW.relation_id,
  `is_module`=NEW.is_module,
  `balance_fine`=NEW.balance_fine,
  `fine_adjusted`=NEW.fine_adjusted,
  `type_id`=NEW.type_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_insert_after_trigger` AFTER INSERT ON `worker_hisab`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_log` 
SET 
  trigger_status = 'INSERT',
  `trigger_run_at`=NOW(),
  `worker_hisab_id`=NEW.worker_hisab_id,
  `department_id`=NEW.department_id,
  `worker_id`=NEW.worker_id,
  `against_account_id`=NEW.against_account_id,
  `is_module`=NEW.is_module,
  `hisab_date`=NEW.hisab_date,
  `fine`=NEW.fine,
  `total_fine_adjusted`=NEW.total_fine_adjusted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `worker_hisab_update_after_trigger` AFTER UPDATE ON `worker_hisab`
 FOR EACH ROW INSERT INTO 
  gurulog.`worker_hisab_log` 
SET 
  trigger_status = 'UPDATE',
  `trigger_run_at`=NOW(),
  `worker_hisab_id`=NEW.worker_hisab_id,
  `department_id`=NEW.department_id,
  `worker_id`=NEW.worker_id,
  `against_account_id`=NEW.against_account_id,
  `is_module`=NEW.is_module,
  `hisab_date`=NEW.hisab_date,
  `fine`=NEW.fine,
  `total_fine_adjusted`=NEW.total_fine_adjusted,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at
$$
DELIMITER ;
