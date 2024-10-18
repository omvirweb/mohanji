-- Account Group
CREATE TRIGGER `mtsdb_account_group_sql_insert_trigger` AFTER INSERT ON guru.`account_group` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'INSERT',
    `trigger_sql`=CONCAT("INSERT INTO hrdkfull.`account_group` SET `account_group_id`='", NEW.account_group_id, "', `parent_group_id`='", IFNULL(NEW.parent_group_id, ''), "', `account_group_name`='", IFNULL(NEW.account_group_name, ''), "', `sequence`='", IFNULL(NEW.sequence, ''), "', `is_display_in_balance_sheet`='", IFNULL(NEW.is_display_in_balance_sheet, ''), "', `use_in_profit_loss`='", IFNULL(NEW.use_in_profit_loss, ''), "', `move_data_opening_zero`='", IFNULL(NEW.move_data_opening_zero, ''), "', `is_deletable`='", IFNULL(NEW.is_deletable, ''), "', `is_deleted`='", IFNULL(NEW.is_deleted, ''), "', `created_by`='", IFNULL(NEW.created_by, ''), "', `created_at`='", IFNULL(NEW.created_at, ''), "', `updated_by`='", IFNULL(NEW.updated_by, ''), "', `updated_at`='", IFNULL(NEW.updated_at, ''),"';");
CREATE TRIGGER `mtsdb_account_group_sql_update_trigger` AFTER UPDATE ON guru.`account_group` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'UPDATE',
    `trigger_sql`=CONCAT(
        "UPDATE hrdkfull.`account_group` SET 
        `parent_group_id`='", IFNULL(NEW.parent_group_id, ''), "', 
        `account_group_name`='", IFNULL(NEW.account_group_name, ''), "', 
        `sequence`='", IFNULL(NEW.sequence, ''), "', 
        `is_display_in_balance_sheet`='", IFNULL(NEW.is_display_in_balance_sheet, ''), "', 
        `use_in_profit_loss`='", IFNULL(NEW.use_in_profit_loss, ''), "', 
        `move_data_opening_zero`='", IFNULL(NEW.move_data_opening_zero, ''), "', 
        `is_deletable`='", IFNULL(NEW.is_deletable, ''), "', 
        `is_deleted`='", IFNULL(NEW.is_deleted, ''), "', 
        `created_by`='", IFNULL(NEW.created_by, ''), "', 
        `created_at`='", IFNULL(NEW.created_at, ''), "', 
        `updated_by`='", IFNULL(NEW.updated_by, ''), "', 
        `updated_at`='", IFNULL(NEW.updated_at, ''),"'
        WHERE `account_group_id`='", NEW.account_group_id, "';"
    );
CREATE TRIGGER `mtsdb_account_group_sql_delete_trigger` AFTER DELETE ON guru.`account_group` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'DELETE',
  `trigger_sql`= CONCAT("DELETE FROM hrdkfull.`account_group` WHERE `account_group_id`='", OLD.account_group_id, "';");

-- Account
CREATE TRIGGER `mtsdb_account_sql_insert_trigger` AFTER INSERT ON guru.`account` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'INSERT',
    `trigger_sql`=CONCAT(
        "INSERT INTO hrdkfull.`account` SET 
        `account_id`='", NEW.account_id, "', 
        `account_name`='", NEW.account_name, "', 
        `account_phone`='", NEW.account_phone, "', 
        `account_mobile`='", NEW.account_mobile, "', 
        `account_email_ids`='", NEW.account_email_ids, "', 
        `account_address`='", NEW.account_address, "', 
        `account_state`='", NEW.account_state, "', 
        `account_city`='", NEW.account_city, "', 
        `account_postal_code`='", NEW.account_postal_code, "', 
        `account_gst_no`='", NEW.account_gst_no, "', 
        `account_pan`='", NEW.account_pan, "', 
        `account_aadhaar`='", NEW.account_aadhaar, "', 
        `account_contect_person_name`='", NEW.account_contect_person_name, "', 
        `account_group_id`='", NEW.account_group_id, "', 
        `opening_balance`='", NEW.opening_balance, "', 
        `interest`='", NEW.interest, "', 
        `credit_debit`='", NEW.credit_debit, "', 
        `opening_balance_in_gold`='", NEW.opening_balance_in_gold, "', 
        `gold_ob_credit_debit`='", NEW.gold_ob_credit_debit, "', 
        `opening_balance_in_silver`='", NEW.opening_balance_in_silver, "', 
        `silver_ob_credit_debit`='", NEW.silver_ob_credit_debit, "', 
        `opening_balance_in_rupees`='", NEW.opening_balance_in_rupees, "', 
        `rupees_ob_credit_debit`='", NEW.rupees_ob_credit_debit, "', 
        `bank_name`='", NEW.bank_name, "', 
        `bank_account_no`='", NEW.bank_account_no, "', 
        `ifsc_code`='", NEW.ifsc_code, "', 
        `bank_interest`='", NEW.bank_interest, "', 
        `gold_fine`='", NEW.gold_fine, "', 
        `silver_fine`='", NEW.silver_fine, "', 
        `amount`='", NEW.amount, "', 
        `credit_limit`='", NEW.credit_limit, "', 
        `balance_date`='", NEW.balance_date, "', 
        `status`='", NEW.status, "', 
        `user_id`='", NEW.user_id, "', 
        `user_name`='", NEW.user_name, "', 
        `is_supplier`='", NEW.is_supplier, "', 
        `password`='", NEW.password, "', 
        `min_price`='", NEW.min_price, "', 
        `price_per_pcs`='", NEW.price_per_pcs, "', 
        `created_by`='", NEW.created_by, "', 
        `created_at`='", NEW.created_at, "', 
        `updated_by`='", NEW.updated_by, "', 
        `updated_at`='", NEW.updated_at,"';"
    );
