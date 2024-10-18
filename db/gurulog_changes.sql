-- Chirag : 2020_03_13 02:20 PM
ALTER TABLE `item_stock_log` ADD `rfid_created_grwt` DOUBLE NOT NULL DEFAULT '0' AFTER `stock_type`;
ALTER TABLE `sell_items_log` ADD `rfid_number` VARCHAR(255) NULL DEFAULT NULL AFTER `fine`, ADD `charges_amt` DOUBLE NOT NULL DEFAULT '0' AFTER `rfid_number`;
ALTER TABLE `item_stock_rfid_log` ADD `rfid_used` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Not Used, 1 = Used' AFTER `rfid_ad_id`;
ALTER TABLE `sell_items_log` ADD `item_stock_rfid_id` INT(11) NULL DEFAULT NULL AFTER `fine`;
ALTER TABLE `item_stock_rfid_log` ADD `from_relation_id` INT(11) NULL DEFAULT NULL AFTER `rfid_used`, ADD `from_module` INT(11) NULL DEFAULT NULL COMMENT '1 = RFID Create, 2 = Sell, 3 = Stock Transfer' AFTER `from_relation_id`, ADD `to_relation_id` INT(11) NULL DEFAULT NULL AFTER `from_module`, ADD `to_module` INT(11) NULL DEFAULT NULL COMMENT '1 = RFID Create, 2 = Sell, 3 = Stock Transfer' AFTER `to_relation_id`;
ALTER TABLE `stock_transfer_detail_log` ADD `item_stock_rfid_id` INT(11) NULL DEFAULT NULL AFTER `fine`, ADD `rfid_number` VARCHAR(255) NULL DEFAULT NULL AFTER `item_stock_rfid_id`;
ALTER TABLE `stock_transfer_detail_log` CHANGE `item_stock_rfid_id` `from_item_stock_rfid_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `stock_transfer_detail_log` ADD `to_item_stock_rfid_id` INT(11) NULL DEFAULT NULL AFTER `from_item_stock_rfid_id`;
ALTER TABLE `item_stock_log` CHANGE `stock_type` `stock_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 = Purchase, 2 = Exchange, 3 = Stock Transfer, 4 = Receive , 5 = MHM Receive Finish, 6 = MHM Receive Scrap, 7 = MC Receive Finish, 8 = MC Receive Scrap, 9 = Casting Entry Receive Finish, 10 = Casting Entry Receive Scrap, 11 = Opening Stock';
ALTER TABLE `item_stock_rfid_log` ADD `rfid_size` VARCHAR(255) NULL DEFAULT NULL AFTER `real_rfid`;
