-- Avinash : 2019_12_27 11:00 AM

CREATE TRIGGER `add_insert_trigger_sql` AFTER INSERT ON testlimited.`users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'INSERT',
  `trigger_sql`=CONCAT("INSERT INTO testfull.`users` SET `id`='", NEW.id, "', `name`='", NEW.name, "', `age`='", NEW.age, "', `email`='", NEW.email,"';");

CREATE TRIGGER `add_update_trigger_sql` AFTER UPDATE ON testlimited.`users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'UPDATE',
  `trigger_sql`= CONCAT("UPDATE testfull.`users` SET `name`='", NEW.name, "', `age`='", NEW.age, "', `email`='", NEW.email,"' WHERE `id`='", NEW.id, "';");

CREATE TRIGGER `add_delete_trigger_sql` AFTER DELETE ON testlimited.`users` FOR EACH ROW INSERT INTO testsql.`insert_trigger` 
SET trigger_status = 'DELETE',
  `trigger_sql`= CONCAT("DELETE FROM testfull.`users` WHERE `id`='", OLD.id, "';");