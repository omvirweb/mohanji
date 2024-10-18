
-- Ishite : 2018_12_31 10:12 AM

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_order_status_id`(IN `order_id_back` INT(11))
    NO SQL
BEGIN
DECLARE realmID INT DEFAULT 0;
SET realmID = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id`!= 3);

IF (realmID = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='3'
       WHERE `n`.`order_id`=order_id_back;
  END IF; 
  SELECT order_status_id FROM new_order;
END$$
DELIMITER ;

-- Ishita : 2019_01_16 03:30 PM
DROP PROCEDURE `update_order_status_id`; 
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_order_status_id`(IN `order_id_back` INT(11))
    NO SQL
BEGIN
DECLARE realmID INT DEFAULT 0;
DECLARE completed INT DEFAULT 0;
SET realmID = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id`NOT IN (2,3));

SET completed = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id`NOT IN (2));

IF (completed = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='2'
       WHERE `n`.`order_id`=order_id_back;
ELSEIF (realmID = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='3'
       WHERE `n`.`order_id`=order_id_back;
  END IF;
  SELECT order_status_id FROM new_order;
END$$
DELIMITER ;

-- Ishita : 2019_01_16 04:00 PM
DROP PROCEDURE IF EXISTS `update_order_status_id`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_order_status_id`(IN `order_id_back` INT(11))
    NO SQL
BEGIN
DECLARE realmID INT DEFAULT 0;
DECLARE completed INT DEFAULT 0;
DECLARE pending INT DEFAULT 0;
SET realmID = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id`NOT IN (2,3));

SET completed = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id`NOT IN (2));

SET pending = (SELECT COUNT(item_status_id) FROM order_lot_item WHERE order_id=order_id_back AND `item_status_id` = 1);
               
IF (pending > 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='1'
       WHERE `n`.`order_id`=order_id_back;
ELSEIF (completed = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='2'
       WHERE `n`.`order_id`=order_id_back;
ELSEIF (realmID = 0) THEN
 UPDATE new_order n
       SET 
           `n`.`order_status_id`='3'
       WHERE `n`.`order_id`=order_id_back;
  END IF;
  SELECT order_status_id FROM new_order;
END$$
DELIMITER ;
