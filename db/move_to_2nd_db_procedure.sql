-- Avinash : 2019_12_27 11:00 AM

DELIMITER $$
CREATE PROCEDURE insert_triggers()
BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE sql_id INTEGER(11);
    DECLARE sql_query TEXT DEFAULT "";
 
    -- declare cursor for sqlQueryData
    DEClARE sqlQueryData 
        CURSOR FOR 
            SELECT `id`, `trigger_sql` FROM hrdksql.`insert_trigger`;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
 
    OPEN sqlQueryData;
 
    getSqlQuery: LOOP
        FETCH sqlQueryData INTO sql_id, sql_query;
        IF finished = 1 THEN 
            LEAVE getSqlQuery;
        END IF;
        -- build sqlQueryList
        IF sql_query != NULL THEN 
            SET @sql = sql_query;
            PREPARE stmt FROM @sql;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
            DELETE FROM hrdksql.`insert_trigger` WHERE `id` = sql_id;
        ELSE
            DELETE FROM hrdksql.`insert_trigger` WHERE `id` = sql_id;
        END IF;
        
    END LOOP getSqlQuery;
    CLOSE sqlQueryData;
 
END$$
DELIMITER ;

-- this query to call above procedure
-- CALL insert_triggers();