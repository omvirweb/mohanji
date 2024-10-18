-- Chirag : 2020_03_13 02:20 PM
DROP TRIGGER IF EXISTS `item_stock_delete_after_trigger`;
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
  `rfid_created_grwt`=OLD.rfid_created_grwt,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at;

DROP TRIGGER IF EXISTS `item_stock_insert_after_trigger`;
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
  `rfid_created_grwt`=NEW.rfid_created_grwt,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `item_stock_update_after_trigger`;
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
  `rfid_created_grwt`=NEW.rfid_created_grwt,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `sell_items_delete_after_trigger`;
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
  `item_stock_rfid_id`=OLD.item_stock_rfid_id,
  `rfid_number`=OLD.rfid_number,
  `charges_amt`=OLD.charges_amt,
  `image`=OLD.image,
  `order_lot_item_id`=OLD.order_lot_item_id,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `wastage_change_approve`=OLD.wastage_change_approve,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at;

DROP TRIGGER IF EXISTS `sell_items_insert_after_trigger`;
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
  `item_stock_rfid_id`=NEW.item_stock_rfid_id,
  `rfid_number`=NEW.rfid_number,
  `charges_amt`=NEW.charges_amt,
  `image`=NEW.image,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `wastage_change_approve`=NEW.wastage_change_approve,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `sell_items_update_after_trigger`;
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
  `item_stock_rfid_id`=NEW.item_stock_rfid_id,
  `rfid_number`=NEW.rfid_number,
  `charges_amt`=NEW.charges_amt,
  `image`=NEW.image,
  `order_lot_item_id`=NEW.order_lot_item_id,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `wastage_change_approve`=NEW.wastage_change_approve,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `item_stock_rfid_delete_after_trigger`;
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
  `rfid_size`=OLD.rfid_size,
  `rfid_charges`=OLD.rfid_charges,
  `rfid_ad_id`=OLD.rfid_ad_id,
  `rfid_used`=OLD.rfid_used,
  `from_relation_id`=OLD.from_relation_id,
  `from_module`=OLD.from_module,
  `to_relation_id`=OLD.to_relation_id,
  `to_module`=OLD.to_module,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at;

DROP TRIGGER IF EXISTS `item_stock_rfid_insert_after_trigger`;
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
  `rfid_size`=NEW.rfid_size,
  `rfid_charges`=NEW.rfid_charges,
  `rfid_ad_id`=NEW.rfid_ad_id,
  `rfid_used`=NEW.rfid_used,
  `from_relation_id`=NEW.from_relation_id,
  `from_module`=NEW.from_module,
  `to_relation_id`=NEW.to_relation_id,
  `to_module`=NEW.to_module,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `item_stock_rfid_update_after_trigger`;
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
  `rfid_size`=NEW.rfid_size,
  `rfid_charges`=NEW.rfid_charges,
  `rfid_ad_id`=NEW.rfid_ad_id,
  `rfid_used`=NEW.rfid_used,
  `from_relation_id`=NEW.from_relation_id,
  `from_module`=NEW.from_module,
  `to_relation_id`=NEW.to_relation_id,
  `to_module`=NEW.to_module,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `stock_transfer_detail_delete_after_trigger`;
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
  `from_item_stock_rfid_id`=OLD.from_item_stock_rfid_id,
  `to_item_stock_rfid_id`=OLD.to_item_stock_rfid_id,
  `rfid_number`=OLD.rfid_number,
  `purchase_sell_item_id`=OLD.purchase_sell_item_id,
  `stock_type`=OLD.stock_type,
  `created_by`=OLD.created_by,
  `created_at`=OLD.created_at,
  `updated_by`=OLD.updated_by,
  `updated_at`=OLD.updated_at;

DROP TRIGGER IF EXISTS `stock_transfer_detail_insert_after_trigger`;
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
  `from_item_stock_rfid_id`=NEW.from_item_stock_rfid_id,
  `to_item_stock_rfid_id`=NEW.to_item_stock_rfid_id,
  `rfid_number`=NEW.rfid_number,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;

DROP TRIGGER IF EXISTS `stock_transfer_detail_update_after_trigger`;
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
  `from_item_stock_rfid_id`=NEW.from_item_stock_rfid_id,
  `to_item_stock_rfid_id`=NEW.to_item_stock_rfid_id,
  `rfid_number`=NEW.rfid_number,
  `purchase_sell_item_id`=NEW.purchase_sell_item_id,
  `stock_type`=NEW.stock_type,
  `created_by`=NEW.created_by,
  `created_at`=NEW.created_at,
  `updated_by`=NEW.updated_by,
  `updated_at`=NEW.updated_at;