CREATE TRIGGER `mtsdb_account_sql_update_trigger` AFTER UPDATE ON guru.`account` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'UPDATE',
    `trigger_sql`=CONCAT(
        "UPDATE hrdkfull.`account` SET 
        `account_name`='", NEW.account_name, "', 
        `account_phone`='", NEW.account_phone, "', 
        `account_mobile`='", NEW.account_mobile, "', 
        `account_email_ids`='", NEW.account_email_ids, "', 
        `account_address`='", NEW.account_address, "', 
        `account_state`='", NEW.account_state, "', 
        `account_city`='", NEW.account_city, "', 
        `account_postal_code`='", NEW.account_postal_code, "', 
        `account_gst_no`='", NEW.account_gst_no, "', 
        `account_pan`='", NEW.account_pan, "', 
        `account_aadhaar`='", NEW.account_aadhaar, "', 
        `account_contect_person_name`='", NEW.account_contect_person_name, "', 
        `account_group_id`='", NEW.account_group_id, "', 
        `opening_balance`='", NEW.opening_balance, "', 
        `interest`='", NEW.interest, "', 
        `credit_debit`='", NEW.credit_debit, "', 
        `opening_balance_in_gold`='", NEW.opening_balance_in_gold, "', 
        `gold_ob_credit_debit`='", NEW.gold_ob_credit_debit, "', 
        `opening_balance_in_silver`='", NEW.opening_balance_in_silver, "', 
        `silver_ob_credit_debit`='", NEW.silver_ob_credit_debit, "', 
        `opening_balance_in_rupees`='", NEW.opening_balance_in_rupees, "', 
        `rupees_ob_credit_debit`='", NEW.rupees_ob_credit_debit, "', 
        `bank_name`='", NEW.bank_name, "', 
        `bank_account_no`='", NEW.bank_account_no, "', 
        `ifsc_code`='", NEW.ifsc_code, "', 
        `bank_interest`='", NEW.bank_interest, "', 
        `gold_fine`='", NEW.gold_fine, "', 
        `silver_fine`='", NEW.silver_fine, "', 
        `amount`='", NEW.amount, "', 
        `credit_limit`='", NEW.credit_limit, "', 
        `balance_date`='", NEW.balance_date, "', 
        `status`='", NEW.status, "', 
        `user_id`='", NEW.user_id, "', 
        `user_name`='", NEW.user_name, "', 
        `is_supplier`='", NEW.is_supplier, "', 
        `password`='", NEW.password, "', 
        `min_price`='", NEW.min_price, "', 
        `price_per_pcs`='", NEW.price_per_pcs, "', 
        `created_by`='", NEW.created_by, "', 
        `created_at`='", NEW.created_at, "', 
        `updated_by`='", NEW.updated_by, "', 
        `updated_at`='", NEW.updated_at,"'
        WHERE `account_id`='", NEW.account_id, "';"
    );
CREATE TRIGGER `mtsdb_account_sql_delete_trigger` AFTER DELETE ON guru.`account` FOR EACH ROW INSERT INTO hrdksql.`insert_trigger` 
SET trigger_status = 'DELETE',
  `trigger_sql`= CONCAT("DELETE FROM hrdkfull.`account` WHERE `account_id`='", OLD.account_id, "';");